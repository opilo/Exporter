<?php namespace Opilo\Exporter\Publishers;

use PHPExcel_IOFactory as PhpExcelFactory;

class PublisherFactory {

    /**
     * Create a publisher based on fileType
     *
     * @author  Behzadsh    <behzad.shabani@gmail.com>
     * @param   \PHPExcel   $phpExcel
     * @param   string      $fileType
     * @param   array       $fields
     * @throws  PublisherFactoryException
     * @return  BasePublisher
     */
    public static function make(\PHPExcel $phpExcel, $fileType, $fields)
    {
        return self::loadPublisher($phpExcel, $fileType, $fields);
    }

    protected static function loadPublisher($phpExcel, $fileType, $fields)
    {
        $class = __NAMESPACE__ . 'Publishers\\' . ucfirst(self::detectPublisherType($fileType)) . 'Publisher';

        if (!class_exists($class)) {
            throw new PublisherFactoryException('No publisher found for this type of file');
        }

        return new $class(
            PhpExcelFactory::createWriter($phpExcel, $fileType),
            PhpExcelFactory::createReader($fileType),
            $fields
        );
    }

    protected static function detectPublisherType($fileType)
    {
        switch ($fileType) {
            case 'Excel2007':
                return 'Xlsx';
            case 'Excel5':
                return 'Xls';
            case 'Excel2003XML':
                return 'Xml';
            case 'OOCalc':
                return 'Ods';
            case 'CSV':
                return 'Csv';
            default:
                throw new PublisherFactoryException('No publisher found for this type of file');
        }
    }
}
