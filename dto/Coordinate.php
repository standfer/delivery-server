<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Coordinate
 *
 * @author Ivan
 */
class Coordinate {
    /**
     * @var integer
     */
    public $id;
    
    /**
     * @var float
     */
    public $lat;
    
    /**
     * @var float
     */
    public $lng;

    function __construct($id, $lat, $lng) {
        $this->id = $id;
        $this->lat = $lat;
        $this->lng = $lng;
    }
}
