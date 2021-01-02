<?php

namespace MEM\prjMitralPHP;

// Page object
$PermitPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid permit"><!-- .card -->
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
<?php if ($Page->start_date->Visible) { // start_date ?>
    <?php if ($Page->SortUrl($Page->start_date) == "") { ?>
        <th class="<?= $Page->start_date->headerCellClass() ?>"><?= $Page->start_date->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->start_date->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->start_date->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->start_date->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->start_date->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->start_date->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->end_date->Visible) { // end_date ?>
    <?php if ($Page->SortUrl($Page->end_date) == "") { ?>
        <th class="<?= $Page->end_date->headerCellClass() ?>"><?= $Page->end_date->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->end_date->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->end_date->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->end_date->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->end_date->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->end_date->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->permit_type->Visible) { // permit_type ?>
    <?php if ($Page->SortUrl($Page->permit_type) == "") { ?>
        <th class="<?= $Page->permit_type->headerCellClass() ?>"><?= $Page->permit_type->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->permit_type->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->permit_type->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->permit_type->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->permit_type->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->permit_type->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
    <?php if ($Page->SortUrl($Page->document) == "") { ?>
        <th class="<?= $Page->document->headerCellClass() ?>"><?= $Page->document->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->document->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->document->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->document->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->document->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->document->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->note->Visible) { // note ?>
    <?php if ($Page->SortUrl($Page->note) == "") { ?>
        <th class="<?= $Page->note->headerCellClass() ?>"><?= $Page->note->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->note->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->note->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->note->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->note->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->note->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->start_date->Visible) { // start_date ?>
        <!-- start_date -->
        <td<?= $Page->start_date->cellAttributes() ?>>
<span<?= $Page->start_date->viewAttributes() ?>>
<?= $Page->start_date->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->end_date->Visible) { // end_date ?>
        <!-- end_date -->
        <td<?= $Page->end_date->cellAttributes() ?>>
<span<?= $Page->end_date->viewAttributes() ?>>
<?= $Page->end_date->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->permit_type->Visible) { // permit_type ?>
        <!-- permit_type -->
        <td<?= $Page->permit_type->cellAttributes() ?>>
<span<?= $Page->permit_type->viewAttributes() ?>>
<?php if (!EmptyString($Page->permit_type->getViewValue()) && $Page->permit_type->linkAttributes() != "") { ?>
<a<?= $Page->permit_type->linkAttributes() ?>><?= $Page->permit_type->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->permit_type->getViewValue() ?>
<?php } ?>
</span>
</td>
<?php } ?>
<?php if ($Page->document->Visible) { // document ?>
        <!-- document -->
        <td<?= $Page->document->cellAttributes() ?>>
<span<?= $Page->document->viewAttributes() ?>>
<?= GetFileViewTag($Page->document, $Page->document->getViewValue(), false) ?>
</span>
</td>
<?php } ?>
<?php if ($Page->note->Visible) { // note ?>
        <!-- note -->
        <td<?= $Page->note->cellAttributes() ?>>
<span<?= $Page->note->viewAttributes() ?>>
<?= $Page->note->getViewValue() ?></span>
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
