<script>
    $(function(){      
        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPendaftaranRuang.php?task=cariPendaftaranRuang2&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
            sortName: 'id_pendaftaran',
            sortOrder: 'asc',
            remoteSort: false,
            idField:'id_pendaftaran',
            frozenColumns:[[
                    {title:'ID',field:'id_pendaftaran',width:50,sortable:true,hidden:true},
                    {field:'id_pasien',title:'No RM',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'id_tipe_pasien',title:'ID Pasien',width:120,hidden:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:120},
                    {field:'kelas',title:'Kelas',width:80},
                    {field:'id_kelas_pendaftaran',title:'ID Kelas',width:120,hidden:true},
                    {field:'id_tipe_pendaftaran',title:'ID Tipe Pendaftaran',width:120,hidden:true},
                    {field:'asal_ruang',title:'Ruang Asal',width:100},
                    {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:100,sortable:true},
                    {field:'id_ruang',title:'ID Ruang',width:120,hidden:true},
                    {field:'ruang',title:'Ruang',width:120},
                    {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:80},
                    {field:'jam_daftar',title:'Jam Daftar',width:80},
                    {field:'asal_rujukan',title:'Asal Rujukan',width:120},
                    {field:'perujuk',title:'Perujuk',width:120}
                ]],
            pagination:true,
            pageList:[50,100,200,400],
            rownumbers:true,
            onDblClickRow:function(){
                var row = $('#dataPendaftaran').datagrid('getSelected');
                $.messager.confirm('Konsul', 'Apakah anda yakin akan mendaftarkan ' + row.nama_pasien + ' dengan RM : ' + row.id_pasien, function(r){
                    if (r){
                        simpanPendaftaran();
                    } else {
                        $.messager.show({
                            title:'Konsul',
                            msg:'<b> Batal Konsul </b>',
                            showType:'show'
                        });
                        $('#dataPendaftaran').datagrid('reload');
                        return false;
                    }
                });
            }
        });
    });
    
    function simpanPendaftaran(){
        var row = $('#dataPendaftaran').datagrid('getSelected');
        var idKelas = 0;
        if ((row.id_ruang == 20 || row.id_ruang == 31 || row.id_ruang == 32) && row.id_kelas_pendaftaran != 6) {
            var idKelas = 2;
        } else if (row.id_ruang == 34 || row.id_ruang == 51 || row.id_ruang == 52 || row.id_ruang == 53) {
            var idKelas = 1;
        } else {
            var idKelas = row.id_kelas_pendaftaran;
        }
        var dataString = "task=simpanPendaftaranKonsul&id_tipe_pendaftaran=7" +
            "&id_pendaftaran=" + row.id_pendaftaran +
            "&id_ruang_asal=" + row.id_ruang + 
            "&id_kelas=" + idKelas + 
            "&biaya_pendaftaran=0" + 
            "&id_pasien=" + row.id_pasien;

        $.ajax({  
            type: "POST",  
            url: "json/pendaftaran.php",  
            data: dataString,
            success: function(data) {
                var returnData = data.split(":");
                var status = returnData[0];
                if(status=='LOGIN'){
                    alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                    window.location.reload();
                } else if(status=='TRUE'){
                    $.messager.show({
                        title:'Konsul',
                        msg:'Pendaftaran berhasil disimpan dengan No ID : <b>' + returnData[1] + '</b>, No Antrian : <b>' + returnData[2] + '</b> di Ruang : <b>' + returnData[3] + '</b>.',
                        showType:'show'
                    });
                    $('#dataPendaftaran').datagrid('reload');
                    if($("#level").val() == 17){
                        window.location='index.php?page=pmrlab&fid=' + returnData[1];
                    } else if($("#level").val() == 18){
                        window.location='index.php?page=pmrrdlg&fid=' + returnData[1];
                    } else if($("#level").val() == 22){
                        window.location='index.php?page=tndknibs&fid=' + returnData[1];
                    }
                    
                    return false;
                } else if(status=='WARNING') {
                    $.messager.show({
                        title:'Konsul',
                        msg:'Pendaftaran berhasil di simpan, dengan Catatan : <b>' + returnData[1] + '</b>',
                        showType:'show'
                    });
                    $('#dataPendaftaran').datagrid('reload');
                    return false;
                } else if(status=='FALSE') {
                    $.messager.show({
                        title:'Konsul',
                        msg:'Gagal menyimpan pendaftaran.<b>' + returnData[1] + '</b>',
                        showType:'show'
                    });
                    $('#dataPendaftaran').datagrid('reload');
                    return false;
                }
            }  
        });  
        return false;
    };

    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPendaftaranRuang.php?task=cariPendaftaranRuang2&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
    }
    
    
</script>