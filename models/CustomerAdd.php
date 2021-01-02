<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class CustomerAdd extends Customer
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'customer';

    // Page object name
    public $PageObjName = "CustomerAdd";

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

        // Table object (customer)
        if (!isset($GLOBALS["customer"]) || get_class($GLOBALS["customer"]) == PROJECT_NAMESPACE . "customer") {
            $GLOBALS["customer"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'customer');
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
                $doc = new $class(Container("customer"));
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
                    if ($pageName == "customerview") {
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
            $key .= @$ar['customer_id'];
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
            $this->customer_id->Visible = false;
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
        $this->customer_id->Visible = false;
        $this->customer_name->setVisibility();
        $this->address->setVisibility();
        $this->city_id->setVisibility();
        $this->phone_number->setVisibility();
        $this->contact->setVisibility();
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
        $this->setupLookupOptions($this->city_id);

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
            if (($keyValue = Get("customer_id") ?? Route("customer_id")) !== null) {
                $this->customer_id->setQueryStringValue($keyValue);
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
                    $this->terminate("customerlist"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->GetAddUrl();
                    if (GetPageName($returnUrl) == "customerlist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "customerview") {
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
        $this->customer_id->CurrentValue = null;
        $this->customer_id->OldValue = $this->customer_id->CurrentValue;
        $this->customer_name->CurrentValue = null;
        $this->customer_name->OldValue = $this->customer_name->CurrentValue;
        $this->address->CurrentValue = null;
        $this->address->OldValue = $this->address->CurrentValue;
        $this->city_id->CurrentValue = null;
        $this->city_id->OldValue = $this->city_id->CurrentValue;
        $this->phone_number->CurrentValue = null;
        $this->phone_number->OldValue = $this->phone_number->CurrentValue;
        $this->contact->CurrentValue = null;
        $this->contact->OldValue = $this->contact->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'customer_name' first before field var 'x_customer_name'
        $val = $CurrentForm->hasValue("customer_name") ? $CurrentForm->getValue("customer_name") : $CurrentForm->getValue("x_customer_name");
        if (!$this->customer_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->customer_name->Visible = false; // Disable update for API request
            } else {
                $this->customer_name->setFormValue($val);
            }
        }

        // Check field name 'address' first before field var 'x_address'
        $val = $CurrentForm->hasValue("address") ? $CurrentForm->getValue("address") : $CurrentForm->getValue("x_address");
        if (!$this->address->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->address->Visible = false; // Disable update for API request
            } else {
                $this->address->setFormValue($val);
            }
        }

        // Check field name 'city_id' first before field var 'x_city_id'
        $val = $CurrentForm->hasValue("city_id") ? $CurrentForm->getValue("city_id") : $CurrentForm->getValue("x_city_id");
        if (!$this->city_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->city_id->Visible = false; // Disable update for API request
            } else {
                $this->city_id->setFormValue($val);
            }
        }

        // Check field name 'phone_number' first before field var 'x_phone_number'
        $val = $CurrentForm->hasValue("phone_number") ? $CurrentForm->getValue("phone_number") : $CurrentForm->getValue("x_phone_number");
        if (!$this->phone_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->phone_number->Visible = false; // Disable update for API request
            } else {
                $this->phone_number->setFormValue($val);
            }
        }

        // Check field name 'contact' first before field var 'x_contact'
        $val = $CurrentForm->hasValue("contact") ? $CurrentForm->getValue("contact") : $CurrentForm->getValue("x_contact");
        if (!$this->contact->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->contact->Visible = false; // Disable update for API request
            } else {
                $this->contact->setFormValue($val);
            }
        }

        // Check field name 'customer_id' first before field var 'x_customer_id'
        $val = $CurrentForm->hasValue("customer_id") ? $CurrentForm->getValue("customer_id") : $CurrentForm->getValue("x_customer_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->customer_name->CurrentValue = $this->customer_name->FormValue;
        $this->address->CurrentValue = $this->address->FormValue;
        $this->city_id->CurrentValue = $this->city_id->FormValue;
        $this->phone_number->CurrentValue = $this->phone_number->FormValue;
        $this->contact->CurrentValue = $this->contact->FormValue;
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
        $this->customer_id->setDbValue($row['customer_id']);
        $this->customer_name->setDbValue($row['customer_name']);
        $this->address->setDbValue($row['address']);
        $this->city_id->setDbValue($row['city_id']);
        $this->phone_number->setDbValue($row['phone_number']);
        $this->contact->setDbValue($row['contact']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['customer_id'] = $this->customer_id->CurrentValue;
        $row['customer_name'] = $this->customer_name->CurrentValue;
        $row['address'] = $this->address->CurrentValue;
        $row['city_id'] = $this->city_id->CurrentValue;
        $row['phone_number'] = $this->phone_number->CurrentValue;
        $row['contact'] = $this->contact->CurrentValue;
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

        // customer_id

        // customer_name

        // address

        // city_id

        // phone_number

        // contact
        if ($this->RowType == ROWTYPE_VIEW) {
            // customer_name
            $this->customer_name->ViewValue = $this->customer_name->CurrentValue;
            $this->customer_name->ViewCustomAttributes = "";

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

            // phone_number
            $this->phone_number->ViewValue = $this->phone_number->CurrentValue;
            $this->phone_number->ViewCustomAttributes = "";

            // contact
            $this->contact->ViewValue = $this->contact->CurrentValue;
            $this->contact->ViewCustomAttributes = "";

            // customer_name
            $this->customer_name->LinkCustomAttributes = "";
            $this->customer_name->HrefValue = "";
            $this->customer_name->TooltipValue = "";

            // address
            $this->address->LinkCustomAttributes = "";
            $this->address->HrefValue = "";
            $this->address->TooltipValue = "";

            // city_id
            $this->city_id->LinkCustomAttributes = "";
            $this->city_id->HrefValue = "";
            $this->city_id->TooltipValue = "";

            // phone_number
            $this->phone_number->LinkCustomAttributes = "";
            $this->phone_number->HrefValue = "";
            $this->phone_number->TooltipValue = "";

            // contact
            $this->contact->LinkCustomAttributes = "";
            $this->contact->HrefValue = "";
            $this->contact->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // customer_name
            $this->customer_name->EditAttrs["class"] = "form-control";
            $this->customer_name->EditCustomAttributes = "";
            if (!$this->customer_name->Raw) {
                $this->customer_name->CurrentValue = HtmlDecode($this->customer_name->CurrentValue);
            }
            $this->customer_name->EditValue = HtmlEncode($this->customer_name->CurrentValue);
            $this->customer_name->PlaceHolder = RemoveHtml($this->customer_name->caption());

            // address
            $this->address->EditAttrs["class"] = "form-control";
            $this->address->EditCustomAttributes = "";
            $this->address->EditValue = HtmlEncode($this->address->CurrentValue);
            $this->address->PlaceHolder = RemoveHtml($this->address->caption());

            // city_id
            $this->city_id->EditAttrs["class"] = "form-control";
            $this->city_id->EditCustomAttributes = "";
            if ($this->city_id->getSessionValue() != "") {
                $this->city_id->CurrentValue = GetForeignKeyValue($this->city_id->getSessionValue());
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
            } else {
                $curVal = trim(strval($this->city_id->CurrentValue));
                if ($curVal != "") {
                    $this->city_id->ViewValue = $this->city_id->lookupCacheOption($curVal);
                } else {
                    $this->city_id->ViewValue = $this->city_id->Lookup !== null && is_array($this->city_id->Lookup->Options) ? $curVal : null;
                }
                if ($this->city_id->ViewValue !== null) { // Load from cache
                    $this->city_id->EditValue = array_values($this->city_id->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`city_id`" . SearchString("=", $this->city_id->CurrentValue, DATATYPE_STRING, "");
                    }
                    $sqlWrk = $this->city_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->city_id->EditValue = $arwrk;
                }
                $this->city_id->PlaceHolder = RemoveHtml($this->city_id->caption());
            }

            // phone_number
            $this->phone_number->EditAttrs["class"] = "form-control";
            $this->phone_number->EditCustomAttributes = "";
            if (!$this->phone_number->Raw) {
                $this->phone_number->CurrentValue = HtmlDecode($this->phone_number->CurrentValue);
            }
            $this->phone_number->EditValue = HtmlEncode($this->phone_number->CurrentValue);
            $this->phone_number->PlaceHolder = RemoveHtml($this->phone_number->caption());

            // contact
            $this->contact->EditAttrs["class"] = "form-control";
            $this->contact->EditCustomAttributes = "";
            if (!$this->contact->Raw) {
                $this->contact->CurrentValue = HtmlDecode($this->contact->CurrentValue);
            }
            $this->contact->EditValue = HtmlEncode($this->contact->CurrentValue);
            $this->contact->PlaceHolder = RemoveHtml($this->contact->caption());

            // Add refer script

            // customer_name
            $this->customer_name->LinkCustomAttributes = "";
            $this->customer_name->HrefValue = "";

            // address
            $this->address->LinkCustomAttributes = "";
            $this->address->HrefValue = "";

            // city_id
            $this->city_id->LinkCustomAttributes = "";
            $this->city_id->HrefValue = "";

            // phone_number
            $this->phone_number->LinkCustomAttributes = "";
            $this->phone_number->HrefValue = "";

            // contact
            $this->contact->LinkCustomAttributes = "";
            $this->contact->HrefValue = "";
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
        if ($this->customer_name->Required) {
            if (!$this->customer_name->IsDetailKey && EmptyValue($this->customer_name->FormValue)) {
                $this->customer_name->addErrorMessage(str_replace("%s", $this->customer_name->caption(), $this->customer_name->RequiredErrorMessage));
            }
        }
        if ($this->address->Required) {
            if (!$this->address->IsDetailKey && EmptyValue($this->address->FormValue)) {
                $this->address->addErrorMessage(str_replace("%s", $this->address->caption(), $this->address->RequiredErrorMessage));
            }
        }
        if ($this->city_id->Required) {
            if (!$this->city_id->IsDetailKey && EmptyValue($this->city_id->FormValue)) {
                $this->city_id->addErrorMessage(str_replace("%s", $this->city_id->caption(), $this->city_id->RequiredErrorMessage));
            }
        }
        if ($this->phone_number->Required) {
            if (!$this->phone_number->IsDetailKey && EmptyValue($this->phone_number->FormValue)) {
                $this->phone_number->addErrorMessage(str_replace("%s", $this->phone_number->caption(), $this->phone_number->RequiredErrorMessage));
            }
        }
        if ($this->contact->Required) {
            if (!$this->contact->IsDetailKey && EmptyValue($this->contact->FormValue)) {
                $this->contact->addErrorMessage(str_replace("%s", $this->contact->caption(), $this->contact->RequiredErrorMessage));
            }
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
        if ($this->customer_name->CurrentValue != "") { // Check field with unique index
            $filter = "(`customer_name` = '" . AdjustSql($this->customer_name->CurrentValue, $this->Dbid) . "')";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->customer_name->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->customer_name->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // customer_name
        $this->customer_name->setDbValueDef($rsnew, $this->customer_name->CurrentValue, "", false);

        // address
        $this->address->setDbValueDef($rsnew, $this->address->CurrentValue, "", false);

        // city_id
        $this->city_id->setDbValueDef($rsnew, $this->city_id->CurrentValue, 0, false);

        // phone_number
        $this->phone_number->setDbValueDef($rsnew, $this->phone_number->CurrentValue, "", false);

        // contact
        $this->contact->setDbValueDef($rsnew, $this->contact->CurrentValue, "", false);

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
            if ($masterTblVar == "master_city") {
                $validMaster = true;
                $masterTbl = Container("master_city");
                if (($parm = Get("fk_city_id", Get("city_id"))) !== null) {
                    $masterTbl->city_id->setQueryStringValue($parm);
                    $this->city_id->setQueryStringValue($masterTbl->city_id->QueryStringValue);
                    $this->city_id->setSessionValue($this->city_id->QueryStringValue);
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
            if ($masterTblVar == "master_city") {
                $validMaster = true;
                $masterTbl = Container("master_city");
                if (($parm = Post("fk_city_id", Post("city_id"))) !== null) {
                    $masterTbl->city_id->setFormValue($parm);
                    $this->city_id->setFormValue($masterTbl->city_id->FormValue);
                    $this->city_id->setSessionValue($this->city_id->FormValue);
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
            if ($masterTblVar != "master_city") {
                if ($this->city_id->CurrentValue == "") {
                    $this->city_id->setSessionValue("");
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("customerlist"), "", $this->TableVar, true);
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
                case "x_city_id":
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
