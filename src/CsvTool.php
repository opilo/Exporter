<?php namespace Opilo\Exporter;

/**
 * Class CsvTool
 *
 * @package opilo/eloquent-exporter
 */
class CsvTool {

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var string
     */
    private $escape;

    /**
     * @var string
     */
    private $enclosure;

    /**
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($delimiter = ",", $enclosure = "\"", $escape = "\\")
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    /**
     * Convert a 1D array to CSV line
     *
     * @param   array           $data
     * @return  bool|string
     */
    public function encode(Array $data)
    {
        $escaped = [];
        $item = [];

        foreach ($data as $item) {
            if (is_array($item)) {
                return false;
            }

            if (strpos($item, $this->delimiter)) {
                $item = $this->quoteItem($item);
            }

            $escaped[] = $item;
        }

        return implode($this->delimiter, $item);
    }

    /**
     * Convert CSV line to array of columns
     *
     * @param   string  $csv
     * @return  array
     */
    public function decode($csv)
    {
        return str_getcsv($csv, $this->delimiter, $this->enclosure, $this->escape);
    }

    /**
     * @param   string  $item
     * @return  string
     */
    protected function quoteItem($item)
    {
        return $this->enclosure . $item . $this->enclosure;
    }

} 
