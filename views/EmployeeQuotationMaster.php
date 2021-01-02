<?php

namespace MEM\prjMitralPHP;

// Table
$employee_quotation = Container("employee_quotation");
?>
<?php if ($employee_quotation->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_employee_quotationmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($employee_quotation->quotation_no->Visible) { // quotation_no ?>
        <tr id="r_quotation_no">
            <td class="<?= $employee_quotation->TableLeftColumnClass ?>"><?= $employee_quotation->quotation_no->caption() ?></td>
            <td <?= $employee_quotation->quotation_no->cellAttributes() ?>>
<span id="el_employee_quotation_quotation_no">
<span<?= $employee_quotation->quotation_no->viewAttributes() ?>>
<?= $employee_quotation->quotation_no->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee_quotation->customer_id->Visible) { // customer_id ?>
        <tr id="r_customer_id">
            <td class="<?= $employee_quotation->TableLeftColumnClass ?>"><?= $employee_quotation->customer_id->caption() ?></td>
            <td <?= $employee_quotation->customer_id->cellAttributes() ?>>
<span id="el_employee_quotation_customer_id">
<span<?= $employee_quotation->customer_id->viewAttributes() ?>>
<?= $employee_quotation->customer_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee_quotation->quotation_date->Visible) { // quotation_date ?>
        <tr id="r_quotation_date">
            <td class="<?= $employee_quotation->TableLeftColumnClass ?>"><?= $employee_quotation->quotation_date->caption() ?></td>
            <td <?= $employee_quotation->quotation_date->cellAttributes() ?>>
<span id="el_employee_quotation_quotation_date">
<span<?= $employee_quotation->quotation_date->viewAttributes() ?>>
<?= $employee_quotation->quotation_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
