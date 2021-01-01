<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterProvinceView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmaster_provinceview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fmaster_provinceview = currentForm = new ew.Form("fmaster_provinceview", "view");
    loadjs.done("fmaster_provinceview");
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
<form name="fmaster_provinceview" id="fmaster_provinceview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_province">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->province_id->Visible) { // province_id ?>
    <tr id="r_province_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_province_province_id"><?= $Page->province_id->caption() ?></span></td>
        <td data-name="province_id" <?= $Page->province_id->cellAttributes() ?>>
<span id="el_master_province_province_id">
<span<?= $Page->province_id->viewAttributes() ?>>
<?= $Page->province_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->province->Visible) { // province ?>
    <tr id="r_province">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_province_province"><?= $Page->province->caption() ?></span></td>
        <td data-name="province" <?= $Page->province->cellAttributes() ?>>
<span id="el_master_province_province">
<span<?= $Page->province->viewAttributes() ?>>
<?= $Page->province->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("master_city", explode(",", $Page->getCurrentDetailTable())) && $master_city->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("master_city", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->master_city_Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "MasterCityGrid.php" ?>
<?php } ?>
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
