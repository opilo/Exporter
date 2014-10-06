<?php namespace Opilo\Exporter\Facades;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;

/**
 * Class Exporter
 * @method  export(Builder $query, $headers = [], $relationHeader = [])
 * @package Opilo\Exporter\Facades
 */
class Exporter extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'exporter'; }

}
