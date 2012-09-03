/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function getURL( name ) {
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null )
        return "";
    else
        return results[1];
}

function IsNumeric(obj) {
    var strValidChars = "0123456789.";
    var strChar;
    var strString = obj.value;

    if (strString.length == 0){
        obj.value="";
        return false;
    } else {
        if (parseInt(strString) >= 0 ){
            for (i = 0; i < strString.length; i++) {
                strChar = strString.charAt(i);
                if (strValidChars.indexOf(strChar) == -1) {
                    obj.value="";
                    return false;
                }
            }
        }else{
            obj.value="";
            return false;
        }	  
    } 
}

function checkSelect( o, n ) {
    if ( o.length == 0 ) {
        $.messager.show({
            title:'Pendaftaran',
            msg: n + ' Belum dipilih.',
            showType:'show'
        });
        return false;
    } else {
        return true;
    }
}

function formatCurrency(num) {
    if(num!=""){
        num = num.toString().replace(/\$|\,/g,'');
        if(isNaN(num))
            num = "0";
        sign = (num == (num = Math.abs(num)));
        num = Math.floor(num*100+0.50000000001);
        cents = num%100;
        num = Math.floor(num/100).toString();
        if(cents<10)
            cents = "0" + cents;
        for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
            num = num.substring(0,num.length-(4*i+3))+'.'+
            num.substring(num.length-(4*i+3));
        return (((sign)?'':'-') + 'Rp. ' + num);
    } else {
        return 'Rp. 0';
    }
}

function codeDate(value){
    var returnData = value.split("-");
    return returnData[2] + '/' + returnData[1] + '/' + returnData[0];
}

function getTglLahir(value){
    var tanggal = new Date();
    
    var tahun = tanggal.getFullYear() - value;
    var bulan = tanggal.getMonth()+1;
    var tgl = tanggal.getDate();
    
    if(bulan<10) bulan = '0' + bulan;
    if(tgl<10) tgl = '0' + tgl;
    
    return tgl + '-' + bulan + '-' + tahun;
}

function getToday(){
    var tanggal = new Date();
    
    var tahun = tanggal.getFullYear();
    var bulan = tanggal.getMonth()+1;
    var tgl = tanggal.getDate();
    
    if(bulan<10) bulan = '0' + bulan;
    if(tgl<10) tgl = '0' + tgl;
    
    return tgl + '-' + bulan + '-' + tahun;
}

function simpanClosePerawatan(){
    
    var rows = $('#dataPendaftaran').datagrid('getSelections');
    var fid = rows[0].id_pendaftaran;
    
    var dataString = "task=simpanClosePerawatan&id_pendaftaran=" + fid +
    "&kondisi_keluar=" + $("#kondisiKeluar").val() + 
    "&cara_keluar=" + $("#caraKeluar").val() + 
    "&keterangan_keluar=" + $("#keteranganKeluar").val() +
    "&tgl_out=" + $("#tglKeluar").datebox("getValue");

    $('#simpan').linkbutton({
        disabled:true
    });

    $.ajax({  
        type: "GET",  
        url: "json/data.php",  
        data: dataString,
        success: function(data) {
            if(data=='1'){
                $.messager.alert('Perawatan',
                    'Close Perawatan Berhasil. Generate Jasa Berhasil.',
                    'alert'
                    );
                $('#winClosePerawatan').window('close');
                $('#dataPendaftaran').datagrid('reload');
            } else if(data=='2'){
                $.messager.alert('Rawat Inap',
                    'Close Rawat Inap Berhasil. Generate Jasa Berhasil. Pasien dinyatakan keluar rumah sakit.',
                    'alert'
                    );
                $('#winClosePerawatan').window('close');
                $('#dataPendaftaran').datagrid('reload');
            } else {
                $.messager.show({
                    title:'Perawatan',
                    msg:'Close Perawatan gagal. ' + data,
                    showType:'show'
                });
                return false;
            }
        }  
    });  
    return false;
}
    
