<?php

/**
 * Description of Courier
 *
 * @author Ivan
 */
class Courier {
    
     /**
     * @var integer
     */
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $surname;
    
    /**
     * @var long
     */
    public $phone;
    
    /**
     * @var integer
     */
    public $idLocation;
    
    /**
     * @var string
     */
    public $login;
    
    /**
     * @var string
     */
    public $password;
    
    /**
     * @var LinkedList
     */
    public $orders;
    
    function __construct(integer $id, string $name, string $surname,
            long $phone, integer $idLocation, 
            string $login, string $password) {
        
    }
    
}
