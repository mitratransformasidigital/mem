<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterOfficeUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.master_office) ew.vars.tables.master_office = <?= JsonEncode(GetClientVar("tables", "master_office")) ?>;
var currentForm, currentPageID;
var fmaster_officeupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fmaster_officeupdate = currentForm = new ew.Form("fmaster_officeupdate", "update");

    // Add fields
    var fields = ew.vars.tables.master_office.fields;
    fmaster_officeupdate.addFields([
        ["office", [fields.office.required ? ew.Validators.required(fields.office.caption) : null], fields.office.isInvalid],
        ["address", [fields.address.required ? ew.Validators.required(fields.address.caption) : null], fields.address.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["phone_number", [fields.phone_number.required ? ew.Validators.required(fields.phone_number.caption) : null], fields.phone_number.isInvalid],
        ["contact_name", [fields.contact_name.required ? ew.Validators.required(fields.contact_name.caption) : null], fields.contact_name.isInvalid],
        ["description", [fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_officeupdate,
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
    fmaster_officeupdate.validate = function () {
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
    fmaster_officeupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_officeupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_officeupdate.lists.city_id = <?= $Page->city_id->toClientList($Page) ?>;
    loadjs.done("fmaster_officeupdate");
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
<form name="fmaster_officeupdate" id="fmaster_officeupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_office">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_master_officeupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->office->Visible && (!$Page->isConfirm() || $Page->office->multiUpdateSelected())) { // office ?>
    <div id="r_office" class="form-group row">
        <label for="x_office" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_office" id="u_office" class="custom-control-input ew-multi-select" value="1"<?= $Page->office->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_office"><?= $Page->office->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->office->cellAttributes() ?>>
                <span id="el_master_office_office">
                <input type="<?= $Page->office->getInputTextType() ?>" data-table="master_office" data-field="x_office" name="x_office" id="x_office" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->office->getPlaceHolder()) ?>" value="<?= $Page->office->EditValue ?>"<?= $Page->office->editAttributes() ?> aria-describedby="x_office_help">
                <?= $Page->office->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->office->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->address->Visible && (!$Page->isConfirm() || $Page->address->multiUpdateSelected())) { // address ?>
    <div id="r_address" class="form-group row">
        <label for="x_address" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_address" id="u_address" class="custom-control-input ew-multi-select" value="1"<?= $Page->address->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_address"><?= $Page->address->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->address->cellAttributes() ?>>
                <span id="el_master_office_address">
                <textarea data-table="master_office" data-field="x_address" name="x_address" id="x_address" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>"<?= $Page->address->editAttributes() ?> aria-describedby="x_address_help"><?= $Page->address->EditValue ?></textarea>
                <?= $Page->address->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->address->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->city_id->Visible && (!$Page->isConfirm() || $Page->city_id->multiUpdateSelected())) { // city_id ?>
    <div id="r_city_id" class="form-group row">
        <label for="x_city_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_city_id" id="u_city_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->city_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_city_id"><?= $Page->city_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->city_id->cellAttributes() ?>>
                <?php if ($Page->city_id->getSessionValue() != "") { ?>
                <span id="el_master_office_city_id">
                <span<?= $Page->city_id->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->city_id->getDisplayValue($Page->city_id->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_city_id" name="x_city_id" value="<?= HtmlEncode($Page->city_id->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_master_office_city_id">
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
                    <?= $Page->city_id->getCustomMessage() ?>
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
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->phone_number->Visible && (!$Page->isConfirm() || $Page->phone_number->multiUpdateSelected())) { // phone_number ?>
    <div id="r_phone_number" class="form-group row">
        <label for="x_phone_number" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_phone_number" id="u_phone_number" class="custom-control-input ew-multi-select" value="1"<?= $Page->phone_number->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_phone_number"><?= $Page->phone_number->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->phone_number->cellAttributes() ?>>
                <span id="el_master_office_phone_number">
                <input type="<?= $Page->phone_number->getInputTextType() ?>" data-table="master_office" data-field="x_phone_number" name="x_phone_number" id="x_phone_number" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->phone_number->getPlaceHolder()) ?>" value="<?= $Page->phone_number->EditValue ?>"<?= $Page->phone_number->editAttributes() ?> aria-describedby="x_phone_number_help">
                <?= $Page->phone_number->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->phone_number->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->contact_name->Visible && (!$Page->isConfirm() || $Page->contact_name->multiUpdateSelected())) { // contact_name ?>
    <div id="r_contact_name" class="form-group row">
        <label for="x_contact_name" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_contact_name" id="u_contact_name" class="custom-control-input ew-multi-select" value="1"<?= $Page->contact_name->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_contact_name"><?= $Page->contact_name->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->contact_name->cellAttributes() ?>>
                <span id="el_master_office_contact_name">
                <input type="<?= $Page->contact_name->getInputTextType() ?>" data-table="master_office" data-field="x_contact_name" name="x_contact_name" id="x_contact_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->contact_name->getPlaceHolder()) ?>" value="<?= $Page->contact_name->EditValue ?>"<?= $Page->contact_name->editAttributes() ?> aria-describedby="x_contact_name_help">
                <?= $Page->contact_name->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->contact_name->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible && (!$Page->isConfirm() || $Page->description->multiUpdateSelected())) { // description ?>
    <div id="r_description" class="form-group row">
        <label for="x_description" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_description" id="u_description" class="custom-control-input ew-multi-select" value="1"<?= $Page->description->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_description"><?= $Page->description->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->description->cellAttributes() ?>>
                <span id="el_master_office_description">
                <textarea data-table="master_office" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help"><?= $Page->description->EditValue ?></textarea>
                <?= $Page->description->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
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
    ew.addEventHandlers("master_office");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
