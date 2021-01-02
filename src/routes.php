<?php

namespace MEM\prjMitralPHP;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Handle Routes
return function (App $app) {
    // employee
    $app->any('/employeelist[/{employee_username}]', EmployeeController::class . ':list')->add(PermissionMiddleware::class)->setName('employeelist-employee-list'); // list
    $app->any('/employeeadd[/{employee_username}]', EmployeeController::class . ':add')->add(PermissionMiddleware::class)->setName('employeeadd-employee-add'); // add
    $app->any('/employeeaddopt', EmployeeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('employeeaddopt-employee-addopt'); // addopt
    $app->any('/employeeview[/{employee_username}]', EmployeeController::class . ':view')->add(PermissionMiddleware::class)->setName('employeeview-employee-view'); // view
    $app->any('/employeeedit[/{employee_username}]', EmployeeController::class . ':edit')->add(PermissionMiddleware::class)->setName('employeeedit-employee-edit'); // edit
    $app->any('/employeeupdate', EmployeeController::class . ':update')->add(PermissionMiddleware::class)->setName('employeeupdate-employee-update'); // update
    $app->any('/employeedelete[/{employee_username}]', EmployeeController::class . ':delete')->add(PermissionMiddleware::class)->setName('employeedelete-employee-delete'); // delete
    $app->any('/employeesearch', EmployeeController::class . ':search')->add(PermissionMiddleware::class)->setName('employeesearch-employee-search'); // search
    $app->any('/employeepreview', EmployeeController::class . ':preview')->add(PermissionMiddleware::class)->setName('employeepreview-employee-preview'); // preview
    $app->group(
        '/employee',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{employee_username}]', EmployeeController::class . ':list')->add(PermissionMiddleware::class)->setName('employee/list-employee-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{employee_username}]', EmployeeController::class . ':add')->add(PermissionMiddleware::class)->setName('employee/add-employee-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', EmployeeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('employee/addopt-employee-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{employee_username}]', EmployeeController::class . ':view')->add(PermissionMiddleware::class)->setName('employee/view-employee-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{employee_username}]', EmployeeController::class . ':edit')->add(PermissionMiddleware::class)->setName('employee/edit-employee-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', EmployeeController::class . ':update')->add(PermissionMiddleware::class)->setName('employee/update-employee-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{employee_username}]', EmployeeController::class . ':delete')->add(PermissionMiddleware::class)->setName('employee/delete-employee-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', EmployeeController::class . ':search')->add(PermissionMiddleware::class)->setName('employee/search-employee-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', EmployeeController::class . ':preview')->add(PermissionMiddleware::class)->setName('employee/preview-employee-preview-2'); // preview
        }
    );

    // employee_asset
    $app->any('/employeeassetlist[/{asset_id}]', EmployeeAssetController::class . ':list')->add(PermissionMiddleware::class)->setName('employeeassetlist-employee_asset-list'); // list
    $app->any('/employeeassetadd[/{asset_id}]', EmployeeAssetController::class . ':add')->add(PermissionMiddleware::class)->setName('employeeassetadd-employee_asset-add'); // add
    $app->any('/employeeassetview[/{asset_id}]', EmployeeAssetController::class . ':view')->add(PermissionMiddleware::class)->setName('employeeassetview-employee_asset-view'); // view
    $app->any('/employeeassetedit[/{asset_id}]', EmployeeAssetController::class . ':edit')->add(PermissionMiddleware::class)->setName('employeeassetedit-employee_asset-edit'); // edit
    $app->any('/employeeassetupdate', EmployeeAssetController::class . ':update')->add(PermissionMiddleware::class)->setName('employeeassetupdate-employee_asset-update'); // update
    $app->any('/employeeassetdelete[/{asset_id}]', EmployeeAssetController::class . ':delete')->add(PermissionMiddleware::class)->setName('employeeassetdelete-employee_asset-delete'); // delete
    $app->any('/employeeassetsearch', EmployeeAssetController::class . ':search')->add(PermissionMiddleware::class)->setName('employeeassetsearch-employee_asset-search'); // search
    $app->any('/employeeassetpreview', EmployeeAssetController::class . ':preview')->add(PermissionMiddleware::class)->setName('employeeassetpreview-employee_asset-preview'); // preview
    $app->group(
        '/employee_asset',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{asset_id}]', EmployeeAssetController::class . ':list')->add(PermissionMiddleware::class)->setName('employee_asset/list-employee_asset-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{asset_id}]', EmployeeAssetController::class . ':add')->add(PermissionMiddleware::class)->setName('employee_asset/add-employee_asset-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{asset_id}]', EmployeeAssetController::class . ':view')->add(PermissionMiddleware::class)->setName('employee_asset/view-employee_asset-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{asset_id}]', EmployeeAssetController::class . ':edit')->add(PermissionMiddleware::class)->setName('employee_asset/edit-employee_asset-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', EmployeeAssetController::class . ':update')->add(PermissionMiddleware::class)->setName('employee_asset/update-employee_asset-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{asset_id}]', EmployeeAssetController::class . ':delete')->add(PermissionMiddleware::class)->setName('employee_asset/delete-employee_asset-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', EmployeeAssetController::class . ':search')->add(PermissionMiddleware::class)->setName('employee_asset/search-employee_asset-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', EmployeeAssetController::class . ':preview')->add(PermissionMiddleware::class)->setName('employee_asset/preview-employee_asset-preview-2'); // preview
        }
    );

    // employee_contract
    $app->any('/employeecontractlist[/{contract_id}]', EmployeeContractController::class . ':list')->add(PermissionMiddleware::class)->setName('employeecontractlist-employee_contract-list'); // list
    $app->any('/employeecontractadd[/{contract_id}]', EmployeeContractController::class . ':add')->add(PermissionMiddleware::class)->setName('employeecontractadd-employee_contract-add'); // add
    $app->any('/employeecontractview[/{contract_id}]', EmployeeContractController::class . ':view')->add(PermissionMiddleware::class)->setName('employeecontractview-employee_contract-view'); // view
    $app->any('/employeecontractedit[/{contract_id}]', EmployeeContractController::class . ':edit')->add(PermissionMiddleware::class)->setName('employeecontractedit-employee_contract-edit'); // edit
    $app->any('/employeecontractupdate', EmployeeContractController::class . ':update')->add(PermissionMiddleware::class)->setName('employeecontractupdate-employee_contract-update'); // update
    $app->any('/employeecontractdelete[/{contract_id}]', EmployeeContractController::class . ':delete')->add(PermissionMiddleware::class)->setName('employeecontractdelete-employee_contract-delete'); // delete
    $app->any('/employeecontractsearch', EmployeeContractController::class . ':search')->add(PermissionMiddleware::class)->setName('employeecontractsearch-employee_contract-search'); // search
    $app->any('/employeecontractpreview', EmployeeContractController::class . ':preview')->add(PermissionMiddleware::class)->setName('employeecontractpreview-employee_contract-preview'); // preview
    $app->group(
        '/employee_contract',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{contract_id}]', EmployeeContractController::class . ':list')->add(PermissionMiddleware::class)->setName('employee_contract/list-employee_contract-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{contract_id}]', EmployeeContractController::class . ':add')->add(PermissionMiddleware::class)->setName('employee_contract/add-employee_contract-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{contract_id}]', EmployeeContractController::class . ':view')->add(PermissionMiddleware::class)->setName('employee_contract/view-employee_contract-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{contract_id}]', EmployeeContractController::class . ':edit')->add(PermissionMiddleware::class)->setName('employee_contract/edit-employee_contract-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', EmployeeContractController::class . ':update')->add(PermissionMiddleware::class)->setName('employee_contract/update-employee_contract-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{contract_id}]', EmployeeContractController::class . ':delete')->add(PermissionMiddleware::class)->setName('employee_contract/delete-employee_contract-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', EmployeeContractController::class . ':search')->add(PermissionMiddleware::class)->setName('employee_contract/search-employee_contract-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', EmployeeContractController::class . ':preview')->add(PermissionMiddleware::class)->setName('employee_contract/preview-employee_contract-preview-2'); // preview
        }
    );

    // employee_timesheet
    $app->any('/employeetimesheetlist[/{timesheet_id}]', EmployeeTimesheetController::class . ':list')->add(PermissionMiddleware::class)->setName('employeetimesheetlist-employee_timesheet-list'); // list
    $app->any('/employeetimesheetadd[/{timesheet_id}]', EmployeeTimesheetController::class . ':add')->add(PermissionMiddleware::class)->setName('employeetimesheetadd-employee_timesheet-add'); // add
    $app->any('/employeetimesheetview[/{timesheet_id}]', EmployeeTimesheetController::class . ':view')->add(PermissionMiddleware::class)->setName('employeetimesheetview-employee_timesheet-view'); // view
    $app->any('/employeetimesheetedit[/{timesheet_id}]', EmployeeTimesheetController::class . ':edit')->add(PermissionMiddleware::class)->setName('employeetimesheetedit-employee_timesheet-edit'); // edit
    $app->any('/employeetimesheetupdate', EmployeeTimesheetController::class . ':update')->add(PermissionMiddleware::class)->setName('employeetimesheetupdate-employee_timesheet-update'); // update
    $app->any('/employeetimesheetdelete[/{timesheet_id}]', EmployeeTimesheetController::class . ':delete')->add(PermissionMiddleware::class)->setName('employeetimesheetdelete-employee_timesheet-delete'); // delete
    $app->any('/employeetimesheetsearch', EmployeeTimesheetController::class . ':search')->add(PermissionMiddleware::class)->setName('employeetimesheetsearch-employee_timesheet-search'); // search
    $app->any('/employeetimesheetpreview', EmployeeTimesheetController::class . ':preview')->add(PermissionMiddleware::class)->setName('employeetimesheetpreview-employee_timesheet-preview'); // preview
    $app->group(
        '/employee_timesheet',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{timesheet_id}]', EmployeeTimesheetController::class . ':list')->add(PermissionMiddleware::class)->setName('employee_timesheet/list-employee_timesheet-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{timesheet_id}]', EmployeeTimesheetController::class . ':add')->add(PermissionMiddleware::class)->setName('employee_timesheet/add-employee_timesheet-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{timesheet_id}]', EmployeeTimesheetController::class . ':view')->add(PermissionMiddleware::class)->setName('employee_timesheet/view-employee_timesheet-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{timesheet_id}]', EmployeeTimesheetController::class . ':edit')->add(PermissionMiddleware::class)->setName('employee_timesheet/edit-employee_timesheet-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', EmployeeTimesheetController::class . ':update')->add(PermissionMiddleware::class)->setName('employee_timesheet/update-employee_timesheet-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{timesheet_id}]', EmployeeTimesheetController::class . ':delete')->add(PermissionMiddleware::class)->setName('employee_timesheet/delete-employee_timesheet-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', EmployeeTimesheetController::class . ':search')->add(PermissionMiddleware::class)->setName('employee_timesheet/search-employee_timesheet-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', EmployeeTimesheetController::class . ':preview')->add(PermissionMiddleware::class)->setName('employee_timesheet/preview-employee_timesheet-preview-2'); // preview
        }
    );

    // master_city
    $app->any('/mastercitylist[/{city_id}]', MasterCityController::class . ':list')->add(PermissionMiddleware::class)->setName('mastercitylist-master_city-list'); // list
    $app->any('/mastercityadd[/{city_id}]', MasterCityController::class . ':add')->add(PermissionMiddleware::class)->setName('mastercityadd-master_city-add'); // add
    $app->any('/mastercityaddopt', MasterCityController::class . ':addopt')->add(PermissionMiddleware::class)->setName('mastercityaddopt-master_city-addopt'); // addopt
    $app->any('/mastercityview[/{city_id}]', MasterCityController::class . ':view')->add(PermissionMiddleware::class)->setName('mastercityview-master_city-view'); // view
    $app->any('/mastercityedit[/{city_id}]', MasterCityController::class . ':edit')->add(PermissionMiddleware::class)->setName('mastercityedit-master_city-edit'); // edit
    $app->any('/mastercityupdate', MasterCityController::class . ':update')->add(PermissionMiddleware::class)->setName('mastercityupdate-master_city-update'); // update
    $app->any('/mastercitydelete[/{city_id}]', MasterCityController::class . ':delete')->add(PermissionMiddleware::class)->setName('mastercitydelete-master_city-delete'); // delete
    $app->any('/mastercitysearch', MasterCityController::class . ':search')->add(PermissionMiddleware::class)->setName('mastercitysearch-master_city-search'); // search
    $app->any('/mastercitypreview', MasterCityController::class . ':preview')->add(PermissionMiddleware::class)->setName('mastercitypreview-master_city-preview'); // preview
    $app->group(
        '/master_city',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{city_id}]', MasterCityController::class . ':list')->add(PermissionMiddleware::class)->setName('master_city/list-master_city-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{city_id}]', MasterCityController::class . ':add')->add(PermissionMiddleware::class)->setName('master_city/add-master_city-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', MasterCityController::class . ':addopt')->add(PermissionMiddleware::class)->setName('master_city/addopt-master_city-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{city_id}]', MasterCityController::class . ':view')->add(PermissionMiddleware::class)->setName('master_city/view-master_city-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{city_id}]', MasterCityController::class . ':edit')->add(PermissionMiddleware::class)->setName('master_city/edit-master_city-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MasterCityController::class . ':update')->add(PermissionMiddleware::class)->setName('master_city/update-master_city-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{city_id}]', MasterCityController::class . ':delete')->add(PermissionMiddleware::class)->setName('master_city/delete-master_city-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MasterCityController::class . ':search')->add(PermissionMiddleware::class)->setName('master_city/search-master_city-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', MasterCityController::class . ':preview')->add(PermissionMiddleware::class)->setName('master_city/preview-master_city-preview-2'); // preview
        }
    );

    // master_office
    $app->any('/masterofficelist[/{office_id}]', MasterOfficeController::class . ':list')->add(PermissionMiddleware::class)->setName('masterofficelist-master_office-list'); // list
    $app->any('/masterofficeadd[/{office_id}]', MasterOfficeController::class . ':add')->add(PermissionMiddleware::class)->setName('masterofficeadd-master_office-add'); // add
    $app->any('/masterofficeaddopt', MasterOfficeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('masterofficeaddopt-master_office-addopt'); // addopt
    $app->any('/masterofficeview[/{office_id}]', MasterOfficeController::class . ':view')->add(PermissionMiddleware::class)->setName('masterofficeview-master_office-view'); // view
    $app->any('/masterofficeedit[/{office_id}]', MasterOfficeController::class . ':edit')->add(PermissionMiddleware::class)->setName('masterofficeedit-master_office-edit'); // edit
    $app->any('/masterofficeupdate', MasterOfficeController::class . ':update')->add(PermissionMiddleware::class)->setName('masterofficeupdate-master_office-update'); // update
    $app->any('/masterofficedelete[/{office_id}]', MasterOfficeController::class . ':delete')->add(PermissionMiddleware::class)->setName('masterofficedelete-master_office-delete'); // delete
    $app->any('/masterofficesearch', MasterOfficeController::class . ':search')->add(PermissionMiddleware::class)->setName('masterofficesearch-master_office-search'); // search
    $app->any('/masterofficepreview', MasterOfficeController::class . ':preview')->add(PermissionMiddleware::class)->setName('masterofficepreview-master_office-preview'); // preview
    $app->group(
        '/master_office',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{office_id}]', MasterOfficeController::class . ':list')->add(PermissionMiddleware::class)->setName('master_office/list-master_office-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{office_id}]', MasterOfficeController::class . ':add')->add(PermissionMiddleware::class)->setName('master_office/add-master_office-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', MasterOfficeController::class . ':addopt')->add(PermissionMiddleware::class)->setName('master_office/addopt-master_office-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{office_id}]', MasterOfficeController::class . ':view')->add(PermissionMiddleware::class)->setName('master_office/view-master_office-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{office_id}]', MasterOfficeController::class . ':edit')->add(PermissionMiddleware::class)->setName('master_office/edit-master_office-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MasterOfficeController::class . ':update')->add(PermissionMiddleware::class)->setName('master_office/update-master_office-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{office_id}]', MasterOfficeController::class . ':delete')->add(PermissionMiddleware::class)->setName('master_office/delete-master_office-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MasterOfficeController::class . ':search')->add(PermissionMiddleware::class)->setName('master_office/search-master_office-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', MasterOfficeController::class . ':preview')->add(PermissionMiddleware::class)->setName('master_office/preview-master_office-preview-2'); // preview
        }
    );

    // master_position
    $app->any('/masterpositionlist[/{position_id}]', MasterPositionController::class . ':list')->add(PermissionMiddleware::class)->setName('masterpositionlist-master_position-list'); // list
    $app->any('/masterpositionadd[/{position_id}]', MasterPositionController::class . ':add')->add(PermissionMiddleware::class)->setName('masterpositionadd-master_position-add'); // add
    $app->any('/masterpositionaddopt', MasterPositionController::class . ':addopt')->add(PermissionMiddleware::class)->setName('masterpositionaddopt-master_position-addopt'); // addopt
    $app->any('/masterpositionview[/{position_id}]', MasterPositionController::class . ':view')->add(PermissionMiddleware::class)->setName('masterpositionview-master_position-view'); // view
    $app->any('/masterpositionedit[/{position_id}]', MasterPositionController::class . ':edit')->add(PermissionMiddleware::class)->setName('masterpositionedit-master_position-edit'); // edit
    $app->any('/masterpositionupdate', MasterPositionController::class . ':update')->add(PermissionMiddleware::class)->setName('masterpositionupdate-master_position-update'); // update
    $app->any('/masterpositiondelete[/{position_id}]', MasterPositionController::class . ':delete')->add(PermissionMiddleware::class)->setName('masterpositiondelete-master_position-delete'); // delete
    $app->any('/masterpositionsearch', MasterPositionController::class . ':search')->add(PermissionMiddleware::class)->setName('masterpositionsearch-master_position-search'); // search
    $app->group(
        '/master_position',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{position_id}]', MasterPositionController::class . ':list')->add(PermissionMiddleware::class)->setName('master_position/list-master_position-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{position_id}]', MasterPositionController::class . ':add')->add(PermissionMiddleware::class)->setName('master_position/add-master_position-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', MasterPositionController::class . ':addopt')->add(PermissionMiddleware::class)->setName('master_position/addopt-master_position-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{position_id}]', MasterPositionController::class . ':view')->add(PermissionMiddleware::class)->setName('master_position/view-master_position-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{position_id}]', MasterPositionController::class . ':edit')->add(PermissionMiddleware::class)->setName('master_position/edit-master_position-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MasterPositionController::class . ':update')->add(PermissionMiddleware::class)->setName('master_position/update-master_position-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{position_id}]', MasterPositionController::class . ':delete')->add(PermissionMiddleware::class)->setName('master_position/delete-master_position-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MasterPositionController::class . ':search')->add(PermissionMiddleware::class)->setName('master_position/search-master_position-search-2'); // search
        }
    );

    // master_skill
    $app->any('/masterskilllist[/{skill_id}]', MasterSkillController::class . ':list')->add(PermissionMiddleware::class)->setName('masterskilllist-master_skill-list'); // list
    $app->any('/masterskilladd[/{skill_id}]', MasterSkillController::class . ':add')->add(PermissionMiddleware::class)->setName('masterskilladd-master_skill-add'); // add
    $app->any('/masterskilladdopt', MasterSkillController::class . ':addopt')->add(PermissionMiddleware::class)->setName('masterskilladdopt-master_skill-addopt'); // addopt
    $app->any('/masterskillview[/{skill_id}]', MasterSkillController::class . ':view')->add(PermissionMiddleware::class)->setName('masterskillview-master_skill-view'); // view
    $app->any('/masterskilledit[/{skill_id}]', MasterSkillController::class . ':edit')->add(PermissionMiddleware::class)->setName('masterskilledit-master_skill-edit'); // edit
    $app->any('/masterskillupdate', MasterSkillController::class . ':update')->add(PermissionMiddleware::class)->setName('masterskillupdate-master_skill-update'); // update
    $app->any('/masterskilldelete[/{skill_id}]', MasterSkillController::class . ':delete')->add(PermissionMiddleware::class)->setName('masterskilldelete-master_skill-delete'); // delete
    $app->any('/masterskillsearch', MasterSkillController::class . ':search')->add(PermissionMiddleware::class)->setName('masterskillsearch-master_skill-search'); // search
    $app->group(
        '/master_skill',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{skill_id}]', MasterSkillController::class . ':list')->add(PermissionMiddleware::class)->setName('master_skill/list-master_skill-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{skill_id}]', MasterSkillController::class . ':add')->add(PermissionMiddleware::class)->setName('master_skill/add-master_skill-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', MasterSkillController::class . ':addopt')->add(PermissionMiddleware::class)->setName('master_skill/addopt-master_skill-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{skill_id}]', MasterSkillController::class . ':view')->add(PermissionMiddleware::class)->setName('master_skill/view-master_skill-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{skill_id}]', MasterSkillController::class . ':edit')->add(PermissionMiddleware::class)->setName('master_skill/edit-master_skill-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MasterSkillController::class . ':update')->add(PermissionMiddleware::class)->setName('master_skill/update-master_skill-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{skill_id}]', MasterSkillController::class . ':delete')->add(PermissionMiddleware::class)->setName('master_skill/delete-master_skill-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MasterSkillController::class . ':search')->add(PermissionMiddleware::class)->setName('master_skill/search-master_skill-search-2'); // search
        }
    );

    // master_status
    $app->any('/masterstatuslist[/{status_id}]', MasterStatusController::class . ':list')->add(PermissionMiddleware::class)->setName('masterstatuslist-master_status-list'); // list
    $app->any('/masterstatusadd[/{status_id}]', MasterStatusController::class . ':add')->add(PermissionMiddleware::class)->setName('masterstatusadd-master_status-add'); // add
    $app->any('/masterstatusaddopt', MasterStatusController::class . ':addopt')->add(PermissionMiddleware::class)->setName('masterstatusaddopt-master_status-addopt'); // addopt
    $app->any('/masterstatusview[/{status_id}]', MasterStatusController::class . ':view')->add(PermissionMiddleware::class)->setName('masterstatusview-master_status-view'); // view
    $app->any('/masterstatusedit[/{status_id}]', MasterStatusController::class . ':edit')->add(PermissionMiddleware::class)->setName('masterstatusedit-master_status-edit'); // edit
    $app->any('/masterstatusupdate', MasterStatusController::class . ':update')->add(PermissionMiddleware::class)->setName('masterstatusupdate-master_status-update'); // update
    $app->any('/masterstatusdelete[/{status_id}]', MasterStatusController::class . ':delete')->add(PermissionMiddleware::class)->setName('masterstatusdelete-master_status-delete'); // delete
    $app->any('/masterstatussearch', MasterStatusController::class . ':search')->add(PermissionMiddleware::class)->setName('masterstatussearch-master_status-search'); // search
    $app->group(
        '/master_status',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{status_id}]', MasterStatusController::class . ':list')->add(PermissionMiddleware::class)->setName('master_status/list-master_status-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{status_id}]', MasterStatusController::class . ':add')->add(PermissionMiddleware::class)->setName('master_status/add-master_status-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', MasterStatusController::class . ':addopt')->add(PermissionMiddleware::class)->setName('master_status/addopt-master_status-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{status_id}]', MasterStatusController::class . ':view')->add(PermissionMiddleware::class)->setName('master_status/view-master_status-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{status_id}]', MasterStatusController::class . ':edit')->add(PermissionMiddleware::class)->setName('master_status/edit-master_status-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MasterStatusController::class . ':update')->add(PermissionMiddleware::class)->setName('master_status/update-master_status-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{status_id}]', MasterStatusController::class . ':delete')->add(PermissionMiddleware::class)->setName('master_status/delete-master_status-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MasterStatusController::class . ':search')->add(PermissionMiddleware::class)->setName('master_status/search-master_status-search-2'); // search
        }
    );

    // master_province
    $app->any('/masterprovincelist[/{province_id}]', MasterProvinceController::class . ':list')->add(PermissionMiddleware::class)->setName('masterprovincelist-master_province-list'); // list
    $app->any('/masterprovinceadd[/{province_id}]', MasterProvinceController::class . ':add')->add(PermissionMiddleware::class)->setName('masterprovinceadd-master_province-add'); // add
    $app->any('/masterprovinceaddopt', MasterProvinceController::class . ':addopt')->add(PermissionMiddleware::class)->setName('masterprovinceaddopt-master_province-addopt'); // addopt
    $app->any('/masterprovinceview[/{province_id}]', MasterProvinceController::class . ':view')->add(PermissionMiddleware::class)->setName('masterprovinceview-master_province-view'); // view
    $app->any('/masterprovinceedit[/{province_id}]', MasterProvinceController::class . ':edit')->add(PermissionMiddleware::class)->setName('masterprovinceedit-master_province-edit'); // edit
    $app->any('/masterprovinceupdate', MasterProvinceController::class . ':update')->add(PermissionMiddleware::class)->setName('masterprovinceupdate-master_province-update'); // update
    $app->any('/masterprovincedelete[/{province_id}]', MasterProvinceController::class . ':delete')->add(PermissionMiddleware::class)->setName('masterprovincedelete-master_province-delete'); // delete
    $app->any('/masterprovincesearch', MasterProvinceController::class . ':search')->add(PermissionMiddleware::class)->setName('masterprovincesearch-master_province-search'); // search
    $app->group(
        '/master_province',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{province_id}]', MasterProvinceController::class . ':list')->add(PermissionMiddleware::class)->setName('master_province/list-master_province-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{province_id}]', MasterProvinceController::class . ':add')->add(PermissionMiddleware::class)->setName('master_province/add-master_province-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', MasterProvinceController::class . ':addopt')->add(PermissionMiddleware::class)->setName('master_province/addopt-master_province-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{province_id}]', MasterProvinceController::class . ':view')->add(PermissionMiddleware::class)->setName('master_province/view-master_province-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{province_id}]', MasterProvinceController::class . ':edit')->add(PermissionMiddleware::class)->setName('master_province/edit-master_province-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MasterProvinceController::class . ':update')->add(PermissionMiddleware::class)->setName('master_province/update-master_province-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{province_id}]', MasterProvinceController::class . ':delete')->add(PermissionMiddleware::class)->setName('master_province/delete-master_province-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MasterProvinceController::class . ':search')->add(PermissionMiddleware::class)->setName('master_province/search-master_province-search-2'); // search
        }
    );

    // master_shift
    $app->any('/mastershiftlist[/{shift_id}]', MasterShiftController::class . ':list')->add(PermissionMiddleware::class)->setName('mastershiftlist-master_shift-list'); // list
    $app->any('/mastershiftadd[/{shift_id}]', MasterShiftController::class . ':add')->add(PermissionMiddleware::class)->setName('mastershiftadd-master_shift-add'); // add
    $app->any('/mastershiftaddopt', MasterShiftController::class . ':addopt')->add(PermissionMiddleware::class)->setName('mastershiftaddopt-master_shift-addopt'); // addopt
    $app->any('/mastershiftview[/{shift_id}]', MasterShiftController::class . ':view')->add(PermissionMiddleware::class)->setName('mastershiftview-master_shift-view'); // view
    $app->any('/mastershiftedit[/{shift_id}]', MasterShiftController::class . ':edit')->add(PermissionMiddleware::class)->setName('mastershiftedit-master_shift-edit'); // edit
    $app->any('/mastershiftupdate', MasterShiftController::class . ':update')->add(PermissionMiddleware::class)->setName('mastershiftupdate-master_shift-update'); // update
    $app->any('/mastershiftdelete[/{shift_id}]', MasterShiftController::class . ':delete')->add(PermissionMiddleware::class)->setName('mastershiftdelete-master_shift-delete'); // delete
    $app->any('/mastershiftsearch', MasterShiftController::class . ':search')->add(PermissionMiddleware::class)->setName('mastershiftsearch-master_shift-search'); // search
    $app->group(
        '/master_shift',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{shift_id}]', MasterShiftController::class . ':list')->add(PermissionMiddleware::class)->setName('master_shift/list-master_shift-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{shift_id}]', MasterShiftController::class . ':add')->add(PermissionMiddleware::class)->setName('master_shift/add-master_shift-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', MasterShiftController::class . ':addopt')->add(PermissionMiddleware::class)->setName('master_shift/addopt-master_shift-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{shift_id}]', MasterShiftController::class . ':view')->add(PermissionMiddleware::class)->setName('master_shift/view-master_shift-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{shift_id}]', MasterShiftController::class . ':edit')->add(PermissionMiddleware::class)->setName('master_shift/edit-master_shift-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MasterShiftController::class . ':update')->add(PermissionMiddleware::class)->setName('master_shift/update-master_shift-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{shift_id}]', MasterShiftController::class . ':delete')->add(PermissionMiddleware::class)->setName('master_shift/delete-master_shift-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MasterShiftController::class . ':search')->add(PermissionMiddleware::class)->setName('master_shift/search-master_shift-search-2'); // search
        }
    );

    // master_holiday
    $app->any('/masterholidaylist[/{holiday_id}]', MasterHolidayController::class . ':list')->add(PermissionMiddleware::class)->setName('masterholidaylist-master_holiday-list'); // list
    $app->any('/masterholidayadd[/{holiday_id}]', MasterHolidayController::class . ':add')->add(PermissionMiddleware::class)->setName('masterholidayadd-master_holiday-add'); // add
    $app->any('/masterholidayview[/{holiday_id}]', MasterHolidayController::class . ':view')->add(PermissionMiddleware::class)->setName('masterholidayview-master_holiday-view'); // view
    $app->any('/masterholidayedit[/{holiday_id}]', MasterHolidayController::class . ':edit')->add(PermissionMiddleware::class)->setName('masterholidayedit-master_holiday-edit'); // edit
    $app->any('/masterholidayupdate', MasterHolidayController::class . ':update')->add(PermissionMiddleware::class)->setName('masterholidayupdate-master_holiday-update'); // update
    $app->any('/masterholidaydelete[/{holiday_id}]', MasterHolidayController::class . ':delete')->add(PermissionMiddleware::class)->setName('masterholidaydelete-master_holiday-delete'); // delete
    $app->any('/masterholidaysearch', MasterHolidayController::class . ':search')->add(PermissionMiddleware::class)->setName('masterholidaysearch-master_holiday-search'); // search
    $app->any('/masterholidaypreview', MasterHolidayController::class . ':preview')->add(PermissionMiddleware::class)->setName('masterholidaypreview-master_holiday-preview'); // preview
    $app->group(
        '/master_holiday',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{holiday_id}]', MasterHolidayController::class . ':list')->add(PermissionMiddleware::class)->setName('master_holiday/list-master_holiday-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{holiday_id}]', MasterHolidayController::class . ':add')->add(PermissionMiddleware::class)->setName('master_holiday/add-master_holiday-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{holiday_id}]', MasterHolidayController::class . ':view')->add(PermissionMiddleware::class)->setName('master_holiday/view-master_holiday-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{holiday_id}]', MasterHolidayController::class . ':edit')->add(PermissionMiddleware::class)->setName('master_holiday/edit-master_holiday-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MasterHolidayController::class . ':update')->add(PermissionMiddleware::class)->setName('master_holiday/update-master_holiday-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{holiday_id}]', MasterHolidayController::class . ':delete')->add(PermissionMiddleware::class)->setName('master_holiday/delete-master_holiday-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MasterHolidayController::class . ':search')->add(PermissionMiddleware::class)->setName('master_holiday/search-master_holiday-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', MasterHolidayController::class . ':preview')->add(PermissionMiddleware::class)->setName('master_holiday/preview-master_holiday-preview-2'); // preview
        }
    );

    // employee_shift
    $app->any('/employeeshiftlist[/{es_id}]', EmployeeShiftController::class . ':list')->add(PermissionMiddleware::class)->setName('employeeshiftlist-employee_shift-list'); // list
    $app->any('/employeeshiftadd[/{es_id}]', EmployeeShiftController::class . ':add')->add(PermissionMiddleware::class)->setName('employeeshiftadd-employee_shift-add'); // add
    $app->any('/employeeshiftview[/{es_id}]', EmployeeShiftController::class . ':view')->add(PermissionMiddleware::class)->setName('employeeshiftview-employee_shift-view'); // view
    $app->any('/employeeshiftedit[/{es_id}]', EmployeeShiftController::class . ':edit')->add(PermissionMiddleware::class)->setName('employeeshiftedit-employee_shift-edit'); // edit
    $app->any('/employeeshiftupdate', EmployeeShiftController::class . ':update')->add(PermissionMiddleware::class)->setName('employeeshiftupdate-employee_shift-update'); // update
    $app->any('/employeeshiftdelete[/{es_id}]', EmployeeShiftController::class . ':delete')->add(PermissionMiddleware::class)->setName('employeeshiftdelete-employee_shift-delete'); // delete
    $app->any('/employeeshiftsearch', EmployeeShiftController::class . ':search')->add(PermissionMiddleware::class)->setName('employeeshiftsearch-employee_shift-search'); // search
    $app->any('/employeeshiftpreview', EmployeeShiftController::class . ':preview')->add(PermissionMiddleware::class)->setName('employeeshiftpreview-employee_shift-preview'); // preview
    $app->group(
        '/employee_shift',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{es_id}]', EmployeeShiftController::class . ':list')->add(PermissionMiddleware::class)->setName('employee_shift/list-employee_shift-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{es_id}]', EmployeeShiftController::class . ':add')->add(PermissionMiddleware::class)->setName('employee_shift/add-employee_shift-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{es_id}]', EmployeeShiftController::class . ':view')->add(PermissionMiddleware::class)->setName('employee_shift/view-employee_shift-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{es_id}]', EmployeeShiftController::class . ':edit')->add(PermissionMiddleware::class)->setName('employee_shift/edit-employee_shift-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', EmployeeShiftController::class . ':update')->add(PermissionMiddleware::class)->setName('employee_shift/update-employee_shift-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{es_id}]', EmployeeShiftController::class . ':delete')->add(PermissionMiddleware::class)->setName('employee_shift/delete-employee_shift-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', EmployeeShiftController::class . ':search')->add(PermissionMiddleware::class)->setName('employee_shift/search-employee_shift-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', EmployeeShiftController::class . ':preview')->add(PermissionMiddleware::class)->setName('employee_shift/preview-employee_shift-preview-2'); // preview
        }
    );

    // activity
    $app->any('/activitylist[/{activity_id}]', ActivityController::class . ':list')->add(PermissionMiddleware::class)->setName('activitylist-activity-list'); // list
    $app->any('/activityadd[/{activity_id}]', ActivityController::class . ':add')->add(PermissionMiddleware::class)->setName('activityadd-activity-add'); // add
    $app->any('/activityview[/{activity_id}]', ActivityController::class . ':view')->add(PermissionMiddleware::class)->setName('activityview-activity-view'); // view
    $app->any('/activityedit[/{activity_id}]', ActivityController::class . ':edit')->add(PermissionMiddleware::class)->setName('activityedit-activity-edit'); // edit
    $app->any('/activityupdate', ActivityController::class . ':update')->add(PermissionMiddleware::class)->setName('activityupdate-activity-update'); // update
    $app->any('/activitydelete[/{activity_id}]', ActivityController::class . ':delete')->add(PermissionMiddleware::class)->setName('activitydelete-activity-delete'); // delete
    $app->any('/activitysearch', ActivityController::class . ':search')->add(PermissionMiddleware::class)->setName('activitysearch-activity-search'); // search
    $app->any('/activitypreview', ActivityController::class . ':preview')->add(PermissionMiddleware::class)->setName('activitypreview-activity-preview'); // preview
    $app->group(
        '/activity',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{activity_id}]', ActivityController::class . ':list')->add(PermissionMiddleware::class)->setName('activity/list-activity-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{activity_id}]', ActivityController::class . ':add')->add(PermissionMiddleware::class)->setName('activity/add-activity-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{activity_id}]', ActivityController::class . ':view')->add(PermissionMiddleware::class)->setName('activity/view-activity-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{activity_id}]', ActivityController::class . ':edit')->add(PermissionMiddleware::class)->setName('activity/edit-activity-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', ActivityController::class . ':update')->add(PermissionMiddleware::class)->setName('activity/update-activity-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{activity_id}]', ActivityController::class . ':delete')->add(PermissionMiddleware::class)->setName('activity/delete-activity-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', ActivityController::class . ':search')->add(PermissionMiddleware::class)->setName('activity/search-activity-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', ActivityController::class . ':preview')->add(PermissionMiddleware::class)->setName('activity/preview-activity-preview-2'); // preview
        }
    );

    // permit
    $app->any('/permitlist[/{permit_id}]', PermitController::class . ':list')->add(PermissionMiddleware::class)->setName('permitlist-permit-list'); // list
    $app->any('/permitadd[/{permit_id}]', PermitController::class . ':add')->add(PermissionMiddleware::class)->setName('permitadd-permit-add'); // add
    $app->any('/permitview[/{permit_id}]', PermitController::class . ':view')->add(PermissionMiddleware::class)->setName('permitview-permit-view'); // view
    $app->any('/permitedit[/{permit_id}]', PermitController::class . ':edit')->add(PermissionMiddleware::class)->setName('permitedit-permit-edit'); // edit
    $app->any('/permitupdate', PermitController::class . ':update')->add(PermissionMiddleware::class)->setName('permitupdate-permit-update'); // update
    $app->any('/permitdelete[/{permit_id}]', PermitController::class . ':delete')->add(PermissionMiddleware::class)->setName('permitdelete-permit-delete'); // delete
    $app->any('/permitsearch', PermitController::class . ':search')->add(PermissionMiddleware::class)->setName('permitsearch-permit-search'); // search
    $app->any('/permitpreview', PermitController::class . ':preview')->add(PermissionMiddleware::class)->setName('permitpreview-permit-preview'); // preview
    $app->group(
        '/permit',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{permit_id}]', PermitController::class . ':list')->add(PermissionMiddleware::class)->setName('permit/list-permit-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{permit_id}]', PermitController::class . ':add')->add(PermissionMiddleware::class)->setName('permit/add-permit-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{permit_id}]', PermitController::class . ':view')->add(PermissionMiddleware::class)->setName('permit/view-permit-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{permit_id}]', PermitController::class . ':edit')->add(PermissionMiddleware::class)->setName('permit/edit-permit-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', PermitController::class . ':update')->add(PermissionMiddleware::class)->setName('permit/update-permit-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{permit_id}]', PermitController::class . ':delete')->add(PermissionMiddleware::class)->setName('permit/delete-permit-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', PermitController::class . ':search')->add(PermissionMiddleware::class)->setName('permit/search-permit-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', PermitController::class . ':preview')->add(PermissionMiddleware::class)->setName('permit/preview-permit-preview-2'); // preview
        }
    );

    // userlevelpermissions
    $app->any('/userlevelpermissionslist[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelpermissionslist-userlevelpermissions-list'); // list
    $app->any('/userlevelpermissionsadd[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelpermissionsadd-userlevelpermissions-add'); // add
    $app->any('/userlevelpermissionsview[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelpermissionsview-userlevelpermissions-view'); // view
    $app->any('/userlevelpermissionsedit[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelpermissionsedit-userlevelpermissions-edit'); // edit
    $app->any('/userlevelpermissionsupdate', UserlevelpermissionsController::class . ':update')->add(PermissionMiddleware::class)->setName('userlevelpermissionsupdate-userlevelpermissions-update'); // update
    $app->any('/userlevelpermissionsdelete[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelpermissionsdelete-userlevelpermissions-delete'); // delete
    $app->group(
        '/userlevelpermissions',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelpermissions/list-userlevelpermissions-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelpermissions/add-userlevelpermissions-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelpermissions/view-userlevelpermissions-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelpermissions/edit-userlevelpermissions-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', UserlevelpermissionsController::class . ':update')->add(PermissionMiddleware::class)->setName('userlevelpermissions/update-userlevelpermissions-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{userlevelid}/{_tablename}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelpermissions/delete-userlevelpermissions-delete-2'); // delete
        }
    );

    // userlevels
    $app->any('/userlevelslist[/{userlevelid}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelslist-userlevels-list'); // list
    $app->any('/userlevelsadd[/{userlevelid}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelsadd-userlevels-add'); // add
    $app->any('/userlevelsaddopt', UserlevelsController::class . ':addopt')->add(PermissionMiddleware::class)->setName('userlevelsaddopt-userlevels-addopt'); // addopt
    $app->any('/userlevelsview[/{userlevelid}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelsview-userlevels-view'); // view
    $app->any('/userlevelsedit[/{userlevelid}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelsedit-userlevels-edit'); // edit
    $app->any('/userlevelsupdate', UserlevelsController::class . ':update')->add(PermissionMiddleware::class)->setName('userlevelsupdate-userlevels-update'); // update
    $app->any('/userlevelsdelete[/{userlevelid}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelsdelete-userlevels-delete'); // delete
    $app->group(
        '/userlevels',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevels/list-userlevels-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevels/add-userlevels-add-2'); // add
            $group->any('/' . Config("ADDOPT_ACTION") . '', UserlevelsController::class . ':addopt')->add(PermissionMiddleware::class)->setName('userlevels/addopt-userlevels-addopt-2'); // addopt
            $group->any('/' . Config("VIEW_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevels/view-userlevels-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevels/edit-userlevels-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', UserlevelsController::class . ':update')->add(PermissionMiddleware::class)->setName('userlevels/update-userlevels-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevels/delete-userlevels-delete-2'); // delete
        }
    );

    // employee_trainings
    $app->any('/employeetrainingslist[/{training_id}]', EmployeeTrainingsController::class . ':list')->add(PermissionMiddleware::class)->setName('employeetrainingslist-employee_trainings-list'); // list
    $app->any('/employeetrainingsadd[/{training_id}]', EmployeeTrainingsController::class . ':add')->add(PermissionMiddleware::class)->setName('employeetrainingsadd-employee_trainings-add'); // add
    $app->any('/employeetrainingsview[/{training_id}]', EmployeeTrainingsController::class . ':view')->add(PermissionMiddleware::class)->setName('employeetrainingsview-employee_trainings-view'); // view
    $app->any('/employeetrainingsedit[/{training_id}]', EmployeeTrainingsController::class . ':edit')->add(PermissionMiddleware::class)->setName('employeetrainingsedit-employee_trainings-edit'); // edit
    $app->any('/employeetrainingsupdate', EmployeeTrainingsController::class . ':update')->add(PermissionMiddleware::class)->setName('employeetrainingsupdate-employee_trainings-update'); // update
    $app->any('/employeetrainingsdelete[/{training_id}]', EmployeeTrainingsController::class . ':delete')->add(PermissionMiddleware::class)->setName('employeetrainingsdelete-employee_trainings-delete'); // delete
    $app->any('/employeetrainingssearch', EmployeeTrainingsController::class . ':search')->add(PermissionMiddleware::class)->setName('employeetrainingssearch-employee_trainings-search'); // search
    $app->any('/employeetrainingspreview', EmployeeTrainingsController::class . ':preview')->add(PermissionMiddleware::class)->setName('employeetrainingspreview-employee_trainings-preview'); // preview
    $app->group(
        '/employee_trainings',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{training_id}]', EmployeeTrainingsController::class . ':list')->add(PermissionMiddleware::class)->setName('employee_trainings/list-employee_trainings-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{training_id}]', EmployeeTrainingsController::class . ':add')->add(PermissionMiddleware::class)->setName('employee_trainings/add-employee_trainings-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{training_id}]', EmployeeTrainingsController::class . ':view')->add(PermissionMiddleware::class)->setName('employee_trainings/view-employee_trainings-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{training_id}]', EmployeeTrainingsController::class . ':edit')->add(PermissionMiddleware::class)->setName('employee_trainings/edit-employee_trainings-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', EmployeeTrainingsController::class . ':update')->add(PermissionMiddleware::class)->setName('employee_trainings/update-employee_trainings-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{training_id}]', EmployeeTrainingsController::class . ':delete')->add(PermissionMiddleware::class)->setName('employee_trainings/delete-employee_trainings-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', EmployeeTrainingsController::class . ':search')->add(PermissionMiddleware::class)->setName('employee_trainings/search-employee_trainings-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', EmployeeTrainingsController::class . ':preview')->add(PermissionMiddleware::class)->setName('employee_trainings/preview-employee_trainings-preview-2'); // preview
        }
    );

    // myprofile
    $app->any('/myprofilelist[/{employee_username}]', MyprofileController::class . ':list')->add(PermissionMiddleware::class)->setName('myprofilelist-myprofile-list'); // list
    $app->any('/myprofileadd[/{employee_username}]', MyprofileController::class . ':add')->add(PermissionMiddleware::class)->setName('myprofileadd-myprofile-add'); // add
    $app->any('/myprofileview[/{employee_username}]', MyprofileController::class . ':view')->add(PermissionMiddleware::class)->setName('myprofileview-myprofile-view'); // view
    $app->any('/myprofileedit[/{employee_username}]', MyprofileController::class . ':edit')->add(PermissionMiddleware::class)->setName('myprofileedit-myprofile-edit'); // edit
    $app->any('/myprofileupdate', MyprofileController::class . ':update')->add(PermissionMiddleware::class)->setName('myprofileupdate-myprofile-update'); // update
    $app->any('/myprofiledelete[/{employee_username}]', MyprofileController::class . ':delete')->add(PermissionMiddleware::class)->setName('myprofiledelete-myprofile-delete'); // delete
    $app->any('/myprofilesearch', MyprofileController::class . ':search')->add(PermissionMiddleware::class)->setName('myprofilesearch-myprofile-search'); // search
    $app->any('/myprofilepreview', MyprofileController::class . ':preview')->add(PermissionMiddleware::class)->setName('myprofilepreview-myprofile-preview'); // preview
    $app->group(
        '/myprofile',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{employee_username}]', MyprofileController::class . ':list')->add(PermissionMiddleware::class)->setName('myprofile/list-myprofile-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{employee_username}]', MyprofileController::class . ':add')->add(PermissionMiddleware::class)->setName('myprofile/add-myprofile-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{employee_username}]', MyprofileController::class . ':view')->add(PermissionMiddleware::class)->setName('myprofile/view-myprofile-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{employee_username}]', MyprofileController::class . ':edit')->add(PermissionMiddleware::class)->setName('myprofile/edit-myprofile-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MyprofileController::class . ':update')->add(PermissionMiddleware::class)->setName('myprofile/update-myprofile-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{employee_username}]', MyprofileController::class . ':delete')->add(PermissionMiddleware::class)->setName('myprofile/delete-myprofile-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MyprofileController::class . ':search')->add(PermissionMiddleware::class)->setName('myprofile/search-myprofile-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', MyprofileController::class . ':preview')->add(PermissionMiddleware::class)->setName('myprofile/preview-myprofile-preview-2'); // preview
        }
    );

    // myasset
    $app->any('/myassetlist[/{asset_id}]', MyassetController::class . ':list')->add(PermissionMiddleware::class)->setName('myassetlist-myasset-list'); // list
    $app->any('/myassetadd[/{asset_id}]', MyassetController::class . ':add')->add(PermissionMiddleware::class)->setName('myassetadd-myasset-add'); // add
    $app->any('/myassetview[/{asset_id}]', MyassetController::class . ':view')->add(PermissionMiddleware::class)->setName('myassetview-myasset-view'); // view
    $app->any('/myassetedit[/{asset_id}]', MyassetController::class . ':edit')->add(PermissionMiddleware::class)->setName('myassetedit-myasset-edit'); // edit
    $app->any('/myassetupdate', MyassetController::class . ':update')->add(PermissionMiddleware::class)->setName('myassetupdate-myasset-update'); // update
    $app->any('/myassetdelete[/{asset_id}]', MyassetController::class . ':delete')->add(PermissionMiddleware::class)->setName('myassetdelete-myasset-delete'); // delete
    $app->any('/myassetsearch', MyassetController::class . ':search')->add(PermissionMiddleware::class)->setName('myassetsearch-myasset-search'); // search
    $app->any('/myassetpreview', MyassetController::class . ':preview')->add(PermissionMiddleware::class)->setName('myassetpreview-myasset-preview'); // preview
    $app->group(
        '/myasset',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{asset_id}]', MyassetController::class . ':list')->add(PermissionMiddleware::class)->setName('myasset/list-myasset-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{asset_id}]', MyassetController::class . ':add')->add(PermissionMiddleware::class)->setName('myasset/add-myasset-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{asset_id}]', MyassetController::class . ':view')->add(PermissionMiddleware::class)->setName('myasset/view-myasset-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{asset_id}]', MyassetController::class . ':edit')->add(PermissionMiddleware::class)->setName('myasset/edit-myasset-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MyassetController::class . ':update')->add(PermissionMiddleware::class)->setName('myasset/update-myasset-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{asset_id}]', MyassetController::class . ':delete')->add(PermissionMiddleware::class)->setName('myasset/delete-myasset-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MyassetController::class . ':search')->add(PermissionMiddleware::class)->setName('myasset/search-myasset-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', MyassetController::class . ':preview')->add(PermissionMiddleware::class)->setName('myasset/preview-myasset-preview-2'); // preview
        }
    );

    // mycontract
    $app->any('/mycontractlist[/{contract_id}]', MycontractController::class . ':list')->add(PermissionMiddleware::class)->setName('mycontractlist-mycontract-list'); // list
    $app->any('/mycontractadd[/{contract_id}]', MycontractController::class . ':add')->add(PermissionMiddleware::class)->setName('mycontractadd-mycontract-add'); // add
    $app->any('/mycontractview[/{contract_id}]', MycontractController::class . ':view')->add(PermissionMiddleware::class)->setName('mycontractview-mycontract-view'); // view
    $app->any('/mycontractedit[/{contract_id}]', MycontractController::class . ':edit')->add(PermissionMiddleware::class)->setName('mycontractedit-mycontract-edit'); // edit
    $app->any('/mycontractupdate', MycontractController::class . ':update')->add(PermissionMiddleware::class)->setName('mycontractupdate-mycontract-update'); // update
    $app->any('/mycontractdelete[/{contract_id}]', MycontractController::class . ':delete')->add(PermissionMiddleware::class)->setName('mycontractdelete-mycontract-delete'); // delete
    $app->any('/mycontractsearch', MycontractController::class . ':search')->add(PermissionMiddleware::class)->setName('mycontractsearch-mycontract-search'); // search
    $app->any('/mycontractpreview', MycontractController::class . ':preview')->add(PermissionMiddleware::class)->setName('mycontractpreview-mycontract-preview'); // preview
    $app->group(
        '/mycontract',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{contract_id}]', MycontractController::class . ':list')->add(PermissionMiddleware::class)->setName('mycontract/list-mycontract-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{contract_id}]', MycontractController::class . ':add')->add(PermissionMiddleware::class)->setName('mycontract/add-mycontract-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{contract_id}]', MycontractController::class . ':view')->add(PermissionMiddleware::class)->setName('mycontract/view-mycontract-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{contract_id}]', MycontractController::class . ':edit')->add(PermissionMiddleware::class)->setName('mycontract/edit-mycontract-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MycontractController::class . ':update')->add(PermissionMiddleware::class)->setName('mycontract/update-mycontract-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{contract_id}]', MycontractController::class . ':delete')->add(PermissionMiddleware::class)->setName('mycontract/delete-mycontract-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MycontractController::class . ':search')->add(PermissionMiddleware::class)->setName('mycontract/search-mycontract-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', MycontractController::class . ':preview')->add(PermissionMiddleware::class)->setName('mycontract/preview-mycontract-preview-2'); // preview
        }
    );

    // mytimesheet
    $app->any('/mytimesheetlist[/{timesheet_id}]', MytimesheetController::class . ':list')->add(PermissionMiddleware::class)->setName('mytimesheetlist-mytimesheet-list'); // list
    $app->any('/mytimesheetadd[/{timesheet_id}]', MytimesheetController::class . ':add')->add(PermissionMiddleware::class)->setName('mytimesheetadd-mytimesheet-add'); // add
    $app->any('/mytimesheetview[/{timesheet_id}]', MytimesheetController::class . ':view')->add(PermissionMiddleware::class)->setName('mytimesheetview-mytimesheet-view'); // view
    $app->any('/mytimesheetedit[/{timesheet_id}]', MytimesheetController::class . ':edit')->add(PermissionMiddleware::class)->setName('mytimesheetedit-mytimesheet-edit'); // edit
    $app->any('/mytimesheetupdate', MytimesheetController::class . ':update')->add(PermissionMiddleware::class)->setName('mytimesheetupdate-mytimesheet-update'); // update
    $app->any('/mytimesheetdelete[/{timesheet_id}]', MytimesheetController::class . ':delete')->add(PermissionMiddleware::class)->setName('mytimesheetdelete-mytimesheet-delete'); // delete
    $app->any('/mytimesheetsearch', MytimesheetController::class . ':search')->add(PermissionMiddleware::class)->setName('mytimesheetsearch-mytimesheet-search'); // search
    $app->any('/mytimesheetpreview', MytimesheetController::class . ':preview')->add(PermissionMiddleware::class)->setName('mytimesheetpreview-mytimesheet-preview'); // preview
    $app->group(
        '/mytimesheet',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{timesheet_id}]', MytimesheetController::class . ':list')->add(PermissionMiddleware::class)->setName('mytimesheet/list-mytimesheet-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{timesheet_id}]', MytimesheetController::class . ':add')->add(PermissionMiddleware::class)->setName('mytimesheet/add-mytimesheet-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{timesheet_id}]', MytimesheetController::class . ':view')->add(PermissionMiddleware::class)->setName('mytimesheet/view-mytimesheet-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{timesheet_id}]', MytimesheetController::class . ':edit')->add(PermissionMiddleware::class)->setName('mytimesheet/edit-mytimesheet-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MytimesheetController::class . ':update')->add(PermissionMiddleware::class)->setName('mytimesheet/update-mytimesheet-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{timesheet_id}]', MytimesheetController::class . ':delete')->add(PermissionMiddleware::class)->setName('mytimesheet/delete-mytimesheet-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MytimesheetController::class . ':search')->add(PermissionMiddleware::class)->setName('mytimesheet/search-mytimesheet-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', MytimesheetController::class . ':preview')->add(PermissionMiddleware::class)->setName('mytimesheet/preview-mytimesheet-preview-2'); // preview
        }
    );

    // mytraining
    $app->any('/mytraininglist[/{training_id}]', MytrainingController::class . ':list')->add(PermissionMiddleware::class)->setName('mytraininglist-mytraining-list'); // list
    $app->any('/mytrainingadd[/{training_id}]', MytrainingController::class . ':add')->add(PermissionMiddleware::class)->setName('mytrainingadd-mytraining-add'); // add
    $app->any('/mytrainingview[/{training_id}]', MytrainingController::class . ':view')->add(PermissionMiddleware::class)->setName('mytrainingview-mytraining-view'); // view
    $app->any('/mytrainingedit[/{training_id}]', MytrainingController::class . ':edit')->add(PermissionMiddleware::class)->setName('mytrainingedit-mytraining-edit'); // edit
    $app->any('/mytrainingupdate', MytrainingController::class . ':update')->add(PermissionMiddleware::class)->setName('mytrainingupdate-mytraining-update'); // update
    $app->any('/mytrainingdelete[/{training_id}]', MytrainingController::class . ':delete')->add(PermissionMiddleware::class)->setName('mytrainingdelete-mytraining-delete'); // delete
    $app->any('/mytrainingsearch', MytrainingController::class . ':search')->add(PermissionMiddleware::class)->setName('mytrainingsearch-mytraining-search'); // search
    $app->any('/mytrainingpreview', MytrainingController::class . ':preview')->add(PermissionMiddleware::class)->setName('mytrainingpreview-mytraining-preview'); // preview
    $app->group(
        '/mytraining',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{training_id}]', MytrainingController::class . ':list')->add(PermissionMiddleware::class)->setName('mytraining/list-mytraining-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{training_id}]', MytrainingController::class . ':add')->add(PermissionMiddleware::class)->setName('mytraining/add-mytraining-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{training_id}]', MytrainingController::class . ':view')->add(PermissionMiddleware::class)->setName('mytraining/view-mytraining-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{training_id}]', MytrainingController::class . ':edit')->add(PermissionMiddleware::class)->setName('mytraining/edit-mytraining-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', MytrainingController::class . ':update')->add(PermissionMiddleware::class)->setName('mytraining/update-mytraining-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{training_id}]', MytrainingController::class . ':delete')->add(PermissionMiddleware::class)->setName('mytraining/delete-mytraining-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', MytrainingController::class . ':search')->add(PermissionMiddleware::class)->setName('mytraining/search-mytraining-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', MytrainingController::class . ':preview')->add(PermissionMiddleware::class)->setName('mytraining/preview-mytraining-preview-2'); // preview
        }
    );

    // Top_10_Days
    $app->any('/top10days', Top10DaysController::class)->add(PermissionMiddleware::class)->setName('top10days-Top_10_Days-summary'); // summary

    // customer
    $app->any('/customerlist[/{customer_id}]', CustomerController::class . ':list')->add(PermissionMiddleware::class)->setName('customerlist-customer-list'); // list
    $app->any('/customeradd[/{customer_id}]', CustomerController::class . ':add')->add(PermissionMiddleware::class)->setName('customeradd-customer-add'); // add
    $app->any('/customerview[/{customer_id}]', CustomerController::class . ':view')->add(PermissionMiddleware::class)->setName('customerview-customer-view'); // view
    $app->any('/customeredit[/{customer_id}]', CustomerController::class . ':edit')->add(PermissionMiddleware::class)->setName('customeredit-customer-edit'); // edit
    $app->any('/customerupdate', CustomerController::class . ':update')->add(PermissionMiddleware::class)->setName('customerupdate-customer-update'); // update
    $app->any('/customerdelete[/{customer_id}]', CustomerController::class . ':delete')->add(PermissionMiddleware::class)->setName('customerdelete-customer-delete'); // delete
    $app->any('/customersearch', CustomerController::class . ':search')->add(PermissionMiddleware::class)->setName('customersearch-customer-search'); // search
    $app->any('/customerpreview', CustomerController::class . ':preview')->add(PermissionMiddleware::class)->setName('customerpreview-customer-preview'); // preview
    $app->group(
        '/customer',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{customer_id}]', CustomerController::class . ':list')->add(PermissionMiddleware::class)->setName('customer/list-customer-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{customer_id}]', CustomerController::class . ':add')->add(PermissionMiddleware::class)->setName('customer/add-customer-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{customer_id}]', CustomerController::class . ':view')->add(PermissionMiddleware::class)->setName('customer/view-customer-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{customer_id}]', CustomerController::class . ':edit')->add(PermissionMiddleware::class)->setName('customer/edit-customer-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', CustomerController::class . ':update')->add(PermissionMiddleware::class)->setName('customer/update-customer-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{customer_id}]', CustomerController::class . ':delete')->add(PermissionMiddleware::class)->setName('customer/delete-customer-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', CustomerController::class . ':search')->add(PermissionMiddleware::class)->setName('customer/search-customer-search-2'); // search
            $group->any('/' . Config("PREVIEW_ACTION") . '', CustomerController::class . ':preview')->add(PermissionMiddleware::class)->setName('customer/preview-customer-preview-2'); // preview
        }
    );

    // employee_quotation
    $app->any('/employeequotationlist[/{quotation_id}]', EmployeeQuotationController::class . ':list')->add(PermissionMiddleware::class)->setName('employeequotationlist-employee_quotation-list'); // list
    $app->any('/employeequotationadd[/{quotation_id}]', EmployeeQuotationController::class . ':add')->add(PermissionMiddleware::class)->setName('employeequotationadd-employee_quotation-add'); // add
    $app->any('/employeequotationview[/{quotation_id}]', EmployeeQuotationController::class . ':view')->add(PermissionMiddleware::class)->setName('employeequotationview-employee_quotation-view'); // view
    $app->any('/employeequotationedit[/{quotation_id}]', EmployeeQuotationController::class . ':edit')->add(PermissionMiddleware::class)->setName('employeequotationedit-employee_quotation-edit'); // edit
    $app->any('/employeequotationupdate', EmployeeQuotationController::class . ':update')->add(PermissionMiddleware::class)->setName('employeequotationupdate-employee_quotation-update'); // update
    $app->any('/employeequotationdelete[/{quotation_id}]', EmployeeQuotationController::class . ':delete')->add(PermissionMiddleware::class)->setName('employeequotationdelete-employee_quotation-delete'); // delete
    $app->any('/employeequotationsearch', EmployeeQuotationController::class . ':search')->add(PermissionMiddleware::class)->setName('employeequotationsearch-employee_quotation-search'); // search
    $app->group(
        '/employee_quotation',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{quotation_id}]', EmployeeQuotationController::class . ':list')->add(PermissionMiddleware::class)->setName('employee_quotation/list-employee_quotation-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{quotation_id}]', EmployeeQuotationController::class . ':add')->add(PermissionMiddleware::class)->setName('employee_quotation/add-employee_quotation-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{quotation_id}]', EmployeeQuotationController::class . ':view')->add(PermissionMiddleware::class)->setName('employee_quotation/view-employee_quotation-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{quotation_id}]', EmployeeQuotationController::class . ':edit')->add(PermissionMiddleware::class)->setName('employee_quotation/edit-employee_quotation-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', EmployeeQuotationController::class . ':update')->add(PermissionMiddleware::class)->setName('employee_quotation/update-employee_quotation-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{quotation_id}]', EmployeeQuotationController::class . ':delete')->add(PermissionMiddleware::class)->setName('employee_quotation/delete-employee_quotation-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', EmployeeQuotationController::class . ':search')->add(PermissionMiddleware::class)->setName('employee_quotation/search-employee_quotation-search-2'); // search
        }
    );

    // employee_quotation_detail
    $app->any('/employeequotationdetaillist[/{detail_id}]', EmployeeQuotationDetailController::class . ':list')->add(PermissionMiddleware::class)->setName('employeequotationdetaillist-employee_quotation_detail-list'); // list
    $app->any('/employeequotationdetailadd[/{detail_id}]', EmployeeQuotationDetailController::class . ':add')->add(PermissionMiddleware::class)->setName('employeequotationdetailadd-employee_quotation_detail-add'); // add
    $app->any('/employeequotationdetailview[/{detail_id}]', EmployeeQuotationDetailController::class . ':view')->add(PermissionMiddleware::class)->setName('employeequotationdetailview-employee_quotation_detail-view'); // view
    $app->any('/employeequotationdetailedit[/{detail_id}]', EmployeeQuotationDetailController::class . ':edit')->add(PermissionMiddleware::class)->setName('employeequotationdetailedit-employee_quotation_detail-edit'); // edit
    $app->any('/employeequotationdetaildelete[/{detail_id}]', EmployeeQuotationDetailController::class . ':delete')->add(PermissionMiddleware::class)->setName('employeequotationdetaildelete-employee_quotation_detail-delete'); // delete
    $app->any('/employeequotationdetailpreview', EmployeeQuotationDetailController::class . ':preview')->add(PermissionMiddleware::class)->setName('employeequotationdetailpreview-employee_quotation_detail-preview'); // preview
    $app->group(
        '/employee_quotation_detail',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{detail_id}]', EmployeeQuotationDetailController::class . ':list')->add(PermissionMiddleware::class)->setName('employee_quotation_detail/list-employee_quotation_detail-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{detail_id}]', EmployeeQuotationDetailController::class . ':add')->add(PermissionMiddleware::class)->setName('employee_quotation_detail/add-employee_quotation_detail-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{detail_id}]', EmployeeQuotationDetailController::class . ':view')->add(PermissionMiddleware::class)->setName('employee_quotation_detail/view-employee_quotation_detail-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{detail_id}]', EmployeeQuotationDetailController::class . ':edit')->add(PermissionMiddleware::class)->setName('employee_quotation_detail/edit-employee_quotation_detail-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{detail_id}]', EmployeeQuotationDetailController::class . ':delete')->add(PermissionMiddleware::class)->setName('employee_quotation_detail/delete-employee_quotation_detail-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', EmployeeQuotationDetailController::class . ':preview')->add(PermissionMiddleware::class)->setName('employee_quotation_detail/preview-employee_quotation_detail-preview-2'); // preview
        }
    );

    // setting
    $app->any('/settinglist[/{setting_id}]', SettingController::class . ':list')->add(PermissionMiddleware::class)->setName('settinglist-setting-list'); // list
    $app->any('/settingadd[/{setting_id}]', SettingController::class . ':add')->add(PermissionMiddleware::class)->setName('settingadd-setting-add'); // add
    $app->any('/settingview[/{setting_id}]', SettingController::class . ':view')->add(PermissionMiddleware::class)->setName('settingview-setting-view'); // view
    $app->any('/settingedit[/{setting_id}]', SettingController::class . ':edit')->add(PermissionMiddleware::class)->setName('settingedit-setting-edit'); // edit
    $app->any('/settingupdate', SettingController::class . ':update')->add(PermissionMiddleware::class)->setName('settingupdate-setting-update'); // update
    $app->any('/settingdelete[/{setting_id}]', SettingController::class . ':delete')->add(PermissionMiddleware::class)->setName('settingdelete-setting-delete'); // delete
    $app->any('/settingsearch', SettingController::class . ':search')->add(PermissionMiddleware::class)->setName('settingsearch-setting-search'); // search
    $app->group(
        '/setting',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{setting_id}]', SettingController::class . ':list')->add(PermissionMiddleware::class)->setName('setting/list-setting-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{setting_id}]', SettingController::class . ':add')->add(PermissionMiddleware::class)->setName('setting/add-setting-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{setting_id}]', SettingController::class . ':view')->add(PermissionMiddleware::class)->setName('setting/view-setting-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{setting_id}]', SettingController::class . ':edit')->add(PermissionMiddleware::class)->setName('setting/edit-setting-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', SettingController::class . ':update')->add(PermissionMiddleware::class)->setName('setting/update-setting-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{setting_id}]', SettingController::class . ':delete')->add(PermissionMiddleware::class)->setName('setting/delete-setting-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', SettingController::class . ':search')->add(PermissionMiddleware::class)->setName('setting/search-setting-search-2'); // search
        }
    );

    // v_quotation_list
    $app->any('/vquotationlistlist[/{quotation_id}]', VQuotationListController::class . ':list')->add(PermissionMiddleware::class)->setName('vquotationlistlist-v_quotation_list-list'); // list
    $app->group(
        '/v_quotation_list',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{quotation_id}]', VQuotationListController::class . ':list')->add(PermissionMiddleware::class)->setName('v_quotation_list/list-v_quotation_list-list-2'); // list
        }
    );

    // quotation_print
    $app->any('/quotationprint', QuotationPrintController::class)->add(PermissionMiddleware::class)->setName('quotationprint-quotation_print-summary'); // summary

    // master_work_date
    $app->any('/masterworkdatelist[/{work_date}]', MasterWorkDateController::class . ':list')->add(PermissionMiddleware::class)->setName('masterworkdatelist-master_work_date-list'); // list
    $app->any('/masterworkdateadd[/{work_date}]', MasterWorkDateController::class . ':add')->add(PermissionMiddleware::class)->setName('masterworkdateadd-master_work_date-add'); // add
    $app->any('/masterworkdateview[/{work_date}]', MasterWorkDateController::class . ':view')->add(PermissionMiddleware::class)->setName('masterworkdateview-master_work_date-view'); // view
    $app->any('/masterworkdateedit[/{work_date}]', MasterWorkDateController::class . ':edit')->add(PermissionMiddleware::class)->setName('masterworkdateedit-master_work_date-edit'); // edit
    $app->any('/masterworkdatedelete[/{work_date}]', MasterWorkDateController::class . ':delete')->add(PermissionMiddleware::class)->setName('masterworkdatedelete-master_work_date-delete'); // delete
    $app->group(
        '/master_work_date',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{work_date}]', MasterWorkDateController::class . ':list')->add(PermissionMiddleware::class)->setName('master_work_date/list-master_work_date-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{work_date}]', MasterWorkDateController::class . ':add')->add(PermissionMiddleware::class)->setName('master_work_date/add-master_work_date-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{work_date}]', MasterWorkDateController::class . ':view')->add(PermissionMiddleware::class)->setName('master_work_date/view-master_work_date-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{work_date}]', MasterWorkDateController::class . ':edit')->add(PermissionMiddleware::class)->setName('master_work_date/edit-master_work_date-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{work_date}]', MasterWorkDateController::class . ':delete')->add(PermissionMiddleware::class)->setName('master_work_date/delete-master_work_date-delete-2'); // delete
        }
    );

    // v_timesheet_list
    $app->any('/vtimesheetlistlist[/{work_date}]', VTimesheetListController::class . ':list')->add(PermissionMiddleware::class)->setName('vtimesheetlistlist-v_timesheet_list-list'); // list
    $app->any('/vtimesheetlistview[/{work_date}]', VTimesheetListController::class . ':view')->add(PermissionMiddleware::class)->setName('vtimesheetlistview-v_timesheet_list-view'); // view
    $app->any('/vtimesheetlistsearch', VTimesheetListController::class . ':search')->add(PermissionMiddleware::class)->setName('vtimesheetlistsearch-v_timesheet_list-search'); // search
    $app->group(
        '/v_timesheet_list',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{work_date}]', VTimesheetListController::class . ':list')->add(PermissionMiddleware::class)->setName('v_timesheet_list/list-v_timesheet_list-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{work_date}]', VTimesheetListController::class . ':view')->add(PermissionMiddleware::class)->setName('v_timesheet_list/view-v_timesheet_list-view-2'); // view
            $group->any('/' . Config("SEARCH_ACTION") . '', VTimesheetListController::class . ':search')->add(PermissionMiddleware::class)->setName('v_timesheet_list/search-v_timesheet_list-search-2'); // search
        }
    );

    // welcome
    $app->any('/welcome', WelcomeController::class)->add(PermissionMiddleware::class)->setName('welcome-welcome-custom'); // custom

    // offering
    $app->any('/offeringlist[/{offering_id}]', OfferingController::class . ':list')->add(PermissionMiddleware::class)->setName('offeringlist-offering-list'); // list
    $app->any('/offeringadd[/{offering_id}]', OfferingController::class . ':add')->add(PermissionMiddleware::class)->setName('offeringadd-offering-add'); // add
    $app->any('/offeringview[/{offering_id}]', OfferingController::class . ':view')->add(PermissionMiddleware::class)->setName('offeringview-offering-view'); // view
    $app->any('/offeringedit[/{offering_id}]', OfferingController::class . ':edit')->add(PermissionMiddleware::class)->setName('offeringedit-offering-edit'); // edit
    $app->any('/offeringupdate', OfferingController::class . ':update')->add(PermissionMiddleware::class)->setName('offeringupdate-offering-update'); // update
    $app->any('/offeringdelete[/{offering_id}]', OfferingController::class . ':delete')->add(PermissionMiddleware::class)->setName('offeringdelete-offering-delete'); // delete
    $app->any('/offeringsearch', OfferingController::class . ':search')->add(PermissionMiddleware::class)->setName('offeringsearch-offering-search'); // search
    $app->group(
        '/offering',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{offering_id}]', OfferingController::class . ':list')->add(PermissionMiddleware::class)->setName('offering/list-offering-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{offering_id}]', OfferingController::class . ':add')->add(PermissionMiddleware::class)->setName('offering/add-offering-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{offering_id}]', OfferingController::class . ':view')->add(PermissionMiddleware::class)->setName('offering/view-offering-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{offering_id}]', OfferingController::class . ':edit')->add(PermissionMiddleware::class)->setName('offering/edit-offering-edit-2'); // edit
            $group->any('/' . Config("UPDATE_ACTION") . '', OfferingController::class . ':update')->add(PermissionMiddleware::class)->setName('offering/update-offering-update-2'); // update
            $group->any('/' . Config("DELETE_ACTION") . '[/{offering_id}]', OfferingController::class . ':delete')->add(PermissionMiddleware::class)->setName('offering/delete-offering-delete-2'); // delete
            $group->any('/' . Config("SEARCH_ACTION") . '', OfferingController::class . ':search')->add(PermissionMiddleware::class)->setName('offering/search-offering-search-2'); // search
        }
    );

    // offering_detail
    $app->any('/offeringdetaillist[/{detail_id}]', OfferingDetailController::class . ':list')->add(PermissionMiddleware::class)->setName('offeringdetaillist-offering_detail-list'); // list
    $app->any('/offeringdetailadd[/{detail_id}]', OfferingDetailController::class . ':add')->add(PermissionMiddleware::class)->setName('offeringdetailadd-offering_detail-add'); // add
    $app->any('/offeringdetailview[/{detail_id}]', OfferingDetailController::class . ':view')->add(PermissionMiddleware::class)->setName('offeringdetailview-offering_detail-view'); // view
    $app->any('/offeringdetailedit[/{detail_id}]', OfferingDetailController::class . ':edit')->add(PermissionMiddleware::class)->setName('offeringdetailedit-offering_detail-edit'); // edit
    $app->any('/offeringdetaildelete[/{detail_id}]', OfferingDetailController::class . ':delete')->add(PermissionMiddleware::class)->setName('offeringdetaildelete-offering_detail-delete'); // delete
    $app->any('/offeringdetailpreview', OfferingDetailController::class . ':preview')->add(PermissionMiddleware::class)->setName('offeringdetailpreview-offering_detail-preview'); // preview
    $app->group(
        '/offering_detail',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{detail_id}]', OfferingDetailController::class . ':list')->add(PermissionMiddleware::class)->setName('offering_detail/list-offering_detail-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{detail_id}]', OfferingDetailController::class . ':add')->add(PermissionMiddleware::class)->setName('offering_detail/add-offering_detail-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{detail_id}]', OfferingDetailController::class . ':view')->add(PermissionMiddleware::class)->setName('offering_detail/view-offering_detail-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{detail_id}]', OfferingDetailController::class . ':edit')->add(PermissionMiddleware::class)->setName('offering_detail/edit-offering_detail-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{detail_id}]', OfferingDetailController::class . ':delete')->add(PermissionMiddleware::class)->setName('offering_detail/delete-offering_detail-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', OfferingDetailController::class . ':preview')->add(PermissionMiddleware::class)->setName('offering_detail/preview-offering_detail-preview-2'); // preview
        }
    );

    // v_offering_list
    $app->any('/vofferinglistlist[/{offering_id}]', VOfferingListController::class . ':list')->add(PermissionMiddleware::class)->setName('vofferinglistlist-v_offering_list-list'); // list
    $app->group(
        '/v_offering_list',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{offering_id}]', VOfferingListController::class . ':list')->add(PermissionMiddleware::class)->setName('v_offering_list/list-v_offering_list-list-2'); // list
        }
    );

    // offering_print
    $app->any('/offeringprint', OfferingPrintController::class)->add(PermissionMiddleware::class)->setName('offeringprint-offering_print-summary'); // summary

    // error
    $app->any('/error', OthersController::class . ':error')->add(PermissionMiddleware::class)->setName('error');

    // personal_data
    $app->any('/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->any('/login', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // reset_password
    $app->any('/resetpassword', OthersController::class . ':resetpassword')->add(PermissionMiddleware::class)->setName('resetpassword');

    // change_password
    $app->any('/changepassword', OthersController::class . ':changepassword')->add(PermissionMiddleware::class)->setName('changepassword');

    // register
    $app->any('/register', OthersController::class . ':register')->add(PermissionMiddleware::class)->setName('register');

    // userpriv
    $app->any('/userpriv', OthersController::class . ':userpriv')->add(PermissionMiddleware::class)->setName('userpriv');

    // logout
    $app->any('/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->any('/[index]', OthersController::class . ':index')->setName('index');
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        Route_Action($app);
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            $error = [
                "statusCode" => "404",
                "error" => [
                    "class" => "text-warning",
                    "type" => Container("language")->phrase("Error"),
                    "description" => str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")),
                ],
            ];
            Container("flash")->addMessage("error", $error);
            return $response->withStatus(302)->withHeader("Location", GetUrl("error")); // Redirect to error page
        }
    );
};
