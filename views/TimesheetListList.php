<?php

namespace MEM\prjMitralPHP;

// Page object
$TimesheetListList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
if (!ew.vars.tables.timesheet_list) ew.vars.tables.timesheet_list = <?= JsonEncode(GetClientVar("tables", "timesheet_list")) ?>;
var currentForm, currentPageID;
var ftimesheet_listlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    ftimesheet_listlist = currentForm = new ew.Form("ftimesheet_listlist", "list");
    ftimesheet_listlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("ftimesheet_listlist");
});
var ftimesheet_listlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    ftimesheet_listlistsrch = currentSearchForm = new ew.Form("ftimesheet_listlistsrch");

    // Add fields
    var fields = ew.vars.tables.timesheet_list.fields;
    ftimesheet_listlistsrch.addFields([
        ["employee_username", [], fields.employee_username.isInvalid],
        ["work_date", [ew.Validators.datetime(0)], fields.work_date.isInvalid],
        ["y_work_date", [ew.Validators.between], false],
        ["time_in", [], fields.time_in.isInvalid],
        ["time_out", [], fields.time_out.isInvalid],
        ["description", [], fields.description.isInvalid],
        ["absence", [], fields.absence.isInvalid],
        ["days", [], fields.days.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        ftimesheet_listlistsrch.setInvalid();
    });

    // Validate form
    ftimesheet_listlistsrch.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj),
            rowIndex = "";
        $fobj.data("rowindex", rowIndex);

        // Validate fields
        if (!this.validateFields(rowIndex))
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    ftimesheet_listlistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ftimesheet_listlistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    ftimesheet_listlistsrch.lists.employee_username = <?= $Page->employee_username->toClientList($Page) ?>;

    // Filters
    ftimesheet_listlistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("ftimesheet_listlistsrch");
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
<form name="ftimesheet_listlistsrch" id="ftimesheet_listlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="ftimesheet_listlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="timesheet_list">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_employee_username" class="ew-cell form-group">
        <label for="x_employee_username" class="ew-search-caption ew-label"><?= $Page->employee_username->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_employee_username" id="z_employee_username" value="=">
</span>
        <span id="el_timesheet_list_employee_username" class="ew-search-field">
    <select
        id="x_employee_username"
        name="x_employee_username"
        class="form-control ew-select<?= $Page->employee_username->isInvalidClass() ?>"
        data-select2-id="timesheet_list_x_employee_username"
        data-table="timesheet_list"
        data-field="x_employee_username"
        data-value-separator="<?= $Page->employee_username->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->employee_username->getPlaceHolder()) ?>"
        <?= $Page->employee_username->editAttributes() ?>>
        <?= $Page->employee_username->selectOptionListHtml("x_employee_username") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->employee_username->getErrorMessage(false) ?></div>
