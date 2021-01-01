<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeAssetEdit = &$Page;
?>
<script>
if (!ew.vars.tables.employee_asset) ew.vars.tables.employee_asset = <?= JsonEncode(GetClientVar("tables", "employee_asset")) ?>;
var currentForm, currentPageID;
var femployee_assetedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    femployee_assetedit = currentForm = new ew.Form("femployee_assetedit", "edit");

    // Add fields
    var fields = ew.vars.tables.employee_asset.fields;
    femployee_assetedit.addFields([
        ["asset_id", [fields.asset_id.required ? ew.Validators.required(fields.asset_id.caption) : null], fields.asset_id.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["asset_name", [fields.asset_name.required ? ew.Validators.required(fields.asset_name.caption) : null], fields.asset_name.isInvalid],
        ["year", [fields.year.required ? ew.Validators.required(fields.year.caption) : null, ew.Validators.integer], fields.year.isInvalid],
        ["serial_number", [fields.serial_number.required ? ew.Validators.required(fields.serial_number.caption) : null], fields.serial_number.isInvalid],
        ["value", [fields.value.required ? ew.Validators.required(fields.value.caption) : null, ew.Validators.float], fields.value.isInvalid],
        ["asset_received", [fields.asset_received.required ? ew.Validators.required(fields.asset_received.caption) : null, ew.Validators.datetime(5)], fields.asset_received.isInvalid],
        ["asset_return", [fields.asset_return.required ? ew.Validators.required(fields.asset_return.caption) : null, ew.Validators.datetime(5)], fields.asset_return.isInvalid],
        ["asset_picture", [fields.asset_picture.required ? ew.Validators.fileRequired(fields.asset_picture.caption) : null], fields.asset_picture.isInvalid],
        ["notes", [fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_assetedit,
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
    femployee_assetedit.validate = function () {
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
    femployee_assetedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_assetedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_assetedit.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("femployee_assetedit");
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
<form name="femployee_assetedit" id="femployee_assetedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_asset">
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
        <label id="elh_employee_asset_employee_username" for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_username->caption() ?><?= $Page->employee_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
<?php if ($Page->employee_username->getSessionValue() != "") { ?>
<span id="el_employee_asset_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_username->getDisplayValue($Page->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_employee_username" name="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_employee_asset_employee_username">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_asset_x_employee_username"
        data-table="employee_asset"
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
    var el = document.querySelector("select[data-select2-id='employee_asset_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "employee_asset_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_asset.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asset_name->Visible) { // asset_name ?>
    <div id="r_asset_name" class="form-group row">
        <label id="elh_employee_asset_asset_name" for="x_asset_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asset_name->caption() ?><?= $Page->asset_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asset_name->cellAttributes() ?>>
<span id="el_employee_asset_asset_name">
<input type="<?= $Page->asset_name->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_name" name="x_asset_name" id="x_asset_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->asset_name->getPlaceHolder()) ?>" value="<?= $Page->asset_name->EditValue ?>"<?= $Page->asset_name->editAttributes() ?> aria-describedby="x_asset_name_help">
<?= $Page->asset_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->asset_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
    <div id="r_year" class="form-group row">
        <label id="elh_employee_asset_year" for="x_year" class="<?= $Page->LeftColumnClass ?>"><?= $Page->year->caption() ?><?= $Page->year->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->year->cellAttributes() ?>>
<span id="el_employee_asset_year">
<input type="<?= $Page->year->getInputTextType() ?>" data-table="employee_asset" data-field="x_year" name="x_year" id="x_year" size="30" placeholder="<?= HtmlEncode($Page->year->getPlaceHolder()) ?>" value="<?= $Page->year->EditValue ?>"<?= $Page->year->editAttributes() ?> aria-describedby="x_year_help">
<?= $Page->year->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->year->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->serial_number->Visible) { // serial_number ?>
    <div id="r_serial_number" class="form-group row">
        <label id="elh_employee_asset_serial_number" for="x_serial_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->serial_number->caption() ?><?= $Page->serial_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->serial_number->cellAttributes() ?>>
<span id="el_employee_asset_serial_number">
<input type="<?= $Page->serial_number->getInputTextType() ?>" data-table="employee_asset" data-field="x_serial_number" name="x_serial_number" id="x_serial_number" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->serial_number->getPlaceHolder()) ?>" value="<?= $Page->serial_number->EditValue ?>"<?= $Page->serial_number->editAttributes() ?> aria-describedby="x_serial_number_help">
<?= $Page->serial_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->serial_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->value->Visible) { // value ?>
    <div id="r_value" class="form-group row">
        <label id="elh_employee_asset_value" for="x_value" class="<?= $Page->LeftColumnClass ?>"><?= $Page->value->caption() ?><?= $Page->value->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->value->cellAttributes() ?>>
<span id="el_employee_asset_value">
<input type="<?= $Page->value->getInputTextType() ?>" data-table="employee_asset" data-field="x_value" name="x_value" id="x_value" size="30" placeholder="<?= HtmlEncode($Page->value->getPlaceHolder()) ?>" value="<?= $Page->value->EditValue ?>"<?= $Page->value->editAttributes() ?> aria-describedby="x_value_help">
<?= $Page->value->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->value->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asset_received->Visible) { // asset_received ?>
    <div id="r_asset_received" class="form-group row">
        <label id="elh_employee_asset_asset_received" for="x_asset_received" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asset_received->caption() ?><?= $Page->asset_received->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asset_received->cellAttributes() ?>>
<span id="el_employee_asset_asset_received">
<input type="<?= $Page->asset_received->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_received" data-format="5" name="x_asset_received" id="x_asset_received" placeholder="<?= HtmlEncode($Page->asset_received->getPlaceHolder()) ?>" value="<?= $Page->asset_received->EditValue ?>"<?= $Page->asset_received->editAttributes() ?> aria-describedby="x_asset_received_help">
<?= $Page->asset_received->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->asset_received->getErrorMessage() ?></div>
<?php if (!$Page->asset_received->ReadOnly && !$Page->asset_received->Disabled && !isset($Page->asset_received->EditAttrs["readonly"]) && !isset($Page->asset_received->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_assetedit", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_assetedit", "x_asset_received", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asset_return->Visible) { // asset_return ?>
    <div id="r_asset_return" class="form-group row">
        <label id="elh_employee_asset_asset_return" for="x_asset_return" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asset_return->caption() ?><?= $Page->asset_return->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asset_return->cellAttributes() ?>>
<span id="el_employee_asset_asset_return">
<input type="<?= $Page->asset_return->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_return" data-format="5" name="x_asset_return" id="x_asset_return" placeholder="<?= HtmlEncode($Page->asset_return->getPlaceHolder()) ?>" value="<?= $Page->asset_return->EditValue ?>"<?= $Page->asset_return->editAttributes() ?> aria-describedby="x_asset_return_help">
<?= $Page->asset_return->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->asset_return->getErrorMessage() ?></div>
<?php if (!$Page->asset_return->ReadOnly && !$Page->asset_return->Disabled && !isset($Page->asset_return->EditAttrs["readonly"]) && !isset($Page->asset_return->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_assetedit", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_assetedit", "x_asset_return", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asset_picture->Visible) { // asset_picture ?>
    <div id="r_asset_picture" class="form-group row">
        <label id="elh_employee_asset_asset_picture" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asset_picture->caption() ?><?= $Page->asset_picture->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asset_picture->cellAttributes() ?>>
<span id="el_employee_asset_asset_picture">
<div id="fd_x_asset_picture">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->asset_picture->title() ?>" data-table="employee_asset" data-field="x_asset_picture" name="x_asset_picture" id="x_asset_picture" lang="<?= CurrentLanguageID() ?>"<?= $Page->asset_picture->editAttributes() ?><?= ($Page->asset_picture->ReadOnly || $Page->asset_picture->Disabled) ? " disabled" : "" ?> aria-describedby="x_asset_picture_help">
        <label class="custom-file-label ew-file-label" for="x_asset_picture"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->asset_picture->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->asset_picture->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_asset_picture" id= "fn_x_asset_picture" value="<?= $Page->asset_picture->Upload->FileName ?>">
<input type="hidden" name="fa_x_asset_picture" id= "fa_x_asset_picture" value="<?= (Post("fa_x_asset_picture") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_asset_picture" id= "fs_x_asset_picture" value="150">
<input type="hidden" name="fx_x_asset_picture" id= "fx_x_asset_picture" value="<?= $Page->asset_picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_asset_picture" id= "fm_x_asset_picture" value="<?= $Page->asset_picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x_asset_picture" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <div id="r_notes" class="form-group row">
        <label id="elh_employee_asset_notes" for="x_notes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notes->caption() ?><?= $Page->notes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->notes->cellAttributes() ?>>
<span id="el_employee_asset_notes">
<textarea data-table="employee_asset" data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>"<?= $Page->notes->editAttributes() ?> aria-describedby="x_notes_help"><?= $Page->notes->EditValue ?></textarea>
<?= $Page->notes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<span id="el_employee_asset_asset_id">
<input type="hidden" data-table="employee_asset" data-field="x_asset_id" data-hidden="1" name="x_asset_id" id="x_asset_id" value="<?= HtmlEncode($Page->asset_id->CurrentValue) ?>">
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
    ew.addEventHandlers("employee_asset");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
