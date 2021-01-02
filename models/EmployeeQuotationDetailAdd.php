<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EmployeeQuotationDetailAdd extends EmployeeQuotationDetail
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'employee_quotation_detail';

    // Page object name
    public $PageObjName = "EmployeeQuotationDetailAdd";

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

        // Table object (employee_quotation_detail)
        if (!isset($GLOBALS["employee_quotation_detail"]) || get_class($GLOBALS["employee_quotation_detail"]) == PROJECT_NAMESPACE . "employee_quotation_detail") {
            $GLOBALS["employee_quotation_detail"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'employee_quotation_detail');
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
                $doc = new $class(Container("employee_quotation_detail"));
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
                    if ($pageName == "employeequotationdetailview") {
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
            $key .= @$ar['detail_id'];
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
            $this->detail_id->Visible = false;
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
    public $FormClassName = "ew-horizontal ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $OldRecordset;
    public $CopyRecord;

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
        $this->quotation_id->setVisibility();
        $this->detail_id->Visible = false;
        $this->employee_username->setVisibility();
        $this->rate->setVisibility();
        $this->qty->setVisibility();
        $this->Total->Visible = false;
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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-add-form ew-horizontal";
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("detail_id") ?? Route("detail_id")) !== null) {
                $this->detail_id->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record / default values
        $loaded = $this->loadOldRecord();

        // Set up master/detail parameters
        // NOTE: must be after loadOldRecord to prevent master key values overwritten
        $this->setupMasterParms();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$loaded) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("employeequotationdetaillist"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "employeequotationdetaillist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "employeequotationdetailview") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->quotation_id->CurrentValue = null;
        $this->quotation_id->OldValue = $this->quotation_id->CurrentValue;
        $this->detail_id->CurrentValue = null;
        $this->detail_id->OldValue = $this->detail_id->CurrentValue;
        $this->employee_username->CurrentValue = null;
        $this->employee_username->OldValue = $this->employee_username->CurrentValue;
        $this->rate->CurrentValue = null;
        $this->rate->OldValue = $this->rate->CurrentValue;
        $this->qty->CurrentValue = null;
        $this->qty->OldValue = $this->qty->CurrentValue;
        $this->Total->CurrentValue = null;
        $this->Total->OldValue = $this->Total->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'quotation_id' first before field var 'x_quotation_id'
        $val = $CurrentForm->hasValue("quotation_id") ? $CurrentForm->getValue("quotation_id") : $CurrentForm->getValue("x_quotation_id");
        if (!$this->quotation_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->quotation_id->Visible = false; // Disable update for API request
            } else {
                $this->quotation_id->setFormValue($val);
            }
        }

        // Check field name 'employee_username' first before field var 'x_employee_username'
        $val = $CurrentForm->hasValue("employee_username") ? $CurrentForm->getValue("employee_username") : $CurrentForm->getValue("x_employee_username");
        if (!$this->employee_username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->employee_username->Visible = false; // Disable update for API request
            } else {
                $this->employee_username->setFormValue($val);
            }
        }

        // Check field name 'rate' first before field var 'x_rate'
        $val = $CurrentForm->hasValue("rate") ? $CurrentForm->getValue("rate") : $CurrentForm->getValue("x_rate");
        if (!$this->rate->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->rate->Visible = false; // Disable update for API request
            } else {
                $this->rate->setFormValue($val);
            }
        }

        // Check field name 'qty' first before field var 'x_qty'
        $val = $CurrentForm->hasValue("qty") ? $CurrentForm->getValue("qty") : $CurrentForm->getValue("x_qty");
        if (!$this->qty->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->qty->Visible = false; // Disable update for API request
            } else {
                $this->qty->setFormValue($val);
            }
        }

        // Check field name 'detail_id' first before field var 'x_detail_id'
        $val = $CurrentForm->hasValue("detail_id") ? $CurrentForm->getValue("detail_id") : $CurrentForm->getValue("x_detail_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->quotation_id->CurrentValue = $this->quotation_id->FormValue;
        $this->employee_username->CurrentValue = $this->employee_username->FormValue;
        $this->rate->CurrentValue = $this->rate->FormValue;
        $this->qty->CurrentValue = $this->qty->FormValue;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssoc($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }

        // Call Row Selected event
        $this->rowSelected($row);
        if (!$rs) {
            return;
        }
        $this->quotation_id->setDbValue($row['quotation_id']);
        $this->detail_id->setDbValue($row['detail_id']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->rate->setDbValue($row['rate']);
        $this->qty->setDbValue($row['qty']);
        $this->Total->setDbValue($row['Total']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['quotation_id'] = $this->quotation_id->CurrentValue;
        $row['detail_id'] = $this->detail_id->CurrentValue;
        $row['employee_username'] = $this->employee_username->CurrentValue;
        $row['rate'] = $this->rate->CurrentValue;
        $row['qty'] = $this->qty->CurrentValue;
        $row['Total'] = $this->Total->CurrentValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // quotation_id

        // detail_id

        // employee_username

        // rate

        // qty

        // Total
        if ($this->RowType == ROWTYPE_VIEW) {
            // quotation_id
            $this->quotation_id->ViewValue = $this->quotation_id->CurrentValue;
            $this->quotation_id->ViewCustomAttributes = "";

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

            // rate
            $this->rate->ViewValue = $this->rate->CurrentValue;
            $this->rate->ViewValue = FormatNumber($this->rate->ViewValue, 0, -2, -2, -2);
            $this->rate->CellCssStyle .= "text-align: right;";
            $this->rate->ViewCustomAttributes = "";

            // qty
            $this->qty->ViewValue = $this->qty->CurrentValue;
            $this->qty->ViewValue = FormatNumber($this->qty->ViewValue, 0, -2, -2, -2);
            $this->qty->CellCssStyle .= "text-align: right;";
            $this->qty->ViewCustomAttributes = "";

            // Total
            $this->Total->ViewValue = $this->Total->CurrentValue;
            $this->Total->ViewValue = FormatNumber($this->Total->ViewValue, 0, -2, -2, -2);
            $this->Total->CellCssStyle .= "text-align: right;";
            $this->Total->ViewCustomAttributes = "";

            // quotation_id
            $this->quotation_id->LinkCustomAttributes = "";
            $this->quotation_id->HrefValue = "";
            $this->quotation_id->TooltipValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";
            $this->employee_username->TooltipValue = "";

            // rate
            $this->rate->LinkCustomAttributes = "";
            $this->rate->HrefValue = "";
            $this->rate->TooltipValue = "";

            // qty
            $this->qty->LinkCustomAttributes = "";
            $this->qty->HrefValue = "";
            $this->qty->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // quotation_id
            $this->quotation_id->EditAttrs["class"] = "form-control";
            $this->quotation_id->EditCustomAttributes = "";
            if ($this->quotation_id->getSessionValue() != "") {
                $this->quotation_id->CurrentValue = GetForeignKeyValue($this->quotation_id->getSessionValue());
                $this->quotation_id->ViewValue = $this->quotation_id->CurrentValue;
                $this->quotation_id->ViewCustomAttributes = "";
            } else {
                $this->quotation_id->EditValue = HtmlEncode($this->quotation_id->CurrentValue);
                $this->quotation_id->PlaceHolder = RemoveHtml($this->quotation_id->caption());
            }

            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            $curVal = trim(strval($this->employee_username->CurrentValue));
            if ($curVal != "") {
                $this->employee_username->ViewValue = $this->employee_username->lookupCacheOption($curVal);
            } else {
                $this->employee_username->ViewValue = $this->employee_username->Lookup !== null && is_array($this->employee_username->Lookup->Options) ? $curVal : null;
            }
            if ($this->employee_username->ViewValue !== null) { // Load from cache
                $this->employee_username->EditValue = array_values($this->employee_username->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`employee_username`" . SearchString("=", $this->employee_username->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->employee_username->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->employee_username->EditValue = $arwrk;
            }
            $this->employee_username->PlaceHolder = RemoveHtml($this->employee_username->caption());

            // rate
            $this->rate->EditAttrs["class"] = "form-control";
            $this->rate->EditCustomAttributes = "";
            $this->rate->EditValue = HtmlEncode($this->rate->CurrentValue);
            $this->rate->PlaceHolder = RemoveHtml($this->rate->caption());

            // qty
            $this->qty->EditAttrs["class"] = "form-control";
            $this->qty->EditCustomAttributes = "";
            $this->qty->EditValue = HtmlEncode($this->qty->CurrentValue);
            $this->qty->PlaceHolder = RemoveHtml($this->qty->caption());

            // Add refer script

            // quotation_id
            $this->quotation_id->LinkCustomAttributes = "";
            $this->quotation_id->HrefValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";

            // rate
            $this->rate->LinkCustomAttributes = "";
            $this->rate->HrefValue = "";

            // qty
            $this->qty->LinkCustomAttributes = "";
            $this->qty->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->quotation_id->Required) {
            if (!$this->quotation_id->IsDetailKey && EmptyValue($this->quotation_id->FormValue)) {
                $this->quotation_id->addErrorMessage(str_replace("%s", $this->quotation_id->caption(), $this->quotation_id->RequiredErrorMessage));
            }
        }
        if ($this->employee_username->Required) {
            if (!$this->employee_username->IsDetailKey && EmptyValue($this->employee_username->FormValue)) {
                $this->employee_username->addErrorMessage(str_replace("%s", $this->employee_username->caption(), $this->employee_username->RequiredErrorMessage));
            }
        }
        if ($this->rate->Required) {
            if (!$this->rate->IsDetailKey && EmptyValue($this->rate->FormValue)) {
                $this->rate->addErrorMessage(str_replace("%s", $this->rate->caption(), $this->rate->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->rate->FormValue)) {
            $this->rate->addErrorMessage($this->rate->getErrorMessage(false));
        }
        if ($this->qty->Required) {
            if (!$this->qty->IsDetailKey && EmptyValue($this->qty->FormValue)) {
                $this->qty->addErrorMessage(str_replace("%s", $this->qty->caption(), $this->qty->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->qty->FormValue)) {
            $this->qty->addErrorMessage($this->qty->getErrorMessage(false));
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Check referential integrity for master table 'employee_quotation_detail'
        $validMasterRecord = true;
        $masterFilter = $this->sqlMasterFilter_employee_quotation();
        if (strval($this->quotation_id->CurrentValue) != "") {
            $masterFilter = str_replace("@quotation_id@", AdjustSql($this->quotation_id->CurrentValue, "DB"), $masterFilter);
        } else {
            $validMasterRecord = false;
        }
        if ($validMasterRecord) {
            $rsmaster = Container("employee_quotation")->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "employee_quotation", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // quotation_id
        $this->quotation_id->setDbValueDef($rsnew, $this->quotation_id->CurrentValue, 0, false);

        // employee_username
        $this->employee_username->setDbValueDef($rsnew, $this->employee_username->CurrentValue, "", false);

        // rate
        $this->rate->setDbValueDef($rsnew, $this->rate->CurrentValue, 0, false);

        // qty
        $this->qty->setDbValueDef($rsnew, $this->qty->CurrentValue, 0, false);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($addRow) {
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "employee_quotation") {
                $validMaster = true;
                $masterTbl = Container("employee_quotation");
                if (($parm = Get("fk_quotation_id", Get("quotation_id"))) !== null) {
                    $masterTbl->quotation_id->setQueryStringValue($parm);
                    $this->quotation_id->setQueryStringValue($masterTbl->quotation_id->QueryStringValue);
                    $this->quotation_id->setSessionValue($this->quotation_id->QueryStringValue);
                    if (!is_numeric($masterTbl->quotation_id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "employee_quotation") {
                $validMaster = true;
                $masterTbl = Container("employee_quotation");
                if (($parm = Post("fk_quotation_id", Post("quotation_id"))) !== null) {
                    $masterTbl->quotation_id->setFormValue($parm);
                    $this->quotation_id->setFormValue($masterTbl->quotation_id->FormValue);
                    $this->quotation_id->setSessionValue($this->quotation_id->FormValue);
                    if (!is_numeric($masterTbl->quotation_id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "employee_quotation") {
                if ($this->quotation_id->CurrentValue == "") {
                    $this->quotation_id->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilter(); // Get master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Get detail filter
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("welcome");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("employeequotationdetaillist"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
