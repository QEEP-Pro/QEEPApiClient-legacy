<?php
/**
 * Created by Michael A. Sivolobov
 * Date: 19.06.13
 */

class Purchase
{
    protected $id;

    protected $price;

    protected $amount;

    // QEEP-Pro принимает `code` вместо `id`

//    public function getId() : ?int
//    {
//        return $this->id;
//    }

    public function getCode()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }
}
