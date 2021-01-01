<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("MasterOfficeGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.master_office) ew.vars.tables.master_office = <?= JsonEncode(GetClientVar("tables", "master_office")) ?>;
var currentForm, currentPageID;
var fmaster_officegrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fmaster_officegrid = new ew.Form("fmaster_officegrid", "grid");
    fmaster_officegrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.master_office.fields;
    fmaster_officegrid.addFields([
        ["office", [fields.office.required ? ew.Validators.required(fields.office.caption) : null], fields.office.isInvalid],
        ["address", [fields.address.required ? ew.Validators.required(fields.address.caption) : null], fields.address.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["phone_number", [fields.phone_number.required ? ew.Validators.required(fields.phone_number.caption) : null], fields.phone_number.isInvalid],
        ["contact_name", [fields.contact_name.required ? ew.Validators.required(fields.contact_name.caption) : null], fields.contact_name.isInvalid],
        ["description", [fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_officegrid,
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
    fmaster_officegrid.validate = function () {
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
    fmaster_officegrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "office", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "address", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "city_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "phone_number", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "contact_name", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "description", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fmaster_officegrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_officegrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_officegrid.lists.city_id = <?= $Grid->city_id->toClientList($Grid) ?>;
    loadjs.done("fmaster_officegrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> master_office">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fmaster_officegrid" class="ew-form ew-list-form form-inline">
<div id="gmp_master_office" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_master_officegrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->office->Visible) { // office ?>
        <th data-name="office" class="<?= $Grid->office->headerCellClass() ?>"><div id="elh_master_office_office" class="master_office_office"><?= $Grid->renderSort($Grid->office) ?></div></th>
<?php } ?>
<?php if ($Grid->address->Visible) { // address ?>
        <th data-name="address" class="<?= $Grid->address->headerCellClass() ?>"><div id="elh_master_office_address" class="master_office_address"><?= $Grid->renderSort($Grid->address) ?></div></th>
<?php } ?>
<?php if ($Grid->city_id->Visible) { // city_id ?>
        <th data-name="city_id" class="<?= $Grid->city_id->headerCellClass() ?>"><div id="elh_master_office_city_id" class="master_office_city_id"><?= $Grid->renderSort($Grid->city_id) ?></div></th>
<?php } ?>
<?php if ($Grid->phone_number->Visible) { // phone_number ?>
        <th data-name="phone_number" class="<?= $Grid->phone_number->headerCellClass() ?>"><div id="elh_master_office_phone_number" class="master_office_phone_number"><?= $Grid->renderSort($Grid->phone_number) ?></div></th>
<?php } ?>
<?php if ($Grid->contact_name->Visible) { // contact_name ?>
        <th data-name="contact_name" class="<?= $Grid->contact_name->headerCellClass() ?>"><div id="elh_master_office_contact_name" class="master_office_contact_name"><?= $Grid->renderSort($Grid->contact_name) ?></div></th>
<?php } ?>
<?php if ($Grid->description->Visible) { // description ?>
        <th data-name="description" class="<?= $Grid->description->headerCellClass() ?>"><div id="elh_master_office_description" class="master_office_description"><?= $Grid->renderSort($Grid->description) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_master_office", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->office->Visible) { // office ?>
        <td data-name="office" <?= $Grid->office->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_office" class="form-group">
<input type="<?= $Grid->office->getInputTextType() ?>" data-table="master_office" data-field="x_office" name="x<?= $Grid->RowIndex ?>_office" id="x<?= $Grid->RowIndex ?>_office" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->office->getPlaceHolder()) ?>" value="<?= $Grid->office->EditValue ?>"<?= $Grid->office->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->office->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="master_office" data-field="x_office" data-hidden="1" name="o<?= $Grid->RowIndex ?>_office" id="o<?= $Grid->RowIndex ?>_office" value="<?= HtmlEncode($Grid->office->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_office" class="form-group">
<input type="<?= $Grid->office->getInputTextType() ?>" data-table="master_office" data-field="x_office" name="x<?= $Grid->RowIndex ?>_office" id="x<?= $Grid->RowIndex ?>_office" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->office->getPlaceHolder()) ?>" value="<?= $Grid->office->EditValue ?>"<?= $Grid->office->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->office->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_office">
<span<?= $Grid->office->viewAttributes() ?>>
<?= $Grid->office->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_office" data-field="x_office" data-hidden="1" name="fmaster_officegrid$x<?= $Grid->RowIndex ?>_office" id="fmaster_officegrid$x<?= $Grid->RowIndex ?>_office" value="<?= HtmlEncode($Grid->office->FormValue) ?>">
<input type="hidden" data-table="master_office" data-field="x_office" data-hidden="1" name="fmaster_officegrid$o<?= $Grid->RowIndex ?>_office" id="fmaster_officegrid$o<?= $Grid->RowIndex ?>_office" value="<?= HtmlEncode($Grid->office->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->address->Visible) { // address ?>
        <td data-name="address" <?= $Grid->address->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_address" class="form-group">
<textarea data-table="master_office" data-field="x_address" name="x<?= $Grid->RowIndex ?>_address" id="x<?= $Grid->RowIndex ?>_address" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->address->getPlaceHolder()) ?>"<?= $Grid->address->editAttributes() ?>><?= $Grid->address->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->address->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="master_office" data-field="x_address" data-hidden="1" name="o<?= $Grid->RowIndex ?>_address" id="o<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_address" class="form-group">
<textarea data-table="master_office" data-field="x_address" name="x<?= $Grid->RowIndex ?>_address" id="x<?= $Grid->RowIndex ?>_address" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->address->getPlaceHolder()) ?>"<?= $Grid->address->editAttributes() ?>><?= $Grid->address->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->address->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_address">
<span<?= $Grid->address->viewAttributes() ?>>
<?= $Grid->address->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_office" data-field="x_address" data-hidden="1" name="fmaster_officegrid$x<?= $Grid->RowIndex ?>_address" id="fmaster_officegrid$x<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->FormValue) ?>">
<input type="hidden" data-table="master_office" data-field="x_address" data-hidden="1" name="fmaster_officegrid$o<?= $Grid->RowIndex ?>_address" id="fmaster_officegrid$o<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->city_id->Visible) { // city_id ?>
        <td data-name="city_id" <?= $Grid->city_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->city_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_master_office_city_id" class="form-group">
<span<?= $Grid->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city_id->getDisplayValue($Grid->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_city_id" name="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_master_office_city_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_city_id"
        name="x<?= $Grid->RowIndex ?>_city_id"
        class="form-control ew-select<?= $Grid->city_id->isInvalidClass() ?>"
        data-select2-id="master_office_x<?= $Grid->RowIndex ?>_city_id"
        data-table="master_office"
        data-field="x_city_id"
        data-value-separator="<?= $Grid->city_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->city_id->getPlaceHolder()) ?>"
        <?= $Grid->city_id->editAttributes() ?>>
        <?= $Grid->city_id->selectOptionListHtml("x{$Grid->RowIndex}_city_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_city") && !$Grid->city_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_city_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->city_id->caption() ?>" data-title="<?= $Grid->city_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_city_id',url:'<?= GetUrl("mastercityaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->city_id->getErrorMessage() ?></div>
<?= $Grid->city_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_city_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_office_x<?= $Grid->RowIndex ?>_city_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_city_id", selectId: "master_office_x<?= $Grid->RowIndex ?>_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_office.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="master_office" data-field="x_city_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_city_id" id="o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->city_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_master_office_city_id" class="form-group">
<span<?= $Grid->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city_id->getDisplayValue($Grid->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_city_id" name="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_master_office_city_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_city_id"
        name="x<?= $Grid->RowIndex ?>_city_id"
        class="form-control ew-select<?= $Grid->city_id->isInvalidClass() ?>"
        data-select2-id="master_office_x<?= $Grid->RowIndex ?>_city_id"
        data-table="master_office"
        data-field="x_city_id"
        data-value-separator="<?= $Grid->city_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->city_id->getPlaceHolder()) ?>"
        <?= $Grid->city_id->editAttributes() ?>>
        <?= $Grid->city_id->selectOptionListHtml("x{$Grid->RowIndex}_city_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_city") && !$Grid->city_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_city_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->city_id->caption() ?>" data-title="<?= $Grid->city_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_city_id',url:'<?= GetUrl("mastercityaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->city_id->getErrorMessage() ?></div>
<?= $Grid->city_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_city_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_office_x<?= $Grid->RowIndex ?>_city_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_city_id", selectId: "master_office_x<?= $Grid->RowIndex ?>_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_office.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_city_id">
<span<?= $Grid->city_id->viewAttributes() ?>>
<?= $Grid->city_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_office" data-field="x_city_id" data-hidden="1" name="fmaster_officegrid$x<?= $Grid->RowIndex ?>_city_id" id="fmaster_officegrid$x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->FormValue) ?>">
<input type="hidden" data-table="master_office" data-field="x_city_id" data-hidden="1" name="fmaster_officegrid$o<?= $Grid->RowIndex ?>_city_id" id="fmaster_officegrid$o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->phone_number->Visible) { // phone_number ?>
        <td data-name="phone_number" <?= $Grid->phone_number->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_phone_number" class="form-group">
<input type="<?= $Grid->phone_number->getInputTextType() ?>" data-table="master_office" data-field="x_phone_number" name="x<?= $Grid->RowIndex ?>_phone_number" id="x<?= $Grid->RowIndex ?>_phone_number" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->phone_number->getPlaceHolder()) ?>" value="<?= $Grid->phone_number->EditValue ?>"<?= $Grid->phone_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->phone_number->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="master_office" data-field="x_phone_number" data-hidden="1" name="o<?= $Grid->RowIndex ?>_phone_number" id="o<?= $Grid->RowIndex ?>_phone_number" value="<?= HtmlEncode($Grid->phone_number->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_phone_number" class="form-group">
<input type="<?= $Grid->phone_number->getInputTextType() ?>" data-table="master_office" data-field="x_phone_number" name="x<?= $Grid->RowIndex ?>_phone_number" id="x<?= $Grid->RowIndex ?>_phone_number" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->phone_number->getPlaceHolder()) ?>" value="<?= $Grid->phone_number->EditValue ?>"<?= $Grid->phone_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->phone_number->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_phone_number">
<span<?= $Grid->phone_number->viewAttributes() ?>>
<?= $Grid->phone_number->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_office" data-field="x_phone_number" data-hidden="1" name="fmaster_officegrid$x<?= $Grid->RowIndex ?>_phone_number" id="fmaster_officegrid$x<?= $Grid->RowIndex ?>_phone_number" value="<?= HtmlEncode($Grid->phone_number->FormValue) ?>">
<input type="hidden" data-table="master_office" data-field="x_phone_number" data-hidden="1" name="fmaster_officegrid$o<?= $Grid->RowIndex ?>_phone_number" id="fmaster_officegrid$o<?= $Grid->RowIndex ?>_phone_number" value="<?= HtmlEncode($Grid->phone_number->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->contact_name->Visible) { // contact_name ?>
        <td data-name="contact_name" <?= $Grid->contact_name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_contact_name" class="form-group">
<input type="<?= $Grid->contact_name->getInputTextType() ?>" data-table="master_office" data-field="x_contact_name" name="x<?= $Grid->RowIndex ?>_contact_name" id="x<?= $Grid->RowIndex ?>_contact_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->contact_name->getPlaceHolder()) ?>" value="<?= $Grid->contact_name->EditValue ?>"<?= $Grid->contact_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->contact_name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="master_office" data-field="x_contact_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_contact_name" id="o<?= $Grid->RowIndex ?>_contact_name" value="<?= HtmlEncode($Grid->contact_name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_contact_name" class="form-group">
<input type="<?= $Grid->contact_name->getInputTextType() ?>" data-table="master_office" data-field="x_contact_name" name="x<?= $Grid->RowIndex ?>_contact_name" id="x<?= $Grid->RowIndex ?>_contact_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->contact_name->getPlaceHolder()) ?>" value="<?= $Grid->contact_name->EditValue ?>"<?= $Grid->contact_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->contact_name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_contact_name">
<span<?= $Grid->contact_name->viewAttributes() ?>>
<?= $Grid->contact_name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_office" data-field="x_contact_name" data-hidden="1" name="fmaster_officegrid$x<?= $Grid->RowIndex ?>_contact_name" id="fmaster_officegrid$x<?= $Grid->RowIndex ?>_contact_name" value="<?= HtmlEncode($Grid->contact_name->FormValue) ?>">
<input type="hidden" data-table="master_office" data-field="x_contact_name" data-hidden="1" name="fmaster_officegrid$o<?= $Grid->RowIndex ?>_contact_name" id="fmaster_officegrid$o<?= $Grid->RowIndex ?>_contact_name" value="<?= HtmlEncode($Grid->contact_name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->description->Visible) { // description ?>
        <td data-name="description" <?= $Grid->description->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_description" class="form-group">
<textarea data-table="master_office" data-field="x_description" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->description->getPlaceHolder()) ?>"<?= $Grid->description->editAttributes() ?>><?= $Grid->description->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->description->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="master_office" data-field="x_description" data-hidden="1" name="o<?= $Grid->RowIndex ?>_description" id="o<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_description" class="form-group">
<textarea data-table="master_office" data-field="x_description" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->description->getPlaceHolder()) ?>"<?= $Grid->description->editAttributes() ?>><?= $Grid->description->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->description->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_master_office_description">
<span<?= $Grid->description->viewAttributes() ?>>
<?= $Grid->description->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="master_office" data-field="x_description" data-hidden="1" name="fmaster_officegrid$x<?= $Grid->RowIndex ?>_description" id="fmaster_officegrid$x<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->FormValue) ?>">
<input type="hidden" data-table="master_office" data-field="x_description" data-hidden="1" name="fmaster_officegrid$o<?= $Grid->RowIndex ?>_description" id="fmaster_officegrid$o<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->OldValue) ?>">
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
loadjs.ready(["fmaster_officegrid","load"], function () {
    fmaster_officegrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_master_office", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->office->Visible) { // office ?>
        <td data-name="office">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_master_office_office" class="form-group master_office_office">
<input type="<?= $Grid->office->getInputTextType() ?>" data-table="master_office" data-field="x_office" name="x<?= $Grid->RowIndex ?>_office" id="x<?= $Grid->RowIndex ?>_office" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->office->getPlaceHolder()) ?>" value="<?= $Grid->office->EditValue ?>"<?= $Grid->office->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->office->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_master_office_office" class="form-group master_office_office">
<span<?= $Grid->office->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->office->getDisplayValue($Grid->office->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="master_office" data-field="x_office" data-hidden="1" name="x<?= $Grid->RowIndex ?>_office" id="x<?= $Grid->RowIndex ?>_office" value="<?= HtmlEncode($Grid->office->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_office" data-field="x_office" data-hidden="1" name="o<?= $Grid->RowIndex ?>_office" id="o<?= $Grid->RowIndex ?>_office" value="<?= HtmlEncode($Grid->office->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->address->Visible) { // address ?>
        <td data-name="address">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_master_office_address" class="form-group master_office_address">
<textarea data-table="master_office" data-field="x_address" name="x<?= $Grid->RowIndex ?>_address" id="x<?= $Grid->RowIndex ?>_address" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->address->getPlaceHolder()) ?>"<?= $Grid->address->editAttributes() ?>><?= $Grid->address->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->address->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_master_office_address" class="form-group master_office_address">
<span<?= $Grid->address->viewAttributes() ?>>
<?= $Grid->address->ViewValue ?></span>
</span>
<input type="hidden" data-table="master_office" data-field="x_address" data-hidden="1" name="x<?= $Grid->RowIndex ?>_address" id="x<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_office" data-field="x_address" data-hidden="1" name="o<?= $Grid->RowIndex ?>_address" id="o<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->city_id->Visible) { // city_id ?>
        <td data-name="city_id">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->city_id->getSessionValue() != "") { ?>
<span id="el$rowindex$_master_office_city_id" class="form-group master_office_city_id">
<span<?= $Grid->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city_id->getDisplayValue($Grid->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_city_id" name="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_master_office_city_id" class="form-group master_office_city_id">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_city_id"
        name="x<?= $Grid->RowIndex ?>_city_id"
        class="form-control ew-select<?= $Grid->city_id->isInvalidClass() ?>"
        data-select2-id="master_office_x<?= $Grid->RowIndex ?>_city_id"
        data-table="master_office"
        data-field="x_city_id"
        data-value-separator="<?= $Grid->city_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->city_id->getPlaceHolder()) ?>"
        <?= $Grid->city_id->editAttributes() ?>>
        <?= $Grid->city_id->selectOptionListHtml("x{$Grid->RowIndex}_city_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_city") && !$Grid->city_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_city_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->city_id->caption() ?>" data-title="<?= $Grid->city_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_city_id',url:'<?= GetUrl("mastercityaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->city_id->getErrorMessage() ?></div>
<?= $Grid->city_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_city_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_office_x<?= $Grid->RowIndex ?>_city_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_city_id", selectId: "master_office_x<?= $Grid->RowIndex ?>_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_office.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_master_office_city_id" class="form-group master_office_city_id">
<span<?= $Grid->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city_id->getDisplayValue($Grid->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="master_office" data-field="x_city_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_city_id" id="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_office" data-field="x_city_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_city_id" id="o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->phone_number->Visible) { // phone_number ?>
        <td data-name="phone_number">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_master_office_phone_number" class="form-group master_office_phone_number">
<input type="<?= $Grid->phone_number->getInputTextType() ?>" data-table="master_office" data-field="x_phone_number" name="x<?= $Grid->RowIndex ?>_phone_number" id="x<?= $Grid->RowIndex ?>_phone_number" size="30" maxlength="20" placeholder="<?= HtmlEncode($Grid->phone_number->getPlaceHolder()) ?>" value="<?= $Grid->phone_number->EditValue ?>"<?= $Grid->phone_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->phone_number->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_master_office_phone_number" class="form-group master_office_phone_number">
<span<?= $Grid->phone_number->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->phone_number->getDisplayValue($Grid->phone_number->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="master_office" data-field="x_phone_number" data-hidden="1" name="x<?= $Grid->RowIndex ?>_phone_number" id="x<?= $Grid->RowIndex ?>_phone_number" value="<?= HtmlEncode($Grid->phone_number->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_office" data-field="x_phone_number" data-hidden="1" name="o<?= $Grid->RowIndex ?>_phone_number" id="o<?= $Grid->RowIndex ?>_phone_number" value="<?= HtmlEncode($Grid->phone_number->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->contact_name->Visible) { // contact_name ?>
        <td data-name="contact_name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_master_office_contact_name" class="form-group master_office_contact_name">
<input type="<?= $Grid->contact_name->getInputTextType() ?>" data-table="master_office" data-field="x_contact_name" name="x<?= $Grid->RowIndex ?>_contact_name" id="x<?= $Grid->RowIndex ?>_contact_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Grid->contact_name->getPlaceHolder()) ?>" value="<?= $Grid->contact_name->EditValue ?>"<?= $Grid->contact_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->contact_name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_master_office_contact_name" class="form-group master_office_contact_name">
<span<?= $Grid->contact_name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->contact_name->getDisplayValue($Grid->contact_name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="master_office" data-field="x_contact_name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_contact_name" id="x<?= $Grid->RowIndex ?>_contact_name" value="<?= HtmlEncode($Grid->contact_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_office" data-field="x_contact_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_contact_name" id="o<?= $Grid->RowIndex ?>_contact_name" value="<?= HtmlEncode($Grid->contact_name->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->description->Visible) { // description ?>
        <td data-name="description">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_master_office_description" class="form-group master_office_description">
<textarea data-table="master_office" data-field="x_description" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->description->getPlaceHolder()) ?>"<?= $Grid->description->editAttributes() ?>><?= $Grid->description->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->description->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_master_office_description" class="form-group master_office_description">
<span<?= $Grid->description->viewAttributes() ?>>
<?= $Grid->description->ViewValue ?></span>
</span>
<input type="hidden" data-table="master_office" data-field="x_description" data-hidden="1" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="master_office" data-field="x_description" data-hidden="1" name="o<?= $Grid->RowIndex ?>_description" id="o<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fmaster_officegrid","load"], function() {
    fmaster_officegrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fmaster_officegrid">
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
    ew.addEventHandlers("master_office");
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
        container: "gmp_master_office",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
