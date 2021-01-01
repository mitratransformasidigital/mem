<?php

namespace MEM\prjMitralPHP;

// Table
$master_skill = Container("master_skill");
?>
<?php if ($master_skill->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_master_skillmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($master_skill->skill->Visible) { // skill ?>
        <tr id="r_skill">
            <td class="<?= $master_skill->TableLeftColumnClass ?>"><?= $master_skill->skill->caption() ?></td>
            <td <?= $master_skill->skill->cellAttributes() ?>>
<span id="el_master_skill_skill">
<span<?= $master_skill->skill->viewAttributes() ?>>
<?= $master_skill->skill->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($master_skill->description->Visible) { // description ?>
        <tr id="r_description">
            <td class="<?= $master_skill->TableLeftColumnClass ?>"><?= $master_skill->description->caption() ?></td>
            <td <?= $master_skill->description->cellAttributes() ?>>
<span id="el_master_skill_description">
<span<?= $master_skill->description->viewAttributes() ?>>
<?= $master_skill->description->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
