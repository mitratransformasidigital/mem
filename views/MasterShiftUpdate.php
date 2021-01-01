<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterShiftUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.master_shift) ew.vars.tables.master_shift = <?= JsonEncode(GetClientVar("tables", "master_shift")) ?>;
var currentForm, currentPageID;
var fmaster_shiftupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fmaster_shiftupdate = currentForm = new ew.Form("fmaster_shiftupdate", "update");

    // Add fields
    var fields = ew.vars.tables.master_shift.fields;
    fmaster_shiftupdate.addFields([
        ["shift_name", [fields.shift_name.required ? ew.Validators.required(fields.shift_name.caption) : null], fields.shift_name.isInvalid],
        ["sunday_time_in", [fields.sunday_time_in.required ? ew.Validators.required(fields.sunday_time_in.caption) : null, ew.Validators.time, ew.Validators.selected], fields.sunday_time_in.isInvalid],
        ["sunday_time_out", [fields.sunday_time_out.required ? ew.Validators.required(fields.sunday_time_out.caption) : null, ew.Validators.time, ew.Validators.selected], fields.sunday_time_out.isInvalid],
        ["monday_time_in", [fields.monday_time_in.required ? ew.Validators.required(fields.monday_time_in.caption) : null, ew.Validators.time, ew.Validators.selected], fields.monday_time_in.isInvalid],
        ["monday_time_out", [fields.monday_time_out.required ? ew.Validators.required(fields.monday_time_out.caption) : null, ew.Validators.time, ew.Validators.selected], fields.monday_time_out.isInvalid],
        ["tuesday_time_in", [fields.tuesday_time_in.required ? ew.Validators.required(fields.tuesday_time_in.caption) : null, ew.Validators.time, ew.Validators.selected], fields.tuesday_time_in.isInvalid],
        ["tuesday_time_out", [fields.tuesday_time_out.required ? ew.Validators.required(fields.tuesday_time_out.caption) : null, ew.Validators.time, ew.Validators.selected], fields.tuesday_time_out.isInvalid],
        ["wednesday_time_in", [fields.wednesday_time_in.required ? ew.Validators.required(fields.wednesday_time_in.caption) : null, ew.Validators.time, ew.Validators.selected], fields.wednesday_time_in.isInvalid],
        ["wednesday_time_out", [fields.wednesday_time_out.required ? ew.Validators.required(fields.wednesday_time_out.caption) : null, ew.Validators.time, ew.Validators.selected], fields.wednesday_time_out.isInvalid],
        ["thursday_time_in", [fields.thursday_time_in.required ? ew.Validators.required(fields.thursday_time_in.caption) : null, ew.Validators.time, ew.Validators.selected], fields.thursday_time_in.isInvalid],
        ["thursday_time_out", [fields.thursday_time_out.required ? ew.Validators.required(fields.thursday_time_out.caption) : null, ew.Validators.time, ew.Validators.selected], fields.thursday_time_out.isInvalid],
        ["friday_time_in", [fields.friday_time_in.required ? ew.Validators.required(fields.friday_time_in.caption) : null, ew.Validators.time, ew.Validators.selected], fields.friday_time_in.isInvalid],
        ["friday_time_out", [fields.friday_time_out.required ? ew.Validators.required(fields.friday_time_out.caption) : null, ew.Validators.time, ew.Validators.selected], fields.friday_time_out.isInvalid],
        ["saturday_time_in", [fields.saturday_time_in.required ? ew.Validators.required(fields.saturday_time_in.caption) : null, ew.Validators.time, ew.Validators.selected], fields.saturday_time_in.isInvalid],
        ["saturday_time_out", [fields.saturday_time_out.required ? ew.Validators.required(fields.saturday_time_out.caption) : null, ew.Validators.time, ew.Validators.selected], fields.saturday_time_out.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_shiftupdate,
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
    fmaster_shiftupdate.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        if (!ew.updateSelected(fobj)) {
            ew.alert(ew.language.phrase("NoFieldSelected"));
            return false;
        }
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
    fmaster_shiftupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_shiftupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_shiftupdate");
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
<form name="fmaster_shiftupdate" id="fmaster_shiftupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_shift">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_master_shiftupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->shift_name->Visible && (!$Page->isConfirm() || $Page->shift_name->multiUpdateSelected())) { // shift_name ?>
    <div id="r_shift_name" class="form-group row">
        <label for="x_shift_name" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_shift_name" id="u_shift_name" class="custom-control-input ew-multi-select" value="1"<?= $Page->shift_name->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_shift_name"><?= $Page->shift_name->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->shift_name->cellAttributes() ?>>
                <span id="el_master_shift_shift_name">
                <input type="<?= $Page->shift_name->getInputTextType() ?>" data-table="master_shift" data-field="x_shift_name" name="x_shift_name" id="x_shift_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->shift_name->getPlaceHolder()) ?>" value="<?= $Page->shift_name->EditValue ?>"<?= $Page->shift_name->editAttributes() ?> aria-describedby="x_shift_name_help">
                <?= $Page->shift_name->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->shift_name->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->sunday_time_in->Visible && (!$Page->isConfirm() || $Page->sunday_time_in->multiUpdateSelected())) { // sunday_time_in ?>
    <div id="r_sunday_time_in" class="form-group row">
        <label for="x_sunday_time_in" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_sunday_time_in" id="u_sunday_time_in" class="custom-control-input ew-multi-select" value="1"<?= $Page->sunday_time_in->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_sunday_time_in"><?= $Page->sunday_time_in->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->sunday_time_in->cellAttributes() ?>>
                <span id="el_master_shift_sunday_time_in">
                <input type="<?= $Page->sunday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_in" name="x_sunday_time_in" id="x_sunday_time_in" placeholder="<?= HtmlEncode($Page->sunday_time_in->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_in->EditValue ?>"<?= $Page->sunday_time_in->editAttributes() ?> aria-describedby="x_sunday_time_in_help">
                <?= $Page->sunday_time_in->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->sunday_time_in->getErrorMessage() ?></div>
                <?php if (!$Page->sunday_time_in->ReadOnly && !$Page->sunday_time_in->Disabled && !isset($Page->sunday_time_in->EditAttrs["readonly"]) && !isset($Page->sunday_time_in->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_sunday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->sunday_time_out->Visible && (!$Page->isConfirm() || $Page->sunday_time_out->multiUpdateSelected())) { // sunday_time_out ?>
    <div id="r_sunday_time_out" class="form-group row">
        <label for="x_sunday_time_out" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_sunday_time_out" id="u_sunday_time_out" class="custom-control-input ew-multi-select" value="1"<?= $Page->sunday_time_out->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_sunday_time_out"><?= $Page->sunday_time_out->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->sunday_time_out->cellAttributes() ?>>
                <span id="el_master_shift_sunday_time_out">
                <input type="<?= $Page->sunday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_sunday_time_out" name="x_sunday_time_out" id="x_sunday_time_out" placeholder="<?= HtmlEncode($Page->sunday_time_out->getPlaceHolder()) ?>" value="<?= $Page->sunday_time_out->EditValue ?>"<?= $Page->sunday_time_out->editAttributes() ?> aria-describedby="x_sunday_time_out_help">
                <?= $Page->sunday_time_out->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->sunday_time_out->getErrorMessage() ?></div>
                <?php if (!$Page->sunday_time_out->ReadOnly && !$Page->sunday_time_out->Disabled && !isset($Page->sunday_time_out->EditAttrs["readonly"]) && !isset($Page->sunday_time_out->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_sunday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->monday_time_in->Visible && (!$Page->isConfirm() || $Page->monday_time_in->multiUpdateSelected())) { // monday_time_in ?>
    <div id="r_monday_time_in" class="form-group row">
        <label for="x_monday_time_in" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_monday_time_in" id="u_monday_time_in" class="custom-control-input ew-multi-select" value="1"<?= $Page->monday_time_in->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_monday_time_in"><?= $Page->monday_time_in->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->monday_time_in->cellAttributes() ?>>
                <span id="el_master_shift_monday_time_in">
                <input type="<?= $Page->monday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_in" name="x_monday_time_in" id="x_monday_time_in" placeholder="<?= HtmlEncode($Page->monday_time_in->getPlaceHolder()) ?>" value="<?= $Page->monday_time_in->EditValue ?>"<?= $Page->monday_time_in->editAttributes() ?> aria-describedby="x_monday_time_in_help">
                <?= $Page->monday_time_in->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->monday_time_in->getErrorMessage() ?></div>
                <?php if (!$Page->monday_time_in->ReadOnly && !$Page->monday_time_in->Disabled && !isset($Page->monday_time_in->EditAttrs["readonly"]) && !isset($Page->monday_time_in->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_monday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->monday_time_out->Visible && (!$Page->isConfirm() || $Page->monday_time_out->multiUpdateSelected())) { // monday_time_out ?>
    <div id="r_monday_time_out" class="form-group row">
        <label for="x_monday_time_out" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_monday_time_out" id="u_monday_time_out" class="custom-control-input ew-multi-select" value="1"<?= $Page->monday_time_out->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_monday_time_out"><?= $Page->monday_time_out->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->monday_time_out->cellAttributes() ?>>
                <span id="el_master_shift_monday_time_out">
                <input type="<?= $Page->monday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_monday_time_out" name="x_monday_time_out" id="x_monday_time_out" placeholder="<?= HtmlEncode($Page->monday_time_out->getPlaceHolder()) ?>" value="<?= $Page->monday_time_out->EditValue ?>"<?= $Page->monday_time_out->editAttributes() ?> aria-describedby="x_monday_time_out_help">
                <?= $Page->monday_time_out->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->monday_time_out->getErrorMessage() ?></div>
                <?php if (!$Page->monday_time_out->ReadOnly && !$Page->monday_time_out->Disabled && !isset($Page->monday_time_out->EditAttrs["readonly"]) && !isset($Page->monday_time_out->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_monday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->tuesday_time_in->Visible && (!$Page->isConfirm() || $Page->tuesday_time_in->multiUpdateSelected())) { // tuesday_time_in ?>
    <div id="r_tuesday_time_in" class="form-group row">
        <label for="x_tuesday_time_in" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_tuesday_time_in" id="u_tuesday_time_in" class="custom-control-input ew-multi-select" value="1"<?= $Page->tuesday_time_in->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_tuesday_time_in"><?= $Page->tuesday_time_in->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->tuesday_time_in->cellAttributes() ?>>
                <span id="el_master_shift_tuesday_time_in">
                <input type="<?= $Page->tuesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_in" name="x_tuesday_time_in" id="x_tuesday_time_in" placeholder="<?= HtmlEncode($Page->tuesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_in->EditValue ?>"<?= $Page->tuesday_time_in->editAttributes() ?> aria-describedby="x_tuesday_time_in_help">
                <?= $Page->tuesday_time_in->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->tuesday_time_in->getErrorMessage() ?></div>
                <?php if (!$Page->tuesday_time_in->ReadOnly && !$Page->tuesday_time_in->Disabled && !isset($Page->tuesday_time_in->EditAttrs["readonly"]) && !isset($Page->tuesday_time_in->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_tuesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->tuesday_time_out->Visible && (!$Page->isConfirm() || $Page->tuesday_time_out->multiUpdateSelected())) { // tuesday_time_out ?>
    <div id="r_tuesday_time_out" class="form-group row">
        <label for="x_tuesday_time_out" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_tuesday_time_out" id="u_tuesday_time_out" class="custom-control-input ew-multi-select" value="1"<?= $Page->tuesday_time_out->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_tuesday_time_out"><?= $Page->tuesday_time_out->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->tuesday_time_out->cellAttributes() ?>>
                <span id="el_master_shift_tuesday_time_out">
                <input type="<?= $Page->tuesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_tuesday_time_out" name="x_tuesday_time_out" id="x_tuesday_time_out" placeholder="<?= HtmlEncode($Page->tuesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->tuesday_time_out->EditValue ?>"<?= $Page->tuesday_time_out->editAttributes() ?> aria-describedby="x_tuesday_time_out_help">
                <?= $Page->tuesday_time_out->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->tuesday_time_out->getErrorMessage() ?></div>
                <?php if (!$Page->tuesday_time_out->ReadOnly && !$Page->tuesday_time_out->Disabled && !isset($Page->tuesday_time_out->EditAttrs["readonly"]) && !isset($Page->tuesday_time_out->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_tuesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->wednesday_time_in->Visible && (!$Page->isConfirm() || $Page->wednesday_time_in->multiUpdateSelected())) { // wednesday_time_in ?>
    <div id="r_wednesday_time_in" class="form-group row">
        <label for="x_wednesday_time_in" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_wednesday_time_in" id="u_wednesday_time_in" class="custom-control-input ew-multi-select" value="1"<?= $Page->wednesday_time_in->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_wednesday_time_in"><?= $Page->wednesday_time_in->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->wednesday_time_in->cellAttributes() ?>>
                <span id="el_master_shift_wednesday_time_in">
                <input type="<?= $Page->wednesday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_in" name="x_wednesday_time_in" id="x_wednesday_time_in" placeholder="<?= HtmlEncode($Page->wednesday_time_in->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_in->EditValue ?>"<?= $Page->wednesday_time_in->editAttributes() ?> aria-describedby="x_wednesday_time_in_help">
                <?= $Page->wednesday_time_in->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->wednesday_time_in->getErrorMessage() ?></div>
                <?php if (!$Page->wednesday_time_in->ReadOnly && !$Page->wednesday_time_in->Disabled && !isset($Page->wednesday_time_in->EditAttrs["readonly"]) && !isset($Page->wednesday_time_in->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_wednesday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->wednesday_time_out->Visible && (!$Page->isConfirm() || $Page->wednesday_time_out->multiUpdateSelected())) { // wednesday_time_out ?>
    <div id="r_wednesday_time_out" class="form-group row">
        <label for="x_wednesday_time_out" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_wednesday_time_out" id="u_wednesday_time_out" class="custom-control-input ew-multi-select" value="1"<?= $Page->wednesday_time_out->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_wednesday_time_out"><?= $Page->wednesday_time_out->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->wednesday_time_out->cellAttributes() ?>>
                <span id="el_master_shift_wednesday_time_out">
                <input type="<?= $Page->wednesday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_wednesday_time_out" name="x_wednesday_time_out" id="x_wednesday_time_out" placeholder="<?= HtmlEncode($Page->wednesday_time_out->getPlaceHolder()) ?>" value="<?= $Page->wednesday_time_out->EditValue ?>"<?= $Page->wednesday_time_out->editAttributes() ?> aria-describedby="x_wednesday_time_out_help">
                <?= $Page->wednesday_time_out->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->wednesday_time_out->getErrorMessage() ?></div>
                <?php if (!$Page->wednesday_time_out->ReadOnly && !$Page->wednesday_time_out->Disabled && !isset($Page->wednesday_time_out->EditAttrs["readonly"]) && !isset($Page->wednesday_time_out->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_wednesday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->thursday_time_in->Visible && (!$Page->isConfirm() || $Page->thursday_time_in->multiUpdateSelected())) { // thursday_time_in ?>
    <div id="r_thursday_time_in" class="form-group row">
        <label for="x_thursday_time_in" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_thursday_time_in" id="u_thursday_time_in" class="custom-control-input ew-multi-select" value="1"<?= $Page->thursday_time_in->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_thursday_time_in"><?= $Page->thursday_time_in->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->thursday_time_in->cellAttributes() ?>>
                <span id="el_master_shift_thursday_time_in">
                <input type="<?= $Page->thursday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_in" name="x_thursday_time_in" id="x_thursday_time_in" placeholder="<?= HtmlEncode($Page->thursday_time_in->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_in->EditValue ?>"<?= $Page->thursday_time_in->editAttributes() ?> aria-describedby="x_thursday_time_in_help">
                <?= $Page->thursday_time_in->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->thursday_time_in->getErrorMessage() ?></div>
                <?php if (!$Page->thursday_time_in->ReadOnly && !$Page->thursday_time_in->Disabled && !isset($Page->thursday_time_in->EditAttrs["readonly"]) && !isset($Page->thursday_time_in->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_thursday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->thursday_time_out->Visible && (!$Page->isConfirm() || $Page->thursday_time_out->multiUpdateSelected())) { // thursday_time_out ?>
    <div id="r_thursday_time_out" class="form-group row">
        <label for="x_thursday_time_out" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_thursday_time_out" id="u_thursday_time_out" class="custom-control-input ew-multi-select" value="1"<?= $Page->thursday_time_out->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_thursday_time_out"><?= $Page->thursday_time_out->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->thursday_time_out->cellAttributes() ?>>
                <span id="el_master_shift_thursday_time_out">
                <input type="<?= $Page->thursday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_thursday_time_out" name="x_thursday_time_out" id="x_thursday_time_out" placeholder="<?= HtmlEncode($Page->thursday_time_out->getPlaceHolder()) ?>" value="<?= $Page->thursday_time_out->EditValue ?>"<?= $Page->thursday_time_out->editAttributes() ?> aria-describedby="x_thursday_time_out_help">
                <?= $Page->thursday_time_out->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->thursday_time_out->getErrorMessage() ?></div>
                <?php if (!$Page->thursday_time_out->ReadOnly && !$Page->thursday_time_out->Disabled && !isset($Page->thursday_time_out->EditAttrs["readonly"]) && !isset($Page->thursday_time_out->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_thursday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->friday_time_in->Visible && (!$Page->isConfirm() || $Page->friday_time_in->multiUpdateSelected())) { // friday_time_in ?>
    <div id="r_friday_time_in" class="form-group row">
        <label for="x_friday_time_in" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_friday_time_in" id="u_friday_time_in" class="custom-control-input ew-multi-select" value="1"<?= $Page->friday_time_in->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_friday_time_in"><?= $Page->friday_time_in->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->friday_time_in->cellAttributes() ?>>
                <span id="el_master_shift_friday_time_in">
                <input type="<?= $Page->friday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_in" name="x_friday_time_in" id="x_friday_time_in" placeholder="<?= HtmlEncode($Page->friday_time_in->getPlaceHolder()) ?>" value="<?= $Page->friday_time_in->EditValue ?>"<?= $Page->friday_time_in->editAttributes() ?> aria-describedby="x_friday_time_in_help">
                <?= $Page->friday_time_in->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->friday_time_in->getErrorMessage() ?></div>
                <?php if (!$Page->friday_time_in->ReadOnly && !$Page->friday_time_in->Disabled && !isset($Page->friday_time_in->EditAttrs["readonly"]) && !isset($Page->friday_time_in->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_friday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->friday_time_out->Visible && (!$Page->isConfirm() || $Page->friday_time_out->multiUpdateSelected())) { // friday_time_out ?>
    <div id="r_friday_time_out" class="form-group row">
        <label for="x_friday_time_out" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_friday_time_out" id="u_friday_time_out" class="custom-control-input ew-multi-select" value="1"<?= $Page->friday_time_out->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_friday_time_out"><?= $Page->friday_time_out->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->friday_time_out->cellAttributes() ?>>
                <span id="el_master_shift_friday_time_out">
                <input type="<?= $Page->friday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_friday_time_out" name="x_friday_time_out" id="x_friday_time_out" placeholder="<?= HtmlEncode($Page->friday_time_out->getPlaceHolder()) ?>" value="<?= $Page->friday_time_out->EditValue ?>"<?= $Page->friday_time_out->editAttributes() ?> aria-describedby="x_friday_time_out_help">
                <?= $Page->friday_time_out->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->friday_time_out->getErrorMessage() ?></div>
                <?php if (!$Page->friday_time_out->ReadOnly && !$Page->friday_time_out->Disabled && !isset($Page->friday_time_out->EditAttrs["readonly"]) && !isset($Page->friday_time_out->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_friday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->saturday_time_in->Visible && (!$Page->isConfirm() || $Page->saturday_time_in->multiUpdateSelected())) { // saturday_time_in ?>
    <div id="r_saturday_time_in" class="form-group row">
        <label for="x_saturday_time_in" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_saturday_time_in" id="u_saturday_time_in" class="custom-control-input ew-multi-select" value="1"<?= $Page->saturday_time_in->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_saturday_time_in"><?= $Page->saturday_time_in->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->saturday_time_in->cellAttributes() ?>>
                <span id="el_master_shift_saturday_time_in">
                <input type="<?= $Page->saturday_time_in->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_in" name="x_saturday_time_in" id="x_saturday_time_in" placeholder="<?= HtmlEncode($Page->saturday_time_in->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_in->EditValue ?>"<?= $Page->saturday_time_in->editAttributes() ?> aria-describedby="x_saturday_time_in_help">
                <?= $Page->saturday_time_in->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->saturday_time_in->getErrorMessage() ?></div>
                <?php if (!$Page->saturday_time_in->ReadOnly && !$Page->saturday_time_in->Disabled && !isset($Page->saturday_time_in->EditAttrs["readonly"]) && !isset($Page->saturday_time_in->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_saturday_time_in", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->saturday_time_out->Visible && (!$Page->isConfirm() || $Page->saturday_time_out->multiUpdateSelected())) { // saturday_time_out ?>
    <div id="r_saturday_time_out" class="form-group row">
        <label for="x_saturday_time_out" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_saturday_time_out" id="u_saturday_time_out" class="custom-control-input ew-multi-select" value="1"<?= $Page->saturday_time_out->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_saturday_time_out"><?= $Page->saturday_time_out->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->saturday_time_out->cellAttributes() ?>>
                <span id="el_master_shift_saturday_time_out">
                <input type="<?= $Page->saturday_time_out->getInputTextType() ?>" data-table="master_shift" data-field="x_saturday_time_out" name="x_saturday_time_out" id="x_saturday_time_out" placeholder="<?= HtmlEncode($Page->saturday_time_out->getPlaceHolder()) ?>" value="<?= $Page->saturday_time_out->EditValue ?>"<?= $Page->saturday_time_out->editAttributes() ?> aria-describedby="x_saturday_time_out_help">
                <?= $Page->saturday_time_out->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->saturday_time_out->getErrorMessage() ?></div>
                <?php if (!$Page->saturday_time_out->ReadOnly && !$Page->saturday_time_out->Disabled && !isset($Page->saturday_time_out->EditAttrs["readonly"]) && !isset($Page->saturday_time_out->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_shiftupdate", "timepicker"], function() {
                    ew.createTimePicker("fmaster_shiftupdate", "x_saturday_time_out", {"timeFormat":"H" + ew.TIME_SEPARATOR + "i","step":15});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page -->
<?php if (!$Page->IsModal) { ?>
    <div class="form-group row"><!-- buttons .form-group -->
        <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("UpdateBtn") ?></button>
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
