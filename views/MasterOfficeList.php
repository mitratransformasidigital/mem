<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterOfficeList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmaster_officelist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fmaster_officelist = currentForm = new ew.Form("fmaster_officelist", "list");
    fmaster_officelist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fmaster_officelist");
});
var fmaster_officelistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fmaster_officelistsrch = currentSearchForm = new ew.Form("fmaster_officelistsrch");

    // Dynamic selection lists

    // Filters
    fmaster_officelistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fmaster_officelistsrch");
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
<form name="fmaster_officelistsrch" id="fmaster_officelistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fmaster_officelistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="master_office">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> master_office">
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
<form name="fmaster_officelist" id="fmaster_officelist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_office">
<?php if ($Page->getCurrentMasterTable() == "master_city" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="master_city">
<input type="hidden" name="fk_city_id" value="<?= HtmlEncode($Page->city_id->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_master_office" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_master_officelist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->office->Visible) { // office ?>
        <th data-name="office" class="<?= $Page->office->headerCellClass() ?>"><div id="elh_master_office_office" class="master_office_office"><?= $Page->renderSort($Page->office) ?></div></th>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
        <th data-name="address" class="<?= $Page->address->headerCellClass() ?>"><div id="elh_master_office_address" class="master_office_address"><?= $Page->renderSort($Page->address) ?></div></th>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <th data-name="city_id" class="<?= $Page->city_id->headerCellClass() ?>"><div id="elh_master_office_city_id" class="master_office_city_id"><?= $Page->renderSort($Page->city_id) ?></div></th>
<?php } ?>
<?php if ($Page->phone_number->Visible) { // phone_number ?>
        <th data-name="phone_number" class="<?= $Page->phone_number->headerCellClass() ?>"><div id="elh_master_office_phone_number" class="master_office_phone_number"><?= $Page->renderSort($Page->phone_number) ?></div></th>
<?php } ?>
<?php if ($Page->contact_name->Visible) { // contact_name ?>
        <th data-name="contact_name" class="<?= $Page->contact_name->headerCellClass() ?>"><div id="elh_master_office_contact_name" class="master_office_contact_name"><?= $Page->renderSort($Page->contact_name) ?></div></th>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <th data-name="description" class="<?= $Page->description->headerCellClass() ?>"><div id="elh_master_office_description" class="master_office_description"><?= $Page->renderSort($Page->description) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_master_office", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->office->Visible) { // office ?>
        <td data-name="office" <?= $Page->office->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_office">
<span<?= $Page->office->viewAttributes() ?>>
<?= $Page->office->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->address->Visible) { // address ?>
        <td data-name="address" <?= $Page->address->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_address">
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->city_id->Visible) { // city_id ?>
        <td data-name="city_id" <?= $Page->city_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_city_id">
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->phone_number->Visible) { // phone_number ?>
        <td data-name="phone_number" <?= $Page->phone_number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_phone_number">
<span<?= $Page->phone_number->viewAttributes() ?>>
<?= $Page->phone_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->contact_name->Visible) { // contact_name ?>
        <td data-name="contact_name" <?= $Page->contact_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_contact_name">
<span<?= $Page->contact_name->viewAttributes() ?>>
<?= $Page->contact_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->description->Visible) { // description ?>
        <td data-name="description" <?= $Page->description->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_office_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
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
    ew.addEventHandlers("master_office");
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
        container: "gmp_master_office",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
