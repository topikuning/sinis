<?php
session_start();
ob_start();
?>
<?php include "phprptinc/ewrcfg4.php"; ?>
<?php include "phprptinc/ewmysql.php"; ?>
<?php include "phprptinc/ewrfn4.php"; ?>
<?php include "phprptinc/ewrusrfn.php"; ?>
<?php

// Global variable for table object
$Laporan_Penjualan_Obat2FPasien = NULL;

//
// Table class for Laporan Penjualan Obat/Pasien
//
class crLaporan_Penjualan_Obat2FPasien {
	var $TableVar = 'Laporan_Penjualan_Obat2FPasien';
	var $TableName = 'Laporan Penjualan Obat/Pasien';
	var $TableType = 'REPORT';
	var $ShowCurrentFilter = EWRPT_SHOW_CURRENT_FILTER;
	var $FilterPanelOption = EWRPT_FILTER_PANEL_OPTION;
	var $CurrentOrder; // Current order
	var $CurrentOrderType; // Current order type

	// Table caption
	function TableCaption() {
		global $ReportLanguage;
		return $ReportLanguage->TablePhrase($this->TableVar, "TblCaption");
	}

	// Session Group Per Page
	function getGroupPerPage() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_grpperpage"];
	}

	function setGroupPerPage($v) {
		@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_grpperpage"] = $v;
	}

	// Session Start Group
	function getStartGroup() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_start"];
	}

	function setStartGroup($v) {
		@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_start"] = $v;
	}

	// Session Order By
	function getOrderBy() {
		return @$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_orderby"];
	}

	function setOrderBy($v) {
		@$_SESSION[EWRPT_PROJECT_VAR . "_" . $this->TableVar . "_orderby"] = $v;
	}

