<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterCityView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmaster_cityview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fmaster_cityview = currentForm = new ew.Form("fmaster_cityview", "view");
    loadjs.done("fmaster_cityview");
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
<form name="fmaster_cityview" id="fmaster_cityview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_city">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->province_id->Visible) { // province_id ?>
    <tr id="r_province_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_city_province_id"><?= $Page->province_id->caption() ?></span></td>
        <td data-name="province_id" <?= $Page->province_id->cellAttributes() ?>>
<span id="el_master_city_province_id">
<span<?= $Page->province_id->viewAttributes() ?>>
<?= $Page->province_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <tr id="r_city_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_city_city_id"><?= $Page->city_id->caption() ?></span></td>
        <td data-name="city_id" <?= $Page->city_id->cellAttributes() ?>>
<span id="el_master_city_city_id">
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->city->Visible) { // city ?>
    <tr id="r_city">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_city_city"><?= $Page->city->caption() ?></span></td>
        <td data-name="city" <?= $Page->city->cellAttributes() ?>>
<span id="el_master_city_city">
<span<?= $Page->city->viewAttributes() ?>>
<?= $Page->city->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("master_office", explode(",", $Page->getCurrentDetailTable())) && $master_office->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("master_office", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "MasterOfficeGrid.php" ?>
<?php } ?>
<?php
    if (in_array("employee", explode(",", $Page->getCurrentDetailTable())) && $employee->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("employee", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EmployeeGrid.php" ?>
<?php } ?>
<?php
    if (in_array("myprofile", explode(",", $Page->getCurrentDetailTable())) && $myprofile->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("myprofile", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "MyprofileGrid.php" ?>
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
