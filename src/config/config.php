<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Query Chunk Size
    |--------------------------------------------------------------------------
    |
    | If your query has a lot (thousands) of Eloquent records, it is recommended
    | to use the chunk command, to allow us to fetch results and store them in
    | file without eating all of your RAM.
    |
    */

    'chunk_size'        => 100,

    /*
    |--------------------------------------------------------------------------
    | Export Store Path
    |--------------------------------------------------------------------------
    |
    | A writable path where exported file should be store in.
    | Notice: Path should be exist and be writable
    |
    */

    'store_path'        => storage_path() . '/export/',

    /*
    |--------------------------------------------------------------------------
    | Export File Extension
    |--------------------------------------------------------------------------
    |
    | Extension of exported file
    |
    */

    'file_extension'    => '.csv',

    /*
    |--------------------------------------------------------------------------
    | Relation Values Glue
    |--------------------------------------------------------------------------
    |
    | When you set relation for export, it may have many result value, for join
    | these values we join them by what you set here as `relation_glue`
    |
    */

    'relation_glue'     => '|',

    /*
    |--------------------------------------------------------------------------
    | CSV Delimiter
    |--------------------------------------------------------------------------
    |
    | The delimiter of CSV file. Notice that this should be diffrent from what
    | you set as `relation_glue`
    |
    */

    'csv_delimiter'     => ','
];
