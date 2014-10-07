<?php namespace Opilo\Exporter;

/**
 * Class FileWriter
 *
 * @package opilo/eloquent-exporter
 */
class FileWriter {

    /**
     * @var string
     */
    protected $file;

    /**
     * @var resource
     */
    protected $handle;

    /**
     * @param string|null $file
     */
    public function __construct($file = null)
    {
        $this->file = $file;

        if (!is_null($this->file)){
            $this->openFile();
        }
    }

    /**
     * @param   string      $file
     * @return  FileWriter
     */
    public function load($file)
    {
        $this->setFile($file);

        return $this;
    }

    /**
     * Read file and return its content
     *
     * @return string
     */
    public function read()
    {
        return fread($this->handle, filesize($this->file));
    }

    /**
     * Write each item of array in seperate line
     *
     * @param array $data
     */
    public function writeArray(Array $data)
    {
        foreach ($data as $line) {
            fwrite($this->handle, $line . PHP_EOL);
        }
    }

    /**
     * Write data to file
     *
     * @param string $data
     */
    public function writeLine($data)
    {
        fwrite($this->handle, $data . PHP_EOL);
    }

    /**
     * @author Behzadsh <behzad.shabani@gmail.com>
     */
    protected function openFile()
    {
        $this->handle = fopen($this->file, 'a+');
    }

    /**
     * Set the file to do read or write with
     *
     * @param $file
     */
    public function setFile($file)
    {
        $this->file = $file;

        $this->openFile();
    }

    /**
     * Close file handle at the end
     */
    function __destruct()
    {
        if (! is_null($this->handle)) {
            fclose($this->handle);
        }
    }

} 
