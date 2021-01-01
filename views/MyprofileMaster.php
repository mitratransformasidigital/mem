<?php

namespace MEM\prjMitralPHP;

// Table
$myprofile = Container("myprofile");
?>
<?php if ($myprofile->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_myprofilemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($myprofile->employee_name->Visible) { // employee_name ?>
        <tr id="r_employee_name">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->employee_name->caption() ?></td>
            <td <?= $myprofile->employee_name->cellAttributes() ?>>
<span id="el_myprofile_employee_name">
<span<?= $myprofile->employee_name->viewAttributes() ?>>
<?= $myprofile->employee_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->employee_username->Visible) { // employee_username ?>
        <tr id="r_employee_username">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->employee_username->caption() ?></td>
            <td <?= $myprofile->employee_username->cellAttributes() ?>>
<span id="el_myprofile_employee_username">
<span<?= $myprofile->employee_username->viewAttributes() ?>>
<?= $myprofile->employee_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->employee_password->Visible) { // employee_password ?>
        <tr id="r_employee_password">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->employee_password->caption() ?></td>
            <td <?= $myprofile->employee_password->cellAttributes() ?>>
<span id="el_myprofile_employee_password">
<span<?= $myprofile->employee_password->viewAttributes() ?>>
<?= $myprofile->employee_password->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->employee_email->Visible) { // employee_email ?>
        <tr id="r_employee_email">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->employee_email->caption() ?></td>
            <td <?= $myprofile->employee_email->cellAttributes() ?>>
<span id="el_myprofile_employee_email">
<span<?= $myprofile->employee_email->viewAttributes() ?>>
<?= $myprofile->employee_email->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->birth_date->Visible) { // birth_date ?>
        <tr id="r_birth_date">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->birth_date->caption() ?></td>
            <td <?= $myprofile->birth_date->cellAttributes() ?>>
<span id="el_myprofile_birth_date">
<span<?= $myprofile->birth_date->viewAttributes() ?>>
<?= $myprofile->birth_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->nik->Visible) { // nik ?>
        <tr id="r_nik">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->nik->caption() ?></td>
            <td <?= $myprofile->nik->cellAttributes() ?>>
<span id="el_myprofile_nik">
<span<?= $myprofile->nik->viewAttributes() ?>>
<?= $myprofile->nik->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->npwp->Visible) { // npwp ?>
        <tr id="r_npwp">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->npwp->caption() ?></td>
            <td <?= $myprofile->npwp->cellAttributes() ?>>
<span id="el_myprofile_npwp">
<span<?= $myprofile->npwp->viewAttributes() ?>>
<?= $myprofile->npwp->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->address->Visible) { // address ?>
        <tr id="r_address">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->address->caption() ?></td>
            <td <?= $myprofile->address->cellAttributes() ?>>
<span id="el_myprofile_address">
<span<?= $myprofile->address->viewAttributes() ?>>
<?= $myprofile->address->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->city_id->Visible) { // city_id ?>
        <tr id="r_city_id">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->city_id->caption() ?></td>
            <td <?= $myprofile->city_id->cellAttributes() ?>>
<span id="el_myprofile_city_id">
<span<?= $myprofile->city_id->viewAttributes() ?>>
<?= $myprofile->city_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->postal_code->Visible) { // postal_code ?>
        <tr id="r_postal_code">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->postal_code->caption() ?></td>
            <td <?= $myprofile->postal_code->cellAttributes() ?>>
<span id="el_myprofile_postal_code">
<span<?= $myprofile->postal_code->viewAttributes() ?>>
<?= $myprofile->postal_code->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->bank_number->Visible) { // bank_number ?>
        <tr id="r_bank_number">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->bank_number->caption() ?></td>
            <td <?= $myprofile->bank_number->cellAttributes() ?>>
<span id="el_myprofile_bank_number">
<span<?= $myprofile->bank_number->viewAttributes() ?>>
<?= $myprofile->bank_number->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->bank_name->Visible) { // bank_name ?>
        <tr id="r_bank_name">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->bank_name->caption() ?></td>
            <td <?= $myprofile->bank_name->cellAttributes() ?>>
<span id="el_myprofile_bank_name">
<span<?= $myprofile->bank_name->viewAttributes() ?>>
<?= $myprofile->bank_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->scan_ktp->Visible) { // scan_ktp ?>
        <tr id="r_scan_ktp">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->scan_ktp->caption() ?></td>
            <td <?= $myprofile->scan_ktp->cellAttributes() ?>>
<span id="el_myprofile_scan_ktp">
<span<?= $myprofile->scan_ktp->viewAttributes() ?>>
<?= GetFileViewTag($myprofile->scan_ktp, $myprofile->scan_ktp->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->scan_npwp->Visible) { // scan_npwp ?>
        <tr id="r_scan_npwp">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->scan_npwp->caption() ?></td>
            <td <?= $myprofile->scan_npwp->cellAttributes() ?>>
<span id="el_myprofile_scan_npwp">
<span<?= $myprofile->scan_npwp->viewAttributes() ?>>
<?= GetFileViewTag($myprofile->scan_npwp, $myprofile->scan_npwp->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <tr id="r_curiculum_vitae">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->curiculum_vitae->caption() ?></td>
            <td <?= $myprofile->curiculum_vitae->cellAttributes() ?>>
<span id="el_myprofile_curiculum_vitae">
<span<?= $myprofile->curiculum_vitae->viewAttributes() ?>>
<?= GetFileViewTag($myprofile->curiculum_vitae, $myprofile->curiculum_vitae->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->technical_skill->Visible) { // technical_skill ?>
        <tr id="r_technical_skill">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->technical_skill->caption() ?></td>
            <td <?= $myprofile->technical_skill->cellAttributes() ?>>
<span id="el_myprofile_technical_skill">
<span<?= $myprofile->technical_skill->viewAttributes() ?>>
<?= $myprofile->technical_skill->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->about_me->Visible) { // about_me ?>
        <tr id="r_about_me">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->about_me->caption() ?></td>
            <td <?= $myprofile->about_me->cellAttributes() ?>>
<span id="el_myprofile_about_me">
<span<?= $myprofile->about_me->viewAttributes() ?>>
<?= $myprofile->about_me->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->position_id->Visible) { // position_id ?>
        <tr id="r_position_id">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->position_id->caption() ?></td>
            <td <?= $myprofile->position_id->cellAttributes() ?>>
<span id="el_myprofile_position_id">
<span<?= $myprofile->position_id->viewAttributes() ?>>
<?= $myprofile->position_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->religion->Visible) { // religion ?>
        <tr id="r_religion">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->religion->caption() ?></td>
            <td <?= $myprofile->religion->cellAttributes() ?>>
<span id="el_myprofile_religion">
<span<?= $myprofile->religion->viewAttributes() ?>>
<?= $myprofile->religion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->status_id->Visible) { // status_id ?>
        <tr id="r_status_id">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->status_id->caption() ?></td>
            <td <?= $myprofile->status_id->cellAttributes() ?>>
<span id="el_myprofile_status_id">
<span<?= $myprofile->status_id->viewAttributes() ?>>
<?= $myprofile->status_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->skill_id->Visible) { // skill_id ?>
        <tr id="r_skill_id">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->skill_id->caption() ?></td>
            <td <?= $myprofile->skill_id->cellAttributes() ?>>
<span id="el_myprofile_skill_id">
<span<?= $myprofile->skill_id->viewAttributes() ?>>
<?= $myprofile->skill_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->office_id->Visible) { // office_id ?>
        <tr id="r_office_id">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->office_id->caption() ?></td>
            <td <?= $myprofile->office_id->cellAttributes() ?>>
<span id="el_myprofile_office_id">
<span<?= $myprofile->office_id->viewAttributes() ?>>
<?= $myprofile->office_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->hire_date->Visible) { // hire_date ?>
        <tr id="r_hire_date">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->hire_date->caption() ?></td>
            <td <?= $myprofile->hire_date->cellAttributes() ?>>
<span id="el_myprofile_hire_date">
<span<?= $myprofile->hire_date->viewAttributes() ?>>
<?= $myprofile->hire_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($myprofile->termination_date->Visible) { // termination_date ?>
        <tr id="r_termination_date">
            <td class="<?= $myprofile->TableLeftColumnClass ?>"><?= $myprofile->termination_date->caption() ?></td>
            <td <?= $myprofile->termination_date->cellAttributes() ?>>
<span id="el_myprofile_termination_date">
<span<?= $myprofile->termination_date->viewAttributes() ?>>
<?= $myprofile->termination_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
