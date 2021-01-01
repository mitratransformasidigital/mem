<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeShiftSearch = &$Page;
?>
<script>
if (!ew.vars.tables.employee_shift) ew.vars.tables.employee_shift = <?= JsonEncode(GetClientVar("tables", "employee_shift")) ?>;
var currentForm, currentPageID;
var femployee_shiftsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    femployee_shiftsearch = currentAdvancedSearchForm = new ew.Form("femployee_shiftsearch", "search");
    <?php } else { ?>
    femployee_shiftsearch = currentForm = new ew.Form("femployee_shiftsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.employee_shift.fields;
    femployee_shiftsearch.addFields([
        ["es_id", [], fields.es_id.isInvalid],
        ["shift_id", [], fields.shift_id.isInvalid],
        ["employee_username", [], fields.employee_username.isInvalid],
        ["start_date", [ew.Validators.datetime(5)], fields.start_date.isInvalid],
        ["y_start_date", [ew.Validators.between], false],
        ["end_date", [ew.Validators.datetime(5)], fields.end_date.isInvalid],
        ["y_end_date", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        femployee_shiftsearch.setInvalid();
    });

    // Validate form
    femployee_shiftsearch.validate = function () {
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
    femployee_shiftsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_shiftsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_shiftsearch.lists.shift_id = <?= $Page->shift_id->toClientList($Page) ?>;
    femployee_shiftsearch.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("femployee_shiftsearch");
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
<form name="femployee_shiftsearch" id="femployee_shiftsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_shift">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->es_id->Visible) { // es_id ?>
    <div id="r_es_id" class="form-group row">
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_shift_es_id"><?= $Page->es_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_es_id" id="z_es_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->es_id->cellAttributes() ?>>
            <span id="el_employee_shift_es_id" class="ew-search-field">
<input type="<?= $Page->es_id->getInputTextType() ?>" data-table="employee_shift" data-field="x_es_id" name="x_es_id" id="x_es_id" placeholder="<?= HtmlEncode($Page->es_id->getPlaceHolder()) ?>" value="<?= $Page->es_id->EditValue ?>"<?= $Page->es_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->es_id->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->shift_id->Visible) { // shift_id ?>
    <div id="r_shift_id" class="form-group row">
        <label for="x_shift_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_shift_shift_id"><?= $Page->shift_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_shift_id" id="z_shift_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->shift_id->cellAttributes() ?>>
            <span id="el_employee_shift_shift_id" class="ew-search-field">
    <select
        id="x_shift_id"
        name="x_shift_id"
        class="form-control ew-select<?= $Page->shift_id->isInvalidClass() ?>"
        data-select2-id="employee_shift_x_shift_id"
        data-table="employee_shift"
        data-field="x_shift_id"
        data-value-separator="<?= $Page->shift_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->shift_id->getPlaceHolder()) ?>"
        <?= $Page->shift_id->editAttributes() ?>>
        <?= $Page->shift_id->selectOptionListHtml("x_shift_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->shift_id->getErrorMessage(false) ?></div>
<?= $Page->shift_id->Lookup->getParamTag($Page, "p_x_shift_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_shift_x_shift_id']"),
        options = { name: "x_shift_id", selectId: "employee_shift_x_shift_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_shift.fields.shift_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_shift_employee_username"><?= $Page->employee_username->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_employee_username" id="z_employee_username" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
            <span id="el_employee_shift_employee_username" class="ew-search-field">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_shift_x_employee_username"
        data-table="employee_shift"
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
    var el = document.querySelector("select[data-select2-id='employee_shift_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "employee_shift_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_shift.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->start_date->Visible) { // start_date ?>
    <div id="r_start_date" class="form-group row">
        <label for="x_start_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_shift_start_date"><?= $Page->start_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->start_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_start_date" id="z_start_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->start_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->start_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->start_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->start_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->start_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->start_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->start_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_shift_start_date" class="ew-search-field">
<input type="<?= $Page->start_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_start_date" data-format="5" name="x_start_date" id="x_start_date" placeholder="<?= HtmlEncode($Page->start_date->getPlaceHolder()) ?>" value="<?= $Page->start_date->EditValue ?>"<?= $Page->start_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_date->getErrorMessage(false) ?></div>
<?php if (!$Page->start_date->ReadOnly && !$Page->start_date->Disabled && !isset($Page->start_date->EditAttrs["readonly"]) && !isset($Page->start_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftsearch", "x_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_shift_start_date" class="ew-search-field2 d-none">
<input type="<?= $Page->start_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_start_date" data-format="5" name="y_start_date" id="y_start_date" placeholder="<?= HtmlEncode($Page->start_date->getPlaceHolder()) ?>" value="<?= $Page->start_date->EditValue2 ?>"<?= $Page->start_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_date->getErrorMessage(false) ?></div>
<?php if (!$Page->start_date->ReadOnly && !$Page->start_date->Disabled && !isset($Page->start_date->EditAttrs["readonly"]) && !isset($Page->start_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftsearch", "y_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->end_date->Visible) { // end_date ?>
    <div id="r_end_date" class="form-group row">
        <label for="x_end_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_employee_shift_end_date"><?= $Page->end_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->end_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_end_date" id="z_end_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->end_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->end_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->end_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->end_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->end_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->end_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->end_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_employee_shift_end_date" class="ew-search-field">
<input type="<?= $Page->end_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_end_date" data-format="5" name="x_end_date" id="x_end_date" placeholder="<?= HtmlEncode($Page->end_date->getPlaceHolder()) ?>" value="<?= $Page->end_date->EditValue ?>"<?= $Page->end_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_date->getErrorMessage(false) ?></div>
<?php if (!$Page->end_date->ReadOnly && !$Page->end_date->Disabled && !isset($Page->end_date->EditAttrs["readonly"]) && !isset($Page->end_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftsearch", "x_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_employee_shift_end_date" class="ew-search-field2 d-none">
<input type="<?= $Page->end_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_end_date" data-format="5" name="y_end_date" id="y_end_date" placeholder="<?= HtmlEncode($Page->end_date->getPlaceHolder()) ?>" value="<?= $Page->end_date->EditValue2 ?>"<?= $Page->end_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_date->getErrorMessage(false) ?></div>
<?php if (!$Page->end_date->ReadOnly && !$Page->end_date->Disabled && !isset($Page->end_date->EditAttrs["readonly"]) && !isset($Page->end_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftsearch", "y_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
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
    ew.addEventHandlers("employee_shift");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
