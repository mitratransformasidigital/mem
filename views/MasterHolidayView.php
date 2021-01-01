<?php

namespace MEM\prjMitralPHP;

// Page object
$MasterHolidayView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fmaster_holidayview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fmaster_holidayview = currentForm = new ew.Form("fmaster_holidayview", "view");
    loadjs.done("fmaster_holidayview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="fmaster_holidayview" id="fmaster_holidayview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="master_holiday">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->holiday_id->Visible) { // holiday_id ?>
    <tr id="r_holiday_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_holiday_holiday_id"><?= $Page->holiday_id->caption() ?></span></td>
        <td data-name="holiday_id" <?= $Page->holiday_id->cellAttributes() ?>>
<span id="el_master_holiday_holiday_id">
<span<?= $Page->holiday_id->viewAttributes() ?>>
<?= $Page->holiday_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->shift_id->Visible) { // shift_id ?>
    <tr id="r_shift_id">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_holiday_shift_id"><?= $Page->shift_id->caption() ?></span></td>
        <td data-name="shift_id" <?= $Page->shift_id->cellAttributes() ?>>
<span id="el_master_holiday_shift_id">
<span<?= $Page->shift_id->viewAttributes() ?>>
<?= $Page->shift_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->holiday_date->Visible) { // holiday_date ?>
    <tr id="r_holiday_date">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_holiday_holiday_date"><?= $Page->holiday_date->caption() ?></span></td>
        <td data-name="holiday_date" <?= $Page->holiday_date->cellAttributes() ?>>
<span id="el_master_holiday_holiday_date">
<span<?= $Page->holiday_date->viewAttributes() ?>>
<?= $Page->holiday_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->holiday_name->Visible) { // holiday_name ?>
    <tr id="r_holiday_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_master_holiday_holiday_name"><?= $Page->holiday_name->caption() ?></span></td>
        <td data-name="holiday_name" <?= $Page->holiday_name->cellAttributes() ?>>
<span id="el_master_holiday_holiday_name">
<span<?= $Page->holiday_name->viewAttributes() ?>>
<?= $Page->holiday_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
