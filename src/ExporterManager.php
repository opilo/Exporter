<?php namespace Opilo\Exporter; 

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Config\Repository as Config;

/**
 * Class ExporterManager
 * @package Opilo\Exporter
 */
class ExporterManager implements ExporterInterface {

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var FileWriter
	 */
	protected $file;

	/**
	 * @var CsvTool
	 */
	protected $csvizer;

	/**
	 * @var array
	 */
	protected $headers = [];

    /**
     * @var array
     */
    protected $buffer = [];

    /**
     * @var array
     */
    protected $lineBuffer = [];

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var int
     */
    protected $chunk;

	/**
     * @var array
     */
    protected $relationHeaders = [];

    /**
     * @var array
     */
    protected $relationHeadersField = [];

    /**
     * @var string
     */
    protected $relationGlue;

	/**
     * @param Config $config
     * @param FileWriter $file
     * @param CsvTool $csvizer
     */
    public function __construct(
		Config $config,
		FileWriter $file,
		CsvTool $csvizer
	) {
		$this->config = $config;
		$this->file = $file;
		$this->csvizer = $csvizer;

		$this->bootExporter();
	}

    /**
     * Boot eloquent-exporter
     */
    protected function bootExporter()
	{
		$this->generateFileName();
		$this->chunk = $this->config->get('eloquent-exporter::chunk_size');
	}

	/**
	 * Export selected or all contacts to a file
	 *
     * @param   Builder $query
     * @param   array   $headers
     * @param   array   $relationHeader
     * @return  mixed
     */
    public function export(Builder $query, $headers = [], $relationHeader = [])
	{
		$table = $query->getModel()->getTable();
		$this->prepareExport($headers, $relationHeader, $table);

        $query->chunk($this->chunk, [$this, 'exportChunk']);

        return $this->fileName;
	}

    /**
     * @param $headers
     * @param $relation
     * @param $table
     */
    protected function prepareExport($headers, $relation, $table)
	{
        $this->setRelationHeaders($relation);
        $this->setHeaders($headers, $table);
		$this->createFile();
	}

    /**
     * @param $headers
     * @param $table
     */
    protected function setHeaders($headers, $table)
	{
		if (empty($headers)) {
			$headers = Schema::getColumnListing($table);
			$headers = array_diff($headers, ['deleted_at', 'created_at', 'updated_at']);
		}

		$this->headers = $headers;
	}

    /**
     * Create file with headers
     */
    protected function createFile()
	{
		$headerLine = $this->csvizer->encode($this->getHeaders());

		$this->file->load($this->fileName)->writeLine($headerLine);
	}

    /**
     * Generate a unique file name
     */
    protected function generateFileName()
	{
		$this->fileName = $this->config->get('eloquent-exporter::store_path') .
                          md5(uniqid(rand(), true)) .
						  $this->config->get('eloquent-exporter::file_extension');
	}

    /**
     * @param $relations
     */
    protected function setRelationHeaders($relations)
    {
        foreach ($relations as $name => $data) {
            if (!isset($data['column'])) {
                // throw exception
            }

            $this->relationHeadersField = array_merge(
                $this->relationHeadersField,
                [$name => $data['column']]
            );
        }

        foreach ($relations as $name => $data) {
            $this->relationHeaders[] = (isset($data['alias'])) ?: $name;
        }
    }

	/**
     * @param $rows
     */
    public function exportChunk($rows)
    {
        foreach ($rows as $row) {
            foreach ($row->toArray() as $field => $value) {
                if (!in_array($field, $this->headers) && !in_array($field, $this->relationHeaders)) {
                    continue;
                }

                if (is_array($value)) {
                    $value = $this->standardizeRelationValues($field, $value);
                }

                $this->buffer($field, $value);
            }

            $this->generateLine();
        }

        $this->file->writeArray($this->lineBuffer);
        $this->resetBuffers();
    }

	/**
     * @param  $header
     * @param  $values
     * @return string
     */
    protected function standardizeRelationValues($header, $values)
    {
        $tmp = [];

        foreach ($values as $value) {
            $tmp[] = $value[$this->relationHeadersField[$header]];
        }

        return implode($this->getGlue(), $tmp);
    }

	/**
     * @return mixed
     */
    protected function getGlue()
    {
        if (empty($this->relationGlue)) {
            $this->relationGlue = $this->config->get('eloquent-exporter::relation_glue');
        }

        return $this->relationGlue;
    }

	/**
     * @param $key
     * @param $value
     */
    protected function buffer($key, $value)
    {
        $this->buffer = array_merge($this->buffer, [$key => $value]);
    }

	/**
     * Generate csv line for each record
     */
    protected function generateLine()
    {
        $tmp = [];

        foreach ($this->headers as $header) {
            $tmp[] = $this->buffer[$header];
        }

        $this->lineBuffer[] = $this->csvizer->encode($tmp);
    }

	/**
     * Reset buffers
     */
    protected function resetBuffers()
    {
        $this->buffer = [];
        $this->lineBuffer = [];
    }

    protected function getHeaders()
    {
        return array_merge($this->headers, $this->relationHeaders);
    }

}
