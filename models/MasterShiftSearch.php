<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MasterShiftSearch extends MasterShift
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'master_shift';

    // Page object name
    public $PageObjName = "MasterShiftSearch";

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

        // Table object (master_shift)
        if (!isset($GLOBALS["master_shift"]) || get_class($GLOBALS["master_shift"]) == PROJECT_NAMESPACE . "master_shift") {
            $GLOBALS["master_shift"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'master_shift');
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
                $doc = new $class(Container("master_shift"));
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
                    if ($pageName == "mastershiftview") {
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
            $key .= @$ar['shift_id'];
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
            $this->shift_id->Visible = false;
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
        $this->shift_id->Visible = false;
        $this->shift_name->setVisibility();
        $this->sunday_time_in->setVisibility();
        $this->sunday_time_out->setVisibility();
        $this->monday_time_in->setVisibility();
        $this->monday_time_out->setVisibility();
        $this->tuesday_time_in->setVisibility();
        $this->tuesday_time_out->setVisibility();
        $this->wednesday_time_in->setVisibility();
        $this->wednesday_time_out->setVisibility();
        $this->thursday_time_in->setVisibility();
        $this->thursday_time_out->setVisibility();
        $this->friday_time_in->setVisibility();
        $this->friday_time_out->setVisibility();
        $this->saturday_time_in->setVisibility();
        $this->saturday_time_out->setVisibility();
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
                    $srchStr = "mastershiftlist" . "?" . $srchStr;
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
        $this->buildSearchUrl($srchUrl, $this->shift_name); // shift_name
        $this->buildSearchUrl($srchUrl, $this->sunday_time_in); // sunday_time_in
        $this->buildSearchUrl($srchUrl, $this->sunday_time_out); // sunday_time_out
        $this->buildSearchUrl($srchUrl, $this->monday_time_in); // monday_time_in
        $this->buildSearchUrl($srchUrl, $this->monday_time_out); // monday_time_out
        $this->buildSearchUrl($srchUrl, $this->tuesday_time_in); // tuesday_time_in
        $this->buildSearchUrl($srchUrl, $this->tuesday_time_out); // tuesday_time_out
        $this->buildSearchUrl($srchUrl, $this->wednesday_time_in); // wednesday_time_in
        $this->buildSearchUrl($srchUrl, $this->wednesday_time_out); // wednesday_time_out
        $this->buildSearchUrl($srchUrl, $this->thursday_time_in); // thursday_time_in
        $this->buildSearchUrl($srchUrl, $this->thursday_time_out); // thursday_time_out
        $this->buildSearchUrl($srchUrl, $this->friday_time_in); // friday_time_in
        $this->buildSearchUrl($srchUrl, $this->friday_time_out); // friday_time_out
        $this->buildSearchUrl($srchUrl, $this->saturday_time_in); // saturday_time_in
        $this->buildSearchUrl($srchUrl, $this->saturday_time_out); // saturday_time_out
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
        if ($this->shift_name->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->sunday_time_in->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->sunday_time_out->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->monday_time_in->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->monday_time_out->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->tuesday_time_in->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->tuesday_time_out->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->wednesday_time_in->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->wednesday_time_out->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->thursday_time_in->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->thursday_time_out->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->friday_time_in->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->friday_time_out->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->saturday_time_in->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->saturday_time_out->AdvancedSearch->post()) {
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

        // shift_id

        // shift_name

        // sunday_time_in

        // sunday_time_out

        // monday_time_in

        // monday_time_out

        // tuesday_time_in

        // tuesday_time_out

        // wednesday_time_in

        // wednesday_time_out

        // thursday_time_in

        // thursday_time_out

        // friday_time_in

        // friday_time_out

        // saturday_time_in

        // saturday_time_out
        if ($this->RowType == ROWTYPE_VIEW) {
            // shift_name
            $this->shift_name->ViewValue = $this->shift_name->CurrentValue;
            $this->shift_name->ViewCustomAttributes = "";

            // sunday_time_in
            $this->sunday_time_in->ViewValue = $this->sunday_time_in->CurrentValue;
            $this->sunday_time_in->ViewValue = FormatDateTime($this->sunday_time_in->ViewValue, 4);
            $this->sunday_time_in->ViewCustomAttributes = "";

            // sunday_time_out
            $this->sunday_time_out->ViewValue = $this->sunday_time_out->CurrentValue;
            $this->sunday_time_out->ViewValue = FormatDateTime($this->sunday_time_out->ViewValue, 4);
            $this->sunday_time_out->ViewCustomAttributes = "";

            // monday_time_in
            $this->monday_time_in->ViewValue = $this->monday_time_in->CurrentValue;
            $this->monday_time_in->ViewValue = FormatDateTime($this->monday_time_in->ViewValue, 4);
            $this->monday_time_in->ViewCustomAttributes = "";

            // monday_time_out
            $this->monday_time_out->ViewValue = $this->monday_time_out->CurrentValue;
            $this->monday_time_out->ViewValue = FormatDateTime($this->monday_time_out->ViewValue, 4);
            $this->monday_time_out->ViewCustomAttributes = "";

            // tuesday_time_in
            $this->tuesday_time_in->ViewValue = $this->tuesday_time_in->CurrentValue;
            $this->tuesday_time_in->ViewValue = FormatDateTime($this->tuesday_time_in->ViewValue, 4);
            $this->tuesday_time_in->ViewCustomAttributes = "";

            // tuesday_time_out
            $this->tuesday_time_out->ViewValue = $this->tuesday_time_out->CurrentValue;
            $this->tuesday_time_out->ViewValue = FormatDateTime($this->tuesday_time_out->ViewValue, 4);
            $this->tuesday_time_out->ViewCustomAttributes = "";

            // wednesday_time_in
            $this->wednesday_time_in->ViewValue = $this->wednesday_time_in->CurrentValue;
            $this->wednesday_time_in->ViewValue = FormatDateTime($this->wednesday_time_in->ViewValue, 4);
            $this->wednesday_time_in->ViewCustomAttributes = "";

            // wednesday_time_out
            $this->wednesday_time_out->ViewValue = $this->wednesday_time_out->CurrentValue;
            $this->wednesday_time_out->ViewValue = FormatDateTime($this->wednesday_time_out->ViewValue, 4);
            $this->wednesday_time_out->ViewCustomAttributes = "";

            // thursday_time_in
            $this->thursday_time_in->ViewValue = $this->thursday_time_in->CurrentValue;
            $this->thursday_time_in->ViewValue = FormatDateTime($this->thursday_time_in->ViewValue, 4);
            $this->thursday_time_in->ViewCustomAttributes = "";

            // thursday_time_out
            $this->thursday_time_out->ViewValue = $this->thursday_time_out->CurrentValue;
            $this->thursday_time_out->ViewValue = FormatDateTime($this->thursday_time_out->ViewValue, 4);
            $this->thursday_time_out->ViewCustomAttributes = "";

            // friday_time_in
            $this->friday_time_in->ViewValue = $this->friday_time_in->CurrentValue;
            $this->friday_time_in->ViewValue = FormatDateTime($this->friday_time_in->ViewValue, 4);
            $this->friday_time_in->ViewCustomAttributes = "";

            // friday_time_out
            $this->friday_time_out->ViewValue = $this->friday_time_out->CurrentValue;
            $this->friday_time_out->ViewValue = FormatDateTime($this->friday_time_out->ViewValue, 4);
            $this->friday_time_out->ViewCustomAttributes = "";

            // saturday_time_in
            $this->saturday_time_in->ViewValue = $this->saturday_time_in->CurrentValue;
            $this->saturday_time_in->ViewValue = FormatDateTime($this->saturday_time_in->ViewValue, 4);
            $this->saturday_time_in->ViewCustomAttributes = "";

            // saturday_time_out
            $this->saturday_time_out->ViewValue = $this->saturday_time_out->CurrentValue;
            $this->saturday_time_out->ViewValue = FormatDateTime($this->saturday_time_out->ViewValue, 4);
            $this->saturday_time_out->ViewCustomAttributes = "";

            // shift_name
            $this->shift_name->LinkCustomAttributes = "";
            $this->shift_name->HrefValue = "";
            $this->shift_name->TooltipValue = "";

            // sunday_time_in
            $this->sunday_time_in->LinkCustomAttributes = "";
            $this->sunday_time_in->HrefValue = "";
            $this->sunday_time_in->TooltipValue = "";

            // sunday_time_out
            $this->sunday_time_out->LinkCustomAttributes = "";
            $this->sunday_time_out->HrefValue = "";
            $this->sunday_time_out->TooltipValue = "";

            // monday_time_in
            $this->monday_time_in->LinkCustomAttributes = "";
            $this->monday_time_in->HrefValue = "";
            $this->monday_time_in->TooltipValue = "";

            // monday_time_out
            $this->monday_time_out->LinkCustomAttributes = "";
            $this->monday_time_out->HrefValue = "";
            $this->monday_time_out->TooltipValue = "";

            // tuesday_time_in
            $this->tuesday_time_in->LinkCustomAttributes = "";
            $this->tuesday_time_in->HrefValue = "";
            $this->tuesday_time_in->TooltipValue = "";

            // tuesday_time_out
            $this->tuesday_time_out->LinkCustomAttributes = "";
            $this->tuesday_time_out->HrefValue = "";
            $this->tuesday_time_out->TooltipValue = "";

            // wednesday_time_in
            $this->wednesday_time_in->LinkCustomAttributes = "";
            $this->wednesday_time_in->HrefValue = "";
            $this->wednesday_time_in->TooltipValue = "";

            // wednesday_time_out
            $this->wednesday_time_out->LinkCustomAttributes = "";
            $this->wednesday_time_out->HrefValue = "";
            $this->wednesday_time_out->TooltipValue = "";

            // thursday_time_in
            $this->thursday_time_in->LinkCustomAttributes = "";
            $this->thursday_time_in->HrefValue = "";
            $this->thursday_time_in->TooltipValue = "";

            // thursday_time_out
            $this->thursday_time_out->LinkCustomAttributes = "";
            $this->thursday_time_out->HrefValue = "";
            $this->thursday_time_out->TooltipValue = "";

            // friday_time_in
            $this->friday_time_in->LinkCustomAttributes = "";
            $this->friday_time_in->HrefValue = "";
            $this->friday_time_in->TooltipValue = "";

            // friday_time_out
            $this->friday_time_out->LinkCustomAttributes = "";
            $this->friday_time_out->HrefValue = "";
            $this->friday_time_out->TooltipValue = "";

            // saturday_time_in
            $this->saturday_time_in->LinkCustomAttributes = "";
            $this->saturday_time_in->HrefValue = "";
            $this->saturday_time_in->TooltipValue = "";

            // saturday_time_out
            $this->saturday_time_out->LinkCustomAttributes = "";
            $this->saturday_time_out->HrefValue = "";
            $this->saturday_time_out->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // shift_name
            $this->shift_name->EditAttrs["class"] = "form-control";
            $this->shift_name->EditCustomAttributes = "";
            if (!$this->shift_name->Raw) {
                $this->shift_name->AdvancedSearch->SearchValue = HtmlDecode($this->shift_name->AdvancedSearch->SearchValue);
            }
            $this->shift_name->EditValue = HtmlEncode($this->shift_name->AdvancedSearch->SearchValue);
            $this->shift_name->PlaceHolder = RemoveHtml($this->shift_name->caption());
            $this->shift_name->EditAttrs["class"] = "form-control";
            $this->shift_name->EditCustomAttributes = "";
            if (!$this->shift_name->Raw) {
                $this->shift_name->AdvancedSearch->SearchValue2 = HtmlDecode($this->shift_name->AdvancedSearch->SearchValue2);
            }
            $this->shift_name->EditValue2 = HtmlEncode($this->shift_name->AdvancedSearch->SearchValue2);
            $this->shift_name->PlaceHolder = RemoveHtml($this->shift_name->caption());

            // sunday_time_in
            $this->sunday_time_in->EditAttrs["class"] = "form-control";
            $this->sunday_time_in->EditCustomAttributes = "";
            $this->sunday_time_in->EditValue = HtmlEncode(UnFormatDateTime($this->sunday_time_in->AdvancedSearch->SearchValue, 4));
            $this->sunday_time_in->PlaceHolder = RemoveHtml($this->sunday_time_in->caption());
            $this->sunday_time_in->EditAttrs["class"] = "form-control";
            $this->sunday_time_in->EditCustomAttributes = "";
            $this->sunday_time_in->EditValue2 = HtmlEncode(UnFormatDateTime($this->sunday_time_in->AdvancedSearch->SearchValue2, 4));
            $this->sunday_time_in->PlaceHolder = RemoveHtml($this->sunday_time_in->caption());

            // sunday_time_out
            $this->sunday_time_out->EditAttrs["class"] = "form-control";
            $this->sunday_time_out->EditCustomAttributes = "";
            $this->sunday_time_out->EditValue = HtmlEncode(UnFormatDateTime($this->sunday_time_out->AdvancedSearch->SearchValue, 4));
            $this->sunday_time_out->PlaceHolder = RemoveHtml($this->sunday_time_out->caption());
            $this->sunday_time_out->EditAttrs["class"] = "form-control";
            $this->sunday_time_out->EditCustomAttributes = "";
            $this->sunday_time_out->EditValue2 = HtmlEncode(UnFormatDateTime($this->sunday_time_out->AdvancedSearch->SearchValue2, 4));
            $this->sunday_time_out->PlaceHolder = RemoveHtml($this->sunday_time_out->caption());

            // monday_time_in
            $this->monday_time_in->EditAttrs["class"] = "form-control";
            $this->monday_time_in->EditCustomAttributes = "";
            $this->monday_time_in->EditValue = HtmlEncode(UnFormatDateTime($this->monday_time_in->AdvancedSearch->SearchValue, 4));
            $this->monday_time_in->PlaceHolder = RemoveHtml($this->monday_time_in->caption());
            $this->monday_time_in->EditAttrs["class"] = "form-control";
            $this->monday_time_in->EditCustomAttributes = "";
            $this->monday_time_in->EditValue2 = HtmlEncode(UnFormatDateTime($this->monday_time_in->AdvancedSearch->SearchValue2, 4));
            $this->monday_time_in->PlaceHolder = RemoveHtml($this->monday_time_in->caption());

            // monday_time_out
            $this->monday_time_out->EditAttrs["class"] = "form-control";
            $this->monday_time_out->EditCustomAttributes = "";
            $this->monday_time_out->EditValue = HtmlEncode(UnFormatDateTime($this->monday_time_out->AdvancedSearch->SearchValue, 4));
            $this->monday_time_out->PlaceHolder = RemoveHtml($this->monday_time_out->caption());
            $this->monday_time_out->EditAttrs["class"] = "form-control";
            $this->monday_time_out->EditCustomAttributes = "";
            $this->monday_time_out->EditValue2 = HtmlEncode(UnFormatDateTime($this->monday_time_out->AdvancedSearch->SearchValue2, 4));
            $this->monday_time_out->PlaceHolder = RemoveHtml($this->monday_time_out->caption());

            // tuesday_time_in
            $this->tuesday_time_in->EditAttrs["class"] = "form-control";
            $this->tuesday_time_in->EditCustomAttributes = "";
            $this->tuesday_time_in->EditValue = HtmlEncode(UnFormatDateTime($this->tuesday_time_in->AdvancedSearch->SearchValue, 4));
            $this->tuesday_time_in->PlaceHolder = RemoveHtml($this->tuesday_time_in->caption());
            $this->tuesday_time_in->EditAttrs["class"] = "form-control";
            $this->tuesday_time_in->EditCustomAttributes = "";
            $this->tuesday_time_in->EditValue2 = HtmlEncode(UnFormatDateTime($this->tuesday_time_in->AdvancedSearch->SearchValue2, 4));
            $this->tuesday_time_in->PlaceHolder = RemoveHtml($this->tuesday_time_in->caption());

            // tuesday_time_out
            $this->tuesday_time_out->EditAttrs["class"] = "form-control";
            $this->tuesday_time_out->EditCustomAttributes = "";
            $this->tuesday_time_out->EditValue = HtmlEncode(UnFormatDateTime($this->tuesday_time_out->AdvancedSearch->SearchValue, 4));
            $this->tuesday_time_out->PlaceHolder = RemoveHtml($this->tuesday_time_out->caption());
            $this->tuesday_time_out->EditAttrs["class"] = "form-control";
            $this->tuesday_time_out->EditCustomAttributes = "";
            $this->tuesday_time_out->EditValue2 = HtmlEncode(UnFormatDateTime($this->tuesday_time_out->AdvancedSearch->SearchValue2, 4));
            $this->tuesday_time_out->PlaceHolder = RemoveHtml($this->tuesday_time_out->caption());

            // wednesday_time_in
            $this->wednesday_time_in->EditAttrs["class"] = "form-control";
            $this->wednesday_time_in->EditCustomAttributes = "";
            $this->wednesday_time_in->EditValue = HtmlEncode(UnFormatDateTime($this->wednesday_time_in->AdvancedSearch->SearchValue, 4));
            $this->wednesday_time_in->PlaceHolder = RemoveHtml($this->wednesday_time_in->caption());
            $this->wednesday_time_in->EditAttrs["class"] = "form-control";
            $this->wednesday_time_in->EditCustomAttributes = "";
            $this->wednesday_time_in->EditValue2 = HtmlEncode(UnFormatDateTime($this->wednesday_time_in->AdvancedSearch->SearchValue2, 4));
            $this->wednesday_time_in->PlaceHolder = RemoveHtml($this->wednesday_time_in->caption());

            // wednesday_time_out
            $this->wednesday_time_out->EditAttrs["class"] = "form-control";
            $this->wednesday_time_out->EditCustomAttributes = "";
            $this->wednesday_time_out->EditValue = HtmlEncode(UnFormatDateTime($this->wednesday_time_out->AdvancedSearch->SearchValue, 4));
            $this->wednesday_time_out->PlaceHolder = RemoveHtml($this->wednesday_time_out->caption());
            $this->wednesday_time_out->EditAttrs["class"] = "form-control";
            $this->wednesday_time_out->EditCustomAttributes = "";
            $this->wednesday_time_out->EditValue2 = HtmlEncode(UnFormatDateTime($this->wednesday_time_out->AdvancedSearch->SearchValue2, 4));
            $this->wednesday_time_out->PlaceHolder = RemoveHtml($this->wednesday_time_out->caption());

            // thursday_time_in
            $this->thursday_time_in->EditAttrs["class"] = "form-control";
            $this->thursday_time_in->EditCustomAttributes = "";
            $this->thursday_time_in->EditValue = HtmlEncode(UnFormatDateTime($this->thursday_time_in->AdvancedSearch->SearchValue, 4));
            $this->thursday_time_in->PlaceHolder = RemoveHtml($this->thursday_time_in->caption());
            $this->thursday_time_in->EditAttrs["class"] = "form-control";
            $this->thursday_time_in->EditCustomAttributes = "";
            $this->thursday_time_in->EditValue2 = HtmlEncode(UnFormatDateTime($this->thursday_time_in->AdvancedSearch->SearchValue2, 4));
            $this->thursday_time_in->PlaceHolder = RemoveHtml($this->thursday_time_in->caption());

            // thursday_time_out
            $this->thursday_time_out->EditAttrs["class"] = "form-control";
            $this->thursday_time_out->EditCustomAttributes = "";
            $this->thursday_time_out->EditValue = HtmlEncode(UnFormatDateTime($this->thursday_time_out->AdvancedSearch->SearchValue, 4));
            $this->thursday_time_out->PlaceHolder = RemoveHtml($this->thursday_time_out->caption());
            $this->thursday_time_out->EditAttrs["class"] = "form-control";
            $this->thursday_time_out->EditCustomAttributes = "";
            $this->thursday_time_out->EditValue2 = HtmlEncode(UnFormatDateTime($this->thursday_time_out->AdvancedSearch->SearchValue2, 4));
            $this->thursday_time_out->PlaceHolder = RemoveHtml($this->thursday_time_out->caption());

            // friday_time_in
            $this->friday_time_in->EditAttrs["class"] = "form-control";
            $this->friday_time_in->EditCustomAttributes = "";
            $this->friday_time_in->EditValue = HtmlEncode(UnFormatDateTime($this->friday_time_in->AdvancedSearch->SearchValue, 4));
            $this->friday_time_in->PlaceHolder = RemoveHtml($this->friday_time_in->caption());
            $this->friday_time_in->EditAttrs["class"] = "form-control";
            $this->friday_time_in->EditCustomAttributes = "";
            $this->friday_time_in->EditValue2 = HtmlEncode(UnFormatDateTime($this->friday_time_in->AdvancedSearch->SearchValue2, 4));
            $this->friday_time_in->PlaceHolder = RemoveHtml($this->friday_time_in->caption());

            // friday_time_out
            $this->friday_time_out->EditAttrs["class"] = "form-control";
            $this->friday_time_out->EditCustomAttributes = "";
            $this->friday_time_out->EditValue = HtmlEncode(UnFormatDateTime($this->friday_time_out->AdvancedSearch->SearchValue, 4));
            $this->friday_time_out->PlaceHolder = RemoveHtml($this->friday_time_out->caption());
            $this->friday_time_out->EditAttrs["class"] = "form-control";
            $this->friday_time_out->EditCustomAttributes = "";
            $this->friday_time_out->EditValue2 = HtmlEncode(UnFormatDateTime($this->friday_time_out->AdvancedSearch->SearchValue2, 4));
            $this->friday_time_out->PlaceHolder = RemoveHtml($this->friday_time_out->caption());

            // saturday_time_in
            $this->saturday_time_in->EditAttrs["class"] = "form-control";
            $this->saturday_time_in->EditCustomAttributes = "";
            $this->saturday_time_in->EditValue = HtmlEncode(UnFormatDateTime($this->saturday_time_in->AdvancedSearch->SearchValue, 4));
            $this->saturday_time_in->PlaceHolder = RemoveHtml($this->saturday_time_in->caption());
            $this->saturday_time_in->EditAttrs["class"] = "form-control";
            $this->saturday_time_in->EditCustomAttributes = "";
            $this->saturday_time_in->EditValue2 = HtmlEncode(UnFormatDateTime($this->saturday_time_in->AdvancedSearch->SearchValue2, 4));
            $this->saturday_time_in->PlaceHolder = RemoveHtml($this->saturday_time_in->caption());

            // saturday_time_out
            $this->saturday_time_out->EditAttrs["class"] = "form-control";
            $this->saturday_time_out->EditCustomAttributes = "";
            $this->saturday_time_out->EditValue = HtmlEncode(UnFormatDateTime($this->saturday_time_out->AdvancedSearch->SearchValue, 4));
            $this->saturday_time_out->PlaceHolder = RemoveHtml($this->saturday_time_out->caption());
            $this->saturday_time_out->EditAttrs["class"] = "form-control";
            $this->saturday_time_out->EditCustomAttributes = "";
            $this->saturday_time_out->EditValue2 = HtmlEncode(UnFormatDateTime($this->saturday_time_out->AdvancedSearch->SearchValue2, 4));
            $this->saturday_time_out->PlaceHolder = RemoveHtml($this->saturday_time_out->caption());
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
        if (!CheckTime($this->sunday_time_in->AdvancedSearch->SearchValue)) {
            $this->sunday_time_in->addErrorMessage($this->sunday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->sunday_time_in->AdvancedSearch->SearchValue2)) {
            $this->sunday_time_in->addErrorMessage($this->sunday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->sunday_time_out->AdvancedSearch->SearchValue)) {
            $this->sunday_time_out->addErrorMessage($this->sunday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->sunday_time_out->AdvancedSearch->SearchValue2)) {
            $this->sunday_time_out->addErrorMessage($this->sunday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->monday_time_in->AdvancedSearch->SearchValue)) {
            $this->monday_time_in->addErrorMessage($this->monday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->monday_time_in->AdvancedSearch->SearchValue2)) {
            $this->monday_time_in->addErrorMessage($this->monday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->monday_time_out->AdvancedSearch->SearchValue)) {
            $this->monday_time_out->addErrorMessage($this->monday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->monday_time_out->AdvancedSearch->SearchValue2)) {
            $this->monday_time_out->addErrorMessage($this->monday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->tuesday_time_in->AdvancedSearch->SearchValue)) {
            $this->tuesday_time_in->addErrorMessage($this->tuesday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->tuesday_time_in->AdvancedSearch->SearchValue2)) {
            $this->tuesday_time_in->addErrorMessage($this->tuesday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->tuesday_time_out->AdvancedSearch->SearchValue)) {
            $this->tuesday_time_out->addErrorMessage($this->tuesday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->tuesday_time_out->AdvancedSearch->SearchValue2)) {
            $this->tuesday_time_out->addErrorMessage($this->tuesday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->wednesday_time_in->AdvancedSearch->SearchValue)) {
            $this->wednesday_time_in->addErrorMessage($this->wednesday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->wednesday_time_in->AdvancedSearch->SearchValue2)) {
            $this->wednesday_time_in->addErrorMessage($this->wednesday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->wednesday_time_out->AdvancedSearch->SearchValue)) {
            $this->wednesday_time_out->addErrorMessage($this->wednesday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->wednesday_time_out->AdvancedSearch->SearchValue2)) {
            $this->wednesday_time_out->addErrorMessage($this->wednesday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->thursday_time_in->AdvancedSearch->SearchValue)) {
            $this->thursday_time_in->addErrorMessage($this->thursday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->thursday_time_in->AdvancedSearch->SearchValue2)) {
            $this->thursday_time_in->addErrorMessage($this->thursday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->thursday_time_out->AdvancedSearch->SearchValue)) {
            $this->thursday_time_out->addErrorMessage($this->thursday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->thursday_time_out->AdvancedSearch->SearchValue2)) {
            $this->thursday_time_out->addErrorMessage($this->thursday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->friday_time_in->AdvancedSearch->SearchValue)) {
            $this->friday_time_in->addErrorMessage($this->friday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->friday_time_in->AdvancedSearch->SearchValue2)) {
            $this->friday_time_in->addErrorMessage($this->friday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->friday_time_out->AdvancedSearch->SearchValue)) {
            $this->friday_time_out->addErrorMessage($this->friday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->friday_time_out->AdvancedSearch->SearchValue2)) {
            $this->friday_time_out->addErrorMessage($this->friday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->saturday_time_in->AdvancedSearch->SearchValue)) {
            $this->saturday_time_in->addErrorMessage($this->saturday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->saturday_time_in->AdvancedSearch->SearchValue2)) {
            $this->saturday_time_in->addErrorMessage($this->saturday_time_in->getErrorMessage(false));
        }
        if (!CheckTime($this->saturday_time_out->AdvancedSearch->SearchValue)) {
            $this->saturday_time_out->addErrorMessage($this->saturday_time_out->getErrorMessage(false));
        }
        if (!CheckTime($this->saturday_time_out->AdvancedSearch->SearchValue2)) {
            $this->saturday_time_out->addErrorMessage($this->saturday_time_out->getErrorMessage(false));
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
        $this->shift_name->AdvancedSearch->load();
        $this->sunday_time_in->AdvancedSearch->load();
        $this->sunday_time_out->AdvancedSearch->load();
        $this->monday_time_in->AdvancedSearch->load();
        $this->monday_time_out->AdvancedSearch->load();
        $this->tuesday_time_in->AdvancedSearch->load();
        $this->tuesday_time_out->AdvancedSearch->load();
        $this->wednesday_time_in->AdvancedSearch->load();
        $this->wednesday_time_out->AdvancedSearch->load();
        $this->thursday_time_in->AdvancedSearch->load();
        $this->thursday_time_out->AdvancedSearch->load();
        $this->friday_time_in->AdvancedSearch->load();
        $this->friday_time_out->AdvancedSearch->load();
        $this->saturday_time_in->AdvancedSearch->load();
        $this->saturday_time_out->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("top10days");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("mastershiftlist"), "", $this->TableVar, true);
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
