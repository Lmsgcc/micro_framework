<?php

namespace Structures{
    require_once './traits/iterator.php';

    use Interfaces\IPrint;
    use OutOfBoundsException;
    use Structures\LinkedList\ListIterator;
    use Structures\LinkedList\Node;

    class LinkedList implements IPrint
    {
        protected $_head;
        protected $_is_strict;
        protected $_size = 0;
        protected $_iterator = null;
        protected $_type = null;
        public function __construct(bool $strict = false)
        {
            $this->_head = new Node();
            $this->_is_strict = $strict;
            $this->_iterator = new ListIterator($this);
        }

        public function print()
        {
            $this->_iterator->Reset();
            while( $node = $this->_iterator->Next() )
            {
                var_dump($node->GetValue());
            }
        }
        public function GetHead() : Node
        {
            return $this->_head;
        }
        public function FindIndex($pos) : ? Node
        {
            if ($pos > $this->_size || $pos < 0) {
                throw new OutOfBoundsException("You are trying to access a position non existing within the list");
            }
            $curr = $this->_head;
            $curr_pos = 0;
            while ($curr_pos < $pos) {
                $curr = $curr->GetNext();
                ++$curr_pos;
            }
            return $curr->Equals($this->_head) ? null : $curr; // if not found returns null
        }

        public function InsertAt($value, int $pos) : bool
        {
            if(!$this->CheckType($value))
            {
                return false;
            }
            $node = new Node($value);
            $to_insert_after = $this->FindIndex($pos);
            if ($to_insert_after === null) {
                $to_insert_after = $this->_head;
            }
            $to_insert_before = $to_insert_after->GetNext();
            $node->SetNext($to_insert_before)->SetPrev($to_insert_after);
            $to_insert_after->SetNext($node);
            $to_insert_before->SetPrev($node);
            ++$this->_size;
            return true;
        }

        public function AddFirst($value) : bool
        {
            if ( !$this->CheckType($value) ){
                return false;
            }
            $this->InsertAt($value, 0);
            return true;
        }

        public function AddLast($value) : bool
        {
            if( !$this->CheckType($value) ){
                return false;
            }
            $this->InsertAt($value, $this->_size);
            return true;
        }

        private function CheckType($value) : bool
        {
            if($this->_is_strict)
            {
                $type = gettype($value);
                if($type === 'object')
                {
                    $type = get_class($value);
                }
                if (!isset($this->_type)){
                    $this->_type = $type;
                    return true;
                }
                return $this->_type === $type;
            }
            return true;
        }
    }
}


namespace Structures\LinkedList
{
    use Interfaces\IComparator;
    use Structures\LinkedList;
    use Traits\Iterator;

    class Node implements IComparator
    {

        private $_next =  null;
        private $_prev = null;
        private $_value = null;
        public function GreaterThan($value_compare): bool
        {
            return false;
        }
        public function Compare($value_compare): int
        {
            return 0;
        }
        public function LessThan($value_compare): bool
        {
            return false;
        }
        public function Equals($value_compare): bool
        {
            if (! ($value_compare instanceof Node)) {
                return false;
            }
            return $this->_value === $value_compare->GetValue() &&
                $this->_next == $value_compare->GetNext() &&
                $this->_prev = $value_compare->GetPrev();
        }
        public function SetValue(&$value) : Node
        {
            $this->_value = $value;
            return $this;
        }
        
        public function IsHead()
        {
            return isset($this->_is_head) && $this->_is_head == true;
        }

        public function GetValue()
        {
            return $this->_value;
        }

        public function GetNext()
        {
            return $this->_next;
        }

        public function SetNext(&$next) : Node
        {
            $this->_next = $next;
            return $this;
        }

        public function GetPrev()
        {
            return $this->_prev;
        }

        public function SetPrev(&$prev) : Node
        {
            $this->_prev = $prev;
            return $this;
        }

        public function __construct(&$value = null)
        {
            $this->_value = $value;
            $this->_prev = $this->_next = & $this;
        }
    }


    class ListIterator
    {
        use Iterator;

        protected $_target = null;

        public function __construct(LinkedList & $list)
        {
            $this->_target = $list;
            $this->_current = $list->GetHead();
            $this->_current->_is_head = true;
        }

        public function Next() : ? Node
        {
            $this->_current = $this->_current->GetNext();
            return $this->_current->IsHead() ? null : $this->_current;
        }
    
        public function Prev() : ? Node
        {
            $this->_current = $this->_current->GetPrev();
            return $this->_current->IsHead() ? null : $this->_current;
        }
    
        public function GetCurrent() : Node
        {
            return $this->_current;
        }

        public function Reset() : Node
        {
            return $this->_current = $this->_target->GetHead();
        }

        public function End() : Node
        {
            return $this->_current = $this->_target->GetHead()->GetPrev();
        }
    }
}


