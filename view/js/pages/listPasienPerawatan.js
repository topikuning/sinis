<script>
    $(function(){

        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPendaftaran.php?task=cariPendaftaranAll&no_pendaftaran=' + $('#no_pendaftaran').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
            sortName: 'id_pendaftaran',
            sortOrder: 'desc',
            remoteSort: false,
            showFooter:true,
            chace:false,
            idField:'id_pendaftaran',
            columns:[[
                {title:'ID',field:'id_pendaftaran',width:50,sortable:true},
                {title:'No RM',field:'id_pasien',width:80,sortable:true},
                {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true},
                {field:'id_tipe_pendaftaran',title:'ID Tipe Pendaftaran',width:120,sortable:true,hidden:true},
                {field:'tipe_pasien',title:'Tipe Pasien',width:120,sortable:true},
                {field:'ruang',title:'Ruang',width:120,sortable:true},
                {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:80},
                {field:'status',title:'Status',width:80},
                {field:'status_pembayaran',title:'Status Pembayaran',width:100},
                {field:'total',title:'Total',width:120,align:'right',
                    formatter:function(value){
                        return formatCurrency(value);
                    }
                },
                {field:'terbayar',title:'Terbayar',width:120,align:'right',
                    formatter:function(value){
                        return formatCurrency(value);
                    }
                },
                {field:'diskon',title:'Diskon',width:120,align:'right',
                    formatter:function(value){
                        if(value>0)
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
                    href:'pages/detPembayaranTagihan.php?id_pasien=' + row.id_pasien + '&status_pembayaran=' + row.status_pembayaran,
                    onLoad:function(){
                        $('#dataPendaftaran').datagrid('fixDetailRowHeight',index);
                    }
                });
                $('#dataPendaftaran').datagrid('fixDetailRowHeight',index);
            },
            toolbar:[{
                id:'btnCetak',
                text:'Cetak Tagihan',
                iconCls:'icon-print',
                handler:function(){
                    var rows = $('#dataPendaftaran').datagrid('getSelections');
                    $.ajax({  
                        type: "GET",  
                        url: "json/data.php",  
                        data: "task=cetakLaporanTagihan&id_pasien=" + rows[0].id_pasien,
                        success: function(dRet) {
                            if(dRet=='1'){
                                var win = window.open('report/cetakLaporanTagihan.html','cetakLaporan','height=400,width=1000,resizable=1,scrollbars=1, menubar=0');
                                //win.print();
                            }
                        }
                    });
                }
            },{
                id:'btnCheckOut',
                text:'Check Out Pasien',
                iconCls:'icon-logout',
                handler:function(){
                    var rows = $('#dataPendaftaran').datagrid('getSelections');
                    if(rows[0].status_pembayaran=='Lunas'){
                        openClosePerawatan();
                        $("#no_rm_pasien").val(rows[0].id_pasien);
                    } else {
                        $.messager.show({
                            title:'Check Out Pasien',
                            msg:'Check Out Pasien Gagal. Pasien Belum melunasi tagihan',
                            showType:'show'
                        });
                    }
                }
            }],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(rowIndex){
                var rows = $('#dataPendaftaran').datagrid('getSelections');
                if(rows[0].status_pembayaran!='2'){
                    window.location='index.php?page=dftrtghnpx&pid=' + rows[0].id_pasien;
                }
            }
        });
    });
    
    function simpanClosePerawatan(){
        var dataString = "task=checkOutPasien&id_pasien=" + $("#no_rm_pasien").val() +
            "&kondisi_keluar=" + $("#kondisiKeluar").val() + 
            "&cara_keluar=" + $("#caraKeluar").val() + 
            "&keterangan_keluar=" + $("#keteranganKeluar").val();

        $.ajax({  
            type: "GET",  
            url: "json/data.php",  
            data: dataString,  
            success: function(data) {
                if(data=='1'){
                    $.messager.alert('Check Out',
                        'Check Out pasien berhasil.',
                        'alert'
                    );
                    $('#winClosePerawatan').window('close');
                    $('#dataPendaftaran').datagrid('reload');
                } else {
                    $.messager.alert('Check Out',
                        'Check Out pasien gagal.',
                        'alert'
                    );
                    $('#winClosePerawatan').window('close');
                }
            }  
        });  
        return false;
    }

    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPendaftaran.php?task=cariPendaftaranAll&no_pendaftaran=' + $('#no_pendaftaran').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
    }
    function openClosePerawatan(){
        $('#winClosePerawatan').window('open');
        kondisiKeluar.focus();
    }
</script>