<?php

namespace MEM\prjMitralPHP;

// Page object
$Top10DaysSummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentForm, currentPageID;
var fsummary, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fsummary = currentForm = new ew.Form("fsummary", "summary");
    currentPageID = ew.PAGE_ID = "summary";

    // Add fields
    var fields = ew.vars.tables.Top_10_Days.fields;
    fsummary.addFields([
        ["employee_username", [], fields.employee_username.isInvalid],
        ["year", [], fields.year.isInvalid],
        ["month", [], fields.month.isInvalid],
        ["days", [], fields.days.isInvalid],
        ["y_days", [ew.Validators.between], false]
    ]);

    // Set invalid fields
    $(function() {
        fsummary.setInvalid();
    });

    // Validate form
    fsummary.validate = function () {
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
    fsummary.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsummary.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fsummary.lists.year = <?= $Page->year->toClientList($Page) ?>;

    // Filters
    fsummary.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fsummary");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<a id="top"></a>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<!-- Content Container -->
<div id="ew-report" class="ew-report container-fluid">
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<div class="btn-toolbar ew-toolbar">
<?php
if (!$Page->DrillDownInPanel) {
    $Page->ExportOptions->render("body");
    $Page->SearchOptions->render("body");
    $Page->FilterOptions->render("body");
}
?>
</div>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<div class="row">
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<!-- Center Container -->
<div id="ew-center" class="<?= $Page->CenterContentClass ?>">
<?php } ?>
<!-- Summary report (begin) -->
<div id="report_summary">
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fsummary" id="fsummary" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fsummary-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="Top_10_Days">
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
        <span id="el_Top_10_Days_year" class="ew-search-field">
    <select
        id="x_year"
        name="x_year"
        class="form-control ew-select<?= $Page->year->isInvalidClass() ?>"
        data-select2-id="Top_10_Days_x_year"
        data-table="Top_10_Days"
        data-field="x_year"
        data-value-separator="<?= $Page->year->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->year->getPlaceHolder()) ?>"
        <?= $Page->year->editAttributes() ?>>
        <?= $Page->year->selectOptionListHtml("x_year") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->year->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='Top_10_Days_x_year']"),
        options = { name: "x_year", selectId: "Top_10_Days_x_year", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.Top_10_Days.fields.year.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.Top_10_Days.fields.year.selectOptions);
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
    <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
</div>
    </div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php } ?>
<?php
while ($Page->RecordCount < count($Page->DetailRecords) && $Page->RecordCount < $Page->DisplayGroups) {
?>
<?php
    // Show header
    if ($Page->ShowHeader) {
?>
<div class="<?php if (!$Page->isExport("word") && !$Page->isExport("excel")) { ?>card ew-card <?php } ?>ew-grid"<?= $Page->ReportTableStyle ?>>
<?php if (!$Page->isExport() && !($Page->DrillDown && $Page->TotalGroups > 0)) { ?>
<!-- Top pager -->
<div class="card-header ew-grid-upper-panel">
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
</form>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<div id="gmp_Top_10_Days" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="<?= $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->employee_username->Visible) { ?>
    <th data-name="employee_username" class="<?= $Page->employee_username->headerCellClass() ?>"><div class="Top_10_Days_employee_username"><?= $Page->renderSort($Page->employee_username) ?></div></th>
<?php } ?>
<?php if ($Page->year->Visible) { ?>
    <th data-name="year" class="<?= $Page->year->headerCellClass() ?>"><div class="Top_10_Days_year"><?= $Page->renderSort($Page->year) ?></div></th>
<?php } ?>
<?php if ($Page->month->Visible) { ?>
    <th data-name="month" class="<?= $Page->month->headerCellClass() ?>"><div class="Top_10_Days_month"><?= $Page->renderSort($Page->month) ?></div></th>
<?php } ?>
<?php if ($Page->days->Visible) { ?>
    <th data-name="days" class="<?= $Page->days->headerCellClass() ?>"><div class="Top_10_Days_days"><?= $Page->renderSort($Page->days) ?></div></th>
<?php } ?>
    </tr>
</thead>
<tbody>
<?php
        if ($Page->TotalGroups == 0) {
            break; // Show header only
        }
        $Page->ShowHeader = false;
    } // End show header
?>
<?php
    $Page->loadRowValues($Page->DetailRecords[$Page->RecordCount]);
    $Page->RecordCount++;
    $Page->RecordIndex++;
?>
<?php
        // Render detail row
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_DETAIL;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->employee_username->Visible) { ?>
        <td data-field="employee_username"<?= $Page->employee_username->cellAttributes() ?>>
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->year->Visible) { ?>
        <td data-field="year"<?= $Page->year->cellAttributes() ?>>
<span<?= $Page->year->viewAttributes() ?>>
<?= $Page->year->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->month->Visible) { ?>
        <td data-field="month"<?= $Page->month->cellAttributes() ?>>
<span<?= $Page->month->viewAttributes() ?>>
<?= $Page->month->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->days->Visible) { ?>
        <td data-field="days"<?= $Page->days->cellAttributes() ?>>
<span<?= $Page->days->viewAttributes() ?>>
<?= $Page->days->getViewValue() ?></span>
</td>
<?php } ?>
    </tr>
