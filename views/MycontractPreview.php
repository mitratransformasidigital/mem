<?php

namespace MEM\prjMitralPHP;

// Page object
$MycontractPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid mycontract"><!-- .card -->
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
<?php if ($Page->salary->Visible) { // salary ?>
    <?php if ($Page->SortUrl($Page->salary) == "") { ?>
        <th class="<?= $Page->salary->headerCellClass() ?>"><?= $Page->salary->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->salary->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->salary->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->salary->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->salary->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->salary->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->bonus->Visible) { // bonus ?>
    <?php if ($Page->SortUrl($Page->bonus) == "") { ?>
        <th class="<?= $Page->bonus->headerCellClass() ?>"><?= $Page->bonus->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->bonus->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->bonus->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->bonus->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->bonus->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->bonus->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->thr->Visible) { // thr ?>
    <?php if ($Page->SortUrl($Page->thr) == "") { ?>
        <th class="<?= $Page->thr->headerCellClass() ?>"><?= $Page->thr->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->thr->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->thr->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->thr->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->thr->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->thr->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->contract_start->Visible) { // contract_start ?>
    <?php if ($Page->SortUrl($Page->contract_start) == "") { ?>
        <th class="<?= $Page->contract_start->headerCellClass() ?>"><?= $Page->contract_start->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->contract_start->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->contract_start->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->contract_start->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->contract_start->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->contract_start->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->contract_end->Visible) { // contract_end ?>
    <?php if ($Page->SortUrl($Page->contract_end) == "") { ?>
        <th class="<?= $Page->contract_end->headerCellClass() ?>"><?= $Page->contract_end->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->contract_end->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->contract_end->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->contract_end->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->contract_end->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->contract_end->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->contract_document->Visible) { // contract_document ?>
    <?php if ($Page->SortUrl($Page->contract_document) == "") { ?>
        <th class="<?= $Page->contract_document->headerCellClass() ?>"><?= $Page->contract_document->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->contract_document->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->contract_document->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->contract_document->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->contract_document->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->contract_document->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->salary->Visible) { // salary ?>
        <!-- salary -->
        <td<?= $Page->salary->cellAttributes() ?>>
<span<?= $Page->salary->viewAttributes() ?>>
<?= $Page->salary->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->bonus->Visible) { // bonus ?>
        <!-- bonus -->
        <td<?= $Page->bonus->cellAttributes() ?>>
<span<?= $Page->bonus->viewAttributes() ?>>
<?= $Page->bonus->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->thr->Visible) { // thr ?>
        <!-- thr -->
        <td<?= $Page->thr->cellAttributes() ?>>
<span<?= $Page->thr->viewAttributes() ?>>
<div class="custom-control custom-checkbox d-inline-block">
    <input type="checkbox" id="x_thr_<?= $Page->RowCount ?>" class="custom-control-input" value="<?= $Page->thr->getViewValue() ?>" disabled<?php if (ConvertToBool($Page->thr->CurrentValue)) { ?> checked<?php } ?>>
    <label class="custom-control-label" for="x_thr_<?= $Page->RowCount ?>"></label>
</div></span>
</td>
<?php } ?>
<?php if ($Page->contract_start->Visible) { // contract_start ?>
        <!-- contract_start -->
        <td<?= $Page->contract_start->cellAttributes() ?>>
<span<?= $Page->contract_start->viewAttributes() ?>>
<?= $Page->contract_start->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->contract_end->Visible) { // contract_end ?>
        <!-- contract_end -->
        <td<?= $Page->contract_end->cellAttributes() ?>>
<span<?= $Page->contract_end->viewAttributes() ?>>
<?= $Page->contract_end->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->office_id->Visible) { // office_id ?>
        <!-- office_id -->
        <td<?= $Page->office_id->cellAttributes() ?>>
<span<?= $Page->office_id->viewAttributes() ?>>
<?= $Page->office_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->contract_document->Visible) { // contract_document ?>
        <!-- contract_document -->
        <td<?= $Page->contract_document->cellAttributes() ?>>
<span<?= $Page->contract_document->viewAttributes() ?>>
<?= GetFileViewTag($Page->contract_document, $Page->contract_document->getViewValue(), false) ?>
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
