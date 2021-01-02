<?php

namespace MEM\prjMitralPHP;

// Page object
$CustomerSearch = &$Page;
?>
<script>
if (!ew.vars.tables.customer) ew.vars.tables.customer = <?= JsonEncode(GetClientVar("tables", "customer")) ?>;
var currentForm, currentPageID;
var fcustomersearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fcustomersearch = currentAdvancedSearchForm = new ew.Form("fcustomersearch", "search");
    <?php } else { ?>
    fcustomersearch = currentForm = new ew.Form("fcustomersearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.customer.fields;
    fcustomersearch.addFields([
        ["customer_name", [], fields.customer_name.isInvalid],
        ["y_customer_name", [ew.Validators.between], false],
        ["address", [], fields.address.isInvalid],
        ["y_address", [ew.Validators.between], false],
        ["city_id", [], fields.city_id.isInvalid],
        ["phone_number", [], fields.phone_number.isInvalid],
        ["y_phone_number", [ew.Validators.between], false],
        ["contact", [], fields.contact.isInvalid],
        ["y_contact", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fcustomersearch.setInvalid();
    });

    // Validate form
    fcustomersearch.validate = function () {
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
    fcustomersearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcustomersearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcustomersearch.lists.city_id = <?= $Page->city_id->toClientList($Page) ?>;
    loadjs.done("fcustomersearch");
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
<form name="fcustomersearch" id="fcustomersearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="customer">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->customer_name->Visible) { // customer_name ?>
    <div id="r_customer_name" class="form-group row">
        <label for="x_customer_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_customer_customer_name"><?= $Page->customer_name->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->customer_name->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_customer_name" id="z_customer_name" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->customer_name->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->customer_name->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->customer_name->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->customer_name->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->customer_name->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->customer_name->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->customer_name->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->customer_name->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->customer_name->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->customer_name->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->customer_name->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_customer_customer_name" class="ew-search-field">
<input type="<?= $Page->customer_name->getInputTextType() ?>" data-table="customer" data-field="x_customer_name" data-page="1" name="x_customer_name" id="x_customer_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->customer_name->getPlaceHolder()) ?>" value="<?= $Page->customer_name->EditValue ?>"<?= $Page->customer_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->customer_name->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_customer_customer_name" class="ew-search-field2 d-none">
<input type="<?= $Page->customer_name->getInputTextType() ?>" data-table="customer" data-field="x_customer_name" data-page="1" name="y_customer_name" id="y_customer_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->customer_name->getPlaceHolder()) ?>" value="<?= $Page->customer_name->EditValue2 ?>"<?= $Page->customer_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->customer_name->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <div id="r_address" class="form-group row">
        <label for="x_address" class="<?= $Page->LeftColumnClass ?>"><span id="elh_customer_address"><?= $Page->address->caption() ?></span>
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
<option value="BETWEEN"<?= $Page->address->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_customer_address" class="ew-search-field">
<input type="<?= $Page->address->getInputTextType() ?>" data-table="customer" data-field="x_address" data-page="1" name="x_address" id="x_address" maxlength="150" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>" value="<?= $Page->address->EditValue ?>"<?= $Page->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->address->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_customer_address" class="ew-search-field2 d-none">
<input type="<?= $Page->address->getInputTextType() ?>" data-table="customer" data-field="x_address" data-page="1" name="y_address" id="y_address" maxlength="150" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>" value="<?= $Page->address->EditValue2 ?>"<?= $Page->address->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->address->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <div id="r_city_id" class="form-group row">
        <label for="x_city_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_customer_city_id"><?= $Page->city_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_city_id" id="z_city_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->city_id->cellAttributes() ?>>
            <span id="el_customer_city_id" class="ew-search-field">
    <select
        id="x_city_id"
        name="x_city_id"
        class="form-control ew-select<?= $Page->city_id->isInvalidClass() ?>"
        data-select2-id="customer_x_city_id"
        data-table="customer"
        data-field="x_city_id"
        data-page="1"
        data-value-separator="<?= $Page->city_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>"
        <?= $Page->city_id->editAttributes() ?>>
        <?= $Page->city_id->selectOptionListHtml("x_city_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->city_id->getErrorMessage(false) ?></div>
<?= $Page->city_id->Lookup->getParamTag($Page, "p_x_city_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='customer_x_city_id']"),
        options = { name: "x_city_id", selectId: "customer_x_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.customer.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->phone_number->Visible) { // phone_number ?>
    <div id="r_phone_number" class="form-group row">
        <label for="x_phone_number" class="<?= $Page->LeftColumnClass ?>"><span id="elh_customer_phone_number"><?= $Page->phone_number->caption() ?></span>
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
<option value="BETWEEN"<?= $Page->phone_number->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_customer_phone_number" class="ew-search-field">
<input type="<?= $Page->phone_number->getInputTextType() ?>" data-table="customer" data-field="x_phone_number" data-page="1" name="x_phone_number" id="x_phone_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->phone_number->getPlaceHolder()) ?>" value="<?= $Page->phone_number->EditValue ?>"<?= $Page->phone_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->phone_number->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_customer_phone_number" class="ew-search-field2 d-none">
<input type="<?= $Page->phone_number->getInputTextType() ?>" data-table="customer" data-field="x_phone_number" data-page="1" name="y_phone_number" id="y_phone_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->phone_number->getPlaceHolder()) ?>" value="<?= $Page->phone_number->EditValue2 ?>"<?= $Page->phone_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->phone_number->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->contact->Visible) { // contact ?>
    <div id="r_contact" class="form-group row">
        <label for="x_contact" class="<?= $Page->LeftColumnClass ?>"><span id="elh_customer_contact"><?= $Page->contact->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->contact->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_contact" id="z_contact" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->contact->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->contact->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->contact->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->contact->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->contact->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->contact->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->contact->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->contact->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->contact->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->contact->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->contact->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_customer_contact" class="ew-search-field">
<input type="<?= $Page->contact->getInputTextType() ?>" data-table="customer" data-field="x_contact" data-page="1" name="x_contact" id="x_contact" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->contact->getPlaceHolder()) ?>" value="<?= $Page->contact->EditValue ?>"<?= $Page->contact->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->contact->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_customer_contact" class="ew-search-field2 d-none">
<input type="<?= $Page->contact->getInputTextType() ?>" data-table="customer" data-field="x_contact" data-page="1" name="y_contact" id="y_contact" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->contact->getPlaceHolder()) ?>" value="<?= $Page->contact->EditValue2 ?>"<?= $Page->contact->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->contact->getErrorMessage(false) ?></div>
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
    ew.addEventHandlers("customer");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
