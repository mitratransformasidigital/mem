<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("MycontractGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.mycontract) ew.vars.tables.mycontract = <?= JsonEncode(GetClientVar("tables", "mycontract")) ?>;
var currentForm, currentPageID;
var fmycontractgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fmycontractgrid = new ew.Form("fmycontractgrid", "grid");
    fmycontractgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.mycontract.fields;
    fmycontractgrid.addFields([
        ["salary", [fields.salary.required ? ew.Validators.required(fields.salary.caption) : null, ew.Validators.float], fields.salary.isInvalid],
        ["bonus", [fields.bonus.required ? ew.Validators.required(fields.bonus.caption) : null, ew.Validators.float], fields.bonus.isInvalid],
        ["thr", [fields.thr.required ? ew.Validators.required(fields.thr.caption) : null], fields.thr.isInvalid],
        ["contract_start", [fields.contract_start.required ? ew.Validators.required(fields.contract_start.caption) : null, ew.Validators.datetime(0)], fields.contract_start.isInvalid],
        ["contract_end", [fields.contract_end.required ? ew.Validators.required(fields.contract_end.caption) : null, ew.Validators.datetime(0)], fields.contract_end.isInvalid],
        ["office_id", [fields.office_id.required ? ew.Validators.required(fields.office_id.caption) : null], fields.office_id.isInvalid],
        ["contract_document", [fields.contract_document.required ? ew.Validators.fileRequired(fields.contract_document.caption) : null], fields.contract_document.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmycontractgrid,
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
    fmycontractgrid.validate = function () {
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
    fmycontractgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "salary", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "bonus", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "thr[]", true))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "contract_start", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "contract_end", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "office_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "contract_document", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fmycontractgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmycontractgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmycontractgrid.lists.office_id = <?= $Grid->office_id->toClientList($Grid) ?>;
    loadjs.done("fmycontractgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> mycontract">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fmycontractgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_mycontract" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_mycontractgrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->salary->Visible) { // salary ?>
        <th data-name="salary" class="<?= $Grid->salary->headerCellClass() ?>"><div id="elh_mycontract_salary" class="mycontract_salary"><?= $Grid->renderSort($Grid->salary) ?></div></th>
<?php } ?>
<?php if ($Grid->bonus->Visible) { // bonus ?>
        <th data-name="bonus" class="<?= $Grid->bonus->headerCellClass() ?>"><div id="elh_mycontract_bonus" class="mycontract_bonus"><?= $Grid->renderSort($Grid->bonus) ?></div></th>
<?php } ?>
<?php if ($Grid->thr->Visible) { // thr ?>
        <th data-name="thr" class="<?= $Grid->thr->headerCellClass() ?>"><div id="elh_mycontract_thr" class="mycontract_thr"><?= $Grid->renderSort($Grid->thr) ?></div></th>
<?php } ?>
<?php if ($Grid->contract_start->Visible) { // contract_start ?>
        <th data-name="contract_start" class="<?= $Grid->contract_start->headerCellClass() ?>"><div id="elh_mycontract_contract_start" class="mycontract_contract_start"><?= $Grid->renderSort($Grid->contract_start) ?></div></th>
<?php } ?>
<?php if ($Grid->contract_end->Visible) { // contract_end ?>
        <th data-name="contract_end" class="<?= $Grid->contract_end->headerCellClass() ?>"><div id="elh_mycontract_contract_end" class="mycontract_contract_end"><?= $Grid->renderSort($Grid->contract_end) ?></div></th>
<?php } ?>
<?php if ($Grid->office_id->Visible) { // office_id ?>
        <th data-name="office_id" class="<?= $Grid->office_id->headerCellClass() ?>"><div id="elh_mycontract_office_id" class="mycontract_office_id"><?= $Grid->renderSort($Grid->office_id) ?></div></th>
<?php } ?>
<?php if ($Grid->contract_document->Visible) { // contract_document ?>
        <th data-name="contract_document" class="<?= $Grid->contract_document->headerCellClass() ?>"><div id="elh_mycontract_contract_document" class="mycontract_contract_document"><?= $Grid->renderSort($Grid->contract_document) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_mycontract", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->salary->Visible) { // salary ?>
        <td data-name="salary" <?= $Grid->salary->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_salary" class="form-group">
<input type="<?= $Grid->salary->getInputTextType() ?>" data-table="mycontract" data-field="x_salary" name="x<?= $Grid->RowIndex ?>_salary" id="x<?= $Grid->RowIndex ?>_salary" size="30" placeholder="<?= HtmlEncode($Grid->salary->getPlaceHolder()) ?>" value="<?= $Grid->salary->EditValue ?>"<?= $Grid->salary->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->salary->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="mycontract" data-field="x_salary" data-hidden="1" name="o<?= $Grid->RowIndex ?>_salary" id="o<?= $Grid->RowIndex ?>_salary" value="<?= HtmlEncode($Grid->salary->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_salary" class="form-group">
<input type="<?= $Grid->salary->getInputTextType() ?>" data-table="mycontract" data-field="x_salary" name="x<?= $Grid->RowIndex ?>_salary" id="x<?= $Grid->RowIndex ?>_salary" size="30" placeholder="<?= HtmlEncode($Grid->salary->getPlaceHolder()) ?>" value="<?= $Grid->salary->EditValue ?>"<?= $Grid->salary->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->salary->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_salary">
<span<?= $Grid->salary->viewAttributes() ?>>
<?= $Grid->salary->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mycontract" data-field="x_salary" data-hidden="1" name="fmycontractgrid$x<?= $Grid->RowIndex ?>_salary" id="fmycontractgrid$x<?= $Grid->RowIndex ?>_salary" value="<?= HtmlEncode($Grid->salary->FormValue) ?>">
<input type="hidden" data-table="mycontract" data-field="x_salary" data-hidden="1" name="fmycontractgrid$o<?= $Grid->RowIndex ?>_salary" id="fmycontractgrid$o<?= $Grid->RowIndex ?>_salary" value="<?= HtmlEncode($Grid->salary->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->bonus->Visible) { // bonus ?>
        <td data-name="bonus" <?= $Grid->bonus->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_bonus" class="form-group">
<input type="<?= $Grid->bonus->getInputTextType() ?>" data-table="mycontract" data-field="x_bonus" name="x<?= $Grid->RowIndex ?>_bonus" id="x<?= $Grid->RowIndex ?>_bonus" size="30" placeholder="<?= HtmlEncode($Grid->bonus->getPlaceHolder()) ?>" value="<?= $Grid->bonus->EditValue ?>"<?= $Grid->bonus->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->bonus->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="mycontract" data-field="x_bonus" data-hidden="1" name="o<?= $Grid->RowIndex ?>_bonus" id="o<?= $Grid->RowIndex ?>_bonus" value="<?= HtmlEncode($Grid->bonus->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_bonus" class="form-group">
<input type="<?= $Grid->bonus->getInputTextType() ?>" data-table="mycontract" data-field="x_bonus" name="x<?= $Grid->RowIndex ?>_bonus" id="x<?= $Grid->RowIndex ?>_bonus" size="30" placeholder="<?= HtmlEncode($Grid->bonus->getPlaceHolder()) ?>" value="<?= $Grid->bonus->EditValue ?>"<?= $Grid->bonus->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->bonus->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_bonus">
<span<?= $Grid->bonus->viewAttributes() ?>>
<?= $Grid->bonus->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mycontract" data-field="x_bonus" data-hidden="1" name="fmycontractgrid$x<?= $Grid->RowIndex ?>_bonus" id="fmycontractgrid$x<?= $Grid->RowIndex ?>_bonus" value="<?= HtmlEncode($Grid->bonus->FormValue) ?>">
<input type="hidden" data-table="mycontract" data-field="x_bonus" data-hidden="1" name="fmycontractgrid$o<?= $Grid->RowIndex ?>_bonus" id="fmycontractgrid$o<?= $Grid->RowIndex ?>_bonus" value="<?= HtmlEncode($Grid->bonus->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->thr->Visible) { // thr ?>
        <td data-name="thr" <?= $Grid->thr->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_thr" class="form-group">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Grid->thr->isInvalidClass() ?>" data-table="mycontract" data-field="x_thr" name="x<?= $Grid->RowIndex ?>_thr[]" id="x<?= $Grid->RowIndex ?>_thr_454166" value="1"<?= ConvertToBool($Grid->thr->CurrentValue) ? " checked" : "" ?><?= $Grid->thr->editAttributes() ?>>
    <label class="custom-control-label" for="x<?= $Grid->RowIndex ?>_thr_454166"></label>
</div>
<div class="invalid-feedback"><?= $Grid->thr->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="mycontract" data-field="x_thr" data-hidden="1" name="o<?= $Grid->RowIndex ?>_thr[]" id="o<?= $Grid->RowIndex ?>_thr[]" value="<?= HtmlEncode($Grid->thr->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_thr" class="form-group">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Grid->thr->isInvalidClass() ?>" data-table="mycontract" data-field="x_thr" name="x<?= $Grid->RowIndex ?>_thr[]" id="x<?= $Grid->RowIndex ?>_thr_444449" value="1"<?= ConvertToBool($Grid->thr->CurrentValue) ? " checked" : "" ?><?= $Grid->thr->editAttributes() ?>>
    <label class="custom-control-label" for="x<?= $Grid->RowIndex ?>_thr_444449"></label>
</div>
<div class="invalid-feedback"><?= $Grid->thr->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_thr">
<span<?= $Grid->thr->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x_thr_<?= $Grid->RowCount ?>" class="custom-control-input" value="<?= $Grid->thr->getViewValue() ?>" disabled<?php if (ConvertToBool($Grid->thr->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x_thr_<?= $Grid->RowCount ?>"></label>
</div></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mycontract" data-field="x_thr" data-hidden="1" name="fmycontractgrid$x<?= $Grid->RowIndex ?>_thr" id="fmycontractgrid$x<?= $Grid->RowIndex ?>_thr" value="<?= HtmlEncode($Grid->thr->FormValue) ?>">
<input type="hidden" data-table="mycontract" data-field="x_thr" data-hidden="1" name="fmycontractgrid$o<?= $Grid->RowIndex ?>_thr[]" id="fmycontractgrid$o<?= $Grid->RowIndex ?>_thr[]" value="<?= HtmlEncode($Grid->thr->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->contract_start->Visible) { // contract_start ?>
        <td data-name="contract_start" <?= $Grid->contract_start->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_contract_start" class="form-group">
<input type="<?= $Grid->contract_start->getInputTextType() ?>" data-table="mycontract" data-field="x_contract_start" name="x<?= $Grid->RowIndex ?>_contract_start" id="x<?= $Grid->RowIndex ?>_contract_start" placeholder="<?= HtmlEncode($Grid->contract_start->getPlaceHolder()) ?>" value="<?= $Grid->contract_start->EditValue ?>"<?= $Grid->contract_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->contract_start->getErrorMessage() ?></div>
<?php if (!$Grid->contract_start->ReadOnly && !$Grid->contract_start->Disabled && !isset($Grid->contract_start->EditAttrs["readonly"]) && !isset($Grid->contract_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmycontractgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmycontractgrid", "x<?= $Grid->RowIndex ?>_contract_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="mycontract" data-field="x_contract_start" data-hidden="1" name="o<?= $Grid->RowIndex ?>_contract_start" id="o<?= $Grid->RowIndex ?>_contract_start" value="<?= HtmlEncode($Grid->contract_start->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_contract_start" class="form-group">
<input type="<?= $Grid->contract_start->getInputTextType() ?>" data-table="mycontract" data-field="x_contract_start" name="x<?= $Grid->RowIndex ?>_contract_start" id="x<?= $Grid->RowIndex ?>_contract_start" placeholder="<?= HtmlEncode($Grid->contract_start->getPlaceHolder()) ?>" value="<?= $Grid->contract_start->EditValue ?>"<?= $Grid->contract_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->contract_start->getErrorMessage() ?></div>
<?php if (!$Grid->contract_start->ReadOnly && !$Grid->contract_start->Disabled && !isset($Grid->contract_start->EditAttrs["readonly"]) && !isset($Grid->contract_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmycontractgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmycontractgrid", "x<?= $Grid->RowIndex ?>_contract_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_contract_start">
<span<?= $Grid->contract_start->viewAttributes() ?>>
<?= $Grid->contract_start->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mycontract" data-field="x_contract_start" data-hidden="1" name="fmycontractgrid$x<?= $Grid->RowIndex ?>_contract_start" id="fmycontractgrid$x<?= $Grid->RowIndex ?>_contract_start" value="<?= HtmlEncode($Grid->contract_start->FormValue) ?>">
<input type="hidden" data-table="mycontract" data-field="x_contract_start" data-hidden="1" name="fmycontractgrid$o<?= $Grid->RowIndex ?>_contract_start" id="fmycontractgrid$o<?= $Grid->RowIndex ?>_contract_start" value="<?= HtmlEncode($Grid->contract_start->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->contract_end->Visible) { // contract_end ?>
        <td data-name="contract_end" <?= $Grid->contract_end->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_contract_end" class="form-group">
<input type="<?= $Grid->contract_end->getInputTextType() ?>" data-table="mycontract" data-field="x_contract_end" name="x<?= $Grid->RowIndex ?>_contract_end" id="x<?= $Grid->RowIndex ?>_contract_end" placeholder="<?= HtmlEncode($Grid->contract_end->getPlaceHolder()) ?>" value="<?= $Grid->contract_end->EditValue ?>"<?= $Grid->contract_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->contract_end->getErrorMessage() ?></div>
<?php if (!$Grid->contract_end->ReadOnly && !$Grid->contract_end->Disabled && !isset($Grid->contract_end->EditAttrs["readonly"]) && !isset($Grid->contract_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmycontractgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmycontractgrid", "x<?= $Grid->RowIndex ?>_contract_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="mycontract" data-field="x_contract_end" data-hidden="1" name="o<?= $Grid->RowIndex ?>_contract_end" id="o<?= $Grid->RowIndex ?>_contract_end" value="<?= HtmlEncode($Grid->contract_end->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_contract_end" class="form-group">
<input type="<?= $Grid->contract_end->getInputTextType() ?>" data-table="mycontract" data-field="x_contract_end" name="x<?= $Grid->RowIndex ?>_contract_end" id="x<?= $Grid->RowIndex ?>_contract_end" placeholder="<?= HtmlEncode($Grid->contract_end->getPlaceHolder()) ?>" value="<?= $Grid->contract_end->EditValue ?>"<?= $Grid->contract_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->contract_end->getErrorMessage() ?></div>
<?php if (!$Grid->contract_end->ReadOnly && !$Grid->contract_end->Disabled && !isset($Grid->contract_end->EditAttrs["readonly"]) && !isset($Grid->contract_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmycontractgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmycontractgrid", "x<?= $Grid->RowIndex ?>_contract_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_contract_end">
<span<?= $Grid->contract_end->viewAttributes() ?>>
<?= $Grid->contract_end->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mycontract" data-field="x_contract_end" data-hidden="1" name="fmycontractgrid$x<?= $Grid->RowIndex ?>_contract_end" id="fmycontractgrid$x<?= $Grid->RowIndex ?>_contract_end" value="<?= HtmlEncode($Grid->contract_end->FormValue) ?>">
<input type="hidden" data-table="mycontract" data-field="x_contract_end" data-hidden="1" name="fmycontractgrid$o<?= $Grid->RowIndex ?>_contract_end" id="fmycontractgrid$o<?= $Grid->RowIndex ?>_contract_end" value="<?= HtmlEncode($Grid->contract_end->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->office_id->Visible) { // office_id ?>
        <td data-name="office_id" <?= $Grid->office_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_office_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_office_id"
        name="x<?= $Grid->RowIndex ?>_office_id"
        class="form-control ew-select<?= $Grid->office_id->isInvalidClass() ?>"
        data-select2-id="mycontract_x<?= $Grid->RowIndex ?>_office_id"
        data-table="mycontract"
        data-field="x_office_id"
        data-value-separator="<?= $Grid->office_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->office_id->getPlaceHolder()) ?>"
        <?= $Grid->office_id->editAttributes() ?>>
        <?= $Grid->office_id->selectOptionListHtml("x{$Grid->RowIndex}_office_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_office") && !$Grid->office_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_office_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->office_id->caption() ?>" data-title="<?= $Grid->office_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_office_id',url:'<?= GetUrl("masterofficeaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->office_id->getErrorMessage() ?></div>
<?= $Grid->office_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_office_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='mycontract_x<?= $Grid->RowIndex ?>_office_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_office_id", selectId: "mycontract_x<?= $Grid->RowIndex ?>_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mycontract.fields.office_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="mycontract" data-field="x_office_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_office_id" id="o<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_office_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_office_id"
        name="x<?= $Grid->RowIndex ?>_office_id"
        class="form-control ew-select<?= $Grid->office_id->isInvalidClass() ?>"
        data-select2-id="mycontract_x<?= $Grid->RowIndex ?>_office_id"
        data-table="mycontract"
        data-field="x_office_id"
        data-value-separator="<?= $Grid->office_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->office_id->getPlaceHolder()) ?>"
        <?= $Grid->office_id->editAttributes() ?>>
        <?= $Grid->office_id->selectOptionListHtml("x{$Grid->RowIndex}_office_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_office") && !$Grid->office_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_office_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->office_id->caption() ?>" data-title="<?= $Grid->office_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_office_id',url:'<?= GetUrl("masterofficeaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->office_id->getErrorMessage() ?></div>
<?= $Grid->office_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_office_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='mycontract_x<?= $Grid->RowIndex ?>_office_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_office_id", selectId: "mycontract_x<?= $Grid->RowIndex ?>_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mycontract.fields.office_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_office_id">
<span<?= $Grid->office_id->viewAttributes() ?>>
<?= $Grid->office_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="mycontract" data-field="x_office_id" data-hidden="1" name="fmycontractgrid$x<?= $Grid->RowIndex ?>_office_id" id="fmycontractgrid$x<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->FormValue) ?>">
<input type="hidden" data-table="mycontract" data-field="x_office_id" data-hidden="1" name="fmycontractgrid$o<?= $Grid->RowIndex ?>_office_id" id="fmycontractgrid$o<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->contract_document->Visible) { // contract_document ?>
        <td data-name="contract_document" <?= $Grid->contract_document->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_mycontract_contract_document" class="form-group mycontract_contract_document">
<div id="fd_x<?= $Grid->RowIndex ?>_contract_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->contract_document->title() ?>" data-table="mycontract" data-field="x_contract_document" name="x<?= $Grid->RowIndex ?>_contract_document" id="x<?= $Grid->RowIndex ?>_contract_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->contract_document->editAttributes() ?><?= ($Grid->contract_document->ReadOnly || $Grid->contract_document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_contract_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->contract_document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_contract_document" id= "fn_x<?= $Grid->RowIndex ?>_contract_document" value="<?= $Grid->contract_document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_contract_document" id= "fa_x<?= $Grid->RowIndex ?>_contract_document" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_contract_document" id= "fs_x<?= $Grid->RowIndex ?>_contract_document" value="150">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_contract_document" id= "fx_x<?= $Grid->RowIndex ?>_contract_document" value="<?= $Grid->contract_document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_contract_document" id= "fm_x<?= $Grid->RowIndex ?>_contract_document" value="<?= $Grid->contract_document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_contract_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="mycontract" data-field="x_contract_document" data-hidden="1" name="o<?= $Grid->RowIndex ?>_contract_document" id="o<?= $Grid->RowIndex ?>_contract_document" value="<?= HtmlEncode($Grid->contract_document->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_contract_document">
<span<?= $Grid->contract_document->viewAttributes() ?>>
<?= GetFileViewTag($Grid->contract_document, $Grid->contract_document->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_mycontract_contract_document" class="form-group mycontract_contract_document">
<div id="fd_x<?= $Grid->RowIndex ?>_contract_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->contract_document->title() ?>" data-table="mycontract" data-field="x_contract_document" name="x<?= $Grid->RowIndex ?>_contract_document" id="x<?= $Grid->RowIndex ?>_contract_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->contract_document->editAttributes() ?><?= ($Grid->contract_document->ReadOnly || $Grid->contract_document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_contract_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->contract_document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_contract_document" id= "fn_x<?= $Grid->RowIndex ?>_contract_document" value="<?= $Grid->contract_document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_contract_document" id= "fa_x<?= $Grid->RowIndex ?>_contract_document" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_contract_document") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_contract_document" id= "fs_x<?= $Grid->RowIndex ?>_contract_document" value="150">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_contract_document" id= "fx_x<?= $Grid->RowIndex ?>_contract_document" value="<?= $Grid->contract_document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_contract_document" id= "fm_x<?= $Grid->RowIndex ?>_contract_document" value="<?= $Grid->contract_document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_contract_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
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
loadjs.ready(["fmycontractgrid","load"], function () {
    fmycontractgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_mycontract", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->salary->Visible) { // salary ?>
        <td data-name="salary">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mycontract_salary" class="form-group mycontract_salary">
<input type="<?= $Grid->salary->getInputTextType() ?>" data-table="mycontract" data-field="x_salary" name="x<?= $Grid->RowIndex ?>_salary" id="x<?= $Grid->RowIndex ?>_salary" size="30" placeholder="<?= HtmlEncode($Grid->salary->getPlaceHolder()) ?>" value="<?= $Grid->salary->EditValue ?>"<?= $Grid->salary->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->salary->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_mycontract_salary" class="form-group mycontract_salary">
<span<?= $Grid->salary->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->salary->getDisplayValue($Grid->salary->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mycontract" data-field="x_salary" data-hidden="1" name="x<?= $Grid->RowIndex ?>_salary" id="x<?= $Grid->RowIndex ?>_salary" value="<?= HtmlEncode($Grid->salary->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mycontract" data-field="x_salary" data-hidden="1" name="o<?= $Grid->RowIndex ?>_salary" id="o<?= $Grid->RowIndex ?>_salary" value="<?= HtmlEncode($Grid->salary->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->bonus->Visible) { // bonus ?>
        <td data-name="bonus">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mycontract_bonus" class="form-group mycontract_bonus">
<input type="<?= $Grid->bonus->getInputTextType() ?>" data-table="mycontract" data-field="x_bonus" name="x<?= $Grid->RowIndex ?>_bonus" id="x<?= $Grid->RowIndex ?>_bonus" size="30" placeholder="<?= HtmlEncode($Grid->bonus->getPlaceHolder()) ?>" value="<?= $Grid->bonus->EditValue ?>"<?= $Grid->bonus->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->bonus->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_mycontract_bonus" class="form-group mycontract_bonus">
<span<?= $Grid->bonus->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->bonus->getDisplayValue($Grid->bonus->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mycontract" data-field="x_bonus" data-hidden="1" name="x<?= $Grid->RowIndex ?>_bonus" id="x<?= $Grid->RowIndex ?>_bonus" value="<?= HtmlEncode($Grid->bonus->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mycontract" data-field="x_bonus" data-hidden="1" name="o<?= $Grid->RowIndex ?>_bonus" id="o<?= $Grid->RowIndex ?>_bonus" value="<?= HtmlEncode($Grid->bonus->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->thr->Visible) { // thr ?>
        <td data-name="thr">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mycontract_thr" class="form-group mycontract_thr">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Grid->thr->isInvalidClass() ?>" data-table="mycontract" data-field="x_thr" name="x<?= $Grid->RowIndex ?>_thr[]" id="x<?= $Grid->RowIndex ?>_thr_148351" value="1"<?= ConvertToBool($Grid->thr->CurrentValue) ? " checked" : "" ?><?= $Grid->thr->editAttributes() ?>>
    <label class="custom-control-label" for="x<?= $Grid->RowIndex ?>_thr_148351"></label>
</div>
<div class="invalid-feedback"><?= $Grid->thr->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_mycontract_thr" class="form-group mycontract_thr">
<span<?= $Grid->thr->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x_thr_<?= $Grid->RowCount ?>" class="custom-control-input" value="<?= $Grid->thr->ViewValue ?>" disabled<?php if (ConvertToBool($Grid->thr->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x_thr_<?= $Grid->RowCount ?>"></label>
</div></span>
</span>
<input type="hidden" data-table="mycontract" data-field="x_thr" data-hidden="1" name="x<?= $Grid->RowIndex ?>_thr" id="x<?= $Grid->RowIndex ?>_thr" value="<?= HtmlEncode($Grid->thr->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mycontract" data-field="x_thr" data-hidden="1" name="o<?= $Grid->RowIndex ?>_thr[]" id="o<?= $Grid->RowIndex ?>_thr[]" value="<?= HtmlEncode($Grid->thr->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->contract_start->Visible) { // contract_start ?>
        <td data-name="contract_start">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mycontract_contract_start" class="form-group mycontract_contract_start">
<input type="<?= $Grid->contract_start->getInputTextType() ?>" data-table="mycontract" data-field="x_contract_start" name="x<?= $Grid->RowIndex ?>_contract_start" id="x<?= $Grid->RowIndex ?>_contract_start" placeholder="<?= HtmlEncode($Grid->contract_start->getPlaceHolder()) ?>" value="<?= $Grid->contract_start->EditValue ?>"<?= $Grid->contract_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->contract_start->getErrorMessage() ?></div>
<?php if (!$Grid->contract_start->ReadOnly && !$Grid->contract_start->Disabled && !isset($Grid->contract_start->EditAttrs["readonly"]) && !isset($Grid->contract_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmycontractgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmycontractgrid", "x<?= $Grid->RowIndex ?>_contract_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_mycontract_contract_start" class="form-group mycontract_contract_start">
<span<?= $Grid->contract_start->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->contract_start->getDisplayValue($Grid->contract_start->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mycontract" data-field="x_contract_start" data-hidden="1" name="x<?= $Grid->RowIndex ?>_contract_start" id="x<?= $Grid->RowIndex ?>_contract_start" value="<?= HtmlEncode($Grid->contract_start->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mycontract" data-field="x_contract_start" data-hidden="1" name="o<?= $Grid->RowIndex ?>_contract_start" id="o<?= $Grid->RowIndex ?>_contract_start" value="<?= HtmlEncode($Grid->contract_start->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->contract_end->Visible) { // contract_end ?>
        <td data-name="contract_end">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mycontract_contract_end" class="form-group mycontract_contract_end">
<input type="<?= $Grid->contract_end->getInputTextType() ?>" data-table="mycontract" data-field="x_contract_end" name="x<?= $Grid->RowIndex ?>_contract_end" id="x<?= $Grid->RowIndex ?>_contract_end" placeholder="<?= HtmlEncode($Grid->contract_end->getPlaceHolder()) ?>" value="<?= $Grid->contract_end->EditValue ?>"<?= $Grid->contract_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->contract_end->getErrorMessage() ?></div>
<?php if (!$Grid->contract_end->ReadOnly && !$Grid->contract_end->Disabled && !isset($Grid->contract_end->EditAttrs["readonly"]) && !isset($Grid->contract_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmycontractgrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmycontractgrid", "x<?= $Grid->RowIndex ?>_contract_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_mycontract_contract_end" class="form-group mycontract_contract_end">
<span<?= $Grid->contract_end->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->contract_end->getDisplayValue($Grid->contract_end->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mycontract" data-field="x_contract_end" data-hidden="1" name="x<?= $Grid->RowIndex ?>_contract_end" id="x<?= $Grid->RowIndex ?>_contract_end" value="<?= HtmlEncode($Grid->contract_end->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mycontract" data-field="x_contract_end" data-hidden="1" name="o<?= $Grid->RowIndex ?>_contract_end" id="o<?= $Grid->RowIndex ?>_contract_end" value="<?= HtmlEncode($Grid->contract_end->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->office_id->Visible) { // office_id ?>
        <td data-name="office_id">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_mycontract_office_id" class="form-group mycontract_office_id">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_office_id"
        name="x<?= $Grid->RowIndex ?>_office_id"
        class="form-control ew-select<?= $Grid->office_id->isInvalidClass() ?>"
        data-select2-id="mycontract_x<?= $Grid->RowIndex ?>_office_id"
        data-table="mycontract"
        data-field="x_office_id"
        data-value-separator="<?= $Grid->office_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->office_id->getPlaceHolder()) ?>"
        <?= $Grid->office_id->editAttributes() ?>>
        <?= $Grid->office_id->selectOptionListHtml("x{$Grid->RowIndex}_office_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_office") && !$Grid->office_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_office_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->office_id->caption() ?>" data-title="<?= $Grid->office_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_office_id',url:'<?= GetUrl("masterofficeaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->office_id->getErrorMessage() ?></div>
<?= $Grid->office_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_office_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='mycontract_x<?= $Grid->RowIndex ?>_office_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_office_id", selectId: "mycontract_x<?= $Grid->RowIndex ?>_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mycontract.fields.office_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_mycontract_office_id" class="form-group mycontract_office_id">
<span<?= $Grid->office_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->office_id->getDisplayValue($Grid->office_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="mycontract" data-field="x_office_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_office_id" id="x<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="mycontract" data-field="x_office_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_office_id" id="o<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->contract_document->Visible) { // contract_document ?>
        <td data-name="contract_document">
<span id="el$rowindex$_mycontract_contract_document" class="form-group mycontract_contract_document">
<div id="fd_x<?= $Grid->RowIndex ?>_contract_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->contract_document->title() ?>" data-table="mycontract" data-field="x_contract_document" name="x<?= $Grid->RowIndex ?>_contract_document" id="x<?= $Grid->RowIndex ?>_contract_document" lang="<?= CurrentLanguageID() ?>"<?= $Grid->contract_document->editAttributes() ?><?= ($Grid->contract_document->ReadOnly || $Grid->contract_document->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_contract_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->contract_document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_contract_document" id= "fn_x<?= $Grid->RowIndex ?>_contract_document" value="<?= $Grid->contract_document->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_contract_document" id= "fa_x<?= $Grid->RowIndex ?>_contract_document" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_contract_document" id= "fs_x<?= $Grid->RowIndex ?>_contract_document" value="150">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_contract_document" id= "fx_x<?= $Grid->RowIndex ?>_contract_document" value="<?= $Grid->contract_document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_contract_document" id= "fm_x<?= $Grid->RowIndex ?>_contract_document" value="<?= $Grid->contract_document->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_contract_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="mycontract" data-field="x_contract_document" data-hidden="1" name="o<?= $Grid->RowIndex ?>_contract_document" id="o<?= $Grid->RowIndex ?>_contract_document" value="<?= HtmlEncode($Grid->contract_document->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fmycontractgrid","load"], function() {
    fmycontractgrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fmycontractgrid">
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
    ew.addEventHandlers("mycontract");
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
        container: "gmp_mycontract",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
