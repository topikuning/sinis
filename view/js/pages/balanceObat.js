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
            title:'Data Obat Balance',
            height:400,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'id_obat_balance',
            url: 'json/apotik.php?task=getObatBal&startDate=&endDate=&tipe_balance=',
            sortOrder: 'asc',
            remoteSort: false,
            collapsible:true,
            idField:'id_obat_balance',
            frozenColumns:[[
                    {title:'ID',field:'id_obat_balance',width:50,hidden:true},
                    {field:'nama_obat',title:'Nama Obat',width:250},
                ]],
            columns:[[
                    {field:'stock',title:'Sisa Stock',width:100},
                    {field:'jumlah',title:'Jumlah',width:150},
                    {field:'keperluan',title:'Tipe Balance',width:150}
                ]],
            pagination:true,
            rownumbers:true,
            toolbar:[
                {
                    id:'btndel',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        hapusBalance();
                    }
                }
            ]
        });
    });
    
    function hapusBalance(){
        var rows = $('#dataBahan').datagrid('getSelections');
        var id_bal = rows[0].id_obat_balance;
        
        var dataString = "task=hapusBalance&id_dist=" + id_bal;
        
        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: dataString,  
            success: function(data) {
                if(data=='1'){
                    $.messager.show({
                        title:'Balance Obat',
                        msg:'Penghapusan Berhasil',
                        showType:'show'
                    });
                    $("#dataBahan").datagrid("reload");
                    return false;
                } else if (data=='0'){
                    $.messager.show({
                        title:'Distribusi Obat',
                        msg:'Penghapusan Gagal.',
                        showType:'show'
                    });
                }
            }  
        });  
        return false;
    }

    function loadBahan(){
        $('#dataBahan').datagrid({
            url:'json/data.php?task=getBarangBal&tgl_awal=' + $("#tgl_awal").datebox("getValue") + 
                '&tgl_akhir=' + $("#tgl_akhir").datebox("getValue") +
                '&tipe_balance=' + $("#tp_bal").val()
        });
        $('#dataBahan').datagrid("reload");
    }
	
    function saveBahan(){
        var dataString = "task=simpanObatBal&id_obat_balance=" +$("#id_obat_balance").val() +
            "&id_obat=" +$("#obatId").val() +
            "&jumlah=" + $("#jumlahObat").val() +
            "&tipe=" + $("#balance").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#obatId").val(), 'Obat');
        bvalid = bvalid && checkSelect($("#jumlahObat").val(), 'Jumlah Obat');
		bvalid = bvalid && checkSelect($("#balance").val(), 'Tipe Balance');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/apotik.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='1'){
                        $.messager.show({
                            title:'Obat',
                            msg:'Obat berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataBahan').datagrid('reload');
                        $("#id_obat_balance").val("");
                        $("#obatId").val("");
                        $("#obatBal").val("");
                        $("#jumlahObat").val("");
                        return false;
                    } else if(data=='2'){
                        $.messager.show({
                            title:'Obat',
                            msg:'Obat berhasil disimpan dengan catatan stock habis.',
                            showType:'show'
                        });
                        $('#dataBahan').datagrid('reload');
                        $("#id_obat_balance").val("");
                        $("#obatId").val("");
                        $("#obatBal").val("");
                        $("#jumlahObat").val("");
                        return false;
					
                    } else if(data=='0'){
                        $.messager.show({
                            title:'Obat',
                            msg:'STOCK TIDAK MENCUKUPI / HABIS',
                            showType:'show'
                        });
                        $('#dataBahan').datagrid('reload');
                        return false;
					
                    } else {
                        $.messager.show({
                            title:'Obat',
                            msg:'Gagal menyimpan Obat. ' + data,
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