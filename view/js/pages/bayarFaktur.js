<script>
    $(function(){    
        no_fakturID.focus();
        
        $('#dataFaktur').datagrid({
            title:'Detail Faktur',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            collapsible:true,
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
                    {field:'supplier',title:'Supplier',width:100,sortable:true,hidden:true}
                ]],
            columns:[[
                    {title:'Kode Obat',field:'kode_obat',width:80,sortable:true},
                    {field:'nama_obat',title:'Nama Obat',width:200,sortable:true},
                    {field:'penyimpanan',title:'Penyimpanan',width:120,sortable:true},
                    {field:'qty',title:'Qty',width:50,sortable:true},
                    {field:'retur',title:'Retur',width:50,sortable:true},
                    {field:'harga',title:'Harga',width:100,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'diskon',title:'Diskon',width:100,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'pajak',title:'Pajak',width:100,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'tgl_kadaluarsa',title:'Tanggal Kadaluarsa',width:80,sortable:true}
                ]],
            groupField: 'no_faktur',
            view: groupview,
            groupFormatter:function(value, rows){
                return 'No Faktur : ' + value + ' - Supplier : ' + rows[0].supplier + ' - ' + 
                        ' Tgl Pembelian : ' + rows[0].tgl_pembelian + ' (' + rows.length + ' Item(s))';
            },
            pagination:true,
            rownumbers:true
        });

        $('#dataListFaktur').datagrid({
            title:'Data List Faktur',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            remoteSort: false,
            idField:'id_faktur',
            columns:[[
                {title:'ID',field:'id_faktur',width:50},
                {field:'no_faktur',title:'No Faktur',width:50},
                {field:'supplier',title:'Supplier',width:150},
                {field:'tgl_pembelian',title:'Tanggal Pembelian',width:80},
                {field:'status',title:'Status',width:80,
                    formatter:function(value){
                        if(value==0)
                            return 'Belum Lunas';
                        else
                            return 'Lunas';
                    }
                }
            ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(rowIndex){
                var row = $('#dataListFaktur').datagrid('getSelected');

                $('#no_fakturID').val(row.no_faktur);
                $('#id_faktur').val(row.id_faktur);
                
                $('#winSearch').window('close');
                no_fakturID.focus();
            }
        });
        
    });
    
    function openWinSearch(id){
        $('#winSearch').window('open')
        supplier.focus();
    }

    function getFaktur(){
        $('#dataFaktur').datagrid({
            url:'json/obat.php?task=getFakturId&no_faktur=' + no_fakturID.value
        });
        $('#frmFaktur').form('load','json/obat.php?task=getIdFaktur&no_faktur=' + no_fakturID.value);
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
            $('#frmBayarFaktur').form('load','json/obat.php?task=getBayarFaktur&id_faktur=' + id_faktur.value);
            $('#winBayar').window('open');
            bayar.focus();
        }
    }
    
    function saveBayarFaktur(){
        var dataString = "task=bayarFaktur&id_faktur=" + id_faktur.value +
                       "&bayarKe=" +$("#bayarKe").val() +
                       "&bayar=" +$("#bayar").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#bayar").val(), 'Bayar');

        if(bvalid){
            if($("#bayar").val()>$("#kurang_bayar").val()){
                $.messager.show({
                    title:'Pembayaran Faktur',
                    msg:'Pmbayarn melebihi jumlah faktur.',
                    showType:'show'
                });
                return false;
            } else {
                $.ajax({  
                    type: "GET",  
                    url: "json/obat.php",  
                    data: dataString,  
                    success: function(data) {
                        if(data==$("#no_fakturID").val()){
                            $.messager.show({
                                title:'Pembayaran Faktur',
                                msg:'Pembayaran Faktur No. <b>' + $("#no_fakturID").val() + '</b> berhasil dilakukan. Menunggu Proses Approval dari Keuangan.',
                                showType:'show'
                            });
                            $('#dataPenjualanObat').datagrid('deleteRow', 0);
                            $('#dataPenjualanObat').datagrid('reload');
                            $("#no_fakturID").val("");
                            no_fakturID.focus();
                            return false;
                        } else if(data=='finish'){
                            $.messager.show({
                                title:'Pembayaran Faktur',
                                msg:'Pembayaran Faktur sudah dilakukan.',
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
        }
    }

    function loadDataListFaktur(){
        $('#dataListFaktur').datagrid({
            url:'json/obat.php?task=getListFaktur&supplier=' + $('#supplier').val() + '&tgl_beli=' + $('#tgl_beli').datebox("getValue")
        });
        $('#dataListFaktur').datagrid('reload');
    }

</script>