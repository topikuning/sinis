<script>
    $(function(){

        $('#dataPendaftaran').datagrid({
            title:'Pasien Pindah Ruang',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPendaftaran.php?task=cariPasienPindah&no_rm=' + $('#no_rm').val() + '&pasien=' + $('#pasien').val() +  '&alamat=' + $('#alamat').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue") + '&id_ruang=' + $('#id_ruang').val(),
            sortName: 'id_pendaftaran',
            sortOrder: 'asc',
            remoteSort: false,
            chace:false,
            idField:'id_pendaftaran',
            frozenColumns:[[
                    {title:'ID',field:'id_pendaftaran',width:80,sortable:true,hidden:true},
                    {title:'id_penggunaan_kamar',field:'id_penggunaan_kamar',width:80,sortable:true,hidden:true},
                    {field:'id_pasien',title:'No RM',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'tipe_pasien',title:'Tipe Pasien',width:100},
                    {field:'ruang',title:'Ruang',width:200,hidden:true},
                    {field:'ruang_asal',title:'Ruang Asal',width:100},
                    {field:'kamar',title:'Kamar',width:80},
                    {field:'bed',title:'Bed',width:100},
                    {field:'kelas',title:'Kelas',width:60},
                    {field:'lama_perawatan',title:'Lama Perawatan',width:50,align:'center'},
                    {field:'tgl_pendaftaran_view',title:'Tanggal Pendaftaran',width:80},
                    {field:'jam_daftar',title:'Jam Daftar',width:60},
                    {field:'tarif',title:'Tarif',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }},
                    {field:'status',title:'Status',width:100}
                ]],
            rownumbers:true,
            onDblClickRow:function(){
                var row = $('#dataPendaftaran').datagrid('getSelected');
                $.messager.confirm('Buka', 'Apakah anda yakin akan membuka ulang ' + row.nama_pasien + ' dengan RM : ' + row.id_pasien + ' di Ruang ' + row.ruang, function(r){
                    if (r){
                        bukaPindah();
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
            url:'json/listPendaftaran.php?task=cariPasienPindah&no_rm=' + $('#no_rm').val() + '&pasien=' + $('#pasien').val() + '&alamat=' + $('#alamat').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue") + '&id_ruang=' + $('#id_ruang').val()
        });
    }
    
    function bukaPindah(){
        
        var row = $('#dataPendaftaran').datagrid('getSelected');
        var dataString = "task=bukaPindah&id_penggunaan=" + row.id_penggunaan_kamar;

        $.ajax({  
            type: "POST",  
            url: "json/pendaftaran.php",  
            data: dataString,
            success: function(data) {
                if(data=='1'){
                    $.messager.show({
                        title:'Buka Ulang',
                        msg:'<b> Pembukaan Ulang Berhasil</b>',
                        showType:'show'
                    });
                    $('#dataPendaftaran').datagrid('reload');
                    return false;
                } else if(data=='0') {
                    $.messager.show({
                        title:'Buka Ulang',
                        msg:'<b> Pembukaan Ulang Gagal</b>',
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