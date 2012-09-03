<script>
    $(function(){        
        id_pasien.focus();
        		
        $('#dataListPasien').datagrid({
            title:'Data Pasien',
            height:300,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'id_pasien',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'id_pasien',
            frozenColumns:[[
                    {title:'No RM',field:'id_pasien',width:50,sortable:true}
                ]],
            columns:[[
                    {field:'no_pendaftaran',title:'Nomor Pendaftaran',width:150,sortable:true,hidden:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:150,sortable:true},
                    {field:'jns_kelamin',title:'L/P',width:30,sortable:true},
                    {field:'tmp_lahir',title:'Tempat Lahir',width:100,sortable:true},
                    {field:'tgl_lahir',title:'Tanggal Lahir',width:80,sortable:true},
                    {field:'alamat',title:'Alamat',width:80,sortable:true},
                    {field:'kelurahan',title:'Kelurahan',width:80,sortable:true},
                    {field:'kecamatan',title:'Kecamatan',width:80,sortable:true},
                    {field:'kota',title:'Kota',width:80,sortable:true},
                    {field:'marital',title:'Marital',width:80,sortable:true},
                    {field:'asuransi',title:'Tipe Asuransi',width:80,sortable:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:80,sortable:true}
                ]],
            onDblClickRow:function(){
                var rows = $('#dataListPasien').datagrid('getSelections');
                $("#id_pasien").val(rows[0].id_pasien);
                $("#id_pendaftaran").val(rows[0].no_pendaftaran);
                loadDataTagihanPasien();
                closeWinSearchPasien();
            },
            pagination:true,
            rownumbers:true
        });
    });
    
    function openWinSearchPasien(){
        $("#winSearchPasien").window('open');
        srcNamaPasien.focus();
    }
    
    function closeWinSearchPasien(){
        $("#winSearchPasien").window('close');
        id_pasien.focus();
    }
    
    function loadDataListPasien(){
        $("#dataListPasien").datagrid({
            url:'json/data.php?task=getDataListPasien&nama_pasien=' + $('#srcNamaPasien').val() + 
                '&alamat=' + $('#srcAlamat').val() +
                '&tgl_lahir=' + $('#srcTglLahir').datebox("getValue") +
                '&tgl_lahir_to=' + $('#srcTglLahirTo').datebox("getValue") +
                '&kecamatan=' + $('#srcKecamatan').val() +
                '&kelurahan=' + $('#srcKelurahan').val() +
                '&asuransi=' + $('#srcAsuransi').val() +
                '&tipe_pasien=' + $('#srcTipePasien').val()
        })
    }
    
    function getDataObatPasien(){
        if($("#id_pasien").val()!=""){
            $('#frmDtlPasien').form('load','json/data.php?task=cariDtlPasienTagih&id_pasien=' + id_pasien.value)
            $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
            $.getJSON("json/apotik.php", {task: 'getLaporanObatPasien',
                      id_pasien: $("#id_pasien").val(), ruang: $("#ruang").val(),status: $("#status").val()},
            function(data) {
                if(data.length>0){
                    $("#detailLaporan").html(data[0].display);
                }
            });        
        } else {
            $.messager.show({
                title:'Pasien',
                msg:'No RM belum diisi.',
                showType:'show'
            });
            id_pasien.focus();
        }
    }
</script>