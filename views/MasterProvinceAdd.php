<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterProvinceAdd = &$Page;
?>
<script>
if (!ew.vars.tables.master_province) ew.vars.tables.master_province = <?= JsonEncode(GetClientVar("tables", "master_province")) ?>;
var currentForm, currentPageID;
var fmaster_provinceadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fmaster_provinceadd = currentForm = new ew.Form("fmaster_provinceadd", "add");

    // Add fields
    var fields = ew.vars.tables.master_province.fields;
    fmaster_provinceadd.addFields([
        ["province_id", [fields.province_id.required ? ew.Validators.required(fields.province_id.caption) : null], fields.province_id.isInvalid],
        ["province", [fields.province.required ? ew.Validators.required(fields.province.caption) : null], fields.province.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_provinceadd,
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
    fmaster_provinceadd.validate = function () {
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
    fmaster_provinceadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_provinceadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_provinceadd");
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
<form name="fmaster_provinceadd" id="fmaster_provinceadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_province">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->province_id->Visible) { // province_id ?>
    <div id="r_province_id" class="form-group row">
        <label id="elh_master_province_province_id" for="x_province_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->province_id->caption() ?><?= $Page->province_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->province_id->cellAttributes() ?>>
<span id="el_master_province_province_id">
<input type="<?= $Page->province_id->getInputTextType() ?>" data-table="master_province" data-field="x_province_id" name="x_province_id" id="x_province_id" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->province_id->getPlaceHolder()) ?>" value="<?= $Page->province_id->EditValue ?>"<?= $Page->province_id->editAttributes() ?> aria-describedby="x_province_id_help">
<?= $Page->province_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->province_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->province->Visible) { // province ?>
    <div id="r_province" class="form-group row">
        <label id="elh_master_province_province" for="x_province" class="<?= $Page->LeftColumnClass ?>"><?= $Page->province->caption() ?><?= $Page->province->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->province->cellAttributes() ?>>
<span id="el_master_province_province">
<input type="<?= $Page->province->getInputTextType() ?>" data-table="master_province" data-field="x_province" name="x_province" id="x_province" size="30" maxlength="75" placeholder="<?= HtmlEncode($Page->province->getPlaceHolder()) ?>" value="<?= $Page->province->EditValue ?>"<?= $Page->province->editAttributes() ?> aria-describedby="x_province_help">
<?= $Page->province->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->province->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("master_city", explode(",", $Page->getCurrentDetailTable())) && $master_city->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("master_city", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "MasterCityGrid.php" ?>
<?php } ?>
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
    ew.addEventHandlers("master_province");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
