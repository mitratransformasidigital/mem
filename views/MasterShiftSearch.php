<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterShiftSearch = &$Page;
?>
<script>
if (!ew.vars.tables.master_shift) ew.vars.tables.master_shift = <?= JsonEncode(GetClientVar("tables", "master_shift")) ?>;
var currentForm, currentPageID;
var fmaster_shiftsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fmaster_shiftsearch = currentAdvancedSearchForm = new ew.Form("fmaster_shiftsearch", "search");
    <?php } else { ?>
    fmaster_shiftsearch = currentForm = new ew.Form("fmaster_shiftsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.master_shift.fields;
    fmaster_shiftsearch.addFields([
        ["shift_name", [], fields.shift_name.isInvalid],
        ["y_shift_name", [ew.Validators.between], false],
        ["sunday_time_in", [ew.Validators.time], fields.sunday_time_in.isInvalid],
        ["y_sunday_time_in", [ew.Validators.between], false],
        ["sunday_time_out", [ew.Validators.time], fields.sunday_time_out.isInvalid],
        ["y_sunday_time_out", [ew.Validators.between], false],
        ["monday_time_in", [ew.Validators.time], fields.monday_time_in.isInvalid],
        ["y_monday_time_in", [ew.Validators.between], false],
        ["monday_time_out", [ew.Validators.time], fields.monday_time_out.isInvalid],
        ["y_monday_time_out", [ew.Validators.between], false],
        ["tuesday_time_in", [ew.Validators.time], fields.tuesday_time_in.isInvalid],
        ["y_tuesday_time_in", [ew.Validators.between], false],
        ["tuesday_time_out", [ew.Validators.time], fields.tuesday_time_out.isInvalid],
        ["y_tuesday_time_out", [ew.Validators.between], false],
        ["wednesday_time_in", [ew.Validators.time], fields.wednesday_time_in.isInvalid],
        ["y_wednesday_time_in", [ew.Validators.between], false],
        ["wednesday_time_out", [ew.Validators.time], fields.wednesday_time_out.isInvalid],
        ["y_wednesday_time_out", [ew.Validators.between], false],
        ["thursday_time_in", [ew.Validators.time], fields.thursday_time_in.isInvalid],
        ["y_thursday_time_in", [ew.Validators.between], false],
        ["thursday_time_out", [ew.Validators.time], fields.thursday_time_out.isInvalid],
        ["y_thursday_time_out", [ew.Validators.between], false],
        ["friday_time_in", [ew.Validators.time], fields.friday_time_in.isInvalid],
        ["y_friday_time_in", [ew.Validators.between], false],
        ["friday_time_out", [ew.Validators.time], fields.friday_time_out.isInvalid],
        ["y_friday_time_out", [ew.Validators.between], false],
        ["saturday_time_in", [ew.Validators.time], fields.saturday_time_in.isInvalid],
        ["y_saturday_time_in", [ew.Validators.between], false],
        ["saturday_time_out", [ew.Validators.time], fields.saturday_time_out.isInvalid],
        ["y_saturday_time_out", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmaster_shiftsearch.setInvalid();
    });

    // Validate form
    fmaster_shiftsearch.validate = function () {
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
    fmaster_shiftsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_shiftsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_shiftsearch");
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
<form name="fmaster_shiftsearch" id="fmaster_shiftsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_shift">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->shift_name->Visible) { // shift_name ?>
    <div id="r_shift_name" class="form-group row">
        <label for="x_shift_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_shift_name"><?= $Page->shift_name->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->shift_name->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_shift_name" id="z_shift_name" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->shift_name->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->shift_name->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->shift_name->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->shift_name->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->shift_name->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->shift_name->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->shift_name->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->shift_name->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->shift_name->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->shift_name->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->shift_name->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_shift_name" class="ew-search-field">
<input type="<?= $Page->shift_name->getInputTextType() ?>" data-table="master_shift" data-field="x_shift_name" name="x_shift_name" id="x_shift_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->shift_name->getPlaceHolder()) ?>" value="<?= $Page->shift_name->EditValue ?>"<?= $Page->shift_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->shift_name->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_shift_name" class="ew-search-field2 d-none">
<input type="<?= $Page->shift_name->getInputTextType() ?>" data-table="master_shift" data-field="x_shift_name" name="y_shift_name" id="y_shift_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->shift_name->getPlaceHolder()) ?>" value="<?= $Page->shift_name->EditValue2 ?>"<?= $Page->shift_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->shift_name->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->sunday_time_in->Visible) { // sunday_time_in ?>
    <div id="r_sunday_time_in" class="form-group row">
        <label for="x_sunday_time_in" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_sunday_time_in"><?= $Page->sunday_time_in->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sunday_time_in->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_sunday_time_in" id="z_sunday_time_in" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->sunday_time_in->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->sunday_time_in->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->sunday_time_in->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->sunday_time_in->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->sunday_time_in->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->sunday_time_in->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->sunday_time_in->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->sunday_time_in->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->sunday_time_in->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_sunday_time_in" class="ew-search-field">
<input type="<?= $Page->sunday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_in" name="x_sunday_time_in" id="x_sunday_time_in" placeholder="<?= HtmlEncode($Page->sunday_time_in->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_in->EditValue ?>"<?= $Page->sunday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sunday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->sunday_time_in->ReadOnly && !$Page->sunday_time_in->Disabled && !isset($Page->sunday_time_in->EditAttrs["readonly"]) && !isset($Page->sunday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_sunday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_sunday_time_in" class="ew-search-field2 d-none">
<input type="<?= $Page->sunday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_in" name="y_sunday_time_in" id="y_sunday_time_in" placeholder="<?= HtmlEncode($Page->sunday_time_in->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_in->EditValue2 ?>"<?= $Page->sunday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sunday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->sunday_time_in->ReadOnly && !$Page->sunday_time_in->Disabled && !isset($Page->sunday_time_in->EditAttrs["readonly"]) && !isset($Page->sunday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_sunday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->sunday_time_out->Visible) { // sunday_time_out ?>
    <div id="r_sunday_time_out" class="form-group row">
        <label for="x_sunday_time_out" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_sunday_time_out"><?= $Page->sunday_time_out->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sunday_time_out->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_sunday_time_out" id="z_sunday_time_out" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->sunday_time_out->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->sunday_time_out->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->sunday_time_out->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->sunday_time_out->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->sunday_time_out->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->sunday_time_out->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->sunday_time_out->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->sunday_time_out->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->sunday_time_out->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_sunday_time_out" class="ew-search-field">
<input type="<?= $Page->sunday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_out" name="x_sunday_time_out" id="x_sunday_time_out" placeholder="<?= HtmlEncode($Page->sunday_time_out->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_out->EditValue ?>"<?= $Page->sunday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sunday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->sunday_time_out->ReadOnly && !$Page->sunday_time_out->Disabled && !isset($Page->sunday_time_out->EditAttrs["readonly"]) && !isset($Page->sunday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_sunday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_sunday_time_out" class="ew-search-field2 d-none">
<input type="<?= $Page->sunday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_out" name="y_sunday_time_out" id="y_sunday_time_out" placeholder="<?= HtmlEncode($Page->sunday_time_out->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_out->EditValue2 ?>"<?= $Page->sunday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sunday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->sunday_time_out->ReadOnly && !$Page->sunday_time_out->Disabled && !isset($Page->sunday_time_out->EditAttrs["readonly"]) && !isset($Page->sunday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_sunday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->monday_time_in->Visible) { // monday_time_in ?>
    <div id="r_monday_time_in" class="form-group row">
        <label for="x_monday_time_in" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_monday_time_in"><?= $Page->monday_time_in->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monday_time_in->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_monday_time_in" id="z_monday_time_in" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->monday_time_in->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->monday_time_in->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->monday_time_in->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->monday_time_in->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->monday_time_in->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->monday_time_in->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->monday_time_in->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->monday_time_in->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->monday_time_in->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_monday_time_in" class="ew-search-field">
<input type="<?= $Page->monday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_in" name="x_monday_time_in" id="x_monday_time_in" placeholder="<?= HtmlEncode($Page->monday_time_in->getPlaceHolder()) ?>" value="<?= $Page->monday_time_in->EditValue ?>"<?= $Page->monday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->monday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->monday_time_in->ReadOnly && !$Page->monday_time_in->Disabled && !isset($Page->monday_time_in->EditAttrs["readonly"]) && !isset($Page->monday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_monday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_monday_time_in" class="ew-search-field2 d-none">
<input type="<?= $Page->monday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_in" name="y_monday_time_in" id="y_monday_time_in" placeholder="<?= HtmlEncode($Page->monday_time_in->getPlaceHolder()) ?>" value="<?= $Page->monday_time_in->EditValue2 ?>"<?= $Page->monday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->monday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->monday_time_in->ReadOnly && !$Page->monday_time_in->Disabled && !isset($Page->monday_time_in->EditAttrs["readonly"]) && !isset($Page->monday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_monday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->monday_time_out->Visible) { // monday_time_out ?>
    <div id="r_monday_time_out" class="form-group row">
        <label for="x_monday_time_out" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_monday_time_out"><?= $Page->monday_time_out->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monday_time_out->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_monday_time_out" id="z_monday_time_out" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->monday_time_out->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->monday_time_out->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->monday_time_out->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->monday_time_out->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->monday_time_out->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->monday_time_out->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->monday_time_out->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->monday_time_out->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->monday_time_out->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_monday_time_out" class="ew-search-field">
<input type="<?= $Page->monday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_out" name="x_monday_time_out" id="x_monday_time_out" placeholder="<?= HtmlEncode($Page->monday_time_out->getPlaceHolder()) ?>" value="<?= $Page->monday_time_out->EditValue ?>"<?= $Page->monday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->monday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->monday_time_out->ReadOnly && !$Page->monday_time_out->Disabled && !isset($Page->monday_time_out->EditAttrs["readonly"]) && !isset($Page->monday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_monday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_monday_time_out" class="ew-search-field2 d-none">
<input type="<?= $Page->monday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_out" name="y_monday_time_out" id="y_monday_time_out" placeholder="<?= HtmlEncode($Page->monday_time_out->getPlaceHolder()) ?>" value="<?= $Page->monday_time_out->EditValue2 ?>"<?= $Page->monday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->monday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->monday_time_out->ReadOnly && !$Page->monday_time_out->Disabled && !isset($Page->monday_time_out->EditAttrs["readonly"]) && !isset($Page->monday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_monday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->tuesday_time_in->Visible) { // tuesday_time_in ?>
    <div id="r_tuesday_time_in" class="form-group row">
        <label for="x_tuesday_time_in" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_tuesday_time_in"><?= $Page->tuesday_time_in->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tuesday_time_in->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_tuesday_time_in" id="z_tuesday_time_in" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->tuesday_time_in->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->tuesday_time_in->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->tuesday_time_in->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->tuesday_time_in->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->tuesday_time_in->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->tuesday_time_in->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->tuesday_time_in->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->tuesday_time_in->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->tuesday_time_in->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_tuesday_time_in" class="ew-search-field">
<input type="<?= $Page->tuesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_in" name="x_tuesday_time_in" id="x_tuesday_time_in" placeholder="<?= HtmlEncode($Page->tuesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_in->EditValue ?>"<?= $Page->tuesday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tuesday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->tuesday_time_in->ReadOnly && !$Page->tuesday_time_in->Disabled && !isset($Page->tuesday_time_in->EditAttrs["readonly"]) && !isset($Page->tuesday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_tuesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_tuesday_time_in" class="ew-search-field2 d-none">
<input type="<?= $Page->tuesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_in" name="y_tuesday_time_in" id="y_tuesday_time_in" placeholder="<?= HtmlEncode($Page->tuesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_in->EditValue2 ?>"<?= $Page->tuesday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tuesday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->tuesday_time_in->ReadOnly && !$Page->tuesday_time_in->Disabled && !isset($Page->tuesday_time_in->EditAttrs["readonly"]) && !isset($Page->tuesday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_tuesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->tuesday_time_out->Visible) { // tuesday_time_out ?>
    <div id="r_tuesday_time_out" class="form-group row">
        <label for="x_tuesday_time_out" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_tuesday_time_out"><?= $Page->tuesday_time_out->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tuesday_time_out->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_tuesday_time_out" id="z_tuesday_time_out" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->tuesday_time_out->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->tuesday_time_out->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->tuesday_time_out->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->tuesday_time_out->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->tuesday_time_out->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->tuesday_time_out->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->tuesday_time_out->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->tuesday_time_out->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->tuesday_time_out->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_tuesday_time_out" class="ew-search-field">
<input type="<?= $Page->tuesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_out" name="x_tuesday_time_out" id="x_tuesday_time_out" placeholder="<?= HtmlEncode($Page->tuesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_out->EditValue ?>"<?= $Page->tuesday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tuesday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->tuesday_time_out->ReadOnly && !$Page->tuesday_time_out->Disabled && !isset($Page->tuesday_time_out->EditAttrs["readonly"]) && !isset($Page->tuesday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_tuesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_tuesday_time_out" class="ew-search-field2 d-none">
<input type="<?= $Page->tuesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_out" name="y_tuesday_time_out" id="y_tuesday_time_out" placeholder="<?= HtmlEncode($Page->tuesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_out->EditValue2 ?>"<?= $Page->tuesday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tuesday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->tuesday_time_out->ReadOnly && !$Page->tuesday_time_out->Disabled && !isset($Page->tuesday_time_out->EditAttrs["readonly"]) && !isset($Page->tuesday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_tuesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->wednesday_time_in->Visible) { // wednesday_time_in ?>
    <div id="r_wednesday_time_in" class="form-group row">
        <label for="x_wednesday_time_in" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_wednesday_time_in"><?= $Page->wednesday_time_in->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->wednesday_time_in->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_wednesday_time_in" id="z_wednesday_time_in" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->wednesday_time_in->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->wednesday_time_in->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->wednesday_time_in->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->wednesday_time_in->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->wednesday_time_in->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->wednesday_time_in->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->wednesday_time_in->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->wednesday_time_in->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->wednesday_time_in->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_wednesday_time_in" class="ew-search-field">
<input type="<?= $Page->wednesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_in" name="x_wednesday_time_in" id="x_wednesday_time_in" placeholder="<?= HtmlEncode($Page->wednesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_in->EditValue ?>"<?= $Page->wednesday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->wednesday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->wednesday_time_in->ReadOnly && !$Page->wednesday_time_in->Disabled && !isset($Page->wednesday_time_in->EditAttrs["readonly"]) && !isset($Page->wednesday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_wednesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_wednesday_time_in" class="ew-search-field2 d-none">
<input type="<?= $Page->wednesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_in" name="y_wednesday_time_in" id="y_wednesday_time_in" placeholder="<?= HtmlEncode($Page->wednesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_in->EditValue2 ?>"<?= $Page->wednesday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->wednesday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->wednesday_time_in->ReadOnly && !$Page->wednesday_time_in->Disabled && !isset($Page->wednesday_time_in->EditAttrs["readonly"]) && !isset($Page->wednesday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_wednesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->wednesday_time_out->Visible) { // wednesday_time_out ?>
    <div id="r_wednesday_time_out" class="form-group row">
        <label for="x_wednesday_time_out" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_wednesday_time_out"><?= $Page->wednesday_time_out->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->wednesday_time_out->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_wednesday_time_out" id="z_wednesday_time_out" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->wednesday_time_out->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->wednesday_time_out->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->wednesday_time_out->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->wednesday_time_out->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->wednesday_time_out->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->wednesday_time_out->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->wednesday_time_out->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->wednesday_time_out->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->wednesday_time_out->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_wednesday_time_out" class="ew-search-field">
<input type="<?= $Page->wednesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_out" name="x_wednesday_time_out" id="x_wednesday_time_out" placeholder="<?= HtmlEncode($Page->wednesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_out->EditValue ?>"<?= $Page->wednesday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->wednesday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->wednesday_time_out->ReadOnly && !$Page->wednesday_time_out->Disabled && !isset($Page->wednesday_time_out->EditAttrs["readonly"]) && !isset($Page->wednesday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_wednesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_wednesday_time_out" class="ew-search-field2 d-none">
<input type="<?= $Page->wednesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_out" name="y_wednesday_time_out" id="y_wednesday_time_out" placeholder="<?= HtmlEncode($Page->wednesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_out->EditValue2 ?>"<?= $Page->wednesday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->wednesday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->wednesday_time_out->ReadOnly && !$Page->wednesday_time_out->Disabled && !isset($Page->wednesday_time_out->EditAttrs["readonly"]) && !isset($Page->wednesday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_wednesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->thursday_time_in->Visible) { // thursday_time_in ?>
    <div id="r_thursday_time_in" class="form-group row">
        <label for="x_thursday_time_in" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_thursday_time_in"><?= $Page->thursday_time_in->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->thursday_time_in->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_thursday_time_in" id="z_thursday_time_in" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->thursday_time_in->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->thursday_time_in->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->thursday_time_in->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->thursday_time_in->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->thursday_time_in->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->thursday_time_in->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->thursday_time_in->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->thursday_time_in->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->thursday_time_in->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_thursday_time_in" class="ew-search-field">
<input type="<?= $Page->thursday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_in" name="x_thursday_time_in" id="x_thursday_time_in" placeholder="<?= HtmlEncode($Page->thursday_time_in->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_in->EditValue ?>"<?= $Page->thursday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->thursday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->thursday_time_in->ReadOnly && !$Page->thursday_time_in->Disabled && !isset($Page->thursday_time_in->EditAttrs["readonly"]) && !isset($Page->thursday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_thursday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_thursday_time_in" class="ew-search-field2 d-none">
<input type="<?= $Page->thursday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_in" name="y_thursday_time_in" id="y_thursday_time_in" placeholder="<?= HtmlEncode($Page->thursday_time_in->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_in->EditValue2 ?>"<?= $Page->thursday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->thursday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->thursday_time_in->ReadOnly && !$Page->thursday_time_in->Disabled && !isset($Page->thursday_time_in->EditAttrs["readonly"]) && !isset($Page->thursday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_thursday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->thursday_time_out->Visible) { // thursday_time_out ?>
    <div id="r_thursday_time_out" class="form-group row">
        <label for="x_thursday_time_out" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_thursday_time_out"><?= $Page->thursday_time_out->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->thursday_time_out->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_thursday_time_out" id="z_thursday_time_out" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->thursday_time_out->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->thursday_time_out->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->thursday_time_out->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->thursday_time_out->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->thursday_time_out->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->thursday_time_out->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->thursday_time_out->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->thursday_time_out->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->thursday_time_out->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_thursday_time_out" class="ew-search-field">
<input type="<?= $Page->thursday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_out" name="x_thursday_time_out" id="x_thursday_time_out" placeholder="<?= HtmlEncode($Page->thursday_time_out->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_out->EditValue ?>"<?= $Page->thursday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->thursday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->thursday_time_out->ReadOnly && !$Page->thursday_time_out->Disabled && !isset($Page->thursday_time_out->EditAttrs["readonly"]) && !isset($Page->thursday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_thursday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_thursday_time_out" class="ew-search-field2 d-none">
<input type="<?= $Page->thursday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_out" name="y_thursday_time_out" id="y_thursday_time_out" placeholder="<?= HtmlEncode($Page->thursday_time_out->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_out->EditValue2 ?>"<?= $Page->thursday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->thursday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->thursday_time_out->ReadOnly && !$Page->thursday_time_out->Disabled && !isset($Page->thursday_time_out->EditAttrs["readonly"]) && !isset($Page->thursday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_thursday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->friday_time_in->Visible) { // friday_time_in ?>
    <div id="r_friday_time_in" class="form-group row">
        <label for="x_friday_time_in" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_friday_time_in"><?= $Page->friday_time_in->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->friday_time_in->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_friday_time_in" id="z_friday_time_in" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->friday_time_in->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->friday_time_in->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->friday_time_in->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->friday_time_in->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->friday_time_in->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->friday_time_in->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->friday_time_in->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->friday_time_in->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->friday_time_in->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_friday_time_in" class="ew-search-field">
<input type="<?= $Page->friday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_in" name="x_friday_time_in" id="x_friday_time_in" placeholder="<?= HtmlEncode($Page->friday_time_in->getPlaceHolder()) ?>" value="<?= $Page->friday_time_in->EditValue ?>"<?= $Page->friday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->friday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->friday_time_in->ReadOnly && !$Page->friday_time_in->Disabled && !isset($Page->friday_time_in->EditAttrs["readonly"]) && !isset($Page->friday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_friday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_friday_time_in" class="ew-search-field2 d-none">
<input type="<?= $Page->friday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_in" name="y_friday_time_in" id="y_friday_time_in" placeholder="<?= HtmlEncode($Page->friday_time_in->getPlaceHolder()) ?>" value="<?= $Page->friday_time_in->EditValue2 ?>"<?= $Page->friday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->friday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->friday_time_in->ReadOnly && !$Page->friday_time_in->Disabled && !isset($Page->friday_time_in->EditAttrs["readonly"]) && !isset($Page->friday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_friday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->friday_time_out->Visible) { // friday_time_out ?>
    <div id="r_friday_time_out" class="form-group row">
        <label for="x_friday_time_out" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_friday_time_out"><?= $Page->friday_time_out->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->friday_time_out->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_friday_time_out" id="z_friday_time_out" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->friday_time_out->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->friday_time_out->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->friday_time_out->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->friday_time_out->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->friday_time_out->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->friday_time_out->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->friday_time_out->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->friday_time_out->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->friday_time_out->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_friday_time_out" class="ew-search-field">
<input type="<?= $Page->friday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_out" name="x_friday_time_out" id="x_friday_time_out" placeholder="<?= HtmlEncode($Page->friday_time_out->getPlaceHolder()) ?>" value="<?= $Page->friday_time_out->EditValue ?>"<?= $Page->friday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->friday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->friday_time_out->ReadOnly && !$Page->friday_time_out->Disabled && !isset($Page->friday_time_out->EditAttrs["readonly"]) && !isset($Page->friday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_friday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_friday_time_out" class="ew-search-field2 d-none">
<input type="<?= $Page->friday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_out" name="y_friday_time_out" id="y_friday_time_out" placeholder="<?= HtmlEncode($Page->friday_time_out->getPlaceHolder()) ?>" value="<?= $Page->friday_time_out->EditValue2 ?>"<?= $Page->friday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->friday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->friday_time_out->ReadOnly && !$Page->friday_time_out->Disabled && !isset($Page->friday_time_out->EditAttrs["readonly"]) && !isset($Page->friday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_friday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->saturday_time_in->Visible) { // saturday_time_in ?>
    <div id="r_saturday_time_in" class="form-group row">
        <label for="x_saturday_time_in" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_saturday_time_in"><?= $Page->saturday_time_in->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->saturday_time_in->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_saturday_time_in" id="z_saturday_time_in" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->saturday_time_in->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->saturday_time_in->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->saturday_time_in->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->saturday_time_in->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->saturday_time_in->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->saturday_time_in->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->saturday_time_in->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->saturday_time_in->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->saturday_time_in->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_saturday_time_in" class="ew-search-field">
<input type="<?= $Page->saturday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_in" name="x_saturday_time_in" id="x_saturday_time_in" placeholder="<?= HtmlEncode($Page->saturday_time_in->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_in->EditValue ?>"<?= $Page->saturday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->saturday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->saturday_time_in->ReadOnly && !$Page->saturday_time_in->Disabled && !isset($Page->saturday_time_in->EditAttrs["readonly"]) && !isset($Page->saturday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_saturday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_saturday_time_in" class="ew-search-field2 d-none">
<input type="<?= $Page->saturday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_in" name="y_saturday_time_in" id="y_saturday_time_in" placeholder="<?= HtmlEncode($Page->saturday_time_in->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_in->EditValue2 ?>"<?= $Page->saturday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->saturday_time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->saturday_time_in->ReadOnly && !$Page->saturday_time_in->Disabled && !isset($Page->saturday_time_in->EditAttrs["readonly"]) && !isset($Page->saturday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_saturday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->saturday_time_out->Visible) { // saturday_time_out ?>
    <div id="r_saturday_time_out" class="form-group row">
        <label for="x_saturday_time_out" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_shift_saturday_time_out"><?= $Page->saturday_time_out->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->saturday_time_out->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_saturday_time_out" id="z_saturday_time_out" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->saturday_time_out->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->saturday_time_out->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->saturday_time_out->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->saturday_time_out->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->saturday_time_out->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->saturday_time_out->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->saturday_time_out->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->saturday_time_out->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->saturday_time_out->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_shift_saturday_time_out" class="ew-search-field">
<input type="<?= $Page->saturday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_out" name="x_saturday_time_out" id="x_saturday_time_out" placeholder="<?= HtmlEncode($Page->saturday_time_out->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_out->EditValue ?>"<?= $Page->saturday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->saturday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->saturday_time_out->ReadOnly && !$Page->saturday_time_out->Disabled && !isset($Page->saturday_time_out->EditAttrs["readonly"]) && !isset($Page->saturday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "x_saturday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_shift_saturday_time_out" class="ew-search-field2 d-none">
<input type="<?= $Page->saturday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_out" name="y_saturday_time_out" id="y_saturday_time_out" placeholder="<?= HtmlEncode($Page->saturday_time_out->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_out->EditValue2 ?>"<?= $Page->saturday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->saturday_time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->saturday_time_out->ReadOnly && !$Page->saturday_time_out->Disabled && !isset($Page->saturday_time_out->EditAttrs["readonly"]) && !isset($Page->saturday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftsearch", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftsearch", "y_saturday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
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
    ew.addEventHandlers("master_shift");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
