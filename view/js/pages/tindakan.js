<script>
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate: setTarif
    });
    
    $.fn.autosugguest({  
        className:'ausu-suggestF',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate: setTarifF
    });
	
    $.fn.autosugguest({  
        className:'prediksi',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate: setStock
    });
    
    $(function(){
        $("#tglInput").inputmask("d-m-y");
        var noDftr = getURL('fid');
        var idPasien = getURL('pid');
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        $('#inTindakan').form('load','json/diagnosa.php?task=cariDokterJb&no_pendaftaran=' + noDftr)
        $('#inFasilitas').form('load','json/diagnosa.php?task=cariDokterJb&no_pendaftaran=' + noDftr)
        
        tindakan.focus();


        $('#dataTindakan').datagrid({
            title:'Data Tindakan',
            height:250,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/tindakan.php?task=getTindakanRuang&no_pendaftaran=' + noDftr,
            sortName: 'id_tindakan_ruang',
            sortOrder: 'des',
            remoteSort: false,
            showFooter:true,
            collapsible:true,
            idField:'id_tindakan_ruang',
            pageList: [100,200,300,400],
            frozenColumns:[[
                    {title:'ID',field:'id_tindakan_ruang',width:50},
                ]],
            columns:[[
                    {field:'tindakan',title:'Tindakan',width:250},
                    {field:'icd',title:'ICD',width:60},
                    {field:'tanggal',title:'Tanggal',width:150},
                    {field:'dokter',title:'Dokter',width:150},
                    {field:'advice',title:'Advice',width:150},
                    {field:'tarif',title:'Tarif',width:120,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }

                    },
                    {field:'operator',title:'Operator',width:150}
                ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(rowIndex){
                var rows = $('#dataTindakan').datagrid('getSelections');
                var id_tindakan_ruang = rows[0].id_tindakan_ruang;
                $('#inTindakan').form('load','json/tindakan.php?task=getDtlTindakan&id_tindakan_ruang=' + id_tindakan_ruang);
            },
            toolbar:[{
                    id:'btndel',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var rows = $('#dataTindakan').datagrid('getSelections');
                        var id = "task=hapusTindakan&id_tindakan=" + rows[0].id_tindakan_ruang;
                        $.ajax({  
                            type: "POST",  
                            url: "json/tindakanPost.php",  
                            data: id,  
                            success: function(data) {  
                                if(data=='1'){
                                    $.messager.show({
                                        title:'Tindakan',
                                        msg:'Penghapusan Tindakan Nomor ' + rows[0].id_tindakan_ruang + ' berhasil.',
                                        showType:'show'
                                    });
                                    var row = $('#dataTindakan').datagrid('getSelected');
                                    var index = $('#dataTindakan').datagrid('getRowIndex', row);
                                    $('#dataTindakan').datagrid('deleteRow', index);
                                    $('#dataTindakan').datagrid('reload');
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Diagnosa',
                                        msg:'Penghapusan Tindakan no ' + rows[0].id_tindakan_ruang + ' gagal.',
                                        showType:'show'
                                    });
                                }
                            }
                        })
                    }
                },{
                    id:'btndiskon',
                    text:'Diskon',
                    iconCls:'icon-bayar',
                    handler:function(){
                        //$('#frmDiskon').form('load','json/tindakan.php?task=getTagihanTindakanPasien&id_pendaftaran=' + noDftr);
                        $('#frmDiskon').form('load','json/data.php?task=getResumeTagihanPasien&id_pasien=' + id_pasien.value);
                        openWinDiskon();
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
            sortOrder: 'des',
            remoteSort: false,
            showFooter:true,
            pageList: [100,200,300,400],
            collapsible:true,
            collapsed:true,			
            idField:'id_fasilitas_ruang',
            frozenColumns:[[
                    {title:'ID',field:'id_fasilitas_ruang',width:50},
                ]],
            columns:[[
                    {field:'tindakan',title:'Fasilitas',width:250},
                    {field:'dokter',title:'Dokter',width:150},
                    {field:'advice',title:'Advice',width:150},
                    {field:'jumlah',title:'Jumlah',width:50},
                    {field:'tarif',title:'Tarif',width:120,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'total',title:'Total',width:120,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    }
                ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(rowIndex){
                var rows = $('#dataFasilitas').datagrid('getSelections');
                var id_fasilitas_ruang = rows[0].id_fasilitas_ruang;
                $('#inFasilitas').form('load','json/tindakan.php?task=getDtlFasilitas&id_fasilitas_ruang=' + id_fasilitas_ruang);
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
                },]
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
            //showFooter:true,
            collapsible:true,
            collapsed:true,			
            idField:'id_barang_tindakan',
            frozenColumns:[[
                    {title:'ID',field:'id_barang_tindakan',width:50},
                ]],
            columns:[[
                    {field:'barang',title:'Nama Barang',width:250},
                    {field:'stock',title:'Sisa Stock',width:50},
                    {field:'jumlah',title:'Jumlah',width:150},
                    {field:'satuan',title:'Satuan',width:150},
                    /*{field:'tarif',title:'Tarif',width:200},
                    {field:'total',title:'Total',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    }*/
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
                    {title:'ID',field:'id_tindakan',width:50},
                    {field:'tindakan',title:'Tindakan',width:350},
                    {field:'icd',title:'ICD',width:80}
                ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(rowIndex){
                var rows = $('#dataListTindakan').datagrid('getSelections');
                var tindakan = rows[0].tindakan;
                var id_tindakan = rows[0].id_tindakan;
                if($('#idTindakan').val()=='1'){
                    $('#tindakan').val(tindakan);
                    $('#tindakanId').val(id_tindakan);
                    $('#tindakan').focus();
                    setTarif(id_tindakan);
                } else {
                    $('#tindakanF').val(tindakan);
                    $('#tindakanFId').val(id_tindakan);
                    $('#tindakanF').focus();
                    setTarifF(id_tindakan);
                }
                
                //setTarif(id_tindakan)
                
                $('#winSearch').window('close');
            }
        });
        
        $('#id_tindakan').change(function(){
            

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
        $('#dataListPasien').datagrid({
            title:'Data Pasien',
            height:300,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'id_pasien',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'id_pasien',
            frozenColumns:[[
                    {title:'No RM',field:'id_pasien',width:50,sortable:true}
                ]],
            columns:[[
                    {field:'no_pendaftaran',title:'Nomor Pendaftaran',width:150,sortable:true,hidden:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:150,sortable:true},
                    {field:'jns_kelamin',title:'L/P',width:30,sortable:true},
                    {field:'tmp_lahir',title:'Tempat Lahir',width:100,sortable:true},
                    {field:'tgl_lahir',title:'Tanggal Lahir',width:80,sortable:true},
                    {field:'alamat',title:'Alamat',width:80,sortable:true},
                    {field:'kelurahan',title:'Kelurahan',width:80,sortable:true},
                    {field:'kecamatan',title:'Kecamatan',width:80,sortable:true},
                    {field:'kota',title:'Kota',width:80,sortable:true},
                    {field:'marital',title:'Marital',width:80,sortable:true},
                    {field:'asuransi',title:'Tipe Asuransi',width:80,sortable:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:80,sortable:true}
                ]],
            onDblClickRow:function(){
                var rows = $('#dataListPasien').datagrid('getSelections');
                if(rows[0].no_pendaftaran>0){
                    window.location='index.php?page=tndkn&fid=' + rows[0].no_pendaftaran;
                    closeWinSearchPasien();
                } else {
                    $.messager.show({
                        title:'Diagnosa',
                        msg:'Pasien tidak terdaftar.',
                        showType:'show'
                    });
                }
            },
            pagination:true,
            rownumbers:true
        });
    });
    
    
    function loadDiagnosaPasien(){
        $.ajax({  
            type: "GET",  
            url: "json/data.php",  
            data: "task=getPendaftaran&id_pasien=" + $("#id_pasien").val(),  
            success: function(data) {
                if(data>'0'){
                    window.location='index.php?page=tndkn&fid=' + data + '&pid=' + $("#id_pasien").val();
                } else {
                    $.messager.show({
                        title:'Tindakan',
                        msg:'Pasien tidak terdaftar di ruangan ini.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
    }
    
    function saveTindakan(){
        var citoCheck = '0';
        if(cito.checked)
            citoCheck = '1';
        var dataString = "task=simpanTindakan&id_pendaftaran=" + getURL('fid') +
            "&id_tindakan_ruang=" +$("#id_tindakan_ruang").val() +
            "&id_tindakan=" +$("#tindakanId").val() +
            "&id_dokter=" + $("#dokter").combobox("getValue") + 
            "&advice=" + $("#advice").val() + 
            "&id_tarif=" + $("#id_tarif").val() +
            "&tarif=" + $("#tarif").val() +
            "&cito=" + citoCheck +
            "&id_operator=" + $("#operator").val() + 
            "&tglInput=" + $("#tglInput").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#tindakanId").val(), 'Tindakan');
        bvalid = bvalid && checkSelect($("#dokter").combobox("getValue"), 'Dokter');
        bvalid = bvalid && checkSelect($("#operator").val(), 'Operator');
        bvalid = bvalid && checkSelect($("#tglInput").val(), 'Tanggal');

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
                            title:'Tindakan',
                            msg:'Tindakan berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataTindakan').datagrid('reload');
                        $("#id_tindakan_ruang").val("");
                        $("#tindakan").val("");
                        $("#tindakanId").val("");
                        $("#advice").val("");
                        $("#id_tarif").val("");
                        $("#tarif").val("");
                        tindakan.focus();
                        return false;
                    } else if (data=='2'){
                        $.messager.show({
                            title:'Tindakan',
                            msg:'Gagal menyimpan Tindakan. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                            showType:'show'
                        });
                        return false;
                    } else if (data=='0'){
                        $.messager.show({
                            title:'Tindakan',
                            msg:'Gagal menyimpan Tindakan.',
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
            "&id_dokter=" + $("#dokterF").combobox("getValue") + 
            "&advice=" + $("#adviceF").val() + 
            "&id_tarif=" + $("#id_tarifF").val() +
            "&tarifF=" + $("#tarifF").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#tindakanFId").val(), 'Fasilitas');
        bvalid = bvalid && checkSelect($("#dokterF").combobox("getValue"), 'Dokter');
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
            "&jumlah=" + $("#jumlahBarang").val();
        //+ "&tarifBahan=" + $("#tarifBahan").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#bahanId").val(), 'Bahan');
        bvalid = bvalid && checkSelect($("#jumlahBarang").val(), 'Jumlah Barang');
        bvalid = bvalid && checkSelect($("#stock").val(), 'Stock');

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
                        $("#id_barang_tindakan").val("");
                        $("#bahanId").val("");
                        $("#bahan").val("");
                        $("#jumlahBarang").val("");
                        $("#stock").val("");
                        $("#satuan").val("");
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

    function simpanDiskon(){
        var dataString = "task=simpanDiskonTindakan&id_pendaftaran=" + getURL('fid') +
            "&id_pasien=" + getURL('pid') + 
            "&diskon=" + $("#diskon").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#diskon").val(), 'Diskon');

        if(bvalid){
            if(parseInt($("#diskon").val()) > parseFloat($("#kurang").val())){
                $.messager.show({
                    title:'Diskon',
                    msg:'Pemberian Diskon melibihi total tagihan.',
                    showType:'show'
                });
                $("#diskon").val("");
                diskon.focus();
            } else {
                $.ajax({  
                    type: "GET",  
                    url: "json/tindakan.php",  
                    data: dataString,  
                    success: function(data) {
                        if(data=='LOGIN'){
                            alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                            window.location.reload();
                        } else if(data=='1'){
                            $.messager.show({
                                title:'Diskon',
                                msg:'Diskon berhasil disimpan. ' + data,
                                showType:'show'
                            });
                            $('#winDiskon').window('close');
                            return false;
                        } else {
                            $.messager.show({
                                title:'Diskon',
                                msg:'Gagal menyimpan Diskon.',
                                showType:'show'
                            });
                            $("#diskon").val("");
                            diskon.focus();
                            return false;
                        }
                    }  
                });  
                return false;
            }
        }
    }

    function openWinSearch(id){
        $('#winSearch').window('open')
        $('#dataListTindakan').datagrid({
            url:'json/tindakan.php?task=getListTindakan&srcTindakan=&idTindakan=' + id
        });
        $('#dataListTindakan').datagrid('reload');
        srcTindakan.focus();
        idTindakan.value = id;
    }
   
    function openWinDiskon(){
        frmDiskon.reset();
        $('#winDiskon').window('open');
        diskon.focus();
    }

    function loadDataListTindakan(){
        $('#dataListTindakan').datagrid({
            url:'json/tindakan.php?task=getListTindakan&srcTindakan=' + $('#srcTindakan').val() + '&idTindakan=' + $('#idTindakan').val()
        });
        $('#dataListTindakan').datagrid('reload');
    }
    
    function setTarif(id){
        var noDftr = getURL('fid');
        
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
        tglInput.focus();
        return false;
    }
    
    function setTarifF(id){
        var noDftr = getURL('fid');
        
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
        jumlah.focus();
        return false;
    }
	
    function setStock(id){
        var noDftr = getURL('fid');
        var dataString = "task=getTarifBahan&id_barang=" + id + "&no_pendaftaran=" + noDftr;

        $.ajax({  
            type: "POST",  
            url: "json/tindakanPost.php",  
            data: dataString,  
            success: function(data) {
                var returnData = data.split(":");
                $('#stock').val(returnData[0]);
                $('#satuan').val(returnData[1]);
                //$('#tarifBahan').val(returnData[2]);
            }  
        });
        return false;
    }

</script>