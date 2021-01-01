<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterShiftDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fmaster_shiftdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fmaster_shiftdelete = currentForm = new ew.Form("fmaster_shiftdelete", "delete");
    loadjs.done("fmaster_shiftdelete");
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
<form name="fmaster_shiftdelete" id="fmaster_shiftdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_shift">
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
<?php if ($Page->shift_name->Visible) { // shift_name ?>
        <th class="<?= $Page->shift_name->headerCellClass() ?>"><span id="elh_master_shift_shift_name" class="master_shift_shift_name"><?= $Page->shift_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sunday_time_in->Visible) { // sunday_time_in ?>
        <th class="<?= $Page->sunday_time_in->headerCellClass() ?>"><span id="elh_master_shift_sunday_time_in" class="master_shift_sunday_time_in"><?= $Page->sunday_time_in->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sunday_time_out->Visible) { // sunday_time_out ?>
        <th class="<?= $Page->sunday_time_out->headerCellClass() ?>"><span id="elh_master_shift_sunday_time_out" class="master_shift_sunday_time_out"><?= $Page->sunday_time_out->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monday_time_in->Visible) { // monday_time_in ?>
        <th class="<?= $Page->monday_time_in->headerCellClass() ?>"><span id="elh_master_shift_monday_time_in" class="master_shift_monday_time_in"><?= $Page->monday_time_in->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monday_time_out->Visible) { // monday_time_out ?>
        <th class="<?= $Page->monday_time_out->headerCellClass() ?>"><span id="elh_master_shift_monday_time_out" class="master_shift_monday_time_out"><?= $Page->monday_time_out->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tuesday_time_in->Visible) { // tuesday_time_in ?>
        <th class="<?= $Page->tuesday_time_in->headerCellClass() ?>"><span id="elh_master_shift_tuesday_time_in" class="master_shift_tuesday_time_in"><?= $Page->tuesday_time_in->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tuesday_time_out->Visible) { // tuesday_time_out ?>
        <th class="<?= $Page->tuesday_time_out->headerCellClass() ?>"><span id="elh_master_shift_tuesday_time_out" class="master_shift_tuesday_time_out"><?= $Page->tuesday_time_out->caption() ?></span></th>
<?php } ?>
<?php if ($Page->wednesday_time_in->Visible) { // wednesday_time_in ?>
        <th class="<?= $Page->wednesday_time_in->headerCellClass() ?>"><span id="elh_master_shift_wednesday_time_in" class="master_shift_wednesday_time_in"><?= $Page->wednesday_time_in->caption() ?></span></th>
<?php } ?>
<?php if ($Page->wednesday_time_out->Visible) { // wednesday_time_out ?>
        <th class="<?= $Page->wednesday_time_out->headerCellClass() ?>"><span id="elh_master_shift_wednesday_time_out" class="master_shift_wednesday_time_out"><?= $Page->wednesday_time_out->caption() ?></span></th>
<?php } ?>
<?php if ($Page->thursday_time_in->Visible) { // thursday_time_in ?>
        <th class="<?= $Page->thursday_time_in->headerCellClass() ?>"><span id="elh_master_shift_thursday_time_in" class="master_shift_thursday_time_in"><?= $Page->thursday_time_in->caption() ?></span></th>
<?php } ?>
<?php if ($Page->thursday_time_out->Visible) { // thursday_time_out ?>
        <th class="<?= $Page->thursday_time_out->headerCellClass() ?>"><span id="elh_master_shift_thursday_time_out" class="master_shift_thursday_time_out"><?= $Page->thursday_time_out->caption() ?></span></th>
<?php } ?>
<?php if ($Page->friday_time_in->Visible) { // friday_time_in ?>
        <th class="<?= $Page->friday_time_in->headerCellClass() ?>"><span id="elh_master_shift_friday_time_in" class="master_shift_friday_time_in"><?= $Page->friday_time_in->caption() ?></span></th>
<?php } ?>
<?php if ($Page->friday_time_out->Visible) { // friday_time_out ?>
        <th class="<?= $Page->friday_time_out->headerCellClass() ?>"><span id="elh_master_shift_friday_time_out" class="master_shift_friday_time_out"><?= $Page->friday_time_out->caption() ?></span></th>
<?php } ?>
<?php if ($Page->saturday_time_in->Visible) { // saturday_time_in ?>
        <th class="<?= $Page->saturday_time_in->headerCellClass() ?>"><span id="elh_master_shift_saturday_time_in" class="master_shift_saturday_time_in"><?= $Page->saturday_time_in->caption() ?></span></th>
<?php } ?>
<?php if ($Page->saturday_time_out->Visible) { // saturday_time_out ?>
        <th class="<?= $Page->saturday_time_out->headerCellClass() ?>"><span id="elh_master_shift_saturday_time_out" class="master_shift_saturday_time_out"><?= $Page->saturday_time_out->caption() ?></span></th>
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
<?php if ($Page->shift_name->Visible) { // shift_name ?>
        <td <?= $Page->shift_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_shift_name" class="master_shift_shift_name">
<span<?= $Page->shift_name->viewAttributes() ?>>
<?= $Page->shift_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sunday_time_in->Visible) { // sunday_time_in ?>
        <td <?= $Page->sunday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_sunday_time_in" class="master_shift_sunday_time_in">
<span<?= $Page->sunday_time_in->viewAttributes() ?>>
<?= $Page->sunday_time_in->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sunday_time_out->Visible) { // sunday_time_out ?>
        <td <?= $Page->sunday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_sunday_time_out" class="master_shift_sunday_time_out">
<span<?= $Page->sunday_time_out->viewAttributes() ?>>
<?= $Page->sunday_time_out->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monday_time_in->Visible) { // monday_time_in ?>
        <td <?= $Page->monday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_monday_time_in" class="master_shift_monday_time_in">
<span<?= $Page->monday_time_in->viewAttributes() ?>>
<?= $Page->monday_time_in->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monday_time_out->Visible) { // monday_time_out ?>
        <td <?= $Page->monday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_monday_time_out" class="master_shift_monday_time_out">
<span<?= $Page->monday_time_out->viewAttributes() ?>>
<?= $Page->monday_time_out->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tuesday_time_in->Visible) { // tuesday_time_in ?>
        <td <?= $Page->tuesday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_tuesday_time_in" class="master_shift_tuesday_time_in">
<span<?= $Page->tuesday_time_in->viewAttributes() ?>>
<?= $Page->tuesday_time_in->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tuesday_time_out->Visible) { // tuesday_time_out ?>
        <td <?= $Page->tuesday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_tuesday_time_out" class="master_shift_tuesday_time_out">
<span<?= $Page->tuesday_time_out->viewAttributes() ?>>
<?= $Page->tuesday_time_out->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->wednesday_time_in->Visible) { // wednesday_time_in ?>
        <td <?= $Page->wednesday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_wednesday_time_in" class="master_shift_wednesday_time_in">
<span<?= $Page->wednesday_time_in->viewAttributes() ?>>
<?= $Page->wednesday_time_in->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->wednesday_time_out->Visible) { // wednesday_time_out ?>
        <td <?= $Page->wednesday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_wednesday_time_out" class="master_shift_wednesday_time_out">
<span<?= $Page->wednesday_time_out->viewAttributes() ?>>
<?= $Page->wednesday_time_out->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->thursday_time_in->Visible) { // thursday_time_in ?>
        <td <?= $Page->thursday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_thursday_time_in" class="master_shift_thursday_time_in">
<span<?= $Page->thursday_time_in->viewAttributes() ?>>
<?= $Page->thursday_time_in->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->thursday_time_out->Visible) { // thursday_time_out ?>
        <td <?= $Page->thursday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_thursday_time_out" class="master_shift_thursday_time_out">
<span<?= $Page->thursday_time_out->viewAttributes() ?>>
<?= $Page->thursday_time_out->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->friday_time_in->Visible) { // friday_time_in ?>
        <td <?= $Page->friday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_friday_time_in" class="master_shift_friday_time_in">
<span<?= $Page->friday_time_in->viewAttributes() ?>>
<?= $Page->friday_time_in->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->friday_time_out->Visible) { // friday_time_out ?>
        <td <?= $Page->friday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_friday_time_out" class="master_shift_friday_time_out">
<span<?= $Page->friday_time_out->viewAttributes() ?>>
<?= $Page->friday_time_out->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->saturday_time_in->Visible) { // saturday_time_in ?>
        <td <?= $Page->saturday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_saturday_time_in" class="master_shift_saturday_time_in">
<span<?= $Page->saturday_time_in->viewAttributes() ?>>
<?= $Page->saturday_time_in->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->saturday_time_out->Visible) { // saturday_time_out ?>
        <td <?= $Page->saturday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_saturday_time_out" class="master_shift_saturday_time_out">
<span<?= $Page->saturday_time_out->viewAttributes() ?>>
<?= $Page->saturday_time_out->getViewValue() ?></span>
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
