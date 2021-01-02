<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MytimesheetAdd extends Mytimesheet
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'mytimesheet';

    // Page object name
    public $PageObjName = "MytimesheetAdd";

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

        // Table object (mytimesheet)
        if (!isset($GLOBALS["mytimesheet"]) || get_class($GLOBALS["mytimesheet"]) == PROJECT_NAMESPACE . "mytimesheet") {
            $GLOBALS["mytimesheet"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'mytimesheet');
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
                $doc = new $class(Container("mytimesheet"));
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
                    if ($pageName == "mytimesheetview") {
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
        $this->timesheet_id->Visible = false;
        $this->employee_username->setVisibility();
        $this->year->setVisibility();
        $this->month->setVisibility();
        $this->days->setVisibility();
        $this->sick->setVisibility();
        $this->leave->setVisibility();
        $this->permit->setVisibility();
        $this->absence->setVisibility();
        $this->timesheet_doc->setVisibility();
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
            if (($keyValue = Get("timesheet_id") ?? Route("timesheet_id")) !== null) {
                $this->timesheet_id->setQueryStringValue($keyValue);
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
                    $this->terminate("mytimesheetlist"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "mytimesheetlist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "mytimesheetview") {
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
        $this->timesheet_doc->Upload->Index = $CurrentForm->Index;
        $this->timesheet_doc->Upload->uploadFile();
        $this->timesheet_doc->CurrentValue = $this->timesheet_doc->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->timesheet_id->CurrentValue = null;
        $this->timesheet_id->OldValue = $this->timesheet_id->CurrentValue;
        $this->employee_username->CurrentValue = CurrentUserName();
        $this->year->CurrentValue = Date('Y', strtotime('-1 months'));;
        $this->month->CurrentValue = Date('m', strtotime('-1 months'));;
        $this->days->CurrentValue = 20;
        $this->sick->CurrentValue = 0;
        $this->leave->CurrentValue = 0;
        $this->permit->CurrentValue = 0;
        $this->absence->CurrentValue = 0;
        $this->timesheet_doc->Upload->DbValue = null;
        $this->timesheet_doc->OldValue = $this->timesheet_doc->Upload->DbValue;
        $this->timesheet_doc->CurrentValue = null; // Clear file related field
        $this->employee_notes->CurrentValue = null;
        $this->employee_notes->OldValue = $this->employee_notes->CurrentValue;
        $this->company_notes->CurrentValue = null;
        $this->company_notes->OldValue = $this->company_notes->CurrentValue;
        $this->approved->CurrentValue = CurrentUserName();
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

        // Check field name 'year' first before field var 'x_year'
        $val = $CurrentForm->hasValue("year") ? $CurrentForm->getValue("year") : $CurrentForm->getValue("x_year");
        if (!$this->year->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->year->Visible = false; // Disable update for API request
            } else {
                $this->year->setFormValue($val);
            }
        }

        // Check field name 'month' first before field var 'x_month'
        $val = $CurrentForm->hasValue("month") ? $CurrentForm->getValue("month") : $CurrentForm->getValue("x_month");
        if (!$this->month->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->month->Visible = false; // Disable update for API request
            } else {
                $this->month->setFormValue($val);
            }
        }

        // Check field name 'days' first before field var 'x_days'
        $val = $CurrentForm->hasValue("days") ? $CurrentForm->getValue("days") : $CurrentForm->getValue("x_days");
        if (!$this->days->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->days->Visible = false; // Disable update for API request
            } else {
                $this->days->setFormValue($val);
            }
        }

        // Check field name 'sick' first before field var 'x_sick'
        $val = $CurrentForm->hasValue("sick") ? $CurrentForm->getValue("sick") : $CurrentForm->getValue("x_sick");
        if (!$this->sick->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sick->Visible = false; // Disable update for API request
            } else {
                $this->sick->setFormValue($val);
            }
        }

        // Check field name 'leave' first before field var 'x_leave'
        $val = $CurrentForm->hasValue("leave") ? $CurrentForm->getValue("leave") : $CurrentForm->getValue("x_leave");
        if (!$this->leave->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->leave->Visible = false; // Disable update for API request
            } else {
                $this->leave->setFormValue($val);
            }
        }

        // Check field name 'permit' first before field var 'x_permit'
        $val = $CurrentForm->hasValue("permit") ? $CurrentForm->getValue("permit") : $CurrentForm->getValue("x_permit");
        if (!$this->permit->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->permit->Visible = false; // Disable update for API request
            } else {
                $this->permit->setFormValue($val);
            }
        }

        // Check field name 'absence' first before field var 'x_absence'
        $val = $CurrentForm->hasValue("absence") ? $CurrentForm->getValue("absence") : $CurrentForm->getValue("x_absence");
        if (!$this->absence->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->absence->Visible = false; // Disable update for API request
            } else {
                $this->absence->setFormValue($val);
            }
        }

        // Check field name 'employee_notes' first before field var 'x_employee_notes'
        $val = $CurrentForm->hasValue("employee_notes") ? $CurrentForm->getValue("employee_notes") : $CurrentForm->getValue("x_employee_notes");
        if (!$this->employee_notes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->employee_notes->Visible = false; // Disable update for API request
            } else {
                $this->employee_notes->setFormValue($val);
            }
        }

        // Check field name 'company_notes' first before field var 'x_company_notes'
        $val = $CurrentForm->hasValue("company_notes") ? $CurrentForm->getValue("company_notes") : $CurrentForm->getValue("x_company_notes");
        if (!$this->company_notes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->company_notes->Visible = false; // Disable update for API request
            } else {
                $this->company_notes->setFormValue($val);
            }
        }

        // Check field name 'approved' first before field var 'x_approved'
        $val = $CurrentForm->hasValue("approved") ? $CurrentForm->getValue("approved") : $CurrentForm->getValue("x_approved");
        if (!$this->approved->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->approved->Visible = false; // Disable update for API request
            } else {
                $this->approved->setFormValue($val);
            }
        }

        // Check field name 'timesheet_id' first before field var 'x_timesheet_id'
        $val = $CurrentForm->hasValue("timesheet_id") ? $CurrentForm->getValue("timesheet_id") : $CurrentForm->getValue("x_timesheet_id");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->employee_username->CurrentValue = $this->employee_username->FormValue;
        $this->year->CurrentValue = $this->year->FormValue;
        $this->month->CurrentValue = $this->month->FormValue;
        $this->days->CurrentValue = $this->days->FormValue;
        $this->sick->CurrentValue = $this->sick->FormValue;
        $this->leave->CurrentValue = $this->leave->FormValue;
        $this->permit->CurrentValue = $this->permit->FormValue;
        $this->absence->CurrentValue = $this->absence->FormValue;
        $this->employee_notes->CurrentValue = $this->employee_notes->FormValue;
        $this->company_notes->CurrentValue = $this->company_notes->FormValue;
        $this->approved->CurrentValue = $this->approved->FormValue;
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
        $this->timesheet_id->setDbValue($row['timesheet_id']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->year->setDbValue($row['year']);
        $this->month->setDbValue($row['month']);
        $this->days->setDbValue($row['days']);
        $this->sick->setDbValue($row['sick']);
        $this->leave->setDbValue($row['leave']);
        $this->permit->setDbValue($row['permit']);
        $this->absence->setDbValue($row['absence']);
        $this->timesheet_doc->Upload->DbValue = $row['timesheet_doc'];
        $this->timesheet_doc->setDbValue($this->timesheet_doc->Upload->DbValue);
        $this->employee_notes->setDbValue($row['employee_notes']);
        $this->company_notes->setDbValue($row['company_notes']);
        $this->approved->setDbValue($row['approved']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['timesheet_id'] = $this->timesheet_id->CurrentValue;
        $row['employee_username'] = $this->employee_username->CurrentValue;
        $row['year'] = $this->year->CurrentValue;
        $row['month'] = $this->month->CurrentValue;
        $row['days'] = $this->days->CurrentValue;
        $row['sick'] = $this->sick->CurrentValue;
        $row['leave'] = $this->leave->CurrentValue;
        $row['permit'] = $this->permit->CurrentValue;
        $row['absence'] = $this->absence->CurrentValue;
        $row['timesheet_doc'] = $this->timesheet_doc->Upload->DbValue;
        $row['employee_notes'] = $this->employee_notes->CurrentValue;
        $row['company_notes'] = $this->company_notes->CurrentValue;
        $row['approved'] = $this->approved->CurrentValue;
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
            $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
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

            // year
            $this->year->LinkCustomAttributes = "";
            $this->year->HrefValue = "";
            $this->year->TooltipValue = "";

            // month
            $this->month->LinkCustomAttributes = "";
            $this->month->HrefValue = "";
            $this->month->TooltipValue = "";

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

            // timesheet_doc
            $this->timesheet_doc->LinkCustomAttributes = "";
            if (!EmptyValue($this->timesheet_doc->Upload->DbValue)) {
                $this->timesheet_doc->HrefValue = GetFileUploadUrl($this->timesheet_doc, $this->timesheet_doc->htmlDecode($this->timesheet_doc->Upload->DbValue)); // Add prefix/suffix
                $this->timesheet_doc->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->timesheet_doc->HrefValue = FullUrl($this->timesheet_doc->HrefValue, "href");
                }
            } else {
                $this->timesheet_doc->HrefValue = "";
            }
            $this->timesheet_doc->ExportHrefValue = $this->timesheet_doc->UploadPath . $this->timesheet_doc->Upload->DbValue;
            $this->timesheet_doc->TooltipValue = "";

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

            // year
            $this->year->EditAttrs["class"] = "form-control";
            $this->year->EditCustomAttributes = "";
            $this->year->EditValue = $this->year->options(true);
            $this->year->PlaceHolder = RemoveHtml($this->year->caption());

            // month
            $this->month->EditAttrs["class"] = "form-control";
            $this->month->EditCustomAttributes = "";
            $this->month->EditValue = $this->month->options(true);
            $this->month->PlaceHolder = RemoveHtml($this->month->caption());

            // days
            $this->days->EditAttrs["class"] = "form-control";
            $this->days->EditCustomAttributes = "";
            $this->days->EditValue = HtmlEncode($this->days->CurrentValue);
            $this->days->PlaceHolder = RemoveHtml($this->days->caption());

            // sick
            $this->sick->EditAttrs["class"] = "form-control";
            $this->sick->EditCustomAttributes = "";
            $this->sick->EditValue = HtmlEncode($this->sick->CurrentValue);
            $this->sick->PlaceHolder = RemoveHtml($this->sick->caption());

            // leave
            $this->leave->EditAttrs["class"] = "form-control";
            $this->leave->EditCustomAttributes = "";
            $this->leave->EditValue = HtmlEncode($this->leave->CurrentValue);
            $this->leave->PlaceHolder = RemoveHtml($this->leave->caption());

            // permit
            $this->permit->EditAttrs["class"] = "form-control";
            $this->permit->EditCustomAttributes = "";
            $this->permit->EditValue = HtmlEncode($this->permit->CurrentValue);
            $this->permit->PlaceHolder = RemoveHtml($this->permit->caption());

            // absence
            $this->absence->EditAttrs["class"] = "form-control";
            $this->absence->EditCustomAttributes = "";
            $this->absence->EditValue = HtmlEncode($this->absence->CurrentValue);
            $this->absence->PlaceHolder = RemoveHtml($this->absence->caption());

            // timesheet_doc
            $this->timesheet_doc->EditAttrs["class"] = "form-control";
            $this->timesheet_doc->EditCustomAttributes = "";
            if (!EmptyValue($this->timesheet_doc->Upload->DbValue)) {
                $this->timesheet_doc->EditValue = $this->timesheet_doc->Upload->DbValue;
            } else {
                $this->timesheet_doc->EditValue = "";
            }
            if (!EmptyValue($this->timesheet_doc->CurrentValue)) {
                $this->timesheet_doc->Upload->FileName = $this->timesheet_doc->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->timesheet_doc);
            }

            // employee_notes
            $this->employee_notes->EditAttrs["class"] = "form-control";
            $this->employee_notes->EditCustomAttributes = "";
            $this->employee_notes->EditValue = HtmlEncode($this->employee_notes->CurrentValue);
            $this->employee_notes->PlaceHolder = RemoveHtml($this->employee_notes->caption());

            // company_notes
            $this->company_notes->EditAttrs["class"] = "form-control";
            $this->company_notes->EditCustomAttributes = "";
            $this->company_notes->EditValue = HtmlEncode($this->company_notes->CurrentValue);
            $this->company_notes->PlaceHolder = RemoveHtml($this->company_notes->caption());

            // approved
            $this->approved->EditAttrs["class"] = "form-control";
            $this->approved->EditCustomAttributes = "";
            $this->approved->EditValue = $this->approved->options(true);
            $this->approved->PlaceHolder = RemoveHtml($this->approved->caption());

            // Add refer script

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";

            // year
            $this->year->LinkCustomAttributes = "";
            $this->year->HrefValue = "";

            // month
            $this->month->LinkCustomAttributes = "";
            $this->month->HrefValue = "";

            // days
            $this->days->LinkCustomAttributes = "";
            $this->days->HrefValue = "";

            // sick
            $this->sick->LinkCustomAttributes = "";
            $this->sick->HrefValue = "";

            // leave
            $this->leave->LinkCustomAttributes = "";
            $this->leave->HrefValue = "";

            // permit
            $this->permit->LinkCustomAttributes = "";
            $this->permit->HrefValue = "";

            // absence
            $this->absence->LinkCustomAttributes = "";
            $this->absence->HrefValue = "";

            // timesheet_doc
            $this->timesheet_doc->LinkCustomAttributes = "";
            if (!EmptyValue($this->timesheet_doc->Upload->DbValue)) {
                $this->timesheet_doc->HrefValue = GetFileUploadUrl($this->timesheet_doc, $this->timesheet_doc->htmlDecode($this->timesheet_doc->Upload->DbValue)); // Add prefix/suffix
                $this->timesheet_doc->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->timesheet_doc->HrefValue = FullUrl($this->timesheet_doc->HrefValue, "href");
                }
            } else {
                $this->timesheet_doc->HrefValue = "";
            }
            $this->timesheet_doc->ExportHrefValue = $this->timesheet_doc->UploadPath . $this->timesheet_doc->Upload->DbValue;

            // employee_notes
            $this->employee_notes->LinkCustomAttributes = "";
            $this->employee_notes->HrefValue = "";

            // company_notes
            $this->company_notes->LinkCustomAttributes = "";
            $this->company_notes->HrefValue = "";

            // approved
            $this->approved->LinkCustomAttributes = "";
            $this->approved->HrefValue = "";
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
        if ($this->year->Required) {
            if (!$this->year->IsDetailKey && EmptyValue($this->year->FormValue)) {
                $this->year->addErrorMessage(str_replace("%s", $this->year->caption(), $this->year->RequiredErrorMessage));
            }
        }
        if ($this->month->Required) {
            if (!$this->month->IsDetailKey && EmptyValue($this->month->FormValue)) {
                $this->month->addErrorMessage(str_replace("%s", $this->month->caption(), $this->month->RequiredErrorMessage));
            }
        }
        if ($this->days->Required) {
            if (!$this->days->IsDetailKey && EmptyValue($this->days->FormValue)) {
                $this->days->addErrorMessage(str_replace("%s", $this->days->caption(), $this->days->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->days->FormValue)) {
            $this->days->addErrorMessage($this->days->getErrorMessage(false));
        }
        if ($this->sick->Required) {
            if (!$this->sick->IsDetailKey && EmptyValue($this->sick->FormValue)) {
                $this->sick->addErrorMessage(str_replace("%s", $this->sick->caption(), $this->sick->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->sick->FormValue)) {
            $this->sick->addErrorMessage($this->sick->getErrorMessage(false));
        }
        if ($this->leave->Required) {
            if (!$this->leave->IsDetailKey && EmptyValue($this->leave->FormValue)) {
                $this->leave->addErrorMessage(str_replace("%s", $this->leave->caption(), $this->leave->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->leave->FormValue)) {
            $this->leave->addErrorMessage($this->leave->getErrorMessage(false));
        }
        if ($this->permit->Required) {
            if (!$this->permit->IsDetailKey && EmptyValue($this->permit->FormValue)) {
                $this->permit->addErrorMessage(str_replace("%s", $this->permit->caption(), $this->permit->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->permit->FormValue)) {
            $this->permit->addErrorMessage($this->permit->getErrorMessage(false));
        }
        if ($this->absence->Required) {
            if (!$this->absence->IsDetailKey && EmptyValue($this->absence->FormValue)) {
                $this->absence->addErrorMessage(str_replace("%s", $this->absence->caption(), $this->absence->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->absence->FormValue)) {
            $this->absence->addErrorMessage($this->absence->getErrorMessage(false));
        }
        if ($this->timesheet_doc->Required) {
            if ($this->timesheet_doc->Upload->FileName == "" && !$this->timesheet_doc->Upload->KeepFile) {
                $this->timesheet_doc->addErrorMessage(str_replace("%s", $this->timesheet_doc->caption(), $this->timesheet_doc->RequiredErrorMessage));
            }
        }
        if ($this->employee_notes->Required) {
            if (!$this->employee_notes->IsDetailKey && EmptyValue($this->employee_notes->FormValue)) {
                $this->employee_notes->addErrorMessage(str_replace("%s", $this->employee_notes->caption(), $this->employee_notes->RequiredErrorMessage));
            }
        }
        if ($this->company_notes->Required) {
            if (!$this->company_notes->IsDetailKey && EmptyValue($this->company_notes->FormValue)) {
                $this->company_notes->addErrorMessage(str_replace("%s", $this->company_notes->caption(), $this->company_notes->RequiredErrorMessage));
            }
        }
        if ($this->approved->Required) {
            if (!$this->approved->IsDetailKey && EmptyValue($this->approved->FormValue)) {
                $this->approved->addErrorMessage(str_replace("%s", $this->approved->caption(), $this->approved->RequiredErrorMessage));
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

        // year
        $this->year->setDbValueDef($rsnew, $this->year->CurrentValue, 0, false);

        // month
        $this->month->setDbValueDef($rsnew, $this->month->CurrentValue, 0, false);

        // days
        $this->days->setDbValueDef($rsnew, $this->days->CurrentValue, null, false);

        // sick
        $this->sick->setDbValueDef($rsnew, $this->sick->CurrentValue, null, false);

        // leave
        $this->leave->setDbValueDef($rsnew, $this->leave->CurrentValue, null, false);

        // permit
        $this->permit->setDbValueDef($rsnew, $this->permit->CurrentValue, null, false);

        // absence
        $this->absence->setDbValueDef($rsnew, $this->absence->CurrentValue, null, false);

        // timesheet_doc
        if ($this->timesheet_doc->Visible && !$this->timesheet_doc->Upload->KeepFile) {
            $this->timesheet_doc->Upload->DbValue = ""; // No need to delete old file
            if ($this->timesheet_doc->Upload->FileName == "") {
                $rsnew['timesheet_doc'] = null;
            } else {
                $rsnew['timesheet_doc'] = $this->timesheet_doc->Upload->FileName;
            }
        }

        // employee_notes
        $this->employee_notes->setDbValueDef($rsnew, $this->employee_notes->CurrentValue, null, false);

        // company_notes
        $this->company_notes->setDbValueDef($rsnew, $this->company_notes->CurrentValue, null, false);

        // approved
        $this->approved->setDbValueDef($rsnew, $this->approved->CurrentValue, null, false);
        if ($this->timesheet_doc->Visible && !$this->timesheet_doc->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->timesheet_doc->Upload->DbValue) ? [] : [$this->timesheet_doc->htmlDecode($this->timesheet_doc->Upload->DbValue)];
            if (!EmptyValue($this->timesheet_doc->Upload->FileName)) {
                $newFiles = [$this->timesheet_doc->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->timesheet_doc, $this->timesheet_doc->Upload->Index);
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
                            $file1 = UniqueFilename($this->timesheet_doc->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->timesheet_doc->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->timesheet_doc->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->timesheet_doc->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->timesheet_doc->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->timesheet_doc->setDbValueDef($rsnew, $this->timesheet_doc->Upload->FileName, null, false);
            }
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->timesheet_doc->Visible && !$this->timesheet_doc->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->timesheet_doc->Upload->DbValue) ? [] : [$this->timesheet_doc->htmlDecode($this->timesheet_doc->Upload->DbValue)];
                    if (!EmptyValue($this->timesheet_doc->Upload->FileName)) {
                        $newFiles = [$this->timesheet_doc->Upload->FileName];
                        $newFiles2 = [$this->timesheet_doc->htmlDecode($rsnew['timesheet_doc'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->timesheet_doc, $this->timesheet_doc->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->timesheet_doc->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->timesheet_doc->oldPhysicalUploadPath() . $oldFile);
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
            // timesheet_doc
            CleanUploadTempPath($this->timesheet_doc, $this->timesheet_doc->Upload->Index);
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
        $Breadcrumb = new Breadcrumb("welcome");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("mytimesheetlist"), "", $this->TableVar, true);
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
