<?php

namespace MEM\prjMitralPHP;

// Table
$master_shift = Container("master_shift");
?>
<?php if ($master_shift->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_master_shiftmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($master_shift->shift_name->Visible) { // shift_name ?>
        <tr id="r_shift_name">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->shift_name->caption() ?></td>
            <td <?= $master_shift->shift_name->cellAttributes() ?>>
<span id="el_master_shift_shift_name">
<span<?= $master_shift->shift_name->viewAttributes() ?>>
<?= $master_shift->shift_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->sunday_time_in->Visible) { // sunday_time_in ?>
        <tr id="r_sunday_time_in">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->sunday_time_in->caption() ?></td>
            <td <?= $master_shift->sunday_time_in->cellAttributes() ?>>
<span id="el_master_shift_sunday_time_in">
<span<?= $master_shift->sunday_time_in->viewAttributes() ?>>
<?= $master_shift->sunday_time_in->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->sunday_time_out->Visible) { // sunday_time_out ?>
        <tr id="r_sunday_time_out">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->sunday_time_out->caption() ?></td>
            <td <?= $master_shift->sunday_time_out->cellAttributes() ?>>
<span id="el_master_shift_sunday_time_out">
<span<?= $master_shift->sunday_time_out->viewAttributes() ?>>
<?= $master_shift->sunday_time_out->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->monday_time_in->Visible) { // monday_time_in ?>
        <tr id="r_monday_time_in">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->monday_time_in->caption() ?></td>
            <td <?= $master_shift->monday_time_in->cellAttributes() ?>>
<span id="el_master_shift_monday_time_in">
<span<?= $master_shift->monday_time_in->viewAttributes() ?>>
<?= $master_shift->monday_time_in->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->monday_time_out->Visible) { // monday_time_out ?>
        <tr id="r_monday_time_out">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->monday_time_out->caption() ?></td>
            <td <?= $master_shift->monday_time_out->cellAttributes() ?>>
<span id="el_master_shift_monday_time_out">
<span<?= $master_shift->monday_time_out->viewAttributes() ?>>
<?= $master_shift->monday_time_out->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->tuesday_time_in->Visible) { // tuesday_time_in ?>
        <tr id="r_tuesday_time_in">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->tuesday_time_in->caption() ?></td>
            <td <?= $master_shift->tuesday_time_in->cellAttributes() ?>>
<span id="el_master_shift_tuesday_time_in">
<span<?= $master_shift->tuesday_time_in->viewAttributes() ?>>
<?= $master_shift->tuesday_time_in->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->tuesday_time_out->Visible) { // tuesday_time_out ?>
        <tr id="r_tuesday_time_out">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->tuesday_time_out->caption() ?></td>
            <td <?= $master_shift->tuesday_time_out->cellAttributes() ?>>
<span id="el_master_shift_tuesday_time_out">
<span<?= $master_shift->tuesday_time_out->viewAttributes() ?>>
<?= $master_shift->tuesday_time_out->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->wednesday_time_in->Visible) { // wednesday_time_in ?>
        <tr id="r_wednesday_time_in">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->wednesday_time_in->caption() ?></td>
            <td <?= $master_shift->wednesday_time_in->cellAttributes() ?>>
<span id="el_master_shift_wednesday_time_in">
<span<?= $master_shift->wednesday_time_in->viewAttributes() ?>>
<?= $master_shift->wednesday_time_in->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->wednesday_time_out->Visible) { // wednesday_time_out ?>
        <tr id="r_wednesday_time_out">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->wednesday_time_out->caption() ?></td>
            <td <?= $master_shift->wednesday_time_out->cellAttributes() ?>>
<span id="el_master_shift_wednesday_time_out">
<span<?= $master_shift->wednesday_time_out->viewAttributes() ?>>
<?= $master_shift->wednesday_time_out->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->thursday_time_in->Visible) { // thursday_time_in ?>
        <tr id="r_thursday_time_in">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->thursday_time_in->caption() ?></td>
            <td <?= $master_shift->thursday_time_in->cellAttributes() ?>>
<span id="el_master_shift_thursday_time_in">
<span<?= $master_shift->thursday_time_in->viewAttributes() ?>>
<?= $master_shift->thursday_time_in->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->thursday_time_out->Visible) { // thursday_time_out ?>
        <tr id="r_thursday_time_out">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->thursday_time_out->caption() ?></td>
            <td <?= $master_shift->thursday_time_out->cellAttributes() ?>>
<span id="el_master_shift_thursday_time_out">
<span<?= $master_shift->thursday_time_out->viewAttributes() ?>>
<?= $master_shift->thursday_time_out->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->friday_time_in->Visible) { // friday_time_in ?>
        <tr id="r_friday_time_in">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->friday_time_in->caption() ?></td>
            <td <?= $master_shift->friday_time_in->cellAttributes() ?>>
<span id="el_master_shift_friday_time_in">
<span<?= $master_shift->friday_time_in->viewAttributes() ?>>
<?= $master_shift->friday_time_in->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->friday_time_out->Visible) { // friday_time_out ?>
        <tr id="r_friday_time_out">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->friday_time_out->caption() ?></td>
            <td <?= $master_shift->friday_time_out->cellAttributes() ?>>
<span id="el_master_shift_friday_time_out">
<span<?= $master_shift->friday_time_out->viewAttributes() ?>>
<?= $master_shift->friday_time_out->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->saturday_time_in->Visible) { // saturday_time_in ?>
        <tr id="r_saturday_time_in">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->saturday_time_in->caption() ?></td>
            <td <?= $master_shift->saturday_time_in->cellAttributes() ?>>
<span id="el_master_shift_saturday_time_in">
<span<?= $master_shift->saturday_time_in->viewAttributes() ?>>
<?= $master_shift->saturday_time_in->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_shift->saturday_time_out->Visible) { // saturday_time_out ?>
        <tr id="r_saturday_time_out">
            <td class="<?= $master_shift->TableLeftColumnClass ?>"><?= $master_shift->saturday_time_out->caption() ?></td>
            <td <?= $master_shift->saturday_time_out->cellAttributes() ?>>
<span id="el_master_shift_saturday_time_out">
<span<?= $master_shift->saturday_time_out->viewAttributes() ?>>
<?= $master_shift->saturday_time_out->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
