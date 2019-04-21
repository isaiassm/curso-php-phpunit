<?php

namespace OrderBundle\Entity;

class Customer
{
    private $id;
    private $name;
    private $phone;
    private $isActive;
    private $isBlocked;

    public function __construct($isActive, $isBlocked, $name, $phone)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->isActive = $isActive;
        $this->isBlocked = $isBlocked;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function isAllowedToOrder()
    {
        return $this->isActive && !$this->isBlocked;
    }
}