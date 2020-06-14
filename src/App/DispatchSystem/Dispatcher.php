<?php

namespace App\DispatchSystem;

use App\Couriers\CourierInterface;
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
     * It will take
     *
     * @var DispatchPeriodInterface
     */
    protected $dispatchPeriod;

    /**
     * @var ParcelQueue
     */
    protected $parcelQueue;

    private $availableCouriers = [
        'royal' => null,
        'anc'   => null,
    ];

    /**
     * Dispatcher constructor.
     *
     * @param DispatchPeriodInterface $dispatchPeriod
     * @param ParcelQueue             $parcelQueue
     */
    public function __construct(DispatchPeriodInterface $dispatchPeriod, ParcelQueue $parcelQueue)
    {
        $this->dispatchPeriod = $dispatchPeriod;
        $this->parcelQueue    = $parcelQueue;
    }

    /**
     * Setting the list of potential couriers
     *
     * @param CourierInterface $royalMail
     * @param CourierInterface $anc
     */
    public function setCouriers(CourierInterface $royalMail, CourierInterface $anc)
    {
        $this->availableCouriers['royal'] = $royalMail;
        $this->availableCouriers['anc']   = $anc;
    }

    public function addConsignment(string $courierName)
    {
        // Checking if we can process. I've added more explanation for on WorkingHoursBatch class
        $this->dispatchPeriod->isDispatchAvailable();

        // just checking if I have that type of courier, will return false for now but it should implement better handling for something like that
        if (!isset($this->availableCouriers[$courierName])) {
            return false;
        }

        // creates the consignment which is passed to the queue processing bellow
        $consignment = (new ConsignmentFactory())->createConsignment($courierName, $this->availableCouriers[$courierName]->assignNumber());

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
            'royal' => [],
            'anc'   => [],
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