//	var $SelectLimit = TRUE;
	var $Kode_Obat;
	var $Nama_Obat;
	var $Nama_Pasien;
	var $Tanggal_Transaksi;
	var $QTY;
	var $Harga;
	var $Diskon;
	var $Jumlah;
	var $Faktur;
	var $Ruang;
	var $Status;
	var $Jam_Transaksi;
	var $fields = array();
	var $Export; // Export
	var $ExportAll = FALSE;
	var $UseTokenInUrl = EWRPT_USE_TOKEN_IN_URL;
	var $RowType; // Row type
	var $RowTotalType; // Row total type
	var $RowTotalSubType; // Row total subtype
	var $RowGroupLevel; // Row group level
	var $RowAttrs = array(); // Row attributes

	// Reset CSS styles for table object
	function ResetCSS() {
    	$this->RowAttrs["style"] = "";
		$this->RowAttrs["class"] = "";
		foreach ($this->fields as $fld) {
			$fld->ResetCSS();
		}
	}

	//
	// Table class constructor
	//
	function crLaporan_Penjualan_Obat2FPasien() {
		global $ReportLanguage;

		// Kode Obat
		$this->Kode_Obat = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Kode_Obat', 'Kode Obat', '`Kode Obat`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->Kode_Obat->GroupingFieldId = 1;
		$this->fields['Kode_Obat'] =& $this->Kode_Obat;
		$this->Kode_Obat->DateFilter = "";
		$this->Kode_Obat->SqlSelect = "";
		$this->Kode_Obat->SqlOrderBy = "";
		$this->Kode_Obat->FldGroupByType = "";
		$this->Kode_Obat->FldGroupInt = "0";
		$this->Kode_Obat->FldGroupSql = "";

		// Nama Obat
		$this->Nama_Obat = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Nama_Obat', 'Nama Obat', '`Nama Obat`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->Nama_Obat->GroupingFieldId = 2;
		$this->fields['Nama_Obat'] =& $this->Nama_Obat;
		$this->Nama_Obat->DateFilter = "";
		$this->Nama_Obat->SqlSelect = "";
		$this->Nama_Obat->SqlOrderBy = "";
		$this->Nama_Obat->FldGroupByType = "";
		$this->Nama_Obat->FldGroupInt = "0";
		$this->Nama_Obat->FldGroupSql = "";

		// Nama Pasien
		$this->Nama_Pasien = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Nama_Pasien', 'Nama Pasien', '`Nama Pasien`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->Nama_Pasien->GroupingFieldId = 3;
		$this->fields['Nama_Pasien'] =& $this->Nama_Pasien;
		$this->Nama_Pasien->DateFilter = "";
		$this->Nama_Pasien->SqlSelect = "";
		$this->Nama_Pasien->SqlOrderBy = "";
		$this->Nama_Pasien->FldGroupByType = "";
		$this->Nama_Pasien->FldGroupInt = "0";
		$this->Nama_Pasien->FldGroupSql = "";

		// Tanggal Transaksi
		$this->Tanggal_Transaksi = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Tanggal_Transaksi', 'Tanggal Transaksi', '`Tanggal Transaksi`', 133, EWRPT_DATATYPE_DATE, 7);
		$this->Tanggal_Transaksi->FldDefaultErrMsg = str_replace("%s", "/", $ReportLanguage->Phrase("IncorrectDateDMY"));
		$this->fields['Tanggal_Transaksi'] =& $this->Tanggal_Transaksi;
		$this->Tanggal_Transaksi->DateFilter = "";
		$this->Tanggal_Transaksi->SqlSelect = "";
		$this->Tanggal_Transaksi->SqlOrderBy = "";

		// QTY
		$this->QTY = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_QTY', 'QTY', '`QTY`', 20, EWRPT_DATATYPE_NUMBER, -1);
		$this->QTY->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['QTY'] =& $this->QTY;
		$this->QTY->DateFilter = "";
		$this->QTY->SqlSelect = "";
		$this->QTY->SqlOrderBy = "";

		// Harga
		$this->Harga = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Harga', 'Harga', '`Harga`', 5, EWRPT_DATATYPE_NUMBER, -1);
		$this->Harga->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['Harga'] =& $this->Harga;
		$this->Harga->DateFilter = "";
		$this->Harga->SqlSelect = "";
		$this->Harga->SqlOrderBy = "";

		// Diskon
		$this->Diskon = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Diskon', 'Diskon', '`Diskon`', 5, EWRPT_DATATYPE_NUMBER, -1);
		$this->Diskon->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['Diskon'] =& $this->Diskon;
		$this->Diskon->DateFilter = "";
		$this->Diskon->SqlSelect = "";
		$this->Diskon->SqlOrderBy = "";

		// Jumlah
		$this->Jumlah = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Jumlah', 'Jumlah', '`Jumlah`', 5, EWRPT_DATATYPE_NUMBER, -1);
		$this->Jumlah->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['Jumlah'] =& $this->Jumlah;
		$this->Jumlah->DateFilter = "";
		$this->Jumlah->SqlSelect = "";
		$this->Jumlah->SqlOrderBy = "";

		// Faktur
		$this->Faktur = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Faktur', 'Faktur', '`Faktur`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->Faktur->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['Faktur'] =& $this->Faktur;
		$this->Faktur->DateFilter = "";
		$this->Faktur->SqlSelect = "";
		$this->Faktur->SqlOrderBy = "";

		// Ruang
		$this->Ruang = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Ruang', 'Ruang', '`Ruang`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['Ruang'] =& $this->Ruang;
		$this->Ruang->DateFilter = "";
		$this->Ruang->SqlSelect = "";
		$this->Ruang->SqlOrderBy = "";

		// Status
		$this->Status = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Status', 'Status', '`Status`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['Status'] =& $this->Status;
		$this->Status->DateFilter = "";
		$this->Status->SqlSelect = "";
		$this->Status->SqlOrderBy = "";

		// Jam Transaksi
		$this->Jam_Transaksi = new crField('Laporan_Penjualan_Obat2FPasien', 'Laporan Penjualan Obat/Pasien', 'x_Jam_Transaksi', 'Jam Transaksi', '`Jam Transaksi`', 134, EWRPT_DATATYPE_TIME, 4);
		$this->Jam_Transaksi->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectTime");
		$this->fields['Jam_Transaksi'] =& $this->Jam_Transaksi;
		$this->Jam_Transaksi->DateFilter = "";
		$this->Jam_Transaksi->SqlSelect = "";
		$this->Jam_Transaksi->SqlOrderBy = "";
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
		} else {
			if ($ofld->GroupingFieldId == 0) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = "";
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fld->FldExpression, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fld->FldExpression . " " . $fld->getSort();
				} else {
					if ($sDtlSortSql <> "") $sDtlSortSql .= ", ";
					$sDtlSortSql .= $fld->FldExpression . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ",";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`penjualanperpasienobat`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		return "";
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "`Kode Obat` ASC, `Nama Obat` ASC, `Nama Pasien` ASC";
	}

	// Table Level Group SQL
	function SqlFirstGroupField() {
		return "`Kode Obat`";
	}

	function SqlSelectGroup() {
		return "SELECT DISTINCT " . $this->SqlFirstGroupField() . " FROM " . $this->SqlFrom();
	}

	function SqlOrderByGroup() {
		return "`Kode Obat` ASC";
	}

	function SqlSelectAgg() {
		return "SELECT SUM(`QTY`) AS sum_qty, SUM(`Diskon`) AS sum_diskon, SUM(`Jumlah`) AS sum_jumlah FROM " . $this->SqlFrom();
	}

	function SqlAggPfx() {
		return "";
	}

	function SqlAggSfx() {
		return "";
	}

	function SqlSelectCount() {
		return "SELECT COUNT(*) FROM " . $this->SqlFrom();
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = "order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort();
			return ewrpt_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Row attributes
	function RowAttributes() {
		$sAtt = "";
		foreach ($this->RowAttrs as $k => $v) {
			if (trim($v) <> "")
				$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
		}
		return $sAtt;
	}

	// Field object by fldvar
	function &fields($fldvar) {
		return $this->fields[$fldvar];
	}

	// Table level events
	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// Load Custom Filters event
	function CustomFilters_Load() {

		// Enter your code here	
		// ewrpt_RegisterCustomFilter($this-><Field>, 'LastMonth', 'Last Month', 'GetLastMonthFilter'); // Date example
		// ewrpt_RegisterCustomFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // String example

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//global $MyTable;
		//$MyTable->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Chart Rendering event
	function Chart_Rendering(&$chart) {

		// var_dump($chart);
	}

	// Chart Rendered event
	function Chart_Rendered($chart, &$chartxml) {

		//var_dump($chart);
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}
}
?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
?>
<?php

// Create page object
$Laporan_Penjualan_Obat2FPasien_summary = new crLaporan_Penjualan_Obat2FPasien_summary();
$Page =& $Laporan_Penjualan_Obat2FPasien_summary;

// Page init
$Laporan_Penjualan_Obat2FPasien_summary->Page_Init();

// Page main
$Laporan_Penjualan_Obat2FPasien_summary->Page_Main();
?>
<?php include "phprptinc/hApotek.php"; ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
<script type="text/javascript">

// Create page object
var Laporan_Penjualan_Obat2FPasien_summary = new ewrpt_Page("Laporan_Penjualan_Obat2FPasien_summary");

// page properties
Laporan_Penjualan_Obat2FPasien_summary.PageID = "summary"; // page ID
Laporan_Penjualan_Obat2FPasien_summary.FormID = "fLaporan_Penjualan_Obat2FPasiensummaryfilter"; // form ID
var EWRPT_PAGE_ID = Laporan_Penjualan_Obat2FPasien_summary.PageID;

// extend page with ValidateForm function
Laporan_Penjualan_Obat2FPasien_summary.ValidateForm = function(fobj) {
	if (!this.ValidateRequired)
		return true; // ignore validation
	var elm = fobj.sv1_Tanggal_Transaksi;
	if (elm && !ewrpt_CheckEuroDate(elm.value)) {
		if (!ewrpt_OnError(elm, "<?php echo ewrpt_JsEncode2($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv2_Tanggal_Transaksi;
	if (elm && !ewrpt_CheckEuroDate(elm.value)) {
		if (!ewrpt_OnError(elm, "<?php echo ewrpt_JsEncode2($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv1_Jam_Transaksi;
	if (elm && !ewrpt_CheckTime(elm.value)) {
		if (!ewrpt_OnError(elm, "<?php echo ewrpt_JsEncode2($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv2_Jam_Transaksi;
	if (elm && !ewrpt_CheckTime(elm.value)) {
		if (!ewrpt_OnError(elm, "<?php echo ewrpt_JsEncode2($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj)) return false;
	return true;
}

// extend page with Form_CustomValidate function
Laporan_Penjualan_Obat2FPasien_summary.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
<?php if (EWRPT_CLIENT_VALIDATE) { ?>
Laporan_Penjualan_Obat2FPasien_summary.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
Laporan_Penjualan_Obat2FPasien_summary.ValidateRequired = false; // no JavaScript validation
<?php } ?>
</script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-1.css" title="win2k-1" />
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script language="JavaScript" type="text/javascript">
<!--

// Write your client script here, no need to add script tags.
// To include another .js script, use:
// ew_ClientScriptInclude("my_javascript.js"); 
//-->

</script>
<?php } ?>
<?php $Laporan_Penjualan_Obat2FPasien_summary->ShowPageHeader(); ?>
<?php $Laporan_Penjualan_Obat2FPasien_summary->ShowMessage(); ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "" || $Laporan_Penjualan_Obat2FPasien->Export == "print" || $Laporan_Penjualan_Obat2FPasien->Export == "email") { ?>
<script src="FusionChartsFree/JSClass/FusionCharts.js" type="text/javascript"></script>
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
<script src="phprptjs/popup.js" type="text/javascript"></script>
<script src="phprptjs/ewrptpop.js" type="text/javascript"></script>
<script type="text/javascript">

// popup fields
</script>
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
<!-- Table Container (Begin) -->
<table id="ewContainer" cellspacing="0" cellpadding="0" border="0">
<!-- Top Container (Begin) -->
<tr><td colspan="3"><div id="ewTop" class="phpreportmaker">
<!-- top slot -->
<a name="top"></a>
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "" || $Laporan_Penjualan_Obat2FPasien->Export == "print" || $Laporan_Penjualan_Obat2FPasien->Export == "email") { ?>
<?php } ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->TableCaption() ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
&nbsp;&nbsp;<a href="<?php echo $Laporan_Penjualan_Obat2FPasien_summary->ExportPrintUrl ?>"><?php echo $ReportLanguage->Phrase("PrinterFriendly") ?></a>
&nbsp;&nbsp;<a href="<?php echo $Laporan_Penjualan_Obat2FPasien_summary->ExportExcelUrl ?>"><?php echo $ReportLanguage->Phrase("ExportToExcel") ?></a>
<?php if ($Laporan_Penjualan_Obat2FPasien_summary->FilterApplied) { ?>
&nbsp;&nbsp;<a href="Laporan_Penjualan_Obat2FPasiensmry.php?cmd=reset"><?php echo $ReportLanguage->Phrase("ResetAllFilter") ?></a>
<?php } ?>
<?php } ?>
<br /><br />
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
</div></td></tr>
<!-- Top Container (End) -->
<tr>
	<!-- Left Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewLeft" class="phpreportmaker">
	<!-- Left slot -->
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "" || $Laporan_Penjualan_Obat2FPasien->Export == "print" || $Laporan_Penjualan_Obat2FPasien->Export == "email") { ?>
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
	</div></td>
	<!-- Left Container (End) -->
	<!-- Center Container - Report (Begin) -->
	<td style="vertical-align: top;" class="ewPadding"><div id="ewCenter" class="phpreportmaker">
	<!-- center slot -->
<?php } ?>
<!-- summary report starts -->
<div id="report_summary">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
<?php
if ($Laporan_Penjualan_Obat2FPasien->FilterPanelOption == 2 || ($Laporan_Penjualan_Obat2FPasien->FilterPanelOption == 3 && $Laporan_Penjualan_Obat2FPasien_summary->FilterApplied) || $Laporan_Penjualan_Obat2FPasien_summary->Filter == "0=101") {
	$sButtonImage = "phprptimages/collapse.gif";
	$sDivDisplay = "";
} else {
	$sButtonImage = "phprptimages/expand.gif";
	$sDivDisplay = " style=\"display: none;\"";
}
?>
<a href="javascript:ewrpt_ToggleFilterPanel();" style="text-decoration: none;"><img id="ewrptToggleFilterImg" src="<?php echo $sButtonImage ?>" alt="" width="9" height="9" border="0"></a><span class="phpreportmaker">&nbsp;<?php echo $ReportLanguage->Phrase("Filters") ?></span><br /><br />
<div id="ewrptExtFilterPanel"<?php echo $sDivDisplay ?>>
<!-- Search form (begin) -->
<form name="fLaporan_Penjualan_Obat2FPasiensummaryfilter" id="fLaporan_Penjualan_Obat2FPasiensummaryfilter" action="Laporan_Penjualan_Obat2FPasiensmry.php" class="ewForm" onsubmit="return Laporan_Penjualan_Obat2FPasien_summary.ValidateForm(this);">
<table class="ewRptExtFilter">
	<tr>
		<td><span class="phpreportmaker"><?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Obat->FldCaption() ?></span></td>
		<td></td>
		<td colspan="4"><span class="ewRptSearchOpr">
		<select name="sv_Nama_Obat[]" id="sv_Nama_Obat[]" multiple<?php echo ($Laporan_Penjualan_Obat2FPasien_summary->ClearExtFilter == 'Laporan_Penjualan_Obat2FPasien_Nama_Obat') ? " class=\"ewInputCleared\"" : "" ?>>
		<option value="<?php echo EWRPT_ALL_VALUE; ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownValue, EWRPT_ALL_VALUE)) echo " selected=\"selected\""; ?>><?php echo $ReportLanguage->Phrase("SelectAll"); ?></option>
<?php

// Popup filter
$cntf = is_array($Laporan_Penjualan_Obat2FPasien->Nama_Obat->CustomFilters) ? count($Laporan_Penjualan_Obat2FPasien->Nama_Obat->CustomFilters) : 0;
$cntd = is_array($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownList) ? count($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownList) : 0;
$totcnt = $cntf + $cntd;
$wrkcnt = 0;
	for ($i = 0; $i < $cntf; $i++) {
		if ($Laporan_Penjualan_Obat2FPasien->Nama_Obat->CustomFilters[$i]->FldName == 'Nama Obat') {
?>
		<option value="<?php echo "@@" . $Laporan_Penjualan_Obat2FPasien->Nama_Obat->CustomFilters[$i]->FilterName ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownValue, "@@" . $Laporan_Penjualan_Obat2FPasien->Nama_Obat->CustomFilters[$i]->FilterName)) echo " selected=\"selected\"" ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Obat->CustomFilters[$i]->DisplayName ?></option>
<?php
		}
		$wrkcnt += 1;
	}

//}
	for ($i = 0; $i < $cntd; $i++) {
?>
		<option value="<?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownList[$i] ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownValue, $Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownList[$i])) echo " selected=\"selected\"" ?>><?php echo ewrpt_DropDownDisplayValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}

//}
?>
		</select>
		</span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->FldCaption() ?></span></td>
		<td><span class="ewRptSearchOpr"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so1_Tanggal_Transaksi" id="so1_Tanggal_Transaksi" value="BETWEEN"></span></td>
		<td>
			<table cellspacing="0" class="ewItemTable"><tr>
				<td><span class="phpreportmaker">
<input type="text" name="sv1_Tanggal_Transaksi" id="sv1_Tanggal_Transaksi" value="<?php echo ewrpt_HtmlEncode($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchValue) ?>"<?php echo ($Laporan_Penjualan_Obat2FPasien_summary->ClearExtFilter == 'Laporan_Penjualan_Obat2FPasien_Tanggal_Transaksi') ? " class=\"ewInputCleared\"" : "" ?>>
<img src="phprptimages/calendar.png" id="csv1_Tanggal_Transaksi" alt="<?php echo $ReportLanguage->Phrase("PickDate"); ?>" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup({
	inputField : "sv1_Tanggal_Transaksi", // ID of the input field
	ifFormat : "%d/%m/%Y", // the date format
	button : "csv1_Tanggal_Transaksi" // ID of the button
})
</script>
</span></td>
				<td><span class="ewRptSearchOpr" id="btw1_Tanggal_Transaksi" name="btw1_Tanggal_Transaksi">&nbsp;<?php echo $ReportLanguage->Phrase("AND") ?>&nbsp;</span></td>
				<td><span class="phpreportmaker" id="btw1_Tanggal_Transaksi" name="btw1_Tanggal_Transaksi">
<input type="text" name="sv2_Tanggal_Transaksi" id="sv2_Tanggal_Transaksi" value="<?php echo ewrpt_HtmlEncode($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchValue2) ?>"<?php echo ($Laporan_Penjualan_Obat2FPasien_summary->ClearExtFilter == 'Laporan_Penjualan_Obat2FPasien_Tanggal_Transaksi') ? " class=\"ewInputCleared\"" : "" ?>>
<img src="phprptimages/calendar.png" id="csv2_Tanggal_Transaksi" alt="<?php echo $ReportLanguage->Phrase("PickDate"); ?>" style="cursor:pointer;cursor:hand;">
<script type="text/javascript">
Calendar.setup({
	inputField : "sv2_Tanggal_Transaksi", // ID of the input field
	ifFormat : "%d/%m/%Y", // the date format
	button : "csv2_Tanggal_Transaksi" // ID of the button
})
</script>
</span></td>
			</tr></table>			
		</td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $Laporan_Penjualan_Obat2FPasien->Ruang->FldCaption() ?></span></td>
		<td></td>
		<td colspan="4"><span class="ewRptSearchOpr">
		<select name="sv_Ruang" id="sv_Ruang"<?php echo ($Laporan_Penjualan_Obat2FPasien_summary->ClearExtFilter == 'Laporan_Penjualan_Obat2FPasien_Ruang') ? " class=\"ewInputCleared\"" : "" ?>>
		<option value="<?php echo EWRPT_ALL_VALUE; ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownValue, EWRPT_ALL_VALUE)) echo " selected=\"selected\""; ?>><?php echo $ReportLanguage->Phrase("PleaseSelect"); ?></option>
<?php

// Popup filter
$cntf = is_array($Laporan_Penjualan_Obat2FPasien->Ruang->CustomFilters) ? count($Laporan_Penjualan_Obat2FPasien->Ruang->CustomFilters) : 0;
$cntd = is_array($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownList) ? count($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownList) : 0;
$totcnt = $cntf + $cntd;
$wrkcnt = 0;
	for ($i = 0; $i < $cntf; $i++) {
		if ($Laporan_Penjualan_Obat2FPasien->Ruang->CustomFilters[$i]->FldName == 'Ruang') {
?>
		<option value="<?php echo "@@" . $Laporan_Penjualan_Obat2FPasien->Ruang->CustomFilters[$i]->FilterName ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownValue, "@@" . $Laporan_Penjualan_Obat2FPasien->Ruang->CustomFilters[$i]->FilterName)) echo " selected=\"selected\"" ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Ruang->CustomFilters[$i]->DisplayName ?></option>
<?php
		}
		$wrkcnt += 1;
	}

//}
	for ($i = 0; $i < $cntd; $i++) {
?>
		<option value="<?php echo $Laporan_Penjualan_Obat2FPasien->Ruang->DropDownList[$i] ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownValue, $Laporan_Penjualan_Obat2FPasien->Ruang->DropDownList[$i])) echo " selected=\"selected\"" ?>><?php echo ewrpt_DropDownDisplayValue($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}

//}
?>
		</select>
		</span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $Laporan_Penjualan_Obat2FPasien->Status->FldCaption() ?></span></td>
		<td></td>
		<td colspan="4"><span class="ewRptSearchOpr">
		<select name="sv_Status" id="sv_Status"<?php echo ($Laporan_Penjualan_Obat2FPasien_summary->ClearExtFilter == 'Laporan_Penjualan_Obat2FPasien_Status') ? " class=\"ewInputCleared\"" : "" ?>>
		<option value="<?php echo EWRPT_ALL_VALUE; ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Penjualan_Obat2FPasien->Status->DropDownValue, EWRPT_ALL_VALUE)) echo " selected=\"selected\""; ?>><?php echo $ReportLanguage->Phrase("PleaseSelect"); ?></option>
<?php

// Popup filter
$cntf = is_array($Laporan_Penjualan_Obat2FPasien->Status->CustomFilters) ? count($Laporan_Penjualan_Obat2FPasien->Status->CustomFilters) : 0;
$cntd = is_array($Laporan_Penjualan_Obat2FPasien->Status->DropDownList) ? count($Laporan_Penjualan_Obat2FPasien->Status->DropDownList) : 0;
$totcnt = $cntf + $cntd;
$wrkcnt = 0;
	for ($i = 0; $i < $cntf; $i++) {
		if ($Laporan_Penjualan_Obat2FPasien->Status->CustomFilters[$i]->FldName == 'Status') {
?>
		<option value="<?php echo "@@" . $Laporan_Penjualan_Obat2FPasien->Status->CustomFilters[$i]->FilterName ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Penjualan_Obat2FPasien->Status->DropDownValue, "@@" . $Laporan_Penjualan_Obat2FPasien->Status->CustomFilters[$i]->FilterName)) echo " selected=\"selected\"" ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Status->CustomFilters[$i]->DisplayName ?></option>
<?php
		}
		$wrkcnt += 1;
	}

//}
	for ($i = 0; $i < $cntd; $i++) {
?>
		<option value="<?php echo $Laporan_Penjualan_Obat2FPasien->Status->DropDownList[$i] ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Penjualan_Obat2FPasien->Status->DropDownValue, $Laporan_Penjualan_Obat2FPasien->Status->DropDownList[$i])) echo " selected=\"selected\"" ?>><?php echo ewrpt_DropDownDisplayValue($Laporan_Penjualan_Obat2FPasien->Status->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}

//}
?>
		</select>
		</span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->FldCaption() ?></span></td>
		<td><span class="ewRptSearchOpr"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so1_Jam_Transaksi" id="so1_Jam_Transaksi" value="BETWEEN"></span></td>
		<td>
			<table cellspacing="0" class="ewItemTable"><tr>
				<td><span class="phpreportmaker">
<input type="text" name="sv1_Jam_Transaksi" id="sv1_Jam_Transaksi" size="30" value="<?php echo ewrpt_HtmlEncode($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchValue) ?>"<?php echo ($Laporan_Penjualan_Obat2FPasien_summary->ClearExtFilter == 'Laporan_Penjualan_Obat2FPasien_Jam_Transaksi') ? " class=\"ewInputCleared\"" : "" ?>>
</span></td>
				<td><span class="ewRptSearchOpr" id="btw1_Jam_Transaksi" name="btw1_Jam_Transaksi">&nbsp;<?php echo $ReportLanguage->Phrase("AND") ?>&nbsp;</span></td>
				<td><span class="phpreportmaker" id="btw1_Jam_Transaksi" name="btw1_Jam_Transaksi">
<input type="text" name="sv2_Jam_Transaksi" id="sv2_Jam_Transaksi" size="30" value="<?php echo ewrpt_HtmlEncode($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchValue2) ?>"<?php echo ($Laporan_Penjualan_Obat2FPasien_summary->ClearExtFilter == 'Laporan_Penjualan_Obat2FPasien_Jam_Transaksi') ? " class=\"ewInputCleared\"" : "" ?>>
</span></td>
			</tr></table>			
		</td>
	</tr>
</table>
<table class="ewRptExtFilter">
	<tr>
		<td><span class="phpreportmaker">
			<input type="Submit" name="Submit" id="Submit" value="<?php echo $ReportLanguage->Phrase("Search") ?>">&nbsp;
			<input type="Reset" name="Reset" id="Reset" value="<?php echo $ReportLanguage->Phrase("Reset") ?>">&nbsp;
		</span></td>
	</tr>
</table>
</form>
<!-- Search form (end) -->
</div>
<br />
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->ShowCurrentFilter) { ?>
<div id="ewrptFilterList">
<?php $Laporan_Penjualan_Obat2FPasien_summary->ShowFilterList() ?>
</div>
<br />
<?php } ?>
<table class="ewGrid" cellspacing="0"><tr>
	<td class="ewGridContent">
<!-- Report Grid (Begin) -->
<div class="ewGridMiddlePanel">
<table class="ewTable ewTableSeparate" cellspacing="0">
<?php

// Set the last group to display if not export all
if ($Laporan_Penjualan_Obat2FPasien->ExportAll && $Laporan_Penjualan_Obat2FPasien->Export <> "") {
	$Laporan_Penjualan_Obat2FPasien_summary->StopGrp = $Laporan_Penjualan_Obat2FPasien_summary->TotalGrps;
} else {
	$Laporan_Penjualan_Obat2FPasien_summary->StopGrp = $Laporan_Penjualan_Obat2FPasien_summary->StartGrp + $Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Laporan_Penjualan_Obat2FPasien_summary->StopGrp) > intval($Laporan_Penjualan_Obat2FPasien_summary->TotalGrps))
	$Laporan_Penjualan_Obat2FPasien_summary->StopGrp = $Laporan_Penjualan_Obat2FPasien_summary->TotalGrps;
$Laporan_Penjualan_Obat2FPasien_summary->RecCount = 0;

// Get first row
if ($Laporan_Penjualan_Obat2FPasien_summary->TotalGrps > 0) {
	$Laporan_Penjualan_Obat2FPasien_summary->GetGrpRow(1);
	$Laporan_Penjualan_Obat2FPasien_summary->GrpCount = 1;
}
while (($rsgrp && !$rsgrp->EOF && $Laporan_Penjualan_Obat2FPasien_summary->GrpCount <= $Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps) || $Laporan_Penjualan_Obat2FPasien_summary->ShowFirstHeader) {

	// Show header
	if ($Laporan_Penjualan_Obat2FPasien_summary->ShowFirstHeader) {
?>
	<thead>
	<tr>
<td class="ewTableHeader">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export <> "") { ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->Kode_Obat->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Kode_Obat) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Penjualan_Obat2FPasien->Kode_Obat->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Kode_Obat) ?>',1);"><?php echo $Laporan_Penjualan_Obat2FPasien->Kode_Obat->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Penjualan_Obat2FPasien->Kode_Obat->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Penjualan_Obat2FPasien->Kode_Obat->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export <> "") { ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Obat->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Nama_Obat) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Obat->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Nama_Obat) ?>',1);"><?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Obat->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Penjualan_Obat2FPasien->Nama_Obat->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Penjualan_Obat2FPasien->Nama_Obat->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export <> "") { ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Nama_Pasien) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Nama_Pasien) ?>',1);"><?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Penjualan_Obat2FPasien->Nama_Pasien->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Penjualan_Obat2FPasien->Nama_Pasien->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export <> "") { ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi) ?>',1);"><?php echo $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export <> "") { ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->QTY->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->QTY) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Penjualan_Obat2FPasien->QTY->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->QTY) ?>',1);"><?php echo $Laporan_Penjualan_Obat2FPasien->QTY->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Penjualan_Obat2FPasien->QTY->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Penjualan_Obat2FPasien->QTY->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export <> "") { ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->Harga->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Harga) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Penjualan_Obat2FPasien->Harga->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Harga) ?>',1);"><?php echo $Laporan_Penjualan_Obat2FPasien->Harga->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Penjualan_Obat2FPasien->Harga->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Penjualan_Obat2FPasien->Harga->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export <> "") { ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->Diskon->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Diskon) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Penjualan_Obat2FPasien->Diskon->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Diskon) ?>',1);"><?php echo $Laporan_Penjualan_Obat2FPasien->Diskon->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Penjualan_Obat2FPasien->Diskon->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Penjualan_Obat2FPasien->Diskon->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export <> "") { ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->Jumlah->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Jumlah) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Penjualan_Obat2FPasien->Jumlah->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Jumlah) ?>',1);"><?php echo $Laporan_Penjualan_Obat2FPasien->Jumlah->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Penjualan_Obat2FPasien->Jumlah->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Penjualan_Obat2FPasien->Jumlah->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Penjualan_Obat2FPasien->Export <> "") { ?>
<?php echo $Laporan_Penjualan_Obat2FPasien->Faktur->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Faktur) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Penjualan_Obat2FPasien->Faktur->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Penjualan_Obat2FPasien->SortUrl($Laporan_Penjualan_Obat2FPasien->Faktur) ?>',1);"><?php echo $Laporan_Penjualan_Obat2FPasien->Faktur->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Penjualan_Obat2FPasien->Faktur->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Penjualan_Obat2FPasien->Faktur->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
	</tr>
	</thead>
	<tbody>
<?php
		$Laporan_Penjualan_Obat2FPasien_summary->ShowFirstHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewrpt_DetailFilterSQL($Laporan_Penjualan_Obat2FPasien->Kode_Obat, $Laporan_Penjualan_Obat2FPasien->SqlFirstGroupField(), $Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupValue());
	if ($Laporan_Penjualan_Obat2FPasien_summary->Filter != "")
		$sWhere = "($Laporan_Penjualan_Obat2FPasien_summary->Filter) AND ($sWhere)";
	$sSql = ewrpt_BuildReportSql($Laporan_Penjualan_Obat2FPasien->SqlSelect(), $Laporan_Penjualan_Obat2FPasien->SqlWhere(), $Laporan_Penjualan_Obat2FPasien->SqlGroupBy(), $Laporan_Penjualan_Obat2FPasien->SqlHaving(), $Laporan_Penjualan_Obat2FPasien->SqlOrderBy(), $sWhere, $Laporan_Penjualan_Obat2FPasien_summary->Sort);
	$rs = $conn->Execute($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Laporan_Penjualan_Obat2FPasien_summary->GetRow(1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$Laporan_Penjualan_Obat2FPasien_summary->RecCount++;

		// Render detail row
		$Laporan_Penjualan_Obat2FPasien->ResetCSS();
		$Laporan_Penjualan_Obat2FPasien->RowType = EWRPT_ROWTYPE_DETAIL;
		$Laporan_Penjualan_Obat2FPasien_summary->RenderRow();
?>
	<tr<?php echo $Laporan_Penjualan_Obat2FPasien->RowAttributes(); ?>>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Kode_Obat->CellAttributes(); ?>><div<?php echo $Laporan_Penjualan_Obat2FPasien->Kode_Obat->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupViewValue; ?></div></td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Obat->CellAttributes(); ?>><div<?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Obat->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupViewValue; ?></div></td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->CellAttributes(); ?>><div<?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupViewValue; ?></div></td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->CellAttributes() ?>>
<div<?php echo $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->QTY->CellAttributes() ?>>
<div<?php echo $Laporan_Penjualan_Obat2FPasien->QTY->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->QTY->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Harga->CellAttributes() ?>>
<div<?php echo $Laporan_Penjualan_Obat2FPasien->Harga->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Harga->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Diskon->CellAttributes() ?>>
<div<?php echo $Laporan_Penjualan_Obat2FPasien->Diskon->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Diskon->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Jumlah->CellAttributes() ?>>
<div<?php echo $Laporan_Penjualan_Obat2FPasien->Jumlah->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Jumlah->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Faktur->CellAttributes() ?>>
<div<?php echo $Laporan_Penjualan_Obat2FPasien->Faktur->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Faktur->ListViewValue(); ?></div>
</td>
	</tr>
<?php

		// Accumulate page summary
		$Laporan_Penjualan_Obat2FPasien_summary->AccumulateSummary();

		// Get next record
		$Laporan_Penjualan_Obat2FPasien_summary->GetRow(2);

		// Show Footers
?>
<?php
	} // End detail records loop
?>
<?php

	// Next group
	$Laporan_Penjualan_Obat2FPasien_summary->GetGrpRow(2);
	$Laporan_Penjualan_Obat2FPasien_summary->GrpCount++;
} // End while
?>
	</tbody>
	<tfoot>
<?php
if ($Laporan_Penjualan_Obat2FPasien_summary->TotalGrps > 0) {
	$Laporan_Penjualan_Obat2FPasien->ResetCSS();
	$Laporan_Penjualan_Obat2FPasien->RowType = EWRPT_ROWTYPE_TOTAL;
	$Laporan_Penjualan_Obat2FPasien->RowTotalType = EWRPT_ROWTOTAL_GRAND;
	$Laporan_Penjualan_Obat2FPasien->RowTotalSubType = EWRPT_ROWTOTAL_FOOTER;
	$Laporan_Penjualan_Obat2FPasien->RowAttrs["class"] = "ewRptGrandSummary";
	$Laporan_Penjualan_Obat2FPasien_summary->RenderRow();
?>
	<!-- tr><td colspan="9"><span class="phpreportmaker">&nbsp;<br /></span></td></tr -->
	<tr<?php echo $Laporan_Penjualan_Obat2FPasien->RowAttributes(); ?>><td colspan="9"><?php echo $ReportLanguage->Phrase("RptGrandTotal") ?> (<?php echo ewrpt_FormatNumber($Laporan_Penjualan_Obat2FPasien_summary->TotCount,0,-2,-2,-2); ?> <?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</td></tr>
<?php
	$Laporan_Penjualan_Obat2FPasien->ResetCSS();
	$Laporan_Penjualan_Obat2FPasien->QTY->Count = $Laporan_Penjualan_Obat2FPasien_summary->TotCount;
	$Laporan_Penjualan_Obat2FPasien->QTY->Summary = $Laporan_Penjualan_Obat2FPasien_summary->GrandSmry[2]; // Load SUM
	$Laporan_Penjualan_Obat2FPasien->RowTotalSubType = EWRPT_ROWTOTAL_SUM;
	$Laporan_Penjualan_Obat2FPasien->Diskon->Count = $Laporan_Penjualan_Obat2FPasien_summary->TotCount;
	$Laporan_Penjualan_Obat2FPasien->Diskon->Summary = $Laporan_Penjualan_Obat2FPasien_summary->GrandSmry[4]; // Load SUM
	$Laporan_Penjualan_Obat2FPasien->RowTotalSubType = EWRPT_ROWTOTAL_SUM;
	$Laporan_Penjualan_Obat2FPasien->Jumlah->Count = $Laporan_Penjualan_Obat2FPasien_summary->TotCount;
	$Laporan_Penjualan_Obat2FPasien->Jumlah->Summary = $Laporan_Penjualan_Obat2FPasien_summary->GrandSmry[5]; // Load SUM
	$Laporan_Penjualan_Obat2FPasien->RowTotalSubType = EWRPT_ROWTOTAL_SUM;
	$Laporan_Penjualan_Obat2FPasien->Jumlah->CurrentValue = $Laporan_Penjualan_Obat2FPasien->Jumlah->Summary;
	$Laporan_Penjualan_Obat2FPasien->RowAttrs["class"] = "ewRptGrandSummary";
	$Laporan_Penjualan_Obat2FPasien_summary->RenderRow();
?>
	<tr<?php echo $Laporan_Penjualan_Obat2FPasien->RowAttributes(); ?>>
		<td colspan="3" class="ewRptGrpAggregate"><?php echo $ReportLanguage->Phrase("RptSum"); ?></td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->QTY->CellAttributes() ?>>
<div<?php echo $Laporan_Penjualan_Obat2FPasien->QTY->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->QTY->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Harga->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Diskon->CellAttributes() ?>>
<div<?php echo $Laporan_Penjualan_Obat2FPasien->Diskon->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Diskon->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Jumlah->CellAttributes() ?>>
<div<?php echo $Laporan_Penjualan_Obat2FPasien->Jumlah->ViewAttributes(); ?>><?php echo $Laporan_Penjualan_Obat2FPasien->Jumlah->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Penjualan_Obat2FPasien->Faktur->CellAttributes() ?>>&nbsp;</td>
	</tr>
<?php } ?>
	</tfoot>
</table>
</div>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
<div class="ewGridLowerPanel">
<form action="Laporan_Penjualan_Obat2FPasiensmry.php" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($Laporan_Penjualan_Obat2FPasien_summary->StartGrp, $Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps, $Laporan_Penjualan_Obat2FPasien_summary->TotalGrps) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<td><a href="Laporan_Penjualan_Obat2FPasiensmry.php?start=<?php echo $Pager->FirstButton->Start ?>"><img src="phprptimages/first.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/firstdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<td><a href="Laporan_Penjualan_Obat2FPasiensmry.php?start=<?php echo $Pager->PrevButton->Start ?>"><img src="phprptimages/prev.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/prevdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" id="pageno" value="<?php echo $Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($Pager->NextButton->Enabled) { ?>
	<td><a href="Laporan_Penjualan_Obat2FPasiensmry.php?start=<?php echo $Pager->NextButton->Start ?>"><img src="phprptimages/next.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/nextdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($Pager->LastButton->Enabled) { ?>
	<td><a href="Laporan_Penjualan_Obat2FPasiensmry.php?start=<?php echo $Pager->LastButton->Start ?>"><img src="phprptimages/last.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/lastdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
	<td><span class="phpreportmaker">&nbsp;<?php echo $ReportLanguage->Phrase("of") ?> <?php echo $Pager->PageCount ?></span></td>
	</tr></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Record") ?> <?php echo $Pager->FromIndex ?> <?php echo $ReportLanguage->Phrase("To") ?> <?php echo $Pager->ToIndex ?> <?php echo $ReportLanguage->Phrase("Of") ?> <?php echo $Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($Laporan_Penjualan_Obat2FPasien_summary->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($Laporan_Penjualan_Obat2FPasien_summary->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="100"<?php if ($Laporan_Penjualan_Obat2FPasien_summary->DisplayGrps == 100) echo " selected=\"selected\"" ?>>100</option>
<option value="ALL"<?php if ($Laporan_Penjualan_Obat2FPasien->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
</div>
<?php } ?>
</td></tr></table>
</div>
<!-- Summary Report Ends -->
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
	</div><br /></td>
	<!-- Center Container - Report (End) -->
	<!-- Right Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewRight" class="phpreportmaker">
	<!-- Right slot -->
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "" || $Laporan_Penjualan_Obat2FPasien->Export == "print" || $Laporan_Penjualan_Obat2FPasien->Export == "email") { ?>
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
	</div></td>
	<!-- Right Container (End) -->
</tr>
<!-- Bottom Container (Begin) -->
<tr><td colspan="3"><div id="ewBottom" class="phpreportmaker">
	<!-- Bottom slot -->
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "" || $Laporan_Penjualan_Obat2FPasien->Export == "print" || $Laporan_Penjualan_Obat2FPasien->Export == "email") { ?>
<?php } ?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
	</div><br /></td></tr>
<!-- Bottom Container (End) -->
</table>
<!-- Table Container (End) -->
<?php } ?>
<?php $Laporan_Penjualan_Obat2FPasien_summary->ShowPageFooter(); ?>
<?php if (EWRPT_DEBUG_ENABLED) echo ewrpt_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Laporan_Penjualan_Obat2FPasien->Export == "") { ?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php } ?>
<?php include "phprptinc/footer.php"; ?>
<?php
$Laporan_Penjualan_Obat2FPasien_summary->Page_Terminate();
?>
<?php

//
// Page class
//
class crLaporan_Penjualan_Obat2FPasien_summary {

	// Page ID
	var $PageID = 'summary';

	// Table name
	var $TableName = 'Laporan Penjualan Obat/Pasien';

	// Page object name
	var $PageObjName = 'Laporan_Penjualan_Obat2FPasien_summary';

	// Page name
	function PageName() {
		return ewrpt_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewrpt_CurrentPage() . "?";
		global $Laporan_Penjualan_Obat2FPasien;
		if ($Laporan_Penjualan_Obat2FPasien->UseTokenInUrl) $PageUrl .= "t=" . $Laporan_Penjualan_Obat2FPasien->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EWRPT_SESSION_MESSAGE];
	}

	function setMessage($v) {
		if (@$_SESSION[EWRPT_SESSION_MESSAGE] <> "") { // Append
			$_SESSION[EWRPT_SESSION_MESSAGE] .= "<br />" . $v;
		} else {
			$_SESSION[EWRPT_SESSION_MESSAGE] = $v;
		}
	}

	// Show message
	function ShowMessage() {
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage);
		if ($sMessage <> "") { // Message in Session, display
			echo "<p><span class=\"ewMessage\">" . $sMessage . "</span></p>";
			$_SESSION[EWRPT_SESSION_MESSAGE] = ""; // Clear message in Session
		}
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p><span class=\"phpreportmaker\">" . $sHeader . "</span></p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p><span class=\"phpreportmaker\">" . $sFooter . "</span></p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $Laporan_Penjualan_Obat2FPasien;
		if ($Laporan_Penjualan_Obat2FPasien->UseTokenInUrl) {
			if (ewrpt_IsHttpPost())
				return ($Laporan_Penjualan_Obat2FPasien->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($Laporan_Penjualan_Obat2FPasien->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function crLaporan_Penjualan_Obat2FPasien_summary() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Table object (Laporan_Penjualan_Obat2FPasien)
		$GLOBALS["Laporan_Penjualan_Obat2FPasien"] = new crLaporan_Penjualan_Obat2FPasien();

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EWRPT_PAGE_ID"))
			define("EWRPT_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWRPT_TABLE_NAME"))
			define("EWRPT_TABLE_NAME", 'Laporan Penjualan Obat/Pasien', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		$conn = ewrpt_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $ReportLanguage, $Security;
		global $Laporan_Penjualan_Obat2FPasien;

	// Get export parameters
	if (@$_GET["export"] <> "") {
		$Laporan_Penjualan_Obat2FPasien->Export = $_GET["export"];
	}
	$gsExport = $Laporan_Penjualan_Obat2FPasien->Export; // Get export parameter, used in header
	$gsExportFile = $Laporan_Penjualan_Obat2FPasien->TableVar; // Get export file, used in header
	if ($Laporan_Penjualan_Obat2FPasien->Export == "excel") {
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=' . $gsExportFile .'.xls');
	}

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;
		global $ReportLanguage;
		global $Laporan_Penjualan_Obat2FPasien;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export to Email (use ob_file_contents for PHP)
		if ($Laporan_Penjualan_Obat2FPasien->Export == "email") {
			$sContent = ob_get_contents();
			$this->ExportEmail($sContent);
			ob_end_clean();

			 // Close connection
			$conn->Close();
			header("Location: " . ewrpt_CurrentPage());
			exit();
		}

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWRPT_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	// Paging variables

	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $DisplayGrps = 100; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $UserIDFilter = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $FilterApplied;
	var $ShowFirstHeader;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;

	//
	// Page main
	//
	function Page_Main() {
		global $Laporan_Penjualan_Obat2FPasien;
		global $rs;
		global $rsgrp;
		global $gsFormError;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 7;
		$nGrps = 4;
		$this->Val = ewrpt_InitArray($nDtls, 0);
		$this->Cnt = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandSmry = ewrpt_InitArray($nDtls, 0);
		$this->GrandMn = ewrpt_InitArray($nDtls, NULL);
		$this->GrandMx = ewrpt_InitArray($nDtls, NULL);

		// Set up if accumulation required
		$this->Col = array(FALSE, FALSE, TRUE, FALSE, TRUE, TRUE, FALSE);

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Load default filter values
		$this->LoadDefaultFilters();

		// Set up popup filter
		$this->SetupPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Get dropdown values
		$this->GetExtendedFilterValues();

		// Load custom filters
		$Laporan_Penjualan_Obat2FPasien->CustomFilters_Load();

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		if ($sExtendedFilter <> "") {
			if ($this->Filter <> "")
  				$this->Filter = "($this->Filter) AND ($sExtendedFilter)";
			else
				$this->Filter = $sExtendedFilter;
		}

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewrpt_SetDebugMsg("popup filter: " . $sPopupFilter);
		if ($sPopupFilter <> "") {
			if ($this->Filter <> "")
				$this->Filter = "($this->Filter) AND ($sPopupFilter)";
			else
				$this->Filter = $sPopupFilter;
		}

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Requires search criteria
		if (!$this->FilterApplied)
			$this->Filter = "0=101";

		// Get sort
		$this->Sort = $this->GetSort();

		// Get total group count
		$sGrpSort = ewrpt_UpdateSortFields($Laporan_Penjualan_Obat2FPasien->SqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewrpt_BuildReportSql($Laporan_Penjualan_Obat2FPasien->SqlSelectGroup(), $Laporan_Penjualan_Obat2FPasien->SqlWhere(), $Laporan_Penjualan_Obat2FPasien->SqlGroupBy(), $Laporan_Penjualan_Obat2FPasien->SqlHaving(), $Laporan_Penjualan_Obat2FPasien->SqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowFirstHeader = ($this->TotalGrps > 0);

		//$this->ShowFirstHeader = TRUE; // Uncomment to always show header
		// Set up start position if not export all

		if ($Laporan_Penjualan_Obat2FPasien->ExportAll && $Laporan_Penjualan_Obat2FPasien->Export <> "")
		    $this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup(); 

		// Get current page groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		global $Laporan_Penjualan_Obat2FPasien;
		switch ($lvl) {
			case 1:
				return (is_null($Laporan_Penjualan_Obat2FPasien->Kode_Obat->CurrentValue) && !is_null($Laporan_Penjualan_Obat2FPasien->Kode_Obat->OldValue)) ||
					(!is_null($Laporan_Penjualan_Obat2FPasien->Kode_Obat->CurrentValue) && is_null($Laporan_Penjualan_Obat2FPasien->Kode_Obat->OldValue)) ||
					($Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupValue() <> $Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupOldValue());
			case 2:
				return (is_null($Laporan_Penjualan_Obat2FPasien->Nama_Obat->CurrentValue) && !is_null($Laporan_Penjualan_Obat2FPasien->Nama_Obat->OldValue)) ||
					(!is_null($Laporan_Penjualan_Obat2FPasien->Nama_Obat->CurrentValue) && is_null($Laporan_Penjualan_Obat2FPasien->Nama_Obat->OldValue)) ||
					($Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupValue() <> $Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
			case 3:
				return (is_null($Laporan_Penjualan_Obat2FPasien->Nama_Pasien->CurrentValue) && !is_null($Laporan_Penjualan_Obat2FPasien->Nama_Pasien->OldValue)) ||
					(!is_null($Laporan_Penjualan_Obat2FPasien->Nama_Pasien->CurrentValue) && is_null($Laporan_Penjualan_Obat2FPasien->Nama_Pasien->OldValue)) ||
					($Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupValue() <> $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupOldValue()) || $this->ChkLvlBreak(2); // Recurse upper level
		}
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy]++;
				if ($this->Col[$iy]) {
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk) || !is_numeric($valwrk)) {

						// skip
					} else {
						$this->Smry[$ix][$iy] += $valwrk;
						if (is_null($this->Mn[$ix][$iy])) {
							$this->Mn[$ix][$iy] = $valwrk;
							$this->Mx[$ix][$iy] = $valwrk;
						} else {
							if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
							if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 1; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->Cnt[0][0]++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {

					// skip
				} else {
					$this->GrandSmry[$iy] += $valwrk;
					if (is_null($this->GrandMn[$iy])) {
						$this->GrandMn[$iy] = $valwrk;
						$this->GrandMx[$iy] = $valwrk;
					} else {
						if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
						if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
					}
				}
			}
		}
	}

	// Get group count
	function GetGrpCnt($sql) {
		global $conn;
		global $Laporan_Penjualan_Obat2FPasien;
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group rs
	function GetGrpRs($sql, $start, $grps) {
		global $conn;
		global $Laporan_Penjualan_Obat2FPasien;
		$wrksql = $sql;
		if ($start > 0 && $grps > -1)
			$wrksql .= " LIMIT " . ($start-1) . ", " . ($grps);
		$rswrk = $conn->Execute($wrksql);
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		global $Laporan_Penjualan_Obat2FPasien;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->setDbValue("");
		}
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		global $Laporan_Penjualan_Obat2FPasien;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			if ($opt <> 1) {
				if (is_array($Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupDbValues))
					$Laporan_Penjualan_Obat2FPasien->Kode_Obat->setDbValue(@$Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupDbValues[$rs->fields('Kode Obat')]);
				else
					$Laporan_Penjualan_Obat2FPasien->Kode_Obat->setDbValue(ewrpt_GroupValue($Laporan_Penjualan_Obat2FPasien->Kode_Obat, $rs->fields('Kode Obat')));
			}
			$Laporan_Penjualan_Obat2FPasien->Nama_Obat->setDbValue($rs->fields('Nama Obat'));
			$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->setDbValue($rs->fields('Nama Pasien'));
			$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->setDbValue($rs->fields('Tanggal Transaksi'));
			$Laporan_Penjualan_Obat2FPasien->QTY->setDbValue($rs->fields('QTY'));
			$Laporan_Penjualan_Obat2FPasien->Harga->setDbValue($rs->fields('Harga'));
			$Laporan_Penjualan_Obat2FPasien->Diskon->setDbValue($rs->fields('Diskon'));
			$Laporan_Penjualan_Obat2FPasien->Jumlah->setDbValue($rs->fields('Jumlah'));
			$Laporan_Penjualan_Obat2FPasien->Faktur->setDbValue($rs->fields('Faktur'));
			$Laporan_Penjualan_Obat2FPasien->Ruang->setDbValue($rs->fields('Ruang'));
			$Laporan_Penjualan_Obat2FPasien->Status->setDbValue($rs->fields('Status'));
			$Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->setDbValue($rs->fields('Jam Transaksi'));
			$this->Val[1] = $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->CurrentValue;
			$this->Val[2] = $Laporan_Penjualan_Obat2FPasien->QTY->CurrentValue;
			$this->Val[3] = $Laporan_Penjualan_Obat2FPasien->Harga->CurrentValue;
			$this->Val[4] = $Laporan_Penjualan_Obat2FPasien->Diskon->CurrentValue;
			$this->Val[5] = $Laporan_Penjualan_Obat2FPasien->Jumlah->CurrentValue;
			$this->Val[6] = $Laporan_Penjualan_Obat2FPasien->Faktur->CurrentValue;
		} else {
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Nama_Obat->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->QTY->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Harga->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Diskon->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Jumlah->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Faktur->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Ruang->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Status->setDbValue("");
			$Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {
		global $Laporan_Penjualan_Obat2FPasien;

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWRPT_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWRPT_TABLE_START_GROUP];
			$Laporan_Penjualan_Obat2FPasien->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$Laporan_Penjualan_Obat2FPasien->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $Laporan_Penjualan_Obat2FPasien->getStartGroup();
			}
		} else {
			$this->StartGrp = $Laporan_Penjualan_Obat2FPasien->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$Laporan_Penjualan_Obat2FPasien->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$Laporan_Penjualan_Obat2FPasien->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$Laporan_Penjualan_Obat2FPasien->setStartGroup($this->StartGrp);
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		global $Laporan_Penjualan_Obat2FPasien;

		// Initialize popup
		// Process post back form

		if (ewrpt_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewrpt_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWRPT_INIT_VALUE;
					if (!ewrpt_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewrpt_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewrpt_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		global $Laporan_Penjualan_Obat2FPasien;
		$this->StartGrp = 1;
		$Laporan_Penjualan_Obat2FPasien->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		global $Laporan_Penjualan_Obat2FPasien;
		$sWrk = @$_GET[EWRPT_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 100; // Non-numeric, load default
				}
			}
			$Laporan_Penjualan_Obat2FPasien->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$Laporan_Penjualan_Obat2FPasien->setStartGroup($this->StartGrp);
		} else {
			if ($Laporan_Penjualan_Obat2FPasien->getGroupPerPage() <> "") {
				$this->DisplayGrps = $Laporan_Penjualan_Obat2FPasien->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 100; // Load default
			}
		}
	}

	function RenderRow() {
		global $conn, $rs, $Security;
		global $Laporan_Penjualan_Obat2FPasien;
		if ($Laporan_Penjualan_Obat2FPasien->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total

			// Get total count from sql directly
			$sSql = ewrpt_BuildReportSql($Laporan_Penjualan_Obat2FPasien->SqlSelectCount(), $Laporan_Penjualan_Obat2FPasien->SqlWhere(), $Laporan_Penjualan_Obat2FPasien->SqlGroupBy(), $Laporan_Penjualan_Obat2FPasien->SqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
			} else {
				$this->TotCount = 0;
			}

			// Get total from sql directly
			$sSql = ewrpt_BuildReportSql($Laporan_Penjualan_Obat2FPasien->SqlSelectAgg(), $Laporan_Penjualan_Obat2FPasien->SqlWhere(), $Laporan_Penjualan_Obat2FPasien->SqlGroupBy(), $Laporan_Penjualan_Obat2FPasien->SqlHaving(), "", $this->Filter, "");
			$sSql = $Laporan_Penjualan_Obat2FPasien->SqlAggPfx() . $sSql . $Laporan_Penjualan_Obat2FPasien->SqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandSmry[2] = $rsagg->fields("sum_qty");
				$this->GrandSmry[4] = $rsagg->fields("sum_diskon");
				$this->GrandSmry[5] = $rsagg->fields("sum_jumlah");
				$rsagg->Close();
			} else {

				// Accumulate grand summary from detail records
				$sSql = ewrpt_BuildReportSql($Laporan_Penjualan_Obat2FPasien->SqlSelect(), $Laporan_Penjualan_Obat2FPasien->SqlWhere(), $Laporan_Penjualan_Obat2FPasien->SqlGroupBy(), $Laporan_Penjualan_Obat2FPasien->SqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
		}

		// Call Row_Rendering event
		$Laporan_Penjualan_Obat2FPasien->Row_Rendering();

		//
		// Render view codes
		//

		if ($Laporan_Penjualan_Obat2FPasien->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row

			// Kode Obat
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupViewValue = $Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupOldValue();
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->CellAttrs["class"] = ($Laporan_Penjualan_Obat2FPasien->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupViewValue = ewrpt_DisplayGroupValue($Laporan_Penjualan_Obat2FPasien->Kode_Obat, $Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupViewValue);

			// Nama Obat
			$Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupViewValue = $Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupOldValue();
			$Laporan_Penjualan_Obat2FPasien->Nama_Obat->CellAttrs["class"] = ($Laporan_Penjualan_Obat2FPasien->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupViewValue = ewrpt_DisplayGroupValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat, $Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupViewValue);

			// Nama Pasien
			$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupViewValue = $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupOldValue();
			$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->CellAttrs["class"] = ($Laporan_Penjualan_Obat2FPasien->RowGroupLevel == 3) ? "ewRptGrpSummary3" : "ewRptGrpField3";
			$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupViewValue = ewrpt_DisplayGroupValue($Laporan_Penjualan_Obat2FPasien->Nama_Pasien, $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupViewValue);

			// Tanggal Transaksi
			$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->ViewValue = $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->Summary;
			$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->ViewValue = ewrpt_FormatDateTime($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->ViewValue, 7);

			// QTY
			$Laporan_Penjualan_Obat2FPasien->QTY->ViewValue = $Laporan_Penjualan_Obat2FPasien->QTY->Summary;
			$Laporan_Penjualan_Obat2FPasien->QTY->ViewValue = ewrpt_FormatNumber($Laporan_Penjualan_Obat2FPasien->QTY->ViewValue, 0, -2, -2, -2);

			// Harga
			$Laporan_Penjualan_Obat2FPasien->Harga->ViewValue = $Laporan_Penjualan_Obat2FPasien->Harga->Summary;
			$Laporan_Penjualan_Obat2FPasien->Harga->ViewValue = ewrpt_FormatNumber($Laporan_Penjualan_Obat2FPasien->Harga->ViewValue, 2, -2, -2, -2);

			// Diskon
			$Laporan_Penjualan_Obat2FPasien->Diskon->ViewValue = $Laporan_Penjualan_Obat2FPasien->Diskon->Summary;
			$Laporan_Penjualan_Obat2FPasien->Diskon->ViewValue = ewrpt_FormatNumber($Laporan_Penjualan_Obat2FPasien->Diskon->ViewValue, 2, -2, -2, -2);

			// Jumlah
			$Laporan_Penjualan_Obat2FPasien->Jumlah->ViewValue = $Laporan_Penjualan_Obat2FPasien->Jumlah->Summary;
			$Laporan_Penjualan_Obat2FPasien->Jumlah->ViewValue = ewrpt_FormatNumber($Laporan_Penjualan_Obat2FPasien->Jumlah->ViewValue, 2, -2, -2, -2);

			// Faktur
			$Laporan_Penjualan_Obat2FPasien->Faktur->ViewValue = $Laporan_Penjualan_Obat2FPasien->Faktur->Summary;
		} else {

			// Kode Obat
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupViewValue = $Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupValue();
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->CellAttrs["class"] = "ewRptGrpField1";
			$Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupViewValue = ewrpt_DisplayGroupValue($Laporan_Penjualan_Obat2FPasien->Kode_Obat, $Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupViewValue);
			if ($Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupValue() == $Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupOldValue() && !$this->ChkLvlBreak(1))
				$Laporan_Penjualan_Obat2FPasien->Kode_Obat->GroupViewValue = "&nbsp;";

			// Nama Obat
			$Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupViewValue = $Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupValue();
			$Laporan_Penjualan_Obat2FPasien->Nama_Obat->CellAttrs["class"] = "ewRptGrpField2";
			$Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupViewValue = ewrpt_DisplayGroupValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat, $Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupViewValue);
			if ($Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupValue() == $Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupOldValue() && !$this->ChkLvlBreak(2))
				$Laporan_Penjualan_Obat2FPasien->Nama_Obat->GroupViewValue = "&nbsp;";

			// Nama Pasien
			$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupViewValue = $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupValue();
			$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->CellAttrs["class"] = "ewRptGrpField3";
			$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupViewValue = ewrpt_DisplayGroupValue($Laporan_Penjualan_Obat2FPasien->Nama_Pasien, $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupViewValue);
			if ($Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupValue() == $Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupOldValue() && !$this->ChkLvlBreak(3))
				$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->GroupViewValue = "&nbsp;";

			// Tanggal Transaksi
			$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->ViewValue = $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->CurrentValue;
			$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->ViewValue = ewrpt_FormatDateTime($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->ViewValue, 7);
			$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// QTY
			$Laporan_Penjualan_Obat2FPasien->QTY->ViewValue = $Laporan_Penjualan_Obat2FPasien->QTY->CurrentValue;
			$Laporan_Penjualan_Obat2FPasien->QTY->ViewValue = ewrpt_FormatNumber($Laporan_Penjualan_Obat2FPasien->QTY->ViewValue, 0, -2, -2, -2);
			$Laporan_Penjualan_Obat2FPasien->QTY->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Harga
			$Laporan_Penjualan_Obat2FPasien->Harga->ViewValue = $Laporan_Penjualan_Obat2FPasien->Harga->CurrentValue;
			$Laporan_Penjualan_Obat2FPasien->Harga->ViewValue = ewrpt_FormatNumber($Laporan_Penjualan_Obat2FPasien->Harga->ViewValue, 2, -2, -2, -2);
			$Laporan_Penjualan_Obat2FPasien->Harga->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Diskon
			$Laporan_Penjualan_Obat2FPasien->Diskon->ViewValue = $Laporan_Penjualan_Obat2FPasien->Diskon->CurrentValue;
			$Laporan_Penjualan_Obat2FPasien->Diskon->ViewValue = ewrpt_FormatNumber($Laporan_Penjualan_Obat2FPasien->Diskon->ViewValue, 2, -2, -2, -2);
			$Laporan_Penjualan_Obat2FPasien->Diskon->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Jumlah
			$Laporan_Penjualan_Obat2FPasien->Jumlah->ViewValue = $Laporan_Penjualan_Obat2FPasien->Jumlah->CurrentValue;
			$Laporan_Penjualan_Obat2FPasien->Jumlah->ViewValue = ewrpt_FormatNumber($Laporan_Penjualan_Obat2FPasien->Jumlah->ViewValue, 2, -2, -2, -2);
			$Laporan_Penjualan_Obat2FPasien->Jumlah->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Faktur
			$Laporan_Penjualan_Obat2FPasien->Faktur->ViewValue = $Laporan_Penjualan_Obat2FPasien->Faktur->CurrentValue;
			$Laporan_Penjualan_Obat2FPasien->Faktur->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
		}

		// Kode Obat
		$Laporan_Penjualan_Obat2FPasien->Kode_Obat->HrefValue = "";

		// Nama Obat
		$Laporan_Penjualan_Obat2FPasien->Nama_Obat->HrefValue = "";

		// Nama Pasien
		$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->HrefValue = "";

		// Tanggal Transaksi
		$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->HrefValue = "";

		// QTY
		$Laporan_Penjualan_Obat2FPasien->QTY->HrefValue = "";

		// Harga
		$Laporan_Penjualan_Obat2FPasien->Harga->HrefValue = "";

		// Diskon
		$Laporan_Penjualan_Obat2FPasien->Diskon->HrefValue = "";

		// Jumlah
		$Laporan_Penjualan_Obat2FPasien->Jumlah->HrefValue = "";

		// Faktur
		$Laporan_Penjualan_Obat2FPasien->Faktur->HrefValue = "";

		// Call Row_Rendered event
		$Laporan_Penjualan_Obat2FPasien->Row_Rendered();
	}

	// Get extended filter values
	function GetExtendedFilterValues() {
		global $Laporan_Penjualan_Obat2FPasien;

		// Field Nama Obat
		$sSelect = "SELECT DISTINCT `Nama Obat` FROM " . $Laporan_Penjualan_Obat2FPasien->SqlFrom();
		$sOrderBy = "`Nama Obat` ASC";
		$wrkSql = ewrpt_BuildReportSql($sSelect, $Laporan_Penjualan_Obat2FPasien->SqlWhere(), "", "", $sOrderBy, $this->UserIDFilter, "");
		$Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownList = ewrpt_GetDistinctValues("", $wrkSql);

		// Field Ruang
		$sSelect = "SELECT DISTINCT `Ruang` FROM " . $Laporan_Penjualan_Obat2FPasien->SqlFrom();
		$sOrderBy = "`Ruang` ASC";
		$wrkSql = ewrpt_BuildReportSql($sSelect, $Laporan_Penjualan_Obat2FPasien->SqlWhere(), "", "", $sOrderBy, $this->UserIDFilter, "");
		$Laporan_Penjualan_Obat2FPasien->Ruang->DropDownList = ewrpt_GetDistinctValues("", $wrkSql);

		// Field Status
		$sSelect = "SELECT DISTINCT `Status` FROM " . $Laporan_Penjualan_Obat2FPasien->SqlFrom();
		$sOrderBy = "`Status` ASC";
		$wrkSql = ewrpt_BuildReportSql($sSelect, $Laporan_Penjualan_Obat2FPasien->SqlWhere(), "", "", $sOrderBy, $this->UserIDFilter, "");
		$Laporan_Penjualan_Obat2FPasien->Status->DropDownList = ewrpt_GetDistinctValues("", $wrkSql);
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $Laporan_Penjualan_Obat2FPasien;
		global $gsFormError;
		$sFilter = "";
		$bPostBack = ewrpt_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			// Field Nama Obat

			$this->SetSessionDropDownValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownValue, 'Nama_Obat');

			// Field Tanggal Transaksi
			$this->SetSessionFilterValues($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchValue, $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchOperator, $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchCondition, $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchValue2, $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchOperator2, 'Tanggal_Transaksi');

			// Field Ruang
			$this->SetSessionDropDownValue($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownValue, 'Ruang');

			// Field Status
			$this->SetSessionDropDownValue($Laporan_Penjualan_Obat2FPasien->Status->DropDownValue, 'Status');

			// Field Jam Transaksi
			$this->SetSessionFilterValues($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchValue, $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchOperator, $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchCondition, $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchValue2, $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchOperator2, 'Jam_Transaksi');
			$bSetupFilter = TRUE;
		} else {

			// Field Nama Obat
			if ($this->GetDropDownValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownValue, 'Nama_Obat')) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			} elseif ($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownValue <> EWRPT_INIT_VALUE && !isset($_SESSION['sv_Laporan_Penjualan_Obat2FPasien->Nama_Obat'])) {
				$bSetupFilter = TRUE;
			}

			// Field Tanggal Transaksi
			if ($this->GetFilterValues($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi)) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			}

			// Field Ruang
			if ($this->GetDropDownValue($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownValue, 'Ruang')) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			} elseif ($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownValue <> EWRPT_INIT_VALUE && !isset($_SESSION['sv_Laporan_Penjualan_Obat2FPasien->Ruang'])) {
				$bSetupFilter = TRUE;
			}

			// Field Status
			if ($this->GetDropDownValue($Laporan_Penjualan_Obat2FPasien->Status->DropDownValue, 'Status')) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			} elseif ($Laporan_Penjualan_Obat2FPasien->Status->DropDownValue <> EWRPT_INIT_VALUE && !isset($_SESSION['sv_Laporan_Penjualan_Obat2FPasien->Status'])) {
				$bSetupFilter = TRUE;
			}

			// Field Jam Transaksi
			if ($this->GetFilterValues($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi)) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			}
			if (!$this->ValidateForm()) {
				$this->setMessage($gsFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {

			// Field Nama Obat
			$this->GetSessionDropDownValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat);

			// Field Tanggal Transaksi
			$this->GetSessionFilterValues($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi);

			// Field Ruang
			$this->GetSessionDropDownValue($Laporan_Penjualan_Obat2FPasien->Ruang);

			// Field Status
			$this->GetSessionDropDownValue($Laporan_Penjualan_Obat2FPasien->Status);

			// Field Jam Transaksi
			$this->GetSessionFilterValues($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi);
		}

		// Call page filter validated event
		$Laporan_Penjualan_Obat2FPasien->Page_FilterValidated();

		// Build SQL
		// Field Nama Obat

		$this->BuildDropDownFilter($Laporan_Penjualan_Obat2FPasien->Nama_Obat, $sFilter, "");

		// Field Tanggal Transaksi
		$this->BuildExtendedFilter($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi, $sFilter);

		// Field Ruang
		$this->BuildDropDownFilter($Laporan_Penjualan_Obat2FPasien->Ruang, $sFilter, "");

		// Field Status
		$this->BuildDropDownFilter($Laporan_Penjualan_Obat2FPasien->Status, $sFilter, "");

		// Field Jam Transaksi
		$this->BuildExtendedFilter($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi, $sFilter);

		// Save parms to session
		// Field Nama Obat

		$this->SetSessionDropDownValue($Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownValue, 'Nama_Obat');

		// Field Tanggal Transaksi
		$this->SetSessionFilterValues($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchValue, $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchOperator, $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchCondition, $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchValue2, $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchOperator2, 'Tanggal_Transaksi');

		// Field Ruang
		$this->SetSessionDropDownValue($Laporan_Penjualan_Obat2FPasien->Ruang->DropDownValue, 'Ruang');

		// Field Status
		$this->SetSessionDropDownValue($Laporan_Penjualan_Obat2FPasien->Status->DropDownValue, 'Status');

		// Field Jam Transaksi
		$this->SetSessionFilterValues($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchValue, $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchOperator, $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchCondition, $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchValue2, $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchOperator2, 'Jam_Transaksi');

		// Setup filter
		if ($bSetupFilter) {
		}
		return $sFilter;
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$sv, $parm) {
		if (ewrpt_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["sv_$parm"])) {
			$sv = ewrpt_StripSlashes($_GET["sv_$parm"]);
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewrpt_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv1_$parm"])) {
			$fld->SearchValue = ewrpt_StripSlashes($_GET["sv1_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so1_$parm"])) {
			$fld->SearchOperator = ewrpt_StripSlashes($_GET["so1_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = ewrpt_StripSlashes($_GET["sc_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = ewrpt_StripSlashes($_GET["sv2_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = ewrpt_StripSlashes($_GET["so2_$parm"]);
			$got = TRUE;
		}
		return $got;
	}

	// Set default ext filter
	function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2) {
		$fld->DefaultSearchValue = $sv1; // Default ext filter value 1
		$fld->DefaultSearchValue2 = $sv2; // Default ext filter value 2 (if operator 2 is enabled)
		$fld->DefaultSearchOperator = $so1; // Default search operator 1
		$fld->DefaultSearchOperator2 = $so2; // Default search operator 2 (if operator 2 is enabled)
		$fld->DefaultSearchCondition = $sc; // Default search condition (if operator 2 is enabled)
	}

	// Apply default ext filter
	function ApplyDefaultExtFilter(&$fld) {
		$fld->SearchValue = $fld->DefaultSearchValue;
		$fld->SearchValue2 = $fld->DefaultSearchValue2;
		$fld->SearchOperator = $fld->DefaultSearchOperator;
		$fld->SearchOperator2 = $fld->DefaultSearchOperator2;
		$fld->SearchCondition = $fld->DefaultSearchCondition;
	}

	// Check if Text Filter applied
	function TextFilterApplied(&$fld) {
		return (strval($fld->SearchValue) <> strval($fld->DefaultSearchValue) ||
			strval($fld->SearchValue2) <> strval($fld->DefaultSearchValue2) ||
			(strval($fld->SearchValue) <> "" &&
				strval($fld->SearchOperator) <> strval($fld->DefaultSearchOperator)) ||
			(strval($fld->SearchValue2) <> "" &&
				strval($fld->SearchOperator2) <> strval($fld->DefaultSearchOperator2)) ||
			strval($fld->SearchCondition) <> strval($fld->DefaultSearchCondition));
	}

	// Check if Non-Text Filter applied
	function NonTextFilterApplied(&$fld) {
		if (is_array($fld->DefaultDropDownValue) && is_array($fld->DropDownValue)) {
			if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
				return TRUE;
			else
				return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
		}
		else {
			$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWRPT_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWRPT_INIT_VALUE || $v2 == EWRPT_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Load selection from a filter clause
	function LoadSelectionFromFilter(&$fld, $filter, &$sel) {
		$sel = "";
		if ($filter <> "") {
			$sSql = ewrpt_BuildReportSql($fld->SqlSelect, "", "", "", $fld->SqlOrderBy, $filter, "");
			ewrpt_LoadArrayFromSql($sSql, $sel);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_Laporan_Penjualan_Obat2FPasien_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv1_Laporan_Penjualan_Obat2FPasien_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so1_Laporan_Penjualan_Obat2FPasien_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_Laporan_Penjualan_Obat2FPasien_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_Laporan_Penjualan_Obat2FPasien_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_Laporan_Penjualan_Obat2FPasien_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (isset($_SESSION[$sn]))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $parm) {
		$_SESSION['sv_Laporan_Penjualan_Obat2FPasien_' . $parm] = $sv;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv1_Laporan_Penjualan_Obat2FPasien_' . $parm] = $sv1;
		$_SESSION['so1_Laporan_Penjualan_Obat2FPasien_' . $parm] = $so1;
		$_SESSION['sc_Laporan_Penjualan_Obat2FPasien_' . $parm] = $sc;
		$_SESSION['sv2_Laporan_Penjualan_Obat2FPasien_' . $parm] = $sv2;
		$_SESSION['so2_Laporan_Penjualan_Obat2FPasien_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWRPT_INIT_VALUE) ||
			(@$_SESSION['sv1_' . $parm] <> "" && @$_SESSION['sv1_' . $parm] <> EWRPT_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWRPT_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr) {
		$FldVal = $fld->DropDownValue;
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownfilter($fld, $val, $FldOpr);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownfilter($fld, $FldVal, $FldOpr);
		}
		if ($sSql <> "") {
			if ($FilterClause <> "") $FilterClause = "(" . $FilterClause . ") AND ";
			$FilterClause .= "(" . $sSql . ")";
		}
	}

	function GetDropDownfilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$sWrk = "";
		if ($FldVal == EWRPT_NULL_VALUE) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif ($FldVal == EWRPT_EMPTY_VALUE) {
			$sWrk = $FldExpression . " = ''";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = ewrpt_getCustomFilter($fld, $FldVal);
			} else {
				if ($FldVal <> "" && $FldVal <> EWRPT_INIT_VALUE && $FldVal <> EWRPT_ALL_VALUE) {
					if ($FldDataType == EWRPT_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = $this->DateFilterString($FldOpr, $FldVal, $FldDataType);
					} else {
						$sWrk = $this->FilterString("=", $FldVal, $FldDataType);
					}
				}
				if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
			}
		}
		return $sWrk;
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDateTimeFormat = $fld->FldDateTimeFormat;
		$FldVal1 = $fld->SearchValue;
		$FldOpr1 = $fld->SearchOperator;
		$FldCond = $fld->SearchCondition;
		$FldVal2 = $fld->SearchValue2;
		$FldOpr2 = $fld->SearchOperator2;
		$sWrk = "";
		$FldOpr1 = strtoupper(trim($FldOpr1));
		if ($FldOpr1 == "") $FldOpr1 = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		$wrkFldVal1 = $FldVal1;
		$wrkFldVal2 = $FldVal2;
		if ($FldDataType == EWRPT_DATATYPE_BOOLEAN) {
			if (EWRPT_IS_MSACCESS) {
				if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "True" : "False";
				if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "True" : "False";
			} else {

				//if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? EWRPT_TRUE_STRING : EWRPT_FALSE_STRING;
				//if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? EWRPT_TRUE_STRING : EWRPT_FALSE_STRING;

				if ($wrkFldVal1 <> "") $wrkFldVal1 = ($wrkFldVal1 == "1") ? "1" : "0";
				if ($wrkFldVal2 <> "") $wrkFldVal2 = ($wrkFldVal2 == "1") ? "1" : "0";
			}
		} elseif ($FldDataType == EWRPT_DATATYPE_DATE) {
			if ($wrkFldVal1 <> "") $wrkFldVal1 = ewrpt_UnFormatDateTime($wrkFldVal1, $FldDateTimeFormat);
			if ($wrkFldVal2 <> "") $wrkFldVal2 = ewrpt_UnFormatDateTime($wrkFldVal2, $FldDateTimeFormat);
		}
		if ($FldOpr1 == "BETWEEN") {
			$IsValidValue = ($FldDataType <> EWRPT_DATATYPE_NUMBER ||
				($FldDataType == EWRPT_DATATYPE_NUMBER && is_numeric($wrkFldVal1) && is_numeric($wrkFldVal2)));
			if ($wrkFldVal1 <> "" && $wrkFldVal2 <> "" && $IsValidValue)
				$sWrk = $FldExpression . " BETWEEN " . ewrpt_QuotedValue($wrkFldVal1, $FldDataType) .
					" AND " . ewrpt_QuotedValue($wrkFldVal2, $FldDataType);
		} elseif ($FldOpr1 == "IS NULL" || $FldOpr1 == "IS NOT NULL") {
			$sWrk = $FldExpression . " " . $wrkFldVal1;
		} else {
			$IsValidValue = ($FldDataType <> EWRPT_DATATYPE_NUMBER ||
				($FldDataType == EWRPT_DATATYPE_NUMBER && is_numeric($wrkFldVal1)));
			if ($wrkFldVal1 <> "" && $IsValidValue && ewrpt_IsValidOpr($FldOpr1, $FldDataType))
				$sWrk = $FldExpression . $this->FilterString($FldOpr1, $wrkFldVal1, $FldDataType);
			$IsValidValue = ($FldDataType <> EWRPT_DATATYPE_NUMBER ||
				($FldDataType == EWRPT_DATATYPE_NUMBER && is_numeric($wrkFldVal2)));
			if ($wrkFldVal2 <> "" && $IsValidValue && ewrpt_IsValidOpr($FldOpr2, $FldDataType)) {
				if ($sWrk <> "")
					$sWrk .= " " . (($FldCond == "OR") ? "OR" : "AND") . " ";
				$sWrk .= $FldExpression . $this->FilterString($FldOpr2, $wrkFldVal2, $FldDataType);
			}
		}
		if ($sWrk <> "") {
			if ($FilterClause <> "") $FilterClause .= " AND ";
			$FilterClause .= "(" . $sWrk . ")";
		}
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $gsFormError, $Laporan_Penjualan_Obat2FPasien;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWRPT_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ewrpt_CheckEuroDate($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchValue)) {
			if ($gsFormError <> "") $gsFormError .= "<br />";
			$gsFormError .= $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->FldErrMsg();
		}
		if (!ewrpt_CheckEuroDate($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->SearchValue2)) {
			if ($gsFormError <> "") $gsFormError .= "<br />";
			$gsFormError .= $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->FldErrMsg();
		}
		if (!ewrpt_CheckTime($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchValue)) {
			if ($gsFormError <> "") $gsFormError .= "<br />";
			$gsFormError .= $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->FldErrMsg();
		}
		if (!ewrpt_CheckTime($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->SearchValue2)) {
			if ($gsFormError <> "") $gsFormError .= "<br />";
			$gsFormError .= $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->FldErrMsg();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$gsFormError .= ($gsFormError <> "") ? "<br />" : "";
			$gsFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Return filter string
	function FilterString($FldOpr, $FldVal, $FldType) {
		if ($FldOpr == "LIKE" || $FldOpr == "NOT LIKE") {
			return " " . $FldOpr . " " . ewrpt_QuotedValue("%$FldVal%", $FldType);
		} elseif ($FldOpr == "STARTS WITH") {
			return " LIKE " . ewrpt_QuotedValue("$FldVal%", $FldType);
		} else {
			return " $FldOpr " . ewrpt_QuotedValue($FldVal, $FldType);
		}
	}

	// Return date search string
	function DateFilterString($FldOpr, $FldVal, $FldType) {
		$wrkVal1 = ewrpt_DateVal($FldOpr, $FldVal, 1);
		$wrkVal2 = ewrpt_DateVal($FldOpr, $FldVal, 2);
		if ($wrkVal1 <> "" && $wrkVal2 <> "") {
			return " BETWEEN " . ewrpt_QuotedValue($wrkVal1, $FldType) . " AND " . ewrpt_QuotedValue($wrkVal2, $FldType);
		} else {
			return "";
		}
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_Laporan_Penjualan_Obat2FPasien_$parm"] = "";
		$_SESSION["rf_Laporan_Penjualan_Obat2FPasien_$parm"] = "";
		$_SESSION["rt_Laporan_Penjualan_Obat2FPasien_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		global $Laporan_Penjualan_Obat2FPasien;
		$fld =& $Laporan_Penjualan_Obat2FPasien->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_Laporan_Penjualan_Obat2FPasien_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_Laporan_Penjualan_Obat2FPasien_$parm"];
		$fld->RangeTo = @$_SESSION["rt_Laporan_Penjualan_Obat2FPasien_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		global $Laporan_Penjualan_Obat2FPasien;

		/**
		* Set up default values for non Text filters
		*/

		// Field Nama Obat
		$Laporan_Penjualan_Obat2FPasien->Nama_Obat->DefaultDropDownValue = EWRPT_INIT_VALUE;
		$Laporan_Penjualan_Obat2FPasien->Nama_Obat->DropDownValue = $Laporan_Penjualan_Obat2FPasien->Nama_Obat->DefaultDropDownValue;

		// Field Ruang
		$Laporan_Penjualan_Obat2FPasien->Ruang->DefaultDropDownValue = EWRPT_INIT_VALUE;
		$Laporan_Penjualan_Obat2FPasien->Ruang->DropDownValue = $Laporan_Penjualan_Obat2FPasien->Ruang->DefaultDropDownValue;

		// Field Status
		$Laporan_Penjualan_Obat2FPasien->Status->DefaultDropDownValue = EWRPT_INIT_VALUE;
		$Laporan_Penjualan_Obat2FPasien->Status->DropDownValue = $Laporan_Penjualan_Obat2FPasien->Status->DefaultDropDownValue;

		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/

		// Field Tanggal Transaksi
		$this->SetDefaultExtFilter($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi, "BETWEEN", NULL, 'AND', "=", NULL);
		$this->ApplyDefaultExtFilter($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi);

		// Field Jam Transaksi
		$this->SetDefaultExtFilter($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi, "BETWEEN", NULL, 'AND', "=", NULL);
		$this->ApplyDefaultExtFilter($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi);

		/**
		* Set up default values for popup filters
		* NOTE: if extended filter is enabled, use default values in extended filter instead
		*/
	}

	// Check if filter applied
	function CheckFilter() {
		global $Laporan_Penjualan_Obat2FPasien;

		// Check Nama Obat extended filter
		if ($this->NonTextFilterApplied($Laporan_Penjualan_Obat2FPasien->Nama_Obat))
			return TRUE;

		// Check Tanggal Transaksi text filter
		if ($this->TextFilterApplied($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi))
			return TRUE;

		// Check Ruang extended filter
		if ($this->NonTextFilterApplied($Laporan_Penjualan_Obat2FPasien->Ruang))
			return TRUE;

		// Check Status extended filter
		if ($this->NonTextFilterApplied($Laporan_Penjualan_Obat2FPasien->Status))
			return TRUE;

		// Check Jam Transaksi text filter
		if ($this->TextFilterApplied($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList() {
		global $Laporan_Penjualan_Obat2FPasien;
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field Nama Obat
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($Laporan_Penjualan_Obat2FPasien->Nama_Obat, $sExtWrk, "");
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Laporan_Penjualan_Obat2FPasien->Nama_Obat->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field Tanggal Transaksi
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi, $sExtWrk);
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field Ruang
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($Laporan_Penjualan_Obat2FPasien->Ruang, $sExtWrk, "");
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Laporan_Penjualan_Obat2FPasien->Ruang->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field Status
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($Laporan_Penjualan_Obat2FPasien->Status, $sExtWrk, "");
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Laporan_Penjualan_Obat2FPasien->Status->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field Jam Transaksi
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($Laporan_Penjualan_Obat2FPasien->Jam_Transaksi, $sExtWrk);
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Laporan_Penjualan_Obat2FPasien->Jam_Transaksi->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Show Filters
		if ($sFilterList <> "")
			echo $ReportLanguage->Phrase("CurrentFilters") . "<br />$sFilterList";
	}

	// Return poup filter
	function GetPopupFilter() {
		global $Laporan_Penjualan_Obat2FPasien;
		$sWrk = "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWRPT_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		global $Laporan_Penjualan_Obat2FPasien;

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$Laporan_Penjualan_Obat2FPasien->setOrderBy("");
				$Laporan_Penjualan_Obat2FPasien->setStartGroup(1);
				$Laporan_Penjualan_Obat2FPasien->Kode_Obat->setSort("");
				$Laporan_Penjualan_Obat2FPasien->Nama_Obat->setSort("");
				$Laporan_Penjualan_Obat2FPasien->Nama_Pasien->setSort("");
				$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->setSort("");
				$Laporan_Penjualan_Obat2FPasien->QTY->setSort("");
				$Laporan_Penjualan_Obat2FPasien->Harga->setSort("");
				$Laporan_Penjualan_Obat2FPasien->Diskon->setSort("");
				$Laporan_Penjualan_Obat2FPasien->Jumlah->setSort("");
				$Laporan_Penjualan_Obat2FPasien->Faktur->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$Laporan_Penjualan_Obat2FPasien->CurrentOrder = ewrpt_StripSlashes(@$_GET["order"]);
			$Laporan_Penjualan_Obat2FPasien->CurrentOrderType = @$_GET["ordertype"];
			$Laporan_Penjualan_Obat2FPasien->UpdateSort($Laporan_Penjualan_Obat2FPasien->Kode_Obat); // Kode Obat
			$Laporan_Penjualan_Obat2FPasien->UpdateSort($Laporan_Penjualan_Obat2FPasien->Nama_Obat); // Nama Obat
			$Laporan_Penjualan_Obat2FPasien->UpdateSort($Laporan_Penjualan_Obat2FPasien->Nama_Pasien); // Nama Pasien
			$Laporan_Penjualan_Obat2FPasien->UpdateSort($Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi); // Tanggal Transaksi
			$Laporan_Penjualan_Obat2FPasien->UpdateSort($Laporan_Penjualan_Obat2FPasien->QTY); // QTY
			$Laporan_Penjualan_Obat2FPasien->UpdateSort($Laporan_Penjualan_Obat2FPasien->Harga); // Harga
			$Laporan_Penjualan_Obat2FPasien->UpdateSort($Laporan_Penjualan_Obat2FPasien->Diskon); // Diskon
			$Laporan_Penjualan_Obat2FPasien->UpdateSort($Laporan_Penjualan_Obat2FPasien->Jumlah); // Jumlah
			$Laporan_Penjualan_Obat2FPasien->UpdateSort($Laporan_Penjualan_Obat2FPasien->Faktur); // Faktur
			$sSortSql = $Laporan_Penjualan_Obat2FPasien->SortSql();
			$Laporan_Penjualan_Obat2FPasien->setOrderBy($sSortSql);
			$Laporan_Penjualan_Obat2FPasien->setStartGroup(1);
		}

		// Set up default sort
		if ($Laporan_Penjualan_Obat2FPasien->getOrderBy() == "") {
			$Laporan_Penjualan_Obat2FPasien->setOrderBy("`Tanggal Transaksi` ASC");
			$Laporan_Penjualan_Obat2FPasien->Tanggal_Transaksi->setSort("ASC");
		}
		return $Laporan_Penjualan_Obat2FPasien->getOrderBy();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Message Showing event
	function Message_Showing(&$msg) {

		// Example:
		//$msg = "your new message";

	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
