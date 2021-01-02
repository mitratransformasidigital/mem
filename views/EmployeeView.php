<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var femployeeview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    femployeeview = currentForm = new ew.Form("femployeeview", "view");
    loadjs.done("femployeeview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="femployeeview" id="femployeeview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (!$Page->isExport()) { ?>
<div class="ew-multi-page">
<div class="ew-nav-tabs" id="Page"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navStyle() ?>">
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(1) ?>" href="#tab_employee1" data-toggle="tab"><?= $Page->pageCaption(1) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(2) ?>" href="#tab_employee2" data-toggle="tab"><?= $Page->pageCaption(2) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(3) ?>" href="#tab_employee3" data-toggle="tab"><?= $Page->pageCaption(3) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(4) ?>" href="#tab_employee4" data-toggle="tab"><?= $Page->pageCaption(4) ?></a></li>
    </ul>
    <div class="tab-content">
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(1) ?>" id="tab_employee1"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->employee_name->Visible) { // employee_name ?>
    <tr id="r_employee_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_employee_name"><?= $Page->employee_name->caption() ?></span></td>
        <td data-name="employee_name" <?= $Page->employee_name->cellAttributes() ?>>
<span id="el_employee_employee_name" data-page="1">
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <tr id="r_employee_username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_employee_username"><?= $Page->employee_username->caption() ?></span></td>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_employee_employee_username" data-page="1">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
    <tr id="r_employee_password">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_employee_password"><?= $Page->employee_password->caption() ?></span></td>
        <td data-name="employee_password" <?= $Page->employee_password->cellAttributes() ?>>
<span id="el_employee_employee_password" data-page="1">
<span<?= $Page->employee_password->viewAttributes() ?>>
<?= $Page->employee_password->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
    <tr id="r_employee_email">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_employee_email"><?= $Page->employee_email->caption() ?></span></td>
        <td data-name="employee_email" <?= $Page->employee_email->cellAttributes() ?>>
<span id="el_employee_employee_email" data-page="1">
<span<?= $Page->employee_email->viewAttributes() ?>>
<?= $Page->employee_email->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
    <tr id="r_birth_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_birth_date"><?= $Page->birth_date->caption() ?></span></td>
        <td data-name="birth_date" <?= $Page->birth_date->cellAttributes() ?>>
<span id="el_employee_birth_date" data-page="1">
<span<?= $Page->birth_date->viewAttributes() ?>>
<?= $Page->birth_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
    <tr id="r_religion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_religion"><?= $Page->religion->caption() ?></span></td>
        <td data-name="religion" <?= $Page->religion->cellAttributes() ?>>
<span id="el_employee_religion" data-page="1">
<span<?= $Page->religion->viewAttributes() ?>>
<?= $Page->religion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
    <tr id="r_nik">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_nik"><?= $Page->nik->caption() ?></span></td>
        <td data-name="nik" <?= $Page->nik->cellAttributes() ?>>
<span id="el_employee_nik" data-page="1">
<span<?= $Page->nik->viewAttributes() ?>>
<?= $Page->nik->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
    <tr id="r_npwp">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_npwp"><?= $Page->npwp->caption() ?></span></td>
        <td data-name="npwp" <?= $Page->npwp->cellAttributes() ?>>
<span id="el_employee_npwp" data-page="1">
<span<?= $Page->npwp->viewAttributes() ?>>
<?= $Page->npwp->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <tr id="r_address">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_address"><?= $Page->address->caption() ?></span></td>
        <td data-name="address" <?= $Page->address->cellAttributes() ?>>
<span id="el_employee_address" data-page="1">
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <tr id="r_city_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_city_id"><?= $Page->city_id->caption() ?></span></td>
        <td data-name="city_id" <?= $Page->city_id->cellAttributes() ?>>
<span id="el_employee_city_id" data-page="1">
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
    <tr id="r_postal_code">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_postal_code"><?= $Page->postal_code->caption() ?></span></td>
        <td data-name="postal_code" <?= $Page->postal_code->cellAttributes() ?>>
<span id="el_employee_postal_code" data-page="1">
<span<?= $Page->postal_code->viewAttributes() ?>>
<?= $Page->postal_code->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(2) ?>" id="tab_employee2"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->bank_number->Visible) { // bank_number ?>
    <tr id="r_bank_number">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_bank_number"><?= $Page->bank_number->caption() ?></span></td>
        <td data-name="bank_number" <?= $Page->bank_number->cellAttributes() ?>>
<span id="el_employee_bank_number" data-page="2">
<span<?= $Page->bank_number->viewAttributes() ?>>
<?= $Page->bank_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
    <tr id="r_bank_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_bank_name"><?= $Page->bank_name->caption() ?></span></td>
        <td data-name="bank_name" <?= $Page->bank_name->cellAttributes() ?>>
<span id="el_employee_bank_name" data-page="2">
<span<?= $Page->bank_name->viewAttributes() ?>>
<?= $Page->bank_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
    <tr id="r_scan_ktp">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_scan_ktp"><?= $Page->scan_ktp->caption() ?></span></td>
        <td data-name="scan_ktp" <?= $Page->scan_ktp->cellAttributes() ?>>
<span id="el_employee_scan_ktp" data-page="2">
<span<?= $Page->scan_ktp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_ktp, $Page->scan_ktp->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
    <tr id="r_scan_npwp">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_scan_npwp"><?= $Page->scan_npwp->caption() ?></span></td>
        <td data-name="scan_npwp" <?= $Page->scan_npwp->cellAttributes() ?>>
<span id="el_employee_scan_npwp" data-page="2">
<span<?= $Page->scan_npwp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_npwp, $Page->scan_npwp->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
    <tr id="r_curiculum_vitae">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_curiculum_vitae"><?= $Page->curiculum_vitae->caption() ?></span></td>
        <td data-name="curiculum_vitae" <?= $Page->curiculum_vitae->cellAttributes() ?>>
<span id="el_employee_curiculum_vitae" data-page="2">
<span<?= $Page->curiculum_vitae->viewAttributes() ?>>
<?= GetFileViewTag($Page->curiculum_vitae, $Page->curiculum_vitae->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(3) ?>" id="tab_employee3"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->position_id->Visible) { // position_id ?>
    <tr id="r_position_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_position_id"><?= $Page->position_id->caption() ?></span></td>
        <td data-name="position_id" <?= $Page->position_id->cellAttributes() ?>>
<span id="el_employee_position_id" data-page="3">
<span<?= $Page->position_id->viewAttributes() ?>>
<?= $Page->position_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
    <tr id="r_status_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_status_id"><?= $Page->status_id->caption() ?></span></td>
        <td data-name="status_id" <?= $Page->status_id->cellAttributes() ?>>
<span id="el_employee_status_id" data-page="3">
<span<?= $Page->status_id->viewAttributes() ?>>
<?= $Page->status_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
    <tr id="r_skill_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_skill_id"><?= $Page->skill_id->caption() ?></span></td>
        <td data-name="skill_id" <?= $Page->skill_id->cellAttributes() ?>>
<span id="el_employee_skill_id" data-page="3">
<span<?= $Page->skill_id->viewAttributes() ?>>
<?= $Page->skill_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
    <tr id="r_office_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_office_id"><?= $Page->office_id->caption() ?></span></td>
        <td data-name="office_id" <?= $Page->office_id->cellAttributes() ?>>
<span id="el_employee_office_id" data-page="3">
<span<?= $Page->office_id->viewAttributes() ?>>
<?= $Page->office_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
    <tr id="r_hire_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_hire_date"><?= $Page->hire_date->caption() ?></span></td>
        <td data-name="hire_date" <?= $Page->hire_date->cellAttributes() ?>>
<span id="el_employee_hire_date" data-page="3">
<span<?= $Page->hire_date->viewAttributes() ?>>
<?= $Page->hire_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
    <tr id="r_termination_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_termination_date"><?= $Page->termination_date->caption() ?></span></td>
        <td data-name="termination_date" <?= $Page->termination_date->cellAttributes() ?>>
<span id="el_employee_termination_date" data-page="3">
<span<?= $Page->termination_date->viewAttributes() ?>>
<?= $Page->termination_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_level->Visible) { // user_level ?>
    <tr id="r_user_level">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_user_level"><?= $Page->user_level->caption() ?></span></td>
        <td data-name="user_level" <?= $Page->user_level->cellAttributes() ?>>
<span id="el_employee_user_level" data-page="3">
<span<?= $Page->user_level->viewAttributes() ?>>
<?= $Page->user_level->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(4) ?>" id="tab_employee4"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
    <tr id="r_technical_skill">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_technical_skill"><?= $Page->technical_skill->caption() ?></span></td>
        <td data-name="technical_skill" <?= $Page->technical_skill->cellAttributes() ?>>
<span id="el_employee_technical_skill" data-page="4">
<span<?= $Page->technical_skill->viewAttributes() ?>>
<?= $Page->technical_skill->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
    <tr id="r_about_me">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_about_me"><?= $Page->about_me->caption() ?></span></td>
        <td data-name="about_me" <?= $Page->about_me->cellAttributes() ?>>
<span id="el_employee_about_me" data-page="4">
<span<?= $Page->about_me->viewAttributes() ?>>
<?= $Page->about_me->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    </div>
</div>
</div>
<?php } ?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<?php
    $Page->DetailPages->ValidKeys = explode(",", $Page->getCurrentDetailTable());
    $firstActiveDetailTable = $Page->DetailPages->activePageIndex();
?>
<div class="ew-detail-pages"><!-- detail-pages -->
<div class="ew-nav-tabs" id="Page_details"><!-- tabs -->
    <ul class="<?= $Page->DetailPages->navStyle() ?>"><!-- .nav -->
<?php
    if (in_array("employee_shift", explode(",", $Page->getCurrentDetailTable())) && $employee_shift->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_shift") {
            $firstActiveDetailTable = "employee_shift";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("employee_shift") ?>" href="#tab_employee_shift" data-toggle="tab"><?= $Language->tablePhrase("employee_shift", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->employee_shift_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("activity", explode(",", $Page->getCurrentDetailTable())) && $activity->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "activity") {
            $firstActiveDetailTable = "activity";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("activity") ?>" href="#tab_activity" data-toggle="tab"><?= $Language->tablePhrase("activity", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->activity_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("permit", explode(",", $Page->getCurrentDetailTable())) && $permit->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "permit") {
            $firstActiveDetailTable = "permit";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("permit") ?>" href="#tab_permit" data-toggle="tab"><?= $Language->tablePhrase("permit", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->permit_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("employee_contract", explode(",", $Page->getCurrentDetailTable())) && $employee_contract->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_contract") {
            $firstActiveDetailTable = "employee_contract";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("employee_contract") ?>" href="#tab_employee_contract" data-toggle="tab"><?= $Language->tablePhrase("employee_contract", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->employee_contract_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("employee_asset", explode(",", $Page->getCurrentDetailTable())) && $employee_asset->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_asset") {
            $firstActiveDetailTable = "employee_asset";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("employee_asset") ?>" href="#tab_employee_asset" data-toggle="tab"><?= $Language->tablePhrase("employee_asset", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->employee_asset_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("employee_timesheet", explode(",", $Page->getCurrentDetailTable())) && $employee_timesheet->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_timesheet") {
            $firstActiveDetailTable = "employee_timesheet";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("employee_timesheet") ?>" href="#tab_employee_timesheet" data-toggle="tab"><?= $Language->tablePhrase("employee_timesheet", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->employee_timesheet_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("employee_trainings", explode(",", $Page->getCurrentDetailTable())) && $employee_trainings->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_trainings") {
            $firstActiveDetailTable = "employee_trainings";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("employee_trainings") ?>" href="#tab_employee_trainings" data-toggle="tab"><?= $Language->tablePhrase("employee_trainings", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->employee_trainings_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
    </ul><!-- /.nav -->
    <div class="tab-content"><!-- .tab-content -->
<?php
    if (in_array("employee_shift", explode(",", $Page->getCurrentDetailTable())) && $employee_shift->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_shift") {
            $firstActiveDetailTable = "employee_shift";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("employee_shift") ?>" id="tab_employee_shift"><!-- page* -->
<?php include_once "EmployeeShiftGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("activity", explode(",", $Page->getCurrentDetailTable())) && $activity->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "activity") {
            $firstActiveDetailTable = "activity";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("activity") ?>" id="tab_activity"><!-- page* -->
<?php include_once "ActivityGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("permit", explode(",", $Page->getCurrentDetailTable())) && $permit->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "permit") {
            $firstActiveDetailTable = "permit";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("permit") ?>" id="tab_permit"><!-- page* -->
<?php include_once "PermitGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("employee_contract", explode(",", $Page->getCurrentDetailTable())) && $employee_contract->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_contract") {
            $firstActiveDetailTable = "employee_contract";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("employee_contract") ?>" id="tab_employee_contract"><!-- page* -->
<?php include_once "EmployeeContractGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("employee_asset", explode(",", $Page->getCurrentDetailTable())) && $employee_asset->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_asset") {
            $firstActiveDetailTable = "employee_asset";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("employee_asset") ?>" id="tab_employee_asset"><!-- page* -->
<?php include_once "EmployeeAssetGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("employee_timesheet", explode(",", $Page->getCurrentDetailTable())) && $employee_timesheet->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_timesheet") {
            $firstActiveDetailTable = "employee_timesheet";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("employee_timesheet") ?>" id="tab_employee_timesheet"><!-- page* -->
<?php include_once "EmployeeTimesheetGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("employee_trainings", explode(",", $Page->getCurrentDetailTable())) && $employee_trainings->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_trainings") {
            $firstActiveDetailTable = "employee_trainings";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("employee_trainings") ?>" id="tab_employee_trainings"><!-- page* -->
<?php include_once "EmployeeTrainingsGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
    </div><!-- /.tab-content -->
</div><!-- /tabs -->
</div><!-- /detail-pages -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
