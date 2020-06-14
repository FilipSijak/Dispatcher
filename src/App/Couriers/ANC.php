<?php

namespace App\Couriers;

use App\Services\TransferDataServices\TransferConsignmentDataInterface;

/**
 *
 * Class ANC
 *
 * @package App\Couriers
 */
class ANC implements CourierInterface
{
    /**
     * @var TransferConsignmentDataInterface
     */
    protected $ftpService;

    public function __construct(TransferConsignmentDataInterface $ftpService)
    {
        $this->ftpService = $ftpService;
    }

    public function assignNumber()
    {
        return hexdec(uniqid());
    }

    public function dataTransport(array $data)
    {
        $this->ftpService->send($data);
    }
}