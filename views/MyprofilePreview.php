<?php

namespace MEM\prjMitralPHP;

// Page object
$MyprofilePreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid myprofile"><!-- .card -->
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel ew-preview-middle-panel"><!-- .table-responsive -->
<table class="table ew-table ew-preview-table"><!-- .table -->
    <thead><!-- Table header -->
        <tr class="ew-table-header">
<?php
// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->employee_name->Visible) { // employee_name ?>
    <?php if ($Page->SortUrl($Page->employee_name) == "") { ?>
        <th class="<?= $Page->employee_name->headerCellClass() ?>"><?= $Page->employee_name->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->employee_name->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->employee_name->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->employee_name->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->employee_name->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->employee_name->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <?php if ($Page->SortUrl($Page->employee_username) == "") { ?>
        <th class="<?= $Page->employee_username->headerCellClass() ?>"><?= $Page->employee_username->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->employee_username->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->employee_username->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->employee_username->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->employee_username->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->employee_username->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
    <?php if ($Page->SortUrl($Page->employee_password) == "") { ?>
        <th class="<?= $Page->employee_password->headerCellClass() ?>"><?= $Page->employee_password->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->employee_password->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->employee_password->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->employee_password->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->employee_password->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->employee_password->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
    <?php if ($Page->SortUrl($Page->employee_email) == "") { ?>
        <th class="<?= $Page->employee_email->headerCellClass() ?>"><?= $Page->employee_email->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->employee_email->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->employee_email->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->employee_email->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->employee_email->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->employee_email->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
    <?php if ($Page->SortUrl($Page->birth_date) == "") { ?>
        <th class="<?= $Page->birth_date->headerCellClass() ?>"><?= $Page->birth_date->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->birth_date->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->birth_date->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->birth_date->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->birth_date->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->birth_date->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
    <?php if ($Page->SortUrl($Page->nik) == "") { ?>
        <th class="<?= $Page->nik->headerCellClass() ?>"><?= $Page->nik->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->nik->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->nik->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->nik->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->nik->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->nik->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
    <?php if ($Page->SortUrl($Page->npwp) == "") { ?>
        <th class="<?= $Page->npwp->headerCellClass() ?>"><?= $Page->npwp->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->npwp->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->npwp->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->npwp->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->npwp->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->npwp->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
    <?php if ($Page->SortUrl($Page->address) == "") { ?>
        <th class="<?= $Page->address->headerCellClass() ?>"><?= $Page->address->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->address->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->address->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->address->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->address->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->address->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
    <?php if ($Page->SortUrl($Page->city_id) == "") { ?>
        <th class="<?= $Page->city_id->headerCellClass() ?>"><?= $Page->city_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->city_id->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->city_id->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->city_id->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->city_id->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->city_id->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
    <?php if ($Page->SortUrl($Page->postal_code) == "") { ?>
        <th class="<?= $Page->postal_code->headerCellClass() ?>"><?= $Page->postal_code->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->postal_code->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->postal_code->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->postal_code->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->postal_code->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->postal_code->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->bank_number->Visible) { // bank_number ?>
    <?php if ($Page->SortUrl($Page->bank_number) == "") { ?>
        <th class="<?= $Page->bank_number->headerCellClass() ?>"><?= $Page->bank_number->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->bank_number->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->bank_number->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->bank_number->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->bank_number->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->bank_number->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
    <?php if ($Page->SortUrl($Page->bank_name) == "") { ?>
        <th class="<?= $Page->bank_name->headerCellClass() ?>"><?= $Page->bank_name->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->bank_name->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->bank_name->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->bank_name->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->bank_name->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->bank_name->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
    <?php if ($Page->SortUrl($Page->scan_ktp) == "") { ?>
        <th class="<?= $Page->scan_ktp->headerCellClass() ?>"><?= $Page->scan_ktp->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->scan_ktp->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->scan_ktp->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->scan_ktp->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->scan_ktp->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->scan_ktp->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
    <?php if ($Page->SortUrl($Page->scan_npwp) == "") { ?>
        <th class="<?= $Page->scan_npwp->headerCellClass() ?>"><?= $Page->scan_npwp->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->scan_npwp->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->scan_npwp->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->scan_npwp->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->scan_npwp->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->scan_npwp->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
    <?php if ($Page->SortUrl($Page->curiculum_vitae) == "") { ?>
        <th class="<?= $Page->curiculum_vitae->headerCellClass() ?>"><?= $Page->curiculum_vitae->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->curiculum_vitae->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->curiculum_vitae->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->curiculum_vitae->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->curiculum_vitae->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->curiculum_vitae->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
    <?php if ($Page->SortUrl($Page->technical_skill) == "") { ?>
        <th class="<?= $Page->technical_skill->headerCellClass() ?>"><?= $Page->technical_skill->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->technical_skill->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->technical_skill->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->technical_skill->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->technical_skill->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->technical_skill->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
    <?php if ($Page->SortUrl($Page->about_me) == "") { ?>
        <th class="<?= $Page->about_me->headerCellClass() ?>"><?= $Page->about_me->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->about_me->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->about_me->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->about_me->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->about_me->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->about_me->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->position_id->Visible) { // position_id ?>
    <?php if ($Page->SortUrl($Page->position_id) == "") { ?>
        <th class="<?= $Page->position_id->headerCellClass() ?>"><?= $Page->position_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->position_id->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->position_id->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->position_id->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->position_id->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->position_id->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
    <?php if ($Page->SortUrl($Page->religion) == "") { ?>
        <th class="<?= $Page->religion->headerCellClass() ?>"><?= $Page->religion->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->religion->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->religion->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->religion->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->religion->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->religion->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
    <?php if ($Page->SortUrl($Page->status_id) == "") { ?>
        <th class="<?= $Page->status_id->headerCellClass() ?>"><?= $Page->status_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->status_id->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->status_id->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->status_id->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->status_id->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->status_id->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
    <?php if ($Page->SortUrl($Page->skill_id) == "") { ?>
        <th class="<?= $Page->skill_id->headerCellClass() ?>"><?= $Page->skill_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->skill_id->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->skill_id->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->skill_id->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->skill_id->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->skill_id->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
    <?php if ($Page->SortUrl($Page->office_id) == "") { ?>
        <th class="<?= $Page->office_id->headerCellClass() ?>"><?= $Page->office_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->office_id->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->office_id->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->office_id->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->office_id->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->office_id->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
    <?php if ($Page->SortUrl($Page->hire_date) == "") { ?>
        <th class="<?= $Page->hire_date->headerCellClass() ?>"><?= $Page->hire_date->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->hire_date->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->hire_date->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->hire_date->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->hire_date->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->hire_date->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
    <?php if ($Page->SortUrl($Page->termination_date) == "") { ?>
        <th class="<?= $Page->termination_date->headerCellClass() ?>"><?= $Page->termination_date->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->termination_date->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->termination_date->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->termination_date->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->termination_date->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->termination_date->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
        </tr>
    </thead>
    <tbody><!-- Table body -->
<?php
$Page->RecCount = 0;
$Page->RowCount = 0;
while ($Page->Recordset && !$Page->Recordset->EOF) {
    // Init row class and style
    $Page->RecCount++;
    $Page->RowCount++;
    $Page->CssStyle = "";
    $Page->loadListRowValues($Page->Recordset);

    // Render row
    $Page->RowType = ROWTYPE_PREVIEW; // Preview record
    $Page->resetAttributes();
    $Page->renderListRow();

    // Render list options
    $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
<?php if ($Page->employee_name->Visible) { // employee_name ?>
        <!-- employee_name -->
        <td<?= $Page->employee_name->cellAttributes() ?>>
<span<?= $Page->employee_name->viewAttributes() ?>>
<?= $Page->employee_name->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <!-- employee_username -->
        <td<?= $Page->employee_username->cellAttributes() ?>>
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->employee_password->Visible) { // employee_password ?>
        <!-- employee_password -->
        <td<?= $Page->employee_password->cellAttributes() ?>>
<span<?= $Page->employee_password->viewAttributes() ?>>
<?= $Page->employee_password->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->employee_email->Visible) { // employee_email ?>
        <!-- employee_email -->
        <td<?= $Page->employee_email->cellAttributes() ?>>
<span<?= $Page->employee_email->viewAttributes() ?>>
<?= $Page->employee_email->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->birth_date->Visible) { // birth_date ?>
        <!-- birth_date -->
        <td<?= $Page->birth_date->cellAttributes() ?>>
<span<?= $Page->birth_date->viewAttributes() ?>>
<?= $Page->birth_date->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->nik->Visible) { // nik ?>
        <!-- nik -->
        <td<?= $Page->nik->cellAttributes() ?>>
<span<?= $Page->nik->viewAttributes() ?>>
<?= $Page->nik->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->npwp->Visible) { // npwp ?>
        <!-- npwp -->
        <td<?= $Page->npwp->cellAttributes() ?>>
<span<?= $Page->npwp->viewAttributes() ?>>
<?= $Page->npwp->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->address->Visible) { // address ?>
        <!-- address -->
        <td<?= $Page->address->cellAttributes() ?>>
<span<?= $Page->address->viewAttributes() ?>>
<?= $Page->address->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <!-- city_id -->
        <td<?= $Page->city_id->cellAttributes() ?>>
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->postal_code->Visible) { // postal_code ?>
        <!-- postal_code -->
        <td<?= $Page->postal_code->cellAttributes() ?>>
<span<?= $Page->postal_code->viewAttributes() ?>>
<?= $Page->postal_code->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->bank_number->Visible) { // bank_number ?>
        <!-- bank_number -->
        <td<?= $Page->bank_number->cellAttributes() ?>>
<span<?= $Page->bank_number->viewAttributes() ?>>
<?= $Page->bank_number->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->bank_name->Visible) { // bank_name ?>
        <!-- bank_name -->
        <td<?= $Page->bank_name->cellAttributes() ?>>
<span<?= $Page->bank_name->viewAttributes() ?>>
<?= $Page->bank_name->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->scan_ktp->Visible) { // scan_ktp ?>
        <!-- scan_ktp -->
        <td<?= $Page->scan_ktp->cellAttributes() ?>>
<span<?= $Page->scan_ktp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_ktp, $Page->scan_ktp->getViewValue(), false) ?>
</span>
</td>
<?php } ?>
<?php if ($Page->scan_npwp->Visible) { // scan_npwp ?>
        <!-- scan_npwp -->
        <td<?= $Page->scan_npwp->cellAttributes() ?>>
<span<?= $Page->scan_npwp->viewAttributes() ?>>
<?= GetFileViewTag($Page->scan_npwp, $Page->scan_npwp->getViewValue(), false) ?>
</span>
</td>
<?php } ?>
<?php if ($Page->curiculum_vitae->Visible) { // curiculum_vitae ?>
        <!-- curiculum_vitae -->
        <td<?= $Page->curiculum_vitae->cellAttributes() ?>>
<span<?= $Page->curiculum_vitae->viewAttributes() ?>>
<?= GetFileViewTag($Page->curiculum_vitae, $Page->curiculum_vitae->getViewValue(), false) ?>
</span>
</td>
<?php } ?>
<?php if ($Page->technical_skill->Visible) { // technical_skill ?>
        <!-- technical_skill -->
        <td<?= $Page->technical_skill->cellAttributes() ?>>
<span<?= $Page->technical_skill->viewAttributes() ?>>
<?= $Page->technical_skill->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->about_me->Visible) { // about_me ?>
        <!-- about_me -->
        <td<?= $Page->about_me->cellAttributes() ?>>
<span<?= $Page->about_me->viewAttributes() ?>>
<?= $Page->about_me->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->position_id->Visible) { // position_id ?>
        <!-- position_id -->
        <td<?= $Page->position_id->cellAttributes() ?>>
<span<?= $Page->position_id->viewAttributes() ?>>
<?= $Page->position_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->religion->Visible) { // religion ?>
        <!-- religion -->
        <td<?= $Page->religion->cellAttributes() ?>>
<span<?= $Page->religion->viewAttributes() ?>>
<?= $Page->religion->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->status_id->Visible) { // status_id ?>
        <!-- status_id -->
        <td<?= $Page->status_id->cellAttributes() ?>>
<span<?= $Page->status_id->viewAttributes() ?>>
<?= $Page->status_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->skill_id->Visible) { // skill_id ?>
        <!-- skill_id -->
        <td<?= $Page->skill_id->cellAttributes() ?>>
<span<?= $Page->skill_id->viewAttributes() ?>>
<?= $Page->skill_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
        <!-- office_id -->
        <td<?= $Page->office_id->cellAttributes() ?>>
<span<?= $Page->office_id->viewAttributes() ?>>
<?= $Page->office_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->hire_date->Visible) { // hire_date ?>
        <!-- hire_date -->
        <td<?= $Page->hire_date->cellAttributes() ?>>
<span<?= $Page->hire_date->viewAttributes() ?>>
<?= $Page->hire_date->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->termination_date->Visible) { // termination_date ?>
        <!-- termination_date -->
        <td<?= $Page->termination_date->cellAttributes() ?>>
<span<?= $Page->termination_date->viewAttributes() ?>>
<?= $Page->termination_date->getViewValue() ?></span>
</td>
<?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    $Page->Recordset->moveNext();
} // while
?>
    </tbody>
</table><!-- /.table -->
</div><!-- /.table-responsive -->
<div class="card-footer ew-grid-lower-panel ew-preview-lower-panel"><!-- .card-footer -->
<?= $Page->Pager->render() ?>
<?php } else { // No record ?>
<div class="card no-border">
<div class="ew-detail-count"><?= $Language->phrase("NoRecord") ?></div>
<?php } ?>
<div class="ew-preview-other-options">
<?php
    foreach ($Page->OtherOptions as $option)
        $option->render("body");
?>
</div>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="clearfix"></div>
</div><!-- /.card-footer -->
<?php } ?>
</div><!-- /.card -->
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
