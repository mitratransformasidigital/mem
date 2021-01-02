<?php

namespace MEM\prjMitralPHP;

// Page object
$VQuotationListList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fv_quotation_listlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fv_quotation_listlist = currentForm = new ew.Form("fv_quotation_listlist", "list");
    fv_quotation_listlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fv_quotation_listlist");
});
var fv_quotation_listlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fv_quotation_listlistsrch = currentSearchForm = new ew.Form("fv_quotation_listlistsrch");

    // Dynamic selection lists

    // Filters
    fv_quotation_listlistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fv_quotation_listlistsrch");
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
<form name="fv_quotation_listlistsrch" id="fv_quotation_listlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fv_quotation_listlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="v_quotation_list">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> v_quotation_list">
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
<form name="fv_quotation_listlist" id="fv_quotation_listlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="v_quotation_list">
<div id="gmp_v_quotation_list" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_v_quotation_listlist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->quotation_no->Visible) { // quotation_no ?>
        <th data-name="quotation_no" class="<?= $Page->quotation_no->headerCellClass() ?>"><div id="elh_v_quotation_list_quotation_no" class="v_quotation_list_quotation_no"><?= $Page->renderSort($Page->quotation_no) ?></div></th>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { // quotation_date ?>
        <th data-name="quotation_date" class="<?= $Page->quotation_date->headerCellClass() ?>"><div id="elh_v_quotation_list_quotation_date" class="v_quotation_list_quotation_date"><?= $Page->renderSort($Page->quotation_date) ?></div></th>
<?php } ?>
<?php if ($Page->customer_name->Visible) { // customer_name ?>
        <th data-name="customer_name" class="<?= $Page->customer_name->headerCellClass() ?>"><div id="elh_v_quotation_list_customer_name" class="v_quotation_list_customer_name"><?= $Page->renderSort($Page->customer_name) ?></div></th>
<?php } ?>
<?php if ($Page->phone_number->Visible) { // phone_number ?>
        <th data-name="phone_number" class="<?= $Page->phone_number->headerCellClass() ?>"><div id="elh_v_quotation_list_phone_number" class="v_quotation_list_phone_number"><?= $Page->renderSort($Page->phone_number) ?></div></th>
<?php } ?>
<?php if ($Page->contact->Visible) { // contact ?>
        <th data-name="contact" class="<?= $Page->contact->headerCellClass() ?>"><div id="elh_v_quotation_list_contact" class="v_quotation_list_contact"><?= $Page->renderSort($Page->contact) ?></div></th>
<?php } ?>
<?php if ($Page->city->Visible) { // city ?>
        <th data-name="city" class="<?= $Page->city->headerCellClass() ?>"><div id="elh_v_quotation_list_city" class="v_quotation_list_city"><?= $Page->renderSort($Page->city) ?></div></th>
<?php } ?>
<?php if ($Page->employee_name->Visible) { // employee_name ?>
        <th data-name="employee_name" class="<?= $Page->employee_name->headerCellClass() ?>"><div id="elh_v_quotation_list_employee_name" class="v_quotation_list_employee_name"><?= $Page->renderSort($Page->employee_name) ?></div></th>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
        <th data-name="rate" class="<?= $Page->rate->headerCellClass() ?>"><div id="elh_v_quotation_list_rate" class="v_quotation_list_rate"><?= $Page->renderSort($Page->rate) ?></div></th>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
        <th data-name="qty" class="<?= $Page->qty->headerCellClass() ?>"><div id="elh_v_quotation_list_qty" class="v_quotation_list_qty"><?= $Page->renderSort($Page->qty) ?></div></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th data-name="total" class="<?= $Page->total->headerCellClass() ?>"><div id="elh_v_quotation_list_total" class="v_quotation_list_total"><?= $Page->renderSort($Page->total) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_v_quotation_list", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->quotation_no->Visible) { // quotation_no ?>
        <td data-name="quotation_no" <?= $Page->quotation_no->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_quotation_no">
<span<?= $Page->quotation_no->viewAttributes() ?>>
<?= $Page->quotation_no->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->quotation_date->Visible) { // quotation_date ?>
        <td data-name="quotation_date" <?= $Page->quotation_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_quotation_date">
<span<?= $Page->quotation_date->viewAttributes() ?>>
<?= $Page->quotation_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->customer_name->Visible) { // customer_name ?>
        <td data-name="customer_name" <?= $Page->customer_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_customer_name">
<span<?= $Page->customer_name->viewAttributes() ?>>
<?= $Page->customer_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->phone_number->Visible) { // phone_number ?>
        <td data-name="phone_number" <?= $Page->phone_number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_phone_number">
<span<?= $Page->phone_number->viewAttributes() ?>>
<?= $Page->phone_number->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->contact->Visible) { // contact ?>
        <td data-name="contact" <?= $Page->contact->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_contact">
<span<?= $Page->contact->viewAttributes() ?>>
<?= $Page->contact->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->city->Visible) { // city ?>
        <td data-name="city" <?= $Page->city->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_city">
<span<?= $Page->city->viewAttributes() ?>>
<?= $Page->city->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->employee_name->Visible) { // employee_name ?>
        <td data-name="employee_name" <?= $Page->employee_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_employee_name">
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rate->Visible) { // rate ?>
        <td data-name="rate" <?= $Page->rate->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_rate">
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->qty->Visible) { // qty ?>
        <td data-name="qty" <?= $Page->qty->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_qty">
<span<?= $Page->qty->viewAttributes() ?>>
<?= $Page->qty->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->total->Visible) { // total ?>
        <td data-name="total" <?= $Page->total->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_v_quotation_list_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
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
<?php
// Render aggregate row
$Page->RowType = ROWTYPE_AGGREGATE;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->TotalRecords > 0 && !$Page->isGridAdd() && !$Page->isGridEdit()) { ?>
<tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options
$Page->renderListOptions();

// Render list options (footer, left)
$Page->ListOptions->render("footer", "left");
?>
    <?php if ($Page->quotation_no->Visible) { // quotation_no ?>
        <td data-name="quotation_no" class="<?= $Page->quotation_no->footerCellClass() ?>"><span id="elf_v_quotation_list_quotation_no" class="v_quotation_list_quotation_no">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->quotation_date->Visible) { // quotation_date ?>
        <td data-name="quotation_date" class="<?= $Page->quotation_date->footerCellClass() ?>"><span id="elf_v_quotation_list_quotation_date" class="v_quotation_list_quotation_date">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->customer_name->Visible) { // customer_name ?>
        <td data-name="customer_name" class="<?= $Page->customer_name->footerCellClass() ?>"><span id="elf_v_quotation_list_customer_name" class="v_quotation_list_customer_name">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->phone_number->Visible) { // phone_number ?>
        <td data-name="phone_number" class="<?= $Page->phone_number->footerCellClass() ?>"><span id="elf_v_quotation_list_phone_number" class="v_quotation_list_phone_number">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->contact->Visible) { // contact ?>
        <td data-name="contact" class="<?= $Page->contact->footerCellClass() ?>"><span id="elf_v_quotation_list_contact" class="v_quotation_list_contact">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->city->Visible) { // city ?>
        <td data-name="city" class="<?= $Page->city->footerCellClass() ?>"><span id="elf_v_quotation_list_city" class="v_quotation_list_city">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->employee_name->Visible) { // employee_name ?>
        <td data-name="employee_name" class="<?= $Page->employee_name->footerCellClass() ?>"><span id="elf_v_quotation_list_employee_name" class="v_quotation_list_employee_name">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->rate->Visible) { // rate ?>
        <td data-name="rate" class="<?= $Page->rate->footerCellClass() ?>"><span id="elf_v_quotation_list_rate" class="v_quotation_list_rate">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->rate->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->qty->Visible) { // qty ?>
        <td data-name="qty" class="<?= $Page->qty->footerCellClass() ?>"><span id="elf_v_quotation_list_qty" class="v_quotation_list_qty">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->total->Visible) { // total ?>
        <td data-name="total" class="<?= $Page->total->footerCellClass() ?>"><span id="elf_v_quotation_list_total" class="v_quotation_list_total">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->total->ViewValue ?></span>
        </span></td>
    <?php } ?>
<?php
// Render list options (footer, right)
$Page->ListOptions->render("footer", "right");
?>
    </tr>
</tfoot>
<?php } ?>
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
    ew.addEventHandlers("v_quotation_list");
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
        container: "gmp_v_quotation_list",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
