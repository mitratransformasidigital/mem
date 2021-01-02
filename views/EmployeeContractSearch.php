<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeContractSearch = &$Page;
?>
<script>
if (!ew.vars.tables.employee_contract) ew.vars.tables.employee_contract = <?= JsonEncode(GetClientVar("tables", "employee_contract")) ?>;
var currentForm, currentPageID;
var femployee_contractsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    femployee_contractsearch = currentAdvancedSearchForm = new ew.Form("femployee_contractsearch", "search");
    <?php } else { ?>
    femployee_contractsearch = currentForm = new ew.Form("femployee_contractsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.employee_contract.fields;
    femployee_contractsearch.addFields([
        ["employee_username", [], fields.employee_username.isInvalid],
        ["salary", [ew.Validators.float], fields.salary.isInvalid],
        ["y_salary", [ew.Validators.between], false],
        ["bonus", [ew.Validators.float], fields.bonus.isInvalid],
        ["y_bonus", [ew.Validators.between], false],
        ["thr", [], fields.thr.isInvalid],
        ["y_thr", [ew.Validators.between], false],
        ["contract_start", [ew.Validators.datetime(0)], fields.contract_start.isInvalid],
        ["y_contract_start", [ew.Validators.between], false],
        ["contract_end", [ew.Validators.datetime(0)], fields.contract_end.isInvalid],
        ["y_contract_end", [ew.Validators.between], false],
        ["office_id", [], fields.office_id.isInvalid],
        ["notes", [], fields.notes.isInvalid],
        ["y_notes", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        femployee_contractsearch.setInvalid();
    });

    // Validate form
    femployee_contractsearch.validate = function () {
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
    femployee_contractsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_contractsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_contractsearch.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    femployee_contractsearch.lists.office_id = <?= $Page->office_id->toClientList($Page) ?>;
    loadjs.done("femployee_contractsearch");
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
<form name="femployee_contractsearch" id="femployee_contractsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_contract">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_contract_employee_username"><?= $Page->employee_username->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_employee_username" id="z_employee_username" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
            <span id="el_employee_contract_employee_username" class="ew-search-field">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_contract_x_employee_username"
        data-table="employee_contract"
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
    var el = document.querySelector("select[data-select2-id='employee_contract_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "employee_contract_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_contract.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->salary->Visible) { // salary ?>
    <div id="r_salary" class="form-group row">
        <label for="x_salary" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_contract_salary"><?= $Page->salary->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->salary->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_salary" id="z_salary" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->salary->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->salary->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->salary->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->salary->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->salary->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->salary->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->salary->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->salary->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->salary->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_contract_salary" class="ew-search-field">
<input type="<?= $Page->salary->getInputTextType() ?>" data-table="employee_contract" data-field="x_salary" name="x_salary" id="x_salary" size="30" placeholder="<?= HtmlEncode($Page->salary->getPlaceHolder()) ?>" value="<?= $Page->salary->EditValue ?>"<?= $Page->salary->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->salary->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_contract_salary" class="ew-search-field2 d-none">
<input type="<?= $Page->salary->getInputTextType() ?>" data-table="employee_contract" data-field="x_salary" name="y_salary" id="y_salary" size="30" placeholder="<?= HtmlEncode($Page->salary->getPlaceHolder()) ?>" value="<?= $Page->salary->EditValue2 ?>"<?= $Page->salary->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->salary->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->bonus->Visible) { // bonus ?>
    <div id="r_bonus" class="form-group row">
        <label for="x_bonus" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_contract_bonus"><?= $Page->bonus->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->bonus->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_bonus" id="z_bonus" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->bonus->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->bonus->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->bonus->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->bonus->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->bonus->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->bonus->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->bonus->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->bonus->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->bonus->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_contract_bonus" class="ew-search-field">
<input type="<?= $Page->bonus->getInputTextType() ?>" data-table="employee_contract" data-field="x_bonus" name="x_bonus" id="x_bonus" size="30" placeholder="<?= HtmlEncode($Page->bonus->getPlaceHolder()) ?>" value="<?= $Page->bonus->EditValue ?>"<?= $Page->bonus->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->bonus->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_contract_bonus" class="ew-search-field2 d-none">
<input type="<?= $Page->bonus->getInputTextType() ?>" data-table="employee_contract" data-field="x_bonus" name="y_bonus" id="y_bonus" size="30" placeholder="<?= HtmlEncode($Page->bonus->getPlaceHolder()) ?>" value="<?= $Page->bonus->EditValue2 ?>"<?= $Page->bonus->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->bonus->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->thr->Visible) { // thr ?>
    <div id="r_thr" class="form-group row">
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_contract_thr"><?= $Page->thr->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->thr->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_thr" id="z_thr" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->thr->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->thr->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->thr->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->thr->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->thr->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->thr->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->thr->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->thr->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->thr->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_contract_thr" class="ew-search-field">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Page->thr->isInvalidClass() ?>" data-table="employee_contract" data-field="x_thr" name="x_thr[]" id="x_thr_512105" value="1"<?= ConvertToBool($Page->thr->AdvancedSearch->SearchValue) ? " checked" : "" ?><?= $Page->thr->editAttributes() ?>>
    <label class="custom-control-label" for="x_thr_512105"></label>
</div>
<div class="invalid-feedback"><?= $Page->thr->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_contract_thr" class="ew-search-field2 d-none">
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" class="custom-control-input<?= $Page->thr->isInvalidClass() ?>" data-table="employee_contract" data-field="x_thr" name="y_thr[]" id="y_thr_894384" value="1"<?= ConvertToBool($Page->thr->AdvancedSearch->SearchValue2) ? " checked" : "" ?><?= $Page->thr->editAttributes() ?>>
    <label class="custom-control-label" for="y_thr_894384"></label>
</div>
<div class="invalid-feedback"><?= $Page->thr->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->contract_start->Visible) { // contract_start ?>
    <div id="r_contract_start" class="form-group row">
        <label for="x_contract_start" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_contract_contract_start"><?= $Page->contract_start->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->contract_start->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_contract_start" id="z_contract_start" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->contract_start->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->contract_start->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->contract_start->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->contract_start->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->contract_start->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->contract_start->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->contract_start->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_contract_contract_start" class="ew-search-field">
<input type="<?= $Page->contract_start->getInputTextType() ?>" data-table="employee_contract" data-field="x_contract_start" name="x_contract_start" id="x_contract_start" placeholder="<?= HtmlEncode($Page->contract_start->getPlaceHolder()) ?>" value="<?= $Page->contract_start->EditValue ?>"<?= $Page->contract_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->contract_start->getErrorMessage(false) ?></div>
<?php if (!$Page->contract_start->ReadOnly && !$Page->contract_start->Disabled && !isset($Page->contract_start->EditAttrs["readonly"]) && !isset($Page->contract_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_contractsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_contractsearch", "x_contract_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_contract_contract_start" class="ew-search-field2 d-none">
<input type="<?= $Page->contract_start->getInputTextType() ?>" data-table="employee_contract" data-field="x_contract_start" name="y_contract_start" id="y_contract_start" placeholder="<?= HtmlEncode($Page->contract_start->getPlaceHolder()) ?>" value="<?= $Page->contract_start->EditValue2 ?>"<?= $Page->contract_start->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->contract_start->getErrorMessage(false) ?></div>
<?php if (!$Page->contract_start->ReadOnly && !$Page->contract_start->Disabled && !isset($Page->contract_start->EditAttrs["readonly"]) && !isset($Page->contract_start->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_contractsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_contractsearch", "y_contract_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->contract_end->Visible) { // contract_end ?>
    <div id="r_contract_end" class="form-group row">
        <label for="x_contract_end" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_contract_contract_end"><?= $Page->contract_end->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->contract_end->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_contract_end" id="z_contract_end" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->contract_end->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->contract_end->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->contract_end->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->contract_end->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->contract_end->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->contract_end->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->contract_end->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_contract_contract_end" class="ew-search-field">
<input type="<?= $Page->contract_end->getInputTextType() ?>" data-table="employee_contract" data-field="x_contract_end" name="x_contract_end" id="x_contract_end" placeholder="<?= HtmlEncode($Page->contract_end->getPlaceHolder()) ?>" value="<?= $Page->contract_end->EditValue ?>"<?= $Page->contract_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->contract_end->getErrorMessage(false) ?></div>
<?php if (!$Page->contract_end->ReadOnly && !$Page->contract_end->Disabled && !isset($Page->contract_end->EditAttrs["readonly"]) && !isset($Page->contract_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_contractsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_contractsearch", "x_contract_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_contract_contract_end" class="ew-search-field2 d-none">
<input type="<?= $Page->contract_end->getInputTextType() ?>" data-table="employee_contract" data-field="x_contract_end" name="y_contract_end" id="y_contract_end" placeholder="<?= HtmlEncode($Page->contract_end->getPlaceHolder()) ?>" value="<?= $Page->contract_end->EditValue2 ?>"<?= $Page->contract_end->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->contract_end->getErrorMessage(false) ?></div>
<?php if (!$Page->contract_end->ReadOnly && !$Page->contract_end->Disabled && !isset($Page->contract_end->EditAttrs["readonly"]) && !isset($Page->contract_end->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_contractsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_contractsearch", "y_contract_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
    <div id="r_office_id" class="form-group row">
        <label for="x_office_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_contract_office_id"><?= $Page->office_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_office_id" id="z_office_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->office_id->cellAttributes() ?>>
            <span id="el_employee_contract_office_id" class="ew-search-field">
    <select
        id="x_office_id"
        name="x_office_id"
        class="form-control ew-select<?= $Page->office_id->isInvalidClass() ?>"
        data-select2-id="employee_contract_x_office_id"
        data-table="employee_contract"
        data-field="x_office_id"
        data-value-separator="<?= $Page->office_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->office_id->getPlaceHolder()) ?>"
        <?= $Page->office_id->editAttributes() ?>>
        <?= $Page->office_id->selectOptionListHtml("x_office_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->office_id->getErrorMessage(false) ?></div>
<?= $Page->office_id->Lookup->getParamTag($Page, "p_x_office_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_contract_x_office_id']"),
        options = { name: "x_office_id", selectId: "employee_contract_x_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_contract.fields.office_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <div id="r_notes" class="form-group row">
        <label for="x_notes" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_contract_notes"><?= $Page->notes->caption() ?></span>
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
            <span id="el_employee_contract_notes" class="ew-search-field">
<input type="<?= $Page->notes->getInputTextType() ?>" data-table="employee_contract" data-field="x_notes" name="x_notes" id="x_notes" size="35" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>" value="<?= $Page->notes->EditValue ?>"<?= $Page->notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_contract_notes" class="ew-search-field2 d-none">
<input type="<?= $Page->notes->getInputTextType() ?>" data-table="employee_contract" data-field="x_notes" name="y_notes" id="y_notes" size="35" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>" value="<?= $Page->notes->EditValue2 ?>"<?= $Page->notes->editAttributes() ?>>
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
    ew.addEventHandlers("employee_contract");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
