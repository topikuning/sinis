<script>
    $(function(){

        $('#dataFaktur').datagrid({
            title:'Detail Faktur Penjualan Obat',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            collapsible:true,
            url:'json/apotik.php?task=getFakturPenjualan&no_faktur=' + $("#no_faktur").val() + 
                '&nama_pasien=' + $("#nama_pasien").val() + 
                '&startDate=' + $("#startDate").datebox("getValue") + 
                '&endDate=' + $("#endDate").datebox("getValue") + 
                '&status=' + $("#status").val(),
            sortName: 'id_faktur_penjualan',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'id_faktur_penjualan',
            columns:[[
                {title:'No Faktur',field:'id_faktur_penjualan',width:50,sortable:true},
                {title:'Tanggal Faktur',field:'tgl_penjualan',width:80,sortable:true},
                {title:'Customer',field:'jns_customer',width:150,sortable:true},
                {field:'id_pasien',title:'NO RM',width:200,sortable:true},
                {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true},
                {title:'Status',field:'status',width:120,sortable:true,
                    formatter:function(value){
                        if(value=="0") return "Belum Terbayar";
                        else if(value=="1") return "Kredit";
                        else if(value=="2") return "Lunas";
                    }
                },
                {field:'total',title:'Total',width:120,align:'right',
                    formatter:function(value){
                        return formatCurrency(value);
                    }
                },
                {field:'terbayar',title:'terbayar',width:120,align:'right',
                    formatter:function(value){
                        return formatCurrency(value);
                    }
                },
                {field:'sisa',title:'Sisa',width:120,align:'right',
                    formatter:function(value){
                        return formatCurrency(value);
                    }
                }
            ]],
            view: detailview,
            detailFormatter:function(index,row){
                    return '<div id="ddv-' + index + '" style="padding:5px 0"></div>';
            },
            onExpandRow: function(index,row){
                $('#ddv-'+index).panel({
                    border:false,
                    cache:false,
                    href:'pages/detPenjualanObat.php?id_faktur_penjualan=' + row.id_faktur_penjualan,
                    onLoad:function(){
                        $('#dataFaktur').datagrid('fixDetailRowHeight',index);
                    }
                });
                $('#dataFaktur').datagrid('fixDetailRowHeight',index);
            },
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(){
                var rows = $('#dataFaktur').datagrid('getSelections');
                if(rows[0].status!='2'){
                    window.location='index.php?page=byrfrm&fid=' + rows[0].id_faktur_penjualan;
                }
            },
            toolbar:[{
                id:'btndelete',
                text:'Faktur',
                iconCls:'icon-daftar',
                handler:function(){
                    var row = $('#dataFaktur').datagrid('getSelected');
                    if(row.status!='2')
                        window.location='index.php?page=pjlobt&fid=' + row.id_faktur_penjualan + '&pid=' + row.id_pasien + '&nid=' + row.nama_pasien;
                }
            }]
        });
    });
    
    function loadDataFakturPenjualan(){
        $('#dataFaktur').datagrid({
            url:'json/apotik.php?task=getFakturPenjualan&no_faktur=' + $("#no_faktur").val() + 
                '&nama_pasien=' + $("#nama_pasien").val() + 
                '&startDate=' + $("#startDate").datebox("getValue") + 
                '&endDate=' + $("#endDate").datebox("getValue") + 
                '&status=' + $("#status").val()
        });
    }
</script>