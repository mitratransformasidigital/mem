<?php

namespace MEM\prjMitralPHP;

// Page object
$RptQuotationPrintSummary = &$Page;
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
    var fields = ew.vars.tables.rpt_quotation_print.fields;
    fsummary.addFields([
        ["quotation_no", [], fields.quotation_no.isInvalid],
        ["y_quotation_no", [ew.Validators.between], false],
        ["quotation_date", [], fields.quotation_date.isInvalid],
        ["y_quotation_date", [ew.Validators.between], false],
        ["customer_name", [], fields.customer_name.isInvalid],
        ["y_customer_name", [ew.Validators.between], false],
        ["address", [], fields.address.isInvalid],
        ["y_address", [ew.Validators.between], false],
        ["city", [], fields.city.isInvalid],
        ["y_city", [ew.Validators.between], false],
        ["province", [], fields.province.isInvalid],
        ["y_province", [ew.Validators.between], false],
        ["contact", [], fields.contact.isInvalid],
        ["y_contact", [ew.Validators.between], false],
        ["employee_name", [], fields.employee_name.isInvalid],
        ["rate", [], fields.rate.isInvalid],
        ["y_rate", [ew.Validators.between], false],
        ["qty", [], fields.qty.isInvalid],
        ["y_qty", [ew.Validators.between], false],
        ["Total", [], fields.Total.isInvalid],
        ["ID", [], fields.ID.isInvalid]
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
    fsummary.lists.quotation_no = <?= $Page->quotation_no->toClientList($Page) ?>;

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
<input type="hidden" name="t" value="rpt_quotation_print">
    <div class="ew-extended-search">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->quotation_no->Visible) { // quotation_no ?>
    <?php
        $Page->SearchColumnCount++;
        if (($Page->SearchColumnCount - 1) % $Page->SearchFieldsPerRow == 0) {
            $Page->SearchRowCount++;
    ?>
<div id="xsr_<?= $Page->SearchRowCount ?>" class="ew-row d-sm-flex">
    <?php
        }
     ?>
    <div id="xsc_quotation_no" class="ew-cell form-group">
        <label class="ew-search-caption ew-label"><?= $Page->quotation_no->caption() ?></label>
        <span class="ew-search-operator">
<select name="z_quotation_no" id="z_quotation_no" class="custom-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="<>"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="<"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="<="<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value=">"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value=">="<?= $Page->quotation_no->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->quotation_no->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</span>
        <span id="el_rpt_quotation_print_quotation_no" class="ew-search-field">
<?php
$onchange = $Page->quotation_no->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->quotation_no->EditAttrs["onchange"] = "";
?>
<span id="as_x_quotation_no" class="ew-auto-suggest">
    <input type="<?= $Page->quotation_no->getInputTextType() ?>" class="form-control" name="sv_x_quotation_no" id="sv_x_quotation_no" value="<?= RemoveHtml($Page->quotation_no->EditValue) ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->quotation_no->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->quotation_no->getPlaceHolder()) ?>"<?= $Page->quotation_no->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="rpt_quotation_print" data-field="x_quotation_no" data-input="sv_x_quotation_no" data-value-separator="<?= $Page->quotation_no->displayValueSeparatorAttribute() ?>" name="x_quotation_no" id="x_quotation_no" value="<?= HtmlEncode($Page->quotation_no->AdvancedSearch->SearchValue) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->quotation_no->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsummary"], function() {
    fsummary.createAutoSuggest(Object.assign({"id":"x_quotation_no","forceSelect":false}, ew.vars.tables.rpt_quotation_print.fields.quotation_no.autoSuggestOptions));
});
</script>
<?= $Page->quotation_no->Lookup->getParamTag($Page, "p_x_quotation_no") ?>
</span>
        <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
        <span id="el2_rpt_quotation_print_quotation_no" class="ew-search-field2 d-none">
