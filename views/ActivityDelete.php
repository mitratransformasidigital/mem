<?php

namespace MEM\prjMitralPHP;

// Page object
$ActivityDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var factivitydelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    factivitydelete = currentForm = new ew.Form("factivitydelete", "delete");
    loadjs.done("factivitydelete");
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
<form name="factivitydelete" id="factivitydelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="activity">
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
        <th class="<?= $Page->employee_username->headerCellClass() ?>"><span id="elh_activity_employee_username" class="activity_employee_username"><?= $Page->employee_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activity_date->Visible) { // activity_date ?>
        <th class="<?= $Page->activity_date->headerCellClass() ?>"><span id="elh_activity_activity_date" class="activity_activity_date"><?= $Page->activity_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
        <th class="<?= $Page->time_in->headerCellClass() ?>"><span id="elh_activity_time_in" class="activity_time_in"><?= $Page->time_in->caption() ?></span></th>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
        <th class="<?= $Page->time_out->headerCellClass() ?>"><span id="elh_activity_time_out" class="activity_time_out"><?= $Page->time_out->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
        <th class="<?= $Page->_action->headerCellClass() ?>"><span id="elh_activity__action" class="activity__action"><?= $Page->_action->caption() ?></span></th>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
        <th class="<?= $Page->document->headerCellClass() ?>"><span id="elh_activity_document" class="activity_document"><?= $Page->document->caption() ?></span></th>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
        <th class="<?= $Page->notes->headerCellClass() ?>"><span id="elh_activity_notes" class="activity_notes"><?= $Page->notes->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_activity_employee_username" class="activity_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->activity_date->Visible) { // activity_date ?>
        <td <?= $Page->activity_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_activity_activity_date" class="activity_activity_date">
<span<?= $Page->activity_date->viewAttributes() ?>>
<?= $Page->activity_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
        <td <?= $Page->time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_activity_time_in" class="activity_time_in">
<span<?= $Page->time_in->viewAttributes() ?>>
<?= $Page->time_in->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
        <td <?= $Page->time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_activity_time_out" class="activity_time_out">
<span<?= $Page->time_out->viewAttributes() ?>>
<?= $Page->time_out->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
        <td <?= $Page->_action->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_activity__action" class="activity__action">
<span<?= $Page->_action->viewAttributes() ?>>
<?= $Page->_action->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
        <td <?= $Page->document->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_activity_document" class="activity_document">
<span<?= $Page->document->viewAttributes() ?>>
<?= GetFileViewTag($Page->document, $Page->document->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
        <td <?= $Page->notes->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_activity_notes" class="activity_notes">
<span<?= $Page->notes->viewAttributes() ?>>
<?= $Page->notes->getViewValue() ?></span>
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
