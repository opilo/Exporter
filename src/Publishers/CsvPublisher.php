<?php namespace Opilo\Exporter\Publishers;

use PHPExcel_Writer_CSV as PhpExcelWriterCsv;

class CsvPublisher extends BasePublisher {

    public function publish($filename)
    {
        $this->writer->save($this->getTempFile($filename));
    }

    protected function appendToFile($file)
    {
        $writer = new PhpExcelWriterCsv($this->processor);
        $writer->save($file);
        unset($writer);
    }

    protected function getExtension()
    {
        return '.csv';
    }
}
