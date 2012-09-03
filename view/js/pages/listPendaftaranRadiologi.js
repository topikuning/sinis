<script>
    $(function(){
        
        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            remoteSort: false,
            url:'json/listPendaftaranRuang.php?task=cariPendaftaranRuang&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
            showFooter:true,
            pageList: [100,200,400,500],
            idField:'id_pendaftaran',
            frozenColumns:[[
                    {field:'no_antrian',title:'No Antrian',width:30,sortable:true},
                    {title:'ID',field:'id_pendaftaran',width:50,sortable:true,hidden:true},
                    {field:'id_pasien',title:'No RM',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'id_tipe_pasien',title:'ID Pasien',width:120,hidden:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:100},
                    {field:'kelas',title:'Kelas',width:70},
                    {field:'id_kelas_pendaftaran',title:'ID Kelas',width:120,hidden:true},
                    {field:'id_tipe_pendaftaran',title:'ID Tipe Pendaftaran',width:120,hidden:true},
                    {field:'asal_ruang',title:'Ruang Asal',width:100},
                    {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:100},
                    {field:'id_ruang',title:'ID Ruang',width:120,hidden:true},
                    {field:'ruang',title:'Ruang',width:150},
                    {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:100},
                    {field:'jam_daftar',title:'Jam Daftar',width:80},
                    {field:'asal_rujukan',title:'Asal Rujukan',width:120},
                    {field:'perujuk',title:'Perujuk',width:120},
                    {field:'jadwal',title:'Jadwal Layanan',width:180},
                    {field:'usia',title:'Usia',width:1,hidden:true},
                    {field:'dokter',title:'Dokter',width:1,hidden:true},
                    {field:'kelamin',title:'Kelamin',width:1,hidden:true}
                ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(){
                layanan();
            },
            toolbar:[{
                    id:'btnLayanan',
                    text:'Layanan',
                    iconCls:'icon-openrm',
                    handler:function(){
                        layanan();
                    }
                },{
                    id:'btnLab',
                    text:'Laborat',
                    iconCls:'icon-lab',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            window.open ("../../reports/Hasil_Labsmry.php?so1_RM_Px2E=%3D&sv1_RM_Px2E="+ row.id_pasien + "&Submit=Search");
                        }
                    }
                },{
                    id:'btnRM',
                    text:'Rekam Medis',
                    iconCls:'icon-double',
                    handler:function(){
                        goToRM();                        
                    }
                },{
                id:'btnHapus',
                text:'Hapus',
                iconCls:'icon-remove',
                handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            var rows = $('#dataPendaftaran').datagrid('getSelections');
                            var noDaftar = "task=batalDaftar&id_pendaftaran=" + rows[0].id_pendaftaran;
                            $.ajax({  
                                type: "POST",
                                url: "json/pendaftaran.php",
                                data: noDaftar,
                                success: function(data) {
                                    if(data=='1'){
                                        $.messager.show({
                                            title:'List Pendaftaran',
                                            msg:'Pembatalan pendaftaran no ' + rows[0].id_pendaftaran + ' berhasil.',
                                            showType:'show'
                                        });
                                        $('#dataPendaftaran').datagrid('reload');
                                    } else if (data=='0') {
                                        $.messager.show({
                                            title:'List Pendaftaran',
                                            msg:'Pembatalan pendaftaran no ' + rows[0].id_pendaftaran + ' gagal, pasien sudah mendapatkan tindakan.',
                                            showType:'show'
                                        });
                                    } else if (data=='2') {
                                        $.messager.show({
                                            title:'List Pendaftaran',
                                            msg:'Pembatalan pendaftaran no ' + rows[0].id_pendaftaran + ' gagal.',
                                            showType:'show'
                                        });
                                    }
                                }
                            })
                        } else {
                            $.messager.show({
                                title:'List Pendaftaran',
                                msg:'Data Pendaftaran belum dipilih.',
                                showType:'show'
                            });
                        }
                    }
            },{
                    id:'btnClose',
                    text:'Close Perawatan',
                    iconCls:'icon-logout',
                    handler:function(){
                        openClosePerawatan();                        
                    }
                }]
        });
    })
    
    function simpanClosePerawatan(){
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var noDftr = rows[0].id_pendaftaran;
        var dataString = "task=simpanClosePemeriksaan&id_pendaftaran=" + noDftr +
            "&kondisi_keluar=" + $("#kondisiKeluar").val() + 
            "&cara_keluar=" + $("#caraKeluar").val() + 
            "&keterangan_keluar=" + $("#keteranganKeluar").val() + 
            "&tgl_out=" + $("#tglKeluar").datebox("getValue");
        
        $('#simpan').linkbutton({
            disabled:true
        });
        
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
                    $('#dataPendaftaran').datagrid('reload');
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
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;
        var dataString = "task=getHarga&id_pendaftaran=" + fid +
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
    
    function layanan(){
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var noDftr = rows[0].id_pendaftaran;
        var idPasien = rows[0].id_pasien;
        var idRuang = rows[0].id_ruang;
                    
        if(idRuang=='18'){
            $("#idp").val(rows[0].id_pendaftaran);
            $("#rm_pas").val(idPasien);
            $("#nm_pas").val(rows[0].nama_pasien);
            $("#kl_pas").val(rows[0].kelas);
            $("#jn_pas").val(rows[0].tipe_pasien);
            $("#jk_pas").val(rows[0].kelamin);
            $("#ag_pas").val(rows[0].usia);

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
                pageList: [50,100,150,200],
                showFooter:true,
                idField:'id_detail_radiologi',
                frozenColumns:[[
                        {title:'ID',field:'id_detail_radiologi',width:50},
                    ]],
                columns:[[
                        {field:'radiologi',title:'Pemeriksaan',width:250},
                        {field:'cito',title:'CITO',width:50},
                        {field:'cito_bed',title:'CITO BED',width:50},
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
                    },{
                        id:'btndiskon',
                        text:'Diskon',
                        iconCls:'icon-bayar',
                        handler:function(){
                            $('#frmDiskon').form('load','json/data.php?task=getResumeTagihanPasien&id_pasien=' + idPasien);
                            openWinDiskon();
                        }
                    }]
            });
                            
            $('#dataBahan').datagrid({
                title:'Data Detail Pemeriksaan',
                height:150,
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
                        {field:'film',title:'Ukuran Film',width:50},
                        {field:'jumlah',title:'Jumlah',width:50}
                    ]],
                pagination:true,
                rownumbers:true
            });
                        
            openWinLayanan();
        }
    }
    
    function simpanDiskon(){
        var dataString = "task=simpanDiskonTindakan&id_pendaftaran=" + $("#idp").val() +
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
    
    function openWinDiskon(){
        frmDiskon.reset();
        $('#winDiskon').window('open');
        diskon.focus();
    }
    

    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPendaftaranRuang.php?task=cariPendaftaranRuang&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
    }
    
    function simpanPemeriksaan(){
        
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;
        
        var valCito = '';
        var cito_bed = '';
        
        if(cito.checked) valCito = '1';
        if(citoBed.checked) cito_bed = '1';
            
        var dataString = "task=simpanPemeriksaan&id_pendaftaran=" + fid +
            "&id_pasien=" + $("#rm_pas").val() +
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
                        $('#dataPemeriksaan').datagrid('reload');
                        $('#dataBahan').datagrid('reload');
                        radiologiField.DOMelem_input.select();
                        return false;
                    } else if(status=='WARNING') {
                        $.messager.show({
                            title:'Pemeriksaan Radiologi',
                            msg:'Pemeriksaan berhasil di simpan, dengan Catatan : <b>' + returnData[1] + '</b>',
                            showType:'show'
                        });
                        pemeriksaanRadiologi.reset();
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
    
    function fungsiRad() {
        setHarga(radiologiField.getSelectedValue());
        radiologiField.DOMelem_input.focus();
        $('#radiologiFieldId').val(radiologiField.getSelectedValue());
    }
    
    function keyRad(key) {
        if(key == 13){
            jumlahA.focus()
        }
    }
    
    //DHTML
    
    var radiologiField = new dhtmlXCombo("radiologiField","radiologiField",200);
    radiologiField.enableFilteringMode(true,"json/data.php?task=dRadiologi",true);
    radiologiField.attachEvent("onChange", fungsiRad);
    radiologiField.attachEvent("onKeyPressed", keyRad);
    
    
</script>