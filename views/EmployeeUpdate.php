<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.employee) ew.vars.tables.employee = <?= JsonEncode(GetClientVar("tables", "employee")) ?>;
var currentForm, currentPageID;
var femployeeupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    femployeeupdate = currentForm = new ew.Form("femployeeupdate", "update");

    // Add fields
    var fields = ew.vars.tables.employee.fields;
    femployeeupdate.addFields([
        ["employee_name", [fields.employee_name.required ? ew.Validators.required(fields.employee_name.caption) : null], fields.employee_name.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["employee_email", [fields.employee_email.required ? ew.Validators.required(fields.employee_email.caption) : null], fields.employee_email.isInvalid],
        ["birth_date", [fields.birth_date.required ? ew.Validators.required(fields.birth_date.caption) : null, ew.Validators.datetime(5), ew.Validators.selected], fields.birth_date.isInvalid],
        ["religion", [fields.religion.required ? ew.Validators.required(fields.religion.caption) : null], fields.religion.isInvalid],
        ["nik", [fields.nik.required ? ew.Validators.required(fields.nik.caption) : null], fields.nik.isInvalid],
        ["npwp", [fields.npwp.required ? ew.Validators.required(fields.npwp.caption) : null], fields.npwp.isInvalid],
        ["address", [fields.address.required ? ew.Validators.required(fields.address.caption) : null], fields.address.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["postal_code", [fields.postal_code.required ? ew.Validators.required(fields.postal_code.caption) : null], fields.postal_code.isInvalid],
        ["bank_number", [fields.bank_number.required ? ew.Validators.required(fields.bank_number.caption) : null], fields.bank_number.isInvalid],
        ["bank_name", [fields.bank_name.required ? ew.Validators.required(fields.bank_name.caption) : null], fields.bank_name.isInvalid],
        ["position_id", [fields.position_id.required ? ew.Validators.required(fields.position_id.caption) : null], fields.position_id.isInvalid],
        ["status_id", [fields.status_id.required ? ew.Validators.required(fields.status_id.caption) : null], fields.status_id.isInvalid],
        ["skill_id", [fields.skill_id.required ? ew.Validators.required(fields.skill_id.caption) : null], fields.skill_id.isInvalid],
        ["office_id", [fields.office_id.required ? ew.Validators.required(fields.office_id.caption) : null], fields.office_id.isInvalid],
        ["hire_date", [fields.hire_date.required ? ew.Validators.required(fields.hire_date.caption) : null, ew.Validators.datetime(5), ew.Validators.selected], fields.hire_date.isInvalid],
        ["termination_date", [fields.termination_date.required ? ew.Validators.required(fields.termination_date.caption) : null, ew.Validators.datetime(5), ew.Validators.selected], fields.termination_date.isInvalid],
        ["user_level", [fields.user_level.required ? ew.Validators.required(fields.user_level.caption) : null], fields.user_level.isInvalid],
        ["technical_skill", [fields.technical_skill.required ? ew.Validators.required(fields.technical_skill.caption) : null], fields.technical_skill.isInvalid],
        ["about_me", [fields.about_me.required ? ew.Validators.required(fields.about_me.caption) : null], fields.about_me.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployeeupdate,
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
    femployeeupdate.validate = function () {
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
    femployeeupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployeeupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployeeupdate.lists.religion = <?= $Page->religion->toClientList($Page) ?>;
    femployeeupdate.lists.city_id = <?= $Page->city_id->toClientList($Page) ?>;
    femployeeupdate.lists.position_id = <?= $Page->position_id->toClientList($Page) ?>;
    femployeeupdate.lists.status_id = <?= $Page->status_id->toClientList($Page) ?>;
    femployeeupdate.lists.skill_id = <?= $Page->skill_id->toClientList($Page) ?>;
    femployeeupdate.lists.office_id = <?= $Page->office_id->toClientList($Page) ?>;
    femployeeupdate.lists.user_level = <?= $Page->user_level->toClientList($Page) ?>;
    loadjs.done("femployeeupdate");
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
<form name="femployeeupdate" id="femployeeupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_employeeupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->employee_name->Visible && (!$Page->isConfirm() || $Page->employee_name->multiUpdateSelected())) { // employee_name ?>
    <div id="r_employee_name" class="form-group row">
        <label for="x_employee_name" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_employee_name" id="u_employee_name" class="custom-control-input ew-multi-select" value="1"<?= $Page->employee_name->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_employee_name"><?= $Page->employee_name->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->employee_name->cellAttributes() ?>>
                <span id="el_employee_employee_name">
                <input type="<?= $Page->employee_name->getInputTextType() ?>" data-table="employee" data-field="x_employee_name" name="x_employee_name" id="x_employee_name" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->employee_name->getPlaceHolder()) ?>" value="<?= $Page->employee_name->EditValue ?>"<?= $Page->employee_name->editAttributes() ?> aria-describedby="x_employee_name_help">
                <?= $Page->employee_name->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->employee_name->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
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
                <input type="<?= $Page->employee_username->getInputTextType() ?>" data-table="employee" data-field="x_employee_username" name="x_employee_username" id="x_employee_username" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>" value="<?= $Page->employee_username->EditValue ?>"<?= $Page->employee_username->editAttributes() ?> aria-describedby="x_employee_username_help">
                <?= $Page->employee_username->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage() ?></div>
                <input type="hidden" data-table="employee" data-field="x_employee_username" data-hidden="1" name="o_employee_username" id="o_employee_username" value="<?= HtmlEncode($Page->employee_username->OldValue ?? $Page->employee_username->CurrentValue) ?>">
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->employee_email->Visible && (!$Page->isConfirm() || $Page->employee_email->multiUpdateSelected())) { // employee_email ?>
    <div id="r_employee_email" class="form-group row">
        <label for="x_employee_email" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_employee_email" id="u_employee_email" class="custom-control-input ew-multi-select" value="1"<?= $Page->employee_email->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_employee_email"><?= $Page->employee_email->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->employee_email->cellAttributes() ?>>
                <span id="el_employee_employee_email">
                <input type="<?= $Page->employee_email->getInputTextType() ?>" data-table="employee" data-field="x_employee_email" name="x_employee_email" id="x_employee_email" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->employee_email->getPlaceHolder()) ?>" value="<?= $Page->employee_email->EditValue ?>"<?= $Page->employee_email->editAttributes() ?> aria-describedby="x_employee_email_help">
                <?= $Page->employee_email->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->employee_email->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->birth_date->Visible && (!$Page->isConfirm() || $Page->birth_date->multiUpdateSelected())) { // birth_date ?>
    <div id="r_birth_date" class="form-group row">
        <label for="x_birth_date" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_birth_date" id="u_birth_date" class="custom-control-input ew-multi-select" value="1"<?= $Page->birth_date->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_birth_date"><?= $Page->birth_date->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->birth_date->cellAttributes() ?>>
                <span id="el_employee_birth_date">
                <input type="<?= $Page->birth_date->getInputTextType() ?>" data-table="employee" data-field="x_birth_date" data-format="5" name="x_birth_date" id="x_birth_date" placeholder="<?= HtmlEncode($Page->birth_date->getPlaceHolder()) ?>" value="<?= $Page->birth_date->EditValue ?>"<?= $Page->birth_date->editAttributes() ?> aria-describedby="x_birth_date_help">
                <?= $Page->birth_date->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->birth_date->getErrorMessage() ?></div>
                <?php if (!$Page->birth_date->ReadOnly && !$Page->birth_date->Disabled && !isset($Page->birth_date->EditAttrs["readonly"]) && !isset($Page->birth_date->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployeeupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployeeupdate", "x_birth_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->religion->Visible && (!$Page->isConfirm() || $Page->religion->multiUpdateSelected())) { // religion ?>
    <div id="r_religion" class="form-group row">
        <label for="x_religion" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_religion" id="u_religion" class="custom-control-input ew-multi-select" value="1"<?= $Page->religion->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_religion"><?= $Page->religion->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->religion->cellAttributes() ?>>
                <span id="el_employee_religion">
                    <select
                        id="x_religion"
                        name="x_religion"
                        class="form-control ew-select<?= $Page->religion->isInvalidClass() ?>"
                        data-select2-id="employee_x_religion"
                        data-table="employee"
                        data-field="x_religion"
                        data-value-separator="<?= $Page->religion->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->religion->getPlaceHolder()) ?>"
                        <?= $Page->religion->editAttributes() ?>>
                        <?= $Page->religion->selectOptionListHtml("x_religion") ?>
                    </select>
                    <?= $Page->religion->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->religion->getErrorMessage() ?></div>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_x_religion']"),
                        options = { name: "x_religion", selectId: "employee_x_religion", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.data = ew.vars.tables.employee.fields.religion.lookupOptions;
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee.fields.religion.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->nik->Visible && (!$Page->isConfirm() || $Page->nik->multiUpdateSelected())) { // nik ?>
    <div id="r_nik" class="form-group row">
        <label for="x_nik" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_nik" id="u_nik" class="custom-control-input ew-multi-select" value="1"<?= $Page->nik->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_nik"><?= $Page->nik->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->nik->cellAttributes() ?>>
                <span id="el_employee_nik">
                <input type="<?= $Page->nik->getInputTextType() ?>" data-table="employee" data-field="x_nik" name="x_nik" id="x_nik" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->nik->getPlaceHolder()) ?>" value="<?= $Page->nik->EditValue ?>"<?= $Page->nik->editAttributes() ?> aria-describedby="x_nik_help">
                <?= $Page->nik->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->nik->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->npwp->Visible && (!$Page->isConfirm() || $Page->npwp->multiUpdateSelected())) { // npwp ?>
    <div id="r_npwp" class="form-group row">
        <label for="x_npwp" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_npwp" id="u_npwp" class="custom-control-input ew-multi-select" value="1"<?= $Page->npwp->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_npwp"><?= $Page->npwp->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->npwp->cellAttributes() ?>>
                <span id="el_employee_npwp">
                <input type="<?= $Page->npwp->getInputTextType() ?>" data-table="employee" data-field="x_npwp" name="x_npwp" id="x_npwp" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->npwp->getPlaceHolder()) ?>" value="<?= $Page->npwp->EditValue ?>"<?= $Page->npwp->editAttributes() ?> aria-describedby="x_npwp_help">
                <?= $Page->npwp->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->npwp->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->address->Visible && (!$Page->isConfirm() || $Page->address->multiUpdateSelected())) { // address ?>
    <div id="r_address" class="form-group row">
        <label for="x_address" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_address" id="u_address" class="custom-control-input ew-multi-select" value="1"<?= $Page->address->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_address"><?= $Page->address->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->address->cellAttributes() ?>>
                <span id="el_employee_address">
                <input type="<?= $Page->address->getInputTextType() ?>" data-table="employee" data-field="x_address" name="x_address" id="x_address" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>" value="<?= $Page->address->EditValue ?>"<?= $Page->address->editAttributes() ?> aria-describedby="x_address_help">
                <?= $Page->address->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->address->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible && (!$Page->isConfirm() || $Page->city_id->multiUpdateSelected())) { // city_id ?>
    <div id="r_city_id" class="form-group row">
        <label for="x_city_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_city_id" id="u_city_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->city_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_city_id"><?= $Page->city_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->city_id->cellAttributes() ?>>
                <?php if ($Page->city_id->getSessionValue() != "") { ?>
                <span id="el_employee_city_id">
                <span<?= $Page->city_id->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->city_id->getDisplayValue($Page->city_id->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_city_id" name="x_city_id" value="<?= HtmlEncode($Page->city_id->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_employee_city_id">
                    <select
                        id="x_city_id"
                        name="x_city_id"
                        class="form-control ew-select<?= $Page->city_id->isInvalidClass() ?>"
                        data-select2-id="employee_x_city_id"
                        data-table="employee"
                        data-field="x_city_id"
                        data-value-separator="<?= $Page->city_id->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>"
                        <?= $Page->city_id->editAttributes() ?>>
                        <?= $Page->city_id->selectOptionListHtml("x_city_id") ?>
                    </select>
                    <?= $Page->city_id->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->city_id->getErrorMessage() ?></div>
                <?= $Page->city_id->Lookup->getParamTag($Page, "p_x_city_id") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_x_city_id']"),
                        options = { name: "x_city_id", selectId: "employee_x_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee.fields.city_id.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->postal_code->Visible && (!$Page->isConfirm() || $Page->postal_code->multiUpdateSelected())) { // postal_code ?>
    <div id="r_postal_code" class="form-group row">
        <label for="x_postal_code" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_postal_code" id="u_postal_code" class="custom-control-input ew-multi-select" value="1"<?= $Page->postal_code->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_postal_code"><?= $Page->postal_code->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->postal_code->cellAttributes() ?>>
                <span id="el_employee_postal_code">
                <input type="<?= $Page->postal_code->getInputTextType() ?>" data-table="employee" data-field="x_postal_code" name="x_postal_code" id="x_postal_code" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->postal_code->getPlaceHolder()) ?>" value="<?= $Page->postal_code->EditValue ?>"<?= $Page->postal_code->editAttributes() ?> aria-describedby="x_postal_code_help">
                <?= $Page->postal_code->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->postal_code->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->bank_number->Visible && (!$Page->isConfirm() || $Page->bank_number->multiUpdateSelected())) { // bank_number ?>
    <div id="r_bank_number" class="form-group row">
        <label for="x_bank_number" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_bank_number" id="u_bank_number" class="custom-control-input ew-multi-select" value="1"<?= $Page->bank_number->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_bank_number"><?= $Page->bank_number->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->bank_number->cellAttributes() ?>>
                <span id="el_employee_bank_number">
                <input type="<?= $Page->bank_number->getInputTextType() ?>" data-table="employee" data-field="x_bank_number" name="x_bank_number" id="x_bank_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->bank_number->getPlaceHolder()) ?>" value="<?= $Page->bank_number->EditValue ?>"<?= $Page->bank_number->editAttributes() ?> aria-describedby="x_bank_number_help">
                <?= $Page->bank_number->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->bank_number->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->bank_name->Visible && (!$Page->isConfirm() || $Page->bank_name->multiUpdateSelected())) { // bank_name ?>
    <div id="r_bank_name" class="form-group row">
        <label for="x_bank_name" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_bank_name" id="u_bank_name" class="custom-control-input ew-multi-select" value="1"<?= $Page->bank_name->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_bank_name"><?= $Page->bank_name->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->bank_name->cellAttributes() ?>>
                <span id="el_employee_bank_name">
                <input type="<?= $Page->bank_name->getInputTextType() ?>" data-table="employee" data-field="x_bank_name" name="x_bank_name" id="x_bank_name" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->bank_name->getPlaceHolder()) ?>" value="<?= $Page->bank_name->EditValue ?>"<?= $Page->bank_name->editAttributes() ?> aria-describedby="x_bank_name_help">
                <?= $Page->bank_name->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->bank_name->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->position_id->Visible && (!$Page->isConfirm() || $Page->position_id->multiUpdateSelected())) { // position_id ?>
    <div id="r_position_id" class="form-group row">
        <label for="x_position_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_position_id" id="u_position_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->position_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_position_id"><?= $Page->position_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->position_id->cellAttributes() ?>>
                <?php if ($Page->position_id->getSessionValue() != "") { ?>
                <span id="el_employee_position_id">
                <span<?= $Page->position_id->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->position_id->getDisplayValue($Page->position_id->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_position_id" name="x_position_id" value="<?= HtmlEncode($Page->position_id->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_employee_position_id">
                    <select
                        id="x_position_id"
                        name="x_position_id"
                        class="form-control ew-select<?= $Page->position_id->isInvalidClass() ?>"
                        data-select2-id="employee_x_position_id"
                        data-table="employee"
                        data-field="x_position_id"
                        data-value-separator="<?= $Page->position_id->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->position_id->getPlaceHolder()) ?>"
                        <?= $Page->position_id->editAttributes() ?>>
                        <?= $Page->position_id->selectOptionListHtml("x_position_id") ?>
                    </select>
                    <?= $Page->position_id->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->position_id->getErrorMessage() ?></div>
                <?= $Page->position_id->Lookup->getParamTag($Page, "p_x_position_id") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_x_position_id']"),
                        options = { name: "x_position_id", selectId: "employee_x_position_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee.fields.position_id.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->status_id->Visible && (!$Page->isConfirm() || $Page->status_id->multiUpdateSelected())) { // status_id ?>
    <div id="r_status_id" class="form-group row">
        <label for="x_status_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_status_id" id="u_status_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->status_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_status_id"><?= $Page->status_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->status_id->cellAttributes() ?>>
                <?php if ($Page->status_id->getSessionValue() != "") { ?>
                <span id="el_employee_status_id">
                <span<?= $Page->status_id->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->status_id->getDisplayValue($Page->status_id->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_status_id" name="x_status_id" value="<?= HtmlEncode($Page->status_id->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_employee_status_id">
                    <select
                        id="x_status_id"
                        name="x_status_id"
                        class="form-control ew-select<?= $Page->status_id->isInvalidClass() ?>"
                        data-select2-id="employee_x_status_id"
                        data-table="employee"
                        data-field="x_status_id"
                        data-value-separator="<?= $Page->status_id->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->status_id->getPlaceHolder()) ?>"
                        <?= $Page->status_id->editAttributes() ?>>
                        <?= $Page->status_id->selectOptionListHtml("x_status_id") ?>
                    </select>
                    <?= $Page->status_id->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->status_id->getErrorMessage() ?></div>
                <?= $Page->status_id->Lookup->getParamTag($Page, "p_x_status_id") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_x_status_id']"),
                        options = { name: "x_status_id", selectId: "employee_x_status_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee.fields.status_id.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->skill_id->Visible && (!$Page->isConfirm() || $Page->skill_id->multiUpdateSelected())) { // skill_id ?>
    <div id="r_skill_id" class="form-group row">
        <label for="x_skill_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_skill_id" id="u_skill_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->skill_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_skill_id"><?= $Page->skill_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->skill_id->cellAttributes() ?>>
                <?php if ($Page->skill_id->getSessionValue() != "") { ?>
                <span id="el_employee_skill_id">
                <span<?= $Page->skill_id->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->skill_id->getDisplayValue($Page->skill_id->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_skill_id" name="x_skill_id" value="<?= HtmlEncode($Page->skill_id->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_employee_skill_id">
                    <select
                        id="x_skill_id"
                        name="x_skill_id"
                        class="form-control ew-select<?= $Page->skill_id->isInvalidClass() ?>"
                        data-select2-id="employee_x_skill_id"
                        data-table="employee"
                        data-field="x_skill_id"
                        data-value-separator="<?= $Page->skill_id->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->skill_id->getPlaceHolder()) ?>"
                        <?= $Page->skill_id->editAttributes() ?>>
                        <?= $Page->skill_id->selectOptionListHtml("x_skill_id") ?>
                    </select>
                    <?= $Page->skill_id->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->skill_id->getErrorMessage() ?></div>
                <?= $Page->skill_id->Lookup->getParamTag($Page, "p_x_skill_id") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_x_skill_id']"),
                        options = { name: "x_skill_id", selectId: "employee_x_skill_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee.fields.skill_id.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->office_id->Visible && (!$Page->isConfirm() || $Page->office_id->multiUpdateSelected())) { // office_id ?>
    <div id="r_office_id" class="form-group row">
        <label for="x_office_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_office_id" id="u_office_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->office_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_office_id"><?= $Page->office_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->office_id->cellAttributes() ?>>
                <?php if ($Page->office_id->getSessionValue() != "") { ?>
                <span id="el_employee_office_id">
                <span<?= $Page->office_id->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->office_id->getDisplayValue($Page->office_id->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_office_id" name="x_office_id" value="<?= HtmlEncode($Page->office_id->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_employee_office_id">
                    <select
                        id="x_office_id"
                        name="x_office_id"
                        class="form-control ew-select<?= $Page->office_id->isInvalidClass() ?>"
                        data-select2-id="employee_x_office_id"
                        data-table="employee"
                        data-field="x_office_id"
                        data-value-separator="<?= $Page->office_id->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->office_id->getPlaceHolder()) ?>"
                        <?= $Page->office_id->editAttributes() ?>>
                        <?= $Page->office_id->selectOptionListHtml("x_office_id") ?>
                    </select>
                    <?= $Page->office_id->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->office_id->getErrorMessage() ?></div>
                <?= $Page->office_id->Lookup->getParamTag($Page, "p_x_office_id") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_x_office_id']"),
                        options = { name: "x_office_id", selectId: "employee_x_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee.fields.office_id.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->hire_date->Visible && (!$Page->isConfirm() || $Page->hire_date->multiUpdateSelected())) { // hire_date ?>
    <div id="r_hire_date" class="form-group row">
        <label for="x_hire_date" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_hire_date" id="u_hire_date" class="custom-control-input ew-multi-select" value="1"<?= $Page->hire_date->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_hire_date"><?= $Page->hire_date->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->hire_date->cellAttributes() ?>>
                <span id="el_employee_hire_date">
                <input type="<?= $Page->hire_date->getInputTextType() ?>" data-table="employee" data-field="x_hire_date" data-format="5" name="x_hire_date" id="x_hire_date" placeholder="<?= HtmlEncode($Page->hire_date->getPlaceHolder()) ?>" value="<?= $Page->hire_date->EditValue ?>"<?= $Page->hire_date->editAttributes() ?> aria-describedby="x_hire_date_help">
                <?= $Page->hire_date->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->hire_date->getErrorMessage() ?></div>
                <?php if (!$Page->hire_date->ReadOnly && !$Page->hire_date->Disabled && !isset($Page->hire_date->EditAttrs["readonly"]) && !isset($Page->hire_date->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployeeupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployeeupdate", "x_hire_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->termination_date->Visible && (!$Page->isConfirm() || $Page->termination_date->multiUpdateSelected())) { // termination_date ?>
    <div id="r_termination_date" class="form-group row">
        <label for="x_termination_date" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_termination_date" id="u_termination_date" class="custom-control-input ew-multi-select" value="1"<?= $Page->termination_date->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_termination_date"><?= $Page->termination_date->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->termination_date->cellAttributes() ?>>
                <span id="el_employee_termination_date">
                <input type="<?= $Page->termination_date->getInputTextType() ?>" data-table="employee" data-field="x_termination_date" data-format="5" name="x_termination_date" id="x_termination_date" placeholder="<?= HtmlEncode($Page->termination_date->getPlaceHolder()) ?>" value="<?= $Page->termination_date->EditValue ?>"<?= $Page->termination_date->editAttributes() ?> aria-describedby="x_termination_date_help">
                <?= $Page->termination_date->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->termination_date->getErrorMessage() ?></div>
                <?php if (!$Page->termination_date->ReadOnly && !$Page->termination_date->Disabled && !isset($Page->termination_date->EditAttrs["readonly"]) && !isset($Page->termination_date->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployeeupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployeeupdate", "x_termination_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->user_level->Visible && (!$Page->isConfirm() || $Page->user_level->multiUpdateSelected())) { // user_level ?>
    <div id="r_user_level" class="form-group row">
        <label for="x_user_level" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_user_level" id="u_user_level" class="custom-control-input ew-multi-select" value="1"<?= $Page->user_level->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_user_level"><?= $Page->user_level->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->user_level->cellAttributes() ?>>
                <?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
                <span id="el_employee_user_level">
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->user_level->getDisplayValue($Page->user_level->EditValue))) ?>">
                </span>
                <?php } else { ?>
                <span id="el_employee_user_level">
                    <select
                        id="x_user_level"
                        name="x_user_level"
                        class="form-control ew-select<?= $Page->user_level->isInvalidClass() ?>"
                        data-select2-id="employee_x_user_level"
                        data-table="employee"
                        data-field="x_user_level"
                        data-value-separator="<?= $Page->user_level->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->user_level->getPlaceHolder()) ?>"
                        <?= $Page->user_level->editAttributes() ?>>
                        <?= $Page->user_level->selectOptionListHtml("x_user_level") ?>
                    </select>
                    <?= $Page->user_level->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->user_level->getErrorMessage() ?></div>
                <?= $Page->user_level->Lookup->getParamTag($Page, "p_x_user_level") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_x_user_level']"),
                        options = { name: "x_user_level", selectId: "employee_x_user_level", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee.fields.user_level.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->technical_skill->Visible && (!$Page->isConfirm() || $Page->technical_skill->multiUpdateSelected())) { // technical_skill ?>
    <div id="r_technical_skill" class="form-group row">
        <label for="x_technical_skill" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_technical_skill" id="u_technical_skill" class="custom-control-input ew-multi-select" value="1"<?= $Page->technical_skill->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_technical_skill"><?= $Page->technical_skill->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->technical_skill->cellAttributes() ?>>
                <span id="el_employee_technical_skill">
                <textarea data-table="employee" data-field="x_technical_skill" name="x_technical_skill" id="x_technical_skill" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->technical_skill->getPlaceHolder()) ?>"<?= $Page->technical_skill->editAttributes() ?> aria-describedby="x_technical_skill_help"><?= $Page->technical_skill->EditValue ?></textarea>
                <?= $Page->technical_skill->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->technical_skill->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->about_me->Visible && (!$Page->isConfirm() || $Page->about_me->multiUpdateSelected())) { // about_me ?>
    <div id="r_about_me" class="form-group row">
        <label for="x_about_me" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_about_me" id="u_about_me" class="custom-control-input ew-multi-select" value="1"<?= $Page->about_me->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_about_me"><?= $Page->about_me->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->about_me->cellAttributes() ?>>
                <span id="el_employee_about_me">
                <textarea data-table="employee" data-field="x_about_me" name="x_about_me" id="x_about_me" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->about_me->getPlaceHolder()) ?>"<?= $Page->about_me->editAttributes() ?> aria-describedby="x_about_me_help"><?= $Page->about_me->EditValue ?></textarea>
                <?= $Page->about_me->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->about_me->getErrorMessage() ?></div>
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
    ew.addEventHandlers("employee");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
