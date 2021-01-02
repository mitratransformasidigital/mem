<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingDetailDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var foffering_detaildelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    foffering_detaildelete = currentForm = new ew.Form("foffering_detaildelete", "delete");
    loadjs.done("foffering_detaildelete");
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
<form name="foffering_detaildelete" id="foffering_detaildelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="offering_detail">
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
<?php if ($Page->offering_id->Visible) { // offering_id ?>
        <th class="<?= $Page->offering_id->headerCellClass() ?>"><span id="elh_offering_detail_offering_id" class="offering_detail_offering_id"><?= $Page->offering_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <th class="<?= $Page->description->headerCellClass() ?>"><span id="elh_offering_detail_description" class="offering_detail_description"><?= $Page->description->caption() ?></span></th>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
        <th class="<?= $Page->qty->headerCellClass() ?>"><span id="elh_offering_detail_qty" class="offering_detail_qty"><?= $Page->qty->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
        <th class="<?= $Page->rate->headerCellClass() ?>"><span id="elh_offering_detail_rate" class="offering_detail_rate"><?= $Page->rate->caption() ?></span></th>
<?php } ?>
<?php if ($Page->discount->Visible) { // discount ?>
        <th class="<?= $Page->discount->headerCellClass() ?>"><span id="elh_offering_detail_discount" class="offering_detail_discount"><?= $Page->discount->caption() ?></span></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th class="<?= $Page->total->headerCellClass() ?>"><span id="elh_offering_detail_total" class="offering_detail_total"><?= $Page->total->caption() ?></span></th>
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
<?php if ($Page->offering_id->Visible) { // offering_id ?>
        <td <?= $Page->offering_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_offering_detail_offering_id" class="offering_detail_offering_id">
<span<?= $Page->offering_id->viewAttributes() ?>>
<?= $Page->offering_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <td <?= $Page->description->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_offering_detail_description" class="offering_detail_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
        <td <?= $Page->qty->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_offering_detail_qty" class="offering_detail_qty">
<span<?= $Page->qty->viewAttributes() ?>>
<?= $Page->qty->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
        <td <?= $Page->rate->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_offering_detail_rate" class="offering_detail_rate">
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->discount->Visible) { // discount ?>
        <td <?= $Page->discount->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_offering_detail_discount" class="offering_detail_discount">
<span<?= $Page->discount->viewAttributes() ?>>
<?= $Page->discount->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <td <?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_offering_detail_total" class="offering_detail_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
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
