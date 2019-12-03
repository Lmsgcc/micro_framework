<?php 


namespace Controllers;

class Start extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    { 
        parent::LoadView();
    }
}

?>