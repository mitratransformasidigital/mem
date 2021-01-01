<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterHolidaySearch = &$Page;
?>
<script>
if (!ew.vars.tables.master_holiday) ew.vars.tables.master_holiday = <?= JsonEncode(GetClientVar("tables", "master_holiday")) ?>;
var currentForm, currentPageID;
var fmaster_holidaysearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fmaster_holidaysearch = currentAdvancedSearchForm = new ew.Form("fmaster_holidaysearch", "search");
    <?php } else { ?>
    fmaster_holidaysearch = currentForm = new ew.Form("fmaster_holidaysearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.master_holiday.fields;
    fmaster_holidaysearch.addFields([
        ["holiday_id", [], fields.holiday_id.isInvalid],
        ["holiday_date", [ew.Validators.datetime(5)], fields.holiday_date.isInvalid],
        ["y_holiday_date", [ew.Validators.between], false],
        ["holiday_name", [], fields.holiday_name.isInvalid],
        ["y_holiday_name", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmaster_holidaysearch.setInvalid();
    });

    // Validate form
    fmaster_holidaysearch.validate = function () {
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
    fmaster_holidaysearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_holidaysearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_holidaysearch.lists.shift_id = <?= $Page->shift_id->toClientList($Page) ?>;
    loadjs.done("fmaster_holidaysearch");
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
<form name="fmaster_holidaysearch" id="fmaster_holidaysearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_holiday">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->holiday_id->Visible) { // holiday_id ?>
    <div id="r_holiday_id" class="form-group row">
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_holiday_holiday_id"><?= $Page->holiday_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_holiday_id" id="z_holiday_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->holiday_id->cellAttributes() ?>>
            <span id="el_master_holiday_holiday_id" class="ew-search-field">
<input type="<?= $Page->holiday_id->getInputTextType() ?>" data-table="master_holiday" data-field="x_holiday_id" name="x_holiday_id" id="x_holiday_id" placeholder="<?= HtmlEncode($Page->holiday_id->getPlaceHolder()) ?>" value="<?= $Page->holiday_id->EditValue ?>"<?= $Page->holiday_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->holiday_id->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->holiday_date->Visible) { // holiday_date ?>
    <div id="r_holiday_date" class="form-group row">
        <label for="x_holiday_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_holiday_holiday_date"><?= $Page->holiday_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->holiday_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_holiday_date" id="z_holiday_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->holiday_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->holiday_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->holiday_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->holiday_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->holiday_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->holiday_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->holiday_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_holiday_holiday_date" class="ew-search-field">
<input type="<?= $Page->holiday_date->getInputTextType() ?>" data-table="master_holiday" data-field="x_holiday_date" data-format="5" name="x_holiday_date" id="x_holiday_date" placeholder="<?= HtmlEncode($Page->holiday_date->getPlaceHolder()) ?>" value="<?= $Page->holiday_date->EditValue ?>"<?= $Page->holiday_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->holiday_date->getErrorMessage(false) ?></div>
<?php if (!$Page->holiday_date->ReadOnly && !$Page->holiday_date->Disabled && !isset($Page->holiday_date->EditAttrs["readonly"]) && !isset($Page->holiday_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_holidaysearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmaster_holidaysearch", "x_holiday_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_holiday_holiday_date" class="ew-search-field2 d-none">
<input type="<?= $Page->holiday_date->getInputTextType() ?>" data-table="master_holiday" data-field="x_holiday_date" data-format="5" name="y_holiday_date" id="y_holiday_date" placeholder="<?= HtmlEncode($Page->holiday_date->getPlaceHolder()) ?>" value="<?= $Page->holiday_date->EditValue2 ?>"<?= $Page->holiday_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->holiday_date->getErrorMessage(false) ?></div>
<?php if (!$Page->holiday_date->ReadOnly && !$Page->holiday_date->Disabled && !isset($Page->holiday_date->EditAttrs["readonly"]) && !isset($Page->holiday_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_holidaysearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fmaster_holidaysearch", "y_holiday_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->holiday_name->Visible) { // holiday_name ?>
    <div id="r_holiday_name" class="form-group row">
        <label for="x_holiday_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_holiday_holiday_name"><?= $Page->holiday_name->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->holiday_name->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_holiday_name" id="z_holiday_name" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->holiday_name->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->holiday_name->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->holiday_name->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->holiday_name->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->holiday_name->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->holiday_name->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->holiday_name->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->holiday_name->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->holiday_name->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->holiday_name->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->holiday_name->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_holiday_holiday_name" class="ew-search-field">
<input type="<?= $Page->holiday_name->getInputTextType() ?>" data-table="master_holiday" data-field="x_holiday_name" name="x_holiday_name" id="x_holiday_name" maxlength="100" placeholder="<?= HtmlEncode($Page->holiday_name->getPlaceHolder()) ?>" value="<?= $Page->holiday_name->EditValue ?>"<?= $Page->holiday_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->holiday_name->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_holiday_holiday_name" class="ew-search-field2 d-none">
<input type="<?= $Page->holiday_name->getInputTextType() ?>" data-table="master_holiday" data-field="x_holiday_name" name="y_holiday_name" id="y_holiday_name" maxlength="100" placeholder="<?= HtmlEncode($Page->holiday_name->getPlaceHolder()) ?>" value="<?= $Page->holiday_name->EditValue2 ?>"<?= $Page->holiday_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->holiday_name->getErrorMessage(false) ?></div>
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
    ew.addEventHandlers("master_holiday");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
