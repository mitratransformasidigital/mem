<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("MytrainingGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.mytraining) ew.vars.tables.mytraining = <?= JsonEncode(GetClientVar("tables", "mytraining")) ?>;
var currentForm, currentPageID;
var fmytraininggrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fmytraininggrid = new ew.Form("fmytraininggrid", "grid");
    fmytraininggrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.mytraining.fields;
    fmytraininggrid.addFields([
        ["training_name", [fields.training_name.required ? ew.Validators.required(fields.training_name.caption) : null], fields.training_name.isInvalid],
        ["training_start", [fields.training_start.required ? ew.Validators.required(fields.training_start.caption) : null, ew.Validators.datetime(0)], fields.training_start.isInvalid],
        ["training_end", [fields.training_end.required ? ew.Validators.required(fields.training_end.caption) : null, ew.Validators.datetime(0)], fields.training_end.isInvalid],
        ["training_company", [fields.training_company.required ? ew.Validators.required(fields.training_company.caption) : null], fields.training_company.isInvalid],
        ["certificate_start", [fields.certificate_start.required ? ew.Validators.required(fields.certificate_start.caption) : null, ew.Validators.datetime(0)], fields.certificate_start.isInvalid],
        ["certificate_end", [fields.certificate_end.required ? ew.Validators.required(fields.certificate_end.caption) : null, ew.Validators.datetime(0)], fields.certificate_end.isInvalid],
        ["training_document", [fields.training_document.required ? ew.Validators.fileRequired(fields.training_document.caption) : null], fields.training_document.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmytraininggrid,
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
    fmytraininggrid.validate = function () {
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
    fmytraininggrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "training_name", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "training_start", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "training_end", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "training_company", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "certificate_start", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "certificate_end", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "training_document", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fmytraininggrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmytraininggrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmytraininggrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> mytraining">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fmytraininggrid" class="ew-form ew-list-form form-inline">
<div id="gmp_mytraining" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_mytraininggrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->training_name->Visible) { // training_name ?>
        <th data-name="training_name" class="<?= $Grid->training_name->headerCellClass() ?>"><div id="elh_mytraining_training_name" class="mytraining_training_name"><?= $Grid->renderSort($Grid->training_name) ?></div></th>
<?php } ?>
<?php if ($Grid->training_start->Visible) { // training_start ?>
        <th data-name="training_start" class="<?= $Grid->training_start->headerCellClass() ?>"><div id="elh_mytraining_training_start" class="mytraining_training_start"><?= $Grid->renderSort($Grid->training_start) ?></div></th>
<?php } ?>
<?php if ($Grid->training_end->Visible) { // training_end ?>
        <th data-name="training_end" class="<?= $Grid->training_end->headerCellClass() ?>"><div id="elh_mytraining_training_end" class="mytraining_training_end"><?= $Grid->renderSort($Grid->training_end) ?></div></th>
<?php } ?>
<?php if ($Grid->training_company->Visible) { // training_company ?>
        <th data-name="training_company" class="<?= $Grid->training_company->headerCellClass() ?>"><div id="elh_mytraining_training_company" class="mytraining_training_company"><?= $Grid->renderSort($Grid->training_company) ?></div></th>
<?php } ?>
<?php if ($Grid->certificate_start->Visible) { // certificate_start ?>
        <th data-name="certificate_start" class="<?= $Grid->certificate_start->headerCellClass() ?>"><div id="elh_mytraining_certificate_start" class="mytraining_certificate_start"><?= $Grid->renderSort($Grid->certificate_start) ?></div></th>
<?php } ?>
<?php if ($Grid->certificate_end->Visible) { // certificate_end ?>
        <th data-name="certificate_end" class="<?= $Grid->certificate_end->headerCellClass() ?>"><div id="elh_mytraining_certificate_end" class="mytraining_certificate_end"><?= $Grid->renderSort($Grid->certificate_end) ?></div></th>
<?php } ?>
<?php if ($Grid->training_document->Visible) { // training_document ?>
        <th data-name="training_document" class="<?= $Grid->training_document->headerCellClass() ?>"><div id="elh_mytraining_training_document" class="mytraining_training_document"><?= $Grid->renderSort($Grid->training_document) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_mytraining", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->training_name->Visible) { // training_name ?>
        <td data-name="training_name" <?= $Grid->training_name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_name" class="form-group">
<input type="<?= $Grid->training_name->getInputTextType() ?>" data-table="mytraining" data-field="x_training_name" name="x<?= $Grid->RowIndex ?>_training_name" id="x<?= $Grid->RowIndex ?>_training_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->training_name->getPlaceHolder()) ?>" value="<?= $Grid->training_name->EditValue ?>"<?= $Grid->training_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_name" id="o<?= $Grid->RowIndex ?>_training_name" value="<?= HtmlEncode($Grid->training_name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_name" class="form-group">
<input type="<?= $Grid->training_name->getInputTextType() ?>" data-table="mytraining" data-field="x_training_name" name="x<?= $Grid->RowIndex ?>_training_name" id="x<?= $Grid->RowIndex ?>_training_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->training_name->getPlaceHolder()) ?>" value="<?= $Grid->training_name->EditValue ?>"<?= $Grid->training_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_name">
<span<?= $Grid->training_name->viewAttributes() ?>>
<?= $Grid->training_name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mytraining" data-field="x_training_name" data-hidden="1" name="fmytraininggrid$x<?= $Grid->RowIndex ?>_training_name" id="fmytraininggrid$x<?= $Grid->RowIndex ?>_training_name" value="<?= HtmlEncode($Grid->training_name->FormValue) ?>">
<input type="hidden" data-table="mytraining" data-field="x_training_name" data-hidden="1" name="fmytraininggrid$o<?= $Grid->RowIndex ?>_training_name" id="fmytraininggrid$o<?= $Grid->RowIndex ?>_training_name" value="<?= HtmlEncode($Grid->training_name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->training_start->Visible) { // training_start ?>
        <td data-name="training_start" <?= $Grid->training_start->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_start" class="form-group">
<input type="<?= $Grid->training_start->getInputTextType() ?>" data-table="mytraining" data-field="x_training_start" name="x<?= $Grid->RowIndex ?>_training_start" id="x<?= $Grid->RowIndex ?>_training_start" placeholder="<?= HtmlEncode($Grid->training_start->getPlaceHolder()) ?>" value="<?= $Grid->training_start->EditValue ?>"<?= $Grid->training_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_start->getErrorMessage() ?></div>
<?php if (!$Grid->training_start->ReadOnly && !$Grid->training_start->Disabled && !isset($Grid->training_start->EditAttrs["readonly"]) && !isset($Grid->training_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_training_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_start" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_start" id="o<?= $Grid->RowIndex ?>_training_start" value="<?= HtmlEncode($Grid->training_start->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_start" class="form-group">
<input type="<?= $Grid->training_start->getInputTextType() ?>" data-table="mytraining" data-field="x_training_start" name="x<?= $Grid->RowIndex ?>_training_start" id="x<?= $Grid->RowIndex ?>_training_start" placeholder="<?= HtmlEncode($Grid->training_start->getPlaceHolder()) ?>" value="<?= $Grid->training_start->EditValue ?>"<?= $Grid->training_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_start->getErrorMessage() ?></div>
<?php if (!$Grid->training_start->ReadOnly && !$Grid->training_start->Disabled && !isset($Grid->training_start->EditAttrs["readonly"]) && !isset($Grid->training_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_training_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_start">
<span<?= $Grid->training_start->viewAttributes() ?>>
<?= $Grid->training_start->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mytraining" data-field="x_training_start" data-hidden="1" name="fmytraininggrid$x<?= $Grid->RowIndex ?>_training_start" id="fmytraininggrid$x<?= $Grid->RowIndex ?>_training_start" value="<?= HtmlEncode($Grid->training_start->FormValue) ?>">
<input type="hidden" data-table="mytraining" data-field="x_training_start" data-hidden="1" name="fmytraininggrid$o<?= $Grid->RowIndex ?>_training_start" id="fmytraininggrid$o<?= $Grid->RowIndex ?>_training_start" value="<?= HtmlEncode($Grid->training_start->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->training_end->Visible) { // training_end ?>
        <td data-name="training_end" <?= $Grid->training_end->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_end" class="form-group">
<input type="<?= $Grid->training_end->getInputTextType() ?>" data-table="mytraining" data-field="x_training_end" name="x<?= $Grid->RowIndex ?>_training_end" id="x<?= $Grid->RowIndex ?>_training_end" placeholder="<?= HtmlEncode($Grid->training_end->getPlaceHolder()) ?>" value="<?= $Grid->training_end->EditValue ?>"<?= $Grid->training_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_end->getErrorMessage() ?></div>
<?php if (!$Grid->training_end->ReadOnly && !$Grid->training_end->Disabled && !isset($Grid->training_end->EditAttrs["readonly"]) && !isset($Grid->training_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_training_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_end" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_end" id="o<?= $Grid->RowIndex ?>_training_end" value="<?= HtmlEncode($Grid->training_end->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_end" class="form-group">
<input type="<?= $Grid->training_end->getInputTextType() ?>" data-table="mytraining" data-field="x_training_end" name="x<?= $Grid->RowIndex ?>_training_end" id="x<?= $Grid->RowIndex ?>_training_end" placeholder="<?= HtmlEncode($Grid->training_end->getPlaceHolder()) ?>" value="<?= $Grid->training_end->EditValue ?>"<?= $Grid->training_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_end->getErrorMessage() ?></div>
<?php if (!$Grid->training_end->ReadOnly && !$Grid->training_end->Disabled && !isset($Grid->training_end->EditAttrs["readonly"]) && !isset($Grid->training_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_training_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_end">
<span<?= $Grid->training_end->viewAttributes() ?>>
<?= $Grid->training_end->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mytraining" data-field="x_training_end" data-hidden="1" name="fmytraininggrid$x<?= $Grid->RowIndex ?>_training_end" id="fmytraininggrid$x<?= $Grid->RowIndex ?>_training_end" value="<?= HtmlEncode($Grid->training_end->FormValue) ?>">
<input type="hidden" data-table="mytraining" data-field="x_training_end" data-hidden="1" name="fmytraininggrid$o<?= $Grid->RowIndex ?>_training_end" id="fmytraininggrid$o<?= $Grid->RowIndex ?>_training_end" value="<?= HtmlEncode($Grid->training_end->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->training_company->Visible) { // training_company ?>
        <td data-name="training_company" <?= $Grid->training_company->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_company" class="form-group">
<input type="<?= $Grid->training_company->getInputTextType() ?>" data-table="mytraining" data-field="x_training_company" name="x<?= $Grid->RowIndex ?>_training_company" id="x<?= $Grid->RowIndex ?>_training_company" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->training_company->getPlaceHolder()) ?>" value="<?= $Grid->training_company->EditValue ?>"<?= $Grid->training_company->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_company->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_company" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_company" id="o<?= $Grid->RowIndex ?>_training_company" value="<?= HtmlEncode($Grid->training_company->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_company" class="form-group">
<input type="<?= $Grid->training_company->getInputTextType() ?>" data-table="mytraining" data-field="x_training_company" name="x<?= $Grid->RowIndex ?>_training_company" id="x<?= $Grid->RowIndex ?>_training_company" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->training_company->getPlaceHolder()) ?>" value="<?= $Grid->training_company->EditValue ?>"<?= $Grid->training_company->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_company->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_company">
<span<?= $Grid->training_company->viewAttributes() ?>>
<?= $Grid->training_company->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mytraining" data-field="x_training_company" data-hidden="1" name="fmytraininggrid$x<?= $Grid->RowIndex ?>_training_company" id="fmytraininggrid$x<?= $Grid->RowIndex ?>_training_company" value="<?= HtmlEncode($Grid->training_company->FormValue) ?>">
<input type="hidden" data-table="mytraining" data-field="x_training_company" data-hidden="1" name="fmytraininggrid$o<?= $Grid->RowIndex ?>_training_company" id="fmytraininggrid$o<?= $Grid->RowIndex ?>_training_company" value="<?= HtmlEncode($Grid->training_company->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->certificate_start->Visible) { // certificate_start ?>
        <td data-name="certificate_start" <?= $Grid->certificate_start->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_certificate_start" class="form-group">
<input type="<?= $Grid->certificate_start->getInputTextType() ?>" data-table="mytraining" data-field="x_certificate_start" name="x<?= $Grid->RowIndex ?>_certificate_start" id="x<?= $Grid->RowIndex ?>_certificate_start" placeholder="<?= HtmlEncode($Grid->certificate_start->getPlaceHolder()) ?>" value="<?= $Grid->certificate_start->EditValue ?>"<?= $Grid->certificate_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->certificate_start->getErrorMessage() ?></div>
<?php if (!$Grid->certificate_start->ReadOnly && !$Grid->certificate_start->Disabled && !isset($Grid->certificate_start->EditAttrs["readonly"]) && !isset($Grid->certificate_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_certificate_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="mytraining" data-field="x_certificate_start" data-hidden="1" name="o<?= $Grid->RowIndex ?>_certificate_start" id="o<?= $Grid->RowIndex ?>_certificate_start" value="<?= HtmlEncode($Grid->certificate_start->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_certificate_start" class="form-group">
<input type="<?= $Grid->certificate_start->getInputTextType() ?>" data-table="mytraining" data-field="x_certificate_start" name="x<?= $Grid->RowIndex ?>_certificate_start" id="x<?= $Grid->RowIndex ?>_certificate_start" placeholder="<?= HtmlEncode($Grid->certificate_start->getPlaceHolder()) ?>" value="<?= $Grid->certificate_start->EditValue ?>"<?= $Grid->certificate_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->certificate_start->getErrorMessage() ?></div>
<?php if (!$Grid->certificate_start->ReadOnly && !$Grid->certificate_start->Disabled && !isset($Grid->certificate_start->EditAttrs["readonly"]) && !isset($Grid->certificate_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_certificate_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_certificate_start">
<span<?= $Grid->certificate_start->viewAttributes() ?>>
<?= $Grid->certificate_start->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mytraining" data-field="x_certificate_start" data-hidden="1" name="fmytraininggrid$x<?= $Grid->RowIndex ?>_certificate_start" id="fmytraininggrid$x<?= $Grid->RowIndex ?>_certificate_start" value="<?= HtmlEncode($Grid->certificate_start->FormValue) ?>">
<input type="hidden" data-table="mytraining" data-field="x_certificate_start" data-hidden="1" name="fmytraininggrid$o<?= $Grid->RowIndex ?>_certificate_start" id="fmytraininggrid$o<?= $Grid->RowIndex ?>_certificate_start" value="<?= HtmlEncode($Grid->certificate_start->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->certificate_end->Visible) { // certificate_end ?>
        <td data-name="certificate_end" <?= $Grid->certificate_end->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_certificate_end" class="form-group">
<input type="<?= $Grid->certificate_end->getInputTextType() ?>" data-table="mytraining" data-field="x_certificate_end" name="x<?= $Grid->RowIndex ?>_certificate_end" id="x<?= $Grid->RowIndex ?>_certificate_end" placeholder="<?= HtmlEncode($Grid->certificate_end->getPlaceHolder()) ?>" value="<?= $Grid->certificate_end->EditValue ?>"<?= $Grid->certificate_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->certificate_end->getErrorMessage() ?></div>
<?php if (!$Grid->certificate_end->ReadOnly && !$Grid->certificate_end->Disabled && !isset($Grid->certificate_end->EditAttrs["readonly"]) && !isset($Grid->certificate_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_certificate_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="mytraining" data-field="x_certificate_end" data-hidden="1" name="o<?= $Grid->RowIndex ?>_certificate_end" id="o<?= $Grid->RowIndex ?>_certificate_end" value="<?= HtmlEncode($Grid->certificate_end->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_certificate_end" class="form-group">
<input type="<?= $Grid->certificate_end->getInputTextType() ?>" data-table="mytraining" data-field="x_certificate_end" name="x<?= $Grid->RowIndex ?>_certificate_end" id="x<?= $Grid->RowIndex ?>_certificate_end" placeholder="<?= HtmlEncode($Grid->certificate_end->getPlaceHolder()) ?>" value="<?= $Grid->certificate_end->EditValue ?>"<?= $Grid->certificate_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->certificate_end->getErrorMessage() ?></div>
<?php if (!$Grid->certificate_end->ReadOnly && !$Grid->certificate_end->Disabled && !isset($Grid->certificate_end->EditAttrs["readonly"]) && !isset($Grid->certificate_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_certificate_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_certificate_end">
<span<?= $Grid->certificate_end->viewAttributes() ?>>
<?= $Grid->certificate_end->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mytraining" data-field="x_certificate_end" data-hidden="1" name="fmytraininggrid$x<?= $Grid->RowIndex ?>_certificate_end" id="fmytraininggrid$x<?= $Grid->RowIndex ?>_certificate_end" value="<?= HtmlEncode($Grid->certificate_end->FormValue) ?>">
<input type="hidden" data-table="mytraining" data-field="x_certificate_end" data-hidden="1" name="fmytraininggrid$o<?= $Grid->RowIndex ?>_certificate_end" id="fmytraininggrid$o<?= $Grid->RowIndex ?>_certificate_end" value="<?= HtmlEncode($Grid->certificate_end->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->training_document->Visible) { // training_document ?>
        <td data-name="training_document" <?= $Grid->training_document->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_mytraining_training_document" class="form-group mytraining_training_document">
<div id="fd_x<?= $Grid->RowIndex ?>_training_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->training_document->title() ?>" data-table="mytraining" data-field="x_training_document" name="x<?= $Grid->RowIndex ?>_training_document" id="x<?= $Grid->RowIndex ?>_training_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->training_document->editAttributes() ?><?= ($Grid->training_document->ReadOnly || $Grid->training_document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_training_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->training_document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_training_document" id= "fn_x<?= $Grid->RowIndex ?>_training_document" value="<?= $Grid->training_document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_training_document" id= "fa_x<?= $Grid->RowIndex ?>_training_document" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_training_document" id= "fs_x<?= $Grid->RowIndex ?>_training_document" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_training_document" id= "fx_x<?= $Grid->RowIndex ?>_training_document" value="<?= $Grid->training_document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_training_document" id= "fm_x<?= $Grid->RowIndex ?>_training_document" value="<?= $Grid->training_document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_training_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_document" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_document" id="o<?= $Grid->RowIndex ?>_training_document" value="<?= HtmlEncode($Grid->training_document->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_document">
<span<?= $Grid->training_document->viewAttributes() ?>>
<?= GetFileViewTag($Grid->training_document, $Grid->training_document->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mytraining_training_document" class="form-group mytraining_training_document">
<div id="fd_x<?= $Grid->RowIndex ?>_training_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->training_document->title() ?>" data-table="mytraining" data-field="x_training_document" name="x<?= $Grid->RowIndex ?>_training_document" id="x<?= $Grid->RowIndex ?>_training_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->training_document->editAttributes() ?><?= ($Grid->training_document->ReadOnly || $Grid->training_document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_training_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->training_document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_training_document" id= "fn_x<?= $Grid->RowIndex ?>_training_document" value="<?= $Grid->training_document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_training_document" id= "fa_x<?= $Grid->RowIndex ?>_training_document" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_training_document") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_training_document" id= "fs_x<?= $Grid->RowIndex ?>_training_document" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_training_document" id= "fx_x<?= $Grid->RowIndex ?>_training_document" value="<?= $Grid->training_document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_training_document" id= "fm_x<?= $Grid->RowIndex ?>_training_document" value="<?= $Grid->training_document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_training_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
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
loadjs.ready(["fmytraininggrid","load"], function () {
    fmytraininggrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_mytraining", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->training_name->Visible) { // training_name ?>
        <td data-name="training_name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mytraining_training_name" class="form-group mytraining_training_name">
<input type="<?= $Grid->training_name->getInputTextType() ?>" data-table="mytraining" data-field="x_training_name" name="x<?= $Grid->RowIndex ?>_training_name" id="x<?= $Grid->RowIndex ?>_training_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->training_name->getPlaceHolder()) ?>" value="<?= $Grid->training_name->EditValue ?>"<?= $Grid->training_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_mytraining_training_name" class="form-group mytraining_training_name">
<span<?= $Grid->training_name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->training_name->getDisplayValue($Grid->training_name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_training_name" id="x<?= $Grid->RowIndex ?>_training_name" value="<?= HtmlEncode($Grid->training_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mytraining" data-field="x_training_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_name" id="o<?= $Grid->RowIndex ?>_training_name" value="<?= HtmlEncode($Grid->training_name->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->training_start->Visible) { // training_start ?>
        <td data-name="training_start">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mytraining_training_start" class="form-group mytraining_training_start">
<input type="<?= $Grid->training_start->getInputTextType() ?>" data-table="mytraining" data-field="x_training_start" name="x<?= $Grid->RowIndex ?>_training_start" id="x<?= $Grid->RowIndex ?>_training_start" placeholder="<?= HtmlEncode($Grid->training_start->getPlaceHolder()) ?>" value="<?= $Grid->training_start->EditValue ?>"<?= $Grid->training_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_start->getErrorMessage() ?></div>
<?php if (!$Grid->training_start->ReadOnly && !$Grid->training_start->Disabled && !isset($Grid->training_start->EditAttrs["readonly"]) && !isset($Grid->training_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_training_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_mytraining_training_start" class="form-group mytraining_training_start">
<span<?= $Grid->training_start->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->training_start->getDisplayValue($Grid->training_start->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_start" data-hidden="1" name="x<?= $Grid->RowIndex ?>_training_start" id="x<?= $Grid->RowIndex ?>_training_start" value="<?= HtmlEncode($Grid->training_start->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mytraining" data-field="x_training_start" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_start" id="o<?= $Grid->RowIndex ?>_training_start" value="<?= HtmlEncode($Grid->training_start->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->training_end->Visible) { // training_end ?>
        <td data-name="training_end">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mytraining_training_end" class="form-group mytraining_training_end">
<input type="<?= $Grid->training_end->getInputTextType() ?>" data-table="mytraining" data-field="x_training_end" name="x<?= $Grid->RowIndex ?>_training_end" id="x<?= $Grid->RowIndex ?>_training_end" placeholder="<?= HtmlEncode($Grid->training_end->getPlaceHolder()) ?>" value="<?= $Grid->training_end->EditValue ?>"<?= $Grid->training_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_end->getErrorMessage() ?></div>
<?php if (!$Grid->training_end->ReadOnly && !$Grid->training_end->Disabled && !isset($Grid->training_end->EditAttrs["readonly"]) && !isset($Grid->training_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_training_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_mytraining_training_end" class="form-group mytraining_training_end">
<span<?= $Grid->training_end->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->training_end->getDisplayValue($Grid->training_end->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_end" data-hidden="1" name="x<?= $Grid->RowIndex ?>_training_end" id="x<?= $Grid->RowIndex ?>_training_end" value="<?= HtmlEncode($Grid->training_end->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mytraining" data-field="x_training_end" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_end" id="o<?= $Grid->RowIndex ?>_training_end" value="<?= HtmlEncode($Grid->training_end->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->training_company->Visible) { // training_company ?>
        <td data-name="training_company">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mytraining_training_company" class="form-group mytraining_training_company">
<input type="<?= $Grid->training_company->getInputTextType() ?>" data-table="mytraining" data-field="x_training_company" name="x<?= $Grid->RowIndex ?>_training_company" id="x<?= $Grid->RowIndex ?>_training_company" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->training_company->getPlaceHolder()) ?>" value="<?= $Grid->training_company->EditValue ?>"<?= $Grid->training_company->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->training_company->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_mytraining_training_company" class="form-group mytraining_training_company">
<span<?= $Grid->training_company->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->training_company->getDisplayValue($Grid->training_company->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_company" data-hidden="1" name="x<?= $Grid->RowIndex ?>_training_company" id="x<?= $Grid->RowIndex ?>_training_company" value="<?= HtmlEncode($Grid->training_company->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mytraining" data-field="x_training_company" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_company" id="o<?= $Grid->RowIndex ?>_training_company" value="<?= HtmlEncode($Grid->training_company->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->certificate_start->Visible) { // certificate_start ?>
        <td data-name="certificate_start">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mytraining_certificate_start" class="form-group mytraining_certificate_start">
<input type="<?= $Grid->certificate_start->getInputTextType() ?>" data-table="mytraining" data-field="x_certificate_start" name="x<?= $Grid->RowIndex ?>_certificate_start" id="x<?= $Grid->RowIndex ?>_certificate_start" placeholder="<?= HtmlEncode($Grid->certificate_start->getPlaceHolder()) ?>" value="<?= $Grid->certificate_start->EditValue ?>"<?= $Grid->certificate_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->certificate_start->getErrorMessage() ?></div>
<?php if (!$Grid->certificate_start->ReadOnly && !$Grid->certificate_start->Disabled && !isset($Grid->certificate_start->EditAttrs["readonly"]) && !isset($Grid->certificate_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_certificate_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_mytraining_certificate_start" class="form-group mytraining_certificate_start">
<span<?= $Grid->certificate_start->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->certificate_start->getDisplayValue($Grid->certificate_start->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mytraining" data-field="x_certificate_start" data-hidden="1" name="x<?= $Grid->RowIndex ?>_certificate_start" id="x<?= $Grid->RowIndex ?>_certificate_start" value="<?= HtmlEncode($Grid->certificate_start->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mytraining" data-field="x_certificate_start" data-hidden="1" name="o<?= $Grid->RowIndex ?>_certificate_start" id="o<?= $Grid->RowIndex ?>_certificate_start" value="<?= HtmlEncode($Grid->certificate_start->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->certificate_end->Visible) { // certificate_end ?>
        <td data-name="certificate_end">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mytraining_certificate_end" class="form-group mytraining_certificate_end">
<input type="<?= $Grid->certificate_end->getInputTextType() ?>" data-table="mytraining" data-field="x_certificate_end" name="x<?= $Grid->RowIndex ?>_certificate_end" id="x<?= $Grid->RowIndex ?>_certificate_end" placeholder="<?= HtmlEncode($Grid->certificate_end->getPlaceHolder()) ?>" value="<?= $Grid->certificate_end->EditValue ?>"<?= $Grid->certificate_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->certificate_end->getErrorMessage() ?></div>
<?php if (!$Grid->certificate_end->ReadOnly && !$Grid->certificate_end->Disabled && !isset($Grid->certificate_end->EditAttrs["readonly"]) && !isset($Grid->certificate_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytraininggrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytraininggrid", "x<?= $Grid->RowIndex ?>_certificate_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_mytraining_certificate_end" class="form-group mytraining_certificate_end">
<span<?= $Grid->certificate_end->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->certificate_end->getDisplayValue($Grid->certificate_end->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mytraining" data-field="x_certificate_end" data-hidden="1" name="x<?= $Grid->RowIndex ?>_certificate_end" id="x<?= $Grid->RowIndex ?>_certificate_end" value="<?= HtmlEncode($Grid->certificate_end->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mytraining" data-field="x_certificate_end" data-hidden="1" name="o<?= $Grid->RowIndex ?>_certificate_end" id="o<?= $Grid->RowIndex ?>_certificate_end" value="<?= HtmlEncode($Grid->certificate_end->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->training_document->Visible) { // training_document ?>
        <td data-name="training_document">
<span id="el$rowindex$_mytraining_training_document" class="form-group mytraining_training_document">
<div id="fd_x<?= $Grid->RowIndex ?>_training_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->training_document->title() ?>" data-table="mytraining" data-field="x_training_document" name="x<?= $Grid->RowIndex ?>_training_document" id="x<?= $Grid->RowIndex ?>_training_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->training_document->editAttributes() ?><?= ($Grid->training_document->ReadOnly || $Grid->training_document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_training_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->training_document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_training_document" id= "fn_x<?= $Grid->RowIndex ?>_training_document" value="<?= $Grid->training_document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_training_document" id= "fa_x<?= $Grid->RowIndex ?>_training_document" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_training_document" id= "fs_x<?= $Grid->RowIndex ?>_training_document" value="255">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_training_document" id= "fx_x<?= $Grid->RowIndex ?>_training_document" value="<?= $Grid->training_document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_training_document" id= "fm_x<?= $Grid->RowIndex ?>_training_document" value="<?= $Grid->training_document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_training_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="mytraining" data-field="x_training_document" data-hidden="1" name="o<?= $Grid->RowIndex ?>_training_document" id="o<?= $Grid->RowIndex ?>_training_document" value="<?= HtmlEncode($Grid->training_document->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fmytraininggrid","load"], function() {
    fmytraininggrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fmytraininggrid">
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
    ew.addEventHandlers("mytraining");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
