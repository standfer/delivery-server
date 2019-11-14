<?php

include 'Product.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProductInOrder
 *
 * @author Ivan
 */
class ProductInOrder {
    /**
     * @var integer
     */
    public $id;
    
    /**
     * @var integer
     */
    public $quantity;

    /**
     * @var Order
     */
    public $order;
    
    /**
     * @var Product
     */
    public $product;

    function __construct($id, $quantity, $order, $productId, $productName, $productPrice) {
        $this->id = $id;
        $this->quantity = $quantity;

        $this->product = new Product($productId, $productName, $productPrice);
        $this->order = new Order($order->id, 
                                    $order->address,
                                    $order->location->lat,
                                    $order->location->lng,
                                    $order->phoneNumber,
                                    $order->cost,
                                    $order->isAssigned,
                                    $order->isDelivered,
                                    $order->workPlace->id,
                                    $order->workPlace->address,
                                    $order->workPlace->location->lat,
                                    $order->workPlace->location->lng,
                                    $order->priority,
                                    $order->odd,
                                    $order->notes,
                                    $order->client->id,
                                    $order->client->name,
                                    $order->client->phone
                                    );
    }

}
