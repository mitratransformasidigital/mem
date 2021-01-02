<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("OfferingDetailGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.offering_detail) ew.vars.tables.offering_detail = <?= JsonEncode(GetClientVar("tables", "offering_detail")) ?>;
var currentForm, currentPageID;
var foffering_detailgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    foffering_detailgrid = new ew.Form("foffering_detailgrid", "grid");
    foffering_detailgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.offering_detail.fields;
    foffering_detailgrid.addFields([
        ["offering_id", [fields.offering_id.required ? ew.Validators.required(fields.offering_id.caption) : null, ew.Validators.integer], fields.offering_id.isInvalid],
        ["description", [fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid],
        ["qty", [fields.qty.required ? ew.Validators.required(fields.qty.caption) : null, ew.Validators.integer], fields.qty.isInvalid],
        ["rate", [fields.rate.required ? ew.Validators.required(fields.rate.caption) : null, ew.Validators.integer], fields.rate.isInvalid],
        ["discount", [fields.discount.required ? ew.Validators.required(fields.discount.caption) : null, ew.Validators.integer], fields.discount.isInvalid],
        ["total", [fields.total.required ? ew.Validators.required(fields.total.caption) : null, ew.Validators.float], fields.total.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = foffering_detailgrid,
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
    foffering_detailgrid.validate = function () {
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
    foffering_detailgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "offering_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "description", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "qty", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "rate", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "discount", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "total", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    foffering_detailgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    foffering_detailgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("foffering_detailgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> offering_detail">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="foffering_detailgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_offering_detail" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_offering_detailgrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->offering_id->Visible) { // offering_id ?>
        <th data-name="offering_id" class="<?= $Grid->offering_id->headerCellClass() ?>"><div id="elh_offering_detail_offering_id" class="offering_detail_offering_id"><?= $Grid->renderSort($Grid->offering_id) ?></div></th>
<?php } ?>
<?php if ($Grid->description->Visible) { // description ?>
        <th data-name="description" class="<?= $Grid->description->headerCellClass() ?>"><div id="elh_offering_detail_description" class="offering_detail_description"><?= $Grid->renderSort($Grid->description) ?></div></th>
<?php } ?>
<?php if ($Grid->qty->Visible) { // qty ?>
        <th data-name="qty" class="<?= $Grid->qty->headerCellClass() ?>"><div id="elh_offering_detail_qty" class="offering_detail_qty"><?= $Grid->renderSort($Grid->qty) ?></div></th>
<?php } ?>
<?php if ($Grid->rate->Visible) { // rate ?>
        <th data-name="rate" class="<?= $Grid->rate->headerCellClass() ?>"><div id="elh_offering_detail_rate" class="offering_detail_rate"><?= $Grid->renderSort($Grid->rate) ?></div></th>
<?php } ?>
<?php if ($Grid->discount->Visible) { // discount ?>
        <th data-name="discount" class="<?= $Grid->discount->headerCellClass() ?>"><div id="elh_offering_detail_discount" class="offering_detail_discount"><?= $Grid->renderSort($Grid->discount) ?></div></th>
<?php } ?>
<?php if ($Grid->total->Visible) { // total ?>
        <th data-name="total" class="<?= $Grid->total->headerCellClass() ?>"><div id="elh_offering_detail_total" class="offering_detail_total"><?= $Grid->renderSort($Grid->total) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_offering_detail", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->offering_id->Visible) { // offering_id ?>
        <td data-name="offering_id" <?= $Grid->offering_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->offering_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_offering_id" class="form-group">
<span<?= $Grid->offering_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->offering_id->getDisplayValue($Grid->offering_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_offering_id" name="x<?= $Grid->RowIndex ?>_offering_id" value="<?= HtmlEncode($Grid->offering_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_offering_id" class="form-group">
<input type="<?= $Grid->offering_id->getInputTextType() ?>" data-table="offering_detail" data-field="x_offering_id" name="x<?= $Grid->RowIndex ?>_offering_id" id="x<?= $Grid->RowIndex ?>_offering_id" size="30" placeholder="<?= HtmlEncode($Grid->offering_id->getPlaceHolder()) ?>" value="<?= $Grid->offering_id->EditValue ?>"<?= $Grid->offering_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->offering_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="offering_detail" data-field="x_offering_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_offering_id" id="o<?= $Grid->RowIndex ?>_offering_id" value="<?= HtmlEncode($Grid->offering_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->offering_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_offering_id" class="form-group">
<span<?= $Grid->offering_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->offering_id->getDisplayValue($Grid->offering_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_offering_id" name="x<?= $Grid->RowIndex ?>_offering_id" value="<?= HtmlEncode($Grid->offering_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_offering_id" class="form-group">
<input type="<?= $Grid->offering_id->getInputTextType() ?>" data-table="offering_detail" data-field="x_offering_id" name="x<?= $Grid->RowIndex ?>_offering_id" id="x<?= $Grid->RowIndex ?>_offering_id" size="30" placeholder="<?= HtmlEncode($Grid->offering_id->getPlaceHolder()) ?>" value="<?= $Grid->offering_id->EditValue ?>"<?= $Grid->offering_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->offering_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_offering_id">
<span<?= $Grid->offering_id->viewAttributes() ?>>
<?= $Grid->offering_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="offering_detail" data-field="x_offering_id" data-hidden="1" name="foffering_detailgrid$x<?= $Grid->RowIndex ?>_offering_id" id="foffering_detailgrid$x<?= $Grid->RowIndex ?>_offering_id" value="<?= HtmlEncode($Grid->offering_id->FormValue) ?>">
<input type="hidden" data-table="offering_detail" data-field="x_offering_id" data-hidden="1" name="foffering_detailgrid$o<?= $Grid->RowIndex ?>_offering_id" id="foffering_detailgrid$o<?= $Grid->RowIndex ?>_offering_id" value="<?= HtmlEncode($Grid->offering_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->description->Visible) { // description ?>
        <td data-name="description" <?= $Grid->description->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_description" class="form-group">
<textarea data-table="offering_detail" data-field="x_description" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->description->getPlaceHolder()) ?>"<?= $Grid->description->editAttributes() ?>><?= $Grid->description->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->description->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_description" data-hidden="1" name="o<?= $Grid->RowIndex ?>_description" id="o<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_description" class="form-group">
<textarea data-table="offering_detail" data-field="x_description" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->description->getPlaceHolder()) ?>"<?= $Grid->description->editAttributes() ?>><?= $Grid->description->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->description->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_description">
<span<?= $Grid->description->viewAttributes() ?>>
<?= $Grid->description->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="offering_detail" data-field="x_description" data-hidden="1" name="foffering_detailgrid$x<?= $Grid->RowIndex ?>_description" id="foffering_detailgrid$x<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->FormValue) ?>">
<input type="hidden" data-table="offering_detail" data-field="x_description" data-hidden="1" name="foffering_detailgrid$o<?= $Grid->RowIndex ?>_description" id="foffering_detailgrid$o<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->qty->Visible) { // qty ?>
        <td data-name="qty" <?= $Grid->qty->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_qty" class="form-group">
<input type="<?= $Grid->qty->getInputTextType() ?>" data-table="offering_detail" data-field="x_qty" name="x<?= $Grid->RowIndex ?>_qty" id="x<?= $Grid->RowIndex ?>_qty" size="30" placeholder="<?= HtmlEncode($Grid->qty->getPlaceHolder()) ?>" value="<?= $Grid->qty->EditValue ?>"<?= $Grid->qty->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->qty->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_qty" data-hidden="1" name="o<?= $Grid->RowIndex ?>_qty" id="o<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_qty" class="form-group">
<input type="<?= $Grid->qty->getInputTextType() ?>" data-table="offering_detail" data-field="x_qty" name="x<?= $Grid->RowIndex ?>_qty" id="x<?= $Grid->RowIndex ?>_qty" size="30" placeholder="<?= HtmlEncode($Grid->qty->getPlaceHolder()) ?>" value="<?= $Grid->qty->EditValue ?>"<?= $Grid->qty->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->qty->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_qty">
<span<?= $Grid->qty->viewAttributes() ?>>
<?= $Grid->qty->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="offering_detail" data-field="x_qty" data-hidden="1" name="foffering_detailgrid$x<?= $Grid->RowIndex ?>_qty" id="foffering_detailgrid$x<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->FormValue) ?>">
<input type="hidden" data-table="offering_detail" data-field="x_qty" data-hidden="1" name="foffering_detailgrid$o<?= $Grid->RowIndex ?>_qty" id="foffering_detailgrid$o<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->rate->Visible) { // rate ?>
        <td data-name="rate" <?= $Grid->rate->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_rate" class="form-group">
<input type="<?= $Grid->rate->getInputTextType() ?>" data-table="offering_detail" data-field="x_rate" name="x<?= $Grid->RowIndex ?>_rate" id="x<?= $Grid->RowIndex ?>_rate" size="30" placeholder="<?= HtmlEncode($Grid->rate->getPlaceHolder()) ?>" value="<?= $Grid->rate->EditValue ?>"<?= $Grid->rate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->rate->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_rate" data-hidden="1" name="o<?= $Grid->RowIndex ?>_rate" id="o<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_rate" class="form-group">
<input type="<?= $Grid->rate->getInputTextType() ?>" data-table="offering_detail" data-field="x_rate" name="x<?= $Grid->RowIndex ?>_rate" id="x<?= $Grid->RowIndex ?>_rate" size="30" placeholder="<?= HtmlEncode($Grid->rate->getPlaceHolder()) ?>" value="<?= $Grid->rate->EditValue ?>"<?= $Grid->rate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->rate->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_rate">
<span<?= $Grid->rate->viewAttributes() ?>>
<?= $Grid->rate->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="offering_detail" data-field="x_rate" data-hidden="1" name="foffering_detailgrid$x<?= $Grid->RowIndex ?>_rate" id="foffering_detailgrid$x<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->FormValue) ?>">
<input type="hidden" data-table="offering_detail" data-field="x_rate" data-hidden="1" name="foffering_detailgrid$o<?= $Grid->RowIndex ?>_rate" id="foffering_detailgrid$o<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->discount->Visible) { // discount ?>
        <td data-name="discount" <?= $Grid->discount->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_discount" class="form-group">
<input type="<?= $Grid->discount->getInputTextType() ?>" data-table="offering_detail" data-field="x_discount" name="x<?= $Grid->RowIndex ?>_discount" id="x<?= $Grid->RowIndex ?>_discount" size="30" placeholder="<?= HtmlEncode($Grid->discount->getPlaceHolder()) ?>" value="<?= $Grid->discount->EditValue ?>"<?= $Grid->discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->discount->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_discount" data-hidden="1" name="o<?= $Grid->RowIndex ?>_discount" id="o<?= $Grid->RowIndex ?>_discount" value="<?= HtmlEncode($Grid->discount->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_discount" class="form-group">
<input type="<?= $Grid->discount->getInputTextType() ?>" data-table="offering_detail" data-field="x_discount" name="x<?= $Grid->RowIndex ?>_discount" id="x<?= $Grid->RowIndex ?>_discount" size="30" placeholder="<?= HtmlEncode($Grid->discount->getPlaceHolder()) ?>" value="<?= $Grid->discount->EditValue ?>"<?= $Grid->discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->discount->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_discount">
<span<?= $Grid->discount->viewAttributes() ?>>
<?= $Grid->discount->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="offering_detail" data-field="x_discount" data-hidden="1" name="foffering_detailgrid$x<?= $Grid->RowIndex ?>_discount" id="foffering_detailgrid$x<?= $Grid->RowIndex ?>_discount" value="<?= HtmlEncode($Grid->discount->FormValue) ?>">
<input type="hidden" data-table="offering_detail" data-field="x_discount" data-hidden="1" name="foffering_detailgrid$o<?= $Grid->RowIndex ?>_discount" id="foffering_detailgrid$o<?= $Grid->RowIndex ?>_discount" value="<?= HtmlEncode($Grid->discount->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->total->Visible) { // total ?>
        <td data-name="total" <?= $Grid->total->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_total" class="form-group">
<input type="<?= $Grid->total->getInputTextType() ?>" data-table="offering_detail" data-field="x_total" name="x<?= $Grid->RowIndex ?>_total" id="x<?= $Grid->RowIndex ?>_total" size="30" placeholder="<?= HtmlEncode($Grid->total->getPlaceHolder()) ?>" value="<?= $Grid->total->EditValue ?>"<?= $Grid->total->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->total->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_total" data-hidden="1" name="o<?= $Grid->RowIndex ?>_total" id="o<?= $Grid->RowIndex ?>_total" value="<?= HtmlEncode($Grid->total->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_total" class="form-group">
<input type="<?= $Grid->total->getInputTextType() ?>" data-table="offering_detail" data-field="x_total" name="x<?= $Grid->RowIndex ?>_total" id="x<?= $Grid->RowIndex ?>_total" size="30" placeholder="<?= HtmlEncode($Grid->total->getPlaceHolder()) ?>" value="<?= $Grid->total->EditValue ?>"<?= $Grid->total->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->total->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_offering_detail_total">
<span<?= $Grid->total->viewAttributes() ?>>
<?= $Grid->total->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="offering_detail" data-field="x_total" data-hidden="1" name="foffering_detailgrid$x<?= $Grid->RowIndex ?>_total" id="foffering_detailgrid$x<?= $Grid->RowIndex ?>_total" value="<?= HtmlEncode($Grid->total->FormValue) ?>">
<input type="hidden" data-table="offering_detail" data-field="x_total" data-hidden="1" name="foffering_detailgrid$o<?= $Grid->RowIndex ?>_total" id="foffering_detailgrid$o<?= $Grid->RowIndex ?>_total" value="<?= HtmlEncode($Grid->total->OldValue) ?>">
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
loadjs.ready(["foffering_detailgrid","load"], function () {
    foffering_detailgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_offering_detail", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->offering_id->Visible) { // offering_id ?>
        <td data-name="offering_id">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->offering_id->getSessionValue() != "") { ?>
<span id="el$rowindex$_offering_detail_offering_id" class="form-group offering_detail_offering_id">
<span<?= $Grid->offering_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->offering_id->getDisplayValue($Grid->offering_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_offering_id" name="x<?= $Grid->RowIndex ?>_offering_id" value="<?= HtmlEncode($Grid->offering_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_offering_detail_offering_id" class="form-group offering_detail_offering_id">
<input type="<?= $Grid->offering_id->getInputTextType() ?>" data-table="offering_detail" data-field="x_offering_id" name="x<?= $Grid->RowIndex ?>_offering_id" id="x<?= $Grid->RowIndex ?>_offering_id" size="30" placeholder="<?= HtmlEncode($Grid->offering_id->getPlaceHolder()) ?>" value="<?= $Grid->offering_id->EditValue ?>"<?= $Grid->offering_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->offering_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_offering_detail_offering_id" class="form-group offering_detail_offering_id">
<span<?= $Grid->offering_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->offering_id->getDisplayValue($Grid->offering_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_offering_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_offering_id" id="x<?= $Grid->RowIndex ?>_offering_id" value="<?= HtmlEncode($Grid->offering_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="offering_detail" data-field="x_offering_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_offering_id" id="o<?= $Grid->RowIndex ?>_offering_id" value="<?= HtmlEncode($Grid->offering_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->description->Visible) { // description ?>
        <td data-name="description">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_offering_detail_description" class="form-group offering_detail_description">
<textarea data-table="offering_detail" data-field="x_description" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->description->getPlaceHolder()) ?>"<?= $Grid->description->editAttributes() ?>><?= $Grid->description->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->description->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_offering_detail_description" class="form-group offering_detail_description">
<span<?= $Grid->description->viewAttributes() ?>>
<?= $Grid->description->ViewValue ?></span>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_description" data-hidden="1" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="offering_detail" data-field="x_description" data-hidden="1" name="o<?= $Grid->RowIndex ?>_description" id="o<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->qty->Visible) { // qty ?>
        <td data-name="qty">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_offering_detail_qty" class="form-group offering_detail_qty">
<input type="<?= $Grid->qty->getInputTextType() ?>" data-table="offering_detail" data-field="x_qty" name="x<?= $Grid->RowIndex ?>_qty" id="x<?= $Grid->RowIndex ?>_qty" size="30" placeholder="<?= HtmlEncode($Grid->qty->getPlaceHolder()) ?>" value="<?= $Grid->qty->EditValue ?>"<?= $Grid->qty->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->qty->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_offering_detail_qty" class="form-group offering_detail_qty">
<span<?= $Grid->qty->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->qty->getDisplayValue($Grid->qty->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_qty" data-hidden="1" name="x<?= $Grid->RowIndex ?>_qty" id="x<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="offering_detail" data-field="x_qty" data-hidden="1" name="o<?= $Grid->RowIndex ?>_qty" id="o<?= $Grid->RowIndex ?>_qty" value="<?= HtmlEncode($Grid->qty->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->rate->Visible) { // rate ?>
        <td data-name="rate">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_offering_detail_rate" class="form-group offering_detail_rate">
<input type="<?= $Grid->rate->getInputTextType() ?>" data-table="offering_detail" data-field="x_rate" name="x<?= $Grid->RowIndex ?>_rate" id="x<?= $Grid->RowIndex ?>_rate" size="30" placeholder="<?= HtmlEncode($Grid->rate->getPlaceHolder()) ?>" value="<?= $Grid->rate->EditValue ?>"<?= $Grid->rate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->rate->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_offering_detail_rate" class="form-group offering_detail_rate">
<span<?= $Grid->rate->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->rate->getDisplayValue($Grid->rate->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_rate" data-hidden="1" name="x<?= $Grid->RowIndex ?>_rate" id="x<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="offering_detail" data-field="x_rate" data-hidden="1" name="o<?= $Grid->RowIndex ?>_rate" id="o<?= $Grid->RowIndex ?>_rate" value="<?= HtmlEncode($Grid->rate->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->discount->Visible) { // discount ?>
        <td data-name="discount">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_offering_detail_discount" class="form-group offering_detail_discount">
<input type="<?= $Grid->discount->getInputTextType() ?>" data-table="offering_detail" data-field="x_discount" name="x<?= $Grid->RowIndex ?>_discount" id="x<?= $Grid->RowIndex ?>_discount" size="30" placeholder="<?= HtmlEncode($Grid->discount->getPlaceHolder()) ?>" value="<?= $Grid->discount->EditValue ?>"<?= $Grid->discount->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->discount->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_offering_detail_discount" class="form-group offering_detail_discount">
<span<?= $Grid->discount->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->discount->getDisplayValue($Grid->discount->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_discount" data-hidden="1" name="x<?= $Grid->RowIndex ?>_discount" id="x<?= $Grid->RowIndex ?>_discount" value="<?= HtmlEncode($Grid->discount->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="offering_detail" data-field="x_discount" data-hidden="1" name="o<?= $Grid->RowIndex ?>_discount" id="o<?= $Grid->RowIndex ?>_discount" value="<?= HtmlEncode($Grid->discount->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->total->Visible) { // total ?>
        <td data-name="total">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_offering_detail_total" class="form-group offering_detail_total">
<input type="<?= $Grid->total->getInputTextType() ?>" data-table="offering_detail" data-field="x_total" name="x<?= $Grid->RowIndex ?>_total" id="x<?= $Grid->RowIndex ?>_total" size="30" placeholder="<?= HtmlEncode($Grid->total->getPlaceHolder()) ?>" value="<?= $Grid->total->EditValue ?>"<?= $Grid->total->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->total->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_offering_detail_total" class="form-group offering_detail_total">
<span<?= $Grid->total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->total->getDisplayValue($Grid->total->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="offering_detail" data-field="x_total" data-hidden="1" name="x<?= $Grid->RowIndex ?>_total" id="x<?= $Grid->RowIndex ?>_total" value="<?= HtmlEncode($Grid->total->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="offering_detail" data-field="x_total" data-hidden="1" name="o<?= $Grid->RowIndex ?>_total" id="o<?= $Grid->RowIndex ?>_total" value="<?= HtmlEncode($Grid->total->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["foffering_detailgrid","load"], function() {
    foffering_detailgrid.updateLists(<?= $Grid->RowIndex ?>);
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
    <?php if ($Grid->offering_id->Visible) { // offering_id ?>
        <td data-name="offering_id" class="<?= $Grid->offering_id->footerCellClass() ?>"><span id="elf_offering_detail_offering_id" class="offering_detail_offering_id">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Grid->description->Visible) { // description ?>
        <td data-name="description" class="<?= $Grid->description->footerCellClass() ?>"><span id="elf_offering_detail_description" class="offering_detail_description">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Grid->qty->Visible) { // qty ?>
        <td data-name="qty" class="<?= $Grid->qty->footerCellClass() ?>"><span id="elf_offering_detail_qty" class="offering_detail_qty">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Grid->rate->Visible) { // rate ?>
        <td data-name="rate" class="<?= $Grid->rate->footerCellClass() ?>"><span id="elf_offering_detail_rate" class="offering_detail_rate">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->rate->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->discount->Visible) { // discount ?>
        <td data-name="discount" class="<?= $Grid->discount->footerCellClass() ?>"><span id="elf_offering_detail_discount" class="offering_detail_discount">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Grid->total->Visible) { // total ?>
        <td data-name="total" class="<?= $Grid->total->footerCellClass() ?>"><span id="elf_offering_detail_total" class="offering_detail_total">
        &nbsp;
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
<input type="hidden" name="detailpage" value="foffering_detailgrid">
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
    ew.addEventHandlers("offering_detail");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
