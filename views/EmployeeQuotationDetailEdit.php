<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeQuotationDetailEdit = &$Page;
?>
<script>
if (!ew.vars.tables.employee_quotation_detail) ew.vars.tables.employee_quotation_detail = <?= JsonEncode(GetClientVar("tables", "employee_quotation_detail")) ?>;
var currentForm, currentPageID;
var femployee_quotation_detailedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    femployee_quotation_detailedit = currentForm = new ew.Form("femployee_quotation_detailedit", "edit");

    // Add fields
    var fields = ew.vars.tables.employee_quotation_detail.fields;
    femployee_quotation_detailedit.addFields([
        ["quotation_id", [fields.quotation_id.required ? ew.Validators.required(fields.quotation_id.caption) : null], fields.quotation_id.isInvalid],
        ["detail_id", [fields.detail_id.required ? ew.Validators.required(fields.detail_id.caption) : null], fields.detail_id.isInvalid],
        ["employee_username", [fields.employee_username.required ? ew.Validators.required(fields.employee_username.caption) : null], fields.employee_username.isInvalid],
        ["rate", [fields.rate.required ? ew.Validators.required(fields.rate.caption) : null, ew.Validators.integer], fields.rate.isInvalid],
        ["qty", [fields.qty.required ? ew.Validators.required(fields.qty.caption) : null, ew.Validators.integer], fields.qty.isInvalid],
        ["Total", [fields.Total.required ? ew.Validators.required(fields.Total.caption) : null], fields.Total.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = femployee_quotation_detailedit,
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
    femployee_quotation_detailedit.validate = function () {
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
    femployee_quotation_detailedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    femployee_quotation_detailedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    femployee_quotation_detailedit.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;
    loadjs.done("femployee_quotation_detailedit");
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
<?php if (!$Page->IsModal) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="femployee_quotation_detailedit" id="femployee_quotation_detailedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_quotation_detail">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "employee_quotation") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="employee_quotation">
<input type="hidden" name="fk_quotation_id" value="<?= HtmlEncode($Page->quotation_id->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <div id="r_employee_username" class="form-group row">
        <label id="elh_employee_quotation_detail_employee_username" for="x_employee_username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->employee_username->caption() ?><?= $Page->employee_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->employee_username->cellAttributes() ?>>
<span id="el_employee_quotation_detail_employee_username">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="employee_quotation_detail_x_employee_username"
        data-table="employee_quotation_detail"
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
    var el = document.querySelector("select[data-select2-id='employee_quotation_detail_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "employee_quotation_detail_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.employee_quotation_detail.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
    <div id="r_rate" class="form-group row">
        <label id="elh_employee_quotation_detail_rate" for="x_rate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rate->caption() ?><?= $Page->rate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rate->cellAttributes() ?>>
<span id="el_employee_quotation_detail_rate">
<input type="<?= $Page->rate->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_rate" name="x_rate" id="x_rate" size="30" placeholder="<?= HtmlEncode($Page->rate->getPlaceHolder()) ?>" value="<?= $Page->rate->EditValue ?>"<?= $Page->rate->editAttributes() ?> aria-describedby="x_rate_help">
<?= $Page->rate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rate->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
    <div id="r_qty" class="form-group row">
        <label id="elh_employee_quotation_detail_qty" for="x_qty" class="<?= $Page->LeftColumnClass ?>"><?= $Page->qty->caption() ?><?= $Page->qty->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->qty->cellAttributes() ?>>
<span id="el_employee_quotation_detail_qty">
<input type="<?= $Page->qty->getInputTextType() ?>" data-table="employee_quotation_detail" data-field="x_qty" name="x_qty" id="x_qty" size="30" placeholder="<?= HtmlEncode($Page->qty->getPlaceHolder()) ?>" value="<?= $Page->qty->EditValue ?>"<?= $Page->qty->editAttributes() ?> aria-describedby="x_qty_help">
<?= $Page->qty->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->qty->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->Total->Visible) { // Total ?>
    <div id="r_Total" class="form-group row">
        <label id="elh_employee_quotation_detail_Total" for="x_Total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->Total->caption() ?><?= $Page->Total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->Total->cellAttributes() ?>>
<span id="el_employee_quotation_detail_Total">
<span<?= $Page->Total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->Total->getDisplayValue($Page->Total->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="employee_quotation_detail" data-field="x_Total" data-hidden="1" name="x_Total" id="x_Total" value="<?= HtmlEncode($Page->Total->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if ($Page->quotation_id->getSessionValue() != "") { ?>
<input type="hidden" id="x_quotation_id" name="x_quotation_id" value="<?= HtmlEncode($Page->quotation_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_employee_quotation_detail_quotation_id">
<input type="hidden" data-table="employee_quotation_detail" data-field="x_quotation_id" data-hidden="1" name="x_quotation_id" id="x_quotation_id" value="<?= HtmlEncode($Page->quotation_id->CurrentValue) ?>">
</span>
<?php } ?>
<span id="el_employee_quotation_detail_detail_id">
<input type="hidden" data-table="employee_quotation_detail" data-field="x_detail_id" data-hidden="1" name="x_detail_id" id="x_detail_id" value="<?= HtmlEncode($Page->detail_id->CurrentValue) ?>">
</span>
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
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
    ew.addEventHandlers("employee_quotation_detail");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
