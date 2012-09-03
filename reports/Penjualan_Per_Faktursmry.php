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
$Penjualan_Per_Faktur = NULL;

//
// Table class for Penjualan Per Faktur
//
class crPenjualan_Per_Faktur {
	var $TableVar = 'Penjualan_Per_Faktur';
	var $TableName = 'Penjualan Per Faktur';
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
	var $Faktur;
	var $Nama_Pasien;
	var $Dokter;
	var $Kode_Obat;
	var $Nama_Obat;
	var $Tanggal_Transaksi;
	var $QTY;
	var $Harga;
	var $Diskon;
	var $Jumlah;
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
	function crPenjualan_Per_Faktur() {
		global $ReportLanguage;

		// Faktur
		$this->Faktur = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Faktur', 'Faktur', '`Faktur`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->Faktur->GroupingFieldId = 1;
		$this->Faktur->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['Faktur'] =& $this->Faktur;
		$this->Faktur->DateFilter = "";
		$this->Faktur->SqlSelect = "";
		$this->Faktur->SqlOrderBy = "";
		$this->Faktur->FldGroupByType = "";
		$this->Faktur->FldGroupInt = "0";
		$this->Faktur->FldGroupSql = "";

		// Nama Pasien
		$this->Nama_Pasien = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Nama_Pasien', 'Nama Pasien', '`Nama Pasien`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->Nama_Pasien->GroupingFieldId = 2;
		$this->fields['Nama_Pasien'] =& $this->Nama_Pasien;
		$this->Nama_Pasien->DateFilter = "";
		$this->Nama_Pasien->SqlSelect = "";
		$this->Nama_Pasien->SqlOrderBy = "";
		$this->Nama_Pasien->FldGroupByType = "";
		$this->Nama_Pasien->FldGroupInt = "0";
		$this->Nama_Pasien->FldGroupSql = "";

		// Dokter
		$this->Dokter = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Dokter', 'Dokter', '`Dokter`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->Dokter->GroupingFieldId = 3;
		$this->fields['Dokter'] =& $this->Dokter;
		$this->Dokter->DateFilter = "";
		$this->Dokter->SqlSelect = "";
		$this->Dokter->SqlOrderBy = "";
		$this->Dokter->FldGroupByType = "";
		$this->Dokter->FldGroupInt = "0";
		$this->Dokter->FldGroupSql = "";

		// Kode Obat
		$this->Kode_Obat = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Kode_Obat', 'Kode Obat', '`Kode Obat`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['Kode_Obat'] =& $this->Kode_Obat;
		$this->Kode_Obat->DateFilter = "";
		$this->Kode_Obat->SqlSelect = "";
		$this->Kode_Obat->SqlOrderBy = "";

		// Nama Obat
		$this->Nama_Obat = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Nama_Obat', 'Nama Obat', '`Nama Obat`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['Nama_Obat'] =& $this->Nama_Obat;
		$this->Nama_Obat->DateFilter = "";
		$this->Nama_Obat->SqlSelect = "";
		$this->Nama_Obat->SqlOrderBy = "";

		// Tanggal Transaksi
		$this->Tanggal_Transaksi = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Tanggal_Transaksi', 'Tanggal Transaksi', '`Tanggal Transaksi`', 133, EWRPT_DATATYPE_DATE, 7);
		$this->Tanggal_Transaksi->FldDefaultErrMsg = str_replace("%s", "/", $ReportLanguage->Phrase("IncorrectDateDMY"));
		$this->fields['Tanggal_Transaksi'] =& $this->Tanggal_Transaksi;
		$this->Tanggal_Transaksi->DateFilter = "";
		$this->Tanggal_Transaksi->SqlSelect = "";
		$this->Tanggal_Transaksi->SqlOrderBy = "";

		// QTY
		$this->QTY = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_QTY', 'QTY', '`QTY`', 20, EWRPT_DATATYPE_NUMBER, -1);
		$this->QTY->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['QTY'] =& $this->QTY;
		$this->QTY->DateFilter = "";
		$this->QTY->SqlSelect = "";
		$this->QTY->SqlOrderBy = "";

		// Harga
		$this->Harga = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Harga', 'Harga', '`Harga`', 5, EWRPT_DATATYPE_NUMBER, -1);
		$this->Harga->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['Harga'] =& $this->Harga;
		$this->Harga->DateFilter = "";
		$this->Harga->SqlSelect = "";
		$this->Harga->SqlOrderBy = "";

		// Diskon
		$this->Diskon = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Diskon', 'Diskon', '`Diskon`', 5, EWRPT_DATATYPE_NUMBER, -1);
		$this->Diskon->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['Diskon'] =& $this->Diskon;
		$this->Diskon->DateFilter = "";
		$this->Diskon->SqlSelect = "";
		$this->Diskon->SqlOrderBy = "";

		// Jumlah
		$this->Jumlah = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Jumlah', 'Jumlah', '`Jumlah`', 5, EWRPT_DATATYPE_NUMBER, -1);
		$this->Jumlah->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['Jumlah'] =& $this->Jumlah;
		$this->Jumlah->DateFilter = "";
		$this->Jumlah->SqlSelect = "";
		$this->Jumlah->SqlOrderBy = "";

		// Ruang
		$this->Ruang = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Ruang', 'Ruang', '`Ruang`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['Ruang'] =& $this->Ruang;
		$this->Ruang->DateFilter = "";
		$this->Ruang->SqlSelect = "";
		$this->Ruang->SqlOrderBy = "";

		// Status
		$this->Status = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Status', 'Status', '`Status`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['Status'] =& $this->Status;
		$this->Status->DateFilter = "";
		$this->Status->SqlSelect = "";
		$this->Status->SqlOrderBy = "";

		// Jam Transaksi
		$this->Jam_Transaksi = new crField('Penjualan_Per_Faktur', 'Penjualan Per Faktur', 'x_Jam_Transaksi', 'Jam Transaksi', '`Jam Transaksi`', 134, EWRPT_DATATYPE_TIME, 4);
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
		return "`penjualanperfaktur`";
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
		return "`Faktur` ASC, `Nama Pasien` ASC, `Dokter` ASC";
	}

	// Table Level Group SQL
	function SqlFirstGroupField() {
		return "`Faktur`";
	}

	function SqlSelectGroup() {
		return "SELECT DISTINCT " . $this->SqlFirstGroupField() . " FROM " . $this->SqlFrom();
	}

	function SqlOrderByGroup() {
		return "`Faktur` ASC";
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
$Penjualan_Per_Faktur_summary = new crPenjualan_Per_Faktur_summary();
$Page =& $Penjualan_Per_Faktur_summary;

// Page init
$Penjualan_Per_Faktur_summary->Page_Init();

// Page main
$Penjualan_Per_Faktur_summary->Page_Main();
?>
<?php include "phprptinc/hApotek.php"; ?>
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
<script type="text/javascript">

// Create page object
var Penjualan_Per_Faktur_summary = new ewrpt_Page("Penjualan_Per_Faktur_summary");

// page properties
Penjualan_Per_Faktur_summary.PageID = "summary"; // page ID
Penjualan_Per_Faktur_summary.FormID = "fPenjualan_Per_Faktursummaryfilter"; // form ID
var EWRPT_PAGE_ID = Penjualan_Per_Faktur_summary.PageID;

// extend page with ValidateForm function
Penjualan_Per_Faktur_summary.ValidateForm = function(fobj) {
	if (!this.ValidateRequired)
		return true; // ignore validation
	var elm = fobj.sv1_Tanggal_Transaksi;
	if (elm && !ewrpt_CheckEuroDate(elm.value)) {
		if (!ewrpt_OnError(elm, "<?php echo ewrpt_JsEncode2($Penjualan_Per_Faktur->Tanggal_Transaksi->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv2_Tanggal_Transaksi;
	if (elm && !ewrpt_CheckEuroDate(elm.value)) {
		if (!ewrpt_OnError(elm, "<?php echo ewrpt_JsEncode2($Penjualan_Per_Faktur->Tanggal_Transaksi->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv1_Jam_Transaksi;
	if (elm && !ewrpt_CheckTime(elm.value)) {
		if (!ewrpt_OnError(elm, "<?php echo ewrpt_JsEncode2($Penjualan_Per_Faktur->Jam_Transaksi->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv2_Jam_Transaksi;
	if (elm && !ewrpt_CheckTime(elm.value)) {
		if (!ewrpt_OnError(elm, "<?php echo ewrpt_JsEncode2($Penjualan_Per_Faktur->Jam_Transaksi->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj)) return false;
	return true;
}

// extend page with Form_CustomValidate function
Penjualan_Per_Faktur_summary.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
<?php if (EWRPT_CLIENT_VALIDATE) { ?>
Penjualan_Per_Faktur_summary.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
Penjualan_Per_Faktur_summary.ValidateRequired = false; // no JavaScript validation
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
<?php $Penjualan_Per_Faktur_summary->ShowPageHeader(); ?>
<?php $Penjualan_Per_Faktur_summary->ShowMessage(); ?>
<?php if ($Penjualan_Per_Faktur->Export == "" || $Penjualan_Per_Faktur->Export == "print" || $Penjualan_Per_Faktur->Export == "email") { ?>
<script src="FusionChartsFree/JSClass/FusionCharts.js" type="text/javascript"></script>
<?php } ?>
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
<script src="phprptjs/popup.js" type="text/javascript"></script>
<script src="phprptjs/ewrptpop.js" type="text/javascript"></script>
<script type="text/javascript">

// popup fields
</script>
<?php } ?>
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
<!-- Table Container (Begin) -->
<table id="ewContainer" cellspacing="0" cellpadding="0" border="0">
<!-- Top Container (Begin) -->
<tr><td colspan="3"><div id="ewTop" class="phpreportmaker">
<!-- top slot -->
<a name="top"></a>
<?php } ?>
<?php if ($Penjualan_Per_Faktur->Export == "" || $Penjualan_Per_Faktur->Export == "print" || $Penjualan_Per_Faktur->Export == "email") { ?>
<?php } ?>
<?php echo $Penjualan_Per_Faktur->TableCaption() ?>
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
&nbsp;&nbsp;<a href="<?php echo $Penjualan_Per_Faktur_summary->ExportPrintUrl ?>"><?php echo $ReportLanguage->Phrase("PrinterFriendly") ?></a>
&nbsp;&nbsp;<a href="<?php echo $Penjualan_Per_Faktur_summary->ExportExcelUrl ?>"><?php echo $ReportLanguage->Phrase("ExportToExcel") ?></a>
<?php if ($Penjualan_Per_Faktur_summary->FilterApplied) { ?>
&nbsp;&nbsp;<a href="Penjualan_Per_Faktursmry.php?cmd=reset"><?php echo $ReportLanguage->Phrase("ResetAllFilter") ?></a>
<?php } ?>
<?php } ?>
<br /><br />
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
</div></td></tr>
<!-- Top Container (End) -->
<tr>
	<!-- Left Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewLeft" class="phpreportmaker">
	<!-- Left slot -->
<?php } ?>
<?php if ($Penjualan_Per_Faktur->Export == "" || $Penjualan_Per_Faktur->Export == "print" || $Penjualan_Per_Faktur->Export == "email") { ?>
<?php } ?>
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
	</div></td>
	<!-- Left Container (End) -->
	<!-- Center Container - Report (Begin) -->
	<td style="vertical-align: top;" class="ewPadding"><div id="ewCenter" class="phpreportmaker">
	<!-- center slot -->
<?php } ?>
<!-- summary report starts -->
<div id="report_summary">
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
<?php
if ($Penjualan_Per_Faktur->FilterPanelOption == 2 || ($Penjualan_Per_Faktur->FilterPanelOption == 3 && $Penjualan_Per_Faktur_summary->FilterApplied) || $Penjualan_Per_Faktur_summary->Filter == "0=101") {
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
<form name="fPenjualan_Per_Faktursummaryfilter" id="fPenjualan_Per_Faktursummaryfilter" action="Penjualan_Per_Faktursmry.php" class="ewForm" onsubmit="return Penjualan_Per_Faktur_summary.ValidateForm(this);">
<table class="ewRptExtFilter">
	<tr>
		<td><span class="phpreportmaker"><?php echo $Penjualan_Per_Faktur->Nama_Obat->FldCaption() ?></span></td>
		<td></td>
		<td colspan="4"><span class="ewRptSearchOpr">
		<select name="sv_Nama_Obat[]" id="sv_Nama_Obat[]" multiple<?php echo ($Penjualan_Per_Faktur_summary->ClearExtFilter == 'Penjualan_Per_Faktur_Nama_Obat') ? " class=\"ewInputCleared\"" : "" ?>>
		<option value="<?php echo EWRPT_ALL_VALUE; ?>"<?php if (ewrpt_MatchedFilterValue($Penjualan_Per_Faktur->Nama_Obat->DropDownValue, EWRPT_ALL_VALUE)) echo " selected=\"selected\""; ?>><?php echo $ReportLanguage->Phrase("SelectAll"); ?></option>
<?php

// Popup filter
$cntf = is_array($Penjualan_Per_Faktur->Nama_Obat->CustomFilters) ? count($Penjualan_Per_Faktur->Nama_Obat->CustomFilters) : 0;
$cntd = is_array($Penjualan_Per_Faktur->Nama_Obat->DropDownList) ? count($Penjualan_Per_Faktur->Nama_Obat->DropDownList) : 0;
$totcnt = $cntf + $cntd;
$wrkcnt = 0;
	for ($i = 0; $i < $cntf; $i++) {
		if ($Penjualan_Per_Faktur->Nama_Obat->CustomFilters[$i]->FldName == 'Nama Obat') {
?>
		<option value="<?php echo "@@" . $Penjualan_Per_Faktur->Nama_Obat->CustomFilters[$i]->FilterName ?>"<?php if (ewrpt_MatchedFilterValue($Penjualan_Per_Faktur->Nama_Obat->DropDownValue, "@@" . $Penjualan_Per_Faktur->Nama_Obat->CustomFilters[$i]->FilterName)) echo " selected=\"selected\"" ?>><?php echo $Penjualan_Per_Faktur->Nama_Obat->CustomFilters[$i]->DisplayName ?></option>
<?php
		}
		$wrkcnt += 1;
	}

//}
	for ($i = 0; $i < $cntd; $i++) {
?>
		<option value="<?php echo $Penjualan_Per_Faktur->Nama_Obat->DropDownList[$i] ?>"<?php if (ewrpt_MatchedFilterValue($Penjualan_Per_Faktur->Nama_Obat->DropDownValue, $Penjualan_Per_Faktur->Nama_Obat->DropDownList[$i])) echo " selected=\"selected\"" ?>><?php echo ewrpt_DropDownDisplayValue($Penjualan_Per_Faktur->Nama_Obat->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}

//}
?>
		</select>
		</span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $Penjualan_Per_Faktur->Tanggal_Transaksi->FldCaption() ?></span></td>
		<td><span class="ewRptSearchOpr"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so1_Tanggal_Transaksi" id="so1_Tanggal_Transaksi" value="BETWEEN"></span></td>
		<td>
			<table cellspacing="0" class="ewItemTable"><tr>
				<td><span class="phpreportmaker">
<input type="text" name="sv1_Tanggal_Transaksi" id="sv1_Tanggal_Transaksi" value="<?php echo ewrpt_HtmlEncode($Penjualan_Per_Faktur->Tanggal_Transaksi->SearchValue) ?>"<?php echo ($Penjualan_Per_Faktur_summary->ClearExtFilter == 'Penjualan_Per_Faktur_Tanggal_Transaksi') ? " class=\"ewInputCleared\"" : "" ?>>
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
<input type="text" name="sv2_Tanggal_Transaksi" id="sv2_Tanggal_Transaksi" value="<?php echo ewrpt_HtmlEncode($Penjualan_Per_Faktur->Tanggal_Transaksi->SearchValue2) ?>"<?php echo ($Penjualan_Per_Faktur_summary->ClearExtFilter == 'Penjualan_Per_Faktur_Tanggal_Transaksi') ? " class=\"ewInputCleared\"" : "" ?>>
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
		<td><span class="phpreportmaker"><?php echo $Penjualan_Per_Faktur->Ruang->FldCaption() ?></span></td>
		<td></td>
		<td colspan="4"><span class="ewRptSearchOpr">
		<select name="sv_Ruang" id="sv_Ruang"<?php echo ($Penjualan_Per_Faktur_summary->ClearExtFilter == 'Penjualan_Per_Faktur_Ruang') ? " class=\"ewInputCleared\"" : "" ?>>
		<option value="<?php echo EWRPT_ALL_VALUE; ?>"<?php if (ewrpt_MatchedFilterValue($Penjualan_Per_Faktur->Ruang->DropDownValue, EWRPT_ALL_VALUE)) echo " selected=\"selected\""; ?>><?php echo $ReportLanguage->Phrase("PleaseSelect"); ?></option>
<?php

// Popup filter
$cntf = is_array($Penjualan_Per_Faktur->Ruang->CustomFilters) ? count($Penjualan_Per_Faktur->Ruang->CustomFilters) : 0;
$cntd = is_array($Penjualan_Per_Faktur->Ruang->DropDownList) ? count($Penjualan_Per_Faktur->Ruang->DropDownList) : 0;
$totcnt = $cntf + $cntd;
$wrkcnt = 0;
	for ($i = 0; $i < $cntf; $i++) {
		if ($Penjualan_Per_Faktur->Ruang->CustomFilters[$i]->FldName == 'Ruang') {
?>
		<option value="<?php echo "@@" . $Penjualan_Per_Faktur->Ruang->CustomFilters[$i]->FilterName ?>"<?php if (ewrpt_MatchedFilterValue($Penjualan_Per_Faktur->Ruang->DropDownValue, "@@" . $Penjualan_Per_Faktur->Ruang->CustomFilters[$i]->FilterName)) echo " selected=\"selected\"" ?>><?php echo $Penjualan_Per_Faktur->Ruang->CustomFilters[$i]->DisplayName ?></option>
<?php
		}
		$wrkcnt += 1;
	}

//}
	for ($i = 0; $i < $cntd; $i++) {
?>
		<option value="<?php echo $Penjualan_Per_Faktur->Ruang->DropDownList[$i] ?>"<?php if (ewrpt_MatchedFilterValue($Penjualan_Per_Faktur->Ruang->DropDownValue, $Penjualan_Per_Faktur->Ruang->DropDownList[$i])) echo " selected=\"selected\"" ?>><?php echo ewrpt_DropDownDisplayValue($Penjualan_Per_Faktur->Ruang->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}

//}
?>
		</select>
		</span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $Penjualan_Per_Faktur->Status->FldCaption() ?></span></td>
		<td></td>
		<td colspan="4"><span class="ewRptSearchOpr">
		<select name="sv_Status" id="sv_Status"<?php echo ($Penjualan_Per_Faktur_summary->ClearExtFilter == 'Penjualan_Per_Faktur_Status') ? " class=\"ewInputCleared\"" : "" ?>>
		<option value="<?php echo EWRPT_ALL_VALUE; ?>"<?php if (ewrpt_MatchedFilterValue($Penjualan_Per_Faktur->Status->DropDownValue, EWRPT_ALL_VALUE)) echo " selected=\"selected\""; ?>><?php echo $ReportLanguage->Phrase("PleaseSelect"); ?></option>
<?php

// Popup filter
$cntf = is_array($Penjualan_Per_Faktur->Status->CustomFilters) ? count($Penjualan_Per_Faktur->Status->CustomFilters) : 0;
$cntd = is_array($Penjualan_Per_Faktur->Status->DropDownList) ? count($Penjualan_Per_Faktur->Status->DropDownList) : 0;
$totcnt = $cntf + $cntd;
$wrkcnt = 0;
	for ($i = 0; $i < $cntf; $i++) {
		if ($Penjualan_Per_Faktur->Status->CustomFilters[$i]->FldName == 'Status') {
?>
		<option value="<?php echo "@@" . $Penjualan_Per_Faktur->Status->CustomFilters[$i]->FilterName ?>"<?php if (ewrpt_MatchedFilterValue($Penjualan_Per_Faktur->Status->DropDownValue, "@@" . $Penjualan_Per_Faktur->Status->CustomFilters[$i]->FilterName)) echo " selected=\"selected\"" ?>><?php echo $Penjualan_Per_Faktur->Status->CustomFilters[$i]->DisplayName ?></option>
<?php
		}
		$wrkcnt += 1;
	}

//}
	for ($i = 0; $i < $cntd; $i++) {
?>
		<option value="<?php echo $Penjualan_Per_Faktur->Status->DropDownList[$i] ?>"<?php if (ewrpt_MatchedFilterValue($Penjualan_Per_Faktur->Status->DropDownValue, $Penjualan_Per_Faktur->Status->DropDownList[$i])) echo " selected=\"selected\"" ?>><?php echo ewrpt_DropDownDisplayValue($Penjualan_Per_Faktur->Status->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}

//}
?>
		</select>
		</span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $Penjualan_Per_Faktur->Jam_Transaksi->FldCaption() ?></span></td>
		<td><span class="ewRptSearchOpr"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so1_Jam_Transaksi" id="so1_Jam_Transaksi" value="BETWEEN"></span></td>
		<td>
			<table cellspacing="0" class="ewItemTable"><tr>
				<td><span class="phpreportmaker">
<input type="text" name="sv1_Jam_Transaksi" id="sv1_Jam_Transaksi" size="30" value="<?php echo ewrpt_HtmlEncode($Penjualan_Per_Faktur->Jam_Transaksi->SearchValue) ?>"<?php echo ($Penjualan_Per_Faktur_summary->ClearExtFilter == 'Penjualan_Per_Faktur_Jam_Transaksi') ? " class=\"ewInputCleared\"" : "" ?>>
</span></td>
				<td><span class="ewRptSearchOpr" id="btw1_Jam_Transaksi" name="btw1_Jam_Transaksi">&nbsp;<?php echo $ReportLanguage->Phrase("AND") ?>&nbsp;</span></td>
				<td><span class="phpreportmaker" id="btw1_Jam_Transaksi" name="btw1_Jam_Transaksi">
<input type="text" name="sv2_Jam_Transaksi" id="sv2_Jam_Transaksi" size="30" value="<?php echo ewrpt_HtmlEncode($Penjualan_Per_Faktur->Jam_Transaksi->SearchValue2) ?>"<?php echo ($Penjualan_Per_Faktur_summary->ClearExtFilter == 'Penjualan_Per_Faktur_Jam_Transaksi') ? " class=\"ewInputCleared\"" : "" ?>>
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
<?php if ($Penjualan_Per_Faktur->ShowCurrentFilter) { ?>
<div id="ewrptFilterList">
<?php $Penjualan_Per_Faktur_summary->ShowFilterList() ?>
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
if ($Penjualan_Per_Faktur->ExportAll && $Penjualan_Per_Faktur->Export <> "") {
	$Penjualan_Per_Faktur_summary->StopGrp = $Penjualan_Per_Faktur_summary->TotalGrps;
} else {
	$Penjualan_Per_Faktur_summary->StopGrp = $Penjualan_Per_Faktur_summary->StartGrp + $Penjualan_Per_Faktur_summary->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Penjualan_Per_Faktur_summary->StopGrp) > intval($Penjualan_Per_Faktur_summary->TotalGrps))
	$Penjualan_Per_Faktur_summary->StopGrp = $Penjualan_Per_Faktur_summary->TotalGrps;
$Penjualan_Per_Faktur_summary->RecCount = 0;

// Get first row
if ($Penjualan_Per_Faktur_summary->TotalGrps > 0) {
	$Penjualan_Per_Faktur_summary->GetGrpRow(1);
	$Penjualan_Per_Faktur_summary->GrpCount = 1;
}
while (($rsgrp && !$rsgrp->EOF && $Penjualan_Per_Faktur_summary->GrpCount <= $Penjualan_Per_Faktur_summary->DisplayGrps) || $Penjualan_Per_Faktur_summary->ShowFirstHeader) {

	// Show header
	if ($Penjualan_Per_Faktur_summary->ShowFirstHeader) {
?>
	<thead>
	<tr>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->Faktur->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Faktur) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->Faktur->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Faktur) ?>',1);"><?php echo $Penjualan_Per_Faktur->Faktur->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->Faktur->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->Faktur->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->Nama_Pasien->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Nama_Pasien) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->Nama_Pasien->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Nama_Pasien) ?>',1);"><?php echo $Penjualan_Per_Faktur->Nama_Pasien->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->Nama_Pasien->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->Nama_Pasien->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->Dokter->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Dokter) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->Dokter->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Dokter) ?>',1);"><?php echo $Penjualan_Per_Faktur->Dokter->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->Dokter->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->Dokter->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->Kode_Obat->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Kode_Obat) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->Kode_Obat->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Kode_Obat) ?>',1);"><?php echo $Penjualan_Per_Faktur->Kode_Obat->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->Kode_Obat->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->Kode_Obat->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->Nama_Obat->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Nama_Obat) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->Nama_Obat->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Nama_Obat) ?>',1);"><?php echo $Penjualan_Per_Faktur->Nama_Obat->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->Nama_Obat->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->Nama_Obat->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->Tanggal_Transaksi->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Tanggal_Transaksi) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->Tanggal_Transaksi->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Tanggal_Transaksi) ?>',1);"><?php echo $Penjualan_Per_Faktur->Tanggal_Transaksi->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->Tanggal_Transaksi->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->Tanggal_Transaksi->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->QTY->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->QTY) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->QTY->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->QTY) ?>',1);"><?php echo $Penjualan_Per_Faktur->QTY->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->QTY->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->QTY->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->Harga->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Harga) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->Harga->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Harga) ?>',1);"><?php echo $Penjualan_Per_Faktur->Harga->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->Harga->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->Harga->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->Diskon->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Diskon) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->Diskon->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Diskon) ?>',1);"><?php echo $Penjualan_Per_Faktur->Diskon->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->Diskon->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->Diskon->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Penjualan_Per_Faktur->Export <> "") { ?>
<?php echo $Penjualan_Per_Faktur->Jumlah->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Jumlah) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Penjualan_Per_Faktur->Jumlah->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Penjualan_Per_Faktur->SortUrl($Penjualan_Per_Faktur->Jumlah) ?>',1);"><?php echo $Penjualan_Per_Faktur->Jumlah->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Penjualan_Per_Faktur->Jumlah->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Penjualan_Per_Faktur->Jumlah->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
	</tr>
	</thead>
	<tbody>
<?php
		$Penjualan_Per_Faktur_summary->ShowFirstHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewrpt_DetailFilterSQL($Penjualan_Per_Faktur->Faktur, $Penjualan_Per_Faktur->SqlFirstGroupField(), $Penjualan_Per_Faktur->Faktur->GroupValue());
	if ($Penjualan_Per_Faktur_summary->Filter != "")
		$sWhere = "($Penjualan_Per_Faktur_summary->Filter) AND ($sWhere)";
	$sSql = ewrpt_BuildReportSql($Penjualan_Per_Faktur->SqlSelect(), $Penjualan_Per_Faktur->SqlWhere(), $Penjualan_Per_Faktur->SqlGroupBy(), $Penjualan_Per_Faktur->SqlHaving(), $Penjualan_Per_Faktur->SqlOrderBy(), $sWhere, $Penjualan_Per_Faktur_summary->Sort);
	$rs = $conn->Execute($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Penjualan_Per_Faktur_summary->GetRow(1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$Penjualan_Per_Faktur_summary->RecCount++;

		// Render detail row
		$Penjualan_Per_Faktur->ResetCSS();
		$Penjualan_Per_Faktur->RowType = EWRPT_ROWTYPE_DETAIL;
		$Penjualan_Per_Faktur_summary->RenderRow();
?>
	<tr<?php echo $Penjualan_Per_Faktur->RowAttributes(); ?>>
		<td<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes(); ?>><div<?php echo $Penjualan_Per_Faktur->Faktur->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Faktur->GroupViewValue; ?></div></td>
		<td<?php echo $Penjualan_Per_Faktur->Nama_Pasien->CellAttributes(); ?>><div<?php echo $Penjualan_Per_Faktur->Nama_Pasien->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Nama_Pasien->GroupViewValue; ?></div></td>
		<td<?php echo $Penjualan_Per_Faktur->Dokter->CellAttributes(); ?>><div<?php echo $Penjualan_Per_Faktur->Dokter->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Dokter->GroupViewValue; ?></div></td>
		<td<?php echo $Penjualan_Per_Faktur->Kode_Obat->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Kode_Obat->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Kode_Obat->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->Nama_Obat->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Nama_Obat->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Nama_Obat->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->Tanggal_Transaksi->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Tanggal_Transaksi->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Tanggal_Transaksi->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->QTY->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->QTY->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->QTY->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->Harga->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Harga->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Harga->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->Diskon->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Diskon->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Diskon->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->Jumlah->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Jumlah->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Jumlah->ListViewValue(); ?></div>
</td>
	</tr>
<?php

		// Accumulate page summary
		$Penjualan_Per_Faktur_summary->AccumulateSummary();

		// Get next record
		$Penjualan_Per_Faktur_summary->GetRow(2);

		// Show Footers
?>
<?php
	} // End detail records loop
?>
<?php
			$Penjualan_Per_Faktur->ResetCSS();
			$Penjualan_Per_Faktur->RowType = EWRPT_ROWTYPE_TOTAL;
			$Penjualan_Per_Faktur->RowTotalType = EWRPT_ROWTOTAL_GROUP;
			$Penjualan_Per_Faktur->RowTotalSubType = EWRPT_ROWTOTAL_FOOTER;
			$Penjualan_Per_Faktur->RowGroupLevel = 1;
			$Penjualan_Per_Faktur_summary->RenderRow();
?>
	<tr<?php echo $Penjualan_Per_Faktur->RowAttributes(); ?>>
		<td colspan="10"<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSumHead") ?> <?php echo $Penjualan_Per_Faktur->Faktur->FldCaption() ?>: <?php echo $Penjualan_Per_Faktur->Faktur->GroupViewValue; ?> (<?php echo ewrpt_FormatNumber($Penjualan_Per_Faktur_summary->Cnt[1][0],0,-2,-2,-2); ?> <?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</td></tr>
<?php
			$Penjualan_Per_Faktur->ResetCSS();
			$Penjualan_Per_Faktur->QTY->Count = $Penjualan_Per_Faktur_summary->Cnt[1][4];
			$Penjualan_Per_Faktur->QTY->Summary = $Penjualan_Per_Faktur_summary->Smry[1][4]; // Load SUM
			$Penjualan_Per_Faktur->Diskon->Count = $Penjualan_Per_Faktur_summary->Cnt[1][6];
			$Penjualan_Per_Faktur->Diskon->Summary = $Penjualan_Per_Faktur_summary->Smry[1][6]; // Load SUM
			$Penjualan_Per_Faktur->Jumlah->Count = $Penjualan_Per_Faktur_summary->Cnt[1][7];
			$Penjualan_Per_Faktur->Jumlah->Summary = $Penjualan_Per_Faktur_summary->Smry[1][7]; // Load SUM
			$Penjualan_Per_Faktur->RowTotalSubType = EWRPT_ROWTOTAL_SUM;
			$Penjualan_Per_Faktur_summary->RenderRow();
?>
	<tr<?php echo $Penjualan_Per_Faktur->RowAttributes(); ?>>
		<td colspan="3"<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes() ?>><?php echo $ReportLanguage->Phrase("RptSum"); ?></td>
		<td<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->QTY->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->QTY->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Diskon->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Diskon->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->Faktur->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Jumlah->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Jumlah->ListViewValue(); ?></div>
</td>
	</tr>
<?php

			// Reset level 1 summary
			$Penjualan_Per_Faktur_summary->ResetLevelSummary(1);
?>
<?php

	// Next group
	$Penjualan_Per_Faktur_summary->GetGrpRow(2);
	$Penjualan_Per_Faktur_summary->GrpCount++;
} // End while
?>
	</tbody>
	<tfoot>
<?php
if ($Penjualan_Per_Faktur_summary->TotalGrps > 0) {
	$Penjualan_Per_Faktur->ResetCSS();
	$Penjualan_Per_Faktur->RowType = EWRPT_ROWTYPE_TOTAL;
	$Penjualan_Per_Faktur->RowTotalType = EWRPT_ROWTOTAL_GRAND;
	$Penjualan_Per_Faktur->RowTotalSubType = EWRPT_ROWTOTAL_FOOTER;
	$Penjualan_Per_Faktur->RowAttrs["class"] = "ewRptGrandSummary";
	$Penjualan_Per_Faktur_summary->RenderRow();
?>
	<!-- tr><td colspan="10"><span class="phpreportmaker">&nbsp;<br /></span></td></tr -->
	<tr<?php echo $Penjualan_Per_Faktur->RowAttributes(); ?>><td colspan="10"><?php echo $ReportLanguage->Phrase("RptGrandTotal") ?> (<?php echo ewrpt_FormatNumber($Penjualan_Per_Faktur_summary->TotCount,0,-2,-2,-2); ?> <?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</td></tr>
<?php
	$Penjualan_Per_Faktur->ResetCSS();
	$Penjualan_Per_Faktur->QTY->Count = $Penjualan_Per_Faktur_summary->TotCount;
	$Penjualan_Per_Faktur->QTY->Summary = $Penjualan_Per_Faktur_summary->GrandSmry[4]; // Load SUM
	$Penjualan_Per_Faktur->RowTotalSubType = EWRPT_ROWTOTAL_SUM;
	$Penjualan_Per_Faktur->Diskon->Count = $Penjualan_Per_Faktur_summary->TotCount;
	$Penjualan_Per_Faktur->Diskon->Summary = $Penjualan_Per_Faktur_summary->GrandSmry[6]; // Load SUM
	$Penjualan_Per_Faktur->RowTotalSubType = EWRPT_ROWTOTAL_SUM;
	$Penjualan_Per_Faktur->Jumlah->Count = $Penjualan_Per_Faktur_summary->TotCount;
	$Penjualan_Per_Faktur->Jumlah->Summary = $Penjualan_Per_Faktur_summary->GrandSmry[7]; // Load SUM
	$Penjualan_Per_Faktur->RowTotalSubType = EWRPT_ROWTOTAL_SUM;
	$Penjualan_Per_Faktur->Jumlah->CurrentValue = $Penjualan_Per_Faktur->Jumlah->Summary;
	$Penjualan_Per_Faktur->RowAttrs["class"] = "ewRptGrandSummary";
	$Penjualan_Per_Faktur_summary->RenderRow();
?>
	<tr<?php echo $Penjualan_Per_Faktur->RowAttributes(); ?>>
		<td colspan="3" class="ewRptGrpAggregate"><?php echo $ReportLanguage->Phrase("RptSum"); ?></td>
		<td<?php echo $Penjualan_Per_Faktur->Kode_Obat->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Penjualan_Per_Faktur->Nama_Obat->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Penjualan_Per_Faktur->Tanggal_Transaksi->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Penjualan_Per_Faktur->QTY->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->QTY->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->QTY->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->Harga->CellAttributes() ?>>&nbsp;</td>
		<td<?php echo $Penjualan_Per_Faktur->Diskon->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Diskon->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Diskon->ListViewValue(); ?></div>
</td>
		<td<?php echo $Penjualan_Per_Faktur->Jumlah->CellAttributes() ?>>
<div<?php echo $Penjualan_Per_Faktur->Jumlah->ViewAttributes(); ?>><?php echo $Penjualan_Per_Faktur->Jumlah->ListViewValue(); ?></div>
</td>
	</tr>
<?php } ?>
	</tfoot>
</table>
</div>
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
<div class="ewGridLowerPanel">
<form action="Penjualan_Per_Faktursmry.php" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($Penjualan_Per_Faktur_summary->StartGrp, $Penjualan_Per_Faktur_summary->DisplayGrps, $Penjualan_Per_Faktur_summary->TotalGrps) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<td><a href="Penjualan_Per_Faktursmry.php?start=<?php echo $Pager->FirstButton->Start ?>"><img src="phprptimages/first.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/firstdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<td><a href="Penjualan_Per_Faktursmry.php?start=<?php echo $Pager->PrevButton->Start ?>"><img src="phprptimages/prev.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/prevdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" id="pageno" value="<?php echo $Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($Pager->NextButton->Enabled) { ?>
	<td><a href="Penjualan_Per_Faktursmry.php?start=<?php echo $Pager->NextButton->Start ?>"><img src="phprptimages/next.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/nextdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($Pager->LastButton->Enabled) { ?>
	<td><a href="Penjualan_Per_Faktursmry.php?start=<?php echo $Pager->LastButton->Start ?>"><img src="phprptimages/last.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
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
	<?php if ($Penjualan_Per_Faktur_summary->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($Penjualan_Per_Faktur_summary->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($Penjualan_Per_Faktur_summary->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($Penjualan_Per_Faktur_summary->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($Penjualan_Per_Faktur_summary->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($Penjualan_Per_Faktur_summary->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($Penjualan_Per_Faktur_summary->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($Penjualan_Per_Faktur_summary->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($Penjualan_Per_Faktur_summary->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($Penjualan_Per_Faktur_summary->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="100"<?php if ($Penjualan_Per_Faktur_summary->DisplayGrps == 100) echo " selected=\"selected\"" ?>>100</option>
<option value="ALL"<?php if ($Penjualan_Per_Faktur->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
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
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
	</div><br /></td>
	<!-- Center Container - Report (End) -->
	<!-- Right Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewRight" class="phpreportmaker">
	<!-- Right slot -->
<?php } ?>
<?php if ($Penjualan_Per_Faktur->Export == "" || $Penjualan_Per_Faktur->Export == "print" || $Penjualan_Per_Faktur->Export == "email") { ?>
<?php } ?>
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
	</div></td>
	<!-- Right Container (End) -->
</tr>
<!-- Bottom Container (Begin) -->
<tr><td colspan="3"><div id="ewBottom" class="phpreportmaker">
	<!-- Bottom slot -->
<?php } ?>
<?php if ($Penjualan_Per_Faktur->Export == "" || $Penjualan_Per_Faktur->Export == "print" || $Penjualan_Per_Faktur->Export == "email") { ?>
<?php } ?>
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
	</div><br /></td></tr>
<!-- Bottom Container (End) -->
</table>
<!-- Table Container (End) -->
<?php } ?>
<?php $Penjualan_Per_Faktur_summary->ShowPageFooter(); ?>
<?php if (EWRPT_DEBUG_ENABLED) echo ewrpt_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Penjualan_Per_Faktur->Export == "") { ?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php } ?>
<?php include "phprptinc/footer.php"; ?>
<?php
$Penjualan_Per_Faktur_summary->Page_Terminate();
?>
<?php

//
// Page class
//
class crPenjualan_Per_Faktur_summary {

	// Page ID
	var $PageID = 'summary';

	// Table name
	var $TableName = 'Penjualan Per Faktur';

	// Page object name
	var $PageObjName = 'Penjualan_Per_Faktur_summary';

	// Page name
	function PageName() {
		return ewrpt_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewrpt_CurrentPage() . "?";
		global $Penjualan_Per_Faktur;
		if ($Penjualan_Per_Faktur->UseTokenInUrl) $PageUrl .= "t=" . $Penjualan_Per_Faktur->TableVar . "&"; // Add page token
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
		global $Penjualan_Per_Faktur;
		if ($Penjualan_Per_Faktur->UseTokenInUrl) {
			if (ewrpt_IsHttpPost())
				return ($Penjualan_Per_Faktur->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($Penjualan_Per_Faktur->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function crPenjualan_Per_Faktur_summary() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Table object (Penjualan_Per_Faktur)
		$GLOBALS["Penjualan_Per_Faktur"] = new crPenjualan_Per_Faktur();

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EWRPT_PAGE_ID"))
			define("EWRPT_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWRPT_TABLE_NAME"))
			define("EWRPT_TABLE_NAME", 'Penjualan Per Faktur', TRUE);

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
		global $Penjualan_Per_Faktur;

	// Get export parameters
	if (@$_GET["export"] <> "") {
		$Penjualan_Per_Faktur->Export = $_GET["export"];
	}
	$gsExport = $Penjualan_Per_Faktur->Export; // Get export parameter, used in header
	$gsExportFile = $Penjualan_Per_Faktur->TableVar; // Get export file, used in header
	if ($Penjualan_Per_Faktur->Export == "excel") {
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
		global $Penjualan_Per_Faktur;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export to Email (use ob_file_contents for PHP)
		if ($Penjualan_Per_Faktur->Export == "email") {
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
		global $Penjualan_Per_Faktur;
		global $rs;
		global $rsgrp;
		global $gsFormError;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 8;
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
		$this->Col = array(FALSE, FALSE, FALSE, FALSE, TRUE, FALSE, TRUE, TRUE);

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
		$Penjualan_Per_Faktur->CustomFilters_Load();

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
		$sGrpSort = ewrpt_UpdateSortFields($Penjualan_Per_Faktur->SqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewrpt_BuildReportSql($Penjualan_Per_Faktur->SqlSelectGroup(), $Penjualan_Per_Faktur->SqlWhere(), $Penjualan_Per_Faktur->SqlGroupBy(), $Penjualan_Per_Faktur->SqlHaving(), $Penjualan_Per_Faktur->SqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowFirstHeader = ($this->TotalGrps > 0);

		//$this->ShowFirstHeader = TRUE; // Uncomment to always show header
		// Set up start position if not export all

		if ($Penjualan_Per_Faktur->ExportAll && $Penjualan_Per_Faktur->Export <> "")
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
		global $Penjualan_Per_Faktur;
		switch ($lvl) {
			case 1:
				return (is_null($Penjualan_Per_Faktur->Faktur->CurrentValue) && !is_null($Penjualan_Per_Faktur->Faktur->OldValue)) ||
					(!is_null($Penjualan_Per_Faktur->Faktur->CurrentValue) && is_null($Penjualan_Per_Faktur->Faktur->OldValue)) ||
					($Penjualan_Per_Faktur->Faktur->GroupValue() <> $Penjualan_Per_Faktur->Faktur->GroupOldValue());
			case 2:
				return (is_null($Penjualan_Per_Faktur->Nama_Pasien->CurrentValue) && !is_null($Penjualan_Per_Faktur->Nama_Pasien->OldValue)) ||
					(!is_null($Penjualan_Per_Faktur->Nama_Pasien->CurrentValue) && is_null($Penjualan_Per_Faktur->Nama_Pasien->OldValue)) ||
					($Penjualan_Per_Faktur->Nama_Pasien->GroupValue() <> $Penjualan_Per_Faktur->Nama_Pasien->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
			case 3:
				return (is_null($Penjualan_Per_Faktur->Dokter->CurrentValue) && !is_null($Penjualan_Per_Faktur->Dokter->OldValue)) ||
					(!is_null($Penjualan_Per_Faktur->Dokter->CurrentValue) && is_null($Penjualan_Per_Faktur->Dokter->OldValue)) ||
					($Penjualan_Per_Faktur->Dokter->GroupValue() <> $Penjualan_Per_Faktur->Dokter->GroupOldValue()) || $this->ChkLvlBreak(2); // Recurse upper level
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
		global $Penjualan_Per_Faktur;
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group rs
	function GetGrpRs($sql, $start, $grps) {
		global $conn;
		global $Penjualan_Per_Faktur;
		$wrksql = $sql;
		if ($start > 0 && $grps > -1)
			$wrksql .= " LIMIT " . ($start-1) . ", " . ($grps);
		$rswrk = $conn->Execute($wrksql);
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		global $Penjualan_Per_Faktur;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$Penjualan_Per_Faktur->Faktur->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$Penjualan_Per_Faktur->Faktur->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$Penjualan_Per_Faktur->Faktur->setDbValue("");
		}
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		global $Penjualan_Per_Faktur;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			if ($opt <> 1) {
				if (is_array($Penjualan_Per_Faktur->Faktur->GroupDbValues))
					$Penjualan_Per_Faktur->Faktur->setDbValue(@$Penjualan_Per_Faktur->Faktur->GroupDbValues[$rs->fields('Faktur')]);
				else
					$Penjualan_Per_Faktur->Faktur->setDbValue(ewrpt_GroupValue($Penjualan_Per_Faktur->Faktur, $rs->fields('Faktur')));
			}
			$Penjualan_Per_Faktur->Nama_Pasien->setDbValue($rs->fields('Nama Pasien'));
			$Penjualan_Per_Faktur->Dokter->setDbValue($rs->fields('Dokter'));
			$Penjualan_Per_Faktur->Kode_Obat->setDbValue($rs->fields('Kode Obat'));
			$Penjualan_Per_Faktur->Nama_Obat->setDbValue($rs->fields('Nama Obat'));
			$Penjualan_Per_Faktur->Tanggal_Transaksi->setDbValue($rs->fields('Tanggal Transaksi'));
			$Penjualan_Per_Faktur->QTY->setDbValue($rs->fields('QTY'));
			$Penjualan_Per_Faktur->Harga->setDbValue($rs->fields('Harga'));
			$Penjualan_Per_Faktur->Diskon->setDbValue($rs->fields('Diskon'));
			$Penjualan_Per_Faktur->Jumlah->setDbValue($rs->fields('Jumlah'));
			$Penjualan_Per_Faktur->Ruang->setDbValue($rs->fields('Ruang'));
			$Penjualan_Per_Faktur->Status->setDbValue($rs->fields('Status'));
			$Penjualan_Per_Faktur->Jam_Transaksi->setDbValue($rs->fields('Jam Transaksi'));
			$this->Val[1] = $Penjualan_Per_Faktur->Kode_Obat->CurrentValue;
			$this->Val[2] = $Penjualan_Per_Faktur->Nama_Obat->CurrentValue;
			$this->Val[3] = $Penjualan_Per_Faktur->Tanggal_Transaksi->CurrentValue;
			$this->Val[4] = $Penjualan_Per_Faktur->QTY->CurrentValue;
			$this->Val[5] = $Penjualan_Per_Faktur->Harga->CurrentValue;
			$this->Val[6] = $Penjualan_Per_Faktur->Diskon->CurrentValue;
			$this->Val[7] = $Penjualan_Per_Faktur->Jumlah->CurrentValue;
		} else {
			$Penjualan_Per_Faktur->Faktur->setDbValue("");
			$Penjualan_Per_Faktur->Nama_Pasien->setDbValue("");
			$Penjualan_Per_Faktur->Dokter->setDbValue("");
			$Penjualan_Per_Faktur->Kode_Obat->setDbValue("");
			$Penjualan_Per_Faktur->Nama_Obat->setDbValue("");
			$Penjualan_Per_Faktur->Tanggal_Transaksi->setDbValue("");
			$Penjualan_Per_Faktur->QTY->setDbValue("");
			$Penjualan_Per_Faktur->Harga->setDbValue("");
			$Penjualan_Per_Faktur->Diskon->setDbValue("");
			$Penjualan_Per_Faktur->Jumlah->setDbValue("");
			$Penjualan_Per_Faktur->Ruang->setDbValue("");
			$Penjualan_Per_Faktur->Status->setDbValue("");
			$Penjualan_Per_Faktur->Jam_Transaksi->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {
		global $Penjualan_Per_Faktur;

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWRPT_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWRPT_TABLE_START_GROUP];
			$Penjualan_Per_Faktur->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$Penjualan_Per_Faktur->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $Penjualan_Per_Faktur->getStartGroup();
			}
		} else {
			$this->StartGrp = $Penjualan_Per_Faktur->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$Penjualan_Per_Faktur->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$Penjualan_Per_Faktur->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$Penjualan_Per_Faktur->setStartGroup($this->StartGrp);
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		global $Penjualan_Per_Faktur;

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
		global $Penjualan_Per_Faktur;
		$this->StartGrp = 1;
		$Penjualan_Per_Faktur->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		global $Penjualan_Per_Faktur;
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
			$Penjualan_Per_Faktur->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$Penjualan_Per_Faktur->setStartGroup($this->StartGrp);
		} else {
			if ($Penjualan_Per_Faktur->getGroupPerPage() <> "") {
				$this->DisplayGrps = $Penjualan_Per_Faktur->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 100; // Load default
			}
		}
	}

	function RenderRow() {
		global $conn, $rs, $Security;
		global $Penjualan_Per_Faktur;
		if ($Penjualan_Per_Faktur->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total

			// Get total count from sql directly
			$sSql = ewrpt_BuildReportSql($Penjualan_Per_Faktur->SqlSelectCount(), $Penjualan_Per_Faktur->SqlWhere(), $Penjualan_Per_Faktur->SqlGroupBy(), $Penjualan_Per_Faktur->SqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
			} else {
				$this->TotCount = 0;
			}

			// Get total from sql directly
			$sSql = ewrpt_BuildReportSql($Penjualan_Per_Faktur->SqlSelectAgg(), $Penjualan_Per_Faktur->SqlWhere(), $Penjualan_Per_Faktur->SqlGroupBy(), $Penjualan_Per_Faktur->SqlHaving(), "", $this->Filter, "");
			$sSql = $Penjualan_Per_Faktur->SqlAggPfx() . $sSql . $Penjualan_Per_Faktur->SqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandSmry[4] = $rsagg->fields("sum_qty");
				$this->GrandSmry[6] = $rsagg->fields("sum_diskon");
				$this->GrandSmry[7] = $rsagg->fields("sum_jumlah");
				$rsagg->Close();
			} else {

				// Accumulate grand summary from detail records
				$sSql = ewrpt_BuildReportSql($Penjualan_Per_Faktur->SqlSelect(), $Penjualan_Per_Faktur->SqlWhere(), $Penjualan_Per_Faktur->SqlGroupBy(), $Penjualan_Per_Faktur->SqlHaving(), "", $this->Filter, "");
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
		$Penjualan_Per_Faktur->Row_Rendering();

		//
		// Render view codes
		//

		if ($Penjualan_Per_Faktur->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row

			// Faktur
			$Penjualan_Per_Faktur->Faktur->GroupViewValue = $Penjualan_Per_Faktur->Faktur->GroupOldValue();
			$Penjualan_Per_Faktur->Faktur->CellAttrs["class"] = ($Penjualan_Per_Faktur->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$Penjualan_Per_Faktur->Faktur->GroupViewValue = ewrpt_DisplayGroupValue($Penjualan_Per_Faktur->Faktur, $Penjualan_Per_Faktur->Faktur->GroupViewValue);

			// Nama Pasien
			$Penjualan_Per_Faktur->Nama_Pasien->GroupViewValue = $Penjualan_Per_Faktur->Nama_Pasien->GroupOldValue();
			$Penjualan_Per_Faktur->Nama_Pasien->CellAttrs["class"] = ($Penjualan_Per_Faktur->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$Penjualan_Per_Faktur->Nama_Pasien->GroupViewValue = ewrpt_DisplayGroupValue($Penjualan_Per_Faktur->Nama_Pasien, $Penjualan_Per_Faktur->Nama_Pasien->GroupViewValue);

			// Dokter
			$Penjualan_Per_Faktur->Dokter->GroupViewValue = $Penjualan_Per_Faktur->Dokter->GroupOldValue();
			$Penjualan_Per_Faktur->Dokter->CellAttrs["class"] = ($Penjualan_Per_Faktur->RowGroupLevel == 3) ? "ewRptGrpSummary3" : "ewRptGrpField3";
			$Penjualan_Per_Faktur->Dokter->GroupViewValue = ewrpt_DisplayGroupValue($Penjualan_Per_Faktur->Dokter, $Penjualan_Per_Faktur->Dokter->GroupViewValue);

			// Kode Obat
			$Penjualan_Per_Faktur->Kode_Obat->ViewValue = $Penjualan_Per_Faktur->Kode_Obat->Summary;

			// Nama Obat
			$Penjualan_Per_Faktur->Nama_Obat->ViewValue = $Penjualan_Per_Faktur->Nama_Obat->Summary;

			// Tanggal Transaksi
			$Penjualan_Per_Faktur->Tanggal_Transaksi->ViewValue = $Penjualan_Per_Faktur->Tanggal_Transaksi->Summary;
			$Penjualan_Per_Faktur->Tanggal_Transaksi->ViewValue = ewrpt_FormatDateTime($Penjualan_Per_Faktur->Tanggal_Transaksi->ViewValue, 7);

			// QTY
			$Penjualan_Per_Faktur->QTY->ViewValue = $Penjualan_Per_Faktur->QTY->Summary;
			$Penjualan_Per_Faktur->QTY->ViewValue = ewrpt_FormatNumber($Penjualan_Per_Faktur->QTY->ViewValue, 0, -2, -2, -2);

			// Harga
			$Penjualan_Per_Faktur->Harga->ViewValue = $Penjualan_Per_Faktur->Harga->Summary;
			$Penjualan_Per_Faktur->Harga->ViewValue = ewrpt_FormatNumber($Penjualan_Per_Faktur->Harga->ViewValue, 2, -2, -2, -2);

			// Diskon
			$Penjualan_Per_Faktur->Diskon->ViewValue = $Penjualan_Per_Faktur->Diskon->Summary;
			$Penjualan_Per_Faktur->Diskon->ViewValue = ewrpt_FormatNumber($Penjualan_Per_Faktur->Diskon->ViewValue, 2, -2, -2, -2);

			// Jumlah
			$Penjualan_Per_Faktur->Jumlah->ViewValue = $Penjualan_Per_Faktur->Jumlah->Summary;
			$Penjualan_Per_Faktur->Jumlah->ViewValue = ewrpt_FormatNumber($Penjualan_Per_Faktur->Jumlah->ViewValue, 2, -2, -2, -2);
		} else {

			// Faktur
			$Penjualan_Per_Faktur->Faktur->GroupViewValue = $Penjualan_Per_Faktur->Faktur->GroupValue();
			$Penjualan_Per_Faktur->Faktur->CellAttrs["class"] = "ewRptGrpField1";
			$Penjualan_Per_Faktur->Faktur->GroupViewValue = ewrpt_DisplayGroupValue($Penjualan_Per_Faktur->Faktur, $Penjualan_Per_Faktur->Faktur->GroupViewValue);
			if ($Penjualan_Per_Faktur->Faktur->GroupValue() == $Penjualan_Per_Faktur->Faktur->GroupOldValue() && !$this->ChkLvlBreak(1))
				$Penjualan_Per_Faktur->Faktur->GroupViewValue = "&nbsp;";

			// Nama Pasien
			$Penjualan_Per_Faktur->Nama_Pasien->GroupViewValue = $Penjualan_Per_Faktur->Nama_Pasien->GroupValue();
			$Penjualan_Per_Faktur->Nama_Pasien->CellAttrs["class"] = "ewRptGrpField2";
			$Penjualan_Per_Faktur->Nama_Pasien->GroupViewValue = ewrpt_DisplayGroupValue($Penjualan_Per_Faktur->Nama_Pasien, $Penjualan_Per_Faktur->Nama_Pasien->GroupViewValue);
			if ($Penjualan_Per_Faktur->Nama_Pasien->GroupValue() == $Penjualan_Per_Faktur->Nama_Pasien->GroupOldValue() && !$this->ChkLvlBreak(2))
				$Penjualan_Per_Faktur->Nama_Pasien->GroupViewValue = "&nbsp;";

			// Dokter
			$Penjualan_Per_Faktur->Dokter->GroupViewValue = $Penjualan_Per_Faktur->Dokter->GroupValue();
			$Penjualan_Per_Faktur->Dokter->CellAttrs["class"] = "ewRptGrpField3";
			$Penjualan_Per_Faktur->Dokter->GroupViewValue = ewrpt_DisplayGroupValue($Penjualan_Per_Faktur->Dokter, $Penjualan_Per_Faktur->Dokter->GroupViewValue);
			if ($Penjualan_Per_Faktur->Dokter->GroupValue() == $Penjualan_Per_Faktur->Dokter->GroupOldValue() && !$this->ChkLvlBreak(3))
				$Penjualan_Per_Faktur->Dokter->GroupViewValue = "&nbsp;";

			// Kode Obat
			$Penjualan_Per_Faktur->Kode_Obat->ViewValue = $Penjualan_Per_Faktur->Kode_Obat->CurrentValue;
			$Penjualan_Per_Faktur->Kode_Obat->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Nama Obat
			$Penjualan_Per_Faktur->Nama_Obat->ViewValue = $Penjualan_Per_Faktur->Nama_Obat->CurrentValue;
			$Penjualan_Per_Faktur->Nama_Obat->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Tanggal Transaksi
			$Penjualan_Per_Faktur->Tanggal_Transaksi->ViewValue = $Penjualan_Per_Faktur->Tanggal_Transaksi->CurrentValue;
			$Penjualan_Per_Faktur->Tanggal_Transaksi->ViewValue = ewrpt_FormatDateTime($Penjualan_Per_Faktur->Tanggal_Transaksi->ViewValue, 7);
			$Penjualan_Per_Faktur->Tanggal_Transaksi->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// QTY
			$Penjualan_Per_Faktur->QTY->ViewValue = $Penjualan_Per_Faktur->QTY->CurrentValue;
			$Penjualan_Per_Faktur->QTY->ViewValue = ewrpt_FormatNumber($Penjualan_Per_Faktur->QTY->ViewValue, 0, -2, -2, -2);
			$Penjualan_Per_Faktur->QTY->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Harga
			$Penjualan_Per_Faktur->Harga->ViewValue = $Penjualan_Per_Faktur->Harga->CurrentValue;
			$Penjualan_Per_Faktur->Harga->ViewValue = ewrpt_FormatNumber($Penjualan_Per_Faktur->Harga->ViewValue, 2, -2, -2, -2);
			$Penjualan_Per_Faktur->Harga->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Diskon
			$Penjualan_Per_Faktur->Diskon->ViewValue = $Penjualan_Per_Faktur->Diskon->CurrentValue;
			$Penjualan_Per_Faktur->Diskon->ViewValue = ewrpt_FormatNumber($Penjualan_Per_Faktur->Diskon->ViewValue, 2, -2, -2, -2);
			$Penjualan_Per_Faktur->Diskon->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Jumlah
			$Penjualan_Per_Faktur->Jumlah->ViewValue = $Penjualan_Per_Faktur->Jumlah->CurrentValue;
			$Penjualan_Per_Faktur->Jumlah->ViewValue = ewrpt_FormatNumber($Penjualan_Per_Faktur->Jumlah->ViewValue, 2, -2, -2, -2);
			$Penjualan_Per_Faktur->Jumlah->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
		}

		// Faktur
		$Penjualan_Per_Faktur->Faktur->HrefValue = "";

		// Nama Pasien
		$Penjualan_Per_Faktur->Nama_Pasien->HrefValue = "";

		// Dokter
		$Penjualan_Per_Faktur->Dokter->HrefValue = "";

		// Kode Obat
		$Penjualan_Per_Faktur->Kode_Obat->HrefValue = "";

		// Nama Obat
		$Penjualan_Per_Faktur->Nama_Obat->HrefValue = "";

		// Tanggal Transaksi
		$Penjualan_Per_Faktur->Tanggal_Transaksi->HrefValue = "";

		// QTY
		$Penjualan_Per_Faktur->QTY->HrefValue = "";

		// Harga
		$Penjualan_Per_Faktur->Harga->HrefValue = "";

		// Diskon
		$Penjualan_Per_Faktur->Diskon->HrefValue = "";

		// Jumlah
		$Penjualan_Per_Faktur->Jumlah->HrefValue = "";

		// Call Row_Rendered event
		$Penjualan_Per_Faktur->Row_Rendered();
	}

	// Get extended filter values
	function GetExtendedFilterValues() {
		global $Penjualan_Per_Faktur;

		// Field Nama Obat
		$sSelect = "SELECT DISTINCT `Nama Obat` FROM " . $Penjualan_Per_Faktur->SqlFrom();
		$sOrderBy = "`Nama Obat` ASC";
		$wrkSql = ewrpt_BuildReportSql($sSelect, $Penjualan_Per_Faktur->SqlWhere(), "", "", $sOrderBy, $this->UserIDFilter, "");
		$Penjualan_Per_Faktur->Nama_Obat->DropDownList = ewrpt_GetDistinctValues("", $wrkSql);

		// Field Ruang
		$sSelect = "SELECT DISTINCT `Ruang` FROM " . $Penjualan_Per_Faktur->SqlFrom();
		$sOrderBy = "`Ruang` ASC";
		$wrkSql = ewrpt_BuildReportSql($sSelect, $Penjualan_Per_Faktur->SqlWhere(), "", "", $sOrderBy, $this->UserIDFilter, "");
		$Penjualan_Per_Faktur->Ruang->DropDownList = ewrpt_GetDistinctValues("", $wrkSql);

		// Field Status
		$sSelect = "SELECT DISTINCT `Status` FROM " . $Penjualan_Per_Faktur->SqlFrom();
		$sOrderBy = "`Status` ASC";
		$wrkSql = ewrpt_BuildReportSql($sSelect, $Penjualan_Per_Faktur->SqlWhere(), "", "", $sOrderBy, $this->UserIDFilter, "");
		$Penjualan_Per_Faktur->Status->DropDownList = ewrpt_GetDistinctValues("", $wrkSql);
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $Penjualan_Per_Faktur;
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

			$this->SetSessionDropDownValue($Penjualan_Per_Faktur->Nama_Obat->DropDownValue, 'Nama_Obat');

			// Field Tanggal Transaksi
			$this->SetSessionFilterValues($Penjualan_Per_Faktur->Tanggal_Transaksi->SearchValue, $Penjualan_Per_Faktur->Tanggal_Transaksi->SearchOperator, $Penjualan_Per_Faktur->Tanggal_Transaksi->SearchCondition, $Penjualan_Per_Faktur->Tanggal_Transaksi->SearchValue2, $Penjualan_Per_Faktur->Tanggal_Transaksi->SearchOperator2, 'Tanggal_Transaksi');

			// Field Ruang
			$this->SetSessionDropDownValue($Penjualan_Per_Faktur->Ruang->DropDownValue, 'Ruang');

			// Field Status
			$this->SetSessionDropDownValue($Penjualan_Per_Faktur->Status->DropDownValue, 'Status');

			// Field Jam Transaksi
			$this->SetSessionFilterValues($Penjualan_Per_Faktur->Jam_Transaksi->SearchValue, $Penjualan_Per_Faktur->Jam_Transaksi->SearchOperator, $Penjualan_Per_Faktur->Jam_Transaksi->SearchCondition, $Penjualan_Per_Faktur->Jam_Transaksi->SearchValue2, $Penjualan_Per_Faktur->Jam_Transaksi->SearchOperator2, 'Jam_Transaksi');
			$bSetupFilter = TRUE;
		} else {

			// Field Nama Obat
			if ($this->GetDropDownValue($Penjualan_Per_Faktur->Nama_Obat->DropDownValue, 'Nama_Obat')) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			} elseif ($Penjualan_Per_Faktur->Nama_Obat->DropDownValue <> EWRPT_INIT_VALUE && !isset($_SESSION['sv_Penjualan_Per_Faktur->Nama_Obat'])) {
				$bSetupFilter = TRUE;
			}

			// Field Tanggal Transaksi
			if ($this->GetFilterValues($Penjualan_Per_Faktur->Tanggal_Transaksi)) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			}

			// Field Ruang
			if ($this->GetDropDownValue($Penjualan_Per_Faktur->Ruang->DropDownValue, 'Ruang')) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			} elseif ($Penjualan_Per_Faktur->Ruang->DropDownValue <> EWRPT_INIT_VALUE && !isset($_SESSION['sv_Penjualan_Per_Faktur->Ruang'])) {
				$bSetupFilter = TRUE;
			}

			// Field Status
			if ($this->GetDropDownValue($Penjualan_Per_Faktur->Status->DropDownValue, 'Status')) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			} elseif ($Penjualan_Per_Faktur->Status->DropDownValue <> EWRPT_INIT_VALUE && !isset($_SESSION['sv_Penjualan_Per_Faktur->Status'])) {
				$bSetupFilter = TRUE;
			}

			// Field Jam Transaksi
			if ($this->GetFilterValues($Penjualan_Per_Faktur->Jam_Transaksi)) {
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
			$this->GetSessionDropDownValue($Penjualan_Per_Faktur->Nama_Obat);

			// Field Tanggal Transaksi
			$this->GetSessionFilterValues($Penjualan_Per_Faktur->Tanggal_Transaksi);

			// Field Ruang
			$this->GetSessionDropDownValue($Penjualan_Per_Faktur->Ruang);

			// Field Status
			$this->GetSessionDropDownValue($Penjualan_Per_Faktur->Status);

			// Field Jam Transaksi
			$this->GetSessionFilterValues($Penjualan_Per_Faktur->Jam_Transaksi);
		}

		// Call page filter validated event
		$Penjualan_Per_Faktur->Page_FilterValidated();

		// Build SQL
		// Field Nama Obat

		$this->BuildDropDownFilter($Penjualan_Per_Faktur->Nama_Obat, $sFilter, "");

		// Field Tanggal Transaksi
		$this->BuildExtendedFilter($Penjualan_Per_Faktur->Tanggal_Transaksi, $sFilter);

		// Field Ruang
		$this->BuildDropDownFilter($Penjualan_Per_Faktur->Ruang, $sFilter, "");

		// Field Status
		$this->BuildDropDownFilter($Penjualan_Per_Faktur->Status, $sFilter, "");

		// Field Jam Transaksi
		$this->BuildExtendedFilter($Penjualan_Per_Faktur->Jam_Transaksi, $sFilter);

		// Save parms to session
		// Field Nama Obat

		$this->SetSessionDropDownValue($Penjualan_Per_Faktur->Nama_Obat->DropDownValue, 'Nama_Obat');

		// Field Tanggal Transaksi
		$this->SetSessionFilterValues($Penjualan_Per_Faktur->Tanggal_Transaksi->SearchValue, $Penjualan_Per_Faktur->Tanggal_Transaksi->SearchOperator, $Penjualan_Per_Faktur->Tanggal_Transaksi->SearchCondition, $Penjualan_Per_Faktur->Tanggal_Transaksi->SearchValue2, $Penjualan_Per_Faktur->Tanggal_Transaksi->SearchOperator2, 'Tanggal_Transaksi');

		// Field Ruang
		$this->SetSessionDropDownValue($Penjualan_Per_Faktur->Ruang->DropDownValue, 'Ruang');

		// Field Status
		$this->SetSessionDropDownValue($Penjualan_Per_Faktur->Status->DropDownValue, 'Status');

		// Field Jam Transaksi
		$this->SetSessionFilterValues($Penjualan_Per_Faktur->Jam_Transaksi->SearchValue, $Penjualan_Per_Faktur->Jam_Transaksi->SearchOperator, $Penjualan_Per_Faktur->Jam_Transaksi->SearchCondition, $Penjualan_Per_Faktur->Jam_Transaksi->SearchValue2, $Penjualan_Per_Faktur->Jam_Transaksi->SearchOperator2, 'Jam_Transaksi');

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
		$this->GetSessionValue($fld->DropDownValue, 'sv_Penjualan_Per_Faktur_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv1_Penjualan_Per_Faktur_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so1_Penjualan_Per_Faktur_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_Penjualan_Per_Faktur_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_Penjualan_Per_Faktur_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_Penjualan_Per_Faktur_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (isset($_SESSION[$sn]))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $parm) {
		$_SESSION['sv_Penjualan_Per_Faktur_' . $parm] = $sv;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv1_Penjualan_Per_Faktur_' . $parm] = $sv1;
		$_SESSION['so1_Penjualan_Per_Faktur_' . $parm] = $so1;
		$_SESSION['sc_Penjualan_Per_Faktur_' . $parm] = $sc;
		$_SESSION['sv2_Penjualan_Per_Faktur_' . $parm] = $sv2;
		$_SESSION['so2_Penjualan_Per_Faktur_' . $parm] = $so2;
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
		global $ReportLanguage, $gsFormError, $Penjualan_Per_Faktur;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWRPT_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ewrpt_CheckEuroDate($Penjualan_Per_Faktur->Tanggal_Transaksi->SearchValue)) {
			if ($gsFormError <> "") $gsFormError .= "<br />";
			$gsFormError .= $Penjualan_Per_Faktur->Tanggal_Transaksi->FldErrMsg();
		}
		if (!ewrpt_CheckEuroDate($Penjualan_Per_Faktur->Tanggal_Transaksi->SearchValue2)) {
			if ($gsFormError <> "") $gsFormError .= "<br />";
			$gsFormError .= $Penjualan_Per_Faktur->Tanggal_Transaksi->FldErrMsg();
		}
		if (!ewrpt_CheckTime($Penjualan_Per_Faktur->Jam_Transaksi->SearchValue)) {
			if ($gsFormError <> "") $gsFormError .= "<br />";
			$gsFormError .= $Penjualan_Per_Faktur->Jam_Transaksi->FldErrMsg();
		}
		if (!ewrpt_CheckTime($Penjualan_Per_Faktur->Jam_Transaksi->SearchValue2)) {
			if ($gsFormError <> "") $gsFormError .= "<br />";
			$gsFormError .= $Penjualan_Per_Faktur->Jam_Transaksi->FldErrMsg();
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
		$_SESSION["sel_Penjualan_Per_Faktur_$parm"] = "";
		$_SESSION["rf_Penjualan_Per_Faktur_$parm"] = "";
		$_SESSION["rt_Penjualan_Per_Faktur_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		global $Penjualan_Per_Faktur;
		$fld =& $Penjualan_Per_Faktur->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_Penjualan_Per_Faktur_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_Penjualan_Per_Faktur_$parm"];
		$fld->RangeTo = @$_SESSION["rt_Penjualan_Per_Faktur_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		global $Penjualan_Per_Faktur;

		/**
		* Set up default values for non Text filters
		*/

		// Field Nama Obat
		$Penjualan_Per_Faktur->Nama_Obat->DefaultDropDownValue = EWRPT_INIT_VALUE;
		$Penjualan_Per_Faktur->Nama_Obat->DropDownValue = $Penjualan_Per_Faktur->Nama_Obat->DefaultDropDownValue;

		// Field Ruang
		$Penjualan_Per_Faktur->Ruang->DefaultDropDownValue = EWRPT_INIT_VALUE;
		$Penjualan_Per_Faktur->Ruang->DropDownValue = $Penjualan_Per_Faktur->Ruang->DefaultDropDownValue;

		// Field Status
		$Penjualan_Per_Faktur->Status->DefaultDropDownValue = EWRPT_INIT_VALUE;
		$Penjualan_Per_Faktur->Status->DropDownValue = $Penjualan_Per_Faktur->Status->DefaultDropDownValue;

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
		$this->SetDefaultExtFilter($Penjualan_Per_Faktur->Tanggal_Transaksi, "BETWEEN", NULL, 'AND', "=", NULL);
		$this->ApplyDefaultExtFilter($Penjualan_Per_Faktur->Tanggal_Transaksi);

		// Field Jam Transaksi
		$this->SetDefaultExtFilter($Penjualan_Per_Faktur->Jam_Transaksi, "BETWEEN", NULL, 'AND', "=", NULL);
		$this->ApplyDefaultExtFilter($Penjualan_Per_Faktur->Jam_Transaksi);

		/**
		* Set up default values for popup filters
		* NOTE: if extended filter is enabled, use default values in extended filter instead
		*/
	}

	// Check if filter applied
	function CheckFilter() {
		global $Penjualan_Per_Faktur;

		// Check Nama Obat extended filter
		if ($this->NonTextFilterApplied($Penjualan_Per_Faktur->Nama_Obat))
			return TRUE;

		// Check Tanggal Transaksi text filter
		if ($this->TextFilterApplied($Penjualan_Per_Faktur->Tanggal_Transaksi))
			return TRUE;

		// Check Ruang extended filter
		if ($this->NonTextFilterApplied($Penjualan_Per_Faktur->Ruang))
			return TRUE;

		// Check Status extended filter
		if ($this->NonTextFilterApplied($Penjualan_Per_Faktur->Status))
			return TRUE;

		// Check Jam Transaksi text filter
		if ($this->TextFilterApplied($Penjualan_Per_Faktur->Jam_Transaksi))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList() {
		global $Penjualan_Per_Faktur;
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field Nama Obat
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($Penjualan_Per_Faktur->Nama_Obat, $sExtWrk, "");
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Penjualan_Per_Faktur->Nama_Obat->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field Tanggal Transaksi
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($Penjualan_Per_Faktur->Tanggal_Transaksi, $sExtWrk);
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Penjualan_Per_Faktur->Tanggal_Transaksi->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field Ruang
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($Penjualan_Per_Faktur->Ruang, $sExtWrk, "");
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Penjualan_Per_Faktur->Ruang->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field Status
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($Penjualan_Per_Faktur->Status, $sExtWrk, "");
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Penjualan_Per_Faktur->Status->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field Jam Transaksi
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($Penjualan_Per_Faktur->Jam_Transaksi, $sExtWrk);
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Penjualan_Per_Faktur->Jam_Transaksi->FldCaption() . "<br />";
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
		global $Penjualan_Per_Faktur;
		$sWrk = "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWRPT_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		global $Penjualan_Per_Faktur;

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$Penjualan_Per_Faktur->setOrderBy("");
				$Penjualan_Per_Faktur->setStartGroup(1);
				$Penjualan_Per_Faktur->Faktur->setSort("");
				$Penjualan_Per_Faktur->Nama_Pasien->setSort("");
				$Penjualan_Per_Faktur->Dokter->setSort("");
				$Penjualan_Per_Faktur->Kode_Obat->setSort("");
				$Penjualan_Per_Faktur->Nama_Obat->setSort("");
				$Penjualan_Per_Faktur->Tanggal_Transaksi->setSort("");
				$Penjualan_Per_Faktur->QTY->setSort("");
				$Penjualan_Per_Faktur->Harga->setSort("");
				$Penjualan_Per_Faktur->Diskon->setSort("");
				$Penjualan_Per_Faktur->Jumlah->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$Penjualan_Per_Faktur->CurrentOrder = ewrpt_StripSlashes(@$_GET["order"]);
			$Penjualan_Per_Faktur->CurrentOrderType = @$_GET["ordertype"];
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->Faktur); // Faktur
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->Nama_Pasien); // Nama Pasien
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->Dokter); // Dokter
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->Kode_Obat); // Kode Obat
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->Nama_Obat); // Nama Obat
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->Tanggal_Transaksi); // Tanggal Transaksi
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->QTY); // QTY
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->Harga); // Harga
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->Diskon); // Diskon
			$Penjualan_Per_Faktur->UpdateSort($Penjualan_Per_Faktur->Jumlah); // Jumlah
			$sSortSql = $Penjualan_Per_Faktur->SortSql();
			$Penjualan_Per_Faktur->setOrderBy($sSortSql);
			$Penjualan_Per_Faktur->setStartGroup(1);
		}
		return $Penjualan_Per_Faktur->getOrderBy();
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
