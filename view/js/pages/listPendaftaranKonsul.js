<script>
    $(function(){

        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPendaftaranRuang.php?task=cariKonsulRuang&id_pasien=' + $('#id_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
            sortName: 'no_antrian',
            sortOrder: 'asc',
            remoteSort: false,
            showFooter:true,
            idField:'id_pendaftaran',
            frozenColumns:[[
                {field:'no_antrian',title:'No Antrian',width:50},
                {title:'ID',field:'id_pendaftaran',width:50,sortable:true},
                {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true}
            ]],
            columns:[[
                {field:'id_tipe_pendaftaran',title:'ID Tipe Pendaftaran',width:120,hidden:true},
                {field:'asal_ruang',title:'Ruang Asal',width:100},
                {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:100},
                {field:'id_ruang',title:'ID Ruang',width:120,hidden:true},
                {field:'ruang',title:'Ruang',width:120},
                {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:80},
                {field:'jam_daftar',title:'Jam Daftar',width:80},
                {field:'status',title:'Status',width:80,
                    formatter:function(value){
                        if(value=='1')
                            return 'Antrian';
                        else if (value=='0')
                            return 'Perawatan';
                        else if (value=='2')
                            return 'Close';
                    }
                }
            ]],
			toolbar:[{
                    id:'btndel',
                    text:'Batalkan',
                    iconCls:'icon-remove',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            var rows = $('#dataPendaftaran').datagrid('getSelections');
                            var noDaftar = "task=batalDaftar&id_pendaftaran=" + rows[0].id_pendaftaran;
                            if(rows[0].status==0){
                                $.messager.show({
                                    title:'List Pendaftaran',
                                    msg:'Pasien dalam perawatan.',
                                    showType:'show'
                                });                                
                            } else if(rows[0].status==2){
                                $.messager.show({
                                    title:'List Pendaftaran',
                                    msg:'Pasien sudah keluar.',
                                    showType:'show'
                                });                                
                            } else {
                                var tanggal = getToday();
                                if(rows[0].tgl_pendaftaran==tanggal) {
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

                                                $('#dataPendaftaran').datagrid('deleteRow', index);
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
                                }
                            }
                        } else {
                            $.messager.show({
                                title:'List Pendaftaran',
                                msg:'Data Pendaftaran belum dipilih.',
                                showType:'show'
                            });
                        }
                    }
                }],
            pagination:true,
            rownumbers:true
        });
    });
    
    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPendaftaranRuang.php?task=cariKonsulRuang&id_pasien=' + $('#id_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
    }
</script>