<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeQuotationView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var femployee_quotationview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    femployee_quotationview = currentForm = new ew.Form("femployee_quotationview", "view");
    loadjs.done("femployee_quotationview");
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
<form name="femployee_quotationview" id="femployee_quotationview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_quotation">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->quotation_no->Visible) { // quotation_no ?>
    <tr id="r_quotation_no">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_quotation_quotation_no"><?= $Page->quotation_no->caption() ?></span></td>
        <td data-name="quotation_no" <?= $Page->quotation_no->cellAttributes() ?>>
<span id="el_employee_quotation_quotation_no" data-page="1">
<span<?= $Page->quotation_no->viewAttributes() ?>>
<?= $Page->quotation_no->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->customer_id->Visible) { // customer_id ?>
    <tr id="r_customer_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_quotation_customer_id"><?= $Page->customer_id->caption() ?></span></td>
        <td data-name="customer_id" <?= $Page->customer_id->cellAttributes() ?>>
<span id="el_employee_quotation_customer_id" data-page="1">
<span<?= $Page->customer_id->viewAttributes() ?>>
<?= $Page->customer_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { // quotation_date ?>
    <tr id="r_quotation_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_quotation_quotation_date"><?= $Page->quotation_date->caption() ?></span></td>
        <td data-name="quotation_date" <?= $Page->quotation_date->cellAttributes() ?>>
<span id="el_employee_quotation_quotation_date" data-page="1">
<span<?= $Page->quotation_date->viewAttributes() ?>>
<?= $Page->quotation_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("employee_quotation_detail", explode(",", $Page->getCurrentDetailTable())) && $employee_quotation_detail->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("employee_quotation_detail", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->employee_quotation_detail_Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "EmployeeQuotationDetailGrid.php" ?>
<?php } ?>
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
