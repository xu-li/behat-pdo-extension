<?php
namespace lixu\BehatPDOExtension\Exception;

class RecordsNotFoundException extends \Exception
{
    // Which database table
    public $table;

    /**
     * Constructor
     */
    public function __construct($message = null, $table = '', $code = 0, $previous = null)
    {
        $this->table = $table;

        parent::__construct($message, $code, $previous);
    }
}
