<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.offering) ew.vars.tables.offering = <?= JsonEncode(GetClientVar("tables", "offering")) ?>;
var currentForm, currentPageID;
var fofferingupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fofferingupdate = currentForm = new ew.Form("fofferingupdate", "update");

    // Add fields
    var fields = ew.vars.tables.offering.fields;
    fofferingupdate.addFields([
        ["offering_no", [fields.offering_no.required ? ew.Validators.required(fields.offering_no.caption) : null], fields.offering_no.isInvalid],
        ["customer_id", [fields.customer_id.required ? ew.Validators.required(fields.customer_id.caption) : null], fields.customer_id.isInvalid],
        ["offering_date", [fields.offering_date.required ? ew.Validators.required(fields.offering_date.caption) : null, ew.Validators.datetime(0), ew.Validators.selected], fields.offering_date.isInvalid],
        ["offering_term", [fields.offering_term.required ? ew.Validators.required(fields.offering_term.caption) : null], fields.offering_term.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fofferingupdate,
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
    fofferingupdate.validate = function () {
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
    fofferingupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fofferingupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fofferingupdate.lists.customer_id = <?= $Page->customer_id->toClientList($Page) ?>;
    loadjs.done("fofferingupdate");
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
<form name="fofferingupdate" id="fofferingupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="offering">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_offeringupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->offering_no->Visible && (!$Page->isConfirm() || $Page->offering_no->multiUpdateSelected())) { // offering_no ?>
    <div id="r_offering_no" class="form-group row">
        <label for="x_offering_no" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_offering_no" id="u_offering_no" class="custom-control-input ew-multi-select" value="1"<?= $Page->offering_no->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_offering_no"><?= $Page->offering_no->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->offering_no->cellAttributes() ?>>
                <span id="el_offering_offering_no">
                <input type="<?= $Page->offering_no->getInputTextType() ?>" data-table="offering" data-field="x_offering_no" name="x_offering_no" id="x_offering_no" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->offering_no->getPlaceHolder()) ?>" value="<?= $Page->offering_no->EditValue ?>"<?= $Page->offering_no->editAttributes() ?> aria-describedby="x_offering_no_help">
                <?= $Page->offering_no->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->offering_no->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->customer_id->Visible && (!$Page->isConfirm() || $Page->customer_id->multiUpdateSelected())) { // customer_id ?>
    <div id="r_customer_id" class="form-group row">
        <label for="x_customer_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_customer_id" id="u_customer_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->customer_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_customer_id"><?= $Page->customer_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->customer_id->cellAttributes() ?>>
                <span id="el_offering_customer_id">
                    <select
                        id="x_customer_id"
                        name="x_customer_id"
                        class="form-control ew-select<?= $Page->customer_id->isInvalidClass() ?>"
                        data-select2-id="offering_x_customer_id"
                        data-table="offering"
                        data-field="x_customer_id"
                        data-value-separator="<?= $Page->customer_id->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->customer_id->getPlaceHolder()) ?>"
                        <?= $Page->customer_id->editAttributes() ?>>
                        <?= $Page->customer_id->selectOptionListHtml("x_customer_id") ?>
                    </select>
                    <?= $Page->customer_id->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->customer_id->getErrorMessage() ?></div>
                <?= $Page->customer_id->Lookup->getParamTag($Page, "p_x_customer_id") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='offering_x_customer_id']"),
                        options = { name: "x_customer_id", selectId: "offering_x_customer_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.offering.fields.customer_id.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->offering_date->Visible && (!$Page->isConfirm() || $Page->offering_date->multiUpdateSelected())) { // offering_date ?>
    <div id="r_offering_date" class="form-group row">
        <label for="x_offering_date" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_offering_date" id="u_offering_date" class="custom-control-input ew-multi-select" value="1"<?= $Page->offering_date->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_offering_date"><?= $Page->offering_date->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->offering_date->cellAttributes() ?>>
                <span id="el_offering_offering_date">
                <input type="<?= $Page->offering_date->getInputTextType() ?>" data-table="offering" data-field="x_offering_date" name="x_offering_date" id="x_offering_date" placeholder="<?= HtmlEncode($Page->offering_date->getPlaceHolder()) ?>" value="<?= $Page->offering_date->EditValue ?>"<?= $Page->offering_date->editAttributes() ?> aria-describedby="x_offering_date_help">
                <?= $Page->offering_date->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->offering_date->getErrorMessage() ?></div>
                <?php if (!$Page->offering_date->ReadOnly && !$Page->offering_date->Disabled && !isset($Page->offering_date->EditAttrs["readonly"]) && !isset($Page->offering_date->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fofferingupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("fofferingupdate", "x_offering_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->offering_term->Visible && (!$Page->isConfirm() || $Page->offering_term->multiUpdateSelected())) { // offering_term ?>
    <div id="r_offering_term" class="form-group row">
        <label for="x_offering_term" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_offering_term" id="u_offering_term" class="custom-control-input ew-multi-select" value="1"<?= $Page->offering_term->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_offering_term"><?= $Page->offering_term->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->offering_term->cellAttributes() ?>>
                <span id="el_offering_offering_term">
                <textarea data-table="offering" data-field="x_offering_term" name="x_offering_term" id="x_offering_term" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->offering_term->getPlaceHolder()) ?>"<?= $Page->offering_term->editAttributes() ?> aria-describedby="x_offering_term_help"><?= $Page->offering_term->EditValue ?></textarea>
                <?= $Page->offering_term->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->offering_term->getErrorMessage() ?></div>
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
    ew.addEventHandlers("offering");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
