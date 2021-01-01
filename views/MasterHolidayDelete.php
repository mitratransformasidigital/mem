<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterHolidayDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fmaster_holidaydelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fmaster_holidaydelete = currentForm = new ew.Form("fmaster_holidaydelete", "delete");
    loadjs.done("fmaster_holidaydelete");
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
<form name="fmaster_holidaydelete" id="fmaster_holidaydelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_holiday">
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
<?php if ($Page->shift_id->Visible) { // shift_id ?>
        <th class="<?= $Page->shift_id->headerCellClass() ?>"><span id="elh_master_holiday_shift_id" class="master_holiday_shift_id"><?= $Page->shift_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->holiday_date->Visible) { // holiday_date ?>
        <th class="<?= $Page->holiday_date->headerCellClass() ?>"><span id="elh_master_holiday_holiday_date" class="master_holiday_holiday_date"><?= $Page->holiday_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->holiday_name->Visible) { // holiday_name ?>
        <th class="<?= $Page->holiday_name->headerCellClass() ?>"><span id="elh_master_holiday_holiday_name" class="master_holiday_holiday_name"><?= $Page->holiday_name->caption() ?></span></th>
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
<?php if ($Page->shift_id->Visible) { // shift_id ?>
        <td <?= $Page->shift_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_holiday_shift_id" class="master_holiday_shift_id">
<span<?= $Page->shift_id->viewAttributes() ?>>
<?= $Page->shift_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->holiday_date->Visible) { // holiday_date ?>
        <td <?= $Page->holiday_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_holiday_holiday_date" class="master_holiday_holiday_date">
<span<?= $Page->holiday_date->viewAttributes() ?>>
<?= $Page->holiday_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->holiday_name->Visible) { // holiday_name ?>
        <td <?= $Page->holiday_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_holiday_holiday_name" class="master_holiday_holiday_name">
<span<?= $Page->holiday_name->viewAttributes() ?>>
<?= $Page->holiday_name->getViewValue() ?></span>
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
