<script>
    $(function(){
        $('#dataObat').datagrid({
            title:'Master Data Obat',
            height:400,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            url:'json/obat.php?task=getMasterObat&nama_obat=' + $('#nama_obat').val(),
            sortName: 'nama_obat',
            sortOrder: 'asc',
            remoteSort: false,
            chace:false,
            idField:'nama_obat',
            columns:[[
                {title:'ID',field:'id_obat',width:30,sortable:true},
                {title:'Kode Obat',field:'kode_obat',width:100,sortable:true},
                {field:'nama_obat',title:'Nama Obat',width:350,sortable:true}
            ]],
            toolbar:[{
                id:'btnTambah',
                text:'Tambah Data',
                iconCls:'icon-add',
                handler:function(){
                    openWinTambahObat();
                }
            },{
                id:'btnHapus',
                text:'Hapus Data',
                iconCls:'icon-remove',
                handler:function(){
                    var row = $('#dataObat').datagrid('getSelected');
                    $.messager.confirm('Master Obat', 'Anda yakin menghapus data ini?', function(r){
                        if (r){
                            $.ajax({  
                                type: "GET",  
                                url: "json/obat.php",  
                                data: "task=hapusMasterObat&id_obat=" + row.id_obat,  
                                success: function(data) {
                                    if(data!='0'){
                                        $.messager.show({
                                            title:'Master Obat',
                                            msg:'Master Obat berhasil dihapus.',
                                            showType:'show'
                                        });
                                        $("#dataObat").datagrid("reload");
                                        closeWinTambahObat();
                                        return false;
                                    } else {
                                        $.messager.show({
                                            title:'Master Obat',
                                            msg:'Gagal menghapus Master Obat.',
                                            showType:'show'
                                        });
                                        return false;
                                    }
                                }  
                            });  
                        }
                    });
                }
            }],
            onDblClickRow:function(){
                var row = $('#dataObat').datagrid('getSelected');
                $('#frmTambahObat').form('load','json/obat.php?task=getDetailMasterObat&id_obat=' + row.id_obat);
                openWinTambahObat();
            },
            pagination:true,
            rownumbers:true
        });
    });
    
    function simpanObat(){
        var dataString = "task=simpanMasterObat&kode_obat=" + $("#kode_obat").val() +
                         "&nama_obat=" + $("#obat").val() +
                         "&id_obat=" + $("#id_obat").val();

        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: dataString,  
            success: function(data) {
                var returnData = data.split(":");
                if(returnData[0]=='DUPLIKAT'){
                    $.messager.show({
                        title:'Master Obat',
                        msg:'Kode Obat sudah ada. Dengan Nama Obat <b>' + returnData[1] +'</b>',
                        showType:'show'
                    });
                    return false;
                } else if(returnData[0]!='0'){
                    $.messager.show({
                        title:'Master Obat',
                        msg:'Master Obat berhasil disimpan.',
                        showType:'show'
                    });
                    $("#dataObat").datagrid("reload");
                    closeWinTambahObat();
                    return false;
                } else {
                    $.messager.show({
                        title:'Master Obat',
                        msg:'Gagal menyimpan Master Obat.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    };
    
    function loadDataObat(){
        $('#dataObat').datagrid({
            url:'json/obat.php?task=getMasterObat&nama_obat=' + $('#nama_obat').val() + '&k_obat=' + $('#k_obat').val()
        });
    }

    function openWinTambahObat(){
        $('#winTambahObat').window('open');
        kode_obat.focus();
    }
    
    function closeWinTambahObat(){
        frmTambahObat.reset();
        $("#winTambahObat").window('close');
    }
    
</script>