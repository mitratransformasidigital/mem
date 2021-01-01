<?php

namespace MEM\prjMitralPHP;

// Page object
$PermitEdit = &$Page;
?>
<script>
if (!ew.vars.tables.permit) ew.vars.tables.permit = <?= JsonEncode(GetClientVar("tables", "permit")) ?>;
var currentForm, currentPageID;
var fpermitedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fpermitedit = currentForm = new ew.Form("fpermitedit", "edit");

    // Add fields
    var fields = ew.vars.tables.permit.fields;
    fpermitedit.addFields([
        ["permit_id", [fields.permit_id.required ? ew.Validators.required(fields.permit_id.caption) : null], fields.permit_id.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["permit_date", [fields.permit_date.required ? ew.Validators.required(fields.permit_date.caption) : null, ew.Validators.datetime(5)], fields.permit_date.isInvalid],
        ["permit_type", [fields.permit_type.required ? ew.Validators.required(fields.permit_type.caption) : null], fields.permit_type.isInvalid],
        ["document", [fields.document.required ? ew.Validators.fileRequired(fields.document.caption) : null], fields.document.isInvalid],
        ["note", [fields.note.required ? ew.Validators.required(fields.note.caption) : null], fields.note.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpermitedit,
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
    fpermitedit.validate = function () {
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
    fpermitedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpermitedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpermitedit.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    fpermitedit.lists.permit_type = <?= $Page->permit_type->toClientList($Page) ?>;
    loadjs.done("fpermitedit");
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
<form name="fpermitedit" id="fpermitedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="permit">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "employee") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="employee">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label id="elh_permit_employee_username" for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_username->caption() ?><?= $Page->employee_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
<?php if ($Page->employee_username->getSessionValue() != "") { ?>
<span id="el_permit_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_username->getDisplayValue($Page->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_employee_username" name="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_permit_employee_username">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="permit_x_employee_username"
        data-table="permit"
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
    var el = document.querySelector("select[data-select2-id='permit_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "permit_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->permit_date->Visible) { // permit_date ?>
    <div id="r_permit_date" class="form-group row">
        <label id="elh_permit_permit_date" for="x_permit_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->permit_date->caption() ?><?= $Page->permit_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->permit_date->cellAttributes() ?>>
<span id="el_permit_permit_date">
<input type="<?= $Page->permit_date->getInputTextType() ?>" data-table="permit" data-field="x_permit_date" data-format="5" name="x_permit_date" id="x_permit_date" placeholder="<?= HtmlEncode($Page->permit_date->getPlaceHolder()) ?>" value="<?= $Page->permit_date->EditValue ?>"<?= $Page->permit_date->editAttributes() ?> aria-describedby="x_permit_date_help">
<?= $Page->permit_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->permit_date->getErrorMessage() ?></div>
<?php if (!$Page->permit_date->ReadOnly && !$Page->permit_date->Disabled && !isset($Page->permit_date->EditAttrs["readonly"]) && !isset($Page->permit_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpermitedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fpermitedit", "x_permit_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->permit_type->Visible) { // permit_type ?>
    <div id="r_permit_type" class="form-group row">
        <label id="elh_permit_permit_type" for="x_permit_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->permit_type->caption() ?><?= $Page->permit_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->permit_type->cellAttributes() ?>>
<span id="el_permit_permit_type">
    <select
        id="x_permit_type"
        name="x_permit_type"
        class="form-control ew-select<?= $Page->permit_type->isInvalidClass() ?>"
        data-select2-id="permit_x_permit_type"
        data-table="permit"
        data-field="x_permit_type"
        data-value-separator="<?= $Page->permit_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->permit_type->getPlaceHolder()) ?>"
        <?= $Page->permit_type->editAttributes() ?>>
        <?= $Page->permit_type->selectOptionListHtml("x_permit_type") ?>
    </select>
    <?= $Page->permit_type->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->permit_type->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permit_x_permit_type']"),
        options = { name: "x_permit_type", selectId: "permit_x_permit_type", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.permit.fields.permit_type.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.permit_type.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
    <div id="r_document" class="form-group row">
        <label id="elh_permit_document" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document->caption() ?><?= $Page->document->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->document->cellAttributes() ?>>
<span id="el_permit_document">
<div id="fd_x_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->document->title() ?>" data-table="permit" data-field="x_document" name="x_document" id="x_document" lang="<?= CurrentLanguageID() ?>"<?= $Page->document->editAttributes() ?><?= ($Page->document->ReadOnly || $Page->document->Disabled) ? " disabled" : "" ?> aria-describedby="x_document_help">
        <label class="custom-file-label ew-file-label" for="x_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->document->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_document" id= "fn_x_document" value="<?= $Page->document->Upload->FileName ?>">
<input type="hidden" name="fa_x_document" id= "fa_x_document" value="<?= (Post("fa_x_document") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_document" id= "fs_x_document" value="250">
<input type="hidden" name="fx_x_document" id= "fx_x_document" value="<?= $Page->document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_document" id= "fm_x_document" value="<?= $Page->document->UploadMaxFileSize ?>">
</div>
<table id="ft_x_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->note->Visible) { // note ?>
    <div id="r_note" class="form-group row">
        <label id="elh_permit_note" for="x_note" class="<?= $Page->LeftColumnClass ?>"><?= $Page->note->caption() ?><?= $Page->note->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->note->cellAttributes() ?>>
<span id="el_permit_note">
<textarea data-table="permit" data-field="x_note" name="x_note" id="x_note" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->note->getPlaceHolder()) ?>"<?= $Page->note->editAttributes() ?> aria-describedby="x_note_help"><?= $Page->note->EditValue ?></textarea>
<?= $Page->note->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->note->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<span id="el_permit_permit_id">
<input type="hidden" data-table="permit" data-field="x_permit_id" data-hidden="1" name="x_permit_id" id="x_permit_id" value="<?= HtmlEncode($Page->permit_id->CurrentValue) ?>">
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
    ew.addEventHandlers("permit");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
