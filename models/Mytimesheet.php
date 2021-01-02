<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for mytimesheet
 */
class Mytimesheet extends DbTable
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
    public $timesheet_id;
    public $employee_username;
    public $year;
    public $month;
    public $days;
    public $sick;
    public $leave;
    public $permit;
    public $absence;
    public $timesheet_doc;
    public $employee_notes;
    public $company_notes;
    public $approved;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'mytimesheet';
        $this->TableName = 'mytimesheet';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`mytimesheet`";
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

        // timesheet_id
        $this->timesheet_id = new DbField('mytimesheet', 'mytimesheet', 'x_timesheet_id', 'timesheet_id', '`timesheet_id`', '`timesheet_id`', 3, 11, -1, false, '`timesheet_id`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->timesheet_id->IsAutoIncrement = true; // Autoincrement field
        $this->timesheet_id->IsPrimaryKey = true; // Primary key field
        $this->timesheet_id->Sortable = false; // Allow sort
        $this->timesheet_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['timesheet_id'] = &$this->timesheet_id;

        // employee_username
        $this->employee_username = new DbField('mytimesheet', 'mytimesheet', 'x_employee_username', 'employee_username', '`employee_username`', '`employee_username`', 200, 50, -1, false, '`employee_username`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->employee_username->IsForeignKey = true; // Foreign key field
        $this->employee_username->Nullable = false; // NOT NULL field
        $this->employee_username->Sortable = false; // Allow sort
        $this->Fields['employee_username'] = &$this->employee_username;

        // year
        $this->year = new DbField('mytimesheet', 'mytimesheet', 'x_year', 'year', '`year`', '`year`', 3, 11, -1, false, '`year`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->year->Nullable = false; // NOT NULL field
        $this->year->Required = true; // Required field
        $this->year->Sortable = true; // Allow sort
        $this->year->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->year->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->year->Lookup = new Lookup('year', 'mytimesheet', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->year->OptionCount = 17;
        $this->year->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->year->AdvancedSearch->SearchValueDefault = Date('Y', strtotime('-1 months'));;
        $this->year->AdvancedSearch->SearchOperatorDefault = "=";
        $this->year->AdvancedSearch->SearchOperatorDefault2 = "";
        $this->year->AdvancedSearch->SearchConditionDefault = "AND";
        $this->Fields['year'] = &$this->year;

        // month
        $this->month = new DbField('mytimesheet', 'mytimesheet', 'x_month', 'month', '`month`', '`month`', 3, 11, -1, false, '`month`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->month->Nullable = false; // NOT NULL field
        $this->month->Required = true; // Required field
        $this->month->Sortable = true; // Allow sort
        $this->month->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->month->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->month->Lookup = new Lookup('month', 'mytimesheet', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->month->OptionCount = 12;
        $this->month->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['month'] = &$this->month;

        // days
        $this->days = new DbField('mytimesheet', 'mytimesheet', 'x_days', 'days', '`days`', '`days`', 3, 11, -1, false, '`days`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->days->Sortable = true; // Allow sort
        $this->days->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['days'] = &$this->days;

        // sick
        $this->sick = new DbField('mytimesheet', 'mytimesheet', 'x_sick', 'sick', '`sick`', '`sick`', 3, 11, -1, false, '`sick`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->sick->Sortable = true; // Allow sort
        $this->sick->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['sick'] = &$this->sick;

        // leave
        $this->leave = new DbField('mytimesheet', 'mytimesheet', 'x_leave', 'leave', '`leave`', '`leave`', 3, 11, -1, false, '`leave`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->leave->Sortable = true; // Allow sort
        $this->leave->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['leave'] = &$this->leave;

        // permit
        $this->permit = new DbField('mytimesheet', 'mytimesheet', 'x_permit', 'permit', '`permit`', '`permit`', 3, 11, -1, false, '`permit`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->permit->Sortable = true; // Allow sort
        $this->permit->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['permit'] = &$this->permit;

        // absence
        $this->absence = new DbField('mytimesheet', 'mytimesheet', 'x_absence', 'absence', '`absence`', '`absence`', 3, 11, -1, false, '`absence`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->absence->Sortable = true; // Allow sort
        $this->absence->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['absence'] = &$this->absence;

        // timesheet_doc
        $this->timesheet_doc = new DbField('mytimesheet', 'mytimesheet', 'x_timesheet_doc', 'timesheet_doc', '`timesheet_doc`', '`timesheet_doc`', 200, 150, -1, true, '`timesheet_doc`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->timesheet_doc->Sortable = true; // Allow sort
        $this->Fields['timesheet_doc'] = &$this->timesheet_doc;

        // employee_notes
        $this->employee_notes = new DbField('mytimesheet', 'mytimesheet', 'x_employee_notes', 'employee_notes', '`employee_notes`', '`employee_notes`', 201, 65535, -1, false, '`employee_notes`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->employee_notes->Sortable = true; // Allow sort
        $this->Fields['employee_notes'] = &$this->employee_notes;

        // company_notes
        $this->company_notes = new DbField('mytimesheet', 'mytimesheet', 'x_company_notes', 'company_notes', '`company_notes`', '`company_notes`', 201, 65535, -1, false, '`company_notes`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->company_notes->Sortable = true; // Allow sort
        $this->Fields['company_notes'] = &$this->company_notes;

        // approved
        $this->approved = new DbField('mytimesheet', 'mytimesheet', 'x_approved', 'approved', '`approved`', '`approved`', 200, 50, -1, false, '`approved`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->approved->Sortable = true; // Allow sort
        $this->approved->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->approved->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->approved->Lookup = new Lookup('approved', 'mytimesheet', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->approved->OptionCount = 3;
        $this->Fields['approved'] = &$this->approved;
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
        if ($this->getCurrentMasterTable() == "myprofile") {
            if ($this->employee_username->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`employee_username`", $this->employee_username->getSessionValue(), DATATYPE_STRING, "DB");
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
        if ($this->getCurrentMasterTable() == "myprofile") {
            if ($this->employee_username->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`employee_username`", $this->employee_username->getSessionValue(), DATATYPE_STRING, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    // Master filter
    public function sqlMasterFilter_myprofile()
    {
        return "`employee_username`='@employee_username@'";
    }
    // Detail filter
    public function sqlDetailFilter_myprofile()
    {
        return "`employee_username`='@employee_username@'";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`mytimesheet`";
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
        $this->DefaultFilter = "`employee_username`='".CurrentUserName()."'";
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
            $this->timesheet_id->setDbValue($conn->lastInsertId());
            $rs['timesheet_id'] = $this->timesheet_id->DbValue;
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
            if (array_key_exists('timesheet_id', $rs)) {
                AddFilter($where, QuotedName('timesheet_id', $this->Dbid) . '=' . QuotedValue($rs['timesheet_id'], $this->timesheet_id->DataType, $this->Dbid));
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
        $this->timesheet_id->DbValue = $row['timesheet_id'];
        $this->employee_username->DbValue = $row['employee_username'];
        $this->year->DbValue = $row['year'];
        $this->month->DbValue = $row['month'];
        $this->days->DbValue = $row['days'];
        $this->sick->DbValue = $row['sick'];
        $this->leave->DbValue = $row['leave'];
        $this->permit->DbValue = $row['permit'];
        $this->absence->DbValue = $row['absence'];
        $this->timesheet_doc->Upload->DbValue = $row['timesheet_doc'];
        $this->employee_notes->DbValue = $row['employee_notes'];
        $this->company_notes->DbValue = $row['company_notes'];
        $this->approved->DbValue = $row['approved'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['timesheet_doc']) ? [] : [$row['timesheet_doc']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->timesheet_doc->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->timesheet_doc->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`timesheet_id` = @timesheet_id@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->timesheet_id->CurrentValue : $this->timesheet_id->OldValue;
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
                $this->timesheet_id->CurrentValue = $keys[0];
            } else {
                $this->timesheet_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('timesheet_id', $row) ? $row['timesheet_id'] : null;
        } else {
            $val = $this->timesheet_id->OldValue !== null ? $this->timesheet_id->OldValue : $this->timesheet_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@timesheet_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("mytimesheetlist");
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
        if ($pageName == "mytimesheetview") {
            return $Language->phrase("View");
        } elseif ($pageName == "mytimesheetedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "mytimesheetadd") {
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
                return "MytimesheetView";
            case Config("API_ADD_ACTION"):
                return "MytimesheetAdd";
            case Config("API_EDIT_ACTION"):
                return "MytimesheetEdit";
            case Config("API_DELETE_ACTION"):
                return "MytimesheetDelete";
            case Config("API_LIST_ACTION"):
                return "MytimesheetList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "mytimesheetlist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("mytimesheetview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("mytimesheetview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "mytimesheetadd?" . $this->getUrlParm($parm);
        } else {
            $url = "mytimesheetadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("mytimesheetedit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("mytimesheetadd", $this->getUrlParm($parm));
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
        return $this->keyUrl("mytimesheetdelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "myprofile" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "timesheet_id:" . JsonEncode($this->timesheet_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->timesheet_id->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->timesheet_id->CurrentValue);
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
            if (($keyValue = Param("timesheet_id") ?? Route("timesheet_id")) !== null) {
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
                $this->timesheet_id->CurrentValue = $key;
            } else {
                $this->timesheet_id->OldValue = $key;
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
        $this->employee_notes->setDbValue($row['employee_notes']);
        $this->company_notes->setDbValue($row['company_notes']);
        $this->approved->setDbValue($row['approved']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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

        // timesheet_id
        $this->timesheet_id->ViewValue = $this->timesheet_id->CurrentValue;
        $this->timesheet_id->ViewValue = FormatNumber($this->timesheet_id->ViewValue, 0, -2, -2, -2);
        $this->timesheet_id->ViewCustomAttributes = "";

        // employee_username
        $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
        $this->employee_username->ViewCustomAttributes = "";

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

        // employee_notes
        $this->employee_notes->ViewValue = $this->employee_notes->CurrentValue;
        $this->employee_notes->ViewCustomAttributes = "";

        // company_notes
        $this->company_notes->ViewValue = $this->company_notes->CurrentValue;
        $this->company_notes->ViewCustomAttributes = "";

        // approved
        if (strval($this->approved->CurrentValue) != "") {
            $this->approved->ViewValue = $this->approved->optionCaption($this->approved->CurrentValue);
        } else {
            $this->approved->ViewValue = null;
        }
        $this->approved->ViewCustomAttributes = "";

        // timesheet_id
        $this->timesheet_id->LinkCustomAttributes = "";
        $this->timesheet_id->HrefValue = "";
        $this->timesheet_id->TooltipValue = "";

        // employee_username
        $this->employee_username->LinkCustomAttributes = "";
        $this->employee_username->HrefValue = "";
        $this->employee_username->TooltipValue = "";

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

        // employee_notes
        $this->employee_notes->LinkCustomAttributes = "";
        $this->employee_notes->HrefValue = "";
        $this->employee_notes->TooltipValue = "";

        // company_notes
        $this->company_notes->LinkCustomAttributes = "";
        $this->company_notes->HrefValue = "";
        $this->company_notes->TooltipValue = "";

        // approved
        $this->approved->LinkCustomAttributes = "";
        $this->approved->HrefValue = "";
        $this->approved->TooltipValue = "";

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

        // timesheet_id
        $this->timesheet_id->EditAttrs["class"] = "form-control";
        $this->timesheet_id->EditCustomAttributes = "";

        // employee_username
        $this->employee_username->EditAttrs["class"] = "form-control";
        $this->employee_username->EditCustomAttributes = "";
        if ($this->employee_username->getSessionValue() != "") {
            $this->employee_username->CurrentValue = GetForeignKeyValue($this->employee_username->getSessionValue());
            $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
            $this->employee_username->ViewCustomAttributes = "";
        } else {
        }

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
        $this->days->EditValue = $this->days->CurrentValue;
        $this->days->PlaceHolder = RemoveHtml($this->days->caption());

        // sick
        $this->sick->EditAttrs["class"] = "form-control";
        $this->sick->EditCustomAttributes = "";
        $this->sick->EditValue = $this->sick->CurrentValue;
        $this->sick->PlaceHolder = RemoveHtml($this->sick->caption());

        // leave
        $this->leave->EditAttrs["class"] = "form-control";
        $this->leave->EditCustomAttributes = "";
        $this->leave->EditValue = $this->leave->CurrentValue;
        $this->leave->PlaceHolder = RemoveHtml($this->leave->caption());

        // permit
        $this->permit->EditAttrs["class"] = "form-control";
        $this->permit->EditCustomAttributes = "";
        $this->permit->EditValue = $this->permit->CurrentValue;
        $this->permit->PlaceHolder = RemoveHtml($this->permit->caption());

        // absence
        $this->absence->EditAttrs["class"] = "form-control";
        $this->absence->EditCustomAttributes = "";
        $this->absence->EditValue = $this->absence->CurrentValue;
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

        // employee_notes
        $this->employee_notes->EditAttrs["class"] = "form-control";
        $this->employee_notes->EditCustomAttributes = "";
        $this->employee_notes->EditValue = $this->employee_notes->CurrentValue;
        $this->employee_notes->PlaceHolder = RemoveHtml($this->employee_notes->caption());

        // company_notes
        $this->company_notes->EditAttrs["class"] = "form-control";
        $this->company_notes->EditCustomAttributes = "";
        $this->company_notes->EditValue = $this->company_notes->CurrentValue;
        $this->company_notes->PlaceHolder = RemoveHtml($this->company_notes->caption());

        // approved
        $this->approved->EditAttrs["class"] = "form-control";
        $this->approved->EditCustomAttributes = "";
        $this->approved->EditValue = $this->approved->options(true);
        $this->approved->PlaceHolder = RemoveHtml($this->approved->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
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

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
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
                    $doc->exportCaption($this->year);
                    $doc->exportCaption($this->month);
                    $doc->exportCaption($this->days);
                    $doc->exportCaption($this->sick);
                    $doc->exportCaption($this->leave);
                    $doc->exportCaption($this->permit);
                    $doc->exportCaption($this->absence);
                    $doc->exportCaption($this->timesheet_doc);
                    $doc->exportCaption($this->employee_notes);
                    $doc->exportCaption($this->company_notes);
                    $doc->exportCaption($this->approved);
                } else {
                    $doc->exportCaption($this->year);
                    $doc->exportCaption($this->month);
                    $doc->exportCaption($this->days);
                    $doc->exportCaption($this->sick);
                    $doc->exportCaption($this->leave);
                    $doc->exportCaption($this->permit);
                    $doc->exportCaption($this->absence);
                    $doc->exportCaption($this->timesheet_doc);
                    $doc->exportCaption($this->approved);
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
                        $doc->exportField($this->year);
                        $doc->exportField($this->month);
                        $doc->exportField($this->days);
                        $doc->exportField($this->sick);
                        $doc->exportField($this->leave);
                        $doc->exportField($this->permit);
                        $doc->exportField($this->absence);
                        $doc->exportField($this->timesheet_doc);
                        $doc->exportField($this->employee_notes);
                        $doc->exportField($this->company_notes);
                        $doc->exportField($this->approved);
                    } else {
                        $doc->exportField($this->year);
                        $doc->exportField($this->month);
                        $doc->exportField($this->days);
                        $doc->exportField($this->sick);
                        $doc->exportField($this->leave);
                        $doc->exportField($this->permit);
                        $doc->exportField($this->absence);
                        $doc->exportField($this->timesheet_doc);
                        $doc->exportField($this->approved);
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
                $doc->exportAggregate($this->year, '');
                $doc->exportAggregate($this->month, '');
                $doc->exportAggregate($this->days, 'TOTAL');
                $doc->exportAggregate($this->sick, 'TOTAL');
                $doc->exportAggregate($this->leave, 'TOTAL');
                $doc->exportAggregate($this->permit, 'TOTAL');
                $doc->exportAggregate($this->absence, 'TOTAL');
                $doc->exportAggregate($this->timesheet_doc, '');
                $doc->exportAggregate($this->approved, '');
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
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'timesheet_doc') {
            $fldName = "timesheet_doc";
            $fileNameFld = "timesheet_doc";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->timesheet_id->CurrentValue = $ar[0];
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssoc($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DATATYPE_BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, 100, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $pathinfo = pathinfo($fileName);
                        $ext = strtolower(@$pathinfo["extension"]);
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
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
