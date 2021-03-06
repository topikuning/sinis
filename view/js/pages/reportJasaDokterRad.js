<script>
    $(function(){
        $('#dataJasa').datagrid({
            title:'Laporan Jasa Radiologi',
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
                {title:'Nama Pasien',field:'nama_pasien',width:180}
            ]],
            columns:[[
                {field:'tipe_pasien',title:'Tipe Pasien',width:80},
                {field:'ruang',title:'Asal Ruang',width:80},
                {field:'kelompok_rad',title:'Kelompok Pemeriksaan',width:150},
                {field:'radiologi',title:'Type Pemeriksaan',width:100},
                {field:'dokter',title:'Dokter',width:150},
                {field:'operator',title:'Operator',width:100},
                {field:'tarif',title:'Tarif',width:120,align:'right'},
                {field:'jasa_dokter',title:'Jasa Dokter',width:120,align:'right'},
                {field:'pajak',title:'Pajak',width:120,align:'right'}
            ]],
            groupField: 'kelompok_rad',
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
            url:'json/dokter.php?task=getJasaTindakanDokterRadiologi&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_pasien=' + $("#tipe_pasien").val()
        });}
</script>