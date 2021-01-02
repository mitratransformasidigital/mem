<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MyassetUpdate extends Myasset
{
    use MessagesTrait;

    // Page ID
    public $PageID = "update";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'myasset';

    // Page object name
    public $PageObjName = "MyassetUpdate";

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

        // Table object (myasset)
        if (!isset($GLOBALS["myasset"]) || get_class($GLOBALS["myasset"]) == PROJECT_NAMESPACE . "myasset") {
            $GLOBALS["myasset"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'myasset');
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
                $doc = new $class(Container("myasset"));
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
                    if ($pageName == "myassetview") {
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
            $key .= @$ar['asset_id'];
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
            $this->asset_id->Visible = false;
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
        $this->asset_id->Visible = false;
        $this->employee_username->Visible = false;
        $this->asset_name->setVisibility();
        $this->year->setVisibility();
        $this->serial_number->setVisibility();
        $this->value->setVisibility();
        $this->asset_received->setVisibility();
        $this->asset_return->Visible = false;
        $this->notes->setVisibility();
        $this->asset_picture->Visible = false;
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
            $this->terminate("myassetlist"); // No records selected, return to list
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
                    $this->asset_name->setDbValue($rs->fields['asset_name']);
                    $this->year->setDbValue($rs->fields['year']);
                    $this->serial_number->setDbValue($rs->fields['serial_number']);
                    $this->value->setDbValue($rs->fields['value']);
                    $this->asset_received->setDbValue($rs->fields['asset_received']);
                    $this->notes->setDbValue($rs->fields['notes']);
                } else {
                    if (!CompareValue($this->asset_name->DbValue, $rs->fields['asset_name'])) {
                        $this->asset_name->CurrentValue = null;
                    }
                    if (!CompareValue($this->year->DbValue, $rs->fields['year'])) {
                        $this->year->CurrentValue = null;
                    }
                    if (!CompareValue($this->serial_number->DbValue, $rs->fields['serial_number'])) {
                        $this->serial_number->CurrentValue = null;
                    }
                    if (!CompareValue($this->value->DbValue, $rs->fields['value'])) {
                        $this->value->CurrentValue = null;
                    }
                    if (!CompareValue($this->asset_received->DbValue, $rs->fields['asset_received'])) {
                        $this->asset_received->CurrentValue = null;
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
        $this->asset_id->OldValue = $keyFld;
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
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'asset_name' first before field var 'x_asset_name'
        $val = $CurrentForm->hasValue("asset_name") ? $CurrentForm->getValue("asset_name") : $CurrentForm->getValue("x_asset_name");
        if (!$this->asset_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->asset_name->Visible = false; // Disable update for API request
            } else {
                $this->asset_name->setFormValue($val);
            }
        }
        $this->asset_name->MultiUpdate = $CurrentForm->getValue("u_asset_name");

        // Check field name 'year' first before field var 'x_year'
        $val = $CurrentForm->hasValue("year") ? $CurrentForm->getValue("year") : $CurrentForm->getValue("x_year");
        if (!$this->year->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->year->Visible = false; // Disable update for API request
            } else {
                $this->year->setFormValue($val);
            }
        }
        $this->year->MultiUpdate = $CurrentForm->getValue("u_year");

        // Check field name 'serial_number' first before field var 'x_serial_number'
        $val = $CurrentForm->hasValue("serial_number") ? $CurrentForm->getValue("serial_number") : $CurrentForm->getValue("x_serial_number");
        if (!$this->serial_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->serial_number->Visible = false; // Disable update for API request
            } else {
                $this->serial_number->setFormValue($val);
            }
        }
        $this->serial_number->MultiUpdate = $CurrentForm->getValue("u_serial_number");

        // Check field name 'value' first before field var 'x_value'
        $val = $CurrentForm->hasValue("value") ? $CurrentForm->getValue("value") : $CurrentForm->getValue("x_value");
        if (!$this->value->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->value->Visible = false; // Disable update for API request
            } else {
                $this->value->setFormValue($val);
            }
        }
        $this->value->MultiUpdate = $CurrentForm->getValue("u_value");

        // Check field name 'asset_received' first before field var 'x_asset_received'
        $val = $CurrentForm->hasValue("asset_received") ? $CurrentForm->getValue("asset_received") : $CurrentForm->getValue("x_asset_received");
        if (!$this->asset_received->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->asset_received->Visible = false; // Disable update for API request
            } else {
                $this->asset_received->setFormValue($val);
            }
            $this->asset_received->CurrentValue = UnFormatDateTime($this->asset_received->CurrentValue, 5);
        }
        $this->asset_received->MultiUpdate = $CurrentForm->getValue("u_asset_received");

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

        // Check field name 'asset_id' first before field var 'x_asset_id'
        $val = $CurrentForm->hasValue("asset_id") ? $CurrentForm->getValue("asset_id") : $CurrentForm->getValue("x_asset_id");
        if (!$this->asset_id->IsDetailKey) {
            $this->asset_id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->asset_id->CurrentValue = $this->asset_id->FormValue;
        $this->asset_name->CurrentValue = $this->asset_name->FormValue;
        $this->year->CurrentValue = $this->year->FormValue;
        $this->serial_number->CurrentValue = $this->serial_number->FormValue;
        $this->value->CurrentValue = $this->value->FormValue;
        $this->asset_received->CurrentValue = $this->asset_received->FormValue;
        $this->asset_received->CurrentValue = UnFormatDateTime($this->asset_received->CurrentValue, 5);
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
        $this->asset_id->setDbValue($row['asset_id']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->asset_name->setDbValue($row['asset_name']);
        $this->year->setDbValue($row['year']);
        $this->serial_number->setDbValue($row['serial_number']);
        $this->value->setDbValue($row['value']);
        $this->asset_received->setDbValue($row['asset_received']);
        $this->asset_return->setDbValue($row['asset_return']);
        $this->notes->setDbValue($row['notes']);
        $this->asset_picture->Upload->DbValue = $row['asset_picture'];
        $this->asset_picture->setDbValue($this->asset_picture->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['asset_id'] = null;
        $row['employee_username'] = null;
        $row['asset_name'] = null;
        $row['year'] = null;
        $row['serial_number'] = null;
        $row['value'] = null;
        $row['asset_received'] = null;
        $row['asset_return'] = null;
        $row['notes'] = null;
        $row['asset_picture'] = null;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Convert decimal values if posted back
        if ($this->value->FormValue == $this->value->CurrentValue && is_numeric(ConvertToFloatString($this->value->CurrentValue))) {
            $this->value->CurrentValue = ConvertToFloatString($this->value->CurrentValue);
        }

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // asset_id

        // employee_username

        // asset_name

        // year

        // serial_number

        // value

        // asset_received

        // asset_return

        // notes

        // asset_picture
        if ($this->RowType == ROWTYPE_VIEW) {
            // asset_name
            $this->asset_name->ViewValue = $this->asset_name->CurrentValue;
            $this->asset_name->ViewCustomAttributes = "";

            // year
            $this->year->ViewValue = $this->year->CurrentValue;
            $this->year->CellCssStyle .= "text-align: justify;";
            $this->year->ViewCustomAttributes = "";

            // serial_number
            $this->serial_number->ViewValue = $this->serial_number->CurrentValue;
            $this->serial_number->ViewCustomAttributes = "";

            // value
            $this->value->ViewValue = $this->value->CurrentValue;
            $this->value->ViewValue = FormatNumber($this->value->ViewValue, 0, -2, -2, -2);
            $this->value->CellCssStyle .= "text-align: right;";
            $this->value->ViewCustomAttributes = "";

            // asset_received
            $this->asset_received->ViewValue = $this->asset_received->CurrentValue;
            $this->asset_received->ViewValue = FormatDateTime($this->asset_received->ViewValue, 5);
            $this->asset_received->ViewCustomAttributes = "";

            // asset_return
            $this->asset_return->ViewValue = $this->asset_return->CurrentValue;
            $this->asset_return->ViewValue = FormatDateTime($this->asset_return->ViewValue, 5);
            $this->asset_return->ViewCustomAttributes = "";

            // notes
            $this->notes->ViewValue = $this->notes->CurrentValue;
            $this->notes->ViewCustomAttributes = "";

            // asset_picture
            if (!EmptyValue($this->asset_picture->Upload->DbValue)) {
                $this->asset_picture->ViewValue = $this->asset_picture->Upload->DbValue;
            } else {
                $this->asset_picture->ViewValue = "";
            }
            $this->asset_picture->ViewCustomAttributes = "";

            // asset_name
            $this->asset_name->LinkCustomAttributes = "";
            $this->asset_name->HrefValue = "";
            $this->asset_name->TooltipValue = "";

            // year
            $this->year->LinkCustomAttributes = "";
            $this->year->HrefValue = "";
            $this->year->TooltipValue = "";

            // serial_number
            $this->serial_number->LinkCustomAttributes = "";
            $this->serial_number->HrefValue = "";
            $this->serial_number->TooltipValue = "";

            // value
            $this->value->LinkCustomAttributes = "";
            $this->value->HrefValue = "";
            $this->value->TooltipValue = "";

            // asset_received
            $this->asset_received->LinkCustomAttributes = "";
            $this->asset_received->HrefValue = "";
            $this->asset_received->TooltipValue = "";

            // notes
            $this->notes->LinkCustomAttributes = "";
            $this->notes->HrefValue = "";
            $this->notes->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // asset_name
            $this->asset_name->EditAttrs["class"] = "form-control";
            $this->asset_name->EditCustomAttributes = "";
            if (!$this->asset_name->Raw) {
                $this->asset_name->CurrentValue = HtmlDecode($this->asset_name->CurrentValue);
            }
            $this->asset_name->EditValue = HtmlEncode($this->asset_name->CurrentValue);
            $this->asset_name->PlaceHolder = RemoveHtml($this->asset_name->caption());

            // year
            $this->year->EditAttrs["class"] = "form-control";
            $this->year->EditCustomAttributes = "";
            $this->year->EditValue = HtmlEncode($this->year->CurrentValue);
            $this->year->PlaceHolder = RemoveHtml($this->year->caption());

            // serial_number
            $this->serial_number->EditAttrs["class"] = "form-control";
            $this->serial_number->EditCustomAttributes = "";
            if (!$this->serial_number->Raw) {
                $this->serial_number->CurrentValue = HtmlDecode($this->serial_number->CurrentValue);
            }
            $this->serial_number->EditValue = HtmlEncode($this->serial_number->CurrentValue);
            $this->serial_number->PlaceHolder = RemoveHtml($this->serial_number->caption());

            // value
            $this->value->EditAttrs["class"] = "form-control";
            $this->value->EditCustomAttributes = "";
            $this->value->EditValue = HtmlEncode($this->value->CurrentValue);
            $this->value->PlaceHolder = RemoveHtml($this->value->caption());
            if (strval($this->value->EditValue) != "" && is_numeric($this->value->EditValue)) {
                $this->value->EditValue = FormatNumber($this->value->EditValue, -2, -2, -2, -2);
            }

            // asset_received
            $this->asset_received->EditAttrs["class"] = "form-control";
            $this->asset_received->EditCustomAttributes = "";
            $this->asset_received->EditValue = HtmlEncode(FormatDateTime($this->asset_received->CurrentValue, 5));
            $this->asset_received->PlaceHolder = RemoveHtml($this->asset_received->caption());

            // notes
            $this->notes->EditAttrs["class"] = "form-control";
            $this->notes->EditCustomAttributes = "";
            $this->notes->EditValue = HtmlEncode($this->notes->CurrentValue);
            $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());

            // Edit refer script

            // asset_name
            $this->asset_name->LinkCustomAttributes = "";
            $this->asset_name->HrefValue = "";

            // year
            $this->year->LinkCustomAttributes = "";
            $this->year->HrefValue = "";

            // serial_number
            $this->serial_number->LinkCustomAttributes = "";
            $this->serial_number->HrefValue = "";

            // value
            $this->value->LinkCustomAttributes = "";
            $this->value->HrefValue = "";

            // asset_received
            $this->asset_received->LinkCustomAttributes = "";
            $this->asset_received->HrefValue = "";

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
        if ($this->asset_name->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->year->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->serial_number->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->value->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->asset_received->multiUpdateSelected()) {
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
        if ($this->asset_name->Required) {
            if ($this->asset_name->MultiUpdate != "" && !$this->asset_name->IsDetailKey && EmptyValue($this->asset_name->FormValue)) {
                $this->asset_name->addErrorMessage(str_replace("%s", $this->asset_name->caption(), $this->asset_name->RequiredErrorMessage));
            }
        }
        if ($this->year->Required) {
            if ($this->year->MultiUpdate != "" && !$this->year->IsDetailKey && EmptyValue($this->year->FormValue)) {
                $this->year->addErrorMessage(str_replace("%s", $this->year->caption(), $this->year->RequiredErrorMessage));
            }
        }
        if ($this->year->MultiUpdate != "") {
            if (!CheckInteger($this->year->FormValue)) {
                $this->year->addErrorMessage($this->year->getErrorMessage(false));
            }
        }
        if ($this->serial_number->Required) {
            if ($this->serial_number->MultiUpdate != "" && !$this->serial_number->IsDetailKey && EmptyValue($this->serial_number->FormValue)) {
                $this->serial_number->addErrorMessage(str_replace("%s", $this->serial_number->caption(), $this->serial_number->RequiredErrorMessage));
            }
        }
        if ($this->value->Required) {
            if ($this->value->MultiUpdate != "" && !$this->value->IsDetailKey && EmptyValue($this->value->FormValue)) {
                $this->value->addErrorMessage(str_replace("%s", $this->value->caption(), $this->value->RequiredErrorMessage));
            }
        }
        if ($this->value->MultiUpdate != "") {
            if (!CheckNumber($this->value->FormValue)) {
                $this->value->addErrorMessage($this->value->getErrorMessage(false));
            }
        }
        if ($this->asset_received->Required) {
            if ($this->asset_received->MultiUpdate != "" && !$this->asset_received->IsDetailKey && EmptyValue($this->asset_received->FormValue)) {
                $this->asset_received->addErrorMessage(str_replace("%s", $this->asset_received->caption(), $this->asset_received->RequiredErrorMessage));
            }
        }
        if ($this->asset_received->MultiUpdate != "") {
            if (!CheckStdDate($this->asset_received->FormValue)) {
                $this->asset_received->addErrorMessage($this->asset_received->getErrorMessage(false));
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

            // asset_name
            $this->asset_name->setDbValueDef($rsnew, $this->asset_name->CurrentValue, "", $this->asset_name->ReadOnly || $this->asset_name->MultiUpdate != "1");

            // year
            $this->year->setDbValueDef($rsnew, $this->year->CurrentValue, 0, $this->year->ReadOnly || $this->year->MultiUpdate != "1");

            // serial_number
            $this->serial_number->setDbValueDef($rsnew, $this->serial_number->CurrentValue, "", $this->serial_number->ReadOnly || $this->serial_number->MultiUpdate != "1");

            // value
            $this->value->setDbValueDef($rsnew, $this->value->CurrentValue, null, $this->value->ReadOnly || $this->value->MultiUpdate != "1");

            // asset_received
            $this->asset_received->setDbValueDef($rsnew, UnFormatDateTime($this->asset_received->CurrentValue, 5), null, $this->asset_received->ReadOnly || $this->asset_received->MultiUpdate != "1");

            // notes
            $this->notes->setDbValueDef($rsnew, $this->notes->CurrentValue, null, $this->notes->ReadOnly || $this->notes->MultiUpdate != "1");

            // Call Row Updating event
            $updateRow = $this->rowUpdating($rsold, $rsnew);
            if ($updateRow) {
                if (count($rsnew) > 0) {
                    $editRow = $this->update($rsnew, "", $rsold);
                } else {
                    $editRow = true; // No field to update
                }
                if ($editRow) {
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("myassetlist"), "", $this->TableVar, true);
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
