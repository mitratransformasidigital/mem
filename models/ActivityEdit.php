<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ActivityEdit extends Activity
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'activity';

    // Page object name
    public $PageObjName = "ActivityEdit";

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

        // Table object (activity)
        if (!isset($GLOBALS["activity"]) || get_class($GLOBALS["activity"]) == PROJECT_NAMESPACE . "activity") {
            $GLOBALS["activity"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'activity');
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
                $doc = new $class(Container("activity"));
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
                    if ($pageName == "activityview") {
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
            $key .= @$ar['activity_id'];
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
            $this->activity_id->Visible = false;
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
    public $FormClassName = "ew-horizontal ew-form ew-edit-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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
        $this->activity_id->setVisibility();
        $this->employee_username->setVisibility();
        $this->activity_date->setVisibility();
        $this->time_in->setVisibility();
        $this->time_out->setVisibility();
        $this->_action->setVisibility();
        $this->document->setVisibility();
        $this->notes->setVisibility();
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
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";

        // Load record by position
        $loadByPosition = false;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("activity_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->activity_id->setQueryStringValue($keyValue);
                $this->activity_id->setOldValue($this->activity_id->QueryStringValue);
            } elseif (Post("activity_id") !== null) {
                $this->activity_id->setFormValue(Post("activity_id"));
                $this->activity_id->setOldValue($this->activity_id->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action") !== null) {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("activity_id") ?? Route("activity_id")) !== null) {
                    $this->activity_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->activity_id->CurrentValue = null;
                }
                if (!$loadByQuery) {
                    $loadByPosition = true;
                }
            }

            // Set up master detail parameters
            $this->setupMasterParms();

            // Load recordset
            if ($this->isShow()) {
                $this->StartRecord = 1; // Initialize start position
                if ($rs = $this->loadRecordset()) { // Load records
                    $this->TotalRecords = $rs->recordCount(); // Get record count
                }
                if ($this->TotalRecords <= 0) { // No record found
                    if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                    }
                    $this->terminate("activitylist"); // Return to list page
                    return;
                } elseif ($loadByPosition) { // Load record by position
                    $this->setupStartRecord(); // Set up start record position
                    // Point to current record
                    if ($this->StartRecord <= $this->TotalRecords) {
                        $rs->move($this->StartRecord - 1);
                        $loaded = true;
                    }
                } else { // Match key values
                    if ($this->activity_id->CurrentValue != null) {
                        while (!$rs->EOF) {
                            if (SameString($this->activity_id->CurrentValue, $rs->fields['activity_id'])) {
                                $this->setStartRecordNumber($this->StartRecord); // Save record position
                                $loaded = true;
                                break;
                            } else {
                                $this->StartRecord++;
                                $rs->moveNext();
                            }
                        }
                    }
                }

                // Load current row values
                if ($loaded) {
                    $this->loadRowValues($rs);
                }
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$loaded) {
                    if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                    }
                    $this->terminate("activitylist"); // Return to list page
                    return;
                } else {
                }
                break;
            case "update": // Update
                $returnUrl = $this->GetViewUrl();
                if (GetPageName($returnUrl) == "activitylist") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsApi()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();
        $this->Pager = new PrevNextPager($this->StartRecord, $this->DisplayRecords, $this->TotalRecords, "", $this->RecordRange, $this->AutoHidePager);

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
        $this->document->Upload->Index = $CurrentForm->Index;
        $this->document->Upload->uploadFile();
        $this->document->CurrentValue = $this->document->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'activity_id' first before field var 'x_activity_id'
        $val = $CurrentForm->hasValue("activity_id") ? $CurrentForm->getValue("activity_id") : $CurrentForm->getValue("x_activity_id");
        if (!$this->activity_id->IsDetailKey) {
            $this->activity_id->setFormValue($val);
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

        // Check field name 'activity_date' first before field var 'x_activity_date'
        $val = $CurrentForm->hasValue("activity_date") ? $CurrentForm->getValue("activity_date") : $CurrentForm->getValue("x_activity_date");
        if (!$this->activity_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->activity_date->Visible = false; // Disable update for API request
            } else {
                $this->activity_date->setFormValue($val);
            }
            $this->activity_date->CurrentValue = UnFormatDateTime($this->activity_date->CurrentValue, 5);
        }

        // Check field name 'time_in' first before field var 'x_time_in'
        $val = $CurrentForm->hasValue("time_in") ? $CurrentForm->getValue("time_in") : $CurrentForm->getValue("x_time_in");
        if (!$this->time_in->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->time_in->Visible = false; // Disable update for API request
            } else {
                $this->time_in->setFormValue($val);
            }
            $this->time_in->CurrentValue = UnFormatDateTime($this->time_in->CurrentValue, 4);
        }

        // Check field name 'time_out' first before field var 'x_time_out'
        $val = $CurrentForm->hasValue("time_out") ? $CurrentForm->getValue("time_out") : $CurrentForm->getValue("x_time_out");
        if (!$this->time_out->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->time_out->Visible = false; // Disable update for API request
            } else {
                $this->time_out->setFormValue($val);
            }
            $this->time_out->CurrentValue = UnFormatDateTime($this->time_out->CurrentValue, 4);
        }

        // Check field name '_action' first before field var 'x__action'
        $val = $CurrentForm->hasValue("_action") ? $CurrentForm->getValue("_action") : $CurrentForm->getValue("x__action");
        if (!$this->_action->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_action->Visible = false; // Disable update for API request
            } else {
                $this->_action->setFormValue($val);
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
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->activity_id->CurrentValue = $this->activity_id->FormValue;
        $this->employee_username->CurrentValue = $this->employee_username->FormValue;
        $this->activity_date->CurrentValue = $this->activity_date->FormValue;
        $this->activity_date->CurrentValue = UnFormatDateTime($this->activity_date->CurrentValue, 5);
        $this->time_in->CurrentValue = $this->time_in->FormValue;
        $this->time_in->CurrentValue = UnFormatDateTime($this->time_in->CurrentValue, 4);
        $this->time_out->CurrentValue = $this->time_out->FormValue;
        $this->time_out->CurrentValue = UnFormatDateTime($this->time_out->CurrentValue, 4);
        $this->_action->CurrentValue = $this->_action->FormValue;
        $this->notes->CurrentValue = $this->notes->FormValue;
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $stmt = $sql->execute();
        $rs = new Recordset($stmt, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
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
        $this->activity_id->setDbValue($row['activity_id']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->activity_date->setDbValue($row['activity_date']);
        $this->time_in->setDbValue($row['time_in']);
        $this->time_out->setDbValue($row['time_out']);
        $this->_action->setDbValue($row['action']);
        $this->document->Upload->DbValue = $row['document'];
        $this->document->setDbValue($this->document->Upload->DbValue);
        $this->notes->setDbValue($row['notes']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['activity_id'] = null;
        $row['employee_username'] = null;
        $row['activity_date'] = null;
        $row['time_in'] = null;
        $row['time_out'] = null;
        $row['action'] = null;
        $row['document'] = null;
        $row['notes'] = null;
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

        // activity_id

        // employee_username

        // activity_date

        // time_in

        // time_out

        // action

        // document

        // notes
        if ($this->RowType == ROWTYPE_VIEW) {
            // activity_id
            $this->activity_id->ViewValue = $this->activity_id->CurrentValue;
            $this->activity_id->ViewCustomAttributes = "";

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

            // activity_date
            $this->activity_date->ViewValue = $this->activity_date->CurrentValue;
            $this->activity_date->ViewValue = FormatDateTime($this->activity_date->ViewValue, 5);
            $this->activity_date->ViewCustomAttributes = "";

            // time_in
            $this->time_in->ViewValue = $this->time_in->CurrentValue;
            $this->time_in->ViewValue = FormatDateTime($this->time_in->ViewValue, 4);
            $this->time_in->ViewCustomAttributes = "";

            // time_out
            $this->time_out->ViewValue = $this->time_out->CurrentValue;
            $this->time_out->ViewValue = FormatDateTime($this->time_out->ViewValue, 4);
            $this->time_out->ViewCustomAttributes = "";

            // action
            $this->_action->ViewValue = $this->_action->CurrentValue;
            $this->_action->ViewCustomAttributes = "";

            // document
            if (!EmptyValue($this->document->Upload->DbValue)) {
                $this->document->ViewValue = $this->document->Upload->DbValue;
            } else {
                $this->document->ViewValue = "";
            }
            $this->document->ViewCustomAttributes = "";

            // notes
            $this->notes->ViewValue = $this->notes->CurrentValue;
            $this->notes->ViewCustomAttributes = "";

            // activity_id
            $this->activity_id->LinkCustomAttributes = "";
            $this->activity_id->HrefValue = "";
            $this->activity_id->TooltipValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";
            $this->employee_username->TooltipValue = "";

            // activity_date
            $this->activity_date->LinkCustomAttributes = "";
            $this->activity_date->HrefValue = "";
            $this->activity_date->TooltipValue = "";

            // time_in
            $this->time_in->LinkCustomAttributes = "";
            $this->time_in->HrefValue = "";
            $this->time_in->TooltipValue = "";

            // time_out
            $this->time_out->LinkCustomAttributes = "";
            $this->time_out->HrefValue = "";
            $this->time_out->TooltipValue = "";

            // action
            $this->_action->LinkCustomAttributes = "";
            $this->_action->HrefValue = "";
            $this->_action->TooltipValue = "";

            // document
            $this->document->LinkCustomAttributes = "";
            if (!EmptyValue($this->document->Upload->DbValue)) {
                $this->document->HrefValue = GetFileUploadUrl($this->document, $this->document->htmlDecode($this->document->Upload->DbValue)); // Add prefix/suffix
                $this->document->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->document->HrefValue = FullUrl($this->document->HrefValue, "href");
                }
            } else {
                $this->document->HrefValue = "";
            }
            $this->document->ExportHrefValue = $this->document->UploadPath . $this->document->Upload->DbValue;
            $this->document->TooltipValue = "";

            // notes
            $this->notes->LinkCustomAttributes = "";
            $this->notes->HrefValue = "";
            $this->notes->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // activity_id
            $this->activity_id->EditAttrs["class"] = "form-control";
            $this->activity_id->EditCustomAttributes = "";

            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            if ($this->employee_username->getSessionValue() != "") {
                $this->employee_username->CurrentValue = GetForeignKeyValue($this->employee_username->getSessionValue());
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
            } else {
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
            }

            // activity_date
            $this->activity_date->EditAttrs["class"] = "form-control";
            $this->activity_date->EditCustomAttributes = "";
            $this->activity_date->EditValue = HtmlEncode(FormatDateTime($this->activity_date->CurrentValue, 5));
            $this->activity_date->PlaceHolder = RemoveHtml($this->activity_date->caption());

            // time_in
            $this->time_in->EditAttrs["class"] = "form-control";
            $this->time_in->EditCustomAttributes = "";
            $this->time_in->EditValue = HtmlEncode($this->time_in->CurrentValue);
            $this->time_in->PlaceHolder = RemoveHtml($this->time_in->caption());

            // time_out
            $this->time_out->EditAttrs["class"] = "form-control";
            $this->time_out->EditCustomAttributes = "";
            $this->time_out->EditValue = HtmlEncode($this->time_out->CurrentValue);
            $this->time_out->PlaceHolder = RemoveHtml($this->time_out->caption());

            // action
            $this->_action->EditAttrs["class"] = "form-control";
            $this->_action->EditCustomAttributes = "";
            $this->_action->EditValue = HtmlEncode($this->_action->CurrentValue);
            $this->_action->PlaceHolder = RemoveHtml($this->_action->caption());

            // document
            $this->document->EditAttrs["class"] = "form-control";
            $this->document->EditCustomAttributes = "";
            if (!EmptyValue($this->document->Upload->DbValue)) {
                $this->document->EditValue = $this->document->Upload->DbValue;
            } else {
                $this->document->EditValue = "";
            }
            if (!EmptyValue($this->document->CurrentValue)) {
                $this->document->Upload->FileName = $this->document->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->document);
            }

            // notes
            $this->notes->EditAttrs["class"] = "form-control";
            $this->notes->EditCustomAttributes = "";
            $this->notes->EditValue = HtmlEncode($this->notes->CurrentValue);
            $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());

            // Edit refer script

            // activity_id
            $this->activity_id->LinkCustomAttributes = "";
            $this->activity_id->HrefValue = "";
            $this->activity_id->TooltipValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";

            // activity_date
            $this->activity_date->LinkCustomAttributes = "";
            $this->activity_date->HrefValue = "";

            // time_in
            $this->time_in->LinkCustomAttributes = "";
            $this->time_in->HrefValue = "";

            // time_out
            $this->time_out->LinkCustomAttributes = "";
            $this->time_out->HrefValue = "";

            // action
            $this->_action->LinkCustomAttributes = "";
            $this->_action->HrefValue = "";

            // document
            $this->document->LinkCustomAttributes = "";
            if (!EmptyValue($this->document->Upload->DbValue)) {
                $this->document->HrefValue = GetFileUploadUrl($this->document, $this->document->htmlDecode($this->document->Upload->DbValue)); // Add prefix/suffix
                $this->document->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->document->HrefValue = FullUrl($this->document->HrefValue, "href");
                }
            } else {
                $this->document->HrefValue = "";
            }
            $this->document->ExportHrefValue = $this->document->UploadPath . $this->document->Upload->DbValue;

            // notes
            $this->notes->LinkCustomAttributes = "";
            $this->notes->HrefValue = "";
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
        if ($this->activity_id->Required) {
            if (!$this->activity_id->IsDetailKey && EmptyValue($this->activity_id->FormValue)) {
                $this->activity_id->addErrorMessage(str_replace("%s", $this->activity_id->caption(), $this->activity_id->RequiredErrorMessage));
            }
        }
        if ($this->employee_username->Required) {
            if (!$this->employee_username->IsDetailKey && EmptyValue($this->employee_username->FormValue)) {
                $this->employee_username->addErrorMessage(str_replace("%s", $this->employee_username->caption(), $this->employee_username->RequiredErrorMessage));
            }
        }
        if ($this->activity_date->Required) {
            if (!$this->activity_date->IsDetailKey && EmptyValue($this->activity_date->FormValue)) {
                $this->activity_date->addErrorMessage(str_replace("%s", $this->activity_date->caption(), $this->activity_date->RequiredErrorMessage));
            }
        }
        if (!CheckStdDate($this->activity_date->FormValue)) {
            $this->activity_date->addErrorMessage($this->activity_date->getErrorMessage(false));
        }
        if ($this->time_in->Required) {
            if (!$this->time_in->IsDetailKey && EmptyValue($this->time_in->FormValue)) {
                $this->time_in->addErrorMessage(str_replace("%s", $this->time_in->caption(), $this->time_in->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->time_in->FormValue)) {
            $this->time_in->addErrorMessage($this->time_in->getErrorMessage(false));
        }
        if ($this->time_out->Required) {
            if (!$this->time_out->IsDetailKey && EmptyValue($this->time_out->FormValue)) {
                $this->time_out->addErrorMessage(str_replace("%s", $this->time_out->caption(), $this->time_out->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->time_out->FormValue)) {
            $this->time_out->addErrorMessage($this->time_out->getErrorMessage(false));
        }
        if ($this->_action->Required) {
            if (!$this->_action->IsDetailKey && EmptyValue($this->_action->FormValue)) {
                $this->_action->addErrorMessage(str_replace("%s", $this->_action->caption(), $this->_action->RequiredErrorMessage));
            }
        }
        if ($this->document->Required) {
            if ($this->document->Upload->FileName == "" && !$this->document->Upload->KeepFile) {
                $this->document->addErrorMessage(str_replace("%s", $this->document->caption(), $this->document->RequiredErrorMessage));
            }
        }
        if ($this->notes->Required) {
            if (!$this->notes->IsDetailKey && EmptyValue($this->notes->FormValue)) {
                $this->notes->addErrorMessage(str_replace("%s", $this->notes->caption(), $this->notes->RequiredErrorMessage));
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

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssoc($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            $editRow = false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
            $rsnew = [];

            // employee_username
            if ($this->employee_username->getSessionValue() != "") {
                $this->employee_username->ReadOnly = true;
            }
            $this->employee_username->setDbValueDef($rsnew, $this->employee_username->CurrentValue, "", $this->employee_username->ReadOnly);

            // activity_date
            $this->activity_date->setDbValueDef($rsnew, UnFormatDateTime($this->activity_date->CurrentValue, 5), null, $this->activity_date->ReadOnly);

            // time_in
            $this->time_in->setDbValueDef($rsnew, $this->time_in->CurrentValue, null, $this->time_in->ReadOnly);

            // time_out
            $this->time_out->setDbValueDef($rsnew, $this->time_out->CurrentValue, null, $this->time_out->ReadOnly);

            // action
            $this->_action->setDbValueDef($rsnew, $this->_action->CurrentValue, null, $this->_action->ReadOnly);

            // document
            if ($this->document->Visible && !$this->document->ReadOnly && !$this->document->Upload->KeepFile) {
                $this->document->Upload->DbValue = $rsold['document']; // Get original value
                if ($this->document->Upload->FileName == "") {
                    $rsnew['document'] = null;
                } else {
                    $rsnew['document'] = $this->document->Upload->FileName;
                }
            }

            // notes
            $this->notes->setDbValueDef($rsnew, $this->notes->CurrentValue, null, $this->notes->ReadOnly);
            if ($this->document->Visible && !$this->document->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->document->Upload->DbValue) ? [] : [$this->document->htmlDecode($this->document->Upload->DbValue)];
                if (!EmptyValue($this->document->Upload->FileName)) {
                    $newFiles = [$this->document->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->document, $this->document->Upload->Index);
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
                                $file1 = UniqueFilename($this->document->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->document->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->document->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->document->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->document->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->document->setDbValueDef($rsnew, $this->document->Upload->FileName, null, $this->document->ReadOnly);
                }
            }

            // Call Row Updating event
            $updateRow = $this->rowUpdating($rsold, $rsnew);
            if ($updateRow) {
                if (count($rsnew) > 0) {
                    $editRow = $this->update($rsnew, "", $rsold);
                } else {
                    $editRow = true; // No field to update
                }
                if ($editRow) {
                    if ($this->document->Visible && !$this->document->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->document->Upload->DbValue) ? [] : [$this->document->htmlDecode($this->document->Upload->DbValue)];
                        if (!EmptyValue($this->document->Upload->FileName)) {
                            $newFiles = [$this->document->Upload->FileName];
                            $newFiles2 = [$this->document->htmlDecode($rsnew['document'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->document, $this->document->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->document->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->document->oldPhysicalUploadPath() . $oldFile);
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
                    $this->setFailureMessage($Language->phrase("UpdateCancelled"));
                }
                $editRow = false;
            }
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($editRow) {
            // document
            CleanUploadTempPath($this->document, $this->document->Upload->Index);
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
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
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);
            $this->setSessionWhere($this->getDetailFilter());

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("activitylist"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            $pageNo = Get(Config("TABLE_PAGE_NO"));
            if ($pageNo !== null) { // Check for "pageno" parameter first
                if (is_numeric($pageNo)) {
                    $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                    if ($this->StartRecord <= 0) {
                        $this->StartRecord = 1;
                    } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                        $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                    }
                    $this->setStartRecordNumber($this->StartRecord);
                }
            } elseif ($startRec !== null) { // Check for "start" parameter
                $this->StartRecord = $startRec;
                $this->setStartRecordNumber($this->StartRecord);
            }
        }
        $this->StartRecord = $this->getStartRecordNumber();

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || $this->StartRecord == "") { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
            $this->setStartRecordNumber($this->StartRecord);
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
            $this->setStartRecordNumber($this->StartRecord);
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
