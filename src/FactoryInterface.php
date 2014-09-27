<?php namespace Opilo\Exporter;

/**
 * Interface FactoryInterface
 *
 * @package Opilo\Exporter
 */
interface FactoryInterface {

    /**
     * Create a contact exporter based on file extension
     *
     * @param   string  $fileExtension
     * @return  ExporterInterface
     */
    public static function make($fileExtension);

}
