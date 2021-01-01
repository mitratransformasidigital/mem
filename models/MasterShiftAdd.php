<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MasterShiftAdd extends MasterShift
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'master_shift';

    // Page object name
    public $PageObjName = "MasterShiftAdd";

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
    public $FormClassName = "ew-horizontal ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $OldRecordset;
    public $CopyRecord;
    public $DetailPages; // Detail pages object

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

        // Set up detail page object
        $this->setupDetailPages();

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
            if (($keyValue = Get("shift_id") ?? Route("shift_id")) !== null) {
                $this->shift_id->setQueryStringValue($keyValue);
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

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Set up detail parameters
        $this->setupDetailParms();

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
                    $this->terminate("mastershiftlist"); // No matching record, return to list
                    return;
                }

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->GetAddUrl();
                    if (GetPageName($returnUrl) == "mastershiftlist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "mastershiftview") {
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

                    // Set up detail parameters
                    $this->setupDetailParms();
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
        $this->shift_id->CurrentValue = null;
        $this->shift_id->OldValue = $this->shift_id->CurrentValue;
        $this->shift_name->CurrentValue = null;
        $this->shift_name->OldValue = $this->shift_name->CurrentValue;
        $this->sunday_time_in->CurrentValue = null;
        $this->sunday_time_in->OldValue = $this->sunday_time_in->CurrentValue;
        $this->sunday_time_out->CurrentValue = null;
        $this->sunday_time_out->OldValue = $this->sunday_time_out->CurrentValue;
        $this->monday_time_in->CurrentValue = '08:00';
        $this->monday_time_out->CurrentValue = '17:00';
        $this->tuesday_time_in->CurrentValue = '08:00';
        $this->tuesday_time_out->CurrentValue = '17:00';
        $this->wednesday_time_in->CurrentValue = '08:00';
        $this->wednesday_time_out->CurrentValue = '17:00';
        $this->thursday_time_in->CurrentValue = '08:00';
        $this->thursday_time_out->CurrentValue = '17:00';
        $this->friday_time_in->CurrentValue = '08:00';
        $this->friday_time_out->CurrentValue = '17:00';
        $this->saturday_time_in->CurrentValue = null;
        $this->saturday_time_in->OldValue = $this->saturday_time_in->CurrentValue;
        $this->saturday_time_out->CurrentValue = null;
        $this->saturday_time_out->OldValue = $this->saturday_time_out->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'shift_name' first before field var 'x_shift_name'
        $val = $CurrentForm->hasValue("shift_name") ? $CurrentForm->getValue("shift_name") : $CurrentForm->getValue("x_shift_name");
        if (!$this->shift_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->shift_name->Visible = false; // Disable update for API request
            } else {
                $this->shift_name->setFormValue($val);
            }
        }

        // Check field name 'sunday_time_in' first before field var 'x_sunday_time_in'
        $val = $CurrentForm->hasValue("sunday_time_in") ? $CurrentForm->getValue("sunday_time_in") : $CurrentForm->getValue("x_sunday_time_in");
        if (!$this->sunday_time_in->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sunday_time_in->Visible = false; // Disable update for API request
            } else {
                $this->sunday_time_in->setFormValue($val);
            }
            $this->sunday_time_in->CurrentValue = UnFormatDateTime($this->sunday_time_in->CurrentValue, 4);
        }

        // Check field name 'sunday_time_out' first before field var 'x_sunday_time_out'
        $val = $CurrentForm->hasValue("sunday_time_out") ? $CurrentForm->getValue("sunday_time_out") : $CurrentForm->getValue("x_sunday_time_out");
        if (!$this->sunday_time_out->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sunday_time_out->Visible = false; // Disable update for API request
            } else {
                $this->sunday_time_out->setFormValue($val);
            }
            $this->sunday_time_out->CurrentValue = UnFormatDateTime($this->sunday_time_out->CurrentValue, 4);
        }

        // Check field name 'monday_time_in' first before field var 'x_monday_time_in'
        $val = $CurrentForm->hasValue("monday_time_in") ? $CurrentForm->getValue("monday_time_in") : $CurrentForm->getValue("x_monday_time_in");
        if (!$this->monday_time_in->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monday_time_in->Visible = false; // Disable update for API request
            } else {
                $this->monday_time_in->setFormValue($val);
            }
            $this->monday_time_in->CurrentValue = UnFormatDateTime($this->monday_time_in->CurrentValue, 4);
        }

        // Check field name 'monday_time_out' first before field var 'x_monday_time_out'
        $val = $CurrentForm->hasValue("monday_time_out") ? $CurrentForm->getValue("monday_time_out") : $CurrentForm->getValue("x_monday_time_out");
        if (!$this->monday_time_out->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->monday_time_out->Visible = false; // Disable update for API request
            } else {
                $this->monday_time_out->setFormValue($val);
            }
            $this->monday_time_out->CurrentValue = UnFormatDateTime($this->monday_time_out->CurrentValue, 4);
        }

        // Check field name 'tuesday_time_in' first before field var 'x_tuesday_time_in'
        $val = $CurrentForm->hasValue("tuesday_time_in") ? $CurrentForm->getValue("tuesday_time_in") : $CurrentForm->getValue("x_tuesday_time_in");
        if (!$this->tuesday_time_in->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tuesday_time_in->Visible = false; // Disable update for API request
            } else {
                $this->tuesday_time_in->setFormValue($val);
            }
            $this->tuesday_time_in->CurrentValue = UnFormatDateTime($this->tuesday_time_in->CurrentValue, 4);
        }

        // Check field name 'tuesday_time_out' first before field var 'x_tuesday_time_out'
        $val = $CurrentForm->hasValue("tuesday_time_out") ? $CurrentForm->getValue("tuesday_time_out") : $CurrentForm->getValue("x_tuesday_time_out");
        if (!$this->tuesday_time_out->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tuesday_time_out->Visible = false; // Disable update for API request
            } else {
                $this->tuesday_time_out->setFormValue($val);
            }
            $this->tuesday_time_out->CurrentValue = UnFormatDateTime($this->tuesday_time_out->CurrentValue, 4);
        }

        // Check field name 'wednesday_time_in' first before field var 'x_wednesday_time_in'
        $val = $CurrentForm->hasValue("wednesday_time_in") ? $CurrentForm->getValue("wednesday_time_in") : $CurrentForm->getValue("x_wednesday_time_in");
        if (!$this->wednesday_time_in->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->wednesday_time_in->Visible = false; // Disable update for API request
            } else {
                $this->wednesday_time_in->setFormValue($val);
            }
            $this->wednesday_time_in->CurrentValue = UnFormatDateTime($this->wednesday_time_in->CurrentValue, 4);
        }

        // Check field name 'wednesday_time_out' first before field var 'x_wednesday_time_out'
        $val = $CurrentForm->hasValue("wednesday_time_out") ? $CurrentForm->getValue("wednesday_time_out") : $CurrentForm->getValue("x_wednesday_time_out");
        if (!$this->wednesday_time_out->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->wednesday_time_out->Visible = false; // Disable update for API request
            } else {
                $this->wednesday_time_out->setFormValue($val);
            }
            $this->wednesday_time_out->CurrentValue = UnFormatDateTime($this->wednesday_time_out->CurrentValue, 4);
        }

        // Check field name 'thursday_time_in' first before field var 'x_thursday_time_in'
        $val = $CurrentForm->hasValue("thursday_time_in") ? $CurrentForm->getValue("thursday_time_in") : $CurrentForm->getValue("x_thursday_time_in");
        if (!$this->thursday_time_in->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->thursday_time_in->Visible = false; // Disable update for API request
            } else {
                $this->thursday_time_in->setFormValue($val);
            }
            $this->thursday_time_in->CurrentValue = UnFormatDateTime($this->thursday_time_in->CurrentValue, 4);
        }

        // Check field name 'thursday_time_out' first before field var 'x_thursday_time_out'
        $val = $CurrentForm->hasValue("thursday_time_out") ? $CurrentForm->getValue("thursday_time_out") : $CurrentForm->getValue("x_thursday_time_out");
        if (!$this->thursday_time_out->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->thursday_time_out->Visible = false; // Disable update for API request
            } else {
                $this->thursday_time_out->setFormValue($val);
            }
            $this->thursday_time_out->CurrentValue = UnFormatDateTime($this->thursday_time_out->CurrentValue, 4);
        }

        // Check field name 'friday_time_in' first before field var 'x_friday_time_in'
        $val = $CurrentForm->hasValue("friday_time_in") ? $CurrentForm->getValue("friday_time_in") : $CurrentForm->getValue("x_friday_time_in");
        if (!$this->friday_time_in->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->friday_time_in->Visible = false; // Disable update for API request
            } else {
                $this->friday_time_in->setFormValue($val);
            }
            $this->friday_time_in->CurrentValue = UnFormatDateTime($this->friday_time_in->CurrentValue, 4);
        }

        // Check field name 'friday_time_out' first before field var 'x_friday_time_out'
        $val = $CurrentForm->hasValue("friday_time_out") ? $CurrentForm->getValue("friday_time_out") : $CurrentForm->getValue("x_friday_time_out");
        if (!$this->friday_time_out->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->friday_time_out->Visible = false; // Disable update for API request
            } else {
                $this->friday_time_out->setFormValue($val);
            }
            $this->friday_time_out->CurrentValue = UnFormatDateTime($this->friday_time_out->CurrentValue, 4);
        }

        // Check field name 'saturday_time_in' first before field var 'x_saturday_time_in'
        $val = $CurrentForm->hasValue("saturday_time_in") ? $CurrentForm->getValue("saturday_time_in") : $CurrentForm->getValue("x_saturday_time_in");
        if (!$this->saturday_time_in->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->saturday_time_in->Visible = false; // Disable update for API request
            } else {
                $this->saturday_time_in->setFormValue($val);
            }
            $this->saturday_time_in->CurrentValue = UnFormatDateTime($this->saturday_time_in->CurrentValue, 4);
        }

        // Check field name 'saturday_time_out' first before field var 'x_saturday_time_out'
        $val = $CurrentForm->hasValue("saturday_time_out") ? $CurrentForm->getValue("saturday_time_out") : $CurrentForm->getValue("x_saturday_time_out");
        if (!$this->saturday_time_out->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->saturday_time_out->Visible = false; // Disable update for API request
            } else {
                $this->saturday_time_out->setFormValue($val);
            }
            $this->saturday_time_out->CurrentValue = UnFormatDateTime($this->saturday_time_out->CurrentValue, 4);
        }

        // Check field name 'shift_id' first before field var 'x_shift_id'
        $val = $CurrentForm->hasValue("shift_id") ? $CurrentForm->getValue("shift_id") : $CurrentForm->getValue("x_shift_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->shift_name->CurrentValue = $this->shift_name->FormValue;
        $this->sunday_time_in->CurrentValue = $this->sunday_time_in->FormValue;
        $this->sunday_time_in->CurrentValue = UnFormatDateTime($this->sunday_time_in->CurrentValue, 4);
        $this->sunday_time_out->CurrentValue = $this->sunday_time_out->FormValue;
        $this->sunday_time_out->CurrentValue = UnFormatDateTime($this->sunday_time_out->CurrentValue, 4);
        $this->monday_time_in->CurrentValue = $this->monday_time_in->FormValue;
        $this->monday_time_in->CurrentValue = UnFormatDateTime($this->monday_time_in->CurrentValue, 4);
        $this->monday_time_out->CurrentValue = $this->monday_time_out->FormValue;
        $this->monday_time_out->CurrentValue = UnFormatDateTime($this->monday_time_out->CurrentValue, 4);
        $this->tuesday_time_in->CurrentValue = $this->tuesday_time_in->FormValue;
        $this->tuesday_time_in->CurrentValue = UnFormatDateTime($this->tuesday_time_in->CurrentValue, 4);
        $this->tuesday_time_out->CurrentValue = $this->tuesday_time_out->FormValue;
        $this->tuesday_time_out->CurrentValue = UnFormatDateTime($this->tuesday_time_out->CurrentValue, 4);
        $this->wednesday_time_in->CurrentValue = $this->wednesday_time_in->FormValue;
        $this->wednesday_time_in->CurrentValue = UnFormatDateTime($this->wednesday_time_in->CurrentValue, 4);
        $this->wednesday_time_out->CurrentValue = $this->wednesday_time_out->FormValue;
        $this->wednesday_time_out->CurrentValue = UnFormatDateTime($this->wednesday_time_out->CurrentValue, 4);
        $this->thursday_time_in->CurrentValue = $this->thursday_time_in->FormValue;
        $this->thursday_time_in->CurrentValue = UnFormatDateTime($this->thursday_time_in->CurrentValue, 4);
        $this->thursday_time_out->CurrentValue = $this->thursday_time_out->FormValue;
        $this->thursday_time_out->CurrentValue = UnFormatDateTime($this->thursday_time_out->CurrentValue, 4);
        $this->friday_time_in->CurrentValue = $this->friday_time_in->FormValue;
        $this->friday_time_in->CurrentValue = UnFormatDateTime($this->friday_time_in->CurrentValue, 4);
        $this->friday_time_out->CurrentValue = $this->friday_time_out->FormValue;
        $this->friday_time_out->CurrentValue = UnFormatDateTime($this->friday_time_out->CurrentValue, 4);
        $this->saturday_time_in->CurrentValue = $this->saturday_time_in->FormValue;
        $this->saturday_time_in->CurrentValue = UnFormatDateTime($this->saturday_time_in->CurrentValue, 4);
        $this->saturday_time_out->CurrentValue = $this->saturday_time_out->FormValue;
        $this->saturday_time_out->CurrentValue = UnFormatDateTime($this->saturday_time_out->CurrentValue, 4);
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
        $this->shift_id->setDbValue($row['shift_id']);
        $this->shift_name->setDbValue($row['shift_name']);
        $this->sunday_time_in->setDbValue($row['sunday_time_in']);
        $this->sunday_time_out->setDbValue($row['sunday_time_out']);
        $this->monday_time_in->setDbValue($row['monday_time_in']);
        $this->monday_time_out->setDbValue($row['monday_time_out']);
        $this->tuesday_time_in->setDbValue($row['tuesday_time_in']);
        $this->tuesday_time_out->setDbValue($row['tuesday_time_out']);
        $this->wednesday_time_in->setDbValue($row['wednesday_time_in']);
        $this->wednesday_time_out->setDbValue($row['wednesday_time_out']);
        $this->thursday_time_in->setDbValue($row['thursday_time_in']);
        $this->thursday_time_out->setDbValue($row['thursday_time_out']);
        $this->friday_time_in->setDbValue($row['friday_time_in']);
        $this->friday_time_out->setDbValue($row['friday_time_out']);
        $this->saturday_time_in->setDbValue($row['saturday_time_in']);
        $this->saturday_time_out->setDbValue($row['saturday_time_out']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['shift_id'] = $this->shift_id->CurrentValue;
        $row['shift_name'] = $this->shift_name->CurrentValue;
        $row['sunday_time_in'] = $this->sunday_time_in->CurrentValue;
        $row['sunday_time_out'] = $this->sunday_time_out->CurrentValue;
        $row['monday_time_in'] = $this->monday_time_in->CurrentValue;
        $row['monday_time_out'] = $this->monday_time_out->CurrentValue;
        $row['tuesday_time_in'] = $this->tuesday_time_in->CurrentValue;
        $row['tuesday_time_out'] = $this->tuesday_time_out->CurrentValue;
        $row['wednesday_time_in'] = $this->wednesday_time_in->CurrentValue;
        $row['wednesday_time_out'] = $this->wednesday_time_out->CurrentValue;
        $row['thursday_time_in'] = $this->thursday_time_in->CurrentValue;
        $row['thursday_time_out'] = $this->thursday_time_out->CurrentValue;
        $row['friday_time_in'] = $this->friday_time_in->CurrentValue;
        $row['friday_time_out'] = $this->friday_time_out->CurrentValue;
        $row['saturday_time_in'] = $this->saturday_time_in->CurrentValue;
        $row['saturday_time_out'] = $this->saturday_time_out->CurrentValue;
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
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // shift_name
            $this->shift_name->EditAttrs["class"] = "form-control";
            $this->shift_name->EditCustomAttributes = "";
            if (!$this->shift_name->Raw) {
                $this->shift_name->CurrentValue = HtmlDecode($this->shift_name->CurrentValue);
            }
            $this->shift_name->EditValue = HtmlEncode($this->shift_name->CurrentValue);
            $this->shift_name->PlaceHolder = RemoveHtml($this->shift_name->caption());

            // sunday_time_in
            $this->sunday_time_in->EditAttrs["class"] = "form-control";
            $this->sunday_time_in->EditCustomAttributes = "";
            $this->sunday_time_in->EditValue = HtmlEncode($this->sunday_time_in->CurrentValue);
            $this->sunday_time_in->PlaceHolder = RemoveHtml($this->sunday_time_in->caption());

            // sunday_time_out
            $this->sunday_time_out->EditAttrs["class"] = "form-control";
            $this->sunday_time_out->EditCustomAttributes = "";
            $this->sunday_time_out->EditValue = HtmlEncode($this->sunday_time_out->CurrentValue);
            $this->sunday_time_out->PlaceHolder = RemoveHtml($this->sunday_time_out->caption());

            // monday_time_in
            $this->monday_time_in->EditAttrs["class"] = "form-control";
            $this->monday_time_in->EditCustomAttributes = "";
            $this->monday_time_in->EditValue = HtmlEncode($this->monday_time_in->CurrentValue);
            $this->monday_time_in->PlaceHolder = RemoveHtml($this->monday_time_in->caption());

            // monday_time_out
            $this->monday_time_out->EditAttrs["class"] = "form-control";
            $this->monday_time_out->EditCustomAttributes = "";
            $this->monday_time_out->EditValue = HtmlEncode($this->monday_time_out->CurrentValue);
            $this->monday_time_out->PlaceHolder = RemoveHtml($this->monday_time_out->caption());

            // tuesday_time_in
            $this->tuesday_time_in->EditAttrs["class"] = "form-control";
            $this->tuesday_time_in->EditCustomAttributes = "";
            $this->tuesday_time_in->EditValue = HtmlEncode($this->tuesday_time_in->CurrentValue);
            $this->tuesday_time_in->PlaceHolder = RemoveHtml($this->tuesday_time_in->caption());

            // tuesday_time_out
            $this->tuesday_time_out->EditAttrs["class"] = "form-control";
            $this->tuesday_time_out->EditCustomAttributes = "";
            $this->tuesday_time_out->EditValue = HtmlEncode($this->tuesday_time_out->CurrentValue);
            $this->tuesday_time_out->PlaceHolder = RemoveHtml($this->tuesday_time_out->caption());

            // wednesday_time_in
            $this->wednesday_time_in->EditAttrs["class"] = "form-control";
            $this->wednesday_time_in->EditCustomAttributes = "";
            $this->wednesday_time_in->EditValue = HtmlEncode($this->wednesday_time_in->CurrentValue);
            $this->wednesday_time_in->PlaceHolder = RemoveHtml($this->wednesday_time_in->caption());

            // wednesday_time_out
            $this->wednesday_time_out->EditAttrs["class"] = "form-control";
            $this->wednesday_time_out->EditCustomAttributes = "";
            $this->wednesday_time_out->EditValue = HtmlEncode($this->wednesday_time_out->CurrentValue);
            $this->wednesday_time_out->PlaceHolder = RemoveHtml($this->wednesday_time_out->caption());

            // thursday_time_in
            $this->thursday_time_in->EditAttrs["class"] = "form-control";
            $this->thursday_time_in->EditCustomAttributes = "";
            $this->thursday_time_in->EditValue = HtmlEncode($this->thursday_time_in->CurrentValue);
            $this->thursday_time_in->PlaceHolder = RemoveHtml($this->thursday_time_in->caption());

            // thursday_time_out
            $this->thursday_time_out->EditAttrs["class"] = "form-control";
            $this->thursday_time_out->EditCustomAttributes = "";
            $this->thursday_time_out->EditValue = HtmlEncode($this->thursday_time_out->CurrentValue);
            $this->thursday_time_out->PlaceHolder = RemoveHtml($this->thursday_time_out->caption());

            // friday_time_in
            $this->friday_time_in->EditAttrs["class"] = "form-control";
            $this->friday_time_in->EditCustomAttributes = "";
            $this->friday_time_in->EditValue = HtmlEncode($this->friday_time_in->CurrentValue);
            $this->friday_time_in->PlaceHolder = RemoveHtml($this->friday_time_in->caption());

            // friday_time_out
            $this->friday_time_out->EditAttrs["class"] = "form-control";
            $this->friday_time_out->EditCustomAttributes = "";
            $this->friday_time_out->EditValue = HtmlEncode($this->friday_time_out->CurrentValue);
            $this->friday_time_out->PlaceHolder = RemoveHtml($this->friday_time_out->caption());

            // saturday_time_in
            $this->saturday_time_in->EditAttrs["class"] = "form-control";
            $this->saturday_time_in->EditCustomAttributes = "";
            $this->saturday_time_in->EditValue = HtmlEncode($this->saturday_time_in->CurrentValue);
            $this->saturday_time_in->PlaceHolder = RemoveHtml($this->saturday_time_in->caption());

            // saturday_time_out
            $this->saturday_time_out->EditAttrs["class"] = "form-control";
            $this->saturday_time_out->EditCustomAttributes = "";
            $this->saturday_time_out->EditValue = HtmlEncode($this->saturday_time_out->CurrentValue);
            $this->saturday_time_out->PlaceHolder = RemoveHtml($this->saturday_time_out->caption());

            // Add refer script

            // shift_name
            $this->shift_name->LinkCustomAttributes = "";
            $this->shift_name->HrefValue = "";

            // sunday_time_in
            $this->sunday_time_in->LinkCustomAttributes = "";
            $this->sunday_time_in->HrefValue = "";

            // sunday_time_out
            $this->sunday_time_out->LinkCustomAttributes = "";
            $this->sunday_time_out->HrefValue = "";

            // monday_time_in
            $this->monday_time_in->LinkCustomAttributes = "";
            $this->monday_time_in->HrefValue = "";

            // monday_time_out
            $this->monday_time_out->LinkCustomAttributes = "";
            $this->monday_time_out->HrefValue = "";

            // tuesday_time_in
            $this->tuesday_time_in->LinkCustomAttributes = "";
            $this->tuesday_time_in->HrefValue = "";

            // tuesday_time_out
            $this->tuesday_time_out->LinkCustomAttributes = "";
            $this->tuesday_time_out->HrefValue = "";

            // wednesday_time_in
            $this->wednesday_time_in->LinkCustomAttributes = "";
            $this->wednesday_time_in->HrefValue = "";

            // wednesday_time_out
            $this->wednesday_time_out->LinkCustomAttributes = "";
            $this->wednesday_time_out->HrefValue = "";

            // thursday_time_in
            $this->thursday_time_in->LinkCustomAttributes = "";
            $this->thursday_time_in->HrefValue = "";

            // thursday_time_out
            $this->thursday_time_out->LinkCustomAttributes = "";
            $this->thursday_time_out->HrefValue = "";

            // friday_time_in
            $this->friday_time_in->LinkCustomAttributes = "";
            $this->friday_time_in->HrefValue = "";

            // friday_time_out
            $this->friday_time_out->LinkCustomAttributes = "";
            $this->friday_time_out->HrefValue = "";

            // saturday_time_in
            $this->saturday_time_in->LinkCustomAttributes = "";
            $this->saturday_time_in->HrefValue = "";

            // saturday_time_out
            $this->saturday_time_out->LinkCustomAttributes = "";
            $this->saturday_time_out->HrefValue = "";
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
        if ($this->shift_name->Required) {
            if (!$this->shift_name->IsDetailKey && EmptyValue($this->shift_name->FormValue)) {
                $this->shift_name->addErrorMessage(str_replace("%s", $this->shift_name->caption(), $this->shift_name->RequiredErrorMessage));
            }
        }
        if ($this->sunday_time_in->Required) {
            if (!$this->sunday_time_in->IsDetailKey && EmptyValue($this->sunday_time_in->FormValue)) {
                $this->sunday_time_in->addErrorMessage(str_replace("%s", $this->sunday_time_in->caption(), $this->sunday_time_in->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->sunday_time_in->FormValue)) {
            $this->sunday_time_in->addErrorMessage($this->sunday_time_in->getErrorMessage(false));
        }
        if ($this->sunday_time_out->Required) {
            if (!$this->sunday_time_out->IsDetailKey && EmptyValue($this->sunday_time_out->FormValue)) {
                $this->sunday_time_out->addErrorMessage(str_replace("%s", $this->sunday_time_out->caption(), $this->sunday_time_out->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->sunday_time_out->FormValue)) {
            $this->sunday_time_out->addErrorMessage($this->sunday_time_out->getErrorMessage(false));
        }
        if ($this->monday_time_in->Required) {
            if (!$this->monday_time_in->IsDetailKey && EmptyValue($this->monday_time_in->FormValue)) {
                $this->monday_time_in->addErrorMessage(str_replace("%s", $this->monday_time_in->caption(), $this->monday_time_in->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->monday_time_in->FormValue)) {
            $this->monday_time_in->addErrorMessage($this->monday_time_in->getErrorMessage(false));
        }
        if ($this->monday_time_out->Required) {
            if (!$this->monday_time_out->IsDetailKey && EmptyValue($this->monday_time_out->FormValue)) {
                $this->monday_time_out->addErrorMessage(str_replace("%s", $this->monday_time_out->caption(), $this->monday_time_out->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->monday_time_out->FormValue)) {
            $this->monday_time_out->addErrorMessage($this->monday_time_out->getErrorMessage(false));
        }
        if ($this->tuesday_time_in->Required) {
            if (!$this->tuesday_time_in->IsDetailKey && EmptyValue($this->tuesday_time_in->FormValue)) {
                $this->tuesday_time_in->addErrorMessage(str_replace("%s", $this->tuesday_time_in->caption(), $this->tuesday_time_in->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->tuesday_time_in->FormValue)) {
            $this->tuesday_time_in->addErrorMessage($this->tuesday_time_in->getErrorMessage(false));
        }
        if ($this->tuesday_time_out->Required) {
            if (!$this->tuesday_time_out->IsDetailKey && EmptyValue($this->tuesday_time_out->FormValue)) {
                $this->tuesday_time_out->addErrorMessage(str_replace("%s", $this->tuesday_time_out->caption(), $this->tuesday_time_out->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->tuesday_time_out->FormValue)) {
            $this->tuesday_time_out->addErrorMessage($this->tuesday_time_out->getErrorMessage(false));
        }
        if ($this->wednesday_time_in->Required) {
            if (!$this->wednesday_time_in->IsDetailKey && EmptyValue($this->wednesday_time_in->FormValue)) {
                $this->wednesday_time_in->addErrorMessage(str_replace("%s", $this->wednesday_time_in->caption(), $this->wednesday_time_in->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->wednesday_time_in->FormValue)) {
            $this->wednesday_time_in->addErrorMessage($this->wednesday_time_in->getErrorMessage(false));
        }
        if ($this->wednesday_time_out->Required) {
            if (!$this->wednesday_time_out->IsDetailKey && EmptyValue($this->wednesday_time_out->FormValue)) {
                $this->wednesday_time_out->addErrorMessage(str_replace("%s", $this->wednesday_time_out->caption(), $this->wednesday_time_out->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->wednesday_time_out->FormValue)) {
            $this->wednesday_time_out->addErrorMessage($this->wednesday_time_out->getErrorMessage(false));
        }
        if ($this->thursday_time_in->Required) {
            if (!$this->thursday_time_in->IsDetailKey && EmptyValue($this->thursday_time_in->FormValue)) {
                $this->thursday_time_in->addErrorMessage(str_replace("%s", $this->thursday_time_in->caption(), $this->thursday_time_in->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->thursday_time_in->FormValue)) {
            $this->thursday_time_in->addErrorMessage($this->thursday_time_in->getErrorMessage(false));
        }
        if ($this->thursday_time_out->Required) {
            if (!$this->thursday_time_out->IsDetailKey && EmptyValue($this->thursday_time_out->FormValue)) {
                $this->thursday_time_out->addErrorMessage(str_replace("%s", $this->thursday_time_out->caption(), $this->thursday_time_out->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->thursday_time_out->FormValue)) {
            $this->thursday_time_out->addErrorMessage($this->thursday_time_out->getErrorMessage(false));
        }
        if ($this->friday_time_in->Required) {
            if (!$this->friday_time_in->IsDetailKey && EmptyValue($this->friday_time_in->FormValue)) {
                $this->friday_time_in->addErrorMessage(str_replace("%s", $this->friday_time_in->caption(), $this->friday_time_in->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->friday_time_in->FormValue)) {
            $this->friday_time_in->addErrorMessage($this->friday_time_in->getErrorMessage(false));
        }
        if ($this->friday_time_out->Required) {
            if (!$this->friday_time_out->IsDetailKey && EmptyValue($this->friday_time_out->FormValue)) {
                $this->friday_time_out->addErrorMessage(str_replace("%s", $this->friday_time_out->caption(), $this->friday_time_out->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->friday_time_out->FormValue)) {
            $this->friday_time_out->addErrorMessage($this->friday_time_out->getErrorMessage(false));
        }
        if ($this->saturday_time_in->Required) {
            if (!$this->saturday_time_in->IsDetailKey && EmptyValue($this->saturday_time_in->FormValue)) {
                $this->saturday_time_in->addErrorMessage(str_replace("%s", $this->saturday_time_in->caption(), $this->saturday_time_in->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->saturday_time_in->FormValue)) {
            $this->saturday_time_in->addErrorMessage($this->saturday_time_in->getErrorMessage(false));
        }
        if ($this->saturday_time_out->Required) {
            if (!$this->saturday_time_out->IsDetailKey && EmptyValue($this->saturday_time_out->FormValue)) {
                $this->saturday_time_out->addErrorMessage(str_replace("%s", $this->saturday_time_out->caption(), $this->saturday_time_out->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->saturday_time_out->FormValue)) {
            $this->saturday_time_out->addErrorMessage($this->saturday_time_out->getErrorMessage(false));
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("MasterHolidayGrid");
        if (in_array("master_holiday", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->validateGridForm();
        }
        $detailPage = Container("EmployeeShiftGrid");
        if (in_array("employee_shift", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->validateGridForm();
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
        if ($this->shift_name->CurrentValue != "") { // Check field with unique index
            $filter = "(`shift_name` = '" . AdjustSql($this->shift_name->CurrentValue, $this->Dbid) . "')";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->shift_name->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->shift_name->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Begin transaction
        if ($this->getCurrentDetailTable() != "") {
            $conn->beginTransaction();
        }

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // shift_name
        $this->shift_name->setDbValueDef($rsnew, $this->shift_name->CurrentValue, "", false);

        // sunday_time_in
        $this->sunday_time_in->setDbValueDef($rsnew, $this->sunday_time_in->CurrentValue, null, false);

        // sunday_time_out
        $this->sunday_time_out->setDbValueDef($rsnew, $this->sunday_time_out->CurrentValue, null, false);

        // monday_time_in
        $this->monday_time_in->setDbValueDef($rsnew, $this->monday_time_in->CurrentValue, null, false);

        // monday_time_out
        $this->monday_time_out->setDbValueDef($rsnew, $this->monday_time_out->CurrentValue, null, false);

        // tuesday_time_in
        $this->tuesday_time_in->setDbValueDef($rsnew, $this->tuesday_time_in->CurrentValue, null, false);

        // tuesday_time_out
        $this->tuesday_time_out->setDbValueDef($rsnew, $this->tuesday_time_out->CurrentValue, null, false);

        // wednesday_time_in
        $this->wednesday_time_in->setDbValueDef($rsnew, $this->wednesday_time_in->CurrentValue, null, false);

        // wednesday_time_out
        $this->wednesday_time_out->setDbValueDef($rsnew, $this->wednesday_time_out->CurrentValue, null, false);

        // thursday_time_in
        $this->thursday_time_in->setDbValueDef($rsnew, $this->thursday_time_in->CurrentValue, null, false);

        // thursday_time_out
        $this->thursday_time_out->setDbValueDef($rsnew, $this->thursday_time_out->CurrentValue, null, false);

        // friday_time_in
        $this->friday_time_in->setDbValueDef($rsnew, $this->friday_time_in->CurrentValue, null, false);

        // friday_time_out
        $this->friday_time_out->setDbValueDef($rsnew, $this->friday_time_out->CurrentValue, null, false);

        // saturday_time_in
        $this->saturday_time_in->setDbValueDef($rsnew, $this->saturday_time_in->CurrentValue, null, false);

        // saturday_time_out
        $this->saturday_time_out->setDbValueDef($rsnew, $this->saturday_time_out->CurrentValue, null, false);

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

        // Add detail records
        if ($addRow) {
            $detailTblVar = explode(",", $this->getCurrentDetailTable());
            $detailPage = Container("MasterHolidayGrid");
            if (in_array("master_holiday", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->shift_id->setSessionValue($this->shift_id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "master_holiday"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->shift_id->setSessionValue(""); // Clear master key if insert failed
                }
            }
            $detailPage = Container("EmployeeShiftGrid");
            if (in_array("employee_shift", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->shift_id->setSessionValue($this->shift_id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "employee_shift"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->shift_id->setSessionValue(""); // Clear master key if insert failed
                }
            }
        }

        // Commit/Rollback transaction
        if ($this->getCurrentDetailTable() != "") {
            if ($addRow) {
                $conn->commit(); // Commit transaction
            } else {
                $conn->rollback(); // Rollback transaction
            }
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

    // Set up detail parms based on QueryString
    protected function setupDetailParms()
    {
        // Get the keys for master table
        $detailTblVar = Get(Config("TABLE_SHOW_DETAIL"));
        if ($detailTblVar !== null) {
            $this->setCurrentDetailTable($detailTblVar);
        } else {
            $detailTblVar = $this->getCurrentDetailTable();
        }
        if ($detailTblVar != "") {
            $detailTblVar = explode(",", $detailTblVar);
            if (in_array("master_holiday", $detailTblVar)) {
                $detailPageObj = Container("MasterHolidayGrid");
                if ($detailPageObj->DetailAdd) {
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->shift_id->IsDetailKey = true;
                    $detailPageObj->shift_id->CurrentValue = $this->shift_id->CurrentValue;
                    $detailPageObj->shift_id->setSessionValue($detailPageObj->shift_id->CurrentValue);
                }
            }
            if (in_array("employee_shift", $detailTblVar)) {
                $detailPageObj = Container("EmployeeShiftGrid");
                if ($detailPageObj->DetailAdd) {
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->shift_id->IsDetailKey = true;
                    $detailPageObj->shift_id->CurrentValue = $this->shift_id->CurrentValue;
                    $detailPageObj->shift_id->setSessionValue($detailPageObj->shift_id->CurrentValue);
                    $detailPageObj->employee_username->setSessionValue(""); // Clear session key
                }
            }
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("top10days");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("mastershiftlist"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
    }

    // Set up detail pages
    protected function setupDetailPages()
    {
        $pages = new SubPages();
        $pages->Style = "tabs";
        $pages->add('master_holiday');
        $pages->add('employee_shift');
        $this->DetailPages = $pages;
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
