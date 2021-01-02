<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for mytraining
 */
class Mytraining extends DbTable
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
    public $training_id;
    public $employee_username;
    public $training_name;
    public $training_start;
    public $training_end;
    public $training_company;
    public $certificate_start;
    public $certificate_end;
    public $notes;
    public $training_document;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'mytraining';
        $this->TableName = 'mytraining';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`mytraining`";
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

        // training_id
        $this->training_id = new DbField('mytraining', 'mytraining', 'x_training_id', 'training_id', '`training_id`', '`training_id`', 3, 11, -1, false, '`training_id`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->training_id->IsAutoIncrement = true; // Autoincrement field
        $this->training_id->IsPrimaryKey = true; // Primary key field
        $this->training_id->Sortable = false; // Allow sort
        $this->training_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['training_id'] = &$this->training_id;

        // employee_username
        $this->employee_username = new DbField('mytraining', 'mytraining', 'x_employee_username', 'employee_username', '`employee_username`', '`employee_username`', 200, 100, -1, false, '`employee_username`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->employee_username->IsForeignKey = true; // Foreign key field
        $this->employee_username->Nullable = false; // NOT NULL field
        $this->employee_username->Sortable = false; // Allow sort
        $this->Fields['employee_username'] = &$this->employee_username;

        // training_name
        $this->training_name = new DbField('mytraining', 'mytraining', 'x_training_name', 'training_name', '`training_name`', '`training_name`', 200, 100, -1, false, '`training_name`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->training_name->Nullable = false; // NOT NULL field
        $this->training_name->Required = true; // Required field
        $this->training_name->Sortable = true; // Allow sort
        $this->Fields['training_name'] = &$this->training_name;

        // training_start
        $this->training_start = new DbField('mytraining', 'mytraining', 'x_training_start', 'training_start', '`training_start`', CastDateFieldForLike("`training_start`", 0, "DB"), 133, 10, 0, false, '`training_start`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->training_start->Sortable = true; // Allow sort
        $this->training_start->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['training_start'] = &$this->training_start;

        // training_end
        $this->training_end = new DbField('mytraining', 'mytraining', 'x_training_end', 'training_end', '`training_end`', CastDateFieldForLike("`training_end`", 0, "DB"), 133, 10, 0, false, '`training_end`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->training_end->Sortable = true; // Allow sort
        $this->training_end->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['training_end'] = &$this->training_end;

        // training_company
        $this->training_company = new DbField('mytraining', 'mytraining', 'x_training_company', 'training_company', '`training_company`', '`training_company`', 200, 100, -1, false, '`training_company`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->training_company->Sortable = true; // Allow sort
        $this->Fields['training_company'] = &$this->training_company;

        // certificate_start
        $this->certificate_start = new DbField('mytraining', 'mytraining', 'x_certificate_start', 'certificate_start', '`certificate_start`', CastDateFieldForLike("`certificate_start`", 0, "DB"), 133, 10, 0, false, '`certificate_start`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->certificate_start->Sortable = true; // Allow sort
        $this->certificate_start->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['certificate_start'] = &$this->certificate_start;

        // certificate_end
        $this->certificate_end = new DbField('mytraining', 'mytraining', 'x_certificate_end', 'certificate_end', '`certificate_end`', CastDateFieldForLike("`certificate_end`", 0, "DB"), 133, 10, 0, false, '`certificate_end`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->certificate_end->Sortable = true; // Allow sort
        $this->certificate_end->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['certificate_end'] = &$this->certificate_end;

        // notes
        $this->notes = new DbField('mytraining', 'mytraining', 'x_notes', 'notes', '`notes`', '`notes`', 200, 255, -1, false, '`notes`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->notes->Sortable = true; // Allow sort
        $this->Fields['notes'] = &$this->notes;

        // training_document
        $this->training_document = new DbField('mytraining', 'mytraining', 'x_training_document', 'training_document', '`training_document`', '`training_document`', 200, 255, -1, true, '`training_document`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->training_document->Sortable = true; // Allow sort
        $this->Fields['training_document'] = &$this->training_document;
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`mytraining`";
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
            $this->training_id->setDbValue($conn->lastInsertId());
            $rs['training_id'] = $this->training_id->DbValue;
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
            if (array_key_exists('training_id', $rs)) {
                AddFilter($where, QuotedName('training_id', $this->Dbid) . '=' . QuotedValue($rs['training_id'], $this->training_id->DataType, $this->Dbid));
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
        $this->training_id->DbValue = $row['training_id'];
        $this->employee_username->DbValue = $row['employee_username'];
        $this->training_name->DbValue = $row['training_name'];
        $this->training_start->DbValue = $row['training_start'];
        $this->training_end->DbValue = $row['training_end'];
        $this->training_company->DbValue = $row['training_company'];
        $this->certificate_start->DbValue = $row['certificate_start'];
        $this->certificate_end->DbValue = $row['certificate_end'];
        $this->notes->DbValue = $row['notes'];
        $this->training_document->Upload->DbValue = $row['training_document'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['training_document']) ? [] : [$row['training_document']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->training_document->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->training_document->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`training_id` = @training_id@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->training_id->CurrentValue : $this->training_id->OldValue;
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
                $this->training_id->CurrentValue = $keys[0];
            } else {
                $this->training_id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('training_id', $row) ? $row['training_id'] : null;
        } else {
            $val = $this->training_id->OldValue !== null ? $this->training_id->OldValue : $this->training_id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@training_id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("mytraininglist");
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
        if ($pageName == "mytrainingview") {
            return $Language->phrase("View");
        } elseif ($pageName == "mytrainingedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "mytrainingadd") {
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
                return "MytrainingView";
            case Config("API_ADD_ACTION"):
                return "MytrainingAdd";
            case Config("API_EDIT_ACTION"):
                return "MytrainingEdit";
            case Config("API_DELETE_ACTION"):
                return "MytrainingDelete";
            case Config("API_LIST_ACTION"):
                return "MytrainingList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "mytraininglist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("mytrainingview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("mytrainingview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "mytrainingadd?" . $this->getUrlParm($parm);
        } else {
            $url = "mytrainingadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("mytrainingedit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("mytrainingadd", $this->getUrlParm($parm));
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
        return $this->keyUrl("mytrainingdelete", $this->getUrlParm());
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
        $json .= "training_id:" . JsonEncode($this->training_id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->training_id->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->training_id->CurrentValue);
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
            if (($keyValue = Param("training_id") ?? Route("training_id")) !== null) {
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
                $this->training_id->CurrentValue = $key;
            } else {
                $this->training_id->OldValue = $key;
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
        $this->training_id->setDbValue($row['training_id']);
        $this->employee_username->setDbValue($row['employee_username']);
        $this->training_name->setDbValue($row['training_name']);
        $this->training_start->setDbValue($row['training_start']);
        $this->training_end->setDbValue($row['training_end']);
        $this->training_company->setDbValue($row['training_company']);
        $this->certificate_start->setDbValue($row['certificate_start']);
        $this->certificate_end->setDbValue($row['certificate_end']);
        $this->notes->setDbValue($row['notes']);
        $this->training_document->Upload->DbValue = $row['training_document'];
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // training_id
        $this->training_id->CellCssStyle = "white-space: nowrap;";

        // employee_username
        $this->employee_username->CellCssStyle = "white-space: nowrap;";

        // training_name

        // training_start

        // training_end

        // training_company

        // certificate_start

        // certificate_end

        // notes

        // training_document

        // training_id
        $this->training_id->ViewValue = $this->training_id->CurrentValue;
        $this->training_id->ViewCustomAttributes = "";

        // employee_username
        $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
        $this->employee_username->ViewCustomAttributes = "";

        // training_name
        $this->training_name->ViewValue = $this->training_name->CurrentValue;
        $this->training_name->ViewCustomAttributes = "";

        // training_start
        $this->training_start->ViewValue = $this->training_start->CurrentValue;
        $this->training_start->ViewValue = FormatDateTime($this->training_start->ViewValue, 0);
        $this->training_start->ViewCustomAttributes = "";

        // training_end
        $this->training_end->ViewValue = $this->training_end->CurrentValue;
        $this->training_end->ViewValue = FormatDateTime($this->training_end->ViewValue, 0);
        $this->training_end->ViewCustomAttributes = "";

        // training_company
        $this->training_company->ViewValue = $this->training_company->CurrentValue;
        $this->training_company->ViewCustomAttributes = "";

        // certificate_start
        $this->certificate_start->ViewValue = $this->certificate_start->CurrentValue;
        $this->certificate_start->ViewValue = FormatDateTime($this->certificate_start->ViewValue, 0);
        $this->certificate_start->ViewCustomAttributes = "";

        // certificate_end
        $this->certificate_end->ViewValue = $this->certificate_end->CurrentValue;
        $this->certificate_end->ViewValue = FormatDateTime($this->certificate_end->ViewValue, 0);
        $this->certificate_end->ViewCustomAttributes = "";

        // notes
        $this->notes->ViewValue = $this->notes->CurrentValue;
        $this->notes->ViewCustomAttributes = "";

        // training_document
        if (!EmptyValue($this->training_document->Upload->DbValue)) {
            $this->training_document->ViewValue = $this->training_document->Upload->DbValue;
        } else {
            $this->training_document->ViewValue = "";
        }
        $this->training_document->ViewCustomAttributes = "";

        // training_id
        $this->training_id->LinkCustomAttributes = "";
        $this->training_id->HrefValue = "";
        $this->training_id->TooltipValue = "";

        // employee_username
        $this->employee_username->LinkCustomAttributes = "";
        $this->employee_username->HrefValue = "";
        $this->employee_username->TooltipValue = "";

        // training_name
        $this->training_name->LinkCustomAttributes = "";
        $this->training_name->HrefValue = "";
        $this->training_name->TooltipValue = "";

        // training_start
        $this->training_start->LinkCustomAttributes = "";
        $this->training_start->HrefValue = "";
        $this->training_start->TooltipValue = "";

        // training_end
        $this->training_end->LinkCustomAttributes = "";
        $this->training_end->HrefValue = "";
        $this->training_end->TooltipValue = "";

        // training_company
        $this->training_company->LinkCustomAttributes = "";
        $this->training_company->HrefValue = "";
        $this->training_company->TooltipValue = "";

        // certificate_start
        $this->certificate_start->LinkCustomAttributes = "";
        $this->certificate_start->HrefValue = "";
        $this->certificate_start->TooltipValue = "";

        // certificate_end
        $this->certificate_end->LinkCustomAttributes = "";
        $this->certificate_end->HrefValue = "";
        $this->certificate_end->TooltipValue = "";

        // notes
        $this->notes->LinkCustomAttributes = "";
        $this->notes->HrefValue = "";
        $this->notes->TooltipValue = "";

        // training_document
        $this->training_document->LinkCustomAttributes = "";
        if (!EmptyValue($this->training_document->Upload->DbValue)) {
            $this->training_document->HrefValue = GetFileUploadUrl($this->training_document, $this->training_document->htmlDecode($this->training_document->Upload->DbValue)); // Add prefix/suffix
            $this->training_document->LinkAttrs["target"] = ""; // Add target
            if ($this->isExport()) {
                $this->training_document->HrefValue = FullUrl($this->training_document->HrefValue, "href");
            }
        } else {
            $this->training_document->HrefValue = "";
        }
        $this->training_document->ExportHrefValue = $this->training_document->UploadPath . $this->training_document->Upload->DbValue;
        $this->training_document->TooltipValue = "";

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

        // training_id
        $this->training_id->EditAttrs["class"] = "form-control";
        $this->training_id->EditCustomAttributes = "";

        // employee_username
        $this->employee_username->EditAttrs["class"] = "form-control";
        $this->employee_username->EditCustomAttributes = "";
        if ($this->employee_username->getSessionValue() != "") {
            $this->employee_username->CurrentValue = GetForeignKeyValue($this->employee_username->getSessionValue());
            $this->employee_username->ViewValue = $this->employee_username->CurrentValue;
            $this->employee_username->ViewCustomAttributes = "";
        } else {
        }

        // training_name
        $this->training_name->EditAttrs["class"] = "form-control";
        $this->training_name->EditCustomAttributes = "";
        if (!$this->training_name->Raw) {
            $this->training_name->CurrentValue = HtmlDecode($this->training_name->CurrentValue);
        }
        $this->training_name->EditValue = $this->training_name->CurrentValue;
        $this->training_name->PlaceHolder = RemoveHtml($this->training_name->caption());

        // training_start
        $this->training_start->EditAttrs["class"] = "form-control";
        $this->training_start->EditCustomAttributes = "";
        $this->training_start->EditValue = FormatDateTime($this->training_start->CurrentValue, 8);
        $this->training_start->PlaceHolder = RemoveHtml($this->training_start->caption());

        // training_end
        $this->training_end->EditAttrs["class"] = "form-control";
        $this->training_end->EditCustomAttributes = "";
        $this->training_end->EditValue = FormatDateTime($this->training_end->CurrentValue, 8);
        $this->training_end->PlaceHolder = RemoveHtml($this->training_end->caption());

        // training_company
        $this->training_company->EditAttrs["class"] = "form-control";
        $this->training_company->EditCustomAttributes = "";
        if (!$this->training_company->Raw) {
            $this->training_company->CurrentValue = HtmlDecode($this->training_company->CurrentValue);
        }
        $this->training_company->EditValue = $this->training_company->CurrentValue;
        $this->training_company->PlaceHolder = RemoveHtml($this->training_company->caption());

        // certificate_start
        $this->certificate_start->EditAttrs["class"] = "form-control";
        $this->certificate_start->EditCustomAttributes = "";
        $this->certificate_start->EditValue = FormatDateTime($this->certificate_start->CurrentValue, 8);
        $this->certificate_start->PlaceHolder = RemoveHtml($this->certificate_start->caption());

        // certificate_end
        $this->certificate_end->EditAttrs["class"] = "form-control";
        $this->certificate_end->EditCustomAttributes = "";
        $this->certificate_end->EditValue = FormatDateTime($this->certificate_end->CurrentValue, 8);
        $this->certificate_end->PlaceHolder = RemoveHtml($this->certificate_end->caption());

        // notes
        $this->notes->EditAttrs["class"] = "form-control";
        $this->notes->EditCustomAttributes = "";
        $this->notes->EditValue = $this->notes->CurrentValue;
        $this->notes->PlaceHolder = RemoveHtml($this->notes->caption());

        // training_document
        $this->training_document->EditAttrs["class"] = "form-control";
        $this->training_document->EditCustomAttributes = "";
        if (!EmptyValue($this->training_document->Upload->DbValue)) {
            $this->training_document->EditValue = $this->training_document->Upload->DbValue;
        } else {
            $this->training_document->EditValue = "";
        }
        if (!EmptyValue($this->training_document->CurrentValue)) {
            $this->training_document->Upload->FileName = $this->training_document->CurrentValue;
        }

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
                    $doc->exportCaption($this->training_name);
                    $doc->exportCaption($this->training_start);
                    $doc->exportCaption($this->training_end);
                    $doc->exportCaption($this->training_company);
                    $doc->exportCaption($this->certificate_start);
                    $doc->exportCaption($this->certificate_end);
                    $doc->exportCaption($this->notes);
                    $doc->exportCaption($this->training_document);
                } else {
                    $doc->exportCaption($this->training_name);
                    $doc->exportCaption($this->training_start);
                    $doc->exportCaption($this->training_end);
                    $doc->exportCaption($this->training_company);
                    $doc->exportCaption($this->certificate_start);
                    $doc->exportCaption($this->certificate_end);
                    $doc->exportCaption($this->training_document);
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
                        $doc->exportField($this->training_name);
                        $doc->exportField($this->training_start);
                        $doc->exportField($this->training_end);
                        $doc->exportField($this->training_company);
                        $doc->exportField($this->certificate_start);
                        $doc->exportField($this->certificate_end);
                        $doc->exportField($this->notes);
                        $doc->exportField($this->training_document);
                    } else {
                        $doc->exportField($this->training_name);
                        $doc->exportField($this->training_start);
                        $doc->exportField($this->training_end);
                        $doc->exportField($this->training_company);
                        $doc->exportField($this->certificate_start);
                        $doc->exportField($this->certificate_end);
                        $doc->exportField($this->training_document);
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
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'training_document') {
            $fldName = "training_document";
            $fileNameFld = "training_document";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->training_id->CurrentValue = $ar[0];
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
