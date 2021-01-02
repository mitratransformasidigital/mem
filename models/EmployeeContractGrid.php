<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EmployeeContractGrid extends EmployeeContract
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'employee_contract';

    // Page object name
    public $PageObjName = "EmployeeContractGrid";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "femployee_contractgrid";
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

        // Table object (employee_contract)
        if (!isset($GLOBALS["employee_contract"]) || get_class($GLOBALS["employee_contract"]) == PROJECT_NAMESPACE . "employee_contract") {
            $GLOBALS["employee_contract"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        $this->AddUrl = "employeecontractadd";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'employee_contract');
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
                $doc = new $class(Container("employee_contract"));
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
        $this->contract_id->Visible = false;
        $this->employee_username->setVisibility();
        $this->salary->setVisibility();
        $this->bonus->setVisibility();
        $this->thr->setVisibility();
        $this->contract_start->setVisibility();
        $this->contract_end->setVisibility();
        $this->office_id->setVisibility();
        $this->contract_document->setVisibility();
        $this->notes->Visible = false;
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
        $this->setupLookupOptions($this->employee_username);
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
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "employee") {
            $masterTbl = Container("employee");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("employeelist"); // Return to master page
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
        $this->salary->FormValue = ""; // Clear form value
        $this->bonus->FormValue = ""; // Clear form value
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
                    $key .= $this->contract_id->CurrentValue;

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
        if ($CurrentForm->hasValue("x_employee_username") && $CurrentForm->hasValue("o_employee_username") && $this->employee_username->CurrentValue != $this->employee_username->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_salary") && $CurrentForm->hasValue("o_salary") && $this->salary->CurrentValue != $this->salary->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_bonus") && $CurrentForm->hasValue("o_bonus") && $this->bonus->CurrentValue != $this->bonus->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_thr") && $CurrentForm->hasValue("o_thr") && ConvertToBool($this->thr->CurrentValue) != ConvertToBool($this->thr->OldValue)) {
            return false;
        }
        if ($CurrentForm->hasValue("x_contract_start") && $CurrentForm->hasValue("o_contract_start") && $this->contract_start->CurrentValue != $this->contract_start->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_contract_end") && $CurrentForm->hasValue("o_contract_end") && $this->contract_end->CurrentValue != $this->contract_end->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_office_id") && $CurrentForm->hasValue("o_office_id") && $this->office_id->CurrentValue != $this->office_id->OldValue) {
            return false;
        }
        if (!EmptyValue($this->contract_document->Upload->Value)) {
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
        $this->employee_username->clearErrorMessage();
        $this->salary->clearErrorMessage();
        $this->bonus->clearErrorMessage();
        $this->thr->clearErrorMessage();
        $this->contract_start->clearErrorMessage();
        $this->contract_end->clearErrorMessage();
        $this->office_id->clearErrorMessage();
        $this->contract_document->clearErrorMessage();
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
            $this->DefaultSort = "`contract_start` DESC";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($this->contract_start->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($useDefaultSort) {
                    $this->contract_start->setSort("DESC");
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
        $this->employee_username->OldValue = $this->employee_username->CurrentValue;
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
        $this->contract_document->Upload->DbValue = null;
        $this->contract_document->OldValue = $this->contract_document->Upload->DbValue;
        $this->contract_document->Upload->Index = $this->RowIndex;
        $this->notes->CurrentValue = null;
        $this->notes->OldValue = $this->notes->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;

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

        // Check field name 'salary' first before field var 'x_salary'
        $val = $CurrentForm->hasValue("salary") ? $CurrentForm->getValue("salary") : $CurrentForm->getValue("x_salary");
        if (!$this->salary->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->salary->Visible = false; // Disable update for API request
            } else {
                $this->salary->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_salary")) {
            $this->salary->setOldValue($CurrentForm->getValue("o_salary"));
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
        if ($CurrentForm->hasValue("o_bonus")) {
            $this->bonus->setOldValue($CurrentForm->getValue("o_bonus"));
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
        if ($CurrentForm->hasValue("o_thr")) {
            $this->thr->setOldValue($CurrentForm->getValue("o_thr"));
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
        if ($CurrentForm->hasValue("o_contract_start")) {
            $this->contract_start->setOldValue($CurrentForm->getValue("o_contract_start"));
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
        if ($CurrentForm->hasValue("o_contract_end")) {
            $this->contract_end->setOldValue($CurrentForm->getValue("o_contract_end"));
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

        // Check field name 'contract_id' first before field var 'x_contract_id'
        $val = $CurrentForm->hasValue("contract_id") ? $CurrentForm->getValue("contract_id") : $CurrentForm->getValue("x_contract_id");
        if (!$this->contract_id->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->contract_id->setFormValue($val);
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->contract_id->CurrentValue = $this->contract_id->FormValue;
        }
        $this->employee_username->CurrentValue = $this->employee_username->FormValue;
        $this->salary->CurrentValue = $this->salary->FormValue;
        $this->bonus->CurrentValue = $this->bonus->FormValue;
        $this->thr->CurrentValue = $this->thr->FormValue;
        $this->contract_start->CurrentValue = $this->contract_start->FormValue;
        $this->contract_start->CurrentValue = UnFormatDateTime($this->contract_start->CurrentValue, 0);
        $this->contract_end->CurrentValue = $this->contract_end->FormValue;
        $this->contract_end->CurrentValue = UnFormatDateTime($this->contract_end->CurrentValue, 0);
        $this->office_id->CurrentValue = $this->office_id->FormValue;
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
        $this->contract_id->setDbValue($row['contract_id']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->salary->setDbValue($row['salary']);
        $this->bonus->setDbValue($row['bonus']);
        $this->thr->setDbValue($row['thr']);
        $this->contract_start->setDbValue($row['contract_start']);
        $this->contract_end->setDbValue($row['contract_end']);
        $this->office_id->setDbValue($row['office_id']);
        $this->contract_document->Upload->DbValue = $row['contract_document'];
        $this->contract_document->setDbValue($this->contract_document->Upload->DbValue);
        $this->contract_document->Upload->Index = $this->RowIndex;
        $this->notes->setDbValue($row['notes']);
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
        $row['contract_document'] = $this->contract_document->Upload->DbValue;
        $row['notes'] = $this->notes->CurrentValue;
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
        $this->contract_id->CellCssStyle = "white-space: nowrap;";

        // employee_username

        // salary

        // bonus

        // thr

        // contract_start

        // contract_end

        // office_id

        // contract_document

        // notes
        if ($this->RowType == ROWTYPE_VIEW) {
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
                $this->employee_username->OldValue = $this->employee_username->CurrentValue;
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

            // salary
            $this->salary->EditAttrs["class"] = "form-control";
            $this->salary->EditCustomAttributes = "";
            $this->salary->EditValue = HtmlEncode($this->salary->CurrentValue);
            $this->salary->PlaceHolder = RemoveHtml($this->salary->caption());
            if (strval($this->salary->EditValue) != "" && is_numeric($this->salary->EditValue)) {
                $this->salary->EditValue = FormatNumber($this->salary->EditValue, -2, -2, -2, -2);
                $this->salary->OldValue = $this->salary->EditValue;
            }

            // bonus
            $this->bonus->EditAttrs["class"] = "form-control";
            $this->bonus->EditCustomAttributes = "";
            $this->bonus->EditValue = HtmlEncode($this->bonus->CurrentValue);
            $this->bonus->PlaceHolder = RemoveHtml($this->bonus->caption());
            if (strval($this->bonus->EditValue) != "" && is_numeric($this->bonus->EditValue)) {
                $this->bonus->EditValue = FormatNumber($this->bonus->EditValue, -2, -2, -2, -2);
                $this->bonus->OldValue = $this->bonus->EditValue;
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
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->contract_document, $this->RowIndex);
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
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // employee_username
            $this->employee_username->EditAttrs["class"] = "form-control";
            $this->employee_username->EditCustomAttributes = "";
            if ($this->employee_username->getSessionValue() != "") {
                $this->employee_username->CurrentValue = GetForeignKeyValue($this->employee_username->getSessionValue());
                $this->employee_username->OldValue = $this->employee_username->CurrentValue;
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

            // salary
            $this->salary->EditAttrs["class"] = "form-control";
            $this->salary->EditCustomAttributes = "";
            $this->salary->EditValue = HtmlEncode($this->salary->CurrentValue);
            $this->salary->PlaceHolder = RemoveHtml($this->salary->caption());
            if (strval($this->salary->EditValue) != "" && is_numeric($this->salary->EditValue)) {
                $this->salary->EditValue = FormatNumber($this->salary->EditValue, -2, -2, -2, -2);
                $this->salary->OldValue = $this->salary->EditValue;
            }

            // bonus
            $this->bonus->EditAttrs["class"] = "form-control";
            $this->bonus->EditCustomAttributes = "";
            $this->bonus->EditValue = HtmlEncode($this->bonus->CurrentValue);
            $this->bonus->PlaceHolder = RemoveHtml($this->bonus->caption());
            if (strval($this->bonus->EditValue) != "" && is_numeric($this->bonus->EditValue)) {
                $this->bonus->EditValue = FormatNumber($this->bonus->EditValue, -2, -2, -2, -2);
                $this->bonus->OldValue = $this->bonus->EditValue;
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
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->contract_document, $this->RowIndex);
            }

            // Edit refer script

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
                $thisKey .= $row['contract_id'];
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

            // employee_username
            if ($this->employee_username->getSessionValue() != "") {
                $this->employee_username->ReadOnly = true;
            }
            $this->employee_username->setDbValueDef($rsnew, $this->employee_username->CurrentValue, "", $this->employee_username->ReadOnly);

            // salary
            $this->salary->setDbValueDef($rsnew, $this->salary->CurrentValue, null, $this->salary->ReadOnly);

            // bonus
            $this->bonus->setDbValueDef($rsnew, $this->bonus->CurrentValue, null, $this->bonus->ReadOnly);

            // thr
            $tmpBool = $this->thr->CurrentValue;
            if ($tmpBool != "1" && $tmpBool != "0") {
                $tmpBool = !empty($tmpBool) ? "1" : "0";
            }
            $this->thr->setDbValueDef($rsnew, $tmpBool, null, $this->thr->ReadOnly);

            // contract_start
            $this->contract_start->setDbValueDef($rsnew, UnFormatDateTime($this->contract_start->CurrentValue, 0), CurrentDate(), $this->contract_start->ReadOnly);

            // contract_end
            $this->contract_end->setDbValueDef($rsnew, UnFormatDateTime($this->contract_end->CurrentValue, 0), CurrentDate(), $this->contract_end->ReadOnly);

            // office_id
            $this->office_id->setDbValueDef($rsnew, $this->office_id->CurrentValue, null, $this->office_id->ReadOnly);

            // contract_document
            if ($this->contract_document->Visible && !$this->contract_document->ReadOnly && !$this->contract_document->Upload->KeepFile) {
                $this->contract_document->Upload->DbValue = $rsold['contract_document']; // Get original value
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
                    $this->contract_document->setDbValueDef($rsnew, $this->contract_document->Upload->FileName, null, $this->contract_document->ReadOnly);
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
            // contract_document
            CleanUploadTempPath($this->contract_document, $this->contract_document->Upload->Index);
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
        if ($this->getCurrentMasterTable() == "employee") {
            $this->employee_username->CurrentValue = $this->employee_username->getSessionValue();
        }
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
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "employee") {
            $masterTbl = Container("employee");
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
                case "x_employee_username":
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
