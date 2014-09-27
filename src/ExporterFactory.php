<?php namespace Opilo\Exporter;

/**
 * Class ExporterFactory
 *
 * @package Opilo\Exporter
 */
class ExporterFactory implements FactoryInterface {

	/**
     * CSV file format (.csv)
     *
     * @const
     */
    const CSV = 'CSV';

	/**
     * Microsoft Excel 2007 and later (.xlsx)
     *
     * @const
     */
    const MS_EXCEL_07 = 'Excel2007';

    /**
     * Microsoft Excel 2003 and below (.xls)
     *
     * @const
     */
    const MS_EXCEL_03 = 'Excel5';

    /**
     * Microsoft Excel 2003 xml (.xml)
     *
     * @const
     */
    const MS_EXCEL_XML = 'Excel2003XML';

    /**
     * Open document formats (.ods, .fods)
     *
     * @const
     */
    const OPEN_DOCUMENT = 'OOCalc';

    /**
     * Create a contact exporter based on file extension
     *
     * @param   string  $fileExtension
     * @return  ExporterInterface
     */
    public static function make($fileExtension)
    {
        return new Exporter(
            new \PHPExcel(),
            $fileExtension
        );
    }
}
