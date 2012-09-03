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
            showFooter:true,
            idField:'tgl_daftar',
            frozenColumns:[[
                {title:'Tanggal',field:'tgl_daftar',width:100},
                {title:'No RM',field:'id_pasien',width:50},
                {title:'Nama Pasien',field:'nama_pasien',width:200}
            ]],
            columns:[[
                {field:'tipe_pasien',title:'Tipe Pasien',width:80},
                {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:80},
                {field:'ruang',title:'Ruang',width:80},
                {field:'dokter',title:'Dokter',width:150},
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
            pageList:[25,50,100,200],
            rownumbers:true
        });

    });
    
    function loadJasa(){
        $('#dataJasa').datagrid({
            url:'json/dokter.php?task=getJasaPendaftaranMnj&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_pasien=' + $("#tipe_pasien").val() +
                '&dokter=' + $("#dokter").val()
        });
    }
    
</script>