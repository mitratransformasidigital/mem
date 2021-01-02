<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterCityPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid master_city"><!-- .card -->
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
<?php if ($Page->province_id->Visible) { // province_id ?>
    <?php if ($Page->SortUrl($Page->province_id) == "") { ?>
        <th class="<?= $Page->province_id->headerCellClass() ?>"><?= $Page->province_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->province_id->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->province_id->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->province_id->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->province_id->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->province_id->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->city->Visible) { // city ?>
    <?php if ($Page->SortUrl($Page->city) == "") { ?>
        <th class="<?= $Page->city->headerCellClass() ?>"><?= $Page->city->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->city->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->city->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->city->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->city->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->city->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->province_id->Visible) { // province_id ?>
        <!-- province_id -->
        <td<?= $Page->province_id->cellAttributes() ?>>
<span<?= $Page->province_id->viewAttributes() ?>>
<?= $Page->province_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->city_id->Visible) { // city_id ?>
        <!-- city_id -->
        <td<?= $Page->city_id->cellAttributes() ?>>
<span<?= $Page->city_id->viewAttributes() ?>>
<?= $Page->city_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->city->Visible) { // city ?>
        <!-- city -->
        <td<?= $Page->city->cellAttributes() ?>>
<span<?= $Page->city->viewAttributes() ?>>
<?= $Page->city->getViewValue() ?></span>
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
