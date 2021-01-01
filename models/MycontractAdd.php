<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MycontractAdd extends Mycontract
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'mycontract';

    // Page object name
    public $PageObjName = "MycontractAdd";

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

        // Table object (mycontract)
        if (!isset($GLOBALS["mycontract"]) || get_class($GLOBALS["mycontract"]) == PROJECT_NAMESPACE . "mycontract") {
            $GLOBALS["mycontract"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'mycontract');
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
                $doc = new $class(Container("mycontract"));
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
                    if ($pageName == "mycontractview") {
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
            $key .= @$ar['contract_id'];
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
            $this->contract_id->Visible = false;
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
        $this->contract_id->Visible = false;
        $this->employee_username->setVisibility();
        $this->salary->setVisibility();
        $this->bonus->setVisibility();
        $this->thr->setVisibility();
        $this->contract_start->setVisibility();
        $this->contract_end->setVisibility();
        $this->office_id->setVisibility();
        $this->notes->setVisibility();
        $this->contract_document->setVisibility();
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
        $this->setupLookupOptions($this->office_id);

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
            if (($keyValue = Get("contract_id") ?? Route("contract_id")) !== null) {
                $this->contract_id->setQueryStringValue($keyValue);
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
                    $this->terminate("mycontractlist"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "mycontractlist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "mycontractview") {
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
        $this->contract_document->Upload->Index = $CurrentForm->Index;
        $this->contract_document->Upload->uploadFile();
        $this->contract_document->CurrentValue = $this->contract_document->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->contract_id->CurrentValue = null;
        $this->contract_id->OldValue = $this->contract_id->CurrentValue;
        $this->employee_username->CurrentValue = CurrentUserName();
        $this->salary->CurrentValue = null;
        $this->salary->OldValue = $this->salary->CurrentValue;
        $this->bonus->CurrentValue = null;
        $this->bonus->OldValue = $this->bonus->CurrentValue;
        $this->thr->CurrentValue = null;
        $this->thr->OldValue = $this->thr->CurrentValue;
        $this->contract_start->CurrentValue = null;
        $this->contract_start->OldValue = $this->contract_start->CurrentValue;
        $this->contract_end->CurrentValue = null;
        $this->contract_end->OldValue = $this->contract_end->CurrentValue;
        $this->office_id->CurrentValue = null;
        $this->office_id->OldValue = $this->office_id->CurrentValue;
        $this->notes->CurrentValue = null;
        $this->notes->OldValue = $this->notes->CurrentValue;
        $this->contract_document->Upload->DbValue = null;
        $this->contract_document->OldValue = $this->contract_document->Upload->DbValue;
        $this->contract_document->CurrentValue = null; // Clear file related field
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'employee_username' first before field var 'x_employee_username'
        $val = $CurrentForm->hasValue("employee_username") ? $CurrentForm->getValue("employee_username") : $CurrentForm->getValue("x_employee_username");
        if (!$this->employee_username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->employee_username->Visible = false; // Disable update for API request
            } else {
                $this->employee_username->setFormValue($val);
            }
        }

        // Check field name 'salary' first before field var 'x_salary'
        $val = $CurrentForm->hasValue("salary") ? $CurrentForm->getValue("salary") : $CurrentForm->getValue("x_salary");
        if (!$this->salary->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->salary->Visible = false; // Disable update for API request
            } else {
                $this->salary->setFormValue($val);
            }
        }

        // Check field name 'bonus' first before field var 'x_bonus'
        $val = $CurrentForm->hasValue("bonus") ? $CurrentForm->getValue("bonus") : $CurrentForm->getValue("x_bonus");
        if (!$this->bonus->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->bonus->Visible = false; // Disable update for API request
            } else {
                $this->bonus->setFormValue($val);
            }
        }

        // Check field name 'thr' first before field var 'x_thr'
        $val = $CurrentForm->hasValue("thr") ? $CurrentForm->getValue("thr") : $CurrentForm->getValue("x_thr");
        if (!$this->thr->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->thr->Visible = false; // Disable update for API request
            } else {
                $this->thr->setFormValue($val);
            }
        }

        // Check field name 'contract_start' first before field var 'x_contract_start'
        $val = $CurrentForm->hasValue("contract_start") ? $CurrentForm->getValue("contract_start") : $CurrentForm->getValue("x_contract_start");
        if (!$this->contract_start->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->contract_start->Visible = false; // Disable update for API request
            } else {
                $this->contract_start->setFormValue($val);
            }
            $this->contract_start->CurrentValue = UnFormatDateTime($this->contract_start->CurrentValue, 0);
        }

        // Check field name 'contract_end' first before field var 'x_contract_end'
        $val = $CurrentForm->hasValue("contract_end") ? $CurrentForm->getValue("contract_end") : $CurrentForm->getValue("x_contract_end");
        if (!$this->contract_end->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->contract_end->Visible = false; // Disable update for API request
            } else {
                $this->contract_end->setFormValue($val);
            }
            $this->contract_end->CurrentValue = UnFormatDateTime($this->contract_end->CurrentValue, 0);
        }

        // Check field name 'office_id' first before field var 'x_office_id'
        $val = $CurrentForm->hasValue("office_id") ? $CurrentForm->getValue("office_id") : $CurrentForm->getValue("x_office_id");
        if (!$this->office_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->office_id->Visible = false; // Disable update for API request
            } else {
                $this->office_id->setFormValue($val);
            }
        }

        // Check field name 'notes' first before field var 'x_notes'
        $val = $CurrentForm->hasValue("notes") ? $CurrentForm->getValue("notes") : $CurrentForm->getValue("x_notes");
        if (!$this->notes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notes->Visible = false; // Disable update for API request
            } else {
                $this->notes->setFormValue($val);
            }
        }

        // Check field name 'contract_id' first before field var 'x_contract_id'
        $val = $CurrentForm->hasValue("contract_id") ? $CurrentForm->getValue("contract_id") : $CurrentForm->getValue("x_contract_id");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->employee_username->CurrentValue = $this->employee_username->FormValue;
        $this->salary->CurrentValue = $this->salary->FormValue;
        $this->bonus->CurrentValue = $this->bonus->FormValue;
        $this->thr->CurrentValue = $this->thr->FormValue;
        $this->contract_start->CurrentValue = $this->contract_start->FormValue;
        $this->contract_start->CurrentValue = UnFormatDateTime($this->contract_start->CurrentValue, 0);
        $this->contract_end->CurrentValue = $this->contract_end->FormValue;
        $this->contract_end->CurrentValue = UnFormatDateTime($this->contract_end->CurrentValue, 0);
        $this->office_id->CurrentValue = $this->office_id->FormValue;
        $this->notes->CurrentValue = $this->notes->FormValue;
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
        $this->contract_id->setDbValue($row['contract_id']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->salary->setDbValue($row['salary']);
        $this->bonus->setDbValue($row['bonus']);
        $this->thr->setDbValue($row['thr']);
        $this->contract_start->setDbValue($row['contract_start']);
        $this->contract_end->setDbValue($row['contract_end']);
        $this->office_id->setDbValue($row['office_id']);
        $this->notes->setDbValue($row['notes']);
        $this->contract_document->Upload->DbValue = $row['contract_document'];
        $this->contract_document->setDbValue($this->contract_document->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['contract_id'] = $this->contract_id->CurrentValue;
        $row['employee_username'] = $this->employee_username->CurrentValue;
        $row['salary'] = $this->salary->CurrentValue;
        $row['bonus'] = $this->bonus->CurrentValue;
        $row['thr'] = $this->thr->CurrentValue;
        $row['contract_start'] = $this->contract_start->CurrentValue;
        $row['contract_end'] = $this->contract_end->CurrentValue;
        $row['office_id'] = $this->office_id->CurrentValue;
        $row['notes'] = $this->notes->CurrentValue;
        $row['contract_document'] = $this->contract_document->Upload->DbValue;
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

        // Convert decimal values if posted back
        if ($this->salary->FormValue == $this->salary->CurrentValue && is_numeric(ConvertToFloatString($this->salary->CurrentValue))) {
            $this->salary->CurrentValue = ConvertToFloatString($this->salary->CurrentValue);
        }

        // Convert decimal values if posted back
        if ($this->bonus->FormValue == $this->bonus->CurrentValue && is_numeric(ConvertToFloatString($this->bonus->CurrentValue))) {
            $this->bonus->CurrentValue = ConvertToFloatString($this->bonus->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // contract_id

        // employee_username

        // salary

        // bonus

        // thr

        // contract_start

        // contract_end

        // office_id

        // notes

        // contract_document
        if ($this->RowType == ROWTYPE_VIEW) {
            // employee_username
            $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
            $this->employee_username->ViewCustomAttributes = "";

            // salary
            $this->salary->ViewValue = $this->salary->CurrentValue;
            $this->salary->ViewValue = FormatNumber($this->salary->ViewValue, 0, -2, -2, -2);
            $this->salary->CellCssStyle .= "text-align: right;";
            $this->salary->ViewCustomAttributes = "";

            // bonus
            $this->bonus->ViewValue = $this->bonus->CurrentValue;
            $this->bonus->ViewValue = FormatNumber($this->bonus->ViewValue, 0, -2, -2, -2);
            $this->bonus->CellCssStyle .= "text-align: right;";
            $this->bonus->ViewCustomAttributes = "";

            // thr
            $this->thr->CellCssStyle .= "text-align: center;";
            $this->thr->ViewCustomAttributes = "";

            // contract_start
            $this->contract_start->ViewValue = $this->contract_start->CurrentValue;
            $this->contract_start->ViewValue = FormatDateTime($this->contract_start->ViewValue, 0);
            $this->contract_start->ViewCustomAttributes = "";

            // contract_end
            $this->contract_end->ViewValue = $this->contract_end->CurrentValue;
            $this->contract_end->ViewValue = FormatDateTime($this->contract_end->ViewValue, 0);
            $this->contract_end->ViewCustomAttributes = "";

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

            // notes
            $this->notes->ViewValue = $this->notes->CurrentValue;
            $this->notes->ViewCustomAttributes = "";

            // contract_document
            if (!EmptyValue($this->contract_document->Upload->DbValue)) {
                $this->contract_document->ViewValue = $this->contract_document->Upload->DbValue;
            } else {
                $this->contract_document->ViewValue = "";
            }
            $this->contract_document->ViewCustomAttributes = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";
            $this->employee_username->TooltipValue = "";

            // salary
            $this->salary->LinkCustomAttributes = "";
            $this->salary->HrefValue = "";
            $this->salary->TooltipValue = "";

            // bonus
            $this->bonus->LinkCustomAttributes = "";
            $this->bonus->HrefValue = "";
            $this->bonus->TooltipValue = "";

            // thr
            $this->thr->LinkCustomAttributes = "";
            $this->thr->HrefValue = "";
            $this->thr->TooltipValue = "";

            // contract_start
            $this->contract_start->LinkCustomAttributes = "";
            $this->contract_start->HrefValue = "";
            $this->contract_start->TooltipValue = "";

            // contract_end
            $this->contract_end->LinkCustomAttributes = "";
            $this->contract_end->HrefValue = "";
            $this->contract_end->TooltipValue = "";

            // office_id
            $this->office_id->LinkCustomAttributes = "";
            $this->office_id->HrefValue = "";
            $this->office_id->TooltipValue = "";

            // notes
            $this->notes->LinkCustomAttributes = "";
            $this->notes->HrefValue = "";
            $this->notes->TooltipValue = "";

            // contract_document
            $this->contract_document->LinkCustomAttributes = "";
            if (!EmptyValue($this->contract_document->Upload->DbValue)) {
                $this->contract_document->HrefValue = GetFileUploadUrl($this->contract_document, $this->contract_document->htmlDecode($this->contract_document->Upload->DbValue)); // Add prefix/suffix
                $this->contract_document->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->contract_document->HrefValue = FullUrl($this->contract_document->HrefValue, "href");
                }
            } else {
                $this->contract_document->HrefValue = "";
            }
            $this->contract_document->ExportHrefValue = $this->contract_document->UploadPath . $this->contract_document->Upload->DbValue;
            $this->contract_document->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            if ($this->employee_username->getSessionValue() != "") {
                $this->employee_username->CurrentValue = GetForeignKeyValue($this->employee_username->getSessionValue());
                $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
                $this->employee_username->ViewCustomAttributes = "";
            } else {
                $this->employee_username->CurrentValue = CurrentUserName();
            }

            // salary
            $this->salary->EditAttrs["class"] = "form-control";
            $this->salary->EditCustomAttributes = "";
            $this->salary->EditValue = HtmlEncode($this->salary->CurrentValue);
            $this->salary->PlaceHolder = RemoveHtml($this->salary->caption());
            if (strval($this->salary->EditValue) != "" && is_numeric($this->salary->EditValue)) {
                $this->salary->EditValue = FormatNumber($this->salary->EditValue, -2, -2, -2, -2);
            }

            // bonus
            $this->bonus->EditAttrs["class"] = "form-control";
            $this->bonus->EditCustomAttributes = "";
            $this->bonus->EditValue = HtmlEncode($this->bonus->CurrentValue);
            $this->bonus->PlaceHolder = RemoveHtml($this->bonus->caption());
            if (strval($this->bonus->EditValue) != "" && is_numeric($this->bonus->EditValue)) {
                $this->bonus->EditValue = FormatNumber($this->bonus->EditValue, -2, -2, -2, -2);
            }

            // thr
            $this->thr->EditCustomAttributes = "";
            $this->thr->PlaceHolder = RemoveHtml($this->thr->caption());

            // contract_start
            $this->contract_start->EditAttrs["class"] = "form-control";
            $this->contract_start->EditCustomAttributes = "";
            $this->contract_start->EditValue = HtmlEncode(FormatDateTime($this->contract_start->CurrentValue, 8));
            $this->contract_start->PlaceHolder = RemoveHtml($this->contract_start->caption());

            // contract_end
            $this->contract_end->EditAttrs["class"] = "form-control";
            $this->contract_end->EditCustomAttributes = "";
            $this->contract_end->EditValue = HtmlEncode(FormatDateTime($this->contract_end->CurrentValue, 8));
            $this->contract_end->PlaceHolder = RemoveHtml($this->contract_end->caption());

            // office_id
            $this->office_id->EditAttrs["class"] = "form-control";
            $this->office_id->EditCustomAttributes = "";
            $curVal = trim(strval($this->office_id->CurrentValue));
            if ($curVal != "") {
                $this->office_id->ViewValue = $this->office_id->lookupCacheOption($curVal);
            } else {
                $this->office_id->ViewValue = $this->office_id->Lookup !== null && is_array($this->office_id->Lookup->Options) ? $curVal : null;
            }
            if ($this->office_id->ViewValue !== null) { // Load from cache
                $this->office_id->EditValue = array_values($this->office_id->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`office_id`" . SearchString("=", $this->office_id->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->office_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->office_id->EditValue = $arwrk;
            }
            $this->office_id->PlaceHolder = RemoveHtml($this->office_id->caption());

            // notes
            $this->notes->EditAttrs["class"] = "form-control";
            $this->notes->EditCustomAttributes = "";
            $this->notes->EditValue = HtmlEncode($this->notes->CurrentValue);
            $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());

            // contract_document
            $this->contract_document->EditAttrs["class"] = "form-control";
            $this->contract_document->EditCustomAttributes = "";
            if (!EmptyValue($this->contract_document->Upload->DbValue)) {
                $this->contract_document->EditValue = $this->contract_document->Upload->DbValue;
            } else {
                $this->contract_document->EditValue = "";
            }
            if (!EmptyValue($this->contract_document->CurrentValue)) {
                $this->contract_document->Upload->FileName = $this->contract_document->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->contract_document);
            }

            // Add refer script

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";

            // salary
            $this->salary->LinkCustomAttributes = "";
            $this->salary->HrefValue = "";

            // bonus
            $this->bonus->LinkCustomAttributes = "";
            $this->bonus->HrefValue = "";

            // thr
            $this->thr->LinkCustomAttributes = "";
            $this->thr->HrefValue = "";

            // contract_start
            $this->contract_start->LinkCustomAttributes = "";
            $this->contract_start->HrefValue = "";

            // contract_end
            $this->contract_end->LinkCustomAttributes = "";
            $this->contract_end->HrefValue = "";

            // office_id
            $this->office_id->LinkCustomAttributes = "";
            $this->office_id->HrefValue = "";

            // notes
            $this->notes->LinkCustomAttributes = "";
            $this->notes->HrefValue = "";

            // contract_document
            $this->contract_document->LinkCustomAttributes = "";
            if (!EmptyValue($this->contract_document->Upload->DbValue)) {
                $this->contract_document->HrefValue = GetFileUploadUrl($this->contract_document, $this->contract_document->htmlDecode($this->contract_document->Upload->DbValue)); // Add prefix/suffix
                $this->contract_document->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->contract_document->HrefValue = FullUrl($this->contract_document->HrefValue, "href");
                }
            } else {
                $this->contract_document->HrefValue = "";
            }
            $this->contract_document->ExportHrefValue = $this->contract_document->UploadPath . $this->contract_document->Upload->DbValue;
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
        if ($this->employee_username->Required) {
            if (!$this->employee_username->IsDetailKey && EmptyValue($this->employee_username->FormValue)) {
                $this->employee_username->addErrorMessage(str_replace("%s", $this->employee_username->caption(), $this->employee_username->RequiredErrorMessage));
            }
        }
        if ($this->salary->Required) {
            if (!$this->salary->IsDetailKey && EmptyValue($this->salary->FormValue)) {
                $this->salary->addErrorMessage(str_replace("%s", $this->salary->caption(), $this->salary->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->salary->FormValue)) {
            $this->salary->addErrorMessage($this->salary->getErrorMessage(false));
        }
        if ($this->bonus->Required) {
            if (!$this->bonus->IsDetailKey && EmptyValue($this->bonus->FormValue)) {
                $this->bonus->addErrorMessage(str_replace("%s", $this->bonus->caption(), $this->bonus->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->bonus->FormValue)) {
            $this->bonus->addErrorMessage($this->bonus->getErrorMessage(false));
        }
        if ($this->thr->Required) {
            if ($this->thr->FormValue == "") {
                $this->thr->addErrorMessage(str_replace("%s", $this->thr->caption(), $this->thr->RequiredErrorMessage));
            }
        }
        if ($this->contract_start->Required) {
            if (!$this->contract_start->IsDetailKey && EmptyValue($this->contract_start->FormValue)) {
                $this->contract_start->addErrorMessage(str_replace("%s", $this->contract_start->caption(), $this->contract_start->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->contract_start->FormValue)) {
            $this->contract_start->addErrorMessage($this->contract_start->getErrorMessage(false));
        }
        if ($this->contract_end->Required) {
            if (!$this->contract_end->IsDetailKey && EmptyValue($this->contract_end->FormValue)) {
                $this->contract_end->addErrorMessage(str_replace("%s", $this->contract_end->caption(), $this->contract_end->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->contract_end->FormValue)) {
            $this->contract_end->addErrorMessage($this->contract_end->getErrorMessage(false));
        }
        if ($this->office_id->Required) {
            if (!$this->office_id->IsDetailKey && EmptyValue($this->office_id->FormValue)) {
                $this->office_id->addErrorMessage(str_replace("%s", $this->office_id->caption(), $this->office_id->RequiredErrorMessage));
            }
        }
        if ($this->notes->Required) {
            if (!$this->notes->IsDetailKey && EmptyValue($this->notes->FormValue)) {
                $this->notes->addErrorMessage(str_replace("%s", $this->notes->caption(), $this->notes->RequiredErrorMessage));
            }
        }
        if ($this->contract_document->Required) {
            if ($this->contract_document->Upload->FileName == "" && !$this->contract_document->Upload->KeepFile) {
                $this->contract_document->addErrorMessage(str_replace("%s", $this->contract_document->caption(), $this->contract_document->RequiredErrorMessage));
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
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // employee_username
        $this->employee_username->setDbValueDef($rsnew, $this->employee_username->CurrentValue, "", false);

        // salary
        $this->salary->setDbValueDef($rsnew, $this->salary->CurrentValue, null, false);

        // bonus
        $this->bonus->setDbValueDef($rsnew, $this->bonus->CurrentValue, null, false);

        // thr
        $tmpBool = $this->thr->CurrentValue;
        if ($tmpBool != "1" && $tmpBool != "0") {
            $tmpBool = !empty($tmpBool) ? "1" : "0";
        }
        $this->thr->setDbValueDef($rsnew, $tmpBool, null, false);

        // contract_start
        $this->contract_start->setDbValueDef($rsnew, UnFormatDateTime($this->contract_start->CurrentValue, 0), CurrentDate(), false);

        // contract_end
        $this->contract_end->setDbValueDef($rsnew, UnFormatDateTime($this->contract_end->CurrentValue, 0), CurrentDate(), false);

        // office_id
        $this->office_id->setDbValueDef($rsnew, $this->office_id->CurrentValue, null, false);

        // notes
        $this->notes->setDbValueDef($rsnew, $this->notes->CurrentValue, null, false);

        // contract_document
        if ($this->contract_document->Visible && !$this->contract_document->Upload->KeepFile) {
            $this->contract_document->Upload->DbValue = ""; // No need to delete old file
            if ($this->contract_document->Upload->FileName == "") {
                $rsnew['contract_document'] = null;
            } else {
                $rsnew['contract_document'] = $this->contract_document->Upload->FileName;
            }
        }
        if ($this->contract_document->Visible && !$this->contract_document->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->contract_document->Upload->DbValue) ? [] : [$this->contract_document->htmlDecode($this->contract_document->Upload->DbValue)];
            if (!EmptyValue($this->contract_document->Upload->FileName)) {
                $newFiles = [$this->contract_document->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->contract_document, $this->contract_document->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->contract_document->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->contract_document->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->contract_document->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->contract_document->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->contract_document->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->contract_document->setDbValueDef($rsnew, $this->contract_document->Upload->FileName, null, false);
            }
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->contract_document->Visible && !$this->contract_document->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->contract_document->Upload->DbValue) ? [] : [$this->contract_document->htmlDecode($this->contract_document->Upload->DbValue)];
                    if (!EmptyValue($this->contract_document->Upload->FileName)) {
                        $newFiles = [$this->contract_document->Upload->FileName];
                        $newFiles2 = [$this->contract_document->htmlDecode($rsnew['contract_document'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->contract_document, $this->contract_document->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->contract_document->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->contract_document->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
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
            // contract_document
            CleanUploadTempPath($this->contract_document, $this->contract_document->Upload->Index);
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
            if ($masterTblVar == "employee") {
                $validMaster = true;
                $masterTbl = Container("employee");
                if (($parm = Get("fk_employee_username", Get("employee_username"))) !== null) {
                    $masterTbl->employee_username->setQueryStringValue($parm);
                    $this->employee_username->setQueryStringValue($masterTbl->employee_username->QueryStringValue);
                    $this->employee_username->setSessionValue($this->employee_username->QueryStringValue);
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "myprofile") {
                $validMaster = true;
                $masterTbl = Container("myprofile");
                if (($parm = Get("fk_employee_username", Get("employee_username"))) !== null) {
                    $masterTbl->employee_username->setQueryStringValue($parm);
                    $this->employee_username->setQueryStringValue($masterTbl->employee_username->QueryStringValue);
                    $this->employee_username->setSessionValue($this->employee_username->QueryStringValue);
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
            if ($masterTblVar == "employee") {
                $validMaster = true;
                $masterTbl = Container("employee");
                if (($parm = Post("fk_employee_username", Post("employee_username"))) !== null) {
                    $masterTbl->employee_username->setFormValue($parm);
                    $this->employee_username->setFormValue($masterTbl->employee_username->FormValue);
                    $this->employee_username->setSessionValue($this->employee_username->FormValue);
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "myprofile") {
                $validMaster = true;
                $masterTbl = Container("myprofile");
                if (($parm = Post("fk_employee_username", Post("employee_username"))) !== null) {
                    $masterTbl->employee_username->setFormValue($parm);
                    $this->employee_username->setFormValue($masterTbl->employee_username->FormValue);
                    $this->employee_username->setSessionValue($this->employee_username->FormValue);
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
            if ($masterTblVar != "employee") {
                if ($this->employee_username->CurrentValue == "") {
                    $this->employee_username->setSessionValue("");
                }
            }
            if ($masterTblVar != "myprofile") {
                if ($this->employee_username->CurrentValue == "") {
                    $this->employee_username->setSessionValue("");
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
        $Breadcrumb = new Breadcrumb("top10days");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("mycontractlist"), "", $this->TableVar, true);
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
                case "x_office_id":
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
