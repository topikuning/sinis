    <script>

    $.fn.autosugguest({
    className:'ausu-suggest',
    methodType:'POST',
    minChars:1,
    rtnIDs:true,
    dataFile:'json/dataList.php',
    afterUpdate: setTarif
});

$(function(){
    var noDftr = getURL('fid');
    var idPasien = getURL('pid');

    $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)

    id_pasien.focus();

    $( "#dokter_operator" ).change(function(){
        var cito;
        var alatTamu1;
        if(citoTindakanMedis.checked) cito='1';
        else cito = '0';
        if(alatTamu.checked) alatTamu1='1';
        else alatTamu1 = '0';
        $.getJSON("json/data.php", {
            task: 'getTarifTindakanMedis',
            id_tindakan_medis:  $( "#tindakanMedisId" ).val(),
            id_pendaftaran: noDftr,
            dokter_operator: $( "#dokter_operator" ).val(),
            cito: cito,
            alat_tamu: alatTamu1
        },
        function(data) {
            if(data.length>0){
                var value = data[0].dataValue;
                var selisih = data[0].dataSelisih;

                $("#tarif").attr("value", value);
                $("#tarifTambah").attr("value", selisih);
            }
        });
    });

    $( "#dokter_anastesi" ).change(function(){
        var cito;
        var alatTamu1;
        if(citoTindakanMedis.checked) cito='1';
        else cito = '0';
        if(alatTamu.checked) alatTamu1='1';
        else alatTamu1 = '0';
        $.getJSON("json/data.php", {
            task: 'getTarifTindakanMedis',
            id_tindakan_medis:  $( "#tindakanMedisId" ).val(),
            id_pendaftaran: noDftr,
            dokter_operator: $( "#dokter_operator" ).val(),
            cito: cito,
            alat_tamu: alatTamu1
        },
        function(data) {
            if(data.length>0){
                var value = data[0].dataValue;
                var selisih = data[0].dataSelisih;

                $("#tarif").attr("value", value);
                $("#tarifTambah").attr("value", selisih);
            }
        });
    });

    $( "#citoTindakanMedis" ).click(function(){
        var cito;
        var alatTamu1;
        if(citoTindakanMedis.checked) cito='1';
        else cito = '0';
        if(alatTamu.checked) alatTamu1='1';
        else alatTamu1 = '0';
        $.getJSON("json/data.php", {
            task: 'getTarifTindakanMedis',
            id_tindakan_medis:  $( "#tindakanMedisId" ).val(),
            id_pendaftaran: noDftr,
            dokter_operator: $( "#dokter_operator" ).val(),
            cito: cito,
            alat_tamu: alatTamu1
        },
        function(data) {
            if(data.length>0){
                var value = data[0].dataValue;
                var selisih = data[0].dataSelisih;

                $("#tarif").attr("value", value);
                $("#tarifTambah").attr("value", selisih);
            }
        });
    });

    $( "#alatTamu" ).click(function(){
        var cito;
        var alatTamu1;
        if(citoTindakanMedis.checked) cito='1';
        else cito = '0';
        if(alatTamu.checked) alatTamu1='1';
        else alatTamu1 = '0';
        $.getJSON("json/data.php", {
            task: 'getTarifTindakanMedis',
            id_tindakan_medis:  $( "#tindakanMedisId" ).val(),
            id_pendaftaran: noDftr,
            dokter_operator: $( "#dokter_operator" ).val(),
            cito: cito,
            alat_tamu: alatTamu1
        },
        function(data) {
            if(data.length>0){
                var value = data[0].dataValue;
                var selisih = data[0].dataSelisih;

                $("#tarif").attr("value", value);
                $("#tarifTambah").attr("value", selisih);
            }
        });
    });

    $('#dataTindakan').datagrid({
        title:'Data Tindakan',
        height:250,
        singleSelect:true,
        nowrap: false,
        striped: true,
        url:'json/tindakanMedis.php?task=getTindakanRuang&no_pendaftaran=' + noDftr,
        sortName: 'id_tindakan_ruang_medis',
        sortOrder: 'desc',
        remoteSort: false,
        showFooter:true,
        collapsible:true,
        idField:'id_tindakan_ruang_medis',
        frozenColumns:[[
                {
                    title:'ID',
                    field:'id_tindakan_ruang_medis',
                    width:50
                },

                {
                    field:'tindakan',
                    title:'Tindakan',
                    fitColumns: true,
                    width:250
                }
            ]],
        columns:[[
                {
                    field:'dokter_operator',
                    title:'Dokter Operator',
                    width:150
                },

                {
                    field:'dokter_anastesi',
                    title:'Dokter Anastesi',
                    width:150
                },

                {
                    field:'advice',
                    title:'Keterangan',
                    width:200
                },

                {
                    field:'cito',
                    title:'cito',
                    width:50
                },

                {
                    field:'tarif',
                    title:'Tarif',
                    width:80,
                    align:'right'
                }
            ]],
        pagination:true,
        rownumbers:true,
        onDblClickRow:function(rowIndex){
            var rows = $('#dataTindakan').datagrid('getSelections');
            var id_tindakan_ruang_medis = rows[0].id_tindakan_ruang_medis;
            $('#frmTindakan').form('load','json/tindakanMedis.php?task=getDtlTindakan&id_tindakan_ruang_medis=' + id_tindakan_ruang_medis);
            setTimeout("setCitoTindakan()",300)
            openWinTindakan();
        },
        toolbar:[{
                id:'btndel',
                text:'Hapus',
                iconCls:'icon-remove',
                handler:function(){
                    var rows = $('#dataTindakan').datagrid('getSelections');
                    var id = "task=hapusTindakanMedis&id_tindakan=" + rows[0].id_tindakan_ruang_medis;
                    $.ajax({
                        type: "POST",
                        url: "json/tindakanMedisPost.php",
                        data: id,
                        success: function(data) {
                            if(data=='1'){
                                $.messager.show({
                                    title:'Tindakan',
                                    msg:'Penghapusan Tindakan Nomor ' + rows[0].id_tindakan_ruang_medis + ' berhasil.',
                                    showType:'show'
                                });
                                $('#dataTindakan').datagrid('reload');
                            } else if (data=='2'){
                                $.messager.show({
                                    title:'Tindakan',
                                    msg:'Penghapusan Gagal. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                                    showType:'show'
                                });
                                return false;
                            } else if (data=='0') {
                                $.messager.show({
                                    title:'Diagnosa',
                                    msg:'Penghapusan Tindakan no ' + rows[0].id_tindakan_ruang_medis + ' gagal.',
                                    showType:'show'
                                });
                            }
                        }
                    })
                }
            }]
    });

    $('#dataFasilitas').datagrid({
        title:'Data Fasilitas',
        height:250,
        singleSelect:true,
        nowrap: false,
        striped: true,
        url:'json/tindakan.php?task=getFasilitasRuang&no_pendaftaran=' + noDftr,
        sortName: 'id_fasilitas_ruang',
        sortOrder: 'desc',
        remoteSort: false,
        showFooter:true,
        collapsible:true,
        idField:'id_fasilitas_ruang',
        frozenColumns:[[
                {
                    title:'ID',
                    field:'id_fasilitas_ruang',
                    width:50
                },

                {
                    field:'tindakan',
                    title:'Fasilitas',
                    width:250
                }
            ]],
        columns:[[
                {
                    field:'jumlah',
                    title:'Jumlah',
                    width:50
                },

                {
                    field:'dokter',
                    title:'Pelaksana',
                    width:150
                },

                {
                    field:'operator',
                    title:'Operator',
                    width:150
                },

                {
                    field:'tarif',
                    title:'Tarif',
                    width:80,
                    align:'right'
                }
            ]],
        pagination:true,
        rownumbers:true,
        onDblClickRow:function(rowIndex){
            var rows = $('#dataFasilitas').datagrid('getSelections');
            var id_fasilitas_ruang = rows[0].id_fasilitas_ruang;
            $('#frmFasilitas').form('load','json/tindakan.php?task=getDtlFasilitas&id_fasilitas_ruang=' + id_fasilitas_ruang);
            openWinFasilitas();
        },
        toolbar:[{
                id:'btndelfasilitas',
                text:'Hapus',
                iconCls:'icon-remove',
                handler:function(){
                    var rows = $('#dataFasilitas').datagrid('getSelections');
                    var id = "task=hapusFasilitas&id_fasilitas=" + rows[0].id_fasilitas_ruang;
                    $.ajax({
                        type: "POST",
                        url: "json/tindakanPost.php",
                        data: id,
                        success: function(data) {
                            if(data=='1'){
                                $.messager.show({
                                    title:'Tindakan',
                                    msg:'Penghapusan Fasilitas Nomor ' + rows[0].id_fasilitas_ruang + ' berhasil.',
                                    showType:'show'
                                });
                                var row = $('#dataFasilitas').datagrid('getSelected');
                                var index = $('#dataFasilitas').datagrid('getRowIndex', row);
                                $('#dataFasilitas').datagrid('deleteRow', index);
                                $('#dataFasilitas').datagrid('reload');
                            } else if (data=='2'){
                                $.messager.show({
                                    title:'Fasilitas',
                                    msg:'Penghapusan. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                                    showType:'show'
                                });
                                return false;
                            } else if (data=='0') {
                                $.messager.show({
                                    title:'Diagnosa',
                                    msg:'Penghapusan Fasilitas no ' + rows[0].id_fasilitas_ruang + ' gagal.',
                                    showType:'show'
                                });
                            }
                        }
                    })
                }
            }]
    });

    $('#dataBahan').datagrid({
        title:'Data Bahan',
        height:250,
        singleSelect:true,
        nowrap: false,
        striped: true,
        url:'json/tindakan.php?task=getBarangTindakan&no_pendaftaran=' + noDftr,
        sortName: 'id_barang_tindakan',
        sortOrder: 'asc',
        remoteSort: false,
        collapsible:true,
        collapsed:true,			
        idField:'id_barang_tindakan',
        frozenColumns:[[
                {
                    title:'ID',
                    field:'id_barang_tindakan',
                    width:50
                },
            ]],
        columns:[[
                {
                    field:'barang',
                    title:'Nama Barang',
                    width:250
                },

                {
                    field:'stock',
                    title:'Sisa Stock',
                    width:50
                },

                {
                    field:'jumlah',
                    title:'Jumlah',
                    width:150
                },

                {
                    field:'satuan',
                    title:'Satuan',
                    width:150
                }
            ]],
        pagination:true,
        rownumbers:true,
        onDblClickRow:function(rowIndex){
            var rows = $('#dataBahan').datagrid('getSelections');
            var id_barang_tindakan = rows[0].id_barang_tindakan;
            $('#inBahan').form('load','json/tindakan.php?task=getDtlBahan&id_barang_tindakan=' + id_barang_tindakan);
        },
        toolbar:[{
                id:'btnbhn',
                text:'Hapus',
                iconCls:'icon-remove',
                handler:function(){
                    var rows = $('#dataBahan').datagrid('getSelections');
                    var id = "task=hapusBahan&id_barang_tindakan=" + rows[0].id_barang_tindakan;
                    $.ajax({  
                        type: "POST",  
                        url: "json/tindakanPost.php",  
                        data: id,  
                        success: function(data) {  
                            if(data=='1'){
                                $.messager.show({
                                    title:'Bahan',
                                    msg:'Penghapusan Bahan Nomor ' + rows[0].id_barang_tindakan + ' berhasil.',
                                    showType:'show'
                                });
                                $('#dataBahan').datagrid('reload');
                            } else if (data=='0') {
                                $.messager.show({
                                    title:'Bahan',
                                    msg:'Penghapusan Bahan no ' + rows[0].id_barang_tindakan + ' gagal.',
                                    showType:'show'
                                });
                            }
                        }
                    })
                }
            }]
    });

    $('#dataListTindakan').datagrid({
        title:'Data List Tindakan',
        height:350,
        singleSelect:true,
        nowrap: false,
        striped: true,
        remoteSort: false,
        idField:'id_tindakan',
        columns:[[
                {
                    title:'ID',
                    field:'id_tindakan',
                    width:50
                },

                {
                    field:'tindakan',
                    title:'Tindakan',
                    width:350
                },

                {
                    field:'icd',
                    title:'ICD',
                    width:80
                }
            ]],
        pagination:true,
        rownumbers:true,
        onDblClickRow:function(rowIndex){
            var rows = $('#dataListTindakan').datagrid('getSelections');
            var tindakan = rows[0].tindakan;
            var id_tindakan = rows[0].id_tindakan;
            if($('#idTindakan').val()=='1'){
                $('#tindakanMedis').val(tindakan);
                $('#tindakanMedisId').val(id_tindakan);
                $('#tindakanMedis').focus();
            } else {
                $('#tindakanF').val(tindakan);
                $('#tindakanFId').val(id_tindakan);
                $('#tindakanF').focus();
            }

            var dataString = "task=getTarif&id_detail_tindakan=" + id_tindakan + "&no_pendaftaran=" + noDftr;

            $.ajax({
                type: "POST",
                url: "json/tindakanPost.php",
                data: dataString,
                success: function(data) {
                    var returnData = data.split(":");
                    if($('#idTindakan').val()=='1'){
                        $('#tarif').val(returnData[1]);
                        $('#id_tarif').val(returnData[0]);
                    } else {
                        $('#tarifF').val(returnData[1]);
                        $('#id_tarifF').val(returnData[0]);
                    }
                }
            });
            $('#winSearch').window('close');
        }
    });
        
    $('#tindakanMedis').change(function(){
    });
    $('#id_tindakanF').change(function(){
        var dataString = "task=getTarif&id_tindakan=" + $('#id_tindakanF').val() + "&no_pendaftaran=" + noDftr;
        $.ajax({
            type: "POST",
            url: "json/tindakanPost.php",
            data: dataString,
            success: function(data) {
                var returnData = data.split(":");
                $('#tarifF').val(returnData[1]);
                $('#id_tarifF').val(returnData[0]);
            }
        });
    });
});

