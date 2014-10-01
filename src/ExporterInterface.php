<?php namespace Opilo\Exporter;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface ExporterInterface
 *
 * @package Opilo\Exporter
 */
interface ExporterInterface {

    /**
     * Export selected or all contacts to a file
     *
     * @param   Builder $query
     * @param   array   $headers
     * @param   array   $relationHeader
     * @return  mixed
     */
    public function export(Builder $query, $headers = [], $relationHeader = []);

}

