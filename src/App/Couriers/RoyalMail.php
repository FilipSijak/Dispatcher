<?php

namespace App\Couriers;

use App\Services\TransferDataServices\TransferConsignmentDataInterface;

/**
 * Class RoyalMail
 *
 * @package App\Couriers
 */
class RoyalMail implements CourierInterface
{
    /**
     * @var TransferConsignmentDataInterface
     */
    protected $emailService;

    public function __construct(TransferConsignmentDataInterface $emailService)
    {
        $this->emailService = $emailService;
    }

    public function assignNumber()
    {
        return abs(crc32(uniqid()));
    }

    public function dataTransport(array $data)
    {
        $this->emailService->send($data);
    }
}