function saveTindakan(){
    var cito;
    var alatTamu1;
    if(citoTindakanMedis.checked) cito='1';
    else cito = '0';
    if(alatTamu.checked) alatTamu1='1';
    else alatTamu1 = '0';
    var dataString = "task=simpanTindakan&id_pendaftaran=" + getURL('fid') +
        "&id_tindakan_ruang_medis=" +$("#id_tindakan_ruang_medis").val() +
        "&id_tindakan_medis=" +$("#tindakanMedisId").val() +
        "&dokter_operator=" + $("#dokter_operator").val() +
        "&dokter_anestesi=" + $("#dokter_anastesi").val() +
        "&cito=" + cito +
        "&tarif=" + $("#tarif").val() +
        "&tarifTambah=" + $("#tarifTambah").val() +
        "&advice=" + $("#advice").val() +
        "&alat_tamu=" + alatTamu1;

    var bvalid = true;

    bvalid = bvalid && checkSelect($("#tindakanMedisId").val(), 'Tindakan');
    bvalid = bvalid && checkSelect($("#dokter_operator").val(), 'Dokter Operator');
    bvalid = bvalid && checkSelect($("#dokter_anastesi").val(), 'Dokter Anestesi');

    if(bvalid){
        $.ajax({
            type: "POST",
            url: "json/tindakanMedisPost.php",
            data: dataString,
            success: function(data) {
                if(data=='LOGIN'){
                    alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                    window.location.reload();
                } else if(data=='1'){
                    $.messager.show({
                        title:'Tindakan',
                        msg:'Tindakan berhasil disimpan.',
                        showType:'show'
                    });
                    $('#dataTindakan').datagrid('reload');
                    $("#tindakanMedisId").val("");
                    $("#tindakanMedis").val("");
                    $("#tarif").val("");
                    $("#advice").val("");
                    $("#tarifTambah").val("");
                    citoTindakanMedis.checked = false;
                    alatTamu.checked=false;
                    tindakanMedis.focus()
                    return false;
                } else if (data=='2'){
                    $.messager.show({
                        title:'Tindakan',
                        msg:'Gagal menyimpan Tindakan. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                        showType:'show'
                    });
                    return false;
                } else {
                    $.messager.show({
                        title:'Tindakan',
                        msg:'Gagal menyimpan Tindakan. ' + data,
                        showType:'show'
                    });
                    return false;
                }
            }
        });
        return false;
    }
}

