<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeContractDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var femployee_contractdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    femployee_contractdelete = currentForm = new ew.Form("femployee_contractdelete", "delete");
    loadjs.done("femployee_contractdelete");
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
<form name="femployee_contractdelete" id="femployee_contractdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_contract">
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
        <th class="<?= $Page->employee_username->headerCellClass() ?>"><span id="elh_employee_contract_employee_username" class="employee_contract_employee_username"><?= $Page->employee_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->salary->Visible) { // salary ?>
        <th class="<?= $Page->salary->headerCellClass() ?>"><span id="elh_employee_contract_salary" class="employee_contract_salary"><?= $Page->salary->caption() ?></span></th>
<?php } ?>
<?php if ($Page->bonus->Visible) { // bonus ?>
        <th class="<?= $Page->bonus->headerCellClass() ?>"><span id="elh_employee_contract_bonus" class="employee_contract_bonus"><?= $Page->bonus->caption() ?></span></th>
<?php } ?>
<?php if ($Page->thr->Visible) { // thr ?>
        <th class="<?= $Page->thr->headerCellClass() ?>"><span id="elh_employee_contract_thr" class="employee_contract_thr"><?= $Page->thr->caption() ?></span></th>
<?php } ?>
<?php if ($Page->contract_start->Visible) { // contract_start ?>
        <th class="<?= $Page->contract_start->headerCellClass() ?>"><span id="elh_employee_contract_contract_start" class="employee_contract_contract_start"><?= $Page->contract_start->caption() ?></span></th>
<?php } ?>
<?php if ($Page->contract_end->Visible) { // contract_end ?>
        <th class="<?= $Page->contract_end->headerCellClass() ?>"><span id="elh_employee_contract_contract_end" class="employee_contract_contract_end"><?= $Page->contract_end->caption() ?></span></th>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
        <th class="<?= $Page->office_id->headerCellClass() ?>"><span id="elh_employee_contract_office_id" class="employee_contract_office_id"><?= $Page->office_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->contract_document->Visible) { // contract_document ?>
        <th class="<?= $Page->contract_document->headerCellClass() ?>"><span id="elh_employee_contract_contract_document" class="employee_contract_contract_document"><?= $Page->contract_document->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_employee_contract_employee_username" class="employee_contract_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->salary->Visible) { // salary ?>
        <td <?= $Page->salary->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_contract_salary" class="employee_contract_salary">
<span<?= $Page->salary->viewAttributes() ?>>
<?= $Page->salary->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->bonus->Visible) { // bonus ?>
        <td <?= $Page->bonus->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_contract_bonus" class="employee_contract_bonus">
<span<?= $Page->bonus->viewAttributes() ?>>
<?= $Page->bonus->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->thr->Visible) { // thr ?>
        <td <?= $Page->thr->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_contract_thr" class="employee_contract_thr">
<span<?= $Page->thr->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x_thr_<?= $Page->RowCount ?>" class="custom-control-input" value="<?= $Page->thr->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->thr->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x_thr_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
<?php } ?>
<?php if ($Page->contract_start->Visible) { // contract_start ?>
        <td <?= $Page->contract_start->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_contract_contract_start" class="employee_contract_contract_start">
<span<?= $Page->contract_start->viewAttributes() ?>>
<?= $Page->contract_start->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->contract_end->Visible) { // contract_end ?>
        <td <?= $Page->contract_end->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_contract_contract_end" class="employee_contract_contract_end">
<span<?= $Page->contract_end->viewAttributes() ?>>
<?= $Page->contract_end->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
        <td <?= $Page->office_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_contract_office_id" class="employee_contract_office_id">
<span<?= $Page->office_id->viewAttributes() ?>>
<?= $Page->office_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->contract_document->Visible) { // contract_document ?>
        <td <?= $Page->contract_document->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_contract_contract_document" class="employee_contract_contract_document">
<span<?= $Page->contract_document->viewAttributes() ?>>
<?= GetFileViewTag($Page->contract_document, $Page->contract_document->getViewValue(), false) ?>
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
