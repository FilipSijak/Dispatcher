<?php

namespace App\Services\TransferDataServices;

/**
 * Class that will improvise service for handling emails
 * For now, it just output some prints and text to see the result of this small application
 *
 * Class EmailService
 *
 * @package App\Services\TransferDataServices
 */
class EmailService implements TransferConsignmentDataInterface
{
    public function send(array $data)
    {
        echo 'Email service (Royal mail) <br />';
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        // TODO: Implement send() method.
    }
}