<?php
$onchange = $Page->quotation_no->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->quotation_no->EditAttrs["onchange"] = "";
?>
<span id="as_y_quotation_no" class="ew-auto-suggest">
    <input type="<?= $Page->quotation_no->getInputTextType() ?>" class="form-control" name="sv_y_quotation_no" id="sv_y_quotation_no" value="<?= RemoveHtml($Page->quotation_no->EditValue2) ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->quotation_no->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->quotation_no->getPlaceHolder()) ?>"<?= $Page->quotation_no->editAttributes() ?>>
</span>
<input type="hidden" is="selection-list" class="form-control" data-table="rpt_quotation_print" data-field="x_quotation_no" data-input="sv_y_quotation_no" data-value-separator="<?= $Page->quotation_no->displayValueSeparatorAttribute() ?>" name="y_quotation_no" id="y_quotation_no" value="<?= HtmlEncode($Page->quotation_no->AdvancedSearch->SearchValue2) ?>"<?= $onchange ?>>
<div class="invalid-feedback"><?= $Page->quotation_no->getErrorMessage() ?></div>
<script>
loadjs.ready(["fsummary"], function() {
    fsummary.createAutoSuggest(Object.assign({"id":"y_quotation_no","forceSelect":false}, ew.vars.tables.rpt_quotation_print.fields.quotation_no.autoSuggestOptions));
});
</script>
<?= $Page->quotation_no->Lookup->getParamTag($Page, "p_x_quotation_no") ?>
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
<template id="tpb<?= $Page->GroupCount - 1 ?>_rpt_quotation_print"><?= $Page->PageBreakContent ?></template>
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
<div id="gmp_rpt_quotation_print" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="<?= $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->quotation_no->Visible) { ?>
    <?php if ($Page->quotation_no->ShowGroupHeaderAsRow) { ?>
    <th data-name="quotation_no">&nbsp;</th>
    <?php } else { ?>
    <th data-name="quotation_no" class="<?= $Page->quotation_no->headerCellClass() ?>" style="white-space: nowrap;"><div class="rpt_quotation_print_quotation_no"><?= $Page->renderSort($Page->quotation_no) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->customer_name->Visible) { ?>
    <?php if ($Page->customer_name->ShowGroupHeaderAsRow) { ?>
    <th data-name="customer_name">&nbsp;</th>
    <?php } else { ?>
    <th data-name="customer_name" class="<?= $Page->customer_name->headerCellClass() ?>"><div class="rpt_quotation_print_customer_name"><?= $Page->renderSort($Page->customer_name) ?></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { ?>
    <th data-name="quotation_date" class="<?= $Page->quotation_date->headerCellClass() ?>"><div class="rpt_quotation_print_quotation_date"><?= $Page->renderSort($Page->quotation_date) ?></div></th>
<?php } ?>
<?php if ($Page->address->Visible) { ?>
    <th data-name="address" class="<?= $Page->address->headerCellClass() ?>" style="white-space: nowrap;"><div class="rpt_quotation_print_address"><?= $Page->renderSort($Page->address) ?></div></th>
<?php } ?>
<?php if ($Page->city->Visible) { ?>
    <th data-name="city" class="<?= $Page->city->headerCellClass() ?>"><div class="rpt_quotation_print_city"><?= $Page->renderSort($Page->city) ?></div></th>
<?php } ?>
<?php if ($Page->province->Visible) { ?>
    <th data-name="province" class="<?= $Page->province->headerCellClass() ?>"><div class="rpt_quotation_print_province"><?= $Page->renderSort($Page->province) ?></div></th>
<?php } ?>
<?php if ($Page->contact->Visible) { ?>
    <th data-name="contact" class="<?= $Page->contact->headerCellClass() ?>"><div class="rpt_quotation_print_contact"><?= $Page->renderSort($Page->contact) ?></div></th>
<?php } ?>
<?php if ($Page->employee_name->Visible) { ?>
    <th data-name="employee_name" class="<?= $Page->employee_name->headerCellClass() ?>"><div class="rpt_quotation_print_employee_name"><?= $Page->renderSort($Page->employee_name) ?></div></th>
<?php } ?>
<?php if ($Page->rate->Visible) { ?>
    <th data-name="rate" class="<?= $Page->rate->headerCellClass() ?>"><div class="rpt_quotation_print_rate"><?= $Page->renderSort($Page->rate) ?></div></th>
<?php } ?>
<?php if ($Page->qty->Visible) { ?>
    <th data-name="qty" class="<?= $Page->qty->headerCellClass() ?>"><div class="rpt_quotation_print_qty"><?= $Page->renderSort($Page->qty) ?></div></th>
<?php } ?>
<?php if ($Page->Total->Visible) { ?>
    <th data-name="Total" class="<?= $Page->Total->headerCellClass() ?>"><div class="rpt_quotation_print_Total"><?= $Page->renderSort($Page->Total) ?></div></th>
<?php } ?>
<?php if ($Page->ID->Visible) { ?>
    <th data-name="ID" class="<?= $Page->ID->headerCellClass() ?>"><div class="rpt_quotation_print_ID"><?= $Page->renderSort($Page->ID) ?></div></th>
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
    $where = DetailFilterSql($Page->quotation_no, $Page->getSqlFirstGroupField(), $Page->quotation_no->groupValue(), $Page->Dbid);
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
    $Page->quotation_no->Records = &$Page->DetailRecords;
    $Page->quotation_no->LevelBreak = true; // Set field level break
    $Page->GroupCounter[1] = $Page->GroupCount;
    $Page->quotation_no->getCnt($Page->quotation_no->Records); // Get record count
    ?>
<?php if ($Page->quotation_no->Visible && $Page->quotation_no->ShowGroupHeaderAsRow) { ?>
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
<?php if ($Page->quotation_no->Visible) { ?>
        <td data-field="quotation_no"<?= $Page->quotation_no->cellAttributes(); ?>><span class="ew-group-toggle icon-collapse"></span></td>
<?php } ?>
        <td data-field="quotation_no" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 1) ?>"<?= $Page->quotation_no->cellAttributes() ?>>
        <span class="ew-summary-caption d-inline-block rpt_quotation_print_quotation_no"><?= $Page->renderSort($Page->quotation_no) ?></span><?= $Language->phrase("SummaryColon") ?><template id="tpx<?= $Page->GroupCount ?>_rpt_quotation_print_quotation_no"><span<?= $Page->quotation_no->viewAttributes() ?>><?= $Page->quotation_no->GroupViewValue ?></span></template>
        <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->quotation_no->Count, 0); ?></span>)</span>
        </td>
    </tr>
<?php } ?>
    <?php
    $Page->customer_name->getDistinctValues($Page->quotation_no->Records);
    $Page->setGroupCount(count($Page->customer_name->DistinctValues), $Page->GroupCounter[1]);
    $Page->GroupCounter[2] = 0; // Init group count index
    foreach ($Page->customer_name->DistinctValues as $customer_name) { // Load records for this distinct value
    $Page->customer_name->setGroupValue($customer_name); // Set group value
    $Page->customer_name->getDistinctRecords($Page->quotation_no->Records, $Page->customer_name->groupValue());
    $Page->customer_name->LevelBreak = true; // Set field level break
    $Page->GroupCounter[2]++;
    $Page->customer_name->getCnt($Page->customer_name->Records); // Get record count
    $Page->setGroupCount($Page->customer_name->Count, $Page->GroupCounter[1], $Page->GroupCounter[2]);
    ?>
<?php if ($Page->customer_name->Visible && $Page->customer_name->ShowGroupHeaderAsRow) { ?>
<?php
        // Render header row
        $Page->customer_name->setDbValue($customer_name); // Set current value for customer_name
        $Page->resetAttributes();
        $Page->RowType = ROWTYPE_TOTAL;
        $Page->RowTotalType = ROWTOTAL_GROUP;
        $Page->RowTotalSubType = ROWTOTAL_HEADER;
        $Page->RowGroupLevel = 2;
        $Page->renderRow();
?>
    <tr<?= $Page->rowAttributes(); ?>>
<?php if ($Page->quotation_no->Visible) { ?>
        <td data-field="quotation_no"<?= $Page->quotation_no->cellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->customer_name->Visible) { ?>
        <td data-field="customer_name"<?= $Page->customer_name->cellAttributes(); ?>><span class="ew-group-toggle icon-collapse"></span></td>
<?php } ?>
        <td data-field="customer_name" colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount - 2) ?>"<?= $Page->customer_name->cellAttributes() ?>>
        <span class="ew-summary-caption d-inline-block rpt_quotation_print_customer_name"><?= $Page->renderSort($Page->customer_name) ?></span><?= $Language->phrase("SummaryColon") ?><template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_rpt_quotation_print_customer_name"><span<?= $Page->customer_name->viewAttributes() ?>><?= $Page->customer_name->GroupViewValue ?></span></template>
        <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->customer_name->Count, 0); ?></span>)</span>
        </td>
    </tr>
