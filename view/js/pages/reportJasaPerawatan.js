<script>
    $(function(){
        $('#dataJasa').datagrid({
            title:'Laporan Jasa Perawatan',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'tgl_keluar',
            sortOrder: 'asc',
            remoteSort: false,
            collapsible: true,
            showFooter:true,
            idField:'tgl_visit',
            frozenColumns:[[
                {title:'Tanggal',field:'tgl_keluar',width:100},
                {title:'No RM',field:'id_pasien',width:50},
                {title:'Nama Pasien',field:'nama_pasien',width:200}
            ]],
            columns:[[
                {field:'tipe_pasien',title:'Tipe Pasien',width:80},
                {field:'ruang',title:'Ruang',width:80},
                {field:'kamar',title:'Kamar',width:80},
                {field:'lama_penggunaan',title:'Lama Penggunaan',width:80,align:'right'},
                {field:'tarif',title:'Tarif',width:120,align:'right'},
                {field:'jasa_perawat',title:'Jasa Perawat',width:120,align:'right'},
                {field:'pajak',title:'Pajak',width:120,align:'right'},
                {field:'jumlah',title:'Jumlah',width:120,align:'right'}
            ]],
            pagination:true,
            rownumbers:true
        });

    });
    
    function loadJasa(){
        $('#dataJasa').datagrid({
            url:'json/dokter.php?task=getJasaPerawatan&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_pasien=' + $("#tipe_pasien").val()
        });
    }
    
</script>