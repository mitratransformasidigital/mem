<?php

namespace MEM\prjMitralPHP;

// Page object
$MytimesheetEdit = &$Page;
?>
<script>
if (!ew.vars.tables.mytimesheet) ew.vars.tables.mytimesheet = <?= JsonEncode(GetClientVar("tables", "mytimesheet")) ?>;
var currentForm, currentPageID;
var fmytimesheetedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fmytimesheetedit = currentForm = new ew.Form("fmytimesheetedit", "edit");

    // Add fields
    var fields = ew.vars.tables.mytimesheet.fields;
    fmytimesheetedit.addFields([
        ["timesheet_id", [fields.timesheet_id.required ? ew.Validators.required(fields.timesheet_id.caption) : null], fields.timesheet_id.isInvalid],
        ["year", [fields.year.required ? ew.Validators.required(fields.year.caption) : null], fields.year.isInvalid],
        ["month", [fields.month.required ? ew.Validators.required(fields.month.caption) : null], fields.month.isInvalid],
        ["days", [fields.days.required ? ew.Validators.required(fields.days.caption) : null, ew.Validators.integer], fields.days.isInvalid],
        ["sick", [fields.sick.required ? ew.Validators.required(fields.sick.caption) : null, ew.Validators.integer], fields.sick.isInvalid],
        ["leave", [fields.leave.required ? ew.Validators.required(fields.leave.caption) : null, ew.Validators.integer], fields.leave.isInvalid],
        ["permit", [fields.permit.required ? ew.Validators.required(fields.permit.caption) : null, ew.Validators.integer], fields.permit.isInvalid],
        ["absence", [fields.absence.required ? ew.Validators.required(fields.absence.caption) : null, ew.Validators.integer], fields.absence.isInvalid],
        ["timesheet_doc", [fields.timesheet_doc.required ? ew.Validators.fileRequired(fields.timesheet_doc.caption) : null], fields.timesheet_doc.isInvalid],
        ["employee_notes", [fields.employee_notes.required ? ew.Validators.required(fields.employee_notes.caption) : null], fields.employee_notes.isInvalid],
        ["company_notes", [fields.company_notes.required ? ew.Validators.required(fields.company_notes.caption) : null], fields.company_notes.isInvalid],
        ["approved", [fields.approved.required ? ew.Validators.required(fields.approved.caption) : null], fields.approved.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmytimesheetedit,
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
    fmytimesheetedit.validate = function () {
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

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    fmytimesheetedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmytimesheetedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmytimesheetedit.lists.year = <?= $Page->year->toClientList($Page) ?>;
    fmytimesheetedit.lists.month = <?= $Page->month->toClientList($Page) ?>;
    fmytimesheetedit.lists.approved = <?= $Page->approved->toClientList($Page) ?>;
    loadjs.done("fmytimesheetedit");
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
<?php if (!$Page->IsModal) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fmytimesheetedit" id="fmytimesheetedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mytimesheet">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "myprofile") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="myprofile">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->year->Visible) { // year ?>
    <div id="r_year" class="form-group row">
        <label id="elh_mytimesheet_year" for="x_year" class="<?= $Page->LeftColumnClass ?>"><?= $Page->year->caption() ?><?= $Page->year->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->year->cellAttributes() ?>>
<span id="el_mytimesheet_year">
    <select
        id="x_year"
        name="x_year"
        class="form-control ew-select<?= $Page->year->isInvalidClass() ?>"
        data-select2-id="mytimesheet_x_year"
        data-table="mytimesheet"
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
    var el = document.querySelector("select[data-select2-id='mytimesheet_x_year']"),
        options = { name: "x_year", selectId: "mytimesheet_x_year", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.mytimesheet.fields.year.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mytimesheet.fields.year.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->month->Visible) { // month ?>
    <div id="r_month" class="form-group row">
        <label id="elh_mytimesheet_month" for="x_month" class="<?= $Page->LeftColumnClass ?>"><?= $Page->month->caption() ?><?= $Page->month->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->month->cellAttributes() ?>>
<span id="el_mytimesheet_month">
    <select
        id="x_month"
        name="x_month"
        class="form-control ew-select<?= $Page->month->isInvalidClass() ?>"
        data-select2-id="mytimesheet_x_month"
        data-table="mytimesheet"
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
    var el = document.querySelector("select[data-select2-id='mytimesheet_x_month']"),
        options = { name: "x_month", selectId: "mytimesheet_x_month", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.mytimesheet.fields.month.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mytimesheet.fields.month.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
    <div id="r_days" class="form-group row">
        <label id="elh_mytimesheet_days" for="x_days" class="<?= $Page->LeftColumnClass ?>"><?= $Page->days->caption() ?><?= $Page->days->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->days->cellAttributes() ?>>
<span id="el_mytimesheet_days">
<input type="<?= $Page->days->getInputTextType() ?>" data-table="mytimesheet" data-field="x_days" name="x_days" id="x_days" size="30" placeholder="<?= HtmlEncode($Page->days->getPlaceHolder()) ?>" value="<?= $Page->days->EditValue ?>"<?= $Page->days->editAttributes() ?> aria-describedby="x_days_help">
<?= $Page->days->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->days->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sick->Visible) { // sick ?>
    <div id="r_sick" class="form-group row">
        <label id="elh_mytimesheet_sick" for="x_sick" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sick->caption() ?><?= $Page->sick->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sick->cellAttributes() ?>>
<span id="el_mytimesheet_sick">
<input type="<?= $Page->sick->getInputTextType() ?>" data-table="mytimesheet" data-field="x_sick" name="x_sick" id="x_sick" size="30" placeholder="<?= HtmlEncode($Page->sick->getPlaceHolder()) ?>" value="<?= $Page->sick->EditValue ?>"<?= $Page->sick->editAttributes() ?> aria-describedby="x_sick_help">
<?= $Page->sick->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sick->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->leave->Visible) { // leave ?>
    <div id="r_leave" class="form-group row">
        <label id="elh_mytimesheet_leave" for="x_leave" class="<?= $Page->LeftColumnClass ?>"><?= $Page->leave->caption() ?><?= $Page->leave->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->leave->cellAttributes() ?>>
<span id="el_mytimesheet_leave">
<input type="<?= $Page->leave->getInputTextType() ?>" data-table="mytimesheet" data-field="x_leave" name="x_leave" id="x_leave" size="30" placeholder="<?= HtmlEncode($Page->leave->getPlaceHolder()) ?>" value="<?= $Page->leave->EditValue ?>"<?= $Page->leave->editAttributes() ?> aria-describedby="x_leave_help">
<?= $Page->leave->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->leave->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->permit->Visible) { // permit ?>
    <div id="r_permit" class="form-group row">
        <label id="elh_mytimesheet_permit" for="x_permit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->permit->caption() ?><?= $Page->permit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->permit->cellAttributes() ?>>
<span id="el_mytimesheet_permit">
<input type="<?= $Page->permit->getInputTextType() ?>" data-table="mytimesheet" data-field="x_permit" name="x_permit" id="x_permit" size="30" placeholder="<?= HtmlEncode($Page->permit->getPlaceHolder()) ?>" value="<?= $Page->permit->EditValue ?>"<?= $Page->permit->editAttributes() ?> aria-describedby="x_permit_help">
<?= $Page->permit->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->permit->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
    <div id="r_absence" class="form-group row">
        <label id="elh_mytimesheet_absence" for="x_absence" class="<?= $Page->LeftColumnClass ?>"><?= $Page->absence->caption() ?><?= $Page->absence->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->absence->cellAttributes() ?>>
<span id="el_mytimesheet_absence">
<input type="<?= $Page->absence->getInputTextType() ?>" data-table="mytimesheet" data-field="x_absence" name="x_absence" id="x_absence" size="30" placeholder="<?= HtmlEncode($Page->absence->getPlaceHolder()) ?>" value="<?= $Page->absence->EditValue ?>"<?= $Page->absence->editAttributes() ?> aria-describedby="x_absence_help">
<?= $Page->absence->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->absence->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
    <div id="r_timesheet_doc" class="form-group row">
        <label id="elh_mytimesheet_timesheet_doc" class="<?= $Page->LeftColumnClass ?>"><?= $Page->timesheet_doc->caption() ?><?= $Page->timesheet_doc->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->timesheet_doc->cellAttributes() ?>>
<span id="el_mytimesheet_timesheet_doc">
<div id="fd_x_timesheet_doc">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->timesheet_doc->title() ?>" data-table="mytimesheet" data-field="x_timesheet_doc" name="x_timesheet_doc" id="x_timesheet_doc" lang="<?= CurrentLanguageID() ?>"<?= $Page->timesheet_doc->editAttributes() ?><?= ($Page->timesheet_doc->ReadOnly || $Page->timesheet_doc->Disabled) ? " disabled" : "" ?> aria-describedby="x_timesheet_doc_help">
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
</div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_notes->Visible) { // employee_notes ?>
    <div id="r_employee_notes" class="form-group row">
        <label id="elh_mytimesheet_employee_notes" for="x_employee_notes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_notes->caption() ?><?= $Page->employee_notes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_notes->cellAttributes() ?>>
<span id="el_mytimesheet_employee_notes">
<textarea data-table="mytimesheet" data-field="x_employee_notes" name="x_employee_notes" id="x_employee_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->employee_notes->getPlaceHolder()) ?>"<?= $Page->employee_notes->editAttributes() ?> aria-describedby="x_employee_notes_help"><?= $Page->employee_notes->EditValue ?></textarea>
<?= $Page->employee_notes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_notes->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->company_notes->Visible) { // company_notes ?>
    <div id="r_company_notes" class="form-group row">
        <label id="elh_mytimesheet_company_notes" for="x_company_notes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->company_notes->caption() ?><?= $Page->company_notes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->company_notes->cellAttributes() ?>>
<span id="el_mytimesheet_company_notes">
<textarea data-table="mytimesheet" data-field="x_company_notes" name="x_company_notes" id="x_company_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->company_notes->getPlaceHolder()) ?>"<?= $Page->company_notes->editAttributes() ?> aria-describedby="x_company_notes_help"><?= $Page->company_notes->EditValue ?></textarea>
<?= $Page->company_notes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->company_notes->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->approved->Visible) { // approved ?>
    <div id="r_approved" class="form-group row">
        <label id="elh_mytimesheet_approved" for="x_approved" class="<?= $Page->LeftColumnClass ?>"><?= $Page->approved->caption() ?><?= $Page->approved->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->approved->cellAttributes() ?>>
<span id="el_mytimesheet_approved">
    <select
        id="x_approved"
        name="x_approved"
        class="form-control ew-select<?= $Page->approved->isInvalidClass() ?>"
        data-select2-id="mytimesheet_x_approved"
        data-table="mytimesheet"
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
    var el = document.querySelector("select[data-select2-id='mytimesheet_x_approved']"),
        options = { name: "x_approved", selectId: "mytimesheet_x_approved", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.mytimesheet.fields.approved.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mytimesheet.fields.approved.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<span id="el_mytimesheet_timesheet_id">
<input type="hidden" data-table="mytimesheet" data-field="x_timesheet_id" data-hidden="1" name="x_timesheet_id" id="x_timesheet_id" value="<?= HtmlEncode($Page->timesheet_id->CurrentValue) ?>">
</span>
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
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
    ew.addEventHandlers("mytimesheet");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
