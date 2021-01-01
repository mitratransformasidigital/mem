<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterPositionSearch = &$Page;
?>
<script>
if (!ew.vars.tables.master_position) ew.vars.tables.master_position = <?= JsonEncode(GetClientVar("tables", "master_position")) ?>;
var currentForm, currentPageID;
var fmaster_positionsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    <?php if ($Page->IsModal) { ?>
    fmaster_positionsearch = currentAdvancedSearchForm = new ew.Form("fmaster_positionsearch", "search");
    <?php } else { ?>
    fmaster_positionsearch = currentForm = new ew.Form("fmaster_positionsearch", "search");
    <?php } ?>
    currentPageID = ew.PAGE_ID = "search";

    // Add fields
    var fields = ew.vars.tables.master_position.fields;
    fmaster_positionsearch.addFields([
        ["position", [], fields.position.isInvalid],
        ["y_position", [ew.Validators.between], false],
        ["description", [], fields.description.isInvalid],
        ["y_description", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmaster_positionsearch.setInvalid();
    });

    // Validate form
    fmaster_positionsearch.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj),
            rowIndex = "";
        $fobj.data("rowindex", rowIndex);

        // Validate fields
        if (!this.validateFields(rowIndex))
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    fmaster_positionsearch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmaster_positionsearch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fmaster_positionsearch");
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
<form name="fmaster_positionsearch" id="fmaster_positionsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_position">
<input type="hidden" name="action" id="action" value="search">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->position->Visible) { // position ?>
    <div id="r_position" class="form-group row">
        <label for="x_position" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_position_position"><?= $Page->position->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->position->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_position" id="z_position" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->position->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->position->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->position->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->position->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->position->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->position->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->position->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->position->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->position->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->position->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->position->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_position_position" class="ew-search-field">
<input type="<?= $Page->position->getInputTextType() ?>" data-table="master_position" data-field="x_position" name="x_position" id="x_position" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->position->getPlaceHolder()) ?>" value="<?= $Page->position->EditValue ?>"<?= $Page->position->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->position->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_position_position" class="ew-search-field2 d-none">
<input type="<?= $Page->position->getInputTextType() ?>" data-table="master_position" data-field="x_position" name="y_position" id="y_position" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->position->getPlaceHolder()) ?>" value="<?= $Page->position->EditValue2 ?>"<?= $Page->position->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->position->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description" class="form-group row">
        <label for="x_description" class="<?= $Page->LeftColumnClass ?>"><span id="elh_master_position_description"><?= $Page->description->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->description->cellAttributes() ?>>
        <span class="ew-search-operator">
<select name="z_description" id="z_description" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->description->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->description->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->description->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->description->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->description->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->description->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->description->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->description->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->description->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->description->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="IS NULL"<?= $Page->description->AdvancedSearch->SearchOperator == "IS NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NULL") ?></option>
<option value="IS NOT NULL"<?= $Page->description->AdvancedSearch->SearchOperator == "IS NOT NULL" ? " selected" : "" ?>><?= $Language->phrase("IS NOT NULL") ?></option>
<option value="BETWEEN"<?= $Page->description->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
            <span id="el_master_position_description" class="ew-search-field">
<input type="<?= $Page->description->getInputTextType() ?>" data-table="master_position" data-field="x_description" name="x_description" id="x_description" maxlength="250" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>" value="<?= $Page->description->EditValue ?>"<?= $Page->description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage(false) ?></div>
</span>
            <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
            <span id="el2_master_position_description" class="ew-search-field2 d-none">
<input type="<?= $Page->description->getInputTextType() ?>" data-table="master_position" data-field="x_description" name="y_description" id="y_description" maxlength="250" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>" value="<?= $Page->description->EditValue2 ?>"<?= $Page->description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage(false) ?></div>
</span>
        </div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("Search") ?></button>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" onclick="location.reload();"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("master_position");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
