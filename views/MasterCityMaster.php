<?php

namespace MEM\prjMitralPHP;

// Table
$master_city = Container("master_city");
?>
<?php if ($master_city->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_master_citymaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($master_city->province_id->Visible) { // province_id ?>
        <tr id="r_province_id">
            <td class="<?= $master_city->TableLeftColumnClass ?>"><?= $master_city->province_id->caption() ?></td>
            <td <?= $master_city->province_id->cellAttributes() ?>>
<span id="el_master_city_province_id">
<span<?= $master_city->province_id->viewAttributes() ?>>
<?= $master_city->province_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_city->city_id->Visible) { // city_id ?>
        <tr id="r_city_id">
            <td class="<?= $master_city->TableLeftColumnClass ?>"><?= $master_city->city_id->caption() ?></td>
            <td <?= $master_city->city_id->cellAttributes() ?>>
<span id="el_master_city_city_id">
<span<?= $master_city->city_id->viewAttributes() ?>>
<?= $master_city->city_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_city->city->Visible) { // city ?>
        <tr id="r_city">
            <td class="<?= $master_city->TableLeftColumnClass ?>"><?= $master_city->city->caption() ?></td>
            <td <?= $master_city->city->cellAttributes() ?>>
<span id="el_master_city_city">
<span<?= $master_city->city->viewAttributes() ?>>
<?= $master_city->city->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
