<script>
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate:''
    });
    
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
            toolbar:[{
                    id:'btnadd',
                    text:'Tambah Stock',
                    iconCls:'icon-add',
                    handler:function(){
                        openWinTambahStock();
                    }
                }],
            onDblClickRow:function(){
                var rows = $('#dataObat').datagrid('getSelections');
                var id_barang = rows[0].id_barang;
                $('#id_barang').val(id_barang);
                $.getJSON("json/data.php", {task: 'listRuangDistribusiBarang'},
                function(data) {
                    var opt = '';
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {
                            opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                        };
                        $("#ruangTujuan").html(opt);
                    }
                });
                openWinDistObat();
            },
            pagination:true,
            rownumbers:true
        });

    });
    
    function simpanDistBarang(){
        var dataString = "task=simpanDistBarang&id_barang=" + $("#id_barang").val() +
            "&ruangTujuan=" + $("#ruangTujuan").val() + 
            "&jmlBarang=" + $("#jmlBarangDist").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#ruangTujuan").val(), 'Ruang Tujuan');
        bvalid = bvalid && checkSelect($("#jmlBarangDist").val(), 'Jumlah');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/obat.php",  
                data: dataString,  
                success: function(data) {
                    var returnData = data.split(":");
                    var status = returnData[0];
                    if(status=='TRUE'){
                        $.messager.show({
                            title:'Distribusi Barang',
                            msg:'Distribusi Barang berhasil disimpan.',
                            showType:'show'
                        });
                        $("#dataObat").datagrid("reload");
                        closeWinDistObat();
                        return false;
                    } else if(status=='WARNING'){
                        $.messager.show({
                            title:'Distribusi Barang',
                            msg:'Distribusi Barang gagal disimpan. ' + returnData[1],
                            showType:'show'
                        });
                        frmDistObat.reset();
                        return false;
                    } else if (status=='ERROR'){
                        $.messager.show({
                            title:'Distribusi Barang',
                            msg:'Distribusi Barang gagal disimpan. ' + returnData[1],
                            showType:'show'
                        });
                        frmDistObat.reset();
                        closeWinDistObat();
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function simpanStockBarang(){
        var dataString = "task=simpanStockBarang&id_barang=" + $("#namaBarangId").val() +
            "&jmlBarang=" + $("#jmlBarang").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#namaBarangId").val(), 'Nama Barang');
        bvalid = bvalid && checkSelect($("#jmlBarang").val(), 'Jumlah');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/obat.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='1'){
                        $.messager.show({
                            title:'Stock Barang',
                            msg:'Stock Barang berhasil disimpan.',
                            showType:'show'
                        });
                        $("#dataObat").datagrid("reload");
                        frmAddStock.reset();
                        namaBarang.focus();
                        return false;
                    } else {
                        $.messager.show({
                            title:'Stock Barang',
                            msg:'Stock Barang gagal disimpan. ' + returnData[1],
                            showType:'show'
                        });
                        frmAddStock.reset();
                        namaBarang.focus();
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function cariStockBarangRuang(){
        $('#dataObat').datagrid({
            url:'json/obat.php?task=cariBarang&id_barang=' + $("#namaBarang1Id").val() +
                '&jenis_barang=' + $("#jenis_barang").val()
        });
    }
	
    function openWinDistObat(){
        $('#winDistObat').window('open');
        ruangTujuan.focus();
    }
    
    function closeWinDistObat(){
        frmDistObat.reset();
        $("#winDistObat").window('close');
    }
    
    function openWinTambahStock(){
        $('#winTambahStock').window('open');
        namaBarang.focus();
    }
    
    function closeWinTambahStock(){
        frmAddStock.reset();
        $("#winTambahStock").window('close');
    }
    
</script>