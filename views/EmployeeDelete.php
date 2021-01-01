<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var femployeedelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    femployeedelete = currentForm = new ew.Form("femployeedelete", "delete");
    loadjs.done("femployeedelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="femployeedelete" id="femployeedelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="employee">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->employee_name->Visible) { // employee_name ?>
        <th class="<?= $Page->employee_name->headerCellClass() ?>"><span id="elh_employee_employee_name" class="employee_employee_name"><?= $Page->employee_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <th class="<?= $Page->employee_username->headerCellClass() ?>"><span id="elh_employee_employee_username" class="employee_employee_username"><?= $Page->employee_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
        <th class="<?= $Page->employee_password->headerCellClass() ?>"><span id="elh_employee_employee_password" class="employee_employee_password"><?= $Page->employee_password->caption() ?></span></th>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
        <th class="<?= $Page->employee_email->headerCellClass() ?>"><span id="elh_employee_employee_email" class="employee_employee_email"><?= $Page->employee_email->caption() ?></span></th>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
        <th class="<?= $Page->birth_date->headerCellClass() ?>"><span id="elh_employee_birth_date" class="employee_birth_date"><?= $Page->birth_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
        <th class="<?= $Page->religion->headerCellClass() ?>"><span id="elh_employee_religion" class="employee_religion"><?= $Page->religion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
        <th class="<?= $Page->nik->headerCellClass() ?>"><span id="elh_employee_nik" class="employee_nik"><?= $Page->nik->caption() ?></span></th>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
        <th class="<?= $Page->npwp->headerCellClass() ?>"><span id="elh_employee_npwp" class="employee_npwp"><?= $Page->npwp->caption() ?></span></th>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
        <th class="<?= $Page->address->headerCellClass() ?>"><span id="elh_employee_address" class="employee_address"><?= $Page->address->caption() ?></span></th>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <th class="<?= $Page->city_id->headerCellClass() ?>"><span id="elh_employee_city_id" class="employee_city_id"><?= $Page->city_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
        <th class="<?= $Page->postal_code->headerCellClass() ?>"><span id="elh_employee_postal_code" class="employee_postal_code"><?= $Page->postal_code->caption() ?></span></th>
<?php } ?>
<?php if ($Page->bank_number->Visible) { // bank_number ?>
        <th class="<?= $Page->bank_number->headerCellClass() ?>"><span id="elh_employee_bank_number" class="employee_bank_number"><?= $Page->bank_number->caption() ?></span></th>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
        <th class="<?= $Page->bank_name->headerCellClass() ?>"><span id="elh_employee_bank_name" class="employee_bank_name"><?= $Page->bank_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
        <th class="<?= $Page->scan_ktp->headerCellClass() ?>"><span id="elh_employee_scan_ktp" class="employee_scan_ktp"><?= $Page->scan_ktp->caption() ?></span></th>
<?php } ?>
<?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
        <th class="<?= $Page->scan_npwp->headerCellClass() ?>"><span id="elh_employee_scan_npwp" class="employee_scan_npwp"><?= $Page->scan_npwp->caption() ?></span></th>
