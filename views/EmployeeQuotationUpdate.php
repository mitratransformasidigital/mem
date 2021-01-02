<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeQuotationUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.employee_quotation) ew.vars.tables.employee_quotation = <?= JsonEncode(GetClientVar("tables", "employee_quotation")) ?>;
var currentForm, currentPageID;
var femployee_quotationupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    femployee_quotationupdate = currentForm = new ew.Form("femployee_quotationupdate", "update");

    // Add fields
    var fields = ew.vars.tables.employee_quotation.fields;
    femployee_quotationupdate.addFields([
        ["quotation_no", [fields.quotation_no.required ? ew.Validators.required(fields.quotation_no.caption) : null], fields.quotation_no.isInvalid],
        ["customer_id", [fields.customer_id.required ? ew.Validators.required(fields.customer_id.caption) : null], fields.customer_id.isInvalid],
        ["quotation_date", [fields.quotation_date.required ? ew.Validators.required(fields.quotation_date.caption) : null, ew.Validators.datetime(5), ew.Validators.selected], fields.quotation_date.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_quotationupdate,
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
    femployee_quotationupdate.validate = function () {
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
    femployee_quotationupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_quotationupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_quotationupdate.lists.customer_id = <?= $Page->customer_id->toClientList($Page) ?>;
    loadjs.done("femployee_quotationupdate");
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
<form name="femployee_quotationupdate" id="femployee_quotationupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_quotation">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_employee_quotationupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->quotation_no->Visible && (!$Page->isConfirm() || $Page->quotation_no->multiUpdateSelected())) { // quotation_no ?>
    <div id="r_quotation_no" class="form-group row">
        <label for="x_quotation_no" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_quotation_no" id="u_quotation_no" class="custom-control-input ew-multi-select" value="1"<?= $Page->quotation_no->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_quotation_no"><?= $Page->quotation_no->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->quotation_no->cellAttributes() ?>>
                <span id="el_employee_quotation_quotation_no">
                <input type="<?= $Page->quotation_no->getInputTextType() ?>" data-table="employee_quotation" data-field="x_quotation_no" name="x_quotation_no" id="x_quotation_no" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->quotation_no->getPlaceHolder()) ?>" value="<?= $Page->quotation_no->EditValue ?>"<?= $Page->quotation_no->editAttributes() ?> aria-describedby="x_quotation_no_help">
                <?= $Page->quotation_no->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->quotation_no->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->customer_id->Visible && (!$Page->isConfirm() || $Page->customer_id->multiUpdateSelected())) { // customer_id ?>
    <div id="r_customer_id" class="form-group row">
        <label for="x_customer_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_customer_id" id="u_customer_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->customer_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_customer_id"><?= $Page->customer_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->customer_id->cellAttributes() ?>>
                <span id="el_employee_quotation_customer_id">
                    <select
                        id="x_customer_id"
                        name="x_customer_id"
                        class="form-control ew-select<?= $Page->customer_id->isInvalidClass() ?>"
                        data-select2-id="employee_quotation_x_customer_id"
                        data-table="employee_quotation"
                        data-field="x_customer_id"
                        data-value-separator="<?= $Page->customer_id->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->customer_id->getPlaceHolder()) ?>"
                        <?= $Page->customer_id->editAttributes() ?>>
                        <?= $Page->customer_id->selectOptionListHtml("x_customer_id") ?>
                    </select>
                    <?= $Page->customer_id->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->customer_id->getErrorMessage() ?></div>
                <?= $Page->customer_id->Lookup->getParamTag($Page, "p_x_customer_id") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='employee_quotation_x_customer_id']"),
                        options = { name: "x_customer_id", selectId: "employee_quotation_x_customer_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.employee_quotation.fields.customer_id.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->quotation_date->Visible && (!$Page->isConfirm() || $Page->quotation_date->multiUpdateSelected())) { // quotation_date ?>
    <div id="r_quotation_date" class="form-group row">
        <label for="x_quotation_date" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_quotation_date" id="u_quotation_date" class="custom-control-input ew-multi-select" value="1"<?= $Page->quotation_date->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_quotation_date"><?= $Page->quotation_date->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->quotation_date->cellAttributes() ?>>
                <span id="el_employee_quotation_quotation_date">
                <input type="<?= $Page->quotation_date->getInputTextType() ?>" data-table="employee_quotation" data-field="x_quotation_date" data-format="5" name="x_quotation_date" id="x_quotation_date" placeholder="<?= HtmlEncode($Page->quotation_date->getPlaceHolder()) ?>" value="<?= $Page->quotation_date->EditValue ?>"<?= $Page->quotation_date->editAttributes() ?> aria-describedby="x_quotation_date_help">
                <?= $Page->quotation_date->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->quotation_date->getErrorMessage() ?></div>
                <?php if (!$Page->quotation_date->ReadOnly && !$Page->quotation_date->Disabled && !isset($Page->quotation_date->EditAttrs["readonly"]) && !isset($Page->quotation_date->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["femployee_quotationupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("femployee_quotationupdate", "x_quotation_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
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
    ew.addEventHandlers("employee_quotation");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
