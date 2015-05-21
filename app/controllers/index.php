<?php
Class Controller_Index Extends Controller_Base {    
    
    public $layouts = "layout";     
    
    function index() 
    {   
        $lang = $this->getLangTitle();         
        $this->template->vars('lang', $lang);
        $this->template->view('index');        
    }    
    
    function about()
    {
        $this->template->view('about');
    }
    
    function language()
    {
        $lang = filter_input(INPUT_GET, 'lang', FILTER_SANITIZE_STRING); 
        
        $this->changeLang($lang);
        
        echo 'Translate site...';
        echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $_SERVER['HTTP_REFERER'] . "\">";
    }
    
}