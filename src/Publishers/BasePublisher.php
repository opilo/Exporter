<?php namespace Opilo\Exporter\Publishers;

use PHPExcel_Writer_IWriter as PhpExcelWriter;
use PHPExcel_Reader_IReader as PhpExcelReader;

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

    public function __construct(
        PhpExcelWriter $writer,
        PhpExcelReader $reader,
        $fields
    ) {
        $this->writer = $writer;
        $this->reader = $reader;
        $this->fields = $fields;
    }

    abstract public function publish($filename);

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

    abstract protected function appendToFile($file);

}
