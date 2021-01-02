<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeAssetPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid employee_asset"><!-- .card -->
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
<?php if ($Page->asset_name->Visible) { // asset_name ?>
    <?php if ($Page->SortUrl($Page->asset_name) == "") { ?>
        <th class="<?= $Page->asset_name->headerCellClass() ?>"><?= $Page->asset_name->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->asset_name->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->asset_name->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->asset_name->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->asset_name->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->asset_name->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
    <?php if ($Page->SortUrl($Page->year) == "") { ?>
        <th class="<?= $Page->year->headerCellClass() ?>"><?= $Page->year->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->year->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->year->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->year->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->year->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->year->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->serial_number->Visible) { // serial_number ?>
    <?php if ($Page->SortUrl($Page->serial_number) == "") { ?>
        <th class="<?= $Page->serial_number->headerCellClass() ?>"><?= $Page->serial_number->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->serial_number->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->serial_number->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->serial_number->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->serial_number->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->serial_number->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->value->Visible) { // value ?>
    <?php if ($Page->SortUrl($Page->value) == "") { ?>
        <th class="<?= $Page->value->headerCellClass() ?>"><?= $Page->value->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->value->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->value->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->value->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->value->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->value->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->asset_received->Visible) { // asset_received ?>
    <?php if ($Page->SortUrl($Page->asset_received) == "") { ?>
        <th class="<?= $Page->asset_received->headerCellClass() ?>"><?= $Page->asset_received->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->asset_received->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->asset_received->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->asset_received->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->asset_received->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->asset_received->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->asset_return->Visible) { // asset_return ?>
    <?php if ($Page->SortUrl($Page->asset_return) == "") { ?>
        <th class="<?= $Page->asset_return->headerCellClass() ?>"><?= $Page->asset_return->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->asset_return->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->asset_return->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->asset_return->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->asset_return->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->asset_return->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->asset_picture->Visible) { // asset_picture ?>
    <?php if ($Page->SortUrl($Page->asset_picture) == "") { ?>
        <th class="<?= $Page->asset_picture->headerCellClass() ?>"><?= $Page->asset_picture->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->asset_picture->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->asset_picture->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->asset_picture->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->asset_picture->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->asset_picture->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->asset_name->Visible) { // asset_name ?>
        <!-- asset_name -->
        <td<?= $Page->asset_name->cellAttributes() ?>>
<span<?= $Page->asset_name->viewAttributes() ?>>
<?= $Page->asset_name->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
        <!-- year -->
        <td<?= $Page->year->cellAttributes() ?>>
<span<?= $Page->year->viewAttributes() ?>>
<?= $Page->year->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->serial_number->Visible) { // serial_number ?>
        <!-- serial_number -->
        <td<?= $Page->serial_number->cellAttributes() ?>>
<span<?= $Page->serial_number->viewAttributes() ?>>
<?= $Page->serial_number->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->value->Visible) { // value ?>
        <!-- value -->
        <td<?= $Page->value->cellAttributes() ?>>
<span<?= $Page->value->viewAttributes() ?>>
<?= $Page->value->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->asset_received->Visible) { // asset_received ?>
        <!-- asset_received -->
        <td<?= $Page->asset_received->cellAttributes() ?>>
<span<?= $Page->asset_received->viewAttributes() ?>>
<?= $Page->asset_received->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->asset_return->Visible) { // asset_return ?>
        <!-- asset_return -->
        <td<?= $Page->asset_return->cellAttributes() ?>>
<span<?= $Page->asset_return->viewAttributes() ?>>
<?= $Page->asset_return->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->asset_picture->Visible) { // asset_picture ?>
        <!-- asset_picture -->
        <td<?= $Page->asset_picture->cellAttributes() ?>>
<span<?= $Page->asset_picture->viewAttributes() ?>>
<?= GetFileViewTag($Page->asset_picture, $Page->asset_picture->getViewValue(), false) ?>
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
