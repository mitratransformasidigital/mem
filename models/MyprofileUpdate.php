<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class MyprofileUpdate extends Myprofile
{
    use MessagesTrait;

    // Page ID
    public $PageID = "update";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'myprofile';

    // Page object name
    public $PageObjName = "MyprofileUpdate";

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

        // Table object (myprofile)
        if (!isset($GLOBALS["myprofile"]) || get_class($GLOBALS["myprofile"]) == PROJECT_NAMESPACE . "myprofile") {
            $GLOBALS["myprofile"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

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
                    if ($pageName == "myprofileview") {
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
        $this->employee_name->setVisibility();
        $this->employee_username->setVisibility();
        $this->employee_password->Visible = false;
        $this->employee_email->setVisibility();
        $this->birth_date->setVisibility();
        $this->nik->setVisibility();
        $this->npwp->setVisibility();
        $this->address->setVisibility();
        $this->city_id->setVisibility();
        $this->postal_code->setVisibility();
        $this->bank_number->setVisibility();
        $this->bank_name->setVisibility();
        $this->scan_ktp->Visible = false;
        $this->scan_npwp->Visible = false;
        $this->curiculum_vitae->Visible = false;
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
        $this->employee_username->Required = false;

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->city_id);
        $this->setupLookupOptions($this->position_id);
        $this->setupLookupOptions($this->status_id);
        $this->setupLookupOptions($this->skill_id);
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
            $this->terminate("myprofilelist"); // No records selected, return to list
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
                    $this->employee_name->setDbValue($rs->fields['employee_name']);
                    $this->employee_username->setDbValue($rs->fields['employee_username']);
                    $this->employee_email->setDbValue($rs->fields['employee_email']);
                    $this->birth_date->setDbValue($rs->fields['birth_date']);
                    $this->nik->setDbValue($rs->fields['nik']);
                    $this->npwp->setDbValue($rs->fields['npwp']);
                    $this->address->setDbValue($rs->fields['address']);
                    $this->city_id->setDbValue($rs->fields['city_id']);
                    $this->postal_code->setDbValue($rs->fields['postal_code']);
                    $this->bank_number->setDbValue($rs->fields['bank_number']);
                    $this->bank_name->setDbValue($rs->fields['bank_name']);
                    $this->technical_skill->setDbValue($rs->fields['technical_skill']);
                    $this->about_me->setDbValue($rs->fields['about_me']);
                    $this->position_id->setDbValue($rs->fields['position_id']);
                    $this->religion->setDbValue($rs->fields['religion']);
                    $this->status_id->setDbValue($rs->fields['status_id']);
                    $this->skill_id->setDbValue($rs->fields['skill_id']);
                    $this->office_id->setDbValue($rs->fields['office_id']);
                    $this->hire_date->setDbValue($rs->fields['hire_date']);
                    $this->termination_date->setDbValue($rs->fields['termination_date']);
                } else {
                    if (!CompareValue($this->employee_name->DbValue, $rs->fields['employee_name'])) {
                        $this->employee_name->CurrentValue = null;
                    }
                    if (!CompareValue($this->employee_username->DbValue, $rs->fields['employee_username'])) {
                        $this->employee_username->CurrentValue = null;
                    }
                    if (!CompareValue($this->employee_email->DbValue, $rs->fields['employee_email'])) {
                        $this->employee_email->CurrentValue = null;
                    }
                    if (!CompareValue($this->birth_date->DbValue, $rs->fields['birth_date'])) {
                        $this->birth_date->CurrentValue = null;
                    }
                    if (!CompareValue($this->nik->DbValue, $rs->fields['nik'])) {
                        $this->nik->CurrentValue = null;
                    }
                    if (!CompareValue($this->npwp->DbValue, $rs->fields['npwp'])) {
                        $this->npwp->CurrentValue = null;
                    }
                    if (!CompareValue($this->address->DbValue, $rs->fields['address'])) {
                        $this->address->CurrentValue = null;
                    }
                    if (!CompareValue($this->city_id->DbValue, $rs->fields['city_id'])) {
                        $this->city_id->CurrentValue = null;
                    }
                    if (!CompareValue($this->postal_code->DbValue, $rs->fields['postal_code'])) {
                        $this->postal_code->CurrentValue = null;
                    }
                    if (!CompareValue($this->bank_number->DbValue, $rs->fields['bank_number'])) {
                        $this->bank_number->CurrentValue = null;
                    }
                    if (!CompareValue($this->bank_name->DbValue, $rs->fields['bank_name'])) {
                        $this->bank_name->CurrentValue = null;
                    }
                    if (!CompareValue($this->technical_skill->DbValue, $rs->fields['technical_skill'])) {
                        $this->technical_skill->CurrentValue = null;
                    }
                    if (!CompareValue($this->about_me->DbValue, $rs->fields['about_me'])) {
                        $this->about_me->CurrentValue = null;
                    }
                    if (!CompareValue($this->position_id->DbValue, $rs->fields['position_id'])) {
                        $this->position_id->CurrentValue = null;
                    }
                    if (!CompareValue($this->religion->DbValue, $rs->fields['religion'])) {
                        $this->religion->CurrentValue = null;
                    }
                    if (!CompareValue($this->status_id->DbValue, $rs->fields['status_id'])) {
                        $this->status_id->CurrentValue = null;
                    }
                    if (!CompareValue($this->skill_id->DbValue, $rs->fields['skill_id'])) {
                        $this->skill_id->CurrentValue = null;
                    }
                    if (!CompareValue($this->office_id->DbValue, $rs->fields['office_id'])) {
                        $this->office_id->CurrentValue = null;
                    }
                    if (!CompareValue($this->hire_date->DbValue, $rs->fields['hire_date'])) {
                        $this->hire_date->CurrentValue = null;
                    }
                    if (!CompareValue($this->termination_date->DbValue, $rs->fields['termination_date'])) {
                        $this->termination_date->CurrentValue = null;
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
        $this->employee_username->OldValue = $keyFld;
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

        // Check field name 'employee_name' first before field var 'x_employee_name'
        $val = $CurrentForm->hasValue("employee_name") ? $CurrentForm->getValue("employee_name") : $CurrentForm->getValue("x_employee_name");
        if (!$this->employee_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->employee_name->Visible = false; // Disable update for API request
            } else {
                $this->employee_name->setFormValue($val);
            }
        }
        $this->employee_name->MultiUpdate = $CurrentForm->getValue("u_employee_name");

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

        // Check field name 'employee_email' first before field var 'x_employee_email'
        $val = $CurrentForm->hasValue("employee_email") ? $CurrentForm->getValue("employee_email") : $CurrentForm->getValue("x_employee_email");
        if (!$this->employee_email->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->employee_email->Visible = false; // Disable update for API request
            } else {
                $this->employee_email->setFormValue($val);
            }
        }
        $this->employee_email->MultiUpdate = $CurrentForm->getValue("u_employee_email");

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
        $this->birth_date->MultiUpdate = $CurrentForm->getValue("u_birth_date");

        // Check field name 'nik' first before field var 'x_nik'
        $val = $CurrentForm->hasValue("nik") ? $CurrentForm->getValue("nik") : $CurrentForm->getValue("x_nik");
        if (!$this->nik->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nik->Visible = false; // Disable update for API request
            } else {
                $this->nik->setFormValue($val);
            }
        }
        $this->nik->MultiUpdate = $CurrentForm->getValue("u_nik");

        // Check field name 'npwp' first before field var 'x_npwp'
        $val = $CurrentForm->hasValue("npwp") ? $CurrentForm->getValue("npwp") : $CurrentForm->getValue("x_npwp");
        if (!$this->npwp->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->npwp->Visible = false; // Disable update for API request
            } else {
                $this->npwp->setFormValue($val);
            }
        }
        $this->npwp->MultiUpdate = $CurrentForm->getValue("u_npwp");

        // Check field name 'address' first before field var 'x_address'
        $val = $CurrentForm->hasValue("address") ? $CurrentForm->getValue("address") : $CurrentForm->getValue("x_address");
        if (!$this->address->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->address->Visible = false; // Disable update for API request
            } else {
                $this->address->setFormValue($val);
            }
        }
        $this->address->MultiUpdate = $CurrentForm->getValue("u_address");

        // Check field name 'city_id' first before field var 'x_city_id'
        $val = $CurrentForm->hasValue("city_id") ? $CurrentForm->getValue("city_id") : $CurrentForm->getValue("x_city_id");
        if (!$this->city_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->city_id->Visible = false; // Disable update for API request
            } else {
                $this->city_id->setFormValue($val);
            }
        }
        $this->city_id->MultiUpdate = $CurrentForm->getValue("u_city_id");

        // Check field name 'postal_code' first before field var 'x_postal_code'
        $val = $CurrentForm->hasValue("postal_code") ? $CurrentForm->getValue("postal_code") : $CurrentForm->getValue("x_postal_code");
        if (!$this->postal_code->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->postal_code->Visible = false; // Disable update for API request
            } else {
                $this->postal_code->setFormValue($val);
            }
        }
        $this->postal_code->MultiUpdate = $CurrentForm->getValue("u_postal_code");

        // Check field name 'bank_number' first before field var 'x_bank_number'
        $val = $CurrentForm->hasValue("bank_number") ? $CurrentForm->getValue("bank_number") : $CurrentForm->getValue("x_bank_number");
        if (!$this->bank_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->bank_number->Visible = false; // Disable update for API request
            } else {
                $this->bank_number->setFormValue($val);
            }
        }
        $this->bank_number->MultiUpdate = $CurrentForm->getValue("u_bank_number");

        // Check field name 'bank_name' first before field var 'x_bank_name'
        $val = $CurrentForm->hasValue("bank_name") ? $CurrentForm->getValue("bank_name") : $CurrentForm->getValue("x_bank_name");
        if (!$this->bank_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->bank_name->Visible = false; // Disable update for API request
            } else {
                $this->bank_name->setFormValue($val);
            }
        }
        $this->bank_name->MultiUpdate = $CurrentForm->getValue("u_bank_name");

        // Check field name 'technical_skill' first before field var 'x_technical_skill'
        $val = $CurrentForm->hasValue("technical_skill") ? $CurrentForm->getValue("technical_skill") : $CurrentForm->getValue("x_technical_skill");
        if (!$this->technical_skill->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->technical_skill->Visible = false; // Disable update for API request
            } else {
                $this->technical_skill->setFormValue($val);
            }
        }
        $this->technical_skill->MultiUpdate = $CurrentForm->getValue("u_technical_skill");

        // Check field name 'about_me' first before field var 'x_about_me'
        $val = $CurrentForm->hasValue("about_me") ? $CurrentForm->getValue("about_me") : $CurrentForm->getValue("x_about_me");
        if (!$this->about_me->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->about_me->Visible = false; // Disable update for API request
            } else {
                $this->about_me->setFormValue($val);
            }
        }
        $this->about_me->MultiUpdate = $CurrentForm->getValue("u_about_me");

        // Check field name 'position_id' first before field var 'x_position_id'
        $val = $CurrentForm->hasValue("position_id") ? $CurrentForm->getValue("position_id") : $CurrentForm->getValue("x_position_id");
        if (!$this->position_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->position_id->Visible = false; // Disable update for API request
            } else {
                $this->position_id->setFormValue($val);
            }
        }
        $this->position_id->MultiUpdate = $CurrentForm->getValue("u_position_id");

        // Check field name 'religion' first before field var 'x_religion'
        $val = $CurrentForm->hasValue("religion") ? $CurrentForm->getValue("religion") : $CurrentForm->getValue("x_religion");
        if (!$this->religion->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->religion->Visible = false; // Disable update for API request
            } else {
                $this->religion->setFormValue($val);
            }
        }
        $this->religion->MultiUpdate = $CurrentForm->getValue("u_religion");

        // Check field name 'status_id' first before field var 'x_status_id'
        $val = $CurrentForm->hasValue("status_id") ? $CurrentForm->getValue("status_id") : $CurrentForm->getValue("x_status_id");
        if (!$this->status_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->status_id->Visible = false; // Disable update for API request
            } else {
                $this->status_id->setFormValue($val);
            }
        }
        $this->status_id->MultiUpdate = $CurrentForm->getValue("u_status_id");

        // Check field name 'skill_id' first before field var 'x_skill_id'
        $val = $CurrentForm->hasValue("skill_id") ? $CurrentForm->getValue("skill_id") : $CurrentForm->getValue("x_skill_id");
        if (!$this->skill_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->skill_id->Visible = false; // Disable update for API request
            } else {
                $this->skill_id->setFormValue($val);
            }
        }
        $this->skill_id->MultiUpdate = $CurrentForm->getValue("u_skill_id");

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
        $this->hire_date->MultiUpdate = $CurrentForm->getValue("u_hire_date");

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
        $this->termination_date->MultiUpdate = $CurrentForm->getValue("u_termination_date");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->employee_name->CurrentValue = $this->employee_name->FormValue;
        $this->employee_username->CurrentValue = $this->employee_username->FormValue;
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
        $this->scan_npwp->Upload->DbValue = $row['scan_npwp'];
        $this->scan_npwp->setDbValue($this->scan_npwp->Upload->DbValue);
        $this->curiculum_vitae->Upload->DbValue = $row['curiculum_vitae'];
        $this->curiculum_vitae->setDbValue($this->curiculum_vitae->Upload->DbValue);
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
        $row = [];
        $row['employee_name'] = null;
        $row['employee_username'] = null;
        $row['employee_password'] = null;
        $row['employee_email'] = null;
        $row['birth_date'] = null;
        $row['nik'] = null;
        $row['npwp'] = null;
        $row['address'] = null;
        $row['city_id'] = null;
        $row['postal_code'] = null;
        $row['bank_number'] = null;
        $row['bank_name'] = null;
        $row['scan_ktp'] = null;
        $row['scan_npwp'] = null;
        $row['curiculum_vitae'] = null;
        $row['technical_skill'] = null;
        $row['about_me'] = null;
        $row['position_id'] = null;
        $row['religion'] = null;
        $row['status_id'] = null;
        $row['skill_id'] = null;
        $row['office_id'] = null;
        $row['hire_date'] = null;
        $row['termination_date'] = null;
        $row['user_level'] = null;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

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
        $updateCnt = 0;
        if ($this->employee_name->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->employee_username->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->employee_email->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->birth_date->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->nik->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->npwp->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->address->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->city_id->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->postal_code->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->bank_number->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->bank_name->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->technical_skill->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->about_me->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->position_id->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->religion->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->status_id->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->skill_id->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->office_id->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->hire_date->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->termination_date->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($updateCnt == 0) {
            return false;
        }

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->employee_name->Required) {
            if ($this->employee_name->MultiUpdate != "" && !$this->employee_name->IsDetailKey && EmptyValue($this->employee_name->FormValue)) {
                $this->employee_name->addErrorMessage(str_replace("%s", $this->employee_name->caption(), $this->employee_name->RequiredErrorMessage));
            }
        }
        if ($this->employee_username->Required) {
            if ($this->employee_username->MultiUpdate != "" && !$this->employee_username->IsDetailKey && EmptyValue($this->employee_username->FormValue)) {
                $this->employee_username->addErrorMessage(str_replace("%s", $this->employee_username->caption(), $this->employee_username->RequiredErrorMessage));
            }
        }
        if ($this->employee_email->Required) {
            if ($this->employee_email->MultiUpdate != "" && !$this->employee_email->IsDetailKey && EmptyValue($this->employee_email->FormValue)) {
                $this->employee_email->addErrorMessage(str_replace("%s", $this->employee_email->caption(), $this->employee_email->RequiredErrorMessage));
            }
        }
        if ($this->birth_date->Required) {
            if ($this->birth_date->MultiUpdate != "" && !$this->birth_date->IsDetailKey && EmptyValue($this->birth_date->FormValue)) {
                $this->birth_date->addErrorMessage(str_replace("%s", $this->birth_date->caption(), $this->birth_date->RequiredErrorMessage));
            }
        }
        if ($this->birth_date->MultiUpdate != "") {
            if (!CheckStdDate($this->birth_date->FormValue)) {
                $this->birth_date->addErrorMessage($this->birth_date->getErrorMessage(false));
            }
        }
        if ($this->nik->Required) {
            if ($this->nik->MultiUpdate != "" && !$this->nik->IsDetailKey && EmptyValue($this->nik->FormValue)) {
                $this->nik->addErrorMessage(str_replace("%s", $this->nik->caption(), $this->nik->RequiredErrorMessage));
            }
        }
        if ($this->npwp->Required) {
            if ($this->npwp->MultiUpdate != "" && !$this->npwp->IsDetailKey && EmptyValue($this->npwp->FormValue)) {
                $this->npwp->addErrorMessage(str_replace("%s", $this->npwp->caption(), $this->npwp->RequiredErrorMessage));
            }
        }
        if ($this->address->Required) {
            if ($this->address->MultiUpdate != "" && !$this->address->IsDetailKey && EmptyValue($this->address->FormValue)) {
                $this->address->addErrorMessage(str_replace("%s", $this->address->caption(), $this->address->RequiredErrorMessage));
            }
        }
        if ($this->city_id->Required) {
            if ($this->city_id->MultiUpdate != "" && !$this->city_id->IsDetailKey && EmptyValue($this->city_id->FormValue)) {
                $this->city_id->addErrorMessage(str_replace("%s", $this->city_id->caption(), $this->city_id->RequiredErrorMessage));
            }
        }
        if ($this->postal_code->Required) {
            if ($this->postal_code->MultiUpdate != "" && !$this->postal_code->IsDetailKey && EmptyValue($this->postal_code->FormValue)) {
                $this->postal_code->addErrorMessage(str_replace("%s", $this->postal_code->caption(), $this->postal_code->RequiredErrorMessage));
            }
        }
        if ($this->bank_number->Required) {
            if ($this->bank_number->MultiUpdate != "" && !$this->bank_number->IsDetailKey && EmptyValue($this->bank_number->FormValue)) {
                $this->bank_number->addErrorMessage(str_replace("%s", $this->bank_number->caption(), $this->bank_number->RequiredErrorMessage));
            }
        }
        if ($this->bank_name->Required) {
            if ($this->bank_name->MultiUpdate != "" && !$this->bank_name->IsDetailKey && EmptyValue($this->bank_name->FormValue)) {
                $this->bank_name->addErrorMessage(str_replace("%s", $this->bank_name->caption(), $this->bank_name->RequiredErrorMessage));
            }
        }
        if ($this->technical_skill->Required) {
            if ($this->technical_skill->MultiUpdate != "" && !$this->technical_skill->IsDetailKey && EmptyValue($this->technical_skill->FormValue)) {
                $this->technical_skill->addErrorMessage(str_replace("%s", $this->technical_skill->caption(), $this->technical_skill->RequiredErrorMessage));
            }
        }
        if ($this->about_me->Required) {
            if ($this->about_me->MultiUpdate != "" && !$this->about_me->IsDetailKey && EmptyValue($this->about_me->FormValue)) {
                $this->about_me->addErrorMessage(str_replace("%s", $this->about_me->caption(), $this->about_me->RequiredErrorMessage));
            }
        }
        if ($this->position_id->Required) {
            if ($this->position_id->MultiUpdate != "" && !$this->position_id->IsDetailKey && EmptyValue($this->position_id->FormValue)) {
                $this->position_id->addErrorMessage(str_replace("%s", $this->position_id->caption(), $this->position_id->RequiredErrorMessage));
            }
        }
        if ($this->religion->Required) {
            if ($this->religion->MultiUpdate != "" && !$this->religion->IsDetailKey && EmptyValue($this->religion->FormValue)) {
                $this->religion->addErrorMessage(str_replace("%s", $this->religion->caption(), $this->religion->RequiredErrorMessage));
            }
        }
        if ($this->status_id->Required) {
            if ($this->status_id->MultiUpdate != "" && !$this->status_id->IsDetailKey && EmptyValue($this->status_id->FormValue)) {
                $this->status_id->addErrorMessage(str_replace("%s", $this->status_id->caption(), $this->status_id->RequiredErrorMessage));
            }
        }
        if ($this->skill_id->Required) {
            if ($this->skill_id->MultiUpdate != "" && !$this->skill_id->IsDetailKey && EmptyValue($this->skill_id->FormValue)) {
                $this->skill_id->addErrorMessage(str_replace("%s", $this->skill_id->caption(), $this->skill_id->RequiredErrorMessage));
            }
        }
        if ($this->office_id->Required) {
            if ($this->office_id->MultiUpdate != "" && !$this->office_id->IsDetailKey && EmptyValue($this->office_id->FormValue)) {
                $this->office_id->addErrorMessage(str_replace("%s", $this->office_id->caption(), $this->office_id->RequiredErrorMessage));
            }
        }
        if ($this->hire_date->Required) {
            if ($this->hire_date->MultiUpdate != "" && !$this->hire_date->IsDetailKey && EmptyValue($this->hire_date->FormValue)) {
                $this->hire_date->addErrorMessage(str_replace("%s", $this->hire_date->caption(), $this->hire_date->RequiredErrorMessage));
            }
        }
        if ($this->hire_date->MultiUpdate != "") {
            if (!CheckStdDate($this->hire_date->FormValue)) {
                $this->hire_date->addErrorMessage($this->hire_date->getErrorMessage(false));
            }
        }
        if ($this->termination_date->Required) {
            if ($this->termination_date->MultiUpdate != "" && !$this->termination_date->IsDetailKey && EmptyValue($this->termination_date->FormValue)) {
                $this->termination_date->addErrorMessage(str_replace("%s", $this->termination_date->caption(), $this->termination_date->RequiredErrorMessage));
            }
        }
        if ($this->termination_date->MultiUpdate != "") {
            if (!CheckStdDate($this->termination_date->FormValue)) {
                $this->termination_date->addErrorMessage($this->termination_date->getErrorMessage(false));
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

            // employee_name
            $this->employee_name->setDbValueDef($rsnew, $this->employee_name->CurrentValue, "", $this->employee_name->ReadOnly || $this->employee_name->MultiUpdate != "1");

            // employee_username
            $this->employee_username->setDbValueDef($rsnew, $this->employee_username->CurrentValue, "", $this->employee_username->ReadOnly || $this->employee_username->MultiUpdate != "1");

            // employee_email
            $this->employee_email->setDbValueDef($rsnew, $this->employee_email->CurrentValue, "", $this->employee_email->ReadOnly || $this->employee_email->MultiUpdate != "1");

            // birth_date
            $this->birth_date->setDbValueDef($rsnew, UnFormatDateTime($this->birth_date->CurrentValue, 5), null, $this->birth_date->ReadOnly || $this->birth_date->MultiUpdate != "1");

            // nik
            $this->nik->setDbValueDef($rsnew, $this->nik->CurrentValue, "", $this->nik->ReadOnly || $this->nik->MultiUpdate != "1");

            // npwp
            $this->npwp->setDbValueDef($rsnew, $this->npwp->CurrentValue, null, $this->npwp->ReadOnly || $this->npwp->MultiUpdate != "1");

            // address
            $this->address->setDbValueDef($rsnew, $this->address->CurrentValue, null, $this->address->ReadOnly || $this->address->MultiUpdate != "1");

            // city_id
            if ($this->city_id->getSessionValue() != "") {
                $this->city_id->ReadOnly = true;
            }
            $this->city_id->setDbValueDef($rsnew, $this->city_id->CurrentValue, null, $this->city_id->ReadOnly || $this->city_id->MultiUpdate != "1");

            // postal_code
            $this->postal_code->setDbValueDef($rsnew, $this->postal_code->CurrentValue, null, $this->postal_code->ReadOnly || $this->postal_code->MultiUpdate != "1");

            // bank_number
            $this->bank_number->setDbValueDef($rsnew, $this->bank_number->CurrentValue, "", $this->bank_number->ReadOnly || $this->bank_number->MultiUpdate != "1");

            // bank_name
            $this->bank_name->setDbValueDef($rsnew, $this->bank_name->CurrentValue, "", $this->bank_name->ReadOnly || $this->bank_name->MultiUpdate != "1");

            // technical_skill
            $this->technical_skill->setDbValueDef($rsnew, $this->technical_skill->CurrentValue, null, $this->technical_skill->ReadOnly || $this->technical_skill->MultiUpdate != "1");

            // about_me
            $this->about_me->setDbValueDef($rsnew, $this->about_me->CurrentValue, null, $this->about_me->ReadOnly || $this->about_me->MultiUpdate != "1");

            // position_id
            if ($this->position_id->getSessionValue() != "") {
                $this->position_id->ReadOnly = true;
            }
            $this->position_id->setDbValueDef($rsnew, $this->position_id->CurrentValue, null, $this->position_id->ReadOnly || $this->position_id->MultiUpdate != "1");

            // religion
            $this->religion->setDbValueDef($rsnew, $this->religion->CurrentValue, null, $this->religion->ReadOnly || $this->religion->MultiUpdate != "1");

            // status_id
            if ($this->status_id->getSessionValue() != "") {
                $this->status_id->ReadOnly = true;
            }
            $this->status_id->setDbValueDef($rsnew, $this->status_id->CurrentValue, null, $this->status_id->ReadOnly || $this->status_id->MultiUpdate != "1");

            // skill_id
            if ($this->skill_id->getSessionValue() != "") {
                $this->skill_id->ReadOnly = true;
            }
            $this->skill_id->setDbValueDef($rsnew, $this->skill_id->CurrentValue, null, $this->skill_id->ReadOnly || $this->skill_id->MultiUpdate != "1");

            // office_id
            if ($this->office_id->getSessionValue() != "") {
                $this->office_id->ReadOnly = true;
            }
            $this->office_id->setDbValueDef($rsnew, $this->office_id->CurrentValue, null, $this->office_id->ReadOnly || $this->office_id->MultiUpdate != "1");

            // hire_date
            $this->hire_date->setDbValueDef($rsnew, UnFormatDateTime($this->hire_date->CurrentValue, 5), null, $this->hire_date->ReadOnly || $this->hire_date->MultiUpdate != "1");

            // termination_date
            $this->termination_date->setDbValueDef($rsnew, UnFormatDateTime($this->termination_date->CurrentValue, 5), null, $this->termination_date->ReadOnly || $this->termination_date->MultiUpdate != "1");

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("myprofilelist"), "", $this->TableVar, true);
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
}
