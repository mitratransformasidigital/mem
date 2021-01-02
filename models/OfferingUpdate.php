<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class OfferingUpdate extends Offering
{
    use MessagesTrait;

    // Page ID
    public $PageID = "update";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'offering';

    // Page object name
    public $PageObjName = "OfferingUpdate";

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

        // Table object (offering)
        if (!isset($GLOBALS["offering"]) || get_class($GLOBALS["offering"]) == PROJECT_NAMESPACE . "offering") {
            $GLOBALS["offering"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'offering');
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
                $doc = new $class(Container("offering"));
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
                    if ($pageName == "offeringview") {
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
            $key .= @$ar['offering_id'];
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
            $this->offering_id->Visible = false;
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
        $this->offering_id->Visible = false;
        $this->offering_no->setVisibility();
        $this->customer_id->setVisibility();
        $this->offering_date->setVisibility();
        $this->offering_term->setVisibility();
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
        $this->setupLookupOptions($this->customer_id);

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
            $this->terminate("offeringlist"); // No records selected, return to list
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
                    $this->offering_no->setDbValue($rs->fields['offering_no']);
                    $this->customer_id->setDbValue($rs->fields['customer_id']);
                    $this->offering_date->setDbValue($rs->fields['offering_date']);
                    $this->offering_term->setDbValue($rs->fields['offering_term']);
                } else {
                    if (!CompareValue($this->offering_no->DbValue, $rs->fields['offering_no'])) {
                        $this->offering_no->CurrentValue = null;
                    }
                    if (!CompareValue($this->customer_id->DbValue, $rs->fields['customer_id'])) {
                        $this->customer_id->CurrentValue = null;
                    }
                    if (!CompareValue($this->offering_date->DbValue, $rs->fields['offering_date'])) {
                        $this->offering_date->CurrentValue = null;
                    }
                    if (!CompareValue($this->offering_term->DbValue, $rs->fields['offering_term'])) {
                        $this->offering_term->CurrentValue = null;
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
        $this->offering_id->OldValue = $keyFld;
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

        // Check field name 'offering_no' first before field var 'x_offering_no'
        $val = $CurrentForm->hasValue("offering_no") ? $CurrentForm->getValue("offering_no") : $CurrentForm->getValue("x_offering_no");
        if (!$this->offering_no->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->offering_no->Visible = false; // Disable update for API request
            } else {
                $this->offering_no->setFormValue($val);
            }
        }
        $this->offering_no->MultiUpdate = $CurrentForm->getValue("u_offering_no");

        // Check field name 'customer_id' first before field var 'x_customer_id'
        $val = $CurrentForm->hasValue("customer_id") ? $CurrentForm->getValue("customer_id") : $CurrentForm->getValue("x_customer_id");
        if (!$this->customer_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->customer_id->Visible = false; // Disable update for API request
            } else {
                $this->customer_id->setFormValue($val);
            }
        }
        $this->customer_id->MultiUpdate = $CurrentForm->getValue("u_customer_id");

        // Check field name 'offering_date' first before field var 'x_offering_date'
        $val = $CurrentForm->hasValue("offering_date") ? $CurrentForm->getValue("offering_date") : $CurrentForm->getValue("x_offering_date");
        if (!$this->offering_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->offering_date->Visible = false; // Disable update for API request
            } else {
                $this->offering_date->setFormValue($val);
            }
            $this->offering_date->CurrentValue = UnFormatDateTime($this->offering_date->CurrentValue, 0);
        }
        $this->offering_date->MultiUpdate = $CurrentForm->getValue("u_offering_date");

        // Check field name 'offering_term' first before field var 'x_offering_term'
        $val = $CurrentForm->hasValue("offering_term") ? $CurrentForm->getValue("offering_term") : $CurrentForm->getValue("x_offering_term");
        if (!$this->offering_term->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->offering_term->Visible = false; // Disable update for API request
            } else {
                $this->offering_term->setFormValue($val);
            }
        }
        $this->offering_term->MultiUpdate = $CurrentForm->getValue("u_offering_term");

        // Check field name 'offering_id' first before field var 'x_offering_id'
        $val = $CurrentForm->hasValue("offering_id") ? $CurrentForm->getValue("offering_id") : $CurrentForm->getValue("x_offering_id");
        if (!$this->offering_id->IsDetailKey) {
            $this->offering_id->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->offering_id->CurrentValue = $this->offering_id->FormValue;
        $this->offering_no->CurrentValue = $this->offering_no->FormValue;
        $this->customer_id->CurrentValue = $this->customer_id->FormValue;
        $this->offering_date->CurrentValue = $this->offering_date->FormValue;
        $this->offering_date->CurrentValue = UnFormatDateTime($this->offering_date->CurrentValue, 0);
        $this->offering_term->CurrentValue = $this->offering_term->FormValue;
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
        $this->offering_id->setDbValue($row['offering_id']);
        $this->offering_no->setDbValue($row['offering_no']);
        $this->customer_id->setDbValue($row['customer_id']);
        $this->offering_date->setDbValue($row['offering_date']);
        $this->offering_term->setDbValue($row['offering_term']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['offering_id'] = null;
        $row['offering_no'] = null;
        $row['customer_id'] = null;
        $row['offering_date'] = null;
        $row['offering_term'] = null;
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

        // offering_id

        // offering_no

        // customer_id

        // offering_date

        // offering_term
        if ($this->RowType == ROWTYPE_VIEW) {
            // offering_no
            $this->offering_no->ViewValue = $this->offering_no->CurrentValue;
            $this->offering_no->ViewCustomAttributes = "";

            // customer_id
            $curVal = strval($this->customer_id->CurrentValue);
            if ($curVal != "") {
                $this->customer_id->ViewValue = $this->customer_id->lookupCacheOption($curVal);
                if ($this->customer_id->ViewValue === null) { // Lookup from database
                    $filterWrk = "`customer_id`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->customer_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->customer_id->Lookup->renderViewRow($rswrk[0]);
                        $this->customer_id->ViewValue = $this->customer_id->displayValue($arwrk);
                    } else {
                        $this->customer_id->ViewValue = $this->customer_id->CurrentValue;
                    }
                }
            } else {
                $this->customer_id->ViewValue = null;
            }
            $this->customer_id->ViewCustomAttributes = "";

            // offering_date
            $this->offering_date->ViewValue = $this->offering_date->CurrentValue;
            $this->offering_date->ViewValue = FormatDateTime($this->offering_date->ViewValue, 0);
            $this->offering_date->ViewCustomAttributes = "";

            // offering_term
            $this->offering_term->ViewValue = $this->offering_term->CurrentValue;
            $this->offering_term->ViewCustomAttributes = "";

            // offering_no
            $this->offering_no->LinkCustomAttributes = "";
            $this->offering_no->HrefValue = "";
            $this->offering_no->TooltipValue = "";

            // customer_id
            $this->customer_id->LinkCustomAttributes = "";
            $this->customer_id->HrefValue = "";
            $this->customer_id->TooltipValue = "";

            // offering_date
            $this->offering_date->LinkCustomAttributes = "";
            $this->offering_date->HrefValue = "";
            $this->offering_date->TooltipValue = "";

            // offering_term
            $this->offering_term->LinkCustomAttributes = "";
            $this->offering_term->HrefValue = "";
            $this->offering_term->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // offering_no
            $this->offering_no->EditAttrs["class"] = "form-control";
            $this->offering_no->EditCustomAttributes = "";
            if (!$this->offering_no->Raw) {
                $this->offering_no->CurrentValue = HtmlDecode($this->offering_no->CurrentValue);
            }
            $this->offering_no->EditValue = HtmlEncode($this->offering_no->CurrentValue);
            $this->offering_no->PlaceHolder = RemoveHtml($this->offering_no->caption());

            // customer_id
            $this->customer_id->EditAttrs["class"] = "form-control";
            $this->customer_id->EditCustomAttributes = "";
            $curVal = trim(strval($this->customer_id->CurrentValue));
            if ($curVal != "") {
                $this->customer_id->ViewValue = $this->customer_id->lookupCacheOption($curVal);
            } else {
                $this->customer_id->ViewValue = $this->customer_id->Lookup !== null && is_array($this->customer_id->Lookup->Options) ? $curVal : null;
            }
            if ($this->customer_id->ViewValue !== null) { // Load from cache
                $this->customer_id->EditValue = array_values($this->customer_id->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`customer_id`" . SearchString("=", $this->customer_id->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->customer_id->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->customer_id->EditValue = $arwrk;
            }
            $this->customer_id->PlaceHolder = RemoveHtml($this->customer_id->caption());

            // offering_date
            $this->offering_date->EditAttrs["class"] = "form-control";
            $this->offering_date->EditCustomAttributes = "";
            $this->offering_date->EditValue = HtmlEncode(FormatDateTime($this->offering_date->CurrentValue, 8));
            $this->offering_date->PlaceHolder = RemoveHtml($this->offering_date->caption());

            // offering_term
            $this->offering_term->EditAttrs["class"] = "form-control";
            $this->offering_term->EditCustomAttributes = "";
            $this->offering_term->EditValue = HtmlEncode($this->offering_term->CurrentValue);
            $this->offering_term->PlaceHolder = RemoveHtml($this->offering_term->caption());

            // Edit refer script

            // offering_no
            $this->offering_no->LinkCustomAttributes = "";
            $this->offering_no->HrefValue = "";

            // customer_id
            $this->customer_id->LinkCustomAttributes = "";
            $this->customer_id->HrefValue = "";

            // offering_date
            $this->offering_date->LinkCustomAttributes = "";
            $this->offering_date->HrefValue = "";

            // offering_term
            $this->offering_term->LinkCustomAttributes = "";
            $this->offering_term->HrefValue = "";
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
        if ($this->offering_no->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->customer_id->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->offering_date->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($this->offering_term->multiUpdateSelected()) {
            $updateCnt++;
        }
        if ($updateCnt == 0) {
            return false;
        }

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->offering_no->Required) {
            if ($this->offering_no->MultiUpdate != "" && !$this->offering_no->IsDetailKey && EmptyValue($this->offering_no->FormValue)) {
                $this->offering_no->addErrorMessage(str_replace("%s", $this->offering_no->caption(), $this->offering_no->RequiredErrorMessage));
            }
        }
        if ($this->customer_id->Required) {
            if ($this->customer_id->MultiUpdate != "" && !$this->customer_id->IsDetailKey && EmptyValue($this->customer_id->FormValue)) {
                $this->customer_id->addErrorMessage(str_replace("%s", $this->customer_id->caption(), $this->customer_id->RequiredErrorMessage));
            }
        }
        if ($this->offering_date->Required) {
            if ($this->offering_date->MultiUpdate != "" && !$this->offering_date->IsDetailKey && EmptyValue($this->offering_date->FormValue)) {
                $this->offering_date->addErrorMessage(str_replace("%s", $this->offering_date->caption(), $this->offering_date->RequiredErrorMessage));
            }
        }
        if ($this->offering_date->MultiUpdate != "") {
            if (!CheckDate($this->offering_date->FormValue)) {
                $this->offering_date->addErrorMessage($this->offering_date->getErrorMessage(false));
            }
        }
        if ($this->offering_term->Required) {
            if ($this->offering_term->MultiUpdate != "" && !$this->offering_term->IsDetailKey && EmptyValue($this->offering_term->FormValue)) {
                $this->offering_term->addErrorMessage(str_replace("%s", $this->offering_term->caption(), $this->offering_term->RequiredErrorMessage));
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
        if ($this->offering_no->CurrentValue != "") { // Check field with unique index
            $filterChk = "(`offering_no` = '" . AdjustSql($this->offering_no->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->offering_no->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->offering_no->CurrentValue, $idxErrMsg);
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

            // offering_no
            $this->offering_no->setDbValueDef($rsnew, $this->offering_no->CurrentValue, "", $this->offering_no->ReadOnly || $this->offering_no->MultiUpdate != "1");

            // customer_id
            $this->customer_id->setDbValueDef($rsnew, $this->customer_id->CurrentValue, 0, $this->customer_id->ReadOnly || $this->customer_id->MultiUpdate != "1");

            // offering_date
            $this->offering_date->setDbValueDef($rsnew, UnFormatDateTime($this->offering_date->CurrentValue, 0), CurrentDate(), $this->offering_date->ReadOnly || $this->offering_date->MultiUpdate != "1");

            // offering_term
            $this->offering_term->setDbValueDef($rsnew, $this->offering_term->CurrentValue, "", $this->offering_term->ReadOnly || $this->offering_term->MultiUpdate != "1");

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("offeringlist"), "", $this->TableVar, true);
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
                case "x_customer_id":
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
