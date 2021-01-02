<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EmployeeView extends Employee
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'employee';

    // Page object name
    public $PageObjName = "EmployeeView";

    // Rendering View
    public $RenderingView = false;

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $CopyUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $ListUrl;

    // Export URLs
    public $ExportPrintUrl;
    public $ExportHtmlUrl;
    public $ExportExcelUrl;
    public $ExportWordUrl;
    public $ExportXmlUrl;
    public $ExportCsvUrl;
    public $ExportPdfUrl;

    // Custom export
    public $ExportExcelCustom = false;
    public $ExportWordCustom = false;
    public $ExportPdfCustom = false;
    public $ExportEmailCustom = false;

    // Update URLs
    public $InlineAddUrl;
    public $InlineCopyUrl;
    public $InlineEditUrl;
    public $GridAddUrl;
    public $GridEditUrl;
    public $MultiDeleteUrl;
    public $MultiUpdateUrl;

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

        // Table object (employee)
        if (!isset($GLOBALS["employee"]) || get_class($GLOBALS["employee"]) == PROJECT_NAMESPACE . "employee") {
            $GLOBALS["employee"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        if (($keyValue = Get("employee_username") ?? Route("employee_username")) !== null) {
            $this->RecKey["employee_username"] = $keyValue;
        }
        $this->ExportPrintUrl = $pageUrl . "export=print";
        $this->ExportHtmlUrl = $pageUrl . "export=html";
        $this->ExportExcelUrl = $pageUrl . "export=excel";
        $this->ExportWordUrl = $pageUrl . "export=word";
        $this->ExportXmlUrl = $pageUrl . "export=xml";
        $this->ExportCsvUrl = $pageUrl . "export=csv";
        $this->ExportPdfUrl = $pageUrl . "export=pdf";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'employee');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // Export options
        $this->ExportOptions = new ListOptions("div");
        $this->ExportOptions->TagClassName = "ew-export-option";

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }
        $this->OtherOptions["action"] = new ListOptions("div");
        $this->OtherOptions["action"]->TagClassName = "ew-action-option";
        $this->OtherOptions["detail"] = new ListOptions("div");
        $this->OtherOptions["detail"]->TagClassName = "ew-detail-option";
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
                $doc = new $class(Container("employee"));
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
                    if ($pageName == "employeeview") {
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
    public $ExportOptions; // Export options
    public $OtherOptions; // Other options
    public $DisplayRecords = 1;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecKey = [];
    public $IsModal = false;
    public $employee_shift_Count;
    public $activity_Count;
    public $permit_Count;
    public $employee_contract_Count;
    public $employee_asset_Count;
    public $employee_timesheet_Count;
    public $employee_trainings_Count;
    public $MultiPages; // Multi pages object
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

        // Get export parameters
        $custom = "";
        if (Param("export") !== null) {
            $this->Export = Param("export");
            $custom = Param("custom", "");
        } elseif (IsPost()) {
            if (Post("exporttype") !== null) {
                $this->Export = Post("exporttype");
            }
            $custom = Post("custom", "");
        } elseif (Get("cmd") == "json") {
            $this->Export = Get("cmd");
        } else {
            $this->setExportReturnUrl(CurrentUrl());
        }
        $ExportFileName = $this->TableVar; // Get export file, used in header
        if (Get("employee_username") !== null) {
            if ($ExportFileName != "") {
                $ExportFileName .= "_";
            }
            $ExportFileName .= Get("employee_username");
        }

        // Get custom export parameters
        if ($this->isExport() && $custom != "") {
            $this->CustomExport = $this->Export;
            $this->Export = "print";
        }
        $CustomExportType = $this->CustomExport;
        $ExportType = $this->Export; // Get export parameter, used in header

        // Update Export URLs
        if (Config("USE_PHPEXCEL")) {
            $this->ExportExcelCustom = false;
        }
        if (Config("USE_PHPWORD")) {
            $this->ExportWordCustom = false;
        }
        if ($this->ExportExcelCustom) {
            $this->ExportExcelUrl .= "&amp;custom=1";
        }
        if ($this->ExportWordCustom) {
            $this->ExportWordUrl .= "&amp;custom=1";
        }
        if ($this->ExportPdfCustom) {
            $this->ExportPdfUrl .= "&amp;custom=1";
        }
        $this->CurrentAction = Param("action"); // Set up current action

        // Setup export options
        $this->setupExportOptions();
        $this->employee_name->setVisibility();
        $this->employee_username->setVisibility();
        $this->employee_password->setVisibility();
        $this->employee_email->setVisibility();
        $this->birth_date->setVisibility();
        $this->religion->setVisibility();
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
        $this->position_id->setVisibility();
        $this->status_id->setVisibility();
        $this->skill_id->setVisibility();
        $this->office_id->setVisibility();
        $this->hire_date->setVisibility();
        $this->termination_date->setVisibility();
        $this->user_level->setVisibility();
        $this->technical_skill->setVisibility();
        $this->about_me->setVisibility();
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Set up multi page object
        $this->setupMultiPages();

        // Set up detail page object
        $this->setupDetailPages();

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
        $this->setupLookupOptions($this->user_level);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }

        // Load current record
        $loadCurrentRecord = false;
        $returnUrl = "";
        $matchRecord = false;

        // Set up master/detail parameters
        $this->setupMasterParms();
        if ($this->isPageRequest()) { // Validate request
            if (($keyValue = Get("employee_username") ?? Route("employee_username")) !== null) {
                $this->employee_username->setQueryStringValue($keyValue);
                $this->RecKey["employee_username"] = $this->employee_username->QueryStringValue;
            } elseif (Post("employee_username") !== null) {
                $this->employee_username->setFormValue(Post("employee_username"));
                $this->RecKey["employee_username"] = $this->employee_username->FormValue;
            } elseif (IsApi() && ($keyValue = Key(0) ?? Route(2)) !== null) {
                $this->employee_username->setQueryStringValue($keyValue);
                $this->RecKey["employee_username"] = $this->employee_username->QueryStringValue;
            } else {
                $loadCurrentRecord = true;
            }

            // Get action
            $this->CurrentAction = "show"; // Display
            switch ($this->CurrentAction) {
                case "show": // Get a record to display
                    $this->StartRecord = 1; // Initialize start position
                    if ($this->Recordset = $this->loadRecordset()) { // Load records
                        $this->TotalRecords = $this->Recordset->recordCount(); // Get record count
                    }
                    if ($this->TotalRecords <= 0) { // No record found
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("employeelist"); // Return to list page
                        return;
                    } elseif ($loadCurrentRecord) { // Load current record position
                        $this->setupStartRecord(); // Set up start record position
                        // Point to current record
                        if ($this->StartRecord <= $this->TotalRecords) {
                            $matchRecord = true;
                            $this->Recordset->move($this->StartRecord - 1);
                        }
                    } else { // Match key values
                        while (!$this->Recordset->EOF) {
                            if (SameString($this->employee_username->CurrentValue, $this->Recordset->fields['employee_username'])) {
                                $this->setStartRecordNumber($this->StartRecord); // Save record position
                                $matchRecord = true;
                                break;
                            } else {
                                $this->StartRecord++;
                                $this->Recordset->moveNext();
                            }
                        }
                    }
                    if (!$matchRecord) {
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $returnUrl = "employeelist"; // No matching record, return to list
                    } else {
                        $this->loadRowValues($this->Recordset); // Load row values
                    }
                    break;
            }

            // Export data only
            if (!$this->CustomExport && in_array($this->Export, array_keys(Config("EXPORT_CLASSES")))) {
                $this->exportData();
                $this->terminate();
                return;
            }
        } else {
            $returnUrl = "employeelist"; // Not page request, return to list
        }
        if ($returnUrl != "") {
            $this->terminate($returnUrl);
            return;
        }

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Render row
        $this->RowType = ROWTYPE_VIEW;
        $this->resetAttributes();
        $this->renderRow();

        // Set up detail parameters
        $this->setupDetailParms();

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset, true); // Get current record only
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows]);
            $this->terminate(true);
            return;
        }

        // Set up pager
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

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["action"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("ViewPageAddLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,url:'" . HtmlEncode(GetUrl($this->AddUrl)) . "'});\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        }
        $item->Visible = ($this->AddUrl != "" && $Security->canAdd());

        // Edit
        $item = &$option->add("edit");
        $editcaption = HtmlTitle($Language->phrase("ViewPageEditLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,url:'" . HtmlEncode(GetUrl($this->EditUrl)) . "'});\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        }
        $item->Visible = ($this->EditUrl != "" && $Security->canEdit());

        // Copy
        $item = &$option->add("copy");
        $copycaption = HtmlTitle($Language->phrase("ViewPageCopyLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,btn:'AddBtn',url:'" . HtmlEncode(GetUrl($this->CopyUrl)) . "'});\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        }
        $item->Visible = ($this->CopyUrl != "" && $Security->canAdd());

        // Delete
        $item = &$option->add("delete");
        if ($this->IsModal) { // Handle as inline delete
            $item->Body = "<a onclick=\"return ew.confirmDelete(this);\" class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(UrlAddQuery(GetUrl($this->DeleteUrl), "action=1")) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        }
        $item->Visible = ($this->DeleteUrl != "" && $Security->canDelete());
        $option = $options["detail"];
        $detailTableLink = "";
        $detailViewTblVar = "";
        $detailCopyTblVar = "";
        $detailEditTblVar = "";

        // "detail_employee_shift"
        $item = &$option->add("detail_employee_shift");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("employee_shift", "TblCaption");
        $body .= "&nbsp;" . str_replace("%c", $this->employee_shift_Count, $Language->phrase("DetailCount"));
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("employeeshiftlist?" . Config("TABLE_SHOW_MASTER") . "=employee&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("EmployeeShiftGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=employee_shift"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "employee_shift";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=employee_shift"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "employee_shift";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=employee_shift"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "employee_shift";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'employee_shift');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "employee_shift";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_activity"
        $item = &$option->add("detail_activity");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("activity", "TblCaption");
        $body .= "&nbsp;" . str_replace("%c", $this->activity_Count, $Language->phrase("DetailCount"));
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("activitylist?" . Config("TABLE_SHOW_MASTER") . "=employee&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("ActivityGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=activity"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "activity";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=activity"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "activity";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=activity"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "activity";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'activity');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "activity";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_permit"
        $item = &$option->add("detail_permit");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("permit", "TblCaption");
        $body .= "&nbsp;" . str_replace("%c", $this->permit_Count, $Language->phrase("DetailCount"));
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("permitlist?" . Config("TABLE_SHOW_MASTER") . "=employee&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("PermitGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=permit"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "permit";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=permit"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "permit";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=permit"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "permit";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'permit');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "permit";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_employee_contract"
        $item = &$option->add("detail_employee_contract");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("employee_contract", "TblCaption");
        $body .= "&nbsp;" . str_replace("%c", $this->employee_contract_Count, $Language->phrase("DetailCount"));
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("employeecontractlist?" . Config("TABLE_SHOW_MASTER") . "=employee&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("EmployeeContractGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=employee_contract"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "employee_contract";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=employee_contract"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "employee_contract";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=employee_contract"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "employee_contract";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'employee_contract');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "employee_contract";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_employee_asset"
        $item = &$option->add("detail_employee_asset");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("employee_asset", "TblCaption");
        $body .= "&nbsp;" . str_replace("%c", $this->employee_asset_Count, $Language->phrase("DetailCount"));
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("employeeassetlist?" . Config("TABLE_SHOW_MASTER") . "=employee&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("EmployeeAssetGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=employee_asset"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "employee_asset";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=employee_asset"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "employee_asset";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=employee_asset"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "employee_asset";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'employee_asset');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "employee_asset";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_employee_timesheet"
        $item = &$option->add("detail_employee_timesheet");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("employee_timesheet", "TblCaption");
        $body .= "&nbsp;" . str_replace("%c", $this->employee_timesheet_Count, $Language->phrase("DetailCount"));
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("employeetimesheetlist?" . Config("TABLE_SHOW_MASTER") . "=employee&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("EmployeeTimesheetGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=employee_timesheet"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "employee_timesheet";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=employee_timesheet"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "employee_timesheet";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=employee_timesheet"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "employee_timesheet";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'employee_timesheet');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "employee_timesheet";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_employee_trainings"
        $item = &$option->add("detail_employee_trainings");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("employee_trainings", "TblCaption");
        $body .= "&nbsp;" . str_replace("%c", $this->employee_trainings_Count, $Language->phrase("DetailCount"));
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("employeetrainingslist?" . Config("TABLE_SHOW_MASTER") . "=employee&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("EmployeeTrainingsGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=employee_trainings"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "employee_trainings";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=employee_trainings"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "employee_trainings";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'employee')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=employee_trainings"))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "employee_trainings";
        }
        if ($links != "") {
            $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'employee_trainings');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "employee_trainings";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // Multiple details
        if ($this->ShowMultipleDetails) {
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">";
            $links = "";
            if ($detailViewTblVar != "") {
                $links .= "<li><a class=\"ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailViewTblVar))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailViewLink")) . "</a></li>";
            }
            if ($detailEditTblVar != "") {
                $links .= "<li><a class=\"ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailEditTblVar))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailEditLink")) . "</a></li>";
            }
            if ($detailCopyTblVar != "") {
                $links .= "<li><a class=\"ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailCopyTblVar))) . "\">" . HtmlImageAndText($Language->phrase("MasterDetailCopyLink")) . "</a></li>";
            }
            if ($links != "") {
                $body .= "<button class=\"dropdown-toggle btn btn-default ew-master-detail\" title=\"" . HtmlTitle($Language->phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->phrase("MultipleMasterDetails") . "</button>";
                $body .= "<ul class=\"dropdown-menu ew-menu\">" . $links . "</ul>";
            }
            $body .= "</div>";
            // Multiple details
            $item = &$option->add("details");
            $item->Body = $body;
        }

        // Set up detail default
        $option = $options["detail"];
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $ar = explode(",", $detailTableLink);
        $cnt = count($ar);
        $option->UseDropDownButton = ($cnt > 1);
        $option->UseButtonGroup = true;
        $item = &$option->add($option->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;

        // Set up action default
        $option = $options["action"];
        $option->DropDownButtonPhrase = $Language->phrase("ButtonActions");
        $option->UseDropDownButton = true;
        $option->UseButtonGroup = true;
        $item = &$option->add($option->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;
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
        $this->religion->setDbValue($row['religion']);
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
        $this->position_id->setDbValue($row['position_id']);
        $this->status_id->setDbValue($row['status_id']);
        $this->skill_id->setDbValue($row['skill_id']);
        $this->office_id->setDbValue($row['office_id']);
        $this->hire_date->setDbValue($row['hire_date']);
        $this->termination_date->setDbValue($row['termination_date']);
        $this->user_level->setDbValue($row['user_level']);
        $this->technical_skill->setDbValue($row['technical_skill']);
        $this->about_me->setDbValue($row['about_me']);
        $detailTbl = Container("employee_shift");
        $detailFilter = $detailTbl->sqlDetailFilter_employee();
        $detailFilter = str_replace("@employee_username@", AdjustSql($this->employee_username->DbValue, "DB"), $detailFilter);
        $detailTbl->setCurrentMasterTable("employee");
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $this->employee_shift_Count = $detailTbl->loadRecordCount($detailFilter);
        $detailTbl = Container("activity");
        $detailFilter = $detailTbl->sqlDetailFilter_employee();
        $detailFilter = str_replace("@employee_username@", AdjustSql($this->employee_username->DbValue, "DB"), $detailFilter);
        $detailTbl->setCurrentMasterTable("employee");
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $this->activity_Count = $detailTbl->loadRecordCount($detailFilter);
        $detailTbl = Container("permit");
        $detailFilter = $detailTbl->sqlDetailFilter_employee();
        $detailFilter = str_replace("@employee_username@", AdjustSql($this->employee_username->DbValue, "DB"), $detailFilter);
        $detailTbl->setCurrentMasterTable("employee");
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $this->permit_Count = $detailTbl->loadRecordCount($detailFilter);
        $detailTbl = Container("employee_contract");
        $detailFilter = $detailTbl->sqlDetailFilter_employee();
        $detailFilter = str_replace("@employee_username@", AdjustSql($this->employee_username->DbValue, "DB"), $detailFilter);
        $detailTbl->setCurrentMasterTable("employee");
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $this->employee_contract_Count = $detailTbl->loadRecordCount($detailFilter);
        $detailTbl = Container("employee_asset");
        $detailFilter = $detailTbl->sqlDetailFilter_employee();
        $detailFilter = str_replace("@employee_username@", AdjustSql($this->employee_username->DbValue, "DB"), $detailFilter);
        $detailTbl->setCurrentMasterTable("employee");
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $this->employee_asset_Count = $detailTbl->loadRecordCount($detailFilter);
        $detailTbl = Container("employee_timesheet");
        $detailFilter = $detailTbl->sqlDetailFilter_employee();
        $detailFilter = str_replace("@employee_username@", AdjustSql($this->employee_username->DbValue, "DB"), $detailFilter);
        $detailTbl->setCurrentMasterTable("employee");
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $this->employee_timesheet_Count = $detailTbl->loadRecordCount($detailFilter);
        $detailTbl = Container("employee_trainings");
        $detailFilter = $detailTbl->sqlDetailFilter_employee();
        $detailFilter = str_replace("@employee_username@", AdjustSql($this->employee_username->DbValue, "DB"), $detailFilter);
        $detailTbl->setCurrentMasterTable("employee");
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $this->employee_trainings_Count = $detailTbl->loadRecordCount($detailFilter);
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
        $row['religion'] = null;
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
        $row['position_id'] = null;
        $row['status_id'] = null;
        $row['skill_id'] = null;
        $row['office_id'] = null;
        $row['hire_date'] = null;
        $row['termination_date'] = null;
        $row['user_level'] = null;
        $row['technical_skill'] = null;
        $row['about_me'] = null;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->AddUrl = $this->getAddUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();
        $this->ListUrl = $this->getListUrl();
        $this->setupOtherOptions();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // employee_name

        // employee_username

        // employee_password

        // employee_email

        // birth_date

        // religion

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

        // position_id

        // status_id

        // skill_id

        // office_id

        // hire_date

        // termination_date

        // user_level

        // technical_skill

        // about_me
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

            // religion
            if (strval($this->religion->CurrentValue) != "") {
                $this->religion->ViewValue = $this->religion->optionCaption($this->religion->CurrentValue);
            } else {
                $this->religion->ViewValue = null;
            }
            $this->religion->ViewCustomAttributes = "";

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

            // user_level
            if ($Security->canAdmin()) { // System admin
                $curVal = strval($this->user_level->CurrentValue);
                if ($curVal != "") {
                    $this->user_level->ViewValue = $this->user_level->lookupCacheOption($curVal);
                    if ($this->user_level->ViewValue === null) { // Lookup from database
                        $filterWrk = "`userlevelid`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->user_level->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->user_level->Lookup->renderViewRow($rswrk[0]);
                            $this->user_level->ViewValue = $this->user_level->displayValue($arwrk);
                        } else {
                            $this->user_level->ViewValue = $this->user_level->CurrentValue;
                        }
                    }
                } else {
                    $this->user_level->ViewValue = null;
                }
            } else {
                $this->user_level->ViewValue = $Language->phrase("PasswordMask");
            }
            $this->user_level->ViewCustomAttributes = "";

            // technical_skill
            $this->technical_skill->ViewValue = $this->technical_skill->CurrentValue;
            $this->technical_skill->ViewCustomAttributes = "";

            // about_me
            $this->about_me->ViewValue = $this->about_me->CurrentValue;
            $this->about_me->ViewCustomAttributes = "";

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

            // religion
            $this->religion->LinkCustomAttributes = "";
            $this->religion->HrefValue = "";
            $this->religion->TooltipValue = "";

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

            // position_id
            $this->position_id->LinkCustomAttributes = "";
            $this->position_id->HrefValue = "";
            $this->position_id->TooltipValue = "";

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

            // user_level
            $this->user_level->LinkCustomAttributes = "";
            $this->user_level->HrefValue = "";
            $this->user_level->TooltipValue = "";

            // technical_skill
            $this->technical_skill->LinkCustomAttributes = "";
            $this->technical_skill->HrefValue = "";
            $this->technical_skill->TooltipValue = "";

            // about_me
            $this->about_me->LinkCustomAttributes = "";
            $this->about_me->HrefValue = "";
            $this->about_me->TooltipValue = "";
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        $pageUrl = $this->pageUrl();
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" onclick=\"return ew.export(document.femployeeview, '" . $this->ExportExcelUrl . "', 'excel', true);\">" . $Language->phrase("ExportToExcel") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" onclick=\"return ew.export(document.femployeeview, '" . $this->ExportWordUrl . "', 'word', true);\">" . $Language->phrase("ExportToWord") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportWordUrl . "\" class=\"ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<a href=\"#\" class=\"ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" onclick=\"return ew.export(document.femployeeview, '" . $this->ExportPdfUrl . "', 'pdf', true);\">" . $Language->phrase("ExportToPDF") . "</a>";
            } else {
                return "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPDFText")) . "\">" . $Language->phrase("ExportToPDF") . "</a>";
            }
        } elseif (SameText($type, "html")) {
            return "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ew-export-link ew-html\" title=\"" . HtmlEncode($Language->phrase("ExportToHtmlText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToHtmlText")) . "\">" . $Language->phrase("ExportToHtml") . "</a>";
        } elseif (SameText($type, "xml")) {
            return "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ew-export-link ew-xml\" title=\"" . HtmlEncode($Language->phrase("ExportToXmlText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToXmlText")) . "\">" . $Language->phrase("ExportToXml") . "</a>";
        } elseif (SameText($type, "csv")) {
            return "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ew-export-link ew-csv\" title=\"" . HtmlEncode($Language->phrase("ExportToCsvText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToCsvText")) . "\">" . $Language->phrase("ExportToCsv") . "</a>";
        } elseif (SameText($type, "email")) {
            $url = $custom ? ",url:'" . $pageUrl . "export=email&amp;custom=1'" : "";
            return '<button id="emf_employee" class="ew-export-link ew-email" title="' . $Language->phrase("ExportToEmailText") . '" data-caption="' . $Language->phrase("ExportToEmailText") . '" onclick="ew.emailDialogShow({lnk:\'emf_employee\', hdr:ew.language.phrase(\'ExportToEmailText\'), f:document.femployeeview, key:' . ArrayToJsonAttribute($this->RecKey) . ', sel:false' . $url . '});">' . $Language->phrase("ExportToEmail") . '</button>';
        } elseif (SameText($type, "print")) {
            return "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ew-export-link ew-print\" title=\"" . HtmlEncode($Language->phrase("PrinterFriendlyText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("PrinterFriendlyText")) . "\">" . $Language->phrase("PrinterFriendly") . "</a>";
        }
    }

    // Set up export options
    protected function setupExportOptions()
    {
        global $Language;

        // Printer friendly
        $item = &$this->ExportOptions->add("print");
        $item->Body = $this->getExportTag("print");
        $item->Visible = true;

        // Export to Excel
        $item = &$this->ExportOptions->add("excel");
        $item->Body = $this->getExportTag("excel");
        $item->Visible = true;

        // Export to Word
        $item = &$this->ExportOptions->add("word");
        $item->Body = $this->getExportTag("word");
        $item->Visible = true;

        // Export to Html
        $item = &$this->ExportOptions->add("html");
        $item->Body = $this->getExportTag("html");
        $item->Visible = true;

        // Export to Xml
        $item = &$this->ExportOptions->add("xml");
        $item->Body = $this->getExportTag("xml");
        $item->Visible = true;

        // Export to Csv
        $item = &$this->ExportOptions->add("csv");
        $item->Body = $this->getExportTag("csv");
        $item->Visible = true;

        // Export to Pdf
        $item = &$this->ExportOptions->add("pdf");
        $item->Body = $this->getExportTag("pdf");
        $item->Visible = true;

        // Export to Email
        $item = &$this->ExportOptions->add("email");
        $item->Body = $this->getExportTag("email");
        $item->Visible = true;

        // Drop down button for export
        $this->ExportOptions->UseButtonGroup = true;
        $this->ExportOptions->UseDropDownButton = true;
        if ($this->ExportOptions->UseButtonGroup && IsMobile()) {
            $this->ExportOptions->UseDropDownButton = true;
        }
        $this->ExportOptions->DropDownButtonPhrase = $Language->phrase("ButtonExport");

        // Add group option item
        $item = &$this->ExportOptions->add($this->ExportOptions->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;

        // Hide options for export
        if ($this->isExport()) {
            $this->ExportOptions->hideAllOptions();
        }
    }

    /**
    * Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
    *
    * @param boolean $return Return the data rather than output it
    * @return mixed
    */
    public function exportData($return = false)
    {
        global $Language;
        $utf8 = SameText(Config("PROJECT_CHARSET"), "utf-8");

        // Load recordset
        if (!$this->Recordset) {
            $this->Recordset = $this->loadRecordset();
        }
        $rs = &$this->Recordset;
        if ($rs) {
            $this->TotalRecords = $rs->recordCount();
        }
        $this->StartRecord = 1;
        $this->setupStartRecord(); // Set up start record position

        // Set the last record to display
        if ($this->DisplayRecords <= 0) {
            $this->StopRecord = $this->TotalRecords;
        } else {
            $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
        }
        $this->ExportDoc = GetExportDocument($this, "v");
        $doc = &$this->ExportDoc;
        if (!$doc) {
            $this->setFailureMessage($Language->phrase("ExportClassNotFound")); // Export class not found
        }
        if (!$rs || !$doc) {
            RemoveHeader("Content-Type"); // Remove header
            RemoveHeader("Content-Disposition");
            $this->showMessage();
            return;
        }
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords;

        // Call Page Exporting server event
        $this->ExportDoc->ExportCustom = !$this->pageExporting();
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        $doc->Text .= $header;
        $this->exportDocument($doc, $rs, $this->StartRecord, $this->StopRecord, "view");

        // Export detail records (employee_shift)
        if (Config("EXPORT_DETAIL_RECORDS") && in_array("employee_shift", explode(",", $this->getCurrentDetailTable()))) {
            $employee_shift = Container("employee_shift");
            $rsdetail = $employee_shift->loadRs($employee_shift->getDetailFilter()); // Load detail records
            if ($rsdetail) {
                $exportStyle = $doc->Style;
                $doc->setStyle("h"); // Change to horizontal
                if (!$this->isExport("csv") || Config("EXPORT_DETAIL_RECORDS_FOR_CSV")) {
                    $doc->exportEmptyRow();
                    $detailcnt = $rsdetail->rowCount();
                    $oldtbl = $doc->Table;
                    $doc->Table = $employee_shift;
                    $employee_shift->exportDocument($doc, new Recordset($rsdetail), 1, $detailcnt);
                    $doc->Table = $oldtbl;
                }
                $doc->setStyle($exportStyle); // Restore
                $rsdetail->closeCursor();
            }
        }

        // Export detail records (activity)
        if (Config("EXPORT_DETAIL_RECORDS") && in_array("activity", explode(",", $this->getCurrentDetailTable()))) {
            $activity = Container("activity");
            $rsdetail = $activity->loadRs($activity->getDetailFilter()); // Load detail records
            if ($rsdetail) {
                $exportStyle = $doc->Style;
                $doc->setStyle("h"); // Change to horizontal
                if (!$this->isExport("csv") || Config("EXPORT_DETAIL_RECORDS_FOR_CSV")) {
                    $doc->exportEmptyRow();
                    $detailcnt = $rsdetail->rowCount();
                    $oldtbl = $doc->Table;
                    $doc->Table = $activity;
                    $activity->exportDocument($doc, new Recordset($rsdetail), 1, $detailcnt);
                    $doc->Table = $oldtbl;
                }
                $doc->setStyle($exportStyle); // Restore
                $rsdetail->closeCursor();
            }
        }

        // Export detail records (permit)
        if (Config("EXPORT_DETAIL_RECORDS") && in_array("permit", explode(",", $this->getCurrentDetailTable()))) {
            $permit = Container("permit");
            $rsdetail = $permit->loadRs($permit->getDetailFilter()); // Load detail records
            if ($rsdetail) {
                $exportStyle = $doc->Style;
                $doc->setStyle("h"); // Change to horizontal
                if (!$this->isExport("csv") || Config("EXPORT_DETAIL_RECORDS_FOR_CSV")) {
                    $doc->exportEmptyRow();
                    $detailcnt = $rsdetail->rowCount();
                    $oldtbl = $doc->Table;
                    $doc->Table = $permit;
                    $permit->exportDocument($doc, new Recordset($rsdetail), 1, $detailcnt);
                    $doc->Table = $oldtbl;
                }
                $doc->setStyle($exportStyle); // Restore
                $rsdetail->closeCursor();
            }
        }

        // Export detail records (employee_contract)
        if (Config("EXPORT_DETAIL_RECORDS") && in_array("employee_contract", explode(",", $this->getCurrentDetailTable()))) {
            $employee_contract = Container("employee_contract");
            $rsdetail = $employee_contract->loadRs($employee_contract->getDetailFilter()); // Load detail records
            if ($rsdetail) {
                $exportStyle = $doc->Style;
                $doc->setStyle("h"); // Change to horizontal
                if (!$this->isExport("csv") || Config("EXPORT_DETAIL_RECORDS_FOR_CSV")) {
                    $doc->exportEmptyRow();
                    $detailcnt = $rsdetail->rowCount();
                    $oldtbl = $doc->Table;
                    $doc->Table = $employee_contract;
                    $employee_contract->exportDocument($doc, new Recordset($rsdetail), 1, $detailcnt);
                    $doc->Table = $oldtbl;
                }
                $doc->setStyle($exportStyle); // Restore
                $rsdetail->closeCursor();
            }
        }

        // Export detail records (employee_asset)
        if (Config("EXPORT_DETAIL_RECORDS") && in_array("employee_asset", explode(",", $this->getCurrentDetailTable()))) {
            $employee_asset = Container("employee_asset");
            $rsdetail = $employee_asset->loadRs($employee_asset->getDetailFilter()); // Load detail records
            if ($rsdetail) {
                $exportStyle = $doc->Style;
                $doc->setStyle("h"); // Change to horizontal
                if (!$this->isExport("csv") || Config("EXPORT_DETAIL_RECORDS_FOR_CSV")) {
                    $doc->exportEmptyRow();
                    $detailcnt = $rsdetail->rowCount();
                    $oldtbl = $doc->Table;
                    $doc->Table = $employee_asset;
                    $employee_asset->exportDocument($doc, new Recordset($rsdetail), 1, $detailcnt);
                    $doc->Table = $oldtbl;
                }
                $doc->setStyle($exportStyle); // Restore
                $rsdetail->closeCursor();
            }
        }

        // Export detail records (employee_timesheet)
        if (Config("EXPORT_DETAIL_RECORDS") && in_array("employee_timesheet", explode(",", $this->getCurrentDetailTable()))) {
            $employee_timesheet = Container("employee_timesheet");
            $rsdetail = $employee_timesheet->loadRs($employee_timesheet->getDetailFilter()); // Load detail records
            if ($rsdetail) {
                $exportStyle = $doc->Style;
                $doc->setStyle("h"); // Change to horizontal
                if (!$this->isExport("csv") || Config("EXPORT_DETAIL_RECORDS_FOR_CSV")) {
                    $doc->exportEmptyRow();
                    $detailcnt = $rsdetail->rowCount();
                    $oldtbl = $doc->Table;
                    $doc->Table = $employee_timesheet;
                    $employee_timesheet->exportDocument($doc, new Recordset($rsdetail), 1, $detailcnt);
                    $doc->Table = $oldtbl;
                }
                $doc->setStyle($exportStyle); // Restore
                $rsdetail->closeCursor();
            }
        }

        // Export detail records (employee_trainings)
        if (Config("EXPORT_DETAIL_RECORDS") && in_array("employee_trainings", explode(",", $this->getCurrentDetailTable()))) {
            $employee_trainings = Container("employee_trainings");
            $rsdetail = $employee_trainings->loadRs($employee_trainings->getDetailFilter()); // Load detail records
            if ($rsdetail) {
                $exportStyle = $doc->Style;
                $doc->setStyle("h"); // Change to horizontal
                if (!$this->isExport("csv") || Config("EXPORT_DETAIL_RECORDS_FOR_CSV")) {
                    $doc->exportEmptyRow();
                    $detailcnt = $rsdetail->rowCount();
                    $oldtbl = $doc->Table;
                    $doc->Table = $employee_trainings;
                    $employee_trainings->exportDocument($doc, new Recordset($rsdetail), 1, $detailcnt);
                    $doc->Table = $oldtbl;
                }
                $doc->setStyle($exportStyle); // Restore
                $rsdetail->closeCursor();
            }
        }
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        $doc->Text .= $footer;

        // Close recordset
        $rs->close();

        // Call Page Exported server event
        $this->pageExported();

        // Export header and footer
        $doc->exportHeaderAndFooter();

        // Clean output buffer (without destroying output buffer)
        $buffer = ob_get_contents(); // Save the output buffer
        if (!Config("DEBUG") && $buffer) {
            ob_clean();
        }

        // Write debug message if enabled
        if (Config("DEBUG") && !$this->isExport("pdf")) {
            echo GetDebugMessage();
        }

        // Output data
        if ($this->isExport("email")) {
            if ($return) {
                return $doc->Text; // Return email content
            } else {
                echo $this->exportEmail($doc->Text); // Send email
            }
        } else {
            $doc->export();
            if ($return) {
                RemoveHeader("Content-Type"); // Remove header
                RemoveHeader("Content-Disposition");
                $content = ob_get_contents();
                if ($content) {
                    ob_clean();
                }
                if ($buffer) {
                    echo $buffer; // Resume the output buffer
                }
                return $content;
            }
        }
    }

    // Export email
    protected function exportEmail($emailContent)
    {
        global $TempImages, $Language;
        $sender = Post("sender", "");
        $recipient = Post("recipient", "");
        $cc = Post("cc", "");
        $bcc = Post("bcc", "");

        // Subject
        $subject = Post("subject", "");
        $emailSubject = $subject;

        // Message
        $content = Post("message", "");
        $emailMessage = $content;

        // Check sender
        if ($sender == "") {
            return "<p class=\"text-danger\">" . str_replace("%s", $Language->phrase("Sender"), $Language->phrase("EnterRequiredField")) . "</p>";
        }
        if (!CheckEmail($sender)) {
            return "<p class=\"text-danger\">" . $Language->phrase("EnterProperSenderEmail") . "</p>";
        }

        // Check recipient
        if ($recipient == "") {
            return "<p class=\"text-danger\">" . str_replace("%s", $Language->phrase("Recipient"), $Language->phrase("EnterRequiredField")) . "</p>";
        }
        if (!CheckEmailList($recipient, Config("MAX_EMAIL_RECIPIENT"))) {
            return "<p class=\"text-danger\">" . $Language->phrase("EnterProperRecipientEmail") . "</p>";
        }

        // Check cc
        if (!CheckEmailList($cc, Config("MAX_EMAIL_RECIPIENT"))) {
            return "<p class=\"text-danger\">" . $Language->phrase("EnterProperCcEmail") . "</p>";
        }

        // Check bcc
        if (!CheckEmailList($bcc, Config("MAX_EMAIL_RECIPIENT"))) {
            return "<p class=\"text-danger\">" . $Language->phrase("EnterProperBccEmail") . "</p>";
        }

        // Check email sent count
        $_SESSION[Config("EXPORT_EMAIL_COUNTER")] = Session(Config("EXPORT_EMAIL_COUNTER")) ?? 0;
        if ((int)Session(Config("EXPORT_EMAIL_COUNTER")) > Config("MAX_EMAIL_SENT_COUNT")) {
            return "<p class=\"text-danger\">" . $Language->phrase("ExceedMaxEmailExport") . "</p>";
        }

        // Send email
        $email = new Email();
        $email->Sender = $sender; // Sender
        $email->Recipient = $recipient; // Recipient
        $email->Cc = $cc; // Cc
        $email->Bcc = $bcc; // Bcc
        $email->Subject = $emailSubject; // Subject
        $email->Format = "html";
        if ($emailMessage != "") {
            $emailMessage = RemoveXss($emailMessage) . "<br><br>";
        }
        foreach ($TempImages as $tmpImage) {
            $email->addEmbeddedImage($tmpImage);
        }
        $email->Content = $emailMessage . CleanEmailContent($emailContent); // Content
        $eventArgs = [];
        if ($this->Recordset) {
            $eventArgs["rs"] = &$this->Recordset;
        }
        $emailSent = false;
        if ($this->emailSending($email, $eventArgs)) {
            $emailSent = $email->send();
        }

        // Check email sent status
        if ($emailSent) {
            // Update email sent count
            $_SESSION[Config("EXPORT_EMAIL_COUNTER")]++;

            // Sent email success
            return "<p class=\"text-success\">" . $Language->phrase("SendEmailSuccess") . "</p>"; // Set up success message
        } else {
            // Sent email failure
            return "<p class=\"text-danger\">" . $email->SendErrDescription . "</p>";
        }
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
            if ($masterTblVar == "master_office") {
                $validMaster = true;
                $masterTbl = Container("master_office");
                if (($parm = Get("fk_office_id", Get("office_id"))) !== null) {
                    $masterTbl->office_id->setQueryStringValue($parm);
                    $this->office_id->setQueryStringValue($masterTbl->office_id->QueryStringValue);
                    $this->office_id->setSessionValue($this->office_id->QueryStringValue);
                    if (!is_numeric($masterTbl->office_id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "master_position") {
                $validMaster = true;
                $masterTbl = Container("master_position");
                if (($parm = Get("fk_position_id", Get("position_id"))) !== null) {
                    $masterTbl->position_id->setQueryStringValue($parm);
                    $this->position_id->setQueryStringValue($masterTbl->position_id->QueryStringValue);
                    $this->position_id->setSessionValue($this->position_id->QueryStringValue);
                    if (!is_numeric($masterTbl->position_id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "master_skill") {
                $validMaster = true;
                $masterTbl = Container("master_skill");
                if (($parm = Get("fk_skill_id", Get("skill_id"))) !== null) {
                    $masterTbl->skill_id->setQueryStringValue($parm);
                    $this->skill_id->setQueryStringValue($masterTbl->skill_id->QueryStringValue);
                    $this->skill_id->setSessionValue($this->skill_id->QueryStringValue);
                    if (!is_numeric($masterTbl->skill_id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "master_status") {
                $validMaster = true;
                $masterTbl = Container("master_status");
                if (($parm = Get("fk_status_id", Get("status_id"))) !== null) {
                    $masterTbl->status_id->setQueryStringValue($parm);
                    $this->status_id->setQueryStringValue($masterTbl->status_id->QueryStringValue);
                    $this->status_id->setSessionValue($this->status_id->QueryStringValue);
                    if (!is_numeric($masterTbl->status_id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "master_city") {
                $validMaster = true;
                $masterTbl = Container("master_city");
                if (($parm = Get("fk_city_id", Get("city_id"))) !== null) {
                    $masterTbl->city_id->setQueryStringValue($parm);
                    $this->city_id->setQueryStringValue($masterTbl->city_id->QueryStringValue);
                    $this->city_id->setSessionValue($this->city_id->QueryStringValue);
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
            if ($masterTblVar == "master_office") {
                $validMaster = true;
                $masterTbl = Container("master_office");
                if (($parm = Post("fk_office_id", Post("office_id"))) !== null) {
                    $masterTbl->office_id->setFormValue($parm);
                    $this->office_id->setFormValue($masterTbl->office_id->FormValue);
                    $this->office_id->setSessionValue($this->office_id->FormValue);
                    if (!is_numeric($masterTbl->office_id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "master_position") {
                $validMaster = true;
                $masterTbl = Container("master_position");
                if (($parm = Post("fk_position_id", Post("position_id"))) !== null) {
                    $masterTbl->position_id->setFormValue($parm);
                    $this->position_id->setFormValue($masterTbl->position_id->FormValue);
                    $this->position_id->setSessionValue($this->position_id->FormValue);
                    if (!is_numeric($masterTbl->position_id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "master_skill") {
                $validMaster = true;
                $masterTbl = Container("master_skill");
                if (($parm = Post("fk_skill_id", Post("skill_id"))) !== null) {
                    $masterTbl->skill_id->setFormValue($parm);
                    $this->skill_id->setFormValue($masterTbl->skill_id->FormValue);
                    $this->skill_id->setSessionValue($this->skill_id->FormValue);
                    if (!is_numeric($masterTbl->skill_id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "master_status") {
                $validMaster = true;
                $masterTbl = Container("master_status");
                if (($parm = Post("fk_status_id", Post("status_id"))) !== null) {
                    $masterTbl->status_id->setFormValue($parm);
                    $this->status_id->setFormValue($masterTbl->status_id->FormValue);
                    $this->status_id->setSessionValue($this->status_id->FormValue);
                    if (!is_numeric($masterTbl->status_id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "master_city") {
                $validMaster = true;
                $masterTbl = Container("master_city");
                if (($parm = Post("fk_city_id", Post("city_id"))) !== null) {
                    $masterTbl->city_id->setFormValue($parm);
                    $this->city_id->setFormValue($masterTbl->city_id->FormValue);
                    $this->city_id->setSessionValue($this->city_id->FormValue);
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
            if ($masterTblVar != "master_office") {
                if ($this->office_id->CurrentValue == "") {
                    $this->office_id->setSessionValue("");
                }
            }
            if ($masterTblVar != "master_position") {
                if ($this->position_id->CurrentValue == "") {
                    $this->position_id->setSessionValue("");
                }
            }
            if ($masterTblVar != "master_skill") {
                if ($this->skill_id->CurrentValue == "") {
                    $this->skill_id->setSessionValue("");
                }
            }
            if ($masterTblVar != "master_status") {
                if ($this->status_id->CurrentValue == "") {
                    $this->status_id->setSessionValue("");
                }
            }
            if ($masterTblVar != "master_city") {
                if ($this->city_id->CurrentValue == "") {
                    $this->city_id->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilter(); // Get master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Get detail filter
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
            if (in_array("employee_shift", $detailTblVar)) {
                $detailPageObj = Container("EmployeeShiftGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->employee_username->IsDetailKey = true;
                    $detailPageObj->employee_username->CurrentValue = $this->employee_username->CurrentValue;
                    $detailPageObj->employee_username->setSessionValue($detailPageObj->employee_username->CurrentValue);
                    $detailPageObj->shift_id->setSessionValue(""); // Clear session key
                }
            }
            if (in_array("activity", $detailTblVar)) {
                $detailPageObj = Container("ActivityGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->employee_username->IsDetailKey = true;
                    $detailPageObj->employee_username->CurrentValue = $this->employee_username->CurrentValue;
                    $detailPageObj->employee_username->setSessionValue($detailPageObj->employee_username->CurrentValue);
                }
            }
            if (in_array("permit", $detailTblVar)) {
                $detailPageObj = Container("PermitGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->employee_username->IsDetailKey = true;
                    $detailPageObj->employee_username->CurrentValue = $this->employee_username->CurrentValue;
                    $detailPageObj->employee_username->setSessionValue($detailPageObj->employee_username->CurrentValue);
                }
            }
            if (in_array("employee_contract", $detailTblVar)) {
                $detailPageObj = Container("EmployeeContractGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->employee_username->IsDetailKey = true;
                    $detailPageObj->employee_username->CurrentValue = $this->employee_username->CurrentValue;
                    $detailPageObj->employee_username->setSessionValue($detailPageObj->employee_username->CurrentValue);
                }
            }
            if (in_array("employee_asset", $detailTblVar)) {
                $detailPageObj = Container("EmployeeAssetGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->employee_username->IsDetailKey = true;
                    $detailPageObj->employee_username->CurrentValue = $this->employee_username->CurrentValue;
                    $detailPageObj->employee_username->setSessionValue($detailPageObj->employee_username->CurrentValue);
                }
            }
            if (in_array("employee_timesheet", $detailTblVar)) {
                $detailPageObj = Container("EmployeeTimesheetGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->employee_username->IsDetailKey = true;
                    $detailPageObj->employee_username->CurrentValue = $this->employee_username->CurrentValue;
                    $detailPageObj->employee_username->setSessionValue($detailPageObj->employee_username->CurrentValue);
                }
            }
            if (in_array("employee_trainings", $detailTblVar)) {
                $detailPageObj = Container("EmployeeTrainingsGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->employee_username->IsDetailKey = true;
                    $detailPageObj->employee_username->CurrentValue = $this->employee_username->CurrentValue;
                    $detailPageObj->employee_username->setSessionValue($detailPageObj->employee_username->CurrentValue);
                }
            }
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("welcome");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("employeelist"), "", $this->TableVar, true);
        $pageId = "view";
        $Breadcrumb->add("view", $pageId, $url);
    }

    // Set up multi pages
    protected function setupMultiPages()
    {
        $pages = new SubPages();
        $pages->Style = "tabs";
        $pages->add(0);
        $pages->add(1);
        $pages->add(2);
        $pages->add(3);
        $pages->add(4);
        $this->MultiPages = $pages;
    }

    // Set up detail pages
    protected function setupDetailPages()
    {
        $pages = new SubPages();
        $pages->Style = "tabs";
        $pages->add('employee_shift');
        $pages->add('activity');
        $pages->add('permit');
        $pages->add('employee_contract');
        $pages->add('employee_asset');
        $pages->add('employee_timesheet');
        $pages->add('employee_trainings');
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
                case "x_religion":
                    break;
                case "x_city_id":
                    break;
                case "x_position_id":
                    break;
                case "x_status_id":
                    break;
                case "x_skill_id":
                    break;
                case "x_office_id":
                    break;
                case "x_user_level":
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

    // Page Exporting event
    // $this->ExportDoc = export document object
    public function pageExporting()
    {
        //$this->ExportDoc->Text = "my header"; // Export header
        //return false; // Return false to skip default export and use Row_Export event
        return true; // Return true to use default export and skip Row_Export event
    }

    // Row Export event
    // $this->ExportDoc = export document object
    public function rowExport($rs)
    {
        //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
    }

    // Page Exported event
    // $this->ExportDoc = export document object
    public function pageExported()
    {
        //$this->ExportDoc->Text .= "my footer"; // Export footer
        //Log($this->ExportDoc->Text);
    }
}
