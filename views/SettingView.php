<?php

namespace MEM\prjMitralPHP;

// Page object
$SettingView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fsettingview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fsettingview = currentForm = new ew.Form("fsettingview", "view");
    loadjs.done("fsettingview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="fsettingview" id="fsettingview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="setting">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->setting_name->Visible) { // setting_name ?>
    <tr id="r_setting_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_setting_setting_name"><?= $Page->setting_name->caption() ?></span></td>
        <td data-name="setting_name" <?= $Page->setting_name->cellAttributes() ?>>
<span id="el_setting_setting_name" data-page="1">
<span<?= $Page->setting_name->viewAttributes() ?>>
<?= $Page->setting_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->setting_value->Visible) { // setting_value ?>
    <tr id="r_setting_value">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_setting_setting_value"><?= $Page->setting_value->caption() ?></span></td>
        <td data-name="setting_value" <?= $Page->setting_value->cellAttributes() ?>>
<span id="el_setting_setting_value" data-page="1">
<span<?= $Page->setting_value->viewAttributes() ?>>
<?= $Page->setting_value->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
