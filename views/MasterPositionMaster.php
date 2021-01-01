<?php

namespace MEM\prjMitralPHP;

// Table
$master_position = Container("master_position");
?>
<?php if ($master_position->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_master_positionmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($master_position->position->Visible) { // position ?>
        <tr id="r_position">
            <td class="<?= $master_position->TableLeftColumnClass ?>"><?= $master_position->position->caption() ?></td>
            <td <?= $master_position->position->cellAttributes() ?>>
<span id="el_master_position_position">
<span<?= $master_position->position->viewAttributes() ?>>
<?= $master_position->position->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_position->description->Visible) { // description ?>
        <tr id="r_description">
            <td class="<?= $master_position->TableLeftColumnClass ?>"><?= $master_position->description->caption() ?></td>
            <td <?= $master_position->description->cellAttributes() ?>>
<span id="el_master_position_description">
<span<?= $master_position->description->viewAttributes() ?>>
<?= $master_position->description->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
