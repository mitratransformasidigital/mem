<?php

namespace MEM\prjMitralPHP;

// Page object
$CustomerUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.customer) ew.vars.tables.customer = <?= JsonEncode(GetClientVar("tables", "customer")) ?>;
var currentForm, currentPageID;
var fcustomerupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fcustomerupdate = currentForm = new ew.Form("fcustomerupdate", "update");

    // Add fields
    var fields = ew.vars.tables.customer.fields;
    fcustomerupdate.addFields([
        ["customer_name", [fields.customer_name.required ? ew.Validators.required(fields.customer_name.caption) : null], fields.customer_name.isInvalid],
        ["address", [fields.address.required ? ew.Validators.required(fields.address.caption) : null], fields.address.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["phone_number", [fields.phone_number.required ? ew.Validators.required(fields.phone_number.caption) : null], fields.phone_number.isInvalid],
        ["contact", [fields.contact.required ? ew.Validators.required(fields.contact.caption) : null], fields.contact.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fcustomerupdate,
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
    fcustomerupdate.validate = function () {
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
    fcustomerupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fcustomerupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fcustomerupdate.lists.city_id = <?= $Page->city_id->toClientList($Page) ?>;
    loadjs.done("fcustomerupdate");
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
<form name="fcustomerupdate" id="fcustomerupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="customer">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_customerupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->customer_name->Visible && (!$Page->isConfirm() || $Page->customer_name->multiUpdateSelected())) { // customer_name ?>
    <div id="r_customer_name" class="form-group row">
        <label for="x_customer_name" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_customer_name" id="u_customer_name" class="custom-control-input ew-multi-select" value="1"<?= $Page->customer_name->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_customer_name"><?= $Page->customer_name->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->customer_name->cellAttributes() ?>>
                <span id="el_customer_customer_name">
                <input type="<?= $Page->customer_name->getInputTextType() ?>" data-table="customer" data-field="x_customer_name" name="x_customer_name" id="x_customer_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->customer_name->getPlaceHolder()) ?>" value="<?= $Page->customer_name->EditValue ?>"<?= $Page->customer_name->editAttributes() ?> aria-describedby="x_customer_name_help">
                <?= $Page->customer_name->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->customer_name->getErrorMessage() ?></div>
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
                <span id="el_customer_address">
                <textarea data-table="customer" data-field="x_address" name="x_address" id="x_address" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->address->getPlaceHolder()) ?>"<?= $Page->address->editAttributes() ?> aria-describedby="x_address_help"><?= $Page->address->EditValue ?></textarea>
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
                <span id="el_customer_city_id">
                <span<?= $Page->city_id->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->city_id->getDisplayValue($Page->city_id->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_city_id" name="x_city_id" value="<?= HtmlEncode($Page->city_id->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_customer_city_id">
                    <select
                        id="x_city_id"
                        name="x_city_id"
                        class="form-control ew-select<?= $Page->city_id->isInvalidClass() ?>"
                        data-select2-id="customer_x_city_id"
                        data-table="customer"
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
                    var el = document.querySelector("select[data-select2-id='customer_x_city_id']"),
                        options = { name: "x_city_id", selectId: "customer_x_city_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.customer.fields.city_id.selectOptions);
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
                <span id="el_customer_phone_number">
                <input type="<?= $Page->phone_number->getInputTextType() ?>" data-table="customer" data-field="x_phone_number" name="x_phone_number" id="x_phone_number" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->phone_number->getPlaceHolder()) ?>" value="<?= $Page->phone_number->EditValue ?>"<?= $Page->phone_number->editAttributes() ?> aria-describedby="x_phone_number_help">
                <?= $Page->phone_number->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->phone_number->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->contact->Visible && (!$Page->isConfirm() || $Page->contact->multiUpdateSelected())) { // contact ?>
    <div id="r_contact" class="form-group row">
        <label for="x_contact" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_contact" id="u_contact" class="custom-control-input ew-multi-select" value="1"<?= $Page->contact->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_contact"><?= $Page->contact->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->contact->cellAttributes() ?>>
                <span id="el_customer_contact">
                <input type="<?= $Page->contact->getInputTextType() ?>" data-table="customer" data-field="x_contact" name="x_contact" id="x_contact" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->contact->getPlaceHolder()) ?>" value="<?= $Page->contact->EditValue ?>"<?= $Page->contact->editAttributes() ?> aria-describedby="x_contact_help">
                <?= $Page->contact->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->contact->getErrorMessage() ?></div>
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
    ew.addEventHandlers("customer");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
