<?php

namespace MEM\prjMitralPHP;

// Page object
$OfferingDetailPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid offering_detail"><!-- .card -->
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
<?php if ($Page->offering_id->Visible) { // offering_id ?>
    <?php if ($Page->SortUrl($Page->offering_id) == "") { ?>
        <th class="<?= $Page->offering_id->headerCellClass() ?>"><?= $Page->offering_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->offering_id->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->offering_id->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->offering_id->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->offering_id->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->offering_id->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <?php if ($Page->SortUrl($Page->description) == "") { ?>
        <th class="<?= $Page->description->headerCellClass() ?>"><?= $Page->description->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->description->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->description->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->description->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->description->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->description->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
    <?php if ($Page->SortUrl($Page->qty) == "") { ?>
        <th class="<?= $Page->qty->headerCellClass() ?>"><?= $Page->qty->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->qty->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->qty->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->qty->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->qty->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->qty->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
    <?php if ($Page->SortUrl($Page->rate) == "") { ?>
        <th class="<?= $Page->rate->headerCellClass() ?>"><?= $Page->rate->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->rate->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->rate->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->rate->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->rate->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->rate->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->discount->Visible) { // discount ?>
    <?php if ($Page->SortUrl($Page->discount) == "") { ?>
        <th class="<?= $Page->discount->headerCellClass() ?>"><?= $Page->discount->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->discount->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->discount->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->discount->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->discount->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->discount->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <?php if ($Page->SortUrl($Page->total) == "") { ?>
        <th class="<?= $Page->total->headerCellClass() ?>"><?= $Page->total->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->total->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->total->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->total->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->total->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->total->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
    $Page->aggregateListRowValues(); // Aggregate row values

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
<?php if ($Page->offering_id->Visible) { // offering_id ?>
        <!-- offering_id -->
        <td<?= $Page->offering_id->cellAttributes() ?>>
<span<?= $Page->offering_id->viewAttributes() ?>>
<?= $Page->offering_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <!-- description -->
        <td<?= $Page->description->cellAttributes() ?>>
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
        <!-- qty -->
        <td<?= $Page->qty->cellAttributes() ?>>
<span<?= $Page->qty->viewAttributes() ?>>
<?= $Page->qty->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
        <!-- rate -->
        <td<?= $Page->rate->cellAttributes() ?>>
<span<?= $Page->rate->viewAttributes() ?>>
<?= $Page->rate->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->discount->Visible) { // discount ?>
        <!-- discount -->
        <td<?= $Page->discount->cellAttributes() ?>>
<span<?= $Page->discount->viewAttributes() ?>>
<?= $Page->discount->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <!-- total -->
        <td<?= $Page->total->cellAttributes() ?>>
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
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
<?php
    // Render aggregate row
    $Page->RowType = ROWTYPE_AGGREGATE; // Aggregate
    $Page->aggregateListRow(); // Prepare aggregate row

    // Render list options
    $Page->renderListOptions();
?>
    <tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options (footer, left)
$Page->ListOptions->render("footer", "left");
?>
<?php if ($Page->offering_id->Visible) { // offering_id ?>
        <!-- offering_id -->
        <td class="<?= $Page->offering_id->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <!-- description -->
        <td class="<?= $Page->description->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->qty->Visible) { // qty ?>
        <!-- qty -->
        <td class="<?= $Page->qty->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->rate->Visible) { // rate ?>
        <!-- rate -->
        <td class="<?= $Page->rate->footerCellClass() ?>">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->rate->ViewValue ?></span>
        </td>
<?php } ?>
<?php if ($Page->discount->Visible) { // discount ?>
        <!-- discount -->
        <td class="<?= $Page->discount->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <!-- total -->
        <td class="<?= $Page->total->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php
// Render list options (footer, right)
$Page->ListOptions->render("footer", "right");
?>
    </tr>
    </tfoot>
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
