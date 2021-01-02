<?php

namespace MEM\prjMitralPHP;

// Page object
$PrintQuotationSummary = &$Page;
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
    var fields = ew.vars.tables.print_quotation.fields;
    fsummary.addFields([
        ["quotation_id", [], fields.quotation_id.isInvalid],
        ["quotation_no", [], fields.quotation_no.isInvalid],
        ["quotation_date", [], fields.quotation_date.isInvalid],
        ["y_quotation_date", [ew.Validators.between], false],
        ["customer_name", [], fields.customer_name.isInvalid],
        ["y_customer_name", [ew.Validators.between], false],
        ["phone_number", [], fields.phone_number.isInvalid],
        ["y_phone_number", [ew.Validators.between], false],
        ["contact", [], fields.contact.isInvalid],
        ["y_contact", [ew.Validators.between], false],
        ["city", [], fields.city.isInvalid],
        ["y_city", [ew.Validators.between], false],
        ["employee_name", [], fields.employee_name.isInvalid],
        ["y_employee_name", [ew.Validators.between], false],
        ["rate", [], fields.rate.isInvalid],
        ["y_rate", [ew.Validators.between], false],
        ["qty", [], fields.qty.isInvalid],
        ["y_qty", [ew.Validators.between], false],
        ["total", [], fields.total.isInvalid],
        ["customer_address", [], fields.customer_address.isInvalid]
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
    fsummary.lists.quotation_id = <?= $Page->quotation_id->toClientList($Page) ?>;

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
<input type="hidden" name="t" value="print_quotation">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->quotation_id->Visible) { // quotation_id ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_quotation_id" class="ew-cell form-group">
        <label for="x_quotation_id" class="ew-search-caption ew-label"><?= $Page->quotation_id->caption() ?></label>
        <span id="el_print_quotation_quotation_id" class="ew-search-field">
    <select
        id="x_quotation_id"
        name="x_quotation_id"
        class="form-control ew-select<?= $Page->quotation_id->isInvalidClass() ?>"
        data-select2-id="print_quotation_x_quotation_id"
        data-table="print_quotation"
        data-field="x_quotation_id"
        data-value-separator="<?= $Page->quotation_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->quotation_id->getPlaceHolder()) ?>"
        <?= $Page->quotation_id->editAttributes() ?>>
        <?= $Page->quotation_id->selectOptionListHtml("x_quotation_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->quotation_id->getErrorMessage() ?></div>
<?= $Page->quotation_id->Lookup->getParamTag($Page, "p_x_quotation_id") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='print_quotation_x_quotation_id']"),
        options = { name: "x_quotation_id", selectId: "print_quotation_x_quotation_id", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.print_quotation.fields.quotation_id.selectOptions);
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
while ($Page->GroupCount <= count($Page->GroupRecords) && $Page->GroupCount <= $Page->DisplayGroups) {
?>
<?php
    // Show header
    if ($Page->ShowHeader) {
?>
<?php if ($Page->GroupCount > 1) { ?>
</tbody>
</table>
</div>
<!-- /.ew-grid-middle-panel -->
<!-- Report grid (end) -->
</div>
<!-- /.ew-grid -->
<template id="tpb<?= $Page->GroupCount - 1 ?>_print_quotation"><?= $Page->PageBreakContent ?></template>
<?php } ?>
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
<div id="gmp_print_quotation" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="<?= $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->quotation_id->Visible) { ?>
    <?php if ($Page->quotation_id->ShowGroupHeaderAsRow) { ?>
    <th data-name="quotation_id">&nbsp;</th>
    <?php } else { ?>
    <th data-name="quotation_id" class="<?= $Page->quotation_id->headerCellClass() ?>"><div class="print_quotation_quotation_id"><?= $Page->renderSort($Page->quotation_id) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { ?>
    <?php if ($Page->quotation_date->ShowGroupHeaderAsRow) { ?>
    <th data-name="quotation_date">&nbsp;</th>
    <?php } else { ?>
    <th data-name="quotation_date" class="<?= $Page->quotation_date->headerCellClass() ?>"><div class="print_quotation_quotation_date"><?= $Page->renderSort($Page->quotation_date) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->quotation_no->Visible) { ?>
    <th data-name="quotation_no" class="<?= $Page->quotation_no->headerCellClass() ?>"><div class="print_quotation_quotation_no"><?= $Page->renderSort($Page->quotation_no) ?></div></th>
<?php } ?>
<?php if ($Page->customer_name->Visible) { ?>
    <th data-name="customer_name" class="<?= $Page->customer_name->headerCellClass() ?>"><div class="print_quotation_customer_name"><?= $Page->renderSort($Page->customer_name) ?></div></th>
<?php } ?>
<?php if ($Page->phone_number->Visible) { ?>
    <th data-name="phone_number" class="<?= $Page->phone_number->headerCellClass() ?>"><div class="print_quotation_phone_number"><?= $Page->renderSort($Page->phone_number) ?></div></th>
<?php } ?>
<?php if ($Page->contact->Visible) { ?>
    <th data-name="contact" class="<?= $Page->contact->headerCellClass() ?>"><div class="print_quotation_contact"><?= $Page->renderSort($Page->contact) ?></div></th>
<?php } ?>
<?php if ($Page->city->Visible) { ?>
    <th data-name="city" class="<?= $Page->city->headerCellClass() ?>"><div class="print_quotation_city"><?= $Page->renderSort($Page->city) ?></div></th>
<?php } ?>
<?php if ($Page->employee_name->Visible) { ?>
    <th data-name="employee_name" class="<?= $Page->employee_name->headerCellClass() ?>"><div class="print_quotation_employee_name"><?= $Page->renderSort($Page->employee_name) ?></div></th>
<?php } ?>
<?php if ($Page->rate->Visible) { ?>
    <th data-name="rate" class="<?= $Page->rate->headerCellClass() ?>"><div class="print_quotation_rate"><?= $Page->renderSort($Page->rate) ?></div></th>
<?php } ?>
<?php if ($Page->qty->Visible) { ?>
    <th data-name="qty" class="<?= $Page->qty->headerCellClass() ?>"><div class="print_quotation_qty"><?= $Page->renderSort($Page->qty) ?></div></th>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
    <th data-name="total" class="<?= $Page->total->headerCellClass() ?>"><div class="print_quotation_total"><?= $Page->renderSort($Page->total) ?></div></th>
<?php } ?>
<?php if ($Page->customer_address->Visible) { ?>
    <th data-name="customer_address" class="<?= $Page->customer_address->headerCellClass() ?>"><div class="print_quotation_customer_address"><?= $Page->renderSort($Page->customer_address) ?></div></th>
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

    // Build detail SQL
    $where = DetailFilterSql($Page->quotation_id, $Page->getSqlFirstGroupField(), $Page->quotation_id->groupValue(), $Page->Dbid);
    if ($Page->PageFirstGroupFilter != "") {
        $Page->PageFirstGroupFilter .= " OR ";
    }
    $Page->PageFirstGroupFilter .= $where;
    if ($Page->Filter != "") {
        $where = "($Page->Filter) AND ($where)";
    }
    $sql = $Page->buildReportSql($Page->getSqlSelect(), $Page->getSqlFrom(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $where, $Page->Sort);
    $rs = $sql->execute();
    $Page->DetailRecords = $rs ? $rs->fetchAll() : [];
    $Page->DetailRecordCount = count($Page->DetailRecords);

    // Load detail records
    $Page->quotation_id->Records = &$Page->DetailRecords;
    $Page->quotation_id->LevelBreak = true; // Set field level break
    $Page->GroupCounter[1] = $Page->GroupCount;
    $Page->quotation_id->getCnt($Page->quotation_id->Records); // Get record count
    ?>
<?php if ($Page->quotation_id->Visible && $Page->quotation_id->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_TOTAL;
        $Page->RowTotalType = ROWTOTAL_GROUP;
        $Page->RowTotalSubType = ROWTOTAL_HEADER;
        $Page->RowGroupLevel = 1;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->quotation_id->Visible) { ?>
        <td data-field="quotation_id"<?= $Page->quotation_id->cellAttributes(); ?>><span class="ew-group-toggle icon-collapse"></span></td>
<?php } ?>
        <td data-field="quotation_id" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 1) ?>"<?= $Page->quotation_id->cellAttributes() ?>>
        <span class="ew-summary-caption d-inline-block print_quotation_quotation_id"><?= $Page->renderSort($Page->quotation_id) ?></span><?= $Language->phrase("SummaryColon") ?><template id="tpx<?= $Page->GroupCount ?>_print_quotation_quotation_id"><span<?= $Page->quotation_id->viewAttributes() ?>><?= $Page->quotation_id->GroupViewValue ?></span></template>
        <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->quotation_id->Count, 0); ?></span>)</span>
        </td>
    </tr>
<?php } ?>
    <?php
    $Page->quotation_date->getDistinctValues($Page->quotation_id->Records);
    $Page->setGroupCount(count($Page->quotation_date->DistinctValues), $Page->GroupCounter[1]);
    $Page->GroupCounter[2] = 0; // Init group count index
    foreach ($Page->quotation_date->DistinctValues as $quotation_date) { // Load records for this distinct value
    $Page->quotation_date->setGroupValue($quotation_date); // Set group value
    $Page->quotation_date->getDistinctRecords($Page->quotation_id->Records, $Page->quotation_date->groupValue());
    $Page->quotation_date->LevelBreak = true; // Set field level break
    $Page->GroupCounter[2]++;
    $Page->quotation_date->getCnt($Page->quotation_date->Records); // Get record count
    $Page->setGroupCount($Page->quotation_date->Count, $Page->GroupCounter[1], $Page->GroupCounter[2]);
    ?>
<?php if ($Page->quotation_date->Visible && $Page->quotation_date->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->quotation_date->setDbValue($quotation_date); // Set current value for quotation_date
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_TOTAL;
        $Page->RowTotalType = ROWTOTAL_GROUP;
        $Page->RowTotalSubType = ROWTOTAL_HEADER;
        $Page->RowGroupLevel = 2;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->quotation_id->Visible) { ?>
        <td data-field="quotation_id"<?= $Page->quotation_id->cellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { ?>
        <td data-field="quotation_date"<?= $Page->quotation_date->cellAttributes(); ?>><span class="ew-group-toggle icon-collapse"></span></td>
<?php } ?>
        <td data-field="quotation_date" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 2) ?>"<?= $Page->quotation_date->cellAttributes() ?>>
        <span class="ew-summary-caption d-inline-block print_quotation_quotation_date"><?= $Page->renderSort($Page->quotation_date) ?></span><?= $Language->phrase("SummaryColon") ?><template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_print_quotation_quotation_date"><span<?= $Page->quotation_date->viewAttributes() ?>><?= $Page->quotation_date->GroupViewValue ?></span></template>
        <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->quotation_date->Count, 0); ?></span>)</span>
        </td>
    </tr>
<?php } ?>
    <?php
    $Page->RecordCount = 0; // Reset record count
    foreach ($Page->quotation_date->Records as $record) {
        $Page->RecordCount++;
        $Page->RecordIndex++;
        $Page->loadRowValues($record);
?>
<?php
        // Render detail row
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_DETAIL;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->quotation_id->Visible) { ?>
    <?php if ($Page->quotation_id->ShowGroupHeaderAsRow) { ?>
        <td data-field="quotation_id"<?= $Page->quotation_id->cellAttributes(); ?>>&nbsp;</td>
    <?php } else { ?>
        <td data-field="quotation_id"<?= $Page->quotation_id->cellAttributes(); ?>><template id="tpx<?= $Page->GroupCount ?>_print_quotation_quotation_id"><span<?= $Page->quotation_id->viewAttributes() ?>><?= $Page->quotation_id->GroupViewValue ?></span></template></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { ?>
    <?php if ($Page->quotation_date->ShowGroupHeaderAsRow) { ?>
        <td data-field="quotation_date"<?= $Page->quotation_date->cellAttributes(); ?>>&nbsp;</td>
    <?php } else { ?>
        <td data-field="quotation_date"<?= $Page->quotation_date->cellAttributes(); ?>><template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_print_quotation_quotation_date"><span<?= $Page->quotation_date->viewAttributes() ?>><?= $Page->quotation_date->GroupViewValue ?></span></template></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->quotation_no->Visible) { ?>
        <td data-field="quotation_no"<?= $Page->quotation_no->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_quotation_no">
<span<?= $Page->quotation_no->viewAttributes() ?>>
<?= $Page->quotation_no->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->customer_name->Visible) { ?>
        <td data-field="customer_name"<?= $Page->customer_name->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_customer_name">
<span<?= $Page->customer_name->viewAttributes() ?>>
<?= $Page->customer_name->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->phone_number->Visible) { ?>
        <td data-field="phone_number"<?= $Page->phone_number->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_phone_number">
<span<?= $Page->phone_number->viewAttributes() ?>>
<?= $Page->phone_number->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->contact->Visible) { ?>
        <td data-field="contact"<?= $Page->contact->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_contact">
<span<?= $Page->contact->viewAttributes() ?>>
<?= $Page->contact->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->city->Visible) { ?>
        <td data-field="city"<?= $Page->city->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_city">
<span<?= $Page->city->viewAttributes() ?>>
<?= $Page->city->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->employee_name->Visible) { ?>
        <td data-field="employee_name"<?= $Page->employee_name->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_employee_name">
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->rate->Visible) { ?>
        <td data-field="rate"<?= $Page->rate->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_rate">
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->qty->Visible) { ?>
        <td data-field="qty"<?= $Page->qty->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_qty">
<span<?= $Page->qty->viewAttributes() ?>>
<?= $Page->qty->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
        <td data-field="total"<?= $Page->total->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->customer_address->Visible) { ?>
        <td data-field="customer_address"<?= $Page->customer_address->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_print_quotation_customer_address">
<span<?= $Page->customer_address->viewAttributes() ?>>
<?= $Page->customer_address->getViewValue() ?></span>
</template>
</td>
<?php } ?>
    </tr>
<?php
    }
    } // End group level 1
?>
<?php

    // Next group
    $Page->loadGroupRowValues();

    // Show header if page break
    if ($Page->isExport()) {
        $Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? false : ($Page->GroupCount % $Page->ExportPageBreakCount == 0);
    }

    // Page_Breaking server event
    if ($Page->ShowHeader) {
        $Page->pageBreaking($Page->ShowHeader, $Page->PageBreakContent);
    }
    $Page->GroupCount++;
} // End while
?>
<?php if ($Page->TotalGroups > 0) { ?>
</tbody>
<tfoot>
<?php
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_TOTAL;
    $Page->RowTotalType = ROWTOTAL_GRAND;
    $Page->RowTotalSubType = ROWTOTAL_FOOTER;
    $Page->RowAttrs["class"] = "ew-rpt-grand-summary";
    $Page->renderRow();
?>
<?php if ($Page->quotation_id->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->TotalCount, 0); ?></span>)</span></td></tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate">&nbsp;</td>
<?php } ?>
<?php if ($Page->quotation_no->Visible) { ?>
        <td data-field="quotation_no"<?= $Page->quotation_no->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->customer_name->Visible) { ?>
        <td data-field="customer_name"<?= $Page->customer_name->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->phone_number->Visible) { ?>
        <td data-field="phone_number"<?= $Page->phone_number->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->contact->Visible) { ?>
        <td data-field="contact"<?= $Page->contact->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->city->Visible) { ?>
        <td data-field="city"<?= $Page->city->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->employee_name->Visible) { ?>
        <td data-field="employee_name"<?= $Page->employee_name->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><template id="tptc_print_quotation_employee_name"><span<?= $Page->employee_name->viewAttributes() ?>><?= $Page->employee_name->CntViewValue ?></span></template></span></td>
<?php } ?>
<?php if ($Page->rate->Visible) { ?>
        <td data-field="rate"<?= $Page->rate->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><template id="tpts_print_quotation_rate"><span<?= $Page->rate->viewAttributes() ?>><?= $Page->rate->SumViewValue ?></span></template></span></td>
<?php } ?>
<?php if ($Page->qty->Visible) { ?>
        <td data-field="qty"<?= $Page->qty->cellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
        <td data-field="total"<?= $Page->total->cellAttributes() ?>><span class="ew-aggregate-caption"><?= $Language->phrase("RptSum") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><template id="tpts_print_quotation_total"><span<?= $Page->total->viewAttributes() ?>><?= $Page->total->SumViewValue ?></span></template></span></td>
<?php } ?>
<?php if ($Page->customer_address->Visible) { ?>
        <td data-field="customer_address"<?= $Page->customer_address->cellAttributes() ?>></td>
<?php } ?>
    </tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<?= FormatNumber($Page->TotalCount, 0); ?><?= $Language->phrase("RptDtlRec") ?>)</span></td></tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"><?= $Language->phrase("RptSum") ?></td>
