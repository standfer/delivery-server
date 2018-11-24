<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WorkPlace
 *
 * @author Ivan
 */
class WorkPlace {
    /**
     * @var integer
     */
    public $id;
    
    /**
     * @var string
     */
    public $address;
    
    /**
     * @var Coordinate
     */
    public $location;
    
    function __construct($id, $address, $location) {
        
        $this->id = $id;
        $this->address = $address;
        $this->location = new Coordinate($location->id, $location->lat, $location->lng);
    }

}
