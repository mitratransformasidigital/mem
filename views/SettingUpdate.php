<?php

namespace MEM\prjMitralPHP;

// Page object
$SettingUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.setting) ew.vars.tables.setting = <?= JsonEncode(GetClientVar("tables", "setting")) ?>;
var currentForm, currentPageID;
var fsettingupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fsettingupdate = currentForm = new ew.Form("fsettingupdate", "update");

    // Add fields
    var fields = ew.vars.tables.setting.fields;
    fsettingupdate.addFields([
        ["setting_name", [fields.setting_name.required ? ew.Validators.required(fields.setting_name.caption) : null], fields.setting_name.isInvalid],
        ["setting_value", [fields.setting_value.required ? ew.Validators.required(fields.setting_value.caption) : null], fields.setting_value.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fsettingupdate,
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
    fsettingupdate.validate = function () {
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
    fsettingupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsettingupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fsettingupdate");
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
<form name="fsettingupdate" id="fsettingupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="setting">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_settingupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->setting_name->Visible && (!$Page->isConfirm() || $Page->setting_name->multiUpdateSelected())) { // setting_name ?>
    <div id="r_setting_name" class="form-group row">
        <label for="x_setting_name" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_setting_name" id="u_setting_name" class="custom-control-input ew-multi-select" value="1"<?= $Page->setting_name->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_setting_name"><?= $Page->setting_name->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->setting_name->cellAttributes() ?>>
                <span id="el_setting_setting_name">
                <input type="<?= $Page->setting_name->getInputTextType() ?>" data-table="setting" data-field="x_setting_name" name="x_setting_name" id="x_setting_name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->setting_name->getPlaceHolder()) ?>" value="<?= $Page->setting_name->EditValue ?>"<?= $Page->setting_name->editAttributes() ?> aria-describedby="x_setting_name_help">
                <?= $Page->setting_name->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->setting_name->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->setting_value->Visible && (!$Page->isConfirm() || $Page->setting_value->multiUpdateSelected())) { // setting_value ?>
    <div id="r_setting_value" class="form-group row">
        <label for="x_setting_value" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_setting_value" id="u_setting_value" class="custom-control-input ew-multi-select" value="1"<?= $Page->setting_value->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_setting_value"><?= $Page->setting_value->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->setting_value->cellAttributes() ?>>
                <span id="el_setting_setting_value">
                <textarea data-table="setting" data-field="x_setting_value" name="x_setting_value" id="x_setting_value" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->setting_value->getPlaceHolder()) ?>"<?= $Page->setting_value->editAttributes() ?> aria-describedby="x_setting_value_help"><?= $Page->setting_value->EditValue ?></textarea>
                <?= $Page->setting_value->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->setting_value->getErrorMessage() ?></div>
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
    ew.addEventHandlers("setting");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
