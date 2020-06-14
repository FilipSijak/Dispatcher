<?php declare(strict_types=1);

require_once "./vendor/autoload.php";

use App\Couriers\ANC;
use App\Couriers\CouriersConfig;
use App\Couriers\RoyalMail;
use App\DispatchSystem\BatchFormats\WorkingHoursBatch;
use App\DispatchSystem\Dispatcher;
use App\DispatchSystem\ConsignmentQueue;
use App\Services\TransferDataServices\EmailService;
use App\Services\TransferDataServices\FTPService;

$royalMail = new RoyalMail(new EmailService());
$anc       = new ANC(new FTPService());

$dispatch = new Dispatcher(new WorkingHoursBatch(), new ConsignmentQueue());
$dispatch->setCouriers($royalMail, $anc);

$dispatch->addConsignment(CouriersConfig::ROYAL_MAIL);
$dispatch->addConsignment(CouriersConfig::ROYAL_MAIL);
$dispatch->addConsignment(CouriersConfig::ROYAL_MAIL);
$dispatch->addConsignment(CouriersConfig::ANC);

$dispatch->transferConsignments();
