<?php

namespace MEM\prjMitralPHP;

// Page object
$Register = &$Page;
?>
<script>
if (!ew.vars.tables.employee) ew.vars.tables.employee = <?= JsonEncode(GetClientVar("tables", "employee")) ?>;
var currentForm, currentPageID;
var fregister;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "register";
    fregister = currentForm = new ew.Form("fregister", "register");

    // Add fields
    var fields = ew.vars.tables.employee.fields;
    fregister.addFields([
        ["employee_name", [fields.employee_name.required ? ew.Validators.required(fields.employee_name.caption) : null], fields.employee_name.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null, ew.Validators.username(fields.employee_username.raw)], fields.employee_username.isInvalid],
        ["c_employee_password", [ew.Validators.required(ew.language.phrase("ConfirmPassword")), ew.Validators.mismatchPassword], fields.employee_password.isInvalid],
        ["employee_password", [fields.employee_password.required ? ew.Validators.required(fields.employee_password.caption) : null, ew.Validators.password(fields.employee_password.raw)], fields.employee_password.isInvalid],
        ["employee_email", [fields.employee_email.required ? ew.Validators.required(fields.employee_email.caption) : null], fields.employee_email.isInvalid],
        ["birth_date", [fields.birth_date.required ? ew.Validators.required(fields.birth_date.caption) : null, ew.Validators.datetime(5)], fields.birth_date.isInvalid],
        ["religion", [fields.religion.required ? ew.Validators.required(fields.religion.caption) : null], fields.religion.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fregister,
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
    fregister.validate = function () {
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
        return true;
    }

    // Form_CustomValidate
    fregister.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fregister.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fregister.lists.religion = <?= $Page->religion->toClientList($Page) ?>;
    loadjs.done("fregister");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fregister" id="fregister" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="t" value="employee">
<?php if ($Page->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<div class="ew-register-div"><!-- page* -->
<?php if ($Page->employee_name->Visible) { // employee_name ?>
    <div id="r_employee_name" class="form-group row">
        <label id="elh_employee_employee_name" for="x_employee_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_name->caption() ?><?= $Page->employee_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_name->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_employee_employee_name">
<input type="<?= $Page->employee_name->getInputTextType() ?>" data-table="employee" data-field="x_employee_name" name="x_employee_name" id="x_employee_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->employee_name->getPlaceHolder()) ?>" value="<?= $Page->employee_name->EditValue ?>"<?= $Page->employee_name->editAttributes() ?> aria-describedby="x_employee_name_help">
<?= $Page->employee_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_name->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_employee_employee_name">
<span<?= $Page->employee_name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_name->getDisplayValue($Page->employee_name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee" data-field="x_employee_name" data-hidden="1" name="x_employee_name" id="x_employee_name" value="<?= HtmlEncode($Page->employee_name->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label id="elh_employee_employee_username" for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_username->caption() ?><?= $Page->employee_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_employee_employee_username">
<input type="<?= $Page->employee_username->getInputTextType() ?>" data-table="employee" data-field="x_employee_username" name="x_employee_username" id="x_employee_username" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>" value="<?= $Page->employee_username->EditValue ?>"<?= $Page->employee_username->editAttributes() ?> aria-describedby="x_employee_username_help">
<?= $Page->employee_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_employee_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_username->getDisplayValue($Page->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee" data-field="x_employee_username" data-hidden="1" name="x_employee_username" id="x_employee_username" value="<?= HtmlEncode($Page->employee_username->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
    <div id="r_employee_password" class="form-group row">
        <label id="elh_employee_employee_password" for="x_employee_password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_password->caption() ?><?= $Page->employee_password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_password->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_employee_employee_password">
<div class="input-group">
    <input type="password" name="x_employee_password" id="x_employee_password" autocomplete="new-password" data-field="x_employee_password" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_password->getPlaceHolder()) ?>"<?= $Page->employee_password->editAttributes() ?> aria-describedby="x_employee_password_help">
    <div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div>
</div>
<?= $Page->employee_password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_password->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_employee_employee_password">
<span<?= $Page->employee_password->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_password->getDisplayValue($Page->employee_password->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee" data-field="x_employee_password" data-hidden="1" name="x_employee_password" id="x_employee_password" value="<?= HtmlEncode($Page->employee_password->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
    <div id="r_c_employee_password" class="form-group row">
        <label id="elh_c_employee_employee_password" for="c_employee_password" class="<?= $Page->LeftColumnClass ?>"><?= $Language->phrase("Confirm") ?> <?= $Page->employee_password->caption() ?><?= $Page->employee_password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_password->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_c_employee_employee_password">
<div class="input-group">
    <input type="password" name="c_employee_password" id="c_employee_password" autocomplete="new-password" data-field="x_employee_password" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_password->getPlaceHolder()) ?>"<?= $Page->employee_password->editAttributes() ?> aria-describedby="x_employee_password_help">
    <div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div>
</div>
<?= $Page->employee_password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_password->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_c_employee_employee_password">
<span<?= $Page->employee_password->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_password->getDisplayValue($Page->employee_password->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee" data-field="x_employee_password" data-hidden="1" name="c_employee_password" id="c_employee_password" value="<?= HtmlEncode($Page->employee_password->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
    <div id="r_employee_email" class="form-group row">
        <label id="elh_employee_employee_email" for="x_employee_email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_email->caption() ?><?= $Page->employee_email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_email->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_employee_employee_email">
<input type="<?= $Page->employee_email->getInputTextType() ?>" data-table="employee" data-field="x_employee_email" name="x_employee_email" id="x_employee_email" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_email->getPlaceHolder()) ?>" value="<?= $Page->employee_email->EditValue ?>"<?= $Page->employee_email->editAttributes() ?> aria-describedby="x_employee_email_help">
<?= $Page->employee_email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_email->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_employee_employee_email">
<span<?= $Page->employee_email->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_email->getDisplayValue($Page->employee_email->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee" data-field="x_employee_email" data-hidden="1" name="x_employee_email" id="x_employee_email" value="<?= HtmlEncode($Page->employee_email->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
    <div id="r_birth_date" class="form-group row">
        <label id="elh_employee_birth_date" for="x_birth_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->birth_date->caption() ?><?= $Page->birth_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->birth_date->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_employee_birth_date">
<input type="<?= $Page->birth_date->getInputTextType() ?>" data-table="employee" data-field="x_birth_date" data-format="5" name="x_birth_date" id="x_birth_date" placeholder="<?= HtmlEncode($Page->birth_date->getPlaceHolder()) ?>" value="<?= $Page->birth_date->EditValue ?>"<?= $Page->birth_date->editAttributes() ?> aria-describedby="x_birth_date_help">
<?= $Page->birth_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->birth_date->getErrorMessage() ?></div>
<?php if (!$Page->birth_date->ReadOnly && !$Page->birth_date->Disabled && !isset($Page->birth_date->EditAttrs["readonly"]) && !isset($Page->birth_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fregister", "datetimepicker"], function() {
    ew.createDateTimePicker("fregister", "x_birth_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_employee_birth_date">
<span<?= $Page->birth_date->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->birth_date->getDisplayValue($Page->birth_date->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee" data-field="x_birth_date" data-hidden="1" name="x_birth_date" id="x_birth_date" value="<?= HtmlEncode($Page->birth_date->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
    <div id="r_religion" class="form-group row">
        <label id="elh_employee_religion" for="x_religion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->religion->caption() ?><?= $Page->religion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->religion->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_employee_religion">
    <select
        id="x_religion"
        name="x_religion"
        class="form-control ew-select<?= $Page->religion->isInvalidClass() ?>"
        data-select2-id="employee_x_religion"
        data-table="employee"
        data-field="x_religion"
        data-value-separator="<?= $Page->religion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->religion->getPlaceHolder()) ?>"
        <?= $Page->religion->editAttributes() ?>>
        <?= $Page->religion->selectOptionListHtml("x_religion") ?>
    </select>
    <?= $Page->religion->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->religion->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_x_religion']"),
        options = { name: "x_religion", selectId: "employee_x_religion", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.employee.fields.religion.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee.fields.religion.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el_employee_religion">
<span<?= $Page->religion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->religion->getDisplayValue($Page->religion->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee" data-field="x_religion" data-hidden="1" name="x_religion" id="x_religion" value="<?= HtmlEncode($Page->religion->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" onclick="this.form.action.value='confirm';"><?= $Language->phrase("RegisterBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" onclick="this.form.action.value='cancel';"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
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
    ew.addEventHandlers("employee");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your startup script here, no need to add script tags.
});
</script>
