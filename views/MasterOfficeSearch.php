<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterOfficeSearch = &$Page;
?>
<script>
if (!ew.vars.tables.master_office) ew.vars.tables.master_office = <?= JsonEncode(GetClientVar("tables", "master_office")) ?>;
var currentForm, currentPageID;
var fmaster_officesearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fmaster_officesearch = currentAdvancedSearchForm = new ew.Form("fmaster_officesearch", "search");
    <?php } else { ?>
    fmaster_officesearch = currentForm = new ew.Form("fmaster_officesearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.master_office.fields;
    fmaster_officesearch.addFields([
        ["office", [], fields.office.isInvalid],
        ["y_office", [ew.Validators.between], false],
        ["address", [], fields.address.isInvalid],
        ["y_address", [ew.Validators.between], false],
        ["city_id", [], fields.city_id.isInvalid],
        ["phone_number", [], fields.phone_number.isInvalid],
        ["y_phone_number", [ew.Validators.between], false],
        ["contact_name", [], fields.contact_name.isInvalid],
        ["y_contact_name", [ew.Validators.between], false],
        ["description", [], fields.description.isInvalid],
        ["y_description", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmaster_officesearch.setInvalid();
    });

    // Validate form
    fmaster_officesearch.validate = function () {
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
    fmaster_officesearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_officesearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_officesearch.lists.city_id = <?= $Page->city_id->toClientList($Page) ?>;
    loadjs.done("fmaster_officesearch");
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
<form name="fmaster_officesearch" id="fmaster_officesearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_office">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->office->Visible) { // office ?>
    <div id="r_office" class="form-group row">
        <label for="x_office" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_office_office"><?= $Page->office->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->office->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_office" id="z_office" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->office->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->office->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->office->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->office->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->office->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->office->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->office->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->office->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->office->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->office->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->office->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_office_office" class="ew-search-field">
<input type="<?= $Page->office->getInputTextType() ?>" data-table="master_office" data-field="x_office" name="x_office" id="x_office" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->office->getPlaceHolder()) ?>" value="<?= $Page->office->EditValue ?>"<?= $Page->office->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->office->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_office_office" class="ew-search-field2 d-none">
<input type="<?= $Page->office->getInputTextType() ?>" data-table="master_office" data-field="x_office" name="y_office" id="y_office" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->office->getPlaceHolder()) ?>" value="<?= $Page->office->EditValue2 ?>"<?= $Page->office->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->office->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <div id="r_address" class="form-group row">
        <label for="x_address" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_office_address"><?= $Page->address->caption() ?></span>
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
            <span id="el_master_office_address" class="ew-search-field">
<input type="<?= $Page->address->getInputTextType() ?>" data-table="master_office" data-field="x_address" name="x_address" id="x_address" maxlength="200" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>" value="<?= $Page->address->EditValue ?>"<?= $Page->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->address->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_office_address" class="ew-search-field2 d-none">
<input type="<?= $Page->address->getInputTextType() ?>" data-table="master_office" data-field="x_address" name="y_address" id="y_address" maxlength="200" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>" value="<?= $Page->address->EditValue2 ?>"<?= $Page->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->address->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <div id="r_city_id" class="form-group row">
        <label for="x_city_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_office_city_id"><?= $Page->city_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_city_id" id="z_city_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->city_id->cellAttributes() ?>>
            <span id="el_master_office_city_id" class="ew-search-field">
<input type="<?= $Page->city_id->getInputTextType() ?>" data-table="master_office" data-field="x_city_id" name="x_city_id" id="x_city_id" size="30" placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>" value="<?= $Page->city_id->EditValue ?>"<?= $Page->city_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->city_id->getErrorMessage(false) ?></div>
<?= $Page->city_id->Lookup->getParamTag($Page, "p_x_city_id") ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->phone_number->Visible) { // phone_number ?>
    <div id="r_phone_number" class="form-group row">
        <label for="x_phone_number" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_office_phone_number"><?= $Page->phone_number->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->phone_number->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_phone_number" id="z_phone_number" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->phone_number->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->phone_number->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->phone_number->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->phone_number->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_office_phone_number" class="ew-search-field">
<input type="<?= $Page->phone_number->getInputTextType() ?>" data-table="master_office" data-field="x_phone_number" name="x_phone_number" id="x_phone_number" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->phone_number->getPlaceHolder()) ?>" value="<?= $Page->phone_number->EditValue ?>"<?= $Page->phone_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->phone_number->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_office_phone_number" class="ew-search-field2 d-none">
<input type="<?= $Page->phone_number->getInputTextType() ?>" data-table="master_office" data-field="x_phone_number" name="y_phone_number" id="y_phone_number" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->phone_number->getPlaceHolder()) ?>" value="<?= $Page->phone_number->EditValue2 ?>"<?= $Page->phone_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->phone_number->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->contact_name->Visible) { // contact_name ?>
    <div id="r_contact_name" class="form-group row">
        <label for="x_contact_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_office_contact_name"><?= $Page->contact_name->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->contact_name->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_contact_name" id="z_contact_name" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->contact_name->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->contact_name->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->contact_name->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->contact_name->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->contact_name->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->contact_name->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->contact_name->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->contact_name->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->contact_name->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->contact_name->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->contact_name->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->contact_name->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->contact_name->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_office_contact_name" class="ew-search-field">
<input type="<?= $Page->contact_name->getInputTextType() ?>" data-table="master_office" data-field="x_contact_name" name="x_contact_name" id="x_contact_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->contact_name->getPlaceHolder()) ?>" value="<?= $Page->contact_name->EditValue ?>"<?= $Page->contact_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->contact_name->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_office_contact_name" class="ew-search-field2 d-none">
<input type="<?= $Page->contact_name->getInputTextType() ?>" data-table="master_office" data-field="x_contact_name" name="y_contact_name" id="y_contact_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->contact_name->getPlaceHolder()) ?>" value="<?= $Page->contact_name->EditValue2 ?>"<?= $Page->contact_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->contact_name->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description" class="form-group row">
        <label for="x_description" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_office_description"><?= $Page->description->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->description->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_description" id="z_description" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->description->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->description->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->description->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->description->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->description->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->description->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->description->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->description->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->description->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->description->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->description->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->description->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->description->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_office_description" class="ew-search-field">
<input type="<?= $Page->description->getInputTextType() ?>" data-table="master_office" data-field="x_description" name="x_description" id="x_description" maxlength="250" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>" value="<?= $Page->description->EditValue ?>"<?= $Page->description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_office_description" class="ew-search-field2 d-none">
<input type="<?= $Page->description->getInputTextType() ?>" data-table="master_office" data-field="x_description" name="y_description" id="y_description" maxlength="250" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>" value="<?= $Page->description->EditValue2 ?>"<?= $Page->description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage(false) ?></div>
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
    ew.addEventHandlers("master_office");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
