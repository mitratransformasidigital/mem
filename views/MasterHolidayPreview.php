<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterHolidayPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid master_holiday"><!-- .card -->
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
<?php if ($Page->shift_id->Visible) { // shift_id ?>
    <?php if ($Page->SortUrl($Page->shift_id) == "") { ?>
        <th class="<?= $Page->shift_id->headerCellClass() ?>"><?= $Page->shift_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->shift_id->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->shift_id->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->shift_id->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->shift_id->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->shift_id->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->holiday_date->Visible) { // holiday_date ?>
    <?php if ($Page->SortUrl($Page->holiday_date) == "") { ?>
        <th class="<?= $Page->holiday_date->headerCellClass() ?>"><?= $Page->holiday_date->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->holiday_date->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->holiday_date->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->holiday_date->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->holiday_date->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->holiday_date->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->holiday_name->Visible) { // holiday_name ?>
    <?php if ($Page->SortUrl($Page->holiday_name) == "") { ?>
        <th class="<?= $Page->holiday_name->headerCellClass() ?>"><?= $Page->holiday_name->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->holiday_name->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->holiday_name->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->holiday_name->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->holiday_name->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->holiday_name->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->shift_id->Visible) { // shift_id ?>
        <!-- shift_id -->
        <td<?= $Page->shift_id->cellAttributes() ?>>
<span<?= $Page->shift_id->viewAttributes() ?>>
<?= $Page->shift_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->holiday_date->Visible) { // holiday_date ?>
        <!-- holiday_date -->
        <td<?= $Page->holiday_date->cellAttributes() ?>>
<span<?= $Page->holiday_date->viewAttributes() ?>>
<?= $Page->holiday_date->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->holiday_name->Visible) { // holiday_name ?>
        <!-- holiday_name -->
        <td<?= $Page->holiday_name->cellAttributes() ?>>
<span<?= $Page->holiday_name->viewAttributes() ?>>
<?= $Page->holiday_name->getViewValue() ?></span>
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
