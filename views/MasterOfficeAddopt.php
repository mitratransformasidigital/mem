<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterOfficeAddopt = &$Page;
?>
<script>
if (!ew.vars.tables.master_office) ew.vars.tables.master_office = <?= JsonEncode(GetClientVar("tables", "master_office")) ?>;
var currentForm, currentPageID;
var fmaster_officeaddopt;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "addopt";
    fmaster_officeaddopt = currentForm = new ew.Form("fmaster_officeaddopt", "addopt");

    // Add fields
    var fields = ew.vars.tables.master_office.fields;
    fmaster_officeaddopt.addFields([
        ["office", [fields.office.required ? ew.Validators.required(fields.office.caption) : null], fields.office.isInvalid],
        ["address", [fields.address.required ? ew.Validators.required(fields.address.caption) : null], fields.address.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["phone_number", [fields.phone_number.required ? ew.Validators.required(fields.phone_number.caption) : null], fields.phone_number.isInvalid],
        ["contact_name", [fields.contact_name.required ? ew.Validators.required(fields.contact_name.caption) : null], fields.contact_name.isInvalid],
        ["description", [fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_officeaddopt,
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
    fmaster_officeaddopt.validate = function () {
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
    fmaster_officeaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_officeaddopt.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_officeaddopt.lists.city_id = <?= $Page->city_id->toClientList($Page) ?>;
    loadjs.done("fmaster_officeaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fmaster_officeaddopt" id="fmaster_officeaddopt" class="ew-form ew-horizontal" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="master_office">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->office->Visible) { // office ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_office"><?= $Page->office->caption() ?><?= $Page->office->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->office->getInputTextType() ?>" data-table="master_office" data-field="x_office" name="x_office" id="x_office" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->office->getPlaceHolder()) ?>" value="<?= $Page->office->EditValue ?>"<?= $Page->office->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->office->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_address"><?= $Page->address->caption() ?><?= $Page->address->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<textarea data-table="master_office" data-field="x_address" name="x_address" id="x_address" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>"<?= $Page->address->editAttributes() ?>><?= $Page->address->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Page->address->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_city_id"><?= $Page->city_id->caption() ?><?= $Page->city_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<div class="input-group flex-nowrap">
    <select
        id="x_city_id"
        name="x_city_id"
        class="form-control ew-select<?= $Page->city_id->isInvalidClass() ?>"
        data-select2-id="master_office_x_city_id"
        data-table="master_office"
        data-field="x_city_id"
        data-value-separator="<?= $Page->city_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>"
        <?= $Page->city_id->editAttributes() ?>>
        <?= $Page->city_id->selectOptionListHtml("x_city_id") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "master_city") && !$Page->city_id->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_city_id" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->city_id->caption() ?>" data-title="<?= $Page->city_id->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_city_id',url:'<?= GetUrl("mastercityaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->city_id->getErrorMessage() ?></div>
<?= $Page->city_id->Lookup->getParamTag($Page, "p_x_city_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='master_office_x_city_id']"),
        options = { name: "x_city_id", selectId: "master_office_x_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.master_office.fields.city_id.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div>
<?php } ?>
<?php if ($Page->phone_number->Visible) { // phone_number ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_phone_number"><?= $Page->phone_number->caption() ?><?= $Page->phone_number->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->phone_number->getInputTextType() ?>" data-table="master_office" data-field="x_phone_number" name="x_phone_number" id="x_phone_number" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->phone_number->getPlaceHolder()) ?>" value="<?= $Page->phone_number->EditValue ?>"<?= $Page->phone_number->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->phone_number->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->contact_name->Visible) { // contact_name ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_contact_name"><?= $Page->contact_name->caption() ?><?= $Page->contact_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<input type="<?= $Page->contact_name->getInputTextType() ?>" data-table="master_office" data-field="x_contact_name" name="x_contact_name" id="x_contact_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->contact_name->getPlaceHolder()) ?>" value="<?= $Page->contact_name->EditValue ?>"<?= $Page->contact_name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->contact_name->getErrorMessage() ?></div>
</div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label ew-label" for="x_description"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10">
<textarea data-table="master_office" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?>><?= $Page->description->EditValue ?></textarea>
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
    ew.addEventHandlers("master_office");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
