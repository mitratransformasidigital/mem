<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterCityDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fmaster_citydelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fmaster_citydelete = currentForm = new ew.Form("fmaster_citydelete", "delete");
    loadjs.done("fmaster_citydelete");
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
<form name="fmaster_citydelete" id="fmaster_citydelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_city">
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
<?php if ($Page->province_id->Visible) { // province_id ?>
        <th class="<?= $Page->province_id->headerCellClass() ?>"><span id="elh_master_city_province_id" class="master_city_province_id"><?= $Page->province_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <th class="<?= $Page->city_id->headerCellClass() ?>"><span id="elh_master_city_city_id" class="master_city_city_id"><?= $Page->city_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->city->Visible) { // city ?>
        <th class="<?= $Page->city->headerCellClass() ?>"><span id="elh_master_city_city" class="master_city_city"><?= $Page->city->caption() ?></span></th>
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
<?php if ($Page->province_id->Visible) { // province_id ?>
        <td <?= $Page->province_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_city_province_id" class="master_city_province_id">
<span<?= $Page->province_id->viewAttributes() ?>>
<?= $Page->province_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <td <?= $Page->city_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_city_city_id" class="master_city_city_id">
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->city->Visible) { // city ?>
        <td <?= $Page->city->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_city_city" class="master_city_city">
<span<?= $Page->city->viewAttributes() ?>>
<?= $Page->city->getViewValue() ?></span>
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
