<script>
    $(function(){
        $('#dataJasa').datagrid({
            title:'Laporan Jasa Bedah',
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
                {field:'tindakan_medis',title:'Tindakan',width:200},
                {field:'dokter_operator',title:'Dokter Operator',width:150},
                {field:'dokter_anastesi',title:'Dokter Anastesi',width:150},
                {field:'tarif',title:'Tarif',width:120,align:'right'},
                {field:'tim_operator',title:'Operator',width:120,align:'right'},
                {field:'ass_tim_operator',title:'Ass. Operator',width:120,align:'right'},
                {field:'tim_anastesi',title:'Anastesi',width:120,align:'right'},
                {field:'ass_tim_anastesi',title:'Ass. Anastesi',width:120,align:'right'}
            ]],
            pagination:true,
            rownumbers:true
        });

    });
    
    function loadJasa(){
        $('#dataJasa').datagrid({
            url:'json/dokter.php?task=getJasaTindakanDokterBedah&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_pasien=' + $("#tipe_pasien").val()
        });}
</script>