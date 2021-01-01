<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("MasterHolidayGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.master_holiday) ew.vars.tables.master_holiday = <?= JsonEncode(GetClientVar("tables", "master_holiday")) ?>;
var currentForm, currentPageID;
var fmaster_holidaygrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fmaster_holidaygrid = new ew.Form("fmaster_holidaygrid", "grid");
    fmaster_holidaygrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.master_holiday.fields;
    fmaster_holidaygrid.addFields([
        ["shift_id", [fields.shift_id.required ? ew.Validators.required(fields.shift_id.caption) : null], fields.shift_id.isInvalid],
        ["holiday_date", [fields.holiday_date.required ? ew.Validators.required(fields.holiday_date.caption) : null, ew.Validators.datetime(5)], fields.holiday_date.isInvalid],
        ["holiday_name", [fields.holiday_name.required ? ew.Validators.required(fields.holiday_name.caption) : null], fields.holiday_name.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_holidaygrid,
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
    fmaster_holidaygrid.validate = function () {
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
    fmaster_holidaygrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "shift_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "holiday_date", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "holiday_name", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fmaster_holidaygrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_holidaygrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_holidaygrid.lists.shift_id = <?= $Grid->shift_id->toClientList($Grid) ?>;
    loadjs.done("fmaster_holidaygrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> master_holiday">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fmaster_holidaygrid" class="ew-form ew-list-form form-inline">
<div id="gmp_master_holiday" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_master_holidaygrid" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="shift_id" class="<?= $Grid->shift_id->headerCellClass() ?>"><div id="elh_master_holiday_shift_id" class="master_holiday_shift_id"><?= $Grid->renderSort($Grid->shift_id) ?></div></th>
<?php } ?>
<?php if ($Grid->holiday_date->Visible) { // holiday_date ?>
        <th data-name="holiday_date" class="<?= $Grid->holiday_date->headerCellClass() ?>"><div id="elh_master_holiday_holiday_date" class="master_holiday_holiday_date"><?= $Grid->renderSort($Grid->holiday_date) ?></div></th>
<?php } ?>
<?php if ($Grid->holiday_name->Visible) { // holiday_name ?>
        <th data-name="holiday_name" class="<?= $Grid->holiday_name->headerCellClass() ?>"><div id="elh_master_holiday_holiday_name" class="master_holiday_holiday_name"><?= $Grid->renderSort($Grid->holiday_name) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_master_holiday", "data-rowtype" => $Grid->RowType]);

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
<span id="el<?= $Grid->RowCount ?>_master_holiday_shift_id" class="form-group">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->shift_id->getDisplayValue($Grid->shift_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_shift_id" name="x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_shift_id" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_shift_id"
        name="x<?= $Grid->RowIndex ?>_shift_id"
        class="form-control ew-select<?= $Grid->shift_id->isInvalidClass() ?>"
        data-select2-id="master_holiday_x<?= $Grid->RowIndex ?>_shift_id"
        data-table="master_holiday"
        data-field="x_shift_id"
        data-value-separator="<?= $Grid->shift_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->shift_id->getPlaceHolder()) ?>"
        <?= $Grid->shift_id->editAttributes() ?>>
        <?= $Grid->shift_id->selectOptionListHtml("x{$Grid->RowIndex}_shift_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->shift_id->getErrorMessage() ?></div>
<?= $Grid->shift_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_shift_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_holiday_x<?= $Grid->RowIndex ?>_shift_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_shift_id", selectId: "master_holiday_x<?= $Grid->RowIndex ?>_shift_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_holiday.fields.shift_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="master_holiday" data-field="x_shift_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_shift_id" id="o<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->shift_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_shift_id" class="form-group">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->shift_id->getDisplayValue($Grid->shift_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_shift_id" name="x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_shift_id" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_shift_id"
        name="x<?= $Grid->RowIndex ?>_shift_id"
        class="form-control ew-select<?= $Grid->shift_id->isInvalidClass() ?>"
        data-select2-id="master_holiday_x<?= $Grid->RowIndex ?>_shift_id"
        data-table="master_holiday"
        data-field="x_shift_id"
        data-value-separator="<?= $Grid->shift_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->shift_id->getPlaceHolder()) ?>"
        <?= $Grid->shift_id->editAttributes() ?>>
        <?= $Grid->shift_id->selectOptionListHtml("x{$Grid->RowIndex}_shift_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->shift_id->getErrorMessage() ?></div>
<?= $Grid->shift_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_shift_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_holiday_x<?= $Grid->RowIndex ?>_shift_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_shift_id", selectId: "master_holiday_x<?= $Grid->RowIndex ?>_shift_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_holiday.fields.shift_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_shift_id">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<?= $Grid->shift_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_holiday" data-field="x_shift_id" data-hidden="1" name="fmaster_holidaygrid$x<?= $Grid->RowIndex ?>_shift_id" id="fmaster_holidaygrid$x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->FormValue) ?>">
<input type="hidden" data-table="master_holiday" data-field="x_shift_id" data-hidden="1" name="fmaster_holidaygrid$o<?= $Grid->RowIndex ?>_shift_id" id="fmaster_holidaygrid$o<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->holiday_date->Visible) { // holiday_date ?>
        <td data-name="holiday_date" <?= $Grid->holiday_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_holiday_date" class="form-group">
<input type="<?= $Grid->holiday_date->getInputTextType() ?>" data-table="master_holiday" data-field="x_holiday_date" data-format="5" name="x<?= $Grid->RowIndex ?>_holiday_date" id="x<?= $Grid->RowIndex ?>_holiday_date" placeholder="<?= HtmlEncode($Grid->holiday_date->getPlaceHolder()) ?>" value="<?= $Grid->holiday_date->EditValue ?>"<?= $Grid->holiday_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->holiday_date->getErrorMessage() ?></div>
<?php if (!$Grid->holiday_date->ReadOnly && !$Grid->holiday_date->Disabled && !isset($Grid->holiday_date->EditAttrs["readonly"]) && !isset($Grid->holiday_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_holidaygrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmaster_holidaygrid", "x<?= $Grid->RowIndex ?>_holiday_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="master_holiday" data-field="x_holiday_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_holiday_date" id="o<?= $Grid->RowIndex ?>_holiday_date" value="<?= HtmlEncode($Grid->holiday_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_holiday_date" class="form-group">
<input type="<?= $Grid->holiday_date->getInputTextType() ?>" data-table="master_holiday" data-field="x_holiday_date" data-format="5" name="x<?= $Grid->RowIndex ?>_holiday_date" id="x<?= $Grid->RowIndex ?>_holiday_date" placeholder="<?= HtmlEncode($Grid->holiday_date->getPlaceHolder()) ?>" value="<?= $Grid->holiday_date->EditValue ?>"<?= $Grid->holiday_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->holiday_date->getErrorMessage() ?></div>
<?php if (!$Grid->holiday_date->ReadOnly && !$Grid->holiday_date->Disabled && !isset($Grid->holiday_date->EditAttrs["readonly"]) && !isset($Grid->holiday_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_holidaygrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmaster_holidaygrid", "x<?= $Grid->RowIndex ?>_holiday_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_holiday_date">
<span<?= $Grid->holiday_date->viewAttributes() ?>>
<?= $Grid->holiday_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_holiday" data-field="x_holiday_date" data-hidden="1" name="fmaster_holidaygrid$x<?= $Grid->RowIndex ?>_holiday_date" id="fmaster_holidaygrid$x<?= $Grid->RowIndex ?>_holiday_date" value="<?= HtmlEncode($Grid->holiday_date->FormValue) ?>">
<input type="hidden" data-table="master_holiday" data-field="x_holiday_date" data-hidden="1" name="fmaster_holidaygrid$o<?= $Grid->RowIndex ?>_holiday_date" id="fmaster_holidaygrid$o<?= $Grid->RowIndex ?>_holiday_date" value="<?= HtmlEncode($Grid->holiday_date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->holiday_name->Visible) { // holiday_name ?>
        <td data-name="holiday_name" <?= $Grid->holiday_name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_holiday_name" class="form-group">
<textarea data-table="master_holiday" data-field="x_holiday_name" name="x<?= $Grid->RowIndex ?>_holiday_name" id="x<?= $Grid->RowIndex ?>_holiday_name" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->holiday_name->getPlaceHolder()) ?>"<?= $Grid->holiday_name->editAttributes() ?>><?= $Grid->holiday_name->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->holiday_name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="master_holiday" data-field="x_holiday_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_holiday_name" id="o<?= $Grid->RowIndex ?>_holiday_name" value="<?= HtmlEncode($Grid->holiday_name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_holiday_name" class="form-group">
<textarea data-table="master_holiday" data-field="x_holiday_name" name="x<?= $Grid->RowIndex ?>_holiday_name" id="x<?= $Grid->RowIndex ?>_holiday_name" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->holiday_name->getPlaceHolder()) ?>"<?= $Grid->holiday_name->editAttributes() ?>><?= $Grid->holiday_name->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->holiday_name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_holiday_holiday_name">
<span<?= $Grid->holiday_name->viewAttributes() ?>>
<?= $Grid->holiday_name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_holiday" data-field="x_holiday_name" data-hidden="1" name="fmaster_holidaygrid$x<?= $Grid->RowIndex ?>_holiday_name" id="fmaster_holidaygrid$x<?= $Grid->RowIndex ?>_holiday_name" value="<?= HtmlEncode($Grid->holiday_name->FormValue) ?>">
<input type="hidden" data-table="master_holiday" data-field="x_holiday_name" data-hidden="1" name="fmaster_holidaygrid$o<?= $Grid->RowIndex ?>_holiday_name" id="fmaster_holidaygrid$o<?= $Grid->RowIndex ?>_holiday_name" value="<?= HtmlEncode($Grid->holiday_name->OldValue) ?>">
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
loadjs.ready(["fmaster_holidaygrid","load"], function () {
    fmaster_holidaygrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_master_holiday", "data-rowtype" => ROWTYPE_ADD]);
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
<span id="el$rowindex$_master_holiday_shift_id" class="form-group master_holiday_shift_id">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->shift_id->getDisplayValue($Grid->shift_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_shift_id" name="x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_master_holiday_shift_id" class="form-group master_holiday_shift_id">
    <select
        id="x<?= $Grid->RowIndex ?>_shift_id"
        name="x<?= $Grid->RowIndex ?>_shift_id"
        class="form-control ew-select<?= $Grid->shift_id->isInvalidClass() ?>"
        data-select2-id="master_holiday_x<?= $Grid->RowIndex ?>_shift_id"
        data-table="master_holiday"
        data-field="x_shift_id"
        data-value-separator="<?= $Grid->shift_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->shift_id->getPlaceHolder()) ?>"
        <?= $Grid->shift_id->editAttributes() ?>>
        <?= $Grid->shift_id->selectOptionListHtml("x{$Grid->RowIndex}_shift_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->shift_id->getErrorMessage() ?></div>
<?= $Grid->shift_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_shift_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_holiday_x<?= $Grid->RowIndex ?>_shift_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_shift_id", selectId: "master_holiday_x<?= $Grid->RowIndex ?>_shift_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_holiday.fields.shift_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_master_holiday_shift_id" class="form-group master_holiday_shift_id">
<span<?= $Grid->shift_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->shift_id->getDisplayValue($Grid->shift_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="master_holiday" data-field="x_shift_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_shift_id" id="x<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_holiday" data-field="x_shift_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_shift_id" id="o<?= $Grid->RowIndex ?>_shift_id" value="<?= HtmlEncode($Grid->shift_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->holiday_date->Visible) { // holiday_date ?>
        <td data-name="holiday_date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_master_holiday_holiday_date" class="form-group master_holiday_holiday_date">
<input type="<?= $Grid->holiday_date->getInputTextType() ?>" data-table="master_holiday" data-field="x_holiday_date" data-format="5" name="x<?= $Grid->RowIndex ?>_holiday_date" id="x<?= $Grid->RowIndex ?>_holiday_date" placeholder="<?= HtmlEncode($Grid->holiday_date->getPlaceHolder()) ?>" value="<?= $Grid->holiday_date->EditValue ?>"<?= $Grid->holiday_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->holiday_date->getErrorMessage() ?></div>
<?php if (!$Grid->holiday_date->ReadOnly && !$Grid->holiday_date->Disabled && !isset($Grid->holiday_date->EditAttrs["readonly"]) && !isset($Grid->holiday_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_holidaygrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmaster_holidaygrid", "x<?= $Grid->RowIndex ?>_holiday_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_master_holiday_holiday_date" class="form-group master_holiday_holiday_date">
<span<?= $Grid->holiday_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->holiday_date->getDisplayValue($Grid->holiday_date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="master_holiday" data-field="x_holiday_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_holiday_date" id="x<?= $Grid->RowIndex ?>_holiday_date" value="<?= HtmlEncode($Grid->holiday_date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_holiday" data-field="x_holiday_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_holiday_date" id="o<?= $Grid->RowIndex ?>_holiday_date" value="<?= HtmlEncode($Grid->holiday_date->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->holiday_name->Visible) { // holiday_name ?>
        <td data-name="holiday_name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_master_holiday_holiday_name" class="form-group master_holiday_holiday_name">
<textarea data-table="master_holiday" data-field="x_holiday_name" name="x<?= $Grid->RowIndex ?>_holiday_name" id="x<?= $Grid->RowIndex ?>_holiday_name" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->holiday_name->getPlaceHolder()) ?>"<?= $Grid->holiday_name->editAttributes() ?>><?= $Grid->holiday_name->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->holiday_name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_master_holiday_holiday_name" class="form-group master_holiday_holiday_name">
<span<?= $Grid->holiday_name->viewAttributes() ?>>
<?= $Grid->holiday_name->ViewValue ?></span>
</span>
<input type="hidden" data-table="master_holiday" data-field="x_holiday_name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_holiday_name" id="x<?= $Grid->RowIndex ?>_holiday_name" value="<?= HtmlEncode($Grid->holiday_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_holiday" data-field="x_holiday_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_holiday_name" id="o<?= $Grid->RowIndex ?>_holiday_name" value="<?= HtmlEncode($Grid->holiday_name->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fmaster_holidaygrid","load"], function() {
    fmaster_holidaygrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fmaster_holidaygrid">
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
    ew.addEventHandlers("master_holiday");
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
        container: "gmp_master_holiday",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
