<script>
    $(function(){
        $('#dataJasa').datagrid({
            title:'Laporan Jasa Analis',
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
                {title:'Tanggal',field:'tgl_tindakan',width:80},
                {title:'No RM',field:'id_pasien',width:50},
                {title:'Nama Pasien',field:'nama_pasien',width:150}
            ]],
            columns:[[
                {field:'tipe_pasien',title:'Tipe Pasien',width:100},
                {field:'ruang',title:'Asal Ruang',width:80},
                {field:'kelompok_lab',title:'Kelompok Pemeriksaan',width:150},
                {field:'laboratorium',title:'Type Pemeriksaan',width:100},
                {field:'dokter',title:'Dokter',width:150},
                {field:'operator',title:'Operator',width:100},
                {field:'tarif',title:'Tarif',width:100,align:'right',
                    formatter:function(value){
                        if(value>0)
                            return formatCurrency(value);
                        else
                            return 'Rp. 0';
                    }
                },
                {field:'jasa_perawat',title:'Jasa Analis',width:100,align:'right',
                    formatter:function(value){
                        if(value>0)
                            return formatCurrency(value);
                        else
                            return 'Rp. 0';
                    }
                },
                {field:'pajak',title:'Pajak Jasa',width:100,align:'right',
                    formatter:function(value){
                        if(value>0)
                            return formatCurrency(value);
                        else
                            return 'Rp. 0';
                    }
                }
            ]],
            groupField: 'kelompok_lab',
            view: groupview,
            groupFormatter:function(value, rows){
                return 'Kelompok Pemeriksaan : ' + value + ' (' + rows.length + ' Item(s))';
            },
            pagination:true,
            rownumbers:true
        });

    });
    
    function loadJasa(){
        $('#dataJasa').datagrid({
            url:'json/laboratorium.php?task=getJasaTindakanAnalis&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_pasien=' + $("#tipe_pasien").val()
        });
    }
    
</script>