<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("MasterCityGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.master_city) ew.vars.tables.master_city = <?= JsonEncode(GetClientVar("tables", "master_city")) ?>;
var currentForm, currentPageID;
var fmaster_citygrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fmaster_citygrid = new ew.Form("fmaster_citygrid", "grid");
    fmaster_citygrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.master_city.fields;
    fmaster_citygrid.addFields([
        ["province_id", [fields.province_id.required ? ew.Validators.required(fields.province_id.caption) : null], fields.province_id.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["city", [fields.city.required ? ew.Validators.required(fields.city.caption) : null], fields.city.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_citygrid,
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
    fmaster_citygrid.validate = function () {
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
    fmaster_citygrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "province_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "city_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "city", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fmaster_citygrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_citygrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_citygrid.lists.province_id = <?= $Grid->province_id->toClientList($Grid) ?>;
    loadjs.done("fmaster_citygrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> master_city">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fmaster_citygrid" class="ew-form ew-list-form form-inline">
<div id="gmp_master_city" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_master_citygrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->province_id->Visible) { // province_id ?>
        <th data-name="province_id" class="<?= $Grid->province_id->headerCellClass() ?>"><div id="elh_master_city_province_id" class="master_city_province_id"><?= $Grid->renderSort($Grid->province_id) ?></div></th>
<?php } ?>
<?php if ($Grid->city_id->Visible) { // city_id ?>
        <th data-name="city_id" class="<?= $Grid->city_id->headerCellClass() ?>"><div id="elh_master_city_city_id" class="master_city_city_id"><?= $Grid->renderSort($Grid->city_id) ?></div></th>
<?php } ?>
<?php if ($Grid->city->Visible) { // city ?>
        <th data-name="city" class="<?= $Grid->city->headerCellClass() ?>"><div id="elh_master_city_city" class="master_city_city"><?= $Grid->renderSort($Grid->city) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_master_city", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->province_id->Visible) { // province_id ?>
        <td data-name="province_id" <?= $Grid->province_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->province_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_master_city_province_id" class="form-group">
<span<?= $Grid->province_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->province_id->getDisplayValue($Grid->province_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_province_id" name="x<?= $Grid->RowIndex ?>_province_id" value="<?= HtmlEncode($Grid->province_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_master_city_province_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_province_id"
        name="x<?= $Grid->RowIndex ?>_province_id"
        class="form-control ew-select<?= $Grid->province_id->isInvalidClass() ?>"
        data-select2-id="master_city_x<?= $Grid->RowIndex ?>_province_id"
        data-table="master_city"
        data-field="x_province_id"
        data-value-separator="<?= $Grid->province_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->province_id->getPlaceHolder()) ?>"
        <?= $Grid->province_id->editAttributes() ?>>
        <?= $Grid->province_id->selectOptionListHtml("x{$Grid->RowIndex}_province_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_province") && !$Grid->province_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_province_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->province_id->caption() ?>" data-title="<?= $Grid->province_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_province_id',url:'<?= GetUrl("masterprovinceaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->province_id->getErrorMessage() ?></div>
<?= $Grid->province_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_province_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_city_x<?= $Grid->RowIndex ?>_province_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_province_id", selectId: "master_city_x<?= $Grid->RowIndex ?>_province_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_city.fields.province_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="master_city" data-field="x_province_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_province_id" id="o<?= $Grid->RowIndex ?>_province_id" value="<?= HtmlEncode($Grid->province_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->province_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_master_city_province_id" class="form-group">
<span<?= $Grid->province_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->province_id->getDisplayValue($Grid->province_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_province_id" name="x<?= $Grid->RowIndex ?>_province_id" value="<?= HtmlEncode($Grid->province_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_master_city_province_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_province_id"
        name="x<?= $Grid->RowIndex ?>_province_id"
        class="form-control ew-select<?= $Grid->province_id->isInvalidClass() ?>"
        data-select2-id="master_city_x<?= $Grid->RowIndex ?>_province_id"
        data-table="master_city"
        data-field="x_province_id"
        data-value-separator="<?= $Grid->province_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->province_id->getPlaceHolder()) ?>"
        <?= $Grid->province_id->editAttributes() ?>>
        <?= $Grid->province_id->selectOptionListHtml("x{$Grid->RowIndex}_province_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_province") && !$Grid->province_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_province_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->province_id->caption() ?>" data-title="<?= $Grid->province_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_province_id',url:'<?= GetUrl("masterprovinceaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->province_id->getErrorMessage() ?></div>
<?= $Grid->province_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_province_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_city_x<?= $Grid->RowIndex ?>_province_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_province_id", selectId: "master_city_x<?= $Grid->RowIndex ?>_province_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_city.fields.province_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_city_province_id">
<span<?= $Grid->province_id->viewAttributes() ?>>
<?= $Grid->province_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_city" data-field="x_province_id" data-hidden="1" name="fmaster_citygrid$x<?= $Grid->RowIndex ?>_province_id" id="fmaster_citygrid$x<?= $Grid->RowIndex ?>_province_id" value="<?= HtmlEncode($Grid->province_id->FormValue) ?>">
<input type="hidden" data-table="master_city" data-field="x_province_id" data-hidden="1" name="fmaster_citygrid$o<?= $Grid->RowIndex ?>_province_id" id="fmaster_citygrid$o<?= $Grid->RowIndex ?>_province_id" value="<?= HtmlEncode($Grid->province_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->city_id->Visible) { // city_id ?>
        <td data-name="city_id" <?= $Grid->city_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_master_city_city_id" class="form-group">
<input type="<?= $Grid->city_id->getInputTextType() ?>" data-table="master_city" data-field="x_city_id" name="x<?= $Grid->RowIndex ?>_city_id" id="x<?= $Grid->RowIndex ?>_city_id" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->city_id->getPlaceHolder()) ?>" value="<?= $Grid->city_id->EditValue ?>"<?= $Grid->city_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->city_id->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="master_city" data-field="x_city_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_city_id" id="o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Grid->city_id->getInputTextType() ?>" data-table="master_city" data-field="x_city_id" name="x<?= $Grid->RowIndex ?>_city_id" id="x<?= $Grid->RowIndex ?>_city_id" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->city_id->getPlaceHolder()) ?>" value="<?= $Grid->city_id->EditValue ?>"<?= $Grid->city_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->city_id->getErrorMessage() ?></div>
<input type="hidden" data-table="master_city" data-field="x_city_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_city_id" id="o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue ?? $Grid->city_id->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_city_city_id">
<span<?= $Grid->city_id->viewAttributes() ?>>
<?= $Grid->city_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_city" data-field="x_city_id" data-hidden="1" name="fmaster_citygrid$x<?= $Grid->RowIndex ?>_city_id" id="fmaster_citygrid$x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->FormValue) ?>">
<input type="hidden" data-table="master_city" data-field="x_city_id" data-hidden="1" name="fmaster_citygrid$o<?= $Grid->RowIndex ?>_city_id" id="fmaster_citygrid$o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="master_city" data-field="x_city_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_city_id" id="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->city->Visible) { // city ?>
        <td data-name="city" <?= $Grid->city->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_master_city_city" class="form-group">
<input type="<?= $Grid->city->getInputTextType() ?>" data-table="master_city" data-field="x_city" name="x<?= $Grid->RowIndex ?>_city" id="x<?= $Grid->RowIndex ?>_city" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->city->getPlaceHolder()) ?>" value="<?= $Grid->city->EditValue ?>"<?= $Grid->city->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->city->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="master_city" data-field="x_city" data-hidden="1" name="o<?= $Grid->RowIndex ?>_city" id="o<?= $Grid->RowIndex ?>_city" value="<?= HtmlEncode($Grid->city->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_master_city_city" class="form-group">
<input type="<?= $Grid->city->getInputTextType() ?>" data-table="master_city" data-field="x_city" name="x<?= $Grid->RowIndex ?>_city" id="x<?= $Grid->RowIndex ?>_city" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->city->getPlaceHolder()) ?>" value="<?= $Grid->city->EditValue ?>"<?= $Grid->city->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->city->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_city_city">
<span<?= $Grid->city->viewAttributes() ?>>
<?= $Grid->city->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_city" data-field="x_city" data-hidden="1" name="fmaster_citygrid$x<?= $Grid->RowIndex ?>_city" id="fmaster_citygrid$x<?= $Grid->RowIndex ?>_city" value="<?= HtmlEncode($Grid->city->FormValue) ?>">
<input type="hidden" data-table="master_city" data-field="x_city" data-hidden="1" name="fmaster_citygrid$o<?= $Grid->RowIndex ?>_city" id="fmaster_citygrid$o<?= $Grid->RowIndex ?>_city" value="<?= HtmlEncode($Grid->city->OldValue) ?>">
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
loadjs.ready(["fmaster_citygrid","load"], function () {
    fmaster_citygrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_master_city", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->province_id->Visible) { // province_id ?>
        <td data-name="province_id">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->province_id->getSessionValue() != "") { ?>
<span id="el$rowindex$_master_city_province_id" class="form-group master_city_province_id">
<span<?= $Grid->province_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->province_id->getDisplayValue($Grid->province_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_province_id" name="x<?= $Grid->RowIndex ?>_province_id" value="<?= HtmlEncode($Grid->province_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_master_city_province_id" class="form-group master_city_province_id">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_province_id"
        name="x<?= $Grid->RowIndex ?>_province_id"
        class="form-control ew-select<?= $Grid->province_id->isInvalidClass() ?>"
        data-select2-id="master_city_x<?= $Grid->RowIndex ?>_province_id"
        data-table="master_city"
        data-field="x_province_id"
        data-value-separator="<?= $Grid->province_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->province_id->getPlaceHolder()) ?>"
        <?= $Grid->province_id->editAttributes() ?>>
        <?= $Grid->province_id->selectOptionListHtml("x{$Grid->RowIndex}_province_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_province") && !$Grid->province_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_province_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->province_id->caption() ?>" data-title="<?= $Grid->province_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_province_id',url:'<?= GetUrl("masterprovinceaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->province_id->getErrorMessage() ?></div>
<?= $Grid->province_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_province_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_city_x<?= $Grid->RowIndex ?>_province_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_province_id", selectId: "master_city_x<?= $Grid->RowIndex ?>_province_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_city.fields.province_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_master_city_province_id" class="form-group master_city_province_id">
<span<?= $Grid->province_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->province_id->getDisplayValue($Grid->province_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="master_city" data-field="x_province_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_province_id" id="x<?= $Grid->RowIndex ?>_province_id" value="<?= HtmlEncode($Grid->province_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_city" data-field="x_province_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_province_id" id="o<?= $Grid->RowIndex ?>_province_id" value="<?= HtmlEncode($Grid->province_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->city_id->Visible) { // city_id ?>
        <td data-name="city_id">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_master_city_city_id" class="form-group master_city_city_id">
<input type="<?= $Grid->city_id->getInputTextType() ?>" data-table="master_city" data-field="x_city_id" name="x<?= $Grid->RowIndex ?>_city_id" id="x<?= $Grid->RowIndex ?>_city_id" size="30" maxlength="10" placeholder="<?= HtmlEncode($Grid->city_id->getPlaceHolder()) ?>" value="<?= $Grid->city_id->EditValue ?>"<?= $Grid->city_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->city_id->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_master_city_city_id" class="form-group master_city_city_id">
<span<?= $Grid->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city_id->getDisplayValue($Grid->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="master_city" data-field="x_city_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_city_id" id="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_city" data-field="x_city_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_city_id" id="o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->city->Visible) { // city ?>
        <td data-name="city">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_master_city_city" class="form-group master_city_city">
<input type="<?= $Grid->city->getInputTextType() ?>" data-table="master_city" data-field="x_city" name="x<?= $Grid->RowIndex ?>_city" id="x<?= $Grid->RowIndex ?>_city" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->city->getPlaceHolder()) ?>" value="<?= $Grid->city->EditValue ?>"<?= $Grid->city->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->city->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_master_city_city" class="form-group master_city_city">
<span<?= $Grid->city->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city->getDisplayValue($Grid->city->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="master_city" data-field="x_city" data-hidden="1" name="x<?= $Grid->RowIndex ?>_city" id="x<?= $Grid->RowIndex ?>_city" value="<?= HtmlEncode($Grid->city->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_city" data-field="x_city" data-hidden="1" name="o<?= $Grid->RowIndex ?>_city" id="o<?= $Grid->RowIndex ?>_city" value="<?= HtmlEncode($Grid->city->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fmaster_citygrid","load"], function() {
    fmaster_citygrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fmaster_citygrid">
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
    ew.addEventHandlers("master_city");
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
        container: "gmp_master_city",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
