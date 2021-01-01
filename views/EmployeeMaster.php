<?php

namespace MEM\prjMitralPHP;

// Table
$employee = Container("employee");
?>
<?php if ($employee->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_employeemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($employee->employee_name->Visible) { // employee_name ?>
        <tr id="r_employee_name">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->employee_name->caption() ?></td>
            <td <?= $employee->employee_name->cellAttributes() ?>>
<span id="el_employee_employee_name">
<span<?= $employee->employee_name->viewAttributes() ?>>
<?= $employee->employee_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->employee_username->Visible) { // employee_username ?>
        <tr id="r_employee_username">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->employee_username->caption() ?></td>
            <td <?= $employee->employee_username->cellAttributes() ?>>
<span id="el_employee_employee_username">
<span<?= $employee->employee_username->viewAttributes() ?>>
<?= $employee->employee_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->employee_password->Visible) { // employee_password ?>
        <tr id="r_employee_password">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->employee_password->caption() ?></td>
            <td <?= $employee->employee_password->cellAttributes() ?>>
<span id="el_employee_employee_password">
<span<?= $employee->employee_password->viewAttributes() ?>>
<?= $employee->employee_password->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->employee_email->Visible) { // employee_email ?>
        <tr id="r_employee_email">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->employee_email->caption() ?></td>
            <td <?= $employee->employee_email->cellAttributes() ?>>
<span id="el_employee_employee_email">
<span<?= $employee->employee_email->viewAttributes() ?>>
<?= $employee->employee_email->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->birth_date->Visible) { // birth_date ?>
        <tr id="r_birth_date">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->birth_date->caption() ?></td>
            <td <?= $employee->birth_date->cellAttributes() ?>>
<span id="el_employee_birth_date">
<span<?= $employee->birth_date->viewAttributes() ?>>
<?= $employee->birth_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->religion->Visible) { // religion ?>
        <tr id="r_religion">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->religion->caption() ?></td>
            <td <?= $employee->religion->cellAttributes() ?>>
<span id="el_employee_religion">
<span<?= $employee->religion->viewAttributes() ?>>
<?= $employee->religion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->nik->Visible) { // nik ?>
        <tr id="r_nik">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->nik->caption() ?></td>
            <td <?= $employee->nik->cellAttributes() ?>>
<span id="el_employee_nik">
<span<?= $employee->nik->viewAttributes() ?>>
<?= $employee->nik->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->npwp->Visible) { // npwp ?>
        <tr id="r_npwp">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->npwp->caption() ?></td>
            <td <?= $employee->npwp->cellAttributes() ?>>
<span id="el_employee_npwp">
<span<?= $employee->npwp->viewAttributes() ?>>
<?= $employee->npwp->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->address->Visible) { // address ?>
        <tr id="r_address">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->address->caption() ?></td>
            <td <?= $employee->address->cellAttributes() ?>>
<span id="el_employee_address">
<span<?= $employee->address->viewAttributes() ?>>
<?= $employee->address->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->city_id->Visible) { // city_id ?>
        <tr id="r_city_id">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->city_id->caption() ?></td>
            <td <?= $employee->city_id->cellAttributes() ?>>
<span id="el_employee_city_id">
<span<?= $employee->city_id->viewAttributes() ?>>
<?= $employee->city_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->postal_code->Visible) { // postal_code ?>
        <tr id="r_postal_code">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->postal_code->caption() ?></td>
            <td <?= $employee->postal_code->cellAttributes() ?>>
<span id="el_employee_postal_code">
<span<?= $employee->postal_code->viewAttributes() ?>>
<?= $employee->postal_code->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->bank_number->Visible) { // bank_number ?>
        <tr id="r_bank_number">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->bank_number->caption() ?></td>
            <td <?= $employee->bank_number->cellAttributes() ?>>
<span id="el_employee_bank_number">
<span<?= $employee->bank_number->viewAttributes() ?>>
<?= $employee->bank_number->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->bank_name->Visible) { // bank_name ?>
        <tr id="r_bank_name">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->bank_name->caption() ?></td>
            <td <?= $employee->bank_name->cellAttributes() ?>>
<span id="el_employee_bank_name">
<span<?= $employee->bank_name->viewAttributes() ?>>
<?= $employee->bank_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->scan_ktp->Visible) { // scan_ktp ?>
        <tr id="r_scan_ktp">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->scan_ktp->caption() ?></td>
            <td <?= $employee->scan_ktp->cellAttributes() ?>>
<span id="el_employee_scan_ktp">
<span<?= $employee->scan_ktp->viewAttributes() ?>>
<?= GetFileViewTag($employee->scan_ktp, $employee->scan_ktp->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->scan_npwp->Visible) { // scan_npwp ?>
        <tr id="r_scan_npwp">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->scan_npwp->caption() ?></td>
            <td <?= $employee->scan_npwp->cellAttributes() ?>>
<span id="el_employee_scan_npwp">
<span<?= $employee->scan_npwp->viewAttributes() ?>>
<?= GetFileViewTag($employee->scan_npwp, $employee->scan_npwp->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <tr id="r_curiculum_vitae">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->curiculum_vitae->caption() ?></td>
            <td <?= $employee->curiculum_vitae->cellAttributes() ?>>
<span id="el_employee_curiculum_vitae">
<span<?= $employee->curiculum_vitae->viewAttributes() ?>>
<?= GetFileViewTag($employee->curiculum_vitae, $employee->curiculum_vitae->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->position_id->Visible) { // position_id ?>
        <tr id="r_position_id">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->position_id->caption() ?></td>
            <td <?= $employee->position_id->cellAttributes() ?>>
<span id="el_employee_position_id">
<span<?= $employee->position_id->viewAttributes() ?>>
<?= $employee->position_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->status_id->Visible) { // status_id ?>
        <tr id="r_status_id">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->status_id->caption() ?></td>
            <td <?= $employee->status_id->cellAttributes() ?>>
<span id="el_employee_status_id">
<span<?= $employee->status_id->viewAttributes() ?>>
<?= $employee->status_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->skill_id->Visible) { // skill_id ?>
        <tr id="r_skill_id">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->skill_id->caption() ?></td>
            <td <?= $employee->skill_id->cellAttributes() ?>>
<span id="el_employee_skill_id">
<span<?= $employee->skill_id->viewAttributes() ?>>
<?= $employee->skill_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->office_id->Visible) { // office_id ?>
        <tr id="r_office_id">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->office_id->caption() ?></td>
            <td <?= $employee->office_id->cellAttributes() ?>>
<span id="el_employee_office_id">
<span<?= $employee->office_id->viewAttributes() ?>>
<?= $employee->office_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->hire_date->Visible) { // hire_date ?>
        <tr id="r_hire_date">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->hire_date->caption() ?></td>
            <td <?= $employee->hire_date->cellAttributes() ?>>
<span id="el_employee_hire_date">
<span<?= $employee->hire_date->viewAttributes() ?>>
<?= $employee->hire_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->termination_date->Visible) { // termination_date ?>
        <tr id="r_termination_date">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->termination_date->caption() ?></td>
            <td <?= $employee->termination_date->cellAttributes() ?>>
<span id="el_employee_termination_date">
<span<?= $employee->termination_date->viewAttributes() ?>>
<?= $employee->termination_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->user_level->Visible) { // user_level ?>
        <tr id="r_user_level">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->user_level->caption() ?></td>
            <td <?= $employee->user_level->cellAttributes() ?>>
<span id="el_employee_user_level">
<span<?= $employee->user_level->viewAttributes() ?>>
<?= $employee->user_level->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->technical_skill->Visible) { // technical_skill ?>
        <tr id="r_technical_skill">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->technical_skill->caption() ?></td>
            <td <?= $employee->technical_skill->cellAttributes() ?>>
<span id="el_employee_technical_skill">
<span<?= $employee->technical_skill->viewAttributes() ?>>
<?= $employee->technical_skill->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($employee->about_me->Visible) { // about_me ?>
        <tr id="r_about_me">
            <td class="<?= $employee->TableLeftColumnClass ?>"><?= $employee->about_me->caption() ?></td>
            <td <?= $employee->about_me->cellAttributes() ?>>
<span id="el_employee_about_me">
<span<?= $employee->about_me->viewAttributes() ?>>
<?= $employee->about_me->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
