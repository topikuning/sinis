<script>
    $(function(){
        $('#dataJasa').datagrid({
            title:'Laporan Jasa Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'tgl_daftar',
            sortOrder: 'asc',
            remoteSort: false,
            collapsible: true,
            pageList: [200,400,800,1000],
            showFooter:true,
            idField:'tgl_daftar',
            frozenColumns:[[
                {title:'Tanggal',field:'tgl_daftar',width:75},
                {title:'No RM',field:'id_pasien',width:50},
                {title:'Nama Pasien',field:'nama_pasien',width:150}
            ]],
            columns:[[
                {field:'tipe_pasien',title:'Tipe Pasien',width:80},
                {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:80,sortable:true},
                {field:'ruang',title:'Ruang',width:80},
                {field:'dokter',title:'Dokter',width:150},
                {field:'tarif',title:'Tarif',width:100,align:'right'},
                {field:'jasa_perawat',title:'Jasa Perawat',width:100,align:'right'},
                {field:'pajak',title:'Pajak',width:85,align:'right'},
                {field:'total',title:'Total',width:100,align:'right'}
            ]],
            pagination:true,
            rownumbers:true
        });

    });
    
    function loadJasa(){
        $('#dataJasa').datagrid({
            url:'json/dokter.php?task=getJasaPendaftaran&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_pasien=' + $("#tipe_pasien").val()
        });
    }
</script>