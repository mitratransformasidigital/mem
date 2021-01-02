<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingSearch = &$Page;
?>
<script>
if (!ew.vars.tables.offering) ew.vars.tables.offering = <?= JsonEncode(GetClientVar("tables", "offering")) ?>;
var currentForm, currentPageID;
var fofferingsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fofferingsearch = currentAdvancedSearchForm = new ew.Form("fofferingsearch", "search");
    <?php } else { ?>
    fofferingsearch = currentForm = new ew.Form("fofferingsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.offering.fields;
    fofferingsearch.addFields([
        ["offering_id", [], fields.offering_id.isInvalid],
        ["offering_no", [], fields.offering_no.isInvalid],
        ["y_offering_no", [ew.Validators.between], false],
        ["customer_id", [], fields.customer_id.isInvalid],
        ["offering_date", [ew.Validators.datetime(0)], fields.offering_date.isInvalid],
        ["y_offering_date", [ew.Validators.between], false],
        ["offering_term", [], fields.offering_term.isInvalid],
        ["y_offering_term", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fofferingsearch.setInvalid();
    });

    // Validate form
    fofferingsearch.validate = function () {
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
    fofferingsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fofferingsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fofferingsearch.lists.customer_id = <?= $Page->customer_id->toClientList($Page) ?>;
    loadjs.done("fofferingsearch");
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
<form name="fofferingsearch" id="fofferingsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="offering">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->offering_id->Visible) { // offering_id ?>
    <div id="r_offering_id" class="form-group row">
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_offering_offering_id"><?= $Page->offering_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_offering_id" id="z_offering_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->offering_id->cellAttributes() ?>>
            <span id="el_offering_offering_id" class="ew-search-field">
<input type="<?= $Page->offering_id->getInputTextType() ?>" data-table="offering" data-field="x_offering_id" data-page="1" name="x_offering_id" id="x_offering_id" placeholder="<?= HtmlEncode($Page->offering_id->getPlaceHolder()) ?>" value="<?= $Page->offering_id->EditValue ?>"<?= $Page->offering_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->offering_id->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->offering_no->Visible) { // offering_no ?>
    <div id="r_offering_no" class="form-group row">
        <label for="x_offering_no" class="<?= $Page->LeftColumnClass ?>"><span id="elh_offering_offering_no"><?= $Page->offering_no->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->offering_no->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_offering_no" id="z_offering_no" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->offering_no->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->offering_no->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->offering_no->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->offering_no->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->offering_no->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->offering_no->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->offering_no->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->offering_no->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->offering_no->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->offering_no->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->offering_no->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_offering_offering_no" class="ew-search-field">
<input type="<?= $Page->offering_no->getInputTextType() ?>" data-table="offering" data-field="x_offering_no" data-page="1" name="x_offering_no" id="x_offering_no" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->offering_no->getPlaceHolder()) ?>" value="<?= $Page->offering_no->EditValue ?>"<?= $Page->offering_no->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->offering_no->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_offering_offering_no" class="ew-search-field2 d-none">
<input type="<?= $Page->offering_no->getInputTextType() ?>" data-table="offering" data-field="x_offering_no" data-page="1" name="y_offering_no" id="y_offering_no" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->offering_no->getPlaceHolder()) ?>" value="<?= $Page->offering_no->EditValue2 ?>"<?= $Page->offering_no->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->offering_no->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->customer_id->Visible) { // customer_id ?>
    <div id="r_customer_id" class="form-group row">
        <label for="x_customer_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_offering_customer_id"><?= $Page->customer_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_customer_id" id="z_customer_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->customer_id->cellAttributes() ?>>
            <span id="el_offering_customer_id" class="ew-search-field">
    <select
        id="x_customer_id"
        name="x_customer_id"
        class="form-control ew-select<?= $Page->customer_id->isInvalidClass() ?>"
        data-select2-id="offering_x_customer_id"
        data-table="offering"
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
    var el = document.querySelector("select[data-select2-id='offering_x_customer_id']"),
        options = { name: "x_customer_id", selectId: "offering_x_customer_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.offering.fields.customer_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->offering_date->Visible) { // offering_date ?>
    <div id="r_offering_date" class="form-group row">
        <label for="x_offering_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_offering_offering_date"><?= $Page->offering_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->offering_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_offering_date" id="z_offering_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->offering_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->offering_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->offering_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->offering_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->offering_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->offering_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->offering_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_offering_offering_date" class="ew-search-field">
<input type="<?= $Page->offering_date->getInputTextType() ?>" data-table="offering" data-field="x_offering_date" data-page="1" name="x_offering_date" id="x_offering_date" placeholder="<?= HtmlEncode($Page->offering_date->getPlaceHolder()) ?>" value="<?= $Page->offering_date->EditValue ?>"<?= $Page->offering_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->offering_date->getErrorMessage(false) ?></div>
<?php if (!$Page->offering_date->ReadOnly && !$Page->offering_date->Disabled && !isset($Page->offering_date->EditAttrs["readonly"]) && !isset($Page->offering_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fofferingsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fofferingsearch", "x_offering_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_offering_offering_date" class="ew-search-field2 d-none">
<input type="<?= $Page->offering_date->getInputTextType() ?>" data-table="offering" data-field="x_offering_date" data-page="1" name="y_offering_date" id="y_offering_date" placeholder="<?= HtmlEncode($Page->offering_date->getPlaceHolder()) ?>" value="<?= $Page->offering_date->EditValue2 ?>"<?= $Page->offering_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->offering_date->getErrorMessage(false) ?></div>
<?php if (!$Page->offering_date->ReadOnly && !$Page->offering_date->Disabled && !isset($Page->offering_date->EditAttrs["readonly"]) && !isset($Page->offering_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fofferingsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fofferingsearch", "y_offering_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->offering_term->Visible) { // offering_term ?>
    <div id="r_offering_term" class="form-group row">
        <label for="x_offering_term" class="<?= $Page->LeftColumnClass ?>"><span id="elh_offering_offering_term"><?= $Page->offering_term->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->offering_term->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_offering_term" id="z_offering_term" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->offering_term->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->offering_term->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->offering_term->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->offering_term->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->offering_term->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->offering_term->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->offering_term->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->offering_term->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->offering_term->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->offering_term->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->offering_term->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_offering_offering_term" class="ew-search-field">
<input type="<?= $Page->offering_term->getInputTextType() ?>" data-table="offering" data-field="x_offering_term" data-page="1" name="x_offering_term" id="x_offering_term" size="35" placeholder="<?= HtmlEncode($Page->offering_term->getPlaceHolder()) ?>" value="<?= $Page->offering_term->EditValue ?>"<?= $Page->offering_term->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->offering_term->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_offering_offering_term" class="ew-search-field2 d-none">
<input type="<?= $Page->offering_term->getInputTextType() ?>" data-table="offering" data-field="x_offering_term" data-page="1" name="y_offering_term" id="y_offering_term" size="35" placeholder="<?= HtmlEncode($Page->offering_term->getPlaceHolder()) ?>" value="<?= $Page->offering_term->EditValue2 ?>"<?= $Page->offering_term->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->offering_term->getErrorMessage(false) ?></div>
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
    ew.addEventHandlers("offering");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
