<script>
    $(function(){

        $('#dataPendaftaran').datagrid({
            title:'Pasien RSUD dr. Soegiri',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPendaftaran.php?task=cariPasienKeluar&no_rm=' + $('#no_rm').val() + '&pasien=' + $('#pasien').val() +  '&alamat=' + $('#alamat').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue") + '&tipe_pasien=' + $('#tipe_pasien').val() + '&status=2' + '&id_ruang=' + $('#id_ruang').val(),
            sortName: 'id_pendaftaran',
            sortOrder: 'asc',
            remoteSort: false,
            chace:false,
            idField:'id_pendaftaran',
            frozenColumns:[[
                    {field:'id_pendaftaran',title:'ID Daftar',width:50,sortable:true},
                    {field:'id_pasien',title:'RM Px',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Px',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'id_tipe_pendaftaran',title:'ID Tipe Pendaftaran',width:120,sortable:true,hidden:true},
                    {field:'alamat',title:'Alamat Px',width:150},
                    {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:100,sortable:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:110,sortable:true},
                    {field:'kelas',title:'Kelas',width:100,sortable:true},
                    {field:'ruang',title:'Ruang',width:120,sortable:true},
                    {field:'tgl_pendaftaran_view',title:'Tgl Keluar',width:80},
                    {field:'tgl_pendaftaran',title:'Tanggal Keluar',width:80,hidden:true},
                    {field:'jam_daftar',title:'Jam Keluar',width:80}
                ]],
            rownumbers:true,
            onDblClickRow:function(){
                var row = $('#dataPendaftaran').datagrid('getSelected');
                $.messager.confirm('Buka', 'Apakah anda yakin akan membuka ulang pendaftaran ' + row.nama_pasien + ' dengan RM : ' + row.id_pasien + ' di Ruang ' + row.ruang, function(r){
                    if (r){
                        bukaUlang();
                    } else {
                        $.messager.show({
                            title:'Konsul',
                            msg:'<b> Batal Membuka Ulang </b>',
                            showType:'show'
                        });
                        $('#dataPendaftaran').datagrid('reload');
                        return false;
                    }
                });
            }
        });
    });
    
    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPendaftaran.php?task=cariPasienKeluar&no_rm=' + $('#no_rm').val() + '&pasien=' + $('#pasien').val() + '&alamat=' + $('#alamat').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue") + '&tipe_pasien=' + $('#tipe_pasien').val() + '&status=' + $('#status').val() + '&id_ruang=' + $('#id_ruang').val()
        });
    }
    
    function bukaUlang(){
        
        var row = $('#dataPendaftaran').datagrid('getSelected');
        var dataString = "task=bukaUlang&id_pendaftaran=" + row.id_pendaftaran;

        $.ajax({  
            type: "POST",  
            url: "json/pendaftaran.php",  
            data: dataString,
            success: function(data) {
                var returnData = data.split(":");
                var status = returnData[0];
                if(status=='TRUE'){
                    $.messager.show({
                        title:'Buka Ulang',
                        msg:'<b> Pembukaan ulang ' + returnData[1] + '</b>',
                        showType:'show'
                    });
                    $('#dataPendaftaran').datagrid('reload');
                    return false;
                } else if(status=='FALSE') {
                    $.messager.show({
                        title:'Buka Ulang',
                        msg:'<b> Pembukaan ulang ' + returnData[1] + '</b>',
                        showType:'show'
                    });
                    $('#dataPendaftaran').datagrid('reload');
                    return false;
                }
            }  
        });  
        return false;
    };
    
</script>