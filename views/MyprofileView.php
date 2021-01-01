<?php

namespace MEM\prjMitralPHP;

// Page object
$MyprofileView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmyprofileview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fmyprofileview = currentForm = new ew.Form("fmyprofileview", "view");
    loadjs.done("fmyprofileview");
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
<form name="fmyprofileview" id="fmyprofileview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="myprofile">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (!$Page->isExport()) { ?>
<div class="ew-multi-page">
<div class="ew-nav-tabs" id="Page"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navStyle() ?>">
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(1) ?>" href="#tab_myprofile1" data-toggle="tab"><?= $Page->pageCaption(1) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(2) ?>" href="#tab_myprofile2" data-toggle="tab"><?= $Page->pageCaption(2) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(3) ?>" href="#tab_myprofile3" data-toggle="tab"><?= $Page->pageCaption(3) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(4) ?>" href="#tab_myprofile4" data-toggle="tab"><?= $Page->pageCaption(4) ?></a></li>
    </ul>
    <div class="tab-content">
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(1) ?>" id="tab_myprofile1"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->employee_name->Visible) { // employee_name ?>
    <tr id="r_employee_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_employee_name"><?= $Page->employee_name->caption() ?></span></td>
        <td data-name="employee_name" <?= $Page->employee_name->cellAttributes() ?>>
<span id="el_myprofile_employee_name" data-page="1">
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <tr id="r_employee_username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_employee_username"><?= $Page->employee_username->caption() ?></span></td>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_myprofile_employee_username" data-page="1">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
    <tr id="r_employee_password">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_employee_password"><?= $Page->employee_password->caption() ?></span></td>
        <td data-name="employee_password" <?= $Page->employee_password->cellAttributes() ?>>
<span id="el_myprofile_employee_password" data-page="1">
<span<?= $Page->employee_password->viewAttributes() ?>>
<?= $Page->employee_password->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
    <tr id="r_employee_email">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_employee_email"><?= $Page->employee_email->caption() ?></span></td>
        <td data-name="employee_email" <?= $Page->employee_email->cellAttributes() ?>>
<span id="el_myprofile_employee_email" data-page="1">
<span<?= $Page->employee_email->viewAttributes() ?>>
<?= $Page->employee_email->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
    <tr id="r_birth_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_birth_date"><?= $Page->birth_date->caption() ?></span></td>
        <td data-name="birth_date" <?= $Page->birth_date->cellAttributes() ?>>
<span id="el_myprofile_birth_date" data-page="1">
<span<?= $Page->birth_date->viewAttributes() ?>>
<?= $Page->birth_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
    <tr id="r_nik">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_nik"><?= $Page->nik->caption() ?></span></td>
        <td data-name="nik" <?= $Page->nik->cellAttributes() ?>>
<span id="el_myprofile_nik" data-page="1">
<span<?= $Page->nik->viewAttributes() ?>>
<?= $Page->nik->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
    <tr id="r_npwp">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_npwp"><?= $Page->npwp->caption() ?></span></td>
        <td data-name="npwp" <?= $Page->npwp->cellAttributes() ?>>
<span id="el_myprofile_npwp" data-page="1">
<span<?= $Page->npwp->viewAttributes() ?>>
<?= $Page->npwp->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <tr id="r_address">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_address"><?= $Page->address->caption() ?></span></td>
        <td data-name="address" <?= $Page->address->cellAttributes() ?>>
<span id="el_myprofile_address" data-page="1">
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <tr id="r_city_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_city_id"><?= $Page->city_id->caption() ?></span></td>
        <td data-name="city_id" <?= $Page->city_id->cellAttributes() ?>>
<span id="el_myprofile_city_id" data-page="1">
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
    <tr id="r_postal_code">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_postal_code"><?= $Page->postal_code->caption() ?></span></td>
        <td data-name="postal_code" <?= $Page->postal_code->cellAttributes() ?>>
<span id="el_myprofile_postal_code" data-page="1">
<span<?= $Page->postal_code->viewAttributes() ?>>
<?= $Page->postal_code->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
    <tr id="r_religion">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_religion"><?= $Page->religion->caption() ?></span></td>
        <td data-name="religion" <?= $Page->religion->cellAttributes() ?>>
<span id="el_myprofile_religion" data-page="1">
<span<?= $Page->religion->viewAttributes() ?>>
<?= $Page->religion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(2) ?>" id="tab_myprofile2"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->bank_number->Visible) { // bank_number ?>
    <tr id="r_bank_number">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_bank_number"><?= $Page->bank_number->caption() ?></span></td>
        <td data-name="bank_number" <?= $Page->bank_number->cellAttributes() ?>>
<span id="el_myprofile_bank_number" data-page="2">
<span<?= $Page->bank_number->viewAttributes() ?>>
<?= $Page->bank_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
    <tr id="r_bank_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_bank_name"><?= $Page->bank_name->caption() ?></span></td>
        <td data-name="bank_name" <?= $Page->bank_name->cellAttributes() ?>>
<span id="el_myprofile_bank_name" data-page="2">
<span<?= $Page->bank_name->viewAttributes() ?>>
<?= $Page->bank_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
    <tr id="r_scan_ktp">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_scan_ktp"><?= $Page->scan_ktp->caption() ?></span></td>
        <td data-name="scan_ktp" <?= $Page->scan_ktp->cellAttributes() ?>>
<span id="el_myprofile_scan_ktp" data-page="2">
<span<?= $Page->scan_ktp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_ktp, $Page->scan_ktp->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
    <tr id="r_scan_npwp">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_scan_npwp"><?= $Page->scan_npwp->caption() ?></span></td>
        <td data-name="scan_npwp" <?= $Page->scan_npwp->cellAttributes() ?>>
