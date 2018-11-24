<?php
include 'C:\xampp\htdocs\delivery\dto\WorkPlace.php';
include 'C:\xampp\htdocs\delivery\dto\Courier.php';
include 'C:\xampp\htdocs\delivery\dto\Client.php';
include 'C:\xampp\htdocs\delivery\dto\Coordinate.php';
/**
 * Description of Order
 *
 * @author Ivan
 */
class Order {

    /**
     * @var string
     */
    private $clientPhone;

    /**
     * @var string
     */
    private $clientName;

    /**
     * @var integer
     */
    private $idClient;

    /**
     * @var float
     */
    private $lngWorkPlace;

    /**
     * @var float
     */
    private $latWorkPlace;

    /**
     * @var float
     */
    private $lng;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var string
     */
    private $addressWorkPlace;

    /**
     * @var integer
     */
    private $idWorkPlace;

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var float
     */
    public $cost;

    /**
     * @var integer
     */
    public $isDelivered;
    
    /**
     * @var integer
     */
    public $priority;
    
    /**
     * @var float
     */
    public $odd;
    
    /**
     * @var string
     */
    public $notes;

    /**
     * @var Coordinate
     */
    public $location;

    /**
     * @var Courier
     */
    public $courier;
    
    /**
     * @var WorkPlace
     */
    public $workPlace;
    
    /**
     * @var Client
     */
    public $client;
    
//    function __construct($id, $address, $lat, $lng,
//                            $phoneNumber, $cost, $isDelivered,
//                            $idWorkPlace, $addressWorkplace,
//                            $latWorkPlace, $lngWorkPlace,
//                            $priority, $odd, $notes,
//                            $idClient, $clientName, $clientPhone
//            ) {
//        $this->id = $id;
//        $this->phoneNumber = $phoneNumber;
//        $this->cost = $cost;
//        $this->isDelivered = $isDelivered;
//        $this->address = $address;
//        $this->priority = $priority;
//        $this->odd = $odd;
//        $this->notes = $notes;
//        
//        $this->workPlace = new WorkPlace($idWorkPlace, $addressWorkplace, 
//                new Coordinate(NULL, $latWorkPlace, $lngWorkPlace));
//        $this->location = new Coordinate(NULL, $lat, $lng);
//        $this->client = new Client($idClient, $clientName, $clientPhone);
//    }
    
    function __construct($address, $lat, $lng, $phoneNumber, $cost, $isDelivered, $idWorkPlace, $addressWorkplace, $latWorkPlace, $lngWorkPlace, $priority, $odd, $notes, $idClient, $clientName, $clientPhone
    ) {
        $this->id = $id;
        $this->phoneNumber = $phoneNumber;
        $this->cost = $cost;
        $this->isDelivered = $isDelivered;
        $this->address = $address;
        $this->priority = $priority;
        $this->odd = $odd;
        $this->notes = $notes;

        $this->workPlace = new WorkPlace($idWorkPlace, $addressWorkplace, new Coordinate(NULL, $latWorkPlace, $lngWorkPlace));
        $this->location = new Coordinate(NULL, $lat, $lng);
        $this->client = new Client($idClient, $clientName, $clientPhone);
    }

}

/* integer $id , string $address, float $lat, float $lng,
      string $phoneNumber, float $cost, integer $isDelivered,
      integer $idWorkPlace, string $addressWorkplace,
      float $latWorkPlace, float $lngWorkPlace,
      integer $priority, float $odd, string $notes,
      integer $idClient, string $clientName, string $clientPhone */
