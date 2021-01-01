<?php

namespace MEM\prjMitralPHP;

// Table
$master_status = Container("master_status");
?>
<?php if ($master_status->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_master_statusmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($master_status->status->Visible) { // status ?>
        <tr id="r_status">
            <td class="<?= $master_status->TableLeftColumnClass ?>"><?= $master_status->status->caption() ?></td>
            <td <?= $master_status->status->cellAttributes() ?>>
<span id="el_master_status_status">
<span<?= $master_status->status->viewAttributes() ?>>
<?= $master_status->status->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_status->description->Visible) { // description ?>
        <tr id="r_description">
            <td class="<?= $master_status->TableLeftColumnClass ?>"><?= $master_status->description->caption() ?></td>
            <td <?= $master_status->description->cellAttributes() ?>>
<span id="el_master_status_description">
<span<?= $master_status->description->viewAttributes() ?>>
<?= $master_status->description->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
