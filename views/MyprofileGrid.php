<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("MyprofileGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.myprofile) ew.vars.tables.myprofile = <?= JsonEncode(GetClientVar("tables", "myprofile")) ?>;
var currentForm, currentPageID;
var fmyprofilegrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fmyprofilegrid = new ew.Form("fmyprofilegrid", "grid");
    fmyprofilegrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.myprofile.fields;
    fmyprofilegrid.addFields([
        ["employee_name", [fields.employee_name.required ? ew.Validators.required(fields.employee_name.caption) : null], fields.employee_name.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["employee_password", [fields.employee_password.required ? ew.Validators.required(fields.employee_password.caption) : null], fields.employee_password.isInvalid],
        ["employee_email", [fields.employee_email.required ? ew.Validators.required(fields.employee_email.caption) : null], fields.employee_email.isInvalid],
        ["birth_date", [fields.birth_date.required ? ew.Validators.required(fields.birth_date.caption) : null, ew.Validators.datetime(5)], fields.birth_date.isInvalid],
        ["nik", [fields.nik.required ? ew.Validators.required(fields.nik.caption) : null], fields.nik.isInvalid],
        ["npwp", [fields.npwp.required ? ew.Validators.required(fields.npwp.caption) : null], fields.npwp.isInvalid],
        ["address", [fields.address.required ? ew.Validators.required(fields.address.caption) : null], fields.address.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["postal_code", [fields.postal_code.required ? ew.Validators.required(fields.postal_code.caption) : null], fields.postal_code.isInvalid],
        ["bank_number", [fields.bank_number.required ? ew.Validators.required(fields.bank_number.caption) : null], fields.bank_number.isInvalid],
        ["bank_name", [fields.bank_name.required ? ew.Validators.required(fields.bank_name.caption) : null], fields.bank_name.isInvalid],
        ["scan_ktp", [fields.scan_ktp.required ? ew.Validators.fileRequired(fields.scan_ktp.caption) : null], fields.scan_ktp.isInvalid],
        ["scan_npwp", [fields.scan_npwp.required ? ew.Validators.fileRequired(fields.scan_npwp.caption) : null], fields.scan_npwp.isInvalid],
        ["curiculum_vitae", [fields.curiculum_vitae.required ? ew.Validators.fileRequired(fields.curiculum_vitae.caption) : null], fields.curiculum_vitae.isInvalid],
        ["technical_skill", [fields.technical_skill.required ? ew.Validators.required(fields.technical_skill.caption) : null], fields.technical_skill.isInvalid],
        ["about_me", [fields.about_me.required ? ew.Validators.required(fields.about_me.caption) : null], fields.about_me.isInvalid],
        ["position_id", [fields.position_id.required ? ew.Validators.required(fields.position_id.caption) : null], fields.position_id.isInvalid],
        ["religion", [fields.religion.required ? ew.Validators.required(fields.religion.caption) : null], fields.religion.isInvalid],
        ["status_id", [fields.status_id.required ? ew.Validators.required(fields.status_id.caption) : null], fields.status_id.isInvalid],
        ["skill_id", [fields.skill_id.required ? ew.Validators.required(fields.skill_id.caption) : null], fields.skill_id.isInvalid],
        ["office_id", [fields.office_id.required ? ew.Validators.required(fields.office_id.caption) : null], fields.office_id.isInvalid],
        ["hire_date", [fields.hire_date.required ? ew.Validators.required(fields.hire_date.caption) : null, ew.Validators.datetime(5)], fields.hire_date.isInvalid],
        ["termination_date", [fields.termination_date.required ? ew.Validators.required(fields.termination_date.caption) : null, ew.Validators.datetime(5)], fields.termination_date.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmyprofilegrid,
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
    fmyprofilegrid.validate = function () {
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
    fmyprofilegrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "employee_name", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "employee_username", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "employee_password", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "employee_email", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "birth_date", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "nik", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "npwp", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "address", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "city_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "postal_code", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "bank_number", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "bank_name", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "scan_ktp", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "scan_npwp", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "curiculum_vitae", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "technical_skill", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "about_me", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "position_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "religion", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "status_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "skill_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "office_id", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "hire_date", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "termination_date", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fmyprofilegrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmyprofilegrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmyprofilegrid.lists.city_id = <?= $Grid->city_id->toClientList($Grid) ?>;
    fmyprofilegrid.lists.position_id = <?= $Grid->position_id->toClientList($Grid) ?>;
    fmyprofilegrid.lists.religion = <?= $Grid->religion->toClientList($Grid) ?>;
    fmyprofilegrid.lists.status_id = <?= $Grid->status_id->toClientList($Grid) ?>;
    fmyprofilegrid.lists.skill_id = <?= $Grid->skill_id->toClientList($Grid) ?>;
    fmyprofilegrid.lists.office_id = <?= $Grid->office_id->toClientList($Grid) ?>;
    loadjs.done("fmyprofilegrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> myprofile">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fmyprofilegrid" class="ew-form ew-list-form form-inline">
<div id="gmp_myprofile" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_myprofilegrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->employee_name->Visible) { // employee_name ?>
        <th data-name="employee_name" class="<?= $Grid->employee_name->headerCellClass() ?>"><div id="elh_myprofile_employee_name" class="myprofile_employee_name"><?= $Grid->renderSort($Grid->employee_name) ?></div></th>
<?php } ?>
<?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <th data-name="employee_username" class="<?= $Grid->employee_username->headerCellClass() ?>"><div id="elh_myprofile_employee_username" class="myprofile_employee_username"><?= $Grid->renderSort($Grid->employee_username) ?></div></th>
<?php } ?>
<?php if ($Grid->employee_password->Visible) { // employee_password ?>
        <th data-name="employee_password" class="<?= $Grid->employee_password->headerCellClass() ?>"><div id="elh_myprofile_employee_password" class="myprofile_employee_password"><?= $Grid->renderSort($Grid->employee_password) ?></div></th>
<?php } ?>
<?php if ($Grid->employee_email->Visible) { // employee_email ?>
        <th data-name="employee_email" class="<?= $Grid->employee_email->headerCellClass() ?>"><div id="elh_myprofile_employee_email" class="myprofile_employee_email"><?= $Grid->renderSort($Grid->employee_email) ?></div></th>
<?php } ?>
<?php if ($Grid->birth_date->Visible) { // birth_date ?>
        <th data-name="birth_date" class="<?= $Grid->birth_date->headerCellClass() ?>"><div id="elh_myprofile_birth_date" class="myprofile_birth_date"><?= $Grid->renderSort($Grid->birth_date) ?></div></th>
<?php } ?>
<?php if ($Grid->nik->Visible) { // nik ?>
        <th data-name="nik" class="<?= $Grid->nik->headerCellClass() ?>"><div id="elh_myprofile_nik" class="myprofile_nik"><?= $Grid->renderSort($Grid->nik) ?></div></th>
<?php } ?>
<?php if ($Grid->npwp->Visible) { // npwp ?>
        <th data-name="npwp" class="<?= $Grid->npwp->headerCellClass() ?>"><div id="elh_myprofile_npwp" class="myprofile_npwp"><?= $Grid->renderSort($Grid->npwp) ?></div></th>
<?php } ?>
<?php if ($Grid->address->Visible) { // address ?>
        <th data-name="address" class="<?= $Grid->address->headerCellClass() ?>" style="white-space: nowrap;"><div id="elh_myprofile_address" class="myprofile_address"><?= $Grid->renderSort($Grid->address) ?></div></th>
<?php } ?>
<?php if ($Grid->city_id->Visible) { // city_id ?>
        <th data-name="city_id" class="<?= $Grid->city_id->headerCellClass() ?>"><div id="elh_myprofile_city_id" class="myprofile_city_id"><?= $Grid->renderSort($Grid->city_id) ?></div></th>
<?php } ?>
<?php if ($Grid->postal_code->Visible) { // postal_code ?>
        <th data-name="postal_code" class="<?= $Grid->postal_code->headerCellClass() ?>"><div id="elh_myprofile_postal_code" class="myprofile_postal_code"><?= $Grid->renderSort($Grid->postal_code) ?></div></th>
<?php } ?>
<?php if ($Grid->bank_number->Visible) { // bank_number ?>
        <th data-name="bank_number" class="<?= $Grid->bank_number->headerCellClass() ?>"><div id="elh_myprofile_bank_number" class="myprofile_bank_number"><?= $Grid->renderSort($Grid->bank_number) ?></div></th>
<?php } ?>
<?php if ($Grid->bank_name->Visible) { // bank_name ?>
        <th data-name="bank_name" class="<?= $Grid->bank_name->headerCellClass() ?>"><div id="elh_myprofile_bank_name" class="myprofile_bank_name"><?= $Grid->renderSort($Grid->bank_name) ?></div></th>
<?php } ?>
<?php if ($Grid->scan_ktp->Visible) { // scan_ktp ?>
        <th data-name="scan_ktp" class="<?= $Grid->scan_ktp->headerCellClass() ?>"><div id="elh_myprofile_scan_ktp" class="myprofile_scan_ktp"><?= $Grid->renderSort($Grid->scan_ktp) ?></div></th>
<?php } ?>
<?php if ($Grid->scan_npwp->Visible) { // scan_npwp ?>
        <th data-name="scan_npwp" class="<?= $Grid->scan_npwp->headerCellClass() ?>"><div id="elh_myprofile_scan_npwp" class="myprofile_scan_npwp"><?= $Grid->renderSort($Grid->scan_npwp) ?></div></th>
<?php } ?>
<?php if ($Grid->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <th data-name="curiculum_vitae" class="<?= $Grid->curiculum_vitae->headerCellClass() ?>"><div id="elh_myprofile_curiculum_vitae" class="myprofile_curiculum_vitae"><?= $Grid->renderSort($Grid->curiculum_vitae) ?></div></th>
<?php } ?>
<?php if ($Grid->technical_skill->Visible) { // technical_skill ?>
        <th data-name="technical_skill" class="<?= $Grid->technical_skill->headerCellClass() ?>"><div id="elh_myprofile_technical_skill" class="myprofile_technical_skill"><?= $Grid->renderSort($Grid->technical_skill) ?></div></th>
<?php } ?>
<?php if ($Grid->about_me->Visible) { // about_me ?>
        <th data-name="about_me" class="<?= $Grid->about_me->headerCellClass() ?>"><div id="elh_myprofile_about_me" class="myprofile_about_me"><?= $Grid->renderSort($Grid->about_me) ?></div></th>
<?php } ?>
<?php if ($Grid->position_id->Visible) { // position_id ?>
        <th data-name="position_id" class="<?= $Grid->position_id->headerCellClass() ?>"><div id="elh_myprofile_position_id" class="myprofile_position_id"><?= $Grid->renderSort($Grid->position_id) ?></div></th>
<?php } ?>
<?php if ($Grid->religion->Visible) { // religion ?>
        <th data-name="religion" class="<?= $Grid->religion->headerCellClass() ?>"><div id="elh_myprofile_religion" class="myprofile_religion"><?= $Grid->renderSort($Grid->religion) ?></div></th>
<?php } ?>
<?php if ($Grid->status_id->Visible) { // status_id ?>
        <th data-name="status_id" class="<?= $Grid->status_id->headerCellClass() ?>"><div id="elh_myprofile_status_id" class="myprofile_status_id"><?= $Grid->renderSort($Grid->status_id) ?></div></th>
<?php } ?>
<?php if ($Grid->skill_id->Visible) { // skill_id ?>
        <th data-name="skill_id" class="<?= $Grid->skill_id->headerCellClass() ?>"><div id="elh_myprofile_skill_id" class="myprofile_skill_id"><?= $Grid->renderSort($Grid->skill_id) ?></div></th>
<?php } ?>
<?php if ($Grid->office_id->Visible) { // office_id ?>
        <th data-name="office_id" class="<?= $Grid->office_id->headerCellClass() ?>"><div id="elh_myprofile_office_id" class="myprofile_office_id"><?= $Grid->renderSort($Grid->office_id) ?></div></th>
<?php } ?>
<?php if ($Grid->hire_date->Visible) { // hire_date ?>
        <th data-name="hire_date" class="<?= $Grid->hire_date->headerCellClass() ?>"><div id="elh_myprofile_hire_date" class="myprofile_hire_date"><?= $Grid->renderSort($Grid->hire_date) ?></div></th>
<?php } ?>
<?php if ($Grid->termination_date->Visible) { // termination_date ?>
        <th data-name="termination_date" class="<?= $Grid->termination_date->headerCellClass() ?>"><div id="elh_myprofile_termination_date" class="myprofile_termination_date"><?= $Grid->renderSort($Grid->termination_date) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_myprofile", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->employee_name->Visible) { // employee_name ?>
        <td data-name="employee_name" <?= $Grid->employee_name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_name" class="form-group">
<input type="<?= $Grid->employee_name->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_name" name="x<?= $Grid->RowIndex ?>_employee_name" id="x<?= $Grid->RowIndex ?>_employee_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Grid->employee_name->getPlaceHolder()) ?>" value="<?= $Grid->employee_name->EditValue ?>"<?= $Grid->employee_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->employee_name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_employee_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_name" id="o<?= $Grid->RowIndex ?>_employee_name" value="<?= HtmlEncode($Grid->employee_name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_name" class="form-group">
<input type="<?= $Grid->employee_name->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_name" name="x<?= $Grid->RowIndex ?>_employee_name" id="x<?= $Grid->RowIndex ?>_employee_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Grid->employee_name->getPlaceHolder()) ?>" value="<?= $Grid->employee_name->EditValue ?>"<?= $Grid->employee_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->employee_name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_name">
<span<?= $Grid->employee_name->viewAttributes() ?>>
<?= $Grid->employee_name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_employee_name" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_employee_name" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_employee_name" value="<?= HtmlEncode($Grid->employee_name->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_employee_name" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_employee_name" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_employee_name" value="<?= HtmlEncode($Grid->employee_name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username" <?= $Grid->employee_username->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_username" class="form-group">
<input type="<?= $Grid->employee_username->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->employee_username->getPlaceHolder()) ?>" value="<?= $Grid->employee_username->EditValue ?>"<?= $Grid->employee_username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->employee_username->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<input type="<?= $Grid->employee_username->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->employee_username->getPlaceHolder()) ?>" value="<?= $Grid->employee_username->EditValue ?>"<?= $Grid->employee_username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->employee_username->getErrorMessage() ?></div>
<input type="hidden" data-table="myprofile" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue ?? $Grid->employee_username->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<?= $Grid->employee_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_employee_username" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_employee_username" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_employee_username" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_employee_username" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="myprofile" data-field="x_employee_username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->employee_password->Visible) { // employee_password ?>
        <td data-name="employee_password" <?= $Grid->employee_password->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_password" class="form-group">
<div class="input-group">
    <input type="password" name="x<?= $Grid->RowIndex ?>_employee_password" id="x<?= $Grid->RowIndex ?>_employee_password" autocomplete="new-password" data-field="x_employee_password" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->employee_password->getPlaceHolder()) ?>"<?= $Grid->employee_password->editAttributes() ?>>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div>
</div>
<div class="invalid-feedback"><?= $Grid->employee_password->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_employee_password" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_password" id="o<?= $Grid->RowIndex ?>_employee_password" value="<?= HtmlEncode($Grid->employee_password->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_password" class="form-group">
<div class="input-group">
    <input type="password" name="x<?= $Grid->RowIndex ?>_employee_password" id="x<?= $Grid->RowIndex ?>_employee_password" autocomplete="new-password" data-field="x_employee_password" value="<?= $Grid->employee_password->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->employee_password->getPlaceHolder()) ?>"<?= $Grid->employee_password->editAttributes() ?>>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div>
</div>
<div class="invalid-feedback"><?= $Grid->employee_password->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_password">
<span<?= $Grid->employee_password->viewAttributes() ?>>
<?= $Grid->employee_password->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_employee_password" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_employee_password" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_employee_password" value="<?= HtmlEncode($Grid->employee_password->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_employee_password" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_employee_password" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_employee_password" value="<?= HtmlEncode($Grid->employee_password->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->employee_email->Visible) { // employee_email ?>
        <td data-name="employee_email" <?= $Grid->employee_email->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_email" class="form-group">
<input type="<?= $Grid->employee_email->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_email" name="x<?= $Grid->RowIndex ?>_employee_email" id="x<?= $Grid->RowIndex ?>_employee_email" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->employee_email->getPlaceHolder()) ?>" value="<?= $Grid->employee_email->EditValue ?>"<?= $Grid->employee_email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->employee_email->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_employee_email" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_email" id="o<?= $Grid->RowIndex ?>_employee_email" value="<?= HtmlEncode($Grid->employee_email->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_email" class="form-group">
<input type="<?= $Grid->employee_email->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_email" name="x<?= $Grid->RowIndex ?>_employee_email" id="x<?= $Grid->RowIndex ?>_employee_email" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->employee_email->getPlaceHolder()) ?>" value="<?= $Grid->employee_email->EditValue ?>"<?= $Grid->employee_email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->employee_email->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_employee_email">
<span<?= $Grid->employee_email->viewAttributes() ?>>
<?= $Grid->employee_email->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_employee_email" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_employee_email" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_employee_email" value="<?= HtmlEncode($Grid->employee_email->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_employee_email" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_employee_email" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_employee_email" value="<?= HtmlEncode($Grid->employee_email->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->birth_date->Visible) { // birth_date ?>
        <td data-name="birth_date" <?= $Grid->birth_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_birth_date" class="form-group">
<input type="<?= $Grid->birth_date->getInputTextType() ?>" data-table="myprofile" data-field="x_birth_date" data-format="5" name="x<?= $Grid->RowIndex ?>_birth_date" id="x<?= $Grid->RowIndex ?>_birth_date" placeholder="<?= HtmlEncode($Grid->birth_date->getPlaceHolder()) ?>" value="<?= $Grid->birth_date->EditValue ?>"<?= $Grid->birth_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->birth_date->getErrorMessage() ?></div>
<?php if (!$Grid->birth_date->ReadOnly && !$Grid->birth_date->Disabled && !isset($Grid->birth_date->EditAttrs["readonly"]) && !isset($Grid->birth_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilegrid", "x<?= $Grid->RowIndex ?>_birth_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="myprofile" data-field="x_birth_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_birth_date" id="o<?= $Grid->RowIndex ?>_birth_date" value="<?= HtmlEncode($Grid->birth_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_birth_date" class="form-group">
<input type="<?= $Grid->birth_date->getInputTextType() ?>" data-table="myprofile" data-field="x_birth_date" data-format="5" name="x<?= $Grid->RowIndex ?>_birth_date" id="x<?= $Grid->RowIndex ?>_birth_date" placeholder="<?= HtmlEncode($Grid->birth_date->getPlaceHolder()) ?>" value="<?= $Grid->birth_date->EditValue ?>"<?= $Grid->birth_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->birth_date->getErrorMessage() ?></div>
<?php if (!$Grid->birth_date->ReadOnly && !$Grid->birth_date->Disabled && !isset($Grid->birth_date->EditAttrs["readonly"]) && !isset($Grid->birth_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilegrid", "x<?= $Grid->RowIndex ?>_birth_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_birth_date">
<span<?= $Grid->birth_date->viewAttributes() ?>>
<?= $Grid->birth_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_birth_date" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_birth_date" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_birth_date" value="<?= HtmlEncode($Grid->birth_date->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_birth_date" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_birth_date" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_birth_date" value="<?= HtmlEncode($Grid->birth_date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->nik->Visible) { // nik ?>
        <td data-name="nik" <?= $Grid->nik->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_nik" class="form-group">
<input type="<?= $Grid->nik->getInputTextType() ?>" data-table="myprofile" data-field="x_nik" name="x<?= $Grid->RowIndex ?>_nik" id="x<?= $Grid->RowIndex ?>_nik" size="30" maxlength="150" placeholder="<?= HtmlEncode($Grid->nik->getPlaceHolder()) ?>" value="<?= $Grid->nik->EditValue ?>"<?= $Grid->nik->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nik->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_nik" data-hidden="1" name="o<?= $Grid->RowIndex ?>_nik" id="o<?= $Grid->RowIndex ?>_nik" value="<?= HtmlEncode($Grid->nik->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_nik" class="form-group">
<input type="<?= $Grid->nik->getInputTextType() ?>" data-table="myprofile" data-field="x_nik" name="x<?= $Grid->RowIndex ?>_nik" id="x<?= $Grid->RowIndex ?>_nik" size="30" maxlength="150" placeholder="<?= HtmlEncode($Grid->nik->getPlaceHolder()) ?>" value="<?= $Grid->nik->EditValue ?>"<?= $Grid->nik->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nik->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_nik">
<span<?= $Grid->nik->viewAttributes() ?>>
<?= $Grid->nik->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_nik" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_nik" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_nik" value="<?= HtmlEncode($Grid->nik->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_nik" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_nik" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_nik" value="<?= HtmlEncode($Grid->nik->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->npwp->Visible) { // npwp ?>
        <td data-name="npwp" <?= $Grid->npwp->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_npwp" class="form-group">
<input type="<?= $Grid->npwp->getInputTextType() ?>" data-table="myprofile" data-field="x_npwp" name="x<?= $Grid->RowIndex ?>_npwp" id="x<?= $Grid->RowIndex ?>_npwp" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->npwp->getPlaceHolder()) ?>" value="<?= $Grid->npwp->EditValue ?>"<?= $Grid->npwp->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->npwp->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_npwp" data-hidden="1" name="o<?= $Grid->RowIndex ?>_npwp" id="o<?= $Grid->RowIndex ?>_npwp" value="<?= HtmlEncode($Grid->npwp->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_npwp" class="form-group">
<input type="<?= $Grid->npwp->getInputTextType() ?>" data-table="myprofile" data-field="x_npwp" name="x<?= $Grid->RowIndex ?>_npwp" id="x<?= $Grid->RowIndex ?>_npwp" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->npwp->getPlaceHolder()) ?>" value="<?= $Grid->npwp->EditValue ?>"<?= $Grid->npwp->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->npwp->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_npwp">
<span<?= $Grid->npwp->viewAttributes() ?>>
<?= $Grid->npwp->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_npwp" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_npwp" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_npwp" value="<?= HtmlEncode($Grid->npwp->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_npwp" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_npwp" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_npwp" value="<?= HtmlEncode($Grid->npwp->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->address->Visible) { // address ?>
        <td data-name="address" <?= $Grid->address->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_address" class="form-group">
<input type="<?= $Grid->address->getInputTextType() ?>" data-table="myprofile" data-field="x_address" name="x<?= $Grid->RowIndex ?>_address" id="x<?= $Grid->RowIndex ?>_address" size="30" maxlength="150" placeholder="<?= HtmlEncode($Grid->address->getPlaceHolder()) ?>" value="<?= $Grid->address->EditValue ?>"<?= $Grid->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->address->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_address" data-hidden="1" name="o<?= $Grid->RowIndex ?>_address" id="o<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_address" class="form-group">
<input type="<?= $Grid->address->getInputTextType() ?>" data-table="myprofile" data-field="x_address" name="x<?= $Grid->RowIndex ?>_address" id="x<?= $Grid->RowIndex ?>_address" size="30" maxlength="150" placeholder="<?= HtmlEncode($Grid->address->getPlaceHolder()) ?>" value="<?= $Grid->address->EditValue ?>"<?= $Grid->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->address->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_address">
<span<?= $Grid->address->viewAttributes() ?>>
<?= $Grid->address->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_address" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_address" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_address" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_address" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->city_id->Visible) { // city_id ?>
        <td data-name="city_id" <?= $Grid->city_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->city_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_city_id" class="form-group">
<span<?= $Grid->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city_id->getDisplayValue($Grid->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_city_id" name="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_city_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_city_id"
        name="x<?= $Grid->RowIndex ?>_city_id"
        class="form-control ew-select<?= $Grid->city_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_city_id"
        data-table="myprofile"
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
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_city_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_city_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_city_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_city_id" id="o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->city_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_city_id" class="form-group">
<span<?= $Grid->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city_id->getDisplayValue($Grid->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_city_id" name="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_city_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_city_id"
        name="x<?= $Grid->RowIndex ?>_city_id"
        class="form-control ew-select<?= $Grid->city_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_city_id"
        data-table="myprofile"
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
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_city_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_city_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_city_id">
<span<?= $Grid->city_id->viewAttributes() ?>>
<?= $Grid->city_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_city_id" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_city_id" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_city_id" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_city_id" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->postal_code->Visible) { // postal_code ?>
        <td data-name="postal_code" <?= $Grid->postal_code->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_postal_code" class="form-group">
<input type="<?= $Grid->postal_code->getInputTextType() ?>" data-table="myprofile" data-field="x_postal_code" name="x<?= $Grid->RowIndex ?>_postal_code" id="x<?= $Grid->RowIndex ?>_postal_code" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->postal_code->getPlaceHolder()) ?>" value="<?= $Grid->postal_code->EditValue ?>"<?= $Grid->postal_code->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->postal_code->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_postal_code" data-hidden="1" name="o<?= $Grid->RowIndex ?>_postal_code" id="o<?= $Grid->RowIndex ?>_postal_code" value="<?= HtmlEncode($Grid->postal_code->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_postal_code" class="form-group">
<input type="<?= $Grid->postal_code->getInputTextType() ?>" data-table="myprofile" data-field="x_postal_code" name="x<?= $Grid->RowIndex ?>_postal_code" id="x<?= $Grid->RowIndex ?>_postal_code" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->postal_code->getPlaceHolder()) ?>" value="<?= $Grid->postal_code->EditValue ?>"<?= $Grid->postal_code->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->postal_code->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_postal_code">
<span<?= $Grid->postal_code->viewAttributes() ?>>
<?= $Grid->postal_code->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_postal_code" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_postal_code" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_postal_code" value="<?= HtmlEncode($Grid->postal_code->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_postal_code" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_postal_code" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_postal_code" value="<?= HtmlEncode($Grid->postal_code->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->bank_number->Visible) { // bank_number ?>
        <td data-name="bank_number" <?= $Grid->bank_number->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_bank_number" class="form-group">
<input type="<?= $Grid->bank_number->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_number" name="x<?= $Grid->RowIndex ?>_bank_number" id="x<?= $Grid->RowIndex ?>_bank_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->bank_number->getPlaceHolder()) ?>" value="<?= $Grid->bank_number->EditValue ?>"<?= $Grid->bank_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->bank_number->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_bank_number" data-hidden="1" name="o<?= $Grid->RowIndex ?>_bank_number" id="o<?= $Grid->RowIndex ?>_bank_number" value="<?= HtmlEncode($Grid->bank_number->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_bank_number" class="form-group">
<input type="<?= $Grid->bank_number->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_number" name="x<?= $Grid->RowIndex ?>_bank_number" id="x<?= $Grid->RowIndex ?>_bank_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->bank_number->getPlaceHolder()) ?>" value="<?= $Grid->bank_number->EditValue ?>"<?= $Grid->bank_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->bank_number->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_bank_number">
<span<?= $Grid->bank_number->viewAttributes() ?>>
<?= $Grid->bank_number->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_bank_number" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_bank_number" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_bank_number" value="<?= HtmlEncode($Grid->bank_number->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_bank_number" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_bank_number" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_bank_number" value="<?= HtmlEncode($Grid->bank_number->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->bank_name->Visible) { // bank_name ?>
        <td data-name="bank_name" <?= $Grid->bank_name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_bank_name" class="form-group">
<input type="<?= $Grid->bank_name->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_name" name="x<?= $Grid->RowIndex ?>_bank_name" id="x<?= $Grid->RowIndex ?>_bank_name" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->bank_name->getPlaceHolder()) ?>" value="<?= $Grid->bank_name->EditValue ?>"<?= $Grid->bank_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->bank_name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_bank_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_bank_name" id="o<?= $Grid->RowIndex ?>_bank_name" value="<?= HtmlEncode($Grid->bank_name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_bank_name" class="form-group">
<input type="<?= $Grid->bank_name->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_name" name="x<?= $Grid->RowIndex ?>_bank_name" id="x<?= $Grid->RowIndex ?>_bank_name" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->bank_name->getPlaceHolder()) ?>" value="<?= $Grid->bank_name->EditValue ?>"<?= $Grid->bank_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->bank_name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_bank_name">
<span<?= $Grid->bank_name->viewAttributes() ?>>
<?= $Grid->bank_name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_bank_name" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_bank_name" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_bank_name" value="<?= HtmlEncode($Grid->bank_name->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_bank_name" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_bank_name" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_bank_name" value="<?= HtmlEncode($Grid->bank_name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->scan_ktp->Visible) { // scan_ktp ?>
        <td data-name="scan_ktp" <?= $Grid->scan_ktp->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_myprofile_scan_ktp" class="form-group myprofile_scan_ktp">
<div id="fd_x<?= $Grid->RowIndex ?>_scan_ktp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->scan_ktp->title() ?>" data-table="myprofile" data-field="x_scan_ktp" name="x<?= $Grid->RowIndex ?>_scan_ktp" id="x<?= $Grid->RowIndex ?>_scan_ktp" lang="<?= CurrentLanguageID() ?>"<?= $Grid->scan_ktp->editAttributes() ?><?= ($Grid->scan_ktp->ReadOnly || $Grid->scan_ktp->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_scan_ktp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->scan_ktp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fn_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= $Grid->scan_ktp->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fa_x<?= $Grid->RowIndex ?>_scan_ktp" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fs_x<?= $Grid->RowIndex ?>_scan_ktp" value="200">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fx_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= $Grid->scan_ktp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fm_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= $Grid->scan_ktp->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_scan_ktp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="myprofile" data-field="x_scan_ktp" data-hidden="1" name="o<?= $Grid->RowIndex ?>_scan_ktp" id="o<?= $Grid->RowIndex ?>_scan_ktp" value="<?= HtmlEncode($Grid->scan_ktp->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_scan_ktp">
<span<?= $Grid->scan_ktp->viewAttributes() ?>>
<?= GetFileViewTag($Grid->scan_ktp, $Grid->scan_ktp->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_scan_ktp" class="form-group myprofile_scan_ktp">
<div id="fd_x<?= $Grid->RowIndex ?>_scan_ktp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->scan_ktp->title() ?>" data-table="myprofile" data-field="x_scan_ktp" name="x<?= $Grid->RowIndex ?>_scan_ktp" id="x<?= $Grid->RowIndex ?>_scan_ktp" lang="<?= CurrentLanguageID() ?>"<?= $Grid->scan_ktp->editAttributes() ?><?= ($Grid->scan_ktp->ReadOnly || $Grid->scan_ktp->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_scan_ktp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->scan_ktp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fn_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= $Grid->scan_ktp->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fa_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_scan_ktp") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fs_x<?= $Grid->RowIndex ?>_scan_ktp" value="200">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fx_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= $Grid->scan_ktp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fm_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= $Grid->scan_ktp->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_scan_ktp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->scan_npwp->Visible) { // scan_npwp ?>
        <td data-name="scan_npwp" <?= $Grid->scan_npwp->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_myprofile_scan_npwp" class="form-group myprofile_scan_npwp">
<div id="fd_x<?= $Grid->RowIndex ?>_scan_npwp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->scan_npwp->title() ?>" data-table="myprofile" data-field="x_scan_npwp" name="x<?= $Grid->RowIndex ?>_scan_npwp" id="x<?= $Grid->RowIndex ?>_scan_npwp" lang="<?= CurrentLanguageID() ?>"<?= $Grid->scan_npwp->editAttributes() ?><?= ($Grid->scan_npwp->ReadOnly || $Grid->scan_npwp->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_scan_npwp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->scan_npwp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fn_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= $Grid->scan_npwp->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fa_x<?= $Grid->RowIndex ?>_scan_npwp" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fs_x<?= $Grid->RowIndex ?>_scan_npwp" value="250">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fx_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= $Grid->scan_npwp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fm_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= $Grid->scan_npwp->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_scan_npwp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="myprofile" data-field="x_scan_npwp" data-hidden="1" name="o<?= $Grid->RowIndex ?>_scan_npwp" id="o<?= $Grid->RowIndex ?>_scan_npwp" value="<?= HtmlEncode($Grid->scan_npwp->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_scan_npwp">
<span<?= $Grid->scan_npwp->viewAttributes() ?>>
<?= GetFileViewTag($Grid->scan_npwp, $Grid->scan_npwp->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_scan_npwp" class="form-group myprofile_scan_npwp">
<div id="fd_x<?= $Grid->RowIndex ?>_scan_npwp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->scan_npwp->title() ?>" data-table="myprofile" data-field="x_scan_npwp" name="x<?= $Grid->RowIndex ?>_scan_npwp" id="x<?= $Grid->RowIndex ?>_scan_npwp" lang="<?= CurrentLanguageID() ?>"<?= $Grid->scan_npwp->editAttributes() ?><?= ($Grid->scan_npwp->ReadOnly || $Grid->scan_npwp->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_scan_npwp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->scan_npwp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fn_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= $Grid->scan_npwp->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fa_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_scan_npwp") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fs_x<?= $Grid->RowIndex ?>_scan_npwp" value="250">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fx_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= $Grid->scan_npwp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fm_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= $Grid->scan_npwp->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_scan_npwp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <td data-name="curiculum_vitae" <?= $Grid->curiculum_vitae->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_myprofile_curiculum_vitae" class="form-group myprofile_curiculum_vitae">
<div id="fd_x<?= $Grid->RowIndex ?>_curiculum_vitae">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->curiculum_vitae->title() ?>" data-table="myprofile" data-field="x_curiculum_vitae" name="x<?= $Grid->RowIndex ?>_curiculum_vitae" id="x<?= $Grid->RowIndex ?>_curiculum_vitae" lang="<?= CurrentLanguageID() ?>"<?= $Grid->curiculum_vitae->editAttributes() ?><?= ($Grid->curiculum_vitae->ReadOnly || $Grid->curiculum_vitae->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_curiculum_vitae"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->curiculum_vitae->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fn_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= $Grid->curiculum_vitae->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fa_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fs_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="250">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fx_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= $Grid->curiculum_vitae->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fm_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= $Grid->curiculum_vitae->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_curiculum_vitae" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="myprofile" data-field="x_curiculum_vitae" data-hidden="1" name="o<?= $Grid->RowIndex ?>_curiculum_vitae" id="o<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= HtmlEncode($Grid->curiculum_vitae->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_curiculum_vitae">
<span<?= $Grid->curiculum_vitae->viewAttributes() ?>>
<?= GetFileViewTag($Grid->curiculum_vitae, $Grid->curiculum_vitae->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_curiculum_vitae" class="form-group myprofile_curiculum_vitae">
<div id="fd_x<?= $Grid->RowIndex ?>_curiculum_vitae">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->curiculum_vitae->title() ?>" data-table="myprofile" data-field="x_curiculum_vitae" name="x<?= $Grid->RowIndex ?>_curiculum_vitae" id="x<?= $Grid->RowIndex ?>_curiculum_vitae" lang="<?= CurrentLanguageID() ?>"<?= $Grid->curiculum_vitae->editAttributes() ?><?= ($Grid->curiculum_vitae->ReadOnly || $Grid->curiculum_vitae->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_curiculum_vitae"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->curiculum_vitae->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fn_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= $Grid->curiculum_vitae->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fa_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_curiculum_vitae") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fs_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="250">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fx_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= $Grid->curiculum_vitae->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fm_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= $Grid->curiculum_vitae->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_curiculum_vitae" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->technical_skill->Visible) { // technical_skill ?>
        <td data-name="technical_skill" <?= $Grid->technical_skill->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_technical_skill" class="form-group">
<textarea data-table="myprofile" data-field="x_technical_skill" name="x<?= $Grid->RowIndex ?>_technical_skill" id="x<?= $Grid->RowIndex ?>_technical_skill" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->technical_skill->getPlaceHolder()) ?>"<?= $Grid->technical_skill->editAttributes() ?>><?= $Grid->technical_skill->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->technical_skill->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_technical_skill" data-hidden="1" name="o<?= $Grid->RowIndex ?>_technical_skill" id="o<?= $Grid->RowIndex ?>_technical_skill" value="<?= HtmlEncode($Grid->technical_skill->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_technical_skill" class="form-group">
<textarea data-table="myprofile" data-field="x_technical_skill" name="x<?= $Grid->RowIndex ?>_technical_skill" id="x<?= $Grid->RowIndex ?>_technical_skill" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->technical_skill->getPlaceHolder()) ?>"<?= $Grid->technical_skill->editAttributes() ?>><?= $Grid->technical_skill->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->technical_skill->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_technical_skill">
<span<?= $Grid->technical_skill->viewAttributes() ?>>
<?= $Grid->technical_skill->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_technical_skill" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_technical_skill" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_technical_skill" value="<?= HtmlEncode($Grid->technical_skill->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_technical_skill" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_technical_skill" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_technical_skill" value="<?= HtmlEncode($Grid->technical_skill->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->about_me->Visible) { // about_me ?>
        <td data-name="about_me" <?= $Grid->about_me->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_about_me" class="form-group">
<textarea data-table="myprofile" data-field="x_about_me" name="x<?= $Grid->RowIndex ?>_about_me" id="x<?= $Grid->RowIndex ?>_about_me" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->about_me->getPlaceHolder()) ?>"<?= $Grid->about_me->editAttributes() ?>><?= $Grid->about_me->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->about_me->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="myprofile" data-field="x_about_me" data-hidden="1" name="o<?= $Grid->RowIndex ?>_about_me" id="o<?= $Grid->RowIndex ?>_about_me" value="<?= HtmlEncode($Grid->about_me->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_about_me" class="form-group">
<textarea data-table="myprofile" data-field="x_about_me" name="x<?= $Grid->RowIndex ?>_about_me" id="x<?= $Grid->RowIndex ?>_about_me" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->about_me->getPlaceHolder()) ?>"<?= $Grid->about_me->editAttributes() ?>><?= $Grid->about_me->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->about_me->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_about_me">
<span<?= $Grid->about_me->viewAttributes() ?>>
<?= $Grid->about_me->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_about_me" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_about_me" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_about_me" value="<?= HtmlEncode($Grid->about_me->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_about_me" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_about_me" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_about_me" value="<?= HtmlEncode($Grid->about_me->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->position_id->Visible) { // position_id ?>
        <td data-name="position_id" <?= $Grid->position_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->position_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_position_id" class="form-group">
<span<?= $Grid->position_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->position_id->getDisplayValue($Grid->position_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_position_id" name="x<?= $Grid->RowIndex ?>_position_id" value="<?= HtmlEncode($Grid->position_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_position_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_position_id"
        name="x<?= $Grid->RowIndex ?>_position_id"
        class="form-control ew-select<?= $Grid->position_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_position_id"
        data-table="myprofile"
        data-field="x_position_id"
        data-value-separator="<?= $Grid->position_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->position_id->getPlaceHolder()) ?>"
        <?= $Grid->position_id->editAttributes() ?>>
        <?= $Grid->position_id->selectOptionListHtml("x{$Grid->RowIndex}_position_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_position") && !$Grid->position_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_position_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->position_id->caption() ?>" data-title="<?= $Grid->position_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_position_id',url:'<?= GetUrl("masterpositionaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->position_id->getErrorMessage() ?></div>
<?= $Grid->position_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_position_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_position_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_position_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_position_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.position_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_position_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_position_id" id="o<?= $Grid->RowIndex ?>_position_id" value="<?= HtmlEncode($Grid->position_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->position_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_position_id" class="form-group">
<span<?= $Grid->position_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->position_id->getDisplayValue($Grid->position_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_position_id" name="x<?= $Grid->RowIndex ?>_position_id" value="<?= HtmlEncode($Grid->position_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_position_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_position_id"
        name="x<?= $Grid->RowIndex ?>_position_id"
        class="form-control ew-select<?= $Grid->position_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_position_id"
        data-table="myprofile"
        data-field="x_position_id"
        data-value-separator="<?= $Grid->position_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->position_id->getPlaceHolder()) ?>"
        <?= $Grid->position_id->editAttributes() ?>>
        <?= $Grid->position_id->selectOptionListHtml("x{$Grid->RowIndex}_position_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_position") && !$Grid->position_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_position_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->position_id->caption() ?>" data-title="<?= $Grid->position_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_position_id',url:'<?= GetUrl("masterpositionaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->position_id->getErrorMessage() ?></div>
<?= $Grid->position_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_position_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_position_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_position_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_position_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.position_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_position_id">
<span<?= $Grid->position_id->viewAttributes() ?>>
<?= $Grid->position_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_position_id" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_position_id" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_position_id" value="<?= HtmlEncode($Grid->position_id->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_position_id" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_position_id" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_position_id" value="<?= HtmlEncode($Grid->position_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->religion->Visible) { // religion ?>
        <td data-name="religion" <?= $Grid->religion->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_religion" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_religion"
        name="x<?= $Grid->RowIndex ?>_religion"
        class="form-control ew-select<?= $Grid->religion->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_religion"
        data-table="myprofile"
        data-field="x_religion"
        data-value-separator="<?= $Grid->religion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->religion->getPlaceHolder()) ?>"
        <?= $Grid->religion->editAttributes() ?>>
        <?= $Grid->religion->selectOptionListHtml("x{$Grid->RowIndex}_religion") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->religion->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_religion']"),
        options = { name: "x<?= $Grid->RowIndex ?>_religion", selectId: "myprofile_x<?= $Grid->RowIndex ?>_religion", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.myprofile.fields.religion.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.religion.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="myprofile" data-field="x_religion" data-hidden="1" name="o<?= $Grid->RowIndex ?>_religion" id="o<?= $Grid->RowIndex ?>_religion" value="<?= HtmlEncode($Grid->religion->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_religion" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_religion"
        name="x<?= $Grid->RowIndex ?>_religion"
        class="form-control ew-select<?= $Grid->religion->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_religion"
        data-table="myprofile"
        data-field="x_religion"
        data-value-separator="<?= $Grid->religion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->religion->getPlaceHolder()) ?>"
        <?= $Grid->religion->editAttributes() ?>>
        <?= $Grid->religion->selectOptionListHtml("x{$Grid->RowIndex}_religion") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->religion->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_religion']"),
        options = { name: "x<?= $Grid->RowIndex ?>_religion", selectId: "myprofile_x<?= $Grid->RowIndex ?>_religion", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.myprofile.fields.religion.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.religion.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_religion">
<span<?= $Grid->religion->viewAttributes() ?>>
<?= $Grid->religion->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_religion" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_religion" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_religion" value="<?= HtmlEncode($Grid->religion->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_religion" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_religion" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_religion" value="<?= HtmlEncode($Grid->religion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->status_id->Visible) { // status_id ?>
        <td data-name="status_id" <?= $Grid->status_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->status_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_status_id" class="form-group">
<span<?= $Grid->status_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->status_id->getDisplayValue($Grid->status_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_status_id" name="x<?= $Grid->RowIndex ?>_status_id" value="<?= HtmlEncode($Grid->status_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_status_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_status_id"
        name="x<?= $Grid->RowIndex ?>_status_id"
        class="form-control ew-select<?= $Grid->status_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_status_id"
        data-table="myprofile"
        data-field="x_status_id"
        data-value-separator="<?= $Grid->status_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->status_id->getPlaceHolder()) ?>"
        <?= $Grid->status_id->editAttributes() ?>>
        <?= $Grid->status_id->selectOptionListHtml("x{$Grid->RowIndex}_status_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_status") && !$Grid->status_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_status_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->status_id->caption() ?>" data-title="<?= $Grid->status_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_status_id',url:'<?= GetUrl("masterstatusaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->status_id->getErrorMessage() ?></div>
<?= $Grid->status_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_status_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_status_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_status_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_status_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.status_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_status_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_status_id" id="o<?= $Grid->RowIndex ?>_status_id" value="<?= HtmlEncode($Grid->status_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->status_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_status_id" class="form-group">
<span<?= $Grid->status_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->status_id->getDisplayValue($Grid->status_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_status_id" name="x<?= $Grid->RowIndex ?>_status_id" value="<?= HtmlEncode($Grid->status_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_status_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_status_id"
        name="x<?= $Grid->RowIndex ?>_status_id"
        class="form-control ew-select<?= $Grid->status_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_status_id"
        data-table="myprofile"
        data-field="x_status_id"
        data-value-separator="<?= $Grid->status_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->status_id->getPlaceHolder()) ?>"
        <?= $Grid->status_id->editAttributes() ?>>
        <?= $Grid->status_id->selectOptionListHtml("x{$Grid->RowIndex}_status_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_status") && !$Grid->status_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_status_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->status_id->caption() ?>" data-title="<?= $Grid->status_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_status_id',url:'<?= GetUrl("masterstatusaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->status_id->getErrorMessage() ?></div>
<?= $Grid->status_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_status_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_status_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_status_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_status_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.status_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_status_id">
<span<?= $Grid->status_id->viewAttributes() ?>>
<?= $Grid->status_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_status_id" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_status_id" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_status_id" value="<?= HtmlEncode($Grid->status_id->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_status_id" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_status_id" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_status_id" value="<?= HtmlEncode($Grid->status_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->skill_id->Visible) { // skill_id ?>
        <td data-name="skill_id" <?= $Grid->skill_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->skill_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_skill_id" class="form-group">
<span<?= $Grid->skill_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->skill_id->getDisplayValue($Grid->skill_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_skill_id" name="x<?= $Grid->RowIndex ?>_skill_id" value="<?= HtmlEncode($Grid->skill_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_skill_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_skill_id"
        name="x<?= $Grid->RowIndex ?>_skill_id"
        class="form-control ew-select<?= $Grid->skill_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_skill_id"
        data-table="myprofile"
        data-field="x_skill_id"
        data-value-separator="<?= $Grid->skill_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->skill_id->getPlaceHolder()) ?>"
        <?= $Grid->skill_id->editAttributes() ?>>
        <?= $Grid->skill_id->selectOptionListHtml("x{$Grid->RowIndex}_skill_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_skill") && !$Grid->skill_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_skill_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->skill_id->caption() ?>" data-title="<?= $Grid->skill_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_skill_id',url:'<?= GetUrl("masterskilladdopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->skill_id->getErrorMessage() ?></div>
<?= $Grid->skill_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_skill_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_skill_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_skill_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_skill_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.skill_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_skill_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_skill_id" id="o<?= $Grid->RowIndex ?>_skill_id" value="<?= HtmlEncode($Grid->skill_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->skill_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_skill_id" class="form-group">
<span<?= $Grid->skill_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->skill_id->getDisplayValue($Grid->skill_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_skill_id" name="x<?= $Grid->RowIndex ?>_skill_id" value="<?= HtmlEncode($Grid->skill_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_skill_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_skill_id"
        name="x<?= $Grid->RowIndex ?>_skill_id"
        class="form-control ew-select<?= $Grid->skill_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_skill_id"
        data-table="myprofile"
        data-field="x_skill_id"
        data-value-separator="<?= $Grid->skill_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->skill_id->getPlaceHolder()) ?>"
        <?= $Grid->skill_id->editAttributes() ?>>
        <?= $Grid->skill_id->selectOptionListHtml("x{$Grid->RowIndex}_skill_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_skill") && !$Grid->skill_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_skill_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->skill_id->caption() ?>" data-title="<?= $Grid->skill_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_skill_id',url:'<?= GetUrl("masterskilladdopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->skill_id->getErrorMessage() ?></div>
<?= $Grid->skill_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_skill_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_skill_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_skill_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_skill_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.skill_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_skill_id">
<span<?= $Grid->skill_id->viewAttributes() ?>>
<?= $Grid->skill_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_skill_id" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_skill_id" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_skill_id" value="<?= HtmlEncode($Grid->skill_id->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_skill_id" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_skill_id" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_skill_id" value="<?= HtmlEncode($Grid->skill_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->office_id->Visible) { // office_id ?>
        <td data-name="office_id" <?= $Grid->office_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->office_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_office_id" class="form-group">
<span<?= $Grid->office_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->office_id->getDisplayValue($Grid->office_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_office_id" name="x<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_office_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_office_id"
        name="x<?= $Grid->RowIndex ?>_office_id"
        class="form-control ew-select<?= $Grid->office_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_office_id"
        data-table="myprofile"
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
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_office_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_office_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.office_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_office_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_office_id" id="o<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->office_id->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_office_id" class="form-group">
<span<?= $Grid->office_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->office_id->getDisplayValue($Grid->office_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_office_id" name="x<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_office_id" class="form-group">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_office_id"
        name="x<?= $Grid->RowIndex ?>_office_id"
        class="form-control ew-select<?= $Grid->office_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_office_id"
        data-table="myprofile"
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
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_office_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_office_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.office_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_office_id">
<span<?= $Grid->office_id->viewAttributes() ?>>
<?= $Grid->office_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_office_id" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_office_id" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_office_id" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_office_id" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->hire_date->Visible) { // hire_date ?>
        <td data-name="hire_date" <?= $Grid->hire_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_hire_date" class="form-group">
<input type="<?= $Grid->hire_date->getInputTextType() ?>" data-table="myprofile" data-field="x_hire_date" data-format="5" name="x<?= $Grid->RowIndex ?>_hire_date" id="x<?= $Grid->RowIndex ?>_hire_date" placeholder="<?= HtmlEncode($Grid->hire_date->getPlaceHolder()) ?>" value="<?= $Grid->hire_date->EditValue ?>"<?= $Grid->hire_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->hire_date->getErrorMessage() ?></div>
<?php if (!$Grid->hire_date->ReadOnly && !$Grid->hire_date->Disabled && !isset($Grid->hire_date->EditAttrs["readonly"]) && !isset($Grid->hire_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilegrid", "x<?= $Grid->RowIndex ?>_hire_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="myprofile" data-field="x_hire_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_hire_date" id="o<?= $Grid->RowIndex ?>_hire_date" value="<?= HtmlEncode($Grid->hire_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_hire_date" class="form-group">
<input type="<?= $Grid->hire_date->getInputTextType() ?>" data-table="myprofile" data-field="x_hire_date" data-format="5" name="x<?= $Grid->RowIndex ?>_hire_date" id="x<?= $Grid->RowIndex ?>_hire_date" placeholder="<?= HtmlEncode($Grid->hire_date->getPlaceHolder()) ?>" value="<?= $Grid->hire_date->EditValue ?>"<?= $Grid->hire_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->hire_date->getErrorMessage() ?></div>
<?php if (!$Grid->hire_date->ReadOnly && !$Grid->hire_date->Disabled && !isset($Grid->hire_date->EditAttrs["readonly"]) && !isset($Grid->hire_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilegrid", "x<?= $Grid->RowIndex ?>_hire_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_hire_date">
<span<?= $Grid->hire_date->viewAttributes() ?>>
<?= $Grid->hire_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_hire_date" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_hire_date" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_hire_date" value="<?= HtmlEncode($Grid->hire_date->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_hire_date" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_hire_date" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_hire_date" value="<?= HtmlEncode($Grid->hire_date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->termination_date->Visible) { // termination_date ?>
        <td data-name="termination_date" <?= $Grid->termination_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_termination_date" class="form-group">
<input type="<?= $Grid->termination_date->getInputTextType() ?>" data-table="myprofile" data-field="x_termination_date" data-format="5" name="x<?= $Grid->RowIndex ?>_termination_date" id="x<?= $Grid->RowIndex ?>_termination_date" placeholder="<?= HtmlEncode($Grid->termination_date->getPlaceHolder()) ?>" value="<?= $Grid->termination_date->EditValue ?>"<?= $Grid->termination_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->termination_date->getErrorMessage() ?></div>
<?php if (!$Grid->termination_date->ReadOnly && !$Grid->termination_date->Disabled && !isset($Grid->termination_date->EditAttrs["readonly"]) && !isset($Grid->termination_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilegrid", "x<?= $Grid->RowIndex ?>_termination_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="myprofile" data-field="x_termination_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_termination_date" id="o<?= $Grid->RowIndex ?>_termination_date" value="<?= HtmlEncode($Grid->termination_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_termination_date" class="form-group">
<input type="<?= $Grid->termination_date->getInputTextType() ?>" data-table="myprofile" data-field="x_termination_date" data-format="5" name="x<?= $Grid->RowIndex ?>_termination_date" id="x<?= $Grid->RowIndex ?>_termination_date" placeholder="<?= HtmlEncode($Grid->termination_date->getPlaceHolder()) ?>" value="<?= $Grid->termination_date->EditValue ?>"<?= $Grid->termination_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->termination_date->getErrorMessage() ?></div>
<?php if (!$Grid->termination_date->ReadOnly && !$Grid->termination_date->Disabled && !isset($Grid->termination_date->EditAttrs["readonly"]) && !isset($Grid->termination_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilegrid", "x<?= $Grid->RowIndex ?>_termination_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_myprofile_termination_date">
<span<?= $Grid->termination_date->viewAttributes() ?>>
<?= $Grid->termination_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="myprofile" data-field="x_termination_date" data-hidden="1" name="fmyprofilegrid$x<?= $Grid->RowIndex ?>_termination_date" id="fmyprofilegrid$x<?= $Grid->RowIndex ?>_termination_date" value="<?= HtmlEncode($Grid->termination_date->FormValue) ?>">
<input type="hidden" data-table="myprofile" data-field="x_termination_date" data-hidden="1" name="fmyprofilegrid$o<?= $Grid->RowIndex ?>_termination_date" id="fmyprofilegrid$o<?= $Grid->RowIndex ?>_termination_date" value="<?= HtmlEncode($Grid->termination_date->OldValue) ?>">
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
loadjs.ready(["fmyprofilegrid","load"], function () {
    fmyprofilegrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_myprofile", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->employee_name->Visible) { // employee_name ?>
        <td data-name="employee_name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_employee_name" class="form-group myprofile_employee_name">
<input type="<?= $Grid->employee_name->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_name" name="x<?= $Grid->RowIndex ?>_employee_name" id="x<?= $Grid->RowIndex ?>_employee_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Grid->employee_name->getPlaceHolder()) ?>" value="<?= $Grid->employee_name->EditValue ?>"<?= $Grid->employee_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->employee_name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_employee_name" class="form-group myprofile_employee_name">
<span<?= $Grid->employee_name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_name->getDisplayValue($Grid->employee_name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_employee_name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_name" id="x<?= $Grid->RowIndex ?>_employee_name" value="<?= HtmlEncode($Grid->employee_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_employee_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_name" id="o<?= $Grid->RowIndex ?>_employee_name" value="<?= HtmlEncode($Grid->employee_name->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_employee_username" class="form-group myprofile_employee_username">
<input type="<?= $Grid->employee_username->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->employee_username->getPlaceHolder()) ?>" value="<?= $Grid->employee_username->EditValue ?>"<?= $Grid->employee_username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->employee_username->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_employee_username" class="form-group myprofile_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_employee_username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->employee_password->Visible) { // employee_password ?>
        <td data-name="employee_password">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_employee_password" class="form-group myprofile_employee_password">
<div class="input-group">
    <input type="password" name="x<?= $Grid->RowIndex ?>_employee_password" id="x<?= $Grid->RowIndex ?>_employee_password" autocomplete="new-password" data-field="x_employee_password" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->employee_password->getPlaceHolder()) ?>"<?= $Grid->employee_password->editAttributes() ?>>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div>
</div>
<div class="invalid-feedback"><?= $Grid->employee_password->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_employee_password" class="form-group myprofile_employee_password">
<span<?= $Grid->employee_password->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_password->getDisplayValue($Grid->employee_password->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_employee_password" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_password" id="x<?= $Grid->RowIndex ?>_employee_password" value="<?= HtmlEncode($Grid->employee_password->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_employee_password" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_password" id="o<?= $Grid->RowIndex ?>_employee_password" value="<?= HtmlEncode($Grid->employee_password->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->employee_email->Visible) { // employee_email ?>
        <td data-name="employee_email">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_employee_email" class="form-group myprofile_employee_email">
<input type="<?= $Grid->employee_email->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_email" name="x<?= $Grid->RowIndex ?>_employee_email" id="x<?= $Grid->RowIndex ?>_employee_email" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->employee_email->getPlaceHolder()) ?>" value="<?= $Grid->employee_email->EditValue ?>"<?= $Grid->employee_email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->employee_email->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_employee_email" class="form-group myprofile_employee_email">
<span<?= $Grid->employee_email->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_email->getDisplayValue($Grid->employee_email->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_employee_email" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_email" id="x<?= $Grid->RowIndex ?>_employee_email" value="<?= HtmlEncode($Grid->employee_email->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_employee_email" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_email" id="o<?= $Grid->RowIndex ?>_employee_email" value="<?= HtmlEncode($Grid->employee_email->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->birth_date->Visible) { // birth_date ?>
        <td data-name="birth_date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_birth_date" class="form-group myprofile_birth_date">
<input type="<?= $Grid->birth_date->getInputTextType() ?>" data-table="myprofile" data-field="x_birth_date" data-format="5" name="x<?= $Grid->RowIndex ?>_birth_date" id="x<?= $Grid->RowIndex ?>_birth_date" placeholder="<?= HtmlEncode($Grid->birth_date->getPlaceHolder()) ?>" value="<?= $Grid->birth_date->EditValue ?>"<?= $Grid->birth_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->birth_date->getErrorMessage() ?></div>
<?php if (!$Grid->birth_date->ReadOnly && !$Grid->birth_date->Disabled && !isset($Grid->birth_date->EditAttrs["readonly"]) && !isset($Grid->birth_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilegrid", "x<?= $Grid->RowIndex ?>_birth_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_birth_date" class="form-group myprofile_birth_date">
<span<?= $Grid->birth_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->birth_date->getDisplayValue($Grid->birth_date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_birth_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_birth_date" id="x<?= $Grid->RowIndex ?>_birth_date" value="<?= HtmlEncode($Grid->birth_date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_birth_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_birth_date" id="o<?= $Grid->RowIndex ?>_birth_date" value="<?= HtmlEncode($Grid->birth_date->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->nik->Visible) { // nik ?>
        <td data-name="nik">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_nik" class="form-group myprofile_nik">
<input type="<?= $Grid->nik->getInputTextType() ?>" data-table="myprofile" data-field="x_nik" name="x<?= $Grid->RowIndex ?>_nik" id="x<?= $Grid->RowIndex ?>_nik" size="30" maxlength="150" placeholder="<?= HtmlEncode($Grid->nik->getPlaceHolder()) ?>" value="<?= $Grid->nik->EditValue ?>"<?= $Grid->nik->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nik->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_nik" class="form-group myprofile_nik">
<span<?= $Grid->nik->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->nik->getDisplayValue($Grid->nik->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_nik" data-hidden="1" name="x<?= $Grid->RowIndex ?>_nik" id="x<?= $Grid->RowIndex ?>_nik" value="<?= HtmlEncode($Grid->nik->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_nik" data-hidden="1" name="o<?= $Grid->RowIndex ?>_nik" id="o<?= $Grid->RowIndex ?>_nik" value="<?= HtmlEncode($Grid->nik->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->npwp->Visible) { // npwp ?>
        <td data-name="npwp">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_npwp" class="form-group myprofile_npwp">
<input type="<?= $Grid->npwp->getInputTextType() ?>" data-table="myprofile" data-field="x_npwp" name="x<?= $Grid->RowIndex ?>_npwp" id="x<?= $Grid->RowIndex ?>_npwp" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->npwp->getPlaceHolder()) ?>" value="<?= $Grid->npwp->EditValue ?>"<?= $Grid->npwp->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->npwp->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_npwp" class="form-group myprofile_npwp">
<span<?= $Grid->npwp->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->npwp->getDisplayValue($Grid->npwp->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_npwp" data-hidden="1" name="x<?= $Grid->RowIndex ?>_npwp" id="x<?= $Grid->RowIndex ?>_npwp" value="<?= HtmlEncode($Grid->npwp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_npwp" data-hidden="1" name="o<?= $Grid->RowIndex ?>_npwp" id="o<?= $Grid->RowIndex ?>_npwp" value="<?= HtmlEncode($Grid->npwp->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->address->Visible) { // address ?>
        <td data-name="address">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_address" class="form-group myprofile_address">
<input type="<?= $Grid->address->getInputTextType() ?>" data-table="myprofile" data-field="x_address" name="x<?= $Grid->RowIndex ?>_address" id="x<?= $Grid->RowIndex ?>_address" size="30" maxlength="150" placeholder="<?= HtmlEncode($Grid->address->getPlaceHolder()) ?>" value="<?= $Grid->address->EditValue ?>"<?= $Grid->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->address->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_address" class="form-group myprofile_address">
<span<?= $Grid->address->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->address->getDisplayValue($Grid->address->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_address" data-hidden="1" name="x<?= $Grid->RowIndex ?>_address" id="x<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_address" data-hidden="1" name="o<?= $Grid->RowIndex ?>_address" id="o<?= $Grid->RowIndex ?>_address" value="<?= HtmlEncode($Grid->address->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->city_id->Visible) { // city_id ?>
        <td data-name="city_id">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->city_id->getSessionValue() != "") { ?>
<span id="el$rowindex$_myprofile_city_id" class="form-group myprofile_city_id">
<span<?= $Grid->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city_id->getDisplayValue($Grid->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_city_id" name="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_myprofile_city_id" class="form-group myprofile_city_id">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_city_id"
        name="x<?= $Grid->RowIndex ?>_city_id"
        class="form-control ew-select<?= $Grid->city_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_city_id"
        data-table="myprofile"
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
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_city_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_city_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_myprofile_city_id" class="form-group myprofile_city_id">
<span<?= $Grid->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->city_id->getDisplayValue($Grid->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_city_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_city_id" id="x<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_city_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_city_id" id="o<?= $Grid->RowIndex ?>_city_id" value="<?= HtmlEncode($Grid->city_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->postal_code->Visible) { // postal_code ?>
        <td data-name="postal_code">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_postal_code" class="form-group myprofile_postal_code">
<input type="<?= $Grid->postal_code->getInputTextType() ?>" data-table="myprofile" data-field="x_postal_code" name="x<?= $Grid->RowIndex ?>_postal_code" id="x<?= $Grid->RowIndex ?>_postal_code" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->postal_code->getPlaceHolder()) ?>" value="<?= $Grid->postal_code->EditValue ?>"<?= $Grid->postal_code->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->postal_code->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_postal_code" class="form-group myprofile_postal_code">
<span<?= $Grid->postal_code->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->postal_code->getDisplayValue($Grid->postal_code->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_postal_code" data-hidden="1" name="x<?= $Grid->RowIndex ?>_postal_code" id="x<?= $Grid->RowIndex ?>_postal_code" value="<?= HtmlEncode($Grid->postal_code->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_postal_code" data-hidden="1" name="o<?= $Grid->RowIndex ?>_postal_code" id="o<?= $Grid->RowIndex ?>_postal_code" value="<?= HtmlEncode($Grid->postal_code->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->bank_number->Visible) { // bank_number ?>
        <td data-name="bank_number">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_bank_number" class="form-group myprofile_bank_number">
<input type="<?= $Grid->bank_number->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_number" name="x<?= $Grid->RowIndex ?>_bank_number" id="x<?= $Grid->RowIndex ?>_bank_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->bank_number->getPlaceHolder()) ?>" value="<?= $Grid->bank_number->EditValue ?>"<?= $Grid->bank_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->bank_number->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_bank_number" class="form-group myprofile_bank_number">
<span<?= $Grid->bank_number->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->bank_number->getDisplayValue($Grid->bank_number->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_bank_number" data-hidden="1" name="x<?= $Grid->RowIndex ?>_bank_number" id="x<?= $Grid->RowIndex ?>_bank_number" value="<?= HtmlEncode($Grid->bank_number->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_bank_number" data-hidden="1" name="o<?= $Grid->RowIndex ?>_bank_number" id="o<?= $Grid->RowIndex ?>_bank_number" value="<?= HtmlEncode($Grid->bank_number->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->bank_name->Visible) { // bank_name ?>
        <td data-name="bank_name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_bank_name" class="form-group myprofile_bank_name">
<input type="<?= $Grid->bank_name->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_name" name="x<?= $Grid->RowIndex ?>_bank_name" id="x<?= $Grid->RowIndex ?>_bank_name" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->bank_name->getPlaceHolder()) ?>" value="<?= $Grid->bank_name->EditValue ?>"<?= $Grid->bank_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->bank_name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_bank_name" class="form-group myprofile_bank_name">
<span<?= $Grid->bank_name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->bank_name->getDisplayValue($Grid->bank_name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_bank_name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_bank_name" id="x<?= $Grid->RowIndex ?>_bank_name" value="<?= HtmlEncode($Grid->bank_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_bank_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_bank_name" id="o<?= $Grid->RowIndex ?>_bank_name" value="<?= HtmlEncode($Grid->bank_name->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->scan_ktp->Visible) { // scan_ktp ?>
        <td data-name="scan_ktp">
<span id="el$rowindex$_myprofile_scan_ktp" class="form-group myprofile_scan_ktp">
<div id="fd_x<?= $Grid->RowIndex ?>_scan_ktp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->scan_ktp->title() ?>" data-table="myprofile" data-field="x_scan_ktp" name="x<?= $Grid->RowIndex ?>_scan_ktp" id="x<?= $Grid->RowIndex ?>_scan_ktp" lang="<?= CurrentLanguageID() ?>"<?= $Grid->scan_ktp->editAttributes() ?><?= ($Grid->scan_ktp->ReadOnly || $Grid->scan_ktp->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_scan_ktp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->scan_ktp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fn_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= $Grid->scan_ktp->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fa_x<?= $Grid->RowIndex ?>_scan_ktp" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fs_x<?= $Grid->RowIndex ?>_scan_ktp" value="200">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fx_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= $Grid->scan_ktp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_scan_ktp" id= "fm_x<?= $Grid->RowIndex ?>_scan_ktp" value="<?= $Grid->scan_ktp->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_scan_ktp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="myprofile" data-field="x_scan_ktp" data-hidden="1" name="o<?= $Grid->RowIndex ?>_scan_ktp" id="o<?= $Grid->RowIndex ?>_scan_ktp" value="<?= HtmlEncode($Grid->scan_ktp->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->scan_npwp->Visible) { // scan_npwp ?>
        <td data-name="scan_npwp">
<span id="el$rowindex$_myprofile_scan_npwp" class="form-group myprofile_scan_npwp">
<div id="fd_x<?= $Grid->RowIndex ?>_scan_npwp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->scan_npwp->title() ?>" data-table="myprofile" data-field="x_scan_npwp" name="x<?= $Grid->RowIndex ?>_scan_npwp" id="x<?= $Grid->RowIndex ?>_scan_npwp" lang="<?= CurrentLanguageID() ?>"<?= $Grid->scan_npwp->editAttributes() ?><?= ($Grid->scan_npwp->ReadOnly || $Grid->scan_npwp->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_scan_npwp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->scan_npwp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fn_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= $Grid->scan_npwp->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fa_x<?= $Grid->RowIndex ?>_scan_npwp" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fs_x<?= $Grid->RowIndex ?>_scan_npwp" value="250">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fx_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= $Grid->scan_npwp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_scan_npwp" id= "fm_x<?= $Grid->RowIndex ?>_scan_npwp" value="<?= $Grid->scan_npwp->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_scan_npwp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="myprofile" data-field="x_scan_npwp" data-hidden="1" name="o<?= $Grid->RowIndex ?>_scan_npwp" id="o<?= $Grid->RowIndex ?>_scan_npwp" value="<?= HtmlEncode($Grid->scan_npwp->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <td data-name="curiculum_vitae">
<span id="el$rowindex$_myprofile_curiculum_vitae" class="form-group myprofile_curiculum_vitae">
<div id="fd_x<?= $Grid->RowIndex ?>_curiculum_vitae">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->curiculum_vitae->title() ?>" data-table="myprofile" data-field="x_curiculum_vitae" name="x<?= $Grid->RowIndex ?>_curiculum_vitae" id="x<?= $Grid->RowIndex ?>_curiculum_vitae" lang="<?= CurrentLanguageID() ?>"<?= $Grid->curiculum_vitae->editAttributes() ?><?= ($Grid->curiculum_vitae->ReadOnly || $Grid->curiculum_vitae->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_curiculum_vitae"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->curiculum_vitae->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fn_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= $Grid->curiculum_vitae->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fa_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fs_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="250">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fx_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= $Grid->curiculum_vitae->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_curiculum_vitae" id= "fm_x<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= $Grid->curiculum_vitae->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_curiculum_vitae" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="myprofile" data-field="x_curiculum_vitae" data-hidden="1" name="o<?= $Grid->RowIndex ?>_curiculum_vitae" id="o<?= $Grid->RowIndex ?>_curiculum_vitae" value="<?= HtmlEncode($Grid->curiculum_vitae->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->technical_skill->Visible) { // technical_skill ?>
        <td data-name="technical_skill">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_technical_skill" class="form-group myprofile_technical_skill">
<textarea data-table="myprofile" data-field="x_technical_skill" name="x<?= $Grid->RowIndex ?>_technical_skill" id="x<?= $Grid->RowIndex ?>_technical_skill" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->technical_skill->getPlaceHolder()) ?>"<?= $Grid->technical_skill->editAttributes() ?>><?= $Grid->technical_skill->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->technical_skill->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_technical_skill" class="form-group myprofile_technical_skill">
<span<?= $Grid->technical_skill->viewAttributes() ?>>
<?= $Grid->technical_skill->ViewValue ?></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_technical_skill" data-hidden="1" name="x<?= $Grid->RowIndex ?>_technical_skill" id="x<?= $Grid->RowIndex ?>_technical_skill" value="<?= HtmlEncode($Grid->technical_skill->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_technical_skill" data-hidden="1" name="o<?= $Grid->RowIndex ?>_technical_skill" id="o<?= $Grid->RowIndex ?>_technical_skill" value="<?= HtmlEncode($Grid->technical_skill->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->about_me->Visible) { // about_me ?>
        <td data-name="about_me">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_about_me" class="form-group myprofile_about_me">
<textarea data-table="myprofile" data-field="x_about_me" name="x<?= $Grid->RowIndex ?>_about_me" id="x<?= $Grid->RowIndex ?>_about_me" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->about_me->getPlaceHolder()) ?>"<?= $Grid->about_me->editAttributes() ?>><?= $Grid->about_me->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->about_me->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_about_me" class="form-group myprofile_about_me">
<span<?= $Grid->about_me->viewAttributes() ?>>
<?= $Grid->about_me->ViewValue ?></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_about_me" data-hidden="1" name="x<?= $Grid->RowIndex ?>_about_me" id="x<?= $Grid->RowIndex ?>_about_me" value="<?= HtmlEncode($Grid->about_me->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_about_me" data-hidden="1" name="o<?= $Grid->RowIndex ?>_about_me" id="o<?= $Grid->RowIndex ?>_about_me" value="<?= HtmlEncode($Grid->about_me->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->position_id->Visible) { // position_id ?>
        <td data-name="position_id">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->position_id->getSessionValue() != "") { ?>
<span id="el$rowindex$_myprofile_position_id" class="form-group myprofile_position_id">
<span<?= $Grid->position_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->position_id->getDisplayValue($Grid->position_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_position_id" name="x<?= $Grid->RowIndex ?>_position_id" value="<?= HtmlEncode($Grid->position_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_myprofile_position_id" class="form-group myprofile_position_id">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_position_id"
        name="x<?= $Grid->RowIndex ?>_position_id"
        class="form-control ew-select<?= $Grid->position_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_position_id"
        data-table="myprofile"
        data-field="x_position_id"
        data-value-separator="<?= $Grid->position_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->position_id->getPlaceHolder()) ?>"
        <?= $Grid->position_id->editAttributes() ?>>
        <?= $Grid->position_id->selectOptionListHtml("x{$Grid->RowIndex}_position_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_position") && !$Grid->position_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_position_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->position_id->caption() ?>" data-title="<?= $Grid->position_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_position_id',url:'<?= GetUrl("masterpositionaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->position_id->getErrorMessage() ?></div>
<?= $Grid->position_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_position_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_position_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_position_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_position_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.position_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_myprofile_position_id" class="form-group myprofile_position_id">
<span<?= $Grid->position_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->position_id->getDisplayValue($Grid->position_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_position_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_position_id" id="x<?= $Grid->RowIndex ?>_position_id" value="<?= HtmlEncode($Grid->position_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_position_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_position_id" id="o<?= $Grid->RowIndex ?>_position_id" value="<?= HtmlEncode($Grid->position_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->religion->Visible) { // religion ?>
        <td data-name="religion">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_religion" class="form-group myprofile_religion">
    <select
        id="x<?= $Grid->RowIndex ?>_religion"
        name="x<?= $Grid->RowIndex ?>_religion"
        class="form-control ew-select<?= $Grid->religion->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_religion"
        data-table="myprofile"
        data-field="x_religion"
        data-value-separator="<?= $Grid->religion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->religion->getPlaceHolder()) ?>"
        <?= $Grid->religion->editAttributes() ?>>
        <?= $Grid->religion->selectOptionListHtml("x{$Grid->RowIndex}_religion") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->religion->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_religion']"),
        options = { name: "x<?= $Grid->RowIndex ?>_religion", selectId: "myprofile_x<?= $Grid->RowIndex ?>_religion", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.myprofile.fields.religion.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.religion.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_religion" class="form-group myprofile_religion">
<span<?= $Grid->religion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->religion->getDisplayValue($Grid->religion->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_religion" data-hidden="1" name="x<?= $Grid->RowIndex ?>_religion" id="x<?= $Grid->RowIndex ?>_religion" value="<?= HtmlEncode($Grid->religion->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_religion" data-hidden="1" name="o<?= $Grid->RowIndex ?>_religion" id="o<?= $Grid->RowIndex ?>_religion" value="<?= HtmlEncode($Grid->religion->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->status_id->Visible) { // status_id ?>
        <td data-name="status_id">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->status_id->getSessionValue() != "") { ?>
<span id="el$rowindex$_myprofile_status_id" class="form-group myprofile_status_id">
<span<?= $Grid->status_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->status_id->getDisplayValue($Grid->status_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_status_id" name="x<?= $Grid->RowIndex ?>_status_id" value="<?= HtmlEncode($Grid->status_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_myprofile_status_id" class="form-group myprofile_status_id">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_status_id"
        name="x<?= $Grid->RowIndex ?>_status_id"
        class="form-control ew-select<?= $Grid->status_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_status_id"
        data-table="myprofile"
        data-field="x_status_id"
        data-value-separator="<?= $Grid->status_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->status_id->getPlaceHolder()) ?>"
        <?= $Grid->status_id->editAttributes() ?>>
        <?= $Grid->status_id->selectOptionListHtml("x{$Grid->RowIndex}_status_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_status") && !$Grid->status_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_status_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->status_id->caption() ?>" data-title="<?= $Grid->status_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_status_id',url:'<?= GetUrl("masterstatusaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->status_id->getErrorMessage() ?></div>
<?= $Grid->status_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_status_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_status_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_status_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_status_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.status_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_myprofile_status_id" class="form-group myprofile_status_id">
<span<?= $Grid->status_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->status_id->getDisplayValue($Grid->status_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_status_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_status_id" id="x<?= $Grid->RowIndex ?>_status_id" value="<?= HtmlEncode($Grid->status_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_status_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_status_id" id="o<?= $Grid->RowIndex ?>_status_id" value="<?= HtmlEncode($Grid->status_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->skill_id->Visible) { // skill_id ?>
        <td data-name="skill_id">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->skill_id->getSessionValue() != "") { ?>
<span id="el$rowindex$_myprofile_skill_id" class="form-group myprofile_skill_id">
<span<?= $Grid->skill_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->skill_id->getDisplayValue($Grid->skill_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_skill_id" name="x<?= $Grid->RowIndex ?>_skill_id" value="<?= HtmlEncode($Grid->skill_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_myprofile_skill_id" class="form-group myprofile_skill_id">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_skill_id"
        name="x<?= $Grid->RowIndex ?>_skill_id"
        class="form-control ew-select<?= $Grid->skill_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_skill_id"
        data-table="myprofile"
        data-field="x_skill_id"
        data-value-separator="<?= $Grid->skill_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->skill_id->getPlaceHolder()) ?>"
        <?= $Grid->skill_id->editAttributes() ?>>
        <?= $Grid->skill_id->selectOptionListHtml("x{$Grid->RowIndex}_skill_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_skill") && !$Grid->skill_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x<?= $Grid->RowIndex ?>_skill_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Grid->skill_id->caption() ?>" data-title="<?= $Grid->skill_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x<?= $Grid->RowIndex ?>_skill_id',url:'<?= GetUrl("masterskilladdopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Grid->skill_id->getErrorMessage() ?></div>
<?= $Grid->skill_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_skill_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_skill_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_skill_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_skill_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.skill_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_myprofile_skill_id" class="form-group myprofile_skill_id">
<span<?= $Grid->skill_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->skill_id->getDisplayValue($Grid->skill_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_skill_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_skill_id" id="x<?= $Grid->RowIndex ?>_skill_id" value="<?= HtmlEncode($Grid->skill_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_skill_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_skill_id" id="o<?= $Grid->RowIndex ?>_skill_id" value="<?= HtmlEncode($Grid->skill_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->office_id->Visible) { // office_id ?>
        <td data-name="office_id">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->office_id->getSessionValue() != "") { ?>
<span id="el$rowindex$_myprofile_office_id" class="form-group myprofile_office_id">
<span<?= $Grid->office_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->office_id->getDisplayValue($Grid->office_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_office_id" name="x<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_myprofile_office_id" class="form-group myprofile_office_id">
<div class="input-group flex-nowrap">
    <select
        id="x<?= $Grid->RowIndex ?>_office_id"
        name="x<?= $Grid->RowIndex ?>_office_id"
        class="form-control ew-select<?= $Grid->office_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x<?= $Grid->RowIndex ?>_office_id"
        data-table="myprofile"
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
    var el = document.querySelector("select[data-select2-id='myprofile_x<?= $Grid->RowIndex ?>_office_id']"),
        options = { name: "x<?= $Grid->RowIndex ?>_office_id", selectId: "myprofile_x<?= $Grid->RowIndex ?>_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.office_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_myprofile_office_id" class="form-group myprofile_office_id">
<span<?= $Grid->office_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->office_id->getDisplayValue($Grid->office_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_office_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_office_id" id="x<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_office_id" data-hidden="1" name="o<?= $Grid->RowIndex ?>_office_id" id="o<?= $Grid->RowIndex ?>_office_id" value="<?= HtmlEncode($Grid->office_id->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->hire_date->Visible) { // hire_date ?>
        <td data-name="hire_date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_hire_date" class="form-group myprofile_hire_date">
<input type="<?= $Grid->hire_date->getInputTextType() ?>" data-table="myprofile" data-field="x_hire_date" data-format="5" name="x<?= $Grid->RowIndex ?>_hire_date" id="x<?= $Grid->RowIndex ?>_hire_date" placeholder="<?= HtmlEncode($Grid->hire_date->getPlaceHolder()) ?>" value="<?= $Grid->hire_date->EditValue ?>"<?= $Grid->hire_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->hire_date->getErrorMessage() ?></div>
<?php if (!$Grid->hire_date->ReadOnly && !$Grid->hire_date->Disabled && !isset($Grid->hire_date->EditAttrs["readonly"]) && !isset($Grid->hire_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilegrid", "x<?= $Grid->RowIndex ?>_hire_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_hire_date" class="form-group myprofile_hire_date">
<span<?= $Grid->hire_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->hire_date->getDisplayValue($Grid->hire_date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_hire_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_hire_date" id="x<?= $Grid->RowIndex ?>_hire_date" value="<?= HtmlEncode($Grid->hire_date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_hire_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_hire_date" id="o<?= $Grid->RowIndex ?>_hire_date" value="<?= HtmlEncode($Grid->hire_date->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->termination_date->Visible) { // termination_date ?>
        <td data-name="termination_date">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_myprofile_termination_date" class="form-group myprofile_termination_date">
<input type="<?= $Grid->termination_date->getInputTextType() ?>" data-table="myprofile" data-field="x_termination_date" data-format="5" name="x<?= $Grid->RowIndex ?>_termination_date" id="x<?= $Grid->RowIndex ?>_termination_date" placeholder="<?= HtmlEncode($Grid->termination_date->getPlaceHolder()) ?>" value="<?= $Grid->termination_date->EditValue ?>"<?= $Grid->termination_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->termination_date->getErrorMessage() ?></div>
<?php if (!$Grid->termination_date->ReadOnly && !$Grid->termination_date->Disabled && !isset($Grid->termination_date->EditAttrs["readonly"]) && !isset($Grid->termination_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilegrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilegrid", "x<?= $Grid->RowIndex ?>_termination_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_myprofile_termination_date" class="form-group myprofile_termination_date">
<span<?= $Grid->termination_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->termination_date->getDisplayValue($Grid->termination_date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="myprofile" data-field="x_termination_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_termination_date" id="x<?= $Grid->RowIndex ?>_termination_date" value="<?= HtmlEncode($Grid->termination_date->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="myprofile" data-field="x_termination_date" data-hidden="1" name="o<?= $Grid->RowIndex ?>_termination_date" id="o<?= $Grid->RowIndex ?>_termination_date" value="<?= HtmlEncode($Grid->termination_date->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fmyprofilegrid","load"], function() {
    fmyprofilegrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fmyprofilegrid">
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
    ew.addEventHandlers("myprofile");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
