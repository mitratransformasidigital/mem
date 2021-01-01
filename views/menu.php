<?php

namespace MEM\prjMitralPHP;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(101, "mi_Top_10_Days", $MenuLanguage->MenuPhrase("101", "MenuText"), $MenuRelativePath . "top10days", -1, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}Top 10 Days'), false, false, "fa-chart-bar", "", false);
$sideMenu->addMenuItem(95, "mci_My_Activity", $MenuLanguage->MenuPhrase("95", "MenuText"), "", -1, "", true, false, true, "fa-tasks", "", false);
$sideMenu->addMenuItem(75, "mi_myprofile", $MenuLanguage->MenuPhrase("75", "MenuText"), $MenuRelativePath . "myprofilelist?cmd=resetall", 95, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}myprofile'), false, false, "", "", false);
$sideMenu->addMenuItem(96, "mi_myasset", $MenuLanguage->MenuPhrase("96", "MenuText"), $MenuRelativePath . "myassetlist?cmd=resetall", 95, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}myasset'), false, false, "", "", false);
$sideMenu->addMenuItem(97, "mi_mycontract", $MenuLanguage->MenuPhrase("97", "MenuText"), $MenuRelativePath . "mycontractlist?cmd=resetall", 95, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}mycontract'), false, false, "", "", false);
$sideMenu->addMenuItem(98, "mi_mytimesheet", $MenuLanguage->MenuPhrase("98", "MenuText"), $MenuRelativePath . "mytimesheetlist?cmd=resetall", 95, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}mytimesheet'), false, false, "", "", false);
$sideMenu->addMenuItem(99, "mi_mytraining", $MenuLanguage->MenuPhrase("99", "MenuText"), $MenuRelativePath . "mytraininglist?cmd=resetall", 95, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}mytraining'), false, false, "", "", false);
$sideMenu->addMenuItem(46, "mci_Transaction", $MenuLanguage->MenuPhrase("46", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "fa-calendar-alt", "", false);
$sideMenu->addMenuItem(47, "mi_permit", $MenuLanguage->MenuPhrase("47", "MenuText"), $MenuRelativePath . "permitlist?cmd=resetall", 46, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}permit'), false, false, "", "", false);
$sideMenu->addMenuItem(31, "mi_activity", $MenuLanguage->MenuPhrase("31", "MenuText"), $MenuRelativePath . "activitylist?cmd=resetall", 46, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}activity'), false, false, "", "", false);
$sideMenu->addMenuItem(72, "mi_employee_contract", $MenuLanguage->MenuPhrase("72", "MenuText"), $MenuRelativePath . "employeecontractlist?cmd=resetall", 46, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}employee_contract'), false, false, "", "", false);
$sideMenu->addMenuItem(73, "mi_employee_asset", $MenuLanguage->MenuPhrase("73", "MenuText"), $MenuRelativePath . "employeeassetlist?cmd=resetall", 46, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}employee_asset'), false, false, "", "", false);
$sideMenu->addMenuItem(74, "mi_employee_trainings", $MenuLanguage->MenuPhrase("74", "MenuText"), $MenuRelativePath . "employeetrainingslist?cmd=resetall", 46, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}employee_trainings'), false, false, "", "", false);
$sideMenu->addMenuItem(14, "mi_employee_timesheet", $MenuLanguage->MenuPhrase("14", "MenuText"), $MenuRelativePath . "employeetimesheetlist?cmd=resetall", 46, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}employee_timesheet'), false, false, "", "", false);
$sideMenu->addMenuItem(2, "mci_Master", $MenuLanguage->MenuPhrase("2", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "fa-list", "", false);
$sideMenu->addMenuItem(28, "mi_employee", $MenuLanguage->MenuPhrase("28", "MenuText"), $MenuRelativePath . "employeelist?cmd=resetall", 2, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}employee'), false, false, "", "", false);
$sideMenu->addMenuItem(26, "mci_Shift", $MenuLanguage->MenuPhrase("26", "MenuText"), "", 2, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(15, "mi_master_shift", $MenuLanguage->MenuPhrase("15", "MenuText"), $MenuRelativePath . "mastershiftlist", 26, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}master_shift'), false, false, "", "", false);
$sideMenu->addMenuItem(16, "mi_master_holiday", $MenuLanguage->MenuPhrase("16", "MenuText"), $MenuRelativePath . "masterholidaylist?cmd=resetall", 26, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}master_holiday'), false, false, "", "", false);
$sideMenu->addMenuItem(30, "mi_employee_shift", $MenuLanguage->MenuPhrase("30", "MenuText"), $MenuRelativePath . "employeeshiftlist?cmd=resetall", 26, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}employee_shift'), false, false, "", "", false);
$sideMenu->addMenuItem(11, "mci_Employee_Category", $MenuLanguage->MenuPhrase("11", "MenuText"), "", 2, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(6, "mi_master_position", $MenuLanguage->MenuPhrase("6", "MenuText"), $MenuRelativePath . "masterpositionlist", 11, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}master_position'), false, false, "", "", false);
$sideMenu->addMenuItem(7, "mi_master_skill", $MenuLanguage->MenuPhrase("7", "MenuText"), $MenuRelativePath . "masterskilllist", 11, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}master_skill'), false, false, "", "", false);
$sideMenu->addMenuItem(12, "mi_master_office", $MenuLanguage->MenuPhrase("12", "MenuText"), $MenuRelativePath . "masterofficelist?cmd=resetall", 11, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}master_office'), false, false, "", "", false);
$sideMenu->addMenuItem(13, "mi_master_status", $MenuLanguage->MenuPhrase("13", "MenuText"), $MenuRelativePath . "masterstatuslist", 11, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}master_status'), false, false, "", "", false);
$sideMenu->addMenuItem(5, "mci_Location", $MenuLanguage->MenuPhrase("5", "MenuText"), "", 2, "", IsLoggedIn(), false, true, "", "", false);
$sideMenu->addMenuItem(3, "mi_master_province", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "masterprovincelist", 5, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}master_province'), false, false, "", "", false);
$sideMenu->addMenuItem(1, "mi_master_city", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "mastercitylist?cmd=resetall", 5, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}master_city'), false, false, "", "", false);
$sideMenu->addMenuItem(69, "mci_Users_Management", $MenuLanguage->MenuPhrase("69", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "fa-users", "", false);
$sideMenu->addMenuItem(71, "mi_userlevels", $MenuLanguage->MenuPhrase("71", "MenuText"), $MenuRelativePath . "userlevelslist", 69, "", AllowListMenu('{83F75968-657B-46AA-B0FF-ABCCF02B6D71}userlevels'), false, false, "", "", false);
echo $sideMenu->toScript();
