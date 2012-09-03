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
$Laporan_Kunjungan_Lab = NULL;

//
// Table class for Laporan Kunjungan Lab
//
class crLaporan_Kunjungan_Lab {
	var $TableVar = 'Laporan_Kunjungan_Lab';
	var $TableName = 'Laporan Kunjungan Lab';
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
	var $Ruang_Asal;
	var $Tipe_Pasien;
	var $Jumlah;
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
	function crLaporan_Kunjungan_Lab() {
		global $ReportLanguage;

		// Ruang Asal
		$this->Ruang_Asal = new crField('Laporan_Kunjungan_Lab', 'Laporan Kunjungan Lab', 'x_Ruang_Asal', 'Ruang Asal', '`Ruang Asal`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['Ruang_Asal'] =& $this->Ruang_Asal;
		$this->Ruang_Asal->DateFilter = "";
		$this->Ruang_Asal->SqlSelect = "";
		$this->Ruang_Asal->SqlOrderBy = "";

		// Tipe Pasien
		$this->Tipe_Pasien = new crField('Laporan_Kunjungan_Lab', 'Laporan Kunjungan Lab', 'x_Tipe_Pasien', 'Tipe Pasien', '`Tipe Pasien`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['Tipe_Pasien'] =& $this->Tipe_Pasien;
		$this->Tipe_Pasien->DateFilter = "";
		$this->Tipe_Pasien->SqlSelect = "";
		$this->Tipe_Pasien->SqlOrderBy = "";

		// Jumlah
		$this->Jumlah = new crField('Laporan_Kunjungan_Lab', 'Laporan Kunjungan Lab', 'x_Jumlah', 'Jumlah', '`Jumlah`', 20, EWRPT_DATATYPE_NUMBER, -1);
		$this->Jumlah->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['Jumlah'] =& $this->Jumlah;
		$this->Jumlah->DateFilter = "";
		$this->Jumlah->SqlSelect = "";
		$this->Jumlah->SqlOrderBy = "";
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
		return "`kunjunganlab`";
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
		return "";
	}

	// Table Level Group SQL
	function SqlFirstGroupField() {
		return "";
	}

	function SqlSelectGroup() {
		return "SELECT DISTINCT " . $this->SqlFirstGroupField() . " FROM " . $this->SqlFrom();
	}

	function SqlOrderByGroup() {
		return "";
	}

	function SqlSelectAgg() {
		return "SELECT * FROM " . $this->SqlFrom();
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
$Laporan_Kunjungan_Lab_summary = new crLaporan_Kunjungan_Lab_summary();
$Page =& $Laporan_Kunjungan_Lab_summary;

// Page init
$Laporan_Kunjungan_Lab_summary->Page_Init();

// Page main
$Laporan_Kunjungan_Lab_summary->Page_Main();
?>
<?php include "phprptinc/hLab.php"; ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
<script type="text/javascript">

// Create page object
var Laporan_Kunjungan_Lab_summary = new ewrpt_Page("Laporan_Kunjungan_Lab_summary");

// page properties
Laporan_Kunjungan_Lab_summary.PageID = "summary"; // page ID
Laporan_Kunjungan_Lab_summary.FormID = "fLaporan_Kunjungan_Labsummaryfilter"; // form ID
var EWRPT_PAGE_ID = Laporan_Kunjungan_Lab_summary.PageID;

// extend page with ValidateForm function
Laporan_Kunjungan_Lab_summary.ValidateForm = function(fobj) {
	if (!this.ValidateRequired)
		return true; // ignore validation

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj)) return false;
	return true;
}

// extend page with Form_CustomValidate function
Laporan_Kunjungan_Lab_summary.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
<?php if (EWRPT_CLIENT_VALIDATE) { ?>
Laporan_Kunjungan_Lab_summary.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
Laporan_Kunjungan_Lab_summary.ValidateRequired = false; // no JavaScript validation
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
<?php $Laporan_Kunjungan_Lab_summary->ShowPageHeader(); ?>
<?php $Laporan_Kunjungan_Lab_summary->ShowMessage(); ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "" || $Laporan_Kunjungan_Lab->Export == "print" || $Laporan_Kunjungan_Lab->Export == "email") { ?>
<script src="FusionChartsFree/JSClass/FusionCharts.js" type="text/javascript"></script>
<?php } ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
<script src="phprptjs/popup.js" type="text/javascript"></script>
<script src="phprptjs/ewrptpop.js" type="text/javascript"></script>
<script type="text/javascript">

// popup fields
</script>
<?php } ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
<!-- Table Container (Begin) -->
<table id="ewContainer" cellspacing="0" cellpadding="0" border="0">
<!-- Top Container (Begin) -->
<tr><td colspan="3"><div id="ewTop" class="phpreportmaker">
<!-- top slot -->
<a name="top"></a>
<?php } ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "" || $Laporan_Kunjungan_Lab->Export == "print" || $Laporan_Kunjungan_Lab->Export == "email") { ?>
<?php } ?>
<?php echo $Laporan_Kunjungan_Lab->TableCaption() ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
&nbsp;&nbsp;<a href="<?php echo $Laporan_Kunjungan_Lab_summary->ExportPrintUrl ?>"><?php echo $ReportLanguage->Phrase("PrinterFriendly") ?></a>
&nbsp;&nbsp;<a href="<?php echo $Laporan_Kunjungan_Lab_summary->ExportExcelUrl ?>"><?php echo $ReportLanguage->Phrase("ExportToExcel") ?></a>
<?php if ($Laporan_Kunjungan_Lab_summary->FilterApplied) { ?>
&nbsp;&nbsp;<a href="Laporan_Kunjungan_Labsmry.php?cmd=reset"><?php echo $ReportLanguage->Phrase("ResetAllFilter") ?></a>
<?php } ?>
<?php } ?>
<br /><br />
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
</div></td></tr>
<!-- Top Container (End) -->
<tr>
	<!-- Left Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewLeft" class="phpreportmaker">
	<!-- Left slot -->
