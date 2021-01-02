<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingDetailAdd = &$Page;
?>
<script>
if (!ew.vars.tables.offering_detail) ew.vars.tables.offering_detail = <?= JsonEncode(GetClientVar("tables", "offering_detail")) ?>;
var currentForm, currentPageID;
var foffering_detailadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    foffering_detailadd = currentForm = new ew.Form("foffering_detailadd", "add");

    // Add fields
    var fields = ew.vars.tables.offering_detail.fields;
    foffering_detailadd.addFields([
        ["offering_id", [fields.offering_id.required ? ew.Validators.required(fields.offering_id.caption) : null, ew.Validators.integer], fields.offering_id.isInvalid],
        ["description", [fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid],
        ["qty", [fields.qty.required ? ew.Validators.required(fields.qty.caption) : null, ew.Validators.integer], fields.qty.isInvalid],
        ["rate", [fields.rate.required ? ew.Validators.required(fields.rate.caption) : null, ew.Validators.integer], fields.rate.isInvalid],
        ["discount", [fields.discount.required ? ew.Validators.required(fields.discount.caption) : null, ew.Validators.integer], fields.discount.isInvalid],
        ["total", [fields.total.required ? ew.Validators.required(fields.total.caption) : null, ew.Validators.float], fields.total.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = foffering_detailadd,
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
    foffering_detailadd.validate = function () {
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
    foffering_detailadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    foffering_detailadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("foffering_detailadd");
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
<form name="foffering_detailadd" id="foffering_detailadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="offering_detail">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "offering") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="offering">
<input type="hidden" name="fk_offering_id" value="<?= HtmlEncode($Page->offering_id->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->offering_id->Visible) { // offering_id ?>
    <div id="r_offering_id" class="form-group row">
        <label id="elh_offering_detail_offering_id" for="x_offering_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->offering_id->caption() ?><?= $Page->offering_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->offering_id->cellAttributes() ?>>
<?php if ($Page->offering_id->getSessionValue() != "") { ?>
<span id="el_offering_detail_offering_id">
<span<?= $Page->offering_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->offering_id->getDisplayValue($Page->offering_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_offering_id" name="x_offering_id" value="<?= HtmlEncode($Page->offering_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_offering_detail_offering_id">
<input type="<?= $Page->offering_id->getInputTextType() ?>" data-table="offering_detail" data-field="x_offering_id" name="x_offering_id" id="x_offering_id" size="30" placeholder="<?= HtmlEncode($Page->offering_id->getPlaceHolder()) ?>" value="<?= $Page->offering_id->EditValue ?>"<?= $Page->offering_id->editAttributes() ?> aria-describedby="x_offering_id_help">
<?= $Page->offering_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->offering_id->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description" class="form-group row">
        <label id="elh_offering_detail_description" for="x_description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->description->cellAttributes() ?>>
<span id="el_offering_detail_description">
<textarea data-table="offering_detail" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help"><?= $Page->description->EditValue ?></textarea>
<?= $Page->description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
    <div id="r_qty" class="form-group row">
        <label id="elh_offering_detail_qty" for="x_qty" class="<?= $Page->LeftColumnClass ?>"><?= $Page->qty->caption() ?><?= $Page->qty->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->qty->cellAttributes() ?>>
<span id="el_offering_detail_qty">
<input type="<?= $Page->qty->getInputTextType() ?>" data-table="offering_detail" data-field="x_qty" name="x_qty" id="x_qty" size="30" placeholder="<?= HtmlEncode($Page->qty->getPlaceHolder()) ?>" value="<?= $Page->qty->EditValue ?>"<?= $Page->qty->editAttributes() ?> aria-describedby="x_qty_help">
<?= $Page->qty->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->qty->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
    <div id="r_rate" class="form-group row">
        <label id="elh_offering_detail_rate" for="x_rate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rate->caption() ?><?= $Page->rate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rate->cellAttributes() ?>>
<span id="el_offering_detail_rate">
<input type="<?= $Page->rate->getInputTextType() ?>" data-table="offering_detail" data-field="x_rate" name="x_rate" id="x_rate" size="30" placeholder="<?= HtmlEncode($Page->rate->getPlaceHolder()) ?>" value="<?= $Page->rate->EditValue ?>"<?= $Page->rate->editAttributes() ?> aria-describedby="x_rate_help">
<?= $Page->rate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rate->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->discount->Visible) { // discount ?>
    <div id="r_discount" class="form-group row">
        <label id="elh_offering_detail_discount" for="x_discount" class="<?= $Page->LeftColumnClass ?>"><?= $Page->discount->caption() ?><?= $Page->discount->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->discount->cellAttributes() ?>>
<span id="el_offering_detail_discount">
<input type="<?= $Page->discount->getInputTextType() ?>" data-table="offering_detail" data-field="x_discount" name="x_discount" id="x_discount" size="30" placeholder="<?= HtmlEncode($Page->discount->getPlaceHolder()) ?>" value="<?= $Page->discount->EditValue ?>"<?= $Page->discount->editAttributes() ?> aria-describedby="x_discount_help">
<?= $Page->discount->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->discount->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <div id="r_total" class="form-group row">
        <label id="elh_offering_detail_total" for="x_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total->caption() ?><?= $Page->total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->total->cellAttributes() ?>>
<span id="el_offering_detail_total">
<input type="<?= $Page->total->getInputTextType() ?>" data-table="offering_detail" data-field="x_total" name="x_total" id="x_total" size="30" placeholder="<?= HtmlEncode($Page->total->getPlaceHolder()) ?>" value="<?= $Page->total->EditValue ?>"<?= $Page->total->editAttributes() ?> aria-describedby="x_total_help">
<?= $Page->total->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->total->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
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
    ew.addEventHandlers("offering_detail");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