function goToRM(){
    var rows = $('#dataPendaftaran').datagrid('getSelections');
    var fid = rows[0].id_pendaftaran;
    var rmx = rows[0].id_pasien;
        
    window.open ("?page=rmpx&fid=" + fid + "&pid=" + rmx);
}
    
function goToSurvey(){
    var rows = $('#dataPendaftaran').datagrid('getSelections');
    var fid = rows[0].id_pendaftaran;
    var rmx = rows[0].id_pasien;
    
    if(fid > 0)
        window.open ("?page=igdsrv&fid=" + fid + "&pid=" + rmx);
    else
        alert('PILIH PASIEN DI ANTRIAN DULU')
}
	
function simpanClosePerawatanMedis(){
    var rows = $('#dataPendaftaran').datagrid('getSelections');
    var fid = rows[0].id_pendaftaran;
    var dataString = "task=simpanClosePerawatanMedis&id_pendaftaran=" + fid +
    "&kondisi_keluar=" + $("#kondisiKeluar").val() + 
    "&cara_keluar=" + $("#caraKeluar").val() + 
    "&keterangan_keluar=" + $("#keteranganKeluar").val() +
    "&tgl_out=" + $("#tglKeluar").datebox("getValue");
    
    $('#simpan').linkbutton({
        disabled:true
    });
    
    $.ajax({  
        type: "GET",  
        url: "json/data.php",  
        data: dataString,
        success: function(data) {
            if(data=='1'){
                $.messager.alert('Perawatan',
                    'Close Perawatan Berhasil. Generate Jasa Berhasil.',
                    'alert'
                    );
                $('#winClosePerawatan').window('close');
                $('#dataPendaftaran').datagrid('reload');
            } else if(data=='2'){
                $.messager.alert('Rawat Inap',
                    'Close Rawat Inap Berhasil. Generate Jasa Berhasil. Pasien dinyatakan keluar rumah sakit.',
                    'alert'
                    );
                $('#winClosePerawatan').window('close');
                $('#dataPendaftaran').datagrid('reload');
            } else {
                $.messager.show({
                    title:'Perawatan',
                    msg:'Close Perawatan gagal. ' + data,
                    showType:'show'
                });
                return false;
            }
        }  
    });  
    return false;
}
	
function openClosePerawatan(){
    $('#winClosePerawatan').window('open');
    $('#simpan').linkbutton({
        disabled:false
    });
    kondisiKeluar.focus();
}
	
function openWinSearchPasien(){
    $("#winSearchPasien").window('open');
    srcNamaPasien.focus();
}
    
function closeWinSearchPasien(){
    $("#winSearchPasien").window('close');
    id_pasien.focus();
}

function loadDataListPasien(){
    $("#dataListPasien").datagrid({
        url:'json/data.php?task=getDataListPasien&nama_pasien=' + $('#srcNamaPasien').val() + 
        '&alamat=' + $('#srcAlamat').val() +
        '&tgl_lahir=' + $('#srcTglLahir').datebox("getValue") +
        '&tgl_lahir_to=' + $('#srcTglLahirTo').datebox("getValue") +
        '&kecamatan=' + $('#srcKecamatan').val() +
        '&kelurahan=' + $('#srcKelurahan').val() +
        '&asuransi=' + $('#srcAsuransi').val() +
        '&tipe_pasien=' + $('#srcTipePasien').val()
    })
}

function cetakAja(){
    var DocumentContainer = document.getElementById('detailLaporan');
    var WindowObject = window.open('', "TrackHistoryData",
        "width=740,height=325,top=200,left=250,toolbars=no,scrollbars=yes,status=no,resizable=no");
    WindowObject.document.writeln('<html><head>');
    WindowObject.document.writeln("<link rel='stylesheet' type='text/css' href='style/style.css'/>");
    WindowObject.document.writeln('</head>');
    WindowObject.document.writeln("<body style='font-family:sans-serif; font-size:12px;'>");
    WindowObject.document.writeln(DocumentContainer.innerHTML);
    WindowObject.document.writeln('</body></html>');
    WindowObject.document.close();
    WindowObject.focus();
    WindowObject.print();
}

