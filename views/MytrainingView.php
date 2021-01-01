<?php

namespace MEM\prjMitralPHP;

// Page object
$MytrainingView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmytrainingview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fmytrainingview = currentForm = new ew.Form("fmytrainingview", "view");
    loadjs.done("fmytrainingview");
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
<form name="fmytrainingview" id="fmytrainingview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mytraining">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->training_name->Visible) { // training_name ?>
    <tr id="r_training_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytraining_training_name"><?= $Page->training_name->caption() ?></span></td>
        <td data-name="training_name" <?= $Page->training_name->cellAttributes() ?>>
<span id="el_mytraining_training_name">
<span<?= $Page->training_name->viewAttributes() ?>>
<?= $Page->training_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->training_start->Visible) { // training_start ?>
    <tr id="r_training_start">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytraining_training_start"><?= $Page->training_start->caption() ?></span></td>
        <td data-name="training_start" <?= $Page->training_start->cellAttributes() ?>>
<span id="el_mytraining_training_start">
<span<?= $Page->training_start->viewAttributes() ?>>
<?= $Page->training_start->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->training_end->Visible) { // training_end ?>
    <tr id="r_training_end">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytraining_training_end"><?= $Page->training_end->caption() ?></span></td>
        <td data-name="training_end" <?= $Page->training_end->cellAttributes() ?>>
<span id="el_mytraining_training_end">
<span<?= $Page->training_end->viewAttributes() ?>>
<?= $Page->training_end->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->training_company->Visible) { // training_company ?>
    <tr id="r_training_company">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytraining_training_company"><?= $Page->training_company->caption() ?></span></td>
        <td data-name="training_company" <?= $Page->training_company->cellAttributes() ?>>
<span id="el_mytraining_training_company">
<span<?= $Page->training_company->viewAttributes() ?>>
<?= $Page->training_company->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->certificate_start->Visible) { // certificate_start ?>
    <tr id="r_certificate_start">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytraining_certificate_start"><?= $Page->certificate_start->caption() ?></span></td>
        <td data-name="certificate_start" <?= $Page->certificate_start->cellAttributes() ?>>
<span id="el_mytraining_certificate_start">
<span<?= $Page->certificate_start->viewAttributes() ?>>
<?= $Page->certificate_start->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->certificate_end->Visible) { // certificate_end ?>
    <tr id="r_certificate_end">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytraining_certificate_end"><?= $Page->certificate_end->caption() ?></span></td>
        <td data-name="certificate_end" <?= $Page->certificate_end->cellAttributes() ?>>
<span id="el_mytraining_certificate_end">
<span<?= $Page->certificate_end->viewAttributes() ?>>
<?= $Page->certificate_end->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <tr id="r_notes">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytraining_notes"><?= $Page->notes->caption() ?></span></td>
        <td data-name="notes" <?= $Page->notes->cellAttributes() ?>>
<span id="el_mytraining_notes">
<span<?= $Page->notes->viewAttributes() ?>>
<?= $Page->notes->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->training_document->Visible) { // training_document ?>
    <tr id="r_training_document">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mytraining_training_document"><?= $Page->training_document->caption() ?></span></td>
        <td data-name="training_document" <?= $Page->training_document->cellAttributes() ?>>
<span id="el_mytraining_training_document">
<span<?= $Page->training_document->viewAttributes() ?>>
<?= GetFileViewTag($Page->training_document, $Page->training_document->getViewValue(), false) ?>
</span>
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
