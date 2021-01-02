<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EmployeeSearch extends Employee
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'employee';

    // Page object name
    public $PageObjName = "EmployeeSearch";

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

        // Table object (employee)
        if (!isset($GLOBALS["employee"]) || get_class($GLOBALS["employee"]) == PROJECT_NAMESPACE . "employee") {
            $GLOBALS["employee"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'employee');
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
                $doc = new $class(Container("employee"));
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
                    if ($pageName == "employeeview") {
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
            $key .= @$ar['employee_username'];
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
    public $MultiPages; // Multi pages object

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
        $this->employee_name->setVisibility();
        $this->employee_username->setVisibility();
        $this->employee_password->Visible = false;
        $this->employee_email->setVisibility();
        $this->birth_date->setVisibility();
        $this->religion->setVisibility();
        $this->nik->setVisibility();
        $this->npwp->setVisibility();
        $this->address->setVisibility();
        $this->city_id->setVisibility();
        $this->postal_code->setVisibility();
        $this->bank_number->setVisibility();
        $this->bank_name->setVisibility();
        $this->scan_ktp->Visible = false;
        $this->scan_npwp->Visible = false;
        $this->curiculum_vitae->Visible = false;
        $this->position_id->setVisibility();
        $this->status_id->setVisibility();
        $this->skill_id->setVisibility();
        $this->office_id->setVisibility();
        $this->hire_date->setVisibility();
        $this->termination_date->setVisibility();
        $this->user_level->setVisibility();
        $this->technical_skill->setVisibility();
        $this->about_me->setVisibility();
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Set up multi page object
        $this->setupMultiPages();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->city_id);
        $this->setupLookupOptions($this->position_id);
        $this->setupLookupOptions($this->status_id);
        $this->setupLookupOptions($this->skill_id);
        $this->setupLookupOptions($this->office_id);
        $this->setupLookupOptions($this->user_level);

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
                    $srchStr = "employeelist" . "?" . $srchStr;
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
        $this->buildSearchUrl($srchUrl, $this->employee_name); // employee_name
        $this->buildSearchUrl($srchUrl, $this->employee_username); // employee_username
        $this->buildSearchUrl($srchUrl, $this->employee_email); // employee_email
        $this->buildSearchUrl($srchUrl, $this->birth_date); // birth_date
        $this->buildSearchUrl($srchUrl, $this->religion); // religion
        $this->buildSearchUrl($srchUrl, $this->nik); // nik
        $this->buildSearchUrl($srchUrl, $this->npwp); // npwp
        $this->buildSearchUrl($srchUrl, $this->address); // address
        $this->buildSearchUrl($srchUrl, $this->city_id); // city_id
        $this->buildSearchUrl($srchUrl, $this->postal_code); // postal_code
        $this->buildSearchUrl($srchUrl, $this->bank_number); // bank_number
        $this->buildSearchUrl($srchUrl, $this->bank_name); // bank_name
        $this->buildSearchUrl($srchUrl, $this->position_id); // position_id
        $this->buildSearchUrl($srchUrl, $this->status_id); // status_id
        $this->buildSearchUrl($srchUrl, $this->skill_id); // skill_id
        $this->buildSearchUrl($srchUrl, $this->office_id); // office_id
        $this->buildSearchUrl($srchUrl, $this->hire_date); // hire_date
        $this->buildSearchUrl($srchUrl, $this->termination_date); // termination_date
        $this->buildSearchUrl($srchUrl, $this->user_level); // user_level
        $this->buildSearchUrl($srchUrl, $this->technical_skill); // technical_skill
        $this->buildSearchUrl($srchUrl, $this->about_me); // about_me
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
        if ($this->employee_name->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->employee_username->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->employee_email->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->birth_date->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->religion->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->nik->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->npwp->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->address->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->city_id->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->postal_code->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->bank_number->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->bank_name->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->position_id->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->status_id->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->skill_id->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->office_id->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->hire_date->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->termination_date->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->user_level->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->technical_skill->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->about_me->AdvancedSearch->post()) {
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

        // employee_name

        // employee_username

        // employee_password

        // employee_email

        // birth_date

        // religion

        // nik

        // npwp

        // address

        // city_id

        // postal_code

        // bank_number

        // bank_name

        // scan_ktp

        // scan_npwp

        // curiculum_vitae

        // position_id

        // status_id

        // skill_id

        // office_id

        // hire_date

        // termination_date

        // user_level

        // technical_skill

        // about_me
        if ($this->RowType == ROWTYPE_VIEW) {
            // employee_name
            $this->employee_name->ViewValue = $this->employee_name->CurrentValue;
            $this->employee_name->ViewCustomAttributes = "";

            // employee_username
            $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
            $this->employee_username->ViewCustomAttributes = "";

            // employee_password
            $this->employee_password->ViewValue = $Language->phrase("PasswordMask");
            $this->employee_password->ViewCustomAttributes = "";

            // employee_email
            $this->employee_email->ViewValue = $this->employee_email->CurrentValue;
            $this->employee_email->ViewCustomAttributes = "";

            // birth_date
            $this->birth_date->ViewValue = $this->birth_date->CurrentValue;
            $this->birth_date->ViewValue = FormatDateTime($this->birth_date->ViewValue, 5);
            $this->birth_date->ViewCustomAttributes = "";

            // religion
            if (strval($this->religion->CurrentValue) != "") {
                $this->religion->ViewValue = $this->religion->optionCaption($this->religion->CurrentValue);
            } else {
                $this->religion->ViewValue = null;
            }
            $this->religion->ViewCustomAttributes = "";

            // nik
            $this->nik->ViewValue = $this->nik->CurrentValue;
            $this->nik->ViewCustomAttributes = "";

            // npwp
            $this->npwp->ViewValue = $this->npwp->CurrentValue;
            $this->npwp->ViewCustomAttributes = "";

            // address
            $this->address->ViewValue = $this->address->CurrentValue;
            $this->address->ViewCustomAttributes = "";

            // city_id
            $curVal = strval($this->city_id->CurrentValue);
            if ($curVal != "") {
                $this->city_id->ViewValue = $this->city_id->lookupCacheOption($curVal);
                if ($this->city_id->ViewValue === null) { // Lookup from database
                    $filterWrk = "`city_id`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->city_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->city_id->Lookup->renderViewRow($rswrk[0]);
                        $this->city_id->ViewValue = $this->city_id->displayValue($arwrk);
                    } else {
                        $this->city_id->ViewValue = $this->city_id->CurrentValue;
                    }
                }
            } else {
                $this->city_id->ViewValue = null;
            }
            $this->city_id->ViewCustomAttributes = "";

            // postal_code
            $this->postal_code->ViewValue = $this->postal_code->CurrentValue;
            $this->postal_code->ViewCustomAttributes = "";

            // bank_number
            $this->bank_number->ViewValue = $this->bank_number->CurrentValue;
            $this->bank_number->ViewCustomAttributes = "";

            // bank_name
            $this->bank_name->ViewValue = $this->bank_name->CurrentValue;
            $this->bank_name->ViewCustomAttributes = "";

            // scan_ktp
            if (!EmptyValue($this->scan_ktp->Upload->DbValue)) {
                $this->scan_ktp->ViewValue = $this->scan_ktp->Upload->DbValue;
            } else {
                $this->scan_ktp->ViewValue = "";
            }
            $this->scan_ktp->ViewCustomAttributes = "";

            // scan_npwp
            if (!EmptyValue($this->scan_npwp->Upload->DbValue)) {
                $this->scan_npwp->ViewValue = $this->scan_npwp->Upload->DbValue;
            } else {
                $this->scan_npwp->ViewValue = "";
            }
            $this->scan_npwp->ViewCustomAttributes = "";

            // curiculum_vitae
            if (!EmptyValue($this->curiculum_vitae->Upload->DbValue)) {
                $this->curiculum_vitae->ViewValue = $this->curiculum_vitae->Upload->DbValue;
            } else {
                $this->curiculum_vitae->ViewValue = "";
            }
            $this->curiculum_vitae->ViewCustomAttributes = "";

            // position_id
            $curVal = strval($this->position_id->CurrentValue);
            if ($curVal != "") {
                $this->position_id->ViewValue = $this->position_id->lookupCacheOption($curVal);
                if ($this->position_id->ViewValue === null) { // Lookup from database
                    $filterWrk = "`position_id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->position_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->position_id->Lookup->renderViewRow($rswrk[0]);
                        $this->position_id->ViewValue = $this->position_id->displayValue($arwrk);
                    } else {
                        $this->position_id->ViewValue = $this->position_id->CurrentValue;
                    }
                }
            } else {
                $this->position_id->ViewValue = null;
            }
            $this->position_id->ViewCustomAttributes = "";

            // status_id
            $curVal = strval($this->status_id->CurrentValue);
            if ($curVal != "") {
                $this->status_id->ViewValue = $this->status_id->lookupCacheOption($curVal);
                if ($this->status_id->ViewValue === null) { // Lookup from database
                    $filterWrk = "`status_id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->status_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->status_id->Lookup->renderViewRow($rswrk[0]);
                        $this->status_id->ViewValue = $this->status_id->displayValue($arwrk);
                    } else {
                        $this->status_id->ViewValue = $this->status_id->CurrentValue;
                    }
                }
            } else {
                $this->status_id->ViewValue = null;
            }
            $this->status_id->ViewCustomAttributes = "";

            // skill_id
            $curVal = strval($this->skill_id->CurrentValue);
            if ($curVal != "") {
                $this->skill_id->ViewValue = $this->skill_id->lookupCacheOption($curVal);
                if ($this->skill_id->ViewValue === null) { // Lookup from database
                    $filterWrk = "`skill_id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->skill_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->skill_id->Lookup->renderViewRow($rswrk[0]);
                        $this->skill_id->ViewValue = $this->skill_id->displayValue($arwrk);
                    } else {
                        $this->skill_id->ViewValue = $this->skill_id->CurrentValue;
                    }
                }
            } else {
                $this->skill_id->ViewValue = null;
            }
            $this->skill_id->ViewCustomAttributes = "";

            // office_id
            $curVal = strval($this->office_id->CurrentValue);
            if ($curVal != "") {
                $this->office_id->ViewValue = $this->office_id->lookupCacheOption($curVal);
                if ($this->office_id->ViewValue === null) { // Lookup from database
                    $filterWrk = "`office_id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->office_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->office_id->Lookup->renderViewRow($rswrk[0]);
                        $this->office_id->ViewValue = $this->office_id->displayValue($arwrk);
                    } else {
                        $this->office_id->ViewValue = $this->office_id->CurrentValue;
                    }
                }
            } else {
                $this->office_id->ViewValue = null;
            }
            $this->office_id->ViewCustomAttributes = "";

            // hire_date
            $this->hire_date->ViewValue = $this->hire_date->CurrentValue;
            $this->hire_date->ViewValue = FormatDateTime($this->hire_date->ViewValue, 5);
            $this->hire_date->ViewCustomAttributes = "";

            // termination_date
            $this->termination_date->ViewValue = $this->termination_date->CurrentValue;
            $this->termination_date->ViewValue = FormatDateTime($this->termination_date->ViewValue, 5);
            $this->termination_date->ViewCustomAttributes = "";

            // user_level
            if ($Security->canAdmin()) { // System admin
                $curVal = strval($this->user_level->CurrentValue);
                if ($curVal != "") {
                    $this->user_level->ViewValue = $this->user_level->lookupCacheOption($curVal);
                    if ($this->user_level->ViewValue === null) { // Lookup from database
                        $filterWrk = "`userlevelid`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->user_level->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->user_level->Lookup->renderViewRow($rswrk[0]);
                            $this->user_level->ViewValue = $this->user_level->displayValue($arwrk);
                        } else {
                            $this->user_level->ViewValue = $this->user_level->CurrentValue;
                        }
                    }
                } else {
                    $this->user_level->ViewValue = null;
                }
            } else {
                $this->user_level->ViewValue = $Language->phrase("PasswordMask");
            }
            $this->user_level->ViewCustomAttributes = "";

            // technical_skill
            $this->technical_skill->ViewValue = $this->technical_skill->CurrentValue;
            $this->technical_skill->ViewCustomAttributes = "";

            // about_me
            $this->about_me->ViewValue = $this->about_me->CurrentValue;
            $this->about_me->ViewCustomAttributes = "";

            // employee_name
            $this->employee_name->LinkCustomAttributes = "";
            $this->employee_name->HrefValue = "";
            $this->employee_name->TooltipValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";
            $this->employee_username->TooltipValue = "";

            // employee_email
            $this->employee_email->LinkCustomAttributes = "";
            $this->employee_email->HrefValue = "";
            $this->employee_email->TooltipValue = "";

            // birth_date
            $this->birth_date->LinkCustomAttributes = "";
            $this->birth_date->HrefValue = "";
            $this->birth_date->TooltipValue = "";

            // religion
            $this->religion->LinkCustomAttributes = "";
            $this->religion->HrefValue = "";
            $this->religion->TooltipValue = "";

            // nik
            $this->nik->LinkCustomAttributes = "";
            $this->nik->HrefValue = "";
            $this->nik->TooltipValue = "";

            // npwp
            $this->npwp->LinkCustomAttributes = "";
            $this->npwp->HrefValue = "";
            $this->npwp->TooltipValue = "";

            // address
            $this->address->LinkCustomAttributes = "";
            $this->address->HrefValue = "";
            $this->address->TooltipValue = "";

            // city_id
            $this->city_id->LinkCustomAttributes = "";
            $this->city_id->HrefValue = "";
            $this->city_id->TooltipValue = "";

            // postal_code
            $this->postal_code->LinkCustomAttributes = "";
            $this->postal_code->HrefValue = "";
            $this->postal_code->TooltipValue = "";

            // bank_number
            $this->bank_number->LinkCustomAttributes = "";
            $this->bank_number->HrefValue = "";
            $this->bank_number->TooltipValue = "";

            // bank_name
            $this->bank_name->LinkCustomAttributes = "";
            $this->bank_name->HrefValue = "";
            $this->bank_name->TooltipValue = "";

            // position_id
            $this->position_id->LinkCustomAttributes = "";
            $this->position_id->HrefValue = "";
            $this->position_id->TooltipValue = "";

            // status_id
            $this->status_id->LinkCustomAttributes = "";
            $this->status_id->HrefValue = "";
            $this->status_id->TooltipValue = "";

            // skill_id
            $this->skill_id->LinkCustomAttributes = "";
            $this->skill_id->HrefValue = "";
            $this->skill_id->TooltipValue = "";

            // office_id
            $this->office_id->LinkCustomAttributes = "";
            $this->office_id->HrefValue = "";
            $this->office_id->TooltipValue = "";

            // hire_date
            $this->hire_date->LinkCustomAttributes = "";
            $this->hire_date->HrefValue = "";
            $this->hire_date->TooltipValue = "";

            // termination_date
            $this->termination_date->LinkCustomAttributes = "";
            $this->termination_date->HrefValue = "";
            $this->termination_date->TooltipValue = "";

            // user_level
            $this->user_level->LinkCustomAttributes = "";
            $this->user_level->HrefValue = "";
            $this->user_level->TooltipValue = "";

            // technical_skill
            $this->technical_skill->LinkCustomAttributes = "";
            $this->technical_skill->HrefValue = "";
            $this->technical_skill->TooltipValue = "";

            // about_me
            $this->about_me->LinkCustomAttributes = "";
            $this->about_me->HrefValue = "";
            $this->about_me->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // employee_name
            $this->employee_name->EditAttrs["class"] = "form-control";
            $this->employee_name->EditCustomAttributes = "";
            if (!$this->employee_name->Raw) {
                $this->employee_name->AdvancedSearch->SearchValue = HtmlDecode($this->employee_name->AdvancedSearch->SearchValue);
            }
            $this->employee_name->EditValue = HtmlEncode($this->employee_name->AdvancedSearch->SearchValue);
            $this->employee_name->PlaceHolder = RemoveHtml($this->employee_name->caption());
            $this->employee_name->EditAttrs["class"] = "form-control";
            $this->employee_name->EditCustomAttributes = "";
            if (!$this->employee_name->Raw) {
                $this->employee_name->AdvancedSearch->SearchValue2 = HtmlDecode($this->employee_name->AdvancedSearch->SearchValue2);
            }
            $this->employee_name->EditValue2 = HtmlEncode($this->employee_name->AdvancedSearch->SearchValue2);
            $this->employee_name->PlaceHolder = RemoveHtml($this->employee_name->caption());

            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            if (!$this->employee_username->Raw) {
                $this->employee_username->AdvancedSearch->SearchValue = HtmlDecode($this->employee_username->AdvancedSearch->SearchValue);
            }
            $this->employee_username->EditValue = HtmlEncode($this->employee_username->AdvancedSearch->SearchValue);
            $this->employee_username->PlaceHolder = RemoveHtml($this->employee_username->caption());
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            if (!$this->employee_username->Raw) {
                $this->employee_username->AdvancedSearch->SearchValue2 = HtmlDecode($this->employee_username->AdvancedSearch->SearchValue2);
            }
            $this->employee_username->EditValue2 = HtmlEncode($this->employee_username->AdvancedSearch->SearchValue2);
            $this->employee_username->PlaceHolder = RemoveHtml($this->employee_username->caption());

            // employee_email
            $this->employee_email->EditAttrs["class"] = "form-control";
            $this->employee_email->EditCustomAttributes = "";
            if (!$this->employee_email->Raw) {
                $this->employee_email->AdvancedSearch->SearchValue = HtmlDecode($this->employee_email->AdvancedSearch->SearchValue);
            }
            $this->employee_email->EditValue = HtmlEncode($this->employee_email->AdvancedSearch->SearchValue);
            $this->employee_email->PlaceHolder = RemoveHtml($this->employee_email->caption());
            $this->employee_email->EditAttrs["class"] = "form-control";
            $this->employee_email->EditCustomAttributes = "";
            if (!$this->employee_email->Raw) {
                $this->employee_email->AdvancedSearch->SearchValue2 = HtmlDecode($this->employee_email->AdvancedSearch->SearchValue2);
            }
            $this->employee_email->EditValue2 = HtmlEncode($this->employee_email->AdvancedSearch->SearchValue2);
            $this->employee_email->PlaceHolder = RemoveHtml($this->employee_email->caption());

            // birth_date
            $this->birth_date->EditAttrs["class"] = "form-control";
            $this->birth_date->EditCustomAttributes = "";
            $this->birth_date->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->birth_date->AdvancedSearch->SearchValue, 5), 5));
            $this->birth_date->PlaceHolder = RemoveHtml($this->birth_date->caption());
            $this->birth_date->EditAttrs["class"] = "form-control";
            $this->birth_date->EditCustomAttributes = "";
            $this->birth_date->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->birth_date->AdvancedSearch->SearchValue2, 5), 5));
            $this->birth_date->PlaceHolder = RemoveHtml($this->birth_date->caption());

            // religion
            $this->religion->EditAttrs["class"] = "form-control";
            $this->religion->EditCustomAttributes = "";
            $this->religion->EditValue = $this->religion->options(true);
            $this->religion->PlaceHolder = RemoveHtml($this->religion->caption());

            // nik
            $this->nik->EditAttrs["class"] = "form-control";
            $this->nik->EditCustomAttributes = "";
            if (!$this->nik->Raw) {
                $this->nik->AdvancedSearch->SearchValue = HtmlDecode($this->nik->AdvancedSearch->SearchValue);
            }
            $this->nik->EditValue = HtmlEncode($this->nik->AdvancedSearch->SearchValue);
            $this->nik->PlaceHolder = RemoveHtml($this->nik->caption());

            // npwp
            $this->npwp->EditAttrs["class"] = "form-control";
            $this->npwp->EditCustomAttributes = "";
            if (!$this->npwp->Raw) {
                $this->npwp->AdvancedSearch->SearchValue = HtmlDecode($this->npwp->AdvancedSearch->SearchValue);
            }
            $this->npwp->EditValue = HtmlEncode($this->npwp->AdvancedSearch->SearchValue);
            $this->npwp->PlaceHolder = RemoveHtml($this->npwp->caption());

            // address
            $this->address->EditAttrs["class"] = "form-control";
            $this->address->EditCustomAttributes = "";
            if (!$this->address->Raw) {
                $this->address->AdvancedSearch->SearchValue = HtmlDecode($this->address->AdvancedSearch->SearchValue);
            }
            $this->address->EditValue = HtmlEncode($this->address->AdvancedSearch->SearchValue);
            $this->address->PlaceHolder = RemoveHtml($this->address->caption());
            $this->address->EditAttrs["class"] = "form-control";
            $this->address->EditCustomAttributes = "";
            if (!$this->address->Raw) {
                $this->address->AdvancedSearch->SearchValue2 = HtmlDecode($this->address->AdvancedSearch->SearchValue2);
            }
            $this->address->EditValue2 = HtmlEncode($this->address->AdvancedSearch->SearchValue2);
            $this->address->PlaceHolder = RemoveHtml($this->address->caption());

            // city_id
            $this->city_id->EditAttrs["class"] = "form-control";
            $this->city_id->EditCustomAttributes = "";
            $curVal = trim(strval($this->city_id->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->city_id->AdvancedSearch->ViewValue = $this->city_id->lookupCacheOption($curVal);
            } else {
                $this->city_id->AdvancedSearch->ViewValue = $this->city_id->Lookup !== null && is_array($this->city_id->Lookup->Options) ? $curVal : null;
            }
            if ($this->city_id->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->city_id->EditValue = array_values($this->city_id->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`city_id`" . SearchString("=", $this->city_id->AdvancedSearch->SearchValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->city_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->city_id->EditValue = $arwrk;
            }
            $this->city_id->PlaceHolder = RemoveHtml($this->city_id->caption());

            // postal_code
            $this->postal_code->EditAttrs["class"] = "form-control";
            $this->postal_code->EditCustomAttributes = "";
            if (!$this->postal_code->Raw) {
                $this->postal_code->AdvancedSearch->SearchValue = HtmlDecode($this->postal_code->AdvancedSearch->SearchValue);
            }
            $this->postal_code->EditValue = HtmlEncode($this->postal_code->AdvancedSearch->SearchValue);
            $this->postal_code->PlaceHolder = RemoveHtml($this->postal_code->caption());
            $this->postal_code->EditAttrs["class"] = "form-control";
            $this->postal_code->EditCustomAttributes = "";
            if (!$this->postal_code->Raw) {
                $this->postal_code->AdvancedSearch->SearchValue2 = HtmlDecode($this->postal_code->AdvancedSearch->SearchValue2);
            }
            $this->postal_code->EditValue2 = HtmlEncode($this->postal_code->AdvancedSearch->SearchValue2);
            $this->postal_code->PlaceHolder = RemoveHtml($this->postal_code->caption());

            // bank_number
            $this->bank_number->EditAttrs["class"] = "form-control";
            $this->bank_number->EditCustomAttributes = "";
            if (!$this->bank_number->Raw) {
                $this->bank_number->AdvancedSearch->SearchValue = HtmlDecode($this->bank_number->AdvancedSearch->SearchValue);
            }
            $this->bank_number->EditValue = HtmlEncode($this->bank_number->AdvancedSearch->SearchValue);
            $this->bank_number->PlaceHolder = RemoveHtml($this->bank_number->caption());

            // bank_name
            $this->bank_name->EditAttrs["class"] = "form-control";
            $this->bank_name->EditCustomAttributes = "";
            if (!$this->bank_name->Raw) {
                $this->bank_name->AdvancedSearch->SearchValue = HtmlDecode($this->bank_name->AdvancedSearch->SearchValue);
            }
            $this->bank_name->EditValue = HtmlEncode($this->bank_name->AdvancedSearch->SearchValue);
            $this->bank_name->PlaceHolder = RemoveHtml($this->bank_name->caption());

            // position_id
            $this->position_id->EditAttrs["class"] = "form-control";
            $this->position_id->EditCustomAttributes = "";
            $curVal = trim(strval($this->position_id->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->position_id->AdvancedSearch->ViewValue = $this->position_id->lookupCacheOption($curVal);
            } else {
                $this->position_id->AdvancedSearch->ViewValue = $this->position_id->Lookup !== null && is_array($this->position_id->Lookup->Options) ? $curVal : null;
            }
            if ($this->position_id->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->position_id->EditValue = array_values($this->position_id->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`position_id`" . SearchString("=", $this->position_id->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->position_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->position_id->EditValue = $arwrk;
            }
            $this->position_id->PlaceHolder = RemoveHtml($this->position_id->caption());

            // status_id
            $this->status_id->EditAttrs["class"] = "form-control";
            $this->status_id->EditCustomAttributes = "";
            $curVal = trim(strval($this->status_id->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->status_id->AdvancedSearch->ViewValue = $this->status_id->lookupCacheOption($curVal);
            } else {
                $this->status_id->AdvancedSearch->ViewValue = $this->status_id->Lookup !== null && is_array($this->status_id->Lookup->Options) ? $curVal : null;
            }
            if ($this->status_id->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->status_id->EditValue = array_values($this->status_id->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`status_id`" . SearchString("=", $this->status_id->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->status_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->status_id->EditValue = $arwrk;
            }
            $this->status_id->PlaceHolder = RemoveHtml($this->status_id->caption());

            // skill_id
            $this->skill_id->EditAttrs["class"] = "form-control";
            $this->skill_id->EditCustomAttributes = "";
            $curVal = trim(strval($this->skill_id->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->skill_id->AdvancedSearch->ViewValue = $this->skill_id->lookupCacheOption($curVal);
            } else {
                $this->skill_id->AdvancedSearch->ViewValue = $this->skill_id->Lookup !== null && is_array($this->skill_id->Lookup->Options) ? $curVal : null;
            }
            if ($this->skill_id->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->skill_id->EditValue = array_values($this->skill_id->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`skill_id`" . SearchString("=", $this->skill_id->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->skill_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->skill_id->EditValue = $arwrk;
            }
            $this->skill_id->PlaceHolder = RemoveHtml($this->skill_id->caption());

            // office_id
            $this->office_id->EditAttrs["class"] = "form-control";
            $this->office_id->EditCustomAttributes = "";
            $curVal = trim(strval($this->office_id->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->office_id->AdvancedSearch->ViewValue = $this->office_id->lookupCacheOption($curVal);
            } else {
                $this->office_id->AdvancedSearch->ViewValue = $this->office_id->Lookup !== null && is_array($this->office_id->Lookup->Options) ? $curVal : null;
            }
            if ($this->office_id->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->office_id->EditValue = array_values($this->office_id->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`office_id`" . SearchString("=", $this->office_id->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->office_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->office_id->EditValue = $arwrk;
            }
            $this->office_id->PlaceHolder = RemoveHtml($this->office_id->caption());

            // hire_date
            $this->hire_date->EditAttrs["class"] = "form-control";
            $this->hire_date->EditCustomAttributes = "";
            $this->hire_date->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->hire_date->AdvancedSearch->SearchValue, 5), 5));
            $this->hire_date->PlaceHolder = RemoveHtml($this->hire_date->caption());
            $this->hire_date->EditAttrs["class"] = "form-control";
            $this->hire_date->EditCustomAttributes = "";
            $this->hire_date->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->hire_date->AdvancedSearch->SearchValue2, 5), 5));
            $this->hire_date->PlaceHolder = RemoveHtml($this->hire_date->caption());

            // termination_date
            $this->termination_date->EditAttrs["class"] = "form-control";
            $this->termination_date->EditCustomAttributes = "";
            $this->termination_date->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->termination_date->AdvancedSearch->SearchValue, 5), 5));
            $this->termination_date->PlaceHolder = RemoveHtml($this->termination_date->caption());
            $this->termination_date->EditAttrs["class"] = "form-control";
            $this->termination_date->EditCustomAttributes = "";
            $this->termination_date->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->termination_date->AdvancedSearch->SearchValue2, 5), 5));
            $this->termination_date->PlaceHolder = RemoveHtml($this->termination_date->caption());

            // user_level
            $this->user_level->EditAttrs["class"] = "form-control";
            $this->user_level->EditCustomAttributes = "";
            if (!$Security->canAdmin()) { // System admin
                $this->user_level->EditValue = $Language->phrase("PasswordMask");
            } else {
                $curVal = trim(strval($this->user_level->AdvancedSearch->SearchValue));
                if ($curVal != "") {
                    $this->user_level->AdvancedSearch->ViewValue = $this->user_level->lookupCacheOption($curVal);
                } else {
                    $this->user_level->AdvancedSearch->ViewValue = $this->user_level->Lookup !== null && is_array($this->user_level->Lookup->Options) ? $curVal : null;
                }
                if ($this->user_level->AdvancedSearch->ViewValue !== null) { // Load from cache
                    $this->user_level->EditValue = array_values($this->user_level->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`userlevelid`" . SearchString("=", $this->user_level->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->user_level->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->user_level->EditValue = $arwrk;
                }
                $this->user_level->PlaceHolder = RemoveHtml($this->user_level->caption());
            }
            $this->user_level->EditAttrs["class"] = "form-control";
            $this->user_level->EditCustomAttributes = "";
            if (!$Security->canAdmin()) { // System admin
                $this->user_level->EditValue2 = $Language->phrase("PasswordMask");
            } else {
                $curVal = trim(strval($this->user_level->AdvancedSearch->SearchValue2));
                if ($curVal != "") {
                    $this->user_level->AdvancedSearch->ViewValue2 = $this->user_level->lookupCacheOption($curVal);
                } else {
                    $this->user_level->AdvancedSearch->ViewValue2 = $this->user_level->Lookup !== null && is_array($this->user_level->Lookup->Options) ? $curVal : null;
                }
                if ($this->user_level->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                    $this->user_level->EditValue2 = array_values($this->user_level->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`userlevelid`" . SearchString("=", $this->user_level->AdvancedSearch->SearchValue2, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->user_level->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->user_level->EditValue2 = $arwrk;
                }
                $this->user_level->PlaceHolder = RemoveHtml($this->user_level->caption());
            }

            // technical_skill
            $this->technical_skill->EditAttrs["class"] = "form-control";
            $this->technical_skill->EditCustomAttributes = "";
            $this->technical_skill->EditValue = HtmlEncode($this->technical_skill->AdvancedSearch->SearchValue);
            $this->technical_skill->PlaceHolder = RemoveHtml($this->technical_skill->caption());
            $this->technical_skill->EditAttrs["class"] = "form-control";
            $this->technical_skill->EditCustomAttributes = "";
            $this->technical_skill->EditValue2 = HtmlEncode($this->technical_skill->AdvancedSearch->SearchValue2);
            $this->technical_skill->PlaceHolder = RemoveHtml($this->technical_skill->caption());

            // about_me
            $this->about_me->EditAttrs["class"] = "form-control";
            $this->about_me->EditCustomAttributes = "";
            $this->about_me->EditValue = HtmlEncode($this->about_me->AdvancedSearch->SearchValue);
            $this->about_me->PlaceHolder = RemoveHtml($this->about_me->caption());
            $this->about_me->EditAttrs["class"] = "form-control";
            $this->about_me->EditCustomAttributes = "";
            $this->about_me->EditValue2 = HtmlEncode($this->about_me->AdvancedSearch->SearchValue2);
            $this->about_me->PlaceHolder = RemoveHtml($this->about_me->caption());
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
        if (!CheckStdDate($this->birth_date->AdvancedSearch->SearchValue)) {
            $this->birth_date->addErrorMessage($this->birth_date->getErrorMessage(false));
        }
        if (!CheckStdDate($this->birth_date->AdvancedSearch->SearchValue2)) {
            $this->birth_date->addErrorMessage($this->birth_date->getErrorMessage(false));
        }
        if (!CheckStdDate($this->hire_date->AdvancedSearch->SearchValue)) {
            $this->hire_date->addErrorMessage($this->hire_date->getErrorMessage(false));
        }
        if (!CheckStdDate($this->hire_date->AdvancedSearch->SearchValue2)) {
            $this->hire_date->addErrorMessage($this->hire_date->getErrorMessage(false));
        }
        if (!CheckStdDate($this->termination_date->AdvancedSearch->SearchValue)) {
            $this->termination_date->addErrorMessage($this->termination_date->getErrorMessage(false));
        }
        if (!CheckStdDate($this->termination_date->AdvancedSearch->SearchValue2)) {
            $this->termination_date->addErrorMessage($this->termination_date->getErrorMessage(false));
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
        $this->employee_name->AdvancedSearch->load();
        $this->employee_username->AdvancedSearch->load();
        $this->employee_email->AdvancedSearch->load();
        $this->birth_date->AdvancedSearch->load();
        $this->religion->AdvancedSearch->load();
        $this->nik->AdvancedSearch->load();
        $this->npwp->AdvancedSearch->load();
        $this->address->AdvancedSearch->load();
        $this->city_id->AdvancedSearch->load();
        $this->postal_code->AdvancedSearch->load();
        $this->bank_number->AdvancedSearch->load();
        $this->bank_name->AdvancedSearch->load();
        $this->position_id->AdvancedSearch->load();
        $this->status_id->AdvancedSearch->load();
        $this->skill_id->AdvancedSearch->load();
        $this->office_id->AdvancedSearch->load();
        $this->hire_date->AdvancedSearch->load();
        $this->termination_date->AdvancedSearch->load();
        $this->user_level->AdvancedSearch->load();
        $this->technical_skill->AdvancedSearch->load();
        $this->about_me->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("welcome");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("employeelist"), "", $this->TableVar, true);
        $pageId = "search";
        $Breadcrumb->add("search", $pageId, $url);
    }

    // Set up multi pages
    protected function setupMultiPages()
    {
        $pages = new SubPages();
        $pages->Style = "tabs";
        $pages->add(0);
        $pages->add(1);
        $pages->add(2);
        $pages->add(3);
        $pages->add(4);
        $this->MultiPages = $pages;
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
                case "x_religion":
                    break;
                case "x_city_id":
                    break;
                case "x_position_id":
                    break;
                case "x_status_id":
                    break;
                case "x_skill_id":
                    break;
                case "x_office_id":
                    break;
                case "x_user_level":
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
