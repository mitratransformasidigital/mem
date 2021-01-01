<?php

namespace MEM\prjMitralPHP;

// Page object
$PermitSearch = &$Page;
?>
<script>
if (!ew.vars.tables.permit) ew.vars.tables.permit = <?= JsonEncode(GetClientVar("tables", "permit")) ?>;
var currentForm, currentPageID;
var fpermitsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fpermitsearch = currentAdvancedSearchForm = new ew.Form("fpermitsearch", "search");
    <?php } else { ?>
    fpermitsearch = currentForm = new ew.Form("fpermitsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.permit.fields;
    fpermitsearch.addFields([
        ["employee_username", [], fields.employee_username.isInvalid],
        ["permit_date", [ew.Validators.datetime(5)], fields.permit_date.isInvalid],
        ["y_permit_date", [ew.Validators.between], false],
        ["permit_type", [], fields.permit_type.isInvalid],
        ["note", [], fields.note.isInvalid],
        ["y_note", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fpermitsearch.setInvalid();
    });

    // Validate form
    fpermitsearch.validate = function () {
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
    fpermitsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpermitsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpermitsearch.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    fpermitsearch.lists.permit_type = <?= $Page->permit_type->toClientList($Page) ?>;
    loadjs.done("fpermitsearch");
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
<form name="fpermitsearch" id="fpermitsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="permit">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><span id="elh_permit_employee_username"><?= $Page->employee_username->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_employee_username" id="z_employee_username" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
            <span id="el_permit_employee_username" class="ew-search-field">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="permit_x_employee_username"
        data-table="permit"
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
    var el = document.querySelector("select[data-select2-id='permit_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "permit_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->permit_date->Visible) { // permit_date ?>
    <div id="r_permit_date" class="form-group row">
        <label for="x_permit_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_permit_permit_date"><?= $Page->permit_date->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->permit_date->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_permit_date" id="z_permit_date" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->permit_date->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->permit_date->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->permit_date->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->permit_date->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->permit_date->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->permit_date->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->permit_date->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_permit_permit_date" class="ew-search-field">
<input type="<?= $Page->permit_date->getInputTextType() ?>" data-table="permit" data-field="x_permit_date" data-format="5" name="x_permit_date" id="x_permit_date" placeholder="<?= HtmlEncode($Page->permit_date->getPlaceHolder()) ?>" value="<?= $Page->permit_date->EditValue ?>"<?= $Page->permit_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->permit_date->getErrorMessage(false) ?></div>
<?php if (!$Page->permit_date->ReadOnly && !$Page->permit_date->Disabled && !isset($Page->permit_date->EditAttrs["readonly"]) && !isset($Page->permit_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpermitsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fpermitsearch", "x_permit_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_permit_permit_date" class="ew-search-field2 d-none">
<input type="<?= $Page->permit_date->getInputTextType() ?>" data-table="permit" data-field="x_permit_date" data-format="5" name="y_permit_date" id="y_permit_date" placeholder="<?= HtmlEncode($Page->permit_date->getPlaceHolder()) ?>" value="<?= $Page->permit_date->EditValue2 ?>"<?= $Page->permit_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->permit_date->getErrorMessage(false) ?></div>
<?php if (!$Page->permit_date->ReadOnly && !$Page->permit_date->Disabled && !isset($Page->permit_date->EditAttrs["readonly"]) && !isset($Page->permit_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpermitsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("fpermitsearch", "y_permit_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->permit_type->Visible) { // permit_type ?>
    <div id="r_permit_type" class="form-group row">
        <label for="x_permit_type" class="<?= $Page->LeftColumnClass ?>"><span id="elh_permit_permit_type"><?= $Page->permit_type->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_permit_type" id="z_permit_type" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->permit_type->cellAttributes() ?>>
            <span id="el_permit_permit_type" class="ew-search-field">
    <select
        id="x_permit_type"
        name="x_permit_type"
        class="form-control ew-select<?= $Page->permit_type->isInvalidClass() ?>"
        data-select2-id="permit_x_permit_type"
        data-table="permit"
        data-field="x_permit_type"
        data-value-separator="<?= $Page->permit_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->permit_type->getPlaceHolder()) ?>"
        <?= $Page->permit_type->editAttributes() ?>>
        <?= $Page->permit_type->selectOptionListHtml("x_permit_type") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->permit_type->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permit_x_permit_type']"),
        options = { name: "x_permit_type", selectId: "permit_x_permit_type", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.permit.fields.permit_type.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permit.fields.permit_type.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->note->Visible) { // note ?>
    <div id="r_note" class="form-group row">
        <label for="x_note" class="<?= $Page->LeftColumnClass ?>"><span id="elh_permit_note"><?= $Page->note->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->note->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_note" id="z_note" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->note->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->note->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->note->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->note->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->note->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->note->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->note->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->note->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->note->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->note->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->note->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->note->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->note->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_permit_note" class="ew-search-field">
<input type="<?= $Page->note->getInputTextType() ?>" data-table="permit" data-field="x_note" name="x_note" id="x_note" size="35" placeholder="<?= HtmlEncode($Page->note->getPlaceHolder()) ?>" value="<?= $Page->note->EditValue ?>"<?= $Page->note->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->note->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_permit_note" class="ew-search-field2 d-none">
<input type="<?= $Page->note->getInputTextType() ?>" data-table="permit" data-field="x_note" name="y_note" id="y_note" size="35" placeholder="<?= HtmlEncode($Page->note->getPlaceHolder()) ?>" value="<?= $Page->note->EditValue2 ?>"<?= $Page->note->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->note->getErrorMessage(false) ?></div>
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
    ew.addEventHandlers("permit");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
