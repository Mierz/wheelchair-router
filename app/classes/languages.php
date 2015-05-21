<?php
class Languages {
        
    protected $registry;
            
    function __construct($registry) {
        $this->registry = $registry;         
    }
    
    protected function getLang() {               
        if(empty($_SESSION['lang']))
        {            
            $language = 'uk';
        }
        else
        {
            $language = $_SESSION['lang'];
        }        
        
        switch($language) :            
            case 'ru': 
                include (SITE_PATH . 'app'. DS . 'languages' . DS . 'ru.php');
                break;
            case 'en': 
                include (SITE_PATH . 'app'. DS . 'languages' . DS . 'en.php'); 
                break;
            case 'uk':                 
                include (SITE_PATH . 'app'. DS . 'languages' . DS . 'uk.php'); 
                break;
        endswitch;        
    }    
    
    public function getLangTitle()
    {
        if(empty($_SESSION['lang']))
        {
            return 'uk';
        }
        else
        {
            return $_SESSION['lang'];
        }        
    }


    public function changeLang($lang) {
        $_SESSION['lang'] = $lang;        
    }
    
}