function toExcel(){
    var content = $("#detailLaporan").html();
    var nama = "laporan";
    uriContent = "data:application/vnd.ms-excel;filename=tada.txt;content-disposition=attachment," +encodeURIComponent(content);
    location.href = uriContent
}

function exExcel(){
    var content = $("#detailTagihan").html();
    var nama = "laporan";
    uriContent = "data:application/vnd.ms-excel;filename=tada.txt;content-disposition=attachment," +encodeURIComponent(content);
    location.href = uriContent
}

function cetakTagihan(){
    var DocumentContainer = document.getElementById('detailTagihan');
    var WindowObject = window.open('', "TrackHistoryData",
        "width=740,height=325,top=200,left=250,toolbars=no,scrollbars=yes,status=no,resizable=no");
    WindowObject.document.writeln('<html><head>');
    WindowObject.document.writeln("<link rel='stylesheet' type='text/css' href='style/style.css'/>");
    WindowObject.document.writeln('</head>');
    WindowObject.document.writeln("<body style='font-family:sans-serif; font-size:12px;'>");
    WindowObject.document.writeln(DocumentContainer.innerHTML);
    WindowObject.document.writeln('</body></html>');
    WindowObject.document.close();
    WindowObject.focus();
    WindowObject.print();
}

//DHTML
function onChangeFunc() {
    setTarif(dTindakan.getSelectedValue());
    dTindakan.DOMelem_input.focus();
    $('#tindakanId').val(dTindakan.getSelectedValue());
}
function onChangeFuncF() {
    setTarif(dFasilitas.getSelectedValue());
    dFasilitas.DOMelem_input.focus();
    $('#tindakanFId').val(dFasilitas.getSelectedValue());
}
	
function onKeyPressedFunc(key) {
    if(key == 13){
        dokter.DOMelem_input.select()
    }
}
    
function changeVisite() {
    dokterV.DOMelem_input.focus();
    $.getJSON("json/data.php", {
        task: 'getBiayaVisit',
        id_dokter: dokterV.getSelectedValue(),
        id_pendaftaran: $("#idp").val()
    },
    function(data) {
        if(data.length>0){
            var value = data[0].dataValue;
            $("#tarifVisite").attr("value", value);
        }
    });
}
    
function changePeriksa() {
    dokterV.DOMelem_input.focus();
    $("#tarifPemeriksaane").attr("value", 30000);
}
        
function onChangeDiagP() {
    dPrim.DOMelem_input.focus();
    sekunder.readonly(false);
    $('#penyakitPrimerId').val(dPrim.getSelectedValue());
}
        
function onChangeDiagS() {
    sekunder.DOMelem_input.focus();
    $('#penyakitSekunderId').val(sekunder.getSelectedValue());
}
    
function onKeyPressedFuncF(key) {
    if (key == 13){
        dokterF.DOMelem_input.select()
    }
}
    
function keyDokter(key) {
    if (key == 13){
        tglInput.focus();
    }
}
    
function keyDokterF(key) {
    if(key == 13){
        jumlah.focus();
    }
}
    
function keyDokterD(key) {
    if(key == 13){
        dPrim.DOMelem_input.select()
    }
}
    
function keyDokterV(key) {
    if(key == 123){
        simpanVisitDokter();
    } else if (key == 13){
        tglVisite.focus();
    }
}
    
function keyDiagP(key) {
    if (key == 13){
        sekunder.DOMelem_input.select();
    }
}
    
function keyDiagS(key) {
    if (key == 13){
        saveDiagnosa();
    }
}

//END DHTML

function openWinLayanan(){
    $('#winLayanan').window('open');
    $('#id_tindakan_ruang').val('');
    $('#tindakanId').val('');
    $('#operator').val('');
    $('#id_tarif').val('');
    $('#tarif').val('');
    $('#visit').val('');
    $('#id_fasilitas_ruang').val('');
    $('#tindakanFId').val('');
    $('#id_tarifF').val('');
}

function closeWinLayanan(){
    $('#winLayanan').window('close');
}
