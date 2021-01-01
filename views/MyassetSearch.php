<?php

namespace MEM\prjMitralPHP;

// Page object
$MyassetSearch = &$Page;
?>
<script>
if (!ew.vars.tables.myasset) ew.vars.tables.myasset = <?= JsonEncode(GetClientVar("tables", "myasset")) ?>;
var currentForm, currentPageID;
var fmyassetsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fmyassetsearch = currentAdvancedSearchForm = new ew.Form("fmyassetsearch", "search");
    <?php } else { ?>
    fmyassetsearch = currentForm = new ew.Form("fmyassetsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.myasset.fields;
    fmyassetsearch.addFields([
        ["asset_name", [], fields.asset_name.isInvalid],
        ["y_asset_name", [ew.Validators.between], false],
        ["year", [ew.Validators.integer], fields.year.isInvalid],
        ["y_year", [ew.Validators.between], false],
        ["serial_number", [], fields.serial_number.isInvalid],
        ["y_serial_number", [ew.Validators.between], false],
        ["value", [ew.Validators.float], fields.value.isInvalid],
        ["y_value", [ew.Validators.between], false],
        ["asset_received", [ew.Validators.datetime(5)], fields.asset_received.isInvalid],
        ["y_asset_received", [ew.Validators.between], false],
        ["asset_return", [ew.Validators.datetime(5)], fields.asset_return.isInvalid],
        ["y_asset_return", [ew.Validators.between], false],
        ["notes", [], fields.notes.isInvalid],
        ["y_notes", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmyassetsearch.setInvalid();
    });

    // Validate form
    fmyassetsearch.validate = function () {
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
    fmyassetsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmyassetsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmyassetsearch");
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
<form name="fmyassetsearch" id="fmyassetsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="myasset">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->asset_name->Visible) { // asset_name ?>
    <div id="r_asset_name" class="form-group row">
        <label for="x_asset_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myasset_asset_name"><?= $Page->asset_name->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asset_name->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_asset_name" id="z_asset_name" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->asset_name->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->asset_name->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->asset_name->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->asset_name->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->asset_name->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->asset_name->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->asset_name->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->asset_name->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->asset_name->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->asset_name->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->asset_name->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myasset_asset_name" class="ew-search-field">
<input type="<?= $Page->asset_name->getInputTextType() ?>" data-table="myasset" data-field="x_asset_name" name="x_asset_name" id="x_asset_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->asset_name->getPlaceHolder()) ?>" value="<?= $Page->asset_name->EditValue ?>"<?= $Page->asset_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->asset_name->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myasset_asset_name" class="ew-search-field2 d-none">
<input type="<?= $Page->asset_name->getInputTextType() ?>" data-table="myasset" data-field="x_asset_name" name="y_asset_name" id="y_asset_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->asset_name->getPlaceHolder()) ?>" value="<?= $Page->asset_name->EditValue2 ?>"<?= $Page->asset_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->asset_name->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
    <div id="r_year" class="form-group row">
        <label for="x_year" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myasset_year"><?= $Page->year->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->year->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_year" id="z_year" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->year->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->year->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->year->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->year->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->year->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->year->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->year->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myasset_year" class="ew-search-field">
<input type="<?= $Page->year->getInputTextType() ?>" data-table="myasset" data-field="x_year" name="x_year" id="x_year" size="30" placeholder="<?= HtmlEncode($Page->year->getPlaceHolder()) ?>" value="<?= $Page->year->EditValue ?>"<?= $Page->year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->year->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myasset_year" class="ew-search-field2 d-none">
<input type="<?= $Page->year->getInputTextType() ?>" data-table="myasset" data-field="x_year" name="y_year" id="y_year" size="30" placeholder="<?= HtmlEncode($Page->year->getPlaceHolder()) ?>" value="<?= $Page->year->EditValue2 ?>"<?= $Page->year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->year->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->serial_number->Visible) { // serial_number ?>
    <div id="r_serial_number" class="form-group row">
        <label for="x_serial_number" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myasset_serial_number"><?= $Page->serial_number->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->serial_number->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_serial_number" id="z_serial_number" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->serial_number->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->serial_number->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->serial_number->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->serial_number->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->serial_number->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->serial_number->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->serial_number->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->serial_number->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->serial_number->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->serial_number->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->serial_number->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myasset_serial_number" class="ew-search-field">
<input type="<?= $Page->serial_number->getInputTextType() ?>" data-table="myasset" data-field="x_serial_number" name="x_serial_number" id="x_serial_number" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->serial_number->getPlaceHolder()) ?>" value="<?= $Page->serial_number->EditValue ?>"<?= $Page->serial_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->serial_number->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myasset_serial_number" class="ew-search-field2 d-none">
<input type="<?= $Page->serial_number->getInputTextType() ?>" data-table="myasset" data-field="x_serial_number" name="y_serial_number" id="y_serial_number" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->serial_number->getPlaceHolder()) ?>" value="<?= $Page->serial_number->EditValue2 ?>"<?= $Page->serial_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->serial_number->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->value->Visible) { // value ?>
    <div id="r_value" class="form-group row">
        <label for="x_value" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myasset_value"><?= $Page->value->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->value->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_value" id="z_value" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->value->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->value->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->value->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->value->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->value->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->value->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->value->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->value->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->value->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myasset_value" class="ew-search-field">
<input type="<?= $Page->value->getInputTextType() ?>" data-table="myasset" data-field="x_value" name="x_value" id="x_value" size="30" placeholder="<?= HtmlEncode($Page->value->getPlaceHolder()) ?>" value="<?= $Page->value->EditValue ?>"<?= $Page->value->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->value->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myasset_value" class="ew-search-field2 d-none">
<input type="<?= $Page->value->getInputTextType() ?>" data-table="myasset" data-field="x_value" name="y_value" id="y_value" size="30" placeholder="<?= HtmlEncode($Page->value->getPlaceHolder()) ?>" value="<?= $Page->value->EditValue2 ?>"<?= $Page->value->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->value->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->asset_received->Visible) { // asset_received ?>
    <div id="r_asset_received" class="form-group row">
        <label for="x_asset_received" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myasset_asset_received"><?= $Page->asset_received->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asset_received->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_asset_received" id="z_asset_received" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->asset_received->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->asset_received->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->asset_received->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->asset_received->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->asset_received->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->asset_received->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->asset_received->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->asset_received->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->asset_received->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myasset_asset_received" class="ew-search-field">
<input type="<?= $Page->asset_received->getInputTextType() ?>" data-table="myasset" data-field="x_asset_received" data-format="5" name="x_asset_received" id="x_asset_received" placeholder="<?= HtmlEncode($Page->asset_received->getPlaceHolder()) ?>" value="<?= $Page->asset_received->EditValue ?>"<?= $Page->asset_received->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->asset_received->getErrorMessage(false) ?></div>
<?php if (!$Page->asset_received->ReadOnly && !$Page->asset_received->Disabled && !isset($Page->asset_received->EditAttrs["readonly"]) && !isset($Page->asset_received->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyassetsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyassetsearch", "x_asset_received", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myasset_asset_received" class="ew-search-field2 d-none">
<input type="<?= $Page->asset_received->getInputTextType() ?>" data-table="myasset" data-field="x_asset_received" data-format="5" name="y_asset_received" id="y_asset_received" placeholder="<?= HtmlEncode($Page->asset_received->getPlaceHolder()) ?>" value="<?= $Page->asset_received->EditValue2 ?>"<?= $Page->asset_received->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->asset_received->getErrorMessage(false) ?></div>
<?php if (!$Page->asset_received->ReadOnly && !$Page->asset_received->Disabled && !isset($Page->asset_received->EditAttrs["readonly"]) && !isset($Page->asset_received->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyassetsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyassetsearch", "y_asset_received", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->asset_return->Visible) { // asset_return ?>
    <div id="r_asset_return" class="form-group row">
        <label for="x_asset_return" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myasset_asset_return"><?= $Page->asset_return->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->asset_return->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_asset_return" id="z_asset_return" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->asset_return->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->asset_return->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->asset_return->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->asset_return->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->asset_return->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->asset_return->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->asset_return->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->asset_return->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->asset_return->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_myasset_asset_return" class="ew-search-field">
<input type="<?= $Page->asset_return->getInputTextType() ?>" data-table="myasset" data-field="x_asset_return" data-format="5" name="x_asset_return" id="x_asset_return" placeholder="<?= HtmlEncode($Page->asset_return->getPlaceHolder()) ?>" value="<?= $Page->asset_return->EditValue ?>"<?= $Page->asset_return->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->asset_return->getErrorMessage(false) ?></div>
<?php if (!$Page->asset_return->ReadOnly && !$Page->asset_return->Disabled && !isset($Page->asset_return->EditAttrs["readonly"]) && !isset($Page->asset_return->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyassetsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyassetsearch", "x_asset_return", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myasset_asset_return" class="ew-search-field2 d-none">
<input type="<?= $Page->asset_return->getInputTextType() ?>" data-table="myasset" data-field="x_asset_return" data-format="5" name="y_asset_return" id="y_asset_return" placeholder="<?= HtmlEncode($Page->asset_return->getPlaceHolder()) ?>" value="<?= $Page->asset_return->EditValue2 ?>"<?= $Page->asset_return->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->asset_return->getErrorMessage(false) ?></div>
<?php if (!$Page->asset_return->ReadOnly && !$Page->asset_return->Disabled && !isset($Page->asset_return->EditAttrs["readonly"]) && !isset($Page->asset_return->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmyassetsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmyassetsearch", "y_asset_return", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible) { // notes ?>
    <div id="r_notes" class="form-group row">
        <label for="x_notes" class="<?= $Page->LeftColumnClass ?>"><span id="elh_myasset_notes"><?= $Page->notes->caption() ?></span>
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
            <span id="el_myasset_notes" class="ew-search-field">
<input type="<?= $Page->notes->getInputTextType() ?>" data-table="myasset" data-field="x_notes" name="x_notes" id="x_notes" size="35" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>" value="<?= $Page->notes->EditValue ?>"<?= $Page->notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->notes->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_myasset_notes" class="ew-search-field2 d-none">
<input type="<?= $Page->notes->getInputTextType() ?>" data-table="myasset" data-field="x_notes" name="y_notes" id="y_notes" size="35" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>" value="<?= $Page->notes->EditValue2 ?>"<?= $Page->notes->editAttributes() ?>>
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
    ew.addEventHandlers("myasset");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
