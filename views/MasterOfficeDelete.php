<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterOfficeDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fmaster_officedelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fmaster_officedelete = currentForm = new ew.Form("fmaster_officedelete", "delete");
    loadjs.done("fmaster_officedelete");
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
<form name="fmaster_officedelete" id="fmaster_officedelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_office">
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
<?php if ($Page->office->Visible) { // office ?>
        <th class="<?= $Page->office->headerCellClass() ?>"><span id="elh_master_office_office" class="master_office_office"><?= $Page->office->caption() ?></span></th>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
        <th class="<?= $Page->address->headerCellClass() ?>"><span id="elh_master_office_address" class="master_office_address"><?= $Page->address->caption() ?></span></th>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <th class="<?= $Page->city_id->headerCellClass() ?>"><span id="elh_master_office_city_id" class="master_office_city_id"><?= $Page->city_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->phone_number->Visible) { // phone_number ?>
        <th class="<?= $Page->phone_number->headerCellClass() ?>"><span id="elh_master_office_phone_number" class="master_office_phone_number"><?= $Page->phone_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->contact_name->Visible) { // contact_name ?>
        <th class="<?= $Page->contact_name->headerCellClass() ?>"><span id="elh_master_office_contact_name" class="master_office_contact_name"><?= $Page->contact_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <th class="<?= $Page->description->headerCellClass() ?>"><span id="elh_master_office_description" class="master_office_description"><?= $Page->description->caption() ?></span></th>
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
<?php if ($Page->office->Visible) { // office ?>
        <td <?= $Page->office->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_office" class="master_office_office">
<span<?= $Page->office->viewAttributes() ?>>
<?= $Page->office->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
        <td <?= $Page->address->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_address" class="master_office_address">
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <td <?= $Page->city_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_city_id" class="master_office_city_id">
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->phone_number->Visible) { // phone_number ?>
        <td <?= $Page->phone_number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_phone_number" class="master_office_phone_number">
<span<?= $Page->phone_number->viewAttributes() ?>>
<?= $Page->phone_number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->contact_name->Visible) { // contact_name ?>
        <td <?= $Page->contact_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_contact_name" class="master_office_contact_name">
<span<?= $Page->contact_name->viewAttributes() ?>>
<?= $Page->contact_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <td <?= $Page->description->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_description" class="master_office_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
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
