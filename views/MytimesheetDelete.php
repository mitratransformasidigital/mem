<?php

namespace MEM\prjMitralPHP;

// Page object
$MytimesheetDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fmytimesheetdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fmytimesheetdelete = currentForm = new ew.Form("fmytimesheetdelete", "delete");
    loadjs.done("fmytimesheetdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fmytimesheetdelete" id="fmytimesheetdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mytimesheet">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->year->Visible) { // year ?>
        <th class="<?= $Page->year->headerCellClass() ?>"><span id="elh_mytimesheet_year" class="mytimesheet_year"><?= $Page->year->caption() ?></span></th>
<?php } ?>
<?php if ($Page->month->Visible) { // month ?>
        <th class="<?= $Page->month->headerCellClass() ?>"><span id="elh_mytimesheet_month" class="mytimesheet_month"><?= $Page->month->caption() ?></span></th>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
        <th class="<?= $Page->days->headerCellClass() ?>"><span id="elh_mytimesheet_days" class="mytimesheet_days"><?= $Page->days->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sick->Visible) { // sick ?>
        <th class="<?= $Page->sick->headerCellClass() ?>"><span id="elh_mytimesheet_sick" class="mytimesheet_sick"><?= $Page->sick->caption() ?></span></th>
<?php } ?>
<?php if ($Page->leave->Visible) { // leave ?>
        <th class="<?= $Page->leave->headerCellClass() ?>"><span id="elh_mytimesheet_leave" class="mytimesheet_leave"><?= $Page->leave->caption() ?></span></th>
<?php } ?>
<?php if ($Page->permit->Visible) { // permit ?>
        <th class="<?= $Page->permit->headerCellClass() ?>"><span id="elh_mytimesheet_permit" class="mytimesheet_permit"><?= $Page->permit->caption() ?></span></th>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
        <th class="<?= $Page->absence->headerCellClass() ?>"><span id="elh_mytimesheet_absence" class="mytimesheet_absence"><?= $Page->absence->caption() ?></span></th>
<?php } ?>
<?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
        <th class="<?= $Page->timesheet_doc->headerCellClass() ?>"><span id="elh_mytimesheet_timesheet_doc" class="mytimesheet_timesheet_doc"><?= $Page->timesheet_doc->caption() ?></span></th>
<?php } ?>
<?php if ($Page->approved->Visible) { // approved ?>
        <th class="<?= $Page->approved->headerCellClass() ?>"><span id="elh_mytimesheet_approved" class="mytimesheet_approved"><?= $Page->approved->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->year->Visible) { // year ?>
        <td <?= $Page->year->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_year" class="mytimesheet_year">
<span<?= $Page->year->viewAttributes() ?>>
<?= $Page->year->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->month->Visible) { // month ?>
        <td <?= $Page->month->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_month" class="mytimesheet_month">
<span<?= $Page->month->viewAttributes() ?>>
<?= $Page->month->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
        <td <?= $Page->days->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_days" class="mytimesheet_days">
<span<?= $Page->days->viewAttributes() ?>>
<?= $Page->days->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sick->Visible) { // sick ?>
        <td <?= $Page->sick->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_sick" class="mytimesheet_sick">
<span<?= $Page->sick->viewAttributes() ?>>
<?= $Page->sick->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->leave->Visible) { // leave ?>
        <td <?= $Page->leave->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_leave" class="mytimesheet_leave">
<span<?= $Page->leave->viewAttributes() ?>>
<?= $Page->leave->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->permit->Visible) { // permit ?>
        <td <?= $Page->permit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_permit" class="mytimesheet_permit">
<span<?= $Page->permit->viewAttributes() ?>>
<?= $Page->permit->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
        <td <?= $Page->absence->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_absence" class="mytimesheet_absence">
<span<?= $Page->absence->viewAttributes() ?>>
<?= $Page->absence->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
        <td <?= $Page->timesheet_doc->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_timesheet_doc" class="mytimesheet_timesheet_doc">
<span<?= $Page->timesheet_doc->viewAttributes() ?>>
<?= GetFileViewTag($Page->timesheet_doc, $Page->timesheet_doc->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->approved->Visible) { // approved ?>
        <td <?= $Page->approved->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_approved" class="mytimesheet_approved">
<span<?= $Page->approved->viewAttributes() ?>>
<?= $Page->approved->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