<?php } ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "" || $Laporan_Kunjungan_Lab->Export == "print" || $Laporan_Kunjungan_Lab->Export == "email") { ?>
<?php } ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
	</div></td>
	<!-- Left Container (End) -->
	<!-- Center Container - Report (Begin) -->
	<td style="vertical-align: top;" class="ewPadding"><div id="ewCenter" class="phpreportmaker">
	<!-- center slot -->
<?php } ?>
<!-- summary report starts -->
<div id="report_summary">
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
<?php
if ($Laporan_Kunjungan_Lab->FilterPanelOption == 2 || ($Laporan_Kunjungan_Lab->FilterPanelOption == 3 && $Laporan_Kunjungan_Lab_summary->FilterApplied) || $Laporan_Kunjungan_Lab_summary->Filter == "0=101") {
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
<form name="fLaporan_Kunjungan_Labsummaryfilter" id="fLaporan_Kunjungan_Labsummaryfilter" action="Laporan_Kunjungan_Labsmry.php" class="ewForm" onsubmit="return Laporan_Kunjungan_Lab_summary.ValidateForm(this);">
<table class="ewRptExtFilter">
	<tr>
		<td><span class="phpreportmaker"><?php echo $Laporan_Kunjungan_Lab->Ruang_Asal->FldCaption() ?></span></td>
		<td></td>
		<td colspan="4"><span class="ewRptSearchOpr">
		<select name="sv_Ruang_Asal" id="sv_Ruang_Asal"<?php echo ($Laporan_Kunjungan_Lab_summary->ClearExtFilter == 'Laporan_Kunjungan_Lab_Ruang_Asal') ? " class=\"ewInputCleared\"" : "" ?>>
		<option value="<?php echo EWRPT_ALL_VALUE; ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownValue, EWRPT_ALL_VALUE)) echo " selected=\"selected\""; ?>><?php echo $ReportLanguage->Phrase("PleaseSelect"); ?></option>
<?php

// Popup filter
$cntf = is_array($Laporan_Kunjungan_Lab->Ruang_Asal->CustomFilters) ? count($Laporan_Kunjungan_Lab->Ruang_Asal->CustomFilters) : 0;
$cntd = is_array($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownList) ? count($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownList) : 0;
$totcnt = $cntf + $cntd;
$wrkcnt = 0;
	for ($i = 0; $i < $cntf; $i++) {
		if ($Laporan_Kunjungan_Lab->Ruang_Asal->CustomFilters[$i]->FldName == 'Ruang Asal') {
?>
		<option value="<?php echo "@@" . $Laporan_Kunjungan_Lab->Ruang_Asal->CustomFilters[$i]->FilterName ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownValue, "@@" . $Laporan_Kunjungan_Lab->Ruang_Asal->CustomFilters[$i]->FilterName)) echo " selected=\"selected\"" ?>><?php echo $Laporan_Kunjungan_Lab->Ruang_Asal->CustomFilters[$i]->DisplayName ?></option>
<?php
		}
		$wrkcnt += 1;
	}

//}
	for ($i = 0; $i < $cntd; $i++) {
?>
		<option value="<?php echo $Laporan_Kunjungan_Lab->Ruang_Asal->DropDownList[$i] ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownValue, $Laporan_Kunjungan_Lab->Ruang_Asal->DropDownList[$i])) echo " selected=\"selected\"" ?>><?php echo ewrpt_DropDownDisplayValue($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}

//}
?>
		</select>
		</span></td>
	</tr>
	<tr>
		<td><span class="phpreportmaker"><?php echo $Laporan_Kunjungan_Lab->Tipe_Pasien->FldCaption() ?></span></td>
		<td></td>
		<td colspan="4"><span class="ewRptSearchOpr">
		<select name="sv_Tipe_Pasien" id="sv_Tipe_Pasien"<?php echo ($Laporan_Kunjungan_Lab_summary->ClearExtFilter == 'Laporan_Kunjungan_Lab_Tipe_Pasien') ? " class=\"ewInputCleared\"" : "" ?>>
		<option value="<?php echo EWRPT_ALL_VALUE; ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownValue, EWRPT_ALL_VALUE)) echo " selected=\"selected\""; ?>><?php echo $ReportLanguage->Phrase("PleaseSelect"); ?></option>
<?php

// Popup filter
$cntf = is_array($Laporan_Kunjungan_Lab->Tipe_Pasien->CustomFilters) ? count($Laporan_Kunjungan_Lab->Tipe_Pasien->CustomFilters) : 0;
$cntd = is_array($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownList) ? count($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownList) : 0;
$totcnt = $cntf + $cntd;
$wrkcnt = 0;
	for ($i = 0; $i < $cntf; $i++) {
		if ($Laporan_Kunjungan_Lab->Tipe_Pasien->CustomFilters[$i]->FldName == 'Tipe Pasien') {
?>
		<option value="<?php echo "@@" . $Laporan_Kunjungan_Lab->Tipe_Pasien->CustomFilters[$i]->FilterName ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownValue, "@@" . $Laporan_Kunjungan_Lab->Tipe_Pasien->CustomFilters[$i]->FilterName)) echo " selected=\"selected\"" ?>><?php echo $Laporan_Kunjungan_Lab->Tipe_Pasien->CustomFilters[$i]->DisplayName ?></option>
<?php
		}
		$wrkcnt += 1;
	}

//}
	for ($i = 0; $i < $cntd; $i++) {
?>
		<option value="<?php echo $Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownList[$i] ?>"<?php if (ewrpt_MatchedFilterValue($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownValue, $Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownList[$i])) echo " selected=\"selected\"" ?>><?php echo ewrpt_DropDownDisplayValue($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}

//}
?>
		</select>
		</span></td>
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
<?php if ($Laporan_Kunjungan_Lab->ShowCurrentFilter) { ?>
<div id="ewrptFilterList">
<?php $Laporan_Kunjungan_Lab_summary->ShowFilterList() ?>
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
if ($Laporan_Kunjungan_Lab->ExportAll && $Laporan_Kunjungan_Lab->Export <> "") {
	$Laporan_Kunjungan_Lab_summary->StopGrp = $Laporan_Kunjungan_Lab_summary->TotalGrps;
} else {
	$Laporan_Kunjungan_Lab_summary->StopGrp = $Laporan_Kunjungan_Lab_summary->StartGrp + $Laporan_Kunjungan_Lab_summary->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Laporan_Kunjungan_Lab_summary->StopGrp) > intval($Laporan_Kunjungan_Lab_summary->TotalGrps))
	$Laporan_Kunjungan_Lab_summary->StopGrp = $Laporan_Kunjungan_Lab_summary->TotalGrps;
$Laporan_Kunjungan_Lab_summary->RecCount = 0;

// Get first row
if ($Laporan_Kunjungan_Lab_summary->TotalGrps > 0) {
	$Laporan_Kunjungan_Lab_summary->GetRow(1);
	$Laporan_Kunjungan_Lab_summary->GrpCount = 1;
}
while (($rs && !$rs->EOF && $Laporan_Kunjungan_Lab_summary->GrpCount <= $Laporan_Kunjungan_Lab_summary->DisplayGrps) || $Laporan_Kunjungan_Lab_summary->ShowFirstHeader) {

	// Show header
	if ($Laporan_Kunjungan_Lab_summary->ShowFirstHeader) {
?>
	<thead>
	<tr>
<td class="ewTableHeader">
<?php if ($Laporan_Kunjungan_Lab->Export <> "") { ?>
<?php echo $Laporan_Kunjungan_Lab->Ruang_Asal->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Kunjungan_Lab->SortUrl($Laporan_Kunjungan_Lab->Ruang_Asal) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Kunjungan_Lab->Ruang_Asal->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Kunjungan_Lab->SortUrl($Laporan_Kunjungan_Lab->Ruang_Asal) ?>',1);"><?php echo $Laporan_Kunjungan_Lab->Ruang_Asal->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Kunjungan_Lab->Ruang_Asal->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Kunjungan_Lab->Ruang_Asal->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Kunjungan_Lab->Export <> "") { ?>
<?php echo $Laporan_Kunjungan_Lab->Tipe_Pasien->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Kunjungan_Lab->SortUrl($Laporan_Kunjungan_Lab->Tipe_Pasien) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Kunjungan_Lab->Tipe_Pasien->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Kunjungan_Lab->SortUrl($Laporan_Kunjungan_Lab->Tipe_Pasien) ?>',1);"><?php echo $Laporan_Kunjungan_Lab->Tipe_Pasien->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Kunjungan_Lab->Tipe_Pasien->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Kunjungan_Lab->Tipe_Pasien->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Laporan_Kunjungan_Lab->Export <> "") { ?>
<?php echo $Laporan_Kunjungan_Lab->Jumlah->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Laporan_Kunjungan_Lab->SortUrl($Laporan_Kunjungan_Lab->Jumlah) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Laporan_Kunjungan_Lab->Jumlah->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Laporan_Kunjungan_Lab->SortUrl($Laporan_Kunjungan_Lab->Jumlah) ?>',1);"><?php echo $Laporan_Kunjungan_Lab->Jumlah->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Laporan_Kunjungan_Lab->Jumlah->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Laporan_Kunjungan_Lab->Jumlah->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
	</tr>
	</thead>
	<tbody>
<?php
		$Laporan_Kunjungan_Lab_summary->ShowFirstHeader = FALSE;
	}
	$Laporan_Kunjungan_Lab_summary->RecCount++;

		// Render detail row
		$Laporan_Kunjungan_Lab->ResetCSS();
		$Laporan_Kunjungan_Lab->RowType = EWRPT_ROWTYPE_DETAIL;
		$Laporan_Kunjungan_Lab_summary->RenderRow();
?>
	<tr<?php echo $Laporan_Kunjungan_Lab->RowAttributes(); ?>>
		<td<?php echo $Laporan_Kunjungan_Lab->Ruang_Asal->CellAttributes() ?>>
<div<?php echo $Laporan_Kunjungan_Lab->Ruang_Asal->ViewAttributes(); ?>><?php echo $Laporan_Kunjungan_Lab->Ruang_Asal->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Kunjungan_Lab->Tipe_Pasien->CellAttributes() ?>>
<div<?php echo $Laporan_Kunjungan_Lab->Tipe_Pasien->ViewAttributes(); ?>><?php echo $Laporan_Kunjungan_Lab->Tipe_Pasien->ListViewValue(); ?></div>
</td>
		<td<?php echo $Laporan_Kunjungan_Lab->Jumlah->CellAttributes() ?>>
<div<?php echo $Laporan_Kunjungan_Lab->Jumlah->ViewAttributes(); ?>><?php echo $Laporan_Kunjungan_Lab->Jumlah->ListViewValue(); ?></div>
</td>
	</tr>
<?php

		// Accumulate page summary
		$Laporan_Kunjungan_Lab_summary->AccumulateSummary();

		// Get next record
		$Laporan_Kunjungan_Lab_summary->GetRow(2);
	$Laporan_Kunjungan_Lab_summary->GrpCount++;
} // End while
?>
	</tbody>
	<tfoot>
<?php
if ($Laporan_Kunjungan_Lab_summary->TotalGrps > 0) {
	$Laporan_Kunjungan_Lab->ResetCSS();
	$Laporan_Kunjungan_Lab->RowType = EWRPT_ROWTYPE_TOTAL;
	$Laporan_Kunjungan_Lab->RowTotalType = EWRPT_ROWTOTAL_GRAND;
	$Laporan_Kunjungan_Lab->RowTotalSubType = EWRPT_ROWTOTAL_FOOTER;
	$Laporan_Kunjungan_Lab->RowAttrs["class"] = "ewRptGrandSummary";
	$Laporan_Kunjungan_Lab_summary->RenderRow();
?>
	<!-- tr><td colspan="3"><span class="phpreportmaker">&nbsp;<br /></span></td></tr -->
	<tr<?php echo $Laporan_Kunjungan_Lab->RowAttributes(); ?>><td colspan="3"><?php echo $ReportLanguage->Phrase("RptGrandTotal") ?> (<?php echo ewrpt_FormatNumber($Laporan_Kunjungan_Lab_summary->TotCount,0,-2,-2,-2); ?> <?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
	</tfoot>
</table>
</div>
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
<div class="ewGridLowerPanel">
<form action="Laporan_Kunjungan_Labsmry.php" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($Laporan_Kunjungan_Lab_summary->StartGrp, $Laporan_Kunjungan_Lab_summary->DisplayGrps, $Laporan_Kunjungan_Lab_summary->TotalGrps) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<td><a href="Laporan_Kunjungan_Labsmry.php?start=<?php echo $Pager->FirstButton->Start ?>"><img src="phprptimages/first.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/firstdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<td><a href="Laporan_Kunjungan_Labsmry.php?start=<?php echo $Pager->PrevButton->Start ?>"><img src="phprptimages/prev.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/prevdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" id="pageno" value="<?php echo $Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($Pager->NextButton->Enabled) { ?>
	<td><a href="Laporan_Kunjungan_Labsmry.php?start=<?php echo $Pager->NextButton->Start ?>"><img src="phprptimages/next.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/nextdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($Pager->LastButton->Enabled) { ?>
	<td><a href="Laporan_Kunjungan_Labsmry.php?start=<?php echo $Pager->LastButton->Start ?>"><img src="phprptimages/last.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
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
	<?php if ($Laporan_Kunjungan_Lab_summary->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($Laporan_Kunjungan_Lab_summary->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($Laporan_Kunjungan_Lab_summary->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($Laporan_Kunjungan_Lab_summary->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($Laporan_Kunjungan_Lab_summary->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($Laporan_Kunjungan_Lab_summary->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($Laporan_Kunjungan_Lab_summary->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($Laporan_Kunjungan_Lab_summary->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($Laporan_Kunjungan_Lab_summary->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($Laporan_Kunjungan_Lab_summary->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="100"<?php if ($Laporan_Kunjungan_Lab_summary->DisplayGrps == 100) echo " selected=\"selected\"" ?>>100</option>
<option value="ALL"<?php if ($Laporan_Kunjungan_Lab->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
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
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
	</div><br /></td>
	<!-- Center Container - Report (End) -->
	<!-- Right Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewRight" class="phpreportmaker">
	<!-- Right slot -->
<?php } ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "" || $Laporan_Kunjungan_Lab->Export == "print" || $Laporan_Kunjungan_Lab->Export == "email") { ?>
<?php } ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
	</div></td>
	<!-- Right Container (End) -->
</tr>
<!-- Bottom Container (Begin) -->
<tr><td colspan="3"><div id="ewBottom" class="phpreportmaker">
	<!-- Bottom slot -->
<?php } ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "" || $Laporan_Kunjungan_Lab->Export == "print" || $Laporan_Kunjungan_Lab->Export == "email") { ?>
<?php } ?>
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
	</div><br /></td></tr>
<!-- Bottom Container (End) -->
</table>
<!-- Table Container (End) -->
<?php } ?>
<?php $Laporan_Kunjungan_Lab_summary->ShowPageFooter(); ?>
<?php if (EWRPT_DEBUG_ENABLED) echo ewrpt_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Laporan_Kunjungan_Lab->Export == "") { ?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php } ?>
<?php include "phprptinc/footer.php"; ?>
<?php
$Laporan_Kunjungan_Lab_summary->Page_Terminate();
?>
<?php

//
// Page class
//
class crLaporan_Kunjungan_Lab_summary {

	// Page ID
	var $PageID = 'summary';

	// Table name
	var $TableName = 'Laporan Kunjungan Lab';

	// Page object name
	var $PageObjName = 'Laporan_Kunjungan_Lab_summary';

	// Page name
	function PageName() {
		return ewrpt_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewrpt_CurrentPage() . "?";
		global $Laporan_Kunjungan_Lab;
		if ($Laporan_Kunjungan_Lab->UseTokenInUrl) $PageUrl .= "t=" . $Laporan_Kunjungan_Lab->TableVar . "&"; // Add page token
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
		global $Laporan_Kunjungan_Lab;
		if ($Laporan_Kunjungan_Lab->UseTokenInUrl) {
			if (ewrpt_IsHttpPost())
				return ($Laporan_Kunjungan_Lab->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($Laporan_Kunjungan_Lab->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function crLaporan_Kunjungan_Lab_summary() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Table object (Laporan_Kunjungan_Lab)
		$GLOBALS["Laporan_Kunjungan_Lab"] = new crLaporan_Kunjungan_Lab();

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EWRPT_PAGE_ID"))
			define("EWRPT_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWRPT_TABLE_NAME"))
			define("EWRPT_TABLE_NAME", 'Laporan Kunjungan Lab', TRUE);

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
		global $Laporan_Kunjungan_Lab;

	// Get export parameters
	if (@$_GET["export"] <> "") {
		$Laporan_Kunjungan_Lab->Export = $_GET["export"];
	}
	$gsExport = $Laporan_Kunjungan_Lab->Export; // Get export parameter, used in header
	$gsExportFile = $Laporan_Kunjungan_Lab->TableVar; // Get export file, used in header
	if ($Laporan_Kunjungan_Lab->Export == "excel") {
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
		global $Laporan_Kunjungan_Lab;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export to Email (use ob_file_contents for PHP)
		if ($Laporan_Kunjungan_Lab->Export == "email") {
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
		global $Laporan_Kunjungan_Lab;
		global $rs;
		global $rsgrp;
		global $gsFormError;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 4;
		$nGrps = 1;
		$this->Val = ewrpt_InitArray($nDtls, 0);
		$this->Cnt = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandSmry = ewrpt_InitArray($nDtls, 0);
		$this->GrandMn = ewrpt_InitArray($nDtls, NULL);
		$this->GrandMx = ewrpt_InitArray($nDtls, NULL);

		// Set up if accumulation required
		$this->Col = array(FALSE, FALSE, FALSE, FALSE);

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
		$Laporan_Kunjungan_Lab->CustomFilters_Load();

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

		// Get total count
		$sSql = ewrpt_BuildReportSql($Laporan_Kunjungan_Lab->SqlSelect(), $Laporan_Kunjungan_Lab->SqlWhere(), $Laporan_Kunjungan_Lab->SqlGroupBy(), $Laporan_Kunjungan_Lab->SqlHaving(), $Laporan_Kunjungan_Lab->SqlOrderBy(), $this->Filter, $this->Sort);
		$this->TotalGrps = $this->GetCnt($sSql);
		if ($this->DisplayGrps <= 0) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowFirstHeader = ($this->TotalGrps > 0);

		//$this->ShowFirstHeader = TRUE; // Uncomment to always show header
		// Set up start position if not export all

		if ($Laporan_Kunjungan_Lab->ExportAll && $Laporan_Kunjungan_Lab->Export <> "")
		    $this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup(); 

		// Get current page records
		$rs = $this->GetRs($sSql, $this->StartGrp, $this->DisplayGrps);
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

	// Get count
	function GetCnt($sql) {
		global $conn;
		$rscnt = $conn->Execute($sql);
		$cnt = ($rscnt) ? $rscnt->RecordCount() : 0;
		if ($rscnt) $rscnt->Close();
		return $cnt;
	}

	// Get rs
	function GetRs($sql, $start, $grps) {
		global $conn;
		$wrksql = $sql;
		if ($start > 0 && $grps > -1)
			$wrksql .= " LIMIT " . ($start-1) . ", " . ($grps);
		$rswrk = $conn->Execute($wrksql);
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		global $Laporan_Kunjungan_Lab;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$Laporan_Kunjungan_Lab->Ruang_Asal->setDbValue($rs->fields('Ruang Asal'));
			$Laporan_Kunjungan_Lab->Tipe_Pasien->setDbValue($rs->fields('Tipe Pasien'));
			$Laporan_Kunjungan_Lab->Jumlah->setDbValue($rs->fields('Jumlah'));
			$this->Val[1] = $Laporan_Kunjungan_Lab->Ruang_Asal->CurrentValue;
			$this->Val[2] = $Laporan_Kunjungan_Lab->Tipe_Pasien->CurrentValue;
			$this->Val[3] = $Laporan_Kunjungan_Lab->Jumlah->CurrentValue;
		} else {
			$Laporan_Kunjungan_Lab->Ruang_Asal->setDbValue("");
			$Laporan_Kunjungan_Lab->Tipe_Pasien->setDbValue("");
			$Laporan_Kunjungan_Lab->Jumlah->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {
		global $Laporan_Kunjungan_Lab;

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWRPT_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWRPT_TABLE_START_GROUP];
			$Laporan_Kunjungan_Lab->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$Laporan_Kunjungan_Lab->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $Laporan_Kunjungan_Lab->getStartGroup();
			}
		} else {
			$this->StartGrp = $Laporan_Kunjungan_Lab->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$Laporan_Kunjungan_Lab->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$Laporan_Kunjungan_Lab->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$Laporan_Kunjungan_Lab->setStartGroup($this->StartGrp);
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		global $Laporan_Kunjungan_Lab;

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
		global $Laporan_Kunjungan_Lab;
		$this->StartGrp = 1;
		$Laporan_Kunjungan_Lab->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		global $Laporan_Kunjungan_Lab;
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
			$Laporan_Kunjungan_Lab->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$Laporan_Kunjungan_Lab->setStartGroup($this->StartGrp);
		} else {
			if ($Laporan_Kunjungan_Lab->getGroupPerPage() <> "") {
				$this->DisplayGrps = $Laporan_Kunjungan_Lab->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 100; // Load default
			}
		}
	}

	function RenderRow() {
		global $conn, $rs, $Security;
		global $Laporan_Kunjungan_Lab;
		if ($Laporan_Kunjungan_Lab->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total

			// Get total count from sql directly
			$sSql = ewrpt_BuildReportSql($Laporan_Kunjungan_Lab->SqlSelectCount(), $Laporan_Kunjungan_Lab->SqlWhere(), $Laporan_Kunjungan_Lab->SqlGroupBy(), $Laporan_Kunjungan_Lab->SqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
			} else {
				$this->TotCount = 0;
			}
		}

		// Call Row_Rendering event
		$Laporan_Kunjungan_Lab->Row_Rendering();

		//
		// Render view codes
		//

		if ($Laporan_Kunjungan_Lab->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row

			// Ruang Asal
			$Laporan_Kunjungan_Lab->Ruang_Asal->ViewValue = $Laporan_Kunjungan_Lab->Ruang_Asal->Summary;

			// Tipe Pasien
			$Laporan_Kunjungan_Lab->Tipe_Pasien->ViewValue = $Laporan_Kunjungan_Lab->Tipe_Pasien->Summary;

			// Jumlah
			$Laporan_Kunjungan_Lab->Jumlah->ViewValue = $Laporan_Kunjungan_Lab->Jumlah->Summary;
			$Laporan_Kunjungan_Lab->Jumlah->ViewValue = ewrpt_FormatNumber($Laporan_Kunjungan_Lab->Jumlah->ViewValue, 0, -2, -2, -2);
		} else {

			// Ruang Asal
			$Laporan_Kunjungan_Lab->Ruang_Asal->ViewValue = $Laporan_Kunjungan_Lab->Ruang_Asal->CurrentValue;
			$Laporan_Kunjungan_Lab->Ruang_Asal->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Tipe Pasien
			$Laporan_Kunjungan_Lab->Tipe_Pasien->ViewValue = $Laporan_Kunjungan_Lab->Tipe_Pasien->CurrentValue;
			$Laporan_Kunjungan_Lab->Tipe_Pasien->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// Jumlah
			$Laporan_Kunjungan_Lab->Jumlah->ViewValue = $Laporan_Kunjungan_Lab->Jumlah->CurrentValue;
			$Laporan_Kunjungan_Lab->Jumlah->ViewValue = ewrpt_FormatNumber($Laporan_Kunjungan_Lab->Jumlah->ViewValue, 0, -2, -2, -2);
			$Laporan_Kunjungan_Lab->Jumlah->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
		}

		// Ruang Asal
		$Laporan_Kunjungan_Lab->Ruang_Asal->HrefValue = "";

		// Tipe Pasien
		$Laporan_Kunjungan_Lab->Tipe_Pasien->HrefValue = "";

		// Jumlah
		$Laporan_Kunjungan_Lab->Jumlah->HrefValue = "";

		// Call Row_Rendered event
		$Laporan_Kunjungan_Lab->Row_Rendered();
	}

	// Get extended filter values
	function GetExtendedFilterValues() {
		global $Laporan_Kunjungan_Lab;

		// Field Ruang Asal
		$sSelect = "SELECT DISTINCT `Ruang Asal` FROM " . $Laporan_Kunjungan_Lab->SqlFrom();
		$sOrderBy = "`Ruang Asal` ASC";
		$wrkSql = ewrpt_BuildReportSql($sSelect, $Laporan_Kunjungan_Lab->SqlWhere(), "", "", $sOrderBy, $this->UserIDFilter, "");
		$Laporan_Kunjungan_Lab->Ruang_Asal->DropDownList = ewrpt_GetDistinctValues("", $wrkSql);

		// Field Tipe Pasien
		$sSelect = "SELECT DISTINCT `Tipe Pasien` FROM " . $Laporan_Kunjungan_Lab->SqlFrom();
		$sOrderBy = "`Tipe Pasien` ASC";
		$wrkSql = ewrpt_BuildReportSql($sSelect, $Laporan_Kunjungan_Lab->SqlWhere(), "", "", $sOrderBy, $this->UserIDFilter, "");
		$Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownList = ewrpt_GetDistinctValues("", $wrkSql);
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $Laporan_Kunjungan_Lab;
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
			// Field Ruang Asal

			$this->SetSessionDropDownValue($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownValue, 'Ruang_Asal');

			// Field Tipe Pasien
			$this->SetSessionDropDownValue($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownValue, 'Tipe_Pasien');
			$bSetupFilter = TRUE;
		} else {

			// Field Ruang Asal
			if ($this->GetDropDownValue($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownValue, 'Ruang_Asal')) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			} elseif ($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownValue <> EWRPT_INIT_VALUE && !isset($_SESSION['sv_Laporan_Kunjungan_Lab->Ruang_Asal'])) {
				$bSetupFilter = TRUE;
			}

			// Field Tipe Pasien
			if ($this->GetDropDownValue($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownValue, 'Tipe_Pasien')) {
				$bSetupFilter = TRUE;
				$bRestoreSession = FALSE;
			} elseif ($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownValue <> EWRPT_INIT_VALUE && !isset($_SESSION['sv_Laporan_Kunjungan_Lab->Tipe_Pasien'])) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setMessage($gsFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {

			// Field Ruang Asal
			$this->GetSessionDropDownValue($Laporan_Kunjungan_Lab->Ruang_Asal);

			// Field Tipe Pasien
			$this->GetSessionDropDownValue($Laporan_Kunjungan_Lab->Tipe_Pasien);
		}

		// Call page filter validated event
		$Laporan_Kunjungan_Lab->Page_FilterValidated();

		// Build SQL
		// Field Ruang Asal

		$this->BuildDropDownFilter($Laporan_Kunjungan_Lab->Ruang_Asal, $sFilter, "");

		// Field Tipe Pasien
		$this->BuildDropDownFilter($Laporan_Kunjungan_Lab->Tipe_Pasien, $sFilter, "");

		// Save parms to session
		// Field Ruang Asal

		$this->SetSessionDropDownValue($Laporan_Kunjungan_Lab->Ruang_Asal->DropDownValue, 'Ruang_Asal');

		// Field Tipe Pasien
		$this->SetSessionDropDownValue($Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownValue, 'Tipe_Pasien');

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
		$this->GetSessionValue($fld->DropDownValue, 'sv_Laporan_Kunjungan_Lab_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv1_Laporan_Kunjungan_Lab_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so1_Laporan_Kunjungan_Lab_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_Laporan_Kunjungan_Lab_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_Laporan_Kunjungan_Lab_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_Laporan_Kunjungan_Lab_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (isset($_SESSION[$sn]))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $parm) {
		$_SESSION['sv_Laporan_Kunjungan_Lab_' . $parm] = $sv;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv1_Laporan_Kunjungan_Lab_' . $parm] = $sv1;
		$_SESSION['so1_Laporan_Kunjungan_Lab_' . $parm] = $so1;
		$_SESSION['sc_Laporan_Kunjungan_Lab_' . $parm] = $sc;
		$_SESSION['sv2_Laporan_Kunjungan_Lab_' . $parm] = $sv2;
		$_SESSION['so2_Laporan_Kunjungan_Lab_' . $parm] = $so2;
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
		global $ReportLanguage, $gsFormError, $Laporan_Kunjungan_Lab;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWRPT_SERVER_VALIDATE)
			return ($gsFormError == "");

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
		$_SESSION["sel_Laporan_Kunjungan_Lab_$parm"] = "";
		$_SESSION["rf_Laporan_Kunjungan_Lab_$parm"] = "";
		$_SESSION["rt_Laporan_Kunjungan_Lab_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		global $Laporan_Kunjungan_Lab;
		$fld =& $Laporan_Kunjungan_Lab->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_Laporan_Kunjungan_Lab_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_Laporan_Kunjungan_Lab_$parm"];
		$fld->RangeTo = @$_SESSION["rt_Laporan_Kunjungan_Lab_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		global $Laporan_Kunjungan_Lab;

		/**
		* Set up default values for non Text filters
		*/

		// Field Ruang Asal
		$Laporan_Kunjungan_Lab->Ruang_Asal->DefaultDropDownValue = EWRPT_INIT_VALUE;
		$Laporan_Kunjungan_Lab->Ruang_Asal->DropDownValue = $Laporan_Kunjungan_Lab->Ruang_Asal->DefaultDropDownValue;

		// Field Tipe Pasien
		$Laporan_Kunjungan_Lab->Tipe_Pasien->DefaultDropDownValue = EWRPT_INIT_VALUE;
		$Laporan_Kunjungan_Lab->Tipe_Pasien->DropDownValue = $Laporan_Kunjungan_Lab->Tipe_Pasien->DefaultDropDownValue;

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

		/**
		* Set up default values for popup filters
		* NOTE: if extended filter is enabled, use default values in extended filter instead
		*/
	}

	// Check if filter applied
	function CheckFilter() {
		global $Laporan_Kunjungan_Lab;

		// Check Ruang Asal extended filter
		if ($this->NonTextFilterApplied($Laporan_Kunjungan_Lab->Ruang_Asal))
			return TRUE;

		// Check Tipe Pasien extended filter
		if ($this->NonTextFilterApplied($Laporan_Kunjungan_Lab->Tipe_Pasien))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList() {
		global $Laporan_Kunjungan_Lab;
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field Ruang Asal
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($Laporan_Kunjungan_Lab->Ruang_Asal, $sExtWrk, "");
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Laporan_Kunjungan_Lab->Ruang_Asal->FldCaption() . "<br />";
		if ($sExtWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sExtWrk<br />";
		if ($sWrk <> "")
			$sFilterList .= "&nbsp;&nbsp;$sWrk<br />";

		// Field Tipe Pasien
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($Laporan_Kunjungan_Lab->Tipe_Pasien, $sExtWrk, "");
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Laporan_Kunjungan_Lab->Tipe_Pasien->FldCaption() . "<br />";
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
		global $Laporan_Kunjungan_Lab;
		$sWrk = "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWRPT_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		global $Laporan_Kunjungan_Lab;

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$Laporan_Kunjungan_Lab->setOrderBy("");
				$Laporan_Kunjungan_Lab->setStartGroup(1);
				$Laporan_Kunjungan_Lab->Ruang_Asal->setSort("");
				$Laporan_Kunjungan_Lab->Tipe_Pasien->setSort("");
				$Laporan_Kunjungan_Lab->Jumlah->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$Laporan_Kunjungan_Lab->CurrentOrder = ewrpt_StripSlashes(@$_GET["order"]);
			$Laporan_Kunjungan_Lab->CurrentOrderType = @$_GET["ordertype"];
			$Laporan_Kunjungan_Lab->UpdateSort($Laporan_Kunjungan_Lab->Ruang_Asal); // Ruang Asal
			$Laporan_Kunjungan_Lab->UpdateSort($Laporan_Kunjungan_Lab->Tipe_Pasien); // Tipe Pasien
			$Laporan_Kunjungan_Lab->UpdateSort($Laporan_Kunjungan_Lab->Jumlah); // Jumlah
			$sSortSql = $Laporan_Kunjungan_Lab->SortSql();
			$Laporan_Kunjungan_Lab->setOrderBy($sSortSql);
			$Laporan_Kunjungan_Lab->setStartGroup(1);
		}
		return $Laporan_Kunjungan_Lab->getOrderBy();
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
