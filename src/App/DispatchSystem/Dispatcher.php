<?php

namespace App\DispatchSystem;

use App\Couriers\CourierInterface;
use App\Couriers\CouriersConfig;
use App\DispatchSystem\BatchFormats\DispatchPeriodInterface;
use App\Factories\ConsignmentFactory;

/**
 * Entry point of the program.
 * The class will take parcels from the outside, pass them to a queue until the working hours are over
 * At the end, it takes all the consignments and uses different service to pass list of id's to each individual courier
 *
 * Class Dispatcher
 *
 * @package App\DispatchSystem
 */
class Dispatcher
{
    /**
     * @var DispatchPeriodInterface
     */
    protected $dispatchPeriod;

    /**
     * @var ConsignmentQueue
     */
    protected $parcelQueue;

    private $availableCouriers = [
        CouriersConfig::ROYAL_MAIL => null,
        CouriersConfig::ANC        => null,
    ];

    /**
     * Dispatcher constructor.
     *
     * @param DispatchPeriodInterface $dispatchPeriod
     * @param ConsignmentQueue        $consignmentQueue
     */
    public function __construct(DispatchPeriodInterface $dispatchPeriod, ConsignmentQueue $consignmentQueue)
    {
        $this->dispatchPeriod = $dispatchPeriod;
        $this->parcelQueue    = $consignmentQueue;
    }

    /**
     * Setting the list of potential couriers
     *
     * @param CourierInterface $royalMail
     * @param CourierInterface $anc
     */
    public function setCouriers(CourierInterface $royalMail, CourierInterface $anc)
    {
        $this->availableCouriers[CouriersConfig::ROYAL_MAIL] = $royalMail;
        $this->availableCouriers[CouriersConfig::ANC]        = $anc;
    }

    public function addConsignment(int $courierId)
    {
        // Checking if we can process. I've added more explanation for on WorkingHoursBatch class
        $this->dispatchPeriod->isDispatchAvailable();

        // just checking if I have that type of courier, will return false for now but it should implement better handling for something like that
        if (!isset($this->availableCouriers[$courierId])) {
            return false;
        }

        // creates the consignment which is passed to the queue processing bellow
        $consignment = (new ConsignmentFactory())->createConsignment($courierId, $this->availableCouriers[$courierId]->assignNumber());

        $this->parcelQueue->add($consignment);
    }

    /**
     * After grouping the consignments to each courier, I'm sending them here with specified method for each courier
     * (Ftp, Email)
     *
     * @return void
     */
    public function transferConsignments()
    {
        $consignmentsByCourier = $this->filterConsignmentsByCourier();

        foreach ($consignmentsByCourier as $courier => $data) {
            $courierInstance = $this->availableCouriers[$courier];
            $courierInstance->dataTransport($data);
        }
    }

    /**
     * The goal is to send a list of consignment id's to each individual courier. I wanted to group them here to do that
     * This is probably not ideal solution or place for a real world task but will have to do it here
     *
     * @return array[]
     */
    private function filterConsignmentsByCourier()
    {
        $consignmentsCount = $this->parcelQueue->getQueueCount();

        $consignmentByCourier = [
            CouriersConfig::ROYAL_MAIL => [],
            CouriersConfig::ANC        => [],
        ];

        // This is basically going through my fake queue and taking items from and grouping those consignments for each courier
        for ($i = 0; $i < $consignmentsCount; $i++) {
            $consignment                                 = $this->parcelQueue->remove();
            $consignmentCourier                          = $consignment->getCourierName();
            $consignmentByCourier[$consignmentCourier][] = $consignment->getId();
        }

        return $consignmentByCourier;
    }
}
