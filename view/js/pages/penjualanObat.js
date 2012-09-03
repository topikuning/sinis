<script>
    $(function(){  
        var fid = getURL("fid");
        var pid = getURL("pid");
        var nid = getURL("nid");
        id_pasien.focus();
        if(pid=="0"){
            if(fid!=""){
                $('#id_faktur_penjualan').val(fid);
                $("#nama_pasien").val(nid);
                $("#alamat").val("");
                if(pid=="0")
                    $("#jns_customer").val("Umum");
                else
                    $("#jns_customer").val("Pasien");
                $('#dataPenjualanObat').datagrid({
                    url:'json/apotik.php?task=getDetailPenjualanObat&id_faktur_penjualan=' + fid
                });
                $('#dataPenjualanObat').datagrid("reload");
                $('#dataRacikanObat').datagrid({
                    url:'json/apotik.php?task=getDetailRacikanObat&id_faktur_penjualan=' + fid
                });
                $('#dataRacikanObat').datagrid("reload");
                $('#id_faktur_penjualan').val(fid);
            }
        } else {
            if(fid!=""){
                $('#id_faktur_penjualan').val(fid);
                $("#id_pasien").val(pid);
                getDtlPasien();
                $('#id_faktur_penjualan').val(fid);
            }
        }
		
        $( "#allAsuransi" ).click(function(){
            if(allAsuransi.checked) {
                var diskon = parseFloat($("#diskonObat").val());
                var total = parseFloat($("#total").val());
                $("#asuransi").val(total - diskon);
                $("#bayar").val("0");
            } else {
                $("#asuransi").val("0");
            }
        });
		
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
            pageList: [50,100],
            idField:'id_obat',
            frozenColumns:[[
                    {title:'No Faktur',field:'id_penjualan_obat',width:50,sortable:true,hidden:true},
                    {title:'No Faktur',field:'no_faktur',width:50,sortable:true,hidden:true},
                    {title:'ID Obat',field:'id_obat',width:50,sortable:true,hidden:true},
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
            onDblClickRow:function(rowIndex){
                var row = $('#dataPenjualanObat').datagrid('getSelected');
                var id_penjualan_obat = row.id_penjualan_obat;
                $('#frmDetailObat').form('load','json/apotik.php?task=getDetailObat&id_penjualan_obat=' + id_penjualan_obat);
                openWinDetailObat();
                setTimeout("checkCodeR()",300);
            },
            toolbar:[{
                    id:'btndelete',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var row = $('#dataPenjualanObat').datagrid('getSelected');
                        var index = $('#dataPenjualanObat').datagrid('getRowIndex', row);
                        $.ajax({  
                            type: "GET",  
                            url: "json/apotik.php",  
                            data: "task=hapusDetailObat&id_penjualan_obat=" + row.id_penjualan_obat + "&id_faktur_penjualan=" + row.no_faktur,
                            success: function(data) {
                                if(data=='1'){
                                    $('#dataPenjualanObat').datagrid('deleteRow', index);
                                    $('#dataPenjualanObat').datagrid('reload');
                                    $.messager.show({
                                        title:'Penjualan Obat',
                                        msg:'Obat berhasil dihapus.',
                                        showType:'show'
                                    });
                                } else if (data=='2') {
                                    $.messager.show({
                                        title:'Penjualan Obat',
                                        msg:'Gagal menghapus Obat. Sudah ada pembayaran Faktur.',
                                        showType:'show'
                                    });
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Penjualan Obat',
                                        msg:'Gagal menghapus Obat.',
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

        $('#dataRacikan').datagrid({
            title:'Detail Racikan',
            height:250,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            collapsible:true,
            url:'json/apotik.php?task=getDetailRacikan&id_racikan=' + $('#id_racikan').val(),
            sortName: 'id_obat',
            remoteSort: false,
            chace:false,
            idField:'id_obat',
            frozenColumns:[[
                    {title:'ID Obat',field:'id_detail_racikan',width:50,sortable:true,hidden:true},
                    {title:'ID Obat',field:'no_faktur',width:50,sortable:true,hidden:true},
                    {title:'ID Obat',field:'id_obat',width:50,sortable:true,hidden:true},
                    {title:'Nama Obat',field:'nama_obat',width:250,sortable:true}
                ]],
            columns:[[
                    {field:'qty',title:'Qty',width:50,sortable:true},
                    {field:'rCode',title:'Kode R',width:50,sortable:true},
                    {field:'harga',title:'Harga',width:120,align:'right'},
                    {field:'total',title:'Total',width:120,align:'right'}
                ]],
            pagination:true,
            rownumbers:true,
            toolbar:[{
                    id:'btndelete',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var row = $('#dataRacikan').datagrid('getSelected');
                        var index = $('#dataRacikan').datagrid('getRowIndex', row);
                        $.ajax({  
                            type: "GET",  
                            url: "json/apotik.php",  
                            data: "task=hapusDetailRacikan&id_detail_racikan=" + row.id_detail_racikan + "&id_faktur_penjualan=" + row.no_faktur,  
                            success: function(data) {
                                if(data=='1'){
                                    $('#dataRacikan').datagrid('deleteRow', index);
                                    $.messager.show({
                                        title:'Penjualan Obat',
                                        msg:'Detail Racikan berhasil dihapus.',
                                        showType:'show'
                                    });
                                } else if (data=='2') {
                                    $.messager.show({
                                        title:'Penjualan Obat',
                                        msg:'Gagal menghapus Detail Racikan. Sudah ada pembayaran Faktur.',
                                        showType:'show'
                                    });
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Penjualan Obat',
                                        msg:'Gagal menghapus Detail Racikan.',
                                        showType:'show'
                                    });
                                }
                            }
                        })
                    }
                }]
        });
    
        $('#dataRacikanObat').datagrid({
            title:'Detail Racikan',
            height:250,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            collapsible:true,
            collapsed:true,
            url:'json/apotik.php?task=getDetailRacikanObat&id_faktur_penjualan=' + $('#id_faktur_penjualan').val(),
            sortName: 'id_obat',
            remoteSort: false,
            chace:false,
            idField:'id_obat',
            frozenColumns:[[
                    {title:'ID Racikan',field:'id_racikan',width:50,sortable:true,hidden:true},
                    {title:'ID Racikan',field:'no_faktur',width:50,sortable:true,hidden:true},
                    {title:'Racikan',field:'racikan',width:50,sortable:true,hidden:true},
                    {title:'ID Obat',field:'id_obat',width:50,sortable:true,hidden:true},
                    {title:'Nama Obat',field:'nama_obat',width:250,sortable:true}
                ]],
            columns:[[
                    {field:'qty',title:'Qty',width:50,sortable:true},
                    {field:'rCode',title:'Kode R',width:50,sortable:true},
                    {field:'harga',title:'Harga',width:120,align:'right'},
                    {field:'total',title:'Total',width:120,align:'right'}
                ]],
            groupField: 'id_racikan',
            view: groupview,
            groupFormatter:function(value, rows){  
                return 'Racikan : ' + rows[0].racikan + ' - ' + rows.length + ' Item(s)';
            },
            onDblClickRow:function(rowIndex){
                var row = $('#dataRacikanObat').datagrid('getSelected');
                var id_racikan = row.id_racikan;
                $('#frmRacikan').form('load','json/apotik.php?task=getRacikan&id_racikan=' + id_racikan);
                $('#dataRacikan').datagrid({
                    url:'json/apotik.php?task=getDetailRacikan&id_racikan=' + id_racikan
                });
                $('#dataRacikan').datagrid("reload");
                openWinRacikan();
            },
            pagination:true,
            rownumbers:true,
            toolbar:[{
                    id:'btndelete',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var row = $('#dataRacikanObat').datagrid('getSelected');
                        var index = $('#dataRacikanObat').datagrid('getRowIndex', row);
                        $.ajax({  
                            type: "GET",  
                            url: "json/apotik.php",  
                            data: "task=hapusRacikan&id_racikan=" + row.id_racikan + "&id_faktur_penjualan=" + row.no_faktur,  
                            success: function(data) {
                                if(data=='1'){
                                    $('#dataRacikanObat').datagrid('deleteRow', index);
                                    $('#dataRacikanObat').datagrid('reload');
                                    $.messager.show({
                                        title:'Penjualan Obat',
                                        msg:'Racikan berhasil dihapus.',
                                        showType:'show'
                                    });
                                } else if (data=='2') {
                                    $.messager.show({
                                        title:'Penjualan Obat',
                                        msg:'Gagal menghapus Racikan. Sudah ada pembayaran Faktur.',
                                        showType:'show'
                                    });
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Penjualan Obat',
                                        msg:'Gagal menghapus Racikan.',
                                        showType:'show'
                                    });
                                }
                            }
                        })
                    }
                }]
        });
    });
    
    $('#dataFaktur').datagrid({
            title:'Data Faktur',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            collapsible:true,
            url:'json/apotik.php?task=getDataFaktur',
            sortName: 'no_faktur',
            remoteSort: false,
            chace:false,
            idField:'no_faktur',
            columns:[[
                    {title:'No Faktur',field:'no_faktur',width:50,sortable:true},
                    {title:'Nama Px',field:'px',width:120,sortable:true},
                    {title:'Nama Obat',field:'nama_obat',width:130,sortable:true},
                    {field:'qty',title:'Qty',width:50,sortable:true,align:'right'},
                    {field:'operator',title:'Operator',width:200}
                ]],
            toolbar:[{
                    id:'btndelete',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var row = $('#dataFaktur').datagrid('getSelected');
                        var index = $('#dataFaktur').datagrid('getRowIndex', row);
                        $.ajax({  
                            type: "GET",  
                            url: "json/apotik.php",  
                            data: "task=hapusFaktur&id_faktur_penjualan=" + row.no_faktur,
                            success: function(data) {
                                if(data=='1'){
                                    $('#dataFaktur').datagrid('reload');
                                    $.messager.show({
                                        title:'Data Faktur',
                                        msg:'Faktur berhasil dihapus.',
                                        showType:'show'
                                    });
                                } else if (data=='2') {
                                    $.messager.show({
                                        title:'Data Faktur',
                                        msg:'Gagal menghapus. Sudah ada pembayaran Faktur.',
                                        showType:'show'
                                    });
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Data Faktur',
                                        msg:'Gagal menghapus.',
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
    
    function checkCodeR(){
        if($("#kode_r").val()=='Ya') r_code.checked = true;
        else r_code.checked = false;
    }
    
    function diskonC(){
        if($('#diskonObat').val() > ($('#total').val() * 0.1)){
            $.messager.show({
                title:'DISKON',
                msg:'<b>DISKON LEBIH DARI 10%.<br>DISKON AKAN DISET 10%</b>',
                showType:'show'
            });
            $('#diskonObat').val($('#total').val() * 0.1);
        }
    }
    
    function simpanFakturPenjualan(){
        var dataString = "task=simpanFakturPenjualan&no_resep=" + $("#no_resep").val() +
            "&jns_customer=" + $("#jns_customer").val() +
            "&dokter=" + $("#dokter").val() +
            "&id_pasien=" + $("#id_pasien").val() +
            "&id_ruang=" + $("#ruang").val() +
            "&nama_pasien=" + $("#nama_pasien").val() +
            "&idp=" + $("#idp").val() +
            "&idr=" + $("#idr").val() +
            "&alamat=" + $("#alamat").val();

        var bvalid = true;
        if($("#jns_customer").val()=="Pasien"){
            bvalid = bvalid && checkSelect($("#dokter").val(), 'Dokter');
            bvalid = bvalid && checkSelect($("#id_pasien").val(), 'No RM');
        }
        bvalid = bvalid && checkSelect($("#nama_pasien").val(), 'Nama Pasien');
        
        if(bvalid){
            $.ajax({  
                type: "GET",  
                url: "json/apotik.php",  
                data: dataString,
                success: function(data) {
                    if(data!='0' && data!='LOGIN'){
                        $.messager.show({
                            title:'Faktur Penjualan Obat',
                            msg:'Faktur Penjualan Obat berhasil disimpan.',
                            showType:'show'
                        });
                        $('#id_faktur_penjualan').val(data);
                        $("#idf").val("")
                        idf.disabled = true;
                        no_resep.disabled = true;
                        dokter.disabled = true;
                        jns_customer.disabled = true;
                        id_pasien.disabled = true;
                        nama_pasien.disabled = true;
                        alamat.disabled = true;
                        openWinDetailObat();
                        return false;
                    } else if (data=='0') {
                        $.messager.show({
                            title:'Faktur Penjualan Obat',
                            msg:'Gagal menyimpan Faktur Penjualan Obat.',
                            showType:'show'
                        });
                        return false;
                    } else if (data=='LOGIN') {
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    }
                }  
            });  
        }
        return false;
    };
    
    function simpanRacikan(){
        var dataString = "task=simpanRacikan&racikan=" + $("#racikan").val() +
            "&id_faktur_penjualan=" + $("#id_faktur_penjualan").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#racikan").val(), 'Racikan');
        
        if(bvalid){
            $.ajax({  
                type: "GET",  
                url: "json/apotik.php",  
                data: dataString,  
                success: function(data) {
                    if(data!='0'){
                        $.messager.show({
                            title:'Penjualan Obat',
                            msg:'Racikan Obat berhasil disimpan.',
                            showType:'show'
                        });
                        $('#id_racikan').val(data);
                        racikan.disabled = true;
                        openWinDetailRacikan();
                        return false;
                    } else {
                        $.messager.show({
                            title:'Penjualan Obat',
                            msg:'Gagal menyimpan Racikan Obat.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
        }
        return false;
    };
    
    function reStock(){
        var bvalid = true;
        bvalid = bvalid && checkSelect($("#nama_obatId").val(), 'Obat');
        
        if(bvalid){
            var dataString = "task=stockUlang&id_obat=" + $("#nama_obatId").val() +
                "&id_penjualan=" + $("#id_penjualan_obat").val();
            $.ajax({  
                type: "GET",  
                url: "json/apotik.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    }
                    if(data=='1'){
                        $.messager.show({
                            title:'Stock Ulang',
                            msg:'STOCK ULANG BERHASIL,<br> <b> SILAHKAN CEK LAGI STOK</b>.',
                            showType:'show'
                        });
                        return false;
                    } else {
                        $.messager.show({
                            title:'Stock Ulang',
                            msg:'GAGAL MELAKUKAN RE-STOCK.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });
        }
        return false;
    };
    
    function simpanDiskonTemp(){
        var dataString = "task=simpanDiskonTemp&diskon=" + $("#diskonObat").val() +
            "&faktur=" + $("#id_faktur_penjualan").val();
        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data=='1'){
                    return false;
                } else {
                    $.messager.show({
                        title:'Diskon Bermasalah',
                        msg:'GAGAL MENYIMPAN DISKON.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    };
    
    function simpanPembayaran(){
        diskonC();
        var sisa;
        var diskon;
        var asuransi;
        if($("#diskonObat").val()=="")
            diskon = 0;
        else
            diskon = parseFloat($("#diskonObat").val());
        if($("#asuransi").val()=="")
            asuransi = 0;
        else
            asuransi = parseFloat($("#asuransi").val());
        sisa = parseFloat($("#total").val()) - asuransi - diskon - parseFloat($("#bayar").val());
        
        if(sisa<0) sisa = 0;
        if(kredit.checked || allAsuransi.checked) {
            var dataString = "task=simpanPembayaran&id_faktur_penjualan=" + $("#id_faktur_penjualan").val() +
                "&total=" + $("#total").val() + 
                "&diskonObat=" + $("#diskonObat").val() + 
                "&bayar=" + $("#bayar").val() + 
                "&asuransi=" + $("#asuransi").val() + 
                "&sisa=" + sisa;

            var bvalid = true;

            bvalid = bvalid && checkSelect($("#bayar").val(), 'Pembayaran');

            if(bvalid){
                var ok = true;
                if(sisa>0){
                    if($("#tipe_pendaftaran").val()=="3" || $("#tipe_pendaftaran").val()=="4" || $("#tipe_pendaftaran").val()=="6" || $("#tipe_pendaftaran").val()=="8"){
                        ok=true;
                    } else {
                        ok=false;
                    }
                }

                if(ok){
                    $('#bayarF').linkbutton({
                        disabled:true
                    });
                    $.ajax({  
                        type: "GET",  
                        url: "json/apotik.php",  
                        data: dataString,
                        success: function(data) {
                            if(data=='LOGIN'){
                                alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                                window.location.reload();
                            } else if(data!='0' && data!='KREDIT' && data!='LOGIN'){
                                $.messager.show({
                                    title:'Pembayaran Obat',
                                    msg:'Pembayaran Obat berhasil.',
                                    showType:'show'
                                });
                                if($('#level').val() != 46){
                                cetakBayarObat(data);}
                                frmFakturPenjualan.reset();
                                frmPembayaran.reset();
                                newFakturPenjualan();
                                closeWinPembayaran();
                                return false;
                            } else if(data=='KREDIT') {
                                $.messager.show({
                                    title:'Pembayaran Obat',
                                    msg:'Faktur Sudah Pernah Dikreditkan',
                                    showType:'show'
                                });
                                return false;
                            } else {
                                $.messager.show({
                                    title:'Pembayaran Obat',
                                    msg:'Pembayaran Obat gagal.',
                                    showType:'show'
                                });
                                return false;
                            }
                        }  
                    });  
                } else {
                    if($("#diskonObat").val() > 0 && $("#diskonObat").val() != ""){
                        simpanDiskonTemp()
                    }
                    getStruk();
                    frmFakturPenjualan.reset();
                    frmPembayaran.reset();
                    newFakturPenjualan();
                    closeWinPembayaran();
                    return false;
                }
            }
        } else {
            if($("#diskonObat").val() > 0 && $("#diskonObat").val() != ""){
                simpanDiskonTemp()
            }
            getStruk();
            frmFakturPenjualan.reset();
            frmPembayaran.reset();
            newFakturPenjualan();
            closeWinPembayaran();
            return false;
        }
        return false;
    };
    
    function simpanDetailObat(){
        if(parseInt($("#qty").val())>0){
            var rCode;
            if(r_code.checked) rCode='Ya';
            else rCode = 'Tidak';
            var dataString = "task=simpanDetailObat&id_faktur_penjualan=" + $("#id_faktur_penjualan").val() +
                "&id_penjualan_obat=" + $("#id_penjualan_obat").val() + 
                "&id_obat=" + $("#nama_obatId").val() + 
                "&qty=" + $("#qty").val() + 
                "&harga=" + $("#harga").val() + 
                "&r_code=" + rCode;

            var bvalid = true;

            bvalid = bvalid && checkSelect($("#id_faktur_penjualan").val(), 'No Faktur');
            bvalid = bvalid && checkSelect($("#nama_obatId").val(), 'Obat');
            bvalid = bvalid && checkSelect($("#qty").val(), 'Quantity');
            bvalid = bvalid && checkSelect($("#harga").val(), 'Harga');

            if(bvalid){                  
                $.ajax({  
                    type: "GET",  
                    url: "json/apotik.php",  
                    data: dataString,  
                    success: function(data) {
                        var returnData = data.split(":");
                        var status = returnData[0];
                        if(status=='LOGIN'){
                            alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                            window.location.reload();
                        } else if(status=='DUPLIKAT'){
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Terjadi Duplikasi.',
                                showType:'show'
                            });
                            frmDetailObat.reset();
                            $("#id_penjualan_obat").val("");
                            $("#nama_obatId").val("");
                            nama_obat.focus();
                            return false;
                        } else if(status=='TRUE'){
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Penjualan Obat berhasil disimpan.',
                                showType:'show'
                            });
                            checkKadaluarsa($("#nama_obatId").val());
                            frmDetailObat.reset();
                            $("#id_penjualan_obat").val("");
                            $("#nama_obatId").val("");
                            $('#dataPenjualanObat').datagrid({
                                url:'json/apotik.php?task=getDetailPenjualanObat&id_faktur_penjualan=' + $('#id_faktur_penjualan').val()
                            });
                            nama_obat.focus();
                            return false;
                        } else if(status=='WARNING'){
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Penjualan Obat berhasil disimpan. Dengan Catatan <b>' + returnData[1] + '</b>',
                                showType:'show'
                            });
                            frmDetailObat.reset();
                            $('#dataPenjualanObat').datagrid({
                                url:'json/apotik.php?task=getDetailPenjualanObat&id_faktur_penjualan=' + $('#id_faktur_penjualan').val()
                            });
                            $("#id_penjualan_obat").val("");
                            $("#nama_obatId").val("");
                            nama_obat.focus();
                            return false;
                        } else if (status=='ERROR'){
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Penjualan Obat gagal disimpan. Karena <b>' + returnData[1] + '</b>',
                                showType:'show'
                            });
                            return false;
                        } else if (status=='BAYAR'){
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Gagal menghapus Obat. Sudah ada pembayaran Faktur.',
                                showType:'show'
                            });
                            return false;
                        }
                    }  
                });  
                return false;
            }
        } else {
            $.messager.show({
                title:'Penjualan Obat',
                msg:'Qty harus lebih dari 0.',
                showType:'show'
            });
            $("#qty").val("");
            qty.focus();
            return false;
        }
    };

    function simpanDetailRacikan(){
        if(parseInt($("#qtyRacikan").val())>0){
            var rCode;
            if(r_codeRacikan.checked) rCode='Ya';
            else rCode = 'Tidak';
            var dataString = "task=simpanDetailRacikan&id_racikan=" + $("#id_racikan").val() +
                "&id_obat=" + $("#nama_obat_racikanId").val() + 
                "&qty=" + $("#qtyRacikan").val() + 
                "&harga=" + $("#hargaRacikan").val() + 
                "&r_code=" + rCode;

            var bvalid = true;

            bvalid = bvalid && checkSelect($("#id_racikan").val(), 'Racikan');
            bvalid = bvalid && checkSelect($("#nama_obat_racikanId").val(), 'Obat');
            bvalid = bvalid && checkSelect($("#qtyRacikan").val(), 'Quantity');
            bvalid = bvalid && checkSelect($("#hargaRacikan").val(), 'Harga');

            if(bvalid){                  
                $.ajax({  
                    type: "GET",  
                    url: "json/apotik.php",  
                    data: dataString,  
                    success: function(data) {
                        var returnData = data.split(":");
                        var status = returnData[0];
                        if(status=='LOGIN'){
                            alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                            window.location.reload();
                        } else if(status=='DUPLIKAT'){
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Terjadi Duplikasi.',
                                showType:'show'
                            });
                            frmDetailRacikan.reset();
                            $("#nama_obat_racikanId").val("");
                            nama_obat_racikan.focus();
                            return false;
                        } else if(status=='TRUE'){
                            frmDetailRacikan.reset();
                            nama_obat_racikan.focus();
                            $("#nama_obat_racikanId").val("");
                            $('#dataRacikan').datagrid({
                                url:'json/apotik.php?task=getDetailRacikan&id_racikan=' + $('#id_racikan').val()
                            });
                            $('#dataRacikan').datagrid("reload");
                            return false;
                        } else if(status=='WARNING'){
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Penjualan Obat berhasil disimpan. Dengan Catatan <b>' + returnData[1] + '</b>',
                                showType:'show'
                            });
                            frmDetailRacikan.reset();
                            nama_obat_racikan.focus();
                            $("#nama_obat_racikanId").val("");
                            $('#dataRacikan').datagrid({
                                url:'json/apotik.php?task=getDetailRacikan&id_racikan=' + $('#id_racikan').val()
                            });
                            $('#dataRacikan').datagrid("reload");
                            return false;
                        } else if (status=='ERROR'){
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Penjualan Obat gagal disimpan. Karena <b>' + returnData[1] + '</b>',
                                showType:'show'
                            });
                            return false;
                        }
                    }  
                });  
                return false;
            }
        } else {
            $.messager.show({
                title:'Penjualan Obat',
                msg:'Qty harus lebih dari 0.',
                showType:'show'
            });
            $("#qtyRacikan").val("");
            qtyRacikan.focus();
            return false;
        }
    };

    function getDtlPasien(){
        var dataString = "task=getDetailPasien&id_pasien=" + $("#id_pasien").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#id_pasien").val(), 'NO Rekam Medis');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/apotik.php",  
                data: dataString,  
                success: function(data) {
                    var returnData = data.split("<>");
                    if(returnData[1] != ''){
                        $("#ruang").val(returnData[0]);
                        $("#nama_pasien").val(returnData[1]);
                        $("#alamat").val(returnData[2]);
                        $("#tipe_pendaftaran").val(returnData[3]);
                        if(returnData[3]=="")
                            $("#jns_customer").val("Umum");
                        else
                            $("#jns_customer").val("Pasien");
                        $("#tipe_pasien").val(returnData[4]);
                        $("#tipe_asuransi").val(returnData[7]);
                        $("#dokter").val(returnData[5]);
                        $("#karyawan").val(returnData[6]);
                        $("#id_faktur_penjualan").val(returnData[8]);
                        $("#idr").val(returnData[9]);
                        $("#idp").val(returnData[10]);
                        $("#usia").val(returnData[11]);
                        if(returnData[8]!=""){
                            $('#dataPenjualanObat').datagrid({
                                url:'json/apotik.php?task=getDetailPenjualanObat&id_faktur_penjualan=' + returnData[8]
                            });
                            $('#dataPenjualanObat').datagrid("reload");
                            $('#dataRacikanObat').datagrid({
                                url:'json/apotik.php?task=getDetailRacikanObat&id_faktur_penjualan=' + returnData[8]
                            });
                            $('#dataRacikanObat').datagrid("reload");
                            idf.disabled = true;
                            no_resep.disabled = true;
                            dokter.disabled = true;
                            jns_customer.disabled = true;
                            id_pasien.disabled = true;
                            nama_pasien.disabled = true;
                            alamat.disabled = true;
                        } else {
                            var rows = $('#dataPenjualanObat').datagrid('getRows');
                            for (var j=0; j<rows.length;j++){
                                $('#dataPenjualanObat').datagrid('deleteRow', 0);
                            }
                            nama_pasien.focus();
                        }
                    } else {
                        $.messager.show({
                            title:'Penjualan Obat',
                            msg:'Data Pasien tidak ditemukan.',
                            showType:'show'
                        });
                        $("#ruang").val("");
                        $("#nama_pasien").val("");
                        $("#alamat").val("");
                        $("#tipe_pendaftaran").val("");
                        $("#karyawan").val("");
                        $("#boleh").val("");
                        $("#idp").val("");
                        $("#idr").val("");
                        $("#tipe_asuransi").val("");
                        $("#id_pasien").val("");
                        $("#dokter").val("");
                        $("#tipe_pasien").val("");
                        $("#jenis_customer").val("Umum");
                        nama_pasien.focus();
                    }
                }  
            });  
            return false;
        } else {
            $("#ruang").val("");
            $("#nama_pasien").val("");
            $("#alamat").val("");
            $("#tipe_pendaftaran").val("");

        }
    };

    function checkKadaluarsa(id_obat){
        var dataString = "task=cekKadaluarsa&id_obat=" + id_obat;

        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data!=""){
                    $.messager.show({
                        title:'Obat',
                        msg:data,
                        showType:'show'
                    });
                }
            }  
        });  
        return false;
    };

    function openWinDetailObat(){
        var jns = $("#jns_customer").val();
        if($('#boleh').val()==0){
            if($('#id_faktur_penjualan').val()!=""){
                if(jns=='Umum'){
                    $('#winDetailObat').window('open');
                    nama_obat.focus();
			return false;
                } else {
                    if($("#id_pasien").val()==''){
                        $.messager.show({
                            title:'Penjualan Obat',
                            msg:'No RM belum diisi.',
                            showType:'show'
                        });
                        id_pasien.focus();
			return false;
                    } else {
                        $('#winDetailObat').window('open');
                        nama_obat.focus();
			return false;
                    }
                }
            } else {
                $.messager.show({
                    title:'Penjualan Obat',
                    msg:'Faktur belum dibuat.',
                    showType:'show'
                });
            }
        } else {
            $.messager.show({
                title:'Penjualan Obat',
                msg:'Faktur Sudah Pernah Dibayar',
                showType:'show'
            });
        }
    }
    
    function openWinDetailRacikan(){
        if($('#id_racikan').val()!=""){
            $('#winDetailRacikan').window('open');
            nama_obat_racikan.focus();
        } else {
            $.messager.show({
                title:'Penjualan Obat',
                msg:'Racikan belum dibuat.',
                showType:'show'
            });
        }
    }
    
    function openWinRacikan(){
        if($('#boleh').val()==0){
            var jns = $("#jns_customer").val();
            if($('#id_faktur_penjualan').val()!=""){
                if(jns=='Umum'){
                    $('#winRacikanObat').window('open');
                    nama_obat.focus();
                } else {
                    if($("#id_pasien").val()==''){
                        $.messager.show({
                            title:'Penjualan Obat',
                            msg:'No RM belum diisi.',
                            showType:'show'
                        });
                        id_pasien.focus();
                    } else {
                        $('#winRacikanObat').window('open');
                        nama_obat.focus();
                    }
                }
            } else {
                $.messager.show({
                    title:'Penjualan Obat',
                    msg:'Faktur belum dibuat.',
                    showType:'show'
                });
            }
        } else {
            $.messager.show({
                title:'Penjualan Obat',
                msg:'Faktur Sudah Pernah Dibayar',
                showType:'show'
            });
        }
    }
    
    function closeWinDetailObat(){
        frmDetailObat.reset();
        $("#id_penjualan_obat").val("");
        $("#nama_obatId").val("");
        $("#winDetailObat").window('close');
    }
    
    function closeWinDetailRacikan(){
        frmDetailRacikan.reset();
        $("#winDetailRacikan").window('close');
    }
    
    function closeWinRacikan(){
        racikan.disabled = false;
        frmRacikan.reset();
        $("#winRacikanObat").window('close');
        $('#dataRacikanObat').datagrid({
            url:'json/apotik.php?task=getDetailRacikanObat&id_faktur_penjualan=' + $('#id_faktur_penjualan').val()
        });
    }
    
    function openWinPembayaran(){
        if($('#id_faktur_penjualan').val()!=''){
            $('#bayarF').linkbutton({
                disabled:false
            });
            getTotalTagihan();
            $('#winPembayaran').window('open');
            if($("#tipe_asuransi").val()=="2") {
                //asuransi.disabled = true;
                allAsuransi.disabled = true;
            } else if ($("#tipe_asuransi").val()=="") {
                //asuransi.disabled = true;
                allAsuransi.disabled = true;
            } else {
                //asuransi.disabled = false;
                if($("#level").val() == 46 && $('#tipe_asuransi').val()=='1'){
                    allAsuransi.disabled = false;
                    allAsuransi.checked = true;
                    kredit.disabled = true;
                } else if($('#tipe_asuransi').val()=='1'){
                    allAsuransi.disabled = false;
                }
            }
        } else {
            $.messager.show({
                title:'Pembayaran Obat',
                msg:'No Faktur masih kosong.',
                showType:'show'
            });
        }
    }
    
    function closeWinPembayaran(){
        frmPembayaran.reset();
        $("#winPembayaran").window('close');
    }
    
    function newFakturPenjualan(){
        idf.disabled = false;
        no_resep.disabled = false;
        dokter.disabled = false;
        jns_customer.disabled = false;
        id_pasien.disabled = false;
        nama_pasien.disabled = false;
        alamat.disabled = false;
        frmFakturPenjualan.reset();
        $('#dataPenjualanObat').datagrid({
            url:'json/apotik.php?task=getDetailPenjualanObat&id_faktur_penjualan=' + $('#id_faktur_penjualan').val()
        });
        $('#dataPenjualanObat').datagrid("reload");
        $('#dataRacikanObat').datagrid({
            url:'json/apotik.php?task=getDetailRacikanObat&id_faktur_penjualan=' + $('#id_faktur_penjualan').val()
        });
        $('#dataRacikanObat').datagrid("reload");
        $("#tipe_pendaftaran").val("");
        $("#tipe_asuransi").val("");
        $("#karyawan").val("");
        $("#boleh").val("");
        $("#idr").val("");
        $("#idp").val("");
        id_pasien.focus();
    }
    
    function newRacikan(){
        id_racikan.value = "";
        frmRacikan.reset();
        racikan.disabled = false;
    }
    
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:1,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate:getHarga
    });
    
    $.fn.autosugguest({  
        className:'ausu-racikan',
        methodType:'POST',
        minChars:1,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate:getHargaRacikan
    });
    
    function getHarga(id){
        var dataString = "task=getHargaObat&id_obat=" + id +
            "&ruang=" + $("#ruang").val() +
            "&id_pasien=" + $("#id_pasien").val();
        
     if($("#nama_obatId").val()>0){
	$.ajax({
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    $('#harga').val(data);
                    $('#hargaRacikan').val(data);
                    qty.focus();
                    return false;
                }
            }  
        });  

        return false;
        }
    }
    
    function getHargaRacikan(id){
        var dataString = "task=getHargaObat&id_obat=" + id +
            "&ruang=" + $("#ruang").val() +
            "&id_pasien=" + $("#id_pasien").val();

        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    $('#harga').val(data);
                    $('#hargaRacikan').val(data);
                    qtyRacikan.focus();
                    return false;
                }
            }  
        });  

        return false;
    }

    function getTotalTagihan(){
        var dataString = "task=getTotalTagihanObatRetur&id_faktur_penjualan=" + $("#id_faktur_penjualan").val();

        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    var diskon;
                    if($('#karyawan').val()=='1' && $('#idf').val()==''){
                        diskon = (parseFloat(data) / 10);
                    } else {
                        diskon = 0;
                    }
                    
                    $('#total').val(data);
                    $('#diskonObat').val(diskon);
                    $('#bayar').val(0);
                    $('#sisa').val(0);
                    
                    if($("#level").val() == 46 && $('#tipe_asuransi').val()=='1'){
                        $('#asuransi').val(data);
                    } else {
                        $('#asuransi').val(0);
                    }
                    return false;
                }
                if(data=='0'){
                    var diskon;
                    if($('#karyawan').val()=='1'){
                        diskon = (parseFloat(data) / 10);
                    } else {
                        diskon = 0;
                    }
                    
                    $('#total').val(data);
                    $('#diskonObat').val(diskon);
                    $('#asuransi').val(0);
                    $('#bayar').val(0);
                    $('#sisa').val(0);
                    return false;
                }
            }  
        });  

        return false;
    }

    function hapusFaktur(){
        $.messager.confirm('Penjualan Obat', 'Hapus Faktur?', function(r){
            if (r){
                var dataString = "task=hapusFaktur&id_faktur_penjualan=" + $("#id_faktur_penjualan").val();

                $.ajax({  
                    type: "GET",  
                    url: "json/apotik.php",  
                    data: dataString,  
                    success: function(data) {
                        if(data=='1'){
                            newFakturPenjualan();
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Faktur berhasil dihapus.',
                                showType:'show'
                            });
                        } else if (data=='2') {
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Gagal menghapus Faktur. Sudah ada pembayaran Faktur.',
                                showType:'show'
                            });
                        } else if (data=='0') {
                            $.messager.show({
                                title:'Penjualan Obat',
                                msg:'Gagal menghapus Faktur.',
                                showType:'show'
                            });
                        }
                    }  
                });  
            }
        });
        return false;
    }

    function getSisaBayar(){
        var sisa;
        var kembalian;
        var diskon;
        var asuransi;
                
        if($("#diskonObat").val()=="")
            diskon = 0;
        else
            diskon = parseFloat($("#diskonObat").val());

        if($("#asuransi").val()=="")
            asuransi = 0;
        else
            asuransi = parseFloat($("#asuransi").val());

        sisa = parseFloat($("#total").val()) - asuransi - diskon - parseFloat($("#bayar").val());
        kembalian = parseFloat($("#bayar").val()) - asuransi - parseFloat($("#total").val()) - diskon;
        
        if(sisa<0) sisa=0;
        $("#sisa").val(sisa);
        if(kembalian<0)
            $("#kembalian").val('0');
        else
            $("#kembalian").val(kembalian);
    }
    
    function cetakBayarObat(idBayar){
        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: "task=cetakBayarObat&id_pembayaran_obat=" + idBayar,  
            success: function(dRet) {
                if(dRet=='1'){
                    var win = window.open('report/bayarObat.html','cetakPembayaranObat','height=600,width=1000,resizable=1,scrollbars=1, menubar=0');
                    //window.location.reload();
                }
            }
        });        
    }
    
    function panggilFaktur(){
        
        var fakture = $("#idf").val();
        var dataString = "task=getDetailFakturPenjualan&id_pasien="+
            "&id_faktur_penjualan=" + fakture +
            "&nama_pasien="
        
        var bvalid = true;
        bvalid = bvalid && checkSelect($("#idf").val(), 'No Faktur');
        
        if(bvalid){
            $.ajax({
                type: "GET",  
                url: "json/apotik.php",  
                data: dataString,  
                success: function(data) {
                    var returnData = data.split("<>");
                    if(returnData[2]!=''){
                        $("#idf").val(returnData[0]);
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
                        $("#detailTagihan").html(returnData[12]);
                        $("#boleh").val(returnData[14]);
                        $("#operator").val(returnData[15]);

                        $('#dataPenjualanObat').datagrid({
                            url:'json/apotik.php?task=getDetailPenjualanObat&id_faktur_penjualan=' + fakture
                        });
                        $('#dataPenjualanObat').datagrid("reload");
                        $('#dataRacikanObat').datagrid({
                            url:'json/apotik.php?task=getDetailRacikanObat&id_faktur_penjualan=' + fakture
                        });
                        $('#dataRacikanObat').datagrid("reload");
                        idf.disabled = true;
                        no_resep.disabled = true;
                        dokter.disabled = true;
                        jns_customer.disabled = true;
                        id_pasien.disabled = true;
                        nama_pasien.disabled = true;
                        alamat.disabled = true;
                        return false;
                    } else  {
                        $.messager.show({
                            title:'Penjualan Obat',
                            msg:'No Faktur Tidak Ditemukan.',
                            showType:'show'
                        });
                        return false;
                    }
                }
            });
        }
    }
    
    function cetakKW(){
        var fakture = $("#idf").val();
        var dataString = "task=cetakKW&id_faktur_penjualan=" + fakture
        
        var bvalid = true;
        bvalid = bvalid && checkSelect($("#idf").val(), 'No Faktur');
        
        if(bvalid){
            $.ajax({
                type: "GET",  
                url: "json/apotik.php",  
                data: dataString,  
                success: function(data) {
                    if(data > 0 && data!='E' && data!='N'){
                        $.ajax({
                            type: "GET",  
                            url: "json/apotik.php",  
                            data: "task=cetakBayarObat&id_pembayaran_obat=" + data,
                            success: function(dRet) {
                                if(dRet=='1'){
                                    var win = window.open('report/bayarObat.html','cetakPembayaranObat','height=600,width=1000,resizable=1,scrollbars=1, menubar=0');
                                }
                            }
                        });        
                        return false;
                    } else if (data=='N') {
                        $.messager.show({
                            title:'Penjualan Obat',
                            msg:'Faktur Tidak Ditemukan.',
                            showType:'show'
                        });
                        return false;
                    } else{
                        $.messager.show({
                            title:'Penjualan Obat',
                            msg:'Belum Ada Pembayaran.',
                            showType:'show'
                        });
                        return false;
                    }
                }
            });
        }
    }
    
    function kreditkan(){
        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: "task=kreditkan",  
            success: function(dRet) {
                alert(dRet);
                if(dRet=='1'){
                    $.messager.show({
                        title:'Penjualan Obat',
                        msg:'HOREEE.',
                        showType:'show'
                    });
                }
            }
        });
    }
    
    function getStruk(){
        var bvalid = true;
        bvalid = bvalid && checkSelect($("#id_faktur_penjualan").val(), 'Faktur');
        if(bvalid){
            $.getJSON("json/apotik.php", {task: 'getStruk', 
                      idf: $("#id_faktur_penjualan").val()},
            function(data) {
                if(data.length>0){
                    $("#detailLaporan").html(data[0].display);
                    var ele = document.getElementById("detailLaporan");
                    ele.style.display = "none";
                    cetakAja();
                }
            });
        }
    }
    
    function cariDataFaktur(){
        $('#dataFaktur').datagrid({            
            url:'json/apotik.php?task=getDataFaktur&obat=' + $('#cari_obat').val() + '&kode_obat=' + $('#cari_kode_obat').val() + '&operator=' +  $('#cr_op').val()
        });
    }
    
</script>
