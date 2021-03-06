<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MytrainingEdit extends Mytraining
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'mytraining';

    // Page object name
    public $PageObjName = "MytrainingEdit";

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
        $this->training_id->setVisibility();
        $this->employee_username->setVisibility();
        $this->training_name->setVisibility();
        $this->training_start->setVisibility();
        $this->training_end->setVisibility();
        $this->training_company->setVisibility();
        $this->certificate_start->setVisibility();
        $this->certificate_end->setVisibility();
        $this->notes->setVisibility();
        $this->training_document->setVisibility();
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
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";

        // Load record by position
        $loadByPosition = false;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("training_id") ?? Key(0) ?? Route(2)) !== null) {
                $this->training_id->setQueryStringValue($keyValue);
                $this->training_id->setOldValue($this->training_id->QueryStringValue);
            } elseif (Post("training_id") !== null) {
                $this->training_id->setFormValue(Post("training_id"));
                $this->training_id->setOldValue($this->training_id->FormValue);
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
                if (($keyValue = Get("training_id") ?? Route("training_id")) !== null) {
                    $this->training_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->training_id->CurrentValue = null;
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
                    $this->terminate("mytraininglist"); // Return to list page
                    return;
                } elseif ($loadByPosition) { // Load record by position
                    $this->setupStartRecord(); // Set up start record position
                    // Point to current record
                    if ($this->StartRecord <= $this->TotalRecords) {
                        $rs->move($this->StartRecord - 1);
                        $loaded = true;
                    }
                } else { // Match key values
                    if ($this->training_id->CurrentValue != null) {
                        while (!$rs->EOF) {
                            if (SameString($this->training_id->CurrentValue, $rs->fields['training_id'])) {
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
                    $this->terminate("mytraininglist"); // Return to list page
                    return;
                } else {
                }
                break;
            case "update": // Update
                $returnUrl = $this->GetViewUrl();
                if (GetPageName($returnUrl) == "mytraininglist") {
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
        $this->training_document->Upload->Index = $CurrentForm->Index;
        $this->training_document->Upload->uploadFile();
        $this->training_document->CurrentValue = $this->training_document->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'training_id' first before field var 'x_training_id'
        $val = $CurrentForm->hasValue("training_id") ? $CurrentForm->getValue("training_id") : $CurrentForm->getValue("x_training_id");
        if (!$this->training_id->IsDetailKey) {
            $this->training_id->setFormValue($val);
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

        // Check field name 'training_name' first before field var 'x_training_name'
        $val = $CurrentForm->hasValue("training_name") ? $CurrentForm->getValue("training_name") : $CurrentForm->getValue("x_training_name");
        if (!$this->training_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->training_name->Visible = false; // Disable update for API request
            } else {
                $this->training_name->setFormValue($val);
            }
        }

        // Check field name 'training_start' first before field var 'x_training_start'
        $val = $CurrentForm->hasValue("training_start") ? $CurrentForm->getValue("training_start") : $CurrentForm->getValue("x_training_start");
        if (!$this->training_start->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->training_start->Visible = false; // Disable update for API request
            } else {
                $this->training_start->setFormValue($val);
            }
            $this->training_start->CurrentValue = UnFormatDateTime($this->training_start->CurrentValue, 0);
        }

        // Check field name 'training_end' first before field var 'x_training_end'
        $val = $CurrentForm->hasValue("training_end") ? $CurrentForm->getValue("training_end") : $CurrentForm->getValue("x_training_end");
        if (!$this->training_end->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->training_end->Visible = false; // Disable update for API request
            } else {
                $this->training_end->setFormValue($val);
            }
            $this->training_end->CurrentValue = UnFormatDateTime($this->training_end->CurrentValue, 0);
        }

        // Check field name 'training_company' first before field var 'x_training_company'
        $val = $CurrentForm->hasValue("training_company") ? $CurrentForm->getValue("training_company") : $CurrentForm->getValue("x_training_company");
        if (!$this->training_company->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->training_company->Visible = false; // Disable update for API request
            } else {
                $this->training_company->setFormValue($val);
            }
        }

        // Check field name 'certificate_start' first before field var 'x_certificate_start'
        $val = $CurrentForm->hasValue("certificate_start") ? $CurrentForm->getValue("certificate_start") : $CurrentForm->getValue("x_certificate_start");
        if (!$this->certificate_start->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->certificate_start->Visible = false; // Disable update for API request
            } else {
                $this->certificate_start->setFormValue($val);
            }
            $this->certificate_start->CurrentValue = UnFormatDateTime($this->certificate_start->CurrentValue, 0);
        }

        // Check field name 'certificate_end' first before field var 'x_certificate_end'
        $val = $CurrentForm->hasValue("certificate_end") ? $CurrentForm->getValue("certificate_end") : $CurrentForm->getValue("x_certificate_end");
        if (!$this->certificate_end->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->certificate_end->Visible = false; // Disable update for API request
            } else {
                $this->certificate_end->setFormValue($val);
            }
            $this->certificate_end->CurrentValue = UnFormatDateTime($this->certificate_end->CurrentValue, 0);
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
        $this->training_id->CurrentValue = $this->training_id->FormValue;
        $this->employee_username->CurrentValue = $this->employee_username->FormValue;
        $this->training_name->CurrentValue = $this->training_name->FormValue;
        $this->training_start->CurrentValue = $this->training_start->FormValue;
        $this->training_start->CurrentValue = UnFormatDateTime($this->training_start->CurrentValue, 0);
        $this->training_end->CurrentValue = $this->training_end->FormValue;
        $this->training_end->CurrentValue = UnFormatDateTime($this->training_end->CurrentValue, 0);
        $this->training_company->CurrentValue = $this->training_company->FormValue;
        $this->certificate_start->CurrentValue = $this->certificate_start->FormValue;
        $this->certificate_start->CurrentValue = UnFormatDateTime($this->certificate_start->CurrentValue, 0);
        $this->certificate_end->CurrentValue = $this->certificate_end->FormValue;
        $this->certificate_end->CurrentValue = UnFormatDateTime($this->certificate_end->CurrentValue, 0);
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
        $this->training_id->setDbValue($row['training_id']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->training_name->setDbValue($row['training_name']);
        $this->training_start->setDbValue($row['training_start']);
        $this->training_end->setDbValue($row['training_end']);
        $this->training_company->setDbValue($row['training_company']);
        $this->certificate_start->setDbValue($row['certificate_start']);
        $this->certificate_end->setDbValue($row['certificate_end']);
        $this->notes->setDbValue($row['notes']);
        $this->training_document->Upload->DbValue = $row['training_document'];
        $this->training_document->setDbValue($this->training_document->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['training_id'] = null;
        $row['employee_username'] = null;
        $row['training_name'] = null;
        $row['training_start'] = null;
        $row['training_end'] = null;
        $row['training_company'] = null;
        $row['certificate_start'] = null;
        $row['certificate_end'] = null;
        $row['notes'] = null;
        $row['training_document'] = null;
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

            // training_document
            $this->training_document->LinkCustomAttributes = "";
            if (!EmptyValue($this->training_document->Upload->DbValue)) {
                $this->training_document->HrefValue = GetFileUploadUrl($this->training_document, $this->training_document->htmlDecode($this->training_document->Upload->DbValue)); // Add prefix/suffix
                $this->training_document->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->training_document->HrefValue = FullUrl($this->training_document->HrefValue, "href");
                }
            } else {
                $this->training_document->HrefValue = "";
            }
            $this->training_document->ExportHrefValue = $this->training_document->UploadPath . $this->training_document->Upload->DbValue;
            $this->training_document->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // training_id
            $this->training_id->EditAttrs["class"] = "form-control";
            $this->training_id->EditCustomAttributes = "";

            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            if ($this->employee_username->getSessionValue() != "") {
                $this->employee_username->CurrentValue = GetForeignKeyValue($this->employee_username->getSessionValue());
                $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
                $this->employee_username->ViewCustomAttributes = "";
            } else {
            }

            // training_name
            $this->training_name->EditAttrs["class"] = "form-control";
            $this->training_name->EditCustomAttributes = "";
            if (!$this->training_name->Raw) {
                $this->training_name->CurrentValue = HtmlDecode($this->training_name->CurrentValue);
            }
            $this->training_name->EditValue = HtmlEncode($this->training_name->CurrentValue);
            $this->training_name->PlaceHolder = RemoveHtml($this->training_name->caption());

            // training_start
            $this->training_start->EditAttrs["class"] = "form-control";
            $this->training_start->EditCustomAttributes = "";
            $this->training_start->EditValue = HtmlEncode(FormatDateTime($this->training_start->CurrentValue, 8));
            $this->training_start->PlaceHolder = RemoveHtml($this->training_start->caption());

            // training_end
            $this->training_end->EditAttrs["class"] = "form-control";
            $this->training_end->EditCustomAttributes = "";
            $this->training_end->EditValue = HtmlEncode(FormatDateTime($this->training_end->CurrentValue, 8));
            $this->training_end->PlaceHolder = RemoveHtml($this->training_end->caption());

            // training_company
            $this->training_company->EditAttrs["class"] = "form-control";
            $this->training_company->EditCustomAttributes = "";
            if (!$this->training_company->Raw) {
                $this->training_company->CurrentValue = HtmlDecode($this->training_company->CurrentValue);
            }
            $this->training_company->EditValue = HtmlEncode($this->training_company->CurrentValue);
            $this->training_company->PlaceHolder = RemoveHtml($this->training_company->caption());

            // certificate_start
            $this->certificate_start->EditAttrs["class"] = "form-control";
            $this->certificate_start->EditCustomAttributes = "";
            $this->certificate_start->EditValue = HtmlEncode(FormatDateTime($this->certificate_start->CurrentValue, 8));
            $this->certificate_start->PlaceHolder = RemoveHtml($this->certificate_start->caption());

            // certificate_end
            $this->certificate_end->EditAttrs["class"] = "form-control";
            $this->certificate_end->EditCustomAttributes = "";
            $this->certificate_end->EditValue = HtmlEncode(FormatDateTime($this->certificate_end->CurrentValue, 8));
            $this->certificate_end->PlaceHolder = RemoveHtml($this->certificate_end->caption());

            // notes
            $this->notes->EditAttrs["class"] = "form-control";
            $this->notes->EditCustomAttributes = "";
            $this->notes->EditValue = HtmlEncode($this->notes->CurrentValue);
            $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());

            // training_document
            $this->training_document->EditAttrs["class"] = "form-control";
            $this->training_document->EditCustomAttributes = "";
            if (!EmptyValue($this->training_document->Upload->DbValue)) {
                $this->training_document->EditValue = $this->training_document->Upload->DbValue;
            } else {
                $this->training_document->EditValue = "";
            }
            if (!EmptyValue($this->training_document->CurrentValue)) {
                $this->training_document->Upload->FileName = $this->training_document->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->training_document);
            }

            // Edit refer script

            // training_id
            $this->training_id->LinkCustomAttributes = "";
            $this->training_id->HrefValue = "";
            $this->training_id->TooltipValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";

            // training_name
            $this->training_name->LinkCustomAttributes = "";
            $this->training_name->HrefValue = "";

            // training_start
            $this->training_start->LinkCustomAttributes = "";
            $this->training_start->HrefValue = "";

            // training_end
            $this->training_end->LinkCustomAttributes = "";
            $this->training_end->HrefValue = "";

            // training_company
            $this->training_company->LinkCustomAttributes = "";
            $this->training_company->HrefValue = "";

            // certificate_start
            $this->certificate_start->LinkCustomAttributes = "";
            $this->certificate_start->HrefValue = "";

            // certificate_end
            $this->certificate_end->LinkCustomAttributes = "";
            $this->certificate_end->HrefValue = "";

            // notes
            $this->notes->LinkCustomAttributes = "";
            $this->notes->HrefValue = "";

            // training_document
            $this->training_document->LinkCustomAttributes = "";
            if (!EmptyValue($this->training_document->Upload->DbValue)) {
                $this->training_document->HrefValue = GetFileUploadUrl($this->training_document, $this->training_document->htmlDecode($this->training_document->Upload->DbValue)); // Add prefix/suffix
                $this->training_document->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->training_document->HrefValue = FullUrl($this->training_document->HrefValue, "href");
                }
            } else {
                $this->training_document->HrefValue = "";
            }
            $this->training_document->ExportHrefValue = $this->training_document->UploadPath . $this->training_document->Upload->DbValue;
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
        if ($this->training_id->Required) {
            if (!$this->training_id->IsDetailKey && EmptyValue($this->training_id->FormValue)) {
                $this->training_id->addErrorMessage(str_replace("%s", $this->training_id->caption(), $this->training_id->RequiredErrorMessage));
            }
        }
        if ($this->employee_username->Required) {
            if (!$this->employee_username->IsDetailKey && EmptyValue($this->employee_username->FormValue)) {
                $this->employee_username->addErrorMessage(str_replace("%s", $this->employee_username->caption(), $this->employee_username->RequiredErrorMessage));
            }
        }
        if ($this->training_name->Required) {
            if (!$this->training_name->IsDetailKey && EmptyValue($this->training_name->FormValue)) {
                $this->training_name->addErrorMessage(str_replace("%s", $this->training_name->caption(), $this->training_name->RequiredErrorMessage));
            }
        }
        if ($this->training_start->Required) {
            if (!$this->training_start->IsDetailKey && EmptyValue($this->training_start->FormValue)) {
                $this->training_start->addErrorMessage(str_replace("%s", $this->training_start->caption(), $this->training_start->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->training_start->FormValue)) {
            $this->training_start->addErrorMessage($this->training_start->getErrorMessage(false));
        }
        if ($this->training_end->Required) {
            if (!$this->training_end->IsDetailKey && EmptyValue($this->training_end->FormValue)) {
                $this->training_end->addErrorMessage(str_replace("%s", $this->training_end->caption(), $this->training_end->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->training_end->FormValue)) {
            $this->training_end->addErrorMessage($this->training_end->getErrorMessage(false));
        }
        if ($this->training_company->Required) {
            if (!$this->training_company->IsDetailKey && EmptyValue($this->training_company->FormValue)) {
                $this->training_company->addErrorMessage(str_replace("%s", $this->training_company->caption(), $this->training_company->RequiredErrorMessage));
            }
        }
        if ($this->certificate_start->Required) {
            if (!$this->certificate_start->IsDetailKey && EmptyValue($this->certificate_start->FormValue)) {
                $this->certificate_start->addErrorMessage(str_replace("%s", $this->certificate_start->caption(), $this->certificate_start->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->certificate_start->FormValue)) {
            $this->certificate_start->addErrorMessage($this->certificate_start->getErrorMessage(false));
        }
        if ($this->certificate_end->Required) {
            if (!$this->certificate_end->IsDetailKey && EmptyValue($this->certificate_end->FormValue)) {
                $this->certificate_end->addErrorMessage(str_replace("%s", $this->certificate_end->caption(), $this->certificate_end->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->certificate_end->FormValue)) {
            $this->certificate_end->addErrorMessage($this->certificate_end->getErrorMessage(false));
        }
        if ($this->notes->Required) {
            if (!$this->notes->IsDetailKey && EmptyValue($this->notes->FormValue)) {
                $this->notes->addErrorMessage(str_replace("%s", $this->notes->caption(), $this->notes->RequiredErrorMessage));
            }
        }
        if ($this->training_document->Required) {
            if ($this->training_document->Upload->FileName == "" && !$this->training_document->Upload->KeepFile) {
                $this->training_document->addErrorMessage(str_replace("%s", $this->training_document->caption(), $this->training_document->RequiredErrorMessage));
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

            // training_name
            $this->training_name->setDbValueDef($rsnew, $this->training_name->CurrentValue, "", $this->training_name->ReadOnly);

            // training_start
            $this->training_start->setDbValueDef($rsnew, UnFormatDateTime($this->training_start->CurrentValue, 0), null, $this->training_start->ReadOnly);

            // training_end
            $this->training_end->setDbValueDef($rsnew, UnFormatDateTime($this->training_end->CurrentValue, 0), null, $this->training_end->ReadOnly);

            // training_company
            $this->training_company->setDbValueDef($rsnew, $this->training_company->CurrentValue, null, $this->training_company->ReadOnly);

            // certificate_start
            $this->certificate_start->setDbValueDef($rsnew, UnFormatDateTime($this->certificate_start->CurrentValue, 0), null, $this->certificate_start->ReadOnly);

            // certificate_end
            $this->certificate_end->setDbValueDef($rsnew, UnFormatDateTime($this->certificate_end->CurrentValue, 0), null, $this->certificate_end->ReadOnly);

            // notes
            $this->notes->setDbValueDef($rsnew, $this->notes->CurrentValue, null, $this->notes->ReadOnly);

            // training_document
            if ($this->training_document->Visible && !$this->training_document->ReadOnly && !$this->training_document->Upload->KeepFile) {
                $this->training_document->Upload->DbValue = $rsold['training_document']; // Get original value
                if ($this->training_document->Upload->FileName == "") {
                    $rsnew['training_document'] = null;
                } else {
                    $rsnew['training_document'] = $this->training_document->Upload->FileName;
                }
            }
            if ($this->training_document->Visible && !$this->training_document->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->training_document->Upload->DbValue) ? [] : [$this->training_document->htmlDecode($this->training_document->Upload->DbValue)];
                if (!EmptyValue($this->training_document->Upload->FileName)) {
                    $newFiles = [$this->training_document->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->training_document, $this->training_document->Upload->Index);
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
                                $file1 = UniqueFilename($this->training_document->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->training_document->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->training_document->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->training_document->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->training_document->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->training_document->setDbValueDef($rsnew, $this->training_document->Upload->FileName, null, $this->training_document->ReadOnly);
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
                    if ($this->training_document->Visible && !$this->training_document->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->training_document->Upload->DbValue) ? [] : [$this->training_document->htmlDecode($this->training_document->Upload->DbValue)];
                        if (!EmptyValue($this->training_document->Upload->FileName)) {
                            $newFiles = [$this->training_document->Upload->FileName];
                            $newFiles2 = [$this->training_document->htmlDecode($rsnew['training_document'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->training_document, $this->training_document->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->training_document->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->training_document->oldPhysicalUploadPath() . $oldFile);
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
            // training_document
            CleanUploadTempPath($this->training_document, $this->training_document->Upload->Index);
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
            $this->setSessionWhere($this->getDetailFilter());

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("mytraininglist"), "", $this->TableVar, true);
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