<?php
} // End while
?>
<?php if ($Page->TotalGroups > 0) { ?>
</tbody>
<tfoot>
<?php if (($Page->StopGroup - $Page->StartGroup + 1) != $Page->TotalGroups) { ?>
<?php
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_PAGE;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowAttrs["class"] = "ew-rpt-page-summary";
    $Page->renderRow();
?>
<?php if ($Page->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes(); ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptPageSummary") ?> <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->PageTotalCount, 0); ?></span>)</span></td></tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate">&nbsp;</td>
<?php } ?>
<?php if ($Page->employee_username->Visible) { ?>
        <td data-field="employee_username"<?= $Page->employee_username->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><span<?= $Page->employee_username->viewAttributes() ?>><?= $Page->employee_username->CntViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->year->Visible) { ?>
        <td data-field="year"<?= $Page->year->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->month->Visible) { ?>
        <td data-field="month"<?= $Page->month->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->days->Visible) { ?>
        <td data-field="days"<?= $Page->days->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><span<?= $Page->days->viewAttributes() ?>><?= $Page->days->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes(); ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptPageSummary") ?> <span class="ew-summary-count">(<?= FormatNumber($Page->PageTotalCount, 0); ?><?= $Language->phrase("RptDtlRec") ?>)</span></td></tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->employee_username->Visible) { ?>
        <td data-field="employee_username"<?= $Page->employee_username->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->year->Visible) { ?>
        <td data-field="year"<?= $Page->year->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->month->Visible) { ?>
        <td data-field="month"<?= $Page->month->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->days->Visible) { ?>
        <td data-field="days"<?= $Page->days->cellAttributes() ?>><span class="ew-aggregate"><?= $Language->phrase("RptSum") ?></span><?= $Language->phrase("AggregateColon") ?>
<span<?= $Page->days->viewAttributes() ?>>
<?= $Page->days->SumViewValue ?></span>
</td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->employee_username->Visible) { ?>
        <td data-field="employee_username"<?= $Page->employee_username->cellAttributes() ?>><span class="ew-aggregate"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateColon") ?>
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->CntViewValue ?></span>
</td>
<?php } ?>
<?php if ($Page->year->Visible) { ?>
        <td data-field="year"<?= $Page->year->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->month->Visible) { ?>
        <td data-field="month"<?= $Page->month->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->days->Visible) { ?>
        <td data-field="days"<?= $Page->days->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
    </tr>
<?php } ?>
<?php } ?>
<?php
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GRAND;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowAttrs["class"] = "ew-rpt-grand-summary";
    $Page->renderRow();
?>
<?php if ($Page->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->TotalCount, 0); ?></span>)</span></td></tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate">&nbsp;</td>
<?php } ?>
<?php if ($Page->employee_username->Visible) { ?>
        <td data-field="employee_username"<?= $Page->employee_username->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><span<?= $Page->employee_username->viewAttributes() ?>><?= $Page->employee_username->CntViewValue ?></span></span></td>
<?php } ?>
<?php if ($Page->year->Visible) { ?>
        <td data-field="year"<?= $Page->year->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->month->Visible) { ?>
        <td data-field="month"<?= $Page->month->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->days->Visible) { ?>
        <td data-field="days"<?= $Page->days->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><span<?= $Page->days->viewAttributes() ?>><?= $Page->days->SumViewValue ?></span></span></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<?= FormatNumber($Page->TotalCount, 0); ?><?= $Language->phrase("RptDtlRec") ?>)</span></td></tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->employee_username->Visible) { ?>
        <td data-field="employee_username"<?= $Page->employee_username->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->year->Visible) { ?>
        <td data-field="year"<?= $Page->year->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->month->Visible) { ?>
        <td data-field="month"<?= $Page->month->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->days->Visible) { ?>
        <td data-field="days"<?= $Page->days->cellAttributes() ?>><span class="ew-aggregate"><?= $Language->phrase("RptSum") ?></span><?= $Language->phrase("AggregateColon") ?>
<span<?= $Page->days->viewAttributes() ?>>
<?= $Page->days->SumViewValue ?></span>
</td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->employee_username->Visible) { ?>
        <td data-field="employee_username"<?= $Page->employee_username->cellAttributes() ?>><span class="ew-aggregate"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateColon") ?>
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->CntViewValue ?></span>
</td>
<?php } ?>
<?php if ($Page->year->Visible) { ?>
        <td data-field="year"<?= $Page->year->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->month->Visible) { ?>
        <td data-field="month"<?= $Page->month->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->days->Visible) { ?>
        <td data-field="days"<?= $Page->days->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
    </tr>
<?php } ?>
</tfoot>
</table>
</div>
<!-- /.ew-grid-middle-panel -->
<!-- Report grid (end) -->
</div>
<!-- /.ew-grid -->
<?php } ?>
</div>
<!-- /#report-summary -->
<!-- Summary report (end) -->
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
</div>
<!-- /#ew-center -->
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
</div>
<!-- /.row -->
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
<!-- Bottom Container -->
<div class="row">
    <div id="ew-bottom" class="<?= $Page->BottomContentClass ?>">
