<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeShiftAdd = &$Page;
?>
<script>
if (!ew.vars.tables.employee_shift) ew.vars.tables.employee_shift = <?= JsonEncode(GetClientVar("tables", "employee_shift")) ?>;
var currentForm, currentPageID;
var femployee_shiftadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    femployee_shiftadd = currentForm = new ew.Form("femployee_shiftadd", "add");

    // Add fields
    var fields = ew.vars.tables.employee_shift.fields;
    femployee_shiftadd.addFields([
        ["shift_id", [fields.shift_id.required ? ew.Validators.required(fields.shift_id.caption) : null], fields.shift_id.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["start_date", [fields.start_date.required ? ew.Validators.required(fields.start_date.caption) : null, ew.Validators.datetime(5)], fields.start_date.isInvalid],
        ["end_date", [fields.end_date.required ? ew.Validators.required(fields.end_date.caption) : null, ew.Validators.datetime(5)], fields.end_date.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_shiftadd,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    femployee_shiftadd.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    femployee_shiftadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_shiftadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_shiftadd.lists.shift_id = <?= $Page->shift_id->toClientList($Page) ?>;
    femployee_shiftadd.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("femployee_shiftadd");
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
<form name="femployee_shiftadd" id="femployee_shiftadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_shift">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "master_shift") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_shift">
<input type="hidden" name="fk_shift_id" value="<?= HtmlEncode($Page->shift_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "employee") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="employee">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->shift_id->Visible) { // shift_id ?>
    <div id="r_shift_id" class="form-group row">
        <label id="elh_employee_shift_shift_id" for="x_shift_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->shift_id->caption() ?><?= $Page->shift_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->shift_id->cellAttributes() ?>>
<?php if ($Page->shift_id->getSessionValue() != "") { ?>
<span id="el_employee_shift_shift_id">
<span<?= $Page->shift_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->shift_id->getDisplayValue($Page->shift_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_shift_id" name="x_shift_id" value="<?= HtmlEncode($Page->shift_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_employee_shift_shift_id">
<div class="input-group flex-nowrap">
    <select
        id="x_shift_id"
        name="x_shift_id"
        class="form-control ew-select<?= $Page->shift_id->isInvalidClass() ?>"
        data-select2-id="employee_shift_x_shift_id"
        data-table="employee_shift"
        data-field="x_shift_id"
        data-value-separator="<?= $Page->shift_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->shift_id->getPlaceHolder()) ?>"
        <?= $Page->shift_id->editAttributes() ?>>
        <?= $Page->shift_id->selectOptionListHtml("x_shift_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_shift") && !$Page->shift_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_shift_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->shift_id->caption() ?>" data-title="<?= $Page->shift_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_shift_id',url:'<?= GetUrl("mastershiftaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->shift_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->shift_id->getErrorMessage() ?></div>
<?= $Page->shift_id->Lookup->getParamTag($Page, "p_x_shift_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_shift_x_shift_id']"),
        options = { name: "x_shift_id", selectId: "employee_shift_x_shift_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_shift.fields.shift_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label id="elh_employee_shift_employee_username" for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_username->caption() ?><?= $Page->employee_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
<?php if ($Page->employee_username->getSessionValue() != "") { ?>
<span id="el_employee_shift_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_username->getDisplayValue($Page->employee_username->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_employee_username" name="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_employee_shift_employee_username">
<div class="input-group flex-nowrap">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_shift_x_employee_username"
        data-table="employee_shift"
        data-field="x_employee_username"
        data-value-separator="<?= $Page->employee_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>"
        <?= $Page->employee_username->editAttributes() ?>>
        <?= $Page->employee_username->selectOptionListHtml("x_employee_username") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "employee") && !$Page->employee_username->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_employee_username" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->employee_username->caption() ?>" data-title="<?= $Page->employee_username->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_employee_username',url:'<?= GetUrl("employeeaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->employee_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage() ?></div>
<?= $Page->employee_username->Lookup->getParamTag($Page, "p_x_employee_username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='employee_shift_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "employee_shift_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_shift.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->start_date->Visible) { // start_date ?>
    <div id="r_start_date" class="form-group row">
        <label id="elh_employee_shift_start_date" for="x_start_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->start_date->caption() ?><?= $Page->start_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->start_date->cellAttributes() ?>>
<span id="el_employee_shift_start_date">
<input type="<?= $Page->start_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_start_date" data-format="5" name="x_start_date" id="x_start_date" placeholder="<?= HtmlEncode($Page->start_date->getPlaceHolder()) ?>" value="<?= $Page->start_date->EditValue ?>"<?= $Page->start_date->editAttributes() ?> aria-describedby="x_start_date_help">
<?= $Page->start_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->start_date->getErrorMessage() ?></div>
<?php if (!$Page->start_date->ReadOnly && !$Page->start_date->Disabled && !isset($Page->start_date->EditAttrs["readonly"]) && !isset($Page->start_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftadd", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftadd", "x_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->end_date->Visible) { // end_date ?>
    <div id="r_end_date" class="form-group row">
        <label id="elh_employee_shift_end_date" for="x_end_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->end_date->caption() ?><?= $Page->end_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->end_date->cellAttributes() ?>>
<span id="el_employee_shift_end_date">
<input type="<?= $Page->end_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_end_date" data-format="5" name="x_end_date" id="x_end_date" placeholder="<?= HtmlEncode($Page->end_date->getPlaceHolder()) ?>" value="<?= $Page->end_date->EditValue ?>"<?= $Page->end_date->editAttributes() ?> aria-describedby="x_end_date_help">
<?= $Page->end_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->end_date->getErrorMessage() ?></div>
<?php if (!$Page->end_date->ReadOnly && !$Page->end_date->Disabled && !isset($Page->end_date->EditAttrs["readonly"]) && !isset($Page->end_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["femployee_shiftadd", "datetimepicker"], function() {
    ew.createDateTimePicker("femployee_shiftadd", "x_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("employee_shift");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
