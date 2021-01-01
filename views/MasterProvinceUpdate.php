<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterProvinceUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.master_province) ew.vars.tables.master_province = <?= JsonEncode(GetClientVar("tables", "master_province")) ?>;
var currentForm, currentPageID;
var fmaster_provinceupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fmaster_provinceupdate = currentForm = new ew.Form("fmaster_provinceupdate", "update");

    // Add fields
    var fields = ew.vars.tables.master_province.fields;
    fmaster_provinceupdate.addFields([
        ["province_id", [fields.province_id.required ? ew.Validators.required(fields.province_id.caption) : null], fields.province_id.isInvalid],
        ["province", [fields.province.required ? ew.Validators.required(fields.province.caption) : null], fields.province.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_provinceupdate,
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
    fmaster_provinceupdate.validate = function () {
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
    fmaster_provinceupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_provinceupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_provinceupdate");
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
<form name="fmaster_provinceupdate" id="fmaster_provinceupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_province">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_master_provinceupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->province_id->Visible && (!$Page->isConfirm() || $Page->province_id->multiUpdateSelected())) { // province_id ?>
    <div id="r_province_id" class="form-group row">
        <label for="x_province_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_province_id" id="u_province_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->province_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_province_id"><?= $Page->province_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->province_id->cellAttributes() ?>>
                <input type="<?= $Page->province_id->getInputTextType() ?>" data-table="master_province" data-field="x_province_id" name="x_province_id" id="x_province_id" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->province_id->getPlaceHolder()) ?>" value="<?= $Page->province_id->EditValue ?>"<?= $Page->province_id->editAttributes() ?> aria-describedby="x_province_id_help">
                <?= $Page->province_id->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->province_id->getErrorMessage() ?></div>
                <input type="hidden" data-table="master_province" data-field="x_province_id" data-hidden="1" name="o_province_id" id="o_province_id" value="<?= HtmlEncode($Page->province_id->OldValue ?? $Page->province_id->CurrentValue) ?>">
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->province->Visible && (!$Page->isConfirm() || $Page->province->multiUpdateSelected())) { // province ?>
    <div id="r_province" class="form-group row">
        <label for="x_province" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_province" id="u_province" class="custom-control-input ew-multi-select" value="1"<?= $Page->province->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_province"><?= $Page->province->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->province->cellAttributes() ?>>
                <span id="el_master_province_province">
                <input type="<?= $Page->province->getInputTextType() ?>" data-table="master_province" data-field="x_province" name="x_province" id="x_province" size="30" maxlength="75" placeholder="<?= HtmlEncode($Page->province->getPlaceHolder()) ?>" value="<?= $Page->province->EditValue ?>"<?= $Page->province->editAttributes() ?> aria-describedby="x_province_help">
                <?= $Page->province->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->province->getErrorMessage() ?></div>
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
    ew.addEventHandlers("master_province");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
