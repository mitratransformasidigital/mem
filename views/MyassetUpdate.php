<?php

namespace MEM\prjMitralPHP;

// Page object
$MyassetUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.myasset) ew.vars.tables.myasset = <?= JsonEncode(GetClientVar("tables", "myasset")) ?>;
var currentForm, currentPageID;
var fmyassetupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fmyassetupdate = currentForm = new ew.Form("fmyassetupdate", "update");

    // Add fields
    var fields = ew.vars.tables.myasset.fields;
    fmyassetupdate.addFields([
        ["asset_name", [fields.asset_name.required ? ew.Validators.required(fields.asset_name.caption) : null], fields.asset_name.isInvalid],
        ["year", [fields.year.required ? ew.Validators.required(fields.year.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.year.isInvalid],
        ["serial_number", [fields.serial_number.required ? ew.Validators.required(fields.serial_number.caption) : null], fields.serial_number.isInvalid],
        ["value", [fields.value.required ? ew.Validators.required(fields.value.caption) : null, ew.Validators.float, ew.Validators.selected], fields.value.isInvalid],
        ["asset_received", [fields.asset_received.required ? ew.Validators.required(fields.asset_received.caption) : null, ew.Validators.datetime(5), ew.Validators.selected], fields.asset_received.isInvalid],
        ["notes", [fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmyassetupdate,
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
    fmyassetupdate.validate = function () {
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
    fmyassetupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmyassetupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmyassetupdate");
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
<form name="fmyassetupdate" id="fmyassetupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="myasset">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_myassetupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->asset_name->Visible && (!$Page->isConfirm() || $Page->asset_name->multiUpdateSelected())) { // asset_name ?>
    <div id="r_asset_name" class="form-group row">
        <label for="x_asset_name" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_asset_name" id="u_asset_name" class="custom-control-input ew-multi-select" value="1"<?= $Page->asset_name->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_asset_name"><?= $Page->asset_name->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->asset_name->cellAttributes() ?>>
                <span id="el_myasset_asset_name">
                <input type="<?= $Page->asset_name->getInputTextType() ?>" data-table="myasset" data-field="x_asset_name" name="x_asset_name" id="x_asset_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->asset_name->getPlaceHolder()) ?>" value="<?= $Page->asset_name->EditValue ?>"<?= $Page->asset_name->editAttributes() ?> aria-describedby="x_asset_name_help">
                <?= $Page->asset_name->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->asset_name->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->year->Visible && (!$Page->isConfirm() || $Page->year->multiUpdateSelected())) { // year ?>
    <div id="r_year" class="form-group row">
        <label for="x_year" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_year" id="u_year" class="custom-control-input ew-multi-select" value="1"<?= $Page->year->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_year"><?= $Page->year->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->year->cellAttributes() ?>>
                <span id="el_myasset_year">
                <input type="<?= $Page->year->getInputTextType() ?>" data-table="myasset" data-field="x_year" name="x_year" id="x_year" size="30" placeholder="<?= HtmlEncode($Page->year->getPlaceHolder()) ?>" value="<?= $Page->year->EditValue ?>"<?= $Page->year->editAttributes() ?> aria-describedby="x_year_help">
                <?= $Page->year->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->year->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->serial_number->Visible && (!$Page->isConfirm() || $Page->serial_number->multiUpdateSelected())) { // serial_number ?>
    <div id="r_serial_number" class="form-group row">
        <label for="x_serial_number" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_serial_number" id="u_serial_number" class="custom-control-input ew-multi-select" value="1"<?= $Page->serial_number->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_serial_number"><?= $Page->serial_number->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->serial_number->cellAttributes() ?>>
                <span id="el_myasset_serial_number">
                <input type="<?= $Page->serial_number->getInputTextType() ?>" data-table="myasset" data-field="x_serial_number" name="x_serial_number" id="x_serial_number" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->serial_number->getPlaceHolder()) ?>" value="<?= $Page->serial_number->EditValue ?>"<?= $Page->serial_number->editAttributes() ?> aria-describedby="x_serial_number_help">
                <?= $Page->serial_number->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->serial_number->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->value->Visible && (!$Page->isConfirm() || $Page->value->multiUpdateSelected())) { // value ?>
    <div id="r_value" class="form-group row">
        <label for="x_value" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_value" id="u_value" class="custom-control-input ew-multi-select" value="1"<?= $Page->value->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_value"><?= $Page->value->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->value->cellAttributes() ?>>
                <span id="el_myasset_value">
                <input type="<?= $Page->value->getInputTextType() ?>" data-table="myasset" data-field="x_value" name="x_value" id="x_value" size="30" placeholder="<?= HtmlEncode($Page->value->getPlaceHolder()) ?>" value="<?= $Page->value->EditValue ?>"<?= $Page->value->editAttributes() ?> aria-describedby="x_value_help">
                <?= $Page->value->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->value->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->asset_received->Visible && (!$Page->isConfirm() || $Page->asset_received->multiUpdateSelected())) { // asset_received ?>
    <div id="r_asset_received" class="form-group row">
        <label for="x_asset_received" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_asset_received" id="u_asset_received" class="custom-control-input ew-multi-select" value="1"<?= $Page->asset_received->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_asset_received"><?= $Page->asset_received->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->asset_received->cellAttributes() ?>>
                <span id="el_myasset_asset_received">
                <input type="<?= $Page->asset_received->getInputTextType() ?>" data-table="myasset" data-field="x_asset_received" data-format="5" name="x_asset_received" id="x_asset_received" placeholder="<?= HtmlEncode($Page->asset_received->getPlaceHolder()) ?>" value="<?= $Page->asset_received->EditValue ?>"<?= $Page->asset_received->editAttributes() ?> aria-describedby="x_asset_received_help">
                <?= $Page->asset_received->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->asset_received->getErrorMessage() ?></div>
                <?php if (!$Page->asset_received->ReadOnly && !$Page->asset_received->Disabled && !isset($Page->asset_received->EditAttrs["readonly"]) && !isset($Page->asset_received->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmyassetupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("fmyassetupdate", "x_asset_received", {"ignoreReadonly":true,"useCurrent":false,"format":5});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible && (!$Page->isConfirm() || $Page->notes->multiUpdateSelected())) { // notes ?>
    <div id="r_notes" class="form-group row">
        <label for="x_notes" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_notes" id="u_notes" class="custom-control-input ew-multi-select" value="1"<?= $Page->notes->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_notes"><?= $Page->notes->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->notes->cellAttributes() ?>>
                <span id="el_myasset_notes">
                <textarea data-table="myasset" data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>"<?= $Page->notes->editAttributes() ?> aria-describedby="x_notes_help"><?= $Page->notes->EditValue ?></textarea>
                <?= $Page->notes->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->notes->getErrorMessage() ?></div>
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
    ew.addEventHandlers("myasset");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
