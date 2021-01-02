<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MytrainingGrid extends Mytraining
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'mytraining';

    // Page object name
    public $PageObjName = "MytrainingGrid";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fmytraininggrid";
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

        // Table object (mytraining)
        if (!isset($GLOBALS["mytraining"]) || get_class($GLOBALS["mytraining"]) == PROJECT_NAMESPACE . "mytraining") {
            $GLOBALS["mytraining"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        $this->AddUrl = "mytrainingadd";

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
        $this->training_id->Visible = false;
        $this->employee_username->Visible = false;
        $this->training_name->setVisibility();
        $this->training_start->setVisibility();
        $this->training_end->setVisibility();
        $this->training_company->setVisibility();
        $this->certificate_start->setVisibility();
        $this->certificate_end->setVisibility();
        $this->notes->Visible = false;
        $this->training_document->setVisibility();
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
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "myprofile") {
            $masterTbl = Container("myprofile");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("myprofilelist"); // Return to master page
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
                    $key .= $this->training_id->CurrentValue;

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
        if ($CurrentForm->hasValue("x_training_name") && $CurrentForm->hasValue("o_training_name") && $this->training_name->CurrentValue != $this->training_name->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_training_start") && $CurrentForm->hasValue("o_training_start") && $this->training_start->CurrentValue != $this->training_start->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_training_end") && $CurrentForm->hasValue("o_training_end") && $this->training_end->CurrentValue != $this->training_end->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_training_company") && $CurrentForm->hasValue("o_training_company") && $this->training_company->CurrentValue != $this->training_company->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_certificate_start") && $CurrentForm->hasValue("o_certificate_start") && $this->certificate_start->CurrentValue != $this->certificate_start->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_certificate_end") && $CurrentForm->hasValue("o_certificate_end") && $this->certificate_end->CurrentValue != $this->certificate_end->OldValue) {
            return false;
        }
        if (!EmptyValue($this->training_document->Upload->Value)) {
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
        $this->training_name->clearErrorMessage();
        $this->training_start->clearErrorMessage();
        $this->training_end->clearErrorMessage();
        $this->training_company->clearErrorMessage();
        $this->certificate_start->clearErrorMessage();
        $this->certificate_end->clearErrorMessage();
        $this->training_document->clearErrorMessage();
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
            $this->DefaultSort = "`training_start` DESC";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($this->training_start->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($useDefaultSort) {
                    $this->training_start->setSort("DESC");
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
                        $this->employee_username->setSessionValue("");
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
        // Hide detail items for dropdown if necessary
        $this->ListOptions->hideDetailItemsForDropDown();
    }

    // Render list options (extended codes)
    protected function renderListOptionsExt()
    {
        global $Security, $Language;
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->training_document->Upload->Index = $CurrentForm->Index;
        $this->training_document->Upload->uploadFile();
        $this->training_document->CurrentValue = $this->training_document->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->training_id->CurrentValue = null;
        $this->training_id->OldValue = $this->training_id->CurrentValue;
        $this->employee_username->CurrentValue = CurrentUserName();
        $this->employee_username->OldValue = $this->employee_username->CurrentValue;
        $this->training_name->CurrentValue = null;
        $this->training_name->OldValue = $this->training_name->CurrentValue;
        $this->training_start->CurrentValue = null;
        $this->training_start->OldValue = $this->training_start->CurrentValue;
        $this->training_end->CurrentValue = null;
        $this->training_end->OldValue = $this->training_end->CurrentValue;
        $this->training_company->CurrentValue = null;
        $this->training_company->OldValue = $this->training_company->CurrentValue;
        $this->certificate_start->CurrentValue = null;
        $this->certificate_start->OldValue = $this->certificate_start->CurrentValue;
        $this->certificate_end->CurrentValue = null;
        $this->certificate_end->OldValue = $this->certificate_end->CurrentValue;
        $this->notes->CurrentValue = null;
        $this->notes->OldValue = $this->notes->CurrentValue;
        $this->training_document->Upload->DbValue = null;
        $this->training_document->OldValue = $this->training_document->Upload->DbValue;
        $this->training_document->Upload->Index = $this->RowIndex;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;

        // Check field name 'training_name' first before field var 'x_training_name'
        $val = $CurrentForm->hasValue("training_name") ? $CurrentForm->getValue("training_name") : $CurrentForm->getValue("x_training_name");
        if (!$this->training_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->training_name->Visible = false; // Disable update for API request
            } else {
                $this->training_name->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_training_name")) {
            $this->training_name->setOldValue($CurrentForm->getValue("o_training_name"));
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
        if ($CurrentForm->hasValue("o_training_start")) {
            $this->training_start->setOldValue($CurrentForm->getValue("o_training_start"));
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
        if ($CurrentForm->hasValue("o_training_end")) {
            $this->training_end->setOldValue($CurrentForm->getValue("o_training_end"));
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
        if ($CurrentForm->hasValue("o_training_company")) {
            $this->training_company->setOldValue($CurrentForm->getValue("o_training_company"));
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
        if ($CurrentForm->hasValue("o_certificate_start")) {
            $this->certificate_start->setOldValue($CurrentForm->getValue("o_certificate_start"));
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
        if ($CurrentForm->hasValue("o_certificate_end")) {
            $this->certificate_end->setOldValue($CurrentForm->getValue("o_certificate_end"));
        }

        // Check field name 'training_id' first before field var 'x_training_id'
        $val = $CurrentForm->hasValue("training_id") ? $CurrentForm->getValue("training_id") : $CurrentForm->getValue("x_training_id");
        if (!$this->training_id->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->training_id->setFormValue($val);
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->training_id->CurrentValue = $this->training_id->FormValue;
        }
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
        $this->training_document->Upload->Index = $this->RowIndex;
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['training_id'] = $this->training_id->CurrentValue;
        $row['employee_username'] = $this->employee_username->CurrentValue;
        $row['training_name'] = $this->training_name->CurrentValue;
        $row['training_start'] = $this->training_start->CurrentValue;
        $row['training_end'] = $this->training_end->CurrentValue;
        $row['training_company'] = $this->training_company->CurrentValue;
        $row['certificate_start'] = $this->certificate_start->CurrentValue;
        $row['certificate_end'] = $this->certificate_end->CurrentValue;
        $row['notes'] = $this->notes->CurrentValue;
        $row['training_document'] = $this->training_document->Upload->DbValue;
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

        // training_id
        $this->training_id->CellCssStyle = "white-space: nowrap;";

        // employee_username
        $this->employee_username->CellCssStyle = "white-space: nowrap;";

        // training_name

        // training_start

        // training_end

        // training_company

        // certificate_start

        // certificate_end

        // notes

        // training_document
        if ($this->RowType == ROWTYPE_VIEW) {
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

            // training_document
            if (!EmptyValue($this->training_document->Upload->DbValue)) {
                $this->training_document->ViewValue = $this->training_document->Upload->DbValue;
            } else {
                $this->training_document->ViewValue = "";
            }
            $this->training_document->ViewCustomAttributes = "";

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
        } elseif ($this->RowType == ROWTYPE_ADD) {
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
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->training_document, $this->RowIndex);
            }

            // Add refer script

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
        } elseif ($this->RowType == ROWTYPE_EDIT) {
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
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->training_document, $this->RowIndex);
            }

            // Edit refer script

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
                $thisKey .= $row['training_id'];
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "myprofile") {
            $this->employee_username->CurrentValue = $this->employee_username->getSessionValue();
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // training_name
        $this->training_name->setDbValueDef($rsnew, $this->training_name->CurrentValue, "", false);

        // training_start
        $this->training_start->setDbValueDef($rsnew, UnFormatDateTime($this->training_start->CurrentValue, 0), null, false);

        // training_end
        $this->training_end->setDbValueDef($rsnew, UnFormatDateTime($this->training_end->CurrentValue, 0), null, false);

        // training_company
        $this->training_company->setDbValueDef($rsnew, $this->training_company->CurrentValue, null, false);

        // certificate_start
        $this->certificate_start->setDbValueDef($rsnew, UnFormatDateTime($this->certificate_start->CurrentValue, 0), null, false);

        // certificate_end
        $this->certificate_end->setDbValueDef($rsnew, UnFormatDateTime($this->certificate_end->CurrentValue, 0), null, false);

        // training_document
        if ($this->training_document->Visible && !$this->training_document->Upload->KeepFile) {
            $this->training_document->Upload->DbValue = ""; // No need to delete old file
            if ($this->training_document->Upload->FileName == "") {
                $rsnew['training_document'] = null;
            } else {
                $rsnew['training_document'] = $this->training_document->Upload->FileName;
            }
        }

        // employee_username
        if ($this->employee_username->getSessionValue() != "") {
            $rsnew['employee_username'] = $this->employee_username->getSessionValue();
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
                $this->training_document->setDbValueDef($rsnew, $this->training_document->Upload->FileName, null, false);
            }
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
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
            // training_document
            CleanUploadTempPath($this->training_document, $this->training_document->Upload->Index);
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
        if ($masterTblVar == "myprofile") {
            $masterTbl = Container("myprofile");
            $this->employee_username->Visible = false;
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
