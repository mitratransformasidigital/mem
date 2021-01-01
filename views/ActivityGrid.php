<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("ActivityGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.activity) ew.vars.tables.activity = <?= JsonEncode(GetClientVar("tables", "activity")) ?>;
var currentForm, currentPageID;
var factivitygrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    factivitygrid = new ew.Form("factivitygrid", "grid");
    factivitygrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.activity.fields;
    factivitygrid.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["activity_date", [fields.activity_date.required ? ew.Validators.required(fields.activity_date.caption) : null, ew.Validators.datetime(5)], fields.activity_date.isInvalid],
        ["time_in", [fields.time_in.required ? ew.Validators.required(fields.time_in.caption) : null, ew.Validators.time], fields.time_in.isInvalid],
        ["time_out", [fields.time_out.required ? ew.Validators.required(fields.time_out.caption) : null, ew.Validators.time], fields.time_out.isInvalid],
        ["_action", [fields._action.required ? ew.Validators.required(fields._action.caption) : null], fields._action.isInvalid],
        ["document", [fields.document.required ? ew.Validators.fileRequired(fields.document.caption) : null], fields.document.isInvalid],
        ["notes", [fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = factivitygrid,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    factivitygrid.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);
            var checkrow = (gridinsert) ? !this.emptyRow(rowIndex) : true;
            if (checkrow) {
                addcnt++;

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
            } // End Grid Add checking
        }
        return true;
    }

    // Check empty row
    factivitygrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "employee_username", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "activity_date", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "time_in", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "time_out", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "_action", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "document", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "notes", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    factivitygrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    factivitygrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    factivitygrid.lists.employee_username = <?= $Grid->employee_username->toClientList($Grid) ?>;
    loadjs.done("factivitygrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> activity">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="factivitygrid" class="ew-form ew-list-form form-inline">
<div id="gmp_activity" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_activitygrid" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = ROWTYPE_HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <th data-name="employee_username" class="<?= $Grid->employee_username->headerCellClass() ?>"><div id="elh_activity_employee_username" class="activity_employee_username"><?= $Grid->renderSort($Grid->employee_username) ?></div></th>
<?php } ?>
<?php if ($Grid->activity_date->Visible) { // activity_date ?>
        <th data-name="activity_date" class="<?= $Grid->activity_date->headerCellClass() ?>"><div id="elh_activity_activity_date" class="activity_activity_date"><?= $Grid->renderSort($Grid->activity_date) ?></div></th>
<?php } ?>
<?php if ($Grid->time_in->Visible) { // time_in ?>
        <th data-name="time_in" class="<?= $Grid->time_in->headerCellClass() ?>"><div id="elh_activity_time_in" class="activity_time_in"><?= $Grid->renderSort($Grid->time_in) ?></div></th>
<?php } ?>
<?php if ($Grid->time_out->Visible) { // time_out ?>
        <th data-name="time_out" class="<?= $Grid->time_out->headerCellClass() ?>"><div id="elh_activity_time_out" class="activity_time_out"><?= $Grid->renderSort($Grid->time_out) ?></div></th>
<?php } ?>
<?php if ($Grid->_action->Visible) { // action ?>
        <th data-name="_action" class="<?= $Grid->_action->headerCellClass() ?>"><div id="elh_activity__action" class="activity__action"><?= $Grid->renderSort($Grid->_action) ?></div></th>
<?php } ?>
<?php if ($Grid->document->Visible) { // document ?>
        <th data-name="document" class="<?= $Grid->document->headerCellClass() ?>"><div id="elh_activity_document" class="activity_document"><?= $Grid->renderSort($Grid->document) ?></div></th>
<?php } ?>
<?php if ($Grid->notes->Visible) { // notes ?>
        <th data-name="notes" class="<?= $Grid->notes->headerCellClass() ?>"><div id="elh_activity_notes" class="activity_notes"><?= $Grid->renderSort($Grid->notes) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
$Grid->StartRecord = 1;
$Grid->StopRecord = $Grid->TotalRecords; // Show all records

// Restore number of post back records
if ($CurrentForm && ($Grid->isConfirm() || $Grid->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Grid->FormKeyCountName) && ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm())) {
        $Grid->KeyCount = $CurrentForm->getValue($Grid->FormKeyCountName);
        $Grid->StopRecord = $Grid->StartRecord + $Grid->KeyCount - 1;
    }
}
$Grid->RecordCount = $Grid->StartRecord - 1;
if ($Grid->Recordset && !$Grid->Recordset->EOF) {
    // Nothing to do
} elseif (!$Grid->AllowAddDeleteRow && $Grid->StopRecord == 0) {
    $Grid->StopRecord = $Grid->GridAddRowCount;
}

