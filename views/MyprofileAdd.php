<?php

namespace MEM\prjMitralPHP;

// Page object
$MyprofileAdd = &$Page;
?>
<script>
if (!ew.vars.tables.myprofile) ew.vars.tables.myprofile = <?= JsonEncode(GetClientVar("tables", "myprofile")) ?>;
var currentForm, currentPageID;
var fmyprofileadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fmyprofileadd = currentForm = new ew.Form("fmyprofileadd", "add");

    // Add fields
    var fields = ew.vars.tables.myprofile.fields;
    fmyprofileadd.addFields([
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
        var f = fmyprofileadd,
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
    fmyprofileadd.validate = function () {
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
    fmyprofileadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmyprofileadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Multi-Page
    fmyprofileadd.multiPage = new ew.MultiPage("fmyprofileadd");

    // Dynamic selection lists
    fmyprofileadd.lists.city_id = <?= $Page->city_id->toClientList($Page) ?>;
    fmyprofileadd.lists.position_id = <?= $Page->position_id->toClientList($Page) ?>;
    fmyprofileadd.lists.religion = <?= $Page->religion->toClientList($Page) ?>;
    fmyprofileadd.lists.status_id = <?= $Page->status_id->toClientList($Page) ?>;
    fmyprofileadd.lists.skill_id = <?= $Page->skill_id->toClientList($Page) ?>;
    fmyprofileadd.lists.office_id = <?= $Page->office_id->toClientList($Page) ?>;
    loadjs.done("fmyprofileadd");
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
<form name="fmyprofileadd" id="fmyprofileadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="myprofile">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "master_office") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_office">
<input type="hidden" name="fk_office_id" value="<?= HtmlEncode($Page->office_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "master_position") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_position">
<input type="hidden" name="fk_position_id" value="<?= HtmlEncode($Page->position_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "master_skill") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_skill">
<input type="hidden" name="fk_skill_id" value="<?= HtmlEncode($Page->skill_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "master_status") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_status">
<input type="hidden" name="fk_status_id" value="<?= HtmlEncode($Page->status_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "master_city") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_city">
<input type="hidden" name="fk_city_id" value="<?= HtmlEncode($Page->city_id->getSessionValue()) ?>">
<?php } ?>
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav-tabs" id="Page"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navStyle() ?>">
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(1) ?>" href="#tab_myprofile1" data-toggle="tab"><?= $Page->pageCaption(1) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(2) ?>" href="#tab_myprofile2" data-toggle="tab"><?= $Page->pageCaption(2) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(3) ?>" href="#tab_myprofile3" data-toggle="tab"><?= $Page->pageCaption(3) ?></a></li>
        <li class="nav-item"><a class="nav-link<?= $Page->MultiPages->pageStyle(4) ?>" href="#tab_myprofile4" data-toggle="tab"><?= $Page->pageCaption(4) ?></a></li>
    </ul>
    <div class="tab-content"><!-- multi-page tabs .tab-content -->
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(1) ?>" id="tab_myprofile1"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->employee_name->Visible) { // employee_name ?>
    <div id="r_employee_name" class="form-group row">
        <label id="elh_myprofile_employee_name" for="x_employee_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_name->caption() ?><?= $Page->employee_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_name->cellAttributes() ?>>
<span id="el_myprofile_employee_name">
<input type="<?= $Page->employee_name->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_name" data-page="1" name="x_employee_name" id="x_employee_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->employee_name->getPlaceHolder()) ?>" value="<?= $Page->employee_name->EditValue ?>"<?= $Page->employee_name->editAttributes() ?> aria-describedby="x_employee_name_help">
<?= $Page->employee_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label id="elh_myprofile_employee_username" for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_username->caption() ?><?= $Page->employee_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_myprofile_employee_username">
<input type="<?= $Page->employee_username->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_username" data-page="1" name="x_employee_username" id="x_employee_username" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>" value="<?= $Page->employee_username->EditValue ?>"<?= $Page->employee_username->editAttributes() ?> aria-describedby="x_employee_username_help">
<?= $Page->employee_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
    <div id="r_employee_password" class="form-group row">
        <label id="elh_myprofile_employee_password" for="x_employee_password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_password->caption() ?><?= $Page->employee_password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_password->cellAttributes() ?>>
<span id="el_myprofile_employee_password">
<div class="input-group">
    <input type="password" name="x_employee_password" id="x_employee_password" autocomplete="new-password" data-field="x_employee_password" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_password->getPlaceHolder()) ?>"<?= $Page->employee_password->editAttributes() ?> aria-describedby="x_employee_password_help">
    <div class="input-group-append"><button type="button" class="btn btn-default ew-toggle-password rounded-right" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button></div>
</div>
<?= $Page->employee_password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_password->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
    <div id="r_employee_email" class="form-group row">
        <label id="elh_myprofile_employee_email" for="x_employee_email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_email->caption() ?><?= $Page->employee_email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_email->cellAttributes() ?>>
<span id="el_myprofile_employee_email">
<input type="<?= $Page->employee_email->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_email" data-page="1" name="x_employee_email" id="x_employee_email" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_email->getPlaceHolder()) ?>" value="<?= $Page->employee_email->EditValue ?>"<?= $Page->employee_email->editAttributes() ?> aria-describedby="x_employee_email_help">
<?= $Page->employee_email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_email->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
    <div id="r_birth_date" class="form-group row">
        <label id="elh_myprofile_birth_date" for="x_birth_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->birth_date->caption() ?><?= $Page->birth_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->birth_date->cellAttributes() ?>>
