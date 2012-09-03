<script>
    $(function(){
        $('#dataSupplier').datagrid({
            title:'Master Data Supplier',
            height:400,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            url:'json/apotik.php?task=getMasterSupplier',
            sortName: 'supplier',
            sortOrder: 'asc',
            remoteSort: false,
            idField:'supplier',
            columns:[[
                {title:'ID',field:'id_supplier',width:30,sortable:true,hidden:true},
                {field:'supplier',title:'Supplier',width:350,sortable:true}
            ]],
            toolbar:[{
                id:'btnTambah',
                text:'Tambah Data',
                iconCls:'icon-add',
                handler:function(){
                    openWinTambahSupplier();
                }
            },{
                id:'btnHapus',
                text:'Hapus Data',
                iconCls:'icon-remove',
                handler:function(){
                    var row = $('#dataSupplier').datagrid('getSelected');
                    $.messager.confirm('Master Supplier', 'Anda yakin menghapus data ini?', function(r){
                        if (r){
                            $.ajax({  
                                type: "GET",  
                                url: "json/apotik.php",  
                                data: "task=hapusMasterSupplier&id_supplier=" + row.id_supplier,
                                success: function(data) {
                                    if(data!='0'){
                                        $.messager.show({
                                            title:'Master Supplier',
                                            msg:'Master Supplier berhasil dihapus.',
                                            showType:'show'
                                        });
                                        $("#dataSupplier").datagrid("reload");
                                        return false;
                                    } else {
                                        $.messager.show({
                                            title:'Master Supplier',
                                            msg:'Gagal menghapus Master Supplier.',
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
                var row = $('#dataSupplier').datagrid('getSelected');
                $('#frmTambahSupplier').form('load','json/apotik.php?task=getDetailMasterSupplier&id_supplier=' + row.id_supplier);
                openWinTambahSupplier();
            },
            pagination:true,
            rownumbers:true
        });
    });
    
    function simpanSupplier(){
        var dataString = "task=simpanMasterSupplier&id_supplier=" + $("#id_supplier").val() +
                         "&supplier=" + $("#supplier").val();

        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    $.messager.show({
                        title:'Master Supplier',
                        msg:'Master Supplier berhasil disimpan.',
                        showType:'show'
                    });
                    $("#dataSupplier").datagrid("reload");
                    closeWinTambahSupplier();
                    return false;
                } else {
                    $.messager.show({
                        title:'Master Supplier',
                        msg:'Gagal menyimpan Master Supplier.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    };
    
    function loadDataSupplier(){
        $('#dataSupplier').datagrid({
            url:'json/apotik.php?task=getMasterSupplier&nama_supplier=' + $('#nama_supplier').val()
        });
    }

    function openWinTambahSupplier(){
        $('#winTambahSupplier').window('open');
        supplier.focus();
    }
    
    function closeWinTambahSupplier(){
        frmTambahSupplier.reset();
        $("#winTambahSupplier").window('close');
    }
    
</script>