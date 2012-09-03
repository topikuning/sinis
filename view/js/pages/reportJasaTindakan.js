<script>
    $(function(){
        $('#dataJasa').datagrid({
            title:'Laporan Jasa Tindakan',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'tgl_tindakan',
            sortOrder: 'asc',
            remoteSort: false,
            collapsible: true,
            showFooter:true,
            idField:'tgl_tindakan',
            frozenColumns:[[
                {title:'Tanggal',field:'tgl_tindakan',width:100},
                {title:'No RM',field:'id_pasien',width:50},
                {title:'Nama Pasien',field:'nama_pasien',width:150}
            ]],
            columns:[[
                {field:'tipe_pasien',title:'Tipe Pasien',width:80},
                {field:'tindakan',title:'Tindakan',width:150},
                {field:'dokter',title:'Dokter',width:100},
                {field:'operator',title:'Pelaku Tindakan',width:100},
                {field:'tarif',title:'Tarif',width:100,align:'right',
                    formatter:function(value){
                        if(value>0)
                            return formatCurrency(value);
                        else
                            return 'Rp. 0';
                    }
                },
                {field:'jasa_perawat',title:'Jasa Perawat',width:100,align:'right',
                    formatter:function(value){
                        if(value>0)
                            return formatCurrency(value);
                        else
                            return 'Rp. 0';
                    }
                },
                {field:'pajak',title:'Pajak',width:100,align:'right',
                    formatter:function(value){
                        if(value>0)
                            return formatCurrency(value);
                        else
                            return 'Rp. 0';
                    }
                }
            ]],
            pagination:true,
            rownumbers:true
        });

    });
    
    function loadJasa(){
        $('#dataJasa').datagrid({
            url:'json/tindakan.php?task=getJasaTindakanPerawat&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_pasien=' + $("#tipe_pasien").val()
        });
    }
    
</script>