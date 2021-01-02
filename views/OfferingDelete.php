<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fofferingdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fofferingdelete = currentForm = new ew.Form("fofferingdelete", "delete");
    loadjs.done("fofferingdelete");
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
<form name="fofferingdelete" id="fofferingdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="offering">
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
<?php if ($Page->offering_no->Visible) { // offering_no ?>
        <th class="<?= $Page->offering_no->headerCellClass() ?>"><span id="elh_offering_offering_no" class="offering_offering_no"><?= $Page->offering_no->caption() ?></span></th>
<?php } ?>
<?php if ($Page->customer_id->Visible) { // customer_id ?>
        <th class="<?= $Page->customer_id->headerCellClass() ?>"><span id="elh_offering_customer_id" class="offering_customer_id"><?= $Page->customer_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->offering_date->Visible) { // offering_date ?>
        <th class="<?= $Page->offering_date->headerCellClass() ?>"><span id="elh_offering_offering_date" class="offering_offering_date"><?= $Page->offering_date->caption() ?></span></th>
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
<?php if ($Page->offering_no->Visible) { // offering_no ?>
        <td <?= $Page->offering_no->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_offering_offering_no" class="offering_offering_no">
<span<?= $Page->offering_no->viewAttributes() ?>>
<?= $Page->offering_no->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->customer_id->Visible) { // customer_id ?>
        <td <?= $Page->customer_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_offering_customer_id" class="offering_customer_id">
<span<?= $Page->customer_id->viewAttributes() ?>>
<?= $Page->customer_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->offering_date->Visible) { // offering_date ?>
        <td <?= $Page->offering_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_offering_offering_date" class="offering_offering_date">
<span<?= $Page->offering_date->viewAttributes() ?>>
<?= $Page->offering_date->getViewValue() ?></span>
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
