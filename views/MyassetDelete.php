<?php

namespace MEM\prjMitralPHP;

// Page object
$MyassetDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fmyassetdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fmyassetdelete = currentForm = new ew.Form("fmyassetdelete", "delete");
    loadjs.done("fmyassetdelete");
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
<form name="fmyassetdelete" id="fmyassetdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="myasset">
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
<?php if ($Page->asset_name->Visible) { // asset_name ?>
        <th class="<?= $Page->asset_name->headerCellClass() ?>"><span id="elh_myasset_asset_name" class="myasset_asset_name"><?= $Page->asset_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
        <th class="<?= $Page->year->headerCellClass() ?>"><span id="elh_myasset_year" class="myasset_year"><?= $Page->year->caption() ?></span></th>
<?php } ?>
<?php if ($Page->serial_number->Visible) { // serial_number ?>
        <th class="<?= $Page->serial_number->headerCellClass() ?>"><span id="elh_myasset_serial_number" class="myasset_serial_number"><?= $Page->serial_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->value->Visible) { // value ?>
        <th class="<?= $Page->value->headerCellClass() ?>"><span id="elh_myasset_value" class="myasset_value"><?= $Page->value->caption() ?></span></th>
<?php } ?>
<?php if ($Page->asset_received->Visible) { // asset_received ?>
        <th class="<?= $Page->asset_received->headerCellClass() ?>"><span id="elh_myasset_asset_received" class="myasset_asset_received"><?= $Page->asset_received->caption() ?></span></th>
<?php } ?>
<?php if ($Page->asset_return->Visible) { // asset_return ?>
        <th class="<?= $Page->asset_return->headerCellClass() ?>"><span id="elh_myasset_asset_return" class="myasset_asset_return"><?= $Page->asset_return->caption() ?></span></th>
<?php } ?>
<?php if ($Page->asset_picture->Visible) { // asset_picture ?>
        <th class="<?= $Page->asset_picture->headerCellClass() ?>"><span id="elh_myasset_asset_picture" class="myasset_asset_picture"><?= $Page->asset_picture->caption() ?></span></th>
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
<?php if ($Page->asset_name->Visible) { // asset_name ?>
        <td <?= $Page->asset_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_asset_name" class="myasset_asset_name">
<span<?= $Page->asset_name->viewAttributes() ?>>
<?= $Page->asset_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
        <td <?= $Page->year->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_year" class="myasset_year">
<span<?= $Page->year->viewAttributes() ?>>
<?= $Page->year->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->serial_number->Visible) { // serial_number ?>
        <td <?= $Page->serial_number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_serial_number" class="myasset_serial_number">
<span<?= $Page->serial_number->viewAttributes() ?>>
<?= $Page->serial_number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->value->Visible) { // value ?>
        <td <?= $Page->value->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_value" class="myasset_value">
<span<?= $Page->value->viewAttributes() ?>>
<?= $Page->value->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->asset_received->Visible) { // asset_received ?>
        <td <?= $Page->asset_received->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_asset_received" class="myasset_asset_received">
<span<?= $Page->asset_received->viewAttributes() ?>>
<?= $Page->asset_received->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->asset_return->Visible) { // asset_return ?>
        <td <?= $Page->asset_return->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_asset_return" class="myasset_asset_return">
<span<?= $Page->asset_return->viewAttributes() ?>>
<?= $Page->asset_return->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->asset_picture->Visible) { // asset_picture ?>
        <td <?= $Page->asset_picture->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_asset_picture" class="myasset_asset_picture">
<span<?= $Page->asset_picture->viewAttributes() ?>>
<?= GetFileViewTag($Page->asset_picture, $Page->asset_picture->getViewValue(), false) ?>
</span>
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
