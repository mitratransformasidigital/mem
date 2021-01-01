<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("EmployeeShiftGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.employee_shift) ew.vars.tables.employee_shift = <?= JsonEncode(GetClientVar("tables", "employee_shift")) ?>;
var currentForm, currentPageID;
var femployee_shiftgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    femployee_shiftgrid = new ew.Form("femployee_shiftgrid", "grid");
    femployee_shiftgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.employee_shift.fields;
    femployee_shiftgrid.addFields([
        ["shift_id", [fields.shift_id.required ? ew.Validators.required(fields.shift_id.caption) : null], fields.shift_id.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["start_date", [fields.start_date.required ? ew.Validators.required(fields.start_date.caption) : null, ew.Validators.datetime(5)], fields.start_date.isInvalid],
        ["end_date", [fields.end_date.required ? ew.Validators.required(fields.end_date.caption) : null, ew.Validators.datetime(5)], fields.end_date.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_shiftgrid,
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
    femployee_shiftgrid.validate = function () {
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
    femployee_shiftgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "shift_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "employee_username", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "start_date", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "end_date", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    femployee_shiftgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_shiftgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_shiftgrid.lists.shift_id = <?= $Grid->shift_id->toClientList($Grid) ?>;
    femployee_shiftgrid.lists.employee_username = <?= $Grid->employee_username->toClientList($Grid) ?>;
    loadjs.done("femployee_shiftgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> employee_shift">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="femployee_shiftgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_employee_shift" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_employee_shiftgrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->shift_id->Visible) { // shift_id ?>
        <th data-name="shift_id" class="<?= $Grid->shift_id->headerCellClass() ?>"><div id="elh_employee_shift_shift_id" class="employee_shift_shift_id"><?= $Grid->renderSort($Grid->shift_id) ?></div></th>
<?php } ?>
<?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <th data-name="employee_username" class="<?= $Grid->employee_username->headerCellClass() ?>"><div id="elh_employee_shift_employee_username" class="employee_shift_employee_username"><?= $Grid->renderSort($Grid->employee_username) ?></div></th>
<?php } ?>
<?php if ($Grid->start_date->Visible) { // start_date ?>
        <th data-name="start_date" class="<?= $Grid->start_date->headerCellClass() ?>"><div id="elh_employee_shift_start_date" class="employee_shift_start_date"><?= $Grid->renderSort($Grid->start_date) ?></div></th>
<?php } ?>
<?php if ($Grid->end_date->Visible) { // end_date ?>
        <th data-name="end_date" class="<?= $Grid->end_date->headerCellClass() ?>"><div id="elh_employee_shift_end_date" class="employee_shift_end_date"><?= $Grid->renderSort($Grid->end_date) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_employee_shift", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->shift_id->Visible) { // shift_id ?>
        <td data-name="shift_id" <?= $Grid->shift_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->shift_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_shift_id" class="form-group">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->shift_id->getDisplayValue($Grid->shift_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_shift_id" name="x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_shift_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_shift_id"
        name="x<?= $Grid->RowIndex ?>_shift_id"
        class="form-control ew-select<?= $Grid->shift_id->isInvalidClass() ?>"
        data-select2-id="employee_shift_x<?= $Grid->RowIndex ?>_shift_id"
        data-table="employee_shift"
        data-field="x_shift_id"
        data-value-separator="<?= $Grid->shift_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->shift_id->getPlaceHolder()) ?>"
        <?= $Grid->shift_id->editAttributes() ?>>
        <?= $Grid->shift_id->selectOptionListHtml("x{$Grid->RowIndex}_shift_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_shift") && !$Grid->shift_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_shift_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->shift_id->caption() ?>" data-title="<?= $Grid->shift_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_shift_id',url:'<?= GetUrl("mastershiftaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->shift_id->getErrorMessage() ?></div>
<?= $Grid->shift_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_shift_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_shift_x<?= $Grid->RowIndex ?>_shift_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_shift_id", selectId: "employee_shift_x<?= $Grid->RowIndex ?>_shift_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_shift.fields.shift_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="employee_shift" data-field="x_shift_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_shift_id" id="o<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_shift_id" class="form-group">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->shift_id->getDisplayValue($Grid->shift_id->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_shift" data-field="x_shift_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_shift_id" id="x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_shift_id">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<?= $Grid->shift_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_shift" data-field="x_shift_id" data-hidden="1" name="femployee_shiftgrid$x<?= $Grid->RowIndex ?>_shift_id" id="femployee_shiftgrid$x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->FormValue) ?>">
<input type="hidden" data-table="employee_shift" data-field="x_shift_id" data-hidden="1" name="femployee_shiftgrid$o<?= $Grid->RowIndex ?>_shift_id" id="femployee_shiftgrid$o<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username" <?= $Grid->employee_username->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->employee_username->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_employee_username" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_shift_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_shift"
        data-field="x_employee_username"
        data-value-separator="<?= $Grid->employee_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->employee_username->getPlaceHolder()) ?>"
        <?= $Grid->employee_username->editAttributes() ?>>
        <?= $Grid->employee_username->selectOptionListHtml("x{$Grid->RowIndex}_employee_username") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "employee") && !$Grid->employee_username->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_employee_username" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->employee_username->caption() ?>" data-title="<?= $Grid->employee_username->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_employee_username',url:'<?= GetUrl("employeeaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->employee_username->getErrorMessage() ?></div>
<?= $Grid->employee_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_employee_username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_shift_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_shift_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_shift.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="employee_shift" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->employee_username->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_employee_username" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_shift_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_shift"
        data-field="x_employee_username"
        data-value-separator="<?= $Grid->employee_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->employee_username->getPlaceHolder()) ?>"
        <?= $Grid->employee_username->editAttributes() ?>>
        <?= $Grid->employee_username->selectOptionListHtml("x{$Grid->RowIndex}_employee_username") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "employee") && !$Grid->employee_username->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_employee_username" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->employee_username->caption() ?>" data-title="<?= $Grid->employee_username->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_employee_username',url:'<?= GetUrl("employeeaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->employee_username->getErrorMessage() ?></div>
<?= $Grid->employee_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_employee_username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_shift_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_shift_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_shift.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<?= $Grid->employee_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_shift" data-field="x_employee_username" data-hidden="1" name="femployee_shiftgrid$x<?= $Grid->RowIndex ?>_employee_username" id="femployee_shiftgrid$x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<input type="hidden" data-table="employee_shift" data-field="x_employee_username" data-hidden="1" name="femployee_shiftgrid$o<?= $Grid->RowIndex ?>_employee_username" id="femployee_shiftgrid$o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->start_date->Visible) { // start_date ?>
        <td data-name="start_date" <?= $Grid->start_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_start_date" class="form-group">
<input type="<?= $Grid->start_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_start_date" data-format="5" name="x<?= $Grid->RowIndex ?>_start_date" id="x<?= $Grid->RowIndex ?>_start_date" placeholder="<?= HtmlEncode($Grid->start_date->getPlaceHolder()) ?>" value="<?= $Grid->start_date->EditValue ?>"<?= $Grid->start_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->start_date->getErrorMessage() ?></div>
<?php if (!$Grid->start_date->ReadOnly && !$Grid->start_date->Disabled && !isset($Grid->start_date->EditAttrs["readonly"]) && !isset($Grid->start_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftgrid", "x<?= $Grid->RowIndex ?>_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="employee_shift" data-field="x_start_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_start_date" id="o<?= $Grid->RowIndex ?>_start_date" value="<?= HtmlEncode($Grid->start_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_start_date" class="form-group">
<input type="<?= $Grid->start_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_start_date" data-format="5" name="x<?= $Grid->RowIndex ?>_start_date" id="x<?= $Grid->RowIndex ?>_start_date" placeholder="<?= HtmlEncode($Grid->start_date->getPlaceHolder()) ?>" value="<?= $Grid->start_date->EditValue ?>"<?= $Grid->start_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->start_date->getErrorMessage() ?></div>
<?php if (!$Grid->start_date->ReadOnly && !$Grid->start_date->Disabled && !isset($Grid->start_date->EditAttrs["readonly"]) && !isset($Grid->start_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftgrid", "x<?= $Grid->RowIndex ?>_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_start_date">
<span<?= $Grid->start_date->viewAttributes() ?>>
<?= $Grid->start_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_shift" data-field="x_start_date" data-hidden="1" name="femployee_shiftgrid$x<?= $Grid->RowIndex ?>_start_date" id="femployee_shiftgrid$x<?= $Grid->RowIndex ?>_start_date" value="<?= HtmlEncode($Grid->start_date->FormValue) ?>">
<input type="hidden" data-table="employee_shift" data-field="x_start_date" data-hidden="1" name="femployee_shiftgrid$o<?= $Grid->RowIndex ?>_start_date" id="femployee_shiftgrid$o<?= $Grid->RowIndex ?>_start_date" value="<?= HtmlEncode($Grid->start_date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->end_date->Visible) { // end_date ?>
        <td data-name="end_date" <?= $Grid->end_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_end_date" class="form-group">
<input type="<?= $Grid->end_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_end_date" data-format="5" name="x<?= $Grid->RowIndex ?>_end_date" id="x<?= $Grid->RowIndex ?>_end_date" placeholder="<?= HtmlEncode($Grid->end_date->getPlaceHolder()) ?>" value="<?= $Grid->end_date->EditValue ?>"<?= $Grid->end_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->end_date->getErrorMessage() ?></div>
<?php if (!$Grid->end_date->ReadOnly && !$Grid->end_date->Disabled && !isset($Grid->end_date->EditAttrs["readonly"]) && !isset($Grid->end_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftgrid", "x<?= $Grid->RowIndex ?>_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="employee_shift" data-field="x_end_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_end_date" id="o<?= $Grid->RowIndex ?>_end_date" value="<?= HtmlEncode($Grid->end_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_end_date" class="form-group">
<input type="<?= $Grid->end_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_end_date" data-format="5" name="x<?= $Grid->RowIndex ?>_end_date" id="x<?= $Grid->RowIndex ?>_end_date" placeholder="<?= HtmlEncode($Grid->end_date->getPlaceHolder()) ?>" value="<?= $Grid->end_date->EditValue ?>"<?= $Grid->end_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->end_date->getErrorMessage() ?></div>
<?php if (!$Grid->end_date->ReadOnly && !$Grid->end_date->Disabled && !isset($Grid->end_date->EditAttrs["readonly"]) && !isset($Grid->end_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftgrid", "x<?= $Grid->RowIndex ?>_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_shift_end_date">
<span<?= $Grid->end_date->viewAttributes() ?>>
<?= $Grid->end_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_shift" data-field="x_end_date" data-hidden="1" name="femployee_shiftgrid$x<?= $Grid->RowIndex ?>_end_date" id="femployee_shiftgrid$x<?= $Grid->RowIndex ?>_end_date" value="<?= HtmlEncode($Grid->end_date->FormValue) ?>">
<input type="hidden" data-table="employee_shift" data-field="x_end_date" data-hidden="1" name="femployee_shiftgrid$o<?= $Grid->RowIndex ?>_end_date" id="femployee_shiftgrid$o<?= $Grid->RowIndex ?>_end_date" value="<?= HtmlEncode($Grid->end_date->OldValue) ?>">
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
loadjs.ready(["femployee_shiftgrid","load"], function () {
    femployee_shiftgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_employee_shift", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->shift_id->Visible) { // shift_id ?>
        <td data-name="shift_id">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->shift_id->getSessionValue() != "") { ?>
<span id="el$rowindex$_employee_shift_shift_id" class="form-group employee_shift_shift_id">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->shift_id->getDisplayValue($Grid->shift_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_shift_id" name="x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_employee_shift_shift_id" class="form-group employee_shift_shift_id">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_shift_id"
        name="x<?= $Grid->RowIndex ?>_shift_id"
        class="form-control ew-select<?= $Grid->shift_id->isInvalidClass() ?>"
        data-select2-id="employee_shift_x<?= $Grid->RowIndex ?>_shift_id"
        data-table="employee_shift"
        data-field="x_shift_id"
        data-value-separator="<?= $Grid->shift_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->shift_id->getPlaceHolder()) ?>"
        <?= $Grid->shift_id->editAttributes() ?>>
        <?= $Grid->shift_id->selectOptionListHtml("x{$Grid->RowIndex}_shift_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_shift") && !$Grid->shift_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_shift_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->shift_id->caption() ?>" data-title="<?= $Grid->shift_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_shift_id',url:'<?= GetUrl("mastershiftaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->shift_id->getErrorMessage() ?></div>
<?= $Grid->shift_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_shift_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_shift_x<?= $Grid->RowIndex ?>_shift_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_shift_id", selectId: "employee_shift_x<?= $Grid->RowIndex ?>_shift_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_shift.fields.shift_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_employee_shift_shift_id" class="form-group employee_shift_shift_id">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->shift_id->getDisplayValue($Grid->shift_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_shift" data-field="x_shift_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_shift_id" id="x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_shift" data-field="x_shift_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_shift_id" id="o<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->employee_username->getSessionValue() != "") { ?>
<span id="el$rowindex$_employee_shift_employee_username" class="form-group employee_shift_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_employee_shift_employee_username" class="form-group employee_shift_employee_username">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_shift_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_shift"
        data-field="x_employee_username"
        data-value-separator="<?= $Grid->employee_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->employee_username->getPlaceHolder()) ?>"
        <?= $Grid->employee_username->editAttributes() ?>>
        <?= $Grid->employee_username->selectOptionListHtml("x{$Grid->RowIndex}_employee_username") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "employee") && !$Grid->employee_username->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_employee_username" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->employee_username->caption() ?>" data-title="<?= $Grid->employee_username->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_employee_username',url:'<?= GetUrl("employeeaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->employee_username->getErrorMessage() ?></div>
<?= $Grid->employee_username->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_employee_username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_shift_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_shift_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_shift.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_employee_shift_employee_username" class="form-group employee_shift_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_shift" data-field="x_employee_username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_shift" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->start_date->Visible) { // start_date ?>
        <td data-name="start_date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_shift_start_date" class="form-group employee_shift_start_date">
<input type="<?= $Grid->start_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_start_date" data-format="5" name="x<?= $Grid->RowIndex ?>_start_date" id="x<?= $Grid->RowIndex ?>_start_date" placeholder="<?= HtmlEncode($Grid->start_date->getPlaceHolder()) ?>" value="<?= $Grid->start_date->EditValue ?>"<?= $Grid->start_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->start_date->getErrorMessage() ?></div>
<?php if (!$Grid->start_date->ReadOnly && !$Grid->start_date->Disabled && !isset($Grid->start_date->EditAttrs["readonly"]) && !isset($Grid->start_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftgrid", "x<?= $Grid->RowIndex ?>_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_shift_start_date" class="form-group employee_shift_start_date">
<span<?= $Grid->start_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->start_date->getDisplayValue($Grid->start_date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_shift" data-field="x_start_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_start_date" id="x<?= $Grid->RowIndex ?>_start_date" value="<?= HtmlEncode($Grid->start_date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_shift" data-field="x_start_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_start_date" id="o<?= $Grid->RowIndex ?>_start_date" value="<?= HtmlEncode($Grid->start_date->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->end_date->Visible) { // end_date ?>
        <td data-name="end_date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_shift_end_date" class="form-group employee_shift_end_date">
<input type="<?= $Grid->end_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_end_date" data-format="5" name="x<?= $Grid->RowIndex ?>_end_date" id="x<?= $Grid->RowIndex ?>_end_date" placeholder="<?= HtmlEncode($Grid->end_date->getPlaceHolder()) ?>" value="<?= $Grid->end_date->EditValue ?>"<?= $Grid->end_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->end_date->getErrorMessage() ?></div>
<?php if (!$Grid->end_date->ReadOnly && !$Grid->end_date->Disabled && !isset($Grid->end_date->EditAttrs["readonly"]) && !isset($Grid->end_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftgrid", "x<?= $Grid->RowIndex ?>_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_shift_end_date" class="form-group employee_shift_end_date">
<span<?= $Grid->end_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->end_date->getDisplayValue($Grid->end_date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_shift" data-field="x_end_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_end_date" id="x<?= $Grid->RowIndex ?>_end_date" value="<?= HtmlEncode($Grid->end_date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_shift" data-field="x_end_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_end_date" id="o<?= $Grid->RowIndex ?>_end_date" value="<?= HtmlEncode($Grid->end_date->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["femployee_shiftgrid","load"], function() {
    femployee_shiftgrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="femployee_shiftgrid">
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
    ew.addEventHandlers("employee_shift");
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
        container: "gmp_employee_shift",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
