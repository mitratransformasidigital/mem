<?php

namespace MEM\prjMitralPHP;

// Page object
$MytimesheetList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
if (!ew.vars.tables.mytimesheet) ew.vars.tables.mytimesheet = <?= JsonEncode(GetClientVar("tables", "mytimesheet")) ?>;
var currentForm, currentPageID;
var fmytimesheetlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fmytimesheetlist = currentForm = new ew.Form("fmytimesheetlist", "list");
    fmytimesheetlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fmytimesheetlist");
});
var fmytimesheetlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fmytimesheetlistsrch = currentSearchForm = new ew.Form("fmytimesheetlistsrch");

    // Add fields
    var fields = ew.vars.tables.mytimesheet.fields;
    fmytimesheetlistsrch.addFields([
        ["year", [], fields.year.isInvalid],
        ["month", [], fields.month.isInvalid],
        ["days", [], fields.days.isInvalid],
        ["y_days", [ew.Validators.between], false],
        ["sick", [], fields.sick.isInvalid],
        ["y_sick", [ew.Validators.between], false],
        ["leave", [], fields.leave.isInvalid],
        ["y_leave", [ew.Validators.between], false],
        ["permit", [], fields.permit.isInvalid],
        ["y_permit", [ew.Validators.between], false],
        ["absence", [], fields.absence.isInvalid],
        ["y_absence", [ew.Validators.between], false],
        ["timesheet_doc", [], fields.timesheet_doc.isInvalid],
        ["approved", [], fields.approved.isInvalid],
        ["y_approved", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fmytimesheetlistsrch.setInvalid();
    });

    // Validate form
    fmytimesheetlistsrch.validate = function () {
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
    fmytimesheetlistsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmytimesheetlistsrch.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fmytimesheetlistsrch.lists.year = <?= $Page->year->toClientList($Page) ?>;
    fmytimesheetlistsrch.lists.month = <?= $Page->month->toClientList($Page) ?>;

    // Filters
    fmytimesheetlistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fmytimesheetlistsrch");
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
<form name="fmytimesheetlistsrch" id="fmytimesheetlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fmytimesheetlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="mytimesheet">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->year->Visible) { // year ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_year" class="ew-cell form-group">
        <label for="x_year" class="ew-search-caption ew-label"><?= $Page->year->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_year" id="z_year" value="=">
</span>
        <span id="el_mytimesheet_year" class="ew-search-field">
    <select
        id="x_year"
        name="x_year"
        class="form-control ew-select<?= $Page->year->isInvalidClass() ?>"
        data-select2-id="mytimesheet_x_year"
        data-table="mytimesheet"
        data-field="x_year"
        data-value-separator="<?= $Page->year->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->year->getPlaceHolder()) ?>"
        <?= $Page->year->editAttributes() ?>>
        <?= $Page->year->selectOptionListHtml("x_year") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->year->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='mytimesheet_x_year']"),
        options = { name: "x_year", selectId: "mytimesheet_x_year", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.mytimesheet.fields.year.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mytimesheet.fields.year.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
    </div>
    <?php if ($Page->SearchColumnCount % $Page->SearchFieldsPerRow == 0) { ?>
</div>
    <?php } ?>
<?php } ?>
<?php if ($Page->month->Visible) { // month ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_month" class="ew-cell form-group">
        <label for="x_month" class="ew-search-caption ew-label"><?= $Page->month->caption() ?></label>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_month" id="z_month" value="=">
</span>
        <span id="el_mytimesheet_month" class="ew-search-field">
    <select
        id="x_month"
        name="x_month"
        class="form-control ew-select<?= $Page->month->isInvalidClass() ?>"
        data-select2-id="mytimesheet_x_month"
        data-table="mytimesheet"
        data-field="x_month"
        data-value-separator="<?= $Page->month->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->month->getPlaceHolder()) ?>"
        <?= $Page->month->editAttributes() ?>>
        <?= $Page->month->selectOptionListHtml("x_month") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->month->getErrorMessage(false) ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='mytimesheet_x_month']"),
        options = { name: "x_month", selectId: "mytimesheet_x_month", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.mytimesheet.fields.month.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.mytimesheet.fields.month.selectOptions);
    ew.createSelect(options);
});
</script>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> mytimesheet">
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
<form name="fmytimesheetlist" id="fmytimesheetlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mytimesheet">
<?php if ($Page->getCurrentMasterTable() == "employee" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="employee">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "myprofile" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="myprofile">
<input type="hidden" name="fk_employee_username" value="<?= HtmlEncode($Page->employee_username->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_mytimesheet" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_mytimesheetlist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->year->Visible) { // year ?>
        <th data-name="year" class="<?= $Page->year->headerCellClass() ?>"><div id="elh_mytimesheet_year" class="mytimesheet_year"><?= $Page->renderSort($Page->year) ?></div></th>
<?php } ?>
<?php if ($Page->month->Visible) { // month ?>
        <th data-name="month" class="<?= $Page->month->headerCellClass() ?>"><div id="elh_mytimesheet_month" class="mytimesheet_month"><?= $Page->renderSort($Page->month) ?></div></th>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
        <th data-name="days" class="<?= $Page->days->headerCellClass() ?>"><div id="elh_mytimesheet_days" class="mytimesheet_days"><?= $Page->renderSort($Page->days) ?></div></th>
<?php } ?>
<?php if ($Page->sick->Visible) { // sick ?>
        <th data-name="sick" class="<?= $Page->sick->headerCellClass() ?>"><div id="elh_mytimesheet_sick" class="mytimesheet_sick"><?= $Page->renderSort($Page->sick) ?></div></th>
<?php } ?>
<?php if ($Page->leave->Visible) { // leave ?>
        <th data-name="leave" class="<?= $Page->leave->headerCellClass() ?>"><div id="elh_mytimesheet_leave" class="mytimesheet_leave"><?= $Page->renderSort($Page->leave) ?></div></th>
<?php } ?>
<?php if ($Page->permit->Visible) { // permit ?>
        <th data-name="permit" class="<?= $Page->permit->headerCellClass() ?>"><div id="elh_mytimesheet_permit" class="mytimesheet_permit"><?= $Page->renderSort($Page->permit) ?></div></th>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
        <th data-name="absence" class="<?= $Page->absence->headerCellClass() ?>"><div id="elh_mytimesheet_absence" class="mytimesheet_absence"><?= $Page->renderSort($Page->absence) ?></div></th>
<?php } ?>
<?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
        <th data-name="timesheet_doc" class="<?= $Page->timesheet_doc->headerCellClass() ?>"><div id="elh_mytimesheet_timesheet_doc" class="mytimesheet_timesheet_doc"><?= $Page->renderSort($Page->timesheet_doc) ?></div></th>
