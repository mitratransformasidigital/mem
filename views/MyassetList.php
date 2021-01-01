<?php

namespace MEM\prjMitralPHP;

// Page object
$MyassetList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmyassetlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fmyassetlist = currentForm = new ew.Form("fmyassetlist", "list");
    fmyassetlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fmyassetlist");
});
var fmyassetlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fmyassetlistsrch = currentSearchForm = new ew.Form("fmyassetlistsrch");

    // Dynamic selection lists

    // Filters
    fmyassetlistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fmyassetlistsrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "employee") {
    if ($Page->MasterRecordExists) {
        include_once "views/EmployeeMaster.php";
    }
}
?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "myprofile") {
    if ($Page->MasterRecordExists) {
        include_once "views/MyprofileMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fmyassetlistsrch" id="fmyassetlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fmyassetlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="myasset">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> myasset">
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
<form name="fmyassetlist" id="fmyassetlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="myasset">
<?php if ($Page->getCurrentMasterTable() == "employee" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="employee">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "myprofile" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="myprofile">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_myasset" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_myassetlist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->asset_name->Visible) { // asset_name ?>
        <th data-name="asset_name" class="<?= $Page->asset_name->headerCellClass() ?>"><div id="elh_myasset_asset_name" class="myasset_asset_name"><?= $Page->renderSort($Page->asset_name) ?></div></th>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
        <th data-name="year" class="<?= $Page->year->headerCellClass() ?>"><div id="elh_myasset_year" class="myasset_year"><?= $Page->renderSort($Page->year) ?></div></th>
<?php } ?>
<?php if ($Page->serial_number->Visible) { // serial_number ?>
        <th data-name="serial_number" class="<?= $Page->serial_number->headerCellClass() ?>"><div id="elh_myasset_serial_number" class="myasset_serial_number"><?= $Page->renderSort($Page->serial_number) ?></div></th>
<?php } ?>
<?php if ($Page->value->Visible) { // value ?>
        <th data-name="value" class="<?= $Page->value->headerCellClass() ?>"><div id="elh_myasset_value" class="myasset_value"><?= $Page->renderSort($Page->value) ?></div></th>
<?php } ?>
<?php if ($Page->asset_received->Visible) { // asset_received ?>
        <th data-name="asset_received" class="<?= $Page->asset_received->headerCellClass() ?>"><div id="elh_myasset_asset_received" class="myasset_asset_received"><?= $Page->renderSort($Page->asset_received) ?></div></th>
<?php } ?>
<?php if ($Page->asset_return->Visible) { // asset_return ?>
        <th data-name="asset_return" class="<?= $Page->asset_return->headerCellClass() ?>"><div id="elh_myasset_asset_return" class="myasset_asset_return"><?= $Page->renderSort($Page->asset_return) ?></div></th>
<?php } ?>
<?php if ($Page->asset_picture->Visible) { // asset_picture ?>
        <th data-name="asset_picture" class="<?= $Page->asset_picture->headerCellClass() ?>"><div id="elh_myasset_asset_picture" class="myasset_asset_picture"><?= $Page->renderSort($Page->asset_picture) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_myasset", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->asset_name->Visible) { // asset_name ?>
        <td data-name="asset_name" <?= $Page->asset_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_asset_name">
<span<?= $Page->asset_name->viewAttributes() ?>>
<?= $Page->asset_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->year->Visible) { // year ?>
        <td data-name="year" <?= $Page->year->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_year">
<span<?= $Page->year->viewAttributes() ?>>
<?= $Page->year->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->serial_number->Visible) { // serial_number ?>
        <td data-name="serial_number" <?= $Page->serial_number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_serial_number">
<span<?= $Page->serial_number->viewAttributes() ?>>
<?= $Page->serial_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->value->Visible) { // value ?>
        <td data-name="value" <?= $Page->value->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_value">
<span<?= $Page->value->viewAttributes() ?>>
<?= $Page->value->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->asset_received->Visible) { // asset_received ?>
        <td data-name="asset_received" <?= $Page->asset_received->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_asset_received">
<span<?= $Page->asset_received->viewAttributes() ?>>
<?= $Page->asset_received->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->asset_return->Visible) { // asset_return ?>
        <td data-name="asset_return" <?= $Page->asset_return->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_asset_return">
<span<?= $Page->asset_return->viewAttributes() ?>>
<?= $Page->asset_return->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->asset_picture->Visible) { // asset_picture ?>
        <td data-name="asset_picture" <?= $Page->asset_picture->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_myasset_asset_picture">
<span<?= $Page->asset_picture->viewAttributes() ?>>
<?= GetFileViewTag($Page->asset_picture, $Page->asset_picture->getViewValue(), false) ?>
</span>
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
    ew.addEventHandlers("myasset");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
