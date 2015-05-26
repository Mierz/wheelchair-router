<?php
Class Controller_Index Extends Controller_Base {    
    
    public $layouts = "layout";
    public $city;

    function index() 
    {
        $this->getCity(filter_input(INPUT_GET, 'city', FILTER_SANITIZE_STRING));

        $lang = $this->getLangTitle();         
        $this->template->vars('lang', $lang);
        $this->template->vars('city', $this->city);
        $this->template->view('index');        
    }    
    
    function about()
    {
        $lang = $this->getLangTitle();
        $this->template->vars('lang', $lang);
        $this->template->vars('city', $this->city);
        $this->template->view('about');
    }
    
    function language()
    {
        $lang = filter_input(INPUT_GET, 'lang', FILTER_SANITIZE_STRING); 
        
        $this->changeLang($lang);

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    private function getCity($city)
    {
        if(isset($city) && !empty($city))
        {
            $this->city = $city;
            $_SESSION['city'] = $city;
        }
        else
        {
            $this->city = 'kyiv';
            $_SESSION['city'] = 'kyiv';
            header('Location: /?city=kyiv');
        }
    }
}