<?php namespace Opilo\Exporter\Publishers;

use PHPExcel_Writer_IWriter as PhpExcelWriter;
use PHPExcel_Reader_IReader as PhpExcelReader;
use Illuminate\Config\Repository as Config;

abstract class BasePublisher {

    protected $writer;

    protected $reader;

    protected $fields;

    /**
     * @var \PHPExcel
     */
    protected $processor;

    protected $lastEmptyRow;

    protected $tmpFile;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(
        PhpExcelWriter $writer,
        PhpExcelReader $reader,
        Config $config,
        $fields
    ) {
        $this->writer = $writer;
        $this->reader = $reader;
        $this->fields = $fields;
        $this->config = $config;
    }

    abstract public function publish($filename);

    abstract protected function getExtension();

    public function append($results)
    {
        $this->prepareAppend($this->tmpFile);

        $activeSheet = $this->processor->getActiveSheet();

        foreach ($results as $result) {
            for ($i = 0, $col = 'A'; $i < count($this->fields); $i++, ++$col) {
                $activeSheet->setCellValue($col . $this->lastEmptyRow, $result[$this->fields[$i]]);
            }

            $this->lastEmptyRow++;
        }

        $this->appendToFile($this->tmpFile);
        unset($this->processor);
    }

    public function prepareAppend($file)
    {
        $this->processor = $this->reader->load($file);
        $this->processor->setActiveSheetIndex(0);
        $this->lastEmptyRow = $this->processor->getActiveSheet()->getHighestRow() + 1;
    }

    protected function getTempFile($filename)
    {
        $filename = $filename . $this->getExtension();
        $path = $this->config->get('opilo/exporter::tmp_path');
        return $path . $filename;
    }

    abstract protected function appendToFile($file);

}
