<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fofferingview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fofferingview = currentForm = new ew.Form("fofferingview", "view");
    loadjs.done("fofferingview");
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
<form name="fofferingview" id="fofferingview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="offering">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->offering_no->Visible) { // offering_no ?>
    <tr id="r_offering_no">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_offering_no"><?= $Page->offering_no->caption() ?></span></td>
        <td data-name="offering_no" <?= $Page->offering_no->cellAttributes() ?>>
<span id="el_offering_offering_no" data-page="1">
<span<?= $Page->offering_no->viewAttributes() ?>>
<?= $Page->offering_no->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->customer_id->Visible) { // customer_id ?>
    <tr id="r_customer_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_customer_id"><?= $Page->customer_id->caption() ?></span></td>
        <td data-name="customer_id" <?= $Page->customer_id->cellAttributes() ?>>
<span id="el_offering_customer_id" data-page="1">
<span<?= $Page->customer_id->viewAttributes() ?>>
<?= $Page->customer_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->offering_date->Visible) { // offering_date ?>
    <tr id="r_offering_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_offering_date"><?= $Page->offering_date->caption() ?></span></td>
        <td data-name="offering_date" <?= $Page->offering_date->cellAttributes() ?>>
<span id="el_offering_offering_date" data-page="1">
<span<?= $Page->offering_date->viewAttributes() ?>>
<?= $Page->offering_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->offering_term->Visible) { // offering_term ?>
    <tr id="r_offering_term">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_offering_term"><?= $Page->offering_term->caption() ?></span></td>
        <td data-name="offering_term" <?= $Page->offering_term->cellAttributes() ?>>
<span id="el_offering_offering_term" data-page="1">
<span<?= $Page->offering_term->viewAttributes() ?>>
<?= $Page->offering_term->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("offering_detail", explode(",", $Page->getCurrentDetailTable())) && $offering_detail->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("offering_detail", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->offering_detail_Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "OfferingDetailGrid.php" ?>
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
