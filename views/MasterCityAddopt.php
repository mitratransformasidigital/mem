<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterCityAddopt = &$Page;
?>
<script>
if (!ew.vars.tables.master_city) ew.vars.tables.master_city = <?= JsonEncode(GetClientVar("tables", "master_city")) ?>;
var currentForm, currentPageID;
var fmaster_cityaddopt;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "addopt";
    fmaster_cityaddopt = currentForm = new ew.Form("fmaster_cityaddopt", "addopt");

    // Add fields
    var fields = ew.vars.tables.master_city.fields;
    fmaster_cityaddopt.addFields([
        ["province_id", [fields.province_id.required ? ew.Validators.required(fields.province_id.caption) : null], fields.province_id.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["city", [fields.city.required ? ew.Validators.required(fields.city.caption) : null], fields.city.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_cityaddopt,
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
    fmaster_cityaddopt.validate = function () {
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
    fmaster_cityaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_cityaddopt.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_cityaddopt.lists.province_id = <?= $Page->province_id->toClientList($Page) ?>;
    loadjs.done("fmaster_cityaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fmaster_cityaddopt" id="fmaster_cityaddopt" class="ew-form ew-horizontal" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="master_city">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->province_id->Visible) { // province_id ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_province_id"><?= $Page->province_id->caption() ?><?= $Page->province_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
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
</div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_city_id"><?= $Page->city_id->caption() ?><?= $Page->city_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->city_id->getInputTextType() ?>" data-table="master_city" data-field="x_city_id" name="x_city_id" id="x_city_id" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>" value="<?= $Page->city_id->EditValue ?>"<?= $Page->city_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->city_id->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->city->Visible) { // city ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_city"><?= $Page->city->caption() ?><?= $Page->city->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->city->getInputTextType() ?>" data-table="master_city" data-field="x_city" name="x_city" id="x_city" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->city->getPlaceHolder()) ?>" value="<?= $Page->city->EditValue ?>"<?= $Page->city->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->city->getErrorMessage() ?></div>
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
    ew.addEventHandlers("master_city");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
