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
     * @return  mixed
     */
    public function export(Builder $query);

    /**
     * @param   array $headers
     * @return  ExporterManager
     */
    public function headers(Array $headers);

    /**
     * @param   string  $headerName
     * @param   string  $alias
     * @return  ExporterManager
     */
    public function headerAlias($headerName, $alias);

    /**
     * @param   string  $relationName
     * @param   string  $relationColumn
     * @return  ExporterManager
     */
    public function relation($relationName, $relationColumn);

    /**
     * @param   string  $relationName
     * @param   string  $relationAlias
     * @return  ExporterManager
     */
    public function relationAlias($relationName, $relationAlias);

}

