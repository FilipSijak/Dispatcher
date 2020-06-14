<?php

namespace App\Services\TransferDataServices;

/**
 * Class that will improvise service for handling ftp transfer
 * For now, it just output some prints and text to see the result of this small application
 *
 * Class FTPService
 *
 * @package App\Services\TransferDataServices
 */
class FTPService implements TransferConsignmentDataInterface
{
    public function send(array $data)
    {
        echo 'Ftp service (ANC) <br />';
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        // TODO: Implement send() method.
    }
}