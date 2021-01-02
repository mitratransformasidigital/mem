<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class QuotationPrintSummary extends QuotationPrint
{
    use MessagesTrait;

    // Page ID
    public $PageID = "summary";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'quotation_print';

    // Page object name
    public $PageObjName = "QuotationPrintSummary";

    // Rendering View
    public $RenderingView = false;

    // CSS
    public $ReportTableClass = "";
    public $ReportTableStyle = "";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $CopyUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $ListUrl;

    // Export URLs
    public $ExportPrintUrl;
    public $ExportHtmlUrl;
    public $ExportExcelUrl;
    public $ExportWordUrl;
    public $ExportXmlUrl;
    public $ExportCsvUrl;
    public $ExportPdfUrl;

    // Custom export
    public $ExportExcelCustom = true;
    public $ExportWordCustom = true;
    public $ExportPdfCustom = true;
    public $ExportEmailCustom = true;

    // Update URLs
    public $InlineAddUrl;
    public $InlineCopyUrl;
    public $InlineEditUrl;
    public $GridAddUrl;
    public $GridEditUrl;
    public $MultiDeleteUrl;
    public $MultiUpdateUrl;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl()
    {
        $url = ScriptName() . "?";
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Custom template
        $this->UseCustomTemplate = true;

        // Initialize
        $GLOBALS["Page"] = &$this;
        $this->TokenTimeout = SessionTimeoutTime();

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (quotation_print)
        if (!isset($GLOBALS["quotation_print"]) || get_class($GLOBALS["quotation_print"]) == PROJECT_NAMESPACE . "quotation_print") {
            $GLOBALS["quotation_print"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Initialize URLs
        $this->ExportPrintUrl = $pageUrl . "export=print";
        $this->ExportExcelUrl = $pageUrl . "export=excel";
        $this->ExportWordUrl = $pageUrl . "export=word";
        $this->ExportPdfUrl = $pageUrl . "export=pdf";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'quotation_print');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // Export options
        $this->ExportOptions = new ListOptions("div");
        $this->ExportOptions->TagClassName = "ew-export-option";

        // Filter options
        $this->FilterOptions = new ListOptions("div");
        $this->FilterOptions->TagClassName = "ew-filter-option fsummary";
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $ExportFileName, $TempImages, $DashboardReport;

        // Page is terminated
        $this->terminated = true;
        if (Post("customexport") === null) {
             // Page Unload event
            if (method_exists($this, "pageUnload")) {
                $this->pageUnload();
            }

            // Global Page Unloaded event (in userfn*.php)
            Page_Unloaded();
        }

        // Export
        if ($this->isExport() && !$this->isExport("print")) {
            $class = PROJECT_NAMESPACE . Config("REPORT_EXPORT_CLASSES." . $this->Export);
            if (class_exists($class)) {
                if (Post("data") !== null) {
                    $content = Post("data");
                } else {
                    $content = $this->getContents();
                }
                $doc = new $class();
                $doc($this, $content);
            }
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection if not in dashboard
        if (!$DashboardReport) {
            CloseConnections();
        }

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }
            SaveDebugMessage();
            Redirect(GetUrl($url));
        }
        return; // Return to controller
    }

    // Lookup data
    public function lookup()
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;
        if (in_array($lookup->LinkTable, [$this->ReportSourceTable, $this->TableVar])) {
            $lookup->RenderViewFunc = "renderLookup"; // Set up view renderer
        }
        $lookup->RenderEditFunc = ""; // Set up edit renderer

        // Get lookup parameters
        $lookupType = Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal")) {
            $searchValue = Post("sv", "");
            $pageSize = Post("recperpage", 10);
            $offset = Post("start", 0);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = Param("q", "");
            $pageSize = Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
            $start = Param("start", -1);
            $start = is_numeric($start) ? (int)$start : -1;
            $page = Param("page", -1);
            $page = is_numeric($page) ? (int)$page : -1;
            $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        }
        $userSelect = Decrypt(Post("s", ""));
        $userFilter = Decrypt(Post("f", ""));
        $userOrderBy = Decrypt(Post("o", ""));
        $keys = Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        $lookup->toJson($this); // Use settings from current page
    }

    // Options
    public $HideOptions = false;
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $FilterOptions; // Filter options

    // Records
    public $GroupRecords = [];
    public $DetailRecords = [];
    public $DetailRecordCount = 0;

    // Paging variables
    public $RecordIndex = 0; // Record index
    public $RecordCount = 0; // Record count (start from 1 for each group)
    public $StartGroup = 0; // Start group
    public $StopGroup = 0; // Stop group
    public $TotalGroups = 0; // Total groups
    public $GroupCount = 0; // Group count
    public $GroupCounter = []; // Group counter
    public $DisplayGroups = 3; // Groups per page
    public $GroupRange = 10;
    public $PageSizes = "1,2,3,5,-1"; // Page sizes (comma separated)
    public $PageFirstGroupFilter = "";
    public $UserIDFilter = "";
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = "";
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchRowCount = 0; // For extended search
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $DrillDownList = "";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $SearchCommand = false;
    public $ShowHeader;
    public $GroupColumnCount = 0;
    public $SubGroupColumnCount = 0;
    public $DetailColumnCount = 0;
    public $TotalCount;
    public $PageTotalCount;
    public $TopContentClass = "col-sm-12 ew-top";
    public $LeftContentClass = "ew-left";
    public $CenterContentClass = "col-sm-12 ew-center";
    public $RightContentClass = "ew-right";
    public $BottomContentClass = "col-sm-12 ew-bottom";

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $ExportFileName, $Language, $Security, $UserProfile,
            $Security, $DrillDownInPanel, $Breadcrumb,
            $DashboardReport, $CustomExportType, $ReportExportType;

        // Get export parameters
        $custom = "";
        if (Param("export") !== null) {
            $this->Export = Param("export");
            $custom = Param("custom", "");
        }
        $ExportFileName = $this->TableVar; // Get export file, used in header

        // Get custom export parameters
        if ($this->isExport() && $custom != "") {
            $this->CustomExport = $this->Export;
            $this->Export = "print";
        }
        $CustomExportType = $this->CustomExport;
        $ExportType = $this->Export; // Get export parameter, used in header
        $ReportExportType = $ExportType; // Report export type, used in header

        // Custom export (post back from ew.applyTemplate), export and terminate page
        if (Post("customexport") !== null) {
            $this->CustomExport = Post("customexport");
            $this->Export = $this->CustomExport;
            $this->terminate();
            return;
        }

        // Update Export URLs
        if ($this->ExportExcelCustom) {
            $this->ExportExcelUrl .= "&amp;custom=1";
        }
        if ($this->ExportWordCustom) {
            $this->ExportWordUrl .= "&amp;custom=1";
        }
        if ($this->ExportPdfCustom) {
            $this->ExportPdfUrl .= "&amp;custom=1";
        }
        $this->CurrentAction = Param("action"); // Set up current action

        // Setup export options
        $this->setupExportOptions();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Setup other options
        $this->setupOtherOptions();

        // Set up table class
        if ($this->isExport("word") || $this->isExport("excel") || $this->isExport("pdf")) {
            $this->ReportTableClass = "ew-table";
        } else {
            $this->ReportTableClass = "table ew-table";
        }

        // Hide main table for custom layout
        if ($this->isExport() || $this->UseCustomTemplate) {
            $this->ReportTableStyle = ' style="display: none;"';
        }

        // Set field visibility for detail fields
        $this->quotation_date->setVisibility();
        $this->customer_name->setVisibility();
        $this->phone_number->setVisibility();
        $this->contact->setVisibility();
        $this->city->setVisibility();
        $this->employee_name->setVisibility();
        $this->rate->setVisibility();
        $this->qty->setVisibility();
        $this->total->setVisibility();
        $this->customer_address->setVisibility();

        // Set up groups per page dynamically
        $this->setupDisplayGroups();

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Check if search command
        $this->SearchCommand = (Get("cmd", "") == "search");

        // Load custom filters
        $this->pageFilterLoad();

        // Extended filter
        $extendedFilter = "";

        // Restore filter list
        $this->restoreFilterList();

        // Build extended filter
        $extendedFilter = $this->getExtendedFilter();
        AddFilter($this->SearchWhere, $extendedFilter);

        // Call Page Selecting event
        $this->pageSelecting($this->SearchWhere);

        // Requires search criteria
        if (($this->SearchWhere == "") && !$this->DrillDown) {
            $this->SearchWhere = "0=101";
        }

        // Set up search panel class
        if ($this->SearchWhere != "") {
            AppendClass($this->SearchPanelClass, "show");
        }

        // Get sort
        $this->Sort = $this->getSort();

        // Search/sort options
        $this->setupSearchSortOptions();

        // Update filter
        AddFilter($this->Filter, $this->SearchWhere);

        // Get total group count
        $sql = $this->buildReportSql($this->getSqlSelectGroup(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
        $this->TotalGroups = $this->getRecordCount($sql);
        if ($this->DisplayGroups <= 0 || $this->DrillDown || $DashboardReport) { // Display all groups
            $this->DisplayGroups = $this->TotalGroups;
        }
        $this->StartGroup = 1;

        // Show header
        $this->ShowHeader = ($this->TotalGroups > 0);

        // Set up start position if not export all
        if ($this->ExportAll && $this->isExport()) {
            $this->DisplayGroups = $this->TotalGroups;
        } else {
            $this->setupStartGroup();
        }

        // Set no record found message
        if ($this->TotalGroups == 0) {
            if ($Security->canList()) {
                if ($this->SearchWhere == "0=101") {
                    $this->setWarningMessage($Language->phrase("EnterSearchCriteria"));
                } else {
                    $this->setWarningMessage($Language->phrase("NoRecord"));
                }
            } else {
                $this->setWarningMessage(DeniedMessage());
            }
        }

        // Hide export options if export/dashboard report/hide options
        if ($this->isExport() || $DashboardReport || $this->HideOptions) {
            $this->ExportOptions->hideAllOptions();
        }

        // Hide search/filter options if export/drilldown/dashboard report/hide options
        if ($this->isExport() || $this->DrillDown || $DashboardReport || $this->HideOptions) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }

        // Get group records
        if ($this->TotalGroups > 0) {
            $grpSort = UpdateSortFields($this->getSqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
            $sql = $this->buildReportSql($this->getSqlSelectGroup(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderByGroup(), $this->Filter, $grpSort);
            $grpRs = $sql->setFirstResult($this->StartGroup - 1)->setMaxResults($this->DisplayGroups)->execute();
            $this->GroupRecords = $grpRs->fetchAll(); // Get records of first grouping field
            $this->loadGroupRowValues();
            $this->GroupCount = 1;
        }

        // Init detail records
        $this->DetailRecords = [];
        $this->setupFieldCount();

        // Set the last group to display if not export all
        if ($this->ExportAll && $this->isExport()) {
            $this->StopGroup = $this->TotalGroups;
        } else {
            $this->StopGroup = $this->StartGroup + $this->DisplayGroups - 1;
        }

        // Stop group <= total number of groups
        if (intval($this->StopGroup) > intval($this->TotalGroups)) {
            $this->StopGroup = $this->TotalGroups;
        }
        $this->RecordCount = 0;
        $this->RecordIndex = 0;

        // Set up pager
        $this->Pager = new PrevNextPager($this->StartGroup, $this->DisplayGroups, $this->TotalGroups, $this->PageSizes, $this->GroupRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Pass table and field properties to client side
            $this->toClientVar(["tableCaption"], ["caption", "Required", "IsInvalid", "Raw"]);

            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Rendering event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }
        }
    }

    // Load group row values
    public function loadGroupRowValues()
    {
        $cnt = count($this->GroupRecords); // Get record count
        if ($this->GroupCount < $cnt) {
            $this->quotation_id->setGroupValue($this->GroupRecords[$this->GroupCount][0]);
        } else {
            $this->quotation_id->setGroupValue("");
        }
    }

    // Load row values
    public function loadRowValues($record)
    {
        $data = [];
        $data["quotation_id"] = $record['quotation_id'];
        $data["quotation_no"] = $record['quotation_no'];
        $data["quotation_date"] = $record['quotation_date'];
        $data["customer_name"] = $record['customer_name'];
        $data["phone_number"] = $record['phone_number'];
        $data["contact"] = $record['contact'];
        $data["city"] = $record['city'];
        $data["employee_name"] = $record['employee_name'];
        $data["rate"] = $record['rate'];
        $data["qty"] = $record['qty'];
        $data["total"] = $record['total'];
        $this->Rows[] = $data;
        $this->quotation_id->setDbValue(GroupValue($this->quotation_id, $record['quotation_id']));
        $this->quotation_no->setDbValue($record['quotation_no']);
        $this->quotation_date->setDbValue($record['quotation_date']);
        $this->customer_name->setDbValue($record['customer_name']);
        $this->phone_number->setDbValue($record['phone_number']);
        $this->contact->setDbValue($record['contact']);
        $this->city->setDbValue($record['city']);
        $this->employee_name->setDbValue($record['employee_name']);
        $this->rate->setDbValue($record['rate']);
        $this->qty->setDbValue($record['qty']);
        $this->total->setDbValue($record['total']);
        $this->customer_address->setDbValue($record['customer_address']);
    }

    // Render row
    public function renderRow()
    {
        global $Security, $Language, $Language;
        $conn = $this->getConnection();
        if ($this->RowType == ROWTYPE_TOTAL && $this->RowTotalSubType == ROWTOTAL_FOOTER && $this->RowTotalType == ROWTOTAL_PAGE) {
            // Build detail SQL
            $firstGrpFld = &$this->quotation_id;
            $firstGrpFld->getDistinctValues($this->GroupRecords);
            $where = DetailFilterSql($firstGrpFld, $this->getSqlFirstGroupField(), $firstGrpFld->DistinctValues, $this->Dbid);
            if ($this->Filter != "") {
                $where = "($this->Filter) AND ($where)";
            }
            $sql = $this->buildReportSql($this->getSqlSelect(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(), $where, $this->Sort);
            $rs = $sql->execute();
            $records = $rs ? $rs->fetchAll() : [];
            $this->employee_name->getCnt($records);
            $this->rate->getSum($records);
            $this->total->getSum($records);
            $this->PageTotalCount = count($records);
        } elseif ($this->RowType == ROWTYPE_TOTAL && $this->RowTotalSubType == ROWTOTAL_FOOTER && $this->RowTotalType == ROWTOTAL_GRAND) { // Get Grand total
            $hasCount = false;
            $hasSummary = false;

            // Get total count from SQL directly
            $sql = $this->buildReportSql($this->getSqlSelectCount(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
            $rstot = $conn->executeQuery($sql);
            if ($rstot && $cnt = $rstot->fetchColumn()) {
                $rstot->closeCursor();
                $hasCount = true;
            } else {
                $cnt = 0;
            }
            $this->TotalCount = $cnt;

            // Get total from SQL directly
            $sql = $this->buildReportSql($this->getSqlSelectAggregate(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
            $sql = $this->getSqlAggregatePrefix() . $sql . $this->getSqlAggregateSuffix();
            $rsagg = $conn->fetchAssoc($sql);
            if ($rsagg) {
                $this->quotation_date->Count = $this->TotalCount;
                $this->customer_name->Count = $this->TotalCount;
                $this->phone_number->Count = $this->TotalCount;
                $this->contact->Count = $this->TotalCount;
                $this->city->Count = $this->TotalCount;
                $this->employee_name->Count = $this->TotalCount;
                $this->employee_name->CntValue = $rsagg["cnt_employee_name"];
                $this->rate->Count = $this->TotalCount;
                $this->rate->SumValue = $rsagg["sum_rate"];
                $this->qty->Count = $this->TotalCount;
                $this->total->Count = $this->TotalCount;
                $this->total->SumValue = $rsagg["sum_total"];
                $this->customer_address->Count = $this->TotalCount;
                $hasSummary = true;
            }

            // Accumulate grand summary from detail records
            if (!$hasCount || !$hasSummary) {
                $sql = $this->buildReportSql($this->getSqlSelect(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
                $rs = $sql->execute();
                $this->DetailRecords = $rs ? $rs->fetchAll() : [];
                $this->employee_name->getCnt($this->DetailRecords);
                $this->rate->getSum($this->DetailRecords);
                $this->total->getSum($this->DetailRecords);
            }
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // quotation_id

        // quotation_no

        // quotation_date

        // customer_name

        // phone_number

        // contact

        // city

        // employee_name

        // rate

        // qty

        // total

        // customer_address
        if ($this->RowType == ROWTYPE_SEARCH) {
            // quotation_id
            $this->quotation_id->EditAttrs["class"] = "form-control";
            $this->quotation_id->EditCustomAttributes = "";
            $curVal = trim(strval($this->quotation_id->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->quotation_id->AdvancedSearch->ViewValue = $this->quotation_id->lookupCacheOption($curVal);
            } else {
                $this->quotation_id->AdvancedSearch->ViewValue = $this->quotation_id->Lookup !== null && is_array($this->quotation_id->Lookup->Options) ? $curVal : null;
            }
            if ($this->quotation_id->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->quotation_id->EditValue = array_values($this->quotation_id->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`quotation_id`" . SearchString("=", $this->quotation_id->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->quotation_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->quotation_id->EditValue = $arwrk;
            }
            $this->quotation_id->PlaceHolder = RemoveHtml($this->quotation_id->caption());
        } elseif ($this->RowType == ROWTYPE_TOTAL && !($this->RowTotalType == ROWTOTAL_GROUP && $this->RowTotalSubType == ROWTOTAL_HEADER)) { // Summary row
            $this->RowAttrs->prependClass(($this->RowTotalType == ROWTOTAL_PAGE || $this->RowTotalType == ROWTOTAL_GRAND) ? "ew-rpt-grp-aggregate" : ""); // Set up row class
            if ($this->RowTotalType == ROWTOTAL_GROUP) {
                $this->RowAttrs["data-group"] = $this->quotation_id->groupValue(); // Set up group attribute
            }
            if ($this->RowTotalType == ROWTOTAL_GROUP && $this->RowGroupLevel >= 2) {
                $this->RowAttrs["data-group-2"] = $this->quotation_no->groupValue(); // Set up group attribute 2
            }

            // quotation_id
            $this->quotation_id->GroupViewValue = $this->quotation_id->groupValue();
            $curVal = strval($this->quotation_id->groupValue());
            if ($curVal != "") {
                $this->quotation_id->GroupViewValue = $this->quotation_id->lookupCacheOption($curVal);
                if ($this->quotation_id->GroupViewValue === null) { // Lookup from database
                    $filterWrk = "`quotation_id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->quotation_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->quotation_id->Lookup->renderViewRow($rswrk[0]);
                        $this->quotation_id->GroupViewValue = $this->quotation_id->displayValue($arwrk);
                    } else {
                        $this->quotation_id->GroupViewValue = $this->quotation_id->groupValue();
                    }
                }
            } else {
                $this->quotation_id->GroupViewValue = null;
            }
            $this->quotation_id->CellCssClass = ($this->RowGroupLevel == 1 ? "ew-rpt-grp-summary-1" : "ew-rpt-grp-field-1");
            $this->quotation_id->ViewCustomAttributes = "";
            $this->quotation_id->GroupViewValue = DisplayGroupValue($this->quotation_id, $this->quotation_id->GroupViewValue);

            // quotation_no
            $this->quotation_no->GroupViewValue = $this->quotation_no->groupValue();
            $this->quotation_no->CellCssClass = ($this->RowGroupLevel == 2 ? "ew-rpt-grp-summary-2" : "ew-rpt-grp-field-2");
            $this->quotation_no->ViewCustomAttributes = "";
            $this->quotation_no->GroupViewValue = DisplayGroupValue($this->quotation_no, $this->quotation_no->GroupViewValue);

            // employee_name
            $this->employee_name->CntViewValue = $this->employee_name->CntValue;
            $this->employee_name->ViewCustomAttributes = "";
            $this->employee_name->CellAttrs["class"] = ($this->RowTotalType == ROWTOTAL_PAGE || $this->RowTotalType == ROWTOTAL_GRAND) ? "ew-rpt-grp-aggregate" : "ew-rpt-grp-summary-" . $this->RowGroupLevel;

            // rate
            $this->rate->SumViewValue = $this->rate->SumValue;
            $this->rate->SumViewValue = FormatNumber($this->rate->SumViewValue, 0, -2, -2, -2);
            $this->rate->CellCssStyle .= "text-align: right;";
            $this->rate->ViewCustomAttributes = "";
            $this->rate->CellAttrs["class"] = ($this->RowTotalType == ROWTOTAL_PAGE || $this->RowTotalType == ROWTOTAL_GRAND) ? "ew-rpt-grp-aggregate" : "ew-rpt-grp-summary-" . $this->RowGroupLevel;

            // total
            $this->total->SumViewValue = $this->total->SumValue;
            $this->total->SumViewValue = FormatNumber($this->total->SumViewValue, 0, -2, -2, -2);
            $this->total->ViewCustomAttributes = "";
            $this->total->CellAttrs["class"] = ($this->RowTotalType == ROWTOTAL_PAGE || $this->RowTotalType == ROWTOTAL_GRAND) ? "ew-rpt-grp-aggregate" : "ew-rpt-grp-summary-" . $this->RowGroupLevel;

            // quotation_id
            $this->quotation_id->HrefValue = "";

            // quotation_no
            $this->quotation_no->HrefValue = "";

            // quotation_date
            $this->quotation_date->HrefValue = "";

            // customer_name
            $this->customer_name->HrefValue = "";

            // phone_number
            $this->phone_number->HrefValue = "";

            // contact
            $this->contact->HrefValue = "";

            // city
            $this->city->HrefValue = "";

            // employee_name
            $this->employee_name->HrefValue = "";

            // rate
            $this->rate->HrefValue = "";

            // qty
            $this->qty->HrefValue = "";

            // total
            $this->total->HrefValue = "";

            // customer_address
            $this->customer_address->HrefValue = "";
        } else {
            if ($this->RowTotalType == ROWTOTAL_GROUP && $this->RowTotalSubType == ROWTOTAL_HEADER) {
                $this->RowAttrs["data-group"] = $this->quotation_id->groupValue(); // Set up group attribute
                if ($this->RowGroupLevel >= 2) {
                    $this->RowAttrs["data-group-2"] = $this->quotation_no->groupValue(); // Set up group attribute 2
                }
            } else {
                $this->RowAttrs["data-group"] = $this->quotation_id->groupValue(); // Set up group attribute
                $this->RowAttrs["data-group-2"] = $this->quotation_no->groupValue(); // Set up group attribute 2
            }

            // quotation_id
            $this->quotation_id->GroupViewValue = $this->quotation_id->groupValue();
            $curVal = strval($this->quotation_id->groupValue());
            if ($curVal != "") {
                $this->quotation_id->GroupViewValue = $this->quotation_id->lookupCacheOption($curVal);
                if ($this->quotation_id->GroupViewValue === null) { // Lookup from database
                    $filterWrk = "`quotation_id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->quotation_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->quotation_id->Lookup->renderViewRow($rswrk[0]);
                        $this->quotation_id->GroupViewValue = $this->quotation_id->displayValue($arwrk);
                    } else {
                        $this->quotation_id->GroupViewValue = $this->quotation_id->groupValue();
                    }
                }
            } else {
                $this->quotation_id->GroupViewValue = null;
            }
            $this->quotation_id->CellCssClass = "ew-rpt-grp-field-1";
            $this->quotation_id->ViewCustomAttributes = "";
            $this->quotation_id->GroupViewValue = DisplayGroupValue($this->quotation_id, $this->quotation_id->GroupViewValue);
            if (!$this->quotation_id->LevelBreak) {
                $this->quotation_id->GroupViewValue = "&nbsp;";
            } else {
                $this->quotation_id->LevelBreak = false;
            }

            // quotation_no
            $this->quotation_no->GroupViewValue = $this->quotation_no->groupValue();
            $this->quotation_no->CellCssClass = "ew-rpt-grp-field-2";
            $this->quotation_no->ViewCustomAttributes = "";
            $this->quotation_no->GroupViewValue = DisplayGroupValue($this->quotation_no, $this->quotation_no->GroupViewValue);
            if (!$this->quotation_no->LevelBreak) {
                $this->quotation_no->GroupViewValue = "&nbsp;";
            } else {
                $this->quotation_no->LevelBreak = false;
            }

            // quotation_date
            $this->quotation_date->ViewValue = $this->quotation_date->CurrentValue;
            $this->quotation_date->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->quotation_date->ViewCustomAttributes = "";

            // customer_name
            $this->customer_name->ViewValue = $this->customer_name->CurrentValue;
            $this->customer_name->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->customer_name->ViewCustomAttributes = "";

            // phone_number
            $this->phone_number->ViewValue = $this->phone_number->CurrentValue;
            $this->phone_number->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->phone_number->ViewCustomAttributes = "";

            // contact
            $this->contact->ViewValue = $this->contact->CurrentValue;
            $this->contact->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->contact->ViewCustomAttributes = "";

            // city
            $this->city->ViewValue = $this->city->CurrentValue;
            $this->city->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->city->ViewCustomAttributes = "";

            // employee_name
            $this->employee_name->ViewValue = $this->employee_name->CurrentValue;
            $this->employee_name->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->employee_name->ViewCustomAttributes = "";

            // rate
            $this->rate->ViewValue = $this->rate->CurrentValue;
            $this->rate->ViewValue = FormatNumber($this->rate->ViewValue, 0, -2, -2, -2);
            $this->rate->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->rate->CellCssStyle .= "text-align: right;";
            $this->rate->ViewCustomAttributes = "";

            // qty
            $this->qty->ViewValue = $this->qty->CurrentValue;
            $this->qty->ViewValue = FormatNumber($this->qty->ViewValue, 0, -2, -2, -2);
            $this->qty->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->qty->CellCssStyle .= "text-align: right;";
            $this->qty->ViewCustomAttributes = "";

            // total
            $this->total->ViewValue = $this->total->CurrentValue;
            $this->total->ViewValue = FormatNumber($this->total->ViewValue, 0, -2, -2, -2);
            $this->total->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->total->ViewCustomAttributes = "";

            // customer_address
            $this->customer_address->ViewValue = $this->customer_address->CurrentValue;
            $this->customer_address->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->customer_address->ViewCustomAttributes = "";

            // quotation_id
            $this->quotation_id->LinkCustomAttributes = "";
            $this->quotation_id->HrefValue = "";
            $this->quotation_id->TooltipValue = "";

            // quotation_no
            $this->quotation_no->LinkCustomAttributes = "";
            $this->quotation_no->HrefValue = "";
            $this->quotation_no->TooltipValue = "";

            // quotation_date
            $this->quotation_date->LinkCustomAttributes = "";
            $this->quotation_date->HrefValue = "";
            $this->quotation_date->TooltipValue = "";

            // customer_name
            $this->customer_name->LinkCustomAttributes = "";
            $this->customer_name->HrefValue = "";
            $this->customer_name->TooltipValue = "";

            // phone_number
            $this->phone_number->LinkCustomAttributes = "";
            $this->phone_number->HrefValue = "";
            $this->phone_number->TooltipValue = "";

            // contact
            $this->contact->LinkCustomAttributes = "";
            $this->contact->HrefValue = "";
            $this->contact->TooltipValue = "";

            // city
            $this->city->LinkCustomAttributes = "";
            $this->city->HrefValue = "";
            $this->city->TooltipValue = "";

            // employee_name
            $this->employee_name->LinkCustomAttributes = "";
            $this->employee_name->HrefValue = "";
            $this->employee_name->TooltipValue = "";

            // rate
            $this->rate->LinkCustomAttributes = "";
            $this->rate->HrefValue = "";
            $this->rate->TooltipValue = "";

            // qty
            $this->qty->LinkCustomAttributes = "";
            $this->qty->HrefValue = "";
            $this->qty->TooltipValue = "";

            // total
            $this->total->LinkCustomAttributes = "";
            $this->total->HrefValue = "";
            $this->total->TooltipValue = "";

            // customer_address
            $this->customer_address->LinkCustomAttributes = "";
            $this->customer_address->HrefValue = "";
            $this->customer_address->TooltipValue = "";
        }

        // Call Cell_Rendered event
        if ($this->RowType == ROWTYPE_TOTAL) {
            // quotation_id
            $currentValue = $this->quotation_id->GroupViewValue;
            $viewValue = &$this->quotation_id->GroupViewValue;
            $viewAttrs = &$this->quotation_id->ViewAttrs;
            $cellAttrs = &$this->quotation_id->CellAttrs;
            $hrefValue = &$this->quotation_id->HrefValue;
            $linkAttrs = &$this->quotation_id->LinkAttrs;
            $this->cellRendered($this->quotation_id, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // quotation_no
            $currentValue = $this->quotation_no->GroupViewValue;
            $viewValue = &$this->quotation_no->GroupViewValue;
            $viewAttrs = &$this->quotation_no->ViewAttrs;
            $cellAttrs = &$this->quotation_no->CellAttrs;
            $hrefValue = &$this->quotation_no->HrefValue;
            $linkAttrs = &$this->quotation_no->LinkAttrs;
            $this->cellRendered($this->quotation_no, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // employee_name
            $currentValue = $this->employee_name->CntValue;
            $viewValue = &$this->employee_name->CntViewValue;
            $viewAttrs = &$this->employee_name->ViewAttrs;
            $cellAttrs = &$this->employee_name->CellAttrs;
            $hrefValue = &$this->employee_name->HrefValue;
            $linkAttrs = &$this->employee_name->LinkAttrs;
            $this->cellRendered($this->employee_name, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // rate
            $currentValue = $this->rate->SumValue;
            $viewValue = &$this->rate->SumViewValue;
            $viewAttrs = &$this->rate->ViewAttrs;
            $cellAttrs = &$this->rate->CellAttrs;
            $hrefValue = &$this->rate->HrefValue;
            $linkAttrs = &$this->rate->LinkAttrs;
            $this->cellRendered($this->rate, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // total
            $currentValue = $this->total->SumValue;
            $viewValue = &$this->total->SumViewValue;
            $viewAttrs = &$this->total->ViewAttrs;
            $cellAttrs = &$this->total->CellAttrs;
            $hrefValue = &$this->total->HrefValue;
            $linkAttrs = &$this->total->LinkAttrs;
            $this->cellRendered($this->total, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);
        } else {
            // quotation_id
            $currentValue = $this->quotation_id->groupValue();
            $viewValue = &$this->quotation_id->GroupViewValue;
            $viewAttrs = &$this->quotation_id->ViewAttrs;
            $cellAttrs = &$this->quotation_id->CellAttrs;
            $hrefValue = &$this->quotation_id->HrefValue;
            $linkAttrs = &$this->quotation_id->LinkAttrs;
            $this->cellRendered($this->quotation_id, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // quotation_no
            $currentValue = $this->quotation_no->groupValue();
            $viewValue = &$this->quotation_no->GroupViewValue;
            $viewAttrs = &$this->quotation_no->ViewAttrs;
            $cellAttrs = &$this->quotation_no->CellAttrs;
            $hrefValue = &$this->quotation_no->HrefValue;
            $linkAttrs = &$this->quotation_no->LinkAttrs;
            $this->cellRendered($this->quotation_no, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // quotation_date
            $currentValue = $this->quotation_date->CurrentValue;
            $viewValue = &$this->quotation_date->ViewValue;
            $viewAttrs = &$this->quotation_date->ViewAttrs;
            $cellAttrs = &$this->quotation_date->CellAttrs;
            $hrefValue = &$this->quotation_date->HrefValue;
            $linkAttrs = &$this->quotation_date->LinkAttrs;
            $this->cellRendered($this->quotation_date, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // customer_name
            $currentValue = $this->customer_name->CurrentValue;
            $viewValue = &$this->customer_name->ViewValue;
            $viewAttrs = &$this->customer_name->ViewAttrs;
            $cellAttrs = &$this->customer_name->CellAttrs;
            $hrefValue = &$this->customer_name->HrefValue;
            $linkAttrs = &$this->customer_name->LinkAttrs;
            $this->cellRendered($this->customer_name, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // phone_number
            $currentValue = $this->phone_number->CurrentValue;
            $viewValue = &$this->phone_number->ViewValue;
            $viewAttrs = &$this->phone_number->ViewAttrs;
            $cellAttrs = &$this->phone_number->CellAttrs;
            $hrefValue = &$this->phone_number->HrefValue;
            $linkAttrs = &$this->phone_number->LinkAttrs;
            $this->cellRendered($this->phone_number, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // contact
            $currentValue = $this->contact->CurrentValue;
            $viewValue = &$this->contact->ViewValue;
            $viewAttrs = &$this->contact->ViewAttrs;
            $cellAttrs = &$this->contact->CellAttrs;
            $hrefValue = &$this->contact->HrefValue;
            $linkAttrs = &$this->contact->LinkAttrs;
            $this->cellRendered($this->contact, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // city
            $currentValue = $this->city->CurrentValue;
            $viewValue = &$this->city->ViewValue;
            $viewAttrs = &$this->city->ViewAttrs;
            $cellAttrs = &$this->city->CellAttrs;
            $hrefValue = &$this->city->HrefValue;
            $linkAttrs = &$this->city->LinkAttrs;
            $this->cellRendered($this->city, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // employee_name
            $currentValue = $this->employee_name->CurrentValue;
            $viewValue = &$this->employee_name->ViewValue;
            $viewAttrs = &$this->employee_name->ViewAttrs;
            $cellAttrs = &$this->employee_name->CellAttrs;
            $hrefValue = &$this->employee_name->HrefValue;
            $linkAttrs = &$this->employee_name->LinkAttrs;
            $this->cellRendered($this->employee_name, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // rate
            $currentValue = $this->rate->CurrentValue;
            $viewValue = &$this->rate->ViewValue;
            $viewAttrs = &$this->rate->ViewAttrs;
            $cellAttrs = &$this->rate->CellAttrs;
            $hrefValue = &$this->rate->HrefValue;
            $linkAttrs = &$this->rate->LinkAttrs;
            $this->cellRendered($this->rate, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // qty
            $currentValue = $this->qty->CurrentValue;
            $viewValue = &$this->qty->ViewValue;
            $viewAttrs = &$this->qty->ViewAttrs;
            $cellAttrs = &$this->qty->CellAttrs;
            $hrefValue = &$this->qty->HrefValue;
            $linkAttrs = &$this->qty->LinkAttrs;
            $this->cellRendered($this->qty, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // total
            $currentValue = $this->total->CurrentValue;
            $viewValue = &$this->total->ViewValue;
            $viewAttrs = &$this->total->ViewAttrs;
            $cellAttrs = &$this->total->CellAttrs;
            $hrefValue = &$this->total->HrefValue;
            $linkAttrs = &$this->total->LinkAttrs;
            $this->cellRendered($this->total, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // customer_address
            $currentValue = $this->customer_address->CurrentValue;
            $viewValue = &$this->customer_address->ViewValue;
            $viewAttrs = &$this->customer_address->ViewAttrs;
            $cellAttrs = &$this->customer_address->CellAttrs;
            $hrefValue = &$this->customer_address->HrefValue;
            $linkAttrs = &$this->customer_address->LinkAttrs;
            $this->cellRendered($this->customer_address, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);
        }

        // Call Row_Rendered event
        $this->rowRendered();
        $this->setupFieldCount();
    }
    private $groupCounts = [];

    // Get group count
    public function getGroupCount(...$args)
    {
        $key = "";
        foreach ($args as $arg) {
            if ($key != "") {
                $key .= "_";
            }
            $key .= strval($arg);
        }
        if ($key == "") {
            return -1;
        } elseif ($key == "0") { // Number of first level groups
            $i = 1;
            while (isset($this->groupCounts[strval($i)])) {
                $i++;
            }
            return $i - 1;
        }
        return isset($this->groupCounts[$key]) ? $this->groupCounts[$key] : -1;
    }

    // Set group count
    public function setGroupCount($value, ...$args)
    {
        $key = "";
        foreach ($args as $arg) {
            if ($key != "") {
                $key .= "_";
            }
            $key .= strval($arg);
        }
        if ($key == "") {
            return;
        }
        $this->groupCounts[$key] = $value;
    }

    // Setup field count
    protected function setupFieldCount()
    {
        $this->GroupColumnCount = 0;
        $this->SubGroupColumnCount = 0;
        $this->DetailColumnCount = 0;
        if ($this->quotation_id->Visible) {
            $this->GroupColumnCount += 1;
        }
        if ($this->quotation_no->Visible) {
            $this->GroupColumnCount += 1;
            $this->SubGroupColumnCount += 1;
        }
        if ($this->quotation_date->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->customer_name->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->phone_number->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->contact->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->city->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->employee_name->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->rate->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->qty->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->total->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->customer_address->Visible) {
            $this->DetailColumnCount += 1;
        }
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        $pageUrl = $this->pageUrl();
        if (SameText($type, "excel")) {
            return '<a class="ew-export-link ew-excel" title="' . HtmlEncode($Language->phrase("ExportToExcel", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToExcel", true)) . '" href="#" onclick="return ew.exportWithCharts(event, \'' . $this->ExportExcelUrl . '\', \'' . session_id() . '\');">' . $Language->phrase("ExportToExcel") . '</a>';
        } elseif (SameText($type, "word")) {
            return '<a class="ew-export-link ew-word" title="' . HtmlEncode($Language->phrase("ExportToWord", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToWord", true)) . '" href="#" onclick="return ew.exportWithCharts(event, \'' . $this->ExportWordUrl . '\', \'' . session_id() . '\');">' . $Language->phrase("ExportToWord") . '</a>';
        } elseif (SameText($type, "pdf")) {
            return '<a class="ew-export-link ew-pdf" title="' . HtmlEncode($Language->phrase("ExportToPDF", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToPDF", true)) . '" href="#" onclick="return ew.exportWithCharts(event, \'' . $this->ExportPdfUrl . '\', \'' . session_id() . '\');">' . $Language->phrase("ExportToPDF") . '</a>';
        } elseif (SameText($type, "email")) {
            $url = $pageUrl . "export=email" . ($custom ? "&amp;custom=1" : "");
            return '<a class="ew-export-link ew-email" title="' . HtmlEncode($Language->phrase("ExportToEmail", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToEmail", true)) . '" id="emf_quotation_print" href="#" onclick="return ew.emailDialogShow({ lnk: \'emf_quotation_print\', hdr: ew.language.phrase(\'ExportToEmailText\'), url: \'' . $url . '\', exportid: \'' . session_id() . '\', el: this });">' . $Language->phrase("ExportToEmail") . '</a>';
        } elseif (SameText($type, "print")) {
            return "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ew-export-link ew-print\" title=\"" . HtmlEncode($Language->phrase("PrinterFriendlyText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("PrinterFriendlyText")) . "\">" . $Language->phrase("PrinterFriendly") . "</a>";
        }
    }

    // Set up export options
    protected function setupExportOptions()
    {
        global $Language;

        // Printer friendly
        $item = &$this->ExportOptions->add("print");
        $item->Body = $this->getExportTag("print");
        $item->Visible = true;

        // Export to Excel
        $item = &$this->ExportOptions->add("excel");
        $item->Body = $this->getExportTag("excel", $this->ExportExcelCustom);
        $item->Visible = true;

        // Export to Word
        $item = &$this->ExportOptions->add("word");
        $item->Body = $this->getExportTag("word", $this->ExportWordCustom);
        $item->Visible = true;

        // Export to Pdf
        $item = &$this->ExportOptions->add("pdf");
        $item->Body = $this->getExportTag("pdf", $this->ExportPdfCustom);
        $item->Visible = false;

        // Export to Email
        $item = &$this->ExportOptions->add("email");
        $item->Body = $this->getExportTag("email", $this->ExportEmailCustom);
        $item->Visible = true;

        // Drop down button for export
        $this->ExportOptions->UseButtonGroup = true;
        $this->ExportOptions->UseDropDownButton = true;
        if ($this->ExportOptions->UseButtonGroup && IsMobile()) {
            $this->ExportOptions->UseDropDownButton = true;
        }
        $this->ExportOptions->DropDownButtonPhrase = $Language->phrase("ButtonExport");

        // Add group option item
        $item = &$this->ExportOptions->add($this->ExportOptions->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;

        // Hide options for export
        if ($this->isExport()) {
            $this->ExportOptions->hideAllOptions();
        }
    }

    // Set up search/sort options
    protected function setupSearchSortOptions()
    {
        global $Language, $Security;
        $pageUrl = $this->pageUrl();
        $this->SearchOptions = new ListOptions("div");
        $this->SearchOptions->TagClassName = "ew-search-option";

        // Search button
        $item = &$this->SearchOptions->add("searchtoggle");
        $searchToggleClass = ($this->SearchWhere != "") ? " active" : " active";
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" href=\"#\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fsummary\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        $item->Body = "<a class=\"btn btn-default ew-show-all\" title=\"" . $Language->phrase("ResetSearch") . "\" data-caption=\"" . $Language->phrase("ResetSearch") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ResetSearchBtn") . "</a>";
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Button group for search
        $this->SearchOptions->UseDropDownButton = false;
        $this->SearchOptions->UseButtonGroup = true;
        $this->SearchOptions->DropDownButtonPhrase = $Language->phrase("ButtonSearch");

        // Add group option item
        $item = &$this->SearchOptions->add($this->SearchOptions->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;

        // Hide search options
        if ($this->isExport() || $this->CurrentAction) {
            $this->SearchOptions->hideAllOptions();
        }
        if (!$Security->canSearch()) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("welcome");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
        $Breadcrumb->add("summary", $this->TableVar, $url, "", $this->TableVar, true);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_quotation_id":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if ($fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll(\PDO::FETCH_BOTH);
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row);
                    $ar[strval($row[0])] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fsummary\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fsummary\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->add($this->FilterOptions->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;
    }

    // Set up starting group
    protected function setupStartGroup()
    {
        // Exit if no groups
        if ($this->DisplayGroups == 0) {
            return;
        }
        $startGrp = Param(Config("TABLE_START_GROUP"), "");
        $pageNo = Param("pageno", "");

        // Check for a 'start' parameter
        if ($startGrp != "") {
            $this->StartGroup = $startGrp;
            $this->setStartGroup($this->StartGroup);
        } elseif ($pageNo != "") {
            if (is_numeric($pageNo)) {
                $this->StartGroup = ($pageNo - 1) * $this->DisplayGroups + 1;
                if ($this->StartGroup <= 0) {
                    $this->StartGroup = 1;
                } elseif ($this->StartGroup >= intval(($this->TotalGroups - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1) {
                    $this->StartGroup = intval(($this->TotalGroups - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1;
                }
                $this->setStartGroup($this->StartGroup);
            } else {
                $this->StartGroup = $this->getStartGroup();
            }
        } else {
            $this->StartGroup = $this->getStartGroup();
        }

        // Check if correct start group counter
        if (!is_numeric($this->StartGroup) || $this->StartGroup == "") { // Avoid invalid start group counter
            $this->StartGroup = 1; // Reset start group counter
            $this->setStartGroup($this->StartGroup);
        } elseif (intval($this->StartGroup) > intval($this->TotalGroups)) { // Avoid starting group > total groups
            $this->StartGroup = intval(($this->TotalGroups - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1; // Point to last page first group
            $this->setStartGroup($this->StartGroup);
        } elseif (($this->StartGroup - 1) % $this->DisplayGroups != 0) {
            $this->StartGroup = intval(($this->StartGroup - 1) / $this->DisplayGroups) * $this->DisplayGroups + 1; // Point to page boundary
            $this->setStartGroup($this->StartGroup);
        }
    }

    // Reset pager
    protected function resetPager()
    {
        // Reset start position (reset command)
        $this->StartGroup = 1;
        $this->setStartGroup($this->StartGroup);
    }

    // Set up number of groups displayed per page
    protected function setupDisplayGroups()
    {
        if (Param(Config("TABLE_GROUP_PER_PAGE")) !== null) {
            $wrk = Param(Config("TABLE_GROUP_PER_PAGE"));
            if (is_numeric($wrk)) {
                $this->DisplayGroups = intval($wrk);
            } else {
                if (strtoupper($wrk) == "ALL") { // Display all groups
                    $this->DisplayGroups = -1;
                } else {
                    $this->DisplayGroups = 3; // Non-numeric, load default
                }
            }
            $this->setGroupPerPage($this->DisplayGroups); // Save to session

            // Reset start position (reset command)
            $this->StartGroup = 1;
            $this->setStartGroup($this->StartGroup);
        } else {
            if ($this->getGroupPerPage() != "") {
                $this->DisplayGroups = $this->getGroupPerPage(); // Restore from session
            } else {
                $this->DisplayGroups = 3; // Load default
            }
        }
    }

    // Get sort parameters based on sort links clicked
    protected function getSort()
    {
        if ($this->DrillDown) {
            return "`employee_name` ASC";
        }
        $resetSort = Param("cmd") === "resetsort";
        $orderBy = Param("order", "");
        $orderType = Param("ordertype", "");

        // Check for a resetsort command
        if ($resetSort) {
            $this->setOrderBy("");
            $this->setStartGroup(1);
            $this->quotation_id->setSort("");
            $this->quotation_no->setSort("");
            $this->quotation_date->setSort("");
            $this->customer_name->setSort("");
            $this->phone_number->setSort("");
            $this->contact->setSort("");
            $this->city->setSort("");
            $this->employee_name->setSort("");
            $this->rate->setSort("");
            $this->qty->setSort("");
            $this->total->setSort("");
            $this->customer_address->setSort("");

        // Check for an Order parameter
        } elseif ($orderBy != "") {
            $this->CurrentOrder = $orderBy;
            $this->CurrentOrderType = $orderType;
            $this->updateSort($this->quotation_id); // quotation_id
            $this->updateSort($this->quotation_no); // quotation_no
            $this->updateSort($this->quotation_date); // quotation_date
            $this->updateSort($this->customer_name); // customer_name
            $this->updateSort($this->phone_number); // phone_number
            $this->updateSort($this->contact); // contact
            $this->updateSort($this->city); // city
            $this->updateSort($this->employee_name); // employee_name
            $this->updateSort($this->rate); // rate
            $this->updateSort($this->qty); // qty
            $this->updateSort($this->total); // total
            $this->updateSort($this->customer_address); // customer_address
            $sortSql = $this->sortSql();
            $this->setOrderBy($sortSql);
            $this->setStartGroup(1);
        }

        // Set up default sort
        if ($this->getOrderBy() == "") {
            $useDefaultSort = true;
            if ($this->employee_name->getSort() != "") {
                $useDefaultSort = false;
            }
            if ($useDefaultSort) {
                $this->employee_name->setSort("ASC");
                $this->setOrderBy("`employee_name` ASC");
            }
        }
        return $this->getOrderBy();
    }

    // Return extended filter
    protected function getExtendedFilter()
    {
        $filter = "";
        if ($this->DrillDown) {
            return "";
        }
        $restoreSession = false;
        $restoreDefault = false;
        // Reset search command
        if (Get("cmd", "") == "reset") {
            // Set default values
                    $this->quotation_id->AdvancedSearch->unsetSession();
            $restoreDefault = true;
        } else {
            $restoreSession = !$this->SearchCommand;

            // Field quotation_id
            $this->getDropDownValue($this->quotation_id);
            if (!$this->validateForm()) {
                return $filter;
            }
        }

        // Restore session
        if ($restoreSession) {
            $restoreDefault = true;
            if ($this->quotation_id->AdvancedSearch->issetSession()) { // Field quotation_id
                $this->quotation_id->AdvancedSearch->load();
                $restoreDefault = false;
            }
        }

        // Restore default
        if ($restoreDefault) {
            $this->loadDefaultFilters();
        }

        // Call page filter validated event
        $this->pageFilterValidated();

        // Build SQL and save to session
        $this->buildDropDownFilter($this->quotation_id, $filter, $this->quotation_id->AdvancedSearch->SearchOperator, false, true); // Field quotation_id
        $this->quotation_id->AdvancedSearch->save();

        // Field quotation_id
        LoadDropDownList($this->quotation_id->EditValue, $this->quotation_id->AdvancedSearch->SearchValue);
        return $filter;
    }

    // Build dropdown filter
    protected function buildDropDownFilter(&$fld, &$filterClause, $fldOpr, $default = false, $saveFilter = false)
    {
        $fldVal = ($default) ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
        $sql = "";
        if (is_array($fldVal)) {
            foreach ($fldVal as $val) {
                $wrk = $this->getDropDownFilter($fld, $val, $fldOpr);

                // Call Page Filtering event
                if (!StartsString("@@", $val)) {
                    $this->pageFiltering($fld, $wrk, "dropdown", $fldOpr, $val);
                }
                if ($wrk != "") {
                    if ($sql != "") {
                        $sql .= " OR " . $wrk;
                    } else {
                        $sql = $wrk;
                    }
                }
            }
        } else {
            $sql = $this->getDropDownFilter($fld, $fldVal, $fldOpr);

            // Call Page Filtering event
            if (!StartsString("@@", $fldVal)) {
                $this->pageFiltering($fld, $sql, "dropdown", $fldOpr, $fldVal);
            }
        }
        if ($sql != "") {
            AddFilter($filterClause, $sql);
            if ($saveFilter) {
                $fld->CurrentFilter = $sql;
            }
        }
    }

    // Get dropdown filter
    protected function getDropDownFilter(&$fld, $fldVal, $fldOpr)
    {
        $fldName = $fld->Name;
        $fldExpression = $fld->Expression;
        $fldDataType = $fld->DataType;
        $isMultiple = $fld->HtmlTag == "CHECKBOX" || $fld->HtmlTag == "SELECT" && $fld->SelectMultiple;
        $fldVal = strval($fldVal);
        if ($fldOpr == "") {
            $fldOpr = "=";
        }
        $wrk = "";
        if (SameString($fldVal, Config("NULL_VALUE"))) {
            $wrk = $fldExpression . " IS NULL";
        } elseif (SameString($fldVal, Config("NOT_NULL_VALUE"))) {
            $wrk = $fldExpression . " IS NOT NULL";
        } elseif (SameString($fldVal, EMPTY_VALUE)) {
            $wrk = $fldExpression . " = ''";
        } elseif (SameString($fldVal, ALL_VALUE)) {
            $wrk = "1 = 1";
        } else {
            if ($fld->GroupSql != "") { // Use grouping SQL for search if exists
                $fldExpression = str_replace("%s", $fldExpression, $fld->GroupSql);
            }
            if (StartsString("@@", $fldVal)) {
                $wrk = $this->getCustomFilter($fld, $fldVal, $this->Dbid);
            } elseif ($isMultiple && IsMultiSearchOperator($fldOpr) && trim($fldVal) != "" && $fldVal != INIT_VALUE && ($fldDataType == DATATYPE_STRING || $fldDataType == DATATYPE_MEMO)) {
                $wrk = GetMultiSearchSql($fld, $fldOpr, trim($fldVal), $this->Dbid);
            } else {
                if ($fldVal != "" && $fldVal != INIT_VALUE) {
                    if ($fldDataType == DATATYPE_DATE && $fld->GroupSql == "" && $fldOpr != "") {
                        $wrk = GetDateFilterSql($fldExpression, $fldOpr, $fldVal, $fldDataType, $this->Dbid);
                    } else {
                        $wrk = GetFilterSql($fldOpr, $fldVal, $fldDataType, $this->Dbid);
                        if ($wrk != "") {
                            $wrk = $fldExpression . $wrk;
                        }
                    }
                }
            }
        }
        return $wrk;
    }

    // Get custom filter
    protected function getCustomFilter(&$fld, $fldVal, $dbid = 0)
    {
        $wrk = "";
        if (is_array($fld->AdvancedFilters)) {
            foreach ($fld->AdvancedFilters as $filter) {
                if ($filter->ID == $fldVal && $filter->Enabled) {
                    $fldExpr = $fld->Expression;
                    $fn = $filter->FunctionName;
                    $wrkid = StartsString("@@", $filter->ID) ? substr($filter->ID, 2) : $filter->ID;
                    $fn = $fn != "" && !function_exists($fn) ? PROJECT_NAMESPACE . $fn : $fn;
                    if (function_exists($fn)) {
                        $wrk = $fn($fldExpr, $dbid);
                    } else {
                        $wrk = "";
                    }
                    $this->pageFiltering($fld, $wrk, "custom", $wrkid);
                    break;
                }
            }
        }
        return $wrk;
    }

    // Build extended filter
    protected function buildExtendedFilter(&$fld, &$filterClause, $default = false, $saveFilter = false)
    {
        $wrk = GetExtendedFilter($fld, $default, $this->Dbid);
        if (!$default) {
            $this->pageFiltering($fld, $wrk, "extended", $fld->AdvancedSearch->SearchOperator, $fld->AdvancedSearch->SearchValue, $fld->AdvancedSearch->SearchCondition, $fld->AdvancedSearch->SearchOperator2, $fld->AdvancedSearch->SearchValue2);
        }
        if ($wrk != "") {
            AddFilter($filterClause, $wrk);
            if ($saveFilter) {
                $fld->CurrentFilter = $wrk;
            }
        }
    }

    // Get drop down value from querystring
    protected function getDropDownValue(&$fld)
    {
        $parm = $fld->Param;
        if (IsPost()) {
            return false; // Skip post back
        }
        $opr = Get("z_$parm");
        if ($opr !== null) {
            $fld->AdvancedSearch->SearchOperator = $opr;
        }
        $val = Get("x_$parm");
        if ($val !== null) {
            if (is_array($val)) {
                $val = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $val);
            }
            $fld->AdvancedSearch->setSearchValue($val);
            return true;
        }
        return false;
    }

    // Dropdown filter exist
    protected function dropDownFilterExist(&$fld, $fldOpr)
    {
        $wrk = "";
        $this->buildDropDownFilter($fld, $wrk, $fldOpr);
        return ($wrk != "");
    }

    // Extended filter exist
    protected function extendedFilterExist(&$fld)
    {
        $extWrk = "";
        $this->buildExtendedFilter($fld, $extWrk);
        return ($extWrk != "");
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }

        // Return validate result
        $validateForm = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Load default value for filters
    protected function loadDefaultFilters()
    {
        // Field quotation_id
        $this->quotation_id->AdvancedSearch->loadDefault();
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";

        // Field quotation_id
        $extWrk = "";
        $this->buildDropDownFilter($this->quotation_id, $extWrk, $this->quotation_id->AdvancedSearch->SearchOperator);
        $filter = "";
        if ($extWrk != "") {
            $filter .= "<span class=\"ew-filter-value\">$extWrk</span>";
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->quotation_id->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Show Filters
        if ($filterList != "") {
            $message = "<div id=\"ew-filter-list\" class=\"alert alert-info d-table\"><div id=\"ew-current-filters\">" .
                $Language->phrase("CurrentFilters") . "</div>" . $filterList . "</div>";
            $message = "<template id=\"tp_current_filters\">" . $message . "</template>";
            $this->messageShowing($message, "");
            Write($message);
        } else {
            Write("<template id=\"tp_current_filters\"></template>"); // Output dummy tag
        }
    }

    // Get list of filters
    public function getFilterList()
    {
        global $UserProfile;

        // Initialize
        $filterList = "";
        $savedFilterList = "";

        // Field quotation_id
        $wrk = "";
        $wrk = ($this->quotation_id->AdvancedSearch->SearchValue != INIT_VALUE) ? $this->quotation_id->AdvancedSearch->SearchValue : "";
        if (is_array($wrk)) {
            $wrk = implode("||", $wrk);
        }
        if ($wrk != "") {
            $wrk = "\"x_quotation_id\":\"" . JsEncode($wrk) . "\"";
        }
        if ($wrk != "") {
            if ($filterList != "") {
                $filterList .= ",";
            }
            $filterList .= $wrk;
        }

        // Return filter list in json
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd", "") != "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter", ""), true);
        return $this->setupFilterList($filter);
    }

    // Setup list of filters
    protected function setupFilterList($filter)
    {
        if (!is_array($filter)) {
            return false;
        }

        // Field quotation_id
        if (!$this->quotation_id->AdvancedSearch->getFromArray($filter)) {
            $this->quotation_id->AdvancedSearch->loadDefault(); // Clear filter
        }
        $this->quotation_id->AdvancedSearch->save();
        return true;
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content
    }

    // Load Filters event
    public function pageFilterLoad()
    {
        // Enter your code here
        // Example: Register/Unregister Custom Extended Filter
        //RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
        //RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
        //UnregisterFilter($this-><Field>, 'StartsWithA');
    }

    // Page Selecting event
    public function pageSelecting(&$filter)
    {
        // Enter your code here
    }

    // Page Filter Validated event
    public function pageFilterValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Page Filtering event
    public function pageFiltering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "")
    {
        // Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
        //if ($typ == "dropdown" && $fld->Name == "MyField") // Dropdown filter
        //    $filter = "..."; // Modify the filter
        //if ($typ == "extended" && $fld->Name == "MyField") // Extended filter
        //    $filter = "..."; // Modify the filter
        //if ($typ == "custom" && $opr == "..." && $fld->Name == "MyField") // Custom filter, $opr is the custom filter ID
        //    $filter = "..."; // Modify the filter
    }

    // Cell Rendered event
    public function cellRendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs)
    {
        //$ViewValue = "xxx";
        //$ViewAttrs["class"] = "xxx";
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }
}
