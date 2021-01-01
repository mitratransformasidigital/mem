<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeShiftUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.employee_shift) ew.vars.tables.employee_shift = <?= JsonEncode(GetClientVar("tables", "employee_shift")) ?>;
var currentForm, currentPageID;
var femployee_shiftupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    femployee_shiftupdate = currentForm = new ew.Form("femployee_shiftupdate", "update");

    // Add fields
    var fields = ew.vars.tables.employee_shift.fields;
    femployee_shiftupdate.addFields([
        ["shift_id", [fields.shift_id.required ? ew.Validators.required(fields.shift_id.caption) : null], fields.shift_id.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["start_date", [fields.start_date.required ? ew.Validators.required(fields.start_date.caption) : null, ew.Validators.datetime(5), ew.Validators.selected], fields.start_date.isInvalid],
        ["end_date", [fields.end_date.required ? ew.Validators.required(fields.end_date.caption) : null, ew.Validators.datetime(5), ew.Validators.selected], fields.end_date.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_shiftupdate,
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
    femployee_shiftupdate.validate = function () {
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
    femployee_shiftupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_shiftupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_shiftupdate.lists.shift_id = <?= $Page->shift_id->toClientList($Page) ?>;
    femployee_shiftupdate.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("femployee_shiftupdate");
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
<form name="femployee_shiftupdate" id="femployee_shiftupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_shift">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_employee_shiftupdate" class="ew-update-div"><!-- page -->
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
                <span id="el_employee_shift_employee_username">
                <span<?= $Page->employee_username->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_username->getDisplayValue($Page->employee_username->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_employee_username" name="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_employee_shift_employee_username">
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
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->start_date->Visible && (!$Page->isConfirm() || $Page->start_date->multiUpdateSelected())) { // start_date ?>
    <div id="r_start_date" class="form-group row">
        <label for="x_start_date" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_start_date" id="u_start_date" class="custom-control-input ew-multi-select" value="1"<?= $Page->start_date->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_start_date"><?= $Page->start_date->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->start_date->cellAttributes() ?>>
                <span id="el_employee_shift_start_date">
                <input type="<?= $Page->start_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_start_date" data-format="5" name="x_start_date" id="x_start_date" placeholder="<?= HtmlEncode($Page->start_date->getPlaceHolder()) ?>" value="<?= $Page->start_date->EditValue ?>"<?= $Page->start_date->editAttributes() ?> aria-describedby="x_start_date_help">
                <?= $Page->start_date->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->start_date->getErrorMessage() ?></div>
                <?php if (!$Page->start_date->ReadOnly && !$Page->start_date->Disabled && !isset($Page->start_date->EditAttrs["readonly"]) && !isset($Page->start_date->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployee_shiftupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployee_shiftupdate", "x_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->end_date->Visible && (!$Page->isConfirm() || $Page->end_date->multiUpdateSelected())) { // end_date ?>
    <div id="r_end_date" class="form-group row">
        <label for="x_end_date" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_end_date" id="u_end_date" class="custom-control-input ew-multi-select" value="1"<?= $Page->end_date->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_end_date"><?= $Page->end_date->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->end_date->cellAttributes() ?>>
                <span id="el_employee_shift_end_date">
                <input type="<?= $Page->end_date->getInputTextType() ?>" data-table="employee_shift" data-field="x_end_date" data-format="5" name="x_end_date" id="x_end_date" placeholder="<?= HtmlEncode($Page->end_date->getPlaceHolder()) ?>" value="<?= $Page->end_date->EditValue ?>"<?= $Page->end_date->editAttributes() ?> aria-describedby="x_end_date_help">
                <?= $Page->end_date->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->end_date->getErrorMessage() ?></div>
                <?php if (!$Page->end_date->ReadOnly && !$Page->end_date->Disabled && !isset($Page->end_date->EditAttrs["readonly"]) && !isset($Page->end_date->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployee_shiftupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployee_shiftupdate", "x_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
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
    ew.addEventHandlers("employee_shift");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
