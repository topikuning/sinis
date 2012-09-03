<script>
    
    $(function(){
        var noDftr = getURL('fid');
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        $('#frmInterHasil').form('load','json/laboratorium.php?task=cariInterHasil&no_pendaftaran=' + noDftr)
        $('#frmHapusanDarah').form('load','json/laboratorium.php?task=cariHapusanDarah&no_pendaftaran=' + noDftr)
        
        id_pasien.focus();

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
                }]
        });

    });
    
    function lihatHasil(){
        window.open ("../../reports/Hasil_Labsmry.php?so1_RM_Px2E=%3D&sv1_RM_Px2E="+ id_pasien.value + "&Submit=Search");
    }
    
    function openWinClosePemeriksaan(){
        $("#frmClosePemeriksaan").form("load",'json/laboratorium.php?task=getDetailPerawatanLab&id_pendaftaran=' + getURL("fid"));
        $('#winClosePemeriksaan').window('open');
        periksaSampel_edit.focus();
    }
    
    function closeWinClosePemeriksaan(){
        frmClosePemeriksaan.reset();
        $('#winClosePemeriksaan').window('close');
        id_pasien.focus();
    }
    
    
    function editDetailPemeriksaan(){
        var rows = $('#dataPemeriksaan').datagrid('getRows');
        for (var j=0; j<rows.length;j++){
            $('#dataPemeriksaan').datagrid('beginEdit', j);
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

    function openCetakHasil(){
        frmCetak.reset();
        $('#winCetakHasil').window('open');
        nom.focus();
    }

</script>