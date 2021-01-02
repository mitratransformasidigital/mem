<?php

namespace MEM\prjMitralPHP;

// Page object
$MyprofileList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmyprofilelist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fmyprofilelist = currentForm = new ew.Form("fmyprofilelist", "list");
    fmyprofilelist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fmyprofilelist");
});
var fmyprofilelistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fmyprofilelistsrch = currentSearchForm = new ew.Form("fmyprofilelistsrch");

    // Dynamic selection lists

    // Filters
    fmyprofilelistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fmyprofilelistsrch");
});
</script>
<style type="text/css">
.ew-table-preview-row { /* main table preview row color */
    background-color: #FFFFFF; /* preview row color */
}
.ew-table-preview-row .ew-grid {
    display: table;
}
</style>
<div id="ew-preview" class="d-none"><!-- preview -->
    <div class="ew-nav-tabs"><!-- .ew-nav-tabs -->
        <ul class="nav nav-tabs"></ul>
        <div class="tab-content"><!-- .tab-content -->
            <div class="tab-pane fade active show"></div>
        </div><!-- /.tab-content -->
    </div><!-- /.ew-nav-tabs -->
</div><!-- /preview -->
<script>
loadjs.ready("head", function() {
    ew.PREVIEW_PLACEMENT = ew.CSS_FLIP ? "left" : "right";
    ew.PREVIEW_SINGLE_ROW = false;
    ew.PREVIEW_OVERLAY = false;
    loadjs(ew.PATH_BASE + "js/ewpreview.js", "preview");
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
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "master_office") {
    if ($Page->MasterRecordExists) {
        include_once "views/MasterOfficeMaster.php";
    }
}
?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "master_position") {
    if ($Page->MasterRecordExists) {
        include_once "views/MasterPositionMaster.php";
    }
}
?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "master_skill") {
    if ($Page->MasterRecordExists) {
        include_once "views/MasterSkillMaster.php";
    }
}
?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "master_status") {
    if ($Page->MasterRecordExists) {
        include_once "views/MasterStatusMaster.php";
    }
}
?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "master_city") {
    if ($Page->MasterRecordExists) {
        include_once "views/MasterCityMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fmyprofilelistsrch" id="fmyprofilelistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fmyprofilelistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="myprofile">
    <div class="ew-extended-search">
<div id="xsr_<?= $Page->SearchRowCount + 1 ?>" class="ew-row d-sm-flex">
    <div class="ew-quick-search input-group">
        <input type="text" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>">
        <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
        <div class="input-group-append">
            <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
            <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span></button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this);"><?= $Language->phrase("QuickSearchAuto") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "=") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, '=');"><?= $Language->phrase("QuickSearchExact") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "AND") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'AND');"><?= $Language->phrase("QuickSearchAll") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "OR") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'OR');"><?= $Language->phrase("QuickSearchAny") ?></a>
            </div>
        </div>
    </div>
</div>
    </div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> myprofile">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fmyprofilelist" id="fmyprofilelist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="myprofile">
<?php if ($Page->getCurrentMasterTable() == "master_office" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_office">
<input type="hidden" name="fk_office_id" value="<?= HtmlEncode($Page->office_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "master_position" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_position">
<input type="hidden" name="fk_position_id" value="<?= HtmlEncode($Page->position_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "master_skill" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_skill">
<input type="hidden" name="fk_skill_id" value="<?= HtmlEncode($Page->skill_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "master_status" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_status">
<input type="hidden" name="fk_status_id" value="<?= HtmlEncode($Page->status_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "master_city" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_city">
<input type="hidden" name="fk_city_id" value="<?= HtmlEncode($Page->city_id->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_myprofile" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_myprofilelist" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->employee_name->Visible) { // employee_name ?>
        <th data-name="employee_name" class="<?= $Page->employee_name->headerCellClass() ?>"><div id="elh_myprofile_employee_name" class="myprofile_employee_name"><?= $Page->renderSort($Page->employee_name) ?></div></th>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <th data-name="employee_username" class="<?= $Page->employee_username->headerCellClass() ?>"><div id="elh_myprofile_employee_username" class="myprofile_employee_username"><?= $Page->renderSort($Page->employee_username) ?></div></th>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
        <th data-name="employee_password" class="<?= $Page->employee_password->headerCellClass() ?>"><div id="elh_myprofile_employee_password" class="myprofile_employee_password"><?= $Page->renderSort($Page->employee_password) ?></div></th>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
        <th data-name="employee_email" class="<?= $Page->employee_email->headerCellClass() ?>"><div id="elh_myprofile_employee_email" class="myprofile_employee_email"><?= $Page->renderSort($Page->employee_email) ?></div></th>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
        <th data-name="birth_date" class="<?= $Page->birth_date->headerCellClass() ?>"><div id="elh_myprofile_birth_date" class="myprofile_birth_date"><?= $Page->renderSort($Page->birth_date) ?></div></th>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
        <th data-name="nik" class="<?= $Page->nik->headerCellClass() ?>"><div id="elh_myprofile_nik" class="myprofile_nik"><?= $Page->renderSort($Page->nik) ?></div></th>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
        <th data-name="npwp" class="<?= $Page->npwp->headerCellClass() ?>"><div id="elh_myprofile_npwp" class="myprofile_npwp"><?= $Page->renderSort($Page->npwp) ?></div></th>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
        <th data-name="address" class="<?= $Page->address->headerCellClass() ?>" style="white-space: nowrap;"><div id="elh_myprofile_address" class="myprofile_address"><?= $Page->renderSort($Page->address) ?></div></th>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <th data-name="city_id" class="<?= $Page->city_id->headerCellClass() ?>"><div id="elh_myprofile_city_id" class="myprofile_city_id"><?= $Page->renderSort($Page->city_id) ?></div></th>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
        <th data-name="postal_code" class="<?= $Page->postal_code->headerCellClass() ?>"><div id="elh_myprofile_postal_code" class="myprofile_postal_code"><?= $Page->renderSort($Page->postal_code) ?></div></th>
<?php } ?>
<?php if ($Page->bank_number->Visible) { // bank_number ?>
        <th data-name="bank_number" class="<?= $Page->bank_number->headerCellClass() ?>"><div id="elh_myprofile_bank_number" class="myprofile_bank_number"><?= $Page->renderSort($Page->bank_number) ?></div></th>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
        <th data-name="bank_name" class="<?= $Page->bank_name->headerCellClass() ?>"><div id="elh_myprofile_bank_name" class="myprofile_bank_name"><?= $Page->renderSort($Page->bank_name) ?></div></th>
<?php } ?>
<?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
        <th data-name="scan_ktp" class="<?= $Page->scan_ktp->headerCellClass() ?>"><div id="elh_myprofile_scan_ktp" class="myprofile_scan_ktp"><?= $Page->renderSort($Page->scan_ktp) ?></div></th>
<?php } ?>
<?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
        <th data-name="scan_npwp" class="<?= $Page->scan_npwp->headerCellClass() ?>"><div id="elh_myprofile_scan_npwp" class="myprofile_scan_npwp"><?= $Page->renderSort($Page->scan_npwp) ?></div></th>
<?php } ?>
<?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <th data-name="curiculum_vitae" class="<?= $Page->curiculum_vitae->headerCellClass() ?>"><div id="elh_myprofile_curiculum_vitae" class="myprofile_curiculum_vitae"><?= $Page->renderSort($Page->curiculum_vitae) ?></div></th>
<?php } ?>
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
        <th data-name="technical_skill" class="<?= $Page->technical_skill->headerCellClass() ?>"><div id="elh_myprofile_technical_skill" class="myprofile_technical_skill"><?= $Page->renderSort($Page->technical_skill) ?></div></th>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
        <th data-name="about_me" class="<?= $Page->about_me->headerCellClass() ?>"><div id="elh_myprofile_about_me" class="myprofile_about_me"><?= $Page->renderSort($Page->about_me) ?></div></th>
