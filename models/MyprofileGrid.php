<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MyprofileGrid extends Myprofile
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'myprofile';

    // Page object name
    public $PageObjName = "MyprofileGrid";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fmyprofilegrid";
    public $FormActionName = "k_action";
    public $FormBlankRowName = "k_blankrow";
    public $FormKeyCountName = "key_count";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $CopyUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $ListUrl;

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
        $this->FormActionName .= "_" . $this->FormName;
        $this->OldKeyName .= "_" . $this->FormName;
        $this->FormBlankRowName .= "_" . $this->FormName;
        $this->FormKeyCountName .= "_" . $this->FormName;
        $GLOBALS["Grid"] = &$this;
        $this->TokenTimeout = SessionTimeoutTime();

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (myprofile)
        if (!isset($GLOBALS["myprofile"]) || get_class($GLOBALS["myprofile"]) == PROJECT_NAMESPACE . "myprofile") {
            $GLOBALS["myprofile"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        $this->AddUrl = "myprofileadd";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'myprofile');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // List options
        $this->ListOptions = new ListOptions();
        $this->ListOptions->TableVar = $this->TableVar;

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }
        $this->OtherOptions["addedit"] = new ListOptions("div");
        $this->OtherOptions["addedit"]->TagClassName = "ew-add-edit-option";
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

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $doc = new $class(Container("myprofile"));
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
        unset($GLOBALS["Grid"]);
        if ($url === "") {
            return;
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

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
            SaveDebugMessage();
            Redirect(GetUrl($url));
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

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $ShowOtherOptions = false;
    public $DisplayRecords = 10;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "10,20,50,-1"; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchRowCount = 0; // For extended search
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $EditRowCount;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $RowAction = ""; // Row action
    public $MultiColumnClass = "col-sm";
    public $MultiColumnEditClass = "w-100";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $RestoreSearch = false;
            public $myasset_Count;
            public $mycontract_Count;
            public $mytimesheet_Count;
            public $mytraining_Count;
    public $DetailPages;
    public $OldRecordset;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
        $this->employee_name->setVisibility();
        $this->employee_username->setVisibility();
        $this->employee_password->setVisibility();
        $this->employee_email->setVisibility();
        $this->birth_date->setVisibility();
        $this->nik->setVisibility();
        $this->npwp->setVisibility();
        $this->address->setVisibility();
        $this->city_id->setVisibility();
        $this->postal_code->setVisibility();
        $this->bank_number->setVisibility();
        $this->bank_name->setVisibility();
        $this->scan_ktp->setVisibility();
        $this->scan_npwp->setVisibility();
        $this->curiculum_vitae->setVisibility();
        $this->technical_skill->setVisibility();
        $this->about_me->setVisibility();
        $this->position_id->setVisibility();
        $this->religion->setVisibility();
        $this->status_id->setVisibility();
        $this->skill_id->setVisibility();
        $this->office_id->setVisibility();
        $this->hire_date->setVisibility();
        $this->termination_date->setVisibility();
        $this->user_level->Visible = false;
        $this->hideFieldsForAddEdit();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up master detail parameters
        $this->setupMasterParms();

        // Setup other options
        $this->setupOtherOptions();

        // Set up lookup cache
        $this->setupLookupOptions($this->city_id);
        $this->setupLookupOptions($this->position_id);
        $this->setupLookupOptions($this->status_id);
        $this->setupLookupOptions($this->skill_id);
        $this->setupLookupOptions($this->office_id);

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = "";

        // Get command
        $this->Command = strtolower(Get("cmd"));
        if ($this->isPageRequest()) {
            // Set up records per page
            $this->setupDisplayRecords();

            // Handle reset command
            $this->resetCmd();

            // Hide list options
            if ($this->isExport()) {
                $this->ListOptions->hideAllOptions(["sequence"]);
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            } elseif ($this->isGridAdd() || $this->isGridEdit()) {
                $this->ListOptions->hideAllOptions();
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            }

            // Show grid delete link for grid add / grid edit
            if ($this->AllowAddDeleteRow) {
                if ($this->isGridAdd() || $this->isGridEdit()) {
                    $item = $this->ListOptions["griddelete"];
                    if ($item) {
                        $item->Visible = true;
                    }
                }
            }

            // Set up sorting order
            $this->setupSortOrder();
        }

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 10; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load Sorting Order
        if ($this->Command != "json") {
            $this->loadSortOrder();
        }

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }

        // Restore master/detail filter
        $this->DbMasterFilter = $this->getMasterFilter(); // Restore master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Restore detail filter
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Load master record
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "master_office") {
            $masterTbl = Container("master_office");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("masterofficelist"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Load master record
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "master_position") {
            $masterTbl = Container("master_position");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("masterpositionlist"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Load master record
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "master_skill") {
            $masterTbl = Container("master_skill");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("masterskilllist"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Load master record
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "master_status") {
            $masterTbl = Container("master_status");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("masterstatuslist"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Load master record
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "master_city") {
            $masterTbl = Container("master_city");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("mastercitylist"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }
        if ($this->isGridAdd()) {
            if ($this->CurrentMode == "copy") {
                $this->TotalRecords = $this->listRecordCount();
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->TotalRecords;
                $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
            } else {
                $this->CurrentFilter = "0=1";
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->GridAddRowCount;
            }
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->TotalRecords; // Display all records
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
        }

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset);
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows, "totalRecordCount" => $this->TotalRecords]);
            $this->terminate(true);
            return;
        }

        // Set up pager
        $this->Pager = new PrevNextPager($this->StartRecord, $this->getRecordsPerPage(), $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

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

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 10; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Exit inline mode
    protected function clearInlineMode()
    {
        $this->LastAction = $this->CurrentAction; // Save last action
        $this->CurrentAction = ""; // Clear action
        $_SESSION[SESSION_INLINE_MODE] = ""; // Clear inline mode
    }

    // Switch to Grid Add mode
    protected function gridAddMode()
    {
        $this->CurrentAction = "gridadd";
        $_SESSION[SESSION_INLINE_MODE] = "gridadd";
        $this->hideFieldsForAddEdit();
    }

    // Switch to Grid Edit mode
    protected function gridEditMode()
    {
        $this->CurrentAction = "gridedit";
        $_SESSION[SESSION_INLINE_MODE] = "gridedit";
        $this->hideFieldsForAddEdit();
    }

    // Perform update to grid
    public function gridUpdate()
    {
        global $Language, $CurrentForm;
        $gridUpdate = true;

        // Get old recordset
        $this->CurrentFilter = $this->buildKeyFilter();
        if ($this->CurrentFilter == "") {
            $this->CurrentFilter = "0=1";
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        if ($rs = $conn->executeQuery($sql)) {
            $rsold = $rs->fetchAll();
            $rs->closeCursor();
        }

        // Call Grid Updating event
        if (!$this->gridUpdating($rsold)) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridEditCancelled")); // Set grid edit cancelled message
            }
            return false;
        }
        $key = "";

        // Update row index and get row key
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Update all rows based on key
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            $CurrentForm->Index = $rowindex;
            $this->setKey($CurrentForm->getValue($this->OldKeyName));
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));

            // Load all values and keys
            if ($rowaction != "insertdelete") { // Skip insert then deleted rows
                $this->loadFormValues(); // Get form values
                if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
                    $gridUpdate = $this->OldKey != ""; // Key must not be empty
                } else {
                    $gridUpdate = true;
                }

                // Skip empty row
                if ($rowaction == "insert" && $this->emptyRow()) {
                // Validate form and insert/update/delete record
                } elseif ($gridUpdate) {
                    if ($rowaction == "delete") {
                        $this->CurrentFilter = $this->getRecordFilter();
                        $gridUpdate = $this->deleteRows(); // Delete this row
                    //} elseif (!$this->validateForm()) { // Already done in validateGridForm
                    //    $gridUpdate = false; // Form error, reset action
                    } else {
                        if ($rowaction == "insert") {
                            $gridUpdate = $this->addRow(); // Insert this row
                        } else {
                            if ($this->OldKey != "") {
                                $this->SendEmail = false; // Do not send email on update success
                                $gridUpdate = $this->editRow(); // Update this row
                            }
                        } // End update
                    }
                }
                if ($gridUpdate) {
                    if ($key != "") {
                        $key .= ", ";
                    }
                    $key .= $this->OldKey;
                } else {
                    break;
                }
            }
        }
        if ($gridUpdate) {
            // Get new records
            $rsnew = $conn->fetchAll($sql);

            // Call Grid_Updated event
            $this->gridUpdated($rsold, $rsnew);
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("UpdateFailed")); // Set update failed message
            }
        }
        return $gridUpdate;
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Perform Grid Add
    public function gridInsert()
    {
        global $Language, $CurrentForm;
        $rowindex = 1;
        $gridInsert = false;
        $conn = $this->getConnection();

        // Call Grid Inserting event
        if (!$this->gridInserting()) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridAddCancelled")); // Set grid add cancelled message
            }
            return false;
        }

        // Init key filter
        $wrkfilter = "";
        $addcnt = 0;
        $key = "";

        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Insert all rows
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "" && $rowaction != "insert") {
                continue; // Skip
            }
            if ($rowaction == "insert") {
                $this->OldKey = strval($CurrentForm->getValue($this->OldKeyName));
                $this->loadOldRecord(); // Load old record
            }
            $this->loadFormValues(); // Get form values
            if (!$this->emptyRow()) {
                $addcnt++;
                $this->SendEmail = false; // Do not send email on insert success

                // Validate form // Already done in validateGridForm
                //if (!$this->validateForm()) {
                //    $gridInsert = false; // Form error, reset action
                //} else {
                    $gridInsert = $this->addRow($this->OldRecordset); // Insert this row
                //}
                if ($gridInsert) {
                    if ($key != "") {
                        $key .= Config("COMPOSITE_KEY_SEPARATOR");
                    }
                    $key .= $this->employee_username->CurrentValue;

                    // Add filter for this record
                    $filter = $this->getRecordFilter();
                    if ($wrkfilter != "") {
                        $wrkfilter .= " OR ";
                    }
                    $wrkfilter .= $filter;
                } else {
                    break;
                }
            }
        }
        if ($addcnt == 0) { // No record inserted
            $this->clearInlineMode(); // Clear grid add mode and return
            return true;
        }
        if ($gridInsert) {
            // Get new records
            $this->CurrentFilter = $wrkfilter;
            $sql = $this->getCurrentSql();
            $rsnew = $conn->fetchAll($sql);

            // Call Grid_Inserted event
            $this->gridInserted($rsnew);
            $this->clearInlineMode(); // Clear grid add mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("InsertFailed")); // Set insert failed message
            }
        }
        return $gridInsert;
    }

    // Check if empty row
    public function emptyRow()
    {
        global $CurrentForm;
        if ($CurrentForm->hasValue("x_employee_name") && $CurrentForm->hasValue("o_employee_name") && $this->employee_name->CurrentValue != $this->employee_name->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_employee_username") && $CurrentForm->hasValue("o_employee_username") && $this->employee_username->CurrentValue != $this->employee_username->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_employee_password") && $CurrentForm->hasValue("o_employee_password") && $this->employee_password->CurrentValue != $this->employee_password->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_employee_email") && $CurrentForm->hasValue("o_employee_email") && $this->employee_email->CurrentValue != $this->employee_email->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_birth_date") && $CurrentForm->hasValue("o_birth_date") && $this->birth_date->CurrentValue != $this->birth_date->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_nik") && $CurrentForm->hasValue("o_nik") && $this->nik->CurrentValue != $this->nik->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_npwp") && $CurrentForm->hasValue("o_npwp") && $this->npwp->CurrentValue != $this->npwp->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_address") && $CurrentForm->hasValue("o_address") && $this->address->CurrentValue != $this->address->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_city_id") && $CurrentForm->hasValue("o_city_id") && $this->city_id->CurrentValue != $this->city_id->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_postal_code") && $CurrentForm->hasValue("o_postal_code") && $this->postal_code->CurrentValue != $this->postal_code->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_bank_number") && $CurrentForm->hasValue("o_bank_number") && $this->bank_number->CurrentValue != $this->bank_number->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_bank_name") && $CurrentForm->hasValue("o_bank_name") && $this->bank_name->CurrentValue != $this->bank_name->OldValue) {
            return false;
        }
        if (!EmptyValue($this->scan_ktp->Upload->Value)) {
            return false;
        }
        if (!EmptyValue($this->scan_npwp->Upload->Value)) {
            return false;
        }
        if (!EmptyValue($this->curiculum_vitae->Upload->Value)) {
            return false;
        }
        if ($CurrentForm->hasValue("x_technical_skill") && $CurrentForm->hasValue("o_technical_skill") && $this->technical_skill->CurrentValue != $this->technical_skill->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_about_me") && $CurrentForm->hasValue("o_about_me") && $this->about_me->CurrentValue != $this->about_me->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_position_id") && $CurrentForm->hasValue("o_position_id") && $this->position_id->CurrentValue != $this->position_id->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_religion") && $CurrentForm->hasValue("o_religion") && $this->religion->CurrentValue != $this->religion->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_status_id") && $CurrentForm->hasValue("o_status_id") && $this->status_id->CurrentValue != $this->status_id->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_skill_id") && $CurrentForm->hasValue("o_skill_id") && $this->skill_id->CurrentValue != $this->skill_id->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_office_id") && $CurrentForm->hasValue("o_office_id") && $this->office_id->CurrentValue != $this->office_id->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_hire_date") && $CurrentForm->hasValue("o_hire_date") && $this->hire_date->CurrentValue != $this->hire_date->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_termination_date") && $CurrentForm->hasValue("o_termination_date") && $this->termination_date->CurrentValue != $this->termination_date->OldValue) {
            return false;
        }
        return true;
    }

    // Validate grid form
    public function validateGridForm()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Validate all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } elseif (!$this->validateForm()) {
                    return false;
                }
            }
        }
        return true;
    }

    // Get all form values of the grid
    public function getGridFormValues()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }
        $rows = [];

        // Loop through all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } else {
                    $rows[] = $this->getFieldValues("FormValue"); // Return row as array
                }
            }
        }
        return $rows; // Return as array of array
    }

    // Restore form values for current row
    public function restoreCurrentRowFormValues($idx)
    {
        global $CurrentForm;

        // Get row based on current index
        $CurrentForm->Index = $idx;
        $rowaction = strval($CurrentForm->getValue($this->FormActionName));
        $this->loadFormValues(); // Load form values
        // Set up invalid status correctly
        $this->resetFormError();
        if ($rowaction == "insert" && $this->emptyRow()) {
            // Ignore
        } else {
            $this->validateForm();
        }
    }

    // Reset form status
    public function resetFormError()
    {
        $this->employee_name->clearErrorMessage();
        $this->employee_username->clearErrorMessage();
        $this->employee_password->clearErrorMessage();
        $this->employee_email->clearErrorMessage();
        $this->birth_date->clearErrorMessage();
        $this->nik->clearErrorMessage();
        $this->npwp->clearErrorMessage();
        $this->address->clearErrorMessage();
        $this->city_id->clearErrorMessage();
        $this->postal_code->clearErrorMessage();
        $this->bank_number->clearErrorMessage();
        $this->bank_name->clearErrorMessage();
        $this->scan_ktp->clearErrorMessage();
        $this->scan_npwp->clearErrorMessage();
        $this->curiculum_vitae->clearErrorMessage();
        $this->technical_skill->clearErrorMessage();
        $this->about_me->clearErrorMessage();
        $this->position_id->clearErrorMessage();
        $this->religion->clearErrorMessage();
        $this->status_id->clearErrorMessage();
        $this->skill_id->clearErrorMessage();
        $this->office_id->clearErrorMessage();
        $this->hire_date->clearErrorMessage();
        $this->termination_date->clearErrorMessage();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->setStartRecordNumber(1); // Reset start position
        }
    }

    // Load sort order parameters
    protected function loadSortOrder()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        if ($orderBy == "") {
            $this->DefaultSort = "`employee_name` ASC";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($this->employee_name->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($useDefaultSort) {
                    $this->employee_name->setSort("ASC");
                    $orderBy = $this->getSqlOrderBy();
                    $this->setSessionOrderBy($orderBy);
                } else {
                    $this->setSessionOrderBy("");
                }
            }
        }
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset master/detail keys
            if ($this->Command == "resetall") {
                $this->setCurrentMasterTable(""); // Clear master table
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
                        $this->office_id->setSessionValue("");
                        $this->position_id->setSessionValue("");
                        $this->skill_id->setSessionValue("");
                        $this->status_id->setSessionValue("");
                        $this->city_id->setSessionValue("");
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // "griddelete"
        if ($this->AllowAddDeleteRow) {
            $item = &$this->ListOptions->add("griddelete");
            $item->CssClass = "text-nowrap";
            $item->OnLeft = true;
            $item->Visible = false; // Default hidden
        }

        // Add group option item
        $item = &$this->ListOptions->add($this->ListOptions->GroupOptionName);
        $item->Body = "";
        $item->OnLeft = true;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = true;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = true;

        // "copy"
        $item = &$this->ListOptions->add("copy");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canAdd();
        $item->OnLeft = true;

        // "sequence"
        $item = &$this->ListOptions->add("sequence");
        $item->CssClass = "text-nowrap";
        $item->Visible = true;
        $item->OnLeft = true; // Always on left
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = true;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = false;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();

        // Set up row action and key
        if ($CurrentForm && is_numeric($this->RowIndex) && $this->RowType != "view") {
            $CurrentForm->Index = $this->RowIndex;
            $actionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
            $oldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->OldKeyName);
            $blankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
            if ($this->RowAction != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $actionName . "\" id=\"" . $actionName . "\" value=\"" . $this->RowAction . "\">";
            }
            $oldKey = $this->getKey(false); // Get from OldValue
            if ($oldKeyName != "" && $oldKey != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $oldKeyName . "\" id=\"" . $oldKeyName . "\" value=\"" . HtmlEncode($oldKey) . "\">";
            }
            if ($this->RowAction == "insert" && $this->isConfirm() && $this->emptyRow()) {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $blankRowName . "\" id=\"" . $blankRowName . "\" value=\"1\">";
            }
        }

        // "delete"
        if ($this->AllowAddDeleteRow) {
            if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
                $options = &$this->ListOptions;
                $options->UseButtonGroup = true; // Use button group for grid delete button
                $opt = $options["griddelete"];
                if (!$Security->canDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
                    $opt->Body = "&nbsp;";
                } else {
                    $opt->Body = "<a class=\"ew-grid-link ew-grid-delete\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" onclick=\"return ew.deleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->phrase("DeleteLink") . "</a>";
                }
            }
        }

        // "sequence"
        $opt = $this->ListOptions["sequence"];
        $opt->Body = FormatSequenceNumber($this->RecordCount);
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit()) {
                $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd()) {
                $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
            } else {
                $opt->Body = "";
            }
        } // End View mode
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $option = $this->OtherOptions["addedit"];
        $option->UseDropDownButton = false;
        $option->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $option->UseButtonGroup = true;
        //$option->ButtonClass = ""; // Class for button group
        $item = &$option->add($option->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;

        // Add
        if ($this->CurrentMode == "view") { // Check view mode
            $item = &$option->add("add");
            $addcaption = HtmlTitle($Language->phrase("AddLink"));
            $this->AddUrl = $this->getAddUrl();
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
            $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        }
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && !$this->isConfirm()) { // Check add/copy/edit mode
            if ($this->AllowAddDeleteRow) {
                $option = $options["addedit"];
                $option->UseDropDownButton = false;
                $item = &$option->add("addblankrow");
                $item->Body = "<a class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" href=\"#\" onclick=\"return ew.addGridRow(this);\">" . $Language->phrase("AddBlankRow") . "</a>";
                $item->Visible = $Security->canAdd();
                $this->ShowOtherOptions = $item->Visible;
            }
        }
        if ($this->CurrentMode == "view") { // Check view mode
            $option = $options["addedit"];
            $item = $option["add"];
            $this->ShowOtherOptions = $item && $item->Visible;
        }
    }

    // Set up list options (extended codes)
    protected function setupListOptionsExt()
    {
    }

    // Render list options (extended codes)
    protected function renderListOptionsExt()
    {
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->scan_ktp->Upload->Index = $CurrentForm->Index;
        $this->scan_ktp->Upload->uploadFile();
        $this->scan_ktp->CurrentValue = $this->scan_ktp->Upload->FileName;
        $this->scan_npwp->Upload->Index = $CurrentForm->Index;
        $this->scan_npwp->Upload->uploadFile();
        $this->scan_npwp->CurrentValue = $this->scan_npwp->Upload->FileName;
        $this->curiculum_vitae->Upload->Index = $CurrentForm->Index;
        $this->curiculum_vitae->Upload->uploadFile();
        $this->curiculum_vitae->CurrentValue = $this->curiculum_vitae->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->employee_name->CurrentValue = null;
        $this->employee_name->OldValue = $this->employee_name->CurrentValue;
        $this->employee_username->CurrentValue = null;
        $this->employee_username->OldValue = $this->employee_username->CurrentValue;
        $this->employee_password->CurrentValue = null;
        $this->employee_password->OldValue = $this->employee_password->CurrentValue;
        $this->employee_email->CurrentValue = null;
        $this->employee_email->OldValue = $this->employee_email->CurrentValue;
        $this->birth_date->CurrentValue = null;
        $this->birth_date->OldValue = $this->birth_date->CurrentValue;
        $this->nik->CurrentValue = null;
        $this->nik->OldValue = $this->nik->CurrentValue;
        $this->npwp->CurrentValue = null;
        $this->npwp->OldValue = $this->npwp->CurrentValue;
        $this->address->CurrentValue = null;
        $this->address->OldValue = $this->address->CurrentValue;
        $this->city_id->CurrentValue = null;
        $this->city_id->OldValue = $this->city_id->CurrentValue;
        $this->postal_code->CurrentValue = null;
        $this->postal_code->OldValue = $this->postal_code->CurrentValue;
        $this->bank_number->CurrentValue = null;
        $this->bank_number->OldValue = $this->bank_number->CurrentValue;
        $this->bank_name->CurrentValue = null;
        $this->bank_name->OldValue = $this->bank_name->CurrentValue;
        $this->scan_ktp->Upload->DbValue = null;
        $this->scan_ktp->OldValue = $this->scan_ktp->Upload->DbValue;
        $this->scan_ktp->Upload->Index = $this->RowIndex;
        $this->scan_npwp->Upload->DbValue = null;
        $this->scan_npwp->OldValue = $this->scan_npwp->Upload->DbValue;
        $this->scan_npwp->Upload->Index = $this->RowIndex;
        $this->curiculum_vitae->Upload->DbValue = null;
        $this->curiculum_vitae->OldValue = $this->curiculum_vitae->Upload->DbValue;
        $this->curiculum_vitae->Upload->Index = $this->RowIndex;
        $this->technical_skill->CurrentValue = null;
        $this->technical_skill->OldValue = $this->technical_skill->CurrentValue;
        $this->about_me->CurrentValue = null;
        $this->about_me->OldValue = $this->about_me->CurrentValue;
        $this->position_id->CurrentValue = null;
        $this->position_id->OldValue = $this->position_id->CurrentValue;
        $this->religion->CurrentValue = null;
        $this->religion->OldValue = $this->religion->CurrentValue;
        $this->status_id->CurrentValue = null;
        $this->status_id->OldValue = $this->status_id->CurrentValue;
        $this->skill_id->CurrentValue = null;
        $this->skill_id->OldValue = $this->skill_id->CurrentValue;
        $this->office_id->CurrentValue = null;
        $this->office_id->OldValue = $this->office_id->CurrentValue;
        $this->hire_date->CurrentValue = null;
        $this->hire_date->OldValue = $this->hire_date->CurrentValue;
        $this->termination_date->CurrentValue = null;
        $this->termination_date->OldValue = $this->termination_date->CurrentValue;
        $this->user_level->CurrentValue = null;
        $this->user_level->OldValue = $this->user_level->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;

        // Check field name 'employee_name' first before field var 'x_employee_name'
        $val = $CurrentForm->hasValue("employee_name") ? $CurrentForm->getValue("employee_name") : $CurrentForm->getValue("x_employee_name");
        if (!$this->employee_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->employee_name->Visible = false; // Disable update for API request
            } else {
                $this->employee_name->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_employee_name")) {
            $this->employee_name->setOldValue($CurrentForm->getValue("o_employee_name"));
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
        if ($CurrentForm->hasValue("o_employee_username")) {
            $this->employee_username->setOldValue($CurrentForm->getValue("o_employee_username"));
        }

        // Check field name 'employee_password' first before field var 'x_employee_password'
        $val = $CurrentForm->hasValue("employee_password") ? $CurrentForm->getValue("employee_password") : $CurrentForm->getValue("x_employee_password");
        if (!$this->employee_password->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->employee_password->Visible = false; // Disable update for API request
            } else {
                $this->employee_password->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_employee_password")) {
            $this->employee_password->setOldValue($CurrentForm->getValue("o_employee_password"));
        }

        // Check field name 'employee_email' first before field var 'x_employee_email'
        $val = $CurrentForm->hasValue("employee_email") ? $CurrentForm->getValue("employee_email") : $CurrentForm->getValue("x_employee_email");
        if (!$this->employee_email->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->employee_email->Visible = false; // Disable update for API request
            } else {
                $this->employee_email->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_employee_email")) {
            $this->employee_email->setOldValue($CurrentForm->getValue("o_employee_email"));
        }

        // Check field name 'birth_date' first before field var 'x_birth_date'
        $val = $CurrentForm->hasValue("birth_date") ? $CurrentForm->getValue("birth_date") : $CurrentForm->getValue("x_birth_date");
        if (!$this->birth_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->birth_date->Visible = false; // Disable update for API request
            } else {
                $this->birth_date->setFormValue($val);
            }
            $this->birth_date->CurrentValue = UnFormatDateTime($this->birth_date->CurrentValue, 5);
        }
        if ($CurrentForm->hasValue("o_birth_date")) {
            $this->birth_date->setOldValue($CurrentForm->getValue("o_birth_date"));
        }

        // Check field name 'nik' first before field var 'x_nik'
        $val = $CurrentForm->hasValue("nik") ? $CurrentForm->getValue("nik") : $CurrentForm->getValue("x_nik");
        if (!$this->nik->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nik->Visible = false; // Disable update for API request
            } else {
                $this->nik->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_nik")) {
            $this->nik->setOldValue($CurrentForm->getValue("o_nik"));
        }

        // Check field name 'npwp' first before field var 'x_npwp'
        $val = $CurrentForm->hasValue("npwp") ? $CurrentForm->getValue("npwp") : $CurrentForm->getValue("x_npwp");
        if (!$this->npwp->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->npwp->Visible = false; // Disable update for API request
            } else {
                $this->npwp->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_npwp")) {
            $this->npwp->setOldValue($CurrentForm->getValue("o_npwp"));
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
        if ($CurrentForm->hasValue("o_address")) {
            $this->address->setOldValue($CurrentForm->getValue("o_address"));
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
        if ($CurrentForm->hasValue("o_city_id")) {
            $this->city_id->setOldValue($CurrentForm->getValue("o_city_id"));
        }

        // Check field name 'postal_code' first before field var 'x_postal_code'
        $val = $CurrentForm->hasValue("postal_code") ? $CurrentForm->getValue("postal_code") : $CurrentForm->getValue("x_postal_code");
        if (!$this->postal_code->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->postal_code->Visible = false; // Disable update for API request
            } else {
                $this->postal_code->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_postal_code")) {
            $this->postal_code->setOldValue($CurrentForm->getValue("o_postal_code"));
        }

        // Check field name 'bank_number' first before field var 'x_bank_number'
        $val = $CurrentForm->hasValue("bank_number") ? $CurrentForm->getValue("bank_number") : $CurrentForm->getValue("x_bank_number");
        if (!$this->bank_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->bank_number->Visible = false; // Disable update for API request
            } else {
                $this->bank_number->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_bank_number")) {
            $this->bank_number->setOldValue($CurrentForm->getValue("o_bank_number"));
        }

        // Check field name 'bank_name' first before field var 'x_bank_name'
        $val = $CurrentForm->hasValue("bank_name") ? $CurrentForm->getValue("bank_name") : $CurrentForm->getValue("x_bank_name");
        if (!$this->bank_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->bank_name->Visible = false; // Disable update for API request
            } else {
                $this->bank_name->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_bank_name")) {
            $this->bank_name->setOldValue($CurrentForm->getValue("o_bank_name"));
        }

        // Check field name 'technical_skill' first before field var 'x_technical_skill'
        $val = $CurrentForm->hasValue("technical_skill") ? $CurrentForm->getValue("technical_skill") : $CurrentForm->getValue("x_technical_skill");
        if (!$this->technical_skill->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->technical_skill->Visible = false; // Disable update for API request
            } else {
                $this->technical_skill->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_technical_skill")) {
            $this->technical_skill->setOldValue($CurrentForm->getValue("o_technical_skill"));
        }

        // Check field name 'about_me' first before field var 'x_about_me'
        $val = $CurrentForm->hasValue("about_me") ? $CurrentForm->getValue("about_me") : $CurrentForm->getValue("x_about_me");
        if (!$this->about_me->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->about_me->Visible = false; // Disable update for API request
            } else {
                $this->about_me->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_about_me")) {
            $this->about_me->setOldValue($CurrentForm->getValue("o_about_me"));
        }

        // Check field name 'position_id' first before field var 'x_position_id'
        $val = $CurrentForm->hasValue("position_id") ? $CurrentForm->getValue("position_id") : $CurrentForm->getValue("x_position_id");
        if (!$this->position_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->position_id->Visible = false; // Disable update for API request
            } else {
                $this->position_id->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_position_id")) {
            $this->position_id->setOldValue($CurrentForm->getValue("o_position_id"));
        }

        // Check field name 'religion' first before field var 'x_religion'
        $val = $CurrentForm->hasValue("religion") ? $CurrentForm->getValue("religion") : $CurrentForm->getValue("x_religion");
        if (!$this->religion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->religion->Visible = false; // Disable update for API request
            } else {
                $this->religion->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_religion")) {
            $this->religion->setOldValue($CurrentForm->getValue("o_religion"));
        }

        // Check field name 'status_id' first before field var 'x_status_id'
        $val = $CurrentForm->hasValue("status_id") ? $CurrentForm->getValue("status_id") : $CurrentForm->getValue("x_status_id");
        if (!$this->status_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->status_id->Visible = false; // Disable update for API request
            } else {
                $this->status_id->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_status_id")) {
            $this->status_id->setOldValue($CurrentForm->getValue("o_status_id"));
        }

        // Check field name 'skill_id' first before field var 'x_skill_id'
        $val = $CurrentForm->hasValue("skill_id") ? $CurrentForm->getValue("skill_id") : $CurrentForm->getValue("x_skill_id");
        if (!$this->skill_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->skill_id->Visible = false; // Disable update for API request
            } else {
                $this->skill_id->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_skill_id")) {
            $this->skill_id->setOldValue($CurrentForm->getValue("o_skill_id"));
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
        if ($CurrentForm->hasValue("o_office_id")) {
            $this->office_id->setOldValue($CurrentForm->getValue("o_office_id"));
        }

        // Check field name 'hire_date' first before field var 'x_hire_date'
        $val = $CurrentForm->hasValue("hire_date") ? $CurrentForm->getValue("hire_date") : $CurrentForm->getValue("x_hire_date");
        if (!$this->hire_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->hire_date->Visible = false; // Disable update for API request
            } else {
                $this->hire_date->setFormValue($val);
            }
            $this->hire_date->CurrentValue = UnFormatDateTime($this->hire_date->CurrentValue, 5);
        }
        if ($CurrentForm->hasValue("o_hire_date")) {
            $this->hire_date->setOldValue($CurrentForm->getValue("o_hire_date"));
        }

        // Check field name 'termination_date' first before field var 'x_termination_date'
        $val = $CurrentForm->hasValue("termination_date") ? $CurrentForm->getValue("termination_date") : $CurrentForm->getValue("x_termination_date");
        if (!$this->termination_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->termination_date->Visible = false; // Disable update for API request
            } else {
                $this->termination_date->setFormValue($val);
            }
            $this->termination_date->CurrentValue = UnFormatDateTime($this->termination_date->CurrentValue, 5);
        }
        if ($CurrentForm->hasValue("o_termination_date")) {
            $this->termination_date->setOldValue($CurrentForm->getValue("o_termination_date"));
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->employee_name->CurrentValue = $this->employee_name->FormValue;
        $this->employee_username->CurrentValue = $this->employee_username->FormValue;
        $this->employee_password->CurrentValue = $this->employee_password->FormValue;
        $this->employee_email->CurrentValue = $this->employee_email->FormValue;
        $this->birth_date->CurrentValue = $this->birth_date->FormValue;
        $this->birth_date->CurrentValue = UnFormatDateTime($this->birth_date->CurrentValue, 5);
        $this->nik->CurrentValue = $this->nik->FormValue;
        $this->npwp->CurrentValue = $this->npwp->FormValue;
        $this->address->CurrentValue = $this->address->FormValue;
        $this->city_id->CurrentValue = $this->city_id->FormValue;
        $this->postal_code->CurrentValue = $this->postal_code->FormValue;
        $this->bank_number->CurrentValue = $this->bank_number->FormValue;
        $this->bank_name->CurrentValue = $this->bank_name->FormValue;
        $this->technical_skill->CurrentValue = $this->technical_skill->FormValue;
        $this->about_me->CurrentValue = $this->about_me->FormValue;
        $this->position_id->CurrentValue = $this->position_id->FormValue;
        $this->religion->CurrentValue = $this->religion->FormValue;
        $this->status_id->CurrentValue = $this->status_id->FormValue;
        $this->skill_id->CurrentValue = $this->skill_id->FormValue;
        $this->office_id->CurrentValue = $this->office_id->FormValue;
        $this->hire_date->CurrentValue = $this->hire_date->FormValue;
        $this->hire_date->CurrentValue = UnFormatDateTime($this->hire_date->CurrentValue, 5);
        $this->termination_date->CurrentValue = $this->termination_date->FormValue;
        $this->termination_date->CurrentValue = UnFormatDateTime($this->termination_date->CurrentValue, 5);
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
        $this->employee_name->setDbValue($row['employee_name']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->employee_password->setDbValue($row['employee_password']);
        $this->employee_email->setDbValue($row['employee_email']);
        $this->birth_date->setDbValue($row['birth_date']);
        $this->nik->setDbValue($row['nik']);
        $this->npwp->setDbValue($row['npwp']);
        $this->address->setDbValue($row['address']);
        $this->city_id->setDbValue($row['city_id']);
        $this->postal_code->setDbValue($row['postal_code']);
        $this->bank_number->setDbValue($row['bank_number']);
        $this->bank_name->setDbValue($row['bank_name']);
        $this->scan_ktp->Upload->DbValue = $row['scan_ktp'];
        $this->scan_ktp->setDbValue($this->scan_ktp->Upload->DbValue);
        $this->scan_ktp->Upload->Index = $this->RowIndex;
        $this->scan_npwp->Upload->DbValue = $row['scan_npwp'];
        $this->scan_npwp->setDbValue($this->scan_npwp->Upload->DbValue);
        $this->scan_npwp->Upload->Index = $this->RowIndex;
        $this->curiculum_vitae->Upload->DbValue = $row['curiculum_vitae'];
        $this->curiculum_vitae->setDbValue($this->curiculum_vitae->Upload->DbValue);
        $this->curiculum_vitae->Upload->Index = $this->RowIndex;
        $this->technical_skill->setDbValue($row['technical_skill']);
        $this->about_me->setDbValue($row['about_me']);
        $this->position_id->setDbValue($row['position_id']);
        $this->religion->setDbValue($row['religion']);
        $this->status_id->setDbValue($row['status_id']);
        $this->skill_id->setDbValue($row['skill_id']);
        $this->office_id->setDbValue($row['office_id']);
        $this->hire_date->setDbValue($row['hire_date']);
        $this->termination_date->setDbValue($row['termination_date']);
        $this->user_level->setDbValue($row['user_level']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['employee_name'] = $this->employee_name->CurrentValue;
        $row['employee_username'] = $this->employee_username->CurrentValue;
        $row['employee_password'] = $this->employee_password->CurrentValue;
        $row['employee_email'] = $this->employee_email->CurrentValue;
        $row['birth_date'] = $this->birth_date->CurrentValue;
        $row['nik'] = $this->nik->CurrentValue;
        $row['npwp'] = $this->npwp->CurrentValue;
        $row['address'] = $this->address->CurrentValue;
        $row['city_id'] = $this->city_id->CurrentValue;
        $row['postal_code'] = $this->postal_code->CurrentValue;
        $row['bank_number'] = $this->bank_number->CurrentValue;
        $row['bank_name'] = $this->bank_name->CurrentValue;
        $row['scan_ktp'] = $this->scan_ktp->Upload->DbValue;
        $row['scan_npwp'] = $this->scan_npwp->Upload->DbValue;
        $row['curiculum_vitae'] = $this->curiculum_vitae->Upload->DbValue;
        $row['technical_skill'] = $this->technical_skill->CurrentValue;
        $row['about_me'] = $this->about_me->CurrentValue;
        $row['position_id'] = $this->position_id->CurrentValue;
        $row['religion'] = $this->religion->CurrentValue;
        $row['status_id'] = $this->status_id->CurrentValue;
        $row['skill_id'] = $this->skill_id->CurrentValue;
        $row['office_id'] = $this->office_id->CurrentValue;
        $row['hire_date'] = $this->hire_date->CurrentValue;
        $row['termination_date'] = $this->termination_date->CurrentValue;
        $row['user_level'] = $this->user_level->CurrentValue;
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
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // employee_name

        // employee_username

        // employee_password

        // employee_email

        // birth_date

        // nik

        // npwp

        // address
        $this->address->CellCssStyle = "white-space: nowrap;";

        // city_id

        // postal_code

        // bank_number

        // bank_name

        // scan_ktp

        // scan_npwp

        // curiculum_vitae

        // technical_skill

        // about_me

        // position_id

        // religion

        // status_id

        // skill_id

        // office_id

        // hire_date

        // termination_date

        // user_level
        $this->user_level->CellCssStyle = "white-space: nowrap;";
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

            // technical_skill
            $this->technical_skill->ViewValue = $this->technical_skill->CurrentValue;
            $this->technical_skill->ViewCustomAttributes = "";

            // about_me
            $this->about_me->ViewValue = $this->about_me->CurrentValue;
            $this->about_me->ViewCustomAttributes = "";

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

            // religion
            if (strval($this->religion->CurrentValue) != "") {
                $this->religion->ViewValue = $this->religion->optionCaption($this->religion->CurrentValue);
            } else {
                $this->religion->ViewValue = null;
            }
            $this->religion->ViewCustomAttributes = "";

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

            // employee_name
            $this->employee_name->LinkCustomAttributes = "";
            $this->employee_name->HrefValue = "";
            $this->employee_name->TooltipValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";
            $this->employee_username->TooltipValue = "";

            // employee_password
            $this->employee_password->LinkCustomAttributes = "";
            $this->employee_password->HrefValue = "";
            $this->employee_password->TooltipValue = "";

            // employee_email
            $this->employee_email->LinkCustomAttributes = "";
            $this->employee_email->HrefValue = "";
            $this->employee_email->TooltipValue = "";

            // birth_date
            $this->birth_date->LinkCustomAttributes = "";
            $this->birth_date->HrefValue = "";
            $this->birth_date->TooltipValue = "";

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

            // scan_ktp
            $this->scan_ktp->LinkCustomAttributes = "";
            if (!EmptyValue($this->scan_ktp->Upload->DbValue)) {
                $this->scan_ktp->HrefValue = GetFileUploadUrl($this->scan_ktp, $this->scan_ktp->htmlDecode($this->scan_ktp->Upload->DbValue)); // Add prefix/suffix
                $this->scan_ktp->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->scan_ktp->HrefValue = FullUrl($this->scan_ktp->HrefValue, "href");
                }
            } else {
                $this->scan_ktp->HrefValue = "";
            }
            $this->scan_ktp->ExportHrefValue = $this->scan_ktp->UploadPath . $this->scan_ktp->Upload->DbValue;
            $this->scan_ktp->TooltipValue = "";

            // scan_npwp
            $this->scan_npwp->LinkCustomAttributes = "";
            if (!EmptyValue($this->scan_npwp->Upload->DbValue)) {
                $this->scan_npwp->HrefValue = GetFileUploadUrl($this->scan_npwp, $this->scan_npwp->htmlDecode($this->scan_npwp->Upload->DbValue)); // Add prefix/suffix
                $this->scan_npwp->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->scan_npwp->HrefValue = FullUrl($this->scan_npwp->HrefValue, "href");
                }
            } else {
                $this->scan_npwp->HrefValue = "";
            }
            $this->scan_npwp->ExportHrefValue = $this->scan_npwp->UploadPath . $this->scan_npwp->Upload->DbValue;
            $this->scan_npwp->TooltipValue = "";

            // curiculum_vitae
            $this->curiculum_vitae->LinkCustomAttributes = "";
            if (!EmptyValue($this->curiculum_vitae->Upload->DbValue)) {
                $this->curiculum_vitae->HrefValue = GetFileUploadUrl($this->curiculum_vitae, $this->curiculum_vitae->htmlDecode($this->curiculum_vitae->Upload->DbValue)); // Add prefix/suffix
                $this->curiculum_vitae->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->curiculum_vitae->HrefValue = FullUrl($this->curiculum_vitae->HrefValue, "href");
                }
            } else {
                $this->curiculum_vitae->HrefValue = "";
            }
            $this->curiculum_vitae->ExportHrefValue = $this->curiculum_vitae->UploadPath . $this->curiculum_vitae->Upload->DbValue;
            $this->curiculum_vitae->TooltipValue = "";

            // technical_skill
            $this->technical_skill->LinkCustomAttributes = "";
            $this->technical_skill->HrefValue = "";
            $this->technical_skill->TooltipValue = "";

            // about_me
            $this->about_me->LinkCustomAttributes = "";
            $this->about_me->HrefValue = "";
            $this->about_me->TooltipValue = "";

            // position_id
            $this->position_id->LinkCustomAttributes = "";
            $this->position_id->HrefValue = "";
            $this->position_id->TooltipValue = "";

            // religion
            $this->religion->LinkCustomAttributes = "";
            $this->religion->HrefValue = "";
            $this->religion->TooltipValue = "";

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
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // employee_name
            $this->employee_name->EditAttrs["class"] = "form-control";
            $this->employee_name->EditCustomAttributes = "";
            if (!$this->employee_name->Raw) {
                $this->employee_name->CurrentValue = HtmlDecode($this->employee_name->CurrentValue);
            }
            $this->employee_name->EditValue = HtmlEncode($this->employee_name->CurrentValue);
            $this->employee_name->PlaceHolder = RemoveHtml($this->employee_name->caption());

            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            if (!$this->employee_username->Raw) {
                $this->employee_username->CurrentValue = HtmlDecode($this->employee_username->CurrentValue);
            }
            $this->employee_username->EditValue = HtmlEncode($this->employee_username->CurrentValue);
            $this->employee_username->PlaceHolder = RemoveHtml($this->employee_username->caption());

            // employee_password
            $this->employee_password->EditAttrs["class"] = "form-control";
            $this->employee_password->EditCustomAttributes = "";
            $this->employee_password->PlaceHolder = RemoveHtml($this->employee_password->caption());

            // employee_email
            $this->employee_email->EditAttrs["class"] = "form-control";
            $this->employee_email->EditCustomAttributes = "";
            if (!$this->employee_email->Raw) {
                $this->employee_email->CurrentValue = HtmlDecode($this->employee_email->CurrentValue);
            }
            $this->employee_email->EditValue = HtmlEncode($this->employee_email->CurrentValue);
            $this->employee_email->PlaceHolder = RemoveHtml($this->employee_email->caption());

            // birth_date
            $this->birth_date->EditAttrs["class"] = "form-control";
            $this->birth_date->EditCustomAttributes = "";
            $this->birth_date->EditValue = HtmlEncode(FormatDateTime($this->birth_date->CurrentValue, 5));
            $this->birth_date->PlaceHolder = RemoveHtml($this->birth_date->caption());

            // nik
            $this->nik->EditAttrs["class"] = "form-control";
            $this->nik->EditCustomAttributes = "";
            if (!$this->nik->Raw) {
                $this->nik->CurrentValue = HtmlDecode($this->nik->CurrentValue);
            }
            $this->nik->EditValue = HtmlEncode($this->nik->CurrentValue);
            $this->nik->PlaceHolder = RemoveHtml($this->nik->caption());

            // npwp
            $this->npwp->EditAttrs["class"] = "form-control";
            $this->npwp->EditCustomAttributes = "";
            if (!$this->npwp->Raw) {
                $this->npwp->CurrentValue = HtmlDecode($this->npwp->CurrentValue);
            }
            $this->npwp->EditValue = HtmlEncode($this->npwp->CurrentValue);
            $this->npwp->PlaceHolder = RemoveHtml($this->npwp->caption());

            // address
            $this->address->EditAttrs["class"] = "form-control";
            $this->address->EditCustomAttributes = "";
            if (!$this->address->Raw) {
                $this->address->CurrentValue = HtmlDecode($this->address->CurrentValue);
            }
            $this->address->EditValue = HtmlEncode($this->address->CurrentValue);
            $this->address->PlaceHolder = RemoveHtml($this->address->caption());

            // city_id
            $this->city_id->EditAttrs["class"] = "form-control";
            $this->city_id->EditCustomAttributes = "";
            if ($this->city_id->getSessionValue() != "") {
                $this->city_id->CurrentValue = GetForeignKeyValue($this->city_id->getSessionValue());
                $this->city_id->OldValue = $this->city_id->CurrentValue;
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

            // postal_code
            $this->postal_code->EditAttrs["class"] = "form-control";
            $this->postal_code->EditCustomAttributes = "";
            if (!$this->postal_code->Raw) {
                $this->postal_code->CurrentValue = HtmlDecode($this->postal_code->CurrentValue);
            }
            $this->postal_code->EditValue = HtmlEncode($this->postal_code->CurrentValue);
            $this->postal_code->PlaceHolder = RemoveHtml($this->postal_code->caption());

            // bank_number
            $this->bank_number->EditAttrs["class"] = "form-control";
            $this->bank_number->EditCustomAttributes = "";
            if (!$this->bank_number->Raw) {
                $this->bank_number->CurrentValue = HtmlDecode($this->bank_number->CurrentValue);
            }
            $this->bank_number->EditValue = HtmlEncode($this->bank_number->CurrentValue);
            $this->bank_number->PlaceHolder = RemoveHtml($this->bank_number->caption());

            // bank_name
            $this->bank_name->EditAttrs["class"] = "form-control";
            $this->bank_name->EditCustomAttributes = "";
            if (!$this->bank_name->Raw) {
                $this->bank_name->CurrentValue = HtmlDecode($this->bank_name->CurrentValue);
            }
            $this->bank_name->EditValue = HtmlEncode($this->bank_name->CurrentValue);
            $this->bank_name->PlaceHolder = RemoveHtml($this->bank_name->caption());

            // scan_ktp
            $this->scan_ktp->EditAttrs["class"] = "form-control";
            $this->scan_ktp->EditCustomAttributes = "";
            if (!EmptyValue($this->scan_ktp->Upload->DbValue)) {
                $this->scan_ktp->EditValue = $this->scan_ktp->Upload->DbValue;
            } else {
                $this->scan_ktp->EditValue = "";
            }
            if (!EmptyValue($this->scan_ktp->CurrentValue)) {
                $this->scan_ktp->Upload->FileName = $this->scan_ktp->CurrentValue;
            }
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->scan_ktp, $this->RowIndex);
            }

            // scan_npwp
            $this->scan_npwp->EditAttrs["class"] = "form-control";
            $this->scan_npwp->EditCustomAttributes = "";
            if (!EmptyValue($this->scan_npwp->Upload->DbValue)) {
                $this->scan_npwp->EditValue = $this->scan_npwp->Upload->DbValue;
            } else {
                $this->scan_npwp->EditValue = "";
            }
            if (!EmptyValue($this->scan_npwp->CurrentValue)) {
                $this->scan_npwp->Upload->FileName = $this->scan_npwp->CurrentValue;
            }
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->scan_npwp, $this->RowIndex);
            }

            // curiculum_vitae
            $this->curiculum_vitae->EditAttrs["class"] = "form-control";
            $this->curiculum_vitae->EditCustomAttributes = "";
            if (!EmptyValue($this->curiculum_vitae->Upload->DbValue)) {
                $this->curiculum_vitae->EditValue = $this->curiculum_vitae->Upload->DbValue;
            } else {
                $this->curiculum_vitae->EditValue = "";
            }
            if (!EmptyValue($this->curiculum_vitae->CurrentValue)) {
                $this->curiculum_vitae->Upload->FileName = $this->curiculum_vitae->CurrentValue;
            }
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->curiculum_vitae, $this->RowIndex);
            }

            // technical_skill
            $this->technical_skill->EditAttrs["class"] = "form-control";
            $this->technical_skill->EditCustomAttributes = "";
            $this->technical_skill->EditValue = HtmlEncode($this->technical_skill->CurrentValue);
            $this->technical_skill->PlaceHolder = RemoveHtml($this->technical_skill->caption());

            // about_me
            $this->about_me->EditAttrs["class"] = "form-control";
            $this->about_me->EditCustomAttributes = "";
            $this->about_me->EditValue = HtmlEncode($this->about_me->CurrentValue);
            $this->about_me->PlaceHolder = RemoveHtml($this->about_me->caption());

            // position_id
            $this->position_id->EditAttrs["class"] = "form-control";
            $this->position_id->EditCustomAttributes = "";
            if ($this->position_id->getSessionValue() != "") {
                $this->position_id->CurrentValue = GetForeignKeyValue($this->position_id->getSessionValue());
                $this->position_id->OldValue = $this->position_id->CurrentValue;
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
            } else {
                $curVal = trim(strval($this->position_id->CurrentValue));
                if ($curVal != "") {
                    $this->position_id->ViewValue = $this->position_id->lookupCacheOption($curVal);
                } else {
                    $this->position_id->ViewValue = $this->position_id->Lookup !== null && is_array($this->position_id->Lookup->Options) ? $curVal : null;
                }
                if ($this->position_id->ViewValue !== null) { // Load from cache
                    $this->position_id->EditValue = array_values($this->position_id->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`position_id`" . SearchString("=", $this->position_id->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->position_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->position_id->EditValue = $arwrk;
                }
                $this->position_id->PlaceHolder = RemoveHtml($this->position_id->caption());
            }

            // religion
            $this->religion->EditAttrs["class"] = "form-control";
            $this->religion->EditCustomAttributes = "";
            $this->religion->EditValue = $this->religion->options(true);
            $this->religion->PlaceHolder = RemoveHtml($this->religion->caption());

            // status_id
            $this->status_id->EditAttrs["class"] = "form-control";
            $this->status_id->EditCustomAttributes = "";
            if ($this->status_id->getSessionValue() != "") {
                $this->status_id->CurrentValue = GetForeignKeyValue($this->status_id->getSessionValue());
                $this->status_id->OldValue = $this->status_id->CurrentValue;
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
            } else {
                $curVal = trim(strval($this->status_id->CurrentValue));
                if ($curVal != "") {
                    $this->status_id->ViewValue = $this->status_id->lookupCacheOption($curVal);
                } else {
                    $this->status_id->ViewValue = $this->status_id->Lookup !== null && is_array($this->status_id->Lookup->Options) ? $curVal : null;
                }
                if ($this->status_id->ViewValue !== null) { // Load from cache
                    $this->status_id->EditValue = array_values($this->status_id->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`status_id`" . SearchString("=", $this->status_id->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->status_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->status_id->EditValue = $arwrk;
                }
                $this->status_id->PlaceHolder = RemoveHtml($this->status_id->caption());
            }

            // skill_id
            $this->skill_id->EditAttrs["class"] = "form-control";
            $this->skill_id->EditCustomAttributes = "";
            if ($this->skill_id->getSessionValue() != "") {
                $this->skill_id->CurrentValue = GetForeignKeyValue($this->skill_id->getSessionValue());
                $this->skill_id->OldValue = $this->skill_id->CurrentValue;
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
            } else {
                $curVal = trim(strval($this->skill_id->CurrentValue));
                if ($curVal != "") {
                    $this->skill_id->ViewValue = $this->skill_id->lookupCacheOption($curVal);
                } else {
                    $this->skill_id->ViewValue = $this->skill_id->Lookup !== null && is_array($this->skill_id->Lookup->Options) ? $curVal : null;
                }
                if ($this->skill_id->ViewValue !== null) { // Load from cache
                    $this->skill_id->EditValue = array_values($this->skill_id->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`skill_id`" . SearchString("=", $this->skill_id->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->skill_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->skill_id->EditValue = $arwrk;
                }
                $this->skill_id->PlaceHolder = RemoveHtml($this->skill_id->caption());
            }

            // office_id
            $this->office_id->EditAttrs["class"] = "form-control";
            $this->office_id->EditCustomAttributes = "";
            if ($this->office_id->getSessionValue() != "") {
                $this->office_id->CurrentValue = GetForeignKeyValue($this->office_id->getSessionValue());
                $this->office_id->OldValue = $this->office_id->CurrentValue;
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
            } else {
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
            }

            // hire_date
            $this->hire_date->EditAttrs["class"] = "form-control";
            $this->hire_date->EditCustomAttributes = "";
            $this->hire_date->EditValue = HtmlEncode(FormatDateTime($this->hire_date->CurrentValue, 5));
            $this->hire_date->PlaceHolder = RemoveHtml($this->hire_date->caption());

            // termination_date
            $this->termination_date->EditAttrs["class"] = "form-control";
            $this->termination_date->EditCustomAttributes = "";
            $this->termination_date->EditValue = HtmlEncode(FormatDateTime($this->termination_date->CurrentValue, 5));
            $this->termination_date->PlaceHolder = RemoveHtml($this->termination_date->caption());

            // Add refer script

            // employee_name
            $this->employee_name->LinkCustomAttributes = "";
            $this->employee_name->HrefValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";

            // employee_password
            $this->employee_password->LinkCustomAttributes = "";
            $this->employee_password->HrefValue = "";

            // employee_email
            $this->employee_email->LinkCustomAttributes = "";
            $this->employee_email->HrefValue = "";

            // birth_date
            $this->birth_date->LinkCustomAttributes = "";
            $this->birth_date->HrefValue = "";

            // nik
            $this->nik->LinkCustomAttributes = "";
            $this->nik->HrefValue = "";

            // npwp
            $this->npwp->LinkCustomAttributes = "";
            $this->npwp->HrefValue = "";

            // address
            $this->address->LinkCustomAttributes = "";
            $this->address->HrefValue = "";

            // city_id
            $this->city_id->LinkCustomAttributes = "";
            $this->city_id->HrefValue = "";

            // postal_code
            $this->postal_code->LinkCustomAttributes = "";
            $this->postal_code->HrefValue = "";

            // bank_number
            $this->bank_number->LinkCustomAttributes = "";
            $this->bank_number->HrefValue = "";

            // bank_name
            $this->bank_name->LinkCustomAttributes = "";
            $this->bank_name->HrefValue = "";

            // scan_ktp
            $this->scan_ktp->LinkCustomAttributes = "";
            if (!EmptyValue($this->scan_ktp->Upload->DbValue)) {
                $this->scan_ktp->HrefValue = GetFileUploadUrl($this->scan_ktp, $this->scan_ktp->htmlDecode($this->scan_ktp->Upload->DbValue)); // Add prefix/suffix
                $this->scan_ktp->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->scan_ktp->HrefValue = FullUrl($this->scan_ktp->HrefValue, "href");
                }
            } else {
                $this->scan_ktp->HrefValue = "";
            }
            $this->scan_ktp->ExportHrefValue = $this->scan_ktp->UploadPath . $this->scan_ktp->Upload->DbValue;

            // scan_npwp
            $this->scan_npwp->LinkCustomAttributes = "";
            if (!EmptyValue($this->scan_npwp->Upload->DbValue)) {
                $this->scan_npwp->HrefValue = GetFileUploadUrl($this->scan_npwp, $this->scan_npwp->htmlDecode($this->scan_npwp->Upload->DbValue)); // Add prefix/suffix
                $this->scan_npwp->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->scan_npwp->HrefValue = FullUrl($this->scan_npwp->HrefValue, "href");
                }
            } else {
                $this->scan_npwp->HrefValue = "";
            }
            $this->scan_npwp->ExportHrefValue = $this->scan_npwp->UploadPath . $this->scan_npwp->Upload->DbValue;

            // curiculum_vitae
            $this->curiculum_vitae->LinkCustomAttributes = "";
            if (!EmptyValue($this->curiculum_vitae->Upload->DbValue)) {
                $this->curiculum_vitae->HrefValue = GetFileUploadUrl($this->curiculum_vitae, $this->curiculum_vitae->htmlDecode($this->curiculum_vitae->Upload->DbValue)); // Add prefix/suffix
                $this->curiculum_vitae->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->curiculum_vitae->HrefValue = FullUrl($this->curiculum_vitae->HrefValue, "href");
                }
            } else {
                $this->curiculum_vitae->HrefValue = "";
            }
            $this->curiculum_vitae->ExportHrefValue = $this->curiculum_vitae->UploadPath . $this->curiculum_vitae->Upload->DbValue;

            // technical_skill
            $this->technical_skill->LinkCustomAttributes = "";
            $this->technical_skill->HrefValue = "";

            // about_me
            $this->about_me->LinkCustomAttributes = "";
            $this->about_me->HrefValue = "";

            // position_id
            $this->position_id->LinkCustomAttributes = "";
            $this->position_id->HrefValue = "";

            // religion
            $this->religion->LinkCustomAttributes = "";
            $this->religion->HrefValue = "";

            // status_id
            $this->status_id->LinkCustomAttributes = "";
            $this->status_id->HrefValue = "";

            // skill_id
            $this->skill_id->LinkCustomAttributes = "";
            $this->skill_id->HrefValue = "";

            // office_id
            $this->office_id->LinkCustomAttributes = "";
            $this->office_id->HrefValue = "";

            // hire_date
            $this->hire_date->LinkCustomAttributes = "";
            $this->hire_date->HrefValue = "";

            // termination_date
            $this->termination_date->LinkCustomAttributes = "";
            $this->termination_date->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // employee_name
            $this->employee_name->EditAttrs["class"] = "form-control";
            $this->employee_name->EditCustomAttributes = "";
            if (!$this->employee_name->Raw) {
                $this->employee_name->CurrentValue = HtmlDecode($this->employee_name->CurrentValue);
            }
            $this->employee_name->EditValue = HtmlEncode($this->employee_name->CurrentValue);
            $this->employee_name->PlaceHolder = RemoveHtml($this->employee_name->caption());

            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            if (!$this->employee_username->Raw) {
                $this->employee_username->CurrentValue = HtmlDecode($this->employee_username->CurrentValue);
            }
            $this->employee_username->EditValue = HtmlEncode($this->employee_username->CurrentValue);
            $this->employee_username->PlaceHolder = RemoveHtml($this->employee_username->caption());

            // employee_password
            $this->employee_password->EditAttrs["class"] = "form-control";
            $this->employee_password->EditCustomAttributes = "";
            $this->employee_password->EditValue = $Language->phrase("PasswordMask"); // Show as masked password
            $this->employee_password->PlaceHolder = RemoveHtml($this->employee_password->caption());

            // employee_email
            $this->employee_email->EditAttrs["class"] = "form-control";
            $this->employee_email->EditCustomAttributes = "";
            if (!$this->employee_email->Raw) {
                $this->employee_email->CurrentValue = HtmlDecode($this->employee_email->CurrentValue);
            }
            $this->employee_email->EditValue = HtmlEncode($this->employee_email->CurrentValue);
            $this->employee_email->PlaceHolder = RemoveHtml($this->employee_email->caption());

            // birth_date
            $this->birth_date->EditAttrs["class"] = "form-control";
            $this->birth_date->EditCustomAttributes = "";
            $this->birth_date->EditValue = HtmlEncode(FormatDateTime($this->birth_date->CurrentValue, 5));
            $this->birth_date->PlaceHolder = RemoveHtml($this->birth_date->caption());

            // nik
            $this->nik->EditAttrs["class"] = "form-control";
            $this->nik->EditCustomAttributes = "";
            if (!$this->nik->Raw) {
                $this->nik->CurrentValue = HtmlDecode($this->nik->CurrentValue);
            }
            $this->nik->EditValue = HtmlEncode($this->nik->CurrentValue);
            $this->nik->PlaceHolder = RemoveHtml($this->nik->caption());

            // npwp
            $this->npwp->EditAttrs["class"] = "form-control";
            $this->npwp->EditCustomAttributes = "";
            if (!$this->npwp->Raw) {
                $this->npwp->CurrentValue = HtmlDecode($this->npwp->CurrentValue);
            }
            $this->npwp->EditValue = HtmlEncode($this->npwp->CurrentValue);
            $this->npwp->PlaceHolder = RemoveHtml($this->npwp->caption());

            // address
            $this->address->EditAttrs["class"] = "form-control";
            $this->address->EditCustomAttributes = "";
            if (!$this->address->Raw) {
                $this->address->CurrentValue = HtmlDecode($this->address->CurrentValue);
            }
            $this->address->EditValue = HtmlEncode($this->address->CurrentValue);
            $this->address->PlaceHolder = RemoveHtml($this->address->caption());

            // city_id
            $this->city_id->EditAttrs["class"] = "form-control";
            $this->city_id->EditCustomAttributes = "";
            if ($this->city_id->getSessionValue() != "") {
                $this->city_id->CurrentValue = GetForeignKeyValue($this->city_id->getSessionValue());
                $this->city_id->OldValue = $this->city_id->CurrentValue;
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

            // postal_code
            $this->postal_code->EditAttrs["class"] = "form-control";
            $this->postal_code->EditCustomAttributes = "";
            if (!$this->postal_code->Raw) {
                $this->postal_code->CurrentValue = HtmlDecode($this->postal_code->CurrentValue);
            }
            $this->postal_code->EditValue = HtmlEncode($this->postal_code->CurrentValue);
            $this->postal_code->PlaceHolder = RemoveHtml($this->postal_code->caption());

            // bank_number
            $this->bank_number->EditAttrs["class"] = "form-control";
            $this->bank_number->EditCustomAttributes = "";
            if (!$this->bank_number->Raw) {
                $this->bank_number->CurrentValue = HtmlDecode($this->bank_number->CurrentValue);
            }
            $this->bank_number->EditValue = HtmlEncode($this->bank_number->CurrentValue);
            $this->bank_number->PlaceHolder = RemoveHtml($this->bank_number->caption());

            // bank_name
            $this->bank_name->EditAttrs["class"] = "form-control";
            $this->bank_name->EditCustomAttributes = "";
            if (!$this->bank_name->Raw) {
                $this->bank_name->CurrentValue = HtmlDecode($this->bank_name->CurrentValue);
            }
            $this->bank_name->EditValue = HtmlEncode($this->bank_name->CurrentValue);
            $this->bank_name->PlaceHolder = RemoveHtml($this->bank_name->caption());

            // scan_ktp
            $this->scan_ktp->EditAttrs["class"] = "form-control";
            $this->scan_ktp->EditCustomAttributes = "";
            if (!EmptyValue($this->scan_ktp->Upload->DbValue)) {
                $this->scan_ktp->EditValue = $this->scan_ktp->Upload->DbValue;
            } else {
                $this->scan_ktp->EditValue = "";
            }
            if (!EmptyValue($this->scan_ktp->CurrentValue)) {
                $this->scan_ktp->Upload->FileName = $this->scan_ktp->CurrentValue;
            }
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->scan_ktp, $this->RowIndex);
            }

            // scan_npwp
            $this->scan_npwp->EditAttrs["class"] = "form-control";
            $this->scan_npwp->EditCustomAttributes = "";
            if (!EmptyValue($this->scan_npwp->Upload->DbValue)) {
                $this->scan_npwp->EditValue = $this->scan_npwp->Upload->DbValue;
            } else {
                $this->scan_npwp->EditValue = "";
            }
            if (!EmptyValue($this->scan_npwp->CurrentValue)) {
                $this->scan_npwp->Upload->FileName = $this->scan_npwp->CurrentValue;
            }
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->scan_npwp, $this->RowIndex);
            }

            // curiculum_vitae
            $this->curiculum_vitae->EditAttrs["class"] = "form-control";
            $this->curiculum_vitae->EditCustomAttributes = "";
            if (!EmptyValue($this->curiculum_vitae->Upload->DbValue)) {
                $this->curiculum_vitae->EditValue = $this->curiculum_vitae->Upload->DbValue;
            } else {
                $this->curiculum_vitae->EditValue = "";
            }
            if (!EmptyValue($this->curiculum_vitae->CurrentValue)) {
                $this->curiculum_vitae->Upload->FileName = $this->curiculum_vitae->CurrentValue;
            }
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->curiculum_vitae, $this->RowIndex);
            }

            // technical_skill
            $this->technical_skill->EditAttrs["class"] = "form-control";
            $this->technical_skill->EditCustomAttributes = "";
            $this->technical_skill->EditValue = HtmlEncode($this->technical_skill->CurrentValue);
            $this->technical_skill->PlaceHolder = RemoveHtml($this->technical_skill->caption());

            // about_me
            $this->about_me->EditAttrs["class"] = "form-control";
            $this->about_me->EditCustomAttributes = "";
            $this->about_me->EditValue = HtmlEncode($this->about_me->CurrentValue);
            $this->about_me->PlaceHolder = RemoveHtml($this->about_me->caption());

            // position_id
            $this->position_id->EditAttrs["class"] = "form-control";
            $this->position_id->EditCustomAttributes = "";
            if ($this->position_id->getSessionValue() != "") {
                $this->position_id->CurrentValue = GetForeignKeyValue($this->position_id->getSessionValue());
                $this->position_id->OldValue = $this->position_id->CurrentValue;
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
            } else {
                $curVal = trim(strval($this->position_id->CurrentValue));
                if ($curVal != "") {
                    $this->position_id->ViewValue = $this->position_id->lookupCacheOption($curVal);
                } else {
                    $this->position_id->ViewValue = $this->position_id->Lookup !== null && is_array($this->position_id->Lookup->Options) ? $curVal : null;
                }
                if ($this->position_id->ViewValue !== null) { // Load from cache
                    $this->position_id->EditValue = array_values($this->position_id->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`position_id`" . SearchString("=", $this->position_id->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->position_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->position_id->EditValue = $arwrk;
                }
                $this->position_id->PlaceHolder = RemoveHtml($this->position_id->caption());
            }

            // religion
            $this->religion->EditAttrs["class"] = "form-control";
            $this->religion->EditCustomAttributes = "";
            $this->religion->EditValue = $this->religion->options(true);
            $this->religion->PlaceHolder = RemoveHtml($this->religion->caption());

            // status_id
            $this->status_id->EditAttrs["class"] = "form-control";
            $this->status_id->EditCustomAttributes = "";
            if ($this->status_id->getSessionValue() != "") {
                $this->status_id->CurrentValue = GetForeignKeyValue($this->status_id->getSessionValue());
                $this->status_id->OldValue = $this->status_id->CurrentValue;
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
            } else {
                $curVal = trim(strval($this->status_id->CurrentValue));
                if ($curVal != "") {
                    $this->status_id->ViewValue = $this->status_id->lookupCacheOption($curVal);
                } else {
                    $this->status_id->ViewValue = $this->status_id->Lookup !== null && is_array($this->status_id->Lookup->Options) ? $curVal : null;
                }
                if ($this->status_id->ViewValue !== null) { // Load from cache
                    $this->status_id->EditValue = array_values($this->status_id->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`status_id`" . SearchString("=", $this->status_id->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->status_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->status_id->EditValue = $arwrk;
                }
                $this->status_id->PlaceHolder = RemoveHtml($this->status_id->caption());
            }

            // skill_id
            $this->skill_id->EditAttrs["class"] = "form-control";
            $this->skill_id->EditCustomAttributes = "";
            if ($this->skill_id->getSessionValue() != "") {
                $this->skill_id->CurrentValue = GetForeignKeyValue($this->skill_id->getSessionValue());
                $this->skill_id->OldValue = $this->skill_id->CurrentValue;
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
            } else {
                $curVal = trim(strval($this->skill_id->CurrentValue));
                if ($curVal != "") {
                    $this->skill_id->ViewValue = $this->skill_id->lookupCacheOption($curVal);
                } else {
                    $this->skill_id->ViewValue = $this->skill_id->Lookup !== null && is_array($this->skill_id->Lookup->Options) ? $curVal : null;
                }
                if ($this->skill_id->ViewValue !== null) { // Load from cache
                    $this->skill_id->EditValue = array_values($this->skill_id->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`skill_id`" . SearchString("=", $this->skill_id->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->skill_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->skill_id->EditValue = $arwrk;
                }
                $this->skill_id->PlaceHolder = RemoveHtml($this->skill_id->caption());
            }

            // office_id
            $this->office_id->EditAttrs["class"] = "form-control";
            $this->office_id->EditCustomAttributes = "";
            if ($this->office_id->getSessionValue() != "") {
                $this->office_id->CurrentValue = GetForeignKeyValue($this->office_id->getSessionValue());
                $this->office_id->OldValue = $this->office_id->CurrentValue;
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
            } else {
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
            }

            // hire_date
            $this->hire_date->EditAttrs["class"] = "form-control";
            $this->hire_date->EditCustomAttributes = "";
            $this->hire_date->EditValue = HtmlEncode(FormatDateTime($this->hire_date->CurrentValue, 5));
            $this->hire_date->PlaceHolder = RemoveHtml($this->hire_date->caption());

            // termination_date
            $this->termination_date->EditAttrs["class"] = "form-control";
            $this->termination_date->EditCustomAttributes = "";
            $this->termination_date->EditValue = HtmlEncode(FormatDateTime($this->termination_date->CurrentValue, 5));
            $this->termination_date->PlaceHolder = RemoveHtml($this->termination_date->caption());

            // Edit refer script

            // employee_name
            $this->employee_name->LinkCustomAttributes = "";
            $this->employee_name->HrefValue = "";

            // employee_username
            $this->employee_username->LinkCustomAttributes = "";
            $this->employee_username->HrefValue = "";

            // employee_password
            $this->employee_password->LinkCustomAttributes = "";
            $this->employee_password->HrefValue = "";

            // employee_email
            $this->employee_email->LinkCustomAttributes = "";
            $this->employee_email->HrefValue = "";

            // birth_date
            $this->birth_date->LinkCustomAttributes = "";
            $this->birth_date->HrefValue = "";

            // nik
            $this->nik->LinkCustomAttributes = "";
            $this->nik->HrefValue = "";

            // npwp
            $this->npwp->LinkCustomAttributes = "";
            $this->npwp->HrefValue = "";

            // address
            $this->address->LinkCustomAttributes = "";
            $this->address->HrefValue = "";

            // city_id
            $this->city_id->LinkCustomAttributes = "";
            $this->city_id->HrefValue = "";

            // postal_code
            $this->postal_code->LinkCustomAttributes = "";
            $this->postal_code->HrefValue = "";

            // bank_number
            $this->bank_number->LinkCustomAttributes = "";
            $this->bank_number->HrefValue = "";

            // bank_name
            $this->bank_name->LinkCustomAttributes = "";
            $this->bank_name->HrefValue = "";

            // scan_ktp
            $this->scan_ktp->LinkCustomAttributes = "";
            if (!EmptyValue($this->scan_ktp->Upload->DbValue)) {
                $this->scan_ktp->HrefValue = GetFileUploadUrl($this->scan_ktp, $this->scan_ktp->htmlDecode($this->scan_ktp->Upload->DbValue)); // Add prefix/suffix
                $this->scan_ktp->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->scan_ktp->HrefValue = FullUrl($this->scan_ktp->HrefValue, "href");
                }
            } else {
                $this->scan_ktp->HrefValue = "";
            }
            $this->scan_ktp->ExportHrefValue = $this->scan_ktp->UploadPath . $this->scan_ktp->Upload->DbValue;

            // scan_npwp
            $this->scan_npwp->LinkCustomAttributes = "";
            if (!EmptyValue($this->scan_npwp->Upload->DbValue)) {
                $this->scan_npwp->HrefValue = GetFileUploadUrl($this->scan_npwp, $this->scan_npwp->htmlDecode($this->scan_npwp->Upload->DbValue)); // Add prefix/suffix
                $this->scan_npwp->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->scan_npwp->HrefValue = FullUrl($this->scan_npwp->HrefValue, "href");
                }
            } else {
                $this->scan_npwp->HrefValue = "";
            }
            $this->scan_npwp->ExportHrefValue = $this->scan_npwp->UploadPath . $this->scan_npwp->Upload->DbValue;

            // curiculum_vitae
            $this->curiculum_vitae->LinkCustomAttributes = "";
            if (!EmptyValue($this->curiculum_vitae->Upload->DbValue)) {
                $this->curiculum_vitae->HrefValue = GetFileUploadUrl($this->curiculum_vitae, $this->curiculum_vitae->htmlDecode($this->curiculum_vitae->Upload->DbValue)); // Add prefix/suffix
                $this->curiculum_vitae->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->curiculum_vitae->HrefValue = FullUrl($this->curiculum_vitae->HrefValue, "href");
                }
            } else {
                $this->curiculum_vitae->HrefValue = "";
            }
            $this->curiculum_vitae->ExportHrefValue = $this->curiculum_vitae->UploadPath . $this->curiculum_vitae->Upload->DbValue;

            // technical_skill
            $this->technical_skill->LinkCustomAttributes = "";
            $this->technical_skill->HrefValue = "";

            // about_me
            $this->about_me->LinkCustomAttributes = "";
            $this->about_me->HrefValue = "";

            // position_id
            $this->position_id->LinkCustomAttributes = "";
            $this->position_id->HrefValue = "";

            // religion
            $this->religion->LinkCustomAttributes = "";
            $this->religion->HrefValue = "";

            // status_id
            $this->status_id->LinkCustomAttributes = "";
            $this->status_id->HrefValue = "";

            // skill_id
            $this->skill_id->LinkCustomAttributes = "";
            $this->skill_id->HrefValue = "";

            // office_id
            $this->office_id->LinkCustomAttributes = "";
            $this->office_id->HrefValue = "";

            // hire_date
            $this->hire_date->LinkCustomAttributes = "";
            $this->hire_date->HrefValue = "";

            // termination_date
            $this->termination_date->LinkCustomAttributes = "";
            $this->termination_date->HrefValue = "";
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
        if ($this->employee_name->Required) {
            if (!$this->employee_name->IsDetailKey && EmptyValue($this->employee_name->FormValue)) {
                $this->employee_name->addErrorMessage(str_replace("%s", $this->employee_name->caption(), $this->employee_name->RequiredErrorMessage));
            }
        }
        if ($this->employee_username->Required) {
            if (!$this->employee_username->IsDetailKey && EmptyValue($this->employee_username->FormValue)) {
                $this->employee_username->addErrorMessage(str_replace("%s", $this->employee_username->caption(), $this->employee_username->RequiredErrorMessage));
            }
        }
        if ($this->employee_password->Required) {
            if (!$this->employee_password->IsDetailKey && EmptyValue($this->employee_password->FormValue)) {
                $this->employee_password->addErrorMessage(str_replace("%s", $this->employee_password->caption(), $this->employee_password->RequiredErrorMessage));
            }
        }
        if (!$this->employee_password->Raw && Config("REMOVE_XSS") && CheckPassword($this->employee_password->FormValue)) {
            $this->employee_password->addErrorMessage($Language->phrase("InvalidPasswordChars"));
        }
        if ($this->employee_email->Required) {
            if (!$this->employee_email->IsDetailKey && EmptyValue($this->employee_email->FormValue)) {
                $this->employee_email->addErrorMessage(str_replace("%s", $this->employee_email->caption(), $this->employee_email->RequiredErrorMessage));
            }
        }
        if ($this->birth_date->Required) {
            if (!$this->birth_date->IsDetailKey && EmptyValue($this->birth_date->FormValue)) {
                $this->birth_date->addErrorMessage(str_replace("%s", $this->birth_date->caption(), $this->birth_date->RequiredErrorMessage));
            }
        }
        if (!CheckStdDate($this->birth_date->FormValue)) {
            $this->birth_date->addErrorMessage($this->birth_date->getErrorMessage(false));
        }
        if ($this->nik->Required) {
            if (!$this->nik->IsDetailKey && EmptyValue($this->nik->FormValue)) {
                $this->nik->addErrorMessage(str_replace("%s", $this->nik->caption(), $this->nik->RequiredErrorMessage));
            }
        }
        if ($this->npwp->Required) {
            if (!$this->npwp->IsDetailKey && EmptyValue($this->npwp->FormValue)) {
                $this->npwp->addErrorMessage(str_replace("%s", $this->npwp->caption(), $this->npwp->RequiredErrorMessage));
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
        if ($this->postal_code->Required) {
            if (!$this->postal_code->IsDetailKey && EmptyValue($this->postal_code->FormValue)) {
                $this->postal_code->addErrorMessage(str_replace("%s", $this->postal_code->caption(), $this->postal_code->RequiredErrorMessage));
            }
        }
        if ($this->bank_number->Required) {
            if (!$this->bank_number->IsDetailKey && EmptyValue($this->bank_number->FormValue)) {
                $this->bank_number->addErrorMessage(str_replace("%s", $this->bank_number->caption(), $this->bank_number->RequiredErrorMessage));
            }
        }
        if ($this->bank_name->Required) {
            if (!$this->bank_name->IsDetailKey && EmptyValue($this->bank_name->FormValue)) {
                $this->bank_name->addErrorMessage(str_replace("%s", $this->bank_name->caption(), $this->bank_name->RequiredErrorMessage));
            }
        }
        if ($this->scan_ktp->Required) {
            if ($this->scan_ktp->Upload->FileName == "" && !$this->scan_ktp->Upload->KeepFile) {
                $this->scan_ktp->addErrorMessage(str_replace("%s", $this->scan_ktp->caption(), $this->scan_ktp->RequiredErrorMessage));
            }
        }
        if ($this->scan_npwp->Required) {
            if ($this->scan_npwp->Upload->FileName == "" && !$this->scan_npwp->Upload->KeepFile) {
                $this->scan_npwp->addErrorMessage(str_replace("%s", $this->scan_npwp->caption(), $this->scan_npwp->RequiredErrorMessage));
            }
        }
        if ($this->curiculum_vitae->Required) {
            if ($this->curiculum_vitae->Upload->FileName == "" && !$this->curiculum_vitae->Upload->KeepFile) {
                $this->curiculum_vitae->addErrorMessage(str_replace("%s", $this->curiculum_vitae->caption(), $this->curiculum_vitae->RequiredErrorMessage));
            }
        }
        if ($this->technical_skill->Required) {
            if (!$this->technical_skill->IsDetailKey && EmptyValue($this->technical_skill->FormValue)) {
                $this->technical_skill->addErrorMessage(str_replace("%s", $this->technical_skill->caption(), $this->technical_skill->RequiredErrorMessage));
            }
        }
        if ($this->about_me->Required) {
            if (!$this->about_me->IsDetailKey && EmptyValue($this->about_me->FormValue)) {
                $this->about_me->addErrorMessage(str_replace("%s", $this->about_me->caption(), $this->about_me->RequiredErrorMessage));
            }
        }
        if ($this->position_id->Required) {
            if (!$this->position_id->IsDetailKey && EmptyValue($this->position_id->FormValue)) {
                $this->position_id->addErrorMessage(str_replace("%s", $this->position_id->caption(), $this->position_id->RequiredErrorMessage));
            }
        }
        if ($this->religion->Required) {
            if (!$this->religion->IsDetailKey && EmptyValue($this->religion->FormValue)) {
                $this->religion->addErrorMessage(str_replace("%s", $this->religion->caption(), $this->religion->RequiredErrorMessage));
            }
        }
        if ($this->status_id->Required) {
            if (!$this->status_id->IsDetailKey && EmptyValue($this->status_id->FormValue)) {
                $this->status_id->addErrorMessage(str_replace("%s", $this->status_id->caption(), $this->status_id->RequiredErrorMessage));
            }
        }
        if ($this->skill_id->Required) {
            if (!$this->skill_id->IsDetailKey && EmptyValue($this->skill_id->FormValue)) {
                $this->skill_id->addErrorMessage(str_replace("%s", $this->skill_id->caption(), $this->skill_id->RequiredErrorMessage));
            }
        }
        if ($this->office_id->Required) {
            if (!$this->office_id->IsDetailKey && EmptyValue($this->office_id->FormValue)) {
                $this->office_id->addErrorMessage(str_replace("%s", $this->office_id->caption(), $this->office_id->RequiredErrorMessage));
            }
        }
        if ($this->hire_date->Required) {
            if (!$this->hire_date->IsDetailKey && EmptyValue($this->hire_date->FormValue)) {
                $this->hire_date->addErrorMessage(str_replace("%s", $this->hire_date->caption(), $this->hire_date->RequiredErrorMessage));
            }
        }
        if (!CheckStdDate($this->hire_date->FormValue)) {
            $this->hire_date->addErrorMessage($this->hire_date->getErrorMessage(false));
        }
        if ($this->termination_date->Required) {
            if (!$this->termination_date->IsDetailKey && EmptyValue($this->termination_date->FormValue)) {
                $this->termination_date->addErrorMessage(str_replace("%s", $this->termination_date->caption(), $this->termination_date->RequiredErrorMessage));
            }
        }
        if (!CheckStdDate($this->termination_date->FormValue)) {
            $this->termination_date->addErrorMessage($this->termination_date->getErrorMessage(false));
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

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $deleteRows = true;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAll($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }

        // Clone old rows
        $rsold = $rows;

        // Call row deleting event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $deleteRows = $this->rowDeleting($row);
                if (!$deleteRows) {
                    break;
                }
            }
        }
        if ($deleteRows) {
            $key = "";
            foreach ($rsold as $row) {
                $thisKey = "";
                if ($thisKey != "") {
                    $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
                }
                $thisKey .= $row['employee_username'];
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }
                $deleteRows = $this->delete($row); // Delete
                if ($deleteRows === false) {
                    break;
                }
                if ($key != "") {
                    $key .= ", ";
                }
                $key .= $thisKey;
            }
        }
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }

        // Call Row Deleted event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $this->rowDeleted($row);
            }
        }

        // Write JSON for API request
        if (IsApi() && $deleteRows) {
            $row = $this->getRecordsFromRecordset($rsold);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $deleteRows;
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

            // employee_name
            $this->employee_name->setDbValueDef($rsnew, $this->employee_name->CurrentValue, "", $this->employee_name->ReadOnly);

            // employee_username
            $this->employee_username->setDbValueDef($rsnew, $this->employee_username->CurrentValue, "", $this->employee_username->ReadOnly);

            // employee_password
            if (!IsMaskedPassword($this->employee_password->CurrentValue)) {
                $this->employee_password->setDbValueDef($rsnew, $this->employee_password->CurrentValue, "", $this->employee_password->ReadOnly);
            }

            // employee_email
            $this->employee_email->setDbValueDef($rsnew, $this->employee_email->CurrentValue, "", $this->employee_email->ReadOnly);

            // birth_date
            $this->birth_date->setDbValueDef($rsnew, UnFormatDateTime($this->birth_date->CurrentValue, 5), null, $this->birth_date->ReadOnly);

            // nik
            $this->nik->setDbValueDef($rsnew, $this->nik->CurrentValue, "", $this->nik->ReadOnly);

            // npwp
            $this->npwp->setDbValueDef($rsnew, $this->npwp->CurrentValue, null, $this->npwp->ReadOnly);

            // address
            $this->address->setDbValueDef($rsnew, $this->address->CurrentValue, null, $this->address->ReadOnly);

            // city_id
            if ($this->city_id->getSessionValue() != "") {
                $this->city_id->ReadOnly = true;
            }
            $this->city_id->setDbValueDef($rsnew, $this->city_id->CurrentValue, null, $this->city_id->ReadOnly);

            // postal_code
            $this->postal_code->setDbValueDef($rsnew, $this->postal_code->CurrentValue, null, $this->postal_code->ReadOnly);

            // bank_number
            $this->bank_number->setDbValueDef($rsnew, $this->bank_number->CurrentValue, "", $this->bank_number->ReadOnly);

            // bank_name
            $this->bank_name->setDbValueDef($rsnew, $this->bank_name->CurrentValue, "", $this->bank_name->ReadOnly);

            // scan_ktp
            if ($this->scan_ktp->Visible && !$this->scan_ktp->ReadOnly && !$this->scan_ktp->Upload->KeepFile) {
                $this->scan_ktp->Upload->DbValue = $rsold['scan_ktp']; // Get original value
                if ($this->scan_ktp->Upload->FileName == "") {
                    $rsnew['scan_ktp'] = null;
                } else {
                    $rsnew['scan_ktp'] = $this->scan_ktp->Upload->FileName;
                }
            }

            // scan_npwp
            if ($this->scan_npwp->Visible && !$this->scan_npwp->ReadOnly && !$this->scan_npwp->Upload->KeepFile) {
                $this->scan_npwp->Upload->DbValue = $rsold['scan_npwp']; // Get original value
                if ($this->scan_npwp->Upload->FileName == "") {
                    $rsnew['scan_npwp'] = null;
                } else {
                    $rsnew['scan_npwp'] = $this->scan_npwp->Upload->FileName;
                }
            }

            // curiculum_vitae
            if ($this->curiculum_vitae->Visible && !$this->curiculum_vitae->ReadOnly && !$this->curiculum_vitae->Upload->KeepFile) {
                $this->curiculum_vitae->Upload->DbValue = $rsold['curiculum_vitae']; // Get original value
                if ($this->curiculum_vitae->Upload->FileName == "") {
                    $rsnew['curiculum_vitae'] = null;
                } else {
                    $rsnew['curiculum_vitae'] = $this->curiculum_vitae->Upload->FileName;
                }
            }

            // technical_skill
            $this->technical_skill->setDbValueDef($rsnew, $this->technical_skill->CurrentValue, null, $this->technical_skill->ReadOnly);

            // about_me
            $this->about_me->setDbValueDef($rsnew, $this->about_me->CurrentValue, null, $this->about_me->ReadOnly);

            // position_id
            if ($this->position_id->getSessionValue() != "") {
                $this->position_id->ReadOnly = true;
            }
            $this->position_id->setDbValueDef($rsnew, $this->position_id->CurrentValue, null, $this->position_id->ReadOnly);

            // religion
            $this->religion->setDbValueDef($rsnew, $this->religion->CurrentValue, null, $this->religion->ReadOnly);

            // status_id
            if ($this->status_id->getSessionValue() != "") {
                $this->status_id->ReadOnly = true;
            }
            $this->status_id->setDbValueDef($rsnew, $this->status_id->CurrentValue, null, $this->status_id->ReadOnly);

            // skill_id
            if ($this->skill_id->getSessionValue() != "") {
                $this->skill_id->ReadOnly = true;
            }
            $this->skill_id->setDbValueDef($rsnew, $this->skill_id->CurrentValue, null, $this->skill_id->ReadOnly);

            // office_id
            if ($this->office_id->getSessionValue() != "") {
                $this->office_id->ReadOnly = true;
            }
            $this->office_id->setDbValueDef($rsnew, $this->office_id->CurrentValue, null, $this->office_id->ReadOnly);

            // hire_date
            $this->hire_date->setDbValueDef($rsnew, UnFormatDateTime($this->hire_date->CurrentValue, 5), null, $this->hire_date->ReadOnly);

            // termination_date
            $this->termination_date->setDbValueDef($rsnew, UnFormatDateTime($this->termination_date->CurrentValue, 5), null, $this->termination_date->ReadOnly);
            if ($this->scan_ktp->Visible && !$this->scan_ktp->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->scan_ktp->Upload->DbValue) ? [] : [$this->scan_ktp->htmlDecode($this->scan_ktp->Upload->DbValue)];
                if (!EmptyValue($this->scan_ktp->Upload->FileName)) {
                    $newFiles = [$this->scan_ktp->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->scan_ktp, $this->scan_ktp->Upload->Index);
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
                                $file1 = UniqueFilename($this->scan_ktp->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->scan_ktp->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->scan_ktp->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->scan_ktp->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->scan_ktp->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->scan_ktp->setDbValueDef($rsnew, $this->scan_ktp->Upload->FileName, null, $this->scan_ktp->ReadOnly);
                }
            }
            if ($this->scan_npwp->Visible && !$this->scan_npwp->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->scan_npwp->Upload->DbValue) ? [] : [$this->scan_npwp->htmlDecode($this->scan_npwp->Upload->DbValue)];
                if (!EmptyValue($this->scan_npwp->Upload->FileName)) {
                    $newFiles = [$this->scan_npwp->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->scan_npwp, $this->scan_npwp->Upload->Index);
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
                                $file1 = UniqueFilename($this->scan_npwp->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->scan_npwp->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->scan_npwp->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->scan_npwp->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->scan_npwp->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->scan_npwp->setDbValueDef($rsnew, $this->scan_npwp->Upload->FileName, null, $this->scan_npwp->ReadOnly);
                }
            }
            if ($this->curiculum_vitae->Visible && !$this->curiculum_vitae->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->curiculum_vitae->Upload->DbValue) ? [] : [$this->curiculum_vitae->htmlDecode($this->curiculum_vitae->Upload->DbValue)];
                if (!EmptyValue($this->curiculum_vitae->Upload->FileName)) {
                    $newFiles = [$this->curiculum_vitae->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->curiculum_vitae, $this->curiculum_vitae->Upload->Index);
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
                                $file1 = UniqueFilename($this->curiculum_vitae->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->curiculum_vitae->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->curiculum_vitae->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->curiculum_vitae->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->curiculum_vitae->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->curiculum_vitae->setDbValueDef($rsnew, $this->curiculum_vitae->Upload->FileName, null, $this->curiculum_vitae->ReadOnly);
                }
            }

            // Call Row Updating event
            $updateRow = $this->rowUpdating($rsold, $rsnew);

            // Check for duplicate key when key changed
            if ($updateRow) {
                $newKeyFilter = $this->getRecordFilter($rsnew);
                if ($newKeyFilter != $oldKeyFilter) {
                    $rsChk = $this->loadRs($newKeyFilter)->fetch();
                    if ($rsChk !== false) {
                        $keyErrMsg = str_replace("%f", $newKeyFilter, $Language->phrase("DupKey"));
                        $this->setFailureMessage($keyErrMsg);
                        $updateRow = false;
                    }
                }
            }
            if ($updateRow) {
                if (count($rsnew) > 0) {
                    $editRow = $this->update($rsnew, "", $rsold);
                } else {
                    $editRow = true; // No field to update
                }
                if ($editRow) {
                    if ($this->scan_ktp->Visible && !$this->scan_ktp->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->scan_ktp->Upload->DbValue) ? [] : [$this->scan_ktp->htmlDecode($this->scan_ktp->Upload->DbValue)];
                        if (!EmptyValue($this->scan_ktp->Upload->FileName)) {
                            $newFiles = [$this->scan_ktp->Upload->FileName];
                            $newFiles2 = [$this->scan_ktp->htmlDecode($rsnew['scan_ktp'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->scan_ktp, $this->scan_ktp->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->scan_ktp->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->scan_ktp->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->scan_npwp->Visible && !$this->scan_npwp->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->scan_npwp->Upload->DbValue) ? [] : [$this->scan_npwp->htmlDecode($this->scan_npwp->Upload->DbValue)];
                        if (!EmptyValue($this->scan_npwp->Upload->FileName)) {
                            $newFiles = [$this->scan_npwp->Upload->FileName];
                            $newFiles2 = [$this->scan_npwp->htmlDecode($rsnew['scan_npwp'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->scan_npwp, $this->scan_npwp->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->scan_npwp->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->scan_npwp->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->curiculum_vitae->Visible && !$this->curiculum_vitae->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->curiculum_vitae->Upload->DbValue) ? [] : [$this->curiculum_vitae->htmlDecode($this->curiculum_vitae->Upload->DbValue)];
                        if (!EmptyValue($this->curiculum_vitae->Upload->FileName)) {
                            $newFiles = [$this->curiculum_vitae->Upload->FileName];
                            $newFiles2 = [$this->curiculum_vitae->htmlDecode($rsnew['curiculum_vitae'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->curiculum_vitae, $this->curiculum_vitae->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->curiculum_vitae->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->curiculum_vitae->oldPhysicalUploadPath() . $oldFile);
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
            // scan_ktp
            CleanUploadTempPath($this->scan_ktp, $this->scan_ktp->Upload->Index);

            // scan_npwp
            CleanUploadTempPath($this->scan_npwp, $this->scan_npwp->Upload->Index);

            // curiculum_vitae
            CleanUploadTempPath($this->curiculum_vitae, $this->curiculum_vitae->Upload->Index);
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "master_office") {
            $this->office_id->CurrentValue = $this->office_id->getSessionValue();
        }
        if ($this->getCurrentMasterTable() == "master_position") {
            $this->position_id->CurrentValue = $this->position_id->getSessionValue();
        }
        if ($this->getCurrentMasterTable() == "master_skill") {
            $this->skill_id->CurrentValue = $this->skill_id->getSessionValue();
        }
        if ($this->getCurrentMasterTable() == "master_status") {
            $this->status_id->CurrentValue = $this->status_id->getSessionValue();
        }
        if ($this->getCurrentMasterTable() == "master_city") {
            $this->city_id->CurrentValue = $this->city_id->getSessionValue();
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // employee_name
        $this->employee_name->setDbValueDef($rsnew, $this->employee_name->CurrentValue, "", false);

        // employee_username
        $this->employee_username->setDbValueDef($rsnew, $this->employee_username->CurrentValue, "", false);

        // employee_password
        if (!IsMaskedPassword($this->employee_password->CurrentValue)) {
            $this->employee_password->setDbValueDef($rsnew, $this->employee_password->CurrentValue, "", false);
        }

        // employee_email
        $this->employee_email->setDbValueDef($rsnew, $this->employee_email->CurrentValue, "", false);

        // birth_date
        $this->birth_date->setDbValueDef($rsnew, UnFormatDateTime($this->birth_date->CurrentValue, 5), null, false);

        // nik
        $this->nik->setDbValueDef($rsnew, $this->nik->CurrentValue, "", false);

        // npwp
        $this->npwp->setDbValueDef($rsnew, $this->npwp->CurrentValue, null, false);

        // address
        $this->address->setDbValueDef($rsnew, $this->address->CurrentValue, null, false);

        // city_id
        $this->city_id->setDbValueDef($rsnew, $this->city_id->CurrentValue, null, false);

        // postal_code
        $this->postal_code->setDbValueDef($rsnew, $this->postal_code->CurrentValue, null, false);

        // bank_number
        $this->bank_number->setDbValueDef($rsnew, $this->bank_number->CurrentValue, "", false);

        // bank_name
        $this->bank_name->setDbValueDef($rsnew, $this->bank_name->CurrentValue, "", false);

        // scan_ktp
        if ($this->scan_ktp->Visible && !$this->scan_ktp->Upload->KeepFile) {
            $this->scan_ktp->Upload->DbValue = ""; // No need to delete old file
            if ($this->scan_ktp->Upload->FileName == "") {
                $rsnew['scan_ktp'] = null;
            } else {
                $rsnew['scan_ktp'] = $this->scan_ktp->Upload->FileName;
            }
        }

        // scan_npwp
        if ($this->scan_npwp->Visible && !$this->scan_npwp->Upload->KeepFile) {
            $this->scan_npwp->Upload->DbValue = ""; // No need to delete old file
            if ($this->scan_npwp->Upload->FileName == "") {
                $rsnew['scan_npwp'] = null;
            } else {
                $rsnew['scan_npwp'] = $this->scan_npwp->Upload->FileName;
            }
        }

        // curiculum_vitae
        if ($this->curiculum_vitae->Visible && !$this->curiculum_vitae->Upload->KeepFile) {
            $this->curiculum_vitae->Upload->DbValue = ""; // No need to delete old file
            if ($this->curiculum_vitae->Upload->FileName == "") {
                $rsnew['curiculum_vitae'] = null;
            } else {
                $rsnew['curiculum_vitae'] = $this->curiculum_vitae->Upload->FileName;
            }
        }

        // technical_skill
        $this->technical_skill->setDbValueDef($rsnew, $this->technical_skill->CurrentValue, null, false);

        // about_me
        $this->about_me->setDbValueDef($rsnew, $this->about_me->CurrentValue, null, false);

        // position_id
        $this->position_id->setDbValueDef($rsnew, $this->position_id->CurrentValue, null, false);

        // religion
        $this->religion->setDbValueDef($rsnew, $this->religion->CurrentValue, null, false);

        // status_id
        $this->status_id->setDbValueDef($rsnew, $this->status_id->CurrentValue, null, false);

        // skill_id
        $this->skill_id->setDbValueDef($rsnew, $this->skill_id->CurrentValue, null, false);

        // office_id
        $this->office_id->setDbValueDef($rsnew, $this->office_id->CurrentValue, null, false);

        // hire_date
        $this->hire_date->setDbValueDef($rsnew, UnFormatDateTime($this->hire_date->CurrentValue, 5), null, false);

        // termination_date
        $this->termination_date->setDbValueDef($rsnew, UnFormatDateTime($this->termination_date->CurrentValue, 5), null, false);
        if ($this->scan_ktp->Visible && !$this->scan_ktp->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->scan_ktp->Upload->DbValue) ? [] : [$this->scan_ktp->htmlDecode($this->scan_ktp->Upload->DbValue)];
            if (!EmptyValue($this->scan_ktp->Upload->FileName)) {
                $newFiles = [$this->scan_ktp->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->scan_ktp, $this->scan_ktp->Upload->Index);
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
                            $file1 = UniqueFilename($this->scan_ktp->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->scan_ktp->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->scan_ktp->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->scan_ktp->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->scan_ktp->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->scan_ktp->setDbValueDef($rsnew, $this->scan_ktp->Upload->FileName, null, false);
            }
        }
        if ($this->scan_npwp->Visible && !$this->scan_npwp->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->scan_npwp->Upload->DbValue) ? [] : [$this->scan_npwp->htmlDecode($this->scan_npwp->Upload->DbValue)];
            if (!EmptyValue($this->scan_npwp->Upload->FileName)) {
                $newFiles = [$this->scan_npwp->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->scan_npwp, $this->scan_npwp->Upload->Index);
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
                            $file1 = UniqueFilename($this->scan_npwp->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->scan_npwp->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->scan_npwp->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->scan_npwp->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->scan_npwp->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->scan_npwp->setDbValueDef($rsnew, $this->scan_npwp->Upload->FileName, null, false);
            }
        }
        if ($this->curiculum_vitae->Visible && !$this->curiculum_vitae->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->curiculum_vitae->Upload->DbValue) ? [] : [$this->curiculum_vitae->htmlDecode($this->curiculum_vitae->Upload->DbValue)];
            if (!EmptyValue($this->curiculum_vitae->Upload->FileName)) {
                $newFiles = [$this->curiculum_vitae->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->curiculum_vitae, $this->curiculum_vitae->Upload->Index);
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
                            $file1 = UniqueFilename($this->curiculum_vitae->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->curiculum_vitae->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->curiculum_vitae->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->curiculum_vitae->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->curiculum_vitae->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->curiculum_vitae->setDbValueDef($rsnew, $this->curiculum_vitae->Upload->FileName, null, false);
            }
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);

        // Check if key value entered
        if ($insertRow && $this->ValidateKey && strval($rsnew['employee_username']) == "") {
            $this->setFailureMessage($Language->phrase("InvalidKeyValue"));
            $insertRow = false;
        }

        // Check for duplicate key
        if ($insertRow && $this->ValidateKey) {
            $filter = $this->getRecordFilter($rsnew);
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $keyErrMsg = str_replace("%f", $filter, $Language->phrase("DupKey"));
                $this->setFailureMessage($keyErrMsg);
                $insertRow = false;
            }
        }
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->scan_ktp->Visible && !$this->scan_ktp->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->scan_ktp->Upload->DbValue) ? [] : [$this->scan_ktp->htmlDecode($this->scan_ktp->Upload->DbValue)];
                    if (!EmptyValue($this->scan_ktp->Upload->FileName)) {
                        $newFiles = [$this->scan_ktp->Upload->FileName];
                        $newFiles2 = [$this->scan_ktp->htmlDecode($rsnew['scan_ktp'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->scan_ktp, $this->scan_ktp->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->scan_ktp->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->scan_ktp->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->scan_npwp->Visible && !$this->scan_npwp->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->scan_npwp->Upload->DbValue) ? [] : [$this->scan_npwp->htmlDecode($this->scan_npwp->Upload->DbValue)];
                    if (!EmptyValue($this->scan_npwp->Upload->FileName)) {
                        $newFiles = [$this->scan_npwp->Upload->FileName];
                        $newFiles2 = [$this->scan_npwp->htmlDecode($rsnew['scan_npwp'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->scan_npwp, $this->scan_npwp->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->scan_npwp->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->scan_npwp->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->curiculum_vitae->Visible && !$this->curiculum_vitae->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->curiculum_vitae->Upload->DbValue) ? [] : [$this->curiculum_vitae->htmlDecode($this->curiculum_vitae->Upload->DbValue)];
                    if (!EmptyValue($this->curiculum_vitae->Upload->FileName)) {
                        $newFiles = [$this->curiculum_vitae->Upload->FileName];
                        $newFiles2 = [$this->curiculum_vitae->htmlDecode($rsnew['curiculum_vitae'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->curiculum_vitae, $this->curiculum_vitae->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->curiculum_vitae->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->curiculum_vitae->oldPhysicalUploadPath() . $oldFile);
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
            // scan_ktp
            CleanUploadTempPath($this->scan_ktp, $this->scan_ktp->Upload->Index);

            // scan_npwp
            CleanUploadTempPath($this->scan_npwp, $this->scan_npwp->Upload->Index);

            // curiculum_vitae
            CleanUploadTempPath($this->curiculum_vitae, $this->curiculum_vitae->Upload->Index);
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
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "master_office") {
            $masterTbl = Container("master_office");
            $this->office_id->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        if ($masterTblVar == "master_position") {
            $masterTbl = Container("master_position");
            $this->position_id->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        if ($masterTblVar == "master_skill") {
            $masterTbl = Container("master_skill");
            $this->skill_id->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        if ($masterTblVar == "master_status") {
            $masterTbl = Container("master_status");
            $this->status_id->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        if ($masterTblVar == "master_city") {
            $masterTbl = Container("master_city");
            $this->city_id->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        $this->DbMasterFilter = $this->getMasterFilter(); // Get master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Get detail filter
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
                case "x_position_id":
                    break;
                case "x_religion":
                    break;
                case "x_status_id":
                    break;
                case "x_skill_id":
                    break;
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

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
    }
}
