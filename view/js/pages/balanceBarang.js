<script>
    
	$.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate: setStock
    });
	
	function setStock(id){
        var dataString = "task=getBahanRuang&id_barang=" + id;
            $.ajax({  
                type: "POST",  
                url: "json/tindakanPost.php",  
                data: dataString,  
                success: function(data) {
                    var returnData = data.split(":");
                    $('#stock').val(returnData[0]);
                    $('#satuan').val(returnData[1]);
                }
            });
        return false;
    }
    
    $(function(){

        $('#dataBahan').datagrid({
            title:'Data Bahan',
            height:400,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'id_barang_tindakan',
			url: 'json/data.php?task=getBarangBal',
            sortOrder: 'asc',
            remoteSort: false,
            collapsible:true,
            idField:'id_barang_tindakan',
            frozenColumns:[[
                    {title:'ID',field:'id_barang_tindakan',width:50,hidden:true},
                    {field:'barang',title:'Nama Barang',width:250},
                ]],
            columns:[[
                    {field:'stock',title:'Sisa Stock',width:100},
                    {field:'jumlah',title:'Jumlah',width:150},
                    {field:'tipe',title:'Tipe Balance',width:150},
                    {field:'satuan',title:'Satuan',width:150},
                ]],
            pagination:true,
            rownumbers:true,
            toolbar:[{
                    id:'btnbhn',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var rows = $('#dataBahan').datagrid('getSelections');
                        var id = "task=hapusBahan&id_barang_tindakan=" + rows[0].id_barang_tindakan;
                        $.ajax({  
                            type: "POST",  
                            url: "json/tindakanPost.php",  
                            data: id,  
                            success: function(data) {  
                                if(data=='1'){
                                    $.messager.show({
                                        title:'Bahan',
                                        msg:'Penghapusan Bahan Nomor ' + rows[0].id_barang_tindakan + ' berhasil.',
                                        showType:'show'
                                    });
                                    $('#dataBahan').datagrid('reload');
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Bahan',
                                        msg:'Penghapusan Bahan no ' + rows[0].id_barang_tindakan + ' gagal.',
                                        showType:'show'
                                    });
                                }
                            }
                        })
                    }
                }]
        });
});

function loadBahan(){
        $('#dataBahan').datagrid({
            url:'json/data.php?task=getBarangBal&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_balance=' + $("#tp_bal").val()
        });
        $('#dataBahan').datagrid("reload");
    }
	
    function saveBahan(){
        var dataString = "task=simpanBahanBal&id_barang_tindakan=" +$("#id_barang_tindakan").val() +
            "&id_barang=" +$("#bahanId").val() +
            "&jumlah=" + $("#jumlahBarang").val() +
			"&tipe=" + $("#balance").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#bahanId").val(), 'Bahan');
        bvalid = bvalid && checkSelect($("#jumlahBarang").val(), 'Jumlah Barang');
		bvalid = bvalid && checkSelect($("#stock").val(), 'Stock');
		bvalid = bvalid && checkSelect($("#balance").val(), 'Tipe Balance');

        if(bvalid){                  
            $.ajax({  
                type: "POST",  
                url: "json/tindakanPost.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='1'){
                        $.messager.show({
                            title:'Bahan',
                            msg:'Bahan berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataBahan').datagrid('reload');
                        $("#id_barang_tindakan").val("");
						$("#bahanId").val("");
						$("#bahanBal").val("");
						$("#jumlahBarang").val("");
						$("#stock").val("");
						$("#satuan").val("");
                        return false;
                    } else if(data=='2'){
                        $.messager.show({
                            title:'Bahan',
                            msg:'Bahan berhasil disimpan dengan catatan stock habis.',
                            showType:'show'
                        });
                        $('#dataBahan').datagrid('reload');
                        $("#id_barang_tindakan").val("");
						$("#bahanId").val("");
						$("#bahanBal").val("");
						$("#jumlahBarang").val("");
						$("#stock").val("");
						$("#satuan").val("");
                        return false;
					
					} else if(data=='0'){
                        $.messager.show({
                            title:'Bahan',
                            msg:'STOCK TIDAK MENCUKUPI / HABIS',
                            showType:'show'
                        });
                        $('#dataBahan').datagrid('reload');
                        return false;
					
					} else {
                        $.messager.show({
                            title:'Bahan',
                            msg:'Gagal menyimpan Bahan. ' + data,
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    }

</script>