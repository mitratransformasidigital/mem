<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterCityAdd = &$Page;
?>
<script>
if (!ew.vars.tables.master_city) ew.vars.tables.master_city = <?= JsonEncode(GetClientVar("tables", "master_city")) ?>;
var currentForm, currentPageID;
var fmaster_cityadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fmaster_cityadd = currentForm = new ew.Form("fmaster_cityadd", "add");

    // Add fields
    var fields = ew.vars.tables.master_city.fields;
    fmaster_cityadd.addFields([
        ["province_id", [fields.province_id.required ? ew.Validators.required(fields.province_id.caption) : null], fields.province_id.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["city", [fields.city.required ? ew.Validators.required(fields.city.caption) : null], fields.city.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_cityadd,
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
    fmaster_cityadd.validate = function () {
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
    fmaster_cityadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_cityadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_cityadd.lists.province_id = <?= $Page->province_id->toClientList($Page) ?>;
    loadjs.done("fmaster_cityadd");
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
<form name="fmaster_cityadd" id="fmaster_cityadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_city">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "master_province") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_province">
<input type="hidden" name="fk_province_id" value="<?= HtmlEncode($Page->province_id->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->province_id->Visible) { // province_id ?>
    <div id="r_province_id" class="form-group row">
        <label id="elh_master_city_province_id" for="x_province_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->province_id->caption() ?><?= $Page->province_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->province_id->cellAttributes() ?>>
<?php if ($Page->province_id->getSessionValue() != "") { ?>
<span id="el_master_city_province_id">
<span<?= $Page->province_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->province_id->getDisplayValue($Page->province_id->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_province_id" name="x_province_id" value="<?= HtmlEncode($Page->province_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_master_city_province_id">
<div class="input-group flex-nowrap">
    <select
        id="x_province_id"
        name="x_province_id"
        class="form-control ew-select<?= $Page->province_id->isInvalidClass() ?>"
        data-select2-id="master_city_x_province_id"
        data-table="master_city"
        data-field="x_province_id"
        data-value-separator="<?= $Page->province_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->province_id->getPlaceHolder()) ?>"
        <?= $Page->province_id->editAttributes() ?>>
        <?= $Page->province_id->selectOptionListHtml("x_province_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_province") && !$Page->province_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_province_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->province_id->caption() ?>" data-title="<?= $Page->province_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_province_id',url:'<?= GetUrl("masterprovinceaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->province_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->province_id->getErrorMessage() ?></div>
<?= $Page->province_id->Lookup->getParamTag($Page, "p_x_province_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_city_x_province_id']"),
        options = { name: "x_province_id", selectId: "master_city_x_province_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_city.fields.province_id.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <div id="r_city_id" class="form-group row">
        <label id="elh_master_city_city_id" for="x_city_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->city_id->caption() ?><?= $Page->city_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->city_id->cellAttributes() ?>>
<span id="el_master_city_city_id">
<input type="<?= $Page->city_id->getInputTextType() ?>" data-table="master_city" data-field="x_city_id" name="x_city_id" id="x_city_id" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>" value="<?= $Page->city_id->EditValue ?>"<?= $Page->city_id->editAttributes() ?> aria-describedby="x_city_id_help">
<?= $Page->city_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->city_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->city->Visible) { // city ?>
    <div id="r_city" class="form-group row">
        <label id="elh_master_city_city" for="x_city" class="<?= $Page->LeftColumnClass ?>"><?= $Page->city->caption() ?><?= $Page->city->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->city->cellAttributes() ?>>
<span id="el_master_city_city">
<input type="<?= $Page->city->getInputTextType() ?>" data-table="master_city" data-field="x_city" name="x_city" id="x_city" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->city->getPlaceHolder()) ?>" value="<?= $Page->city->EditValue ?>"<?= $Page->city->editAttributes() ?> aria-describedby="x_city_help">
<?= $Page->city->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->city->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("master_office", explode(",", $Page->getCurrentDetailTable())) && $master_office->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("master_office", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "MasterOfficeGrid.php" ?>
<?php } ?>
<?php
    if (in_array("myprofile", explode(",", $Page->getCurrentDetailTable())) && $myprofile->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("myprofile", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "MyprofileGrid.php" ?>
<?php } ?>
<?php
    if (in_array("customer", explode(",", $Page->getCurrentDetailTable())) && $customer->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("customer", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "CustomerGrid.php" ?>
<?php } ?>
<?php
    if (in_array("employee", explode(",", $Page->getCurrentDetailTable())) && $employee->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("employee", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EmployeeGrid.php" ?>
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
    ew.addEventHandlers("master_city");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
