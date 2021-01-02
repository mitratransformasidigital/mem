<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("EmployeeQuotationDetailGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.employee_quotation_detail) ew.vars.tables.employee_quotation_detail = <?= JsonEncode(GetClientVar("tables", "employee_quotation_detail")) ?>;
var currentForm, currentPageID;
var femployee_quotation_detailgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    femployee_quotation_detailgrid = new ew.Form("femployee_quotation_detailgrid", "grid");
    femployee_quotation_detailgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.employee_quotation_detail.fields;
    femployee_quotation_detailgrid.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["rate", [fields.rate.required ? ew.Validators.required(fields.rate.caption) : null, ew.Validators.integer], fields.rate.isInvalid],
        ["qty", [fields.qty.required ? ew.Validators.required(fields.qty.caption) : null, ew.Validators.integer], fields.qty.isInvalid],
        ["Total", [fields.Total.required ? ew.Validators.required(fields.Total.caption) : null], fields.Total.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_quotation_detailgrid,
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
    femployee_quotation_detailgrid.validate = function () {
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
    femployee_quotation_detailgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "employee_username", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "rate", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "qty", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "Total", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    femployee_quotation_detailgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_quotation_detailgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_quotation_detailgrid.lists.employee_username = <?= $Grid->employee_username->toClientList($Grid) ?>;
    loadjs.done("femployee_quotation_detailgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> employee_quotation_detail">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="femployee_quotation_detailgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_employee_quotation_detail" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_employee_quotation_detailgrid" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="employee_username" class="<?= $Grid->employee_username->headerCellClass() ?>"><div id="elh_employee_quotation_detail_employee_username" class="employee_quotation_detail_employee_username"><?= $Grid->renderSort($Grid->employee_username) ?></div></th>
<?php } ?>
<?php if ($Grid->rate->Visible) { // rate ?>
        <th data-name="rate" class="<?= $Grid->rate->headerCellClass() ?>"><div id="elh_employee_quotation_detail_rate" class="employee_quotation_detail_rate"><?= $Grid->renderSort($Grid->rate) ?></div></th>
<?php } ?>
<?php if ($Grid->qty->Visible) { // qty ?>
        <th data-name="qty" class="<?= $Grid->qty->headerCellClass() ?>"><div id="elh_employee_quotation_detail_qty" class="employee_quotation_detail_qty"><?= $Grid->renderSort($Grid->qty) ?></div></th>
<?php } ?>
<?php if ($Grid->Total->Visible) { // Total ?>
        <th data-name="Total" class="<?= $Grid->Total->headerCellClass() ?>" style="min-width: 125px;"><div id="elh_employee_quotation_detail_Total" class="employee_quotation_detail_Total"><?= $Grid->renderSort($Grid->Total) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_employee_quotation_detail", "data-rowtype" => $Grid->RowType]);

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
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_quotation_detail_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_quotation_detail"
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
    var el = document.querySelector("select[data-select2-id='employee_quotation_detail_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_quotation_detail_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_quotation_detail.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_quotation_detail_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_quotation_detail"
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
    var el = document.querySelector("select[data-select2-id='employee_quotation_detail_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_quotation_detail_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_quotation_detail.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<?= $Grid->employee_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_employee_username" data-hidden="1" name="femployee_quotation_detailgrid$x<?= $Grid->RowIndex ?>_employee_username" id="femployee_quotation_detailgrid$x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<input type="hidden" data-table="employee_quotation_detail" data-field="x_employee_username" data-hidden="1" name="femployee_quotation_detailgrid$o<?= $Grid->RowIndex ?>_employee_username" id="femployee_quotation_detailgrid$o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->rate->Visible) { // rate ?>
        <td data-name="rate" <?= $Grid->rate->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_rate" class="form-group">
<input type="<?= $Grid->rate->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_rate" name="x<?= $Grid->RowIndex ?>_rate" id="x<?= $Grid->RowIndex ?>_rate" size="30" placeholder="<?= HtmlEncode($Grid->rate->getPlaceHolder()) ?>" value="<?= $Grid->rate->EditValue ?>"<?= $Grid->rate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->rate->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_rate" data-hidden="1" name="o<?= $Grid->RowIndex ?>_rate" id="o<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_rate" class="form-group">
<input type="<?= $Grid->rate->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_rate" name="x<?= $Grid->RowIndex ?>_rate" id="x<?= $Grid->RowIndex ?>_rate" size="30" placeholder="<?= HtmlEncode($Grid->rate->getPlaceHolder()) ?>" value="<?= $Grid->rate->EditValue ?>"<?= $Grid->rate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->rate->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_rate">
<span<?= $Grid->rate->viewAttributes() ?>>
<?= $Grid->rate->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_rate" data-hidden="1" name="femployee_quotation_detailgrid$x<?= $Grid->RowIndex ?>_rate" id="femployee_quotation_detailgrid$x<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->FormValue) ?>">
<input type="hidden" data-table="employee_quotation_detail" data-field="x_rate" data-hidden="1" name="femployee_quotation_detailgrid$o<?= $Grid->RowIndex ?>_rate" id="femployee_quotation_detailgrid$o<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->qty->Visible) { // qty ?>
        <td data-name="qty" <?= $Grid->qty->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_qty" class="form-group">
<input type="<?= $Grid->qty->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_qty" name="x<?= $Grid->RowIndex ?>_qty" id="x<?= $Grid->RowIndex ?>_qty" size="30" placeholder="<?= HtmlEncode($Grid->qty->getPlaceHolder()) ?>" value="<?= $Grid->qty->EditValue ?>"<?= $Grid->qty->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->qty->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_qty" data-hidden="1" name="o<?= $Grid->RowIndex ?>_qty" id="o<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_qty" class="form-group">
<input type="<?= $Grid->qty->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_qty" name="x<?= $Grid->RowIndex ?>_qty" id="x<?= $Grid->RowIndex ?>_qty" size="30" placeholder="<?= HtmlEncode($Grid->qty->getPlaceHolder()) ?>" value="<?= $Grid->qty->EditValue ?>"<?= $Grid->qty->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->qty->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_qty">
<span<?= $Grid->qty->viewAttributes() ?>>
<?= $Grid->qty->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_qty" data-hidden="1" name="femployee_quotation_detailgrid$x<?= $Grid->RowIndex ?>_qty" id="femployee_quotation_detailgrid$x<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->FormValue) ?>">
<input type="hidden" data-table="employee_quotation_detail" data-field="x_qty" data-hidden="1" name="femployee_quotation_detailgrid$o<?= $Grid->RowIndex ?>_qty" id="femployee_quotation_detailgrid$o<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->Total->Visible) { // Total ?>
        <td data-name="Total" <?= $Grid->Total->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_Total" class="form-group">
<input type="<?= $Grid->Total->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_Total" name="x<?= $Grid->RowIndex ?>_Total" id="x<?= $Grid->RowIndex ?>_Total" size="30" placeholder="<?= HtmlEncode($Grid->Total->getPlaceHolder()) ?>" value="<?= $Grid->Total->EditValue ?>"<?= $Grid->Total->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Total->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_Total" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Total" id="o<?= $Grid->RowIndex ?>_Total" value="<?= HtmlEncode($Grid->Total->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_Total" class="form-group">
<span<?= $Grid->Total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Total->getDisplayValue($Grid->Total->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_Total" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Total" id="x<?= $Grid->RowIndex ?>_Total" value="<?= HtmlEncode($Grid->Total->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_quotation_detail_Total">
<span<?= $Grid->Total->viewAttributes() ?>>
<?= $Grid->Total->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_Total" data-hidden="1" name="femployee_quotation_detailgrid$x<?= $Grid->RowIndex ?>_Total" id="femployee_quotation_detailgrid$x<?= $Grid->RowIndex ?>_Total" value="<?= HtmlEncode($Grid->Total->FormValue) ?>">
<input type="hidden" data-table="employee_quotation_detail" data-field="x_Total" data-hidden="1" name="femployee_quotation_detailgrid$o<?= $Grid->RowIndex ?>_Total" id="femployee_quotation_detailgrid$o<?= $Grid->RowIndex ?>_Total" value="<?= HtmlEncode($Grid->Total->OldValue) ?>">
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
loadjs.ready(["femployee_quotation_detailgrid","load"], function () {
    femployee_quotation_detailgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_employee_quotation_detail", "data-rowtype" => ROWTYPE_ADD]);
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
<span id="el$rowindex$_employee_quotation_detail_employee_username" class="form-group employee_quotation_detail_employee_username">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_quotation_detail_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_quotation_detail"
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
    var el = document.querySelector("select[data-select2-id='employee_quotation_detail_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_quotation_detail_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_quotation_detail.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_quotation_detail_employee_username" class="form-group employee_quotation_detail_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_employee_username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->rate->Visible) { // rate ?>
        <td data-name="rate">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_quotation_detail_rate" class="form-group employee_quotation_detail_rate">
<input type="<?= $Grid->rate->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_rate" name="x<?= $Grid->RowIndex ?>_rate" id="x<?= $Grid->RowIndex ?>_rate" size="30" placeholder="<?= HtmlEncode($Grid->rate->getPlaceHolder()) ?>" value="<?= $Grid->rate->EditValue ?>"<?= $Grid->rate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->rate->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_quotation_detail_rate" class="form-group employee_quotation_detail_rate">
<span<?= $Grid->rate->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->rate->getDisplayValue($Grid->rate->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_rate" data-hidden="1" name="x<?= $Grid->RowIndex ?>_rate" id="x<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_rate" data-hidden="1" name="o<?= $Grid->RowIndex ?>_rate" id="o<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->qty->Visible) { // qty ?>
        <td data-name="qty">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_quotation_detail_qty" class="form-group employee_quotation_detail_qty">
<input type="<?= $Grid->qty->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_qty" name="x<?= $Grid->RowIndex ?>_qty" id="x<?= $Grid->RowIndex ?>_qty" size="30" placeholder="<?= HtmlEncode($Grid->qty->getPlaceHolder()) ?>" value="<?= $Grid->qty->EditValue ?>"<?= $Grid->qty->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->qty->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_quotation_detail_qty" class="form-group employee_quotation_detail_qty">
<span<?= $Grid->qty->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->qty->getDisplayValue($Grid->qty->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_qty" data-hidden="1" name="x<?= $Grid->RowIndex ?>_qty" id="x<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_qty" data-hidden="1" name="o<?= $Grid->RowIndex ?>_qty" id="o<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->Total->Visible) { // Total ?>
        <td data-name="Total">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_quotation_detail_Total" class="form-group employee_quotation_detail_Total">
<input type="<?= $Grid->Total->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_Total" name="x<?= $Grid->RowIndex ?>_Total" id="x<?= $Grid->RowIndex ?>_Total" size="30" placeholder="<?= HtmlEncode($Grid->Total->getPlaceHolder()) ?>" value="<?= $Grid->Total->EditValue ?>"<?= $Grid->Total->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->Total->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_quotation_detail_Total" class="form-group employee_quotation_detail_Total">
<span<?= $Grid->Total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->Total->getDisplayValue($Grid->Total->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_Total" data-hidden="1" name="x<?= $Grid->RowIndex ?>_Total" id="x<?= $Grid->RowIndex ?>_Total" value="<?= HtmlEncode($Grid->Total->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_Total" data-hidden="1" name="o<?= $Grid->RowIndex ?>_Total" id="o<?= $Grid->RowIndex ?>_Total" value="<?= HtmlEncode($Grid->Total->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["femployee_quotation_detailgrid","load"], function() {
    femployee_quotation_detailgrid.updateLists(<?= $Grid->RowIndex ?>);
});
</script>
    </tr>
<?php
    }
?>
</tbody>
<?php
// Render aggregate row
$Grid->RowType = ROWTYPE_AGGREGATE;
$Grid->resetAttributes();
$Grid->renderRow();
?>
<?php if ($Grid->TotalRecords > 0 && $Grid->CurrentMode == "view") { ?>
<tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options
$Grid->renderListOptions();

// Render list options (footer, left)
$Grid->ListOptions->render("footer", "left");
?>
    <?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username" class="<?= $Grid->employee_username->footerCellClass() ?>"><span id="elf_employee_quotation_detail_employee_username" class="employee_quotation_detail_employee_username">
        <span class="ew-aggregate"><?= $Language->phrase("COUNT") ?></span><span class="ew-aggregate-value">
        <?= $Grid->employee_username->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->rate->Visible) { // rate ?>
        <td data-name="rate" class="<?= $Grid->rate->footerCellClass() ?>"><span id="elf_employee_quotation_detail_rate" class="employee_quotation_detail_rate">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->rate->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->qty->Visible) { // qty ?>
        <td data-name="qty" class="<?= $Grid->qty->footerCellClass() ?>"><span id="elf_employee_quotation_detail_qty" class="employee_quotation_detail_qty">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Grid->Total->Visible) { // Total ?>
        <td data-name="Total" class="<?= $Grid->Total->footerCellClass() ?>"><span id="elf_employee_quotation_detail_Total" class="employee_quotation_detail_Total">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->Total->ViewValue ?></span>
        </span></td>
    <?php } ?>
<?php
// Render list options (footer, right)
$Grid->ListOptions->render("footer", "right");
?>
    </tr>
</tfoot>
<?php } ?>
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
<input type="hidden" name="detailpage" value="femployee_quotation_detailgrid">
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
    ew.addEventHandlers("employee_quotation_detail");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
