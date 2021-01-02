<?php

namespace MEM\prjMitralPHP;

// Page object
$EmployeeTimesheetPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid employee_timesheet"><!-- .card -->
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
<?php if ($Page->year->Visible) { // year ?>
    <?php if ($Page->SortUrl($Page->year) == "") { ?>
        <th class="<?= $Page->year->headerCellClass() ?>"><?= $Page->year->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->year->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->year->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->year->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->year->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->year->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->month->Visible) { // month ?>
    <?php if ($Page->SortUrl($Page->month) == "") { ?>
        <th class="<?= $Page->month->headerCellClass() ?>"><?= $Page->month->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->month->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->month->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->month->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->month->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->month->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
    <?php if ($Page->SortUrl($Page->days) == "") { ?>
        <th class="<?= $Page->days->headerCellClass() ?>"><?= $Page->days->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->days->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->days->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->days->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->days->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->days->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->sick->Visible) { // sick ?>
    <?php if ($Page->SortUrl($Page->sick) == "") { ?>
        <th class="<?= $Page->sick->headerCellClass() ?>"><?= $Page->sick->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->sick->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->sick->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->sick->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->sick->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->sick->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->leave->Visible) { // leave ?>
    <?php if ($Page->SortUrl($Page->leave) == "") { ?>
        <th class="<?= $Page->leave->headerCellClass() ?>"><?= $Page->leave->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->leave->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->leave->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->leave->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->leave->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->leave->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->permit->Visible) { // permit ?>
    <?php if ($Page->SortUrl($Page->permit) == "") { ?>
        <th class="<?= $Page->permit->headerCellClass() ?>"><?= $Page->permit->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->permit->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->permit->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->permit->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->permit->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->permit->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
    <?php if ($Page->SortUrl($Page->absence) == "") { ?>
        <th class="<?= $Page->absence->headerCellClass() ?>"><?= $Page->absence->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->absence->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->absence->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->absence->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->absence->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->absence->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
    <?php if ($Page->SortUrl($Page->timesheet_doc) == "") { ?>
        <th class="<?= $Page->timesheet_doc->headerCellClass() ?>"><?= $Page->timesheet_doc->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->timesheet_doc->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->timesheet_doc->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->timesheet_doc->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->timesheet_doc->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->timesheet_doc->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->approved->Visible) { // approved ?>
    <?php if ($Page->SortUrl($Page->approved) == "") { ?>
        <th class="<?= $Page->approved->headerCellClass() ?>"><?= $Page->approved->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->approved->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->approved->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->approved->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->approved->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->approved->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <!-- employee_username -->
        <td<?= $Page->employee_username->cellAttributes() ?>>
<span<?= $Page->employee_username->viewAttributes() ?>>
<?= $Page->employee_username->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
        <!-- year -->
        <td<?= $Page->year->cellAttributes() ?>>
<span<?= $Page->year->viewAttributes() ?>>
<?= $Page->year->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->month->Visible) { // month ?>
        <!-- month -->
        <td<?= $Page->month->cellAttributes() ?>>
<span<?= $Page->month->viewAttributes() ?>>
<?= $Page->month->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
        <!-- days -->
        <td<?= $Page->days->cellAttributes() ?>>
<span<?= $Page->days->viewAttributes() ?>>
<?= $Page->days->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->sick->Visible) { // sick ?>
        <!-- sick -->
        <td<?= $Page->sick->cellAttributes() ?>>
<span<?= $Page->sick->viewAttributes() ?>>
<?= $Page->sick->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->leave->Visible) { // leave ?>
        <!-- leave -->
        <td<?= $Page->leave->cellAttributes() ?>>
<span<?= $Page->leave->viewAttributes() ?>>
<?= $Page->leave->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->permit->Visible) { // permit ?>
        <!-- permit -->
        <td<?= $Page->permit->cellAttributes() ?>>
<span<?= $Page->permit->viewAttributes() ?>>
<?= $Page->permit->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
        <!-- absence -->
        <td<?= $Page->absence->cellAttributes() ?>>
<span<?= $Page->absence->viewAttributes() ?>>
<?= $Page->absence->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
        <!-- timesheet_doc -->
        <td<?= $Page->timesheet_doc->cellAttributes() ?>>
<span<?= $Page->timesheet_doc->viewAttributes() ?>>
<?= GetFileViewTag($Page->timesheet_doc, $Page->timesheet_doc->getViewValue(), false) ?>
</span>
</td>
<?php } ?>
<?php if ($Page->approved->Visible) { // approved ?>
        <!-- approved -->
        <td<?= $Page->approved->cellAttributes() ?>>
<span<?= $Page->approved->viewAttributes() ?>>
<?= $Page->approved->getViewValue() ?></span>
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
<?php if ($Page->employee_username->Visible) { // employee_username ?>
        <!-- employee_username -->
        <td class="<?= $Page->employee_username->footerCellClass() ?>">
        <span class="ew-aggregate"><?= $Language->phrase("COUNT") ?></span><span class="ew-aggregate-value">
        <?= $Page->employee_username->ViewValue ?></span>
        </td>
<?php } ?>
<?php if ($Page->year->Visible) { // year ?>
        <!-- year -->
        <td class="<?= $Page->year->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->month->Visible) { // month ?>
        <!-- month -->
        <td class="<?= $Page->month->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->days->Visible) { // days ?>
        <!-- days -->
        <td class="<?= $Page->days->footerCellClass() ?>">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->days->ViewValue ?></span>
        </td>
<?php } ?>
<?php if ($Page->sick->Visible) { // sick ?>
        <!-- sick -->
        <td class="<?= $Page->sick->footerCellClass() ?>">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->sick->ViewValue ?></span>
        </td>
<?php } ?>
<?php if ($Page->leave->Visible) { // leave ?>
        <!-- leave -->
        <td class="<?= $Page->leave->footerCellClass() ?>">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->leave->ViewValue ?></span>
        </td>
<?php } ?>
<?php if ($Page->permit->Visible) { // permit ?>
        <!-- permit -->
        <td class="<?= $Page->permit->footerCellClass() ?>">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->permit->ViewValue ?></span>
        </td>
<?php } ?>
<?php if ($Page->absence->Visible) { // absence ?>
        <!-- absence -->
        <td class="<?= $Page->absence->footerCellClass() ?>">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->absence->ViewValue ?></span>
        </td>
<?php } ?>
<?php if ($Page->timesheet_doc->Visible) { // timesheet_doc ?>
        <!-- timesheet_doc -->
        <td class="<?= $Page->timesheet_doc->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->approved->Visible) { // approved ?>
        <!-- approved -->
        <td class="<?= $Page->approved->footerCellClass() ?>">
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