<?php } ?>
    <?php
    $Page->RecordCount = 0; // Reset record count
    foreach ($Page->customer_name->Records as $record) {
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
<?php if ($Page->quotation_no->Visible) { ?>
    <?php if ($Page->quotation_no->ShowGroupHeaderAsRow) { ?>
        <td data-field="quotation_no"<?= $Page->quotation_no->cellAttributes(); ?>>&nbsp;</td>
    <?php } else { ?>
        <td data-field="quotation_no"<?= $Page->quotation_no->cellAttributes(); ?>><template id="tpx<?= $Page->GroupCount ?>_rpt_quotation_print_quotation_no"><span<?= $Page->quotation_no->viewAttributes() ?>><?= $Page->quotation_no->GroupViewValue ?></span></template></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->customer_name->Visible) { ?>
    <?php if ($Page->customer_name->ShowGroupHeaderAsRow) { ?>
        <td data-field="customer_name"<?= $Page->customer_name->cellAttributes(); ?>>&nbsp;</td>
    <?php } else { ?>
        <td data-field="customer_name"<?= $Page->customer_name->cellAttributes(); ?>><template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_rpt_quotation_print_customer_name"><span<?= $Page->customer_name->viewAttributes() ?>><?= $Page->customer_name->GroupViewValue ?></span></template></td>
    <?php } ?>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { ?>
        <td data-field="quotation_date"<?= $Page->quotation_date->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_quotation_date">
<span<?= $Page->quotation_date->viewAttributes() ?>>
<?= $Page->quotation_date->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->address->Visible) { ?>
        <td data-field="address"<?= $Page->address->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_address">
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->city->Visible) { ?>
        <td data-field="city"<?= $Page->city->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_city">
<span<?= $Page->city->viewAttributes() ?>>
<?= $Page->city->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->province->Visible) { ?>
        <td data-field="province"<?= $Page->province->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_province">
<span<?= $Page->province->viewAttributes() ?>>
<?= $Page->province->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->contact->Visible) { ?>
        <td data-field="contact"<?= $Page->contact->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_contact">
<span<?= $Page->contact->viewAttributes() ?>>
<?= $Page->contact->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->employee_name->Visible) { ?>
        <td data-field="employee_name"<?= $Page->employee_name->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_employee_name">
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->rate->Visible) { ?>
        <td data-field="rate"<?= $Page->rate->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_rate">
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->qty->Visible) { ?>
        <td data-field="qty"<?= $Page->qty->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_qty">
<span<?= $Page->qty->viewAttributes() ?>>
<?= $Page->qty->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->Total->Visible) { ?>
        <td data-field="Total"<?= $Page->Total->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_Total">
<span<?= $Page->Total->viewAttributes() ?>>
<?= $Page->Total->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->ID->Visible) { ?>
        <td data-field="ID"<?= $Page->ID->cellAttributes() ?>>
<template id="tpx<?= $Page->GroupCount ?>_<?= $Page->GroupCounter[2] ?>_<?= $Page->RecordCount ?>_rpt_quotation_print_ID">
<span<?= $Page->ID->viewAttributes() ?>>
<?= $Page->ID->getViewValue() ?></span>
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
<?php if ($Page->quotation_no->ShowCompactSummaryFooter) { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<span class="ew-aggregate-caption"><?= $Language->phrase("RptCnt") ?></span><?= $Language->phrase("AggregateEqual") ?><span class="ew-aggregate-value"><?= FormatNumber($Page->TotalCount, 0); ?></span>)</span></td></tr>
<?php } else { ?>
    <tr<?= $Page->rowAttributes() ?>><td colspan="<?= ($Page->GroupColumnCount + $Page->DetailColumnCount) ?>"><?= $Language->phrase("RptGrandSummary") ?> <span class="ew-summary-count">(<?= FormatNumber($Page->TotalCount, 0); ?><?= $Language->phrase("RptDtlRec") ?>)</span></td></tr>
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
<div id="tpd_rpt_quotation_printsummary"></div>
<template id="tpm_rpt_quotation_printsummary">
<div id="ct_RptQuotationPrintSummary"><?php $k = 1; ?><table class="ew-table no-border"><tr><td><?= date("F j, Y"); ?></td></tr></table><!-- table.ew-table.no-border (without border) -->
<br><!-- Use <br> as link breaks -->
<table class="ew-table no-border">
<tr><td>{{: ~root.rows[<?= $k - 1 ?>].customer_name }}</td></tr>
<tr><td>{{: ~root.rows[<?= $k - 1 ?>].address }}</td></tr>
<tr><td>{{: ~root.rows[<?= $k - 1 ?>].city }}</td></tr>
<tr><td>{{: ~root.rows[<?= $k - 1 ?>].province }}</td></tr>
<tr><td>Attn: {{: ~root.rows[<?= $k - 1 ?>].contact }}</td></tr>
</table>
<br>
<?php
$cnt = $Page->GroupCount - 1;
for ($i = 1; $i <= $cnt; $i++) {
?>
<?php
$cnt1 = $Page->getGroupCount($i);
for ($i1 = 1; $i1 <= $cnt1; $i1++) {
?>
<table class="ew-table no-border"><tr><td><b>Quotation No</b> <slot class="ew-slot" name="tpx<?= $i ?>_rpt_quotation_print_quotation_no"></slot> </td></tr></table>
<br>
<table class="table table-bordered table-sm ew-table ew-export-table w-100"><!-- table.ew-table (with border) -->
<tr>
<th><?= $Page->employee_name->caption() ?></th>
<th><?= $Page->rate->caption() ?></th>
<th><?= $Page->qty->caption() ?></th>
<th><?= $Page->Total->caption() ?></th>
</tr>
<?php
for ($j = 1; $j <= $Page->getGroupCount($i, $i1); $j++) {
?>
<tr>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_rpt_quotation_print_employee_name"></slot></td>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_rpt_quotation_print_rate"></slot></td>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_rpt_quotation_print_qty"></slot></td>
<td><slot class="ew-slot" name="tpx<?= $i ?>_<?= $i1 ?>_<?= $j ?>_rpt_quotation_print_Total"></slot></td>
</tr>
<?php
$k++;
}
?>
<tr>
<td colspan="3"><div class="text-right"><b>Total</b></div></td>
<td><b>{{{sum Total)}}}</b></td>
</tr>
</table>
<?php
}
?>
<?php
if ($Page->ExportPageBreakCount > 0 && $Page->isExport()) {
if ($i % $Page->ExportPageBreakCount == 0 && $i < $cnt) {
?>
<slot class="ew-slot" name="tpb<?= $i ?>_rpt_quotation_print"></slot>
<?php
}
}
}
?>
<br>
<table class="ew-table no-border"><tr><td>Some additional information here.</td></tr></table>
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
    ew.applyTemplate("tpd_rpt_quotation_printsummary", "tpm_rpt_quotation_printsummary", "rpt_quotation_printsummary", "<?= $Page->CustomExport ?>", ew.templateData);
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
