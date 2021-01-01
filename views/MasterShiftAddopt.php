<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterShiftAddopt = &$Page;
?>
<script>
if (!ew.vars.tables.master_shift) ew.vars.tables.master_shift = <?= JsonEncode(GetClientVar("tables", "master_shift")) ?>;
var currentForm, currentPageID;
var fmaster_shiftaddopt;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "addopt";
    fmaster_shiftaddopt = currentForm = new ew.Form("fmaster_shiftaddopt", "addopt");

    // Add fields
    var fields = ew.vars.tables.master_shift.fields;
    fmaster_shiftaddopt.addFields([
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
        var f = fmaster_shiftaddopt,
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
    fmaster_shiftaddopt.validate = function () {
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
        return true;
    }

    // Form_CustomValidate
    fmaster_shiftaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_shiftaddopt.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_shiftaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fmaster_shiftaddopt" id="fmaster_shiftaddopt" class="ew-form ew-horizontal" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="master_shift">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->shift_name->Visible) { // shift_name ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_shift_name"><?= $Page->shift_name->caption() ?><?= $Page->shift_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->shift_name->getInputTextType() ?>" data-table="master_shift" data-field="x_shift_name" name="x_shift_name" id="x_shift_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->shift_name->getPlaceHolder()) ?>" value="<?= $Page->shift_name->EditValue ?>"<?= $Page->shift_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->shift_name->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->sunday_time_in->Visible) { // sunday_time_in ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_sunday_time_in"><?= $Page->sunday_time_in->caption() ?><?= $Page->sunday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->sunday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_in" name="x_sunday_time_in" id="x_sunday_time_in" placeholder="<?= HtmlEncode($Page->sunday_time_in->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_in->EditValue ?>"<?= $Page->sunday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sunday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->sunday_time_in->ReadOnly && !$Page->sunday_time_in->Disabled && !isset($Page->sunday_time_in->EditAttrs["readonly"]) && !isset($Page->sunday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_sunday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->sunday_time_out->Visible) { // sunday_time_out ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_sunday_time_out"><?= $Page->sunday_time_out->caption() ?><?= $Page->sunday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->sunday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_out" name="x_sunday_time_out" id="x_sunday_time_out" placeholder="<?= HtmlEncode($Page->sunday_time_out->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_out->EditValue ?>"<?= $Page->sunday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sunday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->sunday_time_out->ReadOnly && !$Page->sunday_time_out->Disabled && !isset($Page->sunday_time_out->EditAttrs["readonly"]) && !isset($Page->sunday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_sunday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->monday_time_in->Visible) { // monday_time_in ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_monday_time_in"><?= $Page->monday_time_in->caption() ?><?= $Page->monday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->monday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_in" name="x_monday_time_in" id="x_monday_time_in" placeholder="<?= HtmlEncode($Page->monday_time_in->getPlaceHolder()) ?>" value="<?= $Page->monday_time_in->EditValue ?>"<?= $Page->monday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->monday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->monday_time_in->ReadOnly && !$Page->monday_time_in->Disabled && !isset($Page->monday_time_in->EditAttrs["readonly"]) && !isset($Page->monday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_monday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->monday_time_out->Visible) { // monday_time_out ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_monday_time_out"><?= $Page->monday_time_out->caption() ?><?= $Page->monday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->monday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_out" name="x_monday_time_out" id="x_monday_time_out" placeholder="<?= HtmlEncode($Page->monday_time_out->getPlaceHolder()) ?>" value="<?= $Page->monday_time_out->EditValue ?>"<?= $Page->monday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->monday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->monday_time_out->ReadOnly && !$Page->monday_time_out->Disabled && !isset($Page->monday_time_out->EditAttrs["readonly"]) && !isset($Page->monday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_monday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->tuesday_time_in->Visible) { // tuesday_time_in ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_tuesday_time_in"><?= $Page->tuesday_time_in->caption() ?><?= $Page->tuesday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->tuesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_in" name="x_tuesday_time_in" id="x_tuesday_time_in" placeholder="<?= HtmlEncode($Page->tuesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_in->EditValue ?>"<?= $Page->tuesday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tuesday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->tuesday_time_in->ReadOnly && !$Page->tuesday_time_in->Disabled && !isset($Page->tuesday_time_in->EditAttrs["readonly"]) && !isset($Page->tuesday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_tuesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->tuesday_time_out->Visible) { // tuesday_time_out ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_tuesday_time_out"><?= $Page->tuesday_time_out->caption() ?><?= $Page->tuesday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->tuesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_out" name="x_tuesday_time_out" id="x_tuesday_time_out" placeholder="<?= HtmlEncode($Page->tuesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_out->EditValue ?>"<?= $Page->tuesday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tuesday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->tuesday_time_out->ReadOnly && !$Page->tuesday_time_out->Disabled && !isset($Page->tuesday_time_out->EditAttrs["readonly"]) && !isset($Page->tuesday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_tuesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->wednesday_time_in->Visible) { // wednesday_time_in ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_wednesday_time_in"><?= $Page->wednesday_time_in->caption() ?><?= $Page->wednesday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->wednesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_in" name="x_wednesday_time_in" id="x_wednesday_time_in" placeholder="<?= HtmlEncode($Page->wednesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_in->EditValue ?>"<?= $Page->wednesday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->wednesday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->wednesday_time_in->ReadOnly && !$Page->wednesday_time_in->Disabled && !isset($Page->wednesday_time_in->EditAttrs["readonly"]) && !isset($Page->wednesday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_wednesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->wednesday_time_out->Visible) { // wednesday_time_out ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_wednesday_time_out"><?= $Page->wednesday_time_out->caption() ?><?= $Page->wednesday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->wednesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_out" name="x_wednesday_time_out" id="x_wednesday_time_out" placeholder="<?= HtmlEncode($Page->wednesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_out->EditValue ?>"<?= $Page->wednesday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->wednesday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->wednesday_time_out->ReadOnly && !$Page->wednesday_time_out->Disabled && !isset($Page->wednesday_time_out->EditAttrs["readonly"]) && !isset($Page->wednesday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_wednesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->thursday_time_in->Visible) { // thursday_time_in ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_thursday_time_in"><?= $Page->thursday_time_in->caption() ?><?= $Page->thursday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->thursday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_in" name="x_thursday_time_in" id="x_thursday_time_in" placeholder="<?= HtmlEncode($Page->thursday_time_in->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_in->EditValue ?>"<?= $Page->thursday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->thursday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->thursday_time_in->ReadOnly && !$Page->thursday_time_in->Disabled && !isset($Page->thursday_time_in->EditAttrs["readonly"]) && !isset($Page->thursday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_thursday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->thursday_time_out->Visible) { // thursday_time_out ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_thursday_time_out"><?= $Page->thursday_time_out->caption() ?><?= $Page->thursday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->thursday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_out" name="x_thursday_time_out" id="x_thursday_time_out" placeholder="<?= HtmlEncode($Page->thursday_time_out->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_out->EditValue ?>"<?= $Page->thursday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->thursday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->thursday_time_out->ReadOnly && !$Page->thursday_time_out->Disabled && !isset($Page->thursday_time_out->EditAttrs["readonly"]) && !isset($Page->thursday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_thursday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->friday_time_in->Visible) { // friday_time_in ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_friday_time_in"><?= $Page->friday_time_in->caption() ?><?= $Page->friday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->friday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_in" name="x_friday_time_in" id="x_friday_time_in" placeholder="<?= HtmlEncode($Page->friday_time_in->getPlaceHolder()) ?>" value="<?= $Page->friday_time_in->EditValue ?>"<?= $Page->friday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->friday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->friday_time_in->ReadOnly && !$Page->friday_time_in->Disabled && !isset($Page->friday_time_in->EditAttrs["readonly"]) && !isset($Page->friday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_friday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->friday_time_out->Visible) { // friday_time_out ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_friday_time_out"><?= $Page->friday_time_out->caption() ?><?= $Page->friday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->friday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_out" name="x_friday_time_out" id="x_friday_time_out" placeholder="<?= HtmlEncode($Page->friday_time_out->getPlaceHolder()) ?>" value="<?= $Page->friday_time_out->EditValue ?>"<?= $Page->friday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->friday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->friday_time_out->ReadOnly && !$Page->friday_time_out->Disabled && !isset($Page->friday_time_out->EditAttrs["readonly"]) && !isset($Page->friday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_friday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->saturday_time_in->Visible) { // saturday_time_in ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_saturday_time_in"><?= $Page->saturday_time_in->caption() ?><?= $Page->saturday_time_in->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->saturday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_in" name="x_saturday_time_in" id="x_saturday_time_in" placeholder="<?= HtmlEncode($Page->saturday_time_in->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_in->EditValue ?>"<?= $Page->saturday_time_in->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->saturday_time_in->getErrorMessage() ?></div>
<?php if (!$Page->saturday_time_in->ReadOnly && !$Page->saturday_time_in->Disabled && !isset($Page->saturday_time_in->EditAttrs["readonly"]) && !isset($Page->saturday_time_in->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_saturday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
<?php } ?>
<?php if ($Page->saturday_time_out->Visible) { // saturday_time_out ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_saturday_time_out"><?= $Page->saturday_time_out->caption() ?><?= $Page->saturday_time_out->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->saturday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_out" name="x_saturday_time_out" id="x_saturday_time_out" placeholder="<?= HtmlEncode($Page->saturday_time_out->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_out->EditValue ?>"<?= $Page->saturday_time_out->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->saturday_time_out->getErrorMessage() ?></div>
<?php if (!$Page->saturday_time_out->ReadOnly && !$Page->saturday_time_out->Disabled && !isset($Page->saturday_time_out->EditAttrs["readonly"]) && !isset($Page->saturday_time_out->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_shiftaddopt", "timepicker"], function() {
    ew.createTimePicker("fmaster_shiftaddopt", "x_saturday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
});
</script>
<?php } ?>
</div>
    </div>
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
