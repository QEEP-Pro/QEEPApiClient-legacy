<?php

/**
 * Created by Michael A. Sivolobov
 * Date: 06/08/14
 */
class Order
{
    protected $id;

    protected $buyerName;

    protected $buyerPhone;

    protected $buyerEmail;

    protected $deliveryPrice;

    protected $comment;

    protected $salesChannel;

    protected $purchases;

    protected $allowSpam = true;

    protected $paymentMethod;

    public function __construct()
    {
        $this->purchases = [];
    }

    // QEEP-Pro принимает `orderId` вместо `id`

//    public function getId() : ?int
//    {
//        return $this->id;
//    }

    public function getOrderId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getBuyerName()
    {
        return $this->buyerName;
    }

    public function setBuyerName($buyerName)
    {
        $this->buyerName = $buyerName;

        return $this;
    }

    public function getBuyerPhone()
    {
        return $this->buyerPhone;
    }

    public function setBuyerPhone($buyerPhone)
    {
        $this->buyerPhone = $buyerPhone;

        return $this;
    }

    public function getBuyerEmail()
    {
        return $this->buyerEmail;
    }

    public function setBuyerEmail($buyerEmail)
    {
        $this->buyerEmail = $buyerEmail;

        return $this;
    }

    public function getDeliveryPrice()
    {
        return $this->deliveryPrice;
    }

    public function setDeliveryPrice($deliveryPrice)
    {
        $this->deliveryPrice = $deliveryPrice;

        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    // QEEP-Pro принимает `website` вместо `salesChannel`

//    public function getSalesChannel() : ?string
//    {
//        return $this->salesChannel;
//    }

    public function getWebsite()
    {
        return $this->salesChannel;
    }

    public function setSalesChannel($salesChannel)
    {
        $this->salesChannel = $salesChannel;

        return $this;
    }

    // QEEP-Pro принимает `products` вместо `purchases`

//    public function getPurchases() : array
//    {
//        return $this->purchases;
//    }

    public function getProducts()
    {
        return $this->purchases;
    }

    public function setPurchases(array $purchases)
    {
        $this->purchases = $purchases;

        return $this;
    }

    public function getAllowSpam()
    {
        return $this->allowSpam;
    }

    public function setAllowSpam($allowSpam)
    {
        $this->allowSpam = $allowSpam;

        return $this;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }
}
