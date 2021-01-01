<?php

namespace MEM\prjMitralPHP;

// Page object
$ActivityView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var factivityview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    factivityview = currentForm = new ew.Form("factivityview", "view");
    loadjs.done("factivityview");
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
<form name="factivityview" id="factivityview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="activity">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <tr id="r_employee_username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_activity_employee_username"><?= $Page->employee_username->caption() ?></span></td>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_activity_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activity_date->Visible) { // activity_date ?>
    <tr id="r_activity_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_activity_activity_date"><?= $Page->activity_date->caption() ?></span></td>
        <td data-name="activity_date" <?= $Page->activity_date->cellAttributes() ?>>
<span id="el_activity_activity_date">
<span<?= $Page->activity_date->viewAttributes() ?>>
<?= $Page->activity_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
    <tr id="r_time_in">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_activity_time_in"><?= $Page->time_in->caption() ?></span></td>
        <td data-name="time_in" <?= $Page->time_in->cellAttributes() ?>>
<span id="el_activity_time_in">
<span<?= $Page->time_in->viewAttributes() ?>>
<?= $Page->time_in->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
    <tr id="r_time_out">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_activity_time_out"><?= $Page->time_out->caption() ?></span></td>
        <td data-name="time_out" <?= $Page->time_out->cellAttributes() ?>>
<span id="el_activity_time_out">
<span<?= $Page->time_out->viewAttributes() ?>>
<?= $Page->time_out->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <tr id="r__action">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_activity__action"><?= $Page->_action->caption() ?></span></td>
        <td data-name="_action" <?= $Page->_action->cellAttributes() ?>>
<span id="el_activity__action">
<span<?= $Page->_action->viewAttributes() ?>>
<?= $Page->_action->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
    <tr id="r_document">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_activity_document"><?= $Page->document->caption() ?></span></td>
        <td data-name="document" <?= $Page->document->cellAttributes() ?>>
<span id="el_activity_document">
<span<?= $Page->document->viewAttributes() ?>>
<?= GetFileViewTag($Page->document, $Page->document->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <tr id="r_notes">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_activity_notes"><?= $Page->notes->caption() ?></span></td>
        <td data-name="notes" <?= $Page->notes->cellAttributes() ?>>
<span id="el_activity_notes">
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
