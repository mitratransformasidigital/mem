<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for employee_quotation_detail
 */
class EmployeeQuotationDetail extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $quotation_id;
    public $detail_id;
    public $employee_username;
    public $rate;
    public $qty;
    public $Total;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'employee_quotation_detail';
        $this->TableName = 'employee_quotation_detail';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`employee_quotation_detail`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = true; // Allow detail add
        $this->DetailEdit = true; // Allow detail edit
        $this->DetailView = true; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // quotation_id
        $this->quotation_id = new DbField('employee_quotation_detail', 'employee_quotation_detail', 'x_quotation_id', 'quotation_id', '`quotation_id`', '`quotation_id`', 3, 5, -1, false, '`quotation_id`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->quotation_id->IsForeignKey = true; // Foreign key field
        $this->quotation_id->Nullable = false; // NOT NULL field
        $this->quotation_id->Sortable = false; // Allow sort
        $this->quotation_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['quotation_id'] = &$this->quotation_id;

        // detail_id
        $this->detail_id = new DbField('employee_quotation_detail', 'employee_quotation_detail', 'x_detail_id', 'detail_id', '`detail_id`', '`detail_id`', 3, 11, -1, false, '`detail_id`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->detail_id->IsAutoIncrement = true; // Autoincrement field
        $this->detail_id->IsPrimaryKey = true; // Primary key field
        $this->detail_id->Sortable = false; // Allow sort
        $this->detail_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['detail_id'] = &$this->detail_id;

        // employee_username
        $this->employee_username = new DbField('employee_quotation_detail', 'employee_quotation_detail', 'x_employee_username', 'employee_username', '`employee_username`', '`employee_username`', 200, 100, -1, false, '`employee_username`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->employee_username->Nullable = false; // NOT NULL field
        $this->employee_username->Required = true; // Required field
        $this->employee_username->Sortable = true; // Allow sort
        $this->employee_username->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->employee_username->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->employee_username->Lookup = new Lookup('employee_username', 'employee', false, 'employee_username', ["employee_name","","",""], [], [], [], [], [], [], '', '');
        $this->Fields['employee_username'] = &$this->employee_username;

        // rate
        $this->rate = new DbField('employee_quotation_detail', 'employee_quotation_detail', 'x_rate', 'rate', '`rate`', '`rate`', 3, 11, -1, false, '`rate`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->rate->Nullable = false; // NOT NULL field
        $this->rate->Required = true; // Required field
        $this->rate->Sortable = true; // Allow sort
        $this->rate->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['rate'] = &$this->rate;

        // qty
        $this->qty = new DbField('employee_quotation_detail', 'employee_quotation_detail', 'x_qty', 'qty', '`qty`', '`qty`', 3, 11, -1, false, '`qty`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->qty->Nullable = false; // NOT NULL field
        $this->qty->Required = true; // Required field
        $this->qty->Sortable = true; // Allow sort
        $this->qty->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['qty'] = &$this->qty;

        // Total
        $this->Total = new DbField('employee_quotation_detail', 'employee_quotation_detail', 'x_Total', 'Total', 'rate*qty', 'rate*qty', 20, 21, -1, false, 'rate*qty', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Total->IsCustom = true; // Custom field
        $this->Total->Sortable = true; // Allow sort
        $this->Total->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Total'] = &$this->Total;
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        } else {
            $fld->setSort("");
        }
    }

    // Current master table name
    public function getCurrentMasterTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE"));
    }

    public function setCurrentMasterTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE")] = $v;
    }

    // Session master WHERE clause
    public function getMasterFilter()
    {
        // Master filter
        $masterFilter = "";
        if ($this->getCurrentMasterTable() == "employee_quotation") {
            if ($this->quotation_id->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`quotation_id`", $this->quotation_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $masterFilter;
    }

    // Session detail WHERE clause
    public function getDetailFilter()
    {
        // Detail filter
        $detailFilter = "";
        if ($this->getCurrentMasterTable() == "employee_quotation") {
            if ($this->quotation_id->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`quotation_id`", $this->quotation_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    // Master filter
    public function sqlMasterFilter_employee_quotation()
    {
        return "`quotation_id`=@quotation_id@";
    }
    // Detail filter
    public function sqlDetailFilter_employee_quotation()
    {
        return "`quotation_id`=@quotation_id@";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`employee_quotation_detail`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*, rate*qty AS `Total`");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : $this->DefaultSort;
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter)
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof \Doctrine\DBAL\Query\QueryBuilder) { // Query builder
            $sql = $sql->resetQueryPart("orderBy")->getSQL();
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sql) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sql) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sql) && !preg_match('/\s+order\s+by\s+/i', $sql)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sql);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sql . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $rs = $conn->executeQuery($sqlwrk);
        $cnt = $rs->fetchColumn();
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    protected function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
            // Get insert id if necessary
            $this->detail_id->setDbValue($conn->lastInsertId());
            $rs['detail_id'] = $this->detail_id->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('detail_id', $rs)) {
                AddFilter($where, QuotedName('detail_id', $this->Dbid) . '=' . QuotedValue($rs['detail_id'], $this->detail_id->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->quotation_id->DbValue = $row['quotation_id'];
        $this->detail_id->DbValue = $row['detail_id'];
        $this->employee_username->DbValue = $row['employee_username'];
        $this->rate->DbValue = $row['rate'];
        $this->qty->DbValue = $row['qty'];
        $this->Total->DbValue = $row['Total'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`detail_id` = @detail_id@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->detail_id->CurrentValue : $this->detail_id->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->detail_id->CurrentValue = $keys[0];
            } else {
                $this->detail_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('detail_id', $row) ? $row['detail_id'] : null;
        } else {
            $val = $this->detail_id->OldValue !== null ? $this->detail_id->OldValue : $this->detail_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@detail_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("employeequotationdetaillist");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "employeequotationdetailview") {
            return $Language->phrase("View");
        } elseif ($pageName == "employeequotationdetailedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "employeequotationdetailadd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "EmployeeQuotationDetailView";
            case Config("API_ADD_ACTION"):
                return "EmployeeQuotationDetailAdd";
            case Config("API_EDIT_ACTION"):
                return "EmployeeQuotationDetailEdit";
            case Config("API_DELETE_ACTION"):
                return "EmployeeQuotationDetailDelete";
            case Config("API_LIST_ACTION"):
                return "EmployeeQuotationDetailList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "employeequotationdetaillist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("employeequotationdetailview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("employeequotationdetailview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "employeequotationdetailadd?" . $this->getUrlParm($parm);
        } else {
            $url = "employeequotationdetailadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("employeequotationdetailedit", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("employeequotationdetailadd", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("employeequotationdetaildelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "employee_quotation" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_quotation_id", $this->quotation_id->CurrentValue);
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "detail_id:" . JsonEncode($this->detail_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->detail_id->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->detail_id->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderSort($fld)
    {
        $classId = $fld->TableVar . "_" . $fld->Param;
        $scriptId = str_replace("%id%", $classId, "tpc_%id%");
        $scriptStart = $this->UseCustomTemplate ? "<template id=\"" . $scriptId . "\">" : "";
        $scriptEnd = $this->UseCustomTemplate ? "</template>" : "";
        $jsSort = " class=\"ew-pointer\" onclick=\"ew.sort(event, '" . $this->sortUrl($fld) . "', 1);\"";
        if ($this->sortUrl($fld) == "") {
            $html = <<<NOSORTHTML
{$scriptStart}<div class="ew-table-header-caption">{$fld->caption()}</div>{$scriptEnd}
NOSORTHTML;
        } else {
            if ($fld->getSort() == "ASC") {
                $sortIcon = '<i class="fas fa-sort-up"></i>';
            } elseif ($fld->getSort() == "DESC") {
                $sortIcon = '<i class="fas fa-sort-down"></i>';
            } else {
                $sortIcon = '';
            }
            $html = <<<SORTHTML
{$scriptStart}<div{$jsSort}><div class="ew-table-header-btn"><span class="ew-table-header-caption">{$fld->caption()}</span><span class="ew-table-header-sort">{$sortIcon}</span></div></div>{$scriptEnd}
SORTHTML;
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("detail_id") ?? Route("detail_id")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->detail_id->CurrentValue = $key;
            } else {
                $this->detail_id->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function &loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        $stmt = $conn->executeQuery($sql);
        return $stmt;
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->quotation_id->setDbValue($row['quotation_id']);
        $this->detail_id->setDbValue($row['detail_id']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->rate->setDbValue($row['rate']);
        $this->qty->setDbValue($row['qty']);
        $this->Total->setDbValue($row['Total']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // quotation_id
        $this->quotation_id->CellCssStyle = "white-space: nowrap;";

        // detail_id
        $this->detail_id->CellCssStyle = "white-space: nowrap;";

        // employee_username

        // rate

        // qty

        // Total
        $this->Total->CellCssStyle = "min-width: 125px;";

        // quotation_id
        $this->quotation_id->ViewValue = $this->quotation_id->CurrentValue;
        $this->quotation_id->ViewCustomAttributes = "";

        // detail_id
        $this->detail_id->ViewValue = $this->detail_id->CurrentValue;
        $this->detail_id->ViewValue = FormatNumber($this->detail_id->ViewValue, 0, -2, -2, -2);
        $this->detail_id->ViewCustomAttributes = "";

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

        // rate
        $this->rate->ViewValue = $this->rate->CurrentValue;
        $this->rate->ViewValue = FormatNumber($this->rate->ViewValue, 0, -2, -2, -2);
        $this->rate->CellCssStyle .= "text-align: right;";
        $this->rate->ViewCustomAttributes = "";

        // qty
        $this->qty->ViewValue = $this->qty->CurrentValue;
        $this->qty->ViewValue = FormatNumber($this->qty->ViewValue, 0, -2, -2, -2);
        $this->qty->CellCssStyle .= "text-align: right;";
        $this->qty->ViewCustomAttributes = "";

        // Total
        $this->Total->ViewValue = $this->Total->CurrentValue;
        $this->Total->ViewValue = FormatNumber($this->Total->ViewValue, 0, -2, -2, -2);
        $this->Total->CellCssStyle .= "text-align: right;";
        $this->Total->ViewCustomAttributes = "";

        // quotation_id
        $this->quotation_id->LinkCustomAttributes = "";
        $this->quotation_id->HrefValue = "";
        $this->quotation_id->TooltipValue = "";

        // detail_id
        $this->detail_id->LinkCustomAttributes = "";
        $this->detail_id->HrefValue = "";
        $this->detail_id->TooltipValue = "";

        // employee_username
        $this->employee_username->LinkCustomAttributes = "";
        $this->employee_username->HrefValue = "";
        $this->employee_username->TooltipValue = "";

        // rate
        $this->rate->LinkCustomAttributes = "";
        $this->rate->HrefValue = "";
        $this->rate->TooltipValue = "";

        // qty
        $this->qty->LinkCustomAttributes = "";
        $this->qty->HrefValue = "";
        $this->qty->TooltipValue = "";

        // Total
        $this->Total->LinkCustomAttributes = "";
        $this->Total->HrefValue = "";
        $this->Total->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // quotation_id
        $this->quotation_id->EditAttrs["class"] = "form-control";
        $this->quotation_id->EditCustomAttributes = "";
        if ($this->quotation_id->getSessionValue() != "") {
            $this->quotation_id->CurrentValue = GetForeignKeyValue($this->quotation_id->getSessionValue());
            $this->quotation_id->ViewValue = $this->quotation_id->CurrentValue;
            $this->quotation_id->ViewCustomAttributes = "";
        } else {
        }

        // detail_id
        $this->detail_id->EditAttrs["class"] = "form-control";
        $this->detail_id->EditCustomAttributes = "";

        // employee_username
        $this->employee_username->EditAttrs["class"] = "form-control";
        $this->employee_username->EditCustomAttributes = "";
        $this->employee_username->PlaceHolder = RemoveHtml($this->employee_username->caption());

        // rate
        $this->rate->EditAttrs["class"] = "form-control";
        $this->rate->EditCustomAttributes = "";
        $this->rate->EditValue = $this->rate->CurrentValue;
        $this->rate->PlaceHolder = RemoveHtml($this->rate->caption());

        // qty
        $this->qty->EditAttrs["class"] = "form-control";
        $this->qty->EditCustomAttributes = "";
        $this->qty->EditValue = $this->qty->CurrentValue;
        $this->qty->PlaceHolder = RemoveHtml($this->qty->caption());

        // Total
        $this->Total->EditAttrs["class"] = "form-control";
        $this->Total->EditCustomAttributes = "";
        $this->Total->EditValue = $this->Total->CurrentValue;
        $this->Total->EditValue = FormatNumber($this->Total->EditValue, 0, -2, -2, -2);
        $this->Total->CellCssStyle .= "text-align: right;";
        $this->Total->ViewCustomAttributes = "";

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
            $this->employee_username->Count++; // Increment count
            if (is_numeric($this->rate->CurrentValue)) {
                $this->rate->Total += $this->rate->CurrentValue; // Accumulate total
            }
            if (is_numeric($this->Total->CurrentValue)) {
                $this->Total->Total += $this->Total->CurrentValue; // Accumulate total
            }
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
            $this->employee_username->CurrentValue = $this->employee_username->Count;
            $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
            $this->employee_username->ViewCustomAttributes = "";
            $this->employee_username->HrefValue = ""; // Clear href value
            $this->rate->CurrentValue = $this->rate->Total;
            $this->rate->ViewValue = $this->rate->CurrentValue;
            $this->rate->ViewValue = FormatNumber($this->rate->ViewValue, 0, -2, -2, -2);
            $this->rate->CellCssStyle .= "text-align: right;";
            $this->rate->ViewCustomAttributes = "";
            $this->rate->HrefValue = ""; // Clear href value
            $this->Total->CurrentValue = $this->Total->Total;
            $this->Total->ViewValue = $this->Total->CurrentValue;
            $this->Total->ViewValue = FormatNumber($this->Total->ViewValue, 0, -2, -2, -2);
            $this->Total->CellCssStyle .= "text-align: right;";
            $this->Total->ViewCustomAttributes = "";
            $this->Total->HrefValue = ""; // Clear href value

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->employee_username);
                    $doc->exportCaption($this->rate);
                    $doc->exportCaption($this->qty);
                    $doc->exportCaption($this->Total);
                } else {
                    $doc->exportCaption($this->employee_username);
                    $doc->exportCaption($this->rate);
                    $doc->exportCaption($this->qty);
                    $doc->exportCaption($this->Total);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);
                $this->aggregateListRowValues(); // Aggregate row values

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->employee_username);
                        $doc->exportField($this->rate);
                        $doc->exportField($this->qty);
                        $doc->exportField($this->Total);
                    } else {
                        $doc->exportField($this->employee_username);
                        $doc->exportField($this->rate);
                        $doc->exportField($this->qty);
                        $doc->exportField($this->Total);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }

        // Export aggregates (horizontal format only)
        if ($doc->Horizontal) {
            $this->RowType = ROWTYPE_AGGREGATE;
            $this->resetAttributes();
            $this->aggregateListRow();
            if (!$doc->ExportCustom) {
                $doc->beginExportRow(-1);
                $doc->exportAggregate($this->employee_username, 'COUNT');
                $doc->exportAggregate($this->rate, 'TOTAL');
                $doc->exportAggregate($this->qty, '');
                $doc->exportAggregate($this->Total, 'TOTAL');
                $doc->endExportRow();
            }
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        // No binary fields
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email); var_dump($args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
