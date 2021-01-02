<?php

namespace MEM\prjMitralPHP;

// Table
$offering = Container("offering");
?>
<?php if ($offering->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_offeringmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($offering->offering_no->Visible) { // offering_no ?>
        <tr id="r_offering_no">
            <td class="<?= $offering->TableLeftColumnClass ?>"><?= $offering->offering_no->caption() ?></td>
            <td <?= $offering->offering_no->cellAttributes() ?>>
<span id="el_offering_offering_no">
<span<?= $offering->offering_no->viewAttributes() ?>>
<?= $offering->offering_no->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($offering->customer_id->Visible) { // customer_id ?>
        <tr id="r_customer_id">
            <td class="<?= $offering->TableLeftColumnClass ?>"><?= $offering->customer_id->caption() ?></td>
            <td <?= $offering->customer_id->cellAttributes() ?>>
<span id="el_offering_customer_id">
<span<?= $offering->customer_id->viewAttributes() ?>>
<?= $offering->customer_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($offering->offering_date->Visible) { // offering_date ?>
        <tr id="r_offering_date">
            <td class="<?= $offering->TableLeftColumnClass ?>"><?= $offering->offering_date->caption() ?></td>
            <td <?= $offering->offering_date->cellAttributes() ?>>
<span id="el_offering_offering_date">
<span<?= $offering->offering_date->viewAttributes() ?>>
<?= $offering->offering_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
