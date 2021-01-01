<?php

namespace MEM\prjMitralPHP;

// Page object
$MyprofileSearch = &$Page;
?>
<script>
if (!ew.vars.tables.myprofile) ew.vars.tables.myprofile = <?= JsonEncode(GetClientVar("tables", "myprofile")) ?>;
var currentForm, currentPageID;
var fmyprofilesearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fmyprofilesearch = currentAdvancedSearchForm = new ew.Form("fmyprofilesearch", "search");
    <?php } else { ?>
    fmyprofilesearch = currentForm = new ew.Form("fmyprofilesearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.myprofile.fields;
    fmyprofilesearch.addFields([
        ["employee_name", [], fields.employee_name.isInvalid],
        ["y_employee_name", [ew.Validators.between], false],
        ["employee_username", [], fields.employee_username.isInvalid],
        ["y_employee_username", [ew.Validators.between], false],
        ["employee_email", [], fields.employee_email.isInvalid],
        ["y_employee_email", [ew.Validators.between], false],
        ["birth_date", [ew.Validators.datetime(5)], fields.birth_date.isInvalid],
        ["y_birth_date", [ew.Validators.between], false],
        ["nik", [], fields.nik.isInvalid],
        ["npwp", [], fields.npwp.isInvalid],
        ["address", [], fields.address.isInvalid],
        ["y_address", [ew.Validators.between], false],
        ["city_id", [], fields.city_id.isInvalid],
        ["postal_code", [], fields.postal_code.isInvalid],
        ["y_postal_code", [ew.Validators.between], false],
        ["bank_number", [], fields.bank_number.isInvalid],
        ["bank_name", [], fields.bank_name.isInvalid],
        ["technical_skill", [], fields.technical_skill.isInvalid],
        ["y_technical_skill", [ew.Validators.between], false],
        ["about_me", [], fields.about_me.isInvalid],
        ["y_about_me", [ew.Validators.between], false],
        ["position_id", [], fields.position_id.isInvalid],
        ["religion", [], fields.religion.isInvalid],
        ["status_id", [], fields.status_id.isInvalid],
        ["skill_id", [], fields.skill_id.isInvalid],
        ["office_id", [], fields.office_id.isInvalid],
        ["hire_date", [ew.Validators.datetime(5)], fields.hire_date.isInvalid],
        ["y_hire_date", [ew.Validators.between], false],
        ["termination_date", [ew.Validators.datetime(5)], fields.termination_date.isInvalid],
        ["y_termination_date", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmyprofilesearch.setInvalid();
    });

    // Validate form
    fmyprofilesearch.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj),
            rowIndex = "";
        $fobj.data("rowindex", rowIndex);

        // Validate fields
        if (!this.validateFields(rowIndex))
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    fmyprofilesearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmyprofilesearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmyprofilesearch.lists.city_id = <?= $Page->city_id->toClientList($Page) ?>;
    fmyprofilesearch.lists.position_id = <?= $Page->position_id->toClientList($Page) ?>;
    fmyprofilesearch.lists.religion = <?= $Page->religion->toClientList($Page) ?>;
    fmyprofilesearch.lists.status_id = <?= $Page->status_id->toClientList($Page) ?>;
    fmyprofilesearch.lists.skill_id = <?= $Page->skill_id->toClientList($Page) ?>;
    fmyprofilesearch.lists.office_id = <?= $Page->office_id->toClientList($Page) ?>;
    loadjs.done("fmyprofilesearch");
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
<form name="fmyprofilesearch" id="fmyprofilesearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="myprofile">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
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
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->employee_name->Visible) { // employee_name ?>
    <div id="r_employee_name" class="form-group row">
        <label for="x_employee_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_employee_name"><?= $Page->employee_name->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_name->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_employee_name" id="z_employee_name" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->employee_name->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->employee_name->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->employee_name->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->employee_name->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->employee_name->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->employee_name->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->employee_name->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->employee_name->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->employee_name->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->employee_name->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->employee_name->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_employee_name" class="ew-search-field">
<input type="<?= $Page->employee_name->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_name" data-page="1" name="x_employee_name" id="x_employee_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->employee_name->getPlaceHolder()) ?>" value="<?= $Page->employee_name->EditValue ?>"<?= $Page->employee_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_name->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_employee_name" class="ew-search-field2 d-none">
<input type="<?= $Page->employee_name->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_name" data-page="1" name="y_employee_name" id="y_employee_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->employee_name->getPlaceHolder()) ?>" value="<?= $Page->employee_name->EditValue2 ?>"<?= $Page->employee_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_name->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_employee_username"><?= $Page->employee_username->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_employee_username" id="z_employee_username" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->employee_username->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->employee_username->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->employee_username->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->employee_username->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->employee_username->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->employee_username->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->employee_username->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->employee_username->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->employee_username->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->employee_username->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->employee_username->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_employee_username" class="ew-search-field">
<input type="<?= $Page->employee_username->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_username" data-page="1" name="x_employee_username" id="x_employee_username" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>" value="<?= $Page->employee_username->EditValue ?>"<?= $Page->employee_username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_employee_username" class="ew-search-field2 d-none">
<input type="<?= $Page->employee_username->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_username" data-page="1" name="y_employee_username" id="y_employee_username" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>" value="<?= $Page->employee_username->EditValue2 ?>"<?= $Page->employee_username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
    <div id="r_employee_email" class="form-group row">
        <label for="x_employee_email" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_employee_email"><?= $Page->employee_email->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_email->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_employee_email" id="z_employee_email" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->employee_email->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->employee_email->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->employee_email->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->employee_email->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->employee_email->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->employee_email->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->employee_email->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->employee_email->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->employee_email->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->employee_email->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->employee_email->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_employee_email" class="ew-search-field">
<input type="<?= $Page->employee_email->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_email" data-page="1" name="x_employee_email" id="x_employee_email" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_email->getPlaceHolder()) ?>" value="<?= $Page->employee_email->EditValue ?>"<?= $Page->employee_email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_email->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_employee_email" class="ew-search-field2 d-none">
<input type="<?= $Page->employee_email->getInputTextType() ?>" data-table="myprofile" data-field="x_employee_email" data-page="1" name="y_employee_email" id="y_employee_email" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_email->getPlaceHolder()) ?>" value="<?= $Page->employee_email->EditValue2 ?>"<?= $Page->employee_email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_email->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
    <div id="r_birth_date" class="form-group row">
        <label for="x_birth_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_birth_date"><?= $Page->birth_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->birth_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_birth_date" id="z_birth_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->birth_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->birth_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->birth_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->birth_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->birth_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->birth_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->birth_date->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->birth_date->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->birth_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_birth_date" class="ew-search-field">
