<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterShiftView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmaster_shiftview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fmaster_shiftview = currentForm = new ew.Form("fmaster_shiftview", "view");
    loadjs.done("fmaster_shiftview");
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
<form name="fmaster_shiftview" id="fmaster_shiftview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_shift">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->shift_name->Visible) { // shift_name ?>
    <tr id="r_shift_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_shift_name"><?= $Page->shift_name->caption() ?></span></td>
        <td data-name="shift_name" <?= $Page->shift_name->cellAttributes() ?>>
<span id="el_master_shift_shift_name">
<span<?= $Page->shift_name->viewAttributes() ?>>
<?= $Page->shift_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sunday_time_in->Visible) { // sunday_time_in ?>
    <tr id="r_sunday_time_in">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_sunday_time_in"><?= $Page->sunday_time_in->caption() ?></span></td>
        <td data-name="sunday_time_in" <?= $Page->sunday_time_in->cellAttributes() ?>>
<span id="el_master_shift_sunday_time_in">
<span<?= $Page->sunday_time_in->viewAttributes() ?>>
<?= $Page->sunday_time_in->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sunday_time_out->Visible) { // sunday_time_out ?>
    <tr id="r_sunday_time_out">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_sunday_time_out"><?= $Page->sunday_time_out->caption() ?></span></td>
        <td data-name="sunday_time_out" <?= $Page->sunday_time_out->cellAttributes() ?>>
<span id="el_master_shift_sunday_time_out">
<span<?= $Page->sunday_time_out->viewAttributes() ?>>
<?= $Page->sunday_time_out->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monday_time_in->Visible) { // monday_time_in ?>
    <tr id="r_monday_time_in">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_monday_time_in"><?= $Page->monday_time_in->caption() ?></span></td>
        <td data-name="monday_time_in" <?= $Page->monday_time_in->cellAttributes() ?>>
<span id="el_master_shift_monday_time_in">
<span<?= $Page->monday_time_in->viewAttributes() ?>>
<?= $Page->monday_time_in->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monday_time_out->Visible) { // monday_time_out ?>
    <tr id="r_monday_time_out">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_monday_time_out"><?= $Page->monday_time_out->caption() ?></span></td>
        <td data-name="monday_time_out" <?= $Page->monday_time_out->cellAttributes() ?>>
<span id="el_master_shift_monday_time_out">
<span<?= $Page->monday_time_out->viewAttributes() ?>>
<?= $Page->monday_time_out->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tuesday_time_in->Visible) { // tuesday_time_in ?>
    <tr id="r_tuesday_time_in">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_tuesday_time_in"><?= $Page->tuesday_time_in->caption() ?></span></td>
        <td data-name="tuesday_time_in" <?= $Page->tuesday_time_in->cellAttributes() ?>>
<span id="el_master_shift_tuesday_time_in">
<span<?= $Page->tuesday_time_in->viewAttributes() ?>>
<?= $Page->tuesday_time_in->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tuesday_time_out->Visible) { // tuesday_time_out ?>
    <tr id="r_tuesday_time_out">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_tuesday_time_out"><?= $Page->tuesday_time_out->caption() ?></span></td>
        <td data-name="tuesday_time_out" <?= $Page->tuesday_time_out->cellAttributes() ?>>
<span id="el_master_shift_tuesday_time_out">
<span<?= $Page->tuesday_time_out->viewAttributes() ?>>
<?= $Page->tuesday_time_out->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->wednesday_time_in->Visible) { // wednesday_time_in ?>
    <tr id="r_wednesday_time_in">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_wednesday_time_in"><?= $Page->wednesday_time_in->caption() ?></span></td>
        <td data-name="wednesday_time_in" <?= $Page->wednesday_time_in->cellAttributes() ?>>
<span id="el_master_shift_wednesday_time_in">
<span<?= $Page->wednesday_time_in->viewAttributes() ?>>
<?= $Page->wednesday_time_in->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->wednesday_time_out->Visible) { // wednesday_time_out ?>
    <tr id="r_wednesday_time_out">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_wednesday_time_out"><?= $Page->wednesday_time_out->caption() ?></span></td>
        <td data-name="wednesday_time_out" <?= $Page->wednesday_time_out->cellAttributes() ?>>
<span id="el_master_shift_wednesday_time_out">
<span<?= $Page->wednesday_time_out->viewAttributes() ?>>
<?= $Page->wednesday_time_out->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->thursday_time_in->Visible) { // thursday_time_in ?>
    <tr id="r_thursday_time_in">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_thursday_time_in"><?= $Page->thursday_time_in->caption() ?></span></td>
        <td data-name="thursday_time_in" <?= $Page->thursday_time_in->cellAttributes() ?>>
<span id="el_master_shift_thursday_time_in">
<span<?= $Page->thursday_time_in->viewAttributes() ?>>
<?= $Page->thursday_time_in->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->thursday_time_out->Visible) { // thursday_time_out ?>
    <tr id="r_thursday_time_out">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_thursday_time_out"><?= $Page->thursday_time_out->caption() ?></span></td>
        <td data-name="thursday_time_out" <?= $Page->thursday_time_out->cellAttributes() ?>>
<span id="el_master_shift_thursday_time_out">
<span<?= $Page->thursday_time_out->viewAttributes() ?>>
<?= $Page->thursday_time_out->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->friday_time_in->Visible) { // friday_time_in ?>
    <tr id="r_friday_time_in">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_friday_time_in"><?= $Page->friday_time_in->caption() ?></span></td>
        <td data-name="friday_time_in" <?= $Page->friday_time_in->cellAttributes() ?>>
<span id="el_master_shift_friday_time_in">
<span<?= $Page->friday_time_in->viewAttributes() ?>>
<?= $Page->friday_time_in->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->friday_time_out->Visible) { // friday_time_out ?>
    <tr id="r_friday_time_out">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_friday_time_out"><?= $Page->friday_time_out->caption() ?></span></td>
        <td data-name="friday_time_out" <?= $Page->friday_time_out->cellAttributes() ?>>
<span id="el_master_shift_friday_time_out">
<span<?= $Page->friday_time_out->viewAttributes() ?>>
<?= $Page->friday_time_out->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->saturday_time_in->Visible) { // saturday_time_in ?>
    <tr id="r_saturday_time_in">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_saturday_time_in"><?= $Page->saturday_time_in->caption() ?></span></td>
        <td data-name="saturday_time_in" <?= $Page->saturday_time_in->cellAttributes() ?>>
<span id="el_master_shift_saturday_time_in">
<span<?= $Page->saturday_time_in->viewAttributes() ?>>
<?= $Page->saturday_time_in->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->saturday_time_out->Visible) { // saturday_time_out ?>
    <tr id="r_saturday_time_out">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_shift_saturday_time_out"><?= $Page->saturday_time_out->caption() ?></span></td>
        <td data-name="saturday_time_out" <?= $Page->saturday_time_out->cellAttributes() ?>>
<span id="el_master_shift_saturday_time_out">
<span<?= $Page->saturday_time_out->viewAttributes() ?>>
<?= $Page->saturday_time_out->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<?php
    $Page->DetailPages->ValidKeys = explode(",", $Page->getCurrentDetailTable());
    $firstActiveDetailTable = $Page->DetailPages->activePageIndex();
?>
<div class="ew-detail-pages"><!-- detail-pages -->
<div class="ew-nav-tabs" id="Page_details"><!-- tabs -->
    <ul class="<?= $Page->DetailPages->navStyle() ?>"><!-- .nav -->
<?php
    if (in_array("master_holiday", explode(",", $Page->getCurrentDetailTable())) && $master_holiday->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "master_holiday") {
            $firstActiveDetailTable = "master_holiday";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("master_holiday") ?>" href="#tab_master_holiday" data-toggle="tab"><?= $Language->tablePhrase("master_holiday", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->master_holiday_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
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
    </ul><!-- /.nav -->
    <div class="tab-content"><!-- .tab-content -->
<?php
    if (in_array("master_holiday", explode(",", $Page->getCurrentDetailTable())) && $master_holiday->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "master_holiday") {
            $firstActiveDetailTable = "master_holiday";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("master_holiday") ?>" id="tab_master_holiday"><!-- page* -->
<?php include_once "MasterHolidayGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
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
