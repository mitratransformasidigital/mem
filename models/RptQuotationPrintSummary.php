<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class RptQuotationPrintSummary extends RptQuotationPrint
{
    use MessagesTrait;

    // Page ID
    public $PageID = "summary";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'rpt_quotation_print';

    // Page object name
    public $PageObjName = "RptQuotationPrintSummary";

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

        // Table object (rpt_quotation_print)
        if (!isset($GLOBALS["rpt_quotation_print"]) || get_class($GLOBALS["rpt_quotation_print"]) == PROJECT_NAMESPACE . "rpt_quotation_print") {
            $GLOBALS["rpt_quotation_print"] = &$this;
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
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'rpt_quotation_print');
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
        $this->address->setVisibility();
        $this->city->setVisibility();
        $this->province->setVisibility();
        $this->contact->setVisibility();
        $this->employee_name->setVisibility();
        $this->rate->setVisibility();
        $this->qty->setVisibility();
        $this->Total->setVisibility();
        $this->ID->setVisibility();

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
            $this->quotation_no->setGroupValue($this->GroupRecords[$this->GroupCount][0]);
        } else {
            $this->quotation_no->setGroupValue("");
        }
    }

    // Load row values
    public function loadRowValues($record)
    {
        $data = [];
        $data["quotation_no"] = $record['quotation_no'];
        $data["quotation_date"] = $record['quotation_date'];
        $data["customer_name"] = $record['customer_name'];
        $data["city"] = $record['city'];
        $data["province"] = $record['province'];
        $data["contact"] = $record['contact'];
        $data["employee_name"] = $record['employee_name'];
        $data["rate"] = $record['rate'];
        $data["qty"] = $record['qty'];
        $data["Total"] = $record['Total'];
        $data["ID"] = $record['ID'];
        $this->Rows[] = $data;
        $this->quotation_no->setDbValue(GroupValue($this->quotation_no, $record['quotation_no']));
        $this->quotation_date->setDbValue($record['quotation_date']);
        $this->customer_name->setDbValue($record['customer_name']);
        $this->address->setDbValue($record['address']);
        $this->city->setDbValue($record['city']);
        $this->province->setDbValue($record['province']);
        $this->contact->setDbValue($record['contact']);
        $this->employee_name->setDbValue($record['employee_name']);
        $this->rate->setDbValue($record['rate']);
        $this->qty->setDbValue($record['qty']);
        $this->Total->setDbValue($record['Total']);
        $this->ID->setDbValue($record['ID']);
    }

    // Render row
    public function renderRow()
    {
        global $Security, $Language, $Language;
        $conn = $this->getConnection();
        if ($this->RowType == ROWTYPE_TOTAL && $this->RowTotalSubType == ROWTOTAL_FOOTER && $this->RowTotalType == ROWTOTAL_PAGE) {
            // Build detail SQL
            $firstGrpFld = &$this->quotation_no;
            $firstGrpFld->getDistinctValues($this->GroupRecords);
            $where = DetailFilterSql($firstGrpFld, $this->getSqlFirstGroupField(), $firstGrpFld->DistinctValues, $this->Dbid);
            if ($this->Filter != "") {
                $where = "($this->Filter) AND ($where)";
            }
            $sql = $this->buildReportSql($this->getSqlSelect(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(), $where, $this->Sort);
            $rs = $sql->execute();
            $records = $rs ? $rs->fetchAll() : [];
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
            $hasSummary = true;

            // Accumulate grand summary from detail records
            if (!$hasCount || !$hasSummary) {
                $sql = $this->buildReportSql($this->getSqlSelect(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
                $rs = $sql->execute();
                $this->DetailRecords = $rs ? $rs->fetchAll() : [];
            }
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // quotation_no
        $this->quotation_no->CellCssStyle = "white-space: nowrap;";

        // customer_name

        // quotation_date

        // address
        $this->address->CellCssStyle = "white-space: nowrap;";

        // city

        // province

        // contact

        // employee_name

        // rate

        // qty

        // Total

        // ID
        if ($this->RowType == ROWTYPE_SEARCH) {
            // quotation_no
            $this->quotation_no->EditAttrs["class"] = "form-control";
            $this->quotation_no->EditCustomAttributes = "";
            if (!$this->quotation_no->Raw) {
                $this->quotation_no->AdvancedSearch->SearchValue = HtmlDecode($this->quotation_no->AdvancedSearch->SearchValue);
            }
            $this->quotation_no->EditValue = HtmlEncode($this->quotation_no->AdvancedSearch->SearchValue);
            $curVal = strval($this->quotation_no->AdvancedSearch->SearchValue);
            if ($curVal != "") {
                $this->quotation_no->EditValue = $this->quotation_no->lookupCacheOption($curVal);
                if ($this->quotation_no->EditValue === null) { // Lookup from database
                    $filterWrk = "`quotation_no`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->quotation_no->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->quotation_no->Lookup->renderViewRow($rswrk[0]);
                        $this->quotation_no->EditValue = $this->quotation_no->displayValue($arwrk);
                    } else {
                        $this->quotation_no->EditValue = HtmlEncode($this->quotation_no->AdvancedSearch->SearchValue);
                    }
                }
            } else {
                $this->quotation_no->EditValue = null;
            }
            $this->quotation_no->PlaceHolder = RemoveHtml($this->quotation_no->caption());
            $this->quotation_no->EditAttrs["class"] = "form-control";
            $this->quotation_no->EditCustomAttributes = "";
            if (!$this->quotation_no->Raw) {
                $this->quotation_no->AdvancedSearch->SearchValue2 = HtmlDecode($this->quotation_no->AdvancedSearch->SearchValue2);
            }
            $this->quotation_no->EditValue2 = HtmlEncode($this->quotation_no->AdvancedSearch->SearchValue2);
            $curVal = strval($this->quotation_no->AdvancedSearch->SearchValue2);
            if ($curVal != "") {
                $this->quotation_no->EditValue2 = $this->quotation_no->lookupCacheOption($curVal);
                if ($this->quotation_no->EditValue2 === null) { // Lookup from database
                    $filterWrk = "`quotation_no`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->quotation_no->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->quotation_no->Lookup->renderViewRow($rswrk[0]);
                        $this->quotation_no->EditValue2 = $this->quotation_no->displayValue($arwrk);
                    } else {
                        $this->quotation_no->EditValue2 = HtmlEncode($this->quotation_no->AdvancedSearch->SearchValue2);
                    }
                }
            } else {
                $this->quotation_no->EditValue2 = null;
            }
            $this->quotation_no->PlaceHolder = RemoveHtml($this->quotation_no->caption());
        } elseif ($this->RowType == ROWTYPE_TOTAL && !($this->RowTotalType == ROWTOTAL_GROUP && $this->RowTotalSubType == ROWTOTAL_HEADER)) { // Summary row
            $this->RowAttrs->prependClass(($this->RowTotalType == ROWTOTAL_PAGE || $this->RowTotalType == ROWTOTAL_GRAND) ? "ew-rpt-grp-aggregate" : ""); // Set up row class
            if ($this->RowTotalType == ROWTOTAL_GROUP) {
                $this->RowAttrs["data-group"] = $this->quotation_no->groupValue(); // Set up group attribute
            }
            if ($this->RowTotalType == ROWTOTAL_GROUP && $this->RowGroupLevel >= 2) {
                $this->RowAttrs["data-group-2"] = $this->customer_name->groupValue(); // Set up group attribute 2
            }

            // quotation_no
            $this->quotation_no->GroupViewValue = $this->quotation_no->groupValue();
            $curVal = strval($this->quotation_no->groupValue());
            if ($curVal != "") {
                $this->quotation_no->GroupViewValue = $this->quotation_no->lookupCacheOption($curVal);
                if ($this->quotation_no->GroupViewValue === null) { // Lookup from database
                    $filterWrk = "`quotation_no`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->quotation_no->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->quotation_no->Lookup->renderViewRow($rswrk[0]);
                        $this->quotation_no->GroupViewValue = $this->quotation_no->displayValue($arwrk);
                    } else {
                        $this->quotation_no->GroupViewValue = $this->quotation_no->groupValue();
                    }
                }
            } else {
                $this->quotation_no->GroupViewValue = null;
            }
            $this->quotation_no->CellCssClass = ($this->RowGroupLevel == 1 ? "ew-rpt-grp-summary-1" : "ew-rpt-grp-field-1");
            $this->quotation_no->ViewCustomAttributes = "";
            $this->quotation_no->GroupViewValue = DisplayGroupValue($this->quotation_no, $this->quotation_no->GroupViewValue);

            // customer_name
            $this->customer_name->GroupViewValue = $this->customer_name->groupValue();
            $this->customer_name->CellCssClass = ($this->RowGroupLevel == 2 ? "ew-rpt-grp-summary-2" : "ew-rpt-grp-field-2");
            $this->customer_name->ViewCustomAttributes = "";
            $this->customer_name->GroupViewValue = DisplayGroupValue($this->customer_name, $this->customer_name->GroupViewValue);

            // quotation_no
            $this->quotation_no->HrefValue = "";

            // customer_name
            $this->customer_name->HrefValue = "";

            // quotation_date
            $this->quotation_date->HrefValue = "";

            // address
            $this->address->HrefValue = "";

            // city
            $this->city->HrefValue = "";

            // province
            $this->province->HrefValue = "";

            // contact
            $this->contact->HrefValue = "";

            // employee_name
            $this->employee_name->HrefValue = "";

            // rate
            $this->rate->HrefValue = "";

            // qty
            $this->qty->HrefValue = "";

            // Total
            $this->Total->HrefValue = "";

            // ID
            $this->ID->HrefValue = "";
        } else {
            if ($this->RowTotalType == ROWTOTAL_GROUP && $this->RowTotalSubType == ROWTOTAL_HEADER) {
                $this->RowAttrs["data-group"] = $this->quotation_no->groupValue(); // Set up group attribute
                if ($this->RowGroupLevel >= 2) {
                    $this->RowAttrs["data-group-2"] = $this->customer_name->groupValue(); // Set up group attribute 2
                }
            } else {
                $this->RowAttrs["data-group"] = $this->quotation_no->groupValue(); // Set up group attribute
                $this->RowAttrs["data-group-2"] = $this->customer_name->groupValue(); // Set up group attribute 2
            }

            // quotation_no
            $this->quotation_no->GroupViewValue = $this->quotation_no->groupValue();
            $curVal = strval($this->quotation_no->groupValue());
            if ($curVal != "") {
                $this->quotation_no->GroupViewValue = $this->quotation_no->lookupCacheOption($curVal);
                if ($this->quotation_no->GroupViewValue === null) { // Lookup from database
                    $filterWrk = "`quotation_no`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->quotation_no->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->quotation_no->Lookup->renderViewRow($rswrk[0]);
                        $this->quotation_no->GroupViewValue = $this->quotation_no->displayValue($arwrk);
                    } else {
                        $this->quotation_no->GroupViewValue = $this->quotation_no->groupValue();
                    }
                }
            } else {
                $this->quotation_no->GroupViewValue = null;
            }
            $this->quotation_no->CellCssClass = "ew-rpt-grp-field-1";
            $this->quotation_no->ViewCustomAttributes = "";
            $this->quotation_no->GroupViewValue = DisplayGroupValue($this->quotation_no, $this->quotation_no->GroupViewValue);
            if (!$this->quotation_no->LevelBreak) {
                $this->quotation_no->GroupViewValue = "&nbsp;";
            } else {
                $this->quotation_no->LevelBreak = false;
            }

            // customer_name
            $this->customer_name->GroupViewValue = $this->customer_name->groupValue();
            $this->customer_name->CellCssClass = "ew-rpt-grp-field-2";
            $this->customer_name->ViewCustomAttributes = "";
            $this->customer_name->GroupViewValue = DisplayGroupValue($this->customer_name, $this->customer_name->GroupViewValue);
            if (!$this->customer_name->LevelBreak) {
                $this->customer_name->GroupViewValue = "&nbsp;";
            } else {
                $this->customer_name->LevelBreak = false;
            }

            // quotation_date
            $this->quotation_date->ViewValue = $this->quotation_date->CurrentValue;
            $this->quotation_date->ViewValue = FormatDateTime($this->quotation_date->ViewValue, 5);
            $this->quotation_date->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->quotation_date->ViewCustomAttributes = "";

            // address
            $this->address->ViewValue = $this->address->CurrentValue;
            $this->address->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->address->ViewCustomAttributes = "";

            // city
            $this->city->ViewValue = $this->city->CurrentValue;
            $this->city->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->city->ViewCustomAttributes = "";

            // province
            $this->province->ViewValue = $this->province->CurrentValue;
            $this->province->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->province->ViewCustomAttributes = "";

            // contact
            $this->contact->ViewValue = $this->contact->CurrentValue;
            $this->contact->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->contact->ViewCustomAttributes = "";

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

            // Total
            $this->Total->ViewValue = $this->Total->CurrentValue;
            $this->Total->ViewValue = FormatNumber($this->Total->ViewValue, 0, -2, -2, -2);
            $this->Total->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->Total->ViewCustomAttributes = "";

            // ID
            $this->ID->ViewValue = $this->ID->CurrentValue;
            $this->ID->CellCssClass = ($this->RecordCount % 2 != 1 ? "ew-table-alt-row" : "ew-table-row");
            $this->ID->ViewCustomAttributes = "";

            // quotation_no
            $this->quotation_no->LinkCustomAttributes = "";
            $this->quotation_no->HrefValue = "";
            $this->quotation_no->TooltipValue = "";

            // customer_name
            $this->customer_name->LinkCustomAttributes = "";
            $this->customer_name->HrefValue = "";
            $this->customer_name->TooltipValue = "";

            // quotation_date
            $this->quotation_date->LinkCustomAttributes = "";
            $this->quotation_date->HrefValue = "";
            $this->quotation_date->TooltipValue = "";

            // address
            $this->address->LinkCustomAttributes = "";
            $this->address->HrefValue = "";
            $this->address->TooltipValue = "";

            // city
            $this->city->LinkCustomAttributes = "";
            $this->city->HrefValue = "";
            $this->city->TooltipValue = "";

            // province
            $this->province->LinkCustomAttributes = "";
            $this->province->HrefValue = "";
            $this->province->TooltipValue = "";

            // contact
            $this->contact->LinkCustomAttributes = "";
            $this->contact->HrefValue = "";
            $this->contact->TooltipValue = "";

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

            // Total
            $this->Total->LinkCustomAttributes = "";
            $this->Total->HrefValue = "";
            $this->Total->TooltipValue = "";

            // ID
            $this->ID->LinkCustomAttributes = "";
            $this->ID->HrefValue = "";
            $this->ID->TooltipValue = "";
        }

        // Call Cell_Rendered event
        if ($this->RowType == ROWTYPE_TOTAL) {
            // quotation_no
            $currentValue = $this->quotation_no->GroupViewValue;
            $viewValue = &$this->quotation_no->GroupViewValue;
            $viewAttrs = &$this->quotation_no->ViewAttrs;
            $cellAttrs = &$this->quotation_no->CellAttrs;
            $hrefValue = &$this->quotation_no->HrefValue;
            $linkAttrs = &$this->quotation_no->LinkAttrs;
            $this->cellRendered($this->quotation_no, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // customer_name
            $currentValue = $this->customer_name->GroupViewValue;
            $viewValue = &$this->customer_name->GroupViewValue;
            $viewAttrs = &$this->customer_name->ViewAttrs;
            $cellAttrs = &$this->customer_name->CellAttrs;
            $hrefValue = &$this->customer_name->HrefValue;
            $linkAttrs = &$this->customer_name->LinkAttrs;
            $this->cellRendered($this->customer_name, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);
        } else {
            // quotation_no
            $currentValue = $this->quotation_no->groupValue();
            $viewValue = &$this->quotation_no->GroupViewValue;
            $viewAttrs = &$this->quotation_no->ViewAttrs;
            $cellAttrs = &$this->quotation_no->CellAttrs;
            $hrefValue = &$this->quotation_no->HrefValue;
            $linkAttrs = &$this->quotation_no->LinkAttrs;
            $this->cellRendered($this->quotation_no, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // customer_name
            $currentValue = $this->customer_name->groupValue();
            $viewValue = &$this->customer_name->GroupViewValue;
            $viewAttrs = &$this->customer_name->ViewAttrs;
            $cellAttrs = &$this->customer_name->CellAttrs;
            $hrefValue = &$this->customer_name->HrefValue;
            $linkAttrs = &$this->customer_name->LinkAttrs;
            $this->cellRendered($this->customer_name, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // quotation_date
            $currentValue = $this->quotation_date->CurrentValue;
            $viewValue = &$this->quotation_date->ViewValue;
            $viewAttrs = &$this->quotation_date->ViewAttrs;
            $cellAttrs = &$this->quotation_date->CellAttrs;
            $hrefValue = &$this->quotation_date->HrefValue;
            $linkAttrs = &$this->quotation_date->LinkAttrs;
            $this->cellRendered($this->quotation_date, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // address
            $currentValue = $this->address->CurrentValue;
            $viewValue = &$this->address->ViewValue;
            $viewAttrs = &$this->address->ViewAttrs;
            $cellAttrs = &$this->address->CellAttrs;
            $hrefValue = &$this->address->HrefValue;
            $linkAttrs = &$this->address->LinkAttrs;
            $this->cellRendered($this->address, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // city
            $currentValue = $this->city->CurrentValue;
            $viewValue = &$this->city->ViewValue;
            $viewAttrs = &$this->city->ViewAttrs;
            $cellAttrs = &$this->city->CellAttrs;
            $hrefValue = &$this->city->HrefValue;
            $linkAttrs = &$this->city->LinkAttrs;
            $this->cellRendered($this->city, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // province
            $currentValue = $this->province->CurrentValue;
            $viewValue = &$this->province->ViewValue;
            $viewAttrs = &$this->province->ViewAttrs;
            $cellAttrs = &$this->province->CellAttrs;
            $hrefValue = &$this->province->HrefValue;
            $linkAttrs = &$this->province->LinkAttrs;
            $this->cellRendered($this->province, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // contact
            $currentValue = $this->contact->CurrentValue;
            $viewValue = &$this->contact->ViewValue;
            $viewAttrs = &$this->contact->ViewAttrs;
            $cellAttrs = &$this->contact->CellAttrs;
            $hrefValue = &$this->contact->HrefValue;
            $linkAttrs = &$this->contact->LinkAttrs;
            $this->cellRendered($this->contact, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

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

            // Total
            $currentValue = $this->Total->CurrentValue;
            $viewValue = &$this->Total->ViewValue;
            $viewAttrs = &$this->Total->ViewAttrs;
            $cellAttrs = &$this->Total->CellAttrs;
            $hrefValue = &$this->Total->HrefValue;
            $linkAttrs = &$this->Total->LinkAttrs;
            $this->cellRendered($this->Total, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);

            // ID
            $currentValue = $this->ID->CurrentValue;
            $viewValue = &$this->ID->ViewValue;
            $viewAttrs = &$this->ID->ViewAttrs;
            $cellAttrs = &$this->ID->CellAttrs;
            $hrefValue = &$this->ID->HrefValue;
            $linkAttrs = &$this->ID->LinkAttrs;
            $this->cellRendered($this->ID, $currentValue, $viewValue, $viewAttrs, $cellAttrs, $hrefValue, $linkAttrs);
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
        if ($this->quotation_no->Visible) {
            $this->GroupColumnCount += 1;
        }
        if ($this->customer_name->Visible) {
            $this->GroupColumnCount += 1;
            $this->SubGroupColumnCount += 1;
        }
        if ($this->quotation_date->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->address->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->city->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->province->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->contact->Visible) {
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
        if ($this->Total->Visible) {
            $this->DetailColumnCount += 1;
        }
        if ($this->ID->Visible) {
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
            return '<a class="ew-export-link ew-email" title="' . HtmlEncode($Language->phrase("ExportToEmail", true)) . '" data-caption="' . HtmlEncode($Language->phrase("ExportToEmail", true)) . '" id="emf_rpt_quotation_print" href="#" onclick="return ew.emailDialogShow({ lnk: \'emf_rpt_quotation_print\', hdr: ew.language.phrase(\'ExportToEmailText\'), url: \'' . $url . '\', exportid: \'' . session_id() . '\', el: this });">' . $Language->phrase("ExportToEmail") . '</a>';
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
        $item->Body = "<a class=\"btn btn-default ew-show-all\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
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
        $Breadcrumb = new Breadcrumb("index");
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
                case "x_quotation_no":
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
            return "";
        }
        $resetSort = Param("cmd") === "resetsort";
        $orderBy = Param("order", "");
        $orderType = Param("ordertype", "");

        // Check for a resetsort command
        if ($resetSort) {
            $this->setOrderBy("");
            $this->setStartGroup(1);
            $this->quotation_no->setSort("");
            $this->quotation_date->setSort("");
            $this->customer_name->setSort("");
            $this->address->setSort("");
            $this->city->setSort("");
            $this->province->setSort("");
            $this->contact->setSort("");
            $this->employee_name->setSort("");
            $this->rate->setSort("");
            $this->qty->setSort("");
            $this->Total->setSort("");
            $this->ID->setSort("");

        // Check for an Order parameter
        } elseif ($orderBy != "") {
            $this->CurrentOrder = $orderBy;
            $this->CurrentOrderType = $orderType;
            $this->updateSort($this->quotation_no); // quotation_no
            $this->updateSort($this->quotation_date); // quotation_date
            $this->updateSort($this->customer_name); // customer_name
            $this->updateSort($this->address); // address
            $this->updateSort($this->city); // city
            $this->updateSort($this->province); // province
            $this->updateSort($this->contact); // contact
            $this->updateSort($this->employee_name); // employee_name
            $this->updateSort($this->rate); // rate
            $this->updateSort($this->qty); // qty
            $this->updateSort($this->Total); // Total
            $this->updateSort($this->ID); // ID
            $sortSql = $this->sortSql();
            $this->setOrderBy($sortSql);
            $this->setStartGroup(1);
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
                    $this->quotation_no->AdvancedSearch->unsetSession();
            $restoreDefault = true;
        } else {
            $restoreSession = !$this->SearchCommand;

            // Field quotation_no
            if ($this->quotation_no->AdvancedSearch->get()) {
            if (FieldDataType($this->quotation_no->Type) == DATATYPE_DATE) // Format default date format
            $this->quotation_no->AdvancedSearch->SearchValue = FormatDateTime($this->quotation_no->AdvancedSearch->SearchValue, 0);
            }
            if (!$this->validateForm()) {
                return $filter;
            }
        }

        // Restore session
        if ($restoreSession) {
            $restoreDefault = true;
            if ($this->quotation_no->AdvancedSearch->issetSession()) { // Field quotation_no
                $this->quotation_no->AdvancedSearch->load();
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
        $this->buildExtendedFilter($this->quotation_no, $filter, false, true); // Field quotation_no
        $this->quotation_no->AdvancedSearch->save();
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
                if (!CheckInteger($this->ID->FormValue)) {
                    $this->ID->addErrorMessage($this->ID->getErrorMessage(false));
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
        // Field quotation_no
        $this->quotation_no->AdvancedSearch->loadDefault();
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";

        // Field quotation_no
        $extWrk = "";
        $this->buildExtendedFilter($this->quotation_no, $extWrk);
        $filter = "";
        if ($extWrk != "") {
            $filter .= "<span class=\"ew-filter-value\">$extWrk</span>";
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->quotation_no->caption() . "</span>" . $captionSuffix . $filter . "</div>";
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

        // Field quotation_no
        $wrk = "";
        if ($this->quotation_no->AdvancedSearch->SearchValue != "" || $this->quotation_no->AdvancedSearch->SearchValue2 != "") {
            $wrk = "\"x_quotation_no\":\"" . JsEncode($this->quotation_no->AdvancedSearch->SearchValue) . "\"," .
                "\"z_quotation_no\":\"" . JsEncode($this->quotation_no->AdvancedSearch->SearchOperator) . "\"," .
                "\"v_quotation_no\":\"" . JsEncode($this->quotation_no->AdvancedSearch->SearchCondition) . "\"," .
                "\"y_quotation_no\":\"" . JsEncode($this->quotation_no->AdvancedSearch->SearchValue2) . "\"," .
                "\"w_quotation_no\":\"" . JsEncode($this->quotation_no->AdvancedSearch->SearchOperator2) . "\"";
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

        // Field quotation_no
        if (!$this->quotation_no->AdvancedSearch->getFromArray($filter)) {
            $this->quotation_no->AdvancedSearch->loadDefault(); // Clear filter
        }
        $this->quotation_no->AdvancedSearch->save();
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
