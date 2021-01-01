<?php

namespace MEM\prjMitralPHP;

// Page object
$MytrainingDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fmytrainingdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fmytrainingdelete = currentForm = new ew.Form("fmytrainingdelete", "delete");
    loadjs.done("fmytrainingdelete");
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
<form name="fmytrainingdelete" id="fmytrainingdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mytraining">
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
<?php if ($Page->training_name->Visible) { // training_name ?>
        <th class="<?= $Page->training_name->headerCellClass() ?>"><span id="elh_mytraining_training_name" class="mytraining_training_name"><?= $Page->training_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->training_start->Visible) { // training_start ?>
        <th class="<?= $Page->training_start->headerCellClass() ?>"><span id="elh_mytraining_training_start" class="mytraining_training_start"><?= $Page->training_start->caption() ?></span></th>
<?php } ?>
<?php if ($Page->training_end->Visible) { // training_end ?>
        <th class="<?= $Page->training_end->headerCellClass() ?>"><span id="elh_mytraining_training_end" class="mytraining_training_end"><?= $Page->training_end->caption() ?></span></th>
<?php } ?>
<?php if ($Page->training_company->Visible) { // training_company ?>
        <th class="<?= $Page->training_company->headerCellClass() ?>"><span id="elh_mytraining_training_company" class="mytraining_training_company"><?= $Page->training_company->caption() ?></span></th>
<?php } ?>
<?php if ($Page->certificate_start->Visible) { // certificate_start ?>
        <th class="<?= $Page->certificate_start->headerCellClass() ?>"><span id="elh_mytraining_certificate_start" class="mytraining_certificate_start"><?= $Page->certificate_start->caption() ?></span></th>
<?php } ?>
<?php if ($Page->certificate_end->Visible) { // certificate_end ?>
        <th class="<?= $Page->certificate_end->headerCellClass() ?>"><span id="elh_mytraining_certificate_end" class="mytraining_certificate_end"><?= $Page->certificate_end->caption() ?></span></th>
<?php } ?>
<?php if ($Page->training_document->Visible) { // training_document ?>
        <th class="<?= $Page->training_document->headerCellClass() ?>"><span id="elh_mytraining_training_document" class="mytraining_training_document"><?= $Page->training_document->caption() ?></span></th>
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
<?php if ($Page->training_name->Visible) { // training_name ?>
        <td <?= $Page->training_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytraining_training_name" class="mytraining_training_name">
<span<?= $Page->training_name->viewAttributes() ?>>
<?= $Page->training_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->training_start->Visible) { // training_start ?>
        <td <?= $Page->training_start->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytraining_training_start" class="mytraining_training_start">
<span<?= $Page->training_start->viewAttributes() ?>>
<?= $Page->training_start->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->training_end->Visible) { // training_end ?>
        <td <?= $Page->training_end->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytraining_training_end" class="mytraining_training_end">
<span<?= $Page->training_end->viewAttributes() ?>>
<?= $Page->training_end->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->training_company->Visible) { // training_company ?>
        <td <?= $Page->training_company->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytraining_training_company" class="mytraining_training_company">
<span<?= $Page->training_company->viewAttributes() ?>>
<?= $Page->training_company->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->certificate_start->Visible) { // certificate_start ?>
        <td <?= $Page->certificate_start->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytraining_certificate_start" class="mytraining_certificate_start">
<span<?= $Page->certificate_start->viewAttributes() ?>>
<?= $Page->certificate_start->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->certificate_end->Visible) { // certificate_end ?>
        <td <?= $Page->certificate_end->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytraining_certificate_end" class="mytraining_certificate_end">
<span<?= $Page->certificate_end->viewAttributes() ?>>
<?= $Page->certificate_end->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->training_document->Visible) { // training_document ?>
        <td <?= $Page->training_document->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytraining_training_document" class="mytraining_training_document">
<span<?= $Page->training_document->viewAttributes() ?>>
<?= GetFileViewTag($Page->training_document, $Page->training_document->getViewValue(), false) ?>
</span>
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
