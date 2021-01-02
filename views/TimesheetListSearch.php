<?php

namespace MEM\prjMitralPHP;

// Page object
$TimesheetListSearch = &$Page;
?>
<script>
if (!ew.vars.tables.timesheet_list) ew.vars.tables.timesheet_list = <?= JsonEncode(GetClientVar("tables", "timesheet_list")) ?>;
var currentForm, currentPageID;
var ftimesheet_listsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    ftimesheet_listsearch = currentAdvancedSearchForm = new ew.Form("ftimesheet_listsearch", "search");
    <?php } else { ?>
    ftimesheet_listsearch = currentForm = new ew.Form("ftimesheet_listsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.timesheet_list.fields;
    ftimesheet_listsearch.addFields([
        ["employee_username", [], fields.employee_username.isInvalid],
        ["work_date", [ew.Validators.datetime(0)], fields.work_date.isInvalid],
        ["y_work_date", [ew.Validators.between], false],
        ["time_in", [ew.Validators.time], fields.time_in.isInvalid],
        ["time_out", [ew.Validators.time], fields.time_out.isInvalid],
        ["description", [], fields.description.isInvalid],
        ["absence", [ew.Validators.integer], fields.absence.isInvalid],
        ["days", [ew.Validators.integer], fields.days.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        ftimesheet_listsearch.setInvalid();
    });

    // Validate form
    ftimesheet_listsearch.validate = function () {
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
    ftimesheet_listsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftimesheet_listsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftimesheet_listsearch.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("ftimesheet_listsearch");
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
<form name="ftimesheet_listsearch" id="ftimesheet_listsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="timesheet_list">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><span id="elh_timesheet_list_employee_username"><?= $Page->employee_username->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_employee_username" id="z_employee_username" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
            <span id="el_timesheet_list_employee_username" class="ew-search-field">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="timesheet_list_x_employee_username"
        data-table="timesheet_list"
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
    var el = document.querySelector("select[data-select2-id='timesheet_list_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "timesheet_list_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.timesheet_list.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->work_date->Visible) { // work_date ?>
    <div id="r_work_date" class="form-group row">
        <label for="x_work_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_timesheet_list_work_date"><?= $Page->work_date->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_work_date" id="z_work_date" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->work_date->cellAttributes() ?>>
            <span id="el_timesheet_list_work_date" class="ew-search-field">
<input type="<?= $Page->work_date->getInputTextType() ?>" data-table="timesheet_list" data-field="x_work_date" name="x_work_date" id="x_work_date" placeholder="<?= HtmlEncode($Page->work_date->getPlaceHolder()) ?>" value="<?= $Page->work_date->EditValue ?>"<?= $Page->work_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->work_date->getErrorMessage(false) ?></div>
<?php if (!$Page->work_date->ReadOnly && !$Page->work_date->Disabled && !isset($Page->work_date->EditAttrs["readonly"]) && !isset($Page->work_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ftimesheet_listsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("ftimesheet_listsearch", "x_work_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
            <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_timesheet_list_work_date" class="ew-search-field2">
<input type="<?= $Page->work_date->getInputTextType() ?>" data-table="timesheet_list" data-field="x_work_date" name="y_work_date" id="y_work_date" placeholder="<?= HtmlEncode($Page->work_date->getPlaceHolder()) ?>" value="<?= $Page->work_date->EditValue2 ?>"<?= $Page->work_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->work_date->getErrorMessage(false) ?></div>
<?php if (!$Page->work_date->ReadOnly && !$Page->work_date->Disabled && !isset($Page->work_date->EditAttrs["readonly"]) && !isset($Page->work_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ftimesheet_listsearch", "datetimepicker"], function() {
    ew.createDateTimePicker("ftimesheet_listsearch", "y_work_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
    <div id="r_time_in" class="form-group row">
        <label for="x_time_in" class="<?= $Page->LeftColumnClass ?>"><span id="elh_timesheet_list_time_in"><?= $Page->time_in->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_time_in" id="z_time_in" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->time_in->cellAttributes() ?>>
            <span id="el_timesheet_list_time_in" class="ew-search-field">
<input type="<?= $Page->time_in->getInputTextType() ?>" data-table="timesheet_list" data-field="x_time_in" name="x_time_in" id="x_time_in" placeholder="<?= HtmlEncode($Page->time_in->getPlaceHolder()) ?>" value="<?= $Page->time_in->EditValue ?>"<?= $Page->time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->time_in->getErrorMessage(false) ?></div>
<?php if (!$Page->time_in->ReadOnly && !$Page->time_in->Disabled && !isset($Page->time_in->EditAttrs["readonly"]) && !isset($Page->time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ftimesheet_listsearch", "timepicker"], function() {
    ew.createTimePicker("ftimesheet_listsearch", "x_time_in", {"timeFormat":"H:i:s","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
    <div id="r_time_out" class="form-group row">
        <label for="x_time_out" class="<?= $Page->LeftColumnClass ?>"><span id="elh_timesheet_list_time_out"><?= $Page->time_out->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_time_out" id="z_time_out" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->time_out->cellAttributes() ?>>
            <span id="el_timesheet_list_time_out" class="ew-search-field">
<input type="<?= $Page->time_out->getInputTextType() ?>" data-table="timesheet_list" data-field="x_time_out" name="x_time_out" id="x_time_out" placeholder="<?= HtmlEncode($Page->time_out->getPlaceHolder()) ?>" value="<?= $Page->time_out->EditValue ?>"<?= $Page->time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->time_out->getErrorMessage(false) ?></div>
<?php if (!$Page->time_out->ReadOnly && !$Page->time_out->Disabled && !isset($Page->time_out->EditAttrs["readonly"]) && !isset($Page->time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ftimesheet_listsearch", "timepicker"], function() {
    ew.createTimePicker("ftimesheet_listsearch", "x_time_out", {"timeFormat":"H:i:s","step":15});
});
</script>
<?php } ?>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description" class="form-group row">
        <label for="x_description" class="<?= $Page->LeftColumnClass ?>"><span id="elh_timesheet_list_description"><?= $Page->description->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_description" id="z_description" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->description->cellAttributes() ?>>
            <span id="el_timesheet_list_description" class="ew-search-field">
<input type="<?= $Page->description->getInputTextType() ?>" data-table="timesheet_list" data-field="x_description" name="x_description" id="x_description" size="35" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>" value="<?= $Page->description->EditValue ?>"<?= $Page->description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
    <div id="r_absence" class="form-group row">
        <label for="x_absence" class="<?= $Page->LeftColumnClass ?>"><span id="elh_timesheet_list_absence"><?= $Page->absence->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_absence" id="z_absence" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->absence->cellAttributes() ?>>
            <span id="el_timesheet_list_absence" class="ew-search-field">
<input type="<?= $Page->absence->getInputTextType() ?>" data-table="timesheet_list" data-field="x_absence" name="x_absence" id="x_absence" size="30" placeholder="<?= HtmlEncode($Page->absence->getPlaceHolder()) ?>" value="<?= $Page->absence->EditValue ?>"<?= $Page->absence->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->absence->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
    <div id="r_days" class="form-group row">
        <label for="x_days" class="<?= $Page->LeftColumnClass ?>"><span id="elh_timesheet_list_days"><?= $Page->days->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_days" id="z_days" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->days->cellAttributes() ?>>
            <span id="el_timesheet_list_days" class="ew-search-field">
<input type="<?= $Page->days->getInputTextType() ?>" data-table="timesheet_list" data-field="x_days" name="x_days" id="x_days" size="30" placeholder="<?= HtmlEncode($Page->days->getPlaceHolder()) ?>" value="<?= $Page->days->EditValue ?>"<?= $Page->days->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->days->getErrorMessage(false) ?></div>
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
    ew.addEventHandlers("timesheet_list");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
