<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterPositionAddopt = &$Page;
?>
<script>
if (!ew.vars.tables.master_position) ew.vars.tables.master_position = <?= JsonEncode(GetClientVar("tables", "master_position")) ?>;
var currentForm, currentPageID;
var fmaster_positionaddopt;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "addopt";
    fmaster_positionaddopt = currentForm = new ew.Form("fmaster_positionaddopt", "addopt");

    // Add fields
    var fields = ew.vars.tables.master_position.fields;
    fmaster_positionaddopt.addFields([
        ["position", [fields.position.required ? ew.Validators.required(fields.position.caption) : null], fields.position.isInvalid],
        ["description", [fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_positionaddopt,
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
    fmaster_positionaddopt.validate = function () {
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
    fmaster_positionaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_positionaddopt.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_positionaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fmaster_positionaddopt" id="fmaster_positionaddopt" class="ew-form ew-horizontal" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="master_position">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->position->Visible) { // position ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_position"><?= $Page->position->caption() ?><?= $Page->position->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->position->getInputTextType() ?>" data-table="master_position" data-field="x_position" name="x_position" id="x_position" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->position->getPlaceHolder()) ?>" value="<?= $Page->position->EditValue ?>"<?= $Page->position->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->position->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_description"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<textarea data-table="master_position" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?>><?= $Page->description->EditValue ?></textarea>
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
    ew.addEventHandlers("master_position");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
