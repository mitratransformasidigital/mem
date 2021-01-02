<?php

namespace MEM\prjMitralPHP;

// Page object
$PermitView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpermitview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpermitview = currentForm = new ew.Form("fpermitview", "view");
    loadjs.done("fpermitview");
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
<form name="fpermitview" id="fpermitview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="permit">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <tr id="r_employee_username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_permit_employee_username"><?= $Page->employee_username->caption() ?></span></td>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_permit_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->start_date->Visible) { // start_date ?>
    <tr id="r_start_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_permit_start_date"><?= $Page->start_date->caption() ?></span></td>
        <td data-name="start_date" <?= $Page->start_date->cellAttributes() ?>>
<span id="el_permit_start_date">
<span<?= $Page->start_date->viewAttributes() ?>>
<?= $Page->start_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->end_date->Visible) { // end_date ?>
    <tr id="r_end_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_permit_end_date"><?= $Page->end_date->caption() ?></span></td>
        <td data-name="end_date" <?= $Page->end_date->cellAttributes() ?>>
<span id="el_permit_end_date">
<span<?= $Page->end_date->viewAttributes() ?>>
<?= $Page->end_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->permit_type->Visible) { // permit_type ?>
    <tr id="r_permit_type">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_permit_permit_type"><?= $Page->permit_type->caption() ?></span></td>
        <td data-name="permit_type" <?= $Page->permit_type->cellAttributes() ?>>
<span id="el_permit_permit_type">
<span<?= $Page->permit_type->viewAttributes() ?>>
<?php if (!EmptyString($Page->permit_type->getViewValue()) && $Page->permit_type->linkAttributes() != "") { ?>
<a<?= $Page->permit_type->linkAttributes() ?>><?= $Page->permit_type->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->permit_type->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
    <tr id="r_document">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_permit_document"><?= $Page->document->caption() ?></span></td>
        <td data-name="document" <?= $Page->document->cellAttributes() ?>>
<span id="el_permit_document">
<span<?= $Page->document->viewAttributes() ?>>
<?= GetFileViewTag($Page->document, $Page->document->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->note->Visible) { // note ?>
    <tr id="r_note">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_permit_note"><?= $Page->note->caption() ?></span></td>
        <td data-name="note" <?= $Page->note->cellAttributes() ?>>
<span id="el_permit_note">
<span<?= $Page->note->viewAttributes() ?>>
<?= $Page->note->getViewValue() ?></span>
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
