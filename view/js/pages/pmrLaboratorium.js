<script>
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate:''
    });
    
    $(function(){
        var noDftr = getURL('fid');
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        $('#frmInterHasil').form('load','json/laboratorium.php?task=cariInterHasil&no_pendaftaran=' + noDftr)
        $('#frmHapusanDarah').form('load','json/laboratorium.php?task=cariHapusanDarah&no_pendaftaran=' + noDftr)
        
        id_pasien.focus();
        openWinPemeriksaan();

        $( "#kelompokPlus" ).change(function(){
            $.getJSON("json/data.php", {task: 'listLaboratorium', id_kelompok_lab: $(this).val()},
            function(data) {
                var opt = '';
                opt += '<option value="">[Pilih Jenis Pemeriksaan]</option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#periksa").html(opt);
                }
            });
        });

        $('#dataPemeriksaan').datagrid({
            title:'Data Pemeriksaan',
            height:330,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible:true,
            url:'json/laboratorium.php?task=getDetailPemeriksaan&no_pendaftaran=' + noDftr,
            remoteSort: false,
            showFooter:true,
            idField:'id_detail_laboratorium',
            frozenColumns:[[
                    {title:'ID',field:'id_detail_laboratorium',width:50},
                ]],
            columns:[[
                    {field:'kelompok_lab',title:'Kelompok Pemeriksaan',width:150},
                    {field:'laboratorium',title:'Type Periksa',width:150},
                    {field:'metode',title:'Metode',width:150},
                    {field:'nilai_normal',title:'Nilai Normal',width:200},
                    {field:'hasil',title:'Hasil',width:200, editor:"text"},
                    {field:'tarif',title:'Tarif',width:80}
                ]],
            rownumbers:true,
            toolbar:[{
                    id:'btndel',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var rows = $('#dataPemeriksaan').datagrid('getSelections');
                        var stringValue = "task=hapusLaboratorium&id_detail_laboratorium=" + rows[0].id_detail_laboratorium;

                        $.ajax({  
                            type: "GET",  
                            url: "json/laboratorium.php",  
                            data: stringValue,  
                            success: function(data) {  
                                if(data=='1'){
                                    $.messager.show({
                                        title:'Pemeriksaan Laboratorium',
                                        msg:'Penghapusan Pemeriksaan Laboratorium berhasil.',
                                        showType:'show'
                                    });
                                    var row = $('#dataPemeriksaan').datagrid('getSelected');
                                    var index = $('#dataPemeriksaan').datagrid('getRowIndex', row);
                                    $('#dataPemeriksaan').datagrid('deleteRow', index);
                                    $('#dataPemeriksaan').datagrid('reload');
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Pemeriksaan Laboratorium',
                                        msg:'Penghapusan Pemeriksaan Laboratorium gagal.',
                                        showType:'show'
                                    });
                                } else if (data=='2') {
                                    $.messager.show({
                                        title:'Radiologi',
                                        msg:'Penghapusan Gagal. Pasien Sudah Melunasi Tagihan, Silahkan Close Perawatan',
                                        showType:'show'
                                    });
                                }
                            }
                        })
                    }
                },{
                    id:'btnsave',
                    text:'Simpan',
                    iconCls:'icon-save',
                    handler:function(){
                        var rows = $('#dataPemeriksaan').datagrid('getRows');
                        for (var j=0; j<rows.length;j++){
                            $('#dataPemeriksaan').datagrid('endEdit', j);
                        }
                        saveDetail();
                    }
                },{
                    id:'btnedit',
                    text:'Update',
                    iconCls:'icon-edit',
                    handler:function(){
                        editDetailPemeriksaan();
                    }
                },{
                        id:'btndiskon',
                        text:'Diskon',
                        iconCls:'icon-bayar',
                        handler:function(){
                            $('#frmDiskon').form('load','json/data.php?task=getResumeTagihanPasien&id_pasien=' + id_pasien.value);
                            openWinDiskon();
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
            //showFooter:true,
            collapsible:true,
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

    });
    
    function lihatHasil(){
        window.open ("../../reports/Hasil_Labsmry.php?so1_RM_Px2E=%3D&sv1_RM_Px2E="+ id_pasien.value + "&Submit=Search");
    }
    
    function openWinDiskon(){
        frmDiskon.reset();
        $('#winDiskon').window('open');
        diskon.focus();
    }
    
    function simpanDiskon(){
        var idp = getURL('fid');
        var dataString = "task=simpanDiskonTindakan&id_pendaftaran=" + idp +
            "&id_pasien=" + $("#rm_pas").val() + 
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
                        } else if (data=='2') {
                            $.messager.show({
                                title:'Diskon',
                                msg:'Pemberian Diskon Gagal. Pasien Sudah Melunasi Tagihan, Silahkan Close Perawatan',
                                showType:'show'
                            });
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

    function openWinPemeriksaan(){
        pemeriksaanLab.reset();
        $("#pemeriksaanLab").form("load",'json/laboratorium.php?task=getPemeriksaanLab&id_pendaftaran=' + getURL("fid"));
        $('#winPemeriksaan').window('open');
        noPeriksa.focus();
    }

    function openWinClosePemeriksaan(){
        $("#frmClosePemeriksaan").form("load",'json/laboratorium.php?task=getDetailPerawatanLab&id_pendaftaran=' + getURL("fid"));
        $('#winClosePemeriksaan').window('open');
        periksaSampel_edit.focus();
    }

    function openWinPemeriksaanPlus(){
        pemeriksaanLabPlus.reset();
        $('#winPemeriksaanPlus').window('open');
    }

    function closeWinPemeriksaan(){
        pemeriksaanLab.reset();
        $('#winPemeriksaan').window('close');
        id_pasien.focus();
    }
    
    function closeWinClosePemeriksaan(){
        frmClosePemeriksaan.reset();
        $('#winClosePemeriksaan').window('close');
        id_pasien.focus();
    }
    
    function closeWinPemeriksaanPlus(){
        pemeriksaanLabPlus.reset();
        $('#winPemeriksaanPlus').window('close');
        id_pasien.focus();
    }
    
    function editDetailPemeriksaan(){
        var rows = $('#dataPemeriksaan').datagrid('getRows');
        for (var j=0; j<rows.length;j++){
            $('#dataPemeriksaan').datagrid('beginEdit', j);
        }
    }
    
    function simpanPemeriksaan(){
        var valCito = '';
        
        if(cito.checked) valCito = '1';
            
        var noDftr = getURL('fid');
        var dataString = "task=simpanPemeriksaan&id_pendaftaran=" + noDftr +
            "&id_pasien=" + $("#id_pasien").val() +
            "&kelompokPeriksaId=" + $("#kelompokPeriksaId").val() +
            "&noPeriksa=" + $("#noPeriksa").val() +
            "&ambilSampel=" + $("#ambilSampel").datetimebox("getValue") +
            "&periksaSampel=" + $("#periksaSampel").datetimebox("getValue") +
            "&selesaiSampel=" + $("#selesaiSampel").datetimebox("getValue") +
            "&cito=" + valCito;
        
        var bvalid = true;

        bvalid = bvalid && checkSelect($("#noPeriksa").val(), 'Nomor Pemeriksaan');
        //bvalid = bvalid && checkSelect($("#kelompokPeriksaId").val(), 'Kelompok Pemeriksaan');
        bvalid = bvalid && checkSelect($("#ambilSampel").datetimebox("getValue"), 'Ambil Sampel');

        if(bvalid){          
            $.ajax({  
                type: "POST",  
                url: "json/laboratoriumPost.php",  
                data: dataString,
                success: function(data) {
                    var returnData = data.split(":");
                    var status = returnData[0];
                    if(status=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(status=='TRUE'){
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Pemeriksaan berhasil disimpan. ' + returnData[1] + ' Tipe Pemeriksaan berhasil di tambah.',
                            showType:'show'
                        });
                        noPeriksa.focus();
                        $('#dataPemeriksaan').datagrid('reload');
                        return false;
                    } else if(status=='WARNING') {
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Pemeriksaan berhasil di simpan, dengan Catatan : <b>' + returnData[1] + '</b>',
                            showType:'show'
                        });
                        pemeriksaanLab.reset();
                        noPeriksa.focus();
                        $('#dataPemeriksaan').datagrid('reload');
                        return false;
                    } else if(status=='FALSE') {
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Gagal menyimpan Pemeriksaan.',
                            showType:'show'
                        });
                        return false;
                    } else if(status=='LUNAS') {
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Gagal menyimpan Pemeriksaan. Tagihan sudah terbayar. Silahkan Close perawatan.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }        
    }

    function simpanClosePemeriksaan(){
        var noDftr = getURL('fid');
        var dataString = "task=simpanClosePemeriksaan&id_pendaftaran=" + noDftr +
            "&ambilSampel=" + $("#ambilSampel_edit").datetimebox("getValue") +
            "&periksaSampel=" + $("#periksaSampel_edit").datetimebox("getValue") +
            "&selesaiSampel=" + $("#selesaiSampel_edit").datetimebox("getValue");
        
        var bvalid = true;

        bvalid = bvalid && checkSelect($("#periksaSampel_edit").datetimebox("getValue"), 'Tanggal Periksan');
        bvalid = bvalid && checkSelect($("#selesaiSampel_edit").datetimebox("getValue"), 'Tanggal Selesai');

        if(bvalid){          
            $.ajax({  
                type: "POST",  
                url: "json/laboratoriumPost.php",  
                data: dataString,
                success: function(data) {
                    if(data=='1'){
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Close Pemeriksaan berhasil disimpan.',
                            showType:'show'
                        });
                        closeWinClosePemeriksaan();
                        return false;
                    } else if(data=='0') {
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Gagal menyimpan Close Pemeriksaan.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }        
    }

    function simpanPemeriksaanPlus(){
        var valCito = '';
        
        if(citoPlus.checked) valCito = '1';
            
        var noDftr = getURL('fid');
        var dataString = "task=simpanPemeriksaanPlus&id_pendaftaran=" + noDftr +
            "&id_pasien=" + $("#id_pasien").val() +
            "&kelompokPeriksaId=" + $("#kelompokPlus").val() +
            "&citoPlus=" + valCito +
            "&periksa=" + $("#periksa").val();
        
        var bvalid = true;

        bvalid = bvalid && checkSelect($("#kelompokPlus").val(), 'Kelompok Pemeriksaan');
        bvalid = bvalid && checkSelect($("#periksa").val(), 'Jenis Pemeriksaan');

        if(bvalid){          
            $.ajax({  
                type: "POST",  
                url: "json/laboratoriumPost.php",  
                data: dataString,
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='TRUE'){
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Penambahan Pemeriksaan Berhasil.',
                            showType:'show'
                        });
                        pemeriksaanLabPlus.reset();
                        $('#dataPemeriksaan').datagrid('reload');
                        return false;
                    } else if(data=='FALSE') {
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Gagal menyimpan Penambahan Pemeriksaan.',
                            showType:'show'
                        });
                        return false;
                    } else if(data=='LUNAS') {
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Gagal menyimpan Pemeriksaan. Tagihan sudah terbayar. Silahkan Close perawatan.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }        
    }

    function saveInterHasil(){
        var noDftr = getURL('fid');
        var dataString = "task=simpanInterHasil&id_pendaftaran=" + noDftr +
            "&interHasil=" + $("#interHasil").val();
        
        var bvalid = true;

        bvalid = bvalid && checkSelect($("#interHasil").val(), 'Interpretasi Hasil');

        if(bvalid){          
            $.ajax({  
                type: "POST",  
                url: "json/laboratoriumPost.php",  
                data: dataString,
                success: function(data) {
                    var returnData = data.split(":");
                    var status = returnData[0];
                    if(status=='TRUE'){
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Interpretasi Hasil berhasil disimpan.',
                            showType:'show'
                        });
                        return false;
                    } else if(status=='FALSE') {
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Gagal menyimpan Interpretasi Hasil.',
                            showType:'show'
                        });
                        return false;
                    } else if(status=='LUNAS') {
                        $.messager.show({
                            title:'Pemeriksaan Laboratorium',
                            msg:'Gagal menyimpan Pemeriksaan. Tagihan sudah terbayar. Silahkan Close perawatan.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }        
    }

    function saveHapusanDarah(){
        var noDftr = getURL('fid');
        var dataString = "task=simpanHapusanDarah&id_pendaftaran=" + noDftr +
            "&eritrosit=" + $("#eritrosit").val() +
            "&leukosit=" + $("#leukosit").val() +
            "&trombosit=" + $("#trombosit").val();
        
        $.ajax({  
            type: "POST",  
            url: "json/laboratoriumPost.php",  
            data: dataString,
            success: function(data) {
                var returnData = data.split(":");
                var status = returnData[0];
                if(status=='TRUE'){
                    $.messager.show({
                        title:'Hapusan Darah',
                        msg:'Hapusan Darah berhasil disimpan.',
                        showType:'show'
                    });
                    return false;
                } else if(status=='FALSE') {
                    $.messager.show({
                        title:'Hapusan Darah',
                        msg:'Gagal menyimpan Hapusan Darah.',
                        showType:'show'
                    });
                    return false;
                } else if(status=='LUNAS') {
                    $.messager.show({
                        title:'Pemeriksaan Laboratorium',
                        msg:'Gagal menyimpan Pemeriksaan. Tagihan sudah terbayar. Silahkan Close perawatan.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    }

    function cetakHasil(){
        var dataString = "task=cetakLaboratorium&id_pendaftaran=" + nom.value;
        
        var bvalid = true;
        bvalid = bvalid && checkSelect(nom.value, 'Nomor Pemeriksaan');

        if(bvalid){          
            $.ajax({  
                type: "POST",  
                url: "json/laboratoriumPost.php",  
                data: dataString,
                success: function(data) {
                    if(data=='1'){
                        var win = window.open('report/cetakHasilLab.html','cetakLab','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                    }
                    $('#winCetakHasil').window('close')
                }  
            });  
            return false;
        }        
    }
    
    function cetakLaboratorium(){
        var noDftr = getURL('fid');
        
        var dataString = "task=cetakLaboratorium&id_pendaftaran=" + noDftr;
        
        var bvalid = true;

        bvalid = bvalid && checkSelect($("#interHasil").val(), 'Interpretasi Hasil');

        if(bvalid){          
            $.ajax({  
                type: "POST",  
                url: "json/laboratoriumPost.php",  
                data: dataString,
                success: function(data) {
                    if(data=='1'){
                        var win = window.open('report/cetakHasilLab.html','cetakLab','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                    }
                }  
            });  
            return false;
        }        
    }

    function saveDetail(){
        var rows = $('#dataPemeriksaan').datagrid('getRows');
        var sukses=0;
        var gagal=0;
        var noDftr = getURL('fid');
        for (var i=0;i<rows.length;i++){
            var dataString = "task=simpanDetailPemeriksaan&id_pendaftaran=" + noDftr +
                "&id_detail_laboratorium=" + rows[i].id_detail_laboratorium +
                "&hasil=" + rows[i].hasil;

            $.ajax({  
                type: "POST",  
                url: "json/laboratoriumPost.php",  
                data: dataString,
                success: function(data) {
                    if(data=='1')
                        sukses++;
                    else if (data=='0')
                        sukses++;
                    else
                        gagal++;
                }  
            });  
        }
        $.messager.show({
            title:'Hasil Pemeriksaan',
            msg: sukses + ' Data Hasil Pemeriksaan berhasil di update.',
            showType:'show'
        });
        $('#dataPemeriksaan').datagrid('reload');
        return false;
    }

    function openWinBahan(){
        frmBahan.reset();
        $('#checkBahan').val('1');
        $('#winBahan').window('open');
        bahan.focus();
    }
    
    function openCetakHasil(){
        frmCetak.reset();
        $('#winCetakHasil').window('open');
        nom.focus();
    }

    function simpanClosePerawatan(){
        var dataString = "task=simpanClosePerawatan&id_pendaftaran=" + getURL('fid') +
            "&kondisi_keluar=" + $("#kondisiKeluar").val() + 
            "&cara_keluar=" + $("#caraKeluar").val() + 
            "&keterangan_keluar=" + $("#keteranganKeluar").val() + 
            "&tgl_out=" + $("#tglKeluar").datebox("getValue");
        
        $('#simpan').linkbutton({
            disabled:true
        });
        
        $.ajax({  
            type: "POST",  
            url: "json/laboratoriumPost.php",  
            data: dataString,
            success: function(data) {
                if(data=='1'){
                    $.messager.alert('Laboratorium',
                    'Close Pemeriksaan Berhasil. Generate Jasa Berhasil.',
                    'alert'
                );
                    $('#winClosePerawatan').window('close');
                    window.location = "?page=lstlab";
                } else {
                    $.messager.show({
                        title:'Laboratorium',
                        msg:'Close Pemeriksaan gagal. ' + data,
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
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

</script>