<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for Top 10 Days
 */
class Top10Days extends ReportTable
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
    public $ShowGroupHeaderAsRow = false;
    public $ShowCompactSummaryFooter = false;

    // Export
    public $ExportDoc;
    public $Top_10_Days;

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
        $this->TableVar = 'Top_10_Days';
        $this->TableName = 'Top 10 Days';
        $this->TableType = 'REPORT';

        // Update Table
        $this->UpdateTable = "`employee_timesheet`";
        $this->ReportSourceTable = 'employee_timesheet'; // Report source table
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (report only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions

        // timesheet_id
        $this->timesheet_id = new ReportField('Top_10_Days', 'Top 10 Days', 'x_timesheet_id', 'timesheet_id', '`timesheet_id`', '`timesheet_id`', 3, 11, -1, false, '`timesheet_id`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->timesheet_id->IsAutoIncrement = true; // Autoincrement field
        $this->timesheet_id->IsPrimaryKey = true; // Primary key field
        $this->timesheet_id->Sortable = false; // Allow sort
        $this->timesheet_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->timesheet_id->SourceTableVar = 'employee_timesheet';
        $this->Fields['timesheet_id'] = &$this->timesheet_id;

        // employee_username
        $this->employee_username = new ReportField('Top_10_Days', 'Top 10 Days', 'x_employee_username', 'employee_username', '`employee_username`', '`employee_username`', 200, 50, -1, false, '`employee_username`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->employee_username->Nullable = false; // NOT NULL field
        $this->employee_username->Required = true; // Required field
        $this->employee_username->Sortable = true; // Allow sort
        $this->employee_username->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->employee_username->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->employee_username->Lookup = new Lookup('employee_username', 'employee', false, 'employee_username', ["employee_name","","",""], [], [], [], [], [], [], '', '');
        $this->employee_username->SourceTableVar = 'employee_timesheet';
        $this->Fields['employee_username'] = &$this->employee_username;

        // year
        $this->year = new ReportField('Top_10_Days', 'Top 10 Days', 'x_year', 'year', '`year`', '`year`', 3, 11, -1, false, '`year`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->year->Nullable = false; // NOT NULL field
        $this->year->Required = true; // Required field
        $this->year->Sortable = true; // Allow sort
        $this->year->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->year->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->year->Lookup = new Lookup('year', 'Top_10_Days', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->year->OptionCount = 17;
        $this->year->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->year->AdvancedSearch->SearchValueDefault = Date('Y', strtotime('-1 months'));;
        $this->year->AdvancedSearch->SearchOperatorDefault = "=";
        $this->year->AdvancedSearch->SearchOperatorDefault2 = "";
        $this->year->AdvancedSearch->SearchConditionDefault = "AND";
        $this->year->SourceTableVar = 'employee_timesheet';
        $this->Fields['year'] = &$this->year;

        // month
        $this->month = new ReportField('Top_10_Days', 'Top 10 Days', 'x_month', 'month', '`month`', '`month`', 3, 11, -1, false, '`month`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->month->Nullable = false; // NOT NULL field
        $this->month->Required = true; // Required field
        $this->month->Sortable = true; // Allow sort
        $this->month->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->month->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->month->Lookup = new Lookup('month', 'Top_10_Days', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->month->OptionCount = 12;
        $this->month->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->month->SourceTableVar = 'employee_timesheet';
        $this->Fields['month'] = &$this->month;

        // days
        $this->days = new ReportField('Top_10_Days', 'Top 10 Days', 'x_days', 'days', '`days`', '`days`', 3, 11, -1, false, '`days`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->days->Sortable = true; // Allow sort
        $this->days->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->days->SourceTableVar = 'employee_timesheet';
        $this->Fields['days'] = &$this->days;

        // sick
        $this->sick = new ReportField('Top_10_Days', 'Top 10 Days', 'x_sick', 'sick', '`sick`', '`sick`', 3, 11, -1, false, '`sick`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->sick->Sortable = true; // Allow sort
        $this->sick->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->sick->SourceTableVar = 'employee_timesheet';
        $this->Fields['sick'] = &$this->sick;

        // leave
        $this->leave = new ReportField('Top_10_Days', 'Top 10 Days', 'x_leave', 'leave', '`leave`', '`leave`', 3, 11, -1, false, '`leave`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->leave->Sortable = true; // Allow sort
        $this->leave->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->leave->SourceTableVar = 'employee_timesheet';
        $this->Fields['leave'] = &$this->leave;

        // permit
        $this->permit = new ReportField('Top_10_Days', 'Top 10 Days', 'x_permit', 'permit', '`permit`', '`permit`', 3, 11, -1, false, '`permit`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->permit->Sortable = true; // Allow sort
        $this->permit->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->permit->SourceTableVar = 'employee_timesheet';
        $this->Fields['permit'] = &$this->permit;

        // absence
        $this->absence = new ReportField('Top_10_Days', 'Top 10 Days', 'x_absence', 'absence', '`absence`', '`absence`', 3, 11, -1, false, '`absence`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->absence->Sortable = true; // Allow sort
        $this->absence->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->absence->SourceTableVar = 'employee_timesheet';
        $this->Fields['absence'] = &$this->absence;

        // timesheet_doc
        $this->timesheet_doc = new ReportField('Top_10_Days', 'Top 10 Days', 'x_timesheet_doc', 'timesheet_doc', '`timesheet_doc`', '`timesheet_doc`', 200, 150, -1, true, '`timesheet_doc`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->timesheet_doc->Sortable = true; // Allow sort
        $this->timesheet_doc->SourceTableVar = 'employee_timesheet';
        $this->Fields['timesheet_doc'] = &$this->timesheet_doc;

        // employee_notes
        $this->employee_notes = new ReportField('Top_10_Days', 'Top 10 Days', 'x_employee_notes', 'employee_notes', '`employee_notes`', '`employee_notes`', 201, 65535, -1, false, '`employee_notes`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->employee_notes->Sortable = true; // Allow sort
        $this->employee_notes->SourceTableVar = 'employee_timesheet';
        $this->Fields['employee_notes'] = &$this->employee_notes;

        // company_notes
        $this->company_notes = new ReportField('Top_10_Days', 'Top 10 Days', 'x_company_notes', 'company_notes', '`company_notes`', '`company_notes`', 201, 65535, -1, false, '`company_notes`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->company_notes->Sortable = true; // Allow sort
        $this->company_notes->SourceTableVar = 'employee_timesheet';
        $this->Fields['company_notes'] = &$this->company_notes;

        // approved
        $this->approved = new ReportField('Top_10_Days', 'Top 10 Days', 'x_approved', 'approved', '`approved`', '`approved`', 200, 50, -1, false, '`approved`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->approved->Sortable = true; // Allow sort
        $this->approved->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->approved->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->approved->Lookup = new Lookup('approved', 'Top_10_Days', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->approved->OptionCount = 3;
        $this->approved->SourceTableVar = 'employee_timesheet';
        $this->Fields['approved'] = &$this->approved;

        // Top 10 Days
        $this->Top_10_Days = new DbChart($this, 'Top_10_Days', 'Top 10 Days', 'employee_username', 'days', 1001, '', 0, 'SUM', 600, 500);
        $this->Top_10_Days->SortType = 4;
        $this->Top_10_Days->SortSequence = "";
        $this->Top_10_Days->SqlSelect = $this->getQueryBuilder()->select("`employee_username`", "''", "SUM(`days`)");
        $this->Top_10_Days->SqlGroupBy = "`employee_username`";
        $this->Top_10_Days->SqlOrderBy = "";
        $this->Top_10_Days->SeriesDateType = "";
        $this->Top_10_Days->ID = "Top_10_Days_Top_10_Days"; // Chart ID
        $this->Top_10_Days->setParameters([
            ["type", "1001"],
            ["seriestype", "0"]
        ]); // Chart type / Chart series type
        $this->Top_10_Days->setParameters([
            ["caption", $this->Top_10_Days->caption()],
            ["xaxisname", $this->Top_10_Days->xAxisName()]
        ]); // Chart caption / X axis name
        $this->Top_10_Days->setParameter("yaxisname", $this->Top_10_Days->yAxisName()); // Y axis name
        $this->Top_10_Days->setParameters([
            ["shownames", "1"],
            ["showvalues", "1"],
            ["showhovercap", "1"]
        ]); // Show names / Show values / Show hover
        $this->Top_10_Days->setParameter("alpha", "50"); // Chart alpha
        $this->Top_10_Days->setParameter("colorpalette", "#5899DA,#E8743B,#19A979,#ED4A7B,#945ECF,#13A4B4,#525DF4,#BF399E,#6C8893,#EE6868,#2F6497"); // Chart color palette
        $this->Top_10_Days->setParameters([["options.legend.display",false],["options.legend.fullWidth",false],["options.legend.reverse",false],["options.legend.labels.usePointStyle",false],["options.title.display",false],["options.tooltips.enabled",false],["options.tooltips.intersect",false],["options.tooltips.displayColors",false],["options.plugins.filler.propagate",false],["options.animation.animateRotate",false],["options.animation.animateScale",false],["dataset.showLine",false],["dataset.spanGaps",false],["dataset.steppedLine",false],["scale.gridLines.offsetGridLines",false],["annotation1.show",false],["annotation1.secondaryYAxis",false],["annotation2.show",false],["annotation2.secondaryYAxis",false],["annotation3.show",false],["annotation3.secondaryYAxis",false],["annotation4.show",false],["annotation4.secondaryYAxis",false]]);
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Single column sort
    protected function updateSort(&$fld)
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
            $lastOrderBy = in_array($lastSort, ["ASC", "DESC"]) ? $sortField . " " . $lastSort : "";
            $curOrderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            if ($fld->GroupingFieldId == 0) {
                $this->setDetailOrderBy($curOrderBy); // Save to Session
            }
        } else {
            if ($fld->GroupingFieldId == 0) {
                $fld->setSort("");
            }
        }
    }

    // Get Sort SQL
    protected function sortSql()
    {
        $dtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
        $argrps = [];
        foreach ($this->Fields as $fld) {
            if (in_array($fld->getSort(), ["ASC", "DESC"])) {
                $fldsql = $fld->Expression;
                if ($fld->GroupingFieldId > 0) {
                    if ($fld->GroupSql != "") {
                        $argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->GroupSql) . " " . $fld->getSort();
                    } else {
                        $argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
                    }
                }
            }
        }
        $sortSql = "";
        foreach ($argrps as $grp) {
            if ($sortSql != "") {
                $sortSql .= ", ";
            }
            $sortSql .= $grp;
        }
        if ($dtlSortSql != "") {
            if ($sortSql != "") {
                $sortSql .= ", ";
            }
            $sortSql .= $dtlSortSql;
        }
        return $sortSql;
    }

    // Summary properties
    private $sqlSelectAggregate = null;
    private $sqlAggregatePrefix = "";
    private $sqlAggregateSuffix = "";
    private $sqlSelectCount = null;

    // Select Aggregate
    public function getSqlSelectAggregate()
    {
        return $this->sqlSelectAggregate ?? $this->getQueryBuilder()->select("COUNT(*) AS `cnt_employee_username`, SUM(`days`) AS `sum_days`");
    }

    public function setSqlSelectAggregate($v)
    {
        $this->sqlSelectAggregate = $v;
    }

    // Aggregate Prefix
    public function getSqlAggregatePrefix()
    {
        return ($this->sqlAggregatePrefix != "") ? $this->sqlAggregatePrefix : "";
    }

    public function setSqlAggregatePrefix($v)
    {
        $this->sqlAggregatePrefix = $v;
    }

    // Aggregate Suffix
    public function getSqlAggregateSuffix()
    {
        return ($this->sqlAggregateSuffix != "") ? $this->sqlAggregateSuffix : "";
    }

    public function setSqlAggregateSuffix($v)
    {
        $this->sqlAggregateSuffix = $v;
    }

    // Select Count
    public function getSqlSelectCount()
    {
        return $this->sqlSelectCount ?? $this->getQueryBuilder()->select("COUNT(*)");
    }

    public function setSqlSelectCount($v)
    {
        $this->sqlSelectCount = $v;
    }

    // Render for lookup
    public function renderLookup()
    {
        $this->year->ViewValue = GetDropDownDisplayValue($this->year->CurrentValue, "", 0);
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`employee_timesheet`";
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
        if ($this->SqlSelect) {
            return $this->SqlSelect;
        }
        $select = $this->getQueryBuilder()->select("*");
        return $select;
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
        return $_SESSION[$name] ?? GetUrl("");
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
        if ($pageName == "") {
            return $Language->phrase("View");
        } elseif ($pageName == "") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "") {
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
                return "";
            case Config("API_ADD_ACTION"):
                return "";
            case Config("API_EDIT_ACTION"):
                return "";
            case Config("API_DELETE_ACTION"):
                return "";
            case Config("API_LIST_ACTION"):
                return "";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "?" . $this->getUrlParm($parm);
        } else {
            $url = "";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("", $this->getUrlParm($parm));
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
        return $this->keyUrl("", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
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
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            $this->DrillDown || $DashboardReport ||
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
        $sql = $this->buildReportSql($this->getSqlSelect(), $this->getSqlFrom(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $filter, "");
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
