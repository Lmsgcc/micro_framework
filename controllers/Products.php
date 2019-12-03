<?php 

namespace Controllers;

use Model\ProductsModel;

class Products extends Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->_model = new ProductsModel();
    }

    public function view_all()
    {
        $this->_data["products"] = $this->_model->ReadAll();
        parent::LoadView();
    }

    public function update($product_id = null){
        $this->_data["product"] = $this->_model->ReadOne($product_id);
        parent::LoadView();
    }

    public function save()
    {
        if ($this->_model->Save($this->_post["product"] ?? []) > 0)
        {

        }
        $this->RedirectInternal("products", "view_all");
    }
}

?>