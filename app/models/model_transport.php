<?php

Class Model_Transport Extends Model_Base {
    
    function __construct($select = false) {
        parent::__construct($select);        
    }
    
    function getTransport($type, $result = [])
    {
        $sql = "SELECT * FROM {$this->table} JOIN type WHERE type.type = 'trol'";
        $query = $this->query($sql);
        
        foreach($query as $item)
        {
            array_push($result, $item->title);
        }
        
        return $result;
    }
    
}