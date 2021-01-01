<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeContractView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var femployee_contractview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    femployee_contractview = currentForm = new ew.Form("femployee_contractview", "view");
    loadjs.done("femployee_contractview");
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
<form name="femployee_contractview" id="femployee_contractview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_contract">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <tr id="r_employee_username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_contract_employee_username"><?= $Page->employee_username->caption() ?></span></td>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_employee_contract_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->salary->Visible) { // salary ?>
    <tr id="r_salary">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_contract_salary"><?= $Page->salary->caption() ?></span></td>
        <td data-name="salary" <?= $Page->salary->cellAttributes() ?>>
<span id="el_employee_contract_salary">
<span<?= $Page->salary->viewAttributes() ?>>
<?= $Page->salary->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->bonus->Visible) { // bonus ?>
    <tr id="r_bonus">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_contract_bonus"><?= $Page->bonus->caption() ?></span></td>
        <td data-name="bonus" <?= $Page->bonus->cellAttributes() ?>>
<span id="el_employee_contract_bonus">
<span<?= $Page->bonus->viewAttributes() ?>>
<?= $Page->bonus->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->thr->Visible) { // thr ?>
    <tr id="r_thr">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_contract_thr"><?= $Page->thr->caption() ?></span></td>
        <td data-name="thr" <?= $Page->thr->cellAttributes() ?>>
<span id="el_employee_contract_thr">
<span<?= $Page->thr->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x_thr_<?= $Page->RowCount ?>" class="custom-control-input" value="<?= $Page->thr->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->thr->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x_thr_<?= $Page->RowCount ?>"></label>
</div></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->contract_start->Visible) { // contract_start ?>
    <tr id="r_contract_start">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_contract_contract_start"><?= $Page->contract_start->caption() ?></span></td>
        <td data-name="contract_start" <?= $Page->contract_start->cellAttributes() ?>>
<span id="el_employee_contract_contract_start">
<span<?= $Page->contract_start->viewAttributes() ?>>
<?= $Page->contract_start->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->contract_end->Visible) { // contract_end ?>
    <tr id="r_contract_end">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_contract_contract_end"><?= $Page->contract_end->caption() ?></span></td>
        <td data-name="contract_end" <?= $Page->contract_end->cellAttributes() ?>>
<span id="el_employee_contract_contract_end">
<span<?= $Page->contract_end->viewAttributes() ?>>
<?= $Page->contract_end->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
    <tr id="r_office_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_contract_office_id"><?= $Page->office_id->caption() ?></span></td>
        <td data-name="office_id" <?= $Page->office_id->cellAttributes() ?>>
<span id="el_employee_contract_office_id">
<span<?= $Page->office_id->viewAttributes() ?>>
<?= $Page->office_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->contract_document->Visible) { // contract_document ?>
    <tr id="r_contract_document">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_contract_contract_document"><?= $Page->contract_document->caption() ?></span></td>
        <td data-name="contract_document" <?= $Page->contract_document->cellAttributes() ?>>
<span id="el_employee_contract_contract_document">
<span<?= $Page->contract_document->viewAttributes() ?>>
<?= GetFileViewTag($Page->contract_document, $Page->contract_document->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <tr id="r_notes">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_contract_notes"><?= $Page->notes->caption() ?></span></td>
        <td data-name="notes" <?= $Page->notes->cellAttributes() ?>>
<span id="el_employee_contract_notes">
<span<?= $Page->notes->viewAttributes() ?>>
<?= $Page->notes->getViewValue() ?></span>
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
