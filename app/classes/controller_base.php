<?php

Abstract Class Controller_Base extends Languages {

    protected $registry;
    protected $template;
    protected $layouts;
     
    public $vars = array();
    
    function __construct($registry) {
        $this->registry = $registry;
        
        $this->getLang();

        $this->template = new Template($this->layouts, get_class($this));
    }

    abstract function index();
}