<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeTrainingsPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid employee_trainings"><!-- .card -->
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
<?php if ($Page->employee_username->Visible) { // employee_username ?>
    <?php if ($Page->SortUrl($Page->employee_username) == "") { ?>
        <th class="<?= $Page->employee_username->headerCellClass() ?>"><?= $Page->employee_username->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->employee_username->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->employee_username->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->employee_username->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->employee_username->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->employee_username->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->training_name->Visible) { // training_name ?>
    <?php if ($Page->SortUrl($Page->training_name) == "") { ?>
        <th class="<?= $Page->training_name->headerCellClass() ?>"><?= $Page->training_name->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->training_name->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->training_name->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->training_name->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->training_name->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->training_name->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->training_start->Visible) { // training_start ?>
    <?php if ($Page->SortUrl($Page->training_start) == "") { ?>
        <th class="<?= $Page->training_start->headerCellClass() ?>"><?= $Page->training_start->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->training_start->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->training_start->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->training_start->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->training_start->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->training_start->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->training_end->Visible) { // training_end ?>
    <?php if ($Page->SortUrl($Page->training_end) == "") { ?>
        <th class="<?= $Page->training_end->headerCellClass() ?>"><?= $Page->training_end->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->training_end->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->training_end->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->training_end->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->training_end->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->training_end->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->training_company->Visible) { // training_company ?>
    <?php if ($Page->SortUrl($Page->training_company) == "") { ?>
        <th class="<?= $Page->training_company->headerCellClass() ?>"><?= $Page->training_company->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->training_company->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->training_company->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->training_company->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->training_company->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->training_company->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->certificate_start->Visible) { // certificate_start ?>
    <?php if ($Page->SortUrl($Page->certificate_start) == "") { ?>
        <th class="<?= $Page->certificate_start->headerCellClass() ?>"><?= $Page->certificate_start->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->certificate_start->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->certificate_start->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->certificate_start->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->certificate_start->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->certificate_start->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->certificate_end->Visible) { // certificate_end ?>
    <?php if ($Page->SortUrl($Page->certificate_end) == "") { ?>
        <th class="<?= $Page->certificate_end->headerCellClass() ?>"><?= $Page->certificate_end->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->certificate_end->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->certificate_end->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->certificate_end->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->certificate_end->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->certificate_end->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->training_document->Visible) { // training_document ?>
    <?php if ($Page->SortUrl($Page->training_document) == "") { ?>
        <th class="<?= $Page->training_document->headerCellClass() ?>"><?= $Page->training_document->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->training_document->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->training_document->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->training_document->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->training_document->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->training_document->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <!-- employee_username -->
        <td<?= $Page->employee_username->cellAttributes() ?>>
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->training_name->Visible) { // training_name ?>
        <!-- training_name -->
        <td<?= $Page->training_name->cellAttributes() ?>>
<span<?= $Page->training_name->viewAttributes() ?>>
<?= $Page->training_name->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->training_start->Visible) { // training_start ?>
        <!-- training_start -->
        <td<?= $Page->training_start->cellAttributes() ?>>
<span<?= $Page->training_start->viewAttributes() ?>>
<?= $Page->training_start->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->training_end->Visible) { // training_end ?>
        <!-- training_end -->
        <td<?= $Page->training_end->cellAttributes() ?>>
<span<?= $Page->training_end->viewAttributes() ?>>
<?= $Page->training_end->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->training_company->Visible) { // training_company ?>
        <!-- training_company -->
        <td<?= $Page->training_company->cellAttributes() ?>>
<span<?= $Page->training_company->viewAttributes() ?>>
<?= $Page->training_company->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->certificate_start->Visible) { // certificate_start ?>
        <!-- certificate_start -->
        <td<?= $Page->certificate_start->cellAttributes() ?>>
<span<?= $Page->certificate_start->viewAttributes() ?>>
<?= $Page->certificate_start->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->certificate_end->Visible) { // certificate_end ?>
        <!-- certificate_end -->
        <td<?= $Page->certificate_end->cellAttributes() ?>>
<span<?= $Page->certificate_end->viewAttributes() ?>>
<?= $Page->certificate_end->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->training_document->Visible) { // training_document ?>
        <!-- training_document -->
        <td<?= $Page->training_document->cellAttributes() ?>>
<span<?= $Page->training_document->viewAttributes() ?>>
<?= GetFileViewTag($Page->training_document, $Page->training_document->getViewValue(), false) ?>
</span>
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
