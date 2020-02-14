<?php 


namespace Interfaces
{

    interface IComparator
    {
        public function Equals($value_compare) : bool;
        public function GreaterThan($value_compare) : bool;
        public function LessThan($value_compare) : bool;
        public function Compare($value_compare) : int;
    }

}



?>