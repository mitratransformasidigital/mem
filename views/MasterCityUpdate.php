<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterCityUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.master_city) ew.vars.tables.master_city = <?= JsonEncode(GetClientVar("tables", "master_city")) ?>;
var currentForm, currentPageID;
var fmaster_cityupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fmaster_cityupdate = currentForm = new ew.Form("fmaster_cityupdate", "update");

    // Add fields
    var fields = ew.vars.tables.master_city.fields;
    fmaster_cityupdate.addFields([
        ["province_id", [fields.province_id.required ? ew.Validators.required(fields.province_id.caption) : null], fields.province_id.isInvalid],
        ["city_id", [fields.city_id.required ? ew.Validators.required(fields.city_id.caption) : null], fields.city_id.isInvalid],
        ["city", [fields.city.required ? ew.Validators.required(fields.city.caption) : null], fields.city.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_cityupdate,
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
    fmaster_cityupdate.validate = function () {
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
    fmaster_cityupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_cityupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_cityupdate.lists.province_id = <?= $Page->province_id->toClientList($Page) ?>;
    loadjs.done("fmaster_cityupdate");
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
<form name="fmaster_cityupdate" id="fmaster_cityupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_city">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_master_cityupdate" class="ew-update-div"><!-- page -->
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
                <?php if ($Page->province_id->getSessionValue() != "") { ?>
                <span id="el_master_city_province_id">
                <span<?= $Page->province_id->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->province_id->getDisplayValue($Page->province_id->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_province_id" name="x_province_id" value="<?= HtmlEncode($Page->province_id->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_master_city_province_id">
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
                <input type="<?= $Page->city_id->getInputTextType() ?>" data-table="master_city" data-field="x_city_id" name="x_city_id" id="x_city_id" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->city_id->getPlaceHolder()) ?>" value="<?= $Page->city_id->EditValue ?>"<?= $Page->city_id->editAttributes() ?> aria-describedby="x_city_id_help">
                <?= $Page->city_id->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->city_id->getErrorMessage() ?></div>
                <input type="hidden" data-table="master_city" data-field="x_city_id" data-hidden="1" name="o_city_id" id="o_city_id" value="<?= HtmlEncode($Page->city_id->OldValue ?? $Page->city_id->CurrentValue) ?>">
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->city->Visible && (!$Page->isConfirm() || $Page->city->multiUpdateSelected())) { // city ?>
    <div id="r_city" class="form-group row">
        <label for="x_city" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_city" id="u_city" class="custom-control-input ew-multi-select" value="1"<?= $Page->city->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_city"><?= $Page->city->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->city->cellAttributes() ?>>
                <span id="el_master_city_city">
                <input type="<?= $Page->city->getInputTextType() ?>" data-table="master_city" data-field="x_city" name="x_city" id="x_city" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->city->getPlaceHolder()) ?>" value="<?= $Page->city->EditValue ?>"<?= $Page->city->editAttributes() ?> aria-describedby="x_city_help">
                <?= $Page->city->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->city->getErrorMessage() ?></div>
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
    ew.addEventHandlers("master_city");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
