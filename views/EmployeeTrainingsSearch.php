<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeTrainingsSearch = &$Page;
?>
<script>
if (!ew.vars.tables.employee_trainings) ew.vars.tables.employee_trainings = <?= JsonEncode(GetClientVar("tables", "employee_trainings")) ?>;
var currentForm, currentPageID;
var femployee_trainingssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    femployee_trainingssearch = currentAdvancedSearchForm = new ew.Form("femployee_trainingssearch", "search");
    <?php } else { ?>
    femployee_trainingssearch = currentForm = new ew.Form("femployee_trainingssearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.employee_trainings.fields;
    femployee_trainingssearch.addFields([
        ["training_id", [ew.Validators.integer], fields.training_id.isInvalid],
        ["employee_username", [], fields.employee_username.isInvalid],
        ["training_name", [], fields.training_name.isInvalid],
        ["y_training_name", [ew.Validators.between], false],
        ["training_start", [ew.Validators.datetime(0)], fields.training_start.isInvalid],
        ["y_training_start", [ew.Validators.between], false],
        ["training_end", [ew.Validators.datetime(0)], fields.training_end.isInvalid],
        ["y_training_end", [ew.Validators.between], false],
        ["training_company", [], fields.training_company.isInvalid],
        ["y_training_company", [ew.Validators.between], false],
        ["certificate_start", [ew.Validators.datetime(0)], fields.certificate_start.isInvalid],
        ["y_certificate_start", [ew.Validators.between], false],
        ["certificate_end", [ew.Validators.datetime(0)], fields.certificate_end.isInvalid],
        ["y_certificate_end", [ew.Validators.between], false],
        ["notes", [], fields.notes.isInvalid],
        ["y_notes", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        femployee_trainingssearch.setInvalid();
    });

    // Validate form
    femployee_trainingssearch.validate = function () {
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
    femployee_trainingssearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_trainingssearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_trainingssearch.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("femployee_trainingssearch");
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
<form name="femployee_trainingssearch" id="femployee_trainingssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_trainings">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->training_id->Visible) { // training_id ?>
    <div id="r_training_id" class="form-group row">
        <label for="x_training_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_trainings_training_id"><?= $Page->training_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_training_id" id="z_training_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_id->cellAttributes() ?>>
            <span id="el_employee_trainings_training_id" class="ew-search-field">
<input type="<?= $Page->training_id->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_id" name="x_training_id" id="x_training_id" placeholder="<?= HtmlEncode($Page->training_id->getPlaceHolder()) ?>" value="<?= $Page->training_id->EditValue ?>"<?= $Page->training_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->training_id->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_trainings_employee_username"><?= $Page->employee_username->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_employee_username" id="z_employee_username" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
            <span id="el_employee_trainings_employee_username" class="ew-search-field">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_trainings_x_employee_username"
        data-table="employee_trainings"
        data-field="x_employee_username"
        data-value-separator="<?= $Page->employee_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>"
        <?= $Page->employee_username->editAttributes() ?>>
        <?= $Page->employee_username->selectOptionListHtml("x_employee_username") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage(false) ?></div>
<?= $Page->employee_username->Lookup->getParamTag($Page, "p_x_employee_username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_trainings_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "employee_trainings_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_trainings.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->training_name->Visible) { // training_name ?>
    <div id="r_training_name" class="form-group row">
        <label for="x_training_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_trainings_training_name"><?= $Page->training_name->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_name->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_training_name" id="z_training_name" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->training_name->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->training_name->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->training_name->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->training_name->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->training_name->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->training_name->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->training_name->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->training_name->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->training_name->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->training_name->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->training_name->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_trainings_training_name" class="ew-search-field">
<input type="<?= $Page->training_name->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_name" name="x_training_name" id="x_training_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->training_name->getPlaceHolder()) ?>" value="<?= $Page->training_name->EditValue ?>"<?= $Page->training_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->training_name->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_trainings_training_name" class="ew-search-field2 d-none">
<input type="<?= $Page->training_name->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_name" name="y_training_name" id="y_training_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->training_name->getPlaceHolder()) ?>" value="<?= $Page->training_name->EditValue2 ?>"<?= $Page->training_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->training_name->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->training_start->Visible) { // training_start ?>
    <div id="r_training_start" class="form-group row">
        <label for="x_training_start" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_trainings_training_start"><?= $Page->training_start->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_start->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_training_start" id="z_training_start" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->training_start->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->training_start->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->training_start->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->training_start->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->training_start->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->training_start->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->training_start->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->training_start->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->training_start->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_trainings_training_start" class="ew-search-field">
<input type="<?= $Page->training_start->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_start" name="x_training_start" id="x_training_start" placeholder="<?= HtmlEncode($Page->training_start->getPlaceHolder()) ?>" value="<?= $Page->training_start->EditValue ?>"<?= $Page->training_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->training_start->getErrorMessage(false) ?></div>
<?php if (!$Page->training_start->ReadOnly && !$Page->training_start->Disabled && !isset($Page->training_start->EditAttrs["readonly"]) && !isset($Page->training_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_trainingssearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_trainingssearch", "x_training_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_trainings_training_start" class="ew-search-field2 d-none">
<input type="<?= $Page->training_start->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_start" name="y_training_start" id="y_training_start" placeholder="<?= HtmlEncode($Page->training_start->getPlaceHolder()) ?>" value="<?= $Page->training_start->EditValue2 ?>"<?= $Page->training_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->training_start->getErrorMessage(false) ?></div>
<?php if (!$Page->training_start->ReadOnly && !$Page->training_start->Disabled && !isset($Page->training_start->EditAttrs["readonly"]) && !isset($Page->training_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_trainingssearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_trainingssearch", "y_training_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->training_end->Visible) { // training_end ?>
    <div id="r_training_end" class="form-group row">
        <label for="x_training_end" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_trainings_training_end"><?= $Page->training_end->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_end->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_training_end" id="z_training_end" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->training_end->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->training_end->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->training_end->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->training_end->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->training_end->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->training_end->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->training_end->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->training_end->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->training_end->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_trainings_training_end" class="ew-search-field">
<input type="<?= $Page->training_end->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_end" name="x_training_end" id="x_training_end" placeholder="<?= HtmlEncode($Page->training_end->getPlaceHolder()) ?>" value="<?= $Page->training_end->EditValue ?>"<?= $Page->training_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->training_end->getErrorMessage(false) ?></div>
<?php if (!$Page->training_end->ReadOnly && !$Page->training_end->Disabled && !isset($Page->training_end->EditAttrs["readonly"]) && !isset($Page->training_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_trainingssearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_trainingssearch", "x_training_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_trainings_training_end" class="ew-search-field2 d-none">
<input type="<?= $Page->training_end->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_end" name="y_training_end" id="y_training_end" placeholder="<?= HtmlEncode($Page->training_end->getPlaceHolder()) ?>" value="<?= $Page->training_end->EditValue2 ?>"<?= $Page->training_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->training_end->getErrorMessage(false) ?></div>
<?php if (!$Page->training_end->ReadOnly && !$Page->training_end->Disabled && !isset($Page->training_end->EditAttrs["readonly"]) && !isset($Page->training_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_trainingssearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_trainingssearch", "y_training_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->training_company->Visible) { // training_company ?>
    <div id="r_training_company" class="form-group row">
        <label for="x_training_company" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_trainings_training_company"><?= $Page->training_company->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->training_company->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_training_company" id="z_training_company" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->training_company->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->training_company->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->training_company->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->training_company->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->training_company->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->training_company->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->training_company->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->training_company->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->training_company->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->training_company->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->training_company->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->training_company->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->training_company->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_trainings_training_company" class="ew-search-field">
<input type="<?= $Page->training_company->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_company" name="x_training_company" id="x_training_company" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->training_company->getPlaceHolder()) ?>" value="<?= $Page->training_company->EditValue ?>"<?= $Page->training_company->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->training_company->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_trainings_training_company" class="ew-search-field2 d-none">
<input type="<?= $Page->training_company->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_company" name="y_training_company" id="y_training_company" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->training_company->getPlaceHolder()) ?>" value="<?= $Page->training_company->EditValue2 ?>"<?= $Page->training_company->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->training_company->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->certificate_start->Visible) { // certificate_start ?>
    <div id="r_certificate_start" class="form-group row">
        <label for="x_certificate_start" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_trainings_certificate_start"><?= $Page->certificate_start->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->certificate_start->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_certificate_start" id="z_certificate_start" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->certificate_start->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->certificate_start->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->certificate_start->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->certificate_start->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->certificate_start->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->certificate_start->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->certificate_start->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->certificate_start->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->certificate_start->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_trainings_certificate_start" class="ew-search-field">
<input type="<?= $Page->certificate_start->getInputTextType() ?>" data-table="employee_trainings" data-field="x_certificate_start" name="x_certificate_start" id="x_certificate_start" placeholder="<?= HtmlEncode($Page->certificate_start->getPlaceHolder()) ?>" value="<?= $Page->certificate_start->EditValue ?>"<?= $Page->certificate_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->certificate_start->getErrorMessage(false) ?></div>
<?php if (!$Page->certificate_start->ReadOnly && !$Page->certificate_start->Disabled && !isset($Page->certificate_start->EditAttrs["readonly"]) && !isset($Page->certificate_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_trainingssearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_trainingssearch", "x_certificate_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_trainings_certificate_start" class="ew-search-field2 d-none">
<input type="<?= $Page->certificate_start->getInputTextType() ?>" data-table="employee_trainings" data-field="x_certificate_start" name="y_certificate_start" id="y_certificate_start" placeholder="<?= HtmlEncode($Page->certificate_start->getPlaceHolder()) ?>" value="<?= $Page->certificate_start->EditValue2 ?>"<?= $Page->certificate_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->certificate_start->getErrorMessage(false) ?></div>
<?php if (!$Page->certificate_start->ReadOnly && !$Page->certificate_start->Disabled && !isset($Page->certificate_start->EditAttrs["readonly"]) && !isset($Page->certificate_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_trainingssearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_trainingssearch", "y_certificate_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->certificate_end->Visible) { // certificate_end ?>
    <div id="r_certificate_end" class="form-group row">
        <label for="x_certificate_end" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_trainings_certificate_end"><?= $Page->certificate_end->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->certificate_end->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_certificate_end" id="z_certificate_end" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->certificate_end->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->certificate_end->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->certificate_end->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->certificate_end->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->certificate_end->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->certificate_end->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->certificate_end->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->certificate_end->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->certificate_end->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_trainings_certificate_end" class="ew-search-field">
<input type="<?= $Page->certificate_end->getInputTextType() ?>" data-table="employee_trainings" data-field="x_certificate_end" name="x_certificate_end" id="x_certificate_end" placeholder="<?= HtmlEncode($Page->certificate_end->getPlaceHolder()) ?>" value="<?= $Page->certificate_end->EditValue ?>"<?= $Page->certificate_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->certificate_end->getErrorMessage(false) ?></div>
<?php if (!$Page->certificate_end->ReadOnly && !$Page->certificate_end->Disabled && !isset($Page->certificate_end->EditAttrs["readonly"]) && !isset($Page->certificate_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_trainingssearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_trainingssearch", "x_certificate_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_trainings_certificate_end" class="ew-search-field2 d-none">
<input type="<?= $Page->certificate_end->getInputTextType() ?>" data-table="employee_trainings" data-field="x_certificate_end" name="y_certificate_end" id="y_certificate_end" placeholder="<?= HtmlEncode($Page->certificate_end->getPlaceHolder()) ?>" value="<?= $Page->certificate_end->EditValue2 ?>"<?= $Page->certificate_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->certificate_end->getErrorMessage(false) ?></div>
<?php if (!$Page->certificate_end->ReadOnly && !$Page->certificate_end->Disabled && !isset($Page->certificate_end->EditAttrs["readonly"]) && !isset($Page->certificate_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_trainingssearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_trainingssearch", "y_certificate_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <div id="r_notes" class="form-group row">
        <label for="x_notes" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_trainings_notes"><?= $Page->notes->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->notes->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_notes" id="z_notes" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->notes->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->notes->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->notes->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->notes->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->notes->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->notes->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->notes->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->notes->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->notes->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->notes->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->notes->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->notes->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->notes->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_trainings_notes" class="ew-search-field">
<input type="<?= $Page->notes->getInputTextType() ?>" data-table="employee_trainings" data-field="x_notes" name="x_notes" id="x_notes" size="35" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>" value="<?= $Page->notes->EditValue ?>"<?= $Page->notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_trainings_notes" class="ew-search-field2 d-none">
<input type="<?= $Page->notes->getInputTextType() ?>" data-table="employee_trainings" data-field="x_notes" name="y_notes" id="y_notes" size="35" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>" value="<?= $Page->notes->EditValue2 ?>"<?= $Page->notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
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
    ew.addEventHandlers("employee_trainings");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
