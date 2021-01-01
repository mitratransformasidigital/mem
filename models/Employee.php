<?php

namespace MEM\prjMitralPHP;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for employee
 */
class Employee extends DbTable
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
    public $employee_name;
    public $employee_username;
    public $employee_password;
    public $employee_email;
    public $birth_date;
    public $religion;
    public $nik;
    public $npwp;
    public $address;
    public $city_id;
    public $postal_code;
    public $bank_number;
    public $bank_name;
    public $scan_ktp;
    public $scan_npwp;
    public $curiculum_vitae;
    public $position_id;
    public $status_id;
    public $skill_id;
    public $office_id;
    public $hire_date;
    public $termination_date;
    public $user_level;
    public $technical_skill;
    public $about_me;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'employee';
        $this->TableName = 'employee';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`employee`";
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

        // employee_name
        $this->employee_name = new DbField('employee', 'employee', 'x_employee_name', 'employee_name', '`employee_name`', '`employee_name`', 200, 150, -1, false, '`employee_name`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->employee_name->Nullable = false; // NOT NULL field
        $this->employee_name->Required = true; // Required field
        $this->employee_name->Sortable = true; // Allow sort
        $this->Fields['employee_name'] = &$this->employee_name;

        // employee_username
        $this->employee_username = new DbField('employee', 'employee', 'x_employee_username', 'employee_username', '`employee_username`', '`employee_username`', 200, 50, -1, false, '`employee_username`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->employee_username->IsPrimaryKey = true; // Primary key field
        $this->employee_username->IsForeignKey = true; // Foreign key field
        $this->employee_username->Nullable = false; // NOT NULL field
        $this->employee_username->Required = true; // Required field
        $this->employee_username->Sortable = true; // Allow sort
        $this->Fields['employee_username'] = &$this->employee_username;

        // employee_password
        $this->employee_password = new DbField('employee', 'employee', 'x_employee_password', 'employee_password', '`employee_password`', '`employee_password`', 200, 50, -1, false, '`employee_password`', false, false, false, 'FORMATTED TEXT', 'PASSWORD');
        if (Config("ENCRYPTED_PASSWORD")) {
            $this->employee_password->Raw = true;
        }
        $this->employee_password->Nullable = false; // NOT NULL field
        $this->employee_password->Required = true; // Required field
        $this->employee_password->Sortable = true; // Allow sort
        $this->Fields['employee_password'] = &$this->employee_password;

        // employee_email
        $this->employee_email = new DbField('employee', 'employee', 'x_employee_email', 'employee_email', '`employee_email`', '`employee_email`', 200, 50, -1, false, '`employee_email`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->employee_email->Nullable = false; // NOT NULL field
        $this->employee_email->Required = true; // Required field
        $this->employee_email->Sortable = true; // Allow sort
        $this->Fields['employee_email'] = &$this->employee_email;

        // birth_date
        $this->birth_date = new DbField('employee', 'employee', 'x_birth_date', 'birth_date', '`birth_date`', CastDateFieldForLike("`birth_date`", 5, "DB"), 135, 19, 5, false, '`birth_date`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->birth_date->Sortable = true; // Allow sort
        $this->birth_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateYMD"));
        $this->Fields['birth_date'] = &$this->birth_date;

        // religion
        $this->religion = new DbField('employee', 'employee', 'x_religion', 'religion', '`religion`', '`religion`', 200, 50, -1, false, '`religion`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->religion->Sortable = true; // Allow sort
        $this->religion->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->religion->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->religion->Lookup = new Lookup('religion', 'employee', false, '', ["","","",""], [], [], [], [], [], [], '', '');
        $this->religion->OptionCount = 5;
        $this->Fields['religion'] = &$this->religion;

        // nik
        $this->nik = new DbField('employee', 'employee', 'x_nik', 'nik', '`nik`', '`nik`', 200, 150, -1, false, '`nik`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nik->Nullable = false; // NOT NULL field
        $this->nik->Required = true; // Required field
        $this->nik->Sortable = true; // Allow sort
        $this->Fields['nik'] = &$this->nik;

        // npwp
        $this->npwp = new DbField('employee', 'employee', 'x_npwp', 'npwp', '`npwp`', '`npwp`', 200, 50, -1, false, '`npwp`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->npwp->Sortable = true; // Allow sort
        $this->Fields['npwp'] = &$this->npwp;

        // address
        $this->address = new DbField('employee', 'employee', 'x_address', 'address', '`address`', '`address`', 200, 150, -1, false, '`address`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->address->Sortable = true; // Allow sort
        $this->Fields['address'] = &$this->address;

        // city_id
        $this->city_id = new DbField('employee', 'employee', 'x_city_id', 'city_id', '`city_id`', '`city_id`', 200, 10, -1, false, '`city_id`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->city_id->IsForeignKey = true; // Foreign key field
        $this->city_id->Sortable = true; // Allow sort
        $this->city_id->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->city_id->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->city_id->Lookup = new Lookup('city_id', 'master_city', false, 'city_id', ["city","","",""], [], [], [], [], [], [], '', '');
        $this->Fields['city_id'] = &$this->city_id;

        // postal_code
        $this->postal_code = new DbField('employee', 'employee', 'x_postal_code', 'postal_code', '`postal_code`', '`postal_code`', 200, 50, -1, false, '`postal_code`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->postal_code->Sortable = true; // Allow sort
        $this->Fields['postal_code'] = &$this->postal_code;

        // bank_number
        $this->bank_number = new DbField('employee', 'employee', 'x_bank_number', 'bank_number', '`bank_number`', '`bank_number`', 200, 50, -1, false, '`bank_number`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->bank_number->Nullable = false; // NOT NULL field
        $this->bank_number->Required = true; // Required field
        $this->bank_number->Sortable = true; // Allow sort
        $this->Fields['bank_number'] = &$this->bank_number;

        // bank_name
        $this->bank_name = new DbField('employee', 'employee', 'x_bank_name', 'bank_name', '`bank_name`', '`bank_name`', 200, 50, -1, false, '`bank_name`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->bank_name->Nullable = false; // NOT NULL field
        $this->bank_name->Required = true; // Required field
        $this->bank_name->Sortable = true; // Allow sort
        $this->Fields['bank_name'] = &$this->bank_name;

        // scan_ktp
        $this->scan_ktp = new DbField('employee', 'employee', 'x_scan_ktp', 'scan_ktp', '`scan_ktp`', '`scan_ktp`', 200, 200, -1, true, '`scan_ktp`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->scan_ktp->Sortable = true; // Allow sort
        $this->Fields['scan_ktp'] = &$this->scan_ktp;

        // scan_npwp
        $this->scan_npwp = new DbField('employee', 'employee', 'x_scan_npwp', 'scan_npwp', '`scan_npwp`', '`scan_npwp`', 200, 250, -1, true, '`scan_npwp`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->scan_npwp->Sortable = true; // Allow sort
        $this->Fields['scan_npwp'] = &$this->scan_npwp;

        // curiculum_vitae
        $this->curiculum_vitae = new DbField('employee', 'employee', 'x_curiculum_vitae', 'curiculum_vitae', '`curiculum_vitae`', '`curiculum_vitae`', 200, 250, -1, true, '`curiculum_vitae`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->curiculum_vitae->Sortable = true; // Allow sort
        $this->Fields['curiculum_vitae'] = &$this->curiculum_vitae;

        // position_id
        $this->position_id = new DbField('employee', 'employee', 'x_position_id', 'position_id', '`position_id`', '`position_id`', 3, 50, -1, false, '`position_id`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->position_id->IsForeignKey = true; // Foreign key field
        $this->position_id->Sortable = true; // Allow sort
        $this->position_id->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->position_id->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->position_id->Lookup = new Lookup('position_id', 'master_position', false, 'position_id', ["position","","",""], [], [], [], [], [], [], '', '');
        $this->position_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['position_id'] = &$this->position_id;

        // status_id
        $this->status_id = new DbField('employee', 'employee', 'x_status_id', 'status_id', '`status_id`', '`status_id`', 3, 50, -1, false, '`status_id`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->status_id->IsForeignKey = true; // Foreign key field
        $this->status_id->Sortable = true; // Allow sort
        $this->status_id->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->status_id->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->status_id->Lookup = new Lookup('status_id', 'master_status', false, 'status_id', ["status","","",""], [], [], [], [], [], [], '', '');
        $this->status_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['status_id'] = &$this->status_id;

        // skill_id
        $this->skill_id = new DbField('employee', 'employee', 'x_skill_id', 'skill_id', '`skill_id`', '`skill_id`', 3, 50, -1, false, '`skill_id`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->skill_id->IsForeignKey = true; // Foreign key field
        $this->skill_id->Sortable = true; // Allow sort
        $this->skill_id->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->skill_id->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->skill_id->Lookup = new Lookup('skill_id', 'master_skill', false, 'skill_id', ["skill","","",""], [], [], [], [], [], [], '', '');
        $this->skill_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['skill_id'] = &$this->skill_id;

        // office_id
        $this->office_id = new DbField('employee', 'employee', 'x_office_id', 'office_id', '`office_id`', '`office_id`', 3, 50, -1, false, '`office_id`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->office_id->IsForeignKey = true; // Foreign key field
        $this->office_id->Sortable = true; // Allow sort
        $this->office_id->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->office_id->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->office_id->Lookup = new Lookup('office_id', 'master_office', false, 'office_id', ["office","","",""], [], [], [], [], [], [], '', '');
        $this->office_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['office_id'] = &$this->office_id;

        // hire_date
        $this->hire_date = new DbField('employee', 'employee', 'x_hire_date', 'hire_date', '`hire_date`', CastDateFieldForLike("`hire_date`", 5, "DB"), 133, 10, 5, false, '`hire_date`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->hire_date->Sortable = true; // Allow sort
        $this->hire_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateYMD"));
        $this->Fields['hire_date'] = &$this->hire_date;

        // termination_date
        $this->termination_date = new DbField('employee', 'employee', 'x_termination_date', 'termination_date', '`termination_date`', CastDateFieldForLike("`termination_date`", 5, "DB"), 133, 10, 5, false, '`termination_date`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->termination_date->Sortable = true; // Allow sort
        $this->termination_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_SEPARATOR"], $Language->phrase("IncorrectDateYMD"));
        $this->Fields['termination_date'] = &$this->termination_date;

        // user_level
        $this->user_level = new DbField('employee', 'employee', 'x_user_level', 'user_level', '`user_level`', '`user_level`', 3, 11, -1, false, '`user_level`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->user_level->Sortable = true; // Allow sort
        $this->user_level->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->user_level->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->user_level->Lookup = new Lookup('user_level', 'userlevels', false, 'userlevelid', ["userlevelname","","",""], [], [], [], [], [], [], '', '');
        $this->user_level->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['user_level'] = &$this->user_level;

        // technical_skill
        $this->technical_skill = new DbField('employee', 'employee', 'x_technical_skill', 'technical_skill', '`technical_skill`', '`technical_skill`', 200, 250, -1, false, '`technical_skill`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->technical_skill->Sortable = true; // Allow sort
        $this->Fields['technical_skill'] = &$this->technical_skill;

        // about_me
        $this->about_me = new DbField('employee', 'employee', 'x_about_me', 'about_me', '`about_me`', '`about_me`', 200, 250, -1, false, '`about_me`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->about_me->Sortable = true; // Allow sort
        $this->Fields['about_me'] = &$this->about_me;
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
        if ($this->getCurrentMasterTable() == "master_office") {
            if ($this->office_id->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`office_id`", $this->office_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "master_position") {
            if ($this->position_id->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`position_id`", $this->position_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "master_skill") {
            if ($this->skill_id->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`skill_id`", $this->skill_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "master_status") {
            if ($this->status_id->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`status_id`", $this->status_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "master_city") {
            if ($this->city_id->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`city_id`", $this->city_id->getSessionValue(), DATATYPE_STRING, "DB");
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
        if ($this->getCurrentMasterTable() == "master_office") {
            if ($this->office_id->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`office_id`", $this->office_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "master_position") {
            if ($this->position_id->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`position_id`", $this->position_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "master_skill") {
            if ($this->skill_id->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`skill_id`", $this->skill_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "master_status") {
            if ($this->status_id->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`status_id`", $this->status_id->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "master_city") {
            if ($this->city_id->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`city_id`", $this->city_id->getSessionValue(), DATATYPE_STRING, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    // Master filter
    public function sqlMasterFilter_master_office()
    {
        return "`office_id`=@office_id@";
    }
    // Detail filter
    public function sqlDetailFilter_master_office()
    {
        return "`office_id`=@office_id@";
    }

    // Master filter
    public function sqlMasterFilter_master_position()
    {
        return "`position_id`=@position_id@";
    }
    // Detail filter
    public function sqlDetailFilter_master_position()
    {
        return "`position_id`=@position_id@";
    }

    // Master filter
    public function sqlMasterFilter_master_skill()
    {
        return "`skill_id`=@skill_id@";
    }
    // Detail filter
    public function sqlDetailFilter_master_skill()
    {
        return "`skill_id`=@skill_id@";
    }

    // Master filter
    public function sqlMasterFilter_master_status()
    {
        return "`status_id`=@status_id@";
    }
    // Detail filter
    public function sqlDetailFilter_master_status()
    {
        return "`status_id`=@status_id@";
    }

    // Master filter
    public function sqlMasterFilter_master_city()
    {
        return "`city_id`='@city_id@'";
    }
    // Detail filter
    public function sqlDetailFilter_master_city()
    {
        return "`city_id`='@city_id@'";
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
        if ($this->getCurrentDetailTable() == "employee_shift") {
            $detailUrl = Container("employee_shift")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "activity") {
            $detailUrl = Container("activity")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "permit") {
            $detailUrl = Container("permit")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "employee_contract") {
            $detailUrl = Container("employee_contract")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "employee_asset") {
            $detailUrl = Container("employee_asset")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "employee_timesheet") {
            $detailUrl = Container("employee_timesheet")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "employee_trainings") {
            $detailUrl = Container("employee_trainings")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "myasset") {
            $detailUrl = Container("myasset")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "mycontract") {
            $detailUrl = Container("mycontract")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "mytimesheet") {
            $detailUrl = Container("mytimesheet")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "mytraining") {
            $detailUrl = Container("mytraining")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_employee_username", $this->employee_username->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "employeelist";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`employee`";
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
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
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
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                if ($value == $this->Fields[$name]->OldValue) { // No need to update hashed password if not changed
                    continue;
                }
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
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
        // Cascade Update detail table 'employee_shift'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("employee_shift")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
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

        // Cascade Update detail table 'activity'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("activity")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'activity_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("activity")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("activity")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("activity")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'permit'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("permit")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'permit_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("permit")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("permit")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("permit")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'employee_contract'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("employee_contract")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'contract_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("employee_contract")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("employee_contract")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("employee_contract")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'employee_asset'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("employee_asset")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'asset_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("employee_asset")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("employee_asset")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("employee_asset")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'employee_timesheet'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("employee_timesheet")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'timesheet_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("employee_timesheet")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("employee_timesheet")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("employee_timesheet")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'employee_trainings'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("employee_trainings")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'training_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("employee_trainings")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("employee_trainings")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("employee_trainings")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'myasset'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("myasset")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'asset_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("myasset")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("myasset")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("myasset")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'mycontract'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("mycontract")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'contract_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("mycontract")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("mycontract")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("mycontract")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'mytimesheet'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("mytimesheet")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'timesheet_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("mytimesheet")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("mytimesheet")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("mytimesheet")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'mytraining'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['employee_username']) && $rsold['employee_username'] != $rs['employee_username'])) { // Update detail field 'employee_username'
            $cascadeUpdate = true;
            $rscascade['employee_username'] = $rs['employee_username'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("mytraining")->loadRs("`employee_username` = " . QuotedValue($rsold['employee_username'], DATATYPE_STRING, 'DB'))->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'training_id';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("mytraining")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("mytraining")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("mytraining")->rowUpdated($rsdtlold, $rsdtlnew);
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
            if (array_key_exists('employee_username', $rs)) {
                AddFilter($where, QuotedName('employee_username', $this->Dbid) . '=' . QuotedValue($rs['employee_username'], $this->employee_username->DataType, $this->Dbid));
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

        // Cascade delete detail table 'employee_shift'
        $dtlrows = Container("employee_shift")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
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

        // Cascade delete detail table 'activity'
        $dtlrows = Container("activity")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("activity")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("activity")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("activity")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'permit'
        $dtlrows = Container("permit")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("permit")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("permit")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("permit")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'employee_contract'
        $dtlrows = Container("employee_contract")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("employee_contract")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("employee_contract")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("employee_contract")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'employee_asset'
        $dtlrows = Container("employee_asset")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("employee_asset")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("employee_asset")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("employee_asset")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'employee_timesheet'
        $dtlrows = Container("employee_timesheet")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("employee_timesheet")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("employee_timesheet")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("employee_timesheet")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'employee_trainings'
        $dtlrows = Container("employee_trainings")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("employee_trainings")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("employee_trainings")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("employee_trainings")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'myasset'
        $dtlrows = Container("myasset")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("myasset")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("myasset")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("myasset")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'mycontract'
        $dtlrows = Container("mycontract")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("mycontract")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("mycontract")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("mycontract")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'mytimesheet'
        $dtlrows = Container("mytimesheet")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("mytimesheet")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("mytimesheet")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("mytimesheet")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'mytraining'
        $dtlrows = Container("mytraining")->loadRs("`employee_username` = " . QuotedValue($rs['employee_username'], DATATYPE_STRING, "DB"))->fetchAll(\PDO::FETCH_ASSOC);
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("mytraining")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("mytraining")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("mytraining")->rowDeleted($dtlrow);
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
        $this->employee_name->DbValue = $row['employee_name'];
        $this->employee_username->DbValue = $row['employee_username'];
        $this->employee_password->DbValue = $row['employee_password'];
        $this->employee_email->DbValue = $row['employee_email'];
        $this->birth_date->DbValue = $row['birth_date'];
        $this->religion->DbValue = $row['religion'];
        $this->nik->DbValue = $row['nik'];
        $this->npwp->DbValue = $row['npwp'];
        $this->address->DbValue = $row['address'];
        $this->city_id->DbValue = $row['city_id'];
        $this->postal_code->DbValue = $row['postal_code'];
        $this->bank_number->DbValue = $row['bank_number'];
        $this->bank_name->DbValue = $row['bank_name'];
        $this->scan_ktp->Upload->DbValue = $row['scan_ktp'];
        $this->scan_npwp->Upload->DbValue = $row['scan_npwp'];
        $this->curiculum_vitae->Upload->DbValue = $row['curiculum_vitae'];
        $this->position_id->DbValue = $row['position_id'];
        $this->status_id->DbValue = $row['status_id'];
        $this->skill_id->DbValue = $row['skill_id'];
        $this->office_id->DbValue = $row['office_id'];
        $this->hire_date->DbValue = $row['hire_date'];
        $this->termination_date->DbValue = $row['termination_date'];
        $this->user_level->DbValue = $row['user_level'];
        $this->technical_skill->DbValue = $row['technical_skill'];
        $this->about_me->DbValue = $row['about_me'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['scan_ktp']) ? [] : [$row['scan_ktp']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->scan_ktp->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->scan_ktp->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['scan_npwp']) ? [] : [$row['scan_npwp']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->scan_npwp->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->scan_npwp->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['curiculum_vitae']) ? [] : [$row['curiculum_vitae']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->curiculum_vitae->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->curiculum_vitae->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`employee_username` = '@employee_username@'";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->employee_username->CurrentValue : $this->employee_username->OldValue;
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
                $this->employee_username->CurrentValue = $keys[0];
            } else {
                $this->employee_username->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('employee_username', $row) ? $row['employee_username'] : null;
        } else {
            $val = $this->employee_username->OldValue !== null ? $this->employee_username->OldValue : $this->employee_username->CurrentValue;
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@employee_username@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("employeelist");
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
        if ($pageName == "employeeview") {
            return $Language->phrase("View");
        } elseif ($pageName == "employeeedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "employeeadd") {
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
                return "EmployeeView";
            case Config("API_ADD_ACTION"):
                return "EmployeeAdd";
            case Config("API_EDIT_ACTION"):
                return "EmployeeEdit";
            case Config("API_DELETE_ACTION"):
                return "EmployeeDelete";
            case Config("API_LIST_ACTION"):
                return "EmployeeList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "employeelist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("employeeview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("employeeview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "employeeadd?" . $this->getUrlParm($parm);
        } else {
            $url = "employeeadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("employeeedit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("employeeedit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("employeeadd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("employeeadd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("employeedelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "master_office" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_office_id", $this->office_id->CurrentValue);
        }
        if ($this->getCurrentMasterTable() == "master_position" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_position_id", $this->position_id->CurrentValue);
        }
        if ($this->getCurrentMasterTable() == "master_skill" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_skill_id", $this->skill_id->CurrentValue);
        }
        if ($this->getCurrentMasterTable() == "master_status" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_status_id", $this->status_id->CurrentValue);
        }
        if ($this->getCurrentMasterTable() == "master_city" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_city_id", $this->city_id->CurrentValue);
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "employee_username:" . JsonEncode($this->employee_username->CurrentValue, "string");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->employee_username->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->employee_username->CurrentValue);
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
            if (($keyValue = Param("employee_username") ?? Route("employee_username")) !== null) {
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
                $this->employee_username->CurrentValue = $key;
            } else {
                $this->employee_username->OldValue = $key;
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
        $this->scan_npwp->Upload->DbValue = $row['scan_npwp'];
        $this->curiculum_vitae->Upload->DbValue = $row['curiculum_vitae'];
        $this->position_id->setDbValue($row['position_id']);
        $this->status_id->setDbValue($row['status_id']);
        $this->skill_id->setDbValue($row['skill_id']);
        $this->office_id->setDbValue($row['office_id']);
        $this->hire_date->setDbValue($row['hire_date']);
        $this->termination_date->setDbValue($row['termination_date']);
        $this->user_level->setDbValue($row['user_level']);
        $this->technical_skill->setDbValue($row['technical_skill']);
        $this->about_me->setDbValue($row['about_me']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // employee_name

        // employee_username

        // employee_password

        // employee_email

        // birth_date

        // religion

        // nik

        // npwp

        // address
        $this->address->CellCssStyle = "white-space: nowrap;";

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

        // employee_name
        $this->employee_name->EditAttrs["class"] = "form-control";
        $this->employee_name->EditCustomAttributes = "";
        if (!$this->employee_name->Raw) {
            $this->employee_name->CurrentValue = HtmlDecode($this->employee_name->CurrentValue);
        }
        $this->employee_name->EditValue = $this->employee_name->CurrentValue;
        $this->employee_name->PlaceHolder = RemoveHtml($this->employee_name->caption());

        // employee_username
        $this->employee_username->EditAttrs["class"] = "form-control";
        $this->employee_username->EditCustomAttributes = "";
        if (!$this->employee_username->Raw) {
            $this->employee_username->CurrentValue = HtmlDecode($this->employee_username->CurrentValue);
        }
        $this->employee_username->EditValue = $this->employee_username->CurrentValue;
        $this->employee_username->PlaceHolder = RemoveHtml($this->employee_username->caption());

        // employee_password
        $this->employee_password->EditAttrs["class"] = "form-control";
        $this->employee_password->EditCustomAttributes = "";
        $this->employee_password->EditValue = $Language->phrase("PasswordMask"); // Show as masked password
        $this->employee_password->PlaceHolder = RemoveHtml($this->employee_password->caption());

        // employee_email
        $this->employee_email->EditAttrs["class"] = "form-control";
        $this->employee_email->EditCustomAttributes = "";
        if (!$this->employee_email->Raw) {
            $this->employee_email->CurrentValue = HtmlDecode($this->employee_email->CurrentValue);
        }
        $this->employee_email->EditValue = $this->employee_email->CurrentValue;
        $this->employee_email->PlaceHolder = RemoveHtml($this->employee_email->caption());

        // birth_date
        $this->birth_date->EditAttrs["class"] = "form-control";
        $this->birth_date->EditCustomAttributes = "";
        $this->birth_date->EditValue = FormatDateTime($this->birth_date->CurrentValue, 5);
        $this->birth_date->PlaceHolder = RemoveHtml($this->birth_date->caption());

        // religion
        $this->religion->EditAttrs["class"] = "form-control";
        $this->religion->EditCustomAttributes = "";
        $this->religion->EditValue = $this->religion->options(true);
        $this->religion->PlaceHolder = RemoveHtml($this->religion->caption());

        // nik
        $this->nik->EditAttrs["class"] = "form-control";
        $this->nik->EditCustomAttributes = "";
        if (!$this->nik->Raw) {
            $this->nik->CurrentValue = HtmlDecode($this->nik->CurrentValue);
        }
        $this->nik->EditValue = $this->nik->CurrentValue;
        $this->nik->PlaceHolder = RemoveHtml($this->nik->caption());

        // npwp
        $this->npwp->EditAttrs["class"] = "form-control";
        $this->npwp->EditCustomAttributes = "";
        if (!$this->npwp->Raw) {
            $this->npwp->CurrentValue = HtmlDecode($this->npwp->CurrentValue);
        }
        $this->npwp->EditValue = $this->npwp->CurrentValue;
        $this->npwp->PlaceHolder = RemoveHtml($this->npwp->caption());

        // address
        $this->address->EditAttrs["class"] = "form-control";
        $this->address->EditCustomAttributes = "";
        if (!$this->address->Raw) {
            $this->address->CurrentValue = HtmlDecode($this->address->CurrentValue);
        }
        $this->address->EditValue = $this->address->CurrentValue;
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
            $this->city_id->PlaceHolder = RemoveHtml($this->city_id->caption());
        }

        // postal_code
        $this->postal_code->EditAttrs["class"] = "form-control";
        $this->postal_code->EditCustomAttributes = "";
        if (!$this->postal_code->Raw) {
            $this->postal_code->CurrentValue = HtmlDecode($this->postal_code->CurrentValue);
        }
        $this->postal_code->EditValue = $this->postal_code->CurrentValue;
        $this->postal_code->PlaceHolder = RemoveHtml($this->postal_code->caption());

        // bank_number
        $this->bank_number->EditAttrs["class"] = "form-control";
        $this->bank_number->EditCustomAttributes = "";
        if (!$this->bank_number->Raw) {
            $this->bank_number->CurrentValue = HtmlDecode($this->bank_number->CurrentValue);
        }
        $this->bank_number->EditValue = $this->bank_number->CurrentValue;
        $this->bank_number->PlaceHolder = RemoveHtml($this->bank_number->caption());

        // bank_name
        $this->bank_name->EditAttrs["class"] = "form-control";
        $this->bank_name->EditCustomAttributes = "";
        if (!$this->bank_name->Raw) {
            $this->bank_name->CurrentValue = HtmlDecode($this->bank_name->CurrentValue);
        }
        $this->bank_name->EditValue = $this->bank_name->CurrentValue;
        $this->bank_name->PlaceHolder = RemoveHtml($this->bank_name->caption());

        // scan_ktp
        $this->scan_ktp->EditAttrs["class"] = "form-control";
        $this->scan_ktp->EditCustomAttributes = "";
        if (!EmptyValue($this->scan_ktp->Upload->DbValue)) {
            $this->scan_ktp->EditValue = $this->scan_ktp->Upload->DbValue;
        } else {
            $this->scan_ktp->EditValue = "";
        }
        if (!EmptyValue($this->scan_ktp->CurrentValue)) {
            $this->scan_ktp->Upload->FileName = $this->scan_ktp->CurrentValue;
        }

        // scan_npwp
        $this->scan_npwp->EditAttrs["class"] = "form-control";
        $this->scan_npwp->EditCustomAttributes = "";
        if (!EmptyValue($this->scan_npwp->Upload->DbValue)) {
            $this->scan_npwp->EditValue = $this->scan_npwp->Upload->DbValue;
        } else {
            $this->scan_npwp->EditValue = "";
        }
        if (!EmptyValue($this->scan_npwp->CurrentValue)) {
            $this->scan_npwp->Upload->FileName = $this->scan_npwp->CurrentValue;
        }

        // curiculum_vitae
        $this->curiculum_vitae->EditAttrs["class"] = "form-control";
        $this->curiculum_vitae->EditCustomAttributes = "";
        if (!EmptyValue($this->curiculum_vitae->Upload->DbValue)) {
            $this->curiculum_vitae->EditValue = $this->curiculum_vitae->Upload->DbValue;
        } else {
            $this->curiculum_vitae->EditValue = "";
        }
        if (!EmptyValue($this->curiculum_vitae->CurrentValue)) {
            $this->curiculum_vitae->Upload->FileName = $this->curiculum_vitae->CurrentValue;
        }

        // position_id
        $this->position_id->EditAttrs["class"] = "form-control";
        $this->position_id->EditCustomAttributes = "";
        if ($this->position_id->getSessionValue() != "") {
            $this->position_id->CurrentValue = GetForeignKeyValue($this->position_id->getSessionValue());
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
        } else {
            $this->position_id->PlaceHolder = RemoveHtml($this->position_id->caption());
        }

        // status_id
        $this->status_id->EditAttrs["class"] = "form-control";
        $this->status_id->EditCustomAttributes = "";
        if ($this->status_id->getSessionValue() != "") {
            $this->status_id->CurrentValue = GetForeignKeyValue($this->status_id->getSessionValue());
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
        } else {
            $this->status_id->PlaceHolder = RemoveHtml($this->status_id->caption());
        }

        // skill_id
        $this->skill_id->EditAttrs["class"] = "form-control";
        $this->skill_id->EditCustomAttributes = "";
        if ($this->skill_id->getSessionValue() != "") {
            $this->skill_id->CurrentValue = GetForeignKeyValue($this->skill_id->getSessionValue());
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
        } else {
            $this->skill_id->PlaceHolder = RemoveHtml($this->skill_id->caption());
        }

        // office_id
        $this->office_id->EditAttrs["class"] = "form-control";
        $this->office_id->EditCustomAttributes = "";
        if ($this->office_id->getSessionValue() != "") {
            $this->office_id->CurrentValue = GetForeignKeyValue($this->office_id->getSessionValue());
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
        } else {
            $this->office_id->PlaceHolder = RemoveHtml($this->office_id->caption());
        }

        // hire_date
        $this->hire_date->EditAttrs["class"] = "form-control";
        $this->hire_date->EditCustomAttributes = "";
        $this->hire_date->EditValue = FormatDateTime($this->hire_date->CurrentValue, 5);
        $this->hire_date->PlaceHolder = RemoveHtml($this->hire_date->caption());

        // termination_date
        $this->termination_date->EditAttrs["class"] = "form-control";
        $this->termination_date->EditCustomAttributes = "";
        $this->termination_date->EditValue = FormatDateTime($this->termination_date->CurrentValue, 5);
        $this->termination_date->PlaceHolder = RemoveHtml($this->termination_date->caption());

        // user_level
        $this->user_level->EditAttrs["class"] = "form-control";
        $this->user_level->EditCustomAttributes = "";
        if (!$Security->canAdmin()) { // System admin
            $this->user_level->EditValue = $Language->phrase("PasswordMask");
        } else {
            $this->user_level->PlaceHolder = RemoveHtml($this->user_level->caption());
        }

        // technical_skill
        $this->technical_skill->EditAttrs["class"] = "form-control";
        $this->technical_skill->EditCustomAttributes = "";
        $this->technical_skill->EditValue = $this->technical_skill->CurrentValue;
        $this->technical_skill->PlaceHolder = RemoveHtml($this->technical_skill->caption());

        // about_me
        $this->about_me->EditAttrs["class"] = "form-control";
        $this->about_me->EditCustomAttributes = "";
        $this->about_me->EditValue = $this->about_me->CurrentValue;
        $this->about_me->PlaceHolder = RemoveHtml($this->about_me->caption());

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
                    $doc->exportCaption($this->employee_name);
                    $doc->exportCaption($this->employee_username);
                    $doc->exportCaption($this->employee_password);
                    $doc->exportCaption($this->employee_email);
                    $doc->exportCaption($this->birth_date);
                    $doc->exportCaption($this->religion);
                    $doc->exportCaption($this->nik);
                    $doc->exportCaption($this->npwp);
                    $doc->exportCaption($this->address);
                    $doc->exportCaption($this->city_id);
                    $doc->exportCaption($this->postal_code);
                    $doc->exportCaption($this->bank_number);
                    $doc->exportCaption($this->bank_name);
                    $doc->exportCaption($this->scan_ktp);
                    $doc->exportCaption($this->scan_npwp);
                    $doc->exportCaption($this->curiculum_vitae);
                    $doc->exportCaption($this->position_id);
                    $doc->exportCaption($this->status_id);
                    $doc->exportCaption($this->skill_id);
                    $doc->exportCaption($this->office_id);
                    $doc->exportCaption($this->hire_date);
                    $doc->exportCaption($this->termination_date);
                    $doc->exportCaption($this->user_level);
                    $doc->exportCaption($this->technical_skill);
                    $doc->exportCaption($this->about_me);
                } else {
                    $doc->exportCaption($this->employee_name);
                    $doc->exportCaption($this->employee_username);
                    $doc->exportCaption($this->employee_password);
                    $doc->exportCaption($this->employee_email);
                    $doc->exportCaption($this->birth_date);
                    $doc->exportCaption($this->religion);
                    $doc->exportCaption($this->nik);
                    $doc->exportCaption($this->npwp);
                    $doc->exportCaption($this->address);
                    $doc->exportCaption($this->city_id);
                    $doc->exportCaption($this->postal_code);
                    $doc->exportCaption($this->bank_number);
                    $doc->exportCaption($this->bank_name);
                    $doc->exportCaption($this->scan_ktp);
                    $doc->exportCaption($this->scan_npwp);
                    $doc->exportCaption($this->curiculum_vitae);
                    $doc->exportCaption($this->position_id);
                    $doc->exportCaption($this->status_id);
                    $doc->exportCaption($this->skill_id);
                    $doc->exportCaption($this->office_id);
                    $doc->exportCaption($this->hire_date);
                    $doc->exportCaption($this->termination_date);
                    $doc->exportCaption($this->user_level);
                    $doc->exportCaption($this->technical_skill);
                    $doc->exportCaption($this->about_me);
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
                        $doc->exportField($this->employee_name);
                        $doc->exportField($this->employee_username);
                        $doc->exportField($this->employee_password);
                        $doc->exportField($this->employee_email);
                        $doc->exportField($this->birth_date);
                        $doc->exportField($this->religion);
                        $doc->exportField($this->nik);
                        $doc->exportField($this->npwp);
                        $doc->exportField($this->address);
                        $doc->exportField($this->city_id);
                        $doc->exportField($this->postal_code);
                        $doc->exportField($this->bank_number);
                        $doc->exportField($this->bank_name);
                        $doc->exportField($this->scan_ktp);
                        $doc->exportField($this->scan_npwp);
                        $doc->exportField($this->curiculum_vitae);
                        $doc->exportField($this->position_id);
                        $doc->exportField($this->status_id);
                        $doc->exportField($this->skill_id);
                        $doc->exportField($this->office_id);
                        $doc->exportField($this->hire_date);
                        $doc->exportField($this->termination_date);
                        $doc->exportField($this->user_level);
                        $doc->exportField($this->technical_skill);
                        $doc->exportField($this->about_me);
                    } else {
                        $doc->exportField($this->employee_name);
                        $doc->exportField($this->employee_username);
                        $doc->exportField($this->employee_password);
                        $doc->exportField($this->employee_email);
                        $doc->exportField($this->birth_date);
                        $doc->exportField($this->religion);
                        $doc->exportField($this->nik);
                        $doc->exportField($this->npwp);
                        $doc->exportField($this->address);
                        $doc->exportField($this->city_id);
                        $doc->exportField($this->postal_code);
                        $doc->exportField($this->bank_number);
                        $doc->exportField($this->bank_name);
                        $doc->exportField($this->scan_ktp);
                        $doc->exportField($this->scan_npwp);
                        $doc->exportField($this->curiculum_vitae);
                        $doc->exportField($this->position_id);
                        $doc->exportField($this->status_id);
                        $doc->exportField($this->skill_id);
                        $doc->exportField($this->office_id);
                        $doc->exportField($this->hire_date);
                        $doc->exportField($this->termination_date);
                        $doc->exportField($this->user_level);
                        $doc->exportField($this->technical_skill);
                        $doc->exportField($this->about_me);
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

    // Send register email
    public function sendRegisterEmail($row)
    {
        $email = $this->prepareRegisterEmail($row);
        $args = [];
        $args["rs"] = $row;
        $emailSent = false;
        if ($this->emailSending($email, $args)) { // Use Email_Sending server event of user table
            $emailSent = $email->send();
        }
        return $emailSent;
    }

    // Prepare register email
    public function prepareRegisterEmail($row = null, $langId = "")
    {
        global $CurrentForm;
        $email = new Email();
        $email->load(Config("EMAIL_REGISTER_TEMPLATE"), $langId);
        $receiverEmail = $row === null ? $this->employee_email->CurrentValue : GetUserInfo(Config("USER_EMAIL_FIELD_NAME"), $row);
        if ($receiverEmail == "") { // Send to recipient directly
            $receiverEmail = Config("RECIPIENT_EMAIL");
            $bccEmail = "";
        } else { // Bcc recipient
            $bccEmail = Config("RECIPIENT_EMAIL");
        }
        $email->replaceSender(Config("SENDER_EMAIL")); // Replace Sender
        $email->replaceRecipient($receiverEmail); // Replace Recipient
        if ($bccEmail != "") // Add Bcc
            $email->addBcc($bccEmail);
        $email->replaceContent('<!--FieldCaption_employee_name-->', $this->employee_name->caption());
        $email->replaceContent('<!--employee_name-->', $row === null ? strval($this->employee_name->FormValue) : GetUserInfo('employee_name', $row));
        $email->replaceContent('<!--FieldCaption_employee_username-->', $this->employee_username->caption());
        $email->replaceContent('<!--employee_username-->', $row === null ? strval($this->employee_username->FormValue) : GetUserInfo('employee_username', $row));
        $email->replaceContent('<!--FieldCaption_employee_password-->', $this->employee_password->caption());
        $email->replaceContent('<!--employee_password-->', $row === null ? strval($this->employee_password->FormValue) : GetUserInfo('employee_password', $row));
        $email->replaceContent('<!--FieldCaption_employee_email-->', $this->employee_email->caption());
        $email->replaceContent('<!--employee_email-->', $row === null ? strval($this->employee_email->FormValue) : GetUserInfo('employee_email', $row));
        $email->replaceContent('<!--FieldCaption_birth_date-->', $this->birth_date->caption());
        $email->replaceContent('<!--birth_date-->', $row === null ? strval($this->birth_date->FormValue) : GetUserInfo('birth_date', $row));
        $email->replaceContent('<!--FieldCaption_religion-->', $this->religion->caption());
        $email->replaceContent('<!--religion-->', $row === null ? strval($this->religion->FormValue) : GetUserInfo('religion', $row));
        $email->Content = preg_replace('/<!--\s*register_activate_link_begin[\s\S]*?-->[\s\S]*?<!--\s*register_activate_link_end[\s\S]*?-->/i', '', $email->Content); // Remove activate link block
        return $email;
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
        if ($fldparm == 'scan_ktp') {
            $fldName = "scan_ktp";
            $fileNameFld = "scan_ktp";
        } elseif ($fldparm == 'scan_npwp') {
            $fldName = "scan_npwp";
            $fileNameFld = "scan_npwp";
        } elseif ($fldparm == 'curiculum_vitae') {
            $fldName = "curiculum_vitae";
            $fileNameFld = "curiculum_vitae";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->employee_username->CurrentValue = $ar[0];
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