<span id="el_myprofile_birth_date">
<input type="<?= $Page->birth_date->getInputTextType() ?>" data-table="myprofile" data-field="x_birth_date" data-page="1" data-format="5" name="x_birth_date" id="x_birth_date" placeholder="<?= HtmlEncode($Page->birth_date->getPlaceHolder()) ?>" value="<?= $Page->birth_date->EditValue ?>"<?= $Page->birth_date->editAttributes() ?> aria-describedby="x_birth_date_help">
<?= $Page->birth_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->birth_date->getErrorMessage() ?></div>
<?php if (!$Page->birth_date->ReadOnly && !$Page->birth_date->Disabled && !isset($Page->birth_date->EditAttrs["readonly"]) && !isset($Page->birth_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofileadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofileadd", "x_birth_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
    <div id="r_nik" class="form-group row">
        <label id="elh_myprofile_nik" for="x_nik" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nik->caption() ?><?= $Page->nik->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nik->cellAttributes() ?>>
<span id="el_myprofile_nik">
<input type="<?= $Page->nik->getInputTextType() ?>" data-table="myprofile" data-field="x_nik" data-page="1" name="x_nik" id="x_nik" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->nik->getPlaceHolder()) ?>" value="<?= $Page->nik->EditValue ?>"<?= $Page->nik->editAttributes() ?> aria-describedby="x_nik_help">
<?= $Page->nik->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nik->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
    <div id="r_npwp" class="form-group row">
        <label id="elh_myprofile_npwp" for="x_npwp" class="<?= $Page->LeftColumnClass ?>"><?= $Page->npwp->caption() ?><?= $Page->npwp->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->npwp->cellAttributes() ?>>
<span id="el_myprofile_npwp">
<input type="<?= $Page->npwp->getInputTextType() ?>" data-table="myprofile" data-field="x_npwp" data-page="1" name="x_npwp" id="x_npwp" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->npwp->getPlaceHolder()) ?>" value="<?= $Page->npwp->EditValue ?>"<?= $Page->npwp->editAttributes() ?> aria-describedby="x_npwp_help">
<?= $Page->npwp->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->npwp->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <div id="r_address" class="form-group row">
        <label id="elh_myprofile_address" for="x_address" class="<?= $Page->LeftColumnClass ?>"><?= $Page->address->caption() ?><?= $Page->address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->address->cellAttributes() ?>>
<span id="el_myprofile_address">
<input type="<?= $Page->address->getInputTextType() ?>" data-table="myprofile" data-field="x_address" data-page="1" name="x_address" id="x_address" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>" value="<?= $Page->address->EditValue ?>"<?= $Page->address->editAttributes() ?> aria-describedby="x_address_help">
<?= $Page->address->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->address->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <div id="r_city_id" class="form-group row">
        <label id="elh_myprofile_city_id" for="x_city_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->city_id->caption() ?><?= $Page->city_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->city_id->cellAttributes() ?>>
