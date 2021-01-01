<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeTimesheetUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.employee_timesheet) ew.vars.tables.employee_timesheet = <?= JsonEncode(GetClientVar("tables", "employee_timesheet")) ?>;
var currentForm, currentPageID;
var femployee_timesheetupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    femployee_timesheetupdate = currentForm = new ew.Form("femployee_timesheetupdate", "update");

    // Add fields
    var fields = ew.vars.tables.employee_timesheet.fields;
    femployee_timesheetupdate.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["year", [fields.year.required ? ew.Validators.required(fields.year.caption) : null], fields.year.isInvalid],
        ["month", [fields.month.required ? ew.Validators.required(fields.month.caption) : null], fields.month.isInvalid],
        ["days", [fields.days.required ? ew.Validators.required(fields.days.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.days.isInvalid],
        ["sick", [fields.sick.required ? ew.Validators.required(fields.sick.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.sick.isInvalid],
        ["leave", [fields.leave.required ? ew.Validators.required(fields.leave.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.leave.isInvalid],
        ["permit", [fields.permit.required ? ew.Validators.required(fields.permit.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.permit.isInvalid],
        ["absence", [fields.absence.required ? ew.Validators.required(fields.absence.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.absence.isInvalid],
        ["timesheet_doc", [fields.timesheet_doc.required ? ew.Validators.fileRequired(fields.timesheet_doc.caption) : null], fields.timesheet_doc.isInvalid],
        ["employee_notes", [fields.employee_notes.required ? ew.Validators.required(fields.employee_notes.caption) : null], fields.employee_notes.isInvalid],
        ["company_notes", [fields.company_notes.required ? ew.Validators.required(fields.company_notes.caption) : null], fields.company_notes.isInvalid],
        ["approved", [fields.approved.required ? ew.Validators.required(fields.approved.caption) : null], fields.approved.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_timesheetupdate,
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
    femployee_timesheetupdate.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        if (!ew.updateSelected(fobj)) {
            ew.alert(ew.language.phrase("NoFieldSelected"));
            return false;
        }
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }
        return true;
    }

    // Form_CustomValidate
    femployee_timesheetupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_timesheetupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_timesheetupdate.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    femployee_timesheetupdate.lists.year = <?= $Page->year->toClientList($Page) ?>;
    femployee_timesheetupdate.lists.month = <?= $Page->month->toClientList($Page) ?>;
    femployee_timesheetupdate.lists.approved = <?= $Page->approved->toClientList($Page) ?>;
    loadjs.done("femployee_timesheetupdate");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="femployee_timesheetupdate" id="femployee_timesheetupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_timesheet">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_employee_timesheetupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->employee_username->Visible && (!$Page->isConfirm() || $Page->employee_username->multiUpdateSelected())) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label for="x_employee_username" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_employee_username" id="u_employee_username" class="custom-control-input ew-multi-select" value="1"<?= $Page->employee_username->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_employee_username"><?= $Page->employee_username->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->employee_username->cellAttributes() ?>>
                <?php if ($Page->employee_username->getSessionValue() != "") { ?>
                <span id="el_employee_timesheet_employee_username">
                <span<?= $Page->employee_username->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_username->getDisplayValue($Page->employee_username->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_employee_username" name="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_employee_timesheet_employee_username">
                    <select
                        id="x_employee_username"
                        name="x_employee_username"
                        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
                        data-select2-id="employee_timesheet_x_employee_username"
                        data-table="employee_timesheet"
                        data-field="x_employee_username"
                        data-value-separator="<?= $Page->employee_username->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>"
                        <?= $Page->employee_username->editAttributes() ?>>
                        <?= $Page->employee_username->selectOptionListHtml("x_employee_username") ?>
                    </select>
                    <?= $Page->employee_username->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage() ?></div>
                <?= $Page->employee_username->Lookup->getParamTag($Page, "p_x_employee_username") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_timesheet_x_employee_username']"),
                        options = { name: "x_employee_username", selectId: "employee_timesheet_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee_timesheet.fields.employee_username.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->year->Visible && (!$Page->isConfirm() || $Page->year->multiUpdateSelected())) { // year ?>
    <div id="r_year" class="form-group row">
        <label for="x_year" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_year" id="u_year" class="custom-control-input ew-multi-select" value="1"<?= $Page->year->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_year"><?= $Page->year->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->year->cellAttributes() ?>>
                <span id="el_employee_timesheet_year">
                    <select
                        id="x_year"
                        name="x_year"
                        class="form-control ew-select<?= $Page->year->isInvalidClass() ?>"
                        data-select2-id="employee_timesheet_x_year"
                        data-table="employee_timesheet"
                        data-field="x_year"
                        data-value-separator="<?= $Page->year->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->year->getPlaceHolder()) ?>"
                        <?= $Page->year->editAttributes() ?>>
                        <?= $Page->year->selectOptionListHtml("x_year") ?>
                    </select>
                    <?= $Page->year->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->year->getErrorMessage() ?></div>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_timesheet_x_year']"),
                        options = { name: "x_year", selectId: "employee_timesheet_x_year", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.data = ew.vars.tables.employee_timesheet.fields.year.lookupOptions;
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee_timesheet.fields.year.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->month->Visible && (!$Page->isConfirm() || $Page->month->multiUpdateSelected())) { // month ?>
    <div id="r_month" class="form-group row">
        <label for="x_month" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_month" id="u_month" class="custom-control-input ew-multi-select" value="1"<?= $Page->month->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_month"><?= $Page->month->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->month->cellAttributes() ?>>
                <span id="el_employee_timesheet_month">
                    <select
                        id="x_month"
                        name="x_month"
                        class="form-control ew-select<?= $Page->month->isInvalidClass() ?>"
                        data-select2-id="employee_timesheet_x_month"
                        data-table="employee_timesheet"
                        data-field="x_month"
                        data-value-separator="<?= $Page->month->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->month->getPlaceHolder()) ?>"
                        <?= $Page->month->editAttributes() ?>>
                        <?= $Page->month->selectOptionListHtml("x_month") ?>
                    </select>
                    <?= $Page->month->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->month->getErrorMessage() ?></div>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_timesheet_x_month']"),
                        options = { name: "x_month", selectId: "employee_timesheet_x_month", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.data = ew.vars.tables.employee_timesheet.fields.month.lookupOptions;
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee_timesheet.fields.month.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->days->Visible && (!$Page->isConfirm() || $Page->days->multiUpdateSelected())) { // days ?>
    <div id="r_days" class="form-group row">
        <label for="x_days" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_days" id="u_days" class="custom-control-input ew-multi-select" value="1"<?= $Page->days->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_days"><?= $Page->days->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->days->cellAttributes() ?>>
                <span id="el_employee_timesheet_days">
                <input type="<?= $Page->days->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_days" name="x_days" id="x_days" size="30" placeholder="<?= HtmlEncode($Page->days->getPlaceHolder()) ?>" value="<?= $Page->days->EditValue ?>"<?= $Page->days->editAttributes() ?> aria-describedby="x_days_help">
                <?= $Page->days->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->days->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->sick->Visible && (!$Page->isConfirm() || $Page->sick->multiUpdateSelected())) { // sick ?>
    <div id="r_sick" class="form-group row">
        <label for="x_sick" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_sick" id="u_sick" class="custom-control-input ew-multi-select" value="1"<?= $Page->sick->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_sick"><?= $Page->sick->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->sick->cellAttributes() ?>>
                <span id="el_employee_timesheet_sick">
                <input type="<?= $Page->sick->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_sick" name="x_sick" id="x_sick" size="30" placeholder="<?= HtmlEncode($Page->sick->getPlaceHolder()) ?>" value="<?= $Page->sick->EditValue ?>"<?= $Page->sick->editAttributes() ?> aria-describedby="x_sick_help">
                <?= $Page->sick->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->sick->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->leave->Visible && (!$Page->isConfirm() || $Page->leave->multiUpdateSelected())) { // leave ?>
    <div id="r_leave" class="form-group row">
        <label for="x_leave" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_leave" id="u_leave" class="custom-control-input ew-multi-select" value="1"<?= $Page->leave->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_leave"><?= $Page->leave->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->leave->cellAttributes() ?>>
                <span id="el_employee_timesheet_leave">
                <input type="<?= $Page->leave->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_leave" name="x_leave" id="x_leave" size="30" placeholder="<?= HtmlEncode($Page->leave->getPlaceHolder()) ?>" value="<?= $Page->leave->EditValue ?>"<?= $Page->leave->editAttributes() ?> aria-describedby="x_leave_help">
                <?= $Page->leave->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->leave->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->permit->Visible && (!$Page->isConfirm() || $Page->permit->multiUpdateSelected())) { // permit ?>
    <div id="r_permit" class="form-group row">
        <label for="x_permit" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_permit" id="u_permit" class="custom-control-input ew-multi-select" value="1"<?= $Page->permit->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_permit"><?= $Page->permit->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->permit->cellAttributes() ?>>
                <span id="el_employee_timesheet_permit">
                <input type="<?= $Page->permit->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_permit" name="x_permit" id="x_permit" size="30" placeholder="<?= HtmlEncode($Page->permit->getPlaceHolder()) ?>" value="<?= $Page->permit->EditValue ?>"<?= $Page->permit->editAttributes() ?> aria-describedby="x_permit_help">
                <?= $Page->permit->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->permit->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->absence->Visible && (!$Page->isConfirm() || $Page->absence->multiUpdateSelected())) { // absence ?>
    <div id="r_absence" class="form-group row">
        <label for="x_absence" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_absence" id="u_absence" class="custom-control-input ew-multi-select" value="1"<?= $Page->absence->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_absence"><?= $Page->absence->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->absence->cellAttributes() ?>>
                <span id="el_employee_timesheet_absence">
                <input type="<?= $Page->absence->getInputTextType() ?>" data-table="employee_timesheet" data-field="x_absence" name="x_absence" id="x_absence" size="30" placeholder="<?= HtmlEncode($Page->absence->getPlaceHolder()) ?>" value="<?= $Page->absence->EditValue ?>"<?= $Page->absence->editAttributes() ?> aria-describedby="x_absence_help">
                <?= $Page->absence->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->absence->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->timesheet_doc->Visible && (!$Page->isConfirm() || $Page->timesheet_doc->multiUpdateSelected())) { // timesheet_doc ?>
    <div id="r_timesheet_doc" class="form-group row">
        <label class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_timesheet_doc" id="u_timesheet_doc" class="custom-control-input ew-multi-select" value="1"<?= $Page->timesheet_doc->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_timesheet_doc"><?= $Page->timesheet_doc->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->timesheet_doc->cellAttributes() ?>>
                <span id="el_employee_timesheet_timesheet_doc">
                <div id="fd_x_timesheet_doc">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" title="<?= $Page->timesheet_doc->title() ?>" data-table="employee_timesheet" data-field="x_timesheet_doc" name="x_timesheet_doc" id="x_timesheet_doc" lang="<?= CurrentLanguageID() ?>"<?= $Page->timesheet_doc->editAttributes() ?><?= ($Page->timesheet_doc->ReadOnly || $Page->timesheet_doc->Disabled) ? " disabled" : "" ?> aria-describedby="x_timesheet_doc_help">
                        <label class="custom-file-label ew-file-label" for="x_timesheet_doc"><?= $Language->phrase("ChooseFile") ?></label>
                    </div>
                </div>
                <?= $Page->timesheet_doc->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->timesheet_doc->getErrorMessage() ?></div>
                <input type="hidden" name="fn_x_timesheet_doc" id= "fn_x_timesheet_doc" value="<?= $Page->timesheet_doc->Upload->FileName ?>">
                <input type="hidden" name="fa_x_timesheet_doc" id= "fa_x_timesheet_doc" value="<?= (Post("fa_x_timesheet_doc") == "0") ? "0" : "1" ?>">
                <input type="hidden" name="fs_x_timesheet_doc" id= "fs_x_timesheet_doc" value="150">
                <input type="hidden" name="fx_x_timesheet_doc" id= "fx_x_timesheet_doc" value="<?= $Page->timesheet_doc->UploadAllowedFileExt ?>">
                <input type="hidden" name="fm_x_timesheet_doc" id= "fm_x_timesheet_doc" value="<?= $Page->timesheet_doc->UploadMaxFileSize ?>">
                </div>
                <table id="ft_x_timesheet_doc" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->employee_notes->Visible && (!$Page->isConfirm() || $Page->employee_notes->multiUpdateSelected())) { // employee_notes ?>
    <div id="r_employee_notes" class="form-group row">
        <label for="x_employee_notes" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_employee_notes" id="u_employee_notes" class="custom-control-input ew-multi-select" value="1"<?= $Page->employee_notes->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_employee_notes"><?= $Page->employee_notes->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->employee_notes->cellAttributes() ?>>
                <span id="el_employee_timesheet_employee_notes">
                <textarea data-table="employee_timesheet" data-field="x_employee_notes" name="x_employee_notes" id="x_employee_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->employee_notes->getPlaceHolder()) ?>"<?= $Page->employee_notes->editAttributes() ?> aria-describedby="x_employee_notes_help"><?= $Page->employee_notes->EditValue ?></textarea>
                <?= $Page->employee_notes->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->employee_notes->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->company_notes->Visible && (!$Page->isConfirm() || $Page->company_notes->multiUpdateSelected())) { // company_notes ?>
    <div id="r_company_notes" class="form-group row">
        <label for="x_company_notes" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_company_notes" id="u_company_notes" class="custom-control-input ew-multi-select" value="1"<?= $Page->company_notes->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_company_notes"><?= $Page->company_notes->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->company_notes->cellAttributes() ?>>
                <span id="el_employee_timesheet_company_notes">
                <textarea data-table="employee_timesheet" data-field="x_company_notes" name="x_company_notes" id="x_company_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->company_notes->getPlaceHolder()) ?>"<?= $Page->company_notes->editAttributes() ?> aria-describedby="x_company_notes_help"><?= $Page->company_notes->EditValue ?></textarea>
                <?= $Page->company_notes->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->company_notes->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->approved->Visible && (!$Page->isConfirm() || $Page->approved->multiUpdateSelected())) { // approved ?>
    <div id="r_approved" class="form-group row">
        <label for="x_approved" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_approved" id="u_approved" class="custom-control-input ew-multi-select" value="1"<?= $Page->approved->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_approved"><?= $Page->approved->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->approved->cellAttributes() ?>>
                <span id="el_employee_timesheet_approved">
                    <select
                        id="x_approved"
                        name="x_approved"
                        class="form-control ew-select<?= $Page->approved->isInvalidClass() ?>"
                        data-select2-id="employee_timesheet_x_approved"
                        data-table="employee_timesheet"
                        data-field="x_approved"
                        data-value-separator="<?= $Page->approved->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->approved->getPlaceHolder()) ?>"
                        <?= $Page->approved->editAttributes() ?>>
                        <?= $Page->approved->selectOptionListHtml("x_approved") ?>
                    </select>
                    <?= $Page->approved->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->approved->getErrorMessage() ?></div>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_timesheet_x_approved']"),
                        options = { name: "x_approved", selectId: "employee_timesheet_x_approved", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.data = ew.vars.tables.employee_timesheet.fields.approved.lookupOptions;
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee_timesheet.fields.approved.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page -->
<?php if (!$Page->IsModal) { ?>
    <div class="form-group row"><!-- buttons .form-group -->
        <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("UpdateBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
        </div><!-- /buttons offset -->
    </div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
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