function saveFasilitas(){
    var dataString = "task=simpanFasilitas&id_pendaftaran=" + getURL('fid') +
        "&id_fasilitas_ruang=" +$("#id_fasilitas_ruang").val() +
        "&id_tindakan=" +$("#tindakanFId").val() +
        "&jumlah=" + $("#jumlah").val() +
        "&id_dokter=" + $("#dokterF").val() +
        "&advice=" + $("#adviceF").val() +
        "&id_tarif=" + $("#id_tarifF").val() +
        "&tarifF=" + $("#tarifF").val();

    var bvalid = true;

    bvalid = bvalid && checkSelect($("#tindakanFId").val(), 'Fasilitas');
    bvalid = bvalid && checkSelect($("#dokterF").val(), 'Pelaksana');
    bvalid = bvalid && checkSelect($("#jumlah").val(), 'Jumlah');

    if(bvalid){
        $.ajax({
            type: "POST",
            url: "json/tindakanPost.php",
            data: dataString,
            success: function(data) {
                if(data=='LOGIN'){
                    alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                    window.location.reload();
                } else if(data=='1'){
                    $.messager.show({
                        title:'Fasilitas',
                        msg:'Fasilitas berhasil disimpan.',
                        showType:'show'
                    });
                    $('#dataFasilitas').datagrid('reload');
                    $("#id_fasilitas_ruang").val()
                    $("#tindakanF").val("");
                    $("#tindakanFId").val("");
                    $("#jumlah").val("");
                    $("#adviceF").val("");
                    $("#id_tarifF").val("");
                    $("#tarifF").val("");
                    tindakanF.focus();
                    return false;
                } else if (data=='2'){
                    $.messager.show({
                        title:'Fasilitas',
                        msg:'Gagal menyimpan Fasilitas. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                        showType:'show'
                    });
                    return false;
                } else {
                    $.messager.show({
                        title:'Fasilitas',
                        msg:'Gagal menyimpan Fasilitas. ' + data,
                        showType:'show'
                    });
                    return false;
                }
            }
        });
        return false;
    }
}

