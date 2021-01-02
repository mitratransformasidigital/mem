<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingDetailView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var foffering_detailview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    foffering_detailview = currentForm = new ew.Form("foffering_detailview", "view");
    loadjs.done("foffering_detailview");
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
<form name="foffering_detailview" id="foffering_detailview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="offering_detail">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->offering_id->Visible) { // offering_id ?>
    <tr id="r_offering_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_detail_offering_id"><?= $Page->offering_id->caption() ?></span></td>
        <td data-name="offering_id" <?= $Page->offering_id->cellAttributes() ?>>
<span id="el_offering_detail_offering_id">
<span<?= $Page->offering_id->viewAttributes() ?>>
<?= $Page->offering_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <tr id="r_description">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_detail_description"><?= $Page->description->caption() ?></span></td>
        <td data-name="description" <?= $Page->description->cellAttributes() ?>>
<span id="el_offering_detail_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
    <tr id="r_qty">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_detail_qty"><?= $Page->qty->caption() ?></span></td>
        <td data-name="qty" <?= $Page->qty->cellAttributes() ?>>
<span id="el_offering_detail_qty">
<span<?= $Page->qty->viewAttributes() ?>>
<?= $Page->qty->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
    <tr id="r_rate">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_detail_rate"><?= $Page->rate->caption() ?></span></td>
        <td data-name="rate" <?= $Page->rate->cellAttributes() ?>>
<span id="el_offering_detail_rate">
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->discount->Visible) { // discount ?>
    <tr id="r_discount">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_detail_discount"><?= $Page->discount->caption() ?></span></td>
        <td data-name="discount" <?= $Page->discount->cellAttributes() ?>>
<span id="el_offering_detail_discount">
<span<?= $Page->discount->viewAttributes() ?>>
<?= $Page->discount->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <tr id="r_total">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_offering_detail_total"><?= $Page->total->caption() ?></span></td>
        <td data-name="total" <?= $Page->total->cellAttributes() ?>>
<span id="el_offering_detail_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
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
