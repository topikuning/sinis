<script>
    $(function(){
        id_faktur_penjualan.focus()
        
        $('#dataPenjualanObat').datagrid({
            title:'Detail Obat',
            height:250,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            collapsible:true,
            url:'json/apotik.php?task=getDetailPenjualanObat&id_faktur_penjualan=' + $('#id_faktur_penjualan').val(),
            sortName: 'id_obat',
            remoteSort: false,
            chace:false,
            idField:'id_obat',
            frozenColumns:[[
                    {title:'obat_jual',field:'id_penjualan_obat',width:1,sortable:true,hidden:true},
                    {title:'No Faktur',field:'no_faktur',width:1,sortable:true,hidden:true},
                    {title:'ID Obat',field:'id_obat',width:1,sortable:true,hidden:true},
                    {title:'Jml Retur',field:'jml_retur',width:1,sortable:true,hidden:true},
                    {title:'Nama Obat',field:'nama_obat',width:250,sortable:true}
                ]],
            columns:[[
                    {field:'qty',title:'Qty',width:50,sortable:true},
                    {field:'rCode',title:'Kode R',width:50,sortable:true},
                    {field:'harga',title:'Harga',width:120,align:'right'},
                    {field:'total',title:'Total',width:120,align:'right'}
                ]],
            groupField: 'no_faktur',
            view: groupview,
            groupFormatter:function(value, rows){  
                return 'No Faktur : ' + value + ' - ' + rows.length + ' Item(s)';
            },
            rowStyler:function(index,row,css){
                if (row.status==0){
                    return 'background-color:#ff0000;';
                }
            },
            onDblClickRow:function(){
                var row = $('#dataPenjualanObat').datagrid('getSelected');
                $('#nama_obat').val(row.nama_obat);
                $('#id_obat').val(row.id_obat);
                $('#id_jual').val(row.id_penjualan_obat);
                $('#qty').val((row.qty - row.jml_retur));
                jmlRetur.focus();
                openWinDetailObat();
            },
            pagination:true,
            rownumbers:true
        });

        $('#dataReturPenjualanObat').datagrid({
            title:'Detail Retur Penjualan Obat',
            height:250,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            collapsible:true,
            url:'json/apotik.php?task=getDetailReturPenjualanObat&id_faktur_penjualan=' + $('#id_faktur_penjualan').val(),
            sortName: 'id_obat',
            remoteSort: false,
            chace:false,
            idField:'id_obat',
            frozenColumns:[[
                    {title:'id_retur_penjualan_obat',field:'id_retur_penjualan_obat',width:50,sortable:true,hidden:true},
                    {title:'No Faktur',field:'no_faktur',width:50,sortable:true,hidden:true},
                    {title:'ID Obat',field:'id_obat',width:50,sortable:true,hidden:true},
                    {title:'Nama Obat',field:'nama_obat',width:250,sortable:true}
                ]],
            columns:[[
                    {field:'qty',title:'Qty',width:50,sortable:true},
                    {field:'rCode',title:'Kode R',width:50,sortable:true},
                    {field:'pros_retur',title:'Pros Retur',width:50,sortable:true},
                    {field:'harga',title:'Harga',width:120,align:'right'},
                    {field:'total',title:'Total',width:120,align:'right'}
                ]],
            toolbar:[{
                    id:'btndelete',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var row = $('#dataReturPenjualanObat').datagrid('getSelected');
                        var index = $('#dataReturPenjualanObat').datagrid('getRowIndex', row);
                        $.ajax({  
                            type: "GET",  
                            url: "json/apotik.php",  
                            data: "task=hapusReturObat&id_retur_penjualan_obat=" + row.id_retur_penjualan_obat,  
                            success: function(data) {
                                if(data=='1'){
                                    $('#dataReturPenjualanObat').datagrid('deleteRow', index);
                                    $('#dataReturPenjualanObat').datagrid('reload');
                                    $('#dataPenjualanObat').datagrid('reload');
                                    $.messager.show({
                                        title:'Retur Obat',
                                        msg:'Retur Obat berhasil dihapus.',
                                        showType:'show'
                                    });
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Retur Obat',
                                        msg:'Gagal menghapus Retur Obat.',
                                        showType:'show'
                                    });
                                }
                            }
                        })
                    }
                }],
            pagination:true,
            rownumbers:true
        });

    });
    
    function simpanReturObat(){
        var tun;
        var pros;
        
        if(tunai.checked) tun = '1';
        else tun = '0';
        
        if($("#prosRetur").val()!="") pros = parseInt($("#prosRetur").val()) / 100;
        
        var dataString = "task=simpanReturObat&id_faktur_penjualan=" + $("#id_faktur_penjualan").val() +
            "&id_obat=" + $("#id_obat").val() + 
            "&pros_retur=" + pros + 
            "&jns_retur=" + tun + 
            "&id_penjualan=" + $("#id_jual").val() + 
            "&jumlah=" + $("#jmlRetur").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#jmlRetur").val(), 'Jumlah');
        bvalid = bvalid && checkSelect($("#prosRetur").val(), 'Prosentase');
        
        if(bvalid){
            $('#btnRetur').linkbutton({
                disabled:true
            });
            var sip = true;
            
            if($("#prosRetur").val()<0 || $("#prosRetur").val()>20) sip = false;
            if(sip){
                var ok = true;
                if(parseInt($("#jmlRetur").val())>parseInt($("#qty").val())){
                    ok = false;
                }

                if(ok){
                    $.ajax({  
                        type: "GET",  
                        url: "json/apotik.php",  
                        data: dataString,  
                        success: function(data) {
                            if(data=='LOGIN'){
                                alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                                window.location.reload();
                            } else if(data=='1'){
                                $.messager.show({
                                    title:'Retur Obat',
                                    msg:'Retur Obat berhasil.',
                                    showType:'show'
                                });
                                $('#dataPenjualanObat').datagrid('reload');
                                $('#dataReturPenjualanObat').datagrid("reload");
                                closeWinDetailObat();
                                return false;
                            } else {
                                $.messager.show({
                                    title:'Retur Obat',
                                    msg:'Retur Obat gagal.',
                                    showType:'show'
                                });
                                return false;
                            }
                        }  
                    });  
                } else {
                    $.messager.show({
                        title:'Retur Obat',
                        msg:'Jumlah retur melibihi Quantity.',
                        showType:'show'
                    });
                }
            } else {
                $.messager.show({
                    title:'Retur Obat',
                    msg:'Prosentase harus di isi antara 0 - 20%.',
                    showType:'show'
                });
            }
        }
        return false;
    };
    
    function getDtlFaktur(){
        var dataString = "task=getDetailReturFakturPenjualan&id_pasien=" + $("#id_pasien").val() +
            "&id_faktur_penjualan=" + $("#id_faktur_penjualan").val() +
            "&nama_pasien=" + $("#nama_pasien").val();

        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                var returnData = data.split("<>");
                if(returnData[2]!=''){
                    $("#id_faktur_penjualan").val(returnData[0]);
                    $("#no_resep").val(returnData[1]);
                    $("#jns_customer").val(returnData[2]);
                    $("#dokter").val(returnData[3]);
                    $("#id_pasien").val(returnData[4]);
                    $("#ruang").val(returnData[5]);
                    $("#nama_pasien").val(returnData[6]);
                    $("#alamat").val(returnData[7]);
                    $("#tipe_pasien").val(returnData[8]);
                    $("#tipe_asuransi").val(returnData[9]);
                    $("#karyawan").val(returnData[10]);
                    $("#tipe_pendaftaran").val(returnData[11]);
                    $("#tunaiF").val(returnData[12]);
                    $('#dataPenjualanObat').datagrid({
                        url:'json/apotik.php?task=getDetailPenjualanObat&id_faktur_penjualan=' + returnData[0]
                    });
                    $('#dataPenjualanObat').datagrid("reload");
                    $('#dataReturPenjualanObat').datagrid({
                        url:'json/apotik.php?task=getDetailReturPenjualanObat&id_faktur_penjualan=' + returnData[0]
                    });
                    $('#dataReturPenjualanObat').datagrid("reload");
                } else {
                    frmFakturPenjualan.reset();
                    bersihkan();
                    $("#detailTagihan").html("Data tidak ditemukan");
                }
            }  
        });  
        return false;
    };

    function openWinPembayaran(){
        if($('#id_faktur_penjualan').val()!=''){
            getTotalTagihan();
            $('#winPembayaran').window('open');
            bayar.focus();
        } else {
            $.messager.show({
                title:'Pembayaran Obat',
                msg:'No Faktur masih kosong.',
                showType:'show'
            });
        }
    }
    
    function closeWinDetailObat(){
        frmDetailObat.reset();
        $("#winDetailObat").window('close');
    }
    
    function closeWinPembayaran(){
        frmPembayaran.reset();
        $("#winPembayaran").window('close');
    }
    
    function getTotalTagihan(){
        var dataString = "task=getTotalTagihanObat&id_faktur_penjualan=" + $("#id_faktur_penjualan").val();

        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    var diskon;
                    if($('#karyawan').val()=='1'){
                        diskon = (parseFloat(data) / 10);
                    } else {
                        diskon = 0;
                    }
                    
                    if($('#tipe_asuransi').val()=='1'){
                        if($('#ruang').val()!=''){
                            $('#asuransi').val(data);
                            $('#bayar').val(0);
                            $('#sisa').val(0);
                            bayar.disabled = true;
                            lunas.checked = true;
                            lunas.disabled = true;
                        }
                    }
                    
                    $('#total').val(data);
                    $('#diskonObat').val(diskon);
                    return false;
                }
            }  
        });  

        return false;
    }

    function getSisaBayar(){
        var sisa;
        var kembalian;
        var diskon;
                
        if($("#diskonObat").val()=="")
            diskon = 0;
        else
            diskon = parseFloat($("#diskonObat").val());

        sisa = parseFloat($("#total").val()) - diskon - parseFloat($("#bayar").val());
        kembalian = parseFloat($("#bayar").val()) - parseFloat($("#total").val()) - diskon;
        
        if(sisa<0) sisa=0;
        $("#sisa").val(sisa);
        if(kembalian<0)
            $("#kembalian").val('0');
        else
            $("#kembalian").val(kembalian);
    }
    
    function cetakKwitansi(){
        $.ajax({
            type: "GET",  
            url: "json/apotik.php",  
            data: "task=cetakKwitansiRetur&id_faktur_penjualan=" + $("#id_faktur_penjualan").val(),  
            success: function(dRet) {
                if(dRet=='1'){
                    var win = window.open('report/cetakKwitansiRetur.html','cetakPembayaranObat','height=600,width=1000,resizable=1,scrollbars=1, menubar=0');
                    bersihkan();
                }
            }
        });
    }
    
    function cetakUlang(){
        var bvalid = true;
        bvalid = bvalid && checkSelect($("#id_retur").val(), 'ID Retur');
        if(bvalid){
            $.ajax({
                type: "GET",  
                url: "json/apotik.php",  
                data: "task=cetakUlangRetur&id_retur=" + $("#id_retur").val(),  
                success: function(dRet) {
                    if(dRet=='1'){
                        var win = window.open('report/cetakKwitansiRetur.html','cetakPembayaranObat','height=600,width=1000,resizable=1,scrollbars=1, menubar=0');
                    }
                }
            });
        }
    }
    
    function bersihkan(){
        $("#id_pasien").val("");
        $("#jns_customer").val("");
        $("#nama_pasien").val("");
        $("#tipe_pasien").val("");
        $("#tipe_pendaftaran").val("");
        $("#tipe_asuransi").val("");
        $("#karyawan").val("");
        $("#tunaiF").val("");
        $("#alamat").val("");
        $("#ruang").val("");
        $("#no_resep").val("");
        $("#dokter").val("");
        $('#dataPenjualanObat').datagrid({
            url:'json/apotik.php?task=getDetailPenjualanObat&id_faktur_penjualan=0'
        });
        $('#dataReturPenjualanObat').datagrid({
            url:'json/apotik.php?task=getDetailReturPenjualanObat&id_faktur_penjualan=0'
        });
        return false;
    }
    
    function openWinDetailObat(){
        $('#btnRetur').linkbutton({
                disabled:false
            });
        var jns = $("#jns_customer").val();
        if($('#id_faktur_penjualan').val()!=""){
            if($("#tunaiF").val() == 2)
                tunai.checked = true;
            else
                tunai.checked = false;
            
            if(jns=='Umum'){
                $('#winDetailObat').window('open');
                jmlRetur.focus();
            } else {
                if($("#id_pasien").val()==''){
                    $.messager.show({
                        title:'Penjualan Obat',
                        msg:'No RM belum diisi.',
                        showType:'show'
                    });
                    id_pasien.focus();
                } else {
                    $('#winDetailObat').window('open');
                    jmlRetur.focus();
                }
            }
        } else {
            $.messager.show({
                title:'Penjualan Obat',
                msg:'Faktur belum dibuat.',
                showType:'show'
            });
        }
    }
    
</script>