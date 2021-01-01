<?php

namespace MEM\prjMitralPHP;

// Page object
$PermitDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpermitdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpermitdelete = currentForm = new ew.Form("fpermitdelete", "delete");
    loadjs.done("fpermitdelete");
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
<form name="fpermitdelete" id="fpermitdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="permit">
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
        <th class="<?= $Page->employee_username->headerCellClass() ?>"><span id="elh_permit_employee_username" class="permit_employee_username"><?= $Page->employee_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->permit_date->Visible) { // permit_date ?>
        <th class="<?= $Page->permit_date->headerCellClass() ?>"><span id="elh_permit_permit_date" class="permit_permit_date"><?= $Page->permit_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->permit_type->Visible) { // permit_type ?>
        <th class="<?= $Page->permit_type->headerCellClass() ?>"><span id="elh_permit_permit_type" class="permit_permit_type"><?= $Page->permit_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
        <th class="<?= $Page->document->headerCellClass() ?>"><span id="elh_permit_document" class="permit_document"><?= $Page->document->caption() ?></span></th>
<?php } ?>
<?php if ($Page->note->Visible) { // note ?>
        <th class="<?= $Page->note->headerCellClass() ?>"><span id="elh_permit_note" class="permit_note"><?= $Page->note->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_permit_employee_username" class="permit_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->permit_date->Visible) { // permit_date ?>
        <td <?= $Page->permit_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_permit_permit_date" class="permit_permit_date">
<span<?= $Page->permit_date->viewAttributes() ?>>
<?= $Page->permit_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->permit_type->Visible) { // permit_type ?>
        <td <?= $Page->permit_type->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_permit_permit_type" class="permit_permit_type">
<span<?= $Page->permit_type->viewAttributes() ?>>
<?php if (!EmptyString($Page->permit_type->getViewValue()) && $Page->permit_type->linkAttributes() != "") { ?>
<a<?= $Page->permit_type->linkAttributes() ?>><?= $Page->permit_type->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->permit_type->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
        <td <?= $Page->document->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_permit_document" class="permit_document">
<span<?= $Page->document->viewAttributes() ?>>
<?= GetFileViewTag($Page->document, $Page->document->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->note->Visible) { // note ?>
        <td <?= $Page->note->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_permit_note" class="permit_note">
<span<?= $Page->note->viewAttributes() ?>>
<?= $Page->note->getViewValue() ?></span>
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
