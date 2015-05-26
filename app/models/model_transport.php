<?php

Class Model_Transport Extends Model_Base {
    
    function __construct($select = false) {
        parent::__construct($select);        
    }
    
    function getTransport($type, $city, $result = [])
    {
        //$sql = "SELECT * FROM {$this->table} JOIN type AND JOIN city WHERE type.type = 'trol''";

        $sql = "SELECT tr.id, tr.title, t.type
          FROM transport tr
          INNER JOIN type t ON tr.type_id = t.id
          JOIN city ci ON tr.city_id = ci.id
          WHERE ci.city = '{$city}' AND t.type = '{$type}'
          ORDER BY tr.id ASC";

        $query = $this->query($sql);

        foreach($query as $item)
        {

            array_push($result, $item->title);
        }
        
        return $result;
    }
    
}