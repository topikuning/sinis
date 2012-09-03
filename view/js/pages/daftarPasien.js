<script>
    $(function(){

        $('#dataPendaftaran').datagrid({
            title:'Pasien RSUD dr. Soegiri',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPendaftaran.php?task=cariPendaftaranInformasi&no_rm=' + $('#no_rm').val() + '&pasien=' + $('#pasien').val() +  '&alamat=' + $('#alamat').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue") + '&tipe_pasien=' + $('#tipe_pasien').val() + '&status=2' + '&closed=1',
            sortName: 'id_pendaftaran',
            sortOrder: 'asc',
            remoteSort: false,
            chace:false,
            idField:'id_pendaftaran',
            frozenColumns:[[
                    {field:'id_pendaftaran',title:'No Kunj.',width:50,sortable:true},
                    {field:'id_pasien',title:'RM Px',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Px',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'id_tipe_pendaftaran',title:'ID Tipe Pendaftaran',width:120,sortable:true,hidden:true},
                    {field:'alamat',title:'Alamat Px',width:150},
                    {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:100,sortable:true,hidden:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:110,sortable:true},
                    {field:'kelas',title:'Kelas',width:100,sortable:true},
                    {field:'ruang',title:'Ruang',width:120,sortable:true},
                    {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:80},
                    {field:'jam_daftar',title:'Jam Daftar',width:80},
                    {field:'tgl_keluar',title:'Tanggal Keluar',width:80},
                    {field:'status',title:'Status',width:80}
                ]],
            pagination:true,
            rownumbers:true        });
    });
    
    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPendaftaran.php?task=cariPendaftaranInformasi&no_rm=' + $('#no_rm').val() + '&pasien=' + $('#pasien').val() + '&alamat=' + $('#alamat').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue") + '&tipe_pasien=' + $('#tipe_pasien').val() + '&status=' + $('#status').val() + '&closed=' + $('#closed').val() + '&ruangane=' + $('#ruangane').val()
        });
    }
</script>