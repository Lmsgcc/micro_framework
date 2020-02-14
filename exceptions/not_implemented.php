<?php 

namespace Shared\Exceptions
{
    use Exception;
    class NotImplementedExceptions extends Exception
    {
        private $_code = 100;
        public function __construct()
        {
            parent::__construct("This method hasn't been implemented yet", $this->_code);
        }
    }
}
?>