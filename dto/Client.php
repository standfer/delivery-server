<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Client
 *
 * @author Ivan
 */
class Client {
    /**
     * @var integer
     */
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var long
     */
    public $phone;
    
    function __construct($id, $name, $phone) {
        
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
    }

}