// Initialize aggregate
$Grid->RowType = ROWTYPE_AGGREGATEINIT;
$Grid->resetAttributes();
$Grid->renderRow();
if ($Grid->isGridAdd())
    $Grid->RowIndex = 0;
if ($Grid->isGridEdit())
    $Grid->RowIndex = 0;
while ($Grid->RecordCount < $Grid->StopRecord) {
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->RowCount++;
        if ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm()) {
            $Grid->RowIndex++;
            $CurrentForm->Index = $Grid->RowIndex;
            if ($CurrentForm->hasValue($Grid->FormActionName) && ($Grid->isConfirm() || $Grid->EventCancelled)) {
                $Grid->RowAction = strval($CurrentForm->getValue($Grid->FormActionName));
            } elseif ($Grid->isGridAdd()) {
                $Grid->RowAction = "insert";
            } else {
                $Grid->RowAction = "";
            }
        }

        // Set up key count
        $Grid->KeyCount = $Grid->RowIndex;

        // Init row class and style
        $Grid->resetAttributes();
        $Grid->CssClass = "";
        if ($Grid->isGridAdd()) {
            if ($Grid->CurrentMode == "copy") {
                $Grid->loadRowValues($Grid->Recordset); // Load row values
                $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
            } else {
                $Grid->loadRowValues(); // Load default values
                $Grid->OldKey = "";
            }
        } else {
            $Grid->loadRowValues($Grid->Recordset); // Load row values
            $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
        }
        $Grid->setKey($Grid->OldKey);
        $Grid->RowType = ROWTYPE_VIEW; // Render view
        if ($Grid->isGridAdd()) { // Grid add
            $Grid->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Grid->isGridAdd() && $Grid->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->isGridEdit()) { // Grid edit
            if ($Grid->EventCancelled) {
                $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
            }
            if ($Grid->RowAction == "insert") {
                $Grid->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Grid->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Grid->isGridEdit() && ($Grid->RowType == ROWTYPE_EDIT || $Grid->RowType == ROWTYPE_ADD) && $Grid->EventCancelled) { // Update failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->RowType == ROWTYPE_EDIT) { // Edit row
            $Grid->EditRowCount++;
        }
        if ($Grid->isConfirm()) { // Confirm row
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }

        // Set up row id / data-rowindex
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_activity", "data-rowtype" => $Grid->RowType]);

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();

        // Skip delete row / empty row for confirm page
        if ($Grid->RowAction != "delete" && $Grid->RowAction != "insertdelete" && !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow())) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username" <?= $Grid->employee_username->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->employee_username->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_activity_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_activity_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="activity_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="activity"
        data-field="x_employee_username"
        data-value-separator="<?= $Grid->employee_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->employee_username->getPlaceHolder()) ?>"
        <?= $Grid->employee_username->editAttributes() ?>>
        <?= $Grid->employee_username->selectOptionListHtml("x{$Grid->RowIndex}_employee_username") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->employee_username->getErrorMessage() ?></div>
