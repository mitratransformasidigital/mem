<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeQuotationDetailView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var femployee_quotation_detailview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    femployee_quotation_detailview = currentForm = new ew.Form("femployee_quotation_detailview", "view");
    loadjs.done("femployee_quotation_detailview");
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
<form name="femployee_quotation_detailview" id="femployee_quotation_detailview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_quotation_detail">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <tr id="r_employee_username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_quotation_detail_employee_username"><?= $Page->employee_username->caption() ?></span></td>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_employee_quotation_detail_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
    <tr id="r_rate">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_quotation_detail_rate"><?= $Page->rate->caption() ?></span></td>
        <td data-name="rate" <?= $Page->rate->cellAttributes() ?>>
<span id="el_employee_quotation_detail_rate">
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
    <tr id="r_qty">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_quotation_detail_qty"><?= $Page->qty->caption() ?></span></td>
        <td data-name="qty" <?= $Page->qty->cellAttributes() ?>>
<span id="el_employee_quotation_detail_qty">
<span<?= $Page->qty->viewAttributes() ?>>
<?= $Page->qty->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Total->Visible) { // Total ?>
    <tr id="r_Total">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_quotation_detail_Total"><?= $Page->Total->caption() ?></span></td>
        <td data-name="Total" <?= $Page->Total->cellAttributes() ?>>
<span id="el_employee_quotation_detail_Total">
<span<?= $Page->Total->viewAttributes() ?>>
<?= $Page->Total->getViewValue() ?></span>
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