<?php } ?>
<?php if ($Page->quotation_no->Visible) { ?>
        <td data-field="quotation_no"<?= $Page->quotation_no->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->customer_name->Visible) { ?>
        <td data-field="customer_name"<?= $Page->customer_name->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->phone_number->Visible) { ?>
        <td data-field="phone_number"<?= $Page->phone_number->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->contact->Visible) { ?>
        <td data-field="contact"<?= $Page->contact->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->city->Visible) { ?>
        <td data-field="city"<?= $Page->city->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->employee_name->Visible) { ?>
        <td data-field="employee_name"<?= $Page->employee_name->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->rate->Visible) { ?>
        <td data-field="rate"<?= $Page->rate->cellAttributes() ?>>
<template id="tpts_print_quotation_rate">
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->SumViewValue ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->qty->Visible) { ?>
        <td data-field="qty"<?= $Page->qty->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
        <td data-field="total"<?= $Page->total->cellAttributes() ?>>
<template id="tpts_print_quotation_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->SumViewValue ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->customer_address->Visible) { ?>
        <td data-field="customer_address"<?= $Page->customer_address->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
    </tr>
    <tr<?= $Page->rowAttributes() ?>>
<?php if ($Page->GroupColumnCount > 0) { ?>
        <td colspan="<?= $Page->GroupColumnCount ?>" class="ew-rpt-grp-aggregate"><?= $Language->phrase("RptCnt") ?></td>
<?php } ?>
<?php if ($Page->quotation_no->Visible) { ?>
        <td data-field="quotation_no"<?= $Page->quotation_no->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->customer_name->Visible) { ?>
        <td data-field="customer_name"<?= $Page->customer_name->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->phone_number->Visible) { ?>
        <td data-field="phone_number"<?= $Page->phone_number->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->contact->Visible) { ?>
        <td data-field="contact"<?= $Page->contact->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->city->Visible) { ?>
        <td data-field="city"<?= $Page->city->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->employee_name->Visible) { ?>
        <td data-field="employee_name"<?= $Page->employee_name->cellAttributes() ?>>
<template id="tptc_print_quotation_employee_name">
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->CntViewValue ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->rate->Visible) { ?>
        <td data-field="rate"<?= $Page->rate->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->qty->Visible) { ?>
        <td data-field="qty"<?= $Page->qty->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
        <td data-field="total"<?= $Page->total->cellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->customer_address->Visible) { ?>
        <td data-field="customer_address"<?= $Page->customer_address->cellAttributes() ?>>&nbsp;</td>
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
<?php if ($Page->isExport() || $Page->UseCustomTemplate) { ?>
<div id="tpd_print_quotationsummary"></div>
<template id="tpm_print_quotationsummary">
<div id="ct_PrintQuotationSummary"><?php $k = 1; ?><?php
$cnt = $Page->GroupCount - 1;
for ($i = 1; $i <= $cnt; $i++) {
?>
<?php
$cnt1 = $Page->getGroupCount($i);
for ($i1 = 1; $i1 <= $cnt1; $i1++) {
?>
<table class="ew-table no-border">
<tr><div class="text-right"><b>QUOTATION</b></div></tr>
<tr><div class="text-right"><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_print_quotation_quotation_date"></slot></div></tr>
</table>
<br>
<table class="ew-table no-border">
<tr><td><b>To</b></td></tr>
<tr><td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_1_print_quotation_customer_name"></slot></td></tr>
<tr><td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_1_print_quotation_customer_address"></slot> </td></tr>
<tr><td>Attn: <slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_1_print_quotation_contact"></slot></td></tr>
</table>
<br>
<table class="ew-table no-border"><tr><td><b>Quotation Number: </b> <slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_1_print_quotation_quotation_no"></slot> </td></tr>
</table>
<br>
<table class="ew-table no-border">
<tr><td>As Introduced by our senior staff, we are PT Mitra Transformasi Digital as the stated title providing application and resources to the company building and upadating their business systems.
Referring to the disccusion on your current project’s need of project manager/senior analyst personels that meets our resources skill and time frame, we propose to fullfill the opportunity by providing service on this quotation as below detail:
</td></tr>
</table>
<br>
<table class="table table-bordered table-sm ew-table ew-export-table w-100"><!-- table.ew-table (with border) -->
<tr>
<th><?= $Page->employee_name->caption() ?></th>
<th style="text-align:right"><?= $Page->rate->caption() ?></th>
<th style="text-align:right"><?= $Page->qty->caption() ?></th>
<th style="text-align:right"><?= $Page->total->caption() ?></th>
</tr>
<?php
for ($j = 1; $j <= $Page->getGroupCount($i, $i1); $j++) {
?>
<tr>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_print_quotation_employee_name"></slot></td>
<td style="text-align:right"><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_print_quotation_rate"></slot></td>
<td style="text-align:right"><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_print_quotation_qty"></slot></td>
<td style="text-align:right"><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_print_quotation_total"></slot></td>
</tr>
<?php
$k++;
}
?>
<?php
}
?>
<?php
if ($Page->ExportPageBreakCount > 0 && $Page->isExport()) {
if ($i % $Page->ExportPageBreakCount == 0 && $i < $cnt) {
?>
<slot class="ew-slot" name="tpb<?= $i ?>_print_quotation"></slot>
<?php
}
}
}
?>
<br>
<table class="ew-table no-border">
<tr><td><b>Term and Condition:</b></td></tr>
<tr><td> - Initial Duration: resuorce can be extended after this first time frame, as he/she meets the quality of delivery</td></tr>
<tr><td> - Price is subject to VAT 10% and include withholding tax for 2 %.</td></tr>
<tr><td> - Project Location is in <slot class="ew-slot" name="tpx1_1_print_quotation_customer_name"></slot></td></tr> 
<tr><td> - Price shall be paid monthly after invoicing</td></tr>
<tr><td> - Term of Payment: 30 days after invoice date</td></tr>
<tr><td> - Payment to remit to following account:</td></tr>
<tr><td>     <b>Bank</b>		: Bank Central Asia, KCP Pedurungan, Semarang</td></tr>
<tr><td>     <b>A/C No</b>		: 854-5520202</td></tr>
<tr><td>     <b>A/C Name</b>	: MITRA TRANSFORMASI DIGIT</td></tr>
<tr><td>For more information, please contact us below email or phones: 081553222001. Thanks for your kind attention and further good cooperations.
</td></tr></table>
<br>
<br>
<br>
<br>
<br>
<br>
<table class="ew-table no-border">
<tr><div class="text-right"><b>Marketing Dept.</b></tr>
<tr><div class="text-right"><b>Farida Zulkaidah Pane</b></tr>
</table>
</div>
</template>
<?php } ?>
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
</div>
<!-- /.ew-report -->
<?php } ?>
<script>
loadjs.ready(["jsrender", "makerjs"], function() {
    ew.templateData = { rows: <?= JsonEncode($Page->Rows) ?> };
    ew.applyTemplate("tpd_print_quotationsummary", "tpm_print_quotationsummary", "print_quotationsummary", "<?= $Page->CustomExport ?>", ew.templateData);
    loadjs.done("customtemplate");
});
</script>
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