<?= $Grid->employee_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_employee_username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='activity_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "activity_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.activity.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="activity" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->employee_username->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_activity_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_activity_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="activity_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="activity"
        data-field="x_employee_username"
        data-value-separator="<?= $Grid->employee_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->employee_username->getPlaceHolder()) ?>"
        <?= $Grid->employee_username->editAttributes() ?>>
        <?= $Grid->employee_username->selectOptionListHtml("x{$Grid->RowIndex}_employee_username") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->employee_username->getErrorMessage() ?></div>
<?= $Grid->employee_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_employee_username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='activity_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "activity_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.activity.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_activity_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<?= $Grid->employee_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="activity" data-field="x_employee_username" data-hidden="1" name="factivitygrid$x<?= $Grid->RowIndex ?>_employee_username" id="factivitygrid$x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<input type="hidden" data-table="activity" data-field="x_employee_username" data-hidden="1" name="factivitygrid$o<?= $Grid->RowIndex ?>_employee_username" id="factivitygrid$o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->activity_date->Visible) { // activity_date ?>
        <td data-name="activity_date" <?= $Grid->activity_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_activity_activity_date" class="form-group">
<input type="<?= $Grid->activity_date->getInputTextType() ?>" data-table="activity" data-field="x_activity_date" data-format="5" name="x<?= $Grid->RowIndex ?>_activity_date" id="x<?= $Grid->RowIndex ?>_activity_date" placeholder="<?= HtmlEncode($Grid->activity_date->getPlaceHolder()) ?>" value="<?= $Grid->activity_date->EditValue ?>"<?= $Grid->activity_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->activity_date->getErrorMessage() ?></div>
<?php if (!$Grid->activity_date->ReadOnly && !$Grid->activity_date->Disabled && !isset($Grid->activity_date->EditAttrs["readonly"]) && !isset($Grid->activity_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitygrid", "datetimepicker"], function() {
    ew.createDateTimePicker("factivitygrid", "x<?= $Grid->RowIndex ?>_activity_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="activity" data-field="x_activity_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_activity_date" id="o<?= $Grid->RowIndex ?>_activity_date" value="<?= HtmlEncode($Grid->activity_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_activity_activity_date" class="form-group">
<input type="<?= $Grid->activity_date->getInputTextType() ?>" data-table="activity" data-field="x_activity_date" data-format="5" name="x<?= $Grid->RowIndex ?>_activity_date" id="x<?= $Grid->RowIndex ?>_activity_date" placeholder="<?= HtmlEncode($Grid->activity_date->getPlaceHolder()) ?>" value="<?= $Grid->activity_date->EditValue ?>"<?= $Grid->activity_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->activity_date->getErrorMessage() ?></div>
<?php if (!$Grid->activity_date->ReadOnly && !$Grid->activity_date->Disabled && !isset($Grid->activity_date->EditAttrs["readonly"]) && !isset($Grid->activity_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitygrid", "datetimepicker"], function() {
    ew.createDateTimePicker("factivitygrid", "x<?= $Grid->RowIndex ?>_activity_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_activity_activity_date">
<span<?= $Grid->activity_date->viewAttributes() ?>>
<?= $Grid->activity_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="activity" data-field="x_activity_date" data-hidden="1" name="factivitygrid$x<?= $Grid->RowIndex ?>_activity_date" id="factivitygrid$x<?= $Grid->RowIndex ?>_activity_date" value="<?= HtmlEncode($Grid->activity_date->FormValue) ?>">
<input type="hidden" data-table="activity" data-field="x_activity_date" data-hidden="1" name="factivitygrid$o<?= $Grid->RowIndex ?>_activity_date" id="factivitygrid$o<?= $Grid->RowIndex ?>_activity_date" value="<?= HtmlEncode($Grid->activity_date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->time_in->Visible) { // time_in ?>
        <td data-name="time_in" <?= $Grid->time_in->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_activity_time_in" class="form-group">
<input type="<?= $Grid->time_in->getInputTextType() ?>" data-table="activity" data-field="x_time_in" name="x<?= $Grid->RowIndex ?>_time_in" id="x<?= $Grid->RowIndex ?>_time_in" placeholder="<?= HtmlEncode($Grid->time_in->getPlaceHolder()) ?>" value="<?= $Grid->time_in->EditValue ?>"<?= $Grid->time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->time_in->getErrorMessage() ?></div>
<?php if (!$Grid->time_in->ReadOnly && !$Grid->time_in->Disabled && !isset($Grid->time_in->EditAttrs["readonly"]) && !isset($Grid->time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitygrid", "timepicker"], function() {
    ew.createTimePicker("factivitygrid", "x<?= $Grid->RowIndex ?>_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="activity" data-field="x_time_in" data-hidden="1" name="o<?= $Grid->RowIndex ?>_time_in" id="o<?= $Grid->RowIndex ?>_time_in" value="<?= HtmlEncode($Grid->time_in->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_activity_time_in" class="form-group">
<input type="<?= $Grid->time_in->getInputTextType() ?>" data-table="activity" data-field="x_time_in" name="x<?= $Grid->RowIndex ?>_time_in" id="x<?= $Grid->RowIndex ?>_time_in" placeholder="<?= HtmlEncode($Grid->time_in->getPlaceHolder()) ?>" value="<?= $Grid->time_in->EditValue ?>"<?= $Grid->time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->time_in->getErrorMessage() ?></div>
<?php if (!$Grid->time_in->ReadOnly && !$Grid->time_in->Disabled && !isset($Grid->time_in->EditAttrs["readonly"]) && !isset($Grid->time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitygrid", "timepicker"], function() {
    ew.createTimePicker("factivitygrid", "x<?= $Grid->RowIndex ?>_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_activity_time_in">
<span<?= $Grid->time_in->viewAttributes() ?>>
<?= $Grid->time_in->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="activity" data-field="x_time_in" data-hidden="1" name="factivitygrid$x<?= $Grid->RowIndex ?>_time_in" id="factivitygrid$x<?= $Grid->RowIndex ?>_time_in" value="<?= HtmlEncode($Grid->time_in->FormValue) ?>">
<input type="hidden" data-table="activity" data-field="x_time_in" data-hidden="1" name="factivitygrid$o<?= $Grid->RowIndex ?>_time_in" id="factivitygrid$o<?= $Grid->RowIndex ?>_time_in" value="<?= HtmlEncode($Grid->time_in->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->time_out->Visible) { // time_out ?>
        <td data-name="time_out" <?= $Grid->time_out->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_activity_time_out" class="form-group">
<input type="<?= $Grid->time_out->getInputTextType() ?>" data-table="activity" data-field="x_time_out" name="x<?= $Grid->RowIndex ?>_time_out" id="x<?= $Grid->RowIndex ?>_time_out" placeholder="<?= HtmlEncode($Grid->time_out->getPlaceHolder()) ?>" value="<?= $Grid->time_out->EditValue ?>"<?= $Grid->time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->time_out->getErrorMessage() ?></div>
<?php if (!$Grid->time_out->ReadOnly && !$Grid->time_out->Disabled && !isset($Grid->time_out->EditAttrs["readonly"]) && !isset($Grid->time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitygrid", "timepicker"], function() {
    ew.createTimePicker("factivitygrid", "x<?= $Grid->RowIndex ?>_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="activity" data-field="x_time_out" data-hidden="1" name="o<?= $Grid->RowIndex ?>_time_out" id="o<?= $Grid->RowIndex ?>_time_out" value="<?= HtmlEncode($Grid->time_out->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_activity_time_out" class="form-group">
<input type="<?= $Grid->time_out->getInputTextType() ?>" data-table="activity" data-field="x_time_out" name="x<?= $Grid->RowIndex ?>_time_out" id="x<?= $Grid->RowIndex ?>_time_out" placeholder="<?= HtmlEncode($Grid->time_out->getPlaceHolder()) ?>" value="<?= $Grid->time_out->EditValue ?>"<?= $Grid->time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->time_out->getErrorMessage() ?></div>
<?php if (!$Grid->time_out->ReadOnly && !$Grid->time_out->Disabled && !isset($Grid->time_out->EditAttrs["readonly"]) && !isset($Grid->time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitygrid", "timepicker"], function() {
    ew.createTimePicker("factivitygrid", "x<?= $Grid->RowIndex ?>_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_activity_time_out">
<span<?= $Grid->time_out->viewAttributes() ?>>
<?= $Grid->time_out->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="activity" data-field="x_time_out" data-hidden="1" name="factivitygrid$x<?= $Grid->RowIndex ?>_time_out" id="factivitygrid$x<?= $Grid->RowIndex ?>_time_out" value="<?= HtmlEncode($Grid->time_out->FormValue) ?>">
<input type="hidden" data-table="activity" data-field="x_time_out" data-hidden="1" name="factivitygrid$o<?= $Grid->RowIndex ?>_time_out" id="factivitygrid$o<?= $Grid->RowIndex ?>_time_out" value="<?= HtmlEncode($Grid->time_out->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->_action->Visible) { // action ?>
        <td data-name="_action" <?= $Grid->_action->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_activity__action" class="form-group">
<textarea data-table="activity" data-field="x__action" name="x<?= $Grid->RowIndex ?>__action" id="x<?= $Grid->RowIndex ?>__action" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->_action->getPlaceHolder()) ?>"<?= $Grid->_action->editAttributes() ?>><?= $Grid->_action->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->_action->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="activity" data-field="x__action" data-hidden="1" name="o<?= $Grid->RowIndex ?>__action" id="o<?= $Grid->RowIndex ?>__action" value="<?= HtmlEncode($Grid->_action->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_activity__action" class="form-group">
<textarea data-table="activity" data-field="x__action" name="x<?= $Grid->RowIndex ?>__action" id="x<?= $Grid->RowIndex ?>__action" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->_action->getPlaceHolder()) ?>"<?= $Grid->_action->editAttributes() ?>><?= $Grid->_action->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->_action->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_activity__action">
<span<?= $Grid->_action->viewAttributes() ?>>
<?= $Grid->_action->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="activity" data-field="x__action" data-hidden="1" name="factivitygrid$x<?= $Grid->RowIndex ?>__action" id="factivitygrid$x<?= $Grid->RowIndex ?>__action" value="<?= HtmlEncode($Grid->_action->FormValue) ?>">
<input type="hidden" data-table="activity" data-field="x__action" data-hidden="1" name="factivitygrid$o<?= $Grid->RowIndex ?>__action" id="factivitygrid$o<?= $Grid->RowIndex ?>__action" value="<?= HtmlEncode($Grid->_action->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->document->Visible) { // document ?>
        <td data-name="document" <?= $Grid->document->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_activity_document" class="form-group activity_document">
<div id="fd_x<?= $Grid->RowIndex ?>_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->document->title() ?>" data-table="activity" data-field="x_document" name="x<?= $Grid->RowIndex ?>_document" id="x<?= $Grid->RowIndex ?>_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->document->editAttributes() ?><?= ($Grid->document->ReadOnly || $Grid->document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_document" id= "fn_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_document" id= "fa_x<?= $Grid->RowIndex ?>_document" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_document" id= "fs_x<?= $Grid->RowIndex ?>_document" value="500">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_document" id= "fx_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_document" id= "fm_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="activity" data-field="x_document" data-hidden="1" name="o<?= $Grid->RowIndex ?>_document" id="o<?= $Grid->RowIndex ?>_document" value="<?= HtmlEncode($Grid->document->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_activity_document">
<span<?= $Grid->document->viewAttributes() ?>>
<?= GetFileViewTag($Grid->document, $Grid->document->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_activity_document" class="form-group activity_document">
<div id="fd_x<?= $Grid->RowIndex ?>_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->document->title() ?>" data-table="activity" data-field="x_document" name="x<?= $Grid->RowIndex ?>_document" id="x<?= $Grid->RowIndex ?>_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->document->editAttributes() ?><?= ($Grid->document->ReadOnly || $Grid->document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_document" id= "fn_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_document" id= "fa_x<?= $Grid->RowIndex ?>_document" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_document") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_document" id= "fs_x<?= $Grid->RowIndex ?>_document" value="500">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_document" id= "fx_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_document" id= "fm_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->notes->Visible) { // notes ?>
        <td data-name="notes" <?= $Grid->notes->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_activity_notes" class="form-group">
<textarea data-table="activity" data-field="x_notes" name="x<?= $Grid->RowIndex ?>_notes" id="x<?= $Grid->RowIndex ?>_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->notes->getPlaceHolder()) ?>"<?= $Grid->notes->editAttributes() ?>><?= $Grid->notes->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->notes->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="activity" data-field="x_notes" data-hidden="1" name="o<?= $Grid->RowIndex ?>_notes" id="o<?= $Grid->RowIndex ?>_notes" value="<?= HtmlEncode($Grid->notes->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_activity_notes" class="form-group">
<textarea data-table="activity" data-field="x_notes" name="x<?= $Grid->RowIndex ?>_notes" id="x<?= $Grid->RowIndex ?>_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->notes->getPlaceHolder()) ?>"<?= $Grid->notes->editAttributes() ?>><?= $Grid->notes->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->notes->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_activity_notes">
<span<?= $Grid->notes->viewAttributes() ?>>
<?= $Grid->notes->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="activity" data-field="x_notes" data-hidden="1" name="factivitygrid$x<?= $Grid->RowIndex ?>_notes" id="factivitygrid$x<?= $Grid->RowIndex ?>_notes" value="<?= HtmlEncode($Grid->notes->FormValue) ?>">
<input type="hidden" data-table="activity" data-field="x_notes" data-hidden="1" name="factivitygrid$o<?= $Grid->RowIndex ?>_notes" id="factivitygrid$o<?= $Grid->RowIndex ?>_notes" value="<?= HtmlEncode($Grid->notes->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == ROWTYPE_ADD || $Grid->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["factivitygrid","load"], function () {
    factivitygrid.updateLists(<?= $Grid->RowIndex ?>);
});
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy")
        if (!$Grid->Recordset->EOF) {
            $Grid->Recordset->moveNext();
        }
}
?>
<?php
    if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy" || $Grid->CurrentMode == "edit") {
        $Grid->RowIndex = '$rowindex$';
        $Grid->loadRowValues();

        // Set row properties
        $Grid->resetAttributes();
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_activity", "data-rowtype" => ROWTYPE_ADD]);
        $Grid->RowAttrs->appendClass("ew-template");
        $Grid->RowType = ROWTYPE_ADD;

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();
        $Grid->StartRowCount = 0;
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowIndex);
?>
    <?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->employee_username->getSessionValue() != "") { ?>
<span id="el$rowindex$_activity_employee_username" class="form-group activity_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_activity_employee_username" class="form-group activity_employee_username">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="activity_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="activity"
        data-field="x_employee_username"
        data-value-separator="<?= $Grid->employee_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->employee_username->getPlaceHolder()) ?>"
        <?= $Grid->employee_username->editAttributes() ?>>
        <?= $Grid->employee_username->selectOptionListHtml("x{$Grid->RowIndex}_employee_username") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->employee_username->getErrorMessage() ?></div>
<?= $Grid->employee_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_employee_username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='activity_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "activity_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.activity.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_activity_employee_username" class="form-group activity_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="activity" data-field="x_employee_username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="activity" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->activity_date->Visible) { // activity_date ?>
        <td data-name="activity_date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_activity_activity_date" class="form-group activity_activity_date">
<input type="<?= $Grid->activity_date->getInputTextType() ?>" data-table="activity" data-field="x_activity_date" data-format="5" name="x<?= $Grid->RowIndex ?>_activity_date" id="x<?= $Grid->RowIndex ?>_activity_date" placeholder="<?= HtmlEncode($Grid->activity_date->getPlaceHolder()) ?>" value="<?= $Grid->activity_date->EditValue ?>"<?= $Grid->activity_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->activity_date->getErrorMessage() ?></div>
<?php if (!$Grid->activity_date->ReadOnly && !$Grid->activity_date->Disabled && !isset($Grid->activity_date->EditAttrs["readonly"]) && !isset($Grid->activity_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitygrid", "datetimepicker"], function() {
    ew.createDateTimePicker("factivitygrid", "x<?= $Grid->RowIndex ?>_activity_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_activity_activity_date" class="form-group activity_activity_date">
<span<?= $Grid->activity_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->activity_date->getDisplayValue($Grid->activity_date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="activity" data-field="x_activity_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_activity_date" id="x<?= $Grid->RowIndex ?>_activity_date" value="<?= HtmlEncode($Grid->activity_date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="activity" data-field="x_activity_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_activity_date" id="o<?= $Grid->RowIndex ?>_activity_date" value="<?= HtmlEncode($Grid->activity_date->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->time_in->Visible) { // time_in ?>
        <td data-name="time_in">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_activity_time_in" class="form-group activity_time_in">
<input type="<?= $Grid->time_in->getInputTextType() ?>" data-table="activity" data-field="x_time_in" name="x<?= $Grid->RowIndex ?>_time_in" id="x<?= $Grid->RowIndex ?>_time_in" placeholder="<?= HtmlEncode($Grid->time_in->getPlaceHolder()) ?>" value="<?= $Grid->time_in->EditValue ?>"<?= $Grid->time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->time_in->getErrorMessage() ?></div>
<?php if (!$Grid->time_in->ReadOnly && !$Grid->time_in->Disabled && !isset($Grid->time_in->EditAttrs["readonly"]) && !isset($Grid->time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitygrid", "timepicker"], function() {
    ew.createTimePicker("factivitygrid", "x<?= $Grid->RowIndex ?>_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_activity_time_in" class="form-group activity_time_in">
<span<?= $Grid->time_in->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->time_in->getDisplayValue($Grid->time_in->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="activity" data-field="x_time_in" data-hidden="1" name="x<?= $Grid->RowIndex ?>_time_in" id="x<?= $Grid->RowIndex ?>_time_in" value="<?= HtmlEncode($Grid->time_in->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="activity" data-field="x_time_in" data-hidden="1" name="o<?= $Grid->RowIndex ?>_time_in" id="o<?= $Grid->RowIndex ?>_time_in" value="<?= HtmlEncode($Grid->time_in->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->time_out->Visible) { // time_out ?>
        <td data-name="time_out">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_activity_time_out" class="form-group activity_time_out">
<input type="<?= $Grid->time_out->getInputTextType() ?>" data-table="activity" data-field="x_time_out" name="x<?= $Grid->RowIndex ?>_time_out" id="x<?= $Grid->RowIndex ?>_time_out" placeholder="<?= HtmlEncode($Grid->time_out->getPlaceHolder()) ?>" value="<?= $Grid->time_out->EditValue ?>"<?= $Grid->time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->time_out->getErrorMessage() ?></div>
<?php if (!$Grid->time_out->ReadOnly && !$Grid->time_out->Disabled && !isset($Grid->time_out->EditAttrs["readonly"]) && !isset($Grid->time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitygrid", "timepicker"], function() {
    ew.createTimePicker("factivitygrid", "x<?= $Grid->RowIndex ?>_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_activity_time_out" class="form-group activity_time_out">
<span<?= $Grid->time_out->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->time_out->getDisplayValue($Grid->time_out->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="activity" data-field="x_time_out" data-hidden="1" name="x<?= $Grid->RowIndex ?>_time_out" id="x<?= $Grid->RowIndex ?>_time_out" value="<?= HtmlEncode($Grid->time_out->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="activity" data-field="x_time_out" data-hidden="1" name="o<?= $Grid->RowIndex ?>_time_out" id="o<?= $Grid->RowIndex ?>_time_out" value="<?= HtmlEncode($Grid->time_out->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->_action->Visible) { // action ?>
        <td data-name="_action">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_activity__action" class="form-group activity__action">
<textarea data-table="activity" data-field="x__action" name="x<?= $Grid->RowIndex ?>__action" id="x<?= $Grid->RowIndex ?>__action" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->_action->getPlaceHolder()) ?>"<?= $Grid->_action->editAttributes() ?>><?= $Grid->_action->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->_action->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_activity__action" class="form-group activity__action">
<span<?= $Grid->_action->viewAttributes() ?>>
<?= $Grid->_action->ViewValue ?></span>
</span>
<input type="hidden" data-table="activity" data-field="x__action" data-hidden="1" name="x<?= $Grid->RowIndex ?>__action" id="x<?= $Grid->RowIndex ?>__action" value="<?= HtmlEncode($Grid->_action->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="activity" data-field="x__action" data-hidden="1" name="o<?= $Grid->RowIndex ?>__action" id="o<?= $Grid->RowIndex ?>__action" value="<?= HtmlEncode($Grid->_action->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->document->Visible) { // document ?>
        <td data-name="document">
<span id="el$rowindex$_activity_document" class="form-group activity_document">
<div id="fd_x<?= $Grid->RowIndex ?>_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->document->title() ?>" data-table="activity" data-field="x_document" name="x<?= $Grid->RowIndex ?>_document" id="x<?= $Grid->RowIndex ?>_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->document->editAttributes() ?><?= ($Grid->document->ReadOnly || $Grid->document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_document" id= "fn_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_document" id= "fa_x<?= $Grid->RowIndex ?>_document" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_document" id= "fs_x<?= $Grid->RowIndex ?>_document" value="500">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_document" id= "fx_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_document" id= "fm_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="activity" data-field="x_document" data-hidden="1" name="o<?= $Grid->RowIndex ?>_document" id="o<?= $Grid->RowIndex ?>_document" value="<?= HtmlEncode($Grid->document->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->notes->Visible) { // notes ?>
        <td data-name="notes">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_activity_notes" class="form-group activity_notes">
<textarea data-table="activity" data-field="x_notes" name="x<?= $Grid->RowIndex ?>_notes" id="x<?= $Grid->RowIndex ?>_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->notes->getPlaceHolder()) ?>"<?= $Grid->notes->editAttributes() ?>><?= $Grid->notes->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->notes->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_activity_notes" class="form-group activity_notes">
<span<?= $Grid->notes->viewAttributes() ?>>
<?= $Grid->notes->ViewValue ?></span>
</span>
<input type="hidden" data-table="activity" data-field="x_notes" data-hidden="1" name="x<?= $Grid->RowIndex ?>_notes" id="x<?= $Grid->RowIndex ?>_notes" value="<?= HtmlEncode($Grid->notes->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="activity" data-field="x_notes" data-hidden="1" name="o<?= $Grid->RowIndex ?>_notes" id="o<?= $Grid->RowIndex ?>_notes" value="<?= HtmlEncode($Grid->notes->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["factivitygrid","load"], function() {
    factivitygrid.updateLists(<?= $Grid->RowIndex ?>);
});
</script>
    </tr>
<?php
    }
?>
</tbody>
</table><!-- /.ew-table -->
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="factivitygrid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($Grid->TotalRecords == 0 && !$Grid->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if (!$Grid->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("activity");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$Grid->isExport()) { ?>
<script>
loadjs.ready("fixedheadertable", function () {
    ew.fixedHeaderTable({
        delay: 0,
        container: "gmp_activity",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
