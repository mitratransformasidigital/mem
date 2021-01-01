<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterCitySearch = &$Page;
?>
<script>
if (!ew.vars.tables.master_city) ew.vars.tables.master_city = <?= JsonEncode(GetClientVar("tables", "master_city")) ?>;
var currentForm, currentPageID;
var fmaster_citysearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fmaster_citysearch = currentAdvancedSearchForm = new ew.Form("fmaster_citysearch", "search");
    <?php } else { ?>
    fmaster_citysearch = currentForm = new ew.Form("fmaster_citysearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.master_city.fields;
    fmaster_citysearch.addFields([
        ["city_id", [], fields.city_id.isInvalid],
        ["y_city_id", [ew.Validators.between], false],
        ["city", [], fields.city.isInvalid],
        ["y_city", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmaster_citysearch.setInvalid();
    });

    // Validate form
    fmaster_citysearch.validate = function () {
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
    fmaster_citysearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_citysearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_citysearch.lists.province_id = <?= $Page->province_id->toClientList($Page) ?>;
    loadjs.done("fmaster_citysearch");
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
<form name="fmaster_citysearch" id="fmaster_citysearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_city">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->city_id->Visible) { // city_id ?>
    <div id="r_city_id" class="form-group row">
        <label for="x_city_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_city_city_id"><?= $Page->city_id->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->city_id->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_city_id" id="z_city_id" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->city_id->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->city_id->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->city_id->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->city_id->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->city_id->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->city_id->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->city_id->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->city_id->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->city_id->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->city_id->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->city_id->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_city_city_id" class="ew-search-field">
<input type="<?= $Page->city_id->getInputTextType() ?>" data-table="master_city" data-field="x_city_id" name="x_city_id" id="x_city_id" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>" value="<?= $Page->city_id->EditValue ?>"<?= $Page->city_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->city_id->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_city_city_id" class="ew-search-field2 d-none">
<input type="<?= $Page->city_id->getInputTextType() ?>" data-table="master_city" data-field="x_city_id" name="y_city_id" id="y_city_id" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>" value="<?= $Page->city_id->EditValue2 ?>"<?= $Page->city_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->city_id->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->city->Visible) { // city ?>
    <div id="r_city" class="form-group row">
        <label for="x_city" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_city_city"><?= $Page->city->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->city->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_city" id="z_city" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->city->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->city->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->city->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->city->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->city->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->city->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->city->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->city->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->city->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->city->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->city->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_city_city" class="ew-search-field">
<input type="<?= $Page->city->getInputTextType() ?>" data-table="master_city" data-field="x_city" name="x_city" id="x_city" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->city->getPlaceHolder()) ?>" value="<?= $Page->city->EditValue ?>"<?= $Page->city->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->city->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_city_city" class="ew-search-field2 d-none">
<input type="<?= $Page->city->getInputTextType() ?>" data-table="master_city" data-field="x_city" name="y_city" id="y_city" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->city->getPlaceHolder()) ?>" value="<?= $Page->city->EditValue2 ?>"<?= $Page->city->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->city->getErrorMessage(false) ?></div>
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
    ew.addEventHandlers("master_city");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
