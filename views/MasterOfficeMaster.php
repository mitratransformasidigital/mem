<?php

namespace MEM\prjMitralPHP;

// Table
$master_office = Container("master_office");
?>
<?php if ($master_office->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_master_officemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($master_office->office->Visible) { // office ?>
        <tr id="r_office">
            <td class="<?= $master_office->TableLeftColumnClass ?>"><?= $master_office->office->caption() ?></td>
            <td <?= $master_office->office->cellAttributes() ?>>
<span id="el_master_office_office">
<span<?= $master_office->office->viewAttributes() ?>>
<?= $master_office->office->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_office->address->Visible) { // address ?>
        <tr id="r_address">
            <td class="<?= $master_office->TableLeftColumnClass ?>"><?= $master_office->address->caption() ?></td>
            <td <?= $master_office->address->cellAttributes() ?>>
<span id="el_master_office_address">
<span<?= $master_office->address->viewAttributes() ?>>
<?= $master_office->address->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_office->city_id->Visible) { // city_id ?>
        <tr id="r_city_id">
            <td class="<?= $master_office->TableLeftColumnClass ?>"><?= $master_office->city_id->caption() ?></td>
            <td <?= $master_office->city_id->cellAttributes() ?>>
<span id="el_master_office_city_id">
<span<?= $master_office->city_id->viewAttributes() ?>>
<?= $master_office->city_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_office->phone_number->Visible) { // phone_number ?>
        <tr id="r_phone_number">
            <td class="<?= $master_office->TableLeftColumnClass ?>"><?= $master_office->phone_number->caption() ?></td>
            <td <?= $master_office->phone_number->cellAttributes() ?>>
<span id="el_master_office_phone_number">
<span<?= $master_office->phone_number->viewAttributes() ?>>
<?= $master_office->phone_number->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_office->contact_name->Visible) { // contact_name ?>
        <tr id="r_contact_name">
            <td class="<?= $master_office->TableLeftColumnClass ?>"><?= $master_office->contact_name->caption() ?></td>
            <td <?= $master_office->contact_name->cellAttributes() ?>>
<span id="el_master_office_contact_name">
<span<?= $master_office->contact_name->viewAttributes() ?>>
<?= $master_office->contact_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_office->description->Visible) { // description ?>
        <tr id="r_description">
            <td class="<?= $master_office->TableLeftColumnClass ?>"><?= $master_office->description->caption() ?></td>
            <td <?= $master_office->description->cellAttributes() ?>>
<span id="el_master_office_description">
<span<?= $master_office->description->viewAttributes() ?>>
<?= $master_office->description->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
