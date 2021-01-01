<?php

namespace MEM\prjMitralPHP;

// Page object
$ActivitySearch = &$Page;
?>
<script>
if (!ew.vars.tables.activity) ew.vars.tables.activity = <?= JsonEncode(GetClientVar("tables", "activity")) ?>;
var currentForm, currentPageID;
var factivitysearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    factivitysearch = currentAdvancedSearchForm = new ew.Form("factivitysearch", "search");
    <?php } else { ?>
    factivitysearch = currentForm = new ew.Form("factivitysearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.activity.fields;
    factivitysearch.addFields([
        ["employee_username", [], fields.employee_username.isInvalid],
        ["activity_date", [ew.Validators.datetime(5)], fields.activity_date.isInvalid],
        ["y_activity_date", [ew.Validators.between], false],
        ["time_in", [ew.Validators.time], fields.time_in.isInvalid],
        ["y_time_in", [ew.Validators.between], false],
        ["time_out", [ew.Validators.time], fields.time_out.isInvalid],
        ["y_time_out", [ew.Validators.between], false],
        ["_action", [], fields._action.isInvalid],
        ["y__action", [ew.Validators.between], false],
        ["document", [], fields.document.isInvalid],
        ["y_document", [ew.Validators.between], false],
        ["notes", [], fields.notes.isInvalid],
        ["y_notes", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        factivitysearch.setInvalid();
    });

    // Validate form
    factivitysearch.validate = function () {
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
    factivitysearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    factivitysearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    factivitysearch.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("factivitysearch");
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
<form name="factivitysearch" id="factivitysearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="activity">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><span id="elh_activity_employee_username"><?= $Page->employee_username->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_employee_username" id="z_employee_username" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
            <span id="el_activity_employee_username" class="ew-search-field">
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
    <div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage(false) ?></div>
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
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->activity_date->Visible) { // activity_date ?>
    <div id="r_activity_date" class="form-group row">
        <label for="x_activity_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_activity_activity_date"><?= $Page->activity_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->activity_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_activity_date" id="z_activity_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->activity_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->activity_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->activity_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->activity_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->activity_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->activity_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->activity_date->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->activity_date->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->activity_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_activity_activity_date" class="ew-search-field">
<input type="<?= $Page->activity_date->getInputTextType() ?>" data-table="activity" data-field="x_activity_date" data-format="5" name="x_activity_date" id="x_activity_date" placeholder="<?= HtmlEncode($Page->activity_date->getPlaceHolder()) ?>" value="<?= $Page->activity_date->EditValue ?>"<?= $Page->activity_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->activity_date->getErrorMessage(false) ?></div>
<?php if (!$Page->activity_date->ReadOnly && !$Page->activity_date->Disabled && !isset($Page->activity_date->EditAttrs["readonly"]) && !isset($Page->activity_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitysearch", "datetimepicker"], function() {
    ew.createDateTimePicker("factivitysearch", "x_activity_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_activity_activity_date" class="ew-search-field2 d-none">
<input type="<?= $Page->activity_date->getInputTextType() ?>" data-table="activity" data-field="x_activity_date" data-format="5" name="y_activity_date" id="y_activity_date" placeholder="<?= HtmlEncode($Page->activity_date->getPlaceHolder()) ?>" value="<?= $Page->activity_date->EditValue2 ?>"<?= $Page->activity_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->activity_date->getErrorMessage(false) ?></div>
<?php if (!$Page->activity_date->ReadOnly && !$Page->activity_date->Disabled && !isset($Page->activity_date->EditAttrs["readonly"]) && !isset($Page->activity_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitysearch", "datetimepicker"], function() {
    ew.createDateTimePicker("factivitysearch", "y_activity_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
    <div id="r_time_in" class="form-group row">
        <label for="x_time_in" class="<?= $Page->LeftColumnClass ?>"><span id="elh_activity_time_in"><?= $Page->time_in->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->time_in->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_time_in" id="z_time_in" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->time_in->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->time_in->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->time_in->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->time_in->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->time_in->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->time_in->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->time_in->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->time_in->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->time_in->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_activity_time_in" class="ew-search-field">
<input type="<?= $Page->time_in->getInputTextType() ?>" data-table="activity" data-field="x_time_in" name="x_time_in" id="x_time_in" placeholder="<?= HtmlEncode($Page->time_in->getPlaceHolder()) ?>" value="<?= $Page->time_in->EditValue ?>"<?= $Page->time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->time_in->ReadOnly && !$Page->time_in->Disabled && !isset($Page->time_in->EditAttrs["readonly"]) && !isset($Page->time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitysearch", "timepicker"], function() {
    ew.createTimePicker("factivitysearch", "x_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_activity_time_in" class="ew-search-field2 d-none">
<input type="<?= $Page->time_in->getInputTextType() ?>" data-table="activity" data-field="x_time_in" name="y_time_in" id="y_time_in" placeholder="<?= HtmlEncode($Page->time_in->getPlaceHolder()) ?>" value="<?= $Page->time_in->EditValue2 ?>"<?= $Page->time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->time_in->ReadOnly && !$Page->time_in->Disabled && !isset($Page->time_in->EditAttrs["readonly"]) && !isset($Page->time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitysearch", "timepicker"], function() {
    ew.createTimePicker("factivitysearch", "y_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
    <div id="r_time_out" class="form-group row">
        <label for="x_time_out" class="<?= $Page->LeftColumnClass ?>"><span id="elh_activity_time_out"><?= $Page->time_out->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->time_out->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_time_out" id="z_time_out" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->time_out->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->time_out->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->time_out->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->time_out->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->time_out->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->time_out->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->time_out->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->time_out->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->time_out->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_activity_time_out" class="ew-search-field">
<input type="<?= $Page->time_out->getInputTextType() ?>" data-table="activity" data-field="x_time_out" name="x_time_out" id="x_time_out" placeholder="<?= HtmlEncode($Page->time_out->getPlaceHolder()) ?>" value="<?= $Page->time_out->EditValue ?>"<?= $Page->time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->time_out->ReadOnly && !$Page->time_out->Disabled && !isset($Page->time_out->EditAttrs["readonly"]) && !isset($Page->time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitysearch", "timepicker"], function() {
    ew.createTimePicker("factivitysearch", "x_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_activity_time_out" class="ew-search-field2 d-none">
<input type="<?= $Page->time_out->getInputTextType() ?>" data-table="activity" data-field="x_time_out" name="y_time_out" id="y_time_out" placeholder="<?= HtmlEncode($Page->time_out->getPlaceHolder()) ?>" value="<?= $Page->time_out->EditValue2 ?>"<?= $Page->time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->time_out->ReadOnly && !$Page->time_out->Disabled && !isset($Page->time_out->EditAttrs["readonly"]) && !isset($Page->time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["factivitysearch", "timepicker"], function() {
    ew.createTimePicker("factivitysearch", "y_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":1});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <div id="r__action" class="form-group row">
        <label for="x__action" class="<?= $Page->LeftColumnClass ?>"><span id="elh_activity__action"><?= $Page->_action->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_action->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z__action" id="z__action" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->_action->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->_action->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->_action->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->_action->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->_action->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->_action->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->_action->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->_action->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->_action->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->_action->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->_action->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->_action->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->_action->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_activity__action" class="ew-search-field">
<input type="<?= $Page->_action->getInputTextType() ?>" data-table="activity" data-field="x__action" name="x__action" id="x__action" size="35" placeholder="<?= HtmlEncode($Page->_action->getPlaceHolder()) ?>" value="<?= $Page->_action->EditValue ?>"<?= $Page->_action->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_action->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_activity__action" class="ew-search-field2 d-none">
<input type="<?= $Page->_action->getInputTextType() ?>" data-table="activity" data-field="x__action" name="y__action" id="y__action" size="35" placeholder="<?= HtmlEncode($Page->_action->getPlaceHolder()) ?>" value="<?= $Page->_action->EditValue2 ?>"<?= $Page->_action->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_action->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
    <div id="r_document" class="form-group row">
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_activity_document"><?= $Page->document->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->document->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_document" id="z_document" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->document->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->document->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->document->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->document->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->document->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->document->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->document->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->document->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->document->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->document->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->document->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->document->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->document->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_activity_document" class="ew-search-field">
<input type="<?= $Page->document->getInputTextType() ?>" data-table="activity" data-field="x_document" name="x_document" id="x_document" placeholder="<?= HtmlEncode($Page->document->getPlaceHolder()) ?>" value="<?= $Page->document->EditValue ?>"<?= $Page->document->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->document->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_activity_document" class="ew-search-field2 d-none">
<input type="<?= $Page->document->getInputTextType() ?>" data-table="activity" data-field="x_document" name="y_document" id="y_document" placeholder="<?= HtmlEncode($Page->document->getPlaceHolder()) ?>" value="<?= $Page->document->EditValue2 ?>"<?= $Page->document->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->document->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <div id="r_notes" class="form-group row">
        <label for="x_notes" class="<?= $Page->LeftColumnClass ?>"><span id="elh_activity_notes"><?= $Page->notes->caption() ?></span>
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
            <span id="el_activity_notes" class="ew-search-field">
<input type="<?= $Page->notes->getInputTextType() ?>" data-table="activity" data-field="x_notes" name="x_notes" id="x_notes" size="35" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>" value="<?= $Page->notes->EditValue ?>"<?= $Page->notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_activity_notes" class="ew-search-field2 d-none">
<input type="<?= $Page->notes->getInputTextType() ?>" data-table="activity" data-field="x_notes" name="y_notes" id="y_notes" size="35" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>" value="<?= $Page->notes->EditValue2 ?>"<?= $Page->notes->editAttributes() ?>>
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
    ew.addEventHandlers("activity");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
