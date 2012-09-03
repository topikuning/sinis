<script>
    $(function(){
        $('#detailObat').datagrid({
            title:'Detail Faktur',
            height:200,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            url:'json/obat.php?task=getDetailFaktur&id_faktur=' + $('#id_faktur').val(),
            sortName: 'kode_obat',
            sortOrder: 'desc',
            remoteSort: false,
            collapsible: true,
            chace:false,
            idField:'kode_obat',
            frozenColumns:[[
                    {title:'ID',field:'kode_obat',width:30,sortable:true},
                    {field:'nama_obat',title:'Nama Obat',width:150,sortable:true}
                ]],
            columns:[[
                    {field:'penyimpanan',title:'Penyimpanan',width:120,sortable:true},
                    {field:'qty',title:'Qty',width:50,sortable:true},
                    {field:'retur',title:'Retur',width:50,sortable:true},
                    {field:'harga',title:'Harga',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'diskon',title:'Diskon',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'pajak',title:'Pajak',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'tgl_kadaluarsa',title:'Tanggal Kadaluarsa',width:80,sortable:true}
                ]],
            pagination:true,
            rownumbers:true
        });
        
        $('#dataFaktur').datagrid({
            title:'Data Pembayaran Faktur',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible:true,
            url:'json/obat.php?task=getFakturBayarKeuangan',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            sortName: 'id_pembayaran_faktur',
            idField:'id_pembayaran_faktur',
            frozenColumns:[[
                    {title:'ID Faktur',field:'id_pembayaran_faktur',width:50,sortable:true,hidden:true},
                    {title:'No Faktur',field:'no_faktur',width:50,sortable:true,hidden:true},
                    {title:'ID Supplier',field:'id_supplier',width:50,sortable:true,hidden:true},
                    {title:'Tanggal Pembelian',field:'tgl_pembelian',width:80,sortable:true,hidden:true},
                    {field:'supplier',title:'Supplier',width:100,sortable:true,hidden:true}
                ]],
            columns:[[
                    {field:'total',title:'Total',width:120,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return '';
                        }
                    },
                    {field:'bayar',title:'Bayar',width:120,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return '';
                        }
                    },
                    {field:'sisa',title:'Sisa',width:120,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return '';
                        }
                    },
                    {field:'status',title:'Status',width:120,align:'left',
                        formatter:function(value){
                            if(value=='0')
                                return 'Menunggu Approval';
                            else if (value=='1')
                                return 'Terbayar';
                        }
                    }
                ]],
            groupField: 'id_pembayaran_faktur',
            view: groupview,
            groupFormatter:function(value, rows){
                return 'No Faktur : ' + rows[0].no_faktur + ' - Supplier : ' + rows[0].supplier + ' - ' + 
                        ' Tgl Pembelian : ' + rows[0].tgl_pembelian + ' (' + rows.length + ' Item(s))';
            },
            onDblClickRow:function(){
                var rows = $('#dataFaktur').datagrid('getSelections');
                var id_faktur = rows[0].id_faktur;
                $('#frmFaktur').form('load','json/obat.php?task=cariDtlFaktur&id_faktur=' + id_faktur);
                $('#detailObat').datagrid({
                    url:'json/obat.php?task=getDetailFaktur&id_faktur=' + id_faktur
                });
                $('#detailObat').datagrid("reload");
                $('#dataListPembayaran').datagrid({
                    url:'json/obat.php?task=getListPembayaranFaktur&id_faktur=' + id_faktur
                });
                $('#dataListPembayaran').datagrid("reload");
                $('#id_pembayaran_faktur').val(rows[0].id_pembayaran_faktur);
                $('#total').val(formatCurrency(rows[0].total));
                $('#sisa').val(formatCurrency(rows[0].sisa));
                $('#bayar').val(formatCurrency(rows[0].bayar));
                if(rows[0].status=='1')
                    $('#approve').linkbutton({
                        disabled:true
                    });
                else
                    $('#approve').linkbutton({
                        disabled:false
                    });
                openWinFaktur();
            },
            pagination:true,
            rownumbers:true
        });
        
        $('#dataListPembayaran').datagrid({
            title:'Data Pembayaran Faktur',
            height:200,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible:true,
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            sortName: 'id_pembayaran_faktur',
            idField:'id_pembayaran_faktur',
            frozenColumns:[[
                    {title:'ID Faktur',field:'id_pembayaran_faktur',width:50,sortable:true,hidden:true},
                    {title:'No Faktur',field:'no_faktur',width:50,sortable:true,hidden:true},
                    {title:'Ke-',field:'bayarKe',width:50,sortable:true},
                    {title:'Tanggal Pembayaran',field:'tgl_pembayaran',width:80,sortable:true},
                    {field:'supplier',title:'Supplier',width:100,sortable:true,hidden:true}
                ]],
            columns:[[
                    {field:'bayar',title:'Bayar',width:120,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return '';
                        }
                    }
                ]],
            pagination:true,
            rownumbers:true
        });
    });
    
    function approveFaktur(){
        var dataString = "task=approveFaktur&id_pembayaran_faktur=" + $("#id_pembayaran_faktur").val();

        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    $.messager.show({
                        title:'Faktur Pembelian Obat',
                        msg:'Approve Faktur berhasil.',
                        showType:'show'
                    });
                    closeWinFaktur();
                    $('#dataFaktur').datagrid('reload');
                    return false;
                } else {
                    $.messager.show({
                        title:'Faktur Pembelian Obat',
                        msg:'Approve Faktur gagal.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    };
    
    function openWinFaktur(){
        $('#winFaktur').window('open');
        no_faktur.focus();
    }
    
    function closeWinFaktur(){
        frmFaktur.reset();
        $("#winFaktur").window('close');
    }
    
</script>