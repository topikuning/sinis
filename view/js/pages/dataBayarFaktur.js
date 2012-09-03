<script>
    $(function(){        
        $('#dataFaktur').datagrid({
            title:'Data Pembayaran Faktur',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible:true,
            url:'json/obat.php?task=getFakturBayar',
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
            pagination:true,
            rownumbers:true
        });
    });
    
    function getFaktur(){
        $('#dataFaktur').datagrid({
            url:'json/obat.php?task=getFakturId&no_faktur=' + no_fakturID.value
        });
        $('#dataFaktur').datagrid("reload");
    }
    
    function bayarFaktur(){
        if($('#no_fakturID').val()==''){
            $.messager.show({
                title:'Pembayaran Faktur',
                msg:'No Faktur belum diisi.',
                showType:'show'
            });
            no_fakturID.focus();
        } else {
            $.messager.confirm('Pembayaran Faktur', 'Pembayaran Faktur No. ' + $('#no_fakturID').val(), function(r){
                if (r){
                    var dataString = "task=bayarFaktur&no_faktur=" + $("#no_fakturID").val();

                    $.ajax({  
                        type: "GET",  
                        url: "json/obat.php",  
                        data: dataString,  
                        success: function(data) {
                            if(data!='0'){
                                $.messager.show({
                                    title:'Pembayaran Faktur',
                                    msg:'Pembayaran Faktur No. <b>' + $("#no_fakturID").val() + '</b> berhasil dilakukan. Menunggu Proses Approval dari Keuangan.',
                                    showType:'show'
                                });
                                return false;
                            } else {
                                $.messager.show({
                                    title:'Pembayaran Faktur',
                                    msg:'Pembayaran Faktur Gagal.',
                                    showType:'show'
                                });
                                return false;
                            }
                        }  
                    });  
                    return false;
                }
            });            
        }
    }
    
</script>