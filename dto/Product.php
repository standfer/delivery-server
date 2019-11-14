<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author Ivan
 */
class Product {
    /**
     * @var integer
     */
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var float
     */
    public $price;
    
    function __construct($id, $name, $price) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }
}
