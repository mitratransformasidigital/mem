<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("EmployeeAssetGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.employee_asset) ew.vars.tables.employee_asset = <?= JsonEncode(GetClientVar("tables", "employee_asset")) ?>;
var currentForm, currentPageID;
var femployee_assetgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    femployee_assetgrid = new ew.Form("femployee_assetgrid", "grid");
    femployee_assetgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.employee_asset.fields;
    femployee_assetgrid.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["asset_name", [fields.asset_name.required ? ew.Validators.required(fields.asset_name.caption) : null], fields.asset_name.isInvalid],
        ["year", [fields.year.required ? ew.Validators.required(fields.year.caption) : null, ew.Validators.integer], fields.year.isInvalid],
        ["serial_number", [fields.serial_number.required ? ew.Validators.required(fields.serial_number.caption) : null], fields.serial_number.isInvalid],
        ["value", [fields.value.required ? ew.Validators.required(fields.value.caption) : null, ew.Validators.float], fields.value.isInvalid],
        ["asset_received", [fields.asset_received.required ? ew.Validators.required(fields.asset_received.caption) : null, ew.Validators.datetime(5)], fields.asset_received.isInvalid],
        ["asset_return", [fields.asset_return.required ? ew.Validators.required(fields.asset_return.caption) : null, ew.Validators.datetime(5)], fields.asset_return.isInvalid],
        ["asset_picture", [fields.asset_picture.required ? ew.Validators.fileRequired(fields.asset_picture.caption) : null], fields.asset_picture.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_assetgrid,
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
    femployee_assetgrid.validate = function () {
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
    femployee_assetgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "employee_username", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "asset_name", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "year", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "serial_number", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "value", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "asset_received", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "asset_return", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "asset_picture", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    femployee_assetgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_assetgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_assetgrid.lists.employee_username = <?= $Grid->employee_username->toClientList($Grid) ?>;
    loadjs.done("femployee_assetgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> employee_asset">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="femployee_assetgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_employee_asset" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_employee_assetgrid" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="employee_username" class="<?= $Grid->employee_username->headerCellClass() ?>"><div id="elh_employee_asset_employee_username" class="employee_asset_employee_username"><?= $Grid->renderSort($Grid->employee_username) ?></div></th>
<?php } ?>
<?php if ($Grid->asset_name->Visible) { // asset_name ?>
        <th data-name="asset_name" class="<?= $Grid->asset_name->headerCellClass() ?>"><div id="elh_employee_asset_asset_name" class="employee_asset_asset_name"><?= $Grid->renderSort($Grid->asset_name) ?></div></th>
<?php } ?>
<?php if ($Grid->year->Visible) { // year ?>
        <th data-name="year" class="<?= $Grid->year->headerCellClass() ?>"><div id="elh_employee_asset_year" class="employee_asset_year"><?= $Grid->renderSort($Grid->year) ?></div></th>
<?php } ?>
<?php if ($Grid->serial_number->Visible) { // serial_number ?>
        <th data-name="serial_number" class="<?= $Grid->serial_number->headerCellClass() ?>"><div id="elh_employee_asset_serial_number" class="employee_asset_serial_number"><?= $Grid->renderSort($Grid->serial_number) ?></div></th>
<?php } ?>
<?php if ($Grid->value->Visible) { // value ?>
        <th data-name="value" class="<?= $Grid->value->headerCellClass() ?>"><div id="elh_employee_asset_value" class="employee_asset_value"><?= $Grid->renderSort($Grid->value) ?></div></th>
<?php } ?>
<?php if ($Grid->asset_received->Visible) { // asset_received ?>
        <th data-name="asset_received" class="<?= $Grid->asset_received->headerCellClass() ?>"><div id="elh_employee_asset_asset_received" class="employee_asset_asset_received"><?= $Grid->renderSort($Grid->asset_received) ?></div></th>
<?php } ?>
<?php if ($Grid->asset_return->Visible) { // asset_return ?>
        <th data-name="asset_return" class="<?= $Grid->asset_return->headerCellClass() ?>"><div id="elh_employee_asset_asset_return" class="employee_asset_asset_return"><?= $Grid->renderSort($Grid->asset_return) ?></div></th>
<?php } ?>
<?php if ($Grid->asset_picture->Visible) { // asset_picture ?>
        <th data-name="asset_picture" class="<?= $Grid->asset_picture->headerCellClass() ?>"><div id="elh_employee_asset_asset_picture" class="employee_asset_asset_picture"><?= $Grid->renderSort($Grid->asset_picture) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_employee_asset", "data-rowtype" => $Grid->RowType]);

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
<span id="el<?= $Grid->RowCount ?>_employee_asset_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_asset_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_asset"
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
    var el = document.querySelector("select[data-select2-id='employee_asset_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_asset_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_asset.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="employee_asset" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->employee_username->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_asset_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_asset"
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
    var el = document.querySelector("select[data-select2-id='employee_asset_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_asset_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_asset.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<?= $Grid->employee_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_asset" data-field="x_employee_username" data-hidden="1" name="femployee_assetgrid$x<?= $Grid->RowIndex ?>_employee_username" id="femployee_assetgrid$x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<input type="hidden" data-table="employee_asset" data-field="x_employee_username" data-hidden="1" name="femployee_assetgrid$o<?= $Grid->RowIndex ?>_employee_username" id="femployee_assetgrid$o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->asset_name->Visible) { // asset_name ?>
        <td data-name="asset_name" <?= $Grid->asset_name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_name" class="form-group">
<input type="<?= $Grid->asset_name->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_name" name="x<?= $Grid->RowIndex ?>_asset_name" id="x<?= $Grid->RowIndex ?>_asset_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->asset_name->getPlaceHolder()) ?>" value="<?= $Grid->asset_name->EditValue ?>"<?= $Grid->asset_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asset_name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_asset_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_asset_name" id="o<?= $Grid->RowIndex ?>_asset_name" value="<?= HtmlEncode($Grid->asset_name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_name" class="form-group">
<input type="<?= $Grid->asset_name->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_name" name="x<?= $Grid->RowIndex ?>_asset_name" id="x<?= $Grid->RowIndex ?>_asset_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->asset_name->getPlaceHolder()) ?>" value="<?= $Grid->asset_name->EditValue ?>"<?= $Grid->asset_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asset_name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_name">
<span<?= $Grid->asset_name->viewAttributes() ?>>
<?= $Grid->asset_name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_asset" data-field="x_asset_name" data-hidden="1" name="femployee_assetgrid$x<?= $Grid->RowIndex ?>_asset_name" id="femployee_assetgrid$x<?= $Grid->RowIndex ?>_asset_name" value="<?= HtmlEncode($Grid->asset_name->FormValue) ?>">
<input type="hidden" data-table="employee_asset" data-field="x_asset_name" data-hidden="1" name="femployee_assetgrid$o<?= $Grid->RowIndex ?>_asset_name" id="femployee_assetgrid$o<?= $Grid->RowIndex ?>_asset_name" value="<?= HtmlEncode($Grid->asset_name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->year->Visible) { // year ?>
        <td data-name="year" <?= $Grid->year->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_year" class="form-group">
<input type="<?= $Grid->year->getInputTextType() ?>" data-table="employee_asset" data-field="x_year" name="x<?= $Grid->RowIndex ?>_year" id="x<?= $Grid->RowIndex ?>_year" size="30" placeholder="<?= HtmlEncode($Grid->year->getPlaceHolder()) ?>" value="<?= $Grid->year->EditValue ?>"<?= $Grid->year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->year->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_year" data-hidden="1" name="o<?= $Grid->RowIndex ?>_year" id="o<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_year" class="form-group">
<input type="<?= $Grid->year->getInputTextType() ?>" data-table="employee_asset" data-field="x_year" name="x<?= $Grid->RowIndex ?>_year" id="x<?= $Grid->RowIndex ?>_year" size="30" placeholder="<?= HtmlEncode($Grid->year->getPlaceHolder()) ?>" value="<?= $Grid->year->EditValue ?>"<?= $Grid->year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->year->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_year">
<span<?= $Grid->year->viewAttributes() ?>>
<?= $Grid->year->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_asset" data-field="x_year" data-hidden="1" name="femployee_assetgrid$x<?= $Grid->RowIndex ?>_year" id="femployee_assetgrid$x<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->FormValue) ?>">
<input type="hidden" data-table="employee_asset" data-field="x_year" data-hidden="1" name="femployee_assetgrid$o<?= $Grid->RowIndex ?>_year" id="femployee_assetgrid$o<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->serial_number->Visible) { // serial_number ?>
        <td data-name="serial_number" <?= $Grid->serial_number->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_serial_number" class="form-group">
<input type="<?= $Grid->serial_number->getInputTextType() ?>" data-table="employee_asset" data-field="x_serial_number" name="x<?= $Grid->RowIndex ?>_serial_number" id="x<?= $Grid->RowIndex ?>_serial_number" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->serial_number->getPlaceHolder()) ?>" value="<?= $Grid->serial_number->EditValue ?>"<?= $Grid->serial_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->serial_number->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_serial_number" data-hidden="1" name="o<?= $Grid->RowIndex ?>_serial_number" id="o<?= $Grid->RowIndex ?>_serial_number" value="<?= HtmlEncode($Grid->serial_number->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_serial_number" class="form-group">
<input type="<?= $Grid->serial_number->getInputTextType() ?>" data-table="employee_asset" data-field="x_serial_number" name="x<?= $Grid->RowIndex ?>_serial_number" id="x<?= $Grid->RowIndex ?>_serial_number" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->serial_number->getPlaceHolder()) ?>" value="<?= $Grid->serial_number->EditValue ?>"<?= $Grid->serial_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->serial_number->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_serial_number">
<span<?= $Grid->serial_number->viewAttributes() ?>>
<?= $Grid->serial_number->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_asset" data-field="x_serial_number" data-hidden="1" name="femployee_assetgrid$x<?= $Grid->RowIndex ?>_serial_number" id="femployee_assetgrid$x<?= $Grid->RowIndex ?>_serial_number" value="<?= HtmlEncode($Grid->serial_number->FormValue) ?>">
<input type="hidden" data-table="employee_asset" data-field="x_serial_number" data-hidden="1" name="femployee_assetgrid$o<?= $Grid->RowIndex ?>_serial_number" id="femployee_assetgrid$o<?= $Grid->RowIndex ?>_serial_number" value="<?= HtmlEncode($Grid->serial_number->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->value->Visible) { // value ?>
        <td data-name="value" <?= $Grid->value->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_value" class="form-group">
<input type="<?= $Grid->value->getInputTextType() ?>" data-table="employee_asset" data-field="x_value" name="x<?= $Grid->RowIndex ?>_value" id="x<?= $Grid->RowIndex ?>_value" size="30" placeholder="<?= HtmlEncode($Grid->value->getPlaceHolder()) ?>" value="<?= $Grid->value->EditValue ?>"<?= $Grid->value->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->value->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_value" data-hidden="1" name="o<?= $Grid->RowIndex ?>_value" id="o<?= $Grid->RowIndex ?>_value" value="<?= HtmlEncode($Grid->value->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_value" class="form-group">
<input type="<?= $Grid->value->getInputTextType() ?>" data-table="employee_asset" data-field="x_value" name="x<?= $Grid->RowIndex ?>_value" id="x<?= $Grid->RowIndex ?>_value" size="30" placeholder="<?= HtmlEncode($Grid->value->getPlaceHolder()) ?>" value="<?= $Grid->value->EditValue ?>"<?= $Grid->value->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->value->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_value">
<span<?= $Grid->value->viewAttributes() ?>>
<?= $Grid->value->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_asset" data-field="x_value" data-hidden="1" name="femployee_assetgrid$x<?= $Grid->RowIndex ?>_value" id="femployee_assetgrid$x<?= $Grid->RowIndex ?>_value" value="<?= HtmlEncode($Grid->value->FormValue) ?>">
<input type="hidden" data-table="employee_asset" data-field="x_value" data-hidden="1" name="femployee_assetgrid$o<?= $Grid->RowIndex ?>_value" id="femployee_assetgrid$o<?= $Grid->RowIndex ?>_value" value="<?= HtmlEncode($Grid->value->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->asset_received->Visible) { // asset_received ?>
        <td data-name="asset_received" <?= $Grid->asset_received->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_received" class="form-group">
<input type="<?= $Grid->asset_received->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_received" data-format="5" name="x<?= $Grid->RowIndex ?>_asset_received" id="x<?= $Grid->RowIndex ?>_asset_received" placeholder="<?= HtmlEncode($Grid->asset_received->getPlaceHolder()) ?>" value="<?= $Grid->asset_received->EditValue ?>"<?= $Grid->asset_received->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asset_received->getErrorMessage() ?></div>
<?php if (!$Grid->asset_received->ReadOnly && !$Grid->asset_received->Disabled && !isset($Grid->asset_received->EditAttrs["readonly"]) && !isset($Grid->asset_received->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_assetgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_assetgrid", "x<?= $Grid->RowIndex ?>_asset_received", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_asset_received" data-hidden="1" name="o<?= $Grid->RowIndex ?>_asset_received" id="o<?= $Grid->RowIndex ?>_asset_received" value="<?= HtmlEncode($Grid->asset_received->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_received" class="form-group">
<input type="<?= $Grid->asset_received->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_received" data-format="5" name="x<?= $Grid->RowIndex ?>_asset_received" id="x<?= $Grid->RowIndex ?>_asset_received" placeholder="<?= HtmlEncode($Grid->asset_received->getPlaceHolder()) ?>" value="<?= $Grid->asset_received->EditValue ?>"<?= $Grid->asset_received->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asset_received->getErrorMessage() ?></div>
<?php if (!$Grid->asset_received->ReadOnly && !$Grid->asset_received->Disabled && !isset($Grid->asset_received->EditAttrs["readonly"]) && !isset($Grid->asset_received->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_assetgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_assetgrid", "x<?= $Grid->RowIndex ?>_asset_received", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_received">
<span<?= $Grid->asset_received->viewAttributes() ?>>
<?= $Grid->asset_received->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_asset" data-field="x_asset_received" data-hidden="1" name="femployee_assetgrid$x<?= $Grid->RowIndex ?>_asset_received" id="femployee_assetgrid$x<?= $Grid->RowIndex ?>_asset_received" value="<?= HtmlEncode($Grid->asset_received->FormValue) ?>">
<input type="hidden" data-table="employee_asset" data-field="x_asset_received" data-hidden="1" name="femployee_assetgrid$o<?= $Grid->RowIndex ?>_asset_received" id="femployee_assetgrid$o<?= $Grid->RowIndex ?>_asset_received" value="<?= HtmlEncode($Grid->asset_received->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->asset_return->Visible) { // asset_return ?>
        <td data-name="asset_return" <?= $Grid->asset_return->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_return" class="form-group">
<input type="<?= $Grid->asset_return->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_return" data-format="5" name="x<?= $Grid->RowIndex ?>_asset_return" id="x<?= $Grid->RowIndex ?>_asset_return" placeholder="<?= HtmlEncode($Grid->asset_return->getPlaceHolder()) ?>" value="<?= $Grid->asset_return->EditValue ?>"<?= $Grid->asset_return->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asset_return->getErrorMessage() ?></div>
<?php if (!$Grid->asset_return->ReadOnly && !$Grid->asset_return->Disabled && !isset($Grid->asset_return->EditAttrs["readonly"]) && !isset($Grid->asset_return->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_assetgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_assetgrid", "x<?= $Grid->RowIndex ?>_asset_return", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_asset_return" data-hidden="1" name="o<?= $Grid->RowIndex ?>_asset_return" id="o<?= $Grid->RowIndex ?>_asset_return" value="<?= HtmlEncode($Grid->asset_return->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_return" class="form-group">
<input type="<?= $Grid->asset_return->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_return" data-format="5" name="x<?= $Grid->RowIndex ?>_asset_return" id="x<?= $Grid->RowIndex ?>_asset_return" placeholder="<?= HtmlEncode($Grid->asset_return->getPlaceHolder()) ?>" value="<?= $Grid->asset_return->EditValue ?>"<?= $Grid->asset_return->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asset_return->getErrorMessage() ?></div>
<?php if (!$Grid->asset_return->ReadOnly && !$Grid->asset_return->Disabled && !isset($Grid->asset_return->EditAttrs["readonly"]) && !isset($Grid->asset_return->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_assetgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_assetgrid", "x<?= $Grid->RowIndex ?>_asset_return", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_return">
<span<?= $Grid->asset_return->viewAttributes() ?>>
<?= $Grid->asset_return->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_asset" data-field="x_asset_return" data-hidden="1" name="femployee_assetgrid$x<?= $Grid->RowIndex ?>_asset_return" id="femployee_assetgrid$x<?= $Grid->RowIndex ?>_asset_return" value="<?= HtmlEncode($Grid->asset_return->FormValue) ?>">
<input type="hidden" data-table="employee_asset" data-field="x_asset_return" data-hidden="1" name="femployee_assetgrid$o<?= $Grid->RowIndex ?>_asset_return" id="femployee_assetgrid$o<?= $Grid->RowIndex ?>_asset_return" value="<?= HtmlEncode($Grid->asset_return->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->asset_picture->Visible) { // asset_picture ?>
        <td data-name="asset_picture" <?= $Grid->asset_picture->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_employee_asset_asset_picture" class="form-group employee_asset_asset_picture">
<div id="fd_x<?= $Grid->RowIndex ?>_asset_picture">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->asset_picture->title() ?>" data-table="employee_asset" data-field="x_asset_picture" name="x<?= $Grid->RowIndex ?>_asset_picture" id="x<?= $Grid->RowIndex ?>_asset_picture" lang="<?= CurrentLanguageID() ?>"<?= $Grid->asset_picture->editAttributes() ?><?= ($Grid->asset_picture->ReadOnly || $Grid->asset_picture->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_asset_picture"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->asset_picture->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_asset_picture" id= "fn_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= $Grid->asset_picture->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_asset_picture" id= "fa_x<?= $Grid->RowIndex ?>_asset_picture" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_asset_picture" id= "fs_x<?= $Grid->RowIndex ?>_asset_picture" value="150">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_asset_picture" id= "fx_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= $Grid->asset_picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_asset_picture" id= "fm_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= $Grid->asset_picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_asset_picture" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_asset_picture" data-hidden="1" name="o<?= $Grid->RowIndex ?>_asset_picture" id="o<?= $Grid->RowIndex ?>_asset_picture" value="<?= HtmlEncode($Grid->asset_picture->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_picture">
<span<?= $Grid->asset_picture->viewAttributes() ?>>
<?= GetFileViewTag($Grid->asset_picture, $Grid->asset_picture->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_asset_asset_picture" class="form-group employee_asset_asset_picture">
<div id="fd_x<?= $Grid->RowIndex ?>_asset_picture">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->asset_picture->title() ?>" data-table="employee_asset" data-field="x_asset_picture" name="x<?= $Grid->RowIndex ?>_asset_picture" id="x<?= $Grid->RowIndex ?>_asset_picture" lang="<?= CurrentLanguageID() ?>"<?= $Grid->asset_picture->editAttributes() ?><?= ($Grid->asset_picture->ReadOnly || $Grid->asset_picture->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_asset_picture"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->asset_picture->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_asset_picture" id= "fn_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= $Grid->asset_picture->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_asset_picture" id= "fa_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_asset_picture") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_asset_picture" id= "fs_x<?= $Grid->RowIndex ?>_asset_picture" value="150">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_asset_picture" id= "fx_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= $Grid->asset_picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_asset_picture" id= "fm_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= $Grid->asset_picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_asset_picture" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
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
loadjs.ready(["femployee_assetgrid","load"], function () {
    femployee_assetgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_employee_asset", "data-rowtype" => ROWTYPE_ADD]);
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
<span id="el$rowindex$_employee_asset_employee_username" class="form-group employee_asset_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_employee_asset_employee_username" class="form-group employee_asset_employee_username">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_asset_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_asset"
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
    var el = document.querySelector("select[data-select2-id='employee_asset_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_asset_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_asset.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_employee_asset_employee_username" class="form-group employee_asset_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_employee_username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_asset" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->asset_name->Visible) { // asset_name ?>
        <td data-name="asset_name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_asset_asset_name" class="form-group employee_asset_asset_name">
<input type="<?= $Grid->asset_name->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_name" name="x<?= $Grid->RowIndex ?>_asset_name" id="x<?= $Grid->RowIndex ?>_asset_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->asset_name->getPlaceHolder()) ?>" value="<?= $Grid->asset_name->EditValue ?>"<?= $Grid->asset_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asset_name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_asset_asset_name" class="form-group employee_asset_asset_name">
<span<?= $Grid->asset_name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->asset_name->getDisplayValue($Grid->asset_name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_asset_name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_asset_name" id="x<?= $Grid->RowIndex ?>_asset_name" value="<?= HtmlEncode($Grid->asset_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_asset" data-field="x_asset_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_asset_name" id="o<?= $Grid->RowIndex ?>_asset_name" value="<?= HtmlEncode($Grid->asset_name->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->year->Visible) { // year ?>
        <td data-name="year">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_asset_year" class="form-group employee_asset_year">
<input type="<?= $Grid->year->getInputTextType() ?>" data-table="employee_asset" data-field="x_year" name="x<?= $Grid->RowIndex ?>_year" id="x<?= $Grid->RowIndex ?>_year" size="30" placeholder="<?= HtmlEncode($Grid->year->getPlaceHolder()) ?>" value="<?= $Grid->year->EditValue ?>"<?= $Grid->year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->year->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_asset_year" class="form-group employee_asset_year">
<span<?= $Grid->year->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->year->getDisplayValue($Grid->year->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_year" data-hidden="1" name="x<?= $Grid->RowIndex ?>_year" id="x<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_asset" data-field="x_year" data-hidden="1" name="o<?= $Grid->RowIndex ?>_year" id="o<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->serial_number->Visible) { // serial_number ?>
        <td data-name="serial_number">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_asset_serial_number" class="form-group employee_asset_serial_number">
<input type="<?= $Grid->serial_number->getInputTextType() ?>" data-table="employee_asset" data-field="x_serial_number" name="x<?= $Grid->RowIndex ?>_serial_number" id="x<?= $Grid->RowIndex ?>_serial_number" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->serial_number->getPlaceHolder()) ?>" value="<?= $Grid->serial_number->EditValue ?>"<?= $Grid->serial_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->serial_number->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_asset_serial_number" class="form-group employee_asset_serial_number">
<span<?= $Grid->serial_number->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->serial_number->getDisplayValue($Grid->serial_number->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_serial_number" data-hidden="1" name="x<?= $Grid->RowIndex ?>_serial_number" id="x<?= $Grid->RowIndex ?>_serial_number" value="<?= HtmlEncode($Grid->serial_number->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_asset" data-field="x_serial_number" data-hidden="1" name="o<?= $Grid->RowIndex ?>_serial_number" id="o<?= $Grid->RowIndex ?>_serial_number" value="<?= HtmlEncode($Grid->serial_number->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->value->Visible) { // value ?>
        <td data-name="value">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_asset_value" class="form-group employee_asset_value">
<input type="<?= $Grid->value->getInputTextType() ?>" data-table="employee_asset" data-field="x_value" name="x<?= $Grid->RowIndex ?>_value" id="x<?= $Grid->RowIndex ?>_value" size="30" placeholder="<?= HtmlEncode($Grid->value->getPlaceHolder()) ?>" value="<?= $Grid->value->EditValue ?>"<?= $Grid->value->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->value->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_asset_value" class="form-group employee_asset_value">
<span<?= $Grid->value->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->value->getDisplayValue($Grid->value->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_value" data-hidden="1" name="x<?= $Grid->RowIndex ?>_value" id="x<?= $Grid->RowIndex ?>_value" value="<?= HtmlEncode($Grid->value->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_asset" data-field="x_value" data-hidden="1" name="o<?= $Grid->RowIndex ?>_value" id="o<?= $Grid->RowIndex ?>_value" value="<?= HtmlEncode($Grid->value->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->asset_received->Visible) { // asset_received ?>
        <td data-name="asset_received">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_asset_asset_received" class="form-group employee_asset_asset_received">
<input type="<?= $Grid->asset_received->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_received" data-format="5" name="x<?= $Grid->RowIndex ?>_asset_received" id="x<?= $Grid->RowIndex ?>_asset_received" placeholder="<?= HtmlEncode($Grid->asset_received->getPlaceHolder()) ?>" value="<?= $Grid->asset_received->EditValue ?>"<?= $Grid->asset_received->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asset_received->getErrorMessage() ?></div>
<?php if (!$Grid->asset_received->ReadOnly && !$Grid->asset_received->Disabled && !isset($Grid->asset_received->EditAttrs["readonly"]) && !isset($Grid->asset_received->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_assetgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_assetgrid", "x<?= $Grid->RowIndex ?>_asset_received", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_asset_asset_received" class="form-group employee_asset_asset_received">
<span<?= $Grid->asset_received->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->asset_received->getDisplayValue($Grid->asset_received->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_asset_received" data-hidden="1" name="x<?= $Grid->RowIndex ?>_asset_received" id="x<?= $Grid->RowIndex ?>_asset_received" value="<?= HtmlEncode($Grid->asset_received->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_asset" data-field="x_asset_received" data-hidden="1" name="o<?= $Grid->RowIndex ?>_asset_received" id="o<?= $Grid->RowIndex ?>_asset_received" value="<?= HtmlEncode($Grid->asset_received->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->asset_return->Visible) { // asset_return ?>
        <td data-name="asset_return">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_asset_asset_return" class="form-group employee_asset_asset_return">
<input type="<?= $Grid->asset_return->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_return" data-format="5" name="x<?= $Grid->RowIndex ?>_asset_return" id="x<?= $Grid->RowIndex ?>_asset_return" placeholder="<?= HtmlEncode($Grid->asset_return->getPlaceHolder()) ?>" value="<?= $Grid->asset_return->EditValue ?>"<?= $Grid->asset_return->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->asset_return->getErrorMessage() ?></div>
<?php if (!$Grid->asset_return->ReadOnly && !$Grid->asset_return->Disabled && !isset($Grid->asset_return->EditAttrs["readonly"]) && !isset($Grid->asset_return->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_assetgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_assetgrid", "x<?= $Grid->RowIndex ?>_asset_return", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_asset_asset_return" class="form-group employee_asset_asset_return">
<span<?= $Grid->asset_return->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->asset_return->getDisplayValue($Grid->asset_return->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_asset_return" data-hidden="1" name="x<?= $Grid->RowIndex ?>_asset_return" id="x<?= $Grid->RowIndex ?>_asset_return" value="<?= HtmlEncode($Grid->asset_return->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_asset" data-field="x_asset_return" data-hidden="1" name="o<?= $Grid->RowIndex ?>_asset_return" id="o<?= $Grid->RowIndex ?>_asset_return" value="<?= HtmlEncode($Grid->asset_return->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->asset_picture->Visible) { // asset_picture ?>
        <td data-name="asset_picture">
<span id="el$rowindex$_employee_asset_asset_picture" class="form-group employee_asset_asset_picture">
<div id="fd_x<?= $Grid->RowIndex ?>_asset_picture">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->asset_picture->title() ?>" data-table="employee_asset" data-field="x_asset_picture" name="x<?= $Grid->RowIndex ?>_asset_picture" id="x<?= $Grid->RowIndex ?>_asset_picture" lang="<?= CurrentLanguageID() ?>"<?= $Grid->asset_picture->editAttributes() ?><?= ($Grid->asset_picture->ReadOnly || $Grid->asset_picture->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_asset_picture"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->asset_picture->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_asset_picture" id= "fn_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= $Grid->asset_picture->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_asset_picture" id= "fa_x<?= $Grid->RowIndex ?>_asset_picture" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_asset_picture" id= "fs_x<?= $Grid->RowIndex ?>_asset_picture" value="150">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_asset_picture" id= "fx_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= $Grid->asset_picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_asset_picture" id= "fm_x<?= $Grid->RowIndex ?>_asset_picture" value="<?= $Grid->asset_picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_asset_picture" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="employee_asset" data-field="x_asset_picture" data-hidden="1" name="o<?= $Grid->RowIndex ?>_asset_picture" id="o<?= $Grid->RowIndex ?>_asset_picture" value="<?= HtmlEncode($Grid->asset_picture->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["femployee_assetgrid","load"], function() {
    femployee_assetgrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="femployee_assetgrid">
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
    ew.addEventHandlers("employee_asset");
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
        container: "gmp_employee_asset",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
