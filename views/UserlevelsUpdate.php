<?php

namespace MEM\prjMitralPHP;

// Page object
$UserlevelsUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.userlevels) ew.vars.tables.userlevels = <?= JsonEncode(GetClientVar("tables", "userlevels")) ?>;
var currentForm, currentPageID;
var fuserlevelsupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fuserlevelsupdate = currentForm = new ew.Form("fuserlevelsupdate", "update");

    // Add fields
    var fields = ew.vars.tables.userlevels.fields;
    fuserlevelsupdate.addFields([
        ["userlevelid", [fields.userlevelid.required ? ew.Validators.required(fields.userlevelid.caption) : null, ew.Validators.userLevelId, ew.Validators.integer, ew.Validators.selected], fields.userlevelid.isInvalid],
        ["userlevelname", [fields.userlevelname.required ? ew.Validators.required(fields.userlevelname.caption) : null, ew.Validators.userLevelName('userlevelid'), ew.Validators.selected], fields.userlevelname.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fuserlevelsupdate,
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
    fuserlevelsupdate.validate = function () {
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
    fuserlevelsupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fuserlevelsupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fuserlevelsupdate");
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
<form name="fuserlevelsupdate" id="fuserlevelsupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="userlevels">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_userlevelsupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->userlevelid->Visible && (!$Page->isConfirm() || $Page->userlevelid->multiUpdateSelected())) { // userlevelid ?>
    <div id="r_userlevelid" class="form-group row">
        <label for="x_userlevelid" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_userlevelid" id="u_userlevelid" class="custom-control-input ew-multi-select" value="1"<?= $Page->userlevelid->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_userlevelid"><?= $Page->userlevelid->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->userlevelid->cellAttributes() ?>>
                <input type="<?= $Page->userlevelid->getInputTextType() ?>" data-table="userlevels" data-field="x_userlevelid" name="x_userlevelid" id="x_userlevelid" size="30" placeholder="<?= HtmlEncode($Page->userlevelid->getPlaceHolder()) ?>" value="<?= $Page->userlevelid->EditValue ?>"<?= $Page->userlevelid->editAttributes() ?> aria-describedby="x_userlevelid_help">
                <?= $Page->userlevelid->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->userlevelid->getErrorMessage() ?></div>
                <input type="hidden" data-table="userlevels" data-field="x_userlevelid" data-hidden="1" name="o_userlevelid" id="o_userlevelid" value="<?= HtmlEncode($Page->userlevelid->OldValue ?? $Page->userlevelid->CurrentValue) ?>">
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->userlevelname->Visible && (!$Page->isConfirm() || $Page->userlevelname->multiUpdateSelected())) { // userlevelname ?>
    <div id="r_userlevelname" class="form-group row">
        <label for="x_userlevelname" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_userlevelname" id="u_userlevelname" class="custom-control-input ew-multi-select" value="1"<?= $Page->userlevelname->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_userlevelname"><?= $Page->userlevelname->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->userlevelname->cellAttributes() ?>>
                <span id="el_userlevels_userlevelname">
                <input type="<?= $Page->userlevelname->getInputTextType() ?>" data-table="userlevels" data-field="x_userlevelname" name="x_userlevelname" id="x_userlevelname" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->userlevelname->getPlaceHolder()) ?>" value="<?= $Page->userlevelname->EditValue ?>"<?= $Page->userlevelname->editAttributes() ?> aria-describedby="x_userlevelname_help">
                <?= $Page->userlevelname->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->userlevelname->getErrorMessage() ?></div>
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
    ew.addEventHandlers("userlevels");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
