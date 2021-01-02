<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EmployeeContractUpdate extends EmployeeContract
{
    use MessagesTrait;

    // Page ID
    public $PageID = "update";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'employee_contract';

    // Page object name
    public $PageObjName = "EmployeeContractUpdate";

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

        // Table object (employee_contract)
        if (!isset($GLOBALS["employee_contract"]) || get_class($GLOBALS["employee_contract"]) == PROJECT_NAMESPACE . "employee_contract") {
            $GLOBALS["employee_contract"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

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
                    if ($pageName == "employeecontractview") {
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
    public $FormClassName = "ew-horizontal ew-form ew-update-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $RecKeys;
    public $Disabled;
    public $UpdateCount = 0;

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
        $this->contract_document->setVisibility();
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
        $this->setupLookupOptions($this->office_id);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-update-form ew-horizontal";

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Try to load keys from list form
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        if (Post("action") !== null && Post("action") !== "") {
            // Get action
            $this->CurrentAction = Post("action");
            $this->loadFormValues(); // Get form values

            // Validate form
            if (!$this->validateForm()) {
                $this->CurrentAction = "show"; // Form error, reset action
                if (!$this->hasInvalidFields()) { // No fields selected
                    $this->setFailureMessage($Language->phrase("NoFieldSelected"));
                }
            }
        } else {
            $this->loadMultiUpdateValues(); // Load initial values to form
        }
        if (count($this->RecKeys) <= 0) {
            $this->terminate("employeecontractlist"); // No records selected, return to list
            return;
        }
        if ($this->isUpdate()) {
                if ($this->updateRows()) { // Update Records based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Set up update success message
                    }
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                } else {
                    $this->restoreFormValues(); // Restore form values
                }
        }

        // Render row
        $this->RowType = ROWTYPE_EDIT; // Render edit
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

    // Load initial values to form if field values are identical in all selected records
    protected function loadMultiUpdateValues()
    {
        $this->CurrentFilter = $this->getFilterFromRecordKeys();

        // Load recordset
        if ($rs = $this->loadRecordset()) {
            $i = 1;
            while (!$rs->EOF) {
                if ($i == 1) {
                    $this->employee_username->setDbValue($rs->fields['employee_username']);
                    $this->salary->setDbValue($rs->fields['salary']);
                    $this->bonus->setDbValue($rs->fields['bonus']);
                    $this->thr->setDbValue($rs->fields['thr']);
                    $this->contract_start->setDbValue($rs->fields['contract_start']);
                    $this->contract_end->setDbValue($rs->fields['contract_end']);
                    $this->office_id->setDbValue($rs->fields['office_id']);
                    $this->notes->setDbValue($rs->fields['notes']);
                } else {
                    if (!CompareValue($this->employee_username->DbValue, $rs->fields['employee_username'])) {
                        $this->employee_username->CurrentValue = null;
                    }
                    if (!CompareValue($this->salary->DbValue, $rs->fields['salary'])) {
                        $this->salary->CurrentValue = null;
                    }
                    if (!CompareValue($this->bonus->DbValue, $rs->fields['bonus'])) {
                        $this->bonus->CurrentValue = null;
                    }
                    if (!CompareValue($this->thr->DbValue, $rs->fields['thr'])) {
                        $this->thr->CurrentValue = null;
                    }
                    if (!CompareValue($this->contract_start->DbValue, $rs->fields['contract_start'])) {
                        $this->contract_start->CurrentValue = null;
                    }
                    if (!CompareValue($this->contract_end->DbValue, $rs->fields['contract_end'])) {
                        $this->contract_end->CurrentValue = null;
                    }
                    if (!CompareValue($this->office_id->DbValue, $rs->fields['office_id'])) {
                        $this->office_id->CurrentValue = null;
                    }
                    if (!CompareValue($this->notes->DbValue, $rs->fields['notes'])) {
                        $this->notes->CurrentValue = null;
                    }
                }
                $i++;
                $rs->moveNext();
            }
            $rs->close();
        }
    }

    // Set up key value
    protected function setupKeyValues($key)
    {
        $keyFld = $key;
        if (!is_numeric($keyFld)) {
            return false;
        }
        $this->contract_id->OldValue = $keyFld;
        return true;
    }

    // Update all selected rows
    protected function updateRows()
    {
        global $Language;
        $conn = $this->getConnection();
        $conn->beginTransaction();

        // Get old records
        $this->CurrentFilter = $this->getFilterFromRecordKeys(false);
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAll($sql);

        // Update all rows
        $key = "";
        foreach ($this->RecKeys as $reckey) {
            if ($this->setupKeyValues($reckey)) {
                $thisKey = $reckey;
                $this->SendEmail = false; // Do not send email on update success
                $this->UpdateCount += 1; // Update record count for records being updated
                $updateRows = $this->editRow(); // Update this row
            } else {
                $updateRows = false;
            }
            if (!$updateRows) {
                break; // Update failed
            }
            if ($key != "") {
                $key .= ", ";
            }
            $key .= $thisKey;
        }

        // Check if all rows updated
        if ($updateRows) {
            $conn->commit(); // Commit transaction

            // Get new records
            $rsnew = $conn->fetchAll($sql);
        } else {
            $conn->rollback(); // Rollback transaction
        }
        return $updateRows;
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->contract_document->Upload->Index = $CurrentForm->Index;
        $this->contract_document->Upload->uploadFile();
        $this->contract_document->CurrentValue = $this->contract_document->Upload->FileName;
        $this->contract_document->MultiUpdate = $CurrentForm->getValue("u_contract_document");
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
        $this->employee_username->MultiUpdate = $CurrentForm->getValue("u_employee_username");

        // Check field name 'salary' first before field var 'x_salary'
        $val = $CurrentForm->hasValue("salary") ? $CurrentForm->getValue("salary") : $CurrentForm->getValue("x_salary");
        if (!$this->salary->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->salary->Visible = false; // Disable update for API request
            } else {
                $this->salary->setFormValue($val);
            }
        }
        $this->salary->MultiUpdate = $CurrentForm->getValue("u_salary");

        // Check field name 'bonus' first before field var 'x_bonus'
        $val = $CurrentForm->hasValue("bonus") ? $CurrentForm->getValue("bonus") : $CurrentForm->getValue("x_bonus");
        if (!$this->bonus->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->bonus->Visible = false; // Disable update for API request
            } else {
                $this->bonus->setFormValue($val);
            }
        }
        $this->bonus->MultiUpdate = $CurrentForm->getValue("u_bonus");

        // Check field name 'thr' first before field var 'x_thr'
        $val = $CurrentForm->hasValue("thr") ? $CurrentForm->getValue("thr") : $CurrentForm->getValue("x_thr");
        if (!$this->thr->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->thr->Visible = false; // Disable update for API request
            } else {
                $this->thr->setFormValue($val);
            }
        }
        $this->thr->MultiUpdate = $CurrentForm->getValue("u_thr");

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
        $this->contract_start->MultiUpdate = $CurrentForm->getValue("u_contract_start");

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
        $this->contract_end->MultiUpdate = $CurrentForm->getValue("u_contract_end");

        // Check field name 'office_id' first before field var 'x_office_id'
        $val = $CurrentForm->hasValue("office_id") ? $CurrentForm->getValue("office_id") : $CurrentForm->getValue("x_office_id");
        if (!$this->office_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->office_id->Visible = false; // Disable update for API request
            } else {
                $this->office_id->setFormValue($val);
            }
        }
        $this->office_id->MultiUpdate = $CurrentForm->getValue("u_office_id");

        // Check field name 'notes' first before field var 'x_notes'
        $val = $CurrentForm->hasValue("notes") ? $CurrentForm->getValue("notes") : $CurrentForm->getValue("x_notes");
        if (!$this->notes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->notes->Visible = false; // Disable update for API request
            } else {
                $this->notes->setFormValue($val);
            }
        }
        $this->notes->MultiUpdate = $CurrentForm->getValue("u_notes");

        // Check field name 'contract_id' first before field var 'x_contract_id'
        $val = $CurrentForm->hasValue("contract_id") ? $CurrentForm->getValue("contract_id") : $CurrentForm->getValue("x_contract_id");
        if (!$this->contract_id->IsDetailKey) {
            $this->contract_id->setFormValue($val);
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->contract_id->CurrentValue = $this->contract_id->FormValue;
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
        $this->notes->setDbValue($row['notes']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['contract_id'] = null;
        $row['employee_username'] = null;
        $row['salary'] = null;
        $row['bonus'] = null;
        $row['thr'] = null;
        $row['contract_start'] = null;
        $row['contract_end'] = null;
        $row['office_id'] = null;
        $row['contract_document'] = null;
        $row['notes'] = null;
        return $row;
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

            // notes
            $this->notes->ViewValue = $this->notes->CurrentValue;
            $this->notes->ViewCustomAttributes = "";

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

            // notes
            $this->notes->LinkCustomAttributes = "";
            $this->notes->HrefValue = "";
            $this->notes->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
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

            // notes
            $this->notes->EditAttrs["class"] = "form-control";
            $this->notes->EditCustomAttributes = "";
            $this->notes->EditValue = HtmlEncode($this->notes->CurrentValue);
            $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());

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
        $updateCnt = 0;
        if ($this->employee_username->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->salary->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->bonus->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->thr->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->contract_start->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->contract_end->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->office_id->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->contract_document->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->notes->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($updateCnt == 0) {
            return false;
        }

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->employee_username->Required) {
            if ($this->employee_username->MultiUpdate != "" && !$this->employee_username->IsDetailKey && EmptyValue($this->employee_username->FormValue)) {
                $this->employee_username->addErrorMessage(str_replace("%s", $this->employee_username->caption(), $this->employee_username->RequiredErrorMessage));
            }
        }
        if ($this->salary->Required) {
            if ($this->salary->MultiUpdate != "" && !$this->salary->IsDetailKey && EmptyValue($this->salary->FormValue)) {
                $this->salary->addErrorMessage(str_replace("%s", $this->salary->caption(), $this->salary->RequiredErrorMessage));
            }
        }
        if ($this->salary->MultiUpdate != "") {
            if (!CheckNumber($this->salary->FormValue)) {
                $this->salary->addErrorMessage($this->salary->getErrorMessage(false));
            }
        }
        if ($this->bonus->Required) {
            if ($this->bonus->MultiUpdate != "" && !$this->bonus->IsDetailKey && EmptyValue($this->bonus->FormValue)) {
                $this->bonus->addErrorMessage(str_replace("%s", $this->bonus->caption(), $this->bonus->RequiredErrorMessage));
            }
        }
        if ($this->bonus->MultiUpdate != "") {
            if (!CheckNumber($this->bonus->FormValue)) {
                $this->bonus->addErrorMessage($this->bonus->getErrorMessage(false));
            }
        }
        if ($this->thr->Required) {
            if ($this->thr->MultiUpdate != "" && $this->thr->FormValue == "") {
                $this->thr->addErrorMessage(str_replace("%s", $this->thr->caption(), $this->thr->RequiredErrorMessage));
            }
        }
        if ($this->contract_start->Required) {
            if ($this->contract_start->MultiUpdate != "" && !$this->contract_start->IsDetailKey && EmptyValue($this->contract_start->FormValue)) {
                $this->contract_start->addErrorMessage(str_replace("%s", $this->contract_start->caption(), $this->contract_start->RequiredErrorMessage));
            }
        }
        if ($this->contract_start->MultiUpdate != "") {
            if (!CheckDate($this->contract_start->FormValue)) {
                $this->contract_start->addErrorMessage($this->contract_start->getErrorMessage(false));
            }
        }
        if ($this->contract_end->Required) {
            if ($this->contract_end->MultiUpdate != "" && !$this->contract_end->IsDetailKey && EmptyValue($this->contract_end->FormValue)) {
                $this->contract_end->addErrorMessage(str_replace("%s", $this->contract_end->caption(), $this->contract_end->RequiredErrorMessage));
            }
        }
        if ($this->contract_end->MultiUpdate != "") {
            if (!CheckDate($this->contract_end->FormValue)) {
                $this->contract_end->addErrorMessage($this->contract_end->getErrorMessage(false));
            }
        }
        if ($this->office_id->Required) {
            if ($this->office_id->MultiUpdate != "" && !$this->office_id->IsDetailKey && EmptyValue($this->office_id->FormValue)) {
                $this->office_id->addErrorMessage(str_replace("%s", $this->office_id->caption(), $this->office_id->RequiredErrorMessage));
            }
        }
        if ($this->contract_document->Required) {
            if ($this->contract_document->MultiUpdate != "" && $this->contract_document->Upload->FileName == "" && !$this->contract_document->Upload->KeepFile) {
                $this->contract_document->addErrorMessage(str_replace("%s", $this->contract_document->caption(), $this->contract_document->RequiredErrorMessage));
            }
        }
        if ($this->notes->Required) {
            if ($this->notes->MultiUpdate != "" && !$this->notes->IsDetailKey && EmptyValue($this->notes->FormValue)) {
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
            $this->employee_username->setDbValueDef($rsnew, $this->employee_username->CurrentValue, "", $this->employee_username->ReadOnly || $this->employee_username->MultiUpdate != "1");

            // salary
            $this->salary->setDbValueDef($rsnew, $this->salary->CurrentValue, null, $this->salary->ReadOnly || $this->salary->MultiUpdate != "1");

            // bonus
            $this->bonus->setDbValueDef($rsnew, $this->bonus->CurrentValue, null, $this->bonus->ReadOnly || $this->bonus->MultiUpdate != "1");

            // thr
            $tmpBool = $this->thr->CurrentValue;
            if ($tmpBool != "1" && $tmpBool != "0") {
                $tmpBool = !empty($tmpBool) ? "1" : "0";
            }
            $this->thr->setDbValueDef($rsnew, $tmpBool, null, $this->thr->ReadOnly || $this->thr->MultiUpdate != "1");

            // contract_start
            $this->contract_start->setDbValueDef($rsnew, UnFormatDateTime($this->contract_start->CurrentValue, 0), CurrentDate(), $this->contract_start->ReadOnly || $this->contract_start->MultiUpdate != "1");

            // contract_end
            $this->contract_end->setDbValueDef($rsnew, UnFormatDateTime($this->contract_end->CurrentValue, 0), CurrentDate(), $this->contract_end->ReadOnly || $this->contract_end->MultiUpdate != "1");

            // office_id
            $this->office_id->setDbValueDef($rsnew, $this->office_id->CurrentValue, null, $this->office_id->ReadOnly || $this->office_id->MultiUpdate != "1");

            // contract_document
            if ($this->contract_document->Visible && !$this->contract_document->ReadOnly && strval($this->contract_document->MultiUpdate) == "1" && !$this->contract_document->Upload->KeepFile) {
                $this->contract_document->Upload->DbValue = $rsold['contract_document']; // Get original value
                if ($this->contract_document->Upload->FileName == "") {
                    $rsnew['contract_document'] = null;
                } else {
                    $rsnew['contract_document'] = $this->contract_document->Upload->FileName;
                }
            }

            // notes
            $this->notes->setDbValueDef($rsnew, $this->notes->CurrentValue, null, $this->notes->ReadOnly || $this->notes->MultiUpdate != "1");
            if ($this->contract_document->Visible && !$this->contract_document->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->contract_document->Upload->DbValue) ? [] : [$this->contract_document->htmlDecode($this->contract_document->Upload->DbValue)];
                if (!EmptyValue($this->contract_document->Upload->FileName) && $this->UpdateCount == 1) {
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
                    $this->contract_document->setDbValueDef($rsnew, $this->contract_document->Upload->FileName, null, $this->contract_document->ReadOnly || $this->contract_document->MultiUpdate != "1");
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
                        if (!EmptyValue($this->contract_document->Upload->FileName) && $this->UpdateCount == 1) {
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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("welcome");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("employeecontractlist"), "", $this->TableVar, true);
        $pageId = "update";
        $Breadcrumb->add("update", $pageId, $url);
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
}
