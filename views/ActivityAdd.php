<?php

namespace MEM\prjMitralPHP;

// Page object
$ActivityAdd = &$Page;
?>
<script>
if (!ew.vars.tables.activity) ew.vars.tables.activity = <?= JsonEncode(GetClientVar("tables", "activity")) ?>;
var currentForm, currentPageID;
var factivityadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    factivityadd = currentForm = new ew.Form("factivityadd", "add");

    // Add fields
    var fields = ew.vars.tables.activity.fields;
    factivityadd.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["activity_date", [fields.activity_date.required ? ew.Validators.required(fields.activity_date.caption) : null, ew.Validators.datetime(5)], fields.activity_date.isInvalid],
        ["time_in", [fields.time_in.required ? ew.Validators.required(fields.time_in.caption) : null, ew.Validators.time], fields.time_in.isInvalid],
        ["time_out", [fields.time_out.required ? ew.Validators.required(fields.time_out.caption) : null, ew.Validators.time], fields.time_out.isInvalid],
        ["_action", [fields._action.required ? ew.Validators.required(fields._action.caption) : null], fields._action.isInvalid],
        ["document", [fields.document.required ? ew.Validators.fileRequired(fields.document.caption) : null], fields.document.isInvalid],
        ["notes", [fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = factivityadd,
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
    factivityadd.validate = function () {
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
    factivityadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    factivityadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    factivityadd.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("factivityadd");
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
<form name="factivityadd" id="factivityadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="activity">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "employee") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="employee">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label id="elh_activity_employee_username" for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_username->caption() ?><?= $Page->employee_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
<?php if ($Page->employee_username->getSessionValue() != "") { ?>
<span id="el_activity_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_username->getDisplayValue($Page->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_employee_username" name="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_activity_employee_username">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="activity_x_employee_username"
        data-table="activity"
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
    var el = document.querySelector("select[data-select2-id='activity_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "activity_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.activity.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activity_date->Visible) { // activity_date ?>
    <div id="r_activity_date" class="form-group row">
        <label id="elh_activity_activity_date" for="x_activity_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activity_date->caption() ?><?= $Page->activity_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activity_date->cellAttributes() ?>>
<span id="el_activity_activity_date">
<input type="<?= $Page->activity_date->getInputTextType() ?>" data-table="activity" data-field="x_activity_date" data-format="5" name="x_activity_date" id="x_activity_date" placeholder="<?= HtmlEncode($Page->activity_date->getPlaceHolder()) ?>" value="<?= $Page->activity_date->EditValue ?>"<?= $Page->activity_date->editAttributes() ?> aria-describedby="x_activity_date_help">
<?= $Page->activity_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->activity_date->getErrorMessage() ?></div>
<?php if (!$Page->activity_date->ReadOnly && !$Page->activity_date->Disabled && !isset($Page->activity_date->EditAttrs["readonly"]) && !isset($Page->activity_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivityadd", "datetimepicker"], function() {
    ew.createDateTimePicker("factivityadd", "x_activity_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
    <div id="r_time_in" class="form-group row">
        <label id="elh_activity_time_in" for="x_time_in" class="<?= $Page->LeftColumnClass ?>"><?= $Page->time_in->caption() ?><?= $Page->time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->time_in->cellAttributes() ?>>
<span id="el_activity_time_in">
<input type="<?= $Page->time_in->getInputTextType() ?>" data-table="activity" data-field="x_time_in" name="x_time_in" id="x_time_in" placeholder="<?= HtmlEncode($Page->time_in->getPlaceHolder()) ?>" value="<?= $Page->time_in->EditValue ?>"<?= $Page->time_in->editAttributes() ?> aria-describedby="x_time_in_help">
<?= $Page->time_in->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->time_in->getErrorMessage() ?></div>
<?php if (!$Page->time_in->ReadOnly && !$Page->time_in->Disabled && !isset($Page->time_in->EditAttrs["readonly"]) && !isset($Page->time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivityadd", "timepicker"], function() {
    ew.createTimePicker("factivityadd", "x_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
    <div id="r_time_out" class="form-group row">
        <label id="elh_activity_time_out" for="x_time_out" class="<?= $Page->LeftColumnClass ?>"><?= $Page->time_out->caption() ?><?= $Page->time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->time_out->cellAttributes() ?>>
<span id="el_activity_time_out">
<input type="<?= $Page->time_out->getInputTextType() ?>" data-table="activity" data-field="x_time_out" name="x_time_out" id="x_time_out" placeholder="<?= HtmlEncode($Page->time_out->getPlaceHolder()) ?>" value="<?= $Page->time_out->EditValue ?>"<?= $Page->time_out->editAttributes() ?> aria-describedby="x_time_out_help">
<?= $Page->time_out->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->time_out->getErrorMessage() ?></div>
<?php if (!$Page->time_out->ReadOnly && !$Page->time_out->Disabled && !isset($Page->time_out->EditAttrs["readonly"]) && !isset($Page->time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivityadd", "timepicker"], function() {
    ew.createTimePicker("factivityadd", "x_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <div id="r__action" class="form-group row">
        <label id="elh_activity__action" for="x__action" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_action->caption() ?><?= $Page->_action->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_action->cellAttributes() ?>>
<span id="el_activity__action">
<textarea data-table="activity" data-field="x__action" name="x__action" id="x__action" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->_action->getPlaceHolder()) ?>"<?= $Page->_action->editAttributes() ?> aria-describedby="x__action_help"><?= $Page->_action->EditValue ?></textarea>
<?= $Page->_action->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_action->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
    <div id="r_document" class="form-group row">
        <label id="elh_activity_document" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document->caption() ?><?= $Page->document->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->document->cellAttributes() ?>>
<span id="el_activity_document">
<div id="fd_x_document">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->document->title() ?>" data-table="activity" data-field="x_document" name="x_document" id="x_document" lang="<?= CurrentLanguageID() ?>"<?= $Page->document->editAttributes() ?><?= ($Page->document->ReadOnly || $Page->document->Disabled) ? " disabled" : "" ?> aria-describedby="x_document_help">
        <label class="custom-file-label ew-file-label" for="x_document"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->document->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_document" id= "fn_x_document" value="<?= $Page->document->Upload->FileName ?>">
<input type="hidden" name="fa_x_document" id= "fa_x_document" value="0">
<input type="hidden" name="fs_x_document" id= "fs_x_document" value="500">
<input type="hidden" name="fx_x_document" id= "fx_x_document" value="<?= $Page->document->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_document" id= "fm_x_document" value="<?= $Page->document->UploadMaxFileSize ?>">
</div>
<table id="ft_x_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <div id="r_notes" class="form-group row">
        <label id="elh_activity_notes" for="x_notes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->notes->caption() ?><?= $Page->notes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->notes->cellAttributes() ?>>
<span id="el_activity_notes">
<textarea data-table="activity" data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>"<?= $Page->notes->editAttributes() ?> aria-describedby="x_notes_help"><?= $Page->notes->EditValue ?></textarea>
<?= $Page->notes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage() ?></div>
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
    ew.addEventHandlers("activity");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
