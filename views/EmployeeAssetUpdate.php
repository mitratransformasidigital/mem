<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeAssetUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.employee_asset) ew.vars.tables.employee_asset = <?= JsonEncode(GetClientVar("tables", "employee_asset")) ?>;
var currentForm, currentPageID;
var femployee_assetupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    femployee_assetupdate = currentForm = new ew.Form("femployee_assetupdate", "update");

    // Add fields
    var fields = ew.vars.tables.employee_asset.fields;
    femployee_assetupdate.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["asset_name", [fields.asset_name.required ? ew.Validators.required(fields.asset_name.caption) : null], fields.asset_name.isInvalid],
        ["year", [fields.year.required ? ew.Validators.required(fields.year.caption) : null, ew.Validators.integer, ew.Validators.selected], fields.year.isInvalid],
        ["serial_number", [fields.serial_number.required ? ew.Validators.required(fields.serial_number.caption) : null], fields.serial_number.isInvalid],
        ["value", [fields.value.required ? ew.Validators.required(fields.value.caption) : null, ew.Validators.float, ew.Validators.selected], fields.value.isInvalid],
        ["asset_received", [fields.asset_received.required ? ew.Validators.required(fields.asset_received.caption) : null, ew.Validators.datetime(5), ew.Validators.selected], fields.asset_received.isInvalid],
        ["notes", [fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_assetupdate,
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
    femployee_assetupdate.validate = function () {
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
    femployee_assetupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_assetupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_assetupdate.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("femployee_assetupdate");
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
<form name="femployee_assetupdate" id="femployee_assetupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_asset">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_employee_assetupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->employee_username->Visible && (!$Page->isConfirm() || $Page->employee_username->multiUpdateSelected())) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label for="x_employee_username" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_employee_username" id="u_employee_username" class="custom-control-input ew-multi-select" value="1"<?= $Page->employee_username->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_employee_username"><?= $Page->employee_username->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->employee_username->cellAttributes() ?>>
                <?php if ($Page->employee_username->getSessionValue() != "") { ?>
                <span id="el_employee_asset_employee_username">
                <span<?= $Page->employee_username->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_username->getDisplayValue($Page->employee_username->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_employee_username" name="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_employee_asset_employee_username">
                    <select
                        id="x_employee_username"
                        name="x_employee_username"
                        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
                        data-select2-id="employee_asset_x_employee_username"
                        data-table="employee_asset"
                        data-field="x_employee_username"
                        data-value-separator="<?= $Page->employee_username->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>"
                        <?= $Page->employee_username->editAttributes() ?>>
                        <?= $Page->employee_username->selectOptionListHtml("x_employee_username") ?>
                    </select>
                    <?= $Page->employee_username->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage() ?></div>
                <?= $Page->employee_username->Lookup->getParamTag($Page, "p_x_employee_username") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_asset_x_employee_username']"),
                        options = { name: "x_employee_username", selectId: "employee_asset_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee_asset.fields.employee_username.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
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
                <span id="el_employee_asset_asset_name">
                <input type="<?= $Page->asset_name->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_name" name="x_asset_name" id="x_asset_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->asset_name->getPlaceHolder()) ?>" value="<?= $Page->asset_name->EditValue ?>"<?= $Page->asset_name->editAttributes() ?> aria-describedby="x_asset_name_help">
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
                <span id="el_employee_asset_year">
                <input type="<?= $Page->year->getInputTextType() ?>" data-table="employee_asset" data-field="x_year" name="x_year" id="x_year" size="30" placeholder="<?= HtmlEncode($Page->year->getPlaceHolder()) ?>" value="<?= $Page->year->EditValue ?>"<?= $Page->year->editAttributes() ?> aria-describedby="x_year_help">
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
                <span id="el_employee_asset_serial_number">
                <input type="<?= $Page->serial_number->getInputTextType() ?>" data-table="employee_asset" data-field="x_serial_number" name="x_serial_number" id="x_serial_number" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->serial_number->getPlaceHolder()) ?>" value="<?= $Page->serial_number->EditValue ?>"<?= $Page->serial_number->editAttributes() ?> aria-describedby="x_serial_number_help">
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
                <span id="el_employee_asset_value">
                <input type="<?= $Page->value->getInputTextType() ?>" data-table="employee_asset" data-field="x_value" name="x_value" id="x_value" size="30" placeholder="<?= HtmlEncode($Page->value->getPlaceHolder()) ?>" value="<?= $Page->value->EditValue ?>"<?= $Page->value->editAttributes() ?> aria-describedby="x_value_help">
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
                <span id="el_employee_asset_asset_received">
                <input type="<?= $Page->asset_received->getInputTextType() ?>" data-table="employee_asset" data-field="x_asset_received" data-format="5" name="x_asset_received" id="x_asset_received" placeholder="<?= HtmlEncode($Page->asset_received->getPlaceHolder()) ?>" value="<?= $Page->asset_received->EditValue ?>"<?= $Page->asset_received->editAttributes() ?> aria-describedby="x_asset_received_help">
                <?= $Page->asset_received->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->asset_received->getErrorMessage() ?></div>
                <?php if (!$Page->asset_received->ReadOnly && !$Page->asset_received->Disabled && !isset($Page->asset_received->EditAttrs["readonly"]) && !isset($Page->asset_received->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployee_assetupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployee_assetupdate", "x_asset_received", {"ignoreReadonly":true,"useCurrent":false,"format":5});
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
                <span id="el_employee_asset_notes">
                <textarea data-table="employee_asset" data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>"<?= $Page->notes->editAttributes() ?> aria-describedby="x_notes_help"><?= $Page->notes->EditValue ?></textarea>
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
    ew.addEventHandlers("employee_asset");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
