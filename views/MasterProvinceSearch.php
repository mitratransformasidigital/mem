<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterProvinceSearch = &$Page;
?>
<script>
if (!ew.vars.tables.master_province) ew.vars.tables.master_province = <?= JsonEncode(GetClientVar("tables", "master_province")) ?>;
var currentForm, currentPageID;
var fmaster_provincesearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fmaster_provincesearch = currentAdvancedSearchForm = new ew.Form("fmaster_provincesearch", "search");
    <?php } else { ?>
    fmaster_provincesearch = currentForm = new ew.Form("fmaster_provincesearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.master_province.fields;
    fmaster_provincesearch.addFields([
        ["province_id", [], fields.province_id.isInvalid],
        ["y_province_id", [ew.Validators.between], false],
        ["province", [], fields.province.isInvalid],
        ["y_province", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmaster_provincesearch.setInvalid();
    });

    // Validate form
    fmaster_provincesearch.validate = function () {
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
    fmaster_provincesearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_provincesearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_provincesearch");
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
<form name="fmaster_provincesearch" id="fmaster_provincesearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_province">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->province_id->Visible) { // province_id ?>
    <div id="r_province_id" class="form-group row">
        <label for="x_province_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_province_province_id"><?= $Page->province_id->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->province_id->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_province_id" id="z_province_id" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->province_id->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->province_id->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->province_id->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->province_id->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->province_id->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->province_id->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->province_id->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->province_id->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->province_id->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->province_id->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->province_id->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_province_province_id" class="ew-search-field">
<input type="<?= $Page->province_id->getInputTextType() ?>" data-table="master_province" data-field="x_province_id" name="x_province_id" id="x_province_id" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->province_id->getPlaceHolder()) ?>" value="<?= $Page->province_id->EditValue ?>"<?= $Page->province_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->province_id->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_province_province_id" class="ew-search-field2 d-none">
<input type="<?= $Page->province_id->getInputTextType() ?>" data-table="master_province" data-field="x_province_id" name="y_province_id" id="y_province_id" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->province_id->getPlaceHolder()) ?>" value="<?= $Page->province_id->EditValue2 ?>"<?= $Page->province_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->province_id->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->province->Visible) { // province ?>
    <div id="r_province" class="form-group row">
        <label for="x_province" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_province_province"><?= $Page->province->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->province->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_province" id="z_province" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->province->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->province->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->province->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->province->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->province->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->province->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->province->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->province->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->province->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->province->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->province->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_province_province" class="ew-search-field">
<input type="<?= $Page->province->getInputTextType() ?>" data-table="master_province" data-field="x_province" name="x_province" id="x_province" size="30" maxlength="75" placeholder="<?= HtmlEncode($Page->province->getPlaceHolder()) ?>" value="<?= $Page->province->EditValue ?>"<?= $Page->province->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->province->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_province_province" class="ew-search-field2 d-none">
<input type="<?= $Page->province->getInputTextType() ?>" data-table="master_province" data-field="x_province" name="y_province" id="y_province" size="30" maxlength="75" placeholder="<?= HtmlEncode($Page->province->getPlaceHolder()) ?>" value="<?= $Page->province->EditValue2 ?>"<?= $Page->province->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->province->getErrorMessage(false) ?></div>
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
    ew.addEventHandlers("master_province");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
