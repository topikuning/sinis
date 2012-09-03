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
                {field:'ruang',title:'Ruang',width:200},
                {field:'tindakan',title:'Tindakan',width:200},
                {field:'dokter',title:'Dokter',width:150},
                {field:'operator',title:'Pelaku Tindakan',width:100},
                {field:'tarif',title:'Tarif',width:120,align:'right'},
                {field:'jasa_sarana',title:'Jasa Sarana',width:120,align:'right'},
                {field:'jasa_pelayanan',title:'Jasa Pelayanan',width:120,align:'right'},
                {field:'jasa_unit_penghasil',title:'Jasa Unit Penghasil',width:120,align:'right'},
                {field:'jasa_direksi',title:'Jasa Direksi',width:120,align:'right'},
                {field:'jasa_remunerasi',title:'Jasa Remunerasi',width:120,align:'right'},
                {field:'jasa_dokter',title:'Jasa Dokter',width:120,align:'right'},
                {field:'jasa_perawat',title:'Jasa Perawat',width:120,align:'right'},
                {field:'pajakDokter',title:'Pajak Dokter',width:120,align:'right'},
                {field:'pajakPerawat',title:'Pajak Perawat',width:120,align:'right'}
            ]],
            pagination:true,
            rownumbers:true
        });

    });
    
    function loadJasa(){
        $('#dataJasa').datagrid({
            url:'json/dokter.php?task=getJasaTindakanMnj&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_pasien=' + $("#tipe_pasien").val() +
                '&ruang=' + $("#ruang").val() +
                '&dokter=' + $("#dokter").val()
        });
    }
    
</script>