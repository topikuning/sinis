<?php

// PHP Report Maker 4.0 - configuration
define("EWRPT_PROJECT_NAME", "apotek", TRUE); // Project Name
define("EWRPT_PROJECT_VAR", "apotek", TRUE); // Project Var
define("EWRPT_IS_WINDOWS", (strtolower(substr(PHP_OS, 0, 3)) === 'win'), TRUE); // Is Windows OS
define("EWRPT_IS_PHP5", (phpversion() >= "5.0.0"), TRUE); // Is PHP 5 or later
define("EWRPT_PATH_DELIMITER", ((EWRPT_IS_WINDOWS) ? "\\" : "/"), TRUE); // Path delimiter

// Language settings
define("EWRPT_LANGUAGE_FOLDER", "phprptlang/", TRUE);
$EWRPT_LANGUAGE_FILE = array();
$EWRPT_LANGUAGE_FILE[] = array("en", "", "english.xml");
define("EWRPT_LANGUAGE_DEFAULT_ID", "en", TRUE);
define("EWRPT_SESSION_LANGUAGE_ID", EWRPT_PROJECT_VAR . "_LanguageId", TRUE); // Language ID
define("EWRPT_USE_DOM_XML", FALSE, TRUE);

// Database connection
define("EWRPT_CONN_HOST", 'localhost', TRUE);
define("EWRPT_CONN_PORT", 3306, TRUE);
define("EWRPT_CONN_USER", 'root', TRUE);
define("EWRPT_CONN_PASS", '', TRUE);
define("EWRPT_CONN_DB", 'production', TRUE);

// ADODB (Access/SQL Server)
define("EWRPT_CODEPAGE", 0, TRUE); // Code page
define("EWRPT_DBMSNAME", 'MySQL', TRUE); // DBMS Name
define("EWRPT_IS_MSACCESS", FALSE, TRUE); // Access
define("EWRPT_IS_MSSQL", FALSE, TRUE); // SQL Server
define("EWRPT_IS_MYSQL", TRUE, TRUE); // MySQL
define("EWRPT_IS_POSTGRESQL", FALSE, TRUE); // PostgreSQL
define("EWRPT_DB_QUOTE_START", "`", TRUE);
define("EWRPT_DB_QUOTE_END", "`", TRUE);

// Debug
define("EWRPT_DEBUG_ENABLED", FALSE, TRUE); // True to debug

