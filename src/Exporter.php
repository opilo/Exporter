<?php namespace Opilo\Exporter;

use Illuminate\Config\Repository as Config;
use Illuminate\Database\Eloquent\Builder;
use Schema;
use Opilo\Exporter\Publishers\PublisherFactory;
use PHPExcel;

/**
 * Class Exporter
 *
 * @package Opilo\Exporter
 */
class Exporter implements ExporterInterface {

	/**
     * @var string
     */
    protected $fileName;

    /**
     * @var PHPExcel
     */
    protected $processor;

    /**
     * @var string
     */
    protected $fileType;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var \Opilo\Exporter\Publishers\BasePublisher
     */
    protected $publisher;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var string
     */
    protected $table;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param PHPExcel                          $processor
     * @param \Illuminate\Config\Repository     $config
     * @param string                            $fileType
     */
    public function __construct(
        PHPExcel $processor,
        Config $config,
        $fileType
    ) {
        $this->processor = $processor;
        $this->fileType = $fileType;

        $this->bootExporter();
        $this->config = $config;
    }

    /**
     * Export selected or all contacts to a file
     *
     * @param   Builder $query
     * @param   array   $headers
     * @return  mixed
     */
    public function export(Builder $query, $headers = [])
    {
        $this->table = $query->getModel()->getTable();
        $this->prepareExport($headers);

        $query->chunk($this->size, [$this->publisher, 'append']);
    }

    protected function bootExporter()
    {
        $this->fileName = md5(uniqid(rand(), true));
        $this->size = $this->config->get('opilo/exporter::chunk_size');
    }

    private function prepareExport($headers)
    {
        $this->setHeaders($headers);
        $this->ceratePublisher();
        $this->createFile();
    }

    protected function setHeaders($headers)
    {
        if (empty($headers)) {
            $headers = Schema::getColumnListing($this->table);
            $headers = array_diff($headers, ['deleted_at', 'created_at', 'updated_at']);
        }

        $this->headers = $headers;
    }

    protected function ceratePublisher()
    {
        $this->publisher = PublisherFactory::make(
            $this->processor,
            $this->fileType,
            $this->config,
            $this->headers
        );
    }

    protected function createFile()
    {
        $activeSheet = $this->processor->getActiveSheet();

        for ($i = 0, $col = 'A'; $i < count($this->headers); $i++, ++$col) {
            $activeSheet->setCellValue($col . '1', $this->headers[$i]);
        }

        $this->publisher->publish($this->fileName);

        unset($this->processor);
    }

}
