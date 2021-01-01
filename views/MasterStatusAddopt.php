<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterStatusAddopt = &$Page;
?>
<script>
if (!ew.vars.tables.master_status) ew.vars.tables.master_status = <?= JsonEncode(GetClientVar("tables", "master_status")) ?>;
var currentForm, currentPageID;
var fmaster_statusaddopt;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "addopt";
    fmaster_statusaddopt = currentForm = new ew.Form("fmaster_statusaddopt", "addopt");

    // Add fields
    var fields = ew.vars.tables.master_status.fields;
    fmaster_statusaddopt.addFields([
        ["status", [fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
        ["description", [fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_statusaddopt,
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
    fmaster_statusaddopt.validate = function () {
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
    fmaster_statusaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_statusaddopt.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_statusaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fmaster_statusaddopt" id="fmaster_statusaddopt" class="ew-form ew-horizontal" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="master_status">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->status->Visible) { // status ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_status"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->status->getInputTextType() ?>" data-table="master_status" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" value="<?= $Page->status->EditValue ?>"<?= $Page->status->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_description"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<textarea data-table="master_status" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?>><?= $Page->description->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
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
    ew.addEventHandlers("master_status");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
