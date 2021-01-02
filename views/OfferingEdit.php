<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingEdit = &$Page;
?>
<script>
if (!ew.vars.tables.offering) ew.vars.tables.offering = <?= JsonEncode(GetClientVar("tables", "offering")) ?>;
var currentForm, currentPageID;
var fofferingedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fofferingedit = currentForm = new ew.Form("fofferingedit", "edit");

    // Add fields
    var fields = ew.vars.tables.offering.fields;
    fofferingedit.addFields([
        ["offering_id", [fields.offering_id.required ? ew.Validators.required(fields.offering_id.caption) : null], fields.offering_id.isInvalid],
        ["offering_no", [fields.offering_no.required ? ew.Validators.required(fields.offering_no.caption) : null], fields.offering_no.isInvalid],
        ["customer_id", [fields.customer_id.required ? ew.Validators.required(fields.customer_id.caption) : null], fields.customer_id.isInvalid],
        ["offering_date", [fields.offering_date.required ? ew.Validators.required(fields.offering_date.caption) : null, ew.Validators.datetime(0)], fields.offering_date.isInvalid],
        ["offering_term", [fields.offering_term.required ? ew.Validators.required(fields.offering_term.caption) : null], fields.offering_term.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fofferingedit,
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
    fofferingedit.validate = function () {
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
    fofferingedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fofferingedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fofferingedit.lists.customer_id = <?= $Page->customer_id->toClientList($Page) ?>;
    loadjs.done("fofferingedit");
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
<form name="fofferingedit" id="fofferingedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="offering">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->offering_no->Visible) { // offering_no ?>
    <div id="r_offering_no" class="form-group row">
        <label id="elh_offering_offering_no" for="x_offering_no" class="<?= $Page->LeftColumnClass ?>"><?= $Page->offering_no->caption() ?><?= $Page->offering_no->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->offering_no->cellAttributes() ?>>
<span id="el_offering_offering_no">
<input type="<?= $Page->offering_no->getInputTextType() ?>" data-table="offering" data-field="x_offering_no" data-page="1" name="x_offering_no" id="x_offering_no" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->offering_no->getPlaceHolder()) ?>" value="<?= $Page->offering_no->EditValue ?>"<?= $Page->offering_no->editAttributes() ?> aria-describedby="x_offering_no_help">
<?= $Page->offering_no->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->offering_no->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->customer_id->Visible) { // customer_id ?>
    <div id="r_customer_id" class="form-group row">
        <label id="elh_offering_customer_id" for="x_customer_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->customer_id->caption() ?><?= $Page->customer_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->customer_id->cellAttributes() ?>>
<span id="el_offering_customer_id">
    <select
        id="x_customer_id"
        name="x_customer_id"
        class="form-control ew-select<?= $Page->customer_id->isInvalidClass() ?>"
        data-select2-id="offering_x_customer_id"
        data-table="offering"
        data-field="x_customer_id"
        data-page="1"
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
    var el = document.querySelector("select[data-select2-id='offering_x_customer_id']"),
        options = { name: "x_customer_id", selectId: "offering_x_customer_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.offering.fields.customer_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->offering_date->Visible) { // offering_date ?>
    <div id="r_offering_date" class="form-group row">
        <label id="elh_offering_offering_date" for="x_offering_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->offering_date->caption() ?><?= $Page->offering_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->offering_date->cellAttributes() ?>>
<span id="el_offering_offering_date">
<input type="<?= $Page->offering_date->getInputTextType() ?>" data-table="offering" data-field="x_offering_date" data-page="1" name="x_offering_date" id="x_offering_date" placeholder="<?= HtmlEncode($Page->offering_date->getPlaceHolder()) ?>" value="<?= $Page->offering_date->EditValue ?>"<?= $Page->offering_date->editAttributes() ?> aria-describedby="x_offering_date_help">
<?= $Page->offering_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->offering_date->getErrorMessage() ?></div>
<?php if (!$Page->offering_date->ReadOnly && !$Page->offering_date->Disabled && !isset($Page->offering_date->EditAttrs["readonly"]) && !isset($Page->offering_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fofferingedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fofferingedit", "x_offering_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->offering_term->Visible) { // offering_term ?>
    <div id="r_offering_term" class="form-group row">
        <label id="elh_offering_offering_term" for="x_offering_term" class="<?= $Page->LeftColumnClass ?>"><?= $Page->offering_term->caption() ?><?= $Page->offering_term->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->offering_term->cellAttributes() ?>>
<span id="el_offering_offering_term">
<textarea data-table="offering" data-field="x_offering_term" data-page="1" name="x_offering_term" id="x_offering_term" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->offering_term->getPlaceHolder()) ?>"<?= $Page->offering_term->editAttributes() ?> aria-describedby="x_offering_term_help"><?= $Page->offering_term->EditValue ?></textarea>
<?= $Page->offering_term->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->offering_term->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<span id="el_offering_offering_id">
<input type="hidden" data-table="offering" data-field="x_offering_id" data-hidden="1" data-page="1" name="x_offering_id" id="x_offering_id" value="<?= HtmlEncode($Page->offering_id->CurrentValue) ?>">
</span>
<?php
    if (in_array("offering_detail", explode(",", $Page->getCurrentDetailTable())) && $offering_detail->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("offering_detail", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "OfferingDetailGrid.php" ?>
<?php } ?>
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
    ew.addEventHandlers("offering");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
