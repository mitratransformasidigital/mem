<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterShiftList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmaster_shiftlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fmaster_shiftlist = currentForm = new ew.Form("fmaster_shiftlist", "list");
    fmaster_shiftlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fmaster_shiftlist");
});
var fmaster_shiftlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fmaster_shiftlistsrch = currentSearchForm = new ew.Form("fmaster_shiftlistsrch");

    // Dynamic selection lists

    // Filters
    fmaster_shiftlistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fmaster_shiftlistsrch");
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
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fmaster_shiftlistsrch" id="fmaster_shiftlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fmaster_shiftlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="master_shift">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> master_shift">
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
<form name="fmaster_shiftlist" id="fmaster_shiftlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_shift">
<div id="gmp_master_shift" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_master_shiftlist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->shift_name->Visible) { // shift_name ?>
        <th data-name="shift_name" class="<?= $Page->shift_name->headerCellClass() ?>"><div id="elh_master_shift_shift_name" class="master_shift_shift_name"><?= $Page->renderSort($Page->shift_name) ?></div></th>
<?php } ?>
<?php if ($Page->sunday_time_in->Visible) { // sunday_time_in ?>
        <th data-name="sunday_time_in" class="<?= $Page->sunday_time_in->headerCellClass() ?>"><div id="elh_master_shift_sunday_time_in" class="master_shift_sunday_time_in"><?= $Page->renderSort($Page->sunday_time_in) ?></div></th>
<?php } ?>
<?php if ($Page->sunday_time_out->Visible) { // sunday_time_out ?>
        <th data-name="sunday_time_out" class="<?= $Page->sunday_time_out->headerCellClass() ?>"><div id="elh_master_shift_sunday_time_out" class="master_shift_sunday_time_out"><?= $Page->renderSort($Page->sunday_time_out) ?></div></th>
<?php } ?>
<?php if ($Page->monday_time_in->Visible) { // monday_time_in ?>
        <th data-name="monday_time_in" class="<?= $Page->monday_time_in->headerCellClass() ?>"><div id="elh_master_shift_monday_time_in" class="master_shift_monday_time_in"><?= $Page->renderSort($Page->monday_time_in) ?></div></th>
<?php } ?>
<?php if ($Page->monday_time_out->Visible) { // monday_time_out ?>
        <th data-name="monday_time_out" class="<?= $Page->monday_time_out->headerCellClass() ?>"><div id="elh_master_shift_monday_time_out" class="master_shift_monday_time_out"><?= $Page->renderSort($Page->monday_time_out) ?></div></th>
<?php } ?>
<?php if ($Page->tuesday_time_in->Visible) { // tuesday_time_in ?>
        <th data-name="tuesday_time_in" class="<?= $Page->tuesday_time_in->headerCellClass() ?>"><div id="elh_master_shift_tuesday_time_in" class="master_shift_tuesday_time_in"><?= $Page->renderSort($Page->tuesday_time_in) ?></div></th>
<?php } ?>
<?php if ($Page->tuesday_time_out->Visible) { // tuesday_time_out ?>
        <th data-name="tuesday_time_out" class="<?= $Page->tuesday_time_out->headerCellClass() ?>"><div id="elh_master_shift_tuesday_time_out" class="master_shift_tuesday_time_out"><?= $Page->renderSort($Page->tuesday_time_out) ?></div></th>
<?php } ?>
<?php if ($Page->wednesday_time_in->Visible) { // wednesday_time_in ?>
        <th data-name="wednesday_time_in" class="<?= $Page->wednesday_time_in->headerCellClass() ?>"><div id="elh_master_shift_wednesday_time_in" class="master_shift_wednesday_time_in"><?= $Page->renderSort($Page->wednesday_time_in) ?></div></th>
<?php } ?>
<?php if ($Page->wednesday_time_out->Visible) { // wednesday_time_out ?>
        <th data-name="wednesday_time_out" class="<?= $Page->wednesday_time_out->headerCellClass() ?>"><div id="elh_master_shift_wednesday_time_out" class="master_shift_wednesday_time_out"><?= $Page->renderSort($Page->wednesday_time_out) ?></div></th>
<?php } ?>
<?php if ($Page->thursday_time_in->Visible) { // thursday_time_in ?>
        <th data-name="thursday_time_in" class="<?= $Page->thursday_time_in->headerCellClass() ?>"><div id="elh_master_shift_thursday_time_in" class="master_shift_thursday_time_in"><?= $Page->renderSort($Page->thursday_time_in) ?></div></th>
<?php } ?>
<?php if ($Page->thursday_time_out->Visible) { // thursday_time_out ?>
        <th data-name="thursday_time_out" class="<?= $Page->thursday_time_out->headerCellClass() ?>"><div id="elh_master_shift_thursday_time_out" class="master_shift_thursday_time_out"><?= $Page->renderSort($Page->thursday_time_out) ?></div></th>
<?php } ?>
<?php if ($Page->friday_time_in->Visible) { // friday_time_in ?>
        <th data-name="friday_time_in" class="<?= $Page->friday_time_in->headerCellClass() ?>"><div id="elh_master_shift_friday_time_in" class="master_shift_friday_time_in"><?= $Page->renderSort($Page->friday_time_in) ?></div></th>
<?php } ?>
<?php if ($Page->friday_time_out->Visible) { // friday_time_out ?>
        <th data-name="friday_time_out" class="<?= $Page->friday_time_out->headerCellClass() ?>"><div id="elh_master_shift_friday_time_out" class="master_shift_friday_time_out"><?= $Page->renderSort($Page->friday_time_out) ?></div></th>
<?php } ?>
<?php if ($Page->saturday_time_in->Visible) { // saturday_time_in ?>
        <th data-name="saturday_time_in" class="<?= $Page->saturday_time_in->headerCellClass() ?>"><div id="elh_master_shift_saturday_time_in" class="master_shift_saturday_time_in"><?= $Page->renderSort($Page->saturday_time_in) ?></div></th>
<?php } ?>
<?php if ($Page->saturday_time_out->Visible) { // saturday_time_out ?>
        <th data-name="saturday_time_out" class="<?= $Page->saturday_time_out->headerCellClass() ?>"><div id="elh_master_shift_saturday_time_out" class="master_shift_saturday_time_out"><?= $Page->renderSort($Page->saturday_time_out) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_master_shift", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->shift_name->Visible) { // shift_name ?>
        <td data-name="shift_name" <?= $Page->shift_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_shift_name">
<span<?= $Page->shift_name->viewAttributes() ?>>
<?= $Page->shift_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sunday_time_in->Visible) { // sunday_time_in ?>
        <td data-name="sunday_time_in" <?= $Page->sunday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_sunday_time_in">
<span<?= $Page->sunday_time_in->viewAttributes() ?>>
<?= $Page->sunday_time_in->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sunday_time_out->Visible) { // sunday_time_out ?>
        <td data-name="sunday_time_out" <?= $Page->sunday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_sunday_time_out">
<span<?= $Page->sunday_time_out->viewAttributes() ?>>
<?= $Page->sunday_time_out->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monday_time_in->Visible) { // monday_time_in ?>
        <td data-name="monday_time_in" <?= $Page->monday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_monday_time_in">
<span<?= $Page->monday_time_in->viewAttributes() ?>>
<?= $Page->monday_time_in->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->monday_time_out->Visible) { // monday_time_out ?>
        <td data-name="monday_time_out" <?= $Page->monday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_monday_time_out">
<span<?= $Page->monday_time_out->viewAttributes() ?>>
<?= $Page->monday_time_out->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tuesday_time_in->Visible) { // tuesday_time_in ?>
        <td data-name="tuesday_time_in" <?= $Page->tuesday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_tuesday_time_in">
<span<?= $Page->tuesday_time_in->viewAttributes() ?>>
<?= $Page->tuesday_time_in->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tuesday_time_out->Visible) { // tuesday_time_out ?>
        <td data-name="tuesday_time_out" <?= $Page->tuesday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_tuesday_time_out">
<span<?= $Page->tuesday_time_out->viewAttributes() ?>>
<?= $Page->tuesday_time_out->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->wednesday_time_in->Visible) { // wednesday_time_in ?>
        <td data-name="wednesday_time_in" <?= $Page->wednesday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_wednesday_time_in">
<span<?= $Page->wednesday_time_in->viewAttributes() ?>>
<?= $Page->wednesday_time_in->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->wednesday_time_out->Visible) { // wednesday_time_out ?>
        <td data-name="wednesday_time_out" <?= $Page->wednesday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_wednesday_time_out">
<span<?= $Page->wednesday_time_out->viewAttributes() ?>>
<?= $Page->wednesday_time_out->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->thursday_time_in->Visible) { // thursday_time_in ?>
        <td data-name="thursday_time_in" <?= $Page->thursday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_thursday_time_in">
<span<?= $Page->thursday_time_in->viewAttributes() ?>>
<?= $Page->thursday_time_in->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->thursday_time_out->Visible) { // thursday_time_out ?>
        <td data-name="thursday_time_out" <?= $Page->thursday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_thursday_time_out">
<span<?= $Page->thursday_time_out->viewAttributes() ?>>
<?= $Page->thursday_time_out->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->friday_time_in->Visible) { // friday_time_in ?>
        <td data-name="friday_time_in" <?= $Page->friday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_friday_time_in">
<span<?= $Page->friday_time_in->viewAttributes() ?>>
<?= $Page->friday_time_in->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->friday_time_out->Visible) { // friday_time_out ?>
        <td data-name="friday_time_out" <?= $Page->friday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_friday_time_out">
<span<?= $Page->friday_time_out->viewAttributes() ?>>
<?= $Page->friday_time_out->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->saturday_time_in->Visible) { // saturday_time_in ?>
        <td data-name="saturday_time_in" <?= $Page->saturday_time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_saturday_time_in">
<span<?= $Page->saturday_time_in->viewAttributes() ?>>
<?= $Page->saturday_time_in->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->saturday_time_out->Visible) { // saturday_time_out ?>
        <td data-name="saturday_time_out" <?= $Page->saturday_time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_master_shift_saturday_time_out">
<span<?= $Page->saturday_time_out->viewAttributes() ?>>
<?= $Page->saturday_time_out->getViewValue() ?></span>
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
    ew.addEventHandlers("master_shift");
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
        container: "gmp_master_shift",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
