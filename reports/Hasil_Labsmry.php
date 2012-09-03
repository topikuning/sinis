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
$Hasil_Lab = NULL;

//
// Table class for Hasil Lab
//
class crHasil_Lab {
	var $TableVar = 'Hasil_Lab';
	var $TableName = 'Hasil Lab';
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
	var $TANGGAL;
	var $RM_Px2E;
	var $NAMA_PASIEN;
	var $KELOMPOK;
	var $PEMERIKSAAN;
	var $METODE;
	var $NILAI_NORMAL;
	var $HASIL;
	var $No_Kunjungan;
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
	function crHasil_Lab() {
		global $ReportLanguage;

		// TANGGAL
		$this->TANGGAL = new crField('Hasil_Lab', 'Hasil Lab', 'x_TANGGAL', 'TANGGAL', '`TANGGAL`', 133, EWRPT_DATATYPE_DATE, 7);
		$this->TANGGAL->FldDefaultErrMsg = str_replace("%s", "/", $ReportLanguage->Phrase("IncorrectDateDMY"));
		$this->fields['TANGGAL'] =& $this->TANGGAL;
		$this->TANGGAL->DateFilter = "";
		$this->TANGGAL->SqlSelect = "";
		$this->TANGGAL->SqlOrderBy = "";

		// RM Px.
		$this->RM_Px2E = new crField('Hasil_Lab', 'Hasil Lab', 'x_RM_Px2E', 'RM Px.', '`RM Px.`', 19, EWRPT_DATATYPE_NUMBER, -1);
		$this->RM_Px2E->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['RM_Px2E'] =& $this->RM_Px2E;
		$this->RM_Px2E->DateFilter = "";
		$this->RM_Px2E->SqlSelect = "";
		$this->RM_Px2E->SqlOrderBy = "";

		// NAMA PASIEN
		$this->NAMA_PASIEN = new crField('Hasil_Lab', 'Hasil Lab', 'x_NAMA_PASIEN', 'NAMA PASIEN', '`NAMA PASIEN`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['NAMA_PASIEN'] =& $this->NAMA_PASIEN;
		$this->NAMA_PASIEN->DateFilter = "";
		$this->NAMA_PASIEN->SqlSelect = "";
		$this->NAMA_PASIEN->SqlOrderBy = "";

		// KELOMPOK
		$this->KELOMPOK = new crField('Hasil_Lab', 'Hasil Lab', 'x_KELOMPOK', 'KELOMPOK', '`KELOMPOK`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['KELOMPOK'] =& $this->KELOMPOK;
		$this->KELOMPOK->DateFilter = "";
		$this->KELOMPOK->SqlSelect = "";
		$this->KELOMPOK->SqlOrderBy = "";

		// PEMERIKSAAN
		$this->PEMERIKSAAN = new crField('Hasil_Lab', 'Hasil Lab', 'x_PEMERIKSAAN', 'PEMERIKSAAN', '`PEMERIKSAAN`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['PEMERIKSAAN'] =& $this->PEMERIKSAAN;
		$this->PEMERIKSAAN->DateFilter = "";
		$this->PEMERIKSAAN->SqlSelect = "";
		$this->PEMERIKSAAN->SqlOrderBy = "";

		// METODE
		$this->METODE = new crField('Hasil_Lab', 'Hasil Lab', 'x_METODE', 'METODE', '`METODE`', 200, EWRPT_DATATYPE_STRING, -1);
		$this->fields['METODE'] =& $this->METODE;
		$this->METODE->DateFilter = "";
		$this->METODE->SqlSelect = "";
		$this->METODE->SqlOrderBy = "";

		// NILAI NORMAL
		$this->NILAI_NORMAL = new crField('Hasil_Lab', 'Hasil Lab', 'x_NILAI_NORMAL', 'NILAI NORMAL', '`NILAI NORMAL`', 201, EWRPT_DATATYPE_MEMO, -1);
		$this->fields['NILAI_NORMAL'] =& $this->NILAI_NORMAL;
		$this->NILAI_NORMAL->DateFilter = "";
		$this->NILAI_NORMAL->SqlSelect = "";
		$this->NILAI_NORMAL->SqlOrderBy = "";

		// HASIL
		$this->HASIL = new crField('Hasil_Lab', 'Hasil Lab', 'x_HASIL', 'HASIL', '`HASIL`', 201, EWRPT_DATATYPE_MEMO, -1);
		$this->fields['HASIL'] =& $this->HASIL;
		$this->HASIL->DateFilter = "";
		$this->HASIL->SqlSelect = "";
		$this->HASIL->SqlOrderBy = "";

		// No Kunjungan
		$this->No_Kunjungan = new crField('Hasil_Lab', 'Hasil Lab', 'x_No_Kunjungan', 'No Kunjungan', '`No Kunjungan`', 3, EWRPT_DATATYPE_NUMBER, -1);
		$this->No_Kunjungan->GroupingFieldId = 1;
		$this->No_Kunjungan->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['No_Kunjungan'] =& $this->No_Kunjungan;
		$this->No_Kunjungan->DateFilter = "";
		$this->No_Kunjungan->SqlSelect = "";
		$this->No_Kunjungan->SqlOrderBy = "";
		$this->No_Kunjungan->FldGroupByType = "";
		$this->No_Kunjungan->FldGroupInt = "0";
		$this->No_Kunjungan->FldGroupSql = "";
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
		return "`hasil_lab`";
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
		return "`No Kunjungan` ASC";
	}

	// Table Level Group SQL
	function SqlFirstGroupField() {
		return "`No Kunjungan`";
	}

	function SqlSelectGroup() {
		return "SELECT DISTINCT " . $this->SqlFirstGroupField() . " FROM " . $this->SqlFrom();
	}

	function SqlOrderByGroup() {
		return "`No Kunjungan` ASC";
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
$Hasil_Lab_summary = new crHasil_Lab_summary();
$Page =& $Hasil_Lab_summary;

// Page init
$Hasil_Lab_summary->Page_Init();

// Page main
$Hasil_Lab_summary->Page_Main();
?>
<?php include "phprptinc/hLab.php"; ?>
<?php if ($Hasil_Lab->Export == "") { ?>
<script type="text/javascript">

// Create page object
var Hasil_Lab_summary = new ewrpt_Page("Hasil_Lab_summary");

// page properties
Hasil_Lab_summary.PageID = "summary"; // page ID
Hasil_Lab_summary.FormID = "fHasil_Labsummaryfilter"; // form ID
var EWRPT_PAGE_ID = Hasil_Lab_summary.PageID;

// extend page with ValidateForm function
Hasil_Lab_summary.ValidateForm = function(fobj) {
	if (!this.ValidateRequired)
		return true; // ignore validation
	var elm = fobj.sv1_RM_Px2E;
	if (elm && !ewrpt_CheckInteger(elm.value)) {
		if (!ewrpt_OnError(elm, "<?php echo ewrpt_JsEncode2($Hasil_Lab->RM_Px2E->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj)) return false;
	return true;
}

// extend page with Form_CustomValidate function
Hasil_Lab_summary.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }
<?php if (EWRPT_CLIENT_VALIDATE) { ?>
Hasil_Lab_summary.ValidateRequired = true; // uses JavaScript validation
<?php } else { ?>
Hasil_Lab_summary.ValidateRequired = false; // no JavaScript validation
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
<?php $Hasil_Lab_summary->ShowPageHeader(); ?>
<?php $Hasil_Lab_summary->ShowMessage(); ?>
<script src="FusionChartsFree/JSClass/FusionCharts.js" type="text/javascript"></script>
<?php if ($Hasil_Lab->Export == "") { ?>
<script src="phprptjs/popup.js" type="text/javascript"></script>
<script src="phprptjs/ewrptpop.js" type="text/javascript"></script>
<script type="text/javascript">

// popup fields
</script>
<?php } ?>
<?php if ($Hasil_Lab->Export == "") { ?>
<!-- Table Container (Begin) -->
<table id="ewContainer" cellspacing="0" cellpadding="0" border="0">
<!-- Top Container (Begin) -->
<tr><td colspan="3"><div id="ewTop" class="phpreportmaker">
<!-- top slot -->
<a name="top"></a>
<?php } ?>
<?php echo $Hasil_Lab->TableCaption() ?>
<?php if ($Hasil_Lab->Export == "") { ?>
&nbsp;&nbsp;<a href="<?php echo $Hasil_Lab_summary->ExportExcelUrl ?>"><?php echo $ReportLanguage->Phrase("ExportToExcel") ?></a>
<?php if ($Hasil_Lab_summary->FilterApplied) { ?>
&nbsp;&nbsp;<a href="Hasil_Labsmry.php?cmd=reset"><?php echo $ReportLanguage->Phrase("ResetAllFilter") ?></a>
<?php } ?>
<?php } ?>
<br /><br />
<?php if ($Hasil_Lab->Export == "") { ?>
</div></td></tr>
<!-- Top Container (End) -->
<tr>
	<!-- Left Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewLeft" class="phpreportmaker">
	<!-- Left slot -->
<?php } ?>
<?php if ($Hasil_Lab->Export == "") { ?>
	</div></td>
	<!-- Left Container (End) -->
	<!-- Center Container - Report (Begin) -->
	<td style="vertical-align: top;" class="ewPadding"><div id="ewCenter" class="phpreportmaker">
	<!-- center slot -->
<?php } ?>
<!-- summary report starts -->
<div id="report_summary">
<?php if ($Hasil_Lab->Export == "") { ?>
<?php
if ($Hasil_Lab->FilterPanelOption == 2 || ($Hasil_Lab->FilterPanelOption == 3 && $Hasil_Lab_summary->FilterApplied) || $Hasil_Lab_summary->Filter == "0=101") {
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
<form name="fHasil_Labsummaryfilter" id="fHasil_Labsummaryfilter" action="Hasil_Labsmry.php" class="ewForm" onsubmit="return Hasil_Lab_summary.ValidateForm(this);">
<table class="ewRptExtFilter">
	<tr>
		<td><span class="phpreportmaker"><?php echo $Hasil_Lab->RM_Px2E->FldCaption() ?></span></td>
		<td><span class="ewRptSearchOpr"><?php echo $ReportLanguage->Phrase("="); ?><input type="hidden" name="so1_RM_Px2E" id="so1_RM_Px2E" value="="></span></td>
		<td>
			<table cellspacing="0" class="ewItemTable"><tr>
				<td><span class="phpreportmaker">
<input type="text" name="sv1_RM_Px2E" id="sv1_RM_Px2E" size="30" value="<?php echo ewrpt_HtmlEncode($Hasil_Lab->RM_Px2E->SearchValue) ?>"<?php echo ($Hasil_Lab_summary->ClearExtFilter == 'Hasil_Lab_RM_Px2E') ? " class=\"ewInputCleared\"" : "" ?>>
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
<?php if ($Hasil_Lab->ShowCurrentFilter) { ?>
<div id="ewrptFilterList">
<?php $Hasil_Lab_summary->ShowFilterList() ?>
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
if ($Hasil_Lab->ExportAll && $Hasil_Lab->Export <> "") {
	$Hasil_Lab_summary->StopGrp = $Hasil_Lab_summary->TotalGrps;
} else {
	$Hasil_Lab_summary->StopGrp = $Hasil_Lab_summary->StartGrp + $Hasil_Lab_summary->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Hasil_Lab_summary->StopGrp) > intval($Hasil_Lab_summary->TotalGrps))
	$Hasil_Lab_summary->StopGrp = $Hasil_Lab_summary->TotalGrps;
$Hasil_Lab_summary->RecCount = 0;

// Get first row
if ($Hasil_Lab_summary->TotalGrps > 0) {
	$Hasil_Lab_summary->GetGrpRow(1);
	$Hasil_Lab_summary->GrpCount = 1;
}
while (($rsgrp && !$rsgrp->EOF && $Hasil_Lab_summary->GrpCount <= $Hasil_Lab_summary->DisplayGrps) || $Hasil_Lab_summary->ShowFirstHeader) {

	// Show header
	if ($Hasil_Lab_summary->ShowFirstHeader) {
?>
	<thead>
	<tr>
<td class="ewTableHeader">
<?php if ($Hasil_Lab->Export <> "") { ?>
<?php echo $Hasil_Lab->No_Kunjungan->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Hasil_Lab->SortUrl($Hasil_Lab->No_Kunjungan) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Hasil_Lab->No_Kunjungan->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Hasil_Lab->SortUrl($Hasil_Lab->No_Kunjungan) ?>',1);"><?php echo $Hasil_Lab->No_Kunjungan->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Hasil_Lab->No_Kunjungan->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Hasil_Lab->No_Kunjungan->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Hasil_Lab->Export <> "") { ?>
<?php echo $Hasil_Lab->TANGGAL->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Hasil_Lab->SortUrl($Hasil_Lab->TANGGAL) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Hasil_Lab->TANGGAL->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Hasil_Lab->SortUrl($Hasil_Lab->TANGGAL) ?>',1);"><?php echo $Hasil_Lab->TANGGAL->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Hasil_Lab->TANGGAL->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Hasil_Lab->TANGGAL->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Hasil_Lab->Export <> "") { ?>
<?php echo $Hasil_Lab->RM_Px2E->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Hasil_Lab->SortUrl($Hasil_Lab->RM_Px2E) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Hasil_Lab->RM_Px2E->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Hasil_Lab->SortUrl($Hasil_Lab->RM_Px2E) ?>',1);"><?php echo $Hasil_Lab->RM_Px2E->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Hasil_Lab->RM_Px2E->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Hasil_Lab->RM_Px2E->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Hasil_Lab->Export <> "") { ?>
<?php echo $Hasil_Lab->NAMA_PASIEN->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Hasil_Lab->SortUrl($Hasil_Lab->NAMA_PASIEN) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Hasil_Lab->NAMA_PASIEN->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Hasil_Lab->SortUrl($Hasil_Lab->NAMA_PASIEN) ?>',1);"><?php echo $Hasil_Lab->NAMA_PASIEN->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Hasil_Lab->NAMA_PASIEN->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Hasil_Lab->NAMA_PASIEN->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Hasil_Lab->Export <> "") { ?>
<?php echo $Hasil_Lab->PEMERIKSAAN->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Hasil_Lab->SortUrl($Hasil_Lab->PEMERIKSAAN) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Hasil_Lab->PEMERIKSAAN->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Hasil_Lab->SortUrl($Hasil_Lab->PEMERIKSAAN) ?>',1);"><?php echo $Hasil_Lab->PEMERIKSAAN->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Hasil_Lab->PEMERIKSAAN->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Hasil_Lab->PEMERIKSAAN->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Hasil_Lab->Export <> "") { ?>
<?php echo $Hasil_Lab->NILAI_NORMAL->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Hasil_Lab->SortUrl($Hasil_Lab->NILAI_NORMAL) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Hasil_Lab->NILAI_NORMAL->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Hasil_Lab->SortUrl($Hasil_Lab->NILAI_NORMAL) ?>',1);"><?php echo $Hasil_Lab->NILAI_NORMAL->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Hasil_Lab->NILAI_NORMAL->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Hasil_Lab->NILAI_NORMAL->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
<td class="ewTableHeader">
<?php if ($Hasil_Lab->Export <> "") { ?>
<?php echo $Hasil_Lab->HASIL->FldCaption() ?>
<?php } else { ?>
	<table cellspacing="0" class="ewTableHeaderBtn"><tr>
<?php if ($Hasil_Lab->SortUrl($Hasil_Lab->HASIL) == "") { ?>
		<td style="vertical-align: bottom;"><?php echo $Hasil_Lab->HASIL->FldCaption() ?></td>
<?php } else { ?>
		<td class="ewPointer" onmousedown="ewrpt_Sort(event,'<?php echo $Hasil_Lab->SortUrl($Hasil_Lab->HASIL) ?>',1);"><?php echo $Hasil_Lab->HASIL->FldCaption() ?></td><td style="width: 10px;">
		<?php if ($Hasil_Lab->HASIL->getSort() == "ASC") { ?><img src="phprptimages/sortup.gif" width="10" height="9" border="0"><?php } elseif ($Hasil_Lab->HASIL->getSort() == "DESC") { ?><img src="phprptimages/sortdown.gif" width="10" height="9" border="0"><?php } ?></td>
<?php } ?>
	</tr></table>
<?php } ?>
</td>
	</tr>
	</thead>
	<tbody>
<?php
		$Hasil_Lab_summary->ShowFirstHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewrpt_DetailFilterSQL($Hasil_Lab->No_Kunjungan, $Hasil_Lab->SqlFirstGroupField(), $Hasil_Lab->No_Kunjungan->GroupValue());
	if ($Hasil_Lab_summary->Filter != "")
		$sWhere = "($Hasil_Lab_summary->Filter) AND ($sWhere)";
	$sSql = ewrpt_BuildReportSql($Hasil_Lab->SqlSelect(), $Hasil_Lab->SqlWhere(), $Hasil_Lab->SqlGroupBy(), $Hasil_Lab->SqlHaving(), $Hasil_Lab->SqlOrderBy(), $sWhere, $Hasil_Lab_summary->Sort);
	$rs = $conn->Execute($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Hasil_Lab_summary->GetRow(1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$Hasil_Lab_summary->RecCount++;

		// Render detail row
		$Hasil_Lab->ResetCSS();
		$Hasil_Lab->RowType = EWRPT_ROWTYPE_DETAIL;
		$Hasil_Lab_summary->RenderRow();
?>
	<tr<?php echo $Hasil_Lab->RowAttributes(); ?>>
		<td<?php echo $Hasil_Lab->No_Kunjungan->CellAttributes(); ?>><div<?php echo $Hasil_Lab->No_Kunjungan->ViewAttributes(); ?>><?php echo $Hasil_Lab->No_Kunjungan->GroupViewValue; ?></div></td>
		<td<?php echo $Hasil_Lab->TANGGAL->CellAttributes() ?>>
<div<?php echo $Hasil_Lab->TANGGAL->ViewAttributes(); ?>><?php echo $Hasil_Lab->TANGGAL->ListViewValue(); ?></div>
</td>
		<td<?php echo $Hasil_Lab->RM_Px2E->CellAttributes() ?>>
<div<?php echo $Hasil_Lab->RM_Px2E->ViewAttributes(); ?>><?php echo $Hasil_Lab->RM_Px2E->ListViewValue(); ?></div>
</td>
		<td<?php echo $Hasil_Lab->NAMA_PASIEN->CellAttributes() ?>>
<div<?php echo $Hasil_Lab->NAMA_PASIEN->ViewAttributes(); ?>><?php echo $Hasil_Lab->NAMA_PASIEN->ListViewValue(); ?></div>
</td>
		<td<?php echo $Hasil_Lab->PEMERIKSAAN->CellAttributes() ?>>
<div<?php echo $Hasil_Lab->PEMERIKSAAN->ViewAttributes(); ?>><?php echo $Hasil_Lab->PEMERIKSAAN->ListViewValue(); ?></div>
</td>
		<td<?php echo $Hasil_Lab->NILAI_NORMAL->CellAttributes() ?>>
<div<?php echo $Hasil_Lab->NILAI_NORMAL->ViewAttributes(); ?>><?php echo $Hasil_Lab->NILAI_NORMAL->ListViewValue(); ?></div>
</td>
		<td<?php echo $Hasil_Lab->HASIL->CellAttributes() ?>>
<div<?php echo $Hasil_Lab->HASIL->ViewAttributes(); ?>><?php echo $Hasil_Lab->HASIL->ListViewValue(); ?></div>
</td>
	</tr>
<?php

		// Accumulate page summary
		$Hasil_Lab_summary->AccumulateSummary();

		// Get next record
		$Hasil_Lab_summary->GetRow(2);

		// Show Footers
?>
<?php
	} // End detail records loop
?>
<?php

	// Next group
	$Hasil_Lab_summary->GetGrpRow(2);
	$Hasil_Lab_summary->GrpCount++;
} // End while
?>
	</tbody>
	<tfoot>
	</tfoot>
</table>
</div>
<?php if ($Hasil_Lab->Export == "") { ?>
<div class="ewGridLowerPanel">
<form action="Hasil_Labsmry.php" name="ewpagerform" id="ewpagerform" class="ewForm">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="white-space: nowrap;">
<?php if (!isset($Pager)) $Pager = new crPrevNextPager($Hasil_Lab_summary->StartGrp, $Hasil_Lab_summary->DisplayGrps, $Hasil_Lab_summary->TotalGrps) ?>
<?php if ($Pager->RecordCount > 0) { ?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($Pager->FirstButton->Enabled) { ?>
	<td><a href="Hasil_Labsmry.php?start=<?php echo $Pager->FirstButton->Start ?>"><img src="phprptimages/first.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/firstdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerFirst") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($Pager->PrevButton->Enabled) { ?>
	<td><a href="Hasil_Labsmry.php?start=<?php echo $Pager->PrevButton->Start ?>"><img src="phprptimages/prev.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></a></td>
	<?php } else { ?>
	<td><img src="phprptimages/prevdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerPrevious") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" id="pageno" value="<?php echo $Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($Pager->NextButton->Enabled) { ?>
	<td><a href="Hasil_Labsmry.php?start=<?php echo $Pager->NextButton->Start ?>"><img src="phprptimages/next.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></a></td>	
	<?php } else { ?>
	<td><img src="phprptimages/nextdisab.gif" alt="<?php echo $ReportLanguage->Phrase("PagerNext") ?>" width="16" height="16" border="0"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($Pager->LastButton->Enabled) { ?>
	<td><a href="Hasil_Labsmry.php?start=<?php echo $Pager->LastButton->Start ?>"><img src="phprptimages/last.gif" alt="<?php echo $ReportLanguage->Phrase("PagerLast") ?>" width="16" height="16" border="0"></a></td>	
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
	<?php if ($Hasil_Lab_summary->Filter == "0=101") { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
		</td>
<?php if ($Hasil_Lab_summary->TotalGrps > 0) { ?>
		<td style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" style="vertical-align: top; white-space: nowrap;"><span class="phpreportmaker"><?php echo $ReportLanguage->Phrase("GroupsPerPage"); ?>&nbsp;
<select name="<?php echo EWRPT_TABLE_GROUP_PER_PAGE; ?>" onchange="this.form.submit();">
<option value="1"<?php if ($Hasil_Lab_summary->DisplayGrps == 1) echo " selected=\"selected\"" ?>>1</option>
<option value="2"<?php if ($Hasil_Lab_summary->DisplayGrps == 2) echo " selected=\"selected\"" ?>>2</option>
<option value="3"<?php if ($Hasil_Lab_summary->DisplayGrps == 3) echo " selected=\"selected\"" ?>>3</option>
<option value="4"<?php if ($Hasil_Lab_summary->DisplayGrps == 4) echo " selected=\"selected\"" ?>>4</option>
<option value="5"<?php if ($Hasil_Lab_summary->DisplayGrps == 5) echo " selected=\"selected\"" ?>>5</option>
<option value="10"<?php if ($Hasil_Lab_summary->DisplayGrps == 10) echo " selected=\"selected\"" ?>>10</option>
<option value="20"<?php if ($Hasil_Lab_summary->DisplayGrps == 20) echo " selected=\"selected\"" ?>>20</option>
<option value="50"<?php if ($Hasil_Lab_summary->DisplayGrps == 50) echo " selected=\"selected\"" ?>>50</option>
<option value="ALL"<?php if ($Hasil_Lab->getGroupPerPage() == -1) echo " selected=\"selected\"" ?>><?php echo $ReportLanguage->Phrase("AllRecords") ?></option>
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
<?php if ($Hasil_Lab->Export == "") { ?>
	</div><br /></td>
	<!-- Center Container - Report (End) -->
	<!-- Right Container (Begin) -->
	<td style="vertical-align: top;"><div id="ewRight" class="phpreportmaker">
	<!-- Right slot -->
<?php } ?>
<?php if ($Hasil_Lab->Export == "") { ?>
	</div></td>
	<!-- Right Container (End) -->
</tr>
<!-- Bottom Container (Begin) -->
<tr><td colspan="3"><div id="ewBottom" class="phpreportmaker">
	<!-- Bottom slot -->
<?php } ?>
<?php if ($Hasil_Lab->Export == "") { ?>
	</div><br /></td></tr>
<!-- Bottom Container (End) -->
</table>
<!-- Table Container (End) -->
<?php } ?>
<?php $Hasil_Lab_summary->ShowPageFooter(); ?>
<?php if (EWRPT_DEBUG_ENABLED) echo ewrpt_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Hasil_Lab->Export == "") { ?>
<script language="JavaScript" type="text/javascript">
<!--

// Write your table-specific startup script here
// document.write("page loaded");
//-->

</script>
<?php } ?>
<?php include "phprptinc/footer.php"; ?>
<?php
$Hasil_Lab_summary->Page_Terminate();
?>
<?php

//
// Page class
//
class crHasil_Lab_summary {

	// Page ID
	var $PageID = 'summary';

	// Table name
	var $TableName = 'Hasil Lab';

	// Page object name
	var $PageObjName = 'Hasil_Lab_summary';

	// Page name
	function PageName() {
		return ewrpt_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewrpt_CurrentPage() . "?";
		global $Hasil_Lab;
		if ($Hasil_Lab->UseTokenInUrl) $PageUrl .= "t=" . $Hasil_Lab->TableVar . "&"; // Add page token
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
		global $Hasil_Lab;
		if ($Hasil_Lab->UseTokenInUrl) {
			if (ewrpt_IsHttpPost())
				return ($Hasil_Lab->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($Hasil_Lab->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function crHasil_Lab_summary() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Table object (Hasil_Lab)
		$GLOBALS["Hasil_Lab"] = new crHasil_Lab();

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EWRPT_PAGE_ID"))
			define("EWRPT_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWRPT_TABLE_NAME"))
			define("EWRPT_TABLE_NAME", 'Hasil Lab', TRUE);

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
		global $Hasil_Lab;

	// Get export parameters
	if (@$_GET["export"] <> "") {
		$Hasil_Lab->Export = $_GET["export"];
	}
	$gsExport = $Hasil_Lab->Export; // Get export parameter, used in header
	$gsExportFile = $Hasil_Lab->TableVar; // Get export file, used in header
	if ($Hasil_Lab->Export == "excel") {
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
		global $Hasil_Lab;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export to Email (use ob_file_contents for PHP)
		if ($Hasil_Lab->Export == "email") {
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
	var $DisplayGrps = 1; // Groups per page
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
		global $Hasil_Lab;
		global $rs;
		global $rsgrp;
		global $gsFormError;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 7;
		$nGrps = 2;
		$this->Val = ewrpt_InitArray($nDtls, 0);
		$this->Cnt = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = ewrpt_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = ewrpt_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandSmry = ewrpt_InitArray($nDtls, 0);
		$this->GrandMn = ewrpt_InitArray($nDtls, NULL);
		$this->GrandMx = ewrpt_InitArray($nDtls, NULL);

		// Set up if accumulation required
		$this->Col = array(FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);

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
		$Hasil_Lab->CustomFilters_Load();

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
		$sGrpSort = ewrpt_UpdateSortFields($Hasil_Lab->SqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewrpt_BuildReportSql($Hasil_Lab->SqlSelectGroup(), $Hasil_Lab->SqlWhere(), $Hasil_Lab->SqlGroupBy(), $Hasil_Lab->SqlHaving(), $Hasil_Lab->SqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowFirstHeader = ($this->TotalGrps > 0);

		//$this->ShowFirstHeader = TRUE; // Uncomment to always show header
		// Set up start position if not export all

		if ($Hasil_Lab->ExportAll && $Hasil_Lab->Export <> "")
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
		global $Hasil_Lab;
		switch ($lvl) {
			case 1:
				return (is_null($Hasil_Lab->No_Kunjungan->CurrentValue) && !is_null($Hasil_Lab->No_Kunjungan->OldValue)) ||
					(!is_null($Hasil_Lab->No_Kunjungan->CurrentValue) && is_null($Hasil_Lab->No_Kunjungan->OldValue)) ||
					($Hasil_Lab->No_Kunjungan->GroupValue() <> $Hasil_Lab->No_Kunjungan->GroupOldValue());
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
		global $Hasil_Lab;
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group rs
	function GetGrpRs($sql, $start, $grps) {
		global $conn;
		global $Hasil_Lab;
		$wrksql = $sql;
		if ($start > 0 && $grps > -1)
			$wrksql .= " LIMIT " . ($start-1) . ", " . ($grps);
		$rswrk = $conn->Execute($wrksql);
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		global $Hasil_Lab;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$Hasil_Lab->No_Kunjungan->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$Hasil_Lab->No_Kunjungan->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$Hasil_Lab->No_Kunjungan->setDbValue("");
		}
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		global $Hasil_Lab;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$Hasil_Lab->TANGGAL->setDbValue($rs->fields('TANGGAL'));
			$Hasil_Lab->RM_Px2E->setDbValue($rs->fields('RM Px.'));
			$Hasil_Lab->NAMA_PASIEN->setDbValue($rs->fields('NAMA PASIEN'));
			$Hasil_Lab->KELOMPOK->setDbValue($rs->fields('KELOMPOK'));
			$Hasil_Lab->PEMERIKSAAN->setDbValue($rs->fields('PEMERIKSAAN'));
			$Hasil_Lab->METODE->setDbValue($rs->fields('METODE'));
			$Hasil_Lab->NILAI_NORMAL->setDbValue($rs->fields('NILAI NORMAL'));
			$Hasil_Lab->HASIL->setDbValue($rs->fields('HASIL'));
			if ($opt <> 1) {
				if (is_array($Hasil_Lab->No_Kunjungan->GroupDbValues))
					$Hasil_Lab->No_Kunjungan->setDbValue(@$Hasil_Lab->No_Kunjungan->GroupDbValues[$rs->fields('No Kunjungan')]);
				else
					$Hasil_Lab->No_Kunjungan->setDbValue(ewrpt_GroupValue($Hasil_Lab->No_Kunjungan, $rs->fields('No Kunjungan')));
			}
			$this->Val[1] = $Hasil_Lab->TANGGAL->CurrentValue;
			$this->Val[2] = $Hasil_Lab->RM_Px2E->CurrentValue;
			$this->Val[3] = $Hasil_Lab->NAMA_PASIEN->CurrentValue;
			$this->Val[4] = $Hasil_Lab->PEMERIKSAAN->CurrentValue;
			$this->Val[5] = $Hasil_Lab->NILAI_NORMAL->CurrentValue;
			$this->Val[6] = $Hasil_Lab->HASIL->CurrentValue;
		} else {
			$Hasil_Lab->TANGGAL->setDbValue("");
			$Hasil_Lab->RM_Px2E->setDbValue("");
			$Hasil_Lab->NAMA_PASIEN->setDbValue("");
			$Hasil_Lab->KELOMPOK->setDbValue("");
			$Hasil_Lab->PEMERIKSAAN->setDbValue("");
			$Hasil_Lab->METODE->setDbValue("");
			$Hasil_Lab->NILAI_NORMAL->setDbValue("");
			$Hasil_Lab->HASIL->setDbValue("");
			$Hasil_Lab->No_Kunjungan->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {
		global $Hasil_Lab;

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWRPT_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWRPT_TABLE_START_GROUP];
			$Hasil_Lab->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$Hasil_Lab->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $Hasil_Lab->getStartGroup();
			}
		} else {
			$this->StartGrp = $Hasil_Lab->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$Hasil_Lab->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$Hasil_Lab->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$Hasil_Lab->setStartGroup($this->StartGrp);
		}
	}

	// Set up popup
	function SetupPopup() {
		global $conn, $ReportLanguage;
		global $Hasil_Lab;

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
		global $Hasil_Lab;
		$this->StartGrp = 1;
		$Hasil_Lab->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		global $Hasil_Lab;
		$sWrk = @$_GET[EWRPT_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 1; // Non-numeric, load default
				}
			}
			$Hasil_Lab->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$Hasil_Lab->setStartGroup($this->StartGrp);
		} else {
			if ($Hasil_Lab->getGroupPerPage() <> "") {
				$this->DisplayGrps = $Hasil_Lab->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 1; // Load default
			}
		}
	}

	function RenderRow() {
		global $conn, $rs, $Security;
		global $Hasil_Lab;
		if ($Hasil_Lab->RowTotalType == EWRPT_ROWTOTAL_GRAND) { // Grand total

			// Get total count from sql directly
			$sSql = ewrpt_BuildReportSql($Hasil_Lab->SqlSelectCount(), $Hasil_Lab->SqlWhere(), $Hasil_Lab->SqlGroupBy(), $Hasil_Lab->SqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
			} else {
				$this->TotCount = 0;
			}
		}

		// Call Row_Rendering event
		$Hasil_Lab->Row_Rendering();

		//
		// Render view codes
		//

		if ($Hasil_Lab->RowType == EWRPT_ROWTYPE_TOTAL) { // Summary row

			// No Kunjungan
			$Hasil_Lab->No_Kunjungan->GroupViewValue = $Hasil_Lab->No_Kunjungan->GroupOldValue();
			$Hasil_Lab->No_Kunjungan->CellAttrs["class"] = ($Hasil_Lab->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$Hasil_Lab->No_Kunjungan->GroupViewValue = ewrpt_DisplayGroupValue($Hasil_Lab->No_Kunjungan, $Hasil_Lab->No_Kunjungan->GroupViewValue);

			// TANGGAL
			$Hasil_Lab->TANGGAL->ViewValue = $Hasil_Lab->TANGGAL->Summary;
			$Hasil_Lab->TANGGAL->ViewValue = ewrpt_FormatDateTime($Hasil_Lab->TANGGAL->ViewValue, 7);

			// RM Px.
			$Hasil_Lab->RM_Px2E->ViewValue = $Hasil_Lab->RM_Px2E->Summary;

			// NAMA PASIEN
			$Hasil_Lab->NAMA_PASIEN->ViewValue = $Hasil_Lab->NAMA_PASIEN->Summary;

			// PEMERIKSAAN
			$Hasil_Lab->PEMERIKSAAN->ViewValue = $Hasil_Lab->PEMERIKSAAN->Summary;

			// NILAI NORMAL
			$Hasil_Lab->NILAI_NORMAL->ViewValue = $Hasil_Lab->NILAI_NORMAL->Summary;

			// HASIL
			$Hasil_Lab->HASIL->ViewValue = $Hasil_Lab->HASIL->Summary;
		} else {

			// No Kunjungan
			$Hasil_Lab->No_Kunjungan->GroupViewValue = $Hasil_Lab->No_Kunjungan->GroupValue();
			$Hasil_Lab->No_Kunjungan->CellAttrs["class"] = "ewRptGrpField1";
			$Hasil_Lab->No_Kunjungan->GroupViewValue = ewrpt_DisplayGroupValue($Hasil_Lab->No_Kunjungan, $Hasil_Lab->No_Kunjungan->GroupViewValue);
			if ($Hasil_Lab->No_Kunjungan->GroupValue() == $Hasil_Lab->No_Kunjungan->GroupOldValue() && !$this->ChkLvlBreak(1))
				$Hasil_Lab->No_Kunjungan->GroupViewValue = "&nbsp;";

			// TANGGAL
			$Hasil_Lab->TANGGAL->ViewValue = $Hasil_Lab->TANGGAL->CurrentValue;
			$Hasil_Lab->TANGGAL->ViewValue = ewrpt_FormatDateTime($Hasil_Lab->TANGGAL->ViewValue, 7);
			$Hasil_Lab->TANGGAL->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// RM Px.
			$Hasil_Lab->RM_Px2E->ViewValue = $Hasil_Lab->RM_Px2E->CurrentValue;
			$Hasil_Lab->RM_Px2E->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// NAMA PASIEN
			$Hasil_Lab->NAMA_PASIEN->ViewValue = $Hasil_Lab->NAMA_PASIEN->CurrentValue;
			$Hasil_Lab->NAMA_PASIEN->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// PEMERIKSAAN
			$Hasil_Lab->PEMERIKSAAN->ViewValue = $Hasil_Lab->PEMERIKSAAN->CurrentValue;
			$Hasil_Lab->PEMERIKSAAN->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// NILAI NORMAL
			$Hasil_Lab->NILAI_NORMAL->ViewValue = $Hasil_Lab->NILAI_NORMAL->CurrentValue;
			$Hasil_Lab->NILAI_NORMAL->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// HASIL
			$Hasil_Lab->HASIL->ViewValue = $Hasil_Lab->HASIL->CurrentValue;
			$Hasil_Lab->HASIL->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
		}

		// No Kunjungan
		$Hasil_Lab->No_Kunjungan->HrefValue = "";

		// TANGGAL
		$Hasil_Lab->TANGGAL->HrefValue = "";

		// RM Px.
		$Hasil_Lab->RM_Px2E->HrefValue = "";

		// NAMA PASIEN
		$Hasil_Lab->NAMA_PASIEN->HrefValue = "";

		// PEMERIKSAAN
		$Hasil_Lab->PEMERIKSAAN->HrefValue = "";

		// NILAI NORMAL
		$Hasil_Lab->NILAI_NORMAL->HrefValue = "";

		// HASIL
		$Hasil_Lab->HASIL->HrefValue = "";

		// Call Row_Rendered event
		$Hasil_Lab->Row_Rendered();
	}

	// Get extended filter values
	function GetExtendedFilterValues() {
		global $Hasil_Lab;
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $Hasil_Lab;
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
			// Field RM Px.

			$this->SetSessionFilterValues($Hasil_Lab->RM_Px2E->SearchValue, $Hasil_Lab->RM_Px2E->SearchOperator, $Hasil_Lab->RM_Px2E->SearchCondition, $Hasil_Lab->RM_Px2E->SearchValue2, $Hasil_Lab->RM_Px2E->SearchOperator2, 'RM_Px2E');
			$bSetupFilter = TRUE;
		} else {

			// Field RM Px.
			if ($this->GetFilterValues($Hasil_Lab->RM_Px2E)) {
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

			// Field RM Px.
			$this->GetSessionFilterValues($Hasil_Lab->RM_Px2E);
		}

		// Call page filter validated event
		$Hasil_Lab->Page_FilterValidated();

		// Build SQL
		// Field RM Px.

		$this->BuildExtendedFilter($Hasil_Lab->RM_Px2E, $sFilter);

		// Save parms to session
		// Field RM Px.

		$this->SetSessionFilterValues($Hasil_Lab->RM_Px2E->SearchValue, $Hasil_Lab->RM_Px2E->SearchOperator, $Hasil_Lab->RM_Px2E->SearchCondition, $Hasil_Lab->RM_Px2E->SearchValue2, $Hasil_Lab->RM_Px2E->SearchOperator2, 'RM_Px2E');

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
		$this->GetSessionValue($fld->DropDownValue, 'sv_Hasil_Lab_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv1_Hasil_Lab_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so1_Hasil_Lab_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_Hasil_Lab_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_Hasil_Lab_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_Hasil_Lab_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (isset($_SESSION[$sn]))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $parm) {
		$_SESSION['sv_Hasil_Lab_' . $parm] = $sv;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv1_Hasil_Lab_' . $parm] = $sv1;
		$_SESSION['so1_Hasil_Lab_' . $parm] = $so1;
		$_SESSION['sc_Hasil_Lab_' . $parm] = $sc;
		$_SESSION['sv2_Hasil_Lab_' . $parm] = $sv2;
		$_SESSION['so2_Hasil_Lab_' . $parm] = $so2;
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
		global $ReportLanguage, $gsFormError, $Hasil_Lab;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWRPT_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ewrpt_CheckInteger($Hasil_Lab->RM_Px2E->SearchValue)) {
			if ($gsFormError <> "") $gsFormError .= "<br />";
			$gsFormError .= $Hasil_Lab->RM_Px2E->FldErrMsg();
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
		$_SESSION["sel_Hasil_Lab_$parm"] = "";
		$_SESSION["rf_Hasil_Lab_$parm"] = "";
		$_SESSION["rt_Hasil_Lab_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		global $Hasil_Lab;
		$fld =& $Hasil_Lab->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_Hasil_Lab_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_Hasil_Lab_$parm"];
		$fld->RangeTo = @$_SESSION["rt_Hasil_Lab_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		global $Hasil_Lab;

		/**
		* Set up default values for non Text filters
		*/

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

		// Field RM Px.
		$this->SetDefaultExtFilter($Hasil_Lab->RM_Px2E, "=", NULL, 'AND', "=", NULL);
		$this->ApplyDefaultExtFilter($Hasil_Lab->RM_Px2E);

		/**
		* Set up default values for popup filters
		* NOTE: if extended filter is enabled, use default values in extended filter instead
		*/
	}

	// Check if filter applied
	function CheckFilter() {
		global $Hasil_Lab;

		// Check RM Px. text filter
		if ($this->TextFilterApplied($Hasil_Lab->RM_Px2E))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList() {
		global $Hasil_Lab;
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field RM Px.
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($Hasil_Lab->RM_Px2E, $sExtWrk);
		if ($sExtWrk <> "" || $sWrk <> "")
			$sFilterList .= $Hasil_Lab->RM_Px2E->FldCaption() . "<br />";
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
		global $Hasil_Lab;
		$sWrk = "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWRPT_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		global $Hasil_Lab;

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$Hasil_Lab->setOrderBy("");
				$Hasil_Lab->setStartGroup(1);
				$Hasil_Lab->No_Kunjungan->setSort("");
				$Hasil_Lab->TANGGAL->setSort("");
				$Hasil_Lab->RM_Px2E->setSort("");
				$Hasil_Lab->NAMA_PASIEN->setSort("");
				$Hasil_Lab->PEMERIKSAAN->setSort("");
				$Hasil_Lab->NILAI_NORMAL->setSort("");
				$Hasil_Lab->HASIL->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$Hasil_Lab->CurrentOrder = ewrpt_StripSlashes(@$_GET["order"]);
			$Hasil_Lab->CurrentOrderType = @$_GET["ordertype"];
			$Hasil_Lab->UpdateSort($Hasil_Lab->No_Kunjungan); // No Kunjungan
			$Hasil_Lab->UpdateSort($Hasil_Lab->TANGGAL); // TANGGAL
			$Hasil_Lab->UpdateSort($Hasil_Lab->RM_Px2E); // RM Px.
			$Hasil_Lab->UpdateSort($Hasil_Lab->NAMA_PASIEN); // NAMA PASIEN
			$Hasil_Lab->UpdateSort($Hasil_Lab->PEMERIKSAAN); // PEMERIKSAAN
			$Hasil_Lab->UpdateSort($Hasil_Lab->NILAI_NORMAL); // NILAI NORMAL
			$Hasil_Lab->UpdateSort($Hasil_Lab->HASIL); // HASIL
			$sSortSql = $Hasil_Lab->SortSql();
			$Hasil_Lab->setOrderBy($sSortSql);
			$Hasil_Lab->setStartGroup(1);
		}
		return $Hasil_Lab->getOrderBy();
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