<input type="<?= $Page->birth_date->getInputTextType() ?>" data-table="myprofile" data-field="x_birth_date" data-page="1" data-format="5" name="x_birth_date" id="x_birth_date" placeholder="<?= HtmlEncode($Page->birth_date->getPlaceHolder()) ?>" value="<?= $Page->birth_date->EditValue ?>"<?= $Page->birth_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->birth_date->getErrorMessage(false) ?></div>
<?php if (!$Page->birth_date->ReadOnly && !$Page->birth_date->Disabled && !isset($Page->birth_date->EditAttrs["readonly"]) && !isset($Page->birth_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilesearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilesearch", "x_birth_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_birth_date" class="ew-search-field2 d-none">
<input type="<?= $Page->birth_date->getInputTextType() ?>" data-table="myprofile" data-field="x_birth_date" data-page="1" data-format="5" name="y_birth_date" id="y_birth_date" placeholder="<?= HtmlEncode($Page->birth_date->getPlaceHolder()) ?>" value="<?= $Page->birth_date->EditValue2 ?>"<?= $Page->birth_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->birth_date->getErrorMessage(false) ?></div>
<?php if (!$Page->birth_date->ReadOnly && !$Page->birth_date->Disabled && !isset($Page->birth_date->EditAttrs["readonly"]) && !isset($Page->birth_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilesearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilesearch", "y_birth_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
    <div id="r_nik" class="form-group row">
        <label for="x_nik" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_nik"><?= $Page->nik->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_nik" id="z_nik" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nik->cellAttributes() ?>>
            <span id="el_myprofile_nik" class="ew-search-field">
<input type="<?= $Page->nik->getInputTextType() ?>" data-table="myprofile" data-field="x_nik" data-page="1" name="x_nik" id="x_nik" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->nik->getPlaceHolder()) ?>" value="<?= $Page->nik->EditValue ?>"<?= $Page->nik->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nik->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
    <div id="r_npwp" class="form-group row">
        <label for="x_npwp" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_npwp"><?= $Page->npwp->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_npwp" id="z_npwp" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->npwp->cellAttributes() ?>>
            <span id="el_myprofile_npwp" class="ew-search-field">
<input type="<?= $Page->npwp->getInputTextType() ?>" data-table="myprofile" data-field="x_npwp" data-page="1" name="x_npwp" id="x_npwp" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->npwp->getPlaceHolder()) ?>" value="<?= $Page->npwp->EditValue ?>"<?= $Page->npwp->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->npwp->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <div id="r_address" class="form-group row">
        <label for="x_address" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_address"><?= $Page->address->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->address->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_address" id="z_address" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->address->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->address->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->address->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->address->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->address->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->address->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->address->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->address->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->address->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->address->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->address->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->address->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->address->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_address" class="ew-search-field">
<input type="<?= $Page->address->getInputTextType() ?>" data-table="myprofile" data-field="x_address" data-page="1" name="x_address" id="x_address" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>" value="<?= $Page->address->EditValue ?>"<?= $Page->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->address->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_address" class="ew-search-field2 d-none">
<input type="<?= $Page->address->getInputTextType() ?>" data-table="myprofile" data-field="x_address" data-page="1" name="y_address" id="y_address" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>" value="<?= $Page->address->EditValue2 ?>"<?= $Page->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->address->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <div id="r_city_id" class="form-group row">
        <label for="x_city_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_city_id"><?= $Page->city_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_city_id" id="z_city_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->city_id->cellAttributes() ?>>
            <span id="el_myprofile_city_id" class="ew-search-field">
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
    <div class="invalid-feedback"><?= $Page->city_id->getErrorMessage(false) ?></div>
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
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
    <div id="r_postal_code" class="form-group row">
        <label for="x_postal_code" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_postal_code"><?= $Page->postal_code->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->postal_code->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_postal_code" id="z_postal_code" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->postal_code->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->postal_code->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->postal_code->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->postal_code->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->postal_code->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->postal_code->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->postal_code->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->postal_code->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->postal_code->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->postal_code->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->postal_code->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->postal_code->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->postal_code->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_postal_code" class="ew-search-field">
<input type="<?= $Page->postal_code->getInputTextType() ?>" data-table="myprofile" data-field="x_postal_code" data-page="1" name="x_postal_code" id="x_postal_code" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->postal_code->getPlaceHolder()) ?>" value="<?= $Page->postal_code->EditValue ?>"<?= $Page->postal_code->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->postal_code->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_postal_code" class="ew-search-field2 d-none">
<input type="<?= $Page->postal_code->getInputTextType() ?>" data-table="myprofile" data-field="x_postal_code" data-page="1" name="y_postal_code" id="y_postal_code" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->postal_code->getPlaceHolder()) ?>" value="<?= $Page->postal_code->EditValue2 ?>"<?= $Page->postal_code->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->postal_code->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
    <div id="r_religion" class="form-group row">
        <label for="x_religion" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_religion"><?= $Page->religion->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_religion" id="z_religion" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->religion->cellAttributes() ?>>
            <span id="el_myprofile_religion" class="ew-search-field">
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
    <div class="invalid-feedback"><?= $Page->religion->getErrorMessage(false) ?></div>
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
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->bank_number->Visible) { // bank_number ?>
    <div id="r_bank_number" class="form-group row">
        <label for="x_bank_number" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_bank_number"><?= $Page->bank_number->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_bank_number" id="z_bank_number" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->bank_number->cellAttributes() ?>>
            <span id="el_myprofile_bank_number" class="ew-search-field">
<input type="<?= $Page->bank_number->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_number" data-page="2" name="x_bank_number" id="x_bank_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->bank_number->getPlaceHolder()) ?>" value="<?= $Page->bank_number->EditValue ?>"<?= $Page->bank_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->bank_number->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
    <div id="r_bank_name" class="form-group row">
        <label for="x_bank_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_bank_name"><?= $Page->bank_name->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_bank_name" id="z_bank_name" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->bank_name->cellAttributes() ?>>
            <span id="el_myprofile_bank_name" class="ew-search-field">
<input type="<?= $Page->bank_name->getInputTextType() ?>" data-table="myprofile" data-field="x_bank_name" data-page="2" name="x_bank_name" id="x_bank_name" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->bank_name->getPlaceHolder()) ?>" value="<?= $Page->bank_name->EditValue ?>"<?= $Page->bank_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->bank_name->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="tab-pane<?= $Page->MultiPages->pageStyle(3) ?>" id="tab_myprofile3"><!-- multi-page .tab-pane -->
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->position_id->Visible) { // position_id ?>
    <div id="r_position_id" class="form-group row">
        <label for="x_position_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_position_id"><?= $Page->position_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_position_id" id="z_position_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->position_id->cellAttributes() ?>>
            <span id="el_myprofile_position_id" class="ew-search-field">
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
    <div class="invalid-feedback"><?= $Page->position_id->getErrorMessage(false) ?></div>
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
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
    <div id="r_status_id" class="form-group row">
        <label for="x_status_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_status_id"><?= $Page->status_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_status_id" id="z_status_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->status_id->cellAttributes() ?>>
            <span id="el_myprofile_status_id" class="ew-search-field">
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
    <div class="invalid-feedback"><?= $Page->status_id->getErrorMessage(false) ?></div>
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
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
    <div id="r_skill_id" class="form-group row">
        <label for="x_skill_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_skill_id"><?= $Page->skill_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_skill_id" id="z_skill_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->skill_id->cellAttributes() ?>>
            <span id="el_myprofile_skill_id" class="ew-search-field">
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
    <div class="invalid-feedback"><?= $Page->skill_id->getErrorMessage(false) ?></div>
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
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
    <div id="r_office_id" class="form-group row">
        <label for="x_office_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_office_id"><?= $Page->office_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_office_id" id="z_office_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->office_id->cellAttributes() ?>>
            <span id="el_myprofile_office_id" class="ew-search-field">
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
    <div class="invalid-feedback"><?= $Page->office_id->getErrorMessage(false) ?></div>
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
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
    <div id="r_hire_date" class="form-group row">
        <label for="x_hire_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_hire_date"><?= $Page->hire_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->hire_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_hire_date" id="z_hire_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->hire_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->hire_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->hire_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->hire_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->hire_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->hire_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->hire_date->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->hire_date->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->hire_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_hire_date" class="ew-search-field">
<input type="<?= $Page->hire_date->getInputTextType() ?>" data-table="myprofile" data-field="x_hire_date" data-page="3" data-format="5" name="x_hire_date" id="x_hire_date" placeholder="<?= HtmlEncode($Page->hire_date->getPlaceHolder()) ?>" value="<?= $Page->hire_date->EditValue ?>"<?= $Page->hire_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->hire_date->getErrorMessage(false) ?></div>
<?php if (!$Page->hire_date->ReadOnly && !$Page->hire_date->Disabled && !isset($Page->hire_date->EditAttrs["readonly"]) && !isset($Page->hire_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilesearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilesearch", "x_hire_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_hire_date" class="ew-search-field2 d-none">
<input type="<?= $Page->hire_date->getInputTextType() ?>" data-table="myprofile" data-field="x_hire_date" data-page="3" data-format="5" name="y_hire_date" id="y_hire_date" placeholder="<?= HtmlEncode($Page->hire_date->getPlaceHolder()) ?>" value="<?= $Page->hire_date->EditValue2 ?>"<?= $Page->hire_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->hire_date->getErrorMessage(false) ?></div>
<?php if (!$Page->hire_date->ReadOnly && !$Page->hire_date->Disabled && !isset($Page->hire_date->EditAttrs["readonly"]) && !isset($Page->hire_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilesearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilesearch", "y_hire_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
    <div id="r_termination_date" class="form-group row">
        <label for="x_termination_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_termination_date"><?= $Page->termination_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->termination_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_termination_date" id="z_termination_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->termination_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->termination_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->termination_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->termination_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->termination_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->termination_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->termination_date->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->termination_date->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->termination_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_termination_date" class="ew-search-field">
<input type="<?= $Page->termination_date->getInputTextType() ?>" data-table="myprofile" data-field="x_termination_date" data-page="3" data-format="5" name="x_termination_date" id="x_termination_date" placeholder="<?= HtmlEncode($Page->termination_date->getPlaceHolder()) ?>" value="<?= $Page->termination_date->EditValue ?>"<?= $Page->termination_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->termination_date->getErrorMessage(false) ?></div>
<?php if (!$Page->termination_date->ReadOnly && !$Page->termination_date->Disabled && !isset($Page->termination_date->EditAttrs["readonly"]) && !isset($Page->termination_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilesearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilesearch", "x_termination_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_termination_date" class="ew-search-field2 d-none">
<input type="<?= $Page->termination_date->getInputTextType() ?>" data-table="myprofile" data-field="x_termination_date" data-page="3" data-format="5" name="y_termination_date" id="y_termination_date" placeholder="<?= HtmlEncode($Page->termination_date->getPlaceHolder()) ?>" value="<?= $Page->termination_date->EditValue2 ?>"<?= $Page->termination_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->termination_date->getErrorMessage(false) ?></div>
<?php if (!$Page->termination_date->ReadOnly && !$Page->termination_date->Disabled && !isset($Page->termination_date->EditAttrs["readonly"]) && !isset($Page->termination_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyprofilesearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyprofilesearch", "y_termination_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
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
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
    <div id="r_technical_skill" class="form-group row">
        <label for="x_technical_skill" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_technical_skill"><?= $Page->technical_skill->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->technical_skill->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_technical_skill" id="z_technical_skill" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->technical_skill->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->technical_skill->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_technical_skill" class="ew-search-field">
<input type="<?= $Page->technical_skill->getInputTextType() ?>" data-table="myprofile" data-field="x_technical_skill" data-page="4" name="x_technical_skill" id="x_technical_skill" maxlength="250" placeholder="<?= HtmlEncode($Page->technical_skill->getPlaceHolder()) ?>" value="<?= $Page->technical_skill->EditValue ?>"<?= $Page->technical_skill->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->technical_skill->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_technical_skill" class="ew-search-field2 d-none">
<input type="<?= $Page->technical_skill->getInputTextType() ?>" data-table="myprofile" data-field="x_technical_skill" data-page="4" name="y_technical_skill" id="y_technical_skill" maxlength="250" placeholder="<?= HtmlEncode($Page->technical_skill->getPlaceHolder()) ?>" value="<?= $Page->technical_skill->EditValue2 ?>"<?= $Page->technical_skill->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->technical_skill->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
    <div id="r_about_me" class="form-group row">
        <label for="x_about_me" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myprofile_about_me"><?= $Page->about_me->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->about_me->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_about_me" id="z_about_me" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->about_me->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->about_me->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->about_me->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->about_me->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->about_me->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->about_me->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->about_me->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->about_me->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->about_me->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->about_me->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->about_me->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->about_me->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->about_me->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myprofile_about_me" class="ew-search-field">
<input type="<?= $Page->about_me->getInputTextType() ?>" data-table="myprofile" data-field="x_about_me" data-page="4" name="x_about_me" id="x_about_me" maxlength="250" placeholder="<?= HtmlEncode($Page->about_me->getPlaceHolder()) ?>" value="<?= $Page->about_me->EditValue ?>"<?= $Page->about_me->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->about_me->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myprofile_about_me" class="ew-search-field2 d-none">
<input type="<?= $Page->about_me->getInputTextType() ?>" data-table="myprofile" data-field="x_about_me" data-page="4" name="y_about_me" id="y_about_me" maxlength="250" placeholder="<?= HtmlEncode($Page->about_me->getPlaceHolder()) ?>" value="<?= $Page->about_me->EditValue2 ?>"<?= $Page->about_me->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->about_me->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
    </div><!-- /multi-page tabs .tab-content -->
</div><!-- /multi-page tabs -->
</div><!-- /multi-page -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("Search") ?></button>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" onclick="location.reload();"><?= $Language->phrase("Reset") ?></button>
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
