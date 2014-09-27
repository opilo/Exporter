<?php namespace Opilo\Exporter\Publishers;

use PHPExcel_Writer_CSV as PhpExcelWriterCsv;

class CsvPublisher extends BasePublisher {

    public function publish($filename)
    {
        $this->tmpFile = storage_path() . '/tmp/' . $filename . '.csv'; // TODO: Does folder exists? Has it permission to write?
        $this->writer->save($this->tmpFile);
    }

    protected function appendToFile($file)
    {
        $writer = new PhpExcelWriterCsv($this->processor);
        $writer->save($file);
        unset($writer);
    }
}
