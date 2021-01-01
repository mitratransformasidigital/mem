<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeTrainingsUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.employee_trainings) ew.vars.tables.employee_trainings = <?= JsonEncode(GetClientVar("tables", "employee_trainings")) ?>;
var currentForm, currentPageID;
var femployee_trainingsupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    femployee_trainingsupdate = currentForm = new ew.Form("femployee_trainingsupdate", "update");

    // Add fields
    var fields = ew.vars.tables.employee_trainings.fields;
    femployee_trainingsupdate.addFields([
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["training_name", [fields.training_name.required ? ew.Validators.required(fields.training_name.caption) : null], fields.training_name.isInvalid],
        ["training_start", [fields.training_start.required ? ew.Validators.required(fields.training_start.caption) : null, ew.Validators.datetime(0), ew.Validators.selected], fields.training_start.isInvalid],
        ["training_end", [fields.training_end.required ? ew.Validators.required(fields.training_end.caption) : null, ew.Validators.datetime(0), ew.Validators.selected], fields.training_end.isInvalid],
        ["training_company", [fields.training_company.required ? ew.Validators.required(fields.training_company.caption) : null], fields.training_company.isInvalid],
        ["certificate_start", [fields.certificate_start.required ? ew.Validators.required(fields.certificate_start.caption) : null, ew.Validators.datetime(0), ew.Validators.selected], fields.certificate_start.isInvalid],
        ["certificate_end", [fields.certificate_end.required ? ew.Validators.required(fields.certificate_end.caption) : null, ew.Validators.datetime(0), ew.Validators.selected], fields.certificate_end.isInvalid],
        ["notes", [fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_trainingsupdate,
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
    femployee_trainingsupdate.validate = function () {
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
    femployee_trainingsupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_trainingsupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_trainingsupdate.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("femployee_trainingsupdate");
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
<form name="femployee_trainingsupdate" id="femployee_trainingsupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_trainings">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_employee_trainingsupdate" class="ew-update-div"><!-- page -->
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
                <span id="el_employee_trainings_employee_username">
                <span<?= $Page->employee_username->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->employee_username->getDisplayValue($Page->employee_username->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_employee_username" name="x_employee_username" value="<?= HtmlEncode($Page->employee_username->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_employee_trainings_employee_username">
                    <select
                        id="x_employee_username"
                        name="x_employee_username"
                        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
                        data-select2-id="employee_trainings_x_employee_username"
                        data-table="employee_trainings"
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
                    var el = document.querySelector("select[data-select2-id='employee_trainings_x_employee_username']"),
                        options = { name: "x_employee_username", selectId: "employee_trainings_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee_trainings.fields.employee_username.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->training_name->Visible && (!$Page->isConfirm() || $Page->training_name->multiUpdateSelected())) { // training_name ?>
    <div id="r_training_name" class="form-group row">
        <label for="x_training_name" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_training_name" id="u_training_name" class="custom-control-input ew-multi-select" value="1"<?= $Page->training_name->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_training_name"><?= $Page->training_name->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->training_name->cellAttributes() ?>>
                <span id="el_employee_trainings_training_name">
                <input type="<?= $Page->training_name->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_name" name="x_training_name" id="x_training_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->training_name->getPlaceHolder()) ?>" value="<?= $Page->training_name->EditValue ?>"<?= $Page->training_name->editAttributes() ?> aria-describedby="x_training_name_help">
                <?= $Page->training_name->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->training_name->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->training_start->Visible && (!$Page->isConfirm() || $Page->training_start->multiUpdateSelected())) { // training_start ?>
    <div id="r_training_start" class="form-group row">
        <label for="x_training_start" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_training_start" id="u_training_start" class="custom-control-input ew-multi-select" value="1"<?= $Page->training_start->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_training_start"><?= $Page->training_start->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->training_start->cellAttributes() ?>>
                <span id="el_employee_trainings_training_start">
                <input type="<?= $Page->training_start->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_start" name="x_training_start" id="x_training_start" placeholder="<?= HtmlEncode($Page->training_start->getPlaceHolder()) ?>" value="<?= $Page->training_start->EditValue ?>"<?= $Page->training_start->editAttributes() ?> aria-describedby="x_training_start_help">
                <?= $Page->training_start->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->training_start->getErrorMessage() ?></div>
                <?php if (!$Page->training_start->ReadOnly && !$Page->training_start->Disabled && !isset($Page->training_start->EditAttrs["readonly"]) && !isset($Page->training_start->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployee_trainingsupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployee_trainingsupdate", "x_training_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->training_end->Visible && (!$Page->isConfirm() || $Page->training_end->multiUpdateSelected())) { // training_end ?>
    <div id="r_training_end" class="form-group row">
        <label for="x_training_end" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_training_end" id="u_training_end" class="custom-control-input ew-multi-select" value="1"<?= $Page->training_end->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_training_end"><?= $Page->training_end->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->training_end->cellAttributes() ?>>
                <span id="el_employee_trainings_training_end">
                <input type="<?= $Page->training_end->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_end" name="x_training_end" id="x_training_end" placeholder="<?= HtmlEncode($Page->training_end->getPlaceHolder()) ?>" value="<?= $Page->training_end->EditValue ?>"<?= $Page->training_end->editAttributes() ?> aria-describedby="x_training_end_help">
                <?= $Page->training_end->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->training_end->getErrorMessage() ?></div>
                <?php if (!$Page->training_end->ReadOnly && !$Page->training_end->Disabled && !isset($Page->training_end->EditAttrs["readonly"]) && !isset($Page->training_end->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployee_trainingsupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployee_trainingsupdate", "x_training_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->training_company->Visible && (!$Page->isConfirm() || $Page->training_company->multiUpdateSelected())) { // training_company ?>
    <div id="r_training_company" class="form-group row">
        <label for="x_training_company" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_training_company" id="u_training_company" class="custom-control-input ew-multi-select" value="1"<?= $Page->training_company->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_training_company"><?= $Page->training_company->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->training_company->cellAttributes() ?>>
                <span id="el_employee_trainings_training_company">
                <input type="<?= $Page->training_company->getInputTextType() ?>" data-table="employee_trainings" data-field="x_training_company" name="x_training_company" id="x_training_company" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->training_company->getPlaceHolder()) ?>" value="<?= $Page->training_company->EditValue ?>"<?= $Page->training_company->editAttributes() ?> aria-describedby="x_training_company_help">
                <?= $Page->training_company->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->training_company->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->certificate_start->Visible && (!$Page->isConfirm() || $Page->certificate_start->multiUpdateSelected())) { // certificate_start ?>
    <div id="r_certificate_start" class="form-group row">
        <label for="x_certificate_start" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_certificate_start" id="u_certificate_start" class="custom-control-input ew-multi-select" value="1"<?= $Page->certificate_start->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_certificate_start"><?= $Page->certificate_start->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->certificate_start->cellAttributes() ?>>
                <span id="el_employee_trainings_certificate_start">
                <input type="<?= $Page->certificate_start->getInputTextType() ?>" data-table="employee_trainings" data-field="x_certificate_start" name="x_certificate_start" id="x_certificate_start" placeholder="<?= HtmlEncode($Page->certificate_start->getPlaceHolder()) ?>" value="<?= $Page->certificate_start->EditValue ?>"<?= $Page->certificate_start->editAttributes() ?> aria-describedby="x_certificate_start_help">
                <?= $Page->certificate_start->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->certificate_start->getErrorMessage() ?></div>
                <?php if (!$Page->certificate_start->ReadOnly && !$Page->certificate_start->Disabled && !isset($Page->certificate_start->EditAttrs["readonly"]) && !isset($Page->certificate_start->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployee_trainingsupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployee_trainingsupdate", "x_certificate_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->certificate_end->Visible && (!$Page->isConfirm() || $Page->certificate_end->multiUpdateSelected())) { // certificate_end ?>
    <div id="r_certificate_end" class="form-group row">
        <label for="x_certificate_end" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_certificate_end" id="u_certificate_end" class="custom-control-input ew-multi-select" value="1"<?= $Page->certificate_end->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_certificate_end"><?= $Page->certificate_end->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->certificate_end->cellAttributes() ?>>
                <span id="el_employee_trainings_certificate_end">
                <input type="<?= $Page->certificate_end->getInputTextType() ?>" data-table="employee_trainings" data-field="x_certificate_end" name="x_certificate_end" id="x_certificate_end" placeholder="<?= HtmlEncode($Page->certificate_end->getPlaceHolder()) ?>" value="<?= $Page->certificate_end->EditValue ?>"<?= $Page->certificate_end->editAttributes() ?> aria-describedby="x_certificate_end_help">
                <?= $Page->certificate_end->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->certificate_end->getErrorMessage() ?></div>
                <?php if (!$Page->certificate_end->ReadOnly && !$Page->certificate_end->Disabled && !isset($Page->certificate_end->EditAttrs["readonly"]) && !isset($Page->certificate_end->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployee_trainingsupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployee_trainingsupdate", "x_certificate_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
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
                <span id="el_employee_trainings_notes">
                <textarea data-table="employee_trainings" data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>"<?= $Page->notes->editAttributes() ?> aria-describedby="x_notes_help"><?= $Page->notes->EditValue ?></textarea>
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
    ew.addEventHandlers("employee_trainings");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
