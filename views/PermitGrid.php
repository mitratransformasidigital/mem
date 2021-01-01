<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("PermitGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.permit) ew.vars.tables.permit = <?= JsonEncode(GetClientVar("tables", "permit")) ?>;
var currentForm, currentPageID;
var fpermitgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fpermitgrid = new ew.Form("fpermitgrid", "grid");
    fpermitgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.permit.fields;
    fpermitgrid.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["permit_date", [fields.permit_date.required ? ew.Validators.required(fields.permit_date.caption) : null, ew.Validators.datetime(5)], fields.permit_date.isInvalid],
        ["permit_type", [fields.permit_type.required ? ew.Validators.required(fields.permit_type.caption) : null], fields.permit_type.isInvalid],
        ["document", [fields.document.required ? ew.Validators.fileRequired(fields.document.caption) : null], fields.document.isInvalid],
        ["note", [fields.note.required ? ew.Validators.required(fields.note.caption) : null], fields.note.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpermitgrid,
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
    fpermitgrid.validate = function () {
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
    fpermitgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "employee_username", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "permit_date", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "permit_type", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "document", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "note", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fpermitgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpermitgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpermitgrid.lists.employee_username = <?= $Grid->employee_username->toClientList($Grid) ?>;
    fpermitgrid.lists.permit_type = <?= $Grid->permit_type->toClientList($Grid) ?>;
    loadjs.done("fpermitgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> permit">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fpermitgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_permit" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_permitgrid" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="employee_username" class="<?= $Grid->employee_username->headerCellClass() ?>"><div id="elh_permit_employee_username" class="permit_employee_username"><?= $Grid->renderSort($Grid->employee_username) ?></div></th>
<?php } ?>
<?php if ($Grid->permit_date->Visible) { // permit_date ?>
        <th data-name="permit_date" class="<?= $Grid->permit_date->headerCellClass() ?>"><div id="elh_permit_permit_date" class="permit_permit_date"><?= $Grid->renderSort($Grid->permit_date) ?></div></th>
<?php } ?>
<?php if ($Grid->permit_type->Visible) { // permit_type ?>
        <th data-name="permit_type" class="<?= $Grid->permit_type->headerCellClass() ?>"><div id="elh_permit_permit_type" class="permit_permit_type"><?= $Grid->renderSort($Grid->permit_type) ?></div></th>
<?php } ?>
<?php if ($Grid->document->Visible) { // document ?>
        <th data-name="document" class="<?= $Grid->document->headerCellClass() ?>"><div id="elh_permit_document" class="permit_document"><?= $Grid->renderSort($Grid->document) ?></div></th>
<?php } ?>
<?php if ($Grid->note->Visible) { // note ?>
        <th data-name="note" class="<?= $Grid->note->headerCellClass() ?>"><div id="elh_permit_note" class="permit_note"><?= $Grid->renderSort($Grid->note) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_permit", "data-rowtype" => $Grid->RowType]);

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
<span id="el<?= $Grid->RowCount ?>_permit_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_permit_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="permit_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="permit"
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
    var el = document.querySelector("select[data-select2-id='permit_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "permit_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="permit" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->employee_username->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_permit_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_permit_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="permit_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="permit"
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
    var el = document.querySelector("select[data-select2-id='permit_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "permit_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_permit_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<?= $Grid->employee_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="permit" data-field="x_employee_username" data-hidden="1" name="fpermitgrid$x<?= $Grid->RowIndex ?>_employee_username" id="fpermitgrid$x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<input type="hidden" data-table="permit" data-field="x_employee_username" data-hidden="1" name="fpermitgrid$o<?= $Grid->RowIndex ?>_employee_username" id="fpermitgrid$o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->permit_date->Visible) { // permit_date ?>
        <td data-name="permit_date" <?= $Grid->permit_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_permit_permit_date" class="form-group">
<input type="<?= $Grid->permit_date->getInputTextType() ?>" data-table="permit" data-field="x_permit_date" data-format="5" name="x<?= $Grid->RowIndex ?>_permit_date" id="x<?= $Grid->RowIndex ?>_permit_date" placeholder="<?= HtmlEncode($Grid->permit_date->getPlaceHolder()) ?>" value="<?= $Grid->permit_date->EditValue ?>"<?= $Grid->permit_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->permit_date->getErrorMessage() ?></div>
<?php if (!$Grid->permit_date->ReadOnly && !$Grid->permit_date->Disabled && !isset($Grid->permit_date->EditAttrs["readonly"]) && !isset($Grid->permit_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpermitgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpermitgrid", "x<?= $Grid->RowIndex ?>_permit_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="permit" data-field="x_permit_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_permit_date" id="o<?= $Grid->RowIndex ?>_permit_date" value="<?= HtmlEncode($Grid->permit_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_permit_permit_date" class="form-group">
<input type="<?= $Grid->permit_date->getInputTextType() ?>" data-table="permit" data-field="x_permit_date" data-format="5" name="x<?= $Grid->RowIndex ?>_permit_date" id="x<?= $Grid->RowIndex ?>_permit_date" placeholder="<?= HtmlEncode($Grid->permit_date->getPlaceHolder()) ?>" value="<?= $Grid->permit_date->EditValue ?>"<?= $Grid->permit_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->permit_date->getErrorMessage() ?></div>
<?php if (!$Grid->permit_date->ReadOnly && !$Grid->permit_date->Disabled && !isset($Grid->permit_date->EditAttrs["readonly"]) && !isset($Grid->permit_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpermitgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpermitgrid", "x<?= $Grid->RowIndex ?>_permit_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_permit_permit_date">
<span<?= $Grid->permit_date->viewAttributes() ?>>
<?= $Grid->permit_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="permit" data-field="x_permit_date" data-hidden="1" name="fpermitgrid$x<?= $Grid->RowIndex ?>_permit_date" id="fpermitgrid$x<?= $Grid->RowIndex ?>_permit_date" value="<?= HtmlEncode($Grid->permit_date->FormValue) ?>">
<input type="hidden" data-table="permit" data-field="x_permit_date" data-hidden="1" name="fpermitgrid$o<?= $Grid->RowIndex ?>_permit_date" id="fpermitgrid$o<?= $Grid->RowIndex ?>_permit_date" value="<?= HtmlEncode($Grid->permit_date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->permit_type->Visible) { // permit_type ?>
        <td data-name="permit_type" <?= $Grid->permit_type->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_permit_permit_type" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_permit_type"
        name="x<?= $Grid->RowIndex ?>_permit_type"
        class="form-control ew-select<?= $Grid->permit_type->isInvalidClass() ?>"
        data-select2-id="permit_x<?= $Grid->RowIndex ?>_permit_type"
        data-table="permit"
        data-field="x_permit_type"
        data-value-separator="<?= $Grid->permit_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->permit_type->getPlaceHolder()) ?>"
        <?= $Grid->permit_type->editAttributes() ?>>
        <?= $Grid->permit_type->selectOptionListHtml("x{$Grid->RowIndex}_permit_type") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->permit_type->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permit_x<?= $Grid->RowIndex ?>_permit_type']"),
        options = { name: "x<?= $Grid->RowIndex ?>_permit_type", selectId: "permit_x<?= $Grid->RowIndex ?>_permit_type", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.permit.fields.permit_type.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.permit_type.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="permit" data-field="x_permit_type" data-hidden="1" name="o<?= $Grid->RowIndex ?>_permit_type" id="o<?= $Grid->RowIndex ?>_permit_type" value="<?= HtmlEncode($Grid->permit_type->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_permit_permit_type" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_permit_type"
        name="x<?= $Grid->RowIndex ?>_permit_type"
        class="form-control ew-select<?= $Grid->permit_type->isInvalidClass() ?>"
        data-select2-id="permit_x<?= $Grid->RowIndex ?>_permit_type"
        data-table="permit"
        data-field="x_permit_type"
        data-value-separator="<?= $Grid->permit_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->permit_type->getPlaceHolder()) ?>"
        <?= $Grid->permit_type->editAttributes() ?>>
        <?= $Grid->permit_type->selectOptionListHtml("x{$Grid->RowIndex}_permit_type") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->permit_type->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permit_x<?= $Grid->RowIndex ?>_permit_type']"),
        options = { name: "x<?= $Grid->RowIndex ?>_permit_type", selectId: "permit_x<?= $Grid->RowIndex ?>_permit_type", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.permit.fields.permit_type.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.permit_type.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_permit_permit_type">
<span<?= $Grid->permit_type->viewAttributes() ?>>
<?php if (!EmptyString($Grid->permit_type->getViewValue()) && $Grid->permit_type->linkAttributes() != "") { ?>
<a<?= $Grid->permit_type->linkAttributes() ?>><?= $Grid->permit_type->getViewValue() ?></a>
<?php } else { ?>
<?= $Grid->permit_type->getViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="permit" data-field="x_permit_type" data-hidden="1" name="fpermitgrid$x<?= $Grid->RowIndex ?>_permit_type" id="fpermitgrid$x<?= $Grid->RowIndex ?>_permit_type" value="<?= HtmlEncode($Grid->permit_type->FormValue) ?>">
<input type="hidden" data-table="permit" data-field="x_permit_type" data-hidden="1" name="fpermitgrid$o<?= $Grid->RowIndex ?>_permit_type" id="fpermitgrid$o<?= $Grid->RowIndex ?>_permit_type" value="<?= HtmlEncode($Grid->permit_type->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->document->Visible) { // document ?>
        <td data-name="document" <?= $Grid->document->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_permit_document" class="form-group permit_document">
<div id="fd_x<?= $Grid->RowIndex ?>_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->document->title() ?>" data-table="permit" data-field="x_document" name="x<?= $Grid->RowIndex ?>_document" id="x<?= $Grid->RowIndex ?>_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->document->editAttributes() ?><?= ($Grid->document->ReadOnly || $Grid->document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_document" id= "fn_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_document" id= "fa_x<?= $Grid->RowIndex ?>_document" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_document" id= "fs_x<?= $Grid->RowIndex ?>_document" value="250">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_document" id= "fx_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_document" id= "fm_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="permit" data-field="x_document" data-hidden="1" name="o<?= $Grid->RowIndex ?>_document" id="o<?= $Grid->RowIndex ?>_document" value="<?= HtmlEncode($Grid->document->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_permit_document">
<span<?= $Grid->document->viewAttributes() ?>>
<?= GetFileViewTag($Grid->document, $Grid->document->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_permit_document" class="form-group permit_document">
<div id="fd_x<?= $Grid->RowIndex ?>_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->document->title() ?>" data-table="permit" data-field="x_document" name="x<?= $Grid->RowIndex ?>_document" id="x<?= $Grid->RowIndex ?>_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->document->editAttributes() ?><?= ($Grid->document->ReadOnly || $Grid->document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_document" id= "fn_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_document" id= "fa_x<?= $Grid->RowIndex ?>_document" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_document") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_document" id= "fs_x<?= $Grid->RowIndex ?>_document" value="250">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_document" id= "fx_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_document" id= "fm_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->note->Visible) { // note ?>
        <td data-name="note" <?= $Grid->note->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_permit_note" class="form-group">
<textarea data-table="permit" data-field="x_note" name="x<?= $Grid->RowIndex ?>_note" id="x<?= $Grid->RowIndex ?>_note" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->note->getPlaceHolder()) ?>"<?= $Grid->note->editAttributes() ?>><?= $Grid->note->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->note->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="permit" data-field="x_note" data-hidden="1" name="o<?= $Grid->RowIndex ?>_note" id="o<?= $Grid->RowIndex ?>_note" value="<?= HtmlEncode($Grid->note->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_permit_note" class="form-group">
<textarea data-table="permit" data-field="x_note" name="x<?= $Grid->RowIndex ?>_note" id="x<?= $Grid->RowIndex ?>_note" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->note->getPlaceHolder()) ?>"<?= $Grid->note->editAttributes() ?>><?= $Grid->note->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->note->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_permit_note">
<span<?= $Grid->note->viewAttributes() ?>>
<?= $Grid->note->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="permit" data-field="x_note" data-hidden="1" name="fpermitgrid$x<?= $Grid->RowIndex ?>_note" id="fpermitgrid$x<?= $Grid->RowIndex ?>_note" value="<?= HtmlEncode($Grid->note->FormValue) ?>">
<input type="hidden" data-table="permit" data-field="x_note" data-hidden="1" name="fpermitgrid$o<?= $Grid->RowIndex ?>_note" id="fpermitgrid$o<?= $Grid->RowIndex ?>_note" value="<?= HtmlEncode($Grid->note->OldValue) ?>">
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
loadjs.ready(["fpermitgrid","load"], function () {
    fpermitgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_permit", "data-rowtype" => ROWTYPE_ADD]);
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
<span id="el$rowindex$_permit_employee_username" class="form-group permit_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_permit_employee_username" class="form-group permit_employee_username">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="permit_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="permit"
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
    var el = document.querySelector("select[data-select2-id='permit_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "permit_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_permit_employee_username" class="form-group permit_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="permit" data-field="x_employee_username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="permit" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->permit_date->Visible) { // permit_date ?>
        <td data-name="permit_date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_permit_permit_date" class="form-group permit_permit_date">
<input type="<?= $Grid->permit_date->getInputTextType() ?>" data-table="permit" data-field="x_permit_date" data-format="5" name="x<?= $Grid->RowIndex ?>_permit_date" id="x<?= $Grid->RowIndex ?>_permit_date" placeholder="<?= HtmlEncode($Grid->permit_date->getPlaceHolder()) ?>" value="<?= $Grid->permit_date->EditValue ?>"<?= $Grid->permit_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->permit_date->getErrorMessage() ?></div>
<?php if (!$Grid->permit_date->ReadOnly && !$Grid->permit_date->Disabled && !isset($Grid->permit_date->EditAttrs["readonly"]) && !isset($Grid->permit_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpermitgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpermitgrid", "x<?= $Grid->RowIndex ?>_permit_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_permit_permit_date" class="form-group permit_permit_date">
<span<?= $Grid->permit_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->permit_date->getDisplayValue($Grid->permit_date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="permit" data-field="x_permit_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_permit_date" id="x<?= $Grid->RowIndex ?>_permit_date" value="<?= HtmlEncode($Grid->permit_date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="permit" data-field="x_permit_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_permit_date" id="o<?= $Grid->RowIndex ?>_permit_date" value="<?= HtmlEncode($Grid->permit_date->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->permit_type->Visible) { // permit_type ?>
        <td data-name="permit_type">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_permit_permit_type" class="form-group permit_permit_type">
    <select
        id="x<?= $Grid->RowIndex ?>_permit_type"
        name="x<?= $Grid->RowIndex ?>_permit_type"
        class="form-control ew-select<?= $Grid->permit_type->isInvalidClass() ?>"
        data-select2-id="permit_x<?= $Grid->RowIndex ?>_permit_type"
        data-table="permit"
        data-field="x_permit_type"
        data-value-separator="<?= $Grid->permit_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->permit_type->getPlaceHolder()) ?>"
        <?= $Grid->permit_type->editAttributes() ?>>
        <?= $Grid->permit_type->selectOptionListHtml("x{$Grid->RowIndex}_permit_type") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->permit_type->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permit_x<?= $Grid->RowIndex ?>_permit_type']"),
        options = { name: "x<?= $Grid->RowIndex ?>_permit_type", selectId: "permit_x<?= $Grid->RowIndex ?>_permit_type", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.permit.fields.permit_type.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.permit_type.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_permit_permit_type" class="form-group permit_permit_type">
<span<?= $Grid->permit_type->viewAttributes() ?>>
<?php if (!EmptyString($Grid->permit_type->ViewValue) && $Grid->permit_type->linkAttributes() != "") { ?>
<a<?= $Grid->permit_type->linkAttributes() ?>><input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->permit_type->getDisplayValue($Grid->permit_type->ViewValue))) ?>"></a>
<?php } else { ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->permit_type->getDisplayValue($Grid->permit_type->ViewValue))) ?>">
<?php } ?>
</span>
</span>
<input type="hidden" data-table="permit" data-field="x_permit_type" data-hidden="1" name="x<?= $Grid->RowIndex ?>_permit_type" id="x<?= $Grid->RowIndex ?>_permit_type" value="<?= HtmlEncode($Grid->permit_type->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="permit" data-field="x_permit_type" data-hidden="1" name="o<?= $Grid->RowIndex ?>_permit_type" id="o<?= $Grid->RowIndex ?>_permit_type" value="<?= HtmlEncode($Grid->permit_type->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->document->Visible) { // document ?>
        <td data-name="document">
<span id="el$rowindex$_permit_document" class="form-group permit_document">
<div id="fd_x<?= $Grid->RowIndex ?>_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->document->title() ?>" data-table="permit" data-field="x_document" name="x<?= $Grid->RowIndex ?>_document" id="x<?= $Grid->RowIndex ?>_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->document->editAttributes() ?><?= ($Grid->document->ReadOnly || $Grid->document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_document" id= "fn_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_document" id= "fa_x<?= $Grid->RowIndex ?>_document" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_document" id= "fs_x<?= $Grid->RowIndex ?>_document" value="250">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_document" id= "fx_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_document" id= "fm_x<?= $Grid->RowIndex ?>_document" value="<?= $Grid->document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="permit" data-field="x_document" data-hidden="1" name="o<?= $Grid->RowIndex ?>_document" id="o<?= $Grid->RowIndex ?>_document" value="<?= HtmlEncode($Grid->document->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->note->Visible) { // note ?>
        <td data-name="note">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_permit_note" class="form-group permit_note">
<textarea data-table="permit" data-field="x_note" name="x<?= $Grid->RowIndex ?>_note" id="x<?= $Grid->RowIndex ?>_note" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->note->getPlaceHolder()) ?>"<?= $Grid->note->editAttributes() ?>><?= $Grid->note->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->note->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_permit_note" class="form-group permit_note">
<span<?= $Grid->note->viewAttributes() ?>>
<?= $Grid->note->ViewValue ?></span>
</span>
<input type="hidden" data-table="permit" data-field="x_note" data-hidden="1" name="x<?= $Grid->RowIndex ?>_note" id="x<?= $Grid->RowIndex ?>_note" value="<?= HtmlEncode($Grid->note->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="permit" data-field="x_note" data-hidden="1" name="o<?= $Grid->RowIndex ?>_note" id="o<?= $Grid->RowIndex ?>_note" value="<?= HtmlEncode($Grid->note->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fpermitgrid","load"], function() {
    fpermitgrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fpermitgrid">
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
    ew.addEventHandlers("permit");
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
        container: "gmp_permit",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