<span id="el_myprofile_scan_npwp" data-page="2">
<span<?= $Page->scan_npwp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_npwp, $Page->scan_npwp->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
    <tr id="r_curiculum_vitae">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_curiculum_vitae"><?= $Page->curiculum_vitae->caption() ?></span></td>
        <td data-name="curiculum_vitae" <?= $Page->curiculum_vitae->cellAttributes() ?>>
<span id="el_myprofile_curiculum_vitae" data-page="2">
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
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(3) ?>" id="tab_myprofile3"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->position_id->Visible) { // position_id ?>
    <tr id="r_position_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_position_id"><?= $Page->position_id->caption() ?></span></td>
        <td data-name="position_id" <?= $Page->position_id->cellAttributes() ?>>
<span id="el_myprofile_position_id" data-page="3">
<span<?= $Page->position_id->viewAttributes() ?>>
<?= $Page->position_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
    <tr id="r_status_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_status_id"><?= $Page->status_id->caption() ?></span></td>
        <td data-name="status_id" <?= $Page->status_id->cellAttributes() ?>>
<span id="el_myprofile_status_id" data-page="3">
<span<?= $Page->status_id->viewAttributes() ?>>
<?= $Page->status_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
    <tr id="r_skill_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_skill_id"><?= $Page->skill_id->caption() ?></span></td>
        <td data-name="skill_id" <?= $Page->skill_id->cellAttributes() ?>>
<span id="el_myprofile_skill_id" data-page="3">
<span<?= $Page->skill_id->viewAttributes() ?>>
<?= $Page->skill_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
    <tr id="r_office_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_office_id"><?= $Page->office_id->caption() ?></span></td>
        <td data-name="office_id" <?= $Page->office_id->cellAttributes() ?>>
<span id="el_myprofile_office_id" data-page="3">
<span<?= $Page->office_id->viewAttributes() ?>>
<?= $Page->office_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
    <tr id="r_hire_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_hire_date"><?= $Page->hire_date->caption() ?></span></td>
        <td data-name="hire_date" <?= $Page->hire_date->cellAttributes() ?>>
<span id="el_myprofile_hire_date" data-page="3">
<span<?= $Page->hire_date->viewAttributes() ?>>
<?= $Page->hire_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
    <tr id="r_termination_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_termination_date"><?= $Page->termination_date->caption() ?></span></td>
        <td data-name="termination_date" <?= $Page->termination_date->cellAttributes() ?>>
<span id="el_myprofile_termination_date" data-page="3">
<span<?= $Page->termination_date->viewAttributes() ?>>
<?= $Page->termination_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(4) ?>" id="tab_myprofile4"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
    <tr id="r_technical_skill">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_technical_skill"><?= $Page->technical_skill->caption() ?></span></td>
        <td data-name="technical_skill" <?= $Page->technical_skill->cellAttributes() ?>>
<span id="el_myprofile_technical_skill" data-page="4">
<span<?= $Page->technical_skill->viewAttributes() ?>>
<?= $Page->technical_skill->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
    <tr id="r_about_me">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_myprofile_about_me"><?= $Page->about_me->caption() ?></span></td>
        <td data-name="about_me" <?= $Page->about_me->cellAttributes() ?>>
<span id="el_myprofile_about_me" data-page="4">
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
    if (in_array("myasset", explode(",", $Page->getCurrentDetailTable())) && $myasset->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "myasset") {
            $firstActiveDetailTable = "myasset";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("myasset") ?>" href="#tab_myasset" data-toggle="tab"><?= $Language->tablePhrase("myasset", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->myasset_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("mycontract", explode(",", $Page->getCurrentDetailTable())) && $mycontract->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mycontract") {
            $firstActiveDetailTable = "mycontract";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("mycontract") ?>" href="#tab_mycontract" data-toggle="tab"><?= $Language->tablePhrase("mycontract", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->mycontract_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("mytimesheet", explode(",", $Page->getCurrentDetailTable())) && $mytimesheet->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mytimesheet") {
            $firstActiveDetailTable = "mytimesheet";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("mytimesheet") ?>" href="#tab_mytimesheet" data-toggle="tab"><?= $Language->tablePhrase("mytimesheet", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->mytimesheet_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("mytraining", explode(",", $Page->getCurrentDetailTable())) && $mytraining->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mytraining") {
            $firstActiveDetailTable = "mytraining";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("mytraining") ?>" href="#tab_mytraining" data-toggle="tab"><?= $Language->tablePhrase("mytraining", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->mytraining_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
    </ul><!-- /.nav -->
    <div class="tab-content"><!-- .tab-content -->
<?php
    if (in_array("myasset", explode(",", $Page->getCurrentDetailTable())) && $myasset->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "myasset") {
            $firstActiveDetailTable = "myasset";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("myasset") ?>" id="tab_myasset"><!-- page* -->
<?php include_once "MyassetGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("mycontract", explode(",", $Page->getCurrentDetailTable())) && $mycontract->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mycontract") {
            $firstActiveDetailTable = "mycontract";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("mycontract") ?>" id="tab_mycontract"><!-- page* -->
<?php include_once "MycontractGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("mytimesheet", explode(",", $Page->getCurrentDetailTable())) && $mytimesheet->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mytimesheet") {
            $firstActiveDetailTable = "mytimesheet";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("mytimesheet") ?>" id="tab_mytimesheet"><!-- page* -->
<?php include_once "MytimesheetGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("mytraining", explode(",", $Page->getCurrentDetailTable())) && $mytraining->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mytraining") {
            $firstActiveDetailTable = "mytraining";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("mytraining") ?>" id="tab_mytraining"><!-- page* -->
<?php include_once "MytrainingGrid.php" ?>
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
