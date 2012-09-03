<script>
    $(function(){

        $('#dataFaktur').datagrid({
            title:'Detail Faktur Pembelian Obat',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            collapsible:true,
            url:'json/obat.php?task=getFakturNotAssign',
            sortName: 'no_faktur',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'no_faktur',
            frozenColumns:[[
                    {title:'ID Faktur',field:'id_faktur',width:50,sortable:true,hidden:true},
                    {title:'No Faktur',field:'no_faktur',width:50,sortable:true,hidden:true},
                    {title:'ID Supplier',field:'id_supplier',width:50,sortable:true,hidden:true},
                    {title:'Tanggal Pembelian',field:'tgl_pembelian',width:80,sortable:true,hidden:true},
                    {field:'supplier',title:'Supplier',width:100,sortable:true,hidden:true},
                    {field:'total',title:'Total',width:1,sortable:false,hidden:true}
                ]],
            columns:[[
                    {title:'Kode Obat',field:'kode_obat',width:80,sortable:true},
                    {field:'nama_obat',title:'Nama Obat',width:200,sortable:true},
                    {field:'penyimpanan',title:'Penyimpanan',width:120,sortable:true},
                    {field:'qty',title:'Qty',width:50,sortable:true},
                    {field:'retur',title:'Retur',width:50,sortable:true},
                    {field:'harga',title:'Harga',width:80,align:'right',
                        formatter:function(value){
                            return formatCurrency(value);
                        }
                    },
                    {field:'diskon',title:'Diskon',width:80,align:'right',
                        formatter:function(value){
                            return formatCurrency(value);
                        }
                    },
                    {field:'pajak',title:'Pajak',width:80,align:'right',
                        formatter:function(value){
                            return formatCurrency(value);
                        }
                    },
                    {field:'tgl_kadaluarsa',title:'Tanggal Kadaluarsa',width:80,sortable:true}
                ]],
            groupField: 'no_faktur',
            view: groupview,
            groupFormatter:function(value, rows){
                return 'No Faktur : ' + value + ' - Supplier : ' + rows[0].supplier + ' - ' + 
                        ' Tgl Pembelian : ' + rows[0].tgl_pembelian + ' Total : ' + formatCurrency(rows[0].total) + ' (' + rows.length + ' Barang)';
            },
            toolbar:[{
                    id:'btndelete',
                    text:'Kirim ke Gudang',
                    iconCls:'icon-save',
                    handler:function(){
                        var row = $('#dataFaktur').datagrid('getSelected');
                        var index = $('#dataFaktur').datagrid('getRowIndex', row);
                        if(index>=0){
                            $.messager.confirm('Faktur Pembelian Obat', 'Kirim Faktur ke Gudang?', function(r){
                                if (r){
                                    $.ajax({  
                                        type: "GET",  
                                        url: "json/obat.php",  
                                        data: "task=assignObatGudang&id_faktur=" + row.id_faktur,  
                                        success: function(data) {
                                            if(data=='1'){
                                                $('#dataFaktur').datagrid('deleteRow', index);
                                                $('#dataFaktur').datagrid('reload');
                                                $.messager.show({
                                                    title:'Faktur Pembelian Obat',
                                                    msg:'Assign Faktur Berhasil.',
                                                    showType:'show'
                                                });
                                                $('#dataFaktur').datagrid('clearSelections');
                                            } else if (data=='0') {
                                                $.messager.show({
                                                    title:'Faktur Pembelian Obat',
                                                    msg:'Assign Faktur Gagal.',
                                                    showType:'show'
                                                });
                                            } else if (data=='2') {
                                                $.messager.show({
                                                    title:'Faktur Pembelian Obat',
                                                    msg:'Assign Faktur Gagal. Faktur sudah di Assign ke Gudang',
                                                    showType:'show'
                                                });
                                            }
                                        }
                                    })
                                }
                            });
                        }
                    }
                }],
            pagination:true,
            rownumbers:true
        });
    });
    
</script>