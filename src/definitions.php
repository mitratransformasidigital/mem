<?php

namespace MEM\prjMitralPHP;

use Slim\Views\PhpRenderer;
use Slim\Csrf\Guard;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\DebugStack;

return [
    "cache" => function (ContainerInterface $c) {
        return new \Slim\HttpCache\CacheProvider();
    },
    "view" => function (ContainerInterface $c) {
        return new PhpRenderer("views/");
    },
    "flash" => function (ContainerInterface $c) {
        return new \Slim\Flash\Messages();
    },
    "audit" => function (ContainerInterface $c) {
        $logger = new Logger("audit"); // For audit trail
        $logger->pushHandler(new AuditTrailHandler("audit.log"));
        return $logger;
    },
    "log" => function (ContainerInterface $c) {
        global $RELATIVE_PATH;
        $logger = new Logger("log");
        $logger->pushHandler(new RotatingFileHandler($RELATIVE_PATH . "log.log"));
        return $logger;
    },
    "sqllogger" => function (ContainerInterface $c) {
        $loggers = [];
        if (Config("DEBUG")) {
            $loggers[] = $c->get("debugstack");
        }
        return (count($loggers) > 0) ? new LoggerChain($loggers) : null;
    },
    "csrf" => function (ContainerInterface $c) {
        global $ResponseFactory;
        return new Guard($ResponseFactory, Config("CSRF_PREFIX"));
    },
    "debugstack" => \DI\create(DebugStack::class),
    "debugsqllogger" => \DI\create(DebugSqlLogger::class),
    "security" => \DI\create(AdvancedSecurity::class),
    "profile" => \DI\create(UserProfile::class),
    "language" => \DI\create(Language::class),
    "timer" => \DI\create(Timer::class),
    "session" => \DI\create(HttpSession::class),

    // Tables
    "employee" => \DI\create(Employee::class),
    "employee_asset" => \DI\create(EmployeeAsset::class),
    "employee_contract" => \DI\create(EmployeeContract::class),
    "employee_timesheet" => \DI\create(EmployeeTimesheet::class),
    "master_city" => \DI\create(MasterCity::class),
    "master_office" => \DI\create(MasterOffice::class),
    "master_position" => \DI\create(MasterPosition::class),
    "master_skill" => \DI\create(MasterSkill::class),
    "master_status" => \DI\create(MasterStatus::class),
    "master_province" => \DI\create(MasterProvince::class),
    "master_shift" => \DI\create(MasterShift::class),
    "master_holiday" => \DI\create(MasterHoliday::class),
    "employee_shift" => \DI\create(EmployeeShift::class),
    "activity" => \DI\create(Activity::class),
    "permit" => \DI\create(Permit::class),
    "userlevelpermissions" => \DI\create(Userlevelpermissions::class),
    "userlevels" => \DI\create(Userlevels::class),
    "employee_trainings" => \DI\create(EmployeeTrainings::class),
    "myprofile" => \DI\create(Myprofile::class),
    "myasset" => \DI\create(Myasset::class),
    "mycontract" => \DI\create(Mycontract::class),
    "mytimesheet" => \DI\create(Mytimesheet::class),
    "mytraining" => \DI\create(Mytraining::class),
    "Top_10_Days" => \DI\create(Top10Days::class),
    "customer" => \DI\create(Customer::class),
    "employee_quotation" => \DI\create(EmployeeQuotation::class),
    "employee_quotation_detail" => \DI\create(EmployeeQuotationDetail::class),
    "setting" => \DI\create(Setting::class),
    "quotation_list" => \DI\create(QuotationList::class),
    "quotation_print" => \DI\create(QuotationPrint::class),
    "master_work_date" => \DI\create(MasterWorkDate::class),
    "timesheet_list" => \DI\create(TimesheetList::class),
    "welcome" => \DI\create(Welcome::class),

    // User table
    "usertable" => \DI\get("employee"),
];