<?php } ?>
<?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <th class="<?= $Page->curiculum_vitae->headerCellClass() ?>"><span id="elh_employee_curiculum_vitae" class="employee_curiculum_vitae"><?= $Page->curiculum_vitae->caption() ?></span></th>
<?php } ?>
<?php if ($Page->position_id->Visible) { // position_id ?>
        <th class="<?= $Page->position_id->headerCellClass() ?>"><span id="elh_employee_position_id" class="employee_position_id"><?= $Page->position_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
        <th class="<?= $Page->status_id->headerCellClass() ?>"><span id="elh_employee_status_id" class="employee_status_id"><?= $Page->status_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
        <th class="<?= $Page->skill_id->headerCellClass() ?>"><span id="elh_employee_skill_id" class="employee_skill_id"><?= $Page->skill_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
        <th class="<?= $Page->office_id->headerCellClass() ?>"><span id="elh_employee_office_id" class="employee_office_id"><?= $Page->office_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
        <th class="<?= $Page->hire_date->headerCellClass() ?>"><span id="elh_employee_hire_date" class="employee_hire_date"><?= $Page->hire_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
        <th class="<?= $Page->termination_date->headerCellClass() ?>"><span id="elh_employee_termination_date" class="employee_termination_date"><?= $Page->termination_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->user_level->Visible) { // user_level ?>
        <th class="<?= $Page->user_level->headerCellClass() ?>"><span id="elh_employee_user_level" class="employee_user_level"><?= $Page->user_level->caption() ?></span></th>
<?php } ?>
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
        <th class="<?= $Page->technical_skill->headerCellClass() ?>"><span id="elh_employee_technical_skill" class="employee_technical_skill"><?= $Page->technical_skill->caption() ?></span></th>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
        <th class="<?= $Page->about_me->headerCellClass() ?>"><span id="elh_employee_about_me" class="employee_about_me"><?= $Page->about_me->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->employee_name->Visible) { // employee_name ?>
        <td <?= $Page->employee_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_employee_name" class="employee_employee_name">
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <td <?= $Page->employee_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_employee_username" class="employee_employee_username">
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
        <td <?= $Page->employee_password->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_employee_password" class="employee_employee_password">
<span<?= $Page->employee_password->viewAttributes() ?>>
<?= $Page->employee_password->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
        <td <?= $Page->employee_email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_employee_email" class="employee_employee_email">
<span<?= $Page->employee_email->viewAttributes() ?>>
<?= $Page->employee_email->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
        <td <?= $Page->birth_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_birth_date" class="employee_birth_date">
<span<?= $Page->birth_date->viewAttributes() ?>>
<?= $Page->birth_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
        <td <?= $Page->religion->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_religion" class="employee_religion">
<span<?= $Page->religion->viewAttributes() ?>>
<?= $Page->religion->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
        <td <?= $Page->nik->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_nik" class="employee_nik">
<span<?= $Page->nik->viewAttributes() ?>>
<?= $Page->nik->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
        <td <?= $Page->npwp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_npwp" class="employee_npwp">
<span<?= $Page->npwp->viewAttributes() ?>>
<?= $Page->npwp->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
        <td <?= $Page->address->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_address" class="employee_address">
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <td <?= $Page->city_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_city_id" class="employee_city_id">
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
        <td <?= $Page->postal_code->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_postal_code" class="employee_postal_code">
<span<?= $Page->postal_code->viewAttributes() ?>>
<?= $Page->postal_code->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->bank_number->Visible) { // bank_number ?>
        <td <?= $Page->bank_number->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_bank_number" class="employee_bank_number">
<span<?= $Page->bank_number->viewAttributes() ?>>
<?= $Page->bank_number->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
        <td <?= $Page->bank_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_bank_name" class="employee_bank_name">
<span<?= $Page->bank_name->viewAttributes() ?>>
<?= $Page->bank_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
        <td <?= $Page->scan_ktp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_scan_ktp" class="employee_scan_ktp">
<span<?= $Page->scan_ktp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_ktp, $Page->scan_ktp->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
        <td <?= $Page->scan_npwp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_scan_npwp" class="employee_scan_npwp">
<span<?= $Page->scan_npwp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_npwp, $Page->scan_npwp->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <td <?= $Page->curiculum_vitae->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_curiculum_vitae" class="employee_curiculum_vitae">
<span<?= $Page->curiculum_vitae->viewAttributes() ?>>
<?= GetFileViewTag($Page->curiculum_vitae, $Page->curiculum_vitae->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->position_id->Visible) { // position_id ?>
        <td <?= $Page->position_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_position_id" class="employee_position_id">
<span<?= $Page->position_id->viewAttributes() ?>>
<?= $Page->position_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
        <td <?= $Page->status_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_status_id" class="employee_status_id">
<span<?= $Page->status_id->viewAttributes() ?>>
<?= $Page->status_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
        <td <?= $Page->skill_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_skill_id" class="employee_skill_id">
<span<?= $Page->skill_id->viewAttributes() ?>>
<?= $Page->skill_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
        <td <?= $Page->office_id->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_office_id" class="employee_office_id">
<span<?= $Page->office_id->viewAttributes() ?>>
<?= $Page->office_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
        <td <?= $Page->hire_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_hire_date" class="employee_hire_date">
<span<?= $Page->hire_date->viewAttributes() ?>>
<?= $Page->hire_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
        <td <?= $Page->termination_date->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_termination_date" class="employee_termination_date">
<span<?= $Page->termination_date->viewAttributes() ?>>
<?= $Page->termination_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->user_level->Visible) { // user_level ?>
        <td <?= $Page->user_level->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_user_level" class="employee_user_level">
<span<?= $Page->user_level->viewAttributes() ?>>
<?= $Page->user_level->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
        <td <?= $Page->technical_skill->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_technical_skill" class="employee_technical_skill">
<span<?= $Page->technical_skill->viewAttributes() ?>>
<?= $Page->technical_skill->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
        <td <?= $Page->about_me->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_employee_about_me" class="employee_about_me">
<span<?= $Page->about_me->viewAttributes() ?>>
<?= $Page->about_me->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