// Remove XSS
define("EWRPT_REMOVE_XSS", TRUE, TRUE);
$EWRPT_XSS_ARRAY = array('javascript', 'vbscript', 'expression', '<applet', '<meta', '<xml', '<blink', '<link', '<style', '<script', '<embed', '<object', '<iframe', '<frame', '<frameset', '<ilayer', '<layer', '<bgsound', '<title', '<base',
'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

// General
define("EWRPT_ROOT_RELATIVE_PATH", ".", TRUE); // Relative path of app root
define("EWRPT_DEFAULT_DATE_FORMAT", "dd/mm/yyyy", TRUE); // Default date format
define("EWRPT_DATE_SEPARATOR", "/", TRUE); // Date separator
define("EWRPT_RANDOM_KEY", '2W&D0aN5LwJ2&V02', TRUE); // Random key for encryption
define("EWRPT_PROJECT_STYLESHEET_FILENAME", "phprptcss/apotek.css", TRUE); // Project stylesheet file name
define("EWRPT_CHART_WIDTH", 550, TRUE);
define("EWRPT_CHART_HEIGHT", 440, TRUE);
define("EWRPT_CHART_ALIGN", "middle", TRUE);
define("EWRPT_CHART_SHOW_BLANK_SERIES", FALSE, TRUE); // Show blank series

/**
 * Chart data encoding
 * Note: If you use non English languages, you need to set the encoding for
 * charting. Make sure your encoding is supported by your PHP and either
 * iconv functions or multibyte string functions are enabled. See PHP manual
 * for details
 * eg. define("EWRPT_ENCODING", "ISO-8859-1", true);
 */
define("EWRPT_ENCODING", "", TRUE); // Character encoding

/**
 * MySQL charset (for SET NAMES statement, not used by default)
 * Note: Read http://dev.mysql.com/doc/refman/5.0/en/charset-connection.html
 * before using this setting.
 */
define("EWRPT_MYSQL_CHARSET", "", TRUE);

/**
 * Password (MD5 and case-sensitivity)
 * Note: If you enable MD5 password, make sure that the passwords in your
 * user table are stored as MD5 hash (32-character hexadecimal number) of the
 * clear text password. If you also use case-insensitive password, convert the
 * clear text passwords to lower case first before calculating MD5 hash.
 * Otherwise, existing users will not be able to login.
 */
define("EWRPT_MD5_PASSWORD", FALSE, TRUE); // Use MD5 password
define("EWRPT_CASE_SENSITIVE_PASSWORD", FALSE, TRUE); // Case-sensitive password

/**
 * Numeric and monetary formatting options
 * Set EWRPT_USE_DEFAULT_LOCALE to TRUE to override localeconv and use the
 * following constants for ewrpt_FormatCurrency/Number/Percent functions
 * Also read http://www.php.net/localeconv for description of the constants
*/
define("EWRPT_USE_DEFAULT_LOCALE", FALSE, TRUE);
define("EWRPT_DEFAULT_DECIMAL_POINT", ".", TRUE);
define("EWRPT_DEFAULT_THOUSANDS_SEP", ",", TRUE);

// Locale (if localeconv returns empty info)
define("EWRPT_DEFAULT_CURRENCY_SYMBOL", "$", TRUE);
define("EWRPT_DEFAULT_MON_DECIMAL_POINT", ".", TRUE);
define("EWRPT_DEFAULT_MON_THOUSANDS_SEP", ",", TRUE);
define("EWRPT_DEFAULT_POSITIVE_SIGN", "", TRUE);
define("EWRPT_DEFAULT_NEGATIVE_SIGN", "-", TRUE);
define("EWRPT_DEFAULT_FRAC_DIGITS", 2, TRUE);
define("EWRPT_DEFAULT_P_CS_PRECEDES", TRUE, TRUE);
define("EWRPT_DEFAULT_P_SEP_BY_SPACE", FALSE, TRUE);
define("EWRPT_DEFAULT_N_CS_PRECEDES", TRUE, TRUE);
define("EWRPT_DEFAULT_N_SEP_BY_SPACE", FALSE, TRUE);
define("EWRPT_DEFAULT_P_SIGN_POSN", 3, TRUE);
define("EWRPT_DEFAULT_N_SIGN_POSN", 3, TRUE);

// Filter
define("EWRPT_FILTER_PANEL_OPTION", 2, TRUE); // 1/2/3, 1 = always hide, 2 = always show, 3 = show when filtered
define("EWRPT_SHOW_CURRENT_FILTER", FALSE, TRUE); // True to show current filter

// Session names
define("EWRPT_SESSION_STATUS", EWRPT_PROJECT_VAR . "_status", TRUE); // Login Status
define("EWRPT_SESSION_USER_NAME", EWRPT_SESSION_STATUS . "_UserName", TRUE); // User Name
define("EWRPT_SESSION_USER_ID", EWRPT_SESSION_STATUS . "_UserID", TRUE); // User ID
define("EWRPT_SESSION_USER_LEVEL_ID", EWRPT_SESSION_STATUS . "_UserLevel", TRUE); // User Level ID
define("EWRPT_SESSION_USER_LEVEL", EWRPT_SESSION_STATUS . "_UserLevelValue", TRUE); // User Level
define("EWRPT_SESSION_PARENT_USER_ID", EWRPT_SESSION_STATUS . "_ParentUserID", TRUE); // Parent User ID
define("EWRPT_SESSION_SYSTEM_ADMIN", EWRPT_PROJECT_VAR . "_SysAdmin", TRUE); // System Admin
define("EWRPT_SESSION_AR_USER_LEVEL", EWRPT_PROJECT_VAR . "_arUserLevel", TRUE); // User Level Array
define("EWRPT_SESSION_AR_USER_LEVEL_PRIV", EWRPT_PROJECT_VAR . "_arUserLevelPriv", TRUE); // User Level Privilege Array
define("EWRPT_SESSION_MESSAGE", EWRPT_PROJECT_VAR . "_Message", TRUE); // System Message

// Hard-coded admin
define("EWRPT_ADMIN_USER_NAME", "", TRUE);
define("EWRPT_ADMIN_PASSWORD", "", TRUE);

// User admin
define("EWRPT_LOGIN_SELECT_SQL", "", TRUE);

// User table filters
// User level constants

define("EWRPT_ALLOW_LIST", 8, TRUE); // List
define("EWRPT_ALLOW_REPORT", 8, TRUE); // Report
define("EWRPT_ALLOW_ADMIN", 16, TRUE); // Admin

// User id constants
define("EWRPT_USER_ID_IS_HIERARCHICAL", TRUE, TRUE); // Hierarchical user id

// Table level constants
define("EWRPT_TABLE_PREFIX", "||PHPReportMaker||", TRUE);
define("EWRPT_TABLE_GROUP_PER_PAGE", "grpperpage", TRUE);
define("EWRPT_TABLE_START_GROUP", "start", TRUE);
define("EWRPT_TABLE_ORDER_BY", "order", TRUE);
define("EWRPT_TABLE_ORDER_BY_TYPE", "ordertype", TRUE);
define("EWRPT_TABLE_SORT", "sort", TRUE); // Table sort

// Data types
define("EWRPT_DATATYPE_NONE", 0, TRUE);
define("EWRPT_DATATYPE_NUMBER", 1, TRUE);
define("EWRPT_DATATYPE_DATE", 2, TRUE);
define("EWRPT_DATATYPE_STRING", 3, TRUE);
define("EWRPT_DATATYPE_BOOLEAN", 4, TRUE);
define("EWRPT_DATATYPE_MEMO", 5, TRUE);
define("EWRPT_DATATYPE_BLOB", 6, TRUE);
define("EWRPT_DATATYPE_TIME", 7, TRUE);
define("EWRPT_DATATYPE_GUID", 8, TRUE);
define("EWRPT_DATATYPE_OTHER", 9, TRUE);

// Row types
define("EWRPT_ROWTYPE_DETAIL", 1, TRUE); // Row type detail
define("EWRPT_ROWTYPE_TOTAL", 2, TRUE); // Row type group summary

// Row total types
define("EWRPT_ROWTOTAL_GROUP", 1, TRUE); // Page summary
define("EWRPT_ROWTOTAL_PAGE", 2, TRUE); // Page summary
define("EWRPT_ROWTOTAL_GRAND", 3, TRUE); // Grand summary

// Row total sub types
define("EWRPT_ROWTOTAL_FOOTER", 1, TRUE); // Footer
define("EWRPT_ROWTOTAL_SUM", 2, TRUE); // SUM
define("EWRPT_ROWTOTAL_AVG", 3, TRUE); // AVG
define("EWRPT_ROWTOTAL_MIN", 4, TRUE); // MIN
define("EWRPT_ROWTOTAL_MAX", 5, TRUE); // MAX

// Empty/Null/Init/all values
define("EWRPT_EMPTY_VALUE", "##empty##", TRUE);
define("EWRPT_NULL_VALUE", "##null##", TRUE);
define("EWRPT_INIT_VALUE", "##init##", TRUE);
define("EWRPT_ALL_VALUE", "##all##", TRUE);

// Boolean values for ENUM('Y'/'N') or ENUM(1/0)
define("EWRPT_TRUE_STRING", "'Y'", TRUE);
define("EWRPT_FALSE_STRING", "'N'", TRUE);

// Use token in URL (reserved, not used, do NOT change!)
define("EWRPT_USE_TOKEN_IN_URL", FALSE, TRUE);

// Email
define("EWRPT_EMAIL_COMPONENT", "PHPMAILER", TRUE); // Always use PHPMAILER
define("EWRPT_SMTP_SERVER", "localhost", TRUE); // SMTP server
define("EWRPT_SMTP_SERVER_PORT", 25, TRUE); // SMTP server port
define("EWRPT_SMTP_SERVER_USERNAME", "", TRUE); // SMTP server user name
define("EWRPT_SMTP_SERVER_PASSWORD", "", TRUE); // SMTP server password
define("EWRPT_MAX_EMAIL_RECIPIENT", 3, TRUE);
define("EWRPT_MAX_EMAIL_SENT_COUNT", 3, TRUE);
define("EWRPT_MAX_EMAIL_SENT_PERIOD", 20, TRUE);
define("EWRPT_EXPORT_EMAIL_COUNTER", EWRPT_SESSION_STATUS . "_EmailCounter", TRUE);
define("EWRPT_EMAIL_CHARSET", "", TRUE); // Email charset
define("EWRPT_EMAIL_WRITE_LOG", TRUE, TRUE); // Write to log file
define("EWRPT_EMAIL_LOG_SIZE_LIMIT", 20, TRUE); // Email log field size limit
define("EWRPT_EMAIL_WRITE_LOG_TO_DATABASE", FALSE, TRUE); // Write email log to database
define("EWRPT_EMAIL_LOG_TABLE_NAME", "", TRUE); // Email log table name
define("EWRPT_EMAIL_LOG_FIELD_NAME_DATETIME", "", TRUE); // Email log DateTime field name
define("EWRPT_EMAIL_LOG_FIELD_NAME_IP", "", TRUE); // Email log IP field name
define("EWRPT_EMAIL_LOG_FIELD_NAME_SENDER", "", TRUE); // Email log Sender field name
define("EWRPT_EMAIL_LOG_FIELD_NAME_RECIPIENT", "", TRUE); // Email log Recipient field name
define("EWRPT_EMAIL_LOG_FIELD_NAME_SUBJECT", "", TRUE); // Email log Subject field name
define("EWRPT_EMAIL_LOG_FIELD_NAME_MESSAGE", "", TRUE); // Email log Message field name

// Image resize
define("EWRPT_UPLOADED_FILE_MODE", 0666, TRUE); // Uploaded file mode
define("EWRPT_UPLOAD_TMP_PATH", "", TRUE); // User upload temp path (relative to app root) e.g. "tmp/"
define("EWRPT_UPLOAD_DEST_PATH", "files/", TRUE); // Upload destination path (relative to app root)
define("EWRPT_THUMBNAIL_DEFAULT_WIDTH", 0, TRUE); // Thumbnail default width
define("EWRPT_THUMBNAIL_DEFAULT_HEIGHT", 0, TRUE); // Thumbnail default height
define("EWRPT_THUMBNAIL_DEFAULT_QUALITY", 75, TRUE); // Thumbnail default qualtity (JPEG)

// Validate option
define("EWRPT_CLIENT_VALIDATE", FALSE, TRUE);
define("EWRPT_SERVER_VALIDATE", FALSE, TRUE);

// Checkbox and radio button groups
define("EWRPT_ITEM_TEMPLATE_CLASSNAME", "ewTemplate", TRUE);
define("EWRPT_ITEM_TABLE_CLASSNAME", "ewItemTable", TRUE);

// Cookies
define("EWRPT_COOKIE_EXPIRY_TIME", time() + 365*24*60*60, TRUE); // Change cookie expiry time here

/**
 * Time zone (Note: Requires PHP 5 >= 5.1.0)
 * Read http://www.php.net/date_default_timezone_set for details
 * and http://www.php.net/timezones for supported time zones
*/
if (function_exists("date_default_timezone_set"))
	date_default_timezone_set("GMT"); // Note: Change the timezone_identifier here
if (!isset($conn)) {

	// Common objects
	$conn = NULL; // Connection
	$rs = NULL; // Recordset
	$rsgrp = NULL; // Recordset
}
if (!isset($ReportLanguage)) {
	$ReportLanguage = NULL; // Language
}

// Timer
$gsTimer = NULL;

// Used by header.php, export checking
$gsExport = "";
$gsExportFile = "";

// Used by extended filter
$gsFormError = "";

// Debug message
$gsDebugMsg = "";
if (!isset($ADODB_OUTP)) $ADODB_OUTP = 'ewrpt_SetDebugMsg';
?>