<?php } ?>
<?php
if (!$DashboardReport) {
    // Set up page break
    if (($Page->isExport("print") || $Page->isExport("pdf") || $Page->isExport("email") || $Page->isExport("excel") && Config("USE_PHPEXCEL") || $Page->isExport("word") && Config("USE_PHPWORD")) && $Page->ExportChartPageBreak) {
        // Page_Breaking server event
        $Page->pageBreaking($Page->ExportChartPageBreak, $Page->PageBreakContent);

        // Set up chart page break
        $Page->Top_10_Days->PageBreakType = "before"; // Page break type
        $Page->Top_10_Days->PageBreak = $Page->ExportChartPageBreak;
        $Page->Top_10_Days->PageBreakContent = $Page->PageBreakContent;
    }

    // Set up chart drilldown
    $Page->Top_10_Days->DrillDownInPanel = $Page->DrillDownInPanel;
    $Page->Top_10_Days->render("ew-chart-bottom");
}
?>
<?php if (!$DashboardReport && !$Page->isExport("email") && !$Page->DrillDown && $Page->Top_10_Days->hasData()) { ?>
<?php if (!$Page->isExport()) { ?>
<div class="mb-3"><a href="#" class="ew-top-link" onclick="$(document).scrollTop($('#top').offset().top); return false;"><?= $Language->phrase("Top") ?></a></div>
<?php } ?>
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
    </div>
</div>
<!-- /#ew-bottom -->
<?php } ?>
<?php if ((!$Page->isExport() || $Page->isExport("print")) && !$DashboardReport) { ?>
</div>
<!-- /.ew-report -->
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
