<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MytrainingSearch extends Mytraining
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'mytraining';

    // Page object name
    public $PageObjName = "MytrainingSearch";

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

        // Table object (mytraining)
        if (!isset($GLOBALS["mytraining"]) || get_class($GLOBALS["mytraining"]) == PROJECT_NAMESPACE . "mytraining") {
            $GLOBALS["mytraining"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'mytraining');
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
                $doc = new $class(Container("mytraining"));
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
                    if ($pageName == "mytrainingview") {
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
            $key .= @$ar['training_id'];
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
            $this->training_id->Visible = false;
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
        $this->training_id->setVisibility();
        $this->employee_username->setVisibility();
        $this->training_name->setVisibility();
        $this->training_start->setVisibility();
        $this->training_end->setVisibility();
        $this->training_company->setVisibility();
        $this->certificate_start->setVisibility();
        $this->certificate_end->setVisibility();
        $this->notes->setVisibility();
        $this->training_document->Visible = false;
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
                    $srchStr = "mytraininglist" . "?" . $srchStr;
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
        $this->buildSearchUrl($srchUrl, $this->training_id); // training_id
        $this->buildSearchUrl($srchUrl, $this->employee_username); // employee_username
        $this->buildSearchUrl($srchUrl, $this->training_name); // training_name
        $this->buildSearchUrl($srchUrl, $this->training_start); // training_start
        $this->buildSearchUrl($srchUrl, $this->training_end); // training_end
        $this->buildSearchUrl($srchUrl, $this->training_company); // training_company
        $this->buildSearchUrl($srchUrl, $this->certificate_start); // certificate_start
        $this->buildSearchUrl($srchUrl, $this->certificate_end); // certificate_end
        $this->buildSearchUrl($srchUrl, $this->notes); // notes
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
        if ($this->training_id->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->employee_username->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->training_name->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->training_start->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->training_end->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->training_company->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->certificate_start->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->certificate_end->AdvancedSearch->post()) {
            $hasValue = true;
        }
        if ($this->notes->AdvancedSearch->post()) {
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

        // training_id

        // employee_username

        // training_name

        // training_start

        // training_end

        // training_company

        // certificate_start

        // certificate_end

        // notes

        // training_document
        if ($this->RowType == ROWTYPE_VIEW) {
            // training_id
            $this->training_id->ViewValue = $this->training_id->CurrentValue;
            $this->training_id->ViewCustomAttributes = "";

            // employee_username
            $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
            $this->employee_username->ViewCustomAttributes = "";

            // training_name
            $this->training_name->ViewValue = $this->training_name->CurrentValue;
            $this->training_name->ViewCustomAttributes = "";

            // training_start
            $this->training_start->ViewValue = $this->training_start->CurrentValue;
            $this->training_start->ViewValue = FormatDateTime($this->training_start->ViewValue, 0);
            $this->training_start->ViewCustomAttributes = "";

            // training_end
            $this->training_end->ViewValue = $this->training_end->CurrentValue;
            $this->training_end->ViewValue = FormatDateTime($this->training_end->ViewValue, 0);
            $this->training_end->ViewCustomAttributes = "";

            // training_company
            $this->training_company->ViewValue = $this->training_company->CurrentValue;
            $this->training_company->ViewCustomAttributes = "";

            // certificate_start
            $this->certificate_start->ViewValue = $this->certificate_start->CurrentValue;
            $this->certificate_start->ViewValue = FormatDateTime($this->certificate_start->ViewValue, 0);
            $this->certificate_start->ViewCustomAttributes = "";

            // certificate_end
            $this->certificate_end->ViewValue = $this->certificate_end->CurrentValue;
            $this->certificate_end->ViewValue = FormatDateTime($this->certificate_end->ViewValue, 0);
            $this->certificate_end->ViewCustomAttributes = "";

            // notes
            $this->notes->ViewValue = $this->notes->CurrentValue;
            $this->notes->ViewCustomAttributes = "";

            // training_document
            if (!EmptyValue($this->training_document->Upload->DbValue)) {
                $this->training_document->ViewValue = $this->training_document->Upload->DbValue;
            } else {
                $this->training_document->ViewValue = "";
            }
            $this->training_document->ViewCustomAttributes = "";

            // training_id
            $this->training_id->LinkCustomAttributes = "";
            $this->training_id->HrefValue = "";
            $this->training_id->TooltipValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";
            $this->employee_username->TooltipValue = "";

            // training_name
            $this->training_name->LinkCustomAttributes = "";
            $this->training_name->HrefValue = "";
            $this->training_name->TooltipValue = "";

            // training_start
            $this->training_start->LinkCustomAttributes = "";
            $this->training_start->HrefValue = "";
            $this->training_start->TooltipValue = "";

            // training_end
            $this->training_end->LinkCustomAttributes = "";
            $this->training_end->HrefValue = "";
            $this->training_end->TooltipValue = "";

            // training_company
            $this->training_company->LinkCustomAttributes = "";
            $this->training_company->HrefValue = "";
            $this->training_company->TooltipValue = "";

            // certificate_start
            $this->certificate_start->LinkCustomAttributes = "";
            $this->certificate_start->HrefValue = "";
            $this->certificate_start->TooltipValue = "";

            // certificate_end
            $this->certificate_end->LinkCustomAttributes = "";
            $this->certificate_end->HrefValue = "";
            $this->certificate_end->TooltipValue = "";

            // notes
            $this->notes->LinkCustomAttributes = "";
            $this->notes->HrefValue = "";
            $this->notes->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // training_id
            $this->training_id->EditAttrs["class"] = "form-control";
            $this->training_id->EditCustomAttributes = "";
            $this->training_id->EditValue = HtmlEncode($this->training_id->AdvancedSearch->SearchValue);
            $this->training_id->PlaceHolder = RemoveHtml($this->training_id->caption());

            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            if (!$this->employee_username->Raw) {
                $this->employee_username->AdvancedSearch->SearchValue = HtmlDecode($this->employee_username->AdvancedSearch->SearchValue);
            }
            $this->employee_username->EditValue = HtmlEncode($this->employee_username->AdvancedSearch->SearchValue);
            $curVal = strval($this->employee_username->AdvancedSearch->SearchValue);
            if ($curVal != "") {
                $this->employee_username->EditValue = $this->employee_username->lookupCacheOption($curVal);
                if ($this->employee_username->EditValue === null) { // Lookup from database
                    $filterWrk = "`employee_username`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->employee_username->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->employee_username->Lookup->renderViewRow($rswrk[0]);
                        $this->employee_username->EditValue = $this->employee_username->displayValue($arwrk);
                    } else {
                        $this->employee_username->EditValue = HtmlEncode($this->employee_username->AdvancedSearch->SearchValue);
                    }
                }
            } else {
                $this->employee_username->EditValue = null;
            }
            $this->employee_username->PlaceHolder = RemoveHtml($this->employee_username->caption());

            // training_name
            $this->training_name->EditAttrs["class"] = "form-control";
            $this->training_name->EditCustomAttributes = "";
            if (!$this->training_name->Raw) {
                $this->training_name->AdvancedSearch->SearchValue = HtmlDecode($this->training_name->AdvancedSearch->SearchValue);
            }
            $this->training_name->EditValue = HtmlEncode($this->training_name->AdvancedSearch->SearchValue);
            $this->training_name->PlaceHolder = RemoveHtml($this->training_name->caption());
            $this->training_name->EditAttrs["class"] = "form-control";
            $this->training_name->EditCustomAttributes = "";
            if (!$this->training_name->Raw) {
                $this->training_name->AdvancedSearch->SearchValue2 = HtmlDecode($this->training_name->AdvancedSearch->SearchValue2);
            }
            $this->training_name->EditValue2 = HtmlEncode($this->training_name->AdvancedSearch->SearchValue2);
            $this->training_name->PlaceHolder = RemoveHtml($this->training_name->caption());

            // training_start
            $this->training_start->EditAttrs["class"] = "form-control";
            $this->training_start->EditCustomAttributes = "";
            $this->training_start->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->training_start->AdvancedSearch->SearchValue, 0), 8));
            $this->training_start->PlaceHolder = RemoveHtml($this->training_start->caption());
            $this->training_start->EditAttrs["class"] = "form-control";
            $this->training_start->EditCustomAttributes = "";
            $this->training_start->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->training_start->AdvancedSearch->SearchValue2, 0), 8));
            $this->training_start->PlaceHolder = RemoveHtml($this->training_start->caption());

            // training_end
            $this->training_end->EditAttrs["class"] = "form-control";
            $this->training_end->EditCustomAttributes = "";
            $this->training_end->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->training_end->AdvancedSearch->SearchValue, 0), 8));
            $this->training_end->PlaceHolder = RemoveHtml($this->training_end->caption());
            $this->training_end->EditAttrs["class"] = "form-control";
            $this->training_end->EditCustomAttributes = "";
            $this->training_end->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->training_end->AdvancedSearch->SearchValue2, 0), 8));
            $this->training_end->PlaceHolder = RemoveHtml($this->training_end->caption());

            // training_company
            $this->training_company->EditAttrs["class"] = "form-control";
            $this->training_company->EditCustomAttributes = "";
            if (!$this->training_company->Raw) {
                $this->training_company->AdvancedSearch->SearchValue = HtmlDecode($this->training_company->AdvancedSearch->SearchValue);
            }
            $this->training_company->EditValue = HtmlEncode($this->training_company->AdvancedSearch->SearchValue);
            $this->training_company->PlaceHolder = RemoveHtml($this->training_company->caption());
            $this->training_company->EditAttrs["class"] = "form-control";
            $this->training_company->EditCustomAttributes = "";
            if (!$this->training_company->Raw) {
                $this->training_company->AdvancedSearch->SearchValue2 = HtmlDecode($this->training_company->AdvancedSearch->SearchValue2);
            }
            $this->training_company->EditValue2 = HtmlEncode($this->training_company->AdvancedSearch->SearchValue2);
            $this->training_company->PlaceHolder = RemoveHtml($this->training_company->caption());

            // certificate_start
            $this->certificate_start->EditAttrs["class"] = "form-control";
            $this->certificate_start->EditCustomAttributes = "";
            $this->certificate_start->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->certificate_start->AdvancedSearch->SearchValue, 0), 8));
            $this->certificate_start->PlaceHolder = RemoveHtml($this->certificate_start->caption());
            $this->certificate_start->EditAttrs["class"] = "form-control";
            $this->certificate_start->EditCustomAttributes = "";
            $this->certificate_start->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->certificate_start->AdvancedSearch->SearchValue2, 0), 8));
            $this->certificate_start->PlaceHolder = RemoveHtml($this->certificate_start->caption());

            // certificate_end
            $this->certificate_end->EditAttrs["class"] = "form-control";
            $this->certificate_end->EditCustomAttributes = "";
            $this->certificate_end->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->certificate_end->AdvancedSearch->SearchValue, 0), 8));
            $this->certificate_end->PlaceHolder = RemoveHtml($this->certificate_end->caption());
            $this->certificate_end->EditAttrs["class"] = "form-control";
            $this->certificate_end->EditCustomAttributes = "";
            $this->certificate_end->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->certificate_end->AdvancedSearch->SearchValue2, 0), 8));
            $this->certificate_end->PlaceHolder = RemoveHtml($this->certificate_end->caption());

            // notes
            $this->notes->EditAttrs["class"] = "form-control";
            $this->notes->EditCustomAttributes = "";
            $this->notes->EditValue = HtmlEncode($this->notes->AdvancedSearch->SearchValue);
            $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());
            $this->notes->EditAttrs["class"] = "form-control";
            $this->notes->EditCustomAttributes = "";
            $this->notes->EditValue2 = HtmlEncode($this->notes->AdvancedSearch->SearchValue2);
            $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());
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
        if (!CheckInteger($this->training_id->AdvancedSearch->SearchValue)) {
            $this->training_id->addErrorMessage($this->training_id->getErrorMessage(false));
        }
        if (!CheckDate($this->training_start->AdvancedSearch->SearchValue)) {
            $this->training_start->addErrorMessage($this->training_start->getErrorMessage(false));
        }
        if (!CheckDate($this->training_start->AdvancedSearch->SearchValue2)) {
            $this->training_start->addErrorMessage($this->training_start->getErrorMessage(false));
        }
        if (!CheckDate($this->training_end->AdvancedSearch->SearchValue)) {
            $this->training_end->addErrorMessage($this->training_end->getErrorMessage(false));
        }
        if (!CheckDate($this->training_end->AdvancedSearch->SearchValue2)) {
            $this->training_end->addErrorMessage($this->training_end->getErrorMessage(false));
        }
        if (!CheckDate($this->certificate_start->AdvancedSearch->SearchValue)) {
            $this->certificate_start->addErrorMessage($this->certificate_start->getErrorMessage(false));
        }
        if (!CheckDate($this->certificate_start->AdvancedSearch->SearchValue2)) {
            $this->certificate_start->addErrorMessage($this->certificate_start->getErrorMessage(false));
        }
        if (!CheckDate($this->certificate_end->AdvancedSearch->SearchValue)) {
            $this->certificate_end->addErrorMessage($this->certificate_end->getErrorMessage(false));
        }
        if (!CheckDate($this->certificate_end->AdvancedSearch->SearchValue2)) {
            $this->certificate_end->addErrorMessage($this->certificate_end->getErrorMessage(false));
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
        $this->training_id->AdvancedSearch->load();
        $this->employee_username->AdvancedSearch->load();
        $this->training_name->AdvancedSearch->load();
        $this->training_start->AdvancedSearch->load();
        $this->training_end->AdvancedSearch->load();
        $this->training_company->AdvancedSearch->load();
        $this->certificate_start->AdvancedSearch->load();
        $this->certificate_end->AdvancedSearch->load();
        $this->notes->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("top10days");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("mytraininglist"), "", $this->TableVar, true);
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
