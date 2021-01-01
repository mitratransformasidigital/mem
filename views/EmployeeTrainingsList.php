<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeTrainingsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var femployee_trainingslist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    femployee_trainingslist = currentForm = new ew.Form("femployee_trainingslist", "list");
    femployee_trainingslist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("femployee_trainingslist");
});
var femployee_trainingslistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    femployee_trainingslistsrch = currentSearchForm = new ew.Form("femployee_trainingslistsrch");

    // Dynamic selection lists

    // Filters
    femployee_trainingslistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("femployee_trainingslistsrch");
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
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="femployee_trainingslistsrch" id="femployee_trainingslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="femployee_trainingslistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="employee_trainings">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> employee_trainings">
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
<form name="femployee_trainingslist" id="femployee_trainingslist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee_trainings">
<?php if ($Page->getCurrentMasterTable() == "employee" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="employee">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_employee_trainings" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_employee_trainingslist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <th data-name="employee_username" class="<?= $Page->employee_username->headerCellClass() ?>"><div id="elh_employee_trainings_employee_username" class="employee_trainings_employee_username"><?= $Page->renderSort($Page->employee_username) ?></div></th>
<?php } ?>
<?php if ($Page->training_name->Visible) { // training_name ?>
        <th data-name="training_name" class="<?= $Page->training_name->headerCellClass() ?>"><div id="elh_employee_trainings_training_name" class="employee_trainings_training_name"><?= $Page->renderSort($Page->training_name) ?></div></th>
<?php } ?>
<?php if ($Page->training_start->Visible) { // training_start ?>
        <th data-name="training_start" class="<?= $Page->training_start->headerCellClass() ?>"><div id="elh_employee_trainings_training_start" class="employee_trainings_training_start"><?= $Page->renderSort($Page->training_start) ?></div></th>
<?php } ?>
<?php if ($Page->training_end->Visible) { // training_end ?>
        <th data-name="training_end" class="<?= $Page->training_end->headerCellClass() ?>"><div id="elh_employee_trainings_training_end" class="employee_trainings_training_end"><?= $Page->renderSort($Page->training_end) ?></div></th>
<?php } ?>
<?php if ($Page->training_company->Visible) { // training_company ?>
        <th data-name="training_company" class="<?= $Page->training_company->headerCellClass() ?>"><div id="elh_employee_trainings_training_company" class="employee_trainings_training_company"><?= $Page->renderSort($Page->training_company) ?></div></th>
<?php } ?>
<?php if ($Page->certificate_start->Visible) { // certificate_start ?>
        <th data-name="certificate_start" class="<?= $Page->certificate_start->headerCellClass() ?>"><div id="elh_employee_trainings_certificate_start" class="employee_trainings_certificate_start"><?= $Page->renderSort($Page->certificate_start) ?></div></th>
<?php } ?>
<?php if ($Page->certificate_end->Visible) { // certificate_end ?>
        <th data-name="certificate_end" class="<?= $Page->certificate_end->headerCellClass() ?>"><div id="elh_employee_trainings_certificate_end" class="employee_trainings_certificate_end"><?= $Page->renderSort($Page->certificate_end) ?></div></th>
<?php } ?>
<?php if ($Page->training_document->Visible) { // training_document ?>
        <th data-name="training_document" class="<?= $Page->training_document->headerCellClass() ?>"><div id="elh_employee_trainings_training_document" class="employee_trainings_training_document"><?= $Page->renderSort($Page->training_document) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_employee_trainings", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username" <?= $Page->employee_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_trainings_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->training_name->Visible) { // training_name ?>
        <td data-name="training_name" <?= $Page->training_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_trainings_training_name">
<span<?= $Page->training_name->viewAttributes() ?>>
<?= $Page->training_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->training_start->Visible) { // training_start ?>
        <td data-name="training_start" <?= $Page->training_start->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_trainings_training_start">
<span<?= $Page->training_start->viewAttributes() ?>>
<?= $Page->training_start->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->training_end->Visible) { // training_end ?>
        <td data-name="training_end" <?= $Page->training_end->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_trainings_training_end">
<span<?= $Page->training_end->viewAttributes() ?>>
<?= $Page->training_end->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->training_company->Visible) { // training_company ?>
        <td data-name="training_company" <?= $Page->training_company->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_trainings_training_company">
<span<?= $Page->training_company->viewAttributes() ?>>
<?= $Page->training_company->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->certificate_start->Visible) { // certificate_start ?>
        <td data-name="certificate_start" <?= $Page->certificate_start->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_trainings_certificate_start">
<span<?= $Page->certificate_start->viewAttributes() ?>>
<?= $Page->certificate_start->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->certificate_end->Visible) { // certificate_end ?>
        <td data-name="certificate_end" <?= $Page->certificate_end->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_trainings_certificate_end">
<span<?= $Page->certificate_end->viewAttributes() ?>>
<?= $Page->certificate_end->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->training_document->Visible) { // training_document ?>
        <td data-name="training_document" <?= $Page->training_document->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_trainings_training_document">
<span<?= $Page->training_document->viewAttributes() ?>>
<?= GetFileViewTag($Page->training_document, $Page->training_document->getViewValue(), false) ?>
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
    ew.addEventHandlers("employee_trainings");
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
        container: "gmp_employee_trainings",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
