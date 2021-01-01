<?php

namespace MEM\prjMitralPHP;

// Set up and run Grid object
$Grid = Container("EmployeeTimesheetGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.employee_timesheet) ew.vars.tables.employee_timesheet = <?= JsonEncode(GetClientVar("tables", "employee_timesheet")) ?>;
var currentForm, currentPageID;
var femployee_timesheetgrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    femployee_timesheetgrid = new ew.Form("femployee_timesheetgrid", "grid");
    femployee_timesheetgrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.employee_timesheet.fields;
    femployee_timesheetgrid.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["year", [fields.year.required ? ew.Validators.required(fields.year.caption) : null], fields.year.isInvalid],
        ["month", [fields.month.required ? ew.Validators.required(fields.month.caption) : null], fields.month.isInvalid],
        ["days", [fields.days.required ? ew.Validators.required(fields.days.caption) : null, ew.Validators.integer], fields.days.isInvalid],
        ["sick", [fields.sick.required ? ew.Validators.required(fields.sick.caption) : null, ew.Validators.integer], fields.sick.isInvalid],
        ["leave", [fields.leave.required ? ew.Validators.required(fields.leave.caption) : null, ew.Validators.integer], fields.leave.isInvalid],
        ["permit", [fields.permit.required ? ew.Validators.required(fields.permit.caption) : null, ew.Validators.integer], fields.permit.isInvalid],
        ["absence", [fields.absence.required ? ew.Validators.required(fields.absence.caption) : null, ew.Validators.integer], fields.absence.isInvalid],
        ["timesheet_doc", [fields.timesheet_doc.required ? ew.Validators.fileRequired(fields.timesheet_doc.caption) : null], fields.timesheet_doc.isInvalid],
        ["approved", [fields.approved.required ? ew.Validators.required(fields.approved.caption) : null], fields.approved.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_timesheetgrid,
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
    femployee_timesheetgrid.validate = function () {
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
    femployee_timesheetgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "employee_username", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "year", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "month", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "days", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "sick", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "leave", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "permit", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "absence", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "timesheet_doc", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "approved", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    femployee_timesheetgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_timesheetgrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_timesheetgrid.lists.employee_username = <?= $Grid->employee_username->toClientList($Grid) ?>;
    femployee_timesheetgrid.lists.year = <?= $Grid->year->toClientList($Grid) ?>;
    femployee_timesheetgrid.lists.month = <?= $Grid->month->toClientList($Grid) ?>;
    femployee_timesheetgrid.lists.approved = <?= $Grid->approved->toClientList($Grid) ?>;
    loadjs.done("femployee_timesheetgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> employee_timesheet">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="femployee_timesheetgrid" class="ew-form ew-list-form form-inline">
<div id="gmp_employee_timesheet" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_employee_timesheetgrid" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="employee_username" class="<?= $Grid->employee_username->headerCellClass() ?>"><div id="elh_employee_timesheet_employee_username" class="employee_timesheet_employee_username"><?= $Grid->renderSort($Grid->employee_username) ?></div></th>
<?php } ?>
<?php if ($Grid->year->Visible) { // year ?>
        <th data-name="year" class="<?= $Grid->year->headerCellClass() ?>"><div id="elh_employee_timesheet_year" class="employee_timesheet_year"><?= $Grid->renderSort($Grid->year) ?></div></th>
<?php } ?>
<?php if ($Grid->month->Visible) { // month ?>
        <th data-name="month" class="<?= $Grid->month->headerCellClass() ?>"><div id="elh_employee_timesheet_month" class="employee_timesheet_month"><?= $Grid->renderSort($Grid->month) ?></div></th>
<?php } ?>
<?php if ($Grid->days->Visible) { // days ?>
        <th data-name="days" class="<?= $Grid->days->headerCellClass() ?>"><div id="elh_employee_timesheet_days" class="employee_timesheet_days"><?= $Grid->renderSort($Grid->days) ?></div></th>
<?php } ?>
<?php if ($Grid->sick->Visible) { // sick ?>
        <th data-name="sick" class="<?= $Grid->sick->headerCellClass() ?>"><div id="elh_employee_timesheet_sick" class="employee_timesheet_sick"><?= $Grid->renderSort($Grid->sick) ?></div></th>
<?php } ?>
<?php if ($Grid->leave->Visible) { // leave ?>
        <th data-name="leave" class="<?= $Grid->leave->headerCellClass() ?>"><div id="elh_employee_timesheet_leave" class="employee_timesheet_leave"><?= $Grid->renderSort($Grid->leave) ?></div></th>
<?php } ?>
<?php if ($Grid->permit->Visible) { // permit ?>
        <th data-name="permit" class="<?= $Grid->permit->headerCellClass() ?>"><div id="elh_employee_timesheet_permit" class="employee_timesheet_permit"><?= $Grid->renderSort($Grid->permit) ?></div></th>
<?php } ?>
<?php if ($Grid->absence->Visible) { // absence ?>
        <th data-name="absence" class="<?= $Grid->absence->headerCellClass() ?>"><div id="elh_employee_timesheet_absence" class="employee_timesheet_absence"><?= $Grid->renderSort($Grid->absence) ?></div></th>
<?php } ?>
<?php if ($Grid->timesheet_doc->Visible) { // timesheet_doc ?>
        <th data-name="timesheet_doc" class="<?= $Grid->timesheet_doc->headerCellClass() ?>"><div id="elh_employee_timesheet_timesheet_doc" class="employee_timesheet_timesheet_doc"><?= $Grid->renderSort($Grid->timesheet_doc) ?></div></th>
<?php } ?>
<?php if ($Grid->approved->Visible) { // approved ?>
        <th data-name="approved" class="<?= $Grid->approved->headerCellClass() ?>"><div id="elh_employee_timesheet_approved" class="employee_timesheet_approved"><?= $Grid->renderSort($Grid->approved) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_employee_timesheet", "data-rowtype" => $Grid->RowType]);

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
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_timesheet"
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
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->employee_username->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_employee_username" class="form-group">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_employee_username" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_timesheet"
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
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<?= $Grid->employee_username->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_employee_username" data-hidden="1" name="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_employee_username" id="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<input type="hidden" data-table="employee_timesheet" data-field="x_employee_username" data-hidden="1" name="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_employee_username" id="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->year->Visible) { // year ?>
        <td data-name="year" <?= $Grid->year->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_year" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_year"
        name="x<?= $Grid->RowIndex ?>_year"
        class="form-control ew-select<?= $Grid->year->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_year"
        data-table="employee_timesheet"
        data-field="x_year"
        data-value-separator="<?= $Grid->year->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->year->getPlaceHolder()) ?>"
        <?= $Grid->year->editAttributes() ?>>
        <?= $Grid->year->selectOptionListHtml("x{$Grid->RowIndex}_year") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->year->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_year']"),
        options = { name: "x<?= $Grid->RowIndex ?>_year", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_year", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee_timesheet.fields.year.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.year.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_year" data-hidden="1" name="o<?= $Grid->RowIndex ?>_year" id="o<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_year" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_year"
        name="x<?= $Grid->RowIndex ?>_year"
        class="form-control ew-select<?= $Grid->year->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_year"
        data-table="employee_timesheet"
        data-field="x_year"
        data-value-separator="<?= $Grid->year->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->year->getPlaceHolder()) ?>"
        <?= $Grid->year->editAttributes() ?>>
        <?= $Grid->year->selectOptionListHtml("x{$Grid->RowIndex}_year") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->year->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_year']"),
        options = { name: "x<?= $Grid->RowIndex ?>_year", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_year", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee_timesheet.fields.year.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.year.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_year">
<span<?= $Grid->year->viewAttributes() ?>>
<?= $Grid->year->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_year" data-hidden="1" name="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_year" id="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->FormValue) ?>">
<input type="hidden" data-table="employee_timesheet" data-field="x_year" data-hidden="1" name="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_year" id="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->month->Visible) { // month ?>
        <td data-name="month" <?= $Grid->month->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_month" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_month"
        name="x<?= $Grid->RowIndex ?>_month"
        class="form-control ew-select<?= $Grid->month->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_month"
        data-table="employee_timesheet"
        data-field="x_month"
        data-value-separator="<?= $Grid->month->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->month->getPlaceHolder()) ?>"
        <?= $Grid->month->editAttributes() ?>>
        <?= $Grid->month->selectOptionListHtml("x{$Grid->RowIndex}_month") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->month->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_month']"),
        options = { name: "x<?= $Grid->RowIndex ?>_month", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_month", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee_timesheet.fields.month.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.month.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_month" data-hidden="1" name="o<?= $Grid->RowIndex ?>_month" id="o<?= $Grid->RowIndex ?>_month" value="<?= HtmlEncode($Grid->month->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_month" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_month"
        name="x<?= $Grid->RowIndex ?>_month"
        class="form-control ew-select<?= $Grid->month->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_month"
        data-table="employee_timesheet"
        data-field="x_month"
        data-value-separator="<?= $Grid->month->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->month->getPlaceHolder()) ?>"
        <?= $Grid->month->editAttributes() ?>>
        <?= $Grid->month->selectOptionListHtml("x{$Grid->RowIndex}_month") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->month->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_month']"),
        options = { name: "x<?= $Grid->RowIndex ?>_month", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_month", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee_timesheet.fields.month.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.month.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_month">
<span<?= $Grid->month->viewAttributes() ?>>
<?= $Grid->month->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_month" data-hidden="1" name="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_month" id="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_month" value="<?= HtmlEncode($Grid->month->FormValue) ?>">
<input type="hidden" data-table="employee_timesheet" data-field="x_month" data-hidden="1" name="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_month" id="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_month" value="<?= HtmlEncode($Grid->month->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->days->Visible) { // days ?>
        <td data-name="days" <?= $Grid->days->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_days" class="form-group">
<input type="<?= $Grid->days->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_days" name="x<?= $Grid->RowIndex ?>_days" id="x<?= $Grid->RowIndex ?>_days" size="30" placeholder="<?= HtmlEncode($Grid->days->getPlaceHolder()) ?>" value="<?= $Grid->days->EditValue ?>"<?= $Grid->days->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->days->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_days" data-hidden="1" name="o<?= $Grid->RowIndex ?>_days" id="o<?= $Grid->RowIndex ?>_days" value="<?= HtmlEncode($Grid->days->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_days" class="form-group">
<input type="<?= $Grid->days->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_days" name="x<?= $Grid->RowIndex ?>_days" id="x<?= $Grid->RowIndex ?>_days" size="30" placeholder="<?= HtmlEncode($Grid->days->getPlaceHolder()) ?>" value="<?= $Grid->days->EditValue ?>"<?= $Grid->days->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->days->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_days">
<span<?= $Grid->days->viewAttributes() ?>>
<?= $Grid->days->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_days" data-hidden="1" name="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_days" id="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_days" value="<?= HtmlEncode($Grid->days->FormValue) ?>">
<input type="hidden" data-table="employee_timesheet" data-field="x_days" data-hidden="1" name="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_days" id="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_days" value="<?= HtmlEncode($Grid->days->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->sick->Visible) { // sick ?>
        <td data-name="sick" <?= $Grid->sick->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_sick" class="form-group">
<input type="<?= $Grid->sick->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_sick" name="x<?= $Grid->RowIndex ?>_sick" id="x<?= $Grid->RowIndex ?>_sick" size="30" placeholder="<?= HtmlEncode($Grid->sick->getPlaceHolder()) ?>" value="<?= $Grid->sick->EditValue ?>"<?= $Grid->sick->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->sick->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_sick" data-hidden="1" name="o<?= $Grid->RowIndex ?>_sick" id="o<?= $Grid->RowIndex ?>_sick" value="<?= HtmlEncode($Grid->sick->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_sick" class="form-group">
<input type="<?= $Grid->sick->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_sick" name="x<?= $Grid->RowIndex ?>_sick" id="x<?= $Grid->RowIndex ?>_sick" size="30" placeholder="<?= HtmlEncode($Grid->sick->getPlaceHolder()) ?>" value="<?= $Grid->sick->EditValue ?>"<?= $Grid->sick->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->sick->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_sick">
<span<?= $Grid->sick->viewAttributes() ?>>
<?= $Grid->sick->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_sick" data-hidden="1" name="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_sick" id="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_sick" value="<?= HtmlEncode($Grid->sick->FormValue) ?>">
<input type="hidden" data-table="employee_timesheet" data-field="x_sick" data-hidden="1" name="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_sick" id="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_sick" value="<?= HtmlEncode($Grid->sick->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->leave->Visible) { // leave ?>
        <td data-name="leave" <?= $Grid->leave->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_leave" class="form-group">
<input type="<?= $Grid->leave->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_leave" name="x<?= $Grid->RowIndex ?>_leave" id="x<?= $Grid->RowIndex ?>_leave" size="30" placeholder="<?= HtmlEncode($Grid->leave->getPlaceHolder()) ?>" value="<?= $Grid->leave->EditValue ?>"<?= $Grid->leave->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->leave->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_leave" data-hidden="1" name="o<?= $Grid->RowIndex ?>_leave" id="o<?= $Grid->RowIndex ?>_leave" value="<?= HtmlEncode($Grid->leave->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_leave" class="form-group">
<input type="<?= $Grid->leave->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_leave" name="x<?= $Grid->RowIndex ?>_leave" id="x<?= $Grid->RowIndex ?>_leave" size="30" placeholder="<?= HtmlEncode($Grid->leave->getPlaceHolder()) ?>" value="<?= $Grid->leave->EditValue ?>"<?= $Grid->leave->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->leave->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_leave">
<span<?= $Grid->leave->viewAttributes() ?>>
<?= $Grid->leave->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_leave" data-hidden="1" name="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_leave" id="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_leave" value="<?= HtmlEncode($Grid->leave->FormValue) ?>">
<input type="hidden" data-table="employee_timesheet" data-field="x_leave" data-hidden="1" name="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_leave" id="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_leave" value="<?= HtmlEncode($Grid->leave->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->permit->Visible) { // permit ?>
        <td data-name="permit" <?= $Grid->permit->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_permit" class="form-group">
<input type="<?= $Grid->permit->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_permit" name="x<?= $Grid->RowIndex ?>_permit" id="x<?= $Grid->RowIndex ?>_permit" size="30" placeholder="<?= HtmlEncode($Grid->permit->getPlaceHolder()) ?>" value="<?= $Grid->permit->EditValue ?>"<?= $Grid->permit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->permit->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_permit" data-hidden="1" name="o<?= $Grid->RowIndex ?>_permit" id="o<?= $Grid->RowIndex ?>_permit" value="<?= HtmlEncode($Grid->permit->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_permit" class="form-group">
<input type="<?= $Grid->permit->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_permit" name="x<?= $Grid->RowIndex ?>_permit" id="x<?= $Grid->RowIndex ?>_permit" size="30" placeholder="<?= HtmlEncode($Grid->permit->getPlaceHolder()) ?>" value="<?= $Grid->permit->EditValue ?>"<?= $Grid->permit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->permit->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_permit">
<span<?= $Grid->permit->viewAttributes() ?>>
<?= $Grid->permit->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_permit" data-hidden="1" name="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_permit" id="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_permit" value="<?= HtmlEncode($Grid->permit->FormValue) ?>">
<input type="hidden" data-table="employee_timesheet" data-field="x_permit" data-hidden="1" name="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_permit" id="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_permit" value="<?= HtmlEncode($Grid->permit->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->absence->Visible) { // absence ?>
        <td data-name="absence" <?= $Grid->absence->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_absence" class="form-group">
<input type="<?= $Grid->absence->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_absence" name="x<?= $Grid->RowIndex ?>_absence" id="x<?= $Grid->RowIndex ?>_absence" size="30" placeholder="<?= HtmlEncode($Grid->absence->getPlaceHolder()) ?>" value="<?= $Grid->absence->EditValue ?>"<?= $Grid->absence->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->absence->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_absence" data-hidden="1" name="o<?= $Grid->RowIndex ?>_absence" id="o<?= $Grid->RowIndex ?>_absence" value="<?= HtmlEncode($Grid->absence->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_absence" class="form-group">
<input type="<?= $Grid->absence->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_absence" name="x<?= $Grid->RowIndex ?>_absence" id="x<?= $Grid->RowIndex ?>_absence" size="30" placeholder="<?= HtmlEncode($Grid->absence->getPlaceHolder()) ?>" value="<?= $Grid->absence->EditValue ?>"<?= $Grid->absence->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->absence->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_absence">
<span<?= $Grid->absence->viewAttributes() ?>>
<?= $Grid->absence->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_absence" data-hidden="1" name="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_absence" id="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_absence" value="<?= HtmlEncode($Grid->absence->FormValue) ?>">
<input type="hidden" data-table="employee_timesheet" data-field="x_absence" data-hidden="1" name="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_absence" id="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_absence" value="<?= HtmlEncode($Grid->absence->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->timesheet_doc->Visible) { // timesheet_doc ?>
        <td data-name="timesheet_doc" <?= $Grid->timesheet_doc->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_employee_timesheet_timesheet_doc" class="form-group employee_timesheet_timesheet_doc">
<div id="fd_x<?= $Grid->RowIndex ?>_timesheet_doc">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->timesheet_doc->title() ?>" data-table="employee_timesheet" data-field="x_timesheet_doc" name="x<?= $Grid->RowIndex ?>_timesheet_doc" id="x<?= $Grid->RowIndex ?>_timesheet_doc" lang="<?= CurrentLanguageID() ?>"<?= $Grid->timesheet_doc->editAttributes() ?><?= ($Grid->timesheet_doc->ReadOnly || $Grid->timesheet_doc->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_timesheet_doc"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->timesheet_doc->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fn_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= $Grid->timesheet_doc->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fa_x<?= $Grid->RowIndex ?>_timesheet_doc" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fs_x<?= $Grid->RowIndex ?>_timesheet_doc" value="150">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fx_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= $Grid->timesheet_doc->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fm_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= $Grid->timesheet_doc->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_timesheet_doc" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_timesheet_doc" data-hidden="1" name="o<?= $Grid->RowIndex ?>_timesheet_doc" id="o<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= HtmlEncode($Grid->timesheet_doc->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_timesheet_doc">
<span<?= $Grid->timesheet_doc->viewAttributes() ?>>
<?= GetFileViewTag($Grid->timesheet_doc, $Grid->timesheet_doc->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_timesheet_doc" class="form-group employee_timesheet_timesheet_doc">
<div id="fd_x<?= $Grid->RowIndex ?>_timesheet_doc">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->timesheet_doc->title() ?>" data-table="employee_timesheet" data-field="x_timesheet_doc" name="x<?= $Grid->RowIndex ?>_timesheet_doc" id="x<?= $Grid->RowIndex ?>_timesheet_doc" lang="<?= CurrentLanguageID() ?>"<?= $Grid->timesheet_doc->editAttributes() ?><?= ($Grid->timesheet_doc->ReadOnly || $Grid->timesheet_doc->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_timesheet_doc"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->timesheet_doc->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fn_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= $Grid->timesheet_doc->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fa_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_timesheet_doc") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fs_x<?= $Grid->RowIndex ?>_timesheet_doc" value="150">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fx_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= $Grid->timesheet_doc->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fm_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= $Grid->timesheet_doc->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_timesheet_doc" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->approved->Visible) { // approved ?>
        <td data-name="approved" <?= $Grid->approved->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_approved" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_approved"
        name="x<?= $Grid->RowIndex ?>_approved"
        class="form-control ew-select<?= $Grid->approved->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_approved"
        data-table="employee_timesheet"
        data-field="x_approved"
        data-value-separator="<?= $Grid->approved->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->approved->getPlaceHolder()) ?>"
        <?= $Grid->approved->editAttributes() ?>>
        <?= $Grid->approved->selectOptionListHtml("x{$Grid->RowIndex}_approved") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->approved->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_approved']"),
        options = { name: "x<?= $Grid->RowIndex ?>_approved", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_approved", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee_timesheet.fields.approved.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.approved.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_approved" data-hidden="1" name="o<?= $Grid->RowIndex ?>_approved" id="o<?= $Grid->RowIndex ?>_approved" value="<?= HtmlEncode($Grid->approved->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_approved" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_approved"
        name="x<?= $Grid->RowIndex ?>_approved"
        class="form-control ew-select<?= $Grid->approved->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_approved"
        data-table="employee_timesheet"
        data-field="x_approved"
        data-value-separator="<?= $Grid->approved->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->approved->getPlaceHolder()) ?>"
        <?= $Grid->approved->editAttributes() ?>>
        <?= $Grid->approved->selectOptionListHtml("x{$Grid->RowIndex}_approved") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->approved->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_approved']"),
        options = { name: "x<?= $Grid->RowIndex ?>_approved", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_approved", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee_timesheet.fields.approved.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.approved.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_employee_timesheet_approved">
<span<?= $Grid->approved->viewAttributes() ?>>
<?= $Grid->approved->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_approved" data-hidden="1" name="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_approved" id="femployee_timesheetgrid$x<?= $Grid->RowIndex ?>_approved" value="<?= HtmlEncode($Grid->approved->FormValue) ?>">
<input type="hidden" data-table="employee_timesheet" data-field="x_approved" data-hidden="1" name="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_approved" id="femployee_timesheetgrid$o<?= $Grid->RowIndex ?>_approved" value="<?= HtmlEncode($Grid->approved->OldValue) ?>">
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
loadjs.ready(["femployee_timesheetgrid","load"], function () {
    femployee_timesheetgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_employee_timesheet", "data-rowtype" => ROWTYPE_ADD]);
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
<span id="el$rowindex$_employee_timesheet_employee_username" class="form-group employee_timesheet_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_employee_username" name="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_employee_username" class="form-group employee_timesheet_employee_username">
    <select
        id="x<?= $Grid->RowIndex ?>_employee_username"
        name="x<?= $Grid->RowIndex ?>_employee_username"
        class="form-control ew-select<?= $Grid->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_employee_username"
        data-table="employee_timesheet"
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
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_employee_username']"),
        options = { name: "x<?= $Grid->RowIndex ?>_employee_username", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_employee_username" class="form-group employee_timesheet_employee_username">
<span<?= $Grid->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->employee_username->getDisplayValue($Grid->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_employee_username" data-hidden="1" name="x<?= $Grid->RowIndex ?>_employee_username" id="x<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_employee_username" data-hidden="1" name="o<?= $Grid->RowIndex ?>_employee_username" id="o<?= $Grid->RowIndex ?>_employee_username" value="<?= HtmlEncode($Grid->employee_username->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->year->Visible) { // year ?>
        <td data-name="year">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_timesheet_year" class="form-group employee_timesheet_year">
    <select
        id="x<?= $Grid->RowIndex ?>_year"
        name="x<?= $Grid->RowIndex ?>_year"
        class="form-control ew-select<?= $Grid->year->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_year"
        data-table="employee_timesheet"
        data-field="x_year"
        data-value-separator="<?= $Grid->year->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->year->getPlaceHolder()) ?>"
        <?= $Grid->year->editAttributes() ?>>
        <?= $Grid->year->selectOptionListHtml("x{$Grid->RowIndex}_year") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->year->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_year']"),
        options = { name: "x<?= $Grid->RowIndex ?>_year", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_year", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee_timesheet.fields.year.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.year.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_year" class="form-group employee_timesheet_year">
<span<?= $Grid->year->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->year->getDisplayValue($Grid->year->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_year" data-hidden="1" name="x<?= $Grid->RowIndex ?>_year" id="x<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_year" data-hidden="1" name="o<?= $Grid->RowIndex ?>_year" id="o<?= $Grid->RowIndex ?>_year" value="<?= HtmlEncode($Grid->year->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->month->Visible) { // month ?>
        <td data-name="month">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_timesheet_month" class="form-group employee_timesheet_month">
    <select
        id="x<?= $Grid->RowIndex ?>_month"
        name="x<?= $Grid->RowIndex ?>_month"
        class="form-control ew-select<?= $Grid->month->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_month"
        data-table="employee_timesheet"
        data-field="x_month"
        data-value-separator="<?= $Grid->month->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->month->getPlaceHolder()) ?>"
        <?= $Grid->month->editAttributes() ?>>
        <?= $Grid->month->selectOptionListHtml("x{$Grid->RowIndex}_month") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->month->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_month']"),
        options = { name: "x<?= $Grid->RowIndex ?>_month", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_month", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee_timesheet.fields.month.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.month.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_month" class="form-group employee_timesheet_month">
<span<?= $Grid->month->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->month->getDisplayValue($Grid->month->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_month" data-hidden="1" name="x<?= $Grid->RowIndex ?>_month" id="x<?= $Grid->RowIndex ?>_month" value="<?= HtmlEncode($Grid->month->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_month" data-hidden="1" name="o<?= $Grid->RowIndex ?>_month" id="o<?= $Grid->RowIndex ?>_month" value="<?= HtmlEncode($Grid->month->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->days->Visible) { // days ?>
        <td data-name="days">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_timesheet_days" class="form-group employee_timesheet_days">
<input type="<?= $Grid->days->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_days" name="x<?= $Grid->RowIndex ?>_days" id="x<?= $Grid->RowIndex ?>_days" size="30" placeholder="<?= HtmlEncode($Grid->days->getPlaceHolder()) ?>" value="<?= $Grid->days->EditValue ?>"<?= $Grid->days->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->days->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_days" class="form-group employee_timesheet_days">
<span<?= $Grid->days->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->days->getDisplayValue($Grid->days->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_days" data-hidden="1" name="x<?= $Grid->RowIndex ?>_days" id="x<?= $Grid->RowIndex ?>_days" value="<?= HtmlEncode($Grid->days->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_days" data-hidden="1" name="o<?= $Grid->RowIndex ?>_days" id="o<?= $Grid->RowIndex ?>_days" value="<?= HtmlEncode($Grid->days->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->sick->Visible) { // sick ?>
        <td data-name="sick">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_timesheet_sick" class="form-group employee_timesheet_sick">
<input type="<?= $Grid->sick->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_sick" name="x<?= $Grid->RowIndex ?>_sick" id="x<?= $Grid->RowIndex ?>_sick" size="30" placeholder="<?= HtmlEncode($Grid->sick->getPlaceHolder()) ?>" value="<?= $Grid->sick->EditValue ?>"<?= $Grid->sick->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->sick->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_sick" class="form-group employee_timesheet_sick">
<span<?= $Grid->sick->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->sick->getDisplayValue($Grid->sick->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_sick" data-hidden="1" name="x<?= $Grid->RowIndex ?>_sick" id="x<?= $Grid->RowIndex ?>_sick" value="<?= HtmlEncode($Grid->sick->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_sick" data-hidden="1" name="o<?= $Grid->RowIndex ?>_sick" id="o<?= $Grid->RowIndex ?>_sick" value="<?= HtmlEncode($Grid->sick->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->leave->Visible) { // leave ?>
        <td data-name="leave">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_timesheet_leave" class="form-group employee_timesheet_leave">
<input type="<?= $Grid->leave->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_leave" name="x<?= $Grid->RowIndex ?>_leave" id="x<?= $Grid->RowIndex ?>_leave" size="30" placeholder="<?= HtmlEncode($Grid->leave->getPlaceHolder()) ?>" value="<?= $Grid->leave->EditValue ?>"<?= $Grid->leave->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->leave->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_leave" class="form-group employee_timesheet_leave">
<span<?= $Grid->leave->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->leave->getDisplayValue($Grid->leave->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_leave" data-hidden="1" name="x<?= $Grid->RowIndex ?>_leave" id="x<?= $Grid->RowIndex ?>_leave" value="<?= HtmlEncode($Grid->leave->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_leave" data-hidden="1" name="o<?= $Grid->RowIndex ?>_leave" id="o<?= $Grid->RowIndex ?>_leave" value="<?= HtmlEncode($Grid->leave->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->permit->Visible) { // permit ?>
        <td data-name="permit">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_timesheet_permit" class="form-group employee_timesheet_permit">
<input type="<?= $Grid->permit->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_permit" name="x<?= $Grid->RowIndex ?>_permit" id="x<?= $Grid->RowIndex ?>_permit" size="30" placeholder="<?= HtmlEncode($Grid->permit->getPlaceHolder()) ?>" value="<?= $Grid->permit->EditValue ?>"<?= $Grid->permit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->permit->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_permit" class="form-group employee_timesheet_permit">
<span<?= $Grid->permit->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->permit->getDisplayValue($Grid->permit->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_permit" data-hidden="1" name="x<?= $Grid->RowIndex ?>_permit" id="x<?= $Grid->RowIndex ?>_permit" value="<?= HtmlEncode($Grid->permit->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_permit" data-hidden="1" name="o<?= $Grid->RowIndex ?>_permit" id="o<?= $Grid->RowIndex ?>_permit" value="<?= HtmlEncode($Grid->permit->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->absence->Visible) { // absence ?>
        <td data-name="absence">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_timesheet_absence" class="form-group employee_timesheet_absence">
<input type="<?= $Grid->absence->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_absence" name="x<?= $Grid->RowIndex ?>_absence" id="x<?= $Grid->RowIndex ?>_absence" size="30" placeholder="<?= HtmlEncode($Grid->absence->getPlaceHolder()) ?>" value="<?= $Grid->absence->EditValue ?>"<?= $Grid->absence->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->absence->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_absence" class="form-group employee_timesheet_absence">
<span<?= $Grid->absence->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->absence->getDisplayValue($Grid->absence->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_absence" data-hidden="1" name="x<?= $Grid->RowIndex ?>_absence" id="x<?= $Grid->RowIndex ?>_absence" value="<?= HtmlEncode($Grid->absence->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_absence" data-hidden="1" name="o<?= $Grid->RowIndex ?>_absence" id="o<?= $Grid->RowIndex ?>_absence" value="<?= HtmlEncode($Grid->absence->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->timesheet_doc->Visible) { // timesheet_doc ?>
        <td data-name="timesheet_doc">
<span id="el$rowindex$_employee_timesheet_timesheet_doc" class="form-group employee_timesheet_timesheet_doc">
<div id="fd_x<?= $Grid->RowIndex ?>_timesheet_doc">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->timesheet_doc->title() ?>" data-table="employee_timesheet" data-field="x_timesheet_doc" name="x<?= $Grid->RowIndex ?>_timesheet_doc" id="x<?= $Grid->RowIndex ?>_timesheet_doc" lang="<?= CurrentLanguageID() ?>"<?= $Grid->timesheet_doc->editAttributes() ?><?= ($Grid->timesheet_doc->ReadOnly || $Grid->timesheet_doc->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_timesheet_doc"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->timesheet_doc->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fn_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= $Grid->timesheet_doc->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fa_x<?= $Grid->RowIndex ?>_timesheet_doc" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fs_x<?= $Grid->RowIndex ?>_timesheet_doc" value="150">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fx_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= $Grid->timesheet_doc->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_timesheet_doc" id= "fm_x<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= $Grid->timesheet_doc->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_timesheet_doc" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_timesheet_doc" data-hidden="1" name="o<?= $Grid->RowIndex ?>_timesheet_doc" id="o<?= $Grid->RowIndex ?>_timesheet_doc" value="<?= HtmlEncode($Grid->timesheet_doc->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->approved->Visible) { // approved ?>
        <td data-name="approved">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_employee_timesheet_approved" class="form-group employee_timesheet_approved">
    <select
        id="x<?= $Grid->RowIndex ?>_approved"
        name="x<?= $Grid->RowIndex ?>_approved"
        class="form-control ew-select<?= $Grid->approved->isInvalidClass() ?>"
        data-select2-id="employee_timesheet_x<?= $Grid->RowIndex ?>_approved"
        data-table="employee_timesheet"
        data-field="x_approved"
        data-value-separator="<?= $Grid->approved->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->approved->getPlaceHolder()) ?>"
        <?= $Grid->approved->editAttributes() ?>>
        <?= $Grid->approved->selectOptionListHtml("x{$Grid->RowIndex}_approved") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->approved->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_timesheet_x<?= $Grid->RowIndex ?>_approved']"),
        options = { name: "x<?= $Grid->RowIndex ?>_approved", selectId: "employee_timesheet_x<?= $Grid->RowIndex ?>_approved", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee_timesheet.fields.approved.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_timesheet.fields.approved.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_employee_timesheet_approved" class="form-group employee_timesheet_approved">
<span<?= $Grid->approved->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->approved->getDisplayValue($Grid->approved->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_timesheet" data-field="x_approved" data-hidden="1" name="x<?= $Grid->RowIndex ?>_approved" id="x<?= $Grid->RowIndex ?>_approved" value="<?= HtmlEncode($Grid->approved->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="employee_timesheet" data-field="x_approved" data-hidden="1" name="o<?= $Grid->RowIndex ?>_approved" id="o<?= $Grid->RowIndex ?>_approved" value="<?= HtmlEncode($Grid->approved->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["femployee_timesheetgrid","load"], function() {
    femployee_timesheetgrid.updateLists(<?= $Grid->RowIndex ?>);
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
        <td data-name="employee_username" class="<?= $Grid->employee_username->footerCellClass() ?>"><span id="elf_employee_timesheet_employee_username" class="employee_timesheet_employee_username">
        <span class="ew-aggregate"><?= $Language->phrase("COUNT") ?></span><span class="ew-aggregate-value">
        <?= $Grid->employee_username->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->year->Visible) { // year ?>
        <td data-name="year" class="<?= $Grid->year->footerCellClass() ?>"><span id="elf_employee_timesheet_year" class="employee_timesheet_year">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Grid->month->Visible) { // month ?>
        <td data-name="month" class="<?= $Grid->month->footerCellClass() ?>"><span id="elf_employee_timesheet_month" class="employee_timesheet_month">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Grid->days->Visible) { // days ?>
        <td data-name="days" class="<?= $Grid->days->footerCellClass() ?>"><span id="elf_employee_timesheet_days" class="employee_timesheet_days">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->days->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->sick->Visible) { // sick ?>
        <td data-name="sick" class="<?= $Grid->sick->footerCellClass() ?>"><span id="elf_employee_timesheet_sick" class="employee_timesheet_sick">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->sick->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->leave->Visible) { // leave ?>
        <td data-name="leave" class="<?= $Grid->leave->footerCellClass() ?>"><span id="elf_employee_timesheet_leave" class="employee_timesheet_leave">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->leave->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->permit->Visible) { // permit ?>
        <td data-name="permit" class="<?= $Grid->permit->footerCellClass() ?>"><span id="elf_employee_timesheet_permit" class="employee_timesheet_permit">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->permit->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->absence->Visible) { // absence ?>
        <td data-name="absence" class="<?= $Grid->absence->footerCellClass() ?>"><span id="elf_employee_timesheet_absence" class="employee_timesheet_absence">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->absence->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->timesheet_doc->Visible) { // timesheet_doc ?>
        <td data-name="timesheet_doc" class="<?= $Grid->timesheet_doc->footerCellClass() ?>"><span id="elf_employee_timesheet_timesheet_doc" class="employee_timesheet_timesheet_doc">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Grid->approved->Visible) { // approved ?>
        <td data-name="approved" class="<?= $Grid->approved->footerCellClass() ?>"><span id="elf_employee_timesheet_approved" class="employee_timesheet_approved">
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
<input type="hidden" name="detailpage" value="femployee_timesheetgrid">
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
    ew.addEventHandlers("employee_timesheet");
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
        container: "gmp_employee_timesheet",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