<?php if ($Page->city_id->getSessionValue() != "") { ?>
<span id="el_myprofile_city_id">
<span<?= $Page->city_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->city_id->getDisplayValue($Page->city_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_city_id" name="x_city_id" value="<?= HtmlEncode($Page->city_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_myprofile_city_id">
<div class="input-group flex-nowrap">
    <select
        id="x_city_id"
        name="x_city_id"
        class="form-control ew-select<?= $Page->city_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x_city_id"
        data-table="myprofile"
        data-field="x_city_id"
        data-page="1"
        data-value-separator="<?= $Page->city_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>"
        <?= $Page->city_id->editAttributes() ?>>
        <?= $Page->city_id->selectOptionListHtml("x_city_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_city") && !$Page->city_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_city_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->city_id->caption() ?>" data-title="<?= $Page->city_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_city_id',url:'<?= GetUrl("mastercityaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->city_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->city_id->getErrorMessage() ?></div>
<?= $Page->city_id->Lookup->getParamTag($Page, "p_x_city_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x_city_id']"),
        options = { name: "x_city_id", selectId: "myprofile_x_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
    <div id="r_postal_code" class="form-group row">
        <label id="elh_myprofile_postal_code" for="x_postal_code" class="<?= $Page->LeftColumnClass ?>"><?= $Page->postal_code->caption() ?><?= $Page->postal_code->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->postal_code->cellAttributes() ?>>
<span id="el_myprofile_postal_code">
<input type="<?= $Page->postal_code->getInputTextType() ?>" data-table="myprofile" data-field="x_postal_code" data-page="1" name="x_postal_code" id="x_postal_code" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->postal_code->getPlaceHolder()) ?>" value="<?= $Page->postal_code->EditValue ?>"<?= $Page->postal_code->editAttributes() ?> aria-describedby="x_postal_code_help">
<?= $Page->postal_code->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->postal_code->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
    <div id="r_religion" class="form-group row">
        <label id="elh_myprofile_religion" for="x_religion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->religion->caption() ?><?= $Page->religion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->religion->cellAttributes() ?>>
<span id="el_myprofile_religion">
    <select
        id="x_religion"
        name="x_religion"
        class="form-control ew-select<?= $Page->religion->isInvalidClass() ?>"
        data-select2-id="myprofile_x_religion"
        data-table="myprofile"
        data-field="x_religion"
        data-page="1"
        data-value-separator="<?= $Page->religion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->religion->getPlaceHolder()) ?>"
        <?= $Page->religion->editAttributes() ?>>
        <?= $Page->religion->selectOptionListHtml("x_religion") ?>
    </select>
    <?= $Page->religion->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->religion->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x_religion']"),
        options = { name: "x_religion", selectId: "myprofile_x_religion", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.myprofile.fields.religion.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.religion.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(2) ?>" id="tab_myprofile2"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->bank_number->Visible) { // bank_number ?>
    <div id="r_bank_number" class="form-group row">
        <label id="elh_myprofile_bank_number" for="x_bank_number" class="<?= $Page->LeftColumnClass ?>"><?= $Page->bank_number->caption() ?><?= $Page->bank_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->bank_number->cellAttributes() ?>>
<span id="el_myprofile_bank_number">
<input type="<?= $Page->bank_number->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_number" data-page="2" name="x_bank_number" id="x_bank_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->bank_number->getPlaceHolder()) ?>" value="<?= $Page->bank_number->EditValue ?>"<?= $Page->bank_number->editAttributes() ?> aria-describedby="x_bank_number_help">
<?= $Page->bank_number->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->bank_number->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
    <div id="r_bank_name" class="form-group row">
        <label id="elh_myprofile_bank_name" for="x_bank_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->bank_name->caption() ?><?= $Page->bank_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->bank_name->cellAttributes() ?>>
<span id="el_myprofile_bank_name">
<input type="<?= $Page->bank_name->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_name" data-page="2" name="x_bank_name" id="x_bank_name" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->bank_name->getPlaceHolder()) ?>" value="<?= $Page->bank_name->EditValue ?>"<?= $Page->bank_name->editAttributes() ?> aria-describedby="x_bank_name_help">
<?= $Page->bank_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->bank_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
    <div id="r_scan_ktp" class="form-group row">
        <label id="elh_myprofile_scan_ktp" class="<?= $Page->LeftColumnClass ?>"><?= $Page->scan_ktp->caption() ?><?= $Page->scan_ktp->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->scan_ktp->cellAttributes() ?>>
<span id="el_myprofile_scan_ktp">
<div id="fd_x_scan_ktp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->scan_ktp->title() ?>" data-table="myprofile" data-field="x_scan_ktp" data-page="2" name="x_scan_ktp" id="x_scan_ktp" lang="<?= CurrentLanguageID() ?>"<?= $Page->scan_ktp->editAttributes() ?><?= ($Page->scan_ktp->ReadOnly || $Page->scan_ktp->Disabled) ? " disabled" : "" ?> aria-describedby="x_scan_ktp_help">
        <label class="custom-file-label ew-file-label" for="x_scan_ktp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->scan_ktp->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->scan_ktp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_scan_ktp" id= "fn_x_scan_ktp" value="<?= $Page->scan_ktp->Upload->FileName ?>">
<input type="hidden" name="fa_x_scan_ktp" id= "fa_x_scan_ktp" value="0">
<input type="hidden" name="fs_x_scan_ktp" id= "fs_x_scan_ktp" value="200">
<input type="hidden" name="fx_x_scan_ktp" id= "fx_x_scan_ktp" value="<?= $Page->scan_ktp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_scan_ktp" id= "fm_x_scan_ktp" value="<?= $Page->scan_ktp->UploadMaxFileSize ?>">
</div>
<table id="ft_x_scan_ktp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
    <div id="r_scan_npwp" class="form-group row">
        <label id="elh_myprofile_scan_npwp" class="<?= $Page->LeftColumnClass ?>"><?= $Page->scan_npwp->caption() ?><?= $Page->scan_npwp->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->scan_npwp->cellAttributes() ?>>
<span id="el_myprofile_scan_npwp">
<div id="fd_x_scan_npwp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->scan_npwp->title() ?>" data-table="myprofile" data-field="x_scan_npwp" data-page="2" name="x_scan_npwp" id="x_scan_npwp" lang="<?= CurrentLanguageID() ?>"<?= $Page->scan_npwp->editAttributes() ?><?= ($Page->scan_npwp->ReadOnly || $Page->scan_npwp->Disabled) ? " disabled" : "" ?> aria-describedby="x_scan_npwp_help">
        <label class="custom-file-label ew-file-label" for="x_scan_npwp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->scan_npwp->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->scan_npwp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_scan_npwp" id= "fn_x_scan_npwp" value="<?= $Page->scan_npwp->Upload->FileName ?>">
<input type="hidden" name="fa_x_scan_npwp" id= "fa_x_scan_npwp" value="0">
<input type="hidden" name="fs_x_scan_npwp" id= "fs_x_scan_npwp" value="250">
<input type="hidden" name="fx_x_scan_npwp" id= "fx_x_scan_npwp" value="<?= $Page->scan_npwp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_scan_npwp" id= "fm_x_scan_npwp" value="<?= $Page->scan_npwp->UploadMaxFileSize ?>">
</div>
<table id="ft_x_scan_npwp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
    <div id="r_curiculum_vitae" class="form-group row">
        <label id="elh_myprofile_curiculum_vitae" class="<?= $Page->LeftColumnClass ?>"><?= $Page->curiculum_vitae->caption() ?><?= $Page->curiculum_vitae->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->curiculum_vitae->cellAttributes() ?>>
<span id="el_myprofile_curiculum_vitae">
<div id="fd_x_curiculum_vitae">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->curiculum_vitae->title() ?>" data-table="myprofile" data-field="x_curiculum_vitae" data-page="2" name="x_curiculum_vitae" id="x_curiculum_vitae" lang="<?= CurrentLanguageID() ?>"<?= $Page->curiculum_vitae->editAttributes() ?><?= ($Page->curiculum_vitae->ReadOnly || $Page->curiculum_vitae->Disabled) ? " disabled" : "" ?> aria-describedby="x_curiculum_vitae_help">
        <label class="custom-file-label ew-file-label" for="x_curiculum_vitae"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->curiculum_vitae->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->curiculum_vitae->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_curiculum_vitae" id= "fn_x_curiculum_vitae" value="<?= $Page->curiculum_vitae->Upload->FileName ?>">
<input type="hidden" name="fa_x_curiculum_vitae" id= "fa_x_curiculum_vitae" value="0">
<input type="hidden" name="fs_x_curiculum_vitae" id= "fs_x_curiculum_vitae" value="250">
<input type="hidden" name="fx_x_curiculum_vitae" id= "fx_x_curiculum_vitae" value="<?= $Page->curiculum_vitae->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_curiculum_vitae" id= "fm_x_curiculum_vitae" value="<?= $Page->curiculum_vitae->UploadMaxFileSize ?>">
</div>
<table id="ft_x_curiculum_vitae" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(3) ?>" id="tab_myprofile3"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->position_id->Visible) { // position_id ?>
    <div id="r_position_id" class="form-group row">
        <label id="elh_myprofile_position_id" for="x_position_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->position_id->caption() ?><?= $Page->position_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->position_id->cellAttributes() ?>>
<?php if ($Page->position_id->getSessionValue() != "") { ?>
<span id="el_myprofile_position_id">
<span<?= $Page->position_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->position_id->getDisplayValue($Page->position_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_position_id" name="x_position_id" value="<?= HtmlEncode($Page->position_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_myprofile_position_id">
<div class="input-group flex-nowrap">
    <select
        id="x_position_id"
        name="x_position_id"
        class="form-control ew-select<?= $Page->position_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x_position_id"
        data-table="myprofile"
        data-field="x_position_id"
        data-page="3"
        data-value-separator="<?= $Page->position_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->position_id->getPlaceHolder()) ?>"
        <?= $Page->position_id->editAttributes() ?>>
        <?= $Page->position_id->selectOptionListHtml("x_position_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_position") && !$Page->position_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_position_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->position_id->caption() ?>" data-title="<?= $Page->position_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_position_id',url:'<?= GetUrl("masterpositionaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->position_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->position_id->getErrorMessage() ?></div>
<?= $Page->position_id->Lookup->getParamTag($Page, "p_x_position_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x_position_id']"),
        options = { name: "x_position_id", selectId: "myprofile_x_position_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.position_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
    <div id="r_status_id" class="form-group row">
        <label id="elh_myprofile_status_id" for="x_status_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status_id->caption() ?><?= $Page->status_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->status_id->cellAttributes() ?>>
<?php if ($Page->status_id->getSessionValue() != "") { ?>
<span id="el_myprofile_status_id">
<span<?= $Page->status_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->status_id->getDisplayValue($Page->status_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_status_id" name="x_status_id" value="<?= HtmlEncode($Page->status_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_myprofile_status_id">
<div class="input-group flex-nowrap">
    <select
        id="x_status_id"
        name="x_status_id"
        class="form-control ew-select<?= $Page->status_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x_status_id"
        data-table="myprofile"
        data-field="x_status_id"
        data-page="3"
        data-value-separator="<?= $Page->status_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status_id->getPlaceHolder()) ?>"
        <?= $Page->status_id->editAttributes() ?>>
        <?= $Page->status_id->selectOptionListHtml("x_status_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_status") && !$Page->status_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_status_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->status_id->caption() ?>" data-title="<?= $Page->status_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_status_id',url:'<?= GetUrl("masterstatusaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->status_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status_id->getErrorMessage() ?></div>
<?= $Page->status_id->Lookup->getParamTag($Page, "p_x_status_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x_status_id']"),
        options = { name: "x_status_id", selectId: "myprofile_x_status_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.status_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
    <div id="r_skill_id" class="form-group row">
        <label id="elh_myprofile_skill_id" for="x_skill_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->skill_id->caption() ?><?= $Page->skill_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->skill_id->cellAttributes() ?>>
<?php if ($Page->skill_id->getSessionValue() != "") { ?>
<span id="el_myprofile_skill_id">
<span<?= $Page->skill_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->skill_id->getDisplayValue($Page->skill_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_skill_id" name="x_skill_id" value="<?= HtmlEncode($Page->skill_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_myprofile_skill_id">
<div class="input-group flex-nowrap">
    <select
        id="x_skill_id"
        name="x_skill_id"
        class="form-control ew-select<?= $Page->skill_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x_skill_id"
        data-table="myprofile"
        data-field="x_skill_id"
        data-page="3"
        data-value-separator="<?= $Page->skill_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->skill_id->getPlaceHolder()) ?>"
        <?= $Page->skill_id->editAttributes() ?>>
        <?= $Page->skill_id->selectOptionListHtml("x_skill_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_skill") && !$Page->skill_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_skill_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->skill_id->caption() ?>" data-title="<?= $Page->skill_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_skill_id',url:'<?= GetUrl("masterskilladdopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->skill_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->skill_id->getErrorMessage() ?></div>
<?= $Page->skill_id->Lookup->getParamTag($Page, "p_x_skill_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x_skill_id']"),
        options = { name: "x_skill_id", selectId: "myprofile_x_skill_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.skill_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
    <div id="r_office_id" class="form-group row">
        <label id="elh_myprofile_office_id" for="x_office_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->office_id->caption() ?><?= $Page->office_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->office_id->cellAttributes() ?>>
<?php if ($Page->office_id->getSessionValue() != "") { ?>
<span id="el_myprofile_office_id">
<span<?= $Page->office_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->office_id->getDisplayValue($Page->office_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_office_id" name="x_office_id" value="<?= HtmlEncode($Page->office_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_myprofile_office_id">
<div class="input-group flex-nowrap">
    <select
        id="x_office_id"
        name="x_office_id"
        class="form-control ew-select<?= $Page->office_id->isInvalidClass() ?>"
        data-select2-id="myprofile_x_office_id"
        data-table="myprofile"
        data-field="x_office_id"
        data-page="3"
        data-value-separator="<?= $Page->office_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->office_id->getPlaceHolder()) ?>"
        <?= $Page->office_id->editAttributes() ?>>
        <?= $Page->office_id->selectOptionListHtml("x_office_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_office") && !$Page->office_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_office_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->office_id->caption() ?>" data-title="<?= $Page->office_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_office_id',url:'<?= GetUrl("masterofficeaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->office_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->office_id->getErrorMessage() ?></div>
<?= $Page->office_id->Lookup->getParamTag($Page, "p_x_office_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='myprofile_x_office_id']"),
        options = { name: "x_office_id", selectId: "myprofile_x_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.myprofile.fields.office_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
    <div id="r_hire_date" class="form-group row">
        <label id="elh_myprofile_hire_date" for="x_hire_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->hire_date->caption() ?><?= $Page->hire_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->hire_date->cellAttributes() ?>>
<span id="el_myprofile_hire_date">
<input type="<?= $Page->hire_date->getInputTextType() ?>" data-table="myprofile" data-field="x_hire_date" data-page="3" data-format="5" name="x_hire_date" id="x_hire_date" placeholder="<?= HtmlEncode($Page->hire_date->getPlaceHolder()) ?>" value="<?= $Page->hire_date->EditValue ?>"<?= $Page->hire_date->editAttributes() ?> aria-describedby="x_hire_date_help">
<?= $Page->hire_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->hire_date->getErrorMessage() ?></div>
<?php if (!$Page->hire_date->ReadOnly && !$Page->hire_date->Disabled && !isset($Page->hire_date->EditAttrs["readonly"]) && !isset($Page->hire_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofileadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofileadd", "x_hire_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
    <div id="r_termination_date" class="form-group row">
        <label id="elh_myprofile_termination_date" for="x_termination_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->termination_date->caption() ?><?= $Page->termination_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->termination_date->cellAttributes() ?>>
<span id="el_myprofile_termination_date">
<input type="<?= $Page->termination_date->getInputTextType() ?>" data-table="myprofile" data-field="x_termination_date" data-page="3" data-format="5" name="x_termination_date" id="x_termination_date" placeholder="<?= HtmlEncode($Page->termination_date->getPlaceHolder()) ?>" value="<?= $Page->termination_date->EditValue ?>"<?= $Page->termination_date->editAttributes() ?> aria-describedby="x_termination_date_help">
<?= $Page->termination_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->termination_date->getErrorMessage() ?></div>
<?php if (!$Page->termination_date->ReadOnly && !$Page->termination_date->Disabled && !isset($Page->termination_date->EditAttrs["readonly"]) && !isset($Page->termination_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofileadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofileadd", "x_termination_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(4) ?>" id="tab_myprofile4"><!-- multi-page .tab-pane -->
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
    <div id="r_technical_skill" class="form-group row">
        <label id="elh_myprofile_technical_skill" for="x_technical_skill" class="<?= $Page->LeftColumnClass ?>"><?= $Page->technical_skill->caption() ?><?= $Page->technical_skill->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->technical_skill->cellAttributes() ?>>
<span id="el_myprofile_technical_skill">
<textarea data-table="myprofile" data-field="x_technical_skill" data-page="4" name="x_technical_skill" id="x_technical_skill" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->technical_skill->getPlaceHolder()) ?>"<?= $Page->technical_skill->editAttributes() ?> aria-describedby="x_technical_skill_help"><?= $Page->technical_skill->EditValue ?></textarea>
<?= $Page->technical_skill->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->technical_skill->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
    <div id="r_about_me" class="form-group row">
        <label id="elh_myprofile_about_me" for="x_about_me" class="<?= $Page->LeftColumnClass ?>"><?= $Page->about_me->caption() ?><?= $Page->about_me->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->about_me->cellAttributes() ?>>
<span id="el_myprofile_about_me">
<textarea data-table="myprofile" data-field="x_about_me" data-page="4" name="x_about_me" id="x_about_me" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->about_me->getPlaceHolder()) ?>"<?= $Page->about_me->editAttributes() ?> aria-describedby="x_about_me_help"><?= $Page->about_me->EditValue ?></textarea>
<?= $Page->about_me->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->about_me->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
    </div><!-- /multi-page tabs .tab-content -->
</div><!-- /multi-page tabs -->
</div><!-- /multi-page -->
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<?php
    $Page->DetailPages->ValidKeys = explode(",", $Page->getCurrentDetailTable());
    $firstActiveDetailTable = $Page->DetailPages->activePageIndex();
?>
<div class="ew-detail-pages"><!-- detail-pages -->
<div class="ew-nav-tabs" id="Page_details"><!-- tabs -->
    <ul class="<?= $Page->DetailPages->navStyle() ?>"><!-- .nav -->
<?php
    if (in_array("myasset", explode(",", $Page->getCurrentDetailTable())) && $myasset->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "myasset") {
            $firstActiveDetailTable = "myasset";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("myasset") ?>" href="#tab_myasset" data-toggle="tab"><?= $Language->tablePhrase("myasset", "TblCaption") ?></a></li>
<?php
    }
?>
<?php
    if (in_array("mycontract", explode(",", $Page->getCurrentDetailTable())) && $mycontract->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mycontract") {
            $firstActiveDetailTable = "mycontract";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("mycontract") ?>" href="#tab_mycontract" data-toggle="tab"><?= $Language->tablePhrase("mycontract", "TblCaption") ?></a></li>
<?php
    }
?>
<?php
    if (in_array("mytimesheet", explode(",", $Page->getCurrentDetailTable())) && $mytimesheet->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mytimesheet") {
            $firstActiveDetailTable = "mytimesheet";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("mytimesheet") ?>" href="#tab_mytimesheet" data-toggle="tab"><?= $Language->tablePhrase("mytimesheet", "TblCaption") ?></a></li>
<?php
    }
?>
<?php
    if (in_array("mytraining", explode(",", $Page->getCurrentDetailTable())) && $mytraining->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mytraining") {
            $firstActiveDetailTable = "mytraining";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("mytraining") ?>" href="#tab_mytraining" data-toggle="tab"><?= $Language->tablePhrase("mytraining", "TblCaption") ?></a></li>
<?php
    }
?>
    </ul><!-- /.nav -->
    <div class="tab-content"><!-- .tab-content -->
<?php
    if (in_array("myasset", explode(",", $Page->getCurrentDetailTable())) && $myasset->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "myasset") {
            $firstActiveDetailTable = "myasset";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("myasset") ?>" id="tab_myasset"><!-- page* -->
<?php include_once "MyassetGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("mycontract", explode(",", $Page->getCurrentDetailTable())) && $mycontract->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mycontract") {
            $firstActiveDetailTable = "mycontract";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("mycontract") ?>" id="tab_mycontract"><!-- page* -->
<?php include_once "MycontractGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("mytimesheet", explode(",", $Page->getCurrentDetailTable())) && $mytimesheet->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mytimesheet") {
            $firstActiveDetailTable = "mytimesheet";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("mytimesheet") ?>" id="tab_mytimesheet"><!-- page* -->
<?php include_once "MytimesheetGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("mytraining", explode(",", $Page->getCurrentDetailTable())) && $mytraining->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "mytraining") {
            $firstActiveDetailTable = "mytraining";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("mytraining") ?>" id="tab_mytraining"><!-- page* -->
<?php include_once "MytrainingGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
    </div><!-- /.tab-content -->
</div><!-- /tabs -->
</div><!-- /detail-pages -->
<?php } ?>
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
    ew.addEventHandlers("myprofile");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
