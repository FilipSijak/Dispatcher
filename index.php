<?php declare(strict_types = 1);

require_once "./vendor/autoload.php";

use \App\DispatchSystem\Dispatcher;
use \App\DispatchSystem\BatchFormats\WorkingHoursBatch;
use \App\DispatchSystem\ParcelQueue;
use \App\Couriers\RoyalMail;
use \App\Couriers\ANC;
use \App\Services\TransferDataServices\EmailService;
use \App\Services\TransferDataServices\FTPService;
use \App\Couriers\CouriersConfig;

$royalMail = new RoyalMail(new EmailService());
$anc = new ANC(new FTPService());

$dispatch = new Dispatcher(new WorkingHoursBatch(), new ParcelQueue());
$dispatch->setCouriers($royalMail, $anc);

$dispatch->addConsignment(CouriersConfig::ROYAL_MAIL);
$dispatch->addConsignment(CouriersConfig::ROYAL_MAIL);
$dispatch->addConsignment(CouriersConfig::ROYAL_MAIL);
$dispatch->addConsignment(CouriersConfig::ANC);

$dispatch->transferConsignments();