function saveBahan(){
    var dataString = "task=simpanBahan&id_pendaftaran=" + getURL('fid') +
        "&id_barang_tindakan=" +$("#id_barang_tindakan").val() +
        "&id_barang=" +$("#bahanId").val() +
        "&jumlah=" + $("#jumlahBarang").val() +
        "&tarifBahan=" + $("#tarifBahan").val();

    var bvalid = true;

    bvalid = bvalid && checkSelect($("#bahanId").val(), 'Bahan');
    bvalid = bvalid && checkSelect($("#jumlahBarang").val(), 'Jumlah Barang');

    if(bvalid){
        $.ajax({
            type: "POST",
            url: "json/tindakanPost.php",
            data: dataString,
            success: function(data) {
                if(data=='1'){
                    $.messager.show({
                        title:'Bahan',
                        msg:'Bahan berhasil disimpan.',
                        showType:'show'
                    });
                    $('#dataBahan').datagrid('reload');
                    frmBahan.reset();
                    return false;
                } else {
                    $.messager.show({
                        title:'Bahan',
                        msg:'Gagal menyimpan Bahan. ' + data,
                        showType:'show'
                    });
                    return false;
                }
            }
        });
        return false;
    }
}

function openWinSearch(id){
    $('#winSearch').window('open')
    srcTindakan.focus();
    idTindakan.value = id;
}

