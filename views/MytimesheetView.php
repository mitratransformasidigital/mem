<?php

namespace MEM\prjMitralPHP;

// Page object
$MytimesheetView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmytimesheetview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fmytimesheetview = currentForm = new ew.Form("fmytimesheetview", "view");
    loadjs.done("fmytimesheetview");
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
<form name="fmytimesheetview" id="fmytimesheetview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mytimesheet">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->year->Visible) { // year ?>
    <tr id="r_year">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_year"><?= $Page->year->caption() ?></span></td>
        <td data-name="year" <?= $Page->year->cellAttributes() ?>>
<span id="el_mytimesheet_year">
<span<?= $Page->year->viewAttributes() ?>>
<?= $Page->year->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->month->Visible) { // month ?>
    <tr id="r_month">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_month"><?= $Page->month->caption() ?></span></td>
        <td data-name="month" <?= $Page->month->cellAttributes() ?>>
<span id="el_mytimesheet_month">
<span<?= $Page->month->viewAttributes() ?>>
<?= $Page->month->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
    <tr id="r_days">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_days"><?= $Page->days->caption() ?></span></td>
        <td data-name="days" <?= $Page->days->cellAttributes() ?>>
<span id="el_mytimesheet_days">
<span<?= $Page->days->viewAttributes() ?>>
<?= $Page->days->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sick->Visible) { // sick ?>
    <tr id="r_sick">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_sick"><?= $Page->sick->caption() ?></span></td>
        <td data-name="sick" <?= $Page->sick->cellAttributes() ?>>
<span id="el_mytimesheet_sick">
<span<?= $Page->sick->viewAttributes() ?>>
<?= $Page->sick->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->leave->Visible) { // leave ?>
    <tr id="r_leave">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_leave"><?= $Page->leave->caption() ?></span></td>
        <td data-name="leave" <?= $Page->leave->cellAttributes() ?>>
<span id="el_mytimesheet_leave">
<span<?= $Page->leave->viewAttributes() ?>>
<?= $Page->leave->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->permit->Visible) { // permit ?>
    <tr id="r_permit">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_permit"><?= $Page->permit->caption() ?></span></td>
        <td data-name="permit" <?= $Page->permit->cellAttributes() ?>>
<span id="el_mytimesheet_permit">
<span<?= $Page->permit->viewAttributes() ?>>
<?= $Page->permit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
    <tr id="r_absence">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_absence"><?= $Page->absence->caption() ?></span></td>
        <td data-name="absence" <?= $Page->absence->cellAttributes() ?>>
<span id="el_mytimesheet_absence">
<span<?= $Page->absence->viewAttributes() ?>>
<?= $Page->absence->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
    <tr id="r_timesheet_doc">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_timesheet_doc"><?= $Page->timesheet_doc->caption() ?></span></td>
        <td data-name="timesheet_doc" <?= $Page->timesheet_doc->cellAttributes() ?>>
<span id="el_mytimesheet_timesheet_doc">
<span<?= $Page->timesheet_doc->viewAttributes() ?>>
<?= GetFileViewTag($Page->timesheet_doc, $Page->timesheet_doc->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->employee_notes->Visible) { // employee_notes ?>
    <tr id="r_employee_notes">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_employee_notes"><?= $Page->employee_notes->caption() ?></span></td>
        <td data-name="employee_notes" <?= $Page->employee_notes->cellAttributes() ?>>
<span id="el_mytimesheet_employee_notes">
<span<?= $Page->employee_notes->viewAttributes() ?>>
<?= $Page->employee_notes->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->company_notes->Visible) { // company_notes ?>
    <tr id="r_company_notes">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_company_notes"><?= $Page->company_notes->caption() ?></span></td>
        <td data-name="company_notes" <?= $Page->company_notes->cellAttributes() ?>>
<span id="el_mytimesheet_company_notes">
<span<?= $Page->company_notes->viewAttributes() ?>>
<?= $Page->company_notes->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->approved->Visible) { // approved ?>
    <tr id="r_approved">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytimesheet_approved"><?= $Page->approved->caption() ?></span></td>
        <td data-name="approved" <?= $Page->approved->cellAttributes() ?>>
<span id="el_mytimesheet_approved">
<span<?= $Page->approved->viewAttributes() ?>>
<?= $Page->approved->getViewValue() ?></span>
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
