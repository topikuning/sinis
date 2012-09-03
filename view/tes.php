<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> <?php echo $title ?> </title>
    <LINK REL="SHORTCUT ICON" HREF="images/medic.png" />
    <link rel="stylesheet" type="text/css" href="themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="style/style.css" />
    <link rel="stylesheet" type="text/css" href="style/icon.css" />
    <script type="text/javascript" src="js/function.js"></script>
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script src="js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="js/jquery.inputmask.js"></script>
    <script type="text/javascript" src="js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="js/jquery.ausu-autosuggest.js"></script>
    <script type="text/javascript" src="js/datagrid-groupview.js"></script>
    <script type="text/javascript" src="js/datagrid-detailview.js"></script>
    <!-- UNTUK DHTMLX -->
    <script>
        window.dhx_globalImgPath = "js/codebase/imgs/";
    </script>
    <script  src="js/codebase/dhtmlxcommon.js"></script>
    <script  src="js/codebase/dhtmlxcombo.js"></script>

    <link rel="STYLESHEET" type="text/css" href="js/codebase/dhtmlxcombo.css">
    <!-- AKHIR DHTMLX -->
    <script type="text/javascript">

        function clickIE() {
            if (document.all) {
                return false;
            }
        }
        function clickNS(e) {
            if (document.layers||(document.getElementById&&!document.all)) {
                if (e.which==2||e.which==3) {
                    return false;
                }
            }
        }
        if (document.layers) {
            document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;
        } else {
            document.onmouseup=clickNS;
            document.oncontextmenu=clickIE;
        }
    </script>
    <script type="text/javascript">


        function alertTimerClickHandler ()
        {
            alertTimerId = setTimeout ("$('#spinner').window('open')", 1000 );
        }

        function showAlert ( )
        {
            clearTimeout ( alertTimerId );
        }
        
        function show ( )
        {
            alert('a');
        }
    </script>
</head>
<input type="button" name="clickMe" id="alertTimerButton" value="Click me and wait!" onclick="showAlert()"/>
<a href="javascript:void(0)" id="sb2" class="easyui-linkbutton" plain="true" menu="#mm2" iconCls="icon-logout" onclick="alertTimerClickHandler()">Logout</a>
<div id="spinner" class="easyui-window" style="background: transparent; border-color: transparent;" noheader="true" draggable="false" border="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" closed="true">
    <img src="images/loader.gif" alt="Mohon Tunggu..."/>    <b>Mohon Tunggu...</b>
</div>