<?php } ?>
<?php if ($Page->position_id->Visible) { // position_id ?>
        <th data-name="position_id" class="<?= $Page->position_id->headerCellClass() ?>"><div id="elh_myprofile_position_id" class="myprofile_position_id"><?= $Page->renderSort($Page->position_id) ?></div></th>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
        <th data-name="religion" class="<?= $Page->religion->headerCellClass() ?>"><div id="elh_myprofile_religion" class="myprofile_religion"><?= $Page->renderSort($Page->religion) ?></div></th>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
        <th data-name="status_id" class="<?= $Page->status_id->headerCellClass() ?>"><div id="elh_myprofile_status_id" class="myprofile_status_id"><?= $Page->renderSort($Page->status_id) ?></div></th>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
        <th data-name="skill_id" class="<?= $Page->skill_id->headerCellClass() ?>"><div id="elh_myprofile_skill_id" class="myprofile_skill_id"><?= $Page->renderSort($Page->skill_id) ?></div></th>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
        <th data-name="office_id" class="<?= $Page->office_id->headerCellClass() ?>"><div id="elh_myprofile_office_id" class="myprofile_office_id"><?= $Page->renderSort($Page->office_id) ?></div></th>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
        <th data-name="hire_date" class="<?= $Page->hire_date->headerCellClass() ?>"><div id="elh_myprofile_hire_date" class="myprofile_hire_date"><?= $Page->renderSort($Page->hire_date) ?></div></th>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
        <th data-name="termination_date" class="<?= $Page->termination_date->headerCellClass() ?>"><div id="elh_myprofile_termination_date" class="myprofile_termination_date"><?= $Page->renderSort($Page->termination_date) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif (!$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view

        // Set up row id / data-rowindex
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_myprofile", "data-rowtype" => $Page->RowType]);

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->employee_name->Visible) { // employee_name ?>
        <td data-name="employee_name" <?= $Page->employee_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_employee_name">
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->employee_password->Visible) { // employee_password ?>
        <td data-name="employee_password" <?= $Page->employee_password->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_employee_password">
<span<?= $Page->employee_password->viewAttributes() ?>>
<?= $Page->employee_password->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->employee_email->Visible) { // employee_email ?>
        <td data-name="employee_email" <?= $Page->employee_email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_employee_email">
<span<?= $Page->employee_email->viewAttributes() ?>>
<?= $Page->employee_email->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->birth_date->Visible) { // birth_date ?>
        <td data-name="birth_date" <?= $Page->birth_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_birth_date">
<span<?= $Page->birth_date->viewAttributes() ?>>
<?= $Page->birth_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nik->Visible) { // nik ?>
        <td data-name="nik" <?= $Page->nik->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_nik">
<span<?= $Page->nik->viewAttributes() ?>>
<?= $Page->nik->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->npwp->Visible) { // npwp ?>
        <td data-name="npwp" <?= $Page->npwp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_npwp">
<span<?= $Page->npwp->viewAttributes() ?>>
<?= $Page->npwp->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->address->Visible) { // address ?>
        <td data-name="address" <?= $Page->address->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_address">
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->city_id->Visible) { // city_id ?>
        <td data-name="city_id" <?= $Page->city_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_city_id">
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->postal_code->Visible) { // postal_code ?>
        <td data-name="postal_code" <?= $Page->postal_code->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_postal_code">
<span<?= $Page->postal_code->viewAttributes() ?>>
<?= $Page->postal_code->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->bank_number->Visible) { // bank_number ?>
        <td data-name="bank_number" <?= $Page->bank_number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_bank_number">
<span<?= $Page->bank_number->viewAttributes() ?>>
<?= $Page->bank_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->bank_name->Visible) { // bank_name ?>
        <td data-name="bank_name" <?= $Page->bank_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_bank_name">
<span<?= $Page->bank_name->viewAttributes() ?>>
<?= $Page->bank_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
        <td data-name="scan_ktp" <?= $Page->scan_ktp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_scan_ktp">
<span<?= $Page->scan_ktp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_ktp, $Page->scan_ktp->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
        <td data-name="scan_npwp" <?= $Page->scan_npwp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_scan_npwp">
<span<?= $Page->scan_npwp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_npwp, $Page->scan_npwp->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <td data-name="curiculum_vitae" <?= $Page->curiculum_vitae->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_curiculum_vitae">
<span<?= $Page->curiculum_vitae->viewAttributes() ?>>
<?= GetFileViewTag($Page->curiculum_vitae, $Page->curiculum_vitae->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->technical_skill->Visible) { // technical_skill ?>
        <td data-name="technical_skill" <?= $Page->technical_skill->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_technical_skill">
<span<?= $Page->technical_skill->viewAttributes() ?>>
<?= $Page->technical_skill->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->about_me->Visible) { // about_me ?>
        <td data-name="about_me" <?= $Page->about_me->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_about_me">
<span<?= $Page->about_me->viewAttributes() ?>>
<?= $Page->about_me->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->position_id->Visible) { // position_id ?>
        <td data-name="position_id" <?= $Page->position_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_position_id">
<span<?= $Page->position_id->viewAttributes() ?>>
<?= $Page->position_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->religion->Visible) { // religion ?>
        <td data-name="religion" <?= $Page->religion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_religion">
<span<?= $Page->religion->viewAttributes() ?>>
<?= $Page->religion->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->status_id->Visible) { // status_id ?>
        <td data-name="status_id" <?= $Page->status_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_status_id">
<span<?= $Page->status_id->viewAttributes() ?>>
<?= $Page->status_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->skill_id->Visible) { // skill_id ?>
        <td data-name="skill_id" <?= $Page->skill_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_skill_id">
<span<?= $Page->skill_id->viewAttributes() ?>>
<?= $Page->skill_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->office_id->Visible) { // office_id ?>
        <td data-name="office_id" <?= $Page->office_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_office_id">
<span<?= $Page->office_id->viewAttributes() ?>>
<?= $Page->office_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->hire_date->Visible) { // hire_date ?>
        <td data-name="hire_date" <?= $Page->hire_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_hire_date">
<span<?= $Page->hire_date->viewAttributes() ?>>
<?= $Page->hire_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->termination_date->Visible) { // termination_date ?>
        <td data-name="termination_date" <?= $Page->termination_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myprofile_termination_date">
<span<?= $Page->termination_date->viewAttributes() ?>>
<?= $Page->termination_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($Page->TotalRecords == 0 && !$Page->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("myprofile");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("fixedheadertable", function () {
    ew.fixedHeaderTable({
        delay: 0,
        container: "gmp_myprofile",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
