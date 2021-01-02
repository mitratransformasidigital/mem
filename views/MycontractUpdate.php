<?php

namespace MEM\prjMitralPHP;

// Page object
$MycontractUpdate = &$Page;
?>
<script>
if (!ew.vars.tables.mycontract) ew.vars.tables.mycontract = <?= JsonEncode(GetClientVar("tables", "mycontract")) ?>;
var currentForm, currentPageID;
var fmycontractupdate;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "update";
    fmycontractupdate = currentForm = new ew.Form("fmycontractupdate", "update");

    // Add fields
    var fields = ew.vars.tables.mycontract.fields;
    fmycontractupdate.addFields([
        ["salary", [fields.salary.required ? ew.Validators.required(fields.salary.caption) : null, ew.Validators.float, ew.Validators.selected], fields.salary.isInvalid],
        ["bonus", [fields.bonus.required ? ew.Validators.required(fields.bonus.caption) : null, ew.Validators.float, ew.Validators.selected], fields.bonus.isInvalid],
        ["thr", [fields.thr.required ? ew.Validators.required(fields.thr.caption) : null], fields.thr.isInvalid],
        ["contract_start", [fields.contract_start.required ? ew.Validators.required(fields.contract_start.caption) : null, ew.Validators.datetime(0), ew.Validators.selected], fields.contract_start.isInvalid],
        ["contract_end", [fields.contract_end.required ? ew.Validators.required(fields.contract_end.caption) : null, ew.Validators.datetime(0), ew.Validators.selected], fields.contract_end.isInvalid],
        ["office_id", [fields.office_id.required ? ew.Validators.required(fields.office_id.caption) : null], fields.office_id.isInvalid],
        ["notes", [fields.notes.required ? ew.Validators.required(fields.notes.caption) : null], fields.notes.isInvalid],
        ["contract_document", [fields.contract_document.required ? ew.Validators.fileRequired(fields.contract_document.caption) : null], fields.contract_document.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fmycontractupdate,
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
    fmycontractupdate.validate = function () {
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
    fmycontractupdate.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmycontractupdate.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmycontractupdate.lists.office_id = <?= $Page->office_id->toClientList($Page) ?>;
    loadjs.done("fmycontractupdate");
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
<form name="fmycontractupdate" id="fmycontractupdate" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mycontract">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div id="tbl_mycontractupdate" class="ew-update-div"><!-- page -->
    <?php if (!$Page->isConfirm()) { // Confirm page ?>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="u" id="u" onclick="ew.selectAll(this);"><label class="custom-control-label" for="u"><?= $Language->phrase("UpdateSelectAll") ?></label>
    </div>
    <?php } ?>
<?php if ($Page->salary->Visible && (!$Page->isConfirm() || $Page->salary->multiUpdateSelected())) { // salary ?>
    <div id="r_salary" class="form-group row">
        <label for="x_salary" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_salary" id="u_salary" class="custom-control-input ew-multi-select" value="1"<?= $Page->salary->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_salary"><?= $Page->salary->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->salary->cellAttributes() ?>>
                <span id="el_mycontract_salary">
                <input type="<?= $Page->salary->getInputTextType() ?>" data-table="mycontract" data-field="x_salary" name="x_salary" id="x_salary" size="30" placeholder="<?= HtmlEncode($Page->salary->getPlaceHolder()) ?>" value="<?= $Page->salary->EditValue ?>"<?= $Page->salary->editAttributes() ?> aria-describedby="x_salary_help">
                <?= $Page->salary->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->salary->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->bonus->Visible && (!$Page->isConfirm() || $Page->bonus->multiUpdateSelected())) { // bonus ?>
    <div id="r_bonus" class="form-group row">
        <label for="x_bonus" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_bonus" id="u_bonus" class="custom-control-input ew-multi-select" value="1"<?= $Page->bonus->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_bonus"><?= $Page->bonus->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->bonus->cellAttributes() ?>>
                <span id="el_mycontract_bonus">
                <input type="<?= $Page->bonus->getInputTextType() ?>" data-table="mycontract" data-field="x_bonus" name="x_bonus" id="x_bonus" size="30" placeholder="<?= HtmlEncode($Page->bonus->getPlaceHolder()) ?>" value="<?= $Page->bonus->EditValue ?>"<?= $Page->bonus->editAttributes() ?> aria-describedby="x_bonus_help">
                <?= $Page->bonus->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->bonus->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->thr->Visible && (!$Page->isConfirm() || $Page->thr->multiUpdateSelected())) { // thr ?>
    <div id="r_thr" class="form-group row">
        <label class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_thr" id="u_thr" class="custom-control-input ew-multi-select" value="1"<?= $Page->thr->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_thr"><?= $Page->thr->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->thr->cellAttributes() ?>>
                <span id="el_mycontract_thr">
                <div class="custom-control custom-checkbox d-inline-block">
                    <input type="checkbox" class="custom-control-input<?= $Page->thr->isInvalidClass() ?>" data-table="mycontract" data-field="x_thr" name="x_thr[]" id="x_thr_468058" value="1"<?= ConvertToBool($Page->thr->CurrentValue) ? " checked" : "" ?><?= $Page->thr->editAttributes() ?> aria-describedby="x_thr_help">
                    <label class="custom-control-label" for="x_thr_468058"></label>
                </div>
                <?= $Page->thr->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->thr->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->contract_start->Visible && (!$Page->isConfirm() || $Page->contract_start->multiUpdateSelected())) { // contract_start ?>
    <div id="r_contract_start" class="form-group row">
        <label for="x_contract_start" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_contract_start" id="u_contract_start" class="custom-control-input ew-multi-select" value="1"<?= $Page->contract_start->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_contract_start"><?= $Page->contract_start->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->contract_start->cellAttributes() ?>>
                <span id="el_mycontract_contract_start">
                <input type="<?= $Page->contract_start->getInputTextType() ?>" data-table="mycontract" data-field="x_contract_start" name="x_contract_start" id="x_contract_start" placeholder="<?= HtmlEncode($Page->contract_start->getPlaceHolder()) ?>" value="<?= $Page->contract_start->EditValue ?>"<?= $Page->contract_start->editAttributes() ?> aria-describedby="x_contract_start_help">
                <?= $Page->contract_start->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->contract_start->getErrorMessage() ?></div>
                <?php if (!$Page->contract_start->ReadOnly && !$Page->contract_start->Disabled && !isset($Page->contract_start->EditAttrs["readonly"]) && !isset($Page->contract_start->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmycontractupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("fmycontractupdate", "x_contract_start", {"ignoreReadonly":true,"useCurrent":false,"format":0});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->contract_end->Visible && (!$Page->isConfirm() || $Page->contract_end->multiUpdateSelected())) { // contract_end ?>
    <div id="r_contract_end" class="form-group row">
        <label for="x_contract_end" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_contract_end" id="u_contract_end" class="custom-control-input ew-multi-select" value="1"<?= $Page->contract_end->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_contract_end"><?= $Page->contract_end->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->contract_end->cellAttributes() ?>>
                <span id="el_mycontract_contract_end">
                <input type="<?= $Page->contract_end->getInputTextType() ?>" data-table="mycontract" data-field="x_contract_end" name="x_contract_end" id="x_contract_end" placeholder="<?= HtmlEncode($Page->contract_end->getPlaceHolder()) ?>" value="<?= $Page->contract_end->EditValue ?>"<?= $Page->contract_end->editAttributes() ?> aria-describedby="x_contract_end_help">
                <?= $Page->contract_end->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->contract_end->getErrorMessage() ?></div>
                <?php if (!$Page->contract_end->ReadOnly && !$Page->contract_end->Disabled && !isset($Page->contract_end->EditAttrs["readonly"]) && !isset($Page->contract_end->EditAttrs["disabled"])) { ?>
                <script>
                loadjs.ready(["fmycontractupdate", "datetimepicker"], function() {
                    ew.createDateTimePicker("fmycontractupdate", "x_contract_end", {"ignoreReadonly":true,"useCurrent":false,"format":0});
                });
                </script>
                <?php } ?>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->office_id->Visible && (!$Page->isConfirm() || $Page->office_id->multiUpdateSelected())) { // office_id ?>
    <div id="r_office_id" class="form-group row">
        <label for="x_office_id" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_office_id" id="u_office_id" class="custom-control-input ew-multi-select" value="1"<?= $Page->office_id->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_office_id"><?= $Page->office_id->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->office_id->cellAttributes() ?>>
                <span id="el_mycontract_office_id">
                    <select
                        id="x_office_id"
                        name="x_office_id"
                        class="form-control ew-select<?= $Page->office_id->isInvalidClass() ?>"
                        data-select2-id="mycontract_x_office_id"
                        data-table="mycontract"
                        data-field="x_office_id"
                        data-value-separator="<?= $Page->office_id->displayValueSeparatorAttribute() ?>"
                        data-placeholder="<?= HtmlEncode($Page->office_id->getPlaceHolder()) ?>"
                        <?= $Page->office_id->editAttributes() ?>>
                        <?= $Page->office_id->selectOptionListHtml("x_office_id") ?>
                    </select>
                    <?= $Page->office_id->getCustomMessage() ?>
                    <div class="invalid-feedback"><?= $Page->office_id->getErrorMessage() ?></div>
                <?= $Page->office_id->Lookup->getParamTag($Page, "p_x_office_id") ?>
                <script>
                loadjs.ready("head", function() {
                    var el = document.querySelector("select[data-select2-id='mycontract_x_office_id']"),
                        options = { name: "x_office_id", selectId: "mycontract_x_office_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
                    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
                    Object.assign(options, ew.vars.tables.mycontract.fields.office_id.selectOptions);
                    ew.createSelect(options);
                });
                </script>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->notes->Visible && (!$Page->isConfirm() || $Page->notes->multiUpdateSelected())) { // notes ?>
    <div id="r_notes" class="form-group row">
        <label for="x_notes" class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_notes" id="u_notes" class="custom-control-input ew-multi-select" value="1"<?= $Page->notes->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_notes"><?= $Page->notes->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->notes->cellAttributes() ?>>
                <span id="el_mycontract_notes">
                <textarea data-table="mycontract" data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->notes->getPlaceHolder()) ?>"<?= $Page->notes->editAttributes() ?> aria-describedby="x_notes_help"><?= $Page->notes->EditValue ?></textarea>
                <?= $Page->notes->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->notes->getErrorMessage() ?></div>
                </span>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->contract_document->Visible && (!$Page->isConfirm() || $Page->contract_document->multiUpdateSelected())) { // contract_document ?>
    <div id="r_contract_document" class="form-group row">
        <label class="<?= $Page->LeftColumnClass ?>">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="u_contract_document" id="u_contract_document" class="custom-control-input ew-multi-select" value="1"<?= $Page->contract_document->multiUpdateSelected() ? " checked" : "" ?>>
                <label class="custom-control-label" for="u_contract_document"><?= $Page->contract_document->caption() ?></label>
            </div>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div <?= $Page->contract_document->cellAttributes() ?>>
                <span id="el_mycontract_contract_document">
                <div id="fd_x_contract_document">
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" title="<?= $Page->contract_document->title() ?>" data-table="mycontract" data-field="x_contract_document" name="x_contract_document" id="x_contract_document" lang="<?= CurrentLanguageID() ?>"<?= $Page->contract_document->editAttributes() ?><?= ($Page->contract_document->ReadOnly || $Page->contract_document->Disabled) ? " disabled" : "" ?> aria-describedby="x_contract_document_help">
                        <label class="custom-file-label ew-file-label" for="x_contract_document"><?= $Language->phrase("ChooseFile") ?></label>
                    </div>
                </div>
                <?= $Page->contract_document->getCustomMessage() ?>
                <div class="invalid-feedback"><?= $Page->contract_document->getErrorMessage() ?></div>
                <input type="hidden" name="fn_x_contract_document" id= "fn_x_contract_document" value="<?= $Page->contract_document->Upload->FileName ?>">
                <input type="hidden" name="fa_x_contract_document" id= "fa_x_contract_document" value="<?= (Post("fa_x_contract_document") == "0") ? "0" : "1" ?>">
                <input type="hidden" name="fs_x_contract_document" id= "fs_x_contract_document" value="150">
                <input type="hidden" name="fx_x_contract_document" id= "fx_x_contract_document" value="<?= $Page->contract_document->UploadAllowedFileExt ?>">
                <input type="hidden" name="fm_x_contract_document" id= "fm_x_contract_document" value="<?= $Page->contract_document->UploadMaxFileSize ?>">
                </div>
                <table id="ft_x_contract_document" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
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
    ew.addEventHandlers("mycontract");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