<?php } ?>
<?php if ($Page->approved->Visible) { // approved ?>
        <th data-name="approved" class="<?= $Page->approved->headerCellClass() ?>"><div id="elh_mytimesheet_approved" class="mytimesheet_approved"><?= $Page->renderSort($Page->approved) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_mytimesheet", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->year->Visible) { // year ?>
        <td data-name="year" <?= $Page->year->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_year">
<span<?= $Page->year->viewAttributes() ?>>
<?= $Page->year->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->month->Visible) { // month ?>
        <td data-name="month" <?= $Page->month->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_month">
<span<?= $Page->month->viewAttributes() ?>>
<?= $Page->month->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->days->Visible) { // days ?>
        <td data-name="days" <?= $Page->days->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_days">
<span<?= $Page->days->viewAttributes() ?>>
<?= $Page->days->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sick->Visible) { // sick ?>
        <td data-name="sick" <?= $Page->sick->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_sick">
<span<?= $Page->sick->viewAttributes() ?>>
<?= $Page->sick->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->leave->Visible) { // leave ?>
        <td data-name="leave" <?= $Page->leave->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_leave">
<span<?= $Page->leave->viewAttributes() ?>>
<?= $Page->leave->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->permit->Visible) { // permit ?>
        <td data-name="permit" <?= $Page->permit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_permit">
<span<?= $Page->permit->viewAttributes() ?>>
<?= $Page->permit->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->absence->Visible) { // absence ?>
        <td data-name="absence" <?= $Page->absence->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_absence">
<span<?= $Page->absence->viewAttributes() ?>>
<?= $Page->absence->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
        <td data-name="timesheet_doc" <?= $Page->timesheet_doc->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_timesheet_doc">
<span<?= $Page->timesheet_doc->viewAttributes() ?>>
<?= GetFileViewTag($Page->timesheet_doc, $Page->timesheet_doc->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->approved->Visible) { // approved ?>
        <td data-name="approved" <?= $Page->approved->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_mytimesheet_approved">
<span<?= $Page->approved->viewAttributes() ?>>
<?= $Page->approved->getViewValue() ?></span>
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
    <?php if ($Page->year->Visible) { // year ?>
        <td data-name="year" class="<?= $Page->year->footerCellClass() ?>"><span id="elf_mytimesheet_year" class="mytimesheet_year">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->month->Visible) { // month ?>
        <td data-name="month" class="<?= $Page->month->footerCellClass() ?>"><span id="elf_mytimesheet_month" class="mytimesheet_month">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->days->Visible) { // days ?>
        <td data-name="days" class="<?= $Page->days->footerCellClass() ?>"><span id="elf_mytimesheet_days" class="mytimesheet_days">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->days->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->sick->Visible) { // sick ?>
        <td data-name="sick" class="<?= $Page->sick->footerCellClass() ?>"><span id="elf_mytimesheet_sick" class="mytimesheet_sick">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->sick->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->leave->Visible) { // leave ?>
        <td data-name="leave" class="<?= $Page->leave->footerCellClass() ?>"><span id="elf_mytimesheet_leave" class="mytimesheet_leave">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->leave->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->permit->Visible) { // permit ?>
        <td data-name="permit" class="<?= $Page->permit->footerCellClass() ?>"><span id="elf_mytimesheet_permit" class="mytimesheet_permit">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->permit->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->absence->Visible) { // absence ?>
        <td data-name="absence" class="<?= $Page->absence->footerCellClass() ?>"><span id="elf_mytimesheet_absence" class="mytimesheet_absence">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->absence->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
        <td data-name="timesheet_doc" class="<?= $Page->timesheet_doc->footerCellClass() ?>"><span id="elf_mytimesheet_timesheet_doc" class="mytimesheet_timesheet_doc">
        &nbsp;
        </span></td>
    <?php } ?>
    <?php if ($Page->approved->Visible) { // approved ?>
        <td data-name="approved" class="<?= $Page->approved->footerCellClass() ?>"><span id="elf_mytimesheet_approved" class="mytimesheet_approved">
        &nbsp;
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
    ew.addEventHandlers("mytimesheet");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>