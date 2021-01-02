<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingPrintSummary = &$Page;
?>
<?php if (!$Page->isExport() && !$Page->DrillDown && !$DashboardReport) { ?>
<script>
var currentForm, currentPageID;
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
<div id="gmp_offering_print" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="<?= $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
    <tr class="ew-table-header">
<?php if ($Page->customer_name->Visible) { ?>
    <th data-name="customer_name" class="<?= $Page->customer_name->headerCellClass() ?>"><div class="offering_print_customer_name"><?= $Page->renderSort($Page->customer_name) ?></div></th>
<?php } ?>
<?php if ($Page->customer_address->Visible) { ?>
    <th data-name="customer_address" class="<?= $Page->customer_address->headerCellClass() ?>"><div class="offering_print_customer_address"><?= $Page->renderSort($Page->customer_address) ?></div></th>
<?php } ?>
<?php if ($Page->phone_number->Visible) { ?>
    <th data-name="phone_number" class="<?= $Page->phone_number->headerCellClass() ?>"><div class="offering_print_phone_number"><?= $Page->renderSort($Page->phone_number) ?></div></th>
<?php } ?>
<?php if ($Page->contact->Visible) { ?>
    <th data-name="contact" class="<?= $Page->contact->headerCellClass() ?>"><div class="offering_print_contact"><?= $Page->renderSort($Page->contact) ?></div></th>
<?php } ?>
<?php if ($Page->city->Visible) { ?>
    <th data-name="city" class="<?= $Page->city->headerCellClass() ?>"><div class="offering_print_city"><?= $Page->renderSort($Page->city) ?></div></th>
<?php } ?>
<?php if ($Page->rate->Visible) { ?>
    <th data-name="rate" class="<?= $Page->rate->headerCellClass() ?>"><div class="offering_print_rate"><?= $Page->renderSort($Page->rate) ?></div></th>
<?php } ?>
<?php if ($Page->qty->Visible) { ?>
    <th data-name="qty" class="<?= $Page->qty->headerCellClass() ?>"><div class="offering_print_qty"><?= $Page->renderSort($Page->qty) ?></div></th>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
    <th data-name="total" class="<?= $Page->total->headerCellClass() ?>"><div class="offering_print_total"><?= $Page->renderSort($Page->total) ?></div></th>
<?php } ?>
<?php if ($Page->quotation_id->Visible) { ?>
    <th data-name="quotation_id" class="<?= $Page->quotation_id->headerCellClass() ?>"><div class="offering_print_quotation_id"><?= $Page->renderSort($Page->quotation_id) ?></div></th>
<?php } ?>
<?php if ($Page->quotation_no->Visible) { ?>
    <th data-name="quotation_no" class="<?= $Page->quotation_no->headerCellClass() ?>"><div class="offering_print_quotation_no"><?= $Page->renderSort($Page->quotation_no) ?></div></th>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { ?>
    <th data-name="quotation_date" class="<?= $Page->quotation_date->headerCellClass() ?>"><div class="offering_print_quotation_date"><?= $Page->renderSort($Page->quotation_date) ?></div></th>
<?php } ?>
<?php if ($Page->employee_name->Visible) { ?>
    <th data-name="employee_name" class="<?= $Page->employee_name->headerCellClass() ?>"><div class="offering_print_employee_name"><?= $Page->renderSort($Page->employee_name) ?></div></th>
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
<?php if ($Page->customer_name->Visible) { ?>
        <td data-field="customer_name"<?= $Page->customer_name->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_customer_name">
<span<?= $Page->customer_name->viewAttributes() ?>>
<?= $Page->customer_name->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->customer_address->Visible) { ?>
        <td data-field="customer_address"<?= $Page->customer_address->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_customer_address">
<span<?= $Page->customer_address->viewAttributes() ?>>
<?= $Page->customer_address->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->phone_number->Visible) { ?>
        <td data-field="phone_number"<?= $Page->phone_number->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_phone_number">
<span<?= $Page->phone_number->viewAttributes() ?>>
<?= $Page->phone_number->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->contact->Visible) { ?>
        <td data-field="contact"<?= $Page->contact->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_contact">
<span<?= $Page->contact->viewAttributes() ?>>
<?= $Page->contact->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->city->Visible) { ?>
        <td data-field="city"<?= $Page->city->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_city">
<span<?= $Page->city->viewAttributes() ?>>
<?= $Page->city->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->rate->Visible) { ?>
        <td data-field="rate"<?= $Page->rate->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_rate">
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->qty->Visible) { ?>
        <td data-field="qty"<?= $Page->qty->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_qty">
<span<?= $Page->qty->viewAttributes() ?>>
<?= $Page->qty->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->total->Visible) { ?>
        <td data-field="total"<?= $Page->total->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->quotation_id->Visible) { ?>
        <td data-field="quotation_id"<?= $Page->quotation_id->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_quotation_id">
<span<?= $Page->quotation_id->viewAttributes() ?>>
<?= $Page->quotation_id->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->quotation_no->Visible) { ?>
        <td data-field="quotation_no"<?= $Page->quotation_no->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_quotation_no">
<span<?= $Page->quotation_no->viewAttributes() ?>>
<?= $Page->quotation_no->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->quotation_date->Visible) { ?>
        <td data-field="quotation_date"<?= $Page->quotation_date->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_quotation_date">
<span<?= $Page->quotation_date->viewAttributes() ?>>
<?= $Page->quotation_date->getViewValue() ?></span>
</template>
</td>
<?php } ?>
<?php if ($Page->employee_name->Visible) { ?>
        <td data-field="employee_name"<?= $Page->employee_name->cellAttributes() ?>>
<template id="tpx<?= $Page->RecordCount ?>_<?= $Page->RecordCount ?>_offering_print_employee_name">
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->getViewValue() ?></span>
</template>
</td>
<?php } ?>
    </tr>
<?php
} // End while
?>
<?php if ($Page->TotalGroups > 0) { ?>
</tbody>
<tfoot>
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
<div id="tpd_offering_printsummary"></div>
<template id="tpm_offering_printsummary">
<div id="ct_OfferingPrintSummary"><?php $k = 1; ?><table class="ew-table no-border">
<tr><img src="img/smallogo.png" alt="Logo Mitral" width="168" height="100"></tr>
</table>
<br>
<?php
$cnt = $Page->GroupCount - 1;
for ($i = 1; $i <= $cnt; $i++) {
?>
<?php
for ($j = 1; $j <= $Page->getGroupCount($i); $j++) {
?>
<tr>
<td><?= $j ?></td>
<td><slot class="ew-slot" name="tpx<?= $j ?>_<?= $j ?>_offering_print_employee_name"></slot></td>
<td style="text-align:right"><slot class="ew-slot" name="tpx<?= $j ?>_<?= $j ?>_offering_print_rate"></slot></td>
<td style="text-align:right"><slot class="ew-slot" name="tpx<?= $j ?>_<?= $j ?>_offering_print_qty"></slot></td>
<td style="text-align:right"><slot class="ew-slot" name="tpx<?= $j ?>_<?= $j ?>_offering_print_total"></slot></td>
</tr>
<?php
$k++;
}
?>
<?php
if ($Page->ExportPageBreakCount > 0 && $Page->isExport()) {
if ($i % $Page->ExportPageBreakCount == 0 && $i < $cnt) {
?>
<slot class="ew-slot" name="tpb<?= $i ?>_offering_print"></slot>
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
<tr><td> - Price shall be paid monthly after invoicing</td></tr>
<tr><td> - Term of Payment: 30 days after invoice date</td></tr>
<tr><td> - Payment to remit to following account:</td></tr>
<tr><td>     <b>Bank</b>		: Bank Central Asia, KCP Pedurungan, Semarang</td></tr>
<tr><td>     <b>A/C No</b>		: 854-5520202</td></tr>
<tr><td>     <b>A/C Name</b>	: MITRA TRANSFORMASI DIGIT</td></tr>
<tr><td>For more information, please contact us below email or phones: 081553222001. Thanks for your kind attention and further good cooperations.
</td></tr></table>
<br>
<table class="ew-table no-border">
<tr><div class="text-right"><img src="img/marketing.jpg" alt="Marketing TTD" width="138" height="100"></tr>
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
    ew.applyTemplate("tpd_offering_printsummary", "tpm_offering_printsummary", "offering_printsummary", "<?= $Page->CustomExport ?>", ew.templateData);
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
