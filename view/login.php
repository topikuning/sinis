<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" href="images/medic.png">
    <title>RSUD Dr. Soegiri :: Login Page</title>
    <link rel="stylesheet" type="text/css" href="themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" type="text/css" href="style/icon.css">
    <script type="text/javascript" src="js/function.js"></script>
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="js/jquery.easyui.min.js"></script>
    <script>
        function doLogin(){
            if(frmLogin.loginUser.value==""){
                $.messager.show({
                    title:'Login Page',
                    msg:'Kolom Username harus diisi!',
                    showType:'show'
                });
                frmLogin.loginUser.focus();
            } else if(frmLogin.loginPassword.value==""){
                $.messager.show({
                    title:'Login Page',
                    msg:'Kolom password harus diisi!',
                    showType:'show'
                });
                frmLogin.loginPassword.focus();
            } else {
                frmLogin.submit();
            }
        }
    </script>
</head>
<body onload="frmLogin.loginUser.focus()">
    <script>
        if(getURL('act')=='failed'){
            $.messager.show({
                title:'Login Page',
                msg:'Login Gagal! Silahkan ulangi lagi.',
                showType:'show'
            });            
        }
    </script>
    <div id="w" class="easyui-window" title="Login Form" draggable="false" resizable="false" closable="false" collapsible="false" minimizable="false" maximizable="false" modal="true" style="width:470px;height:250px;background: #fafafa;">
        <div class="easyui-layout" fit="true">
            <div region="center" border="false" style="background:#99FF99;">
                <table class='data'>
                    <tr>
                        <td valign='top' width='35%'>
                            <center>
                                <img src='images/logo.png' width='90%' height='90%'><br><br>
                                <span><strong>Selamat Datang</strong><br>di RSUD Dr.Soegiri<br><br>
                                    Copyright</b> &copy <?echo date('Y');?></span>
                            </center>
                        </td>
                        <td valign='top'>
                            <font style="font-family: verdana; font-size: 24pt; color: #63CA93;">USER LOGIN</font>
                            <hr width="70%" style="border-color: #30A667;" align="left">
                            <form name="frmLogin" id="frmLogin" method='POST' action="login_exec.php">
                                <table>
                                    <tr>
                                        <td width="50%">Username:</td>
                                        <td>
                                            <input name="loginUser" class="easyui-validatebox" required="true" onkeydown="
                                                if (event.keyCode == 13){
                                                    doLogin();
                                                }
                                            ">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Password:</td>
                                        <td>
                                            <input name="loginPassword" type="password" class="easyui-validatebox" required="true" onkeydown="
                                                if (event.keyCode == 13){
                                                    doLogin();
                                                }
                                            ">
                                        </td>
                                    </tr>
<!--                                    <tr valign='top'>
                                        <td>Jenis:</td>
                                        <td>
                                            <input name="jenis" type="radio" value="pegawai" checked="checked" onkeydown="
                                                if (event.keyCode == 13){
                                                    doLogin();
                                                }
                                            "/> Pegawai<br>
                                            <input name="jenis" type="radio" value="perawat" onkeydown="
                                                if (event.keyCode == 13){
                                                    doLogin();
                                                }
                                            "/> Perawat<br>
                                            <input name="jenis" type="radio" value="dokter" onkeydown="
                                                if (event.keyCode == 13){
                                                    doLogin();
                                                }
                                            "/> Dokter
                                        </td>
                                    </tr>-->
                                </table>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <div region="south" border="false" style="background-color: #99FF99;text-align:right;height:30px;line-height:30px;">
                <a class="easyui-linkbutton" iconCls="icon-login" href="javascript:void(0)" onclick="doLogin()" plain="true">Login</a>
            </div>
        </div>
    </div>
</body>
</html>