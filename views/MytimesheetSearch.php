<?php

namespace MEM\prjMitralPHP;

// Page object
$MytimesheetSearch = &$Page;
?>
<script>
if (!ew.vars.tables.mytimesheet) ew.vars.tables.mytimesheet = <?= JsonEncode(GetClientVar("tables", "mytimesheet")) ?>;
var currentForm, currentPageID;
var fmytimesheetsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fmytimesheetsearch = currentAdvancedSearchForm = new ew.Form("fmytimesheetsearch", "search");
    <?php } else { ?>
    fmytimesheetsearch = currentForm = new ew.Form("fmytimesheetsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.mytimesheet.fields;
    fmytimesheetsearch.addFields([
        ["days", [ew.Validators.integer], fields.days.isInvalid],
        ["y_days", [ew.Validators.between], false],
        ["sick", [ew.Validators.integer], fields.sick.isInvalid],
        ["y_sick", [ew.Validators.between], false],
        ["leave", [ew.Validators.integer], fields.leave.isInvalid],
        ["y_leave", [ew.Validators.between], false],
        ["permit", [ew.Validators.integer], fields.permit.isInvalid],
        ["y_permit", [ew.Validators.between], false],
        ["absence", [ew.Validators.integer], fields.absence.isInvalid],
        ["y_absence", [ew.Validators.between], false],
        ["employee_notes", [], fields.employee_notes.isInvalid],
        ["y_employee_notes", [ew.Validators.between], false],
        ["company_notes", [], fields.company_notes.isInvalid],
        ["y_company_notes", [ew.Validators.between], false],
        ["approved", [], fields.approved.isInvalid],
        ["y_approved", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmytimesheetsearch.setInvalid();
    });

    // Validate form
    fmytimesheetsearch.validate = function () {
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
    fmytimesheetsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmytimesheetsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmytimesheetsearch.lists.year = <?= $Page->year->toClientList($Page) ?>;
    fmytimesheetsearch.lists.month = <?= $Page->month->toClientList($Page) ?>;
    fmytimesheetsearch.lists.approved = <?= $Page->approved->toClientList($Page) ?>;
    loadjs.done("fmytimesheetsearch");
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
<form name="fmytimesheetsearch" id="fmytimesheetsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mytimesheet">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->days->Visible) { // days ?>
    <div id="r_days" class="form-group row">
        <label for="x_days" class="<?= $Page->LeftColumnClass ?>"><span id="elh_mytimesheet_days"><?= $Page->days->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->days->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_days" id="z_days" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->days->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->days->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->days->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->days->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->days->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->days->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->days->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->days->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->days->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_mytimesheet_days" class="ew-search-field">
<input type="<?= $Page->days->getInputTextType() ?>" data-table="mytimesheet" data-field="x_days" name="x_days" id="x_days" size="30" placeholder="<?= HtmlEncode($Page->days->getPlaceHolder()) ?>" value="<?= $Page->days->EditValue ?>"<?= $Page->days->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->days->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_mytimesheet_days" class="ew-search-field2 d-none">
<input type="<?= $Page->days->getInputTextType() ?>" data-table="mytimesheet" data-field="x_days" name="y_days" id="y_days" size="30" placeholder="<?= HtmlEncode($Page->days->getPlaceHolder()) ?>" value="<?= $Page->days->EditValue2 ?>"<?= $Page->days->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->days->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->sick->Visible) { // sick ?>
    <div id="r_sick" class="form-group row">
        <label for="x_sick" class="<?= $Page->LeftColumnClass ?>"><span id="elh_mytimesheet_sick"><?= $Page->sick->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sick->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_sick" id="z_sick" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->sick->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->sick->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->sick->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->sick->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->sick->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->sick->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->sick->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->sick->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->sick->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_mytimesheet_sick" class="ew-search-field">
<input type="<?= $Page->sick->getInputTextType() ?>" data-table="mytimesheet" data-field="x_sick" name="x_sick" id="x_sick" size="30" placeholder="<?= HtmlEncode($Page->sick->getPlaceHolder()) ?>" value="<?= $Page->sick->EditValue ?>"<?= $Page->sick->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sick->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_mytimesheet_sick" class="ew-search-field2 d-none">
<input type="<?= $Page->sick->getInputTextType() ?>" data-table="mytimesheet" data-field="x_sick" name="y_sick" id="y_sick" size="30" placeholder="<?= HtmlEncode($Page->sick->getPlaceHolder()) ?>" value="<?= $Page->sick->EditValue2 ?>"<?= $Page->sick->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sick->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->leave->Visible) { // leave ?>
    <div id="r_leave" class="form-group row">
        <label for="x_leave" class="<?= $Page->LeftColumnClass ?>"><span id="elh_mytimesheet_leave"><?= $Page->leave->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->leave->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_leave" id="z_leave" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->leave->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->leave->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->leave->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->leave->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->leave->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->leave->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->leave->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->leave->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->leave->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_mytimesheet_leave" class="ew-search-field">
<input type="<?= $Page->leave->getInputTextType() ?>" data-table="mytimesheet" data-field="x_leave" name="x_leave" id="x_leave" size="30" placeholder="<?= HtmlEncode($Page->leave->getPlaceHolder()) ?>" value="<?= $Page->leave->EditValue ?>"<?= $Page->leave->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->leave->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_mytimesheet_leave" class="ew-search-field2 d-none">
<input type="<?= $Page->leave->getInputTextType() ?>" data-table="mytimesheet" data-field="x_leave" name="y_leave" id="y_leave" size="30" placeholder="<?= HtmlEncode($Page->leave->getPlaceHolder()) ?>" value="<?= $Page->leave->EditValue2 ?>"<?= $Page->leave->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->leave->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->permit->Visible) { // permit ?>
    <div id="r_permit" class="form-group row">
        <label for="x_permit" class="<?= $Page->LeftColumnClass ?>"><span id="elh_mytimesheet_permit"><?= $Page->permit->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->permit->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_permit" id="z_permit" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->permit->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->permit->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->permit->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->permit->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->permit->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->permit->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->permit->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->permit->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->permit->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_mytimesheet_permit" class="ew-search-field">
<input type="<?= $Page->permit->getInputTextType() ?>" data-table="mytimesheet" data-field="x_permit" name="x_permit" id="x_permit" size="30" placeholder="<?= HtmlEncode($Page->permit->getPlaceHolder()) ?>" value="<?= $Page->permit->EditValue ?>"<?= $Page->permit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->permit->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_mytimesheet_permit" class="ew-search-field2 d-none">
<input type="<?= $Page->permit->getInputTextType() ?>" data-table="mytimesheet" data-field="x_permit" name="y_permit" id="y_permit" size="30" placeholder="<?= HtmlEncode($Page->permit->getPlaceHolder()) ?>" value="<?= $Page->permit->EditValue2 ?>"<?= $Page->permit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->permit->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
    <div id="r_absence" class="form-group row">
        <label for="x_absence" class="<?= $Page->LeftColumnClass ?>"><span id="elh_mytimesheet_absence"><?= $Page->absence->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->absence->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_absence" id="z_absence" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->absence->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->absence->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->absence->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->absence->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->absence->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->absence->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="IS NULL"<?= $Page->absence->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->absence->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->absence->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_mytimesheet_absence" class="ew-search-field">
<input type="<?= $Page->absence->getInputTextType() ?>" data-table="mytimesheet" data-field="x_absence" name="x_absence" id="x_absence" size="30" placeholder="<?= HtmlEncode($Page->absence->getPlaceHolder()) ?>" value="<?= $Page->absence->EditValue ?>"<?= $Page->absence->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->absence->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_mytimesheet_absence" class="ew-search-field2 d-none">
<input type="<?= $Page->absence->getInputTextType() ?>" data-table="mytimesheet" data-field="x_absence" name="y_absence" id="y_absence" size="30" placeholder="<?= HtmlEncode($Page->absence->getPlaceHolder()) ?>" value="<?= $Page->absence->EditValue2 ?>"<?= $Page->absence->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->absence->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_notes->Visible) { // employee_notes ?>
    <div id="r_employee_notes" class="form-group row">
        <label for="x_employee_notes" class="<?= $Page->LeftColumnClass ?>"><span id="elh_mytimesheet_employee_notes"><?= $Page->employee_notes->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_notes->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_employee_notes" id="z_employee_notes" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->employee_notes->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->employee_notes->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_mytimesheet_employee_notes" class="ew-search-field">
<input type="<?= $Page->employee_notes->getInputTextType() ?>" data-table="mytimesheet" data-field="x_employee_notes" name="x_employee_notes" id="x_employee_notes" size="35" placeholder="<?= HtmlEncode($Page->employee_notes->getPlaceHolder()) ?>" value="<?= $Page->employee_notes->EditValue ?>"<?= $Page->employee_notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_notes->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_mytimesheet_employee_notes" class="ew-search-field2 d-none">
<input type="<?= $Page->employee_notes->getInputTextType() ?>" data-table="mytimesheet" data-field="x_employee_notes" name="y_employee_notes" id="y_employee_notes" size="35" placeholder="<?= HtmlEncode($Page->employee_notes->getPlaceHolder()) ?>" value="<?= $Page->employee_notes->EditValue2 ?>"<?= $Page->employee_notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->employee_notes->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->company_notes->Visible) { // company_notes ?>
    <div id="r_company_notes" class="form-group row">
        <label for="x_company_notes" class="<?= $Page->LeftColumnClass ?>"><span id="elh_mytimesheet_company_notes"><?= $Page->company_notes->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->company_notes->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_company_notes" id="z_company_notes" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->company_notes->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->company_notes->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->company_notes->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->company_notes->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->company_notes->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->company_notes->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->company_notes->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->company_notes->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->company_notes->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->company_notes->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->company_notes->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->company_notes->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->company_notes->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_mytimesheet_company_notes" class="ew-search-field">
<input type="<?= $Page->company_notes->getInputTextType() ?>" data-table="mytimesheet" data-field="x_company_notes" name="x_company_notes" id="x_company_notes" size="35" placeholder="<?= HtmlEncode($Page->company_notes->getPlaceHolder()) ?>" value="<?= $Page->company_notes->EditValue ?>"<?= $Page->company_notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->company_notes->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_mytimesheet_company_notes" class="ew-search-field2 d-none">
<input type="<?= $Page->company_notes->getInputTextType() ?>" data-table="mytimesheet" data-field="x_company_notes" name="y_company_notes" id="y_company_notes" size="35" placeholder="<?= HtmlEncode($Page->company_notes->getPlaceHolder()) ?>" value="<?= $Page->company_notes->EditValue2 ?>"<?= $Page->company_notes->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->company_notes->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->approved->Visible) { // approved ?>
    <div id="r_approved" class="form-group row">
        <label for="x_approved" class="<?= $Page->LeftColumnClass ?>"><span id="elh_mytimesheet_approved"><?= $Page->approved->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->approved->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_approved" id="z_approved" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->approved->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->approved->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->approved->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->approved->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->approved->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->approved->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->approved->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->approved->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->approved->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->approved->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->approved->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->approved->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->approved->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_mytimesheet_approved" class="ew-search-field">
    <select
        id="x_approved"
        name="x_approved"
        class="form-control ew-select<?= $Page->approved->isInvalidClass() ?>"
        data-select2-id="mytimesheet_x_approved"
        data-table="mytimesheet"
        data-field="x_approved"
        data-value-separator="<?= $Page->approved->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->approved->getPlaceHolder()) ?>"
        <?= $Page->approved->editAttributes() ?>>
        <?= $Page->approved->selectOptionListHtml("x_approved") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->approved->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='mytimesheet_x_approved']"),
        options = { name: "x_approved", selectId: "mytimesheet_x_approved", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.mytimesheet.fields.approved.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mytimesheet.fields.approved.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_mytimesheet_approved" class="ew-search-field2 d-none">
    <select
        id="y_approved"
        name="y_approved"
        class="form-control ew-select<?= $Page->approved->isInvalidClass() ?>"
        data-select2-id="mytimesheet_y_approved"
        data-table="mytimesheet"
        data-field="x_approved"
        data-value-separator="<?= $Page->approved->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->approved->getPlaceHolder()) ?>"
        <?= $Page->approved->editAttributes() ?>>
        <?= $Page->approved->selectOptionListHtml("y_approved") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->approved->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='mytimesheet_y_approved']"),
        options = { name: "y_approved", selectId: "mytimesheet_y_approved", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.mytimesheet.fields.approved.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mytimesheet.fields.approved.selectOptions);
    ew.createSelect(options);
});
</script>
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
    ew.addEventHandlers("mytimesheet");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
