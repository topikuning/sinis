    <script>
    $(function(){

    $('#dataPendaftaran').datagrid({
        title:'Data List Pendaftaran',
        height:350,
        singleSelect:true,
        nowrap: false,
        remoteSort: false,
        striped: true,
        url:'json/listPendaftaranRuang.php?task=cariPendaftaranRuang&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
        showFooter:true,
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
                {field:'perawatan',title:'Status',width:1,hidden:true},
                {field:'usia',title:'Usia',width:1,hidden:true},
                {field:'dokter',title:'Dokter',width:1,hidden:true},
                {field:'kelamin',title:'Kelamin',width:1,hidden:true}
            ]],
        pagination:true,
        rownumbers:true,
        onDblClickRow:function(){
            var rows = $('#dataPendaftaran').datagrid('getSelections');
            var noDftr = rows[0].id_pendaftaran;
            window.open('index.php?page=pmrlab&fid=' + noDftr);
        },
        toolbar:[{
                id:'btnLayanan',
                text:'Layanan',
                iconCls:'icon-openrm',
                handler:function(){
                    var rows = $('#dataPendaftaran').datagrid('getSelections');
                    var noDftr = rows[0].id_pendaftaran;
                    if(rows[0].perawatan != 2){
                        window.open('index.php?page=pmrlab&fid=' + noDftr);
                    } else {
                        window.open('index.php?page=labPul&fid=' + noDftr);
                    }
                }
            },
            {
                id:'btnLab',
                text:'Laborat',
                iconCls:'icon-lab',
                handler:function(){
                    var row = $('#dataPendaftaran').datagrid('getSelected');
                    var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                    if(index>=0){
                        window.open ("../../reports/Hasil_Labsmry.php?so1_RM_Px2E=%3D&sv1_RM_Px2E="+ row.id_pasien + "&Submit=Search");
                    } else {
                        window.open ("../../reports/Hasil_Labsmry.php");
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
                    var row = $('#dataPendaftaran').datagrid('getSelected');
                    if(row.perawatan != 2){
                        openClosePerawatan();
                    } else {
                        $.messager.show({
                            title:'Status',
                            msg:'Pasien Sudah Dinyatakan Pulang.',
                            showType:'show'
                        });
                    }
                }
            }]
    });
});
    
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
                $('#dataPendaftaran').datagrid('reload');
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

function openWinDiskon(){
    frmDiskon.reset();
    $('#winDiskon').window('open');
    diskon.focus();
}

function loadDataPendaftaran(){
    $('#dataPendaftaran').datagrid({
        url:'json/listPendaftaranRuang.php?task=cariPendaftaranRuang&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue") + '&perawatan=' + $('#perawatan').val()
    });
}
    
    </script>