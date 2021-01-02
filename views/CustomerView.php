<?php

namespace MEM\prjMitralPHP;

// Page object
$CustomerView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fcustomerview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fcustomerview = currentForm = new ew.Form("fcustomerview", "view");
    loadjs.done("fcustomerview");
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
<form name="fcustomerview" id="fcustomerview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="customer">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->customer_name->Visible) { // customer_name ?>
    <tr id="r_customer_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_customer_customer_name"><?= $Page->customer_name->caption() ?></span></td>
        <td data-name="customer_name" <?= $Page->customer_name->cellAttributes() ?>>
<span id="el_customer_customer_name" data-page="1">
<span<?= $Page->customer_name->viewAttributes() ?>>
<?= $Page->customer_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <tr id="r_address">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_customer_address"><?= $Page->address->caption() ?></span></td>
        <td data-name="address" <?= $Page->address->cellAttributes() ?>>
<span id="el_customer_address" data-page="1">
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <tr id="r_city_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_customer_city_id"><?= $Page->city_id->caption() ?></span></td>
        <td data-name="city_id" <?= $Page->city_id->cellAttributes() ?>>
<span id="el_customer_city_id" data-page="1">
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->phone_number->Visible) { // phone_number ?>
    <tr id="r_phone_number">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_customer_phone_number"><?= $Page->phone_number->caption() ?></span></td>
        <td data-name="phone_number" <?= $Page->phone_number->cellAttributes() ?>>
<span id="el_customer_phone_number" data-page="1">
<span<?= $Page->phone_number->viewAttributes() ?>>
<?= $Page->phone_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->contact->Visible) { // contact ?>
    <tr id="r_contact">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_customer_contact"><?= $Page->contact->caption() ?></span></td>
        <td data-name="contact" <?= $Page->contact->cellAttributes() ?>>
<span id="el_customer_contact" data-page="1">
<span<?= $Page->contact->viewAttributes() ?>>
<?= $Page->contact->getViewValue() ?></span>
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
