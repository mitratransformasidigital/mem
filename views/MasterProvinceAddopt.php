<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterProvinceAddopt = &$Page;
?>
<script>
if (!ew.vars.tables.master_province) ew.vars.tables.master_province = <?= JsonEncode(GetClientVar("tables", "master_province")) ?>;
var currentForm, currentPageID;
var fmaster_provinceaddopt;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "addopt";
    fmaster_provinceaddopt = currentForm = new ew.Form("fmaster_provinceaddopt", "addopt");

    // Add fields
    var fields = ew.vars.tables.master_province.fields;
    fmaster_provinceaddopt.addFields([
        ["province_id", [fields.province_id.required ? ew.Validators.required(fields.province_id.caption) : null], fields.province_id.isInvalid],
        ["province", [fields.province.required ? ew.Validators.required(fields.province.caption) : null], fields.province.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_provinceaddopt,
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
    fmaster_provinceaddopt.validate = function () {
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
        return true;
    }

    // Form_CustomValidate
    fmaster_provinceaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_provinceaddopt.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_provinceaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fmaster_provinceaddopt" id="fmaster_provinceaddopt" class="ew-form ew-horizontal" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="master_province">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->province_id->Visible) { // province_id ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_province_id"><?= $Page->province_id->caption() ?><?= $Page->province_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->province_id->getInputTextType() ?>" data-table="master_province" data-field="x_province_id" name="x_province_id" id="x_province_id" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->province_id->getPlaceHolder()) ?>" value="<?= $Page->province_id->EditValue ?>"<?= $Page->province_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->province_id->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->province->Visible) { // province ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_province"><?= $Page->province->caption() ?><?= $Page->province->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->province->getInputTextType() ?>" data-table="master_province" data-field="x_province" name="x_province" id="x_province" size="30" maxlength="75" placeholder="<?= HtmlEncode($Page->province->getPlaceHolder()) ?>" value="<?= $Page->province->EditValue ?>"<?= $Page->province->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->province->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("master_province");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
