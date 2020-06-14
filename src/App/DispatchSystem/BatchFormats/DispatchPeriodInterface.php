<?php

namespace App\DispatchSystem\BatchFormats;

interface DispatchPeriodInterface
{
    public function isDispatchAvailable();
}