<?php

namespace App\Couriers;

interface CourierInterface
{
    public function assignNumber();

    public function dataTransport(array $data);
}