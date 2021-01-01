<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterShiftAdd = &$Page;
?>
<script>
if (!ew.vars.tables.master_shift) ew.vars.tables.master_shift = <?= JsonEncode(GetClientVar("tables", "master_shift")) ?>;
var currentForm, currentPageID;
var fmaster_shiftadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fmaster_shiftadd = currentForm = new ew.Form("fmaster_shiftadd", "add");

    // Add fields
    var fields = ew.vars.tables.master_shift.fields;
    fmaster_shiftadd.addFields([
        ["shift_name", [fields.shift_name.required ? ew.Validators.required(fields.shift_name.caption) : null], fields.shift_name.isInvalid],
        ["sunday_time_in", [fields.sunday_time_in.required ? ew.Validators.required(fields.sunday_time_in.caption) : null, ew.Validators.time], fields.sunday_time_in.isInvalid],
        ["sunday_time_out", [fields.sunday_time_out.required ? ew.Validators.required(fields.sunday_time_out.caption) : null, ew.Validators.time], fields.sunday_time_out.isInvalid],
        ["monday_time_in", [fields.monday_time_in.required ? ew.Validators.required(fields.monday_time_in.caption) : null, ew.Validators.time], fields.monday_time_in.isInvalid],
        ["monday_time_out", [fields.monday_time_out.required ? ew.Validators.required(fields.monday_time_out.caption) : null, ew.Validators.time], fields.monday_time_out.isInvalid],
        ["tuesday_time_in", [fields.tuesday_time_in.required ? ew.Validators.required(fields.tuesday_time_in.caption) : null, ew.Validators.time], fields.tuesday_time_in.isInvalid],
        ["tuesday_time_out", [fields.tuesday_time_out.required ? ew.Validators.required(fields.tuesday_time_out.caption) : null, ew.Validators.time], fields.tuesday_time_out.isInvalid],
        ["wednesday_time_in", [fields.wednesday_time_in.required ? ew.Validators.required(fields.wednesday_time_in.caption) : null, ew.Validators.time], fields.wednesday_time_in.isInvalid],
        ["wednesday_time_out", [fields.wednesday_time_out.required ? ew.Validators.required(fields.wednesday_time_out.caption) : null, ew.Validators.time], fields.wednesday_time_out.isInvalid],
        ["thursday_time_in", [fields.thursday_time_in.required ? ew.Validators.required(fields.thursday_time_in.caption) : null, ew.Validators.time], fields.thursday_time_in.isInvalid],
        ["thursday_time_out", [fields.thursday_time_out.required ? ew.Validators.required(fields.thursday_time_out.caption) : null, ew.Validators.time], fields.thursday_time_out.isInvalid],
        ["friday_time_in", [fields.friday_time_in.required ? ew.Validators.required(fields.friday_time_in.caption) : null, ew.Validators.time], fields.friday_time_in.isInvalid],
        ["friday_time_out", [fields.friday_time_out.required ? ew.Validators.required(fields.friday_time_out.caption) : null, ew.Validators.time], fields.friday_time_out.isInvalid],
        ["saturday_time_in", [fields.saturday_time_in.required ? ew.Validators.required(fields.saturday_time_in.caption) : null, ew.Validators.time], fields.saturday_time_in.isInvalid],
        ["saturday_time_out", [fields.saturday_time_out.required ? ew.Validators.required(fields.saturday_time_out.caption) : null, ew.Validators.time], fields.saturday_time_out.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_shiftadd,
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
    fmaster_shiftadd.validate = function () {
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
    fmaster_shiftadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_shiftadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_shiftadd");
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
<form name="fmaster_shiftadd" id="fmaster_shiftadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_shift">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->shift_name->Visible) { // shift_name ?>
    <div id="r_shift_name" class="form-group row">
        <label id="elh_master_shift_shift_name" for="x_shift_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->shift_name->caption() ?><?= $Page->shift_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->shift_name->cellAttributes() ?>>
<span id="el_master_shift_shift_name">
<input type="<?= $Page->shift_name->getInputTextType() ?>" data-table="master_shift" data-field="x_shift_name" name="x_shift_name" id="x_shift_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->shift_name->getPlaceHolder()) ?>" value="<?= $Page->shift_name->EditValue ?>"<?= $Page->shift_name->editAttributes() ?> aria-describedby="x_shift_name_help">
<?= $Page->shift_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->shift_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sunday_time_in->Visible) { // sunday_time_in ?>
    <div id="r_sunday_time_in" class="form-group row">
        <label id="elh_master_shift_sunday_time_in" for="x_sunday_time_in" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sunday_time_in->caption() ?><?= $Page->sunday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sunday_time_in->cellAttributes() ?>>
<span id="el_master_shift_sunday_time_in">
<input type="<?= $Page->sunday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_in" name="x_sunday_time_in" id="x_sunday_time_in" placeholder="<?= HtmlEncode($Page->sunday_time_in->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_in->EditValue ?>"<?= $Page->sunday_time_in->editAttributes() ?> aria-describedby="x_sunday_time_in_help">
<?= $Page->sunday_time_in->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sunday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->sunday_time_in->ReadOnly && !$Page->sunday_time_in->Disabled && !isset($Page->sunday_time_in->EditAttrs["readonly"]) && !isset($Page->sunday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_sunday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sunday_time_out->Visible) { // sunday_time_out ?>
    <div id="r_sunday_time_out" class="form-group row">
        <label id="elh_master_shift_sunday_time_out" for="x_sunday_time_out" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sunday_time_out->caption() ?><?= $Page->sunday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sunday_time_out->cellAttributes() ?>>
<span id="el_master_shift_sunday_time_out">
<input type="<?= $Page->sunday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_out" name="x_sunday_time_out" id="x_sunday_time_out" placeholder="<?= HtmlEncode($Page->sunday_time_out->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_out->EditValue ?>"<?= $Page->sunday_time_out->editAttributes() ?> aria-describedby="x_sunday_time_out_help">
<?= $Page->sunday_time_out->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sunday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->sunday_time_out->ReadOnly && !$Page->sunday_time_out->Disabled && !isset($Page->sunday_time_out->EditAttrs["readonly"]) && !isset($Page->sunday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_sunday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monday_time_in->Visible) { // monday_time_in ?>
    <div id="r_monday_time_in" class="form-group row">
        <label id="elh_master_shift_monday_time_in" for="x_monday_time_in" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monday_time_in->caption() ?><?= $Page->monday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monday_time_in->cellAttributes() ?>>
<span id="el_master_shift_monday_time_in">
<input type="<?= $Page->monday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_in" name="x_monday_time_in" id="x_monday_time_in" placeholder="<?= HtmlEncode($Page->monday_time_in->getPlaceHolder()) ?>" value="<?= $Page->monday_time_in->EditValue ?>"<?= $Page->monday_time_in->editAttributes() ?> aria-describedby="x_monday_time_in_help">
<?= $Page->monday_time_in->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->monday_time_in->ReadOnly && !$Page->monday_time_in->Disabled && !isset($Page->monday_time_in->EditAttrs["readonly"]) && !isset($Page->monday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_monday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monday_time_out->Visible) { // monday_time_out ?>
    <div id="r_monday_time_out" class="form-group row">
        <label id="elh_master_shift_monday_time_out" for="x_monday_time_out" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monday_time_out->caption() ?><?= $Page->monday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->monday_time_out->cellAttributes() ?>>
<span id="el_master_shift_monday_time_out">
<input type="<?= $Page->monday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_out" name="x_monday_time_out" id="x_monday_time_out" placeholder="<?= HtmlEncode($Page->monday_time_out->getPlaceHolder()) ?>" value="<?= $Page->monday_time_out->EditValue ?>"<?= $Page->monday_time_out->editAttributes() ?> aria-describedby="x_monday_time_out_help">
<?= $Page->monday_time_out->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->monday_time_out->ReadOnly && !$Page->monday_time_out->Disabled && !isset($Page->monday_time_out->EditAttrs["readonly"]) && !isset($Page->monday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_monday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tuesday_time_in->Visible) { // tuesday_time_in ?>
    <div id="r_tuesday_time_in" class="form-group row">
        <label id="elh_master_shift_tuesday_time_in" for="x_tuesday_time_in" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tuesday_time_in->caption() ?><?= $Page->tuesday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tuesday_time_in->cellAttributes() ?>>
<span id="el_master_shift_tuesday_time_in">
<input type="<?= $Page->tuesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_in" name="x_tuesday_time_in" id="x_tuesday_time_in" placeholder="<?= HtmlEncode($Page->tuesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_in->EditValue ?>"<?= $Page->tuesday_time_in->editAttributes() ?> aria-describedby="x_tuesday_time_in_help">
<?= $Page->tuesday_time_in->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tuesday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->tuesday_time_in->ReadOnly && !$Page->tuesday_time_in->Disabled && !isset($Page->tuesday_time_in->EditAttrs["readonly"]) && !isset($Page->tuesday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_tuesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tuesday_time_out->Visible) { // tuesday_time_out ?>
    <div id="r_tuesday_time_out" class="form-group row">
        <label id="elh_master_shift_tuesday_time_out" for="x_tuesday_time_out" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tuesday_time_out->caption() ?><?= $Page->tuesday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tuesday_time_out->cellAttributes() ?>>
<span id="el_master_shift_tuesday_time_out">
<input type="<?= $Page->tuesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_out" name="x_tuesday_time_out" id="x_tuesday_time_out" placeholder="<?= HtmlEncode($Page->tuesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_out->EditValue ?>"<?= $Page->tuesday_time_out->editAttributes() ?> aria-describedby="x_tuesday_time_out_help">
<?= $Page->tuesday_time_out->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tuesday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->tuesday_time_out->ReadOnly && !$Page->tuesday_time_out->Disabled && !isset($Page->tuesday_time_out->EditAttrs["readonly"]) && !isset($Page->tuesday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_tuesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->wednesday_time_in->Visible) { // wednesday_time_in ?>
    <div id="r_wednesday_time_in" class="form-group row">
        <label id="elh_master_shift_wednesday_time_in" for="x_wednesday_time_in" class="<?= $Page->LeftColumnClass ?>"><?= $Page->wednesday_time_in->caption() ?><?= $Page->wednesday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->wednesday_time_in->cellAttributes() ?>>
<span id="el_master_shift_wednesday_time_in">
<input type="<?= $Page->wednesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_in" name="x_wednesday_time_in" id="x_wednesday_time_in" placeholder="<?= HtmlEncode($Page->wednesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_in->EditValue ?>"<?= $Page->wednesday_time_in->editAttributes() ?> aria-describedby="x_wednesday_time_in_help">
<?= $Page->wednesday_time_in->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->wednesday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->wednesday_time_in->ReadOnly && !$Page->wednesday_time_in->Disabled && !isset($Page->wednesday_time_in->EditAttrs["readonly"]) && !isset($Page->wednesday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_wednesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->wednesday_time_out->Visible) { // wednesday_time_out ?>
    <div id="r_wednesday_time_out" class="form-group row">
        <label id="elh_master_shift_wednesday_time_out" for="x_wednesday_time_out" class="<?= $Page->LeftColumnClass ?>"><?= $Page->wednesday_time_out->caption() ?><?= $Page->wednesday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->wednesday_time_out->cellAttributes() ?>>
<span id="el_master_shift_wednesday_time_out">
<input type="<?= $Page->wednesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_out" name="x_wednesday_time_out" id="x_wednesday_time_out" placeholder="<?= HtmlEncode($Page->wednesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_out->EditValue ?>"<?= $Page->wednesday_time_out->editAttributes() ?> aria-describedby="x_wednesday_time_out_help">
<?= $Page->wednesday_time_out->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->wednesday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->wednesday_time_out->ReadOnly && !$Page->wednesday_time_out->Disabled && !isset($Page->wednesday_time_out->EditAttrs["readonly"]) && !isset($Page->wednesday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_wednesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->thursday_time_in->Visible) { // thursday_time_in ?>
    <div id="r_thursday_time_in" class="form-group row">
        <label id="elh_master_shift_thursday_time_in" for="x_thursday_time_in" class="<?= $Page->LeftColumnClass ?>"><?= $Page->thursday_time_in->caption() ?><?= $Page->thursday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->thursday_time_in->cellAttributes() ?>>
<span id="el_master_shift_thursday_time_in">
<input type="<?= $Page->thursday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_in" name="x_thursday_time_in" id="x_thursday_time_in" placeholder="<?= HtmlEncode($Page->thursday_time_in->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_in->EditValue ?>"<?= $Page->thursday_time_in->editAttributes() ?> aria-describedby="x_thursday_time_in_help">
<?= $Page->thursday_time_in->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->thursday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->thursday_time_in->ReadOnly && !$Page->thursday_time_in->Disabled && !isset($Page->thursday_time_in->EditAttrs["readonly"]) && !isset($Page->thursday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_thursday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->thursday_time_out->Visible) { // thursday_time_out ?>
    <div id="r_thursday_time_out" class="form-group row">
        <label id="elh_master_shift_thursday_time_out" for="x_thursday_time_out" class="<?= $Page->LeftColumnClass ?>"><?= $Page->thursday_time_out->caption() ?><?= $Page->thursday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->thursday_time_out->cellAttributes() ?>>
<span id="el_master_shift_thursday_time_out">
<input type="<?= $Page->thursday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_out" name="x_thursday_time_out" id="x_thursday_time_out" placeholder="<?= HtmlEncode($Page->thursday_time_out->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_out->EditValue ?>"<?= $Page->thursday_time_out->editAttributes() ?> aria-describedby="x_thursday_time_out_help">
<?= $Page->thursday_time_out->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->thursday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->thursday_time_out->ReadOnly && !$Page->thursday_time_out->Disabled && !isset($Page->thursday_time_out->EditAttrs["readonly"]) && !isset($Page->thursday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_thursday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->friday_time_in->Visible) { // friday_time_in ?>
    <div id="r_friday_time_in" class="form-group row">
        <label id="elh_master_shift_friday_time_in" for="x_friday_time_in" class="<?= $Page->LeftColumnClass ?>"><?= $Page->friday_time_in->caption() ?><?= $Page->friday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->friday_time_in->cellAttributes() ?>>
<span id="el_master_shift_friday_time_in">
<input type="<?= $Page->friday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_in" name="x_friday_time_in" id="x_friday_time_in" placeholder="<?= HtmlEncode($Page->friday_time_in->getPlaceHolder()) ?>" value="<?= $Page->friday_time_in->EditValue ?>"<?= $Page->friday_time_in->editAttributes() ?> aria-describedby="x_friday_time_in_help">
<?= $Page->friday_time_in->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->friday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->friday_time_in->ReadOnly && !$Page->friday_time_in->Disabled && !isset($Page->friday_time_in->EditAttrs["readonly"]) && !isset($Page->friday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_friday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->friday_time_out->Visible) { // friday_time_out ?>
    <div id="r_friday_time_out" class="form-group row">
        <label id="elh_master_shift_friday_time_out" for="x_friday_time_out" class="<?= $Page->LeftColumnClass ?>"><?= $Page->friday_time_out->caption() ?><?= $Page->friday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->friday_time_out->cellAttributes() ?>>
<span id="el_master_shift_friday_time_out">
<input type="<?= $Page->friday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_out" name="x_friday_time_out" id="x_friday_time_out" placeholder="<?= HtmlEncode($Page->friday_time_out->getPlaceHolder()) ?>" value="<?= $Page->friday_time_out->EditValue ?>"<?= $Page->friday_time_out->editAttributes() ?> aria-describedby="x_friday_time_out_help">
<?= $Page->friday_time_out->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->friday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->friday_time_out->ReadOnly && !$Page->friday_time_out->Disabled && !isset($Page->friday_time_out->EditAttrs["readonly"]) && !isset($Page->friday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_friday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->saturday_time_in->Visible) { // saturday_time_in ?>
    <div id="r_saturday_time_in" class="form-group row">
        <label id="elh_master_shift_saturday_time_in" for="x_saturday_time_in" class="<?= $Page->LeftColumnClass ?>"><?= $Page->saturday_time_in->caption() ?><?= $Page->saturday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->saturday_time_in->cellAttributes() ?>>
<span id="el_master_shift_saturday_time_in">
<input type="<?= $Page->saturday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_in" name="x_saturday_time_in" id="x_saturday_time_in" placeholder="<?= HtmlEncode($Page->saturday_time_in->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_in->EditValue ?>"<?= $Page->saturday_time_in->editAttributes() ?> aria-describedby="x_saturday_time_in_help">
<?= $Page->saturday_time_in->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->saturday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->saturday_time_in->ReadOnly && !$Page->saturday_time_in->Disabled && !isset($Page->saturday_time_in->EditAttrs["readonly"]) && !isset($Page->saturday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_saturday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->saturday_time_out->Visible) { // saturday_time_out ?>
    <div id="r_saturday_time_out" class="form-group row">
        <label id="elh_master_shift_saturday_time_out" for="x_saturday_time_out" class="<?= $Page->LeftColumnClass ?>"><?= $Page->saturday_time_out->caption() ?><?= $Page->saturday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->saturday_time_out->cellAttributes() ?>>
<span id="el_master_shift_saturday_time_out">
<input type="<?= $Page->saturday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_out" name="x_saturday_time_out" id="x_saturday_time_out" placeholder="<?= HtmlEncode($Page->saturday_time_out->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_out->EditValue ?>"<?= $Page->saturday_time_out->editAttributes() ?> aria-describedby="x_saturday_time_out_help">
<?= $Page->saturday_time_out->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->saturday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->saturday_time_out->ReadOnly && !$Page->saturday_time_out->Disabled && !isset($Page->saturday_time_out->EditAttrs["readonly"]) && !isset($Page->saturday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftadd", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftadd", "x_saturday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<?php
    $Page->DetailPages->ValidKeys = explode(",", $Page->getCurrentDetailTable());
    $firstActiveDetailTable = $Page->DetailPages->activePageIndex();
?>
<div class="ew-detail-pages"><!-- detail-pages -->
<div class="ew-nav-tabs" id="Page_details"><!-- tabs -->
    <ul class="<?= $Page->DetailPages->navStyle() ?>"><!-- .nav -->
<?php
    if (in_array("master_holiday", explode(",", $Page->getCurrentDetailTable())) && $master_holiday->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "master_holiday") {
            $firstActiveDetailTable = "master_holiday";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("master_holiday") ?>" href="#tab_master_holiday" data-toggle="tab"><?= $Language->tablePhrase("master_holiday", "TblCaption") ?></a></li>
<?php
    }
?>
<?php
    if (in_array("employee_shift", explode(",", $Page->getCurrentDetailTable())) && $employee_shift->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_shift") {
            $firstActiveDetailTable = "employee_shift";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("employee_shift") ?>" href="#tab_employee_shift" data-toggle="tab"><?= $Language->tablePhrase("employee_shift", "TblCaption") ?></a></li>
<?php
    }
?>
    </ul><!-- /.nav -->
    <div class="tab-content"><!-- .tab-content -->
<?php
    if (in_array("master_holiday", explode(",", $Page->getCurrentDetailTable())) && $master_holiday->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "master_holiday") {
            $firstActiveDetailTable = "master_holiday";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("master_holiday") ?>" id="tab_master_holiday"><!-- page* -->
<?php include_once "MasterHolidayGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("employee_shift", explode(",", $Page->getCurrentDetailTable())) && $employee_shift->DetailAdd) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee_shift") {
            $firstActiveDetailTable = "employee_shift";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("employee_shift") ?>" id="tab_employee_shift"><!-- page* -->
<?php include_once "EmployeeShiftGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
    </div><!-- /.tab-content -->
</div><!-- /tabs -->
</div><!-- /detail-pages -->
<?php } ?>
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
    ew.addEventHandlers("master_shift");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
