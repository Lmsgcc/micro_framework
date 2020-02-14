<?php 

namespace Traits{

use Shared\Exceptions\NotImplementedExceptions;

trait Iterator
    {
        protected $_current = null;
    
        public function Next()
        {
            throw new NotImplementedExceptions();        
        }
    
        public function Prev()
        {
            throw new NotImplementedExceptions();
        }
    
        public function GetCurrent()
        {
            throw new NotImplementedExceptions();
        }
        
        public function Reset()
        {
            throw new NotImplementedExceptions();
        }

        public function End()
        {
            throw new NotImplementedExceptions();
        }
    }

}

?>