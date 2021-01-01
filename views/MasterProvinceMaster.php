<?php

namespace MEM\prjMitralPHP;

// Table
$master_province = Container("master_province");
?>
<?php if ($master_province->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_master_provincemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($master_province->province_id->Visible) { // province_id ?>
        <tr id="r_province_id">
            <td class="<?= $master_province->TableLeftColumnClass ?>"><?= $master_province->province_id->caption() ?></td>
            <td <?= $master_province->province_id->cellAttributes() ?>>
<span id="el_master_province_province_id">
<span<?= $master_province->province_id->viewAttributes() ?>>
<?= $master_province->province_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_province->province->Visible) { // province ?>
        <tr id="r_province">
            <td class="<?= $master_province->TableLeftColumnClass ?>"><?= $master_province->province->caption() ?></td>
            <td <?= $master_province->province->cellAttributes() ?>>
<span id="el_master_province_province">
<span<?= $master_province->province->viewAttributes() ?>>
<?= $master_province->province->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
