<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeAssetView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var femployee_assetview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    femployee_assetview = currentForm = new ew.Form("femployee_assetview", "view");
    loadjs.done("femployee_assetview");
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
<form name="femployee_assetview" id="femployee_assetview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_asset">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <tr id="r_employee_username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_asset_employee_username"><?= $Page->employee_username->caption() ?></span></td>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_employee_asset_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asset_name->Visible) { // asset_name ?>
    <tr id="r_asset_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_asset_asset_name"><?= $Page->asset_name->caption() ?></span></td>
        <td data-name="asset_name" <?= $Page->asset_name->cellAttributes() ?>>
<span id="el_employee_asset_asset_name">
<span<?= $Page->asset_name->viewAttributes() ?>>
<?= $Page->asset_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
    <tr id="r_year">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_asset_year"><?= $Page->year->caption() ?></span></td>
        <td data-name="year" <?= $Page->year->cellAttributes() ?>>
<span id="el_employee_asset_year">
<span<?= $Page->year->viewAttributes() ?>>
<?= $Page->year->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->serial_number->Visible) { // serial_number ?>
    <tr id="r_serial_number">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_asset_serial_number"><?= $Page->serial_number->caption() ?></span></td>
        <td data-name="serial_number" <?= $Page->serial_number->cellAttributes() ?>>
<span id="el_employee_asset_serial_number">
<span<?= $Page->serial_number->viewAttributes() ?>>
<?= $Page->serial_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->value->Visible) { // value ?>
    <tr id="r_value">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_asset_value"><?= $Page->value->caption() ?></span></td>
        <td data-name="value" <?= $Page->value->cellAttributes() ?>>
<span id="el_employee_asset_value">
<span<?= $Page->value->viewAttributes() ?>>
<?= $Page->value->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asset_received->Visible) { // asset_received ?>
    <tr id="r_asset_received">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_asset_asset_received"><?= $Page->asset_received->caption() ?></span></td>
        <td data-name="asset_received" <?= $Page->asset_received->cellAttributes() ?>>
<span id="el_employee_asset_asset_received">
<span<?= $Page->asset_received->viewAttributes() ?>>
<?= $Page->asset_received->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asset_return->Visible) { // asset_return ?>
    <tr id="r_asset_return">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_asset_asset_return"><?= $Page->asset_return->caption() ?></span></td>
        <td data-name="asset_return" <?= $Page->asset_return->cellAttributes() ?>>
<span id="el_employee_asset_asset_return">
<span<?= $Page->asset_return->viewAttributes() ?>>
<?= $Page->asset_return->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asset_picture->Visible) { // asset_picture ?>
    <tr id="r_asset_picture">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_asset_asset_picture"><?= $Page->asset_picture->caption() ?></span></td>
        <td data-name="asset_picture" <?= $Page->asset_picture->cellAttributes() ?>>
<span id="el_employee_asset_asset_picture">
<span<?= $Page->asset_picture->viewAttributes() ?>>
<?= GetFileViewTag($Page->asset_picture, $Page->asset_picture->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <tr id="r_notes">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_employee_asset_notes"><?= $Page->notes->caption() ?></span></td>
        <td data-name="notes" <?= $Page->notes->cellAttributes() ?>>
<span id="el_employee_asset_notes">
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
