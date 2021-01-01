<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for master_shift
 */
class MasterShift extends DbTable
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
    public $shift_id;
    public $shift_name;
    public $sunday_time_in;
    public $sunday_time_out;
    public $monday_time_in;
    public $monday_time_out;
    public $tuesday_time_in;
    public $tuesday_time_out;
    public $wednesday_time_in;
    public $wednesday_time_out;
    public $thursday_time_in;
    public $thursday_time_out;
    public $friday_time_in;
    public $friday_time_out;
    public $saturday_time_in;
    public $saturday_time_out;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'master_shift';
        $this->TableName = 'master_shift';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`master_shift`";
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
        $this->ShowMultipleDetails = true; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // shift_id
        $this->shift_id = new DbField('master_shift', 'master_shift', 'x_shift_id', 'shift_id', '`shift_id`', '`shift_id`', 3, 11, -1, false, '`shift_id`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->shift_id->IsAutoIncrement = true; // Autoincrement field
        $this->shift_id->IsPrimaryKey = true; // Primary key field
        $this->shift_id->IsForeignKey = true; // Foreign key field
        $this->shift_id->Sortable = false; // Allow sort
        $this->shift_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['shift_id'] = &$this->shift_id;

        // shift_name
        $this->shift_name = new DbField('master_shift', 'master_shift', 'x_shift_name', 'shift_name', '`shift_name`', '`shift_name`', 200, 150, -1, false, '`shift_name`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->shift_name->Nullable = false; // NOT NULL field
        $this->shift_name->Required = true; // Required field
        $this->shift_name->Sortable = true; // Allow sort
        $this->Fields['shift_name'] = &$this->shift_name;

        // sunday_time_in
        $this->sunday_time_in = new DbField('master_shift', 'master_shift', 'x_sunday_time_in', 'sunday_time_in', '`sunday_time_in`', CastDateFieldForLike("`sunday_time_in`", 4, "DB"), 134, 10, 4, false, '`sunday_time_in`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->sunday_time_in->Sortable = true; // Allow sort
        $this->sunday_time_in->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['sunday_time_in'] = &$this->sunday_time_in;

        // sunday_time_out
        $this->sunday_time_out = new DbField('master_shift', 'master_shift', 'x_sunday_time_out', 'sunday_time_out', '`sunday_time_out`', CastDateFieldForLike("`sunday_time_out`", 4, "DB"), 134, 10, 4, false, '`sunday_time_out`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->sunday_time_out->Sortable = true; // Allow sort
        $this->sunday_time_out->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['sunday_time_out'] = &$this->sunday_time_out;

        // monday_time_in
        $this->monday_time_in = new DbField('master_shift', 'master_shift', 'x_monday_time_in', 'monday_time_in', '`monday_time_in`', CastDateFieldForLike("`monday_time_in`", 4, "DB"), 134, 10, 4, false, '`monday_time_in`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monday_time_in->Sortable = true; // Allow sort
        $this->monday_time_in->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['monday_time_in'] = &$this->monday_time_in;

        // monday_time_out
        $this->monday_time_out = new DbField('master_shift', 'master_shift', 'x_monday_time_out', 'monday_time_out', '`monday_time_out`', CastDateFieldForLike("`monday_time_out`", 4, "DB"), 134, 10, 4, false, '`monday_time_out`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->monday_time_out->Sortable = true; // Allow sort
        $this->monday_time_out->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['monday_time_out'] = &$this->monday_time_out;

        // tuesday_time_in
        $this->tuesday_time_in = new DbField('master_shift', 'master_shift', 'x_tuesday_time_in', 'tuesday_time_in', '`tuesday_time_in`', CastDateFieldForLike("`tuesday_time_in`", 4, "DB"), 134, 10, 4, false, '`tuesday_time_in`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tuesday_time_in->Sortable = true; // Allow sort
        $this->tuesday_time_in->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['tuesday_time_in'] = &$this->tuesday_time_in;

        // tuesday_time_out
        $this->tuesday_time_out = new DbField('master_shift', 'master_shift', 'x_tuesday_time_out', 'tuesday_time_out', '`tuesday_time_out`', CastDateFieldForLike("`tuesday_time_out`", 4, "DB"), 134, 10, 4, false, '`tuesday_time_out`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tuesday_time_out->Sortable = true; // Allow sort
        $this->tuesday_time_out->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['tuesday_time_out'] = &$this->tuesday_time_out;

        // wednesday_time_in
        $this->wednesday_time_in = new DbField('master_shift', 'master_shift', 'x_wednesday_time_in', 'wednesday_time_in', '`wednesday_time_in`', CastDateFieldForLike("`wednesday_time_in`", 4, "DB"), 134, 10, 4, false, '`wednesday_time_in`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->wednesday_time_in->Sortable = true; // Allow sort
        $this->wednesday_time_in->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['wednesday_time_in'] = &$this->wednesday_time_in;

        // wednesday_time_out
        $this->wednesday_time_out = new DbField('master_shift', 'master_shift', 'x_wednesday_time_out', 'wednesday_time_out', '`wednesday_time_out`', CastDateFieldForLike("`wednesday_time_out`", 4, "DB"), 134, 10, 4, false, '`wednesday_time_out`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->wednesday_time_out->Sortable = true; // Allow sort
        $this->wednesday_time_out->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['wednesday_time_out'] = &$this->wednesday_time_out;

        // thursday_time_in
        $this->thursday_time_in = new DbField('master_shift', 'master_shift', 'x_thursday_time_in', 'thursday_time_in', '`thursday_time_in`', CastDateFieldForLike("`thursday_time_in`", 4, "DB"), 134, 10, 4, false, '`thursday_time_in`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->thursday_time_in->Sortable = true; // Allow sort
        $this->thursday_time_in->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['thursday_time_in'] = &$this->thursday_time_in;

        // thursday_time_out
        $this->thursday_time_out = new DbField('master_shift', 'master_shift', 'x_thursday_time_out', 'thursday_time_out', '`thursday_time_out`', CastDateFieldForLike("`thursday_time_out`", 4, "DB"), 134, 10, 4, false, '`thursday_time_out`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->thursday_time_out->Sortable = true; // Allow sort
        $this->thursday_time_out->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['thursday_time_out'] = &$this->thursday_time_out;

        // friday_time_in
        $this->friday_time_in = new DbField('master_shift', 'master_shift', 'x_friday_time_in', 'friday_time_in', '`friday_time_in`', CastDateFieldForLike("`friday_time_in`", 4, "DB"), 134, 10, 4, false, '`friday_time_in`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->friday_time_in->Sortable = true; // Allow sort
        $this->friday_time_in->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['friday_time_in'] = &$this->friday_time_in;

        // friday_time_out
        $this->friday_time_out = new DbField('master_shift', 'master_shift', 'x_friday_time_out', 'friday_time_out', '`friday_time_out`', CastDateFieldForLike("`friday_time_out`", 4, "DB"), 134, 10, 4, false, '`friday_time_out`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->friday_time_out->Sortable = true; // Allow sort
        $this->friday_time_out->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['friday_time_out'] = &$this->friday_time_out;

        // saturday_time_in
        $this->saturday_time_in = new DbField('master_shift', 'master_shift', 'x_saturday_time_in', 'saturday_time_in', '`saturday_time_in`', CastDateFieldForLike("`saturday_time_in`", 4, "DB"), 134, 10, 4, false, '`saturday_time_in`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->saturday_time_in->Sortable = true; // Allow sort
        $this->saturday_time_in->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['saturday_time_in'] = &$this->saturday_time_in;

        // saturday_time_out
        $this->saturday_time_out = new DbField('master_shift', 'master_shift', 'x_saturday_time_out', 'saturday_time_out', '`saturday_time_out`', CastDateFieldForLike("`saturday_time_out`", 4, "DB"), 134, 10, 4, false, '`saturday_time_out`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->saturday_time_out->Sortable = true; // Allow sort
        $this->saturday_time_out->DefaultErrorMessage = str_replace("%s", $GLOBALS["TIME_SEPARATOR"], $Language->phrase("IncorrectTime"));
        $this->Fields['saturday_time_out'] = &$this->saturday_time_out;
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

    // Current detail table name
    public function getCurrentDetailTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE"));
    }

    public function setCurrentDetailTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")] = $v;
    }

    // Get detail url
    public function getDetailUrl()
    {
        // Detail url
        $detailUrl = "";
        if ($this->getCurrentDetailTable() == "master_holiday") {
            $detailUrl = Container("master_holiday")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_shift_id", $this->shift_id->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "employee_shift") {
            $detailUrl = Container("employee_shift")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_shift_id", $this->shift_id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "mastershiftlist";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`master_shift`";
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
            $this->shift_id->setDbValue($conn->lastInsertId());
            $rs['shift_id'] = $this->shift_id->DbValue;
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
        // Cascade Update detail table 'master_holiday'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['shift_id']) && $rsold['shift_id'] != $rs['shift_id'])) { // Update detail field 'shift_id'
            $cascadeUpdate = true;
            $rscascade['shift_id'] = $rs['shift_id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("master_holiday")->loadRs("`shift_id` = " . QuotedValue($rsold['shift_id'], DATATYPE_NUMBER, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'holiday_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("master_holiday")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("master_holiday")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("master_holiday")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'employee_shift'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['shift_id']) && $rsold['shift_id'] != $rs['shift_id'])) { // Update detail field 'shift_id'
            $cascadeUpdate = true;
            $rscascade['shift_id'] = $rs['shift_id'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("employee_shift")->loadRs("`shift_id` = " . QuotedValue($rsold['shift_id'], DATATYPE_NUMBER, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'es_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("employee_shift")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("employee_shift")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("employee_shift")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

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
            if (array_key_exists('shift_id', $rs)) {
                AddFilter($where, QuotedName('shift_id', $this->Dbid) . '=' . QuotedValue($rs['shift_id'], $this->shift_id->DataType, $this->Dbid));
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

        // Cascade delete detail table 'master_holiday'
        $dtlrows = Container("master_holiday")->loadRs("`shift_id` = " . QuotedValue($rs['shift_id'], DATATYPE_NUMBER, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("master_holiday")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("master_holiday")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("master_holiday")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'employee_shift'
        $dtlrows = Container("employee_shift")->loadRs("`shift_id` = " . QuotedValue($rs['shift_id'], DATATYPE_NUMBER, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("employee_shift")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("employee_shift")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("employee_shift")->rowDeleted($dtlrow);
            }
        }
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
        $this->shift_id->DbValue = $row['shift_id'];
        $this->shift_name->DbValue = $row['shift_name'];
        $this->sunday_time_in->DbValue = $row['sunday_time_in'];
        $this->sunday_time_out->DbValue = $row['sunday_time_out'];
        $this->monday_time_in->DbValue = $row['monday_time_in'];
        $this->monday_time_out->DbValue = $row['monday_time_out'];
        $this->tuesday_time_in->DbValue = $row['tuesday_time_in'];
        $this->tuesday_time_out->DbValue = $row['tuesday_time_out'];
        $this->wednesday_time_in->DbValue = $row['wednesday_time_in'];
        $this->wednesday_time_out->DbValue = $row['wednesday_time_out'];
        $this->thursday_time_in->DbValue = $row['thursday_time_in'];
        $this->thursday_time_out->DbValue = $row['thursday_time_out'];
        $this->friday_time_in->DbValue = $row['friday_time_in'];
        $this->friday_time_out->DbValue = $row['friday_time_out'];
        $this->saturday_time_in->DbValue = $row['saturday_time_in'];
        $this->saturday_time_out->DbValue = $row['saturday_time_out'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`shift_id` = @shift_id@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->shift_id->CurrentValue : $this->shift_id->OldValue;
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
                $this->shift_id->CurrentValue = $keys[0];
            } else {
                $this->shift_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('shift_id', $row) ? $row['shift_id'] : null;
        } else {
            $val = $this->shift_id->OldValue !== null ? $this->shift_id->OldValue : $this->shift_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@shift_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("mastershiftlist");
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
        if ($pageName == "mastershiftview") {
            return $Language->phrase("View");
        } elseif ($pageName == "mastershiftedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "mastershiftadd") {
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
                return "MasterShiftView";
            case Config("API_ADD_ACTION"):
                return "MasterShiftAdd";
            case Config("API_EDIT_ACTION"):
                return "MasterShiftEdit";
            case Config("API_DELETE_ACTION"):
                return "MasterShiftDelete";
            case Config("API_LIST_ACTION"):
                return "MasterShiftList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "mastershiftlist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("mastershiftview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("mastershiftview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "mastershiftadd?" . $this->getUrlParm($parm);
        } else {
            $url = "mastershiftadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("mastershiftedit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("mastershiftedit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
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
        if ($parm != "") {
            $url = $this->keyUrl("mastershiftadd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("mastershiftadd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
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
        return $this->keyUrl("mastershiftdelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "shift_id:" . JsonEncode($this->shift_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->shift_id->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->shift_id->CurrentValue);
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
            if (($keyValue = Param("shift_id") ?? Route("shift_id")) !== null) {
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
                $this->shift_id->CurrentValue = $key;
            } else {
                $this->shift_id->OldValue = $key;
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
        $this->shift_id->setDbValue($row['shift_id']);
        $this->shift_name->setDbValue($row['shift_name']);
        $this->sunday_time_in->setDbValue($row['sunday_time_in']);
        $this->sunday_time_out->setDbValue($row['sunday_time_out']);
        $this->monday_time_in->setDbValue($row['monday_time_in']);
        $this->monday_time_out->setDbValue($row['monday_time_out']);
        $this->tuesday_time_in->setDbValue($row['tuesday_time_in']);
        $this->tuesday_time_out->setDbValue($row['tuesday_time_out']);
        $this->wednesday_time_in->setDbValue($row['wednesday_time_in']);
        $this->wednesday_time_out->setDbValue($row['wednesday_time_out']);
        $this->thursday_time_in->setDbValue($row['thursday_time_in']);
        $this->thursday_time_out->setDbValue($row['thursday_time_out']);
        $this->friday_time_in->setDbValue($row['friday_time_in']);
        $this->friday_time_out->setDbValue($row['friday_time_out']);
        $this->saturday_time_in->setDbValue($row['saturday_time_in']);
        $this->saturday_time_out->setDbValue($row['saturday_time_out']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // shift_id

        // shift_name

        // sunday_time_in

        // sunday_time_out

        // monday_time_in

        // monday_time_out

        // tuesday_time_in

        // tuesday_time_out

        // wednesday_time_in

        // wednesday_time_out

        // thursday_time_in

        // thursday_time_out

        // friday_time_in

        // friday_time_out

        // saturday_time_in

        // saturday_time_out

        // shift_id
        $this->shift_id->ViewValue = $this->shift_id->CurrentValue;
        $this->shift_id->ViewCustomAttributes = "";

        // shift_name
        $this->shift_name->ViewValue = $this->shift_name->CurrentValue;
        $this->shift_name->ViewCustomAttributes = "";

        // sunday_time_in
        $this->sunday_time_in->ViewValue = $this->sunday_time_in->CurrentValue;
        $this->sunday_time_in->ViewValue = FormatDateTime($this->sunday_time_in->ViewValue, 4);
        $this->sunday_time_in->ViewCustomAttributes = "";

        // sunday_time_out
        $this->sunday_time_out->ViewValue = $this->sunday_time_out->CurrentValue;
        $this->sunday_time_out->ViewValue = FormatDateTime($this->sunday_time_out->ViewValue, 4);
        $this->sunday_time_out->ViewCustomAttributes = "";

        // monday_time_in
        $this->monday_time_in->ViewValue = $this->monday_time_in->CurrentValue;
        $this->monday_time_in->ViewValue = FormatDateTime($this->monday_time_in->ViewValue, 4);
        $this->monday_time_in->ViewCustomAttributes = "";

        // monday_time_out
        $this->monday_time_out->ViewValue = $this->monday_time_out->CurrentValue;
        $this->monday_time_out->ViewValue = FormatDateTime($this->monday_time_out->ViewValue, 4);
        $this->monday_time_out->ViewCustomAttributes = "";

        // tuesday_time_in
        $this->tuesday_time_in->ViewValue = $this->tuesday_time_in->CurrentValue;
        $this->tuesday_time_in->ViewValue = FormatDateTime($this->tuesday_time_in->ViewValue, 4);
        $this->tuesday_time_in->ViewCustomAttributes = "";

        // tuesday_time_out
        $this->tuesday_time_out->ViewValue = $this->tuesday_time_out->CurrentValue;
        $this->tuesday_time_out->ViewValue = FormatDateTime($this->tuesday_time_out->ViewValue, 4);
        $this->tuesday_time_out->ViewCustomAttributes = "";

        // wednesday_time_in
        $this->wednesday_time_in->ViewValue = $this->wednesday_time_in->CurrentValue;
        $this->wednesday_time_in->ViewValue = FormatDateTime($this->wednesday_time_in->ViewValue, 4);
        $this->wednesday_time_in->ViewCustomAttributes = "";

        // wednesday_time_out
        $this->wednesday_time_out->ViewValue = $this->wednesday_time_out->CurrentValue;
        $this->wednesday_time_out->ViewValue = FormatDateTime($this->wednesday_time_out->ViewValue, 4);
        $this->wednesday_time_out->ViewCustomAttributes = "";

        // thursday_time_in
        $this->thursday_time_in->ViewValue = $this->thursday_time_in->CurrentValue;
        $this->thursday_time_in->ViewValue = FormatDateTime($this->thursday_time_in->ViewValue, 4);
        $this->thursday_time_in->ViewCustomAttributes = "";

        // thursday_time_out
        $this->thursday_time_out->ViewValue = $this->thursday_time_out->CurrentValue;
        $this->thursday_time_out->ViewValue = FormatDateTime($this->thursday_time_out->ViewValue, 4);
        $this->thursday_time_out->ViewCustomAttributes = "";

        // friday_time_in
        $this->friday_time_in->ViewValue = $this->friday_time_in->CurrentValue;
        $this->friday_time_in->ViewValue = FormatDateTime($this->friday_time_in->ViewValue, 4);
        $this->friday_time_in->ViewCustomAttributes = "";

        // friday_time_out
        $this->friday_time_out->ViewValue = $this->friday_time_out->CurrentValue;
        $this->friday_time_out->ViewValue = FormatDateTime($this->friday_time_out->ViewValue, 4);
        $this->friday_time_out->ViewCustomAttributes = "";

        // saturday_time_in
        $this->saturday_time_in->ViewValue = $this->saturday_time_in->CurrentValue;
        $this->saturday_time_in->ViewValue = FormatDateTime($this->saturday_time_in->ViewValue, 4);
        $this->saturday_time_in->ViewCustomAttributes = "";

        // saturday_time_out
        $this->saturday_time_out->ViewValue = $this->saturday_time_out->CurrentValue;
        $this->saturday_time_out->ViewValue = FormatDateTime($this->saturday_time_out->ViewValue, 4);
        $this->saturday_time_out->ViewCustomAttributes = "";

        // shift_id
        $this->shift_id->LinkCustomAttributes = "";
        $this->shift_id->HrefValue = "";
        $this->shift_id->TooltipValue = "";

        // shift_name
        $this->shift_name->LinkCustomAttributes = "";
        $this->shift_name->HrefValue = "";
        $this->shift_name->TooltipValue = "";

        // sunday_time_in
        $this->sunday_time_in->LinkCustomAttributes = "";
        $this->sunday_time_in->HrefValue = "";
        $this->sunday_time_in->TooltipValue = "";

        // sunday_time_out
        $this->sunday_time_out->LinkCustomAttributes = "";
        $this->sunday_time_out->HrefValue = "";
        $this->sunday_time_out->TooltipValue = "";

        // monday_time_in
        $this->monday_time_in->LinkCustomAttributes = "";
        $this->monday_time_in->HrefValue = "";
        $this->monday_time_in->TooltipValue = "";

        // monday_time_out
        $this->monday_time_out->LinkCustomAttributes = "";
        $this->monday_time_out->HrefValue = "";
        $this->monday_time_out->TooltipValue = "";

        // tuesday_time_in
        $this->tuesday_time_in->LinkCustomAttributes = "";
        $this->tuesday_time_in->HrefValue = "";
        $this->tuesday_time_in->TooltipValue = "";

        // tuesday_time_out
        $this->tuesday_time_out->LinkCustomAttributes = "";
        $this->tuesday_time_out->HrefValue = "";
        $this->tuesday_time_out->TooltipValue = "";

        // wednesday_time_in
        $this->wednesday_time_in->LinkCustomAttributes = "";
        $this->wednesday_time_in->HrefValue = "";
        $this->wednesday_time_in->TooltipValue = "";

        // wednesday_time_out
        $this->wednesday_time_out->LinkCustomAttributes = "";
        $this->wednesday_time_out->HrefValue = "";
        $this->wednesday_time_out->TooltipValue = "";

        // thursday_time_in
        $this->thursday_time_in->LinkCustomAttributes = "";
        $this->thursday_time_in->HrefValue = "";
        $this->thursday_time_in->TooltipValue = "";

        // thursday_time_out
        $this->thursday_time_out->LinkCustomAttributes = "";
        $this->thursday_time_out->HrefValue = "";
        $this->thursday_time_out->TooltipValue = "";

        // friday_time_in
        $this->friday_time_in->LinkCustomAttributes = "";
        $this->friday_time_in->HrefValue = "";
        $this->friday_time_in->TooltipValue = "";

        // friday_time_out
        $this->friday_time_out->LinkCustomAttributes = "";
        $this->friday_time_out->HrefValue = "";
        $this->friday_time_out->TooltipValue = "";

        // saturday_time_in
        $this->saturday_time_in->LinkCustomAttributes = "";
        $this->saturday_time_in->HrefValue = "";
        $this->saturday_time_in->TooltipValue = "";

        // saturday_time_out
        $this->saturday_time_out->LinkCustomAttributes = "";
        $this->saturday_time_out->HrefValue = "";
        $this->saturday_time_out->TooltipValue = "";

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

        // shift_id
        $this->shift_id->EditAttrs["class"] = "form-control";
        $this->shift_id->EditCustomAttributes = "";

        // shift_name
        $this->shift_name->EditAttrs["class"] = "form-control";
        $this->shift_name->EditCustomAttributes = "";
        if (!$this->shift_name->Raw) {
            $this->shift_name->CurrentValue = HtmlDecode($this->shift_name->CurrentValue);
        }
        $this->shift_name->EditValue = $this->shift_name->CurrentValue;
        $this->shift_name->PlaceHolder = RemoveHtml($this->shift_name->caption());

        // sunday_time_in
        $this->sunday_time_in->EditAttrs["class"] = "form-control";
        $this->sunday_time_in->EditCustomAttributes = "";
        $this->sunday_time_in->EditValue = $this->sunday_time_in->CurrentValue;
        $this->sunday_time_in->PlaceHolder = RemoveHtml($this->sunday_time_in->caption());

        // sunday_time_out
        $this->sunday_time_out->EditAttrs["class"] = "form-control";
        $this->sunday_time_out->EditCustomAttributes = "";
        $this->sunday_time_out->EditValue = $this->sunday_time_out->CurrentValue;
        $this->sunday_time_out->PlaceHolder = RemoveHtml($this->sunday_time_out->caption());

        // monday_time_in
        $this->monday_time_in->EditAttrs["class"] = "form-control";
        $this->monday_time_in->EditCustomAttributes = "";
        $this->monday_time_in->EditValue = $this->monday_time_in->CurrentValue;
        $this->monday_time_in->PlaceHolder = RemoveHtml($this->monday_time_in->caption());

        // monday_time_out
        $this->monday_time_out->EditAttrs["class"] = "form-control";
        $this->monday_time_out->EditCustomAttributes = "";
        $this->monday_time_out->EditValue = $this->monday_time_out->CurrentValue;
        $this->monday_time_out->PlaceHolder = RemoveHtml($this->monday_time_out->caption());

        // tuesday_time_in
        $this->tuesday_time_in->EditAttrs["class"] = "form-control";
        $this->tuesday_time_in->EditCustomAttributes = "";
        $this->tuesday_time_in->EditValue = $this->tuesday_time_in->CurrentValue;
        $this->tuesday_time_in->PlaceHolder = RemoveHtml($this->tuesday_time_in->caption());

        // tuesday_time_out
        $this->tuesday_time_out->EditAttrs["class"] = "form-control";
        $this->tuesday_time_out->EditCustomAttributes = "";
        $this->tuesday_time_out->EditValue = $this->tuesday_time_out->CurrentValue;
        $this->tuesday_time_out->PlaceHolder = RemoveHtml($this->tuesday_time_out->caption());

        // wednesday_time_in
        $this->wednesday_time_in->EditAttrs["class"] = "form-control";
        $this->wednesday_time_in->EditCustomAttributes = "";
        $this->wednesday_time_in->EditValue = $this->wednesday_time_in->CurrentValue;
        $this->wednesday_time_in->PlaceHolder = RemoveHtml($this->wednesday_time_in->caption());

        // wednesday_time_out
        $this->wednesday_time_out->EditAttrs["class"] = "form-control";
        $this->wednesday_time_out->EditCustomAttributes = "";
        $this->wednesday_time_out->EditValue = $this->wednesday_time_out->CurrentValue;
        $this->wednesday_time_out->PlaceHolder = RemoveHtml($this->wednesday_time_out->caption());

        // thursday_time_in
        $this->thursday_time_in->EditAttrs["class"] = "form-control";
        $this->thursday_time_in->EditCustomAttributes = "";
        $this->thursday_time_in->EditValue = $this->thursday_time_in->CurrentValue;
        $this->thursday_time_in->PlaceHolder = RemoveHtml($this->thursday_time_in->caption());

        // thursday_time_out
        $this->thursday_time_out->EditAttrs["class"] = "form-control";
        $this->thursday_time_out->EditCustomAttributes = "";
        $this->thursday_time_out->EditValue = $this->thursday_time_out->CurrentValue;
        $this->thursday_time_out->PlaceHolder = RemoveHtml($this->thursday_time_out->caption());

        // friday_time_in
        $this->friday_time_in->EditAttrs["class"] = "form-control";
        $this->friday_time_in->EditCustomAttributes = "";
        $this->friday_time_in->EditValue = $this->friday_time_in->CurrentValue;
        $this->friday_time_in->PlaceHolder = RemoveHtml($this->friday_time_in->caption());

        // friday_time_out
        $this->friday_time_out->EditAttrs["class"] = "form-control";
        $this->friday_time_out->EditCustomAttributes = "";
        $this->friday_time_out->EditValue = $this->friday_time_out->CurrentValue;
        $this->friday_time_out->PlaceHolder = RemoveHtml($this->friday_time_out->caption());

        // saturday_time_in
        $this->saturday_time_in->EditAttrs["class"] = "form-control";
        $this->saturday_time_in->EditCustomAttributes = "";
        $this->saturday_time_in->EditValue = $this->saturday_time_in->CurrentValue;
        $this->saturday_time_in->PlaceHolder = RemoveHtml($this->saturday_time_in->caption());

        // saturday_time_out
        $this->saturday_time_out->EditAttrs["class"] = "form-control";
        $this->saturday_time_out->EditCustomAttributes = "";
        $this->saturday_time_out->EditValue = $this->saturday_time_out->CurrentValue;
        $this->saturday_time_out->PlaceHolder = RemoveHtml($this->saturday_time_out->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
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
                    $doc->exportCaption($this->shift_name);
                    $doc->exportCaption($this->sunday_time_in);
                    $doc->exportCaption($this->sunday_time_out);
                    $doc->exportCaption($this->monday_time_in);
                    $doc->exportCaption($this->monday_time_out);
                    $doc->exportCaption($this->tuesday_time_in);
                    $doc->exportCaption($this->tuesday_time_out);
                    $doc->exportCaption($this->wednesday_time_in);
                    $doc->exportCaption($this->wednesday_time_out);
                    $doc->exportCaption($this->thursday_time_in);
                    $doc->exportCaption($this->thursday_time_out);
                    $doc->exportCaption($this->friday_time_in);
                    $doc->exportCaption($this->friday_time_out);
                    $doc->exportCaption($this->saturday_time_in);
                    $doc->exportCaption($this->saturday_time_out);
                } else {
                    $doc->exportCaption($this->shift_name);
                    $doc->exportCaption($this->sunday_time_in);
                    $doc->exportCaption($this->sunday_time_out);
                    $doc->exportCaption($this->monday_time_in);
                    $doc->exportCaption($this->monday_time_out);
                    $doc->exportCaption($this->tuesday_time_in);
                    $doc->exportCaption($this->tuesday_time_out);
                    $doc->exportCaption($this->wednesday_time_in);
                    $doc->exportCaption($this->wednesday_time_out);
                    $doc->exportCaption($this->thursday_time_in);
                    $doc->exportCaption($this->thursday_time_out);
                    $doc->exportCaption($this->friday_time_in);
                    $doc->exportCaption($this->friday_time_out);
                    $doc->exportCaption($this->saturday_time_in);
                    $doc->exportCaption($this->saturday_time_out);
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

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->shift_name);
                        $doc->exportField($this->sunday_time_in);
                        $doc->exportField($this->sunday_time_out);
                        $doc->exportField($this->monday_time_in);
                        $doc->exportField($this->monday_time_out);
                        $doc->exportField($this->tuesday_time_in);
                        $doc->exportField($this->tuesday_time_out);
                        $doc->exportField($this->wednesday_time_in);
                        $doc->exportField($this->wednesday_time_out);
                        $doc->exportField($this->thursday_time_in);
                        $doc->exportField($this->thursday_time_out);
                        $doc->exportField($this->friday_time_in);
                        $doc->exportField($this->friday_time_out);
                        $doc->exportField($this->saturday_time_in);
                        $doc->exportField($this->saturday_time_out);
                    } else {
                        $doc->exportField($this->shift_name);
                        $doc->exportField($this->sunday_time_in);
                        $doc->exportField($this->sunday_time_out);
                        $doc->exportField($this->monday_time_in);
                        $doc->exportField($this->monday_time_out);
                        $doc->exportField($this->tuesday_time_in);
                        $doc->exportField($this->tuesday_time_out);
                        $doc->exportField($this->wednesday_time_in);
                        $doc->exportField($this->wednesday_time_out);
                        $doc->exportField($this->thursday_time_in);
                        $doc->exportField($this->thursday_time_out);
                        $doc->exportField($this->friday_time_in);
                        $doc->exportField($this->friday_time_out);
                        $doc->exportField($this->saturday_time_in);
                        $doc->exportField($this->saturday_time_out);
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
