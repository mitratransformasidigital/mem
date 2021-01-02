<?php

namespace MEM\prjMitralPHP;

// Page object
$SettingSearch = &$Page;
?>
<script>
if (!ew.vars.tables.setting) ew.vars.tables.setting = <?= JsonEncode(GetClientVar("tables", "setting")) ?>;
var currentForm, currentPageID;
var fsettingsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fsettingsearch = currentAdvancedSearchForm = new ew.Form("fsettingsearch", "search");
    <?php } else { ?>
    fsettingsearch = currentForm = new ew.Form("fsettingsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.setting.fields;
    fsettingsearch.addFields([
        ["setting_id", [], fields.setting_id.isInvalid],
        ["setting_name", [], fields.setting_name.isInvalid],
        ["y_setting_name", [ew.Validators.between], false],
        ["setting_value", [], fields.setting_value.isInvalid],
        ["y_setting_value", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fsettingsearch.setInvalid();
    });

    // Validate form
    fsettingsearch.validate = function () {
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
    fsettingsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsettingsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fsettingsearch");
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
<form name="fsettingsearch" id="fsettingsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="setting">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->setting_id->Visible) { // setting_id ?>
    <div id="r_setting_id" class="form-group row">
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_setting_setting_id"><?= $Page->setting_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_setting_id" id="z_setting_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->setting_id->cellAttributes() ?>>
            <span id="el_setting_setting_id" class="ew-search-field">
<input type="<?= $Page->setting_id->getInputTextType() ?>" data-table="setting" data-field="x_setting_id" data-page="1" name="x_setting_id" id="x_setting_id" placeholder="<?= HtmlEncode($Page->setting_id->getPlaceHolder()) ?>" value="<?= $Page->setting_id->EditValue ?>"<?= $Page->setting_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->setting_id->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->setting_name->Visible) { // setting_name ?>
    <div id="r_setting_name" class="form-group row">
        <label for="x_setting_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_setting_setting_name"><?= $Page->setting_name->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->setting_name->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_setting_name" id="z_setting_name" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->setting_name->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->setting_name->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->setting_name->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->setting_name->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->setting_name->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->setting_name->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->setting_name->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->setting_name->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->setting_name->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->setting_name->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->setting_name->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_setting_setting_name" class="ew-search-field">
<input type="<?= $Page->setting_name->getInputTextType() ?>" data-table="setting" data-field="x_setting_name" data-page="1" name="x_setting_name" id="x_setting_name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->setting_name->getPlaceHolder()) ?>" value="<?= $Page->setting_name->EditValue ?>"<?= $Page->setting_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->setting_name->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_setting_setting_name" class="ew-search-field2 d-none">
<input type="<?= $Page->setting_name->getInputTextType() ?>" data-table="setting" data-field="x_setting_name" data-page="1" name="y_setting_name" id="y_setting_name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->setting_name->getPlaceHolder()) ?>" value="<?= $Page->setting_name->EditValue2 ?>"<?= $Page->setting_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->setting_name->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->setting_value->Visible) { // setting_value ?>
    <div id="r_setting_value" class="form-group row">
        <label for="x_setting_value" class="<?= $Page->LeftColumnClass ?>"><span id="elh_setting_setting_value"><?= $Page->setting_value->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->setting_value->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_setting_value" id="z_setting_value" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->setting_value->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->setting_value->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->setting_value->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->setting_value->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->setting_value->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->setting_value->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->setting_value->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->setting_value->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->setting_value->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->setting_value->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->setting_value->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_setting_setting_value" class="ew-search-field">
<input type="<?= $Page->setting_value->getInputTextType() ?>" data-table="setting" data-field="x_setting_value" data-page="1" name="x_setting_value" id="x_setting_value" size="35" placeholder="<?= HtmlEncode($Page->setting_value->getPlaceHolder()) ?>" value="<?= $Page->setting_value->EditValue ?>"<?= $Page->setting_value->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->setting_value->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_setting_setting_value" class="ew-search-field2 d-none">
<input type="<?= $Page->setting_value->getInputTextType() ?>" data-table="setting" data-field="x_setting_value" data-page="1" name="y_setting_value" id="y_setting_value" size="35" placeholder="<?= HtmlEncode($Page->setting_value->getPlaceHolder()) ?>" value="<?= $Page->setting_value->EditValue2 ?>"<?= $Page->setting_value->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->setting_value->getErrorMessage(false) ?></div>
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
    ew.addEventHandlers("setting");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
