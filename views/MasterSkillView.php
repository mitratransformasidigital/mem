<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterSkillView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmaster_skillview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fmaster_skillview = currentForm = new ew.Form("fmaster_skillview", "view");
    loadjs.done("fmaster_skillview");
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
<form name="fmaster_skillview" id="fmaster_skillview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_skill">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->skill_id->Visible) { // skill_id ?>
    <tr id="r_skill_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_skill_skill_id"><?= $Page->skill_id->caption() ?></span></td>
        <td data-name="skill_id" <?= $Page->skill_id->cellAttributes() ?>>
<span id="el_master_skill_skill_id">
<span<?= $Page->skill_id->viewAttributes() ?>>
<?= $Page->skill_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->skill->Visible) { // skill ?>
    <tr id="r_skill">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_skill_skill"><?= $Page->skill->caption() ?></span></td>
        <td data-name="skill" <?= $Page->skill->cellAttributes() ?>>
<span id="el_master_skill_skill">
<span<?= $Page->skill->viewAttributes() ?>>
<?= $Page->skill->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <tr id="r_description">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_skill_description"><?= $Page->description->caption() ?></span></td>
        <td data-name="description" <?= $Page->description->cellAttributes() ?>>
<span id="el_master_skill_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<?php
    $Page->DetailPages->ValidKeys = explode(",", $Page->getCurrentDetailTable());
    $firstActiveDetailTable = $Page->DetailPages->activePageIndex();
?>
<div class="ew-detail-pages"><!-- detail-pages -->
<div class="ew-nav-tabs" id="Page_details"><!-- tabs -->
    <ul class="<?= $Page->DetailPages->navStyle() ?>"><!-- .nav -->
<?php
    if (in_array("employee", explode(",", $Page->getCurrentDetailTable())) && $employee->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee") {
            $firstActiveDetailTable = "employee";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("employee") ?>" href="#tab_employee" data-toggle="tab"><?= $Language->tablePhrase("employee", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->employee_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
<?php
    if (in_array("myprofile", explode(",", $Page->getCurrentDetailTable())) && $myprofile->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "myprofile") {
            $firstActiveDetailTable = "myprofile";
        }
?>
        <li class="nav-item"><a class="nav-link <?= $Page->DetailPages->pageStyle("myprofile") ?>" href="#tab_myprofile" data-toggle="tab"><?= $Language->tablePhrase("myprofile", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->myprofile_Count, $Language->phrase("DetailCount")) ?></a></li>
<?php
    }
?>
    </ul><!-- /.nav -->
    <div class="tab-content"><!-- .tab-content -->
<?php
    if (in_array("employee", explode(",", $Page->getCurrentDetailTable())) && $employee->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "employee") {
            $firstActiveDetailTable = "employee";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("employee") ?>" id="tab_employee"><!-- page* -->
<?php include_once "EmployeeGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("myprofile", explode(",", $Page->getCurrentDetailTable())) && $myprofile->DetailView) {
        if ($firstActiveDetailTable == "" || $firstActiveDetailTable == "myprofile") {
            $firstActiveDetailTable = "myprofile";
        }
?>
        <div class="tab-pane <?= $Page->DetailPages->pageStyle("myprofile") ?>" id="tab_myprofile"><!-- page* -->
<?php include_once "MyprofileGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
    </div><!-- /.tab-content -->
</div><!-- /tabs -->
</div><!-- /detail-pages -->
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
