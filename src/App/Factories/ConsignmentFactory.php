<?php

namespace App\Factories;

use App\Models\Consignment;

/**
 * Factory for creating Consignment
 *
 * Class ConsignmentFactory
 *
 * @package App\Factories
 */
class ConsignmentFactory
{
    /**
     * @param string $courierName
     * @param int    $id
     *
     * @return Consignment
     */
    public function createConsignment(string $courierName, int $id)
    {
        $consignment = new Consignment();

        $consignment->setId($id);
        $consignment->setCourierName($courierName);

        return $consignment;
    }
}
