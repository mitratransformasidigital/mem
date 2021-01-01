<?php

namespace MEM\prjMitralPHP;

// Page object
$MytrainingAdd = &$Page;
?>
<script>
if (!ew.vars.tables.mytraining) ew.vars.tables.mytraining = <?= JsonEncode(GetClientVar("tables", "mytraining")) ?>;
var currentForm, currentPageID;
var fmytrainingadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fmytrainingadd = currentForm = new ew.Form("fmytrainingadd", "add");

    // Add fields
    var fields = ew.vars.tables.mytraining.fields;
    fmytrainingadd.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["training_name", [fields.training_name.required ? ew.Validators.required(fields.training_name.caption) : null], fields.training_name.isInvalid],
        ["training_start", [fields.training_start.required ? ew.Validators.required(fields.training_start.caption) : null, ew.Validators.datetime(0)], fields.training_start.isInvalid],
        ["training_end", [fields.training_end.required ? ew.Validators.required(fields.training_end.caption) : null, ew.Validators.datetime(0)], fields.training_end.isInvalid],
        ["training_company", [fields.training_company.required ? ew.Validators.required(fields.training_company.caption) : null], fields.training_company.isInvalid],
        ["certificate_start", [fields.certificate_start.required ? ew.Validators.required(fields.certificate_start.caption) : null, ew.Validators.datetime(0)], fields.certificate_start.isInvalid],
        ["certificate_end", [fields.certificate_end.required ? ew.Validators.required(fields.certificate_end.caption) : null, ew.Validators.datetime(0)], fields.certificate_end.isInvalid],
        ["notes", [fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid],
        ["training_document", [fields.training_document.required ? ew.Validators.fileRequired(fields.training_document.caption) : null], fields.training_document.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmytrainingadd,
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
    fmytrainingadd.validate = function () {
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
    fmytrainingadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmytrainingadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmytrainingadd");
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
<form name="fmytrainingadd" id="fmytrainingadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mytraining">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "employee") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="employee">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "myprofile") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="myprofile">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
    <?php if ($Page->employee_username->getSessionValue() != "") { ?>
    <input type="hidden" id="x_employee_username" name="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>" data-hidden="1">
    <?php } else { ?>
    <span id="el_mytraining_employee_username">
    <input type="hidden" data-table="mytraining" data-field="x_employee_username" data-hidden="1" name="x_employee_username" id="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>">
    </span>
    <?php } ?>
<?php if ($Page->training_name->Visible) { // training_name ?>
    <div id="r_training_name" class="form-group row">
        <label id="elh_mytraining_training_name" for="x_training_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->training_name->caption() ?><?= $Page->training_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_name->cellAttributes() ?>>
<span id="el_mytraining_training_name">
<input type="<?= $Page->training_name->getInputTextType() ?>" data-table="mytraining" data-field="x_training_name" name="x_training_name" id="x_training_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->training_name->getPlaceHolder()) ?>" value="<?= $Page->training_name->EditValue ?>"<?= $Page->training_name->editAttributes() ?> aria-describedby="x_training_name_help">
<?= $Page->training_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->training_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->training_start->Visible) { // training_start ?>
    <div id="r_training_start" class="form-group row">
        <label id="elh_mytraining_training_start" for="x_training_start" class="<?= $Page->LeftColumnClass ?>"><?= $Page->training_start->caption() ?><?= $Page->training_start->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_start->cellAttributes() ?>>
<span id="el_mytraining_training_start">
<input type="<?= $Page->training_start->getInputTextType() ?>" data-table="mytraining" data-field="x_training_start" name="x_training_start" id="x_training_start" placeholder="<?= HtmlEncode($Page->training_start->getPlaceHolder()) ?>" value="<?= $Page->training_start->EditValue ?>"<?= $Page->training_start->editAttributes() ?> aria-describedby="x_training_start_help">
<?= $Page->training_start->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->training_start->getErrorMessage() ?></div>
<?php if (!$Page->training_start->ReadOnly && !$Page->training_start->Disabled && !isset($Page->training_start->EditAttrs["readonly"]) && !isset($Page->training_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytrainingadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytrainingadd", "x_training_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->training_end->Visible) { // training_end ?>
    <div id="r_training_end" class="form-group row">
        <label id="elh_mytraining_training_end" for="x_training_end" class="<?= $Page->LeftColumnClass ?>"><?= $Page->training_end->caption() ?><?= $Page->training_end->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_end->cellAttributes() ?>>
<span id="el_mytraining_training_end">
<input type="<?= $Page->training_end->getInputTextType() ?>" data-table="mytraining" data-field="x_training_end" name="x_training_end" id="x_training_end" placeholder="<?= HtmlEncode($Page->training_end->getPlaceHolder()) ?>" value="<?= $Page->training_end->EditValue ?>"<?= $Page->training_end->editAttributes() ?> aria-describedby="x_training_end_help">
<?= $Page->training_end->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->training_end->getErrorMessage() ?></div>
<?php if (!$Page->training_end->ReadOnly && !$Page->training_end->Disabled && !isset($Page->training_end->EditAttrs["readonly"]) && !isset($Page->training_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytrainingadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytrainingadd", "x_training_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->training_company->Visible) { // training_company ?>
    <div id="r_training_company" class="form-group row">
        <label id="elh_mytraining_training_company" for="x_training_company" class="<?= $Page->LeftColumnClass ?>"><?= $Page->training_company->caption() ?><?= $Page->training_company->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_company->cellAttributes() ?>>
<span id="el_mytraining_training_company">
<input type="<?= $Page->training_company->getInputTextType() ?>" data-table="mytraining" data-field="x_training_company" name="x_training_company" id="x_training_company" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->training_company->getPlaceHolder()) ?>" value="<?= $Page->training_company->EditValue ?>"<?= $Page->training_company->editAttributes() ?> aria-describedby="x_training_company_help">
<?= $Page->training_company->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->training_company->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->certificate_start->Visible) { // certificate_start ?>
    <div id="r_certificate_start" class="form-group row">
        <label id="elh_mytraining_certificate_start" for="x_certificate_start" class="<?= $Page->LeftColumnClass ?>"><?= $Page->certificate_start->caption() ?><?= $Page->certificate_start->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->certificate_start->cellAttributes() ?>>
<span id="el_mytraining_certificate_start">
<input type="<?= $Page->certificate_start->getInputTextType() ?>" data-table="mytraining" data-field="x_certificate_start" name="x_certificate_start" id="x_certificate_start" placeholder="<?= HtmlEncode($Page->certificate_start->getPlaceHolder()) ?>" value="<?= $Page->certificate_start->EditValue ?>"<?= $Page->certificate_start->editAttributes() ?> aria-describedby="x_certificate_start_help">
<?= $Page->certificate_start->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->certificate_start->getErrorMessage() ?></div>
<?php if (!$Page->certificate_start->ReadOnly && !$Page->certificate_start->Disabled && !isset($Page->certificate_start->EditAttrs["readonly"]) && !isset($Page->certificate_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytrainingadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytrainingadd", "x_certificate_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->certificate_end->Visible) { // certificate_end ?>
    <div id="r_certificate_end" class="form-group row">
        <label id="elh_mytraining_certificate_end" for="x_certificate_end" class="<?= $Page->LeftColumnClass ?>"><?= $Page->certificate_end->caption() ?><?= $Page->certificate_end->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->certificate_end->cellAttributes() ?>>
<span id="el_mytraining_certificate_end">
<input type="<?= $Page->certificate_end->getInputTextType() ?>" data-table="mytraining" data-field="x_certificate_end" name="x_certificate_end" id="x_certificate_end" placeholder="<?= HtmlEncode($Page->certificate_end->getPlaceHolder()) ?>" value="<?= $Page->certificate_end->EditValue ?>"<?= $Page->certificate_end->editAttributes() ?> aria-describedby="x_certificate_end_help">
<?= $Page->certificate_end->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->certificate_end->getErrorMessage() ?></div>
<?php if (!$Page->certificate_end->ReadOnly && !$Page->certificate_end->Disabled && !isset($Page->certificate_end->EditAttrs["readonly"]) && !isset($Page->certificate_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmytrainingadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fmytrainingadd", "x_certificate_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <div id="r_notes" class="form-group row">
        <label id="elh_mytraining_notes" for="x_notes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notes->caption() ?><?= $Page->notes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->notes->cellAttributes() ?>>
<span id="el_mytraining_notes">
<textarea data-table="mytraining" data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>"<?= $Page->notes->editAttributes() ?> aria-describedby="x_notes_help"><?= $Page->notes->EditValue ?></textarea>
<?= $Page->notes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->training_document->Visible) { // training_document ?>
    <div id="r_training_document" class="form-group row">
        <label id="elh_mytraining_training_document" class="<?= $Page->LeftColumnClass ?>"><?= $Page->training_document->caption() ?><?= $Page->training_document->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_document->cellAttributes() ?>>
<span id="el_mytraining_training_document">
<div id="fd_x_training_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->training_document->title() ?>" data-table="mytraining" data-field="x_training_document" name="x_training_document" id="x_training_document" lang="<?= CurrentLanguageID() ?>"<?= $Page->training_document->editAttributes() ?><?= ($Page->training_document->ReadOnly || $Page->training_document->Disabled) ? " disabled" : "" ?> aria-describedby="x_training_document_help">
        <label class="custom-file-label ew-file-label" for="x_training_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->training_document->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->training_document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_training_document" id= "fn_x_training_document" value="<?= $Page->training_document->Upload->FileName ?>">
<input type="hidden" name="fa_x_training_document" id= "fa_x_training_document" value="0">
<input type="hidden" name="fs_x_training_document" id= "fs_x_training_document" value="255">
<input type="hidden" name="fx_x_training_document" id= "fx_x_training_document" value="<?= $Page->training_document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_training_document" id= "fm_x_training_document" value="<?= $Page->training_document->UploadMaxFileSize ?>">
</div>
<table id="ft_x_training_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
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
    ew.addEventHandlers("mytraining");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
