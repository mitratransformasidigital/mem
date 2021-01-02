<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterWorkDateEdit = &$Page;
?>
<script>
if (!ew.vars.tables.master_work_date) ew.vars.tables.master_work_date = <?= JsonEncode(GetClientVar("tables", "master_work_date")) ?>;
var currentForm, currentPageID;
var fmaster_work_dateedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fmaster_work_dateedit = currentForm = new ew.Form("fmaster_work_dateedit", "edit");

    // Add fields
    var fields = ew.vars.tables.master_work_date.fields;
    fmaster_work_dateedit.addFields([
        ["work_date", [fields.work_date.required ? ew.Validators.required(fields.work_date.caption) : null, ew.Validators.datetime(0)], fields.work_date.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmaster_work_dateedit,
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
    fmaster_work_dateedit.validate = function () {
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
    fmaster_work_dateedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_work_dateedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_work_dateedit");
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
<?php if (!$Page->IsModal) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fmaster_work_dateedit" id="fmaster_work_dateedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_work_date">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->work_date->Visible) { // work_date ?>
    <div id="r_work_date" class="form-group row">
        <label id="elh_master_work_date_work_date" for="x_work_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->work_date->caption() ?><?= $Page->work_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->work_date->cellAttributes() ?>>
<input type="<?= $Page->work_date->getInputTextType() ?>" data-table="master_work_date" data-field="x_work_date" name="x_work_date" id="x_work_date" placeholder="<?= HtmlEncode($Page->work_date->getPlaceHolder()) ?>" value="<?= $Page->work_date->EditValue ?>"<?= $Page->work_date->editAttributes() ?> aria-describedby="x_work_date_help">
<?= $Page->work_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->work_date->getErrorMessage() ?></div>
<?php if (!$Page->work_date->ReadOnly && !$Page->work_date->Disabled && !isset($Page->work_date->EditAttrs["readonly"]) && !isset($Page->work_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmaster_work_dateedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fmaster_work_dateedit", "x_work_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
<input type="hidden" data-table="master_work_date" data-field="x_work_date" data-hidden="1" name="o_work_date" id="o_work_date" value="<?= HtmlEncode($Page->work_date->OldValue ?? $Page->work_date->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
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
    ew.addEventHandlers("master_work_date");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
