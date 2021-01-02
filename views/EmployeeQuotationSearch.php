<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeQuotationSearch = &$Page;
?>
<script>
if (!ew.vars.tables.employee_quotation) ew.vars.tables.employee_quotation = <?= JsonEncode(GetClientVar("tables", "employee_quotation")) ?>;
var currentForm, currentPageID;
var femployee_quotationsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    femployee_quotationsearch = currentAdvancedSearchForm = new ew.Form("femployee_quotationsearch", "search");
    <?php } else { ?>
    femployee_quotationsearch = currentForm = new ew.Form("femployee_quotationsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.employee_quotation.fields;
    femployee_quotationsearch.addFields([
        ["quotation_no", [], fields.quotation_no.isInvalid],
        ["y_quotation_no", [ew.Validators.between], false],
        ["customer_id", [], fields.customer_id.isInvalid],
        ["quotation_date", [ew.Validators.datetime(5)], fields.quotation_date.isInvalid],
        ["y_quotation_date", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        femployee_quotationsearch.setInvalid();
    });

    // Validate form
    femployee_quotationsearch.validate = function () {
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
    femployee_quotationsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_quotationsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_quotationsearch.lists.customer_id = <?= $Page->customer_id->toClientList($Page) ?>;
    loadjs.done("femployee_quotationsearch");
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
<form name="femployee_quotationsearch" id="femployee_quotationsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_quotation">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->quotation_no->Visible) { // quotation_no ?>
    <div id="r_quotation_no" class="form-group row">
        <label for="x_quotation_no" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_quotation_quotation_no"><?= $Page->quotation_no->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->quotation_no->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_quotation_no" id="z_quotation_no" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->quotation_no->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_quotation_quotation_no" class="ew-search-field">
<input type="<?= $Page->quotation_no->getInputTextType() ?>" data-table="employee_quotation" data-field="x_quotation_no" data-page="1" name="x_quotation_no" id="x_quotation_no" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->quotation_no->getPlaceHolder()) ?>" value="<?= $Page->quotation_no->EditValue ?>"<?= $Page->quotation_no->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->quotation_no->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_quotation_quotation_no" class="ew-search-field2 d-none">
<input type="<?= $Page->quotation_no->getInputTextType() ?>" data-table="employee_quotation" data-field="x_quotation_no" data-page="1" name="y_quotation_no" id="y_quotation_no" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->quotation_no->getPlaceHolder()) ?>" value="<?= $Page->quotation_no->EditValue2 ?>"<?= $Page->quotation_no->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->quotation_no->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->customer_id->Visible) { // customer_id ?>
    <div id="r_customer_id" class="form-group row">
        <label for="x_customer_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_quotation_customer_id"><?= $Page->customer_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_customer_id" id="z_customer_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->customer_id->cellAttributes() ?>>
            <span id="el_employee_quotation_customer_id" class="ew-search-field">
    <select
        id="x_customer_id"
        name="x_customer_id"
        class="form-control ew-select<?= $Page->customer_id->isInvalidClass() ?>"
        data-select2-id="employee_quotation_x_customer_id"
        data-table="employee_quotation"
        data-field="x_customer_id"
        data-page="1"
        data-value-separator="<?= $Page->customer_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->customer_id->getPlaceHolder()) ?>"
        <?= $Page->customer_id->editAttributes() ?>>
        <?= $Page->customer_id->selectOptionListHtml("x_customer_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->customer_id->getErrorMessage(false) ?></div>
<?= $Page->customer_id->Lookup->getParamTag($Page, "p_x_customer_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_quotation_x_customer_id']"),
        options = { name: "x_customer_id", selectId: "employee_quotation_x_customer_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_quotation.fields.customer_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { // quotation_date ?>
    <div id="r_quotation_date" class="form-group row">
        <label for="x_quotation_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_quotation_quotation_date"><?= $Page->quotation_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->quotation_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_quotation_date" id="z_quotation_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->quotation_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->quotation_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->quotation_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->quotation_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->quotation_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->quotation_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->quotation_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_quotation_quotation_date" class="ew-search-field">
<input type="<?= $Page->quotation_date->getInputTextType() ?>" data-table="employee_quotation" data-field="x_quotation_date" data-page="1" data-format="5" name="x_quotation_date" id="x_quotation_date" placeholder="<?= HtmlEncode($Page->quotation_date->getPlaceHolder()) ?>" value="<?= $Page->quotation_date->EditValue ?>"<?= $Page->quotation_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->quotation_date->getErrorMessage(false) ?></div>
<?php if (!$Page->quotation_date->ReadOnly && !$Page->quotation_date->Disabled && !isset($Page->quotation_date->EditAttrs["readonly"]) && !isset($Page->quotation_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_quotationsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_quotationsearch", "x_quotation_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_quotation_quotation_date" class="ew-search-field2 d-none">
<input type="<?= $Page->quotation_date->getInputTextType() ?>" data-table="employee_quotation" data-field="x_quotation_date" data-page="1" data-format="5" name="y_quotation_date" id="y_quotation_date" placeholder="<?= HtmlEncode($Page->quotation_date->getPlaceHolder()) ?>" value="<?= $Page->quotation_date->EditValue2 ?>"<?= $Page->quotation_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->quotation_date->getErrorMessage(false) ?></div>
<?php if (!$Page->quotation_date->ReadOnly && !$Page->quotation_date->Disabled && !isset($Page->quotation_date->EditAttrs["readonly"]) && !isset($Page->quotation_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_quotationsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_quotationsearch", "y_quotation_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
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
    ew.addEventHandlers("employee_quotation");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