function openWinTindakan(){
    $('#checkBahan').val('');
    $('#winTindakan').window('open');
    tindakanMedis.focus();
}

function openWinBahan(){
    frmBahan.reset();
    $('#checkBahan').val('1');
    $('#winBahan').window('open');
    bahan.focus();
}

function closeTindakan(){
    frmTindakan.reset();
    $('#frmTindakan').form('clear');
    $('#winTindakan').window('close');
    id_pasien.focus();
}

function openWinFasilitas(){
    frmFasilitas.reset();
    $('#checkBahan').val('');
    $('#jumlah').val('1');
    $('#winFasilitas').window('open');
    tindakanF.focus();
}

function loadDataListTindakan(){
    $('#dataListTindakan').datagrid({
        url:'json/tindakan.php?task=getListTindakan&srcTindakan=' + $('#srcTindakan').val() + '&idTindakan=' + $('#idTindakan').val()
    });
    $('#dataListTindakan').datagrid('reload');
}

function goToDiagnosa(){
    var noDftr = getURL('fid');

    window.location = "?page=dgns&fid=" + noDftr;
}

function goToTindakanKeperawatan(){
    var noDftr = getURL('fid');

    window.location = "?page=tndkn&fid=" + noDftr;
}

function setCitoTindakan(){
    if($('#cekCitoTindakan').val()=="1") citoTindakanMedis.checked = true;
    if($('#cekAlatTamu').val()=="1") alatTamu.checked = true;
}

function setTarif(id){
    $("#dokter_operator").val("");
    var noDftr = getURL('fid');

    if($('#checkBahan').val()==''){
        var dataString = "task=getTarif&id_detail_tindakan=" + id + "&no_pendaftaran=" + noDftr;

        $.ajax({
            type: "POST",
            url: "json/tindakanPost.php",
            data: dataString,
            success: function(data) {
                var returnData = data.split(":");
                if(returnData[2]=='1'){
                    $('#tarif').val(returnData[1]);
                    $('#id_tarif').val(returnData[0]);
                } else {
                    $('#tarifF').val(returnData[1]);
                    $('#id_tarifF').val(returnData[0]);
                }
            }
        });
    } else {
        var dataString = "task=getTarifBahan&id_barang=" + id + "&no_pendaftaran=" + noDftr;

        $.ajax({
            type: "POST",
            url: "json/tindakanPost.php",
            data: dataString,
            success: function(data) {
                var returnData = data.split(":");
                $('#stock').val(returnData[0]);
                $('#satuan').val(returnData[1]);
                $('#tarifBahan').val(returnData[2]);
                jumlahBarang.focus();
            }
        });
    }
    dokter_operator.focus()
    return false;
}
    
    </script>