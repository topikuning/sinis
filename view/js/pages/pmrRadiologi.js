<script>
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:1,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate:setHarga
    });
    
    $(function(){
        var noDftr = getURL('fid');
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        
        id_pasien.focus();
        openWinPemeriksaan();

        $('#dataPemeriksaan').datagrid({
            title:'Data Pemeriksaan',
            height:250,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible:true,
            url:'json/radiologi.php?task=getDetailPemeriksaan&no_pendaftaran=' + noDftr,
            sortName: 'id_detail_radiologi',
            sortOrder: 'asc',
            remoteSort: false,
            showFooter:true,
            idField:'id_detail_radiologi',
            frozenColumns:[[
                    {title:'ID',field:'id_detail_radiologi',width:50},
                ]],
            columns:[[
                    {field:'radiologi',title:'Pemeriksaan',width:250},
                    {field:'cito',title:'CITO',width:50},
                    {field:'cito_bed',title:'CITO BED',width:50},
                    {field:'keterangan',title:'Keterangan',width:400},
                    {field:'tarif',title:'Tarif',width:80}
                ]],
            pagination:true,
            rownumbers:true,
            toolbar:[{
                    id:'btndel',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var rows = $('#dataPemeriksaan').datagrid('getSelections');
                        var id = "task=hapusRadiologi&id_detail_radiologi=" + rows[0].id_detail_radiologi;
                        $.ajax({  
                            type: "POST",  
                            url: "json/radiologiPost.php",  
                            data: id,  
                            success: function(data) {  
                                if(data=='1'){
                                    $.messager.show({
                                        title:'Radiologi',
                                        msg:'Penghapusan Radiologi Nomor ' + rows[0].id_detail_radiologi + ' berhasil.',
                                        showType:'show'
                                    });
                                    $('#dataBahan').datagrid('reload');
                                    $('#dataPemeriksaan').datagrid('reload');
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Radiologi',
                                        msg:'Penghapusan Radiologi no ' + rows[0].id_detail_radiologi + ' gagal.',
                                        showType:'show'
                                    });
                                } else if (data=='2') {
                                        $.messager.show({
                                            title:'Radiologi',
                                            msg:'Penghapusan Radiologi Gagal. Pasien Sudah Melunasi Tagihan, Silahkan Close Perawatan',
                                            showType:'show'
                                        });
                                }
                            }
                        })
                    }
                }]
        });
        
        $('#dataBahan').datagrid({
            title:'Data Detail Pemeriksaan',
            height:250,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible:true,
            url:'json/radiologi.php?task=getBahanPemeriksaan&no_pendaftaran=' + noDftr,
            sortName: 'id_bahan_radiologi',
            sortOrder: 'asc',
            remoteSort: false,
            idField:'id_bahan_radiologi',
            frozenColumns:[[
                    {title:'ID',field:'id_bahan_radiologi',width:50},
                ]],
            columns:[[
                    {field:'radiologi',title:'Pemeriksaan',width:250},
                    {field:'film',title:'Ukuran Film',width:150},
                    {field:'jumlah',title:'Jumlah',width:50}
                ]],
            pagination:true,
            rownumbers:true
            //            toolbar:[{
            //                    id:'btndel',
            //                    text:'Hapus',
            //                    iconCls:'icon-remove',
            //                    handler:function(){
            //                        var rows = $('#dataBahan').datagrid('getSelections');
            //                        var id = "task=hapusDiagnosa&id_diagnosa=" + rows[0].id_diagnosa;
            //                        $.ajax({  
            //                            type: "POST",  
            //                            url: "json/diagnosaPost.php",  
            //                            data: id,  
            //                            success: function(data) {  
            //                                if(data=='1'){
            //                                    $.messager.show({
            //                                        title:'Diagnosa',
            //                                        msg:'Penghapusan Diagnosa Nomor ' + rows[0].id_diagnosa + ' berhasil.',
            //                                        showType:'show'
            //                                    });
            //                                    $('#databahan').datagrid('reload');
            //                                } else if (data=='0') {
            //                                    $.messager.show({
            //                                        title:'Diagnosa',
            //                                        msg:'Penghapusan Diagnosa no ' + rows[0].id_diagnosa + ' gagal.',
            //                                        showType:'show'
            //                                    });
            //                                }
            //                            }
            //                        })
            //                    }
            //                }]
        });

    });

    function openWinPemeriksaan(){
        pemeriksaanRadiologi.reset();
        $('#winPemeriksaan').window('open');
        radiologiField.focus();
    }

    function closeWinPemeriksaan(){
        pemeriksaanRadiologi.reset();
        $('#winPemeriksaan').window('close');
        id_pasien.focus();
    }
    
    function simpanPemeriksaan(){
        var valCito = '';
        var cito_bed = '';
        
        if(cito.checked) valCito = '1';
        if(citoBed.checked) cito_bed = '1';
            
        var noDftr = getURL('fid');
        var dataString = "task=simpanPemeriksaan&id_pendaftaran=" + noDftr +
            "&id_pasien=" + $("#id_pasien").val() +
            "&radiologiFieldId=" + $("#radiologiFieldId").val() +
            "&tarif=" + $("#tarif").val() +
            "&ukuranA=" + $("#ukuranA").val() +
            "&jumlahA=" + $("#jumlahA").val() +
            "&ukuranB=" + $("#ukuranB").val() +
            "&jumlahB=" + $("#jumlahB").val() +
            "&ukuranC=" + $("#ukuranC").val() +
            "&jumlahC=" + $("#jumlahC").val() +
            "&ukuranD=" + $("#ukuranD").val() +
            "&jumlahD=" + $("#jumlahD").val() +
            "&cito=" + valCito +
            "&citoBed=" + cito_bed +
            "&keterangan=" + $("#keterangan").val();
        
        var bvalid = true;

        bvalid = bvalid && checkSelect($("#radiologiFieldId").val(), 'Jenis Pemeriksaan');
        bvalid = bvalid && checkSelect($("#tarif").val(), 'Tarif');

        if(bvalid){          
            $.ajax({  
                type: "POST",  
                url: "json/radiologiPost.php",  
                data: dataString,
                success: function(data) { 
                    var returnData = data.split(":");
                    var status = returnData[0];
                    if(status=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(status=='TRUE'){
                        $.messager.show({
                            title:'Pemeriksaan Radiologi',
                            msg:'Pemeriksaan berhasil disimpan.',
                            showType:'show'
                        });
                        pemeriksaanRadiologi.reset();
                        radiologiField.focus();
                        $('#dataPemeriksaan').datagrid('reload');
                        $('#dataBahan').datagrid('reload');
                        return false;
                    } else if(status=='WARNING') {
                        $.messager.show({
                            title:'Pemeriksaan Radiologi',
                            msg:'Pemeriksaan berhasil di simpan, dengan Catatan : <b>' + returnData[1] + '</b>',
                            showType:'show'
                        });
                        pemeriksaanRadiologi.reset();
                        radiologiField.focus();
                        $('#dataPemeriksaan').datagrid('reload');
                        $('#dataBahan').datagrid('reload');
                        return false;
                    } else if(status=='ERROR') {
                        $.messager.show({
                            title:'Pemeriksaan Radiologi',
                            msg:'Gagal menyimpan Pemeriksaan.',
                            showType:'show'
                        });
                        return false;
                    } else if(status=='LUNAS') {
                        $.messager.show({
                            title:'Pemeriksaan Radiologi',
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

    function simpanClosePerawatan(){
        var dataString = "task=simpanClosePemeriksaan&id_pendaftaran=" + getURL('fid') +
            "&kondisi_keluar=" + $("#kondisiKeluar").val() + 
            "&cara_keluar=" + $("#caraKeluar").val() + 
            "&keterangan_keluar=" + $("#keteranganKeluar").val() + 
            "&tgl_out=" + $("#tglKeluar").datebox("getValue");

        $.ajax({  
            type: "POST",  
            url: "json/radiologiPost.php",  
            data: dataString,
            success: function(data) {
                if(data=='1'){
                    $.messager.alert('Radiologi',
                    'Close Pemeriksaan Berhasil. Generate Jasa Berhasil.',
                    'alert'
                );
                    $('#winClosePerawatan').window('close');
                    window.location = "?page=lstdftrpr";
                } else {
                    $.messager.show({
                        title:'Radiologi',
                        msg:'Close Pemeriksaan gagal. ' + data,
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    }

    function setHarga(id){
        var noDftr = getURL('fid');
        
        var dataString = "task=getHarga&id_pendaftaran=" + noDftr +
            "&id_radiologi=" + id;

        $.ajax({  
            type: "POST",  
            url: "json/radiologiPost.php",  
            data: dataString,  
            success: function(data) {  
                if(data){
                    $('#tarif').val(data);
                    $('#ukuranA').focus();
                }
            }  
        });  
        return false;
    }
    
    function openClosePerawatan(){
        $('#winClosePerawatan').window('open');
        kondisiKeluar.focus();
    }

</script>