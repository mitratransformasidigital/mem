<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class CustomerUpdate extends Customer
{
    use MessagesTrait;

    // Page ID
    public $PageID = "update";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'customer';

    // Page object name
    public $PageObjName = "CustomerUpdate";

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

        // Table object (customer)
        if (!isset($GLOBALS["customer"]) || get_class($GLOBALS["customer"]) == PROJECT_NAMESPACE . "customer") {
            $GLOBALS["customer"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'customer');
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
                $doc = new $class(Container("customer"));
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
                    if ($pageName == "customerview") {
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
            $key .= @$ar['customer_id'];
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
            $this->customer_id->Visible = false;
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
        $this->customer_id->Visible = false;
        $this->customer_name->setVisibility();
        $this->address->setVisibility();
        $this->city_id->setVisibility();
        $this->phone_number->setVisibility();
        $this->contact->setVisibility();
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
        $this->setupLookupOptions($this->city_id);

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
            $this->terminate("customerlist"); // No records selected, return to list
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
                    $this->customer_name->setDbValue($rs->fields['customer_name']);
                    $this->address->setDbValue($rs->fields['address']);
                    $this->city_id->setDbValue($rs->fields['city_id']);
                    $this->phone_number->setDbValue($rs->fields['phone_number']);
                    $this->contact->setDbValue($rs->fields['contact']);
                } else {
                    if (!CompareValue($this->customer_name->DbValue, $rs->fields['customer_name'])) {
                        $this->customer_name->CurrentValue = null;
                    }
                    if (!CompareValue($this->address->DbValue, $rs->fields['address'])) {
                        $this->address->CurrentValue = null;
                    }
                    if (!CompareValue($this->city_id->DbValue, $rs->fields['city_id'])) {
                        $this->city_id->CurrentValue = null;
                    }
                    if (!CompareValue($this->phone_number->DbValue, $rs->fields['phone_number'])) {
                        $this->phone_number->CurrentValue = null;
                    }
                    if (!CompareValue($this->contact->DbValue, $rs->fields['contact'])) {
                        $this->contact->CurrentValue = null;
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
        $this->customer_id->OldValue = $keyFld;
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

        // Check field name 'customer_name' first before field var 'x_customer_name'
        $val = $CurrentForm->hasValue("customer_name") ? $CurrentForm->getValue("customer_name") : $CurrentForm->getValue("x_customer_name");
        if (!$this->customer_name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->customer_name->Visible = false; // Disable update for API request
            } else {
                $this->customer_name->setFormValue($val);
            }
        }
        $this->customer_name->MultiUpdate = $CurrentForm->getValue("u_customer_name");

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

        // Check field name 'phone_number' first before field var 'x_phone_number'
        $val = $CurrentForm->hasValue("phone_number") ? $CurrentForm->getValue("phone_number") : $CurrentForm->getValue("x_phone_number");
        if (!$this->phone_number->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->phone_number->Visible = false; // Disable update for API request
            } else {
                $this->phone_number->setFormValue($val);
            }
        }
        $this->phone_number->MultiUpdate = $CurrentForm->getValue("u_phone_number");

        // Check field name 'contact' first before field var 'x_contact'
        $val = $CurrentForm->hasValue("contact") ? $CurrentForm->getValue("contact") : $CurrentForm->getValue("x_contact");
        if (!$this->contact->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->contact->Visible = false; // Disable update for API request
            } else {
                $this->contact->setFormValue($val);
            }
        }
        $this->contact->MultiUpdate = $CurrentForm->getValue("u_contact");

        // Check field name 'customer_id' first before field var 'x_customer_id'
        $val = $CurrentForm->hasValue("customer_id") ? $CurrentForm->getValue("customer_id") : $CurrentForm->getValue("x_customer_id");
        if (!$this->customer_id->IsDetailKey) {
            $this->customer_id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->customer_id->CurrentValue = $this->customer_id->FormValue;
        $this->customer_name->CurrentValue = $this->customer_name->FormValue;
        $this->address->CurrentValue = $this->address->FormValue;
        $this->city_id->CurrentValue = $this->city_id->FormValue;
        $this->phone_number->CurrentValue = $this->phone_number->FormValue;
        $this->contact->CurrentValue = $this->contact->FormValue;
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
        $this->customer_id->setDbValue($row['customer_id']);
        $this->customer_name->setDbValue($row['customer_name']);
        $this->address->setDbValue($row['address']);
        $this->city_id->setDbValue($row['city_id']);
        $this->phone_number->setDbValue($row['phone_number']);
        $this->contact->setDbValue($row['contact']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['customer_id'] = null;
        $row['customer_name'] = null;
        $row['address'] = null;
        $row['city_id'] = null;
        $row['phone_number'] = null;
        $row['contact'] = null;
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

        // customer_id

        // customer_name

        // address

        // city_id

        // phone_number

        // contact
        if ($this->RowType == ROWTYPE_VIEW) {
            // customer_name
            $this->customer_name->ViewValue = $this->customer_name->CurrentValue;
            $this->customer_name->ViewCustomAttributes = "";

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

            // phone_number
            $this->phone_number->ViewValue = $this->phone_number->CurrentValue;
            $this->phone_number->ViewCustomAttributes = "";

            // contact
            $this->contact->ViewValue = $this->contact->CurrentValue;
            $this->contact->ViewCustomAttributes = "";

            // customer_name
            $this->customer_name->LinkCustomAttributes = "";
            $this->customer_name->HrefValue = "";
            $this->customer_name->TooltipValue = "";

            // address
            $this->address->LinkCustomAttributes = "";
            $this->address->HrefValue = "";
            $this->address->TooltipValue = "";

            // city_id
            $this->city_id->LinkCustomAttributes = "";
            $this->city_id->HrefValue = "";
            $this->city_id->TooltipValue = "";

            // phone_number
            $this->phone_number->LinkCustomAttributes = "";
            $this->phone_number->HrefValue = "";
            $this->phone_number->TooltipValue = "";

            // contact
            $this->contact->LinkCustomAttributes = "";
            $this->contact->HrefValue = "";
            $this->contact->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // customer_name
            $this->customer_name->EditAttrs["class"] = "form-control";
            $this->customer_name->EditCustomAttributes = "";
            if (!$this->customer_name->Raw) {
                $this->customer_name->CurrentValue = HtmlDecode($this->customer_name->CurrentValue);
            }
            $this->customer_name->EditValue = HtmlEncode($this->customer_name->CurrentValue);
            $this->customer_name->PlaceHolder = RemoveHtml($this->customer_name->caption());

            // address
            $this->address->EditAttrs["class"] = "form-control";
            $this->address->EditCustomAttributes = "";
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

            // phone_number
            $this->phone_number->EditAttrs["class"] = "form-control";
            $this->phone_number->EditCustomAttributes = "";
            if (!$this->phone_number->Raw) {
                $this->phone_number->CurrentValue = HtmlDecode($this->phone_number->CurrentValue);
            }
            $this->phone_number->EditValue = HtmlEncode($this->phone_number->CurrentValue);
            $this->phone_number->PlaceHolder = RemoveHtml($this->phone_number->caption());

            // contact
            $this->contact->EditAttrs["class"] = "form-control";
            $this->contact->EditCustomAttributes = "";
            if (!$this->contact->Raw) {
                $this->contact->CurrentValue = HtmlDecode($this->contact->CurrentValue);
            }
            $this->contact->EditValue = HtmlEncode($this->contact->CurrentValue);
            $this->contact->PlaceHolder = RemoveHtml($this->contact->caption());

            // Edit refer script

            // customer_name
            $this->customer_name->LinkCustomAttributes = "";
            $this->customer_name->HrefValue = "";

            // address
            $this->address->LinkCustomAttributes = "";
            $this->address->HrefValue = "";

            // city_id
            $this->city_id->LinkCustomAttributes = "";
            $this->city_id->HrefValue = "";

            // phone_number
            $this->phone_number->LinkCustomAttributes = "";
            $this->phone_number->HrefValue = "";

            // contact
            $this->contact->LinkCustomAttributes = "";
            $this->contact->HrefValue = "";
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
        if ($this->customer_name->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->address->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->city_id->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->phone_number->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->contact->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($updateCnt == 0) {
            return false;
        }

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->customer_name->Required) {
            if ($this->customer_name->MultiUpdate != "" && !$this->customer_name->IsDetailKey && EmptyValue($this->customer_name->FormValue)) {
                $this->customer_name->addErrorMessage(str_replace("%s", $this->customer_name->caption(), $this->customer_name->RequiredErrorMessage));
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
        if ($this->phone_number->Required) {
            if ($this->phone_number->MultiUpdate != "" && !$this->phone_number->IsDetailKey && EmptyValue($this->phone_number->FormValue)) {
                $this->phone_number->addErrorMessage(str_replace("%s", $this->phone_number->caption(), $this->phone_number->RequiredErrorMessage));
            }
        }
        if ($this->contact->Required) {
            if ($this->contact->MultiUpdate != "" && !$this->contact->IsDetailKey && EmptyValue($this->contact->FormValue)) {
                $this->contact->addErrorMessage(str_replace("%s", $this->contact->caption(), $this->contact->RequiredErrorMessage));
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
        if ($this->customer_name->CurrentValue != "") { // Check field with unique index
            $filterChk = "(`customer_name` = '" . AdjustSql($this->customer_name->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->customer_name->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->customer_name->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                $rsChk->closeCursor();
                return false;
            }
        }
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

            // customer_name
            $this->customer_name->setDbValueDef($rsnew, $this->customer_name->CurrentValue, "", $this->customer_name->ReadOnly || $this->customer_name->MultiUpdate != "1");

            // address
            $this->address->setDbValueDef($rsnew, $this->address->CurrentValue, "", $this->address->ReadOnly || $this->address->MultiUpdate != "1");

            // city_id
            if ($this->city_id->getSessionValue() != "") {
                $this->city_id->ReadOnly = true;
            }
            $this->city_id->setDbValueDef($rsnew, $this->city_id->CurrentValue, 0, $this->city_id->ReadOnly || $this->city_id->MultiUpdate != "1");

            // phone_number
            $this->phone_number->setDbValueDef($rsnew, $this->phone_number->CurrentValue, "", $this->phone_number->ReadOnly || $this->phone_number->MultiUpdate != "1");

            // contact
            $this->contact->setDbValueDef($rsnew, $this->contact->CurrentValue, "", $this->contact->ReadOnly || $this->contact->MultiUpdate != "1");

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("customerlist"), "", $this->TableVar, true);
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
