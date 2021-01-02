<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeQuotationDetailDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var femployee_quotation_detaildelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    femployee_quotation_detaildelete = currentForm = new ew.Form("femployee_quotation_detaildelete", "delete");
    loadjs.done("femployee_quotation_detaildelete");
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
<form name="femployee_quotation_detaildelete" id="femployee_quotation_detaildelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_quotation_detail">
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
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <th class="<?= $Page->employee_username->headerCellClass() ?>"><span id="elh_employee_quotation_detail_employee_username" class="employee_quotation_detail_employee_username"><?= $Page->employee_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
        <th class="<?= $Page->rate->headerCellClass() ?>"><span id="elh_employee_quotation_detail_rate" class="employee_quotation_detail_rate"><?= $Page->rate->caption() ?></span></th>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
        <th class="<?= $Page->qty->headerCellClass() ?>"><span id="elh_employee_quotation_detail_qty" class="employee_quotation_detail_qty"><?= $Page->qty->caption() ?></span></th>
<?php } ?>
<?php if ($Page->Total->Visible) { // Total ?>
        <th class="<?= $Page->Total->headerCellClass() ?>"><span id="elh_employee_quotation_detail_Total" class="employee_quotation_detail_Total"><?= $Page->Total->caption() ?></span></th>
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
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <td <?= $Page->employee_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_quotation_detail_employee_username" class="employee_quotation_detail_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
        <td <?= $Page->rate->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_quotation_detail_rate" class="employee_quotation_detail_rate">
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
        <td <?= $Page->qty->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_quotation_detail_qty" class="employee_quotation_detail_qty">
<span<?= $Page->qty->viewAttributes() ?>>
<?= $Page->qty->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->Total->Visible) { // Total ?>
        <td <?= $Page->Total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_quotation_detail_Total" class="employee_quotation_detail_Total">
<span<?= $Page->Total->viewAttributes() ?>>
<?= $Page->Total->getViewValue() ?></span>
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
