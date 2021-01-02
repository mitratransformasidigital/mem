<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MytimesheetGrid extends Mytimesheet
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'mytimesheet';

    // Page object name
    public $PageObjName = "MytimesheetGrid";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fmytimesheetgrid";
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

        // Table object (mytimesheet)
        if (!isset($GLOBALS["mytimesheet"]) || get_class($GLOBALS["mytimesheet"]) == PROJECT_NAMESPACE . "mytimesheet") {
            $GLOBALS["mytimesheet"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        $this->AddUrl = "mytimesheetadd";

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
        $this->timesheet_id->Visible = false;
        $this->employee_username->Visible = false;
        $this->year->setVisibility();
        $this->month->setVisibility();
        $this->days->setVisibility();
        $this->sick->setVisibility();
        $this->leave->setVisibility();
        $this->permit->setVisibility();
        $this->absence->setVisibility();
        $this->timesheet_doc->setVisibility();
        $this->employee_notes->Visible = false;
        $this->company_notes->Visible = false;
        $this->approved->setVisibility();
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
                    $key .= $this->timesheet_id->CurrentValue;

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
        if ($CurrentForm->hasValue("x_year") && $CurrentForm->hasValue("o_year") && $this->year->CurrentValue != $this->year->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_month") && $CurrentForm->hasValue("o_month") && $this->month->CurrentValue != $this->month->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_days") && $CurrentForm->hasValue("o_days") && $this->days->CurrentValue != $this->days->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_sick") && $CurrentForm->hasValue("o_sick") && $this->sick->CurrentValue != $this->sick->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_leave") && $CurrentForm->hasValue("o_leave") && $this->leave->CurrentValue != $this->leave->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_permit") && $CurrentForm->hasValue("o_permit") && $this->permit->CurrentValue != $this->permit->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_absence") && $CurrentForm->hasValue("o_absence") && $this->absence->CurrentValue != $this->absence->OldValue) {
            return false;
        }
        if (!EmptyValue($this->timesheet_doc->Upload->Value)) {
            return false;
        }
        if ($CurrentForm->hasValue("x_approved") && $CurrentForm->hasValue("o_approved") && $this->approved->CurrentValue != $this->approved->OldValue) {
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
        $this->year->clearErrorMessage();
        $this->month->clearErrorMessage();
        $this->days->clearErrorMessage();
        $this->sick->clearErrorMessage();
        $this->leave->clearErrorMessage();
        $this->permit->clearErrorMessage();
        $this->absence->clearErrorMessage();
        $this->timesheet_doc->clearErrorMessage();
        $this->approved->clearErrorMessage();
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
            $this->DefaultSort = "`year` DESC,`month` DESC";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($this->year->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($this->month->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($useDefaultSort) {
                    $this->year->setSort("DESC");
                    $this->month->setSort("DESC");
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
        $this->employee_username->OldValue = $this->employee_username->CurrentValue;
        $this->year->CurrentValue = Date('Y', strtotime('-1 months'));;
        $this->year->OldValue = $this->year->CurrentValue;
        $this->month->CurrentValue = Date('m', strtotime('-1 months'));;
        $this->month->OldValue = $this->month->CurrentValue;
        $this->days->CurrentValue = 20;
        $this->days->OldValue = $this->days->CurrentValue;
        $this->sick->CurrentValue = 0;
        $this->sick->OldValue = $this->sick->CurrentValue;
        $this->leave->CurrentValue = 0;
        $this->leave->OldValue = $this->leave->CurrentValue;
        $this->permit->CurrentValue = 0;
        $this->permit->OldValue = $this->permit->CurrentValue;
        $this->absence->CurrentValue = 0;
        $this->absence->OldValue = $this->absence->CurrentValue;
        $this->timesheet_doc->Upload->DbValue = null;
        $this->timesheet_doc->OldValue = $this->timesheet_doc->Upload->DbValue;
        $this->timesheet_doc->Upload->Index = $this->RowIndex;
        $this->employee_notes->CurrentValue = null;
        $this->employee_notes->OldValue = $this->employee_notes->CurrentValue;
        $this->company_notes->CurrentValue = null;
        $this->company_notes->OldValue = $this->company_notes->CurrentValue;
        $this->approved->CurrentValue = CurrentUserName();
        $this->approved->OldValue = $this->approved->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;

        // Check field name 'year' first before field var 'x_year'
        $val = $CurrentForm->hasValue("year") ? $CurrentForm->getValue("year") : $CurrentForm->getValue("x_year");
        if (!$this->year->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->year->Visible = false; // Disable update for API request
            } else {
                $this->year->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_year")) {
            $this->year->setOldValue($CurrentForm->getValue("o_year"));
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
        if ($CurrentForm->hasValue("o_month")) {
            $this->month->setOldValue($CurrentForm->getValue("o_month"));
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
        if ($CurrentForm->hasValue("o_days")) {
            $this->days->setOldValue($CurrentForm->getValue("o_days"));
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
        if ($CurrentForm->hasValue("o_sick")) {
            $this->sick->setOldValue($CurrentForm->getValue("o_sick"));
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
        if ($CurrentForm->hasValue("o_leave")) {
            $this->leave->setOldValue($CurrentForm->getValue("o_leave"));
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
        if ($CurrentForm->hasValue("o_permit")) {
            $this->permit->setOldValue($CurrentForm->getValue("o_permit"));
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
        if ($CurrentForm->hasValue("o_absence")) {
            $this->absence->setOldValue($CurrentForm->getValue("o_absence"));
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
        if ($CurrentForm->hasValue("o_approved")) {
            $this->approved->setOldValue($CurrentForm->getValue("o_approved"));
        }

        // Check field name 'timesheet_id' first before field var 'x_timesheet_id'
        $val = $CurrentForm->hasValue("timesheet_id") ? $CurrentForm->getValue("timesheet_id") : $CurrentForm->getValue("x_timesheet_id");
        if (!$this->timesheet_id->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->timesheet_id->setFormValue($val);
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->timesheet_id->CurrentValue = $this->timesheet_id->FormValue;
        }
        $this->year->CurrentValue = $this->year->FormValue;
        $this->month->CurrentValue = $this->month->FormValue;
        $this->days->CurrentValue = $this->days->FormValue;
        $this->sick->CurrentValue = $this->sick->FormValue;
        $this->leave->CurrentValue = $this->leave->FormValue;
        $this->permit->CurrentValue = $this->permit->FormValue;
        $this->absence->CurrentValue = $this->absence->FormValue;
        $this->approved->CurrentValue = $this->approved->FormValue;
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
        $this->timesheet_doc->Upload->Index = $this->RowIndex;
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
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // timesheet_id

        // employee_username
        $this->employee_username->CellCssStyle = "white-space: nowrap;";

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

        // Accumulate aggregate value
        if ($this->RowType != ROWTYPE_AGGREGATEINIT && $this->RowType != ROWTYPE_AGGREGATE) {
            $this->employee_username->Count++; // Increment count
            if (is_numeric($this->days->CurrentValue)) {
                $this->days->Total += $this->days->CurrentValue; // Accumulate total
            }
            if (is_numeric($this->sick->CurrentValue)) {
                $this->sick->Total += $this->sick->CurrentValue; // Accumulate total
            }
            if (is_numeric($this->leave->CurrentValue)) {
                $this->leave->Total += $this->leave->CurrentValue; // Accumulate total
            }
            if (is_numeric($this->permit->CurrentValue)) {
                $this->permit->Total += $this->permit->CurrentValue; // Accumulate total
            }
            if (is_numeric($this->absence->CurrentValue)) {
                $this->absence->Total += $this->absence->CurrentValue; // Accumulate total
            }
        }
        if ($this->RowType == ROWTYPE_VIEW) {
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

            // approved
            if (strval($this->approved->CurrentValue) != "") {
                $this->approved->ViewValue = $this->approved->optionCaption($this->approved->CurrentValue);
            } else {
                $this->approved->ViewValue = null;
            }
            $this->approved->ViewCustomAttributes = "";

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

            // approved
            $this->approved->LinkCustomAttributes = "";
            $this->approved->HrefValue = "";
            $this->approved->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
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
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->timesheet_doc, $this->RowIndex);
            }

            // approved
            $this->approved->EditAttrs["class"] = "form-control";
            $this->approved->EditCustomAttributes = "";
            $this->approved->EditValue = $this->approved->options(true);
            $this->approved->PlaceHolder = RemoveHtml($this->approved->caption());

            // Add refer script

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

            // approved
            $this->approved->LinkCustomAttributes = "";
            $this->approved->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
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
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->timesheet_doc, $this->RowIndex);
            }

            // approved
            $this->approved->EditAttrs["class"] = "form-control";
            $this->approved->EditCustomAttributes = "";
            $this->approved->EditValue = $this->approved->options(true);
            $this->approved->PlaceHolder = RemoveHtml($this->approved->caption());

            // Edit refer script

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

            // approved
            $this->approved->LinkCustomAttributes = "";
            $this->approved->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
                $this->employee_username->Count = 0; // Initialize count
                    $this->days->Total = 0; // Initialize total
                    $this->sick->Total = 0; // Initialize total
                    $this->leave->Total = 0; // Initialize total
                    $this->permit->Total = 0; // Initialize total
                    $this->absence->Total = 0; // Initialize total
        } elseif ($this->RowType == ROWTYPE_AGGREGATE) { // Aggregate row
            $this->employee_username->CurrentValue = $this->employee_username->Count;
            $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
            $this->employee_username->ViewCustomAttributes = "";
            $this->employee_username->HrefValue = ""; // Clear href value
            $this->days->CurrentValue = $this->days->Total;
            $this->days->ViewValue = $this->days->CurrentValue;
            $this->days->ViewValue = FormatNumber($this->days->ViewValue, 0, -2, -2, -2);
            $this->days->CellCssStyle .= "text-align: right;";
            $this->days->ViewCustomAttributes = "";
            $this->days->HrefValue = ""; // Clear href value
            $this->sick->CurrentValue = $this->sick->Total;
            $this->sick->ViewValue = $this->sick->CurrentValue;
            $this->sick->ViewValue = FormatNumber($this->sick->ViewValue, 0, -2, -2, -2);
            $this->sick->CellCssStyle .= "text-align: right;";
            $this->sick->ViewCustomAttributes = "";
            $this->sick->HrefValue = ""; // Clear href value
            $this->leave->CurrentValue = $this->leave->Total;
            $this->leave->ViewValue = $this->leave->CurrentValue;
            $this->leave->ViewValue = FormatNumber($this->leave->ViewValue, 0, -2, -2, -2);
            $this->leave->CellCssStyle .= "text-align: right;";
            $this->leave->ViewCustomAttributes = "";
            $this->leave->HrefValue = ""; // Clear href value
            $this->permit->CurrentValue = $this->permit->Total;
            $this->permit->ViewValue = $this->permit->CurrentValue;
            $this->permit->ViewValue = FormatNumber($this->permit->ViewValue, 0, -2, -2, -2);
            $this->permit->CellCssStyle .= "text-align: right;";
            $this->permit->ViewCustomAttributes = "";
            $this->permit->HrefValue = ""; // Clear href value
            $this->absence->CurrentValue = $this->absence->Total;
            $this->absence->ViewValue = $this->absence->CurrentValue;
            $this->absence->ViewValue = FormatNumber($this->absence->ViewValue, 0, -2, -2, -2);
            $this->absence->CellCssStyle .= "text-align: right;";
            $this->absence->ViewCustomAttributes = "";
            $this->absence->HrefValue = ""; // Clear href value
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
                $thisKey .= $row['timesheet_id'];
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

            // year
            $this->year->setDbValueDef($rsnew, $this->year->CurrentValue, 0, $this->year->ReadOnly);

            // month
            $this->month->setDbValueDef($rsnew, $this->month->CurrentValue, 0, $this->month->ReadOnly);

            // days
            $this->days->setDbValueDef($rsnew, $this->days->CurrentValue, null, $this->days->ReadOnly);

            // sick
            $this->sick->setDbValueDef($rsnew, $this->sick->CurrentValue, null, $this->sick->ReadOnly);

            // leave
            $this->leave->setDbValueDef($rsnew, $this->leave->CurrentValue, null, $this->leave->ReadOnly);

            // permit
            $this->permit->setDbValueDef($rsnew, $this->permit->CurrentValue, null, $this->permit->ReadOnly);

            // absence
            $this->absence->setDbValueDef($rsnew, $this->absence->CurrentValue, null, $this->absence->ReadOnly);

            // timesheet_doc
            if ($this->timesheet_doc->Visible && !$this->timesheet_doc->ReadOnly && !$this->timesheet_doc->Upload->KeepFile) {
                $this->timesheet_doc->Upload->DbValue = $rsold['timesheet_doc']; // Get original value
                if ($this->timesheet_doc->Upload->FileName == "") {
                    $rsnew['timesheet_doc'] = null;
                } else {
                    $rsnew['timesheet_doc'] = $this->timesheet_doc->Upload->FileName;
                }
            }

            // approved
            $this->approved->setDbValueDef($rsnew, $this->approved->CurrentValue, null, $this->approved->ReadOnly);
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
                    $this->timesheet_doc->setDbValueDef($rsnew, $this->timesheet_doc->Upload->FileName, null, $this->timesheet_doc->ReadOnly);
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
            // timesheet_doc
            CleanUploadTempPath($this->timesheet_doc, $this->timesheet_doc->Upload->Index);
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

        // approved
        $this->approved->setDbValueDef($rsnew, $this->approved->CurrentValue, null, false);

        // employee_username
        if ($this->employee_username->getSessionValue() != "") {
            $rsnew['employee_username'] = $this->employee_username->getSessionValue();
        }
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
