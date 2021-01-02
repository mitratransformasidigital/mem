<?php

namespace MEM\prjMitralPHP;

// Page object
$CustomerPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid customer"><!-- .card -->
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
<?php if ($Page->customer_name->Visible) { // customer_name ?>
    <?php if ($Page->SortUrl($Page->customer_name) == "") { ?>
        <th class="<?= $Page->customer_name->headerCellClass() ?>"><?= $Page->customer_name->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->customer_name->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->customer_name->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->customer_name->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->customer_name->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->customer_name->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->phone_number->Visible) { // phone_number ?>
    <?php if ($Page->SortUrl($Page->phone_number) == "") { ?>
        <th class="<?= $Page->phone_number->headerCellClass() ?>"><?= $Page->phone_number->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->phone_number->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->phone_number->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->phone_number->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->phone_number->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->phone_number->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->contact->Visible) { // contact ?>
    <?php if ($Page->SortUrl($Page->contact) == "") { ?>
        <th class="<?= $Page->contact->headerCellClass() ?>"><?= $Page->contact->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->contact->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->contact->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->contact->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->contact->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->contact->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->customer_name->Visible) { // customer_name ?>
        <!-- customer_name -->
        <td<?= $Page->customer_name->cellAttributes() ?>>
<span<?= $Page->customer_name->viewAttributes() ?>>
<?= $Page->customer_name->getViewValue() ?></span>
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
<?php if ($Page->phone_number->Visible) { // phone_number ?>
        <!-- phone_number -->
        <td<?= $Page->phone_number->cellAttributes() ?>>
<span<?= $Page->phone_number->viewAttributes() ?>>
<?= $Page->phone_number->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->contact->Visible) { // contact ?>
        <!-- contact -->
        <td<?= $Page->contact->cellAttributes() ?>>
<span<?= $Page->contact->viewAttributes() ?>>
<?= $Page->contact->getViewValue() ?></span>
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
