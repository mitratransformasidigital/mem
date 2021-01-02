<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for print_quotation
 */
class PrintQuotation extends ReportTable
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
    public $ShowCompactSummaryFooter = true;

    // Export
    public $ExportDoc;

    // Fields
    public $quotation_id;
    public $quotation_no;
    public $quotation_date;
    public $customer_name;
    public $phone_number;
    public $contact;
    public $city;
    public $employee_name;
    public $rate;
    public $qty;
    public $total;
    public $customer_address;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'print_quotation';
        $this->TableName = 'print_quotation';
        $this->TableType = 'REPORT';

        // Update Table
        $this->UpdateTable = "`quotation_list`";
        $this->ReportSourceTable = 'quotation_list'; // Report source table
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

        // quotation_id
        $this->quotation_id = new ReportField('print_quotation', 'print_quotation', 'x_quotation_id', 'quotation_id', 'employee_quotation.quotation_id', 'employee_quotation.quotation_id', 3, 11, -1, false, 'employee_quotation.quotation_id', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->quotation_id->GroupingFieldId = 1;
        $this->quotation_id->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
        $this->quotation_id->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
        $this->quotation_id->GroupByType = "";
        $this->quotation_id->GroupInterval = "0";
        $this->quotation_id->GroupSql = "";
        $this->quotation_id->IsAutoIncrement = true; // Autoincrement field
        $this->quotation_id->Sortable = false; // Allow sort
        $this->quotation_id->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->quotation_id->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->quotation_id->Lookup = new Lookup('quotation_id', 'employee_quotation', false, 'quotation_id', ["quotation_no","","",""], [], [], [], [], [], [], '', '');
        $this->quotation_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->quotation_id->AdvancedSearch->SearchValueDefault = INIT_VALUE;
        $this->quotation_id->SourceTableVar = 'quotation_list';
        $this->Fields['quotation_id'] = &$this->quotation_id;

        // quotation_no
        $this->quotation_no = new ReportField('print_quotation', 'print_quotation', 'x_quotation_no', 'quotation_no', 'employee_quotation.quotation_no', 'employee_quotation.quotation_no', 200, 50, -1, false, 'employee_quotation.quotation_no', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->quotation_no->Nullable = false; // NOT NULL field
        $this->quotation_no->Required = true; // Required field
        $this->quotation_no->Sortable = true; // Allow sort
        $this->quotation_no->SourceTableVar = 'quotation_list';
        $this->Fields['quotation_no'] = &$this->quotation_no;

        // quotation_date
        $this->quotation_date = new ReportField('print_quotation', 'print_quotation', 'x_quotation_date', 'quotation_date', 'Date_Format(employee_quotation.quotation_date, \'%d %b %Y\')', 'Date_Format(employee_quotation.quotation_date, \'%d %b %Y\')', 200, 40, -1, false, 'Date_Format(employee_quotation.quotation_date, \'%d %b %Y\')', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->quotation_date->GroupingFieldId = 2;
        $this->quotation_date->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
        $this->quotation_date->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
        $this->quotation_date->GroupByType = "";
        $this->quotation_date->GroupInterval = "0";
        $this->quotation_date->GroupSql = "";
        $this->quotation_date->Required = true; // Required field
        $this->quotation_date->Sortable = true; // Allow sort
        $this->quotation_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateDMY"));
        $this->quotation_date->SourceTableVar = 'quotation_list';
        $this->Fields['quotation_date'] = &$this->quotation_date;

        // customer_name
        $this->customer_name = new ReportField('print_quotation', 'print_quotation', 'x_customer_name', 'customer_name', 'customer.customer_name', 'customer.customer_name', 200, 100, -1, false, 'customer.customer_name', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->customer_name->Nullable = false; // NOT NULL field
        $this->customer_name->Required = true; // Required field
        $this->customer_name->Sortable = true; // Allow sort
        $this->customer_name->SourceTableVar = 'quotation_list';
        $this->Fields['customer_name'] = &$this->customer_name;

        // phone_number
        $this->phone_number = new ReportField('print_quotation', 'print_quotation', 'x_phone_number', 'phone_number', 'customer.phone_number', 'customer.phone_number', 200, 50, -1, false, 'customer.phone_number', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->phone_number->Nullable = false; // NOT NULL field
        $this->phone_number->Required = true; // Required field
        $this->phone_number->Sortable = true; // Allow sort
        $this->phone_number->SourceTableVar = 'quotation_list';
        $this->Fields['phone_number'] = &$this->phone_number;

        // contact
        $this->contact = new ReportField('print_quotation', 'print_quotation', 'x_contact', 'contact', 'customer.contact', 'customer.contact', 200, 100, -1, false, 'customer.contact', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->contact->Nullable = false; // NOT NULL field
        $this->contact->Required = true; // Required field
        $this->contact->Sortable = true; // Allow sort
        $this->contact->SourceTableVar = 'quotation_list';
        $this->Fields['contact'] = &$this->contact;

        // city
        $this->city = new ReportField('print_quotation', 'print_quotation', 'x_city', 'city', 'master_city.city', 'master_city.city', 200, 50, -1, false, 'master_city.city', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->city->Nullable = false; // NOT NULL field
        $this->city->Required = true; // Required field
        $this->city->Sortable = true; // Allow sort
        $this->city->SourceTableVar = 'quotation_list';
        $this->Fields['city'] = &$this->city;

        // employee_name
        $this->employee_name = new ReportField('print_quotation', 'print_quotation', 'x_employee_name', 'employee_name', 'employee.employee_name', 'employee.employee_name', 200, 150, -1, false, 'employee.employee_name', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->employee_name->Nullable = false; // NOT NULL field
        $this->employee_name->Required = true; // Required field
        $this->employee_name->Sortable = true; // Allow sort
        $this->employee_name->SourceTableVar = 'quotation_list';
        $this->Fields['employee_name'] = &$this->employee_name;

        // rate
        $this->rate = new ReportField('print_quotation', 'print_quotation', 'x_rate', 'rate', 'employee_quotation_detail.rate', 'employee_quotation_detail.rate', 3, 11, -1, false, 'employee_quotation_detail.rate', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->rate->Nullable = false; // NOT NULL field
        $this->rate->Required = true; // Required field
        $this->rate->Sortable = true; // Allow sort
        $this->rate->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->rate->SourceTableVar = 'quotation_list';
        $this->Fields['rate'] = &$this->rate;

        // qty
        $this->qty = new ReportField('print_quotation', 'print_quotation', 'x_qty', 'qty', 'employee_quotation_detail.qty', 'employee_quotation_detail.qty', 3, 11, -1, false, 'employee_quotation_detail.qty', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->qty->Nullable = false; // NOT NULL field
        $this->qty->Required = true; // Required field
        $this->qty->Sortable = true; // Allow sort
        $this->qty->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->qty->SourceTableVar = 'quotation_list';
        $this->Fields['qty'] = &$this->qty;

        // total
        $this->total = new ReportField('print_quotation', 'print_quotation', 'x_total', 'total', 'employee_quotation_detail.rate * employee_quotation_detail.qty', 'employee_quotation_detail.rate * employee_quotation_detail.qty', 20, 21, -1, false, 'employee_quotation_detail.rate * employee_quotation_detail.qty', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->total->Nullable = false; // NOT NULL field
        $this->total->Required = true; // Required field
        $this->total->Sortable = true; // Allow sort
        $this->total->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->total->SourceTableVar = 'quotation_list';
        $this->Fields['total'] = &$this->total;

        // customer_address
        $this->customer_address = new ReportField('print_quotation', 'print_quotation', 'x_customer_address', 'customer_address', 'customer.address', 'customer.address', 201, 300, -1, false, 'customer.address', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->customer_address->Nullable = false; // NOT NULL field
        $this->customer_address->Required = true; // Required field
        $this->customer_address->Sortable = true; // Allow sort
        $this->customer_address->SourceTableVar = 'quotation_list';
        $this->Fields['customer_address'] = &$this->customer_address;
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

    // Table Level Group SQL
    private $sqlFirstGroupField = "";
    private $sqlSelectGroup = null;
    private $sqlOrderByGroup = "";

    // First Group Field
    public function getSqlFirstGroupField($alias = false)
    {
        if ($this->sqlFirstGroupField != "") {
            return $this->sqlFirstGroupField;
        }
        $firstGroupField = &$this->quotation_id;
        $expr = $firstGroupField->Expression;
        if ($firstGroupField->GroupSql != "") {
            $expr = str_replace("%s", $firstGroupField->Expression, $firstGroupField->GroupSql);
            if ($alias) {
                $expr .= " AS " . QuotedName($firstGroupField->getGroupName(), $this->Dbid);
            }
        }
        return $expr;
    }

    public function setSqlFirstGroupField($v)
    {
        $this->sqlFirstGroupField = $v;
    }

    // Select Group
    public function getSqlSelectGroup()
    {
        return $this->sqlSelectGroup ?? $this->getQueryBuilder()->select($this->getSqlFirstGroupField(true))->distinct();
    }

    public function setSqlSelectGroup($v)
    {
        $this->sqlSelectGroup = $v;
    }

    // Order By Group
    public function getSqlOrderByGroup()
    {
        if ($this->sqlOrderByGroup != "") {
            return $this->sqlOrderByGroup;
        }
        return $this->getSqlFirstGroupField() . " ASC";
    }

    public function setSqlOrderByGroup($v)
    {
        $this->sqlOrderByGroup = $v;
    }

    // Summary properties
    private $sqlSelectAggregate = null;
    private $sqlAggregatePrefix = "";
    private $sqlAggregateSuffix = "";
    private $sqlSelectCount = null;

    // Select Aggregate
    public function getSqlSelectAggregate()
    {
        return $this->sqlSelectAggregate ?? $this->getQueryBuilder()->select("COUNT(*) AS `cnt_employee_name`, SUM(employee_quotation_detail.rate) AS `sum_rate`, SUM(employee_quotation_detail.rate * employee_quotation_detail.qty) AS `sum_total`");
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
        $this->quotation_id->ViewValue = GetDropDownDisplayValue($this->quotation_id->CurrentValue, "", 0);
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`quotation_list`";
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
        $groupField = &$this->quotation_id;
        if ($groupField->GroupSql != "") {
            $expr = str_replace("%s", $groupField->Expression, $groupField->GroupSql) . " AS " . QuotedName($groupField->getGroupName(), $this->Dbid);
            $select->addSelect($expr);
        }
        $groupField = &$this->quotation_date;
        if ($groupField->GroupSql != "") {
            $expr = str_replace("%s", $groupField->Expression, $groupField->GroupSql) . " AS " . QuotedName($groupField->getGroupName(), $this->Dbid);
            $select->addSelect($expr);
        }
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
        return "";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 0) {
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
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
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
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
            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
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
        // No binary fields
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
