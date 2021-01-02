<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EmployeeTimesheetSearch extends EmployeeTimesheet
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'employee_timesheet';

    // Page object name
    public $PageObjName = "EmployeeTimesheetSearch";

    // Rendering View
    public $RenderingView = false;

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
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
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
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
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
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return ($this->TableVar == $CurrentForm->getValue("t"));
            }
            if (Get("t") !== null) {
                return ($this->TableVar == Get("t"));
            }
        }
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $GLOBALS["Page"] = &$this;
        $this->TokenTimeout = SessionTimeoutTime();

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (employee_timesheet)
        if (!isset($GLOBALS["employee_timesheet"]) || get_class($GLOBALS["employee_timesheet"]) == PROJECT_NAMESPACE . "employee_timesheet") {
            $GLOBALS["employee_timesheet"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'employee_timesheet');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
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

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $doc = new $class(Container("employee_timesheet"));
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "employeetimesheetview") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['timesheet_id'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->timesheet_id->Visible = false;
        }
    }

    // Lookup data
    public function lookup()
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

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
    public $FormClassName = "ew-horizontal ew-form ew-search-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->timesheet_id->Visible = false;
        $this->employee_username->setVisibility();
        $this->year->Visible = false;
        $this->month->Visible = false;
        $this->days->setVisibility();
        $this->sick->setVisibility();
        $this->leave->setVisibility();
        $this->permit->setVisibility();
        $this->absence->setVisibility();
        $this->timesheet_doc->Visible = false;
        $this->employee_notes->setVisibility();
        $this->company_notes->setVisibility();
        $this->approved->setVisibility();
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->employee_username);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        if ($this->isPageRequest()) {
            // Get action
            $this->CurrentAction = Post("action");
            if ($this->isSearch()) {
                // Build search string for advanced search, remove blank field
                $this->loadSearchValues(); // Get search values
                if ($this->validateSearch()) {
                    $srchStr = $this->buildAdvancedSearch();
                } else {
                    $srchStr = "";
                }
                if ($srchStr != "") {
                    $srchStr = $this->getUrlParm($srchStr);
                    $srchStr = "employeetimesheetlist" . "?" . $srchStr;
                    $this->terminate($srchStr); // Go to list page
                    return;
                }
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Render row for search
        $this->RowType = ROWTYPE_SEARCH;
        $this->resetAttributes();
        $this->renderRow();

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

    // Build advanced search
    protected function buildAdvancedSearch()
    {
        $srchUrl = "";
        $this->buildSearchUrl($srchUrl, $this->employee_username); // employee_username
        $this->buildSearchUrl($srchUrl, $this->days); // days
        $this->buildSearchUrl($srchUrl, $this->sick); // sick
        $this->buildSearchUrl($srchUrl, $this->leave); // leave
        $this->buildSearchUrl($srchUrl, $this->permit); // permit
        $this->buildSearchUrl($srchUrl, $this->absence); // absence
        $this->buildSearchUrl($srchUrl, $this->employee_notes); // employee_notes
        $this->buildSearchUrl($srchUrl, $this->company_notes); // company_notes
        $this->buildSearchUrl($srchUrl, $this->approved); // approved
        if ($srchUrl != "") {
            $srchUrl .= "&";
        }
        $srchUrl .= "cmd=search";
        return $srchUrl;
    }

    // Build search URL
    protected function buildSearchUrl(&$url, &$fld, $oprOnly = false)
    {
        global $CurrentForm;
        $wrk = "";
        $fldParm = $fld->Param;
        $fldVal = $CurrentForm->getValue("x_$fldParm");
        $fldOpr = $CurrentForm->getValue("z_$fldParm");
        $fldCond = $CurrentForm->getValue("v_$fldParm");
        $fldVal2 = $CurrentForm->getValue("y_$fldParm");
        $fldOpr2 = $CurrentForm->getValue("w_$fldParm");
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        $fldOpr = strtoupper(trim($fldOpr));
        $fldDataType = ($fld->IsVirtual) ? DATATYPE_STRING : $fld->DataType;
        if ($fldOpr == "BETWEEN") {
            $isValidValue = ($fldDataType != DATATYPE_NUMBER) ||
                ($fldDataType == DATATYPE_NUMBER && $this->searchValueIsNumeric($fld, $fldVal) && $this->searchValueIsNumeric($fld, $fldVal2));
            if ($fldVal != "" && $fldVal2 != "" && $isValidValue) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) .
                    "&y_" . $fldParm . "=" . urlencode($fldVal2) .
                    "&z_" . $fldParm . "=" . urlencode($fldOpr);
            }
        } else {
            $isValidValue = ($fldDataType != DATATYPE_NUMBER) ||
                ($fldDataType == DATATYPE_NUMBER && $this->searchValueIsNumeric($fld, $fldVal));
            if ($fldVal != "" && $isValidValue && IsValidOperator($fldOpr, $fldDataType)) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) .
                    "&z_" . $fldParm . "=" . urlencode($fldOpr);
            } elseif ($fldOpr == "IS NULL" || $fldOpr == "IS NOT NULL" || ($fldOpr != "" && $oprOnly && IsValidOperator($fldOpr, $fldDataType))) {
                $wrk = "z_" . $fldParm . "=" . urlencode($fldOpr);
            }
            $isValidValue = ($fldDataType != DATATYPE_NUMBER) ||
                ($fldDataType == DATATYPE_NUMBER && $this->searchValueIsNumeric($fld, $fldVal2));
            if ($fldVal2 != "" && $isValidValue && IsValidOperator($fldOpr2, $fldDataType)) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "y_" . $fldParm . "=" . urlencode($fldVal2) .
                    "&w_" . $fldParm . "=" . urlencode($fldOpr2);
            } elseif ($fldOpr2 == "IS NULL" || $fldOpr2 == "IS NOT NULL" || ($fldOpr2 != "" && $oprOnly && IsValidOperator($fldOpr2, $fldDataType))) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "w_" . $fldParm . "=" . urlencode($fldOpr2);
            }
        }
        if ($wrk != "") {
            if ($url != "") {
                $url .= "&";
            }
            $url .= $wrk;
        }
    }

    // Check if search value is numeric
    protected function searchValueIsNumeric($fld, $value)
    {
        if (IsFloatFormat($fld->Type)) {
            $value = ConvertToFloatString($value);
        }
        return is_numeric($value);
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;
        if ($this->employee_username->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->year->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->month->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->days->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->sick->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->leave->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->permit->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->absence->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->employee_notes->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->company_notes->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->approved->AdvancedSearch->post()) {
            $hasValue = true;
        }
        return $hasValue;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // timesheet_id

        // employee_username

        // year

        // month

        // days

        // sick

        // leave

        // permit

        // absence

        // timesheet_doc

        // employee_notes

        // company_notes

        // approved
        if ($this->RowType == ROWTYPE_VIEW) {
            // employee_username
            $curVal = strval($this->employee_username->CurrentValue);
            if ($curVal != "") {
                $this->employee_username->ViewValue = $this->employee_username->lookupCacheOption($curVal);
                if ($this->employee_username->ViewValue === null) { // Lookup from database
                    $filterWrk = "`employee_username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->employee_username->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->employee_username->Lookup->renderViewRow($rswrk[0]);
                        $this->employee_username->ViewValue = $this->employee_username->displayValue($arwrk);
                    } else {
                        $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
                    }
                }
            } else {
                $this->employee_username->ViewValue = null;
            }
            $this->employee_username->ViewCustomAttributes = "";

            // year
            if (strval($this->year->CurrentValue) != "") {
                $this->year->ViewValue = $this->year->optionCaption($this->year->CurrentValue);
            } else {
                $this->year->ViewValue = null;
            }
            $this->year->ViewCustomAttributes = "";

            // month
            if (strval($this->month->CurrentValue) != "") {
                $this->month->ViewValue = $this->month->optionCaption($this->month->CurrentValue);
            } else {
                $this->month->ViewValue = null;
            }
            $this->month->ViewCustomAttributes = "";

            // days
            $this->days->ViewValue = $this->days->CurrentValue;
            $this->days->ViewValue = FormatNumber($this->days->ViewValue, 0, -2, -2, -2);
            $this->days->CellCssStyle .= "text-align: right;";
            $this->days->ViewCustomAttributes = "";

            // sick
            $this->sick->ViewValue = $this->sick->CurrentValue;
            $this->sick->ViewValue = FormatNumber($this->sick->ViewValue, 0, -2, -2, -2);
            $this->sick->CellCssStyle .= "text-align: right;";
            $this->sick->ViewCustomAttributes = "";

            // leave
            $this->leave->ViewValue = $this->leave->CurrentValue;
            $this->leave->ViewValue = FormatNumber($this->leave->ViewValue, 0, -2, -2, -2);
            $this->leave->CellCssStyle .= "text-align: right;";
            $this->leave->ViewCustomAttributes = "";

            // permit
            $this->permit->ViewValue = $this->permit->CurrentValue;
            $this->permit->ViewValue = FormatNumber($this->permit->ViewValue, 0, -2, -2, -2);
            $this->permit->CellCssStyle .= "text-align: right;";
            $this->permit->ViewCustomAttributes = "";

            // absence
            $this->absence->ViewValue = $this->absence->CurrentValue;
            $this->absence->ViewValue = FormatNumber($this->absence->ViewValue, 0, -2, -2, -2);
            $this->absence->CellCssStyle .= "text-align: right;";
            $this->absence->ViewCustomAttributes = "";

            // timesheet_doc
            if (!EmptyValue($this->timesheet_doc->Upload->DbValue)) {
                $this->timesheet_doc->ViewValue = $this->timesheet_doc->Upload->DbValue;
            } else {
                $this->timesheet_doc->ViewValue = "";
            }
            $this->timesheet_doc->ViewCustomAttributes = "";

            // employee_notes
            $this->employee_notes->ViewValue = $this->employee_notes->CurrentValue;
            $this->employee_notes->ViewCustomAttributes = "";

            // company_notes
            $this->company_notes->ViewValue = $this->company_notes->CurrentValue;
            $this->company_notes->ViewCustomAttributes = "";

            // approved
            if (strval($this->approved->CurrentValue) != "") {
                $this->approved->ViewValue = $this->approved->optionCaption($this->approved->CurrentValue);
            } else {
                $this->approved->ViewValue = null;
            }
            $this->approved->ViewCustomAttributes = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";
            $this->employee_username->TooltipValue = "";

            // days
            $this->days->LinkCustomAttributes = "";
            $this->days->HrefValue = "";
            $this->days->TooltipValue = "";

            // sick
            $this->sick->LinkCustomAttributes = "";
            $this->sick->HrefValue = "";
            $this->sick->TooltipValue = "";

            // leave
            $this->leave->LinkCustomAttributes = "";
            $this->leave->HrefValue = "";
            $this->leave->TooltipValue = "";

            // permit
            $this->permit->LinkCustomAttributes = "";
            $this->permit->HrefValue = "";
            $this->permit->TooltipValue = "";

            // absence
            $this->absence->LinkCustomAttributes = "";
            $this->absence->HrefValue = "";
            $this->absence->TooltipValue = "";

            // employee_notes
            $this->employee_notes->LinkCustomAttributes = "";
            $this->employee_notes->HrefValue = "";
            $this->employee_notes->TooltipValue = "";

            // company_notes
            $this->company_notes->LinkCustomAttributes = "";
            $this->company_notes->HrefValue = "";
            $this->company_notes->TooltipValue = "";

            // approved
            $this->approved->LinkCustomAttributes = "";
            $this->approved->HrefValue = "";
            $this->approved->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            $curVal = trim(strval($this->employee_username->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->employee_username->AdvancedSearch->ViewValue = $this->employee_username->lookupCacheOption($curVal);
            } else {
                $this->employee_username->AdvancedSearch->ViewValue = $this->employee_username->Lookup !== null && is_array($this->employee_username->Lookup->Options) ? $curVal : null;
            }
            if ($this->employee_username->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->employee_username->EditValue = array_values($this->employee_username->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`employee_username`" . SearchString("=", $this->employee_username->AdvancedSearch->SearchValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->employee_username->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->employee_username->EditValue = $arwrk;
            }
            $this->employee_username->PlaceHolder = RemoveHtml($this->employee_username->caption());

            // days
            $this->days->EditAttrs["class"] = "form-control";
            $this->days->EditCustomAttributes = "";
            $this->days->EditValue = HtmlEncode($this->days->AdvancedSearch->SearchValue);
            $this->days->PlaceHolder = RemoveHtml($this->days->caption());
            $this->days->EditAttrs["class"] = "form-control";
            $this->days->EditCustomAttributes = "";
            $this->days->EditValue2 = HtmlEncode($this->days->AdvancedSearch->SearchValue2);
            $this->days->PlaceHolder = RemoveHtml($this->days->caption());

            // sick
            $this->sick->EditAttrs["class"] = "form-control";
            $this->sick->EditCustomAttributes = "";
            $this->sick->EditValue = HtmlEncode($this->sick->AdvancedSearch->SearchValue);
            $this->sick->PlaceHolder = RemoveHtml($this->sick->caption());
            $this->sick->EditAttrs["class"] = "form-control";
            $this->sick->EditCustomAttributes = "";
            $this->sick->EditValue2 = HtmlEncode($this->sick->AdvancedSearch->SearchValue2);
            $this->sick->PlaceHolder = RemoveHtml($this->sick->caption());

            // leave
            $this->leave->EditAttrs["class"] = "form-control";
            $this->leave->EditCustomAttributes = "";
            $this->leave->EditValue = HtmlEncode($this->leave->AdvancedSearch->SearchValue);
            $this->leave->PlaceHolder = RemoveHtml($this->leave->caption());
            $this->leave->EditAttrs["class"] = "form-control";
            $this->leave->EditCustomAttributes = "";
            $this->leave->EditValue2 = HtmlEncode($this->leave->AdvancedSearch->SearchValue2);
            $this->leave->PlaceHolder = RemoveHtml($this->leave->caption());

            // permit
            $this->permit->EditAttrs["class"] = "form-control";
            $this->permit->EditCustomAttributes = "";
            $this->permit->EditValue = HtmlEncode($this->permit->AdvancedSearch->SearchValue);
            $this->permit->PlaceHolder = RemoveHtml($this->permit->caption());
            $this->permit->EditAttrs["class"] = "form-control";
            $this->permit->EditCustomAttributes = "";
            $this->permit->EditValue2 = HtmlEncode($this->permit->AdvancedSearch->SearchValue2);
            $this->permit->PlaceHolder = RemoveHtml($this->permit->caption());

            // absence
            $this->absence->EditAttrs["class"] = "form-control";
            $this->absence->EditCustomAttributes = "";
            $this->absence->EditValue = HtmlEncode($this->absence->AdvancedSearch->SearchValue);
            $this->absence->PlaceHolder = RemoveHtml($this->absence->caption());
            $this->absence->EditAttrs["class"] = "form-control";
            $this->absence->EditCustomAttributes = "";
            $this->absence->EditValue2 = HtmlEncode($this->absence->AdvancedSearch->SearchValue2);
            $this->absence->PlaceHolder = RemoveHtml($this->absence->caption());

            // employee_notes
            $this->employee_notes->EditAttrs["class"] = "form-control";
            $this->employee_notes->EditCustomAttributes = "";
            $this->employee_notes->EditValue = HtmlEncode($this->employee_notes->AdvancedSearch->SearchValue);
            $this->employee_notes->PlaceHolder = RemoveHtml($this->employee_notes->caption());
            $this->employee_notes->EditAttrs["class"] = "form-control";
            $this->employee_notes->EditCustomAttributes = "";
            $this->employee_notes->EditValue2 = HtmlEncode($this->employee_notes->AdvancedSearch->SearchValue2);
            $this->employee_notes->PlaceHolder = RemoveHtml($this->employee_notes->caption());

            // company_notes
            $this->company_notes->EditAttrs["class"] = "form-control";
            $this->company_notes->EditCustomAttributes = "";
            $this->company_notes->EditValue = HtmlEncode($this->company_notes->AdvancedSearch->SearchValue);
            $this->company_notes->PlaceHolder = RemoveHtml($this->company_notes->caption());
            $this->company_notes->EditAttrs["class"] = "form-control";
            $this->company_notes->EditCustomAttributes = "";
            $this->company_notes->EditValue2 = HtmlEncode($this->company_notes->AdvancedSearch->SearchValue2);
            $this->company_notes->PlaceHolder = RemoveHtml($this->company_notes->caption());

            // approved
            $this->approved->EditAttrs["class"] = "form-control";
            $this->approved->EditCustomAttributes = "";
            $this->approved->EditValue = $this->approved->options(true);
            $this->approved->PlaceHolder = RemoveHtml($this->approved->caption());
            $this->approved->EditAttrs["class"] = "form-control";
            $this->approved->EditCustomAttributes = "";
            $this->approved->EditValue2 = $this->approved->options(true);
            $this->approved->PlaceHolder = RemoveHtml($this->approved->caption());
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if (!CheckInteger($this->days->AdvancedSearch->SearchValue)) {
            $this->days->addErrorMessage($this->days->getErrorMessage(false));
        }
        if (!CheckInteger($this->days->AdvancedSearch->SearchValue2)) {
            $this->days->addErrorMessage($this->days->getErrorMessage(false));
        }
        if (!CheckInteger($this->sick->AdvancedSearch->SearchValue)) {
            $this->sick->addErrorMessage($this->sick->getErrorMessage(false));
        }
        if (!CheckInteger($this->sick->AdvancedSearch->SearchValue2)) {
            $this->sick->addErrorMessage($this->sick->getErrorMessage(false));
        }
        if (!CheckInteger($this->leave->AdvancedSearch->SearchValue)) {
            $this->leave->addErrorMessage($this->leave->getErrorMessage(false));
        }
        if (!CheckInteger($this->leave->AdvancedSearch->SearchValue2)) {
            $this->leave->addErrorMessage($this->leave->getErrorMessage(false));
        }
        if (!CheckInteger($this->permit->AdvancedSearch->SearchValue)) {
            $this->permit->addErrorMessage($this->permit->getErrorMessage(false));
        }
        if (!CheckInteger($this->permit->AdvancedSearch->SearchValue2)) {
            $this->permit->addErrorMessage($this->permit->getErrorMessage(false));
        }
        if (!CheckInteger($this->absence->AdvancedSearch->SearchValue)) {
            $this->absence->addErrorMessage($this->absence->getErrorMessage(false));
        }
        if (!CheckInteger($this->absence->AdvancedSearch->SearchValue2)) {
            $this->absence->addErrorMessage($this->absence->getErrorMessage(false));
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->employee_username->AdvancedSearch->load();
        $this->year->AdvancedSearch->load();
        $this->month->AdvancedSearch->load();
        $this->days->AdvancedSearch->load();
        $this->sick->AdvancedSearch->load();
        $this->leave->AdvancedSearch->load();
        $this->permit->AdvancedSearch->load();
        $this->absence->AdvancedSearch->load();
        $this->employee_notes->AdvancedSearch->load();
        $this->company_notes->AdvancedSearch->load();
        $this->approved->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("welcome");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("employeetimesheetlist"), "", $this->TableVar, true);
        $pageId = "search";
        $Breadcrumb->add("search", $pageId, $url);
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
                case "x_employee_username":
                    break;
                case "x_year":
                    break;
                case "x_month":
                    break;
                case "x_approved":
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }
}
