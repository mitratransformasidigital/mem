<?php

namespace MEM\prjMitralPHP;

// Page object
$SettingAdd = &$Page;
?>
<script>
if (!ew.vars.tables.setting) ew.vars.tables.setting = <?= JsonEncode(GetClientVar("tables", "setting")) ?>;
var currentForm, currentPageID;
var fsettingadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fsettingadd = currentForm = new ew.Form("fsettingadd", "add");

    // Add fields
    var fields = ew.vars.tables.setting.fields;
    fsettingadd.addFields([
        ["setting_name", [fields.setting_name.required ? ew.Validators.required(fields.setting_name.caption) : null], fields.setting_name.isInvalid],
        ["setting_value", [fields.setting_value.required ? ew.Validators.required(fields.setting_value.caption) : null], fields.setting_value.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fsettingadd,
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
    fsettingadd.validate = function () {
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
    fsettingadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsettingadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fsettingadd");
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
<form name="fsettingadd" id="fsettingadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="setting">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->setting_name->Visible) { // setting_name ?>
    <div id="r_setting_name" class="form-group row">
        <label id="elh_setting_setting_name" for="x_setting_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->setting_name->caption() ?><?= $Page->setting_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->setting_name->cellAttributes() ?>>
<span id="el_setting_setting_name">
<input type="<?= $Page->setting_name->getInputTextType() ?>" data-table="setting" data-field="x_setting_name" data-page="1" name="x_setting_name" id="x_setting_name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->setting_name->getPlaceHolder()) ?>" value="<?= $Page->setting_name->EditValue ?>"<?= $Page->setting_name->editAttributes() ?> aria-describedby="x_setting_name_help">
<?= $Page->setting_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->setting_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->setting_value->Visible) { // setting_value ?>
    <div id="r_setting_value" class="form-group row">
        <label id="elh_setting_setting_value" for="x_setting_value" class="<?= $Page->LeftColumnClass ?>"><?= $Page->setting_value->caption() ?><?= $Page->setting_value->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->setting_value->cellAttributes() ?>>
<span id="el_setting_setting_value">
<textarea data-table="setting" data-field="x_setting_value" data-page="1" name="x_setting_value" id="x_setting_value" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->setting_value->getPlaceHolder()) ?>"<?= $Page->setting_value->editAttributes() ?> aria-describedby="x_setting_value_help"><?= $Page->setting_value->EditValue ?></textarea>
<?= $Page->setting_value->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->setting_value->getErrorMessage() ?></div>
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
    ew.addEventHandlers("setting");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