<?= $Page->employee_username->Lookup->getParamTag($Page, "p_x_employee_username") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='timesheet_list_x_employee_username']"),
        options = { name: "x_employee_username", selectId: "timesheet_list_x_employee_username", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.timesheet_list.fields.employee_username.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->work_date->Visible) { // work_date ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_work_date" class="ew-cell form-group">
        <label for="x_work_date" class="ew-search-caption ew-label"><?= $Page->work_date->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_work_date" id="z_work_date" value="BETWEEN">
</span>
        <span id="el_timesheet_list_work_date" class="ew-search-field">
<input type="<?= $Page->work_date->getInputTextType() ?>" data-table="timesheet_list" data-field="x_work_date" name="x_work_date" id="x_work_date" placeholder="<?= HtmlEncode($Page->work_date->getPlaceHolder()) ?>" value="<?= $Page->work_date->EditValue ?>"<?= $Page->work_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->work_date->getErrorMessage(false) ?></div>
<?php if (!$Page->work_date->ReadOnly && !$Page->work_date->Disabled && !isset($Page->work_date->EditAttrs["readonly"]) && !isset($Page->work_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ftimesheet_listlistsrch", "datetimepicker"], function() {
    ew.createDateTimePicker("ftimesheet_listlistsrch", "x_work_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
        <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
        <span id="el2_timesheet_list_work_date" class="ew-search-field2">
<input type="<?= $Page->work_date->getInputTextType() ?>" data-table="timesheet_list" data-field="x_work_date" name="y_work_date" id="y_work_date" placeholder="<?= HtmlEncode($Page->work_date->getPlaceHolder()) ?>" value="<?= $Page->work_date->EditValue2 ?>"<?= $Page->work_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->work_date->getErrorMessage(false) ?></div>
<?php if (!$Page->work_date->ReadOnly && !$Page->work_date->Disabled && !isset($Page->work_date->EditAttrs["readonly"]) && !isset($Page->work_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ftimesheet_listlistsrch", "datetimepicker"], function() {
    ew.createDateTimePicker("ftimesheet_listlistsrch", "y_work_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow > 0) { ?>
</div>
    <?php } ?>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> timesheet_list">
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
<form name="ftimesheet_listlist" id="ftimesheet_listlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="timesheet_list">
<div id="gmp_timesheet_list" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_timesheet_listlist" class="table ew-table"><!-- .ew-table -->
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
        <th data-name="employee_username" class="<?= $Page->employee_username->headerCellClass() ?>"><div id="elh_timesheet_list_employee_username" class="timesheet_list_employee_username"><?= $Page->renderSort($Page->employee_username) ?></div></th>
<?php } ?>
<?php if ($Page->work_date->Visible) { // work_date ?>
        <th data-name="work_date" class="<?= $Page->work_date->headerCellClass() ?>"><div id="elh_timesheet_list_work_date" class="timesheet_list_work_date"><?= $Page->renderSort($Page->work_date) ?></div></th>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
        <th data-name="time_in" class="<?= $Page->time_in->headerCellClass() ?>"><div id="elh_timesheet_list_time_in" class="timesheet_list_time_in"><?= $Page->renderSort($Page->time_in) ?></div></th>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
        <th data-name="time_out" class="<?= $Page->time_out->headerCellClass() ?>"><div id="elh_timesheet_list_time_out" class="timesheet_list_time_out"><?= $Page->renderSort($Page->time_out) ?></div></th>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <th data-name="description" class="<?= $Page->description->headerCellClass() ?>"><div id="elh_timesheet_list_description" class="timesheet_list_description"><?= $Page->renderSort($Page->description) ?></div></th>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
        <th data-name="absence" class="<?= $Page->absence->headerCellClass() ?>"><div id="elh_timesheet_list_absence" class="timesheet_list_absence"><?= $Page->renderSort($Page->absence) ?></div></th>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
        <th data-name="days" class="<?= $Page->days->headerCellClass() ?>"><div id="elh_timesheet_list_days" class="timesheet_list_days"><?= $Page->renderSort($Page->days) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_timesheet_list", "data-rowtype" => $Page->RowType]);

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
<span id="el<?= $Page->RowCount ?>_timesheet_list_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->work_date->Visible) { // work_date ?>
        <td data-name="work_date" <?= $Page->work_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_timesheet_list_work_date">
<span<?= $Page->work_date->viewAttributes() ?>>
<?= $Page->work_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->time_in->Visible) { // time_in ?>
        <td data-name="time_in" <?= $Page->time_in->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_timesheet_list_time_in">
<span<?= $Page->time_in->viewAttributes() ?>>
<?= $Page->time_in->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->time_out->Visible) { // time_out ?>
        <td data-name="time_out" <?= $Page->time_out->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_timesheet_list_time_out">
<span<?= $Page->time_out->viewAttributes() ?>>
<?= $Page->time_out->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->description->Visible) { // description ?>
        <td data-name="description" <?= $Page->description->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_timesheet_list_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->absence->Visible) { // absence ?>
        <td data-name="absence" <?= $Page->absence->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_timesheet_list_absence">
<span<?= $Page->absence->viewAttributes() ?>>
<?= $Page->absence->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->days->Visible) { // days ?>
        <td data-name="days" <?= $Page->days->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_timesheet_list_days">
<span<?= $Page->days->viewAttributes() ?>>
<?= $Page->days->getViewValue() ?></span>
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
    <?php if ($Page->employee_username->Visible) { // employee_username ?>
        <td data-name="employee_username" class="<?= $Page->employee_username->footerCellClass() ?>"><span id="elf_timesheet_list_employee_username" class="timesheet_list_employee_username">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->work_date->Visible) { // work_date ?>
        <td data-name="work_date" class="<?= $Page->work_date->footerCellClass() ?>"><span id="elf_timesheet_list_work_date" class="timesheet_list_work_date">
        <span class="ew-aggregate"><?= $Language->phrase("COUNT") ?></span><span class="ew-aggregate-value">
        <?= $Page->work_date->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->time_in->Visible) { // time_in ?>
        <td data-name="time_in" class="<?= $Page->time_in->footerCellClass() ?>"><span id="elf_timesheet_list_time_in" class="timesheet_list_time_in">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->time_out->Visible) { // time_out ?>
        <td data-name="time_out" class="<?= $Page->time_out->footerCellClass() ?>"><span id="elf_timesheet_list_time_out" class="timesheet_list_time_out">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->description->Visible) { // description ?>
        <td data-name="description" class="<?= $Page->description->footerCellClass() ?>"><span id="elf_timesheet_list_description" class="timesheet_list_description">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->absence->Visible) { // absence ?>
        <td data-name="absence" class="<?= $Page->absence->footerCellClass() ?>"><span id="elf_timesheet_list_absence" class="timesheet_list_absence">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->absence->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->days->Visible) { // days ?>
        <td data-name="days" class="<?= $Page->days->footerCellClass() ?>"><span id="elf_timesheet_list_days" class="timesheet_list_days">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->days->ViewValue ?></span>
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
    ew.addEventHandlers("timesheet_list");
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
        container: "gmp_timesheet_list",
        width: "",
        height: "500px"
    });
});
</script>
<?php } ?>
<?php } ?>
