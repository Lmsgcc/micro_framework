<?php

namespace Model;


class ProductsModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->_table = "products";
    }

    public function GetProducts()
    {
        $query = "SELECT * FROM $this->_table";
    }

    public function Save($data) : int
    {
        return parent::Save($data);
    }

    public function ReadOne($product_id) : array
    {
        return parent::ReadOne($product_id);
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function ReadAll() : array
    {
        $query = "SELECT `id`, `name`, `price` FROM $this->_table WHERE `active` = 1";
        return $this->FetchAssociative($query);
    }
}


?>