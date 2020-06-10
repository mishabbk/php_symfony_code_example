<?php

namespace App\Entity;

interface AddressInterface
{
    public function getAddress(): ?Address;

    public function setAddress(?Address $address);
}
