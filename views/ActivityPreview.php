<?php

namespace MEM\prjMitralPHP;

// Page object
$ActivityPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid activity"><!-- .card -->
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
<?php if ($Page->activity_date->Visible) { // activity_date ?>
    <?php if ($Page->SortUrl($Page->activity_date) == "") { ?>
        <th class="<?= $Page->activity_date->headerCellClass() ?>"><?= $Page->activity_date->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->activity_date->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->activity_date->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->activity_date->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->activity_date->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->activity_date->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
    <?php if ($Page->SortUrl($Page->time_in) == "") { ?>
        <th class="<?= $Page->time_in->headerCellClass() ?>"><?= $Page->time_in->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->time_in->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->time_in->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->time_in->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->time_in->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->time_in->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
    <?php if ($Page->SortUrl($Page->time_out) == "") { ?>
        <th class="<?= $Page->time_out->headerCellClass() ?>"><?= $Page->time_out->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->time_out->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->time_out->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->time_out->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->time_out->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->time_out->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <?php if ($Page->SortUrl($Page->_action) == "") { ?>
        <th class="<?= $Page->_action->headerCellClass() ?>"><?= $Page->_action->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->_action->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->_action->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->_action->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->_action->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->_action->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->notes->Visible) { // notes ?>
    <?php if ($Page->SortUrl($Page->notes) == "") { ?>
        <th class="<?= $Page->notes->headerCellClass() ?>"><?= $Page->notes->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->notes->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->notes->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->notes->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->notes->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->notes->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->activity_date->Visible) { // activity_date ?>
        <!-- activity_date -->
        <td<?= $Page->activity_date->cellAttributes() ?>>
<span<?= $Page->activity_date->viewAttributes() ?>>
<?= $Page->activity_date->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->time_in->Visible) { // time_in ?>
        <!-- time_in -->
        <td<?= $Page->time_in->cellAttributes() ?>>
<span<?= $Page->time_in->viewAttributes() ?>>
<?= $Page->time_in->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->time_out->Visible) { // time_out ?>
        <!-- time_out -->
        <td<?= $Page->time_out->cellAttributes() ?>>
<span<?= $Page->time_out->viewAttributes() ?>>
<?= $Page->time_out->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
        <!-- action -->
        <td<?= $Page->_action->cellAttributes() ?>>
<span<?= $Page->_action->viewAttributes() ?>>
<?= $Page->_action->getViewValue() ?></span>
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
<?php if ($Page->notes->Visible) { // notes ?>
        <!-- notes -->
        <td<?= $Page->notes->cellAttributes() ?>>
<span<?= $Page->notes->viewAttributes() ?>>
<?= $Page->notes->getViewValue() ?></span>
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
