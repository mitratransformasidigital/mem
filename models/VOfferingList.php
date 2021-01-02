<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for v_offering_list
 */
class VOfferingList extends DbTable
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
    public $offering_id;
    public $offering_no;
    public $term;
    public $offering_date;
    public $customer_name;
    public $customer_address;
    public $phone_number;
    public $contact;
    public $city;
    public $description;
    public $rate;
    public $qty;
    public $total;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'v_offering_list';
        $this->TableName = 'v_offering_list';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`v_offering_list`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // offering_id
        $this->offering_id = new DbField('v_offering_list', 'v_offering_list', 'x_offering_id', 'offering_id', '`offering_id`', '`offering_id`', 3, 11, -1, false, '`offering_id`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->offering_id->IsAutoIncrement = true; // Autoincrement field
        $this->offering_id->IsPrimaryKey = true; // Primary key field
        $this->offering_id->Sortable = false; // Allow sort
        $this->offering_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['offering_id'] = &$this->offering_id;

        // offering_no
        $this->offering_no = new DbField('v_offering_list', 'v_offering_list', 'x_offering_no', 'offering_no', '`offering_no`', '`offering_no`', 200, 100, -1, false, '`offering_no`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->offering_no->Nullable = false; // NOT NULL field
        $this->offering_no->Required = true; // Required field
        $this->offering_no->Sortable = true; // Allow sort
        $this->Fields['offering_no'] = &$this->offering_no;

        // term
        $this->term = new DbField('v_offering_list', 'v_offering_list', 'x_term', 'term', '`term`', '`term`', 201, 5000, -1, false, '`term`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->term->Nullable = false; // NOT NULL field
        $this->term->Required = true; // Required field
        $this->term->Sortable = true; // Allow sort
        $this->Fields['term'] = &$this->term;

        // offering_date
        $this->offering_date = new DbField('v_offering_list', 'v_offering_list', 'x_offering_date', 'offering_date', '`offering_date`', '`offering_date`', 200, 40, -1, false, '`offering_date`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->offering_date->Sortable = true; // Allow sort
        $this->Fields['offering_date'] = &$this->offering_date;

        // customer_name
        $this->customer_name = new DbField('v_offering_list', 'v_offering_list', 'x_customer_name', 'customer_name', '`customer_name`', '`customer_name`', 200, 100, -1, false, '`customer_name`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->customer_name->Nullable = false; // NOT NULL field
        $this->customer_name->Required = true; // Required field
        $this->customer_name->Sortable = true; // Allow sort
        $this->Fields['customer_name'] = &$this->customer_name;

        // customer_address
        $this->customer_address = new DbField('v_offering_list', 'v_offering_list', 'x_customer_address', 'customer_address', '`customer_address`', '`customer_address`', 201, 300, -1, false, '`customer_address`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->customer_address->Nullable = false; // NOT NULL field
        $this->customer_address->Required = true; // Required field
        $this->customer_address->Sortable = true; // Allow sort
        $this->Fields['customer_address'] = &$this->customer_address;

        // phone_number
        $this->phone_number = new DbField('v_offering_list', 'v_offering_list', 'x_phone_number', 'phone_number', '`phone_number`', '`phone_number`', 200, 50, -1, false, '`phone_number`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->phone_number->Nullable = false; // NOT NULL field
        $this->phone_number->Required = true; // Required field
        $this->phone_number->Sortable = true; // Allow sort
        $this->Fields['phone_number'] = &$this->phone_number;

        // contact
        $this->contact = new DbField('v_offering_list', 'v_offering_list', 'x_contact', 'contact', '`contact`', '`contact`', 200, 100, -1, false, '`contact`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->contact->Nullable = false; // NOT NULL field
        $this->contact->Required = true; // Required field
        $this->contact->Sortable = true; // Allow sort
        $this->Fields['contact'] = &$this->contact;

        // city
        $this->city = new DbField('v_offering_list', 'v_offering_list', 'x_city', 'city', '`city`', '`city`', 200, 50, -1, false, '`city`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->city->Nullable = false; // NOT NULL field
        $this->city->Required = true; // Required field
        $this->city->Sortable = true; // Allow sort
        $this->Fields['city'] = &$this->city;

        // description
        $this->description = new DbField('v_offering_list', 'v_offering_list', 'x_description', 'description', '`description`', '`description`', 201, 500, -1, false, '`description`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->description->Nullable = false; // NOT NULL field
        $this->description->Required = true; // Required field
        $this->description->Sortable = true; // Allow sort
        $this->Fields['description'] = &$this->description;

        // rate
        $this->rate = new DbField('v_offering_list', 'v_offering_list', 'x_rate', 'rate', '`rate`', '`rate`', 3, 11, -1, false, '`rate`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->rate->Nullable = false; // NOT NULL field
        $this->rate->Required = true; // Required field
        $this->rate->Sortable = true; // Allow sort
        $this->rate->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['rate'] = &$this->rate;

        // qty
        $this->qty = new DbField('v_offering_list', 'v_offering_list', 'x_qty', 'qty', '`qty`', '`qty`', 3, 11, -1, false, '`qty`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->qty->Nullable = false; // NOT NULL field
        $this->qty->Required = true; // Required field
        $this->qty->Sortable = true; // Allow sort
        $this->qty->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['qty'] = &$this->qty;

        // total
        $this->total = new DbField('v_offering_list', 'v_offering_list', 'x_total', 'total', '`total`', '`total`', 131, 35, -1, false, '`total`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->total->Required = true; // Required field
        $this->total->Sortable = true; // Allow sort
        $this->total->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['total'] = &$this->total;
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

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`v_offering_list`";
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
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
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
            $this->offering_id->setDbValue($conn->lastInsertId());
            $rs['offering_id'] = $this->offering_id->DbValue;
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
            if (array_key_exists('offering_id', $rs)) {
                AddFilter($where, QuotedName('offering_id', $this->Dbid) . '=' . QuotedValue($rs['offering_id'], $this->offering_id->DataType, $this->Dbid));
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
        $this->offering_id->DbValue = $row['offering_id'];
        $this->offering_no->DbValue = $row['offering_no'];
        $this->term->DbValue = $row['term'];
        $this->offering_date->DbValue = $row['offering_date'];
        $this->customer_name->DbValue = $row['customer_name'];
        $this->customer_address->DbValue = $row['customer_address'];
        $this->phone_number->DbValue = $row['phone_number'];
        $this->contact->DbValue = $row['contact'];
        $this->city->DbValue = $row['city'];
        $this->description->DbValue = $row['description'];
        $this->rate->DbValue = $row['rate'];
        $this->qty->DbValue = $row['qty'];
        $this->total->DbValue = $row['total'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`offering_id` = @offering_id@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->offering_id->CurrentValue : $this->offering_id->OldValue;
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
                $this->offering_id->CurrentValue = $keys[0];
            } else {
                $this->offering_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('offering_id', $row) ? $row['offering_id'] : null;
        } else {
            $val = $this->offering_id->OldValue !== null ? $this->offering_id->OldValue : $this->offering_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@offering_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("vofferinglistlist");
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
        if ($pageName == "vofferinglistview") {
            return $Language->phrase("View");
        } elseif ($pageName == "vofferinglistedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "vofferinglistadd") {
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
                return "VOfferingListView";
            case Config("API_ADD_ACTION"):
                return "VOfferingListAdd";
            case Config("API_EDIT_ACTION"):
                return "VOfferingListEdit";
            case Config("API_DELETE_ACTION"):
                return "VOfferingListDelete";
            case Config("API_LIST_ACTION"):
                return "VOfferingListList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "vofferinglistlist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("vofferinglistview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("vofferinglistview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "vofferinglistadd?" . $this->getUrlParm($parm);
        } else {
            $url = "vofferinglistadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("vofferinglistedit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("vofferinglistadd", $this->getUrlParm($parm));
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
        return $this->keyUrl("vofferinglistdelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "offering_id:" . JsonEncode($this->offering_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->offering_id->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->offering_id->CurrentValue);
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
            if (($keyValue = Param("offering_id") ?? Route("offering_id")) !== null) {
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
                $this->offering_id->CurrentValue = $key;
            } else {
                $this->offering_id->OldValue = $key;
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
        $this->offering_id->setDbValue($row['offering_id']);
        $this->offering_no->setDbValue($row['offering_no']);
        $this->term->setDbValue($row['term']);
        $this->offering_date->setDbValue($row['offering_date']);
        $this->customer_name->setDbValue($row['customer_name']);
        $this->customer_address->setDbValue($row['customer_address']);
        $this->phone_number->setDbValue($row['phone_number']);
        $this->contact->setDbValue($row['contact']);
        $this->city->setDbValue($row['city']);
        $this->description->setDbValue($row['description']);
        $this->rate->setDbValue($row['rate']);
        $this->qty->setDbValue($row['qty']);
        $this->total->setDbValue($row['total']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // offering_id

        // offering_no

        // term

        // offering_date

        // customer_name

        // customer_address

        // phone_number

        // contact

        // city

        // description

        // rate

        // qty

        // total

        // offering_id
        $this->offering_id->ViewValue = $this->offering_id->CurrentValue;
        $this->offering_id->ViewCustomAttributes = "";

        // offering_no
        $this->offering_no->ViewValue = $this->offering_no->CurrentValue;
        $this->offering_no->ViewCustomAttributes = "";

        // term
        $this->term->ViewValue = $this->term->CurrentValue;
        $this->term->ViewCustomAttributes = "";

        // offering_date
        $this->offering_date->ViewValue = $this->offering_date->CurrentValue;
        $this->offering_date->ViewCustomAttributes = "";

        // customer_name
        $this->customer_name->ViewValue = $this->customer_name->CurrentValue;
        $this->customer_name->ViewCustomAttributes = "";

        // customer_address
        $this->customer_address->ViewValue = $this->customer_address->CurrentValue;
        $this->customer_address->ViewCustomAttributes = "";

        // phone_number
        $this->phone_number->ViewValue = $this->phone_number->CurrentValue;
        $this->phone_number->ViewCustomAttributes = "";

        // contact
        $this->contact->ViewValue = $this->contact->CurrentValue;
        $this->contact->ViewCustomAttributes = "";

        // city
        $this->city->ViewValue = $this->city->CurrentValue;
        $this->city->ViewCustomAttributes = "";

        // description
        $this->description->ViewValue = $this->description->CurrentValue;
        $this->description->ViewCustomAttributes = "";

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

        // total
        $this->total->ViewValue = $this->total->CurrentValue;
        $this->total->ViewValue = FormatNumber($this->total->ViewValue, 0, -2, -2, -2);
        $this->total->ViewCustomAttributes = "";

        // offering_id
        $this->offering_id->LinkCustomAttributes = "";
        $this->offering_id->HrefValue = "";
        $this->offering_id->TooltipValue = "";

        // offering_no
        $this->offering_no->LinkCustomAttributes = "";
        $this->offering_no->HrefValue = "";
        $this->offering_no->TooltipValue = "";

        // term
        $this->term->LinkCustomAttributes = "";
        $this->term->HrefValue = "";
        $this->term->TooltipValue = "";

        // offering_date
        $this->offering_date->LinkCustomAttributes = "";
        $this->offering_date->HrefValue = "";
        $this->offering_date->TooltipValue = "";

        // customer_name
        $this->customer_name->LinkCustomAttributes = "";
        $this->customer_name->HrefValue = "";
        $this->customer_name->TooltipValue = "";

        // customer_address
        $this->customer_address->LinkCustomAttributes = "";
        $this->customer_address->HrefValue = "";
        $this->customer_address->TooltipValue = "";

        // phone_number
        $this->phone_number->LinkCustomAttributes = "";
        $this->phone_number->HrefValue = "";
        $this->phone_number->TooltipValue = "";

        // contact
        $this->contact->LinkCustomAttributes = "";
        $this->contact->HrefValue = "";
        $this->contact->TooltipValue = "";

        // city
        $this->city->LinkCustomAttributes = "";
        $this->city->HrefValue = "";
        $this->city->TooltipValue = "";

        // description
        $this->description->LinkCustomAttributes = "";
        $this->description->HrefValue = "";
        $this->description->TooltipValue = "";

        // rate
        $this->rate->LinkCustomAttributes = "";
        $this->rate->HrefValue = "";
        $this->rate->TooltipValue = "";

        // qty
        $this->qty->LinkCustomAttributes = "";
        $this->qty->HrefValue = "";
        $this->qty->TooltipValue = "";

        // total
        $this->total->LinkCustomAttributes = "";
        $this->total->HrefValue = "";
        $this->total->TooltipValue = "";

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

        // offering_id
        $this->offering_id->EditAttrs["class"] = "form-control";
        $this->offering_id->EditCustomAttributes = "";

        // offering_no
        $this->offering_no->EditAttrs["class"] = "form-control";
        $this->offering_no->EditCustomAttributes = "";
        if (!$this->offering_no->Raw) {
            $this->offering_no->CurrentValue = HtmlDecode($this->offering_no->CurrentValue);
        }
        $this->offering_no->EditValue = $this->offering_no->CurrentValue;
        $this->offering_no->PlaceHolder = RemoveHtml($this->offering_no->caption());

        // term
        $this->term->EditAttrs["class"] = "form-control";
        $this->term->EditCustomAttributes = "";
        $this->term->EditValue = $this->term->CurrentValue;
        $this->term->PlaceHolder = RemoveHtml($this->term->caption());

        // offering_date
        $this->offering_date->EditAttrs["class"] = "form-control";
        $this->offering_date->EditCustomAttributes = "";
        if (!$this->offering_date->Raw) {
            $this->offering_date->CurrentValue = HtmlDecode($this->offering_date->CurrentValue);
        }
        $this->offering_date->EditValue = $this->offering_date->CurrentValue;
        $this->offering_date->PlaceHolder = RemoveHtml($this->offering_date->caption());

        // customer_name
        $this->customer_name->EditAttrs["class"] = "form-control";
        $this->customer_name->EditCustomAttributes = "";
        if (!$this->customer_name->Raw) {
            $this->customer_name->CurrentValue = HtmlDecode($this->customer_name->CurrentValue);
        }
        $this->customer_name->EditValue = $this->customer_name->CurrentValue;
        $this->customer_name->PlaceHolder = RemoveHtml($this->customer_name->caption());

        // customer_address
        $this->customer_address->EditAttrs["class"] = "form-control";
        $this->customer_address->EditCustomAttributes = "";
        $this->customer_address->EditValue = $this->customer_address->CurrentValue;
        $this->customer_address->PlaceHolder = RemoveHtml($this->customer_address->caption());

        // phone_number
        $this->phone_number->EditAttrs["class"] = "form-control";
        $this->phone_number->EditCustomAttributes = "";
        if (!$this->phone_number->Raw) {
            $this->phone_number->CurrentValue = HtmlDecode($this->phone_number->CurrentValue);
        }
        $this->phone_number->EditValue = $this->phone_number->CurrentValue;
        $this->phone_number->PlaceHolder = RemoveHtml($this->phone_number->caption());

        // contact
        $this->contact->EditAttrs["class"] = "form-control";
        $this->contact->EditCustomAttributes = "";
        if (!$this->contact->Raw) {
            $this->contact->CurrentValue = HtmlDecode($this->contact->CurrentValue);
        }
        $this->contact->EditValue = $this->contact->CurrentValue;
        $this->contact->PlaceHolder = RemoveHtml($this->contact->caption());

        // city
        $this->city->EditAttrs["class"] = "form-control";
        $this->city->EditCustomAttributes = "";
        if (!$this->city->Raw) {
            $this->city->CurrentValue = HtmlDecode($this->city->CurrentValue);
        }
        $this->city->EditValue = $this->city->CurrentValue;
        $this->city->PlaceHolder = RemoveHtml($this->city->caption());

        // description
        $this->description->EditAttrs["class"] = "form-control";
        $this->description->EditCustomAttributes = "";
        $this->description->EditValue = $this->description->CurrentValue;
        $this->description->PlaceHolder = RemoveHtml($this->description->caption());

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

        // total
        $this->total->EditAttrs["class"] = "form-control";
        $this->total->EditCustomAttributes = "";
        $this->total->EditValue = $this->total->CurrentValue;
        $this->total->PlaceHolder = RemoveHtml($this->total->caption());
        if (strval($this->total->EditValue) != "" && is_numeric($this->total->EditValue)) {
            $this->total->EditValue = FormatNumber($this->total->EditValue, -2, -2, -2, -2);
        }

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
            if (is_numeric($this->rate->CurrentValue)) {
                $this->rate->Total += $this->rate->CurrentValue; // Accumulate total
            }
            if (is_numeric($this->total->CurrentValue)) {
                $this->total->Total += $this->total->CurrentValue; // Accumulate total
            }
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
            $this->rate->CurrentValue = $this->rate->Total;
            $this->rate->ViewValue = $this->rate->CurrentValue;
            $this->rate->ViewValue = FormatNumber($this->rate->ViewValue, 0, -2, -2, -2);
            $this->rate->CellCssStyle .= "text-align: right;";
            $this->rate->ViewCustomAttributes = "";
            $this->rate->HrefValue = ""; // Clear href value
            $this->total->CurrentValue = $this->total->Total;
            $this->total->ViewValue = $this->total->CurrentValue;
            $this->total->ViewValue = FormatNumber($this->total->ViewValue, 0, -2, -2, -2);
            $this->total->ViewCustomAttributes = "";
            $this->total->HrefValue = ""; // Clear href value

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
                    $doc->exportCaption($this->offering_no);
                    $doc->exportCaption($this->term);
                    $doc->exportCaption($this->offering_date);
                    $doc->exportCaption($this->customer_name);
                    $doc->exportCaption($this->customer_address);
                    $doc->exportCaption($this->phone_number);
                    $doc->exportCaption($this->contact);
                    $doc->exportCaption($this->city);
                    $doc->exportCaption($this->description);
                    $doc->exportCaption($this->rate);
                    $doc->exportCaption($this->qty);
                    $doc->exportCaption($this->total);
                } else {
                    $doc->exportCaption($this->offering_no);
                    $doc->exportCaption($this->offering_date);
                    $doc->exportCaption($this->customer_name);
                    $doc->exportCaption($this->phone_number);
                    $doc->exportCaption($this->contact);
                    $doc->exportCaption($this->city);
                    $doc->exportCaption($this->rate);
                    $doc->exportCaption($this->qty);
                    $doc->exportCaption($this->total);
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
                        $doc->exportField($this->offering_no);
                        $doc->exportField($this->term);
                        $doc->exportField($this->offering_date);
                        $doc->exportField($this->customer_name);
                        $doc->exportField($this->customer_address);
                        $doc->exportField($this->phone_number);
                        $doc->exportField($this->contact);
                        $doc->exportField($this->city);
                        $doc->exportField($this->description);
                        $doc->exportField($this->rate);
                        $doc->exportField($this->qty);
                        $doc->exportField($this->total);
                    } else {
                        $doc->exportField($this->offering_no);
                        $doc->exportField($this->offering_date);
                        $doc->exportField($this->customer_name);
                        $doc->exportField($this->phone_number);
                        $doc->exportField($this->contact);
                        $doc->exportField($this->city);
                        $doc->exportField($this->rate);
                        $doc->exportField($this->qty);
                        $doc->exportField($this->total);
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
                $doc->exportAggregate($this->offering_no, '');
                $doc->exportAggregate($this->offering_date, '');
                $doc->exportAggregate($this->customer_name, '');
                $doc->exportAggregate($this->phone_number, '');
                $doc->exportAggregate($this->contact, '');
                $doc->exportAggregate($this->city, '');
                $doc->exportAggregate($this->rate, 'TOTAL');
                $doc->exportAggregate($this->qty, '');
                $doc->exportAggregate($this->total, 'TOTAL');
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
