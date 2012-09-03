<script>
    $(function(){

        $('#dataObat').datagrid({
            title:'Data Stock Barang',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible: true,
            url:'json/obat.php?task=cariBarang&id_barang=&jenis_barang=',
            sortName: 'kode_obat',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'kode_obat',
            frozenColumns:[[
                    {title:'ID',field:'id_barang',width:80,sortable:true,hidden:true},
                    {field:'nama_barang',title:'Nama Barang',width:250,sortable:true}
                ]],
            columns:[[
                    {field:'jenis_barang',title:'Jenis Barang',width:100,sortable:true},
                    {field:'satuan',title:'Satuan',width:50,sortable:true},
                    {field:'jumlah_stock',title:'Jumlah Stock',align:'right',width:50,sortable:true},
                    {field:'stock_limit',title:'Stock Limit',align:'right',width:50,sortable:true}
                ]],
            pagination:true,
            rownumbers:true
        });

        $('#dataDistribusiObat').datagrid({
            title:'Daftar Kiriman Barang',
            height:200,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible: true,
            url:'json/obat.php?task=cariDistribusiBarang',
            sortName: 'id_obat',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'id_obat',
            frozenColumns:[[
                    {title:'ID',field:'id_distribusi_barang',width:80,sortable:true,hidden:true},
                    {title:'ID',field:'id_barang',width:80,sortable:true,hidden:true},
                    {field:'nama_barang',title:'Nama Barang',width:250,sortable:true}
                ]],
            columns:[[
                    {field:'jumlah_stock',title:'Jumlah',width:50,sortable:true}
                ]],
            onDblClickRow:function(){
                var rows = $('#dataDistribusiObat').datagrid('getSelections');
                var id_distribusi_barang = rows[0].id_distribusi_barang;
                $("#frmSimpanDistBarang").form("load",'json/obat.php?task=getDetailDistBarang&id_distribusi_barang=' + id_distribusi_barang);
                openWinDistBarang();
            },
            pagination:true,
            rownumbers:true
        });

    });
    
    function simpanDistBarang(){
        var dataString = "task=simpanDistBarangRuang&id_barang=" + $("#id_barang").val() +
            "&id_distribusi_barang=" + $("#id_distribusi_barang").val() + 
            "&jumlah_stock=" + $("#jumlah").val();

        var bvalid = true;

        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: dataString,  
            success: function(data) {
                if(data=='1'){
                    $.messager.show({
                        title:'Distribusi Barang',
                        msg:'Distribusi Barang berhasil disimpan.',
                        showType:'show'
                    });
                    $("#dataObat").datagrid("reload");
                    $("#dataDistribusiObat").datagrid("reload");
                    closeWinDistBarang();
                    return false;
                } else if(data=='2'){
                    $.messager.show({
                        title:'Distribusi Barang',
                        msg:'Distribusi Barang berhasil disimpan. Data Distribusi Gagal di update.',
                        showType:'show'
                    });
                    frmSimpanDistBarang.reset();
                    return false;
                } else if (data=='0'){
                    $.messager.show({
                        title:'Distribusi Barang',
                        msg:'Distribusi Barang gagal disimpan.',
                        showType:'show'
                    });
                    frmSimpanDistBarang.reset();
                    closeWinDistBarang();
                    return false;
                }
            }  
        });  
        return false;
    };

    function cariStockBarangRuang(){
        $('#dataObat').datagrid({
            url:'json/obat.php?task=cariBarang&id_barang=' + $("#namaBarangId").val() +
                '&jenis_barang=' + $("#jenis_barang").val()
        });
    }
	
    function openWinDistBarang(){
        $('#winSimpanDistBarang').window('open');
    }
    
    function closeWinDistBarang(){
        frmSimpanDistBarang.reset();
        $("#winSimpanDistBarang").window('close');
    }
    
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate: ''
    });
</script>