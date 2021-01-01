<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterHolidayUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.master_holiday) ew.vars.tables.master_holiday = <?= JsonEncode(GetClientVar("tables", "master_holiday")) ?>;
var currentForm, currentPageID;
var fmaster_holidayupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fmaster_holidayupdate = currentForm = new ew.Form("fmaster_holidayupdate", "update");

    // Add fields
    var fields = ew.vars.tables.master_holiday.fields;
    fmaster_holidayupdate.addFields([
        ["shift_id", [fields.shift_id.required ? ew.Validators.required(fields.shift_id.caption) : null], fields.shift_id.isInvalid],
        ["holiday_date", [fields.holiday_date.required ? ew.Validators.required(fields.holiday_date.caption) : null, ew.Validators.datetime(5), ew.Validators.selected], fields.holiday_date.isInvalid],
        ["holiday_name", [fields.holiday_name.required ? ew.Validators.required(fields.holiday_name.caption) : null], fields.holiday_name.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_holidayupdate,
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
    fmaster_holidayupdate.validate = function () {
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
    fmaster_holidayupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_holidayupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmaster_holidayupdate.lists.shift_id = <?= $Page->shift_id->toClientList($Page) ?>;
    loadjs.done("fmaster_holidayupdate");
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
<form name="fmaster_holidayupdate" id="fmaster_holidayupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_holiday">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_master_holidayupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->shift_id->Visible && (!$Page->isConfirm() || $Page->shift_id->multiUpdateSelected())) { // shift_id ?>
    <div id="r_shift_id" class="form-group row">
        <label for="x_shift_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_shift_id" id="u_shift_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->shift_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_shift_id"><?= $Page->shift_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->shift_id->cellAttributes() ?>>
                <?php if ($Page->shift_id->getSessionValue() != "") { ?>
                <span id="el_master_holiday_shift_id">
                <span<?= $Page->shift_id->viewAttributes() ?>>
                <input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->shift_id->getDisplayValue($Page->shift_id->ViewValue))) ?>"></span>
                </span>
                <input type="hidden" id="x_shift_id" name="x_shift_id" value="<?= HtmlEncode($Page->shift_id->CurrentValue) ?>" data-hidden="1">
                <?php } else { ?>
                <span id="el_master_holiday_shift_id">
                    <select
                        id="x_shift_id"
                        name="x_shift_id"
                        class="form-control ew-select<?= $Page->shift_id->isInvalidClass() ?>"
                        data-select2-id="master_holiday_x_shift_id"
                        data-table="master_holiday"
                        data-field="x_shift_id"
                        data-value-separator="<?= $Page->shift_id->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->shift_id->getPlaceHolder()) ?>"
                        <?= $Page->shift_id->editAttributes() ?>>
                        <?= $Page->shift_id->selectOptionListHtml("x_shift_id") ?>
                    </select>
                    <?= $Page->shift_id->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->shift_id->getErrorMessage() ?></div>
                <?= $Page->shift_id->Lookup->getParamTag($Page, "p_x_shift_id") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='master_holiday_x_shift_id']"),
                        options = { name: "x_shift_id", selectId: "master_holiday_x_shift_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.master_holiday.fields.shift_id.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->holiday_date->Visible && (!$Page->isConfirm() || $Page->holiday_date->multiUpdateSelected())) { // holiday_date ?>
    <div id="r_holiday_date" class="form-group row">
        <label for="x_holiday_date" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_holiday_date" id="u_holiday_date" class="custom-control-input ew-multi-select" value="1"<?= $Page->holiday_date->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_holiday_date"><?= $Page->holiday_date->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->holiday_date->cellAttributes() ?>>
                <span id="el_master_holiday_holiday_date">
                <input type="<?= $Page->holiday_date->getInputTextType() ?>" data-table="master_holiday" data-field="x_holiday_date" data-format="5" name="x_holiday_date" id="x_holiday_date" placeholder="<?= HtmlEncode($Page->holiday_date->getPlaceHolder()) ?>" value="<?= $Page->holiday_date->EditValue ?>"<?= $Page->holiday_date->editAttributes() ?> aria-describedby="x_holiday_date_help">
                <?= $Page->holiday_date->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->holiday_date->getErrorMessage() ?></div>
                <?php if (!$Page->holiday_date->ReadOnly && !$Page->holiday_date->Disabled && !isset($Page->holiday_date->EditAttrs["readonly"]) && !isset($Page->holiday_date->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmaster_holidayupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("fmaster_holidayupdate", "x_holiday_date", {"ignoreReadonly":true,"useCurrent":false,"format":5});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->holiday_name->Visible && (!$Page->isConfirm() || $Page->holiday_name->multiUpdateSelected())) { // holiday_name ?>
    <div id="r_holiday_name" class="form-group row">
        <label for="x_holiday_name" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_holiday_name" id="u_holiday_name" class="custom-control-input ew-multi-select" value="1"<?= $Page->holiday_name->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_holiday_name"><?= $Page->holiday_name->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->holiday_name->cellAttributes() ?>>
                <span id="el_master_holiday_holiday_name">
                <textarea data-table="master_holiday" data-field="x_holiday_name" name="x_holiday_name" id="x_holiday_name" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->holiday_name->getPlaceHolder()) ?>"<?= $Page->holiday_name->editAttributes() ?> aria-describedby="x_holiday_name_help"><?= $Page->holiday_name->EditValue ?></textarea>
                <?= $Page->holiday_name->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->holiday_name->getErrorMessage() ?></div>
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
    ew.addEventHandlers("master_holiday");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
