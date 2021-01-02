<?php

namespace MEM\prjMitralPHP;

// Page object
$TimesheetListView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var ftimesheet_listview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    ftimesheet_listview = currentForm = new ew.Form("ftimesheet_listview", "view");
    loadjs.done("ftimesheet_listview");
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
<form name="ftimesheet_listview" id="ftimesheet_listview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="timesheet_list">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <tr id="r_employee_username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_timesheet_list_employee_username"><?= $Page->employee_username->caption() ?></span></td>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_timesheet_list_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->work_date->Visible) { // work_date ?>
    <tr id="r_work_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_timesheet_list_work_date"><?= $Page->work_date->caption() ?></span></td>
        <td data-name="work_date" <?= $Page->work_date->cellAttributes() ?>>
<span id="el_timesheet_list_work_date">
<span<?= $Page->work_date->viewAttributes() ?>>
<?= $Page->work_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
    <tr id="r_time_in">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_timesheet_list_time_in"><?= $Page->time_in->caption() ?></span></td>
        <td data-name="time_in" <?= $Page->time_in->cellAttributes() ?>>
<span id="el_timesheet_list_time_in">
<span<?= $Page->time_in->viewAttributes() ?>>
<?= $Page->time_in->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
    <tr id="r_time_out">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_timesheet_list_time_out"><?= $Page->time_out->caption() ?></span></td>
        <td data-name="time_out" <?= $Page->time_out->cellAttributes() ?>>
<span id="el_timesheet_list_time_out">
<span<?= $Page->time_out->viewAttributes() ?>>
<?= $Page->time_out->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <tr id="r_description">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_timesheet_list_description"><?= $Page->description->caption() ?></span></td>
        <td data-name="description" <?= $Page->description->cellAttributes() ?>>
<span id="el_timesheet_list_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
    <tr id="r_absence">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_timesheet_list_absence"><?= $Page->absence->caption() ?></span></td>
        <td data-name="absence" <?= $Page->absence->cellAttributes() ?>>
<span id="el_timesheet_list_absence">
<span<?= $Page->absence->viewAttributes() ?>>
<?= $Page->absence->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
    <tr id="r_days">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_timesheet_list_days"><?= $Page->days->caption() ?></span></td>
        <td data-name="days" <?= $Page->days->cellAttributes() ?>>
<span id="el_timesheet_list_days">
<span<?= $Page->days->viewAttributes() ?>>
<?= $Page->days->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
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
