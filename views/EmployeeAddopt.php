<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeAddopt = &$Page;
?>
<script>
if (!ew.vars.tables.employee) ew.vars.tables.employee = <?= JsonEncode(GetClientVar("tables", "employee")) ?>;
var currentForm, currentPageID;
var femployeeaddopt;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "addopt";
    femployeeaddopt = currentForm = new ew.Form("femployeeaddopt", "addopt");

    // Add fields
    var fields = ew.vars.tables.employee.fields;
    femployeeaddopt.addFields([
        ["employee_name", [fields.employee_name.required ? ew.Validators.required(fields.employee_name.caption) : null], fields.employee_name.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["employee_password", [fields.employee_password.required ? ew.Validators.required(fields.employee_password.caption) : null], fields.employee_password.isInvalid],
        ["employee_email", [fields.employee_email.required ? ew.Validators.required(fields.employee_email.caption) : null], fields.employee_email.isInvalid],
        ["birth_date", [fields.birth_date.required ? ew.Validators.required(fields.birth_date.caption) : null, ew.Validators.datetime(5)], fields.birth_date.isInvalid],
        ["religion", [fields.religion.required ? ew.Validators.required(fields.religion.caption) : null], fields.religion.isInvalid],
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
        ["position_id", [fields.position_id.required ? ew.Validators.required(fields.position_id.caption) : null], fields.position_id.isInvalid],
        ["status_id", [fields.status_id.required ? ew.Validators.required(fields.status_id.caption) : null], fields.status_id.isInvalid],
        ["skill_id", [fields.skill_id.required ? ew.Validators.required(fields.skill_id.caption) : null], fields.skill_id.isInvalid],
        ["office_id", [fields.office_id.required ? ew.Validators.required(fields.office_id.caption) : null], fields.office_id.isInvalid],
        ["hire_date", [fields.hire_date.required ? ew.Validators.required(fields.hire_date.caption) : null, ew.Validators.datetime(5)], fields.hire_date.isInvalid],
        ["termination_date", [fields.termination_date.required ? ew.Validators.required(fields.termination_date.caption) : null, ew.Validators.datetime(5)], fields.termination_date.isInvalid],
        ["user_level", [fields.user_level.required ? ew.Validators.required(fields.user_level.caption) : null], fields.user_level.isInvalid],
        ["technical_skill", [fields.technical_skill.required ? ew.Validators.required(fields.technical_skill.caption) : null], fields.technical_skill.isInvalid],
        ["about_me", [fields.about_me.required ? ew.Validators.required(fields.about_me.caption) : null], fields.about_me.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployeeaddopt,
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
    femployeeaddopt.validate = function () {
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
    femployeeaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployeeaddopt.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployeeaddopt.lists.religion = <?= $Page->religion->toClientList($Page) ?>;
    femployeeaddopt.lists.city_id = <?= $Page->city_id->toClientList($Page) ?>;
    femployeeaddopt.lists.position_id = <?= $Page->position_id->toClientList($Page) ?>;
    femployeeaddopt.lists.status_id = <?= $Page->status_id->toClientList($Page) ?>;
    femployeeaddopt.lists.skill_id = <?= $Page->skill_id->toClientList($Page) ?>;
    femployeeaddopt.lists.office_id = <?= $Page->office_id->toClientList($Page) ?>;
    femployeeaddopt.lists.user_level = <?= $Page->user_level->toClientList($Page) ?>;
    loadjs.done("femployeeaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="femployeeaddopt" id="femployeeaddopt" class="ew-form ew-horizontal" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="employee">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->employee_name->Visible) { // employee_name ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_employee_name"><?= $Page->employee_name->caption() ?><?= $Page->employee_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->employee_name->getInputTextType() ?>" data-table="employee" data-field="x_employee_name" name="x_employee_name" id="x_employee_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->employee_name->getPlaceHolder()) ?>" value="<?= $Page->employee_name->EditValue ?>"<?= $Page->employee_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_name->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_employee_username"><?= $Page->employee_username->caption() ?><?= $Page->employee_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->employee_username->getInputTextType() ?>" data-table="employee" data-field="x_employee_username" name="x_employee_username" id="x_employee_username" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>" value="<?= $Page->employee_username->EditValue ?>"<?= $Page->employee_username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_employee_password"><?= $Page->employee_password->caption() ?><?= $Page->employee_password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div class="input-group">
    <input type="password" name="x_employee_password" id="x_employee_password" autocomplete="new-password" data-field="x_employee_password" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_password->getPlaceHolder()) ?>"<?= $Page->employee_password->editAttributes() ?>>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div>
</div>
<div class="invalid-feedback"><?= $Page->employee_password->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_employee_email"><?= $Page->employee_email->caption() ?><?= $Page->employee_email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->employee_email->getInputTextType() ?>" data-table="employee" data-field="x_employee_email" name="x_employee_email" id="x_employee_email" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_email->getPlaceHolder()) ?>" value="<?= $Page->employee_email->EditValue ?>"<?= $Page->employee_email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_email->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_birth_date"><?= $Page->birth_date->caption() ?><?= $Page->birth_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->birth_date->getInputTextType() ?>" data-table="employee" data-field="x_birth_date" data-format="5" name="x_birth_date" id="x_birth_date" placeholder="<?= HtmlEncode($Page->birth_date->getPlaceHolder()) ?>" value="<?= $Page->birth_date->EditValue ?>"<?= $Page->birth_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->birth_date->getErrorMessage() ?></div>
<?php if (!$Page->birth_date->ReadOnly && !$Page->birth_date->Disabled && !isset($Page->birth_date->EditAttrs["readonly"]) && !isset($Page->birth_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployeeaddopt", "datetimepicker"], function() {
    ew.createDateTimePicker("femployeeaddopt", "x_birth_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_religion"><?= $Page->religion->caption() ?><?= $Page->religion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
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
</div>
    </div>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_nik"><?= $Page->nik->caption() ?><?= $Page->nik->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->nik->getInputTextType() ?>" data-table="employee" data-field="x_nik" name="x_nik" id="x_nik" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->nik->getPlaceHolder()) ?>" value="<?= $Page->nik->EditValue ?>"<?= $Page->nik->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nik->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_npwp"><?= $Page->npwp->caption() ?><?= $Page->npwp->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->npwp->getInputTextType() ?>" data-table="employee" data-field="x_npwp" name="x_npwp" id="x_npwp" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->npwp->getPlaceHolder()) ?>" value="<?= $Page->npwp->EditValue ?>"<?= $Page->npwp->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->npwp->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_address"><?= $Page->address->caption() ?><?= $Page->address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->address->getInputTextType() ?>" data-table="employee" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>" value="<?= $Page->address->EditValue ?>"<?= $Page->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->address->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_city_id"><?= $Page->city_id->caption() ?><?= $Page->city_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div class="input-group flex-nowrap">
    <select
        id="x_city_id"
        name="x_city_id"
        class="form-control ew-select<?= $Page->city_id->isInvalidClass() ?>"
        data-select2-id="employee_x_city_id"
        data-table="employee"
        data-field="x_city_id"
        data-value-separator="<?= $Page->city_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>"
        <?= $Page->city_id->editAttributes() ?>>
        <?= $Page->city_id->selectOptionListHtml("x_city_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_city") && !$Page->city_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_city_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->city_id->caption() ?>" data-title="<?= $Page->city_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_city_id',url:'<?= GetUrl("mastercityaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->city_id->getErrorMessage() ?></div>
<?= $Page->city_id->Lookup->getParamTag($Page, "p_x_city_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_x_city_id']"),
        options = { name: "x_city_id", selectId: "employee_x_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_postal_code"><?= $Page->postal_code->caption() ?><?= $Page->postal_code->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->postal_code->getInputTextType() ?>" data-table="employee" data-field="x_postal_code" name="x_postal_code" id="x_postal_code" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->postal_code->getPlaceHolder()) ?>" value="<?= $Page->postal_code->EditValue ?>"<?= $Page->postal_code->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->postal_code->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->bank_number->Visible) { // bank_number ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_bank_number"><?= $Page->bank_number->caption() ?><?= $Page->bank_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->bank_number->getInputTextType() ?>" data-table="employee" data-field="x_bank_number" name="x_bank_number" id="x_bank_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->bank_number->getPlaceHolder()) ?>" value="<?= $Page->bank_number->EditValue ?>"<?= $Page->bank_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->bank_number->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_bank_name"><?= $Page->bank_name->caption() ?><?= $Page->bank_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->bank_name->getInputTextType() ?>" data-table="employee" data-field="x_bank_name" name="x_bank_name" id="x_bank_name" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->bank_name->getPlaceHolder()) ?>" value="<?= $Page->bank_name->EditValue ?>"<?= $Page->bank_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->bank_name->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->scan_ktp->caption() ?><?= $Page->scan_ktp->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div id="fd_x_scan_ktp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->scan_ktp->title() ?>" data-table="employee" data-field="x_scan_ktp" name="x_scan_ktp" id="x_scan_ktp" lang="<?= CurrentLanguageID() ?>"<?= $Page->scan_ktp->editAttributes() ?><?= ($Page->scan_ktp->ReadOnly || $Page->scan_ktp->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x_scan_ktp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->scan_ktp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_scan_ktp" id= "fn_x_scan_ktp" value="<?= $Page->scan_ktp->Upload->FileName ?>">
<input type="hidden" name="fa_x_scan_ktp" id= "fa_x_scan_ktp" value="0">
<input type="hidden" name="fs_x_scan_ktp" id= "fs_x_scan_ktp" value="200">
<input type="hidden" name="fx_x_scan_ktp" id= "fx_x_scan_ktp" value="<?= $Page->scan_ktp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_scan_ktp" id= "fm_x_scan_ktp" value="<?= $Page->scan_ktp->UploadMaxFileSize ?>">
</div>
<table id="ft_x_scan_ktp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</div>
    </div>
<?php } ?>
<?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->scan_npwp->caption() ?><?= $Page->scan_npwp->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div id="fd_x_scan_npwp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->scan_npwp->title() ?>" data-table="employee" data-field="x_scan_npwp" name="x_scan_npwp" id="x_scan_npwp" lang="<?= CurrentLanguageID() ?>"<?= $Page->scan_npwp->editAttributes() ?><?= ($Page->scan_npwp->ReadOnly || $Page->scan_npwp->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x_scan_npwp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->scan_npwp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_scan_npwp" id= "fn_x_scan_npwp" value="<?= $Page->scan_npwp->Upload->FileName ?>">
<input type="hidden" name="fa_x_scan_npwp" id= "fa_x_scan_npwp" value="0">
<input type="hidden" name="fs_x_scan_npwp" id= "fs_x_scan_npwp" value="250">
<input type="hidden" name="fx_x_scan_npwp" id= "fx_x_scan_npwp" value="<?= $Page->scan_npwp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_scan_npwp" id= "fm_x_scan_npwp" value="<?= $Page->scan_npwp->UploadMaxFileSize ?>">
</div>
<table id="ft_x_scan_npwp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</div>
    </div>
<?php } ?>
<?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->curiculum_vitae->caption() ?><?= $Page->curiculum_vitae->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div id="fd_x_curiculum_vitae">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->curiculum_vitae->title() ?>" data-table="employee" data-field="x_curiculum_vitae" name="x_curiculum_vitae" id="x_curiculum_vitae" lang="<?= CurrentLanguageID() ?>"<?= $Page->curiculum_vitae->editAttributes() ?><?= ($Page->curiculum_vitae->ReadOnly || $Page->curiculum_vitae->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x_curiculum_vitae"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->curiculum_vitae->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_curiculum_vitae" id= "fn_x_curiculum_vitae" value="<?= $Page->curiculum_vitae->Upload->FileName ?>">
<input type="hidden" name="fa_x_curiculum_vitae" id= "fa_x_curiculum_vitae" value="0">
<input type="hidden" name="fs_x_curiculum_vitae" id= "fs_x_curiculum_vitae" value="250">
<input type="hidden" name="fx_x_curiculum_vitae" id= "fx_x_curiculum_vitae" value="<?= $Page->curiculum_vitae->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_curiculum_vitae" id= "fm_x_curiculum_vitae" value="<?= $Page->curiculum_vitae->UploadMaxFileSize ?>">
</div>
<table id="ft_x_curiculum_vitae" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</div>
    </div>
<?php } ?>
<?php if ($Page->position_id->Visible) { // position_id ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_position_id"><?= $Page->position_id->caption() ?><?= $Page->position_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div class="input-group flex-nowrap">
    <select
        id="x_position_id"
        name="x_position_id"
        class="form-control ew-select<?= $Page->position_id->isInvalidClass() ?>"
        data-select2-id="employee_x_position_id"
        data-table="employee"
        data-field="x_position_id"
        data-value-separator="<?= $Page->position_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->position_id->getPlaceHolder()) ?>"
        <?= $Page->position_id->editAttributes() ?>>
        <?= $Page->position_id->selectOptionListHtml("x_position_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_position") && !$Page->position_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_position_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->position_id->caption() ?>" data-title="<?= $Page->position_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_position_id',url:'<?= GetUrl("masterpositionaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->position_id->getErrorMessage() ?></div>
<?= $Page->position_id->Lookup->getParamTag($Page, "p_x_position_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_x_position_id']"),
        options = { name: "x_position_id", selectId: "employee_x_position_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee.fields.position_id.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_status_id"><?= $Page->status_id->caption() ?><?= $Page->status_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div class="input-group flex-nowrap">
    <select
        id="x_status_id"
        name="x_status_id"
        class="form-control ew-select<?= $Page->status_id->isInvalidClass() ?>"
        data-select2-id="employee_x_status_id"
        data-table="employee"
        data-field="x_status_id"
        data-value-separator="<?= $Page->status_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status_id->getPlaceHolder()) ?>"
        <?= $Page->status_id->editAttributes() ?>>
        <?= $Page->status_id->selectOptionListHtml("x_status_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_status") && !$Page->status_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_status_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->status_id->caption() ?>" data-title="<?= $Page->status_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_status_id',url:'<?= GetUrl("masterstatusaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->status_id->getErrorMessage() ?></div>
<?= $Page->status_id->Lookup->getParamTag($Page, "p_x_status_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_x_status_id']"),
        options = { name: "x_status_id", selectId: "employee_x_status_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee.fields.status_id.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_skill_id"><?= $Page->skill_id->caption() ?><?= $Page->skill_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div class="input-group flex-nowrap">
    <select
        id="x_skill_id"
        name="x_skill_id"
        class="form-control ew-select<?= $Page->skill_id->isInvalidClass() ?>"
        data-select2-id="employee_x_skill_id"
        data-table="employee"
        data-field="x_skill_id"
        data-value-separator="<?= $Page->skill_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->skill_id->getPlaceHolder()) ?>"
        <?= $Page->skill_id->editAttributes() ?>>
        <?= $Page->skill_id->selectOptionListHtml("x_skill_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_skill") && !$Page->skill_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_skill_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->skill_id->caption() ?>" data-title="<?= $Page->skill_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_skill_id',url:'<?= GetUrl("masterskilladdopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->skill_id->getErrorMessage() ?></div>
<?= $Page->skill_id->Lookup->getParamTag($Page, "p_x_skill_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_x_skill_id']"),
        options = { name: "x_skill_id", selectId: "employee_x_skill_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee.fields.skill_id.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_office_id"><?= $Page->office_id->caption() ?><?= $Page->office_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div class="input-group flex-nowrap">
    <select
        id="x_office_id"
        name="x_office_id"
        class="form-control ew-select<?= $Page->office_id->isInvalidClass() ?>"
        data-select2-id="employee_x_office_id"
        data-table="employee"
        data-field="x_office_id"
        data-value-separator="<?= $Page->office_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->office_id->getPlaceHolder()) ?>"
        <?= $Page->office_id->editAttributes() ?>>
        <?= $Page->office_id->selectOptionListHtml("x_office_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_office") && !$Page->office_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_office_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->office_id->caption() ?>" data-title="<?= $Page->office_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_office_id',url:'<?= GetUrl("masterofficeaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->office_id->getErrorMessage() ?></div>
<?= $Page->office_id->Lookup->getParamTag($Page, "p_x_office_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_x_office_id']"),
        options = { name: "x_office_id", selectId: "employee_x_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee.fields.office_id.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_hire_date"><?= $Page->hire_date->caption() ?><?= $Page->hire_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->hire_date->getInputTextType() ?>" data-table="employee" data-field="x_hire_date" data-format="5" name="x_hire_date" id="x_hire_date" placeholder="<?= HtmlEncode($Page->hire_date->getPlaceHolder()) ?>" value="<?= $Page->hire_date->EditValue ?>"<?= $Page->hire_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->hire_date->getErrorMessage() ?></div>
<?php if (!$Page->hire_date->ReadOnly && !$Page->hire_date->Disabled && !isset($Page->hire_date->EditAttrs["readonly"]) && !isset($Page->hire_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployeeaddopt", "datetimepicker"], function() {
    ew.createDateTimePicker("femployeeaddopt", "x_hire_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_termination_date"><?= $Page->termination_date->caption() ?><?= $Page->termination_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->termination_date->getInputTextType() ?>" data-table="employee" data-field="x_termination_date" data-format="5" name="x_termination_date" id="x_termination_date" placeholder="<?= HtmlEncode($Page->termination_date->getPlaceHolder()) ?>" value="<?= $Page->termination_date->EditValue ?>"<?= $Page->termination_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->termination_date->getErrorMessage() ?></div>
<?php if (!$Page->termination_date->ReadOnly && !$Page->termination_date->Disabled && !isset($Page->termination_date->EditAttrs["readonly"]) && !isset($Page->termination_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployeeaddopt", "datetimepicker"], function() {
    ew.createDateTimePicker("femployeeaddopt", "x_termination_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->user_level->Visible) { // user_level ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_user_level"><?= $Page->user_level->caption() ?><?= $Page->user_level->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->user_level->getDisplayValue($Page->user_level->EditValue))) ?>">
<?php } else { ?>
<div class="input-group flex-nowrap">
    <select
        id="x_user_level"
        name="x_user_level"
        class="form-control ew-select<?= $Page->user_level->isInvalidClass() ?>"
        data-select2-id="employee_x_user_level"
        data-table="employee"
        data-field="x_user_level"
        data-value-separator="<?= $Page->user_level->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->user_level->getPlaceHolder()) ?>"
        <?= $Page->user_level->editAttributes() ?>>
        <?= $Page->user_level->selectOptionListHtml("x_user_level") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "userlevels") && !$Page->user_level->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_user_level" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->user_level->caption() ?>" data-title="<?= $Page->user_level->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_user_level',url:'<?= GetUrl("userlevelsaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->user_level->getErrorMessage() ?></div>
<?= $Page->user_level->Lookup->getParamTag($Page, "p_x_user_level") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_x_user_level']"),
        options = { name: "x_user_level", selectId: "employee_x_user_level", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee.fields.user_level.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_technical_skill"><?= $Page->technical_skill->caption() ?><?= $Page->technical_skill->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<textarea data-table="employee" data-field="x_technical_skill" name="x_technical_skill" id="x_technical_skill" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->technical_skill->getPlaceHolder()) ?>"<?= $Page->technical_skill->editAttributes() ?>><?= $Page->technical_skill->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Page->technical_skill->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_about_me"><?= $Page->about_me->caption() ?><?= $Page->about_me->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<textarea data-table="employee" data-field="x_about_me" name="x_about_me" id="x_about_me" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->about_me->getPlaceHolder()) ?>"<?= $Page->about_me->editAttributes() ?>><?= $Page->about_me->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Page->about_me->getErrorMessage() ?></div>
</div>
    </div>
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
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
