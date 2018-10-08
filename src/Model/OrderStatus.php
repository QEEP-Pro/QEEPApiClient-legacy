<?php
/**
 * Created by Michael A. Sivolobov
 * Date: 19.06.13
 */

class OrderStatus {
    protected $code;

    protected $value;

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
