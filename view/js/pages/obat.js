<script>
    $(function(){

        $('#detailObat').datagrid({
            title:'Detail Faktur',
            height:250,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            url:'json/obat.php?task=getDetailFaktur&id_faktur=' + $('#id_faktur').val(),
            sortName: 'kode_obat',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'kode_obat',
            frozenColumns:[[
                    {title:'ID',field:'kode_obat',width:30,sortable:true}
                ]],
            columns:[[
                    {field:'id_pembelian_obat',title:'ID Obat',width:150,sortable:true,hidden:true},
                    {field:'id_obat',title:'ID Obat',width:150,sortable:true,hidden:true},
                    {field:'id_penyimpanan',title:'ID Penyimpanan',width:150,sortable:true,hidden:true},
                    {field:'nama_obat',title:'Nama Obat',width:150,sortable:true},
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
            onDblClickRow:function(){
                var rows = $('#detailObat').datagrid('getSelections');
                var id_pembelian_obat = rows[0].id_pembelian_obat;
                $("#frmDetailFaktur").form("load",'json/obat.php?task=cariDtlObat&id_pembelian_obat=' + id_pembelian_obat);
                openWinDetailFaktur();
            },
            toolbar:[{
                    id:'btndelete',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var row = $('#detailObat').datagrid('getSelected');
                        var index = $('#detailObat').datagrid('getRowIndex', row);
                        $.ajax({  
                            type: "GET",  
                            url: "json/obat.php",  
                            data: "task=hapusDetailObat&id_pembelian_obat=" + row.id_pembelian_obat,  
                            success: function(data) {
                                if(data=='1'){
                                    $('#dataFaktur').datagrid('deleteRow', index);
                                    $('#dataFaktur').datagrid('reload');
                                    $('#detailObat').datagrid('deleteRow', index);
                                    $('#detailObat').datagrid('reload');
                                    $.messager.show({
                                        title:'Detail Faktur',
                                        msg:'Detail Faktur berhasil dihapus.',
                                        showType:'show'
                                    });
                                } else if (data=='2') {
                                    $.messager.show({
                                        title:'Detail Faktur',
                                        msg:'Gagal menghapus Detail Faktur. Obat Sudah Di distribusikan ke Gudang.',
                                        showType:'show'
                                    });
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Detail Faktur',
                                        msg:'Gagal menghapus Detail Faktur.',
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
        
        $('#dataFaktur').datagrid({
            title:'Detail Faktur Belum Terbayar',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            collapsible:true,
            //url:'json/obat.php?task=getFaktur',
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
                    {title:'Tanggal Jatuh Tempo',field:'tgl_jatuh_tempo',width:80,sortable:true,hidden:true},
                    {title:'Batas Tempo',field:'batas_tempo',width:80,sortable:true,hidden:true},
                    {field:'supplier',title:'Supplier',width:100,sortable:true,hidden:true}
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
                    ' Tgl Pembelian : ' + rows[0].tgl_pembelian + ' Tgl Jatuh Tempo : ' + rows[0].tgl_jatuh_tempo + ' (' + rows.length + ' Item(s))';
            },
            onDblClickRow:function(){
                var rows = $('#dataFaktur').datagrid('getSelections');
                var id_faktur = rows[0].id_faktur;
                $('#frmFaktur').form('load','json/obat.php?task=cariDtlFaktur&id_faktur=' + id_faktur);
                $('#detailObat').datagrid({
                    url:'json/obat.php?task=getDetailFaktur&id_faktur=' + id_faktur
                });
                $('#detailObat').datagrid("reload");
                id_faktur.disabled = true;
                no_faktur.disabled = true;
                supplier.disabled = true;
                tgl_pembelian.disabled = true;
                tgl_jatuh_tempo.disabled = true;
                $('#simpanFaktur').linkbutton({
                    disabled:true
                });
                openWinFaktur();
            },
            toolbar:[{
                    id:'btndelete',
                    text:'Hapus',
                    iconCls:'icon-remove',
                    handler:function(){
                        var row = $('#dataFaktur').datagrid('getSelected');
                        var index = $('#dataFaktur').datagrid('getRowIndex', row);
                        $.ajax({  
                            type: "GET",  
                            url: "json/obat.php",  
                            data: "task=hapusFakturObat&id_faktur=" + row.id_faktur,  
                            success: function(data) {
                                if(data=='1'){
                                    $('#dataFaktur').datagrid('deleteRow', index);
                                    $('#dataFaktur').datagrid('reload');
                                    $.messager.show({
                                        title:'Faktur Pembelian Obat',
                                        msg:'Faktur berhasil dihapus.',
                                        showType:'show'
                                    });
                                } else if (data=='2') {
                                    $.messager.show({
                                        title:'Faktur Pembelian Obat',
                                        msg:'Gagal menghapus Faktur. Obat Sudah Di distribusikan ke Gudang.',
                                        showType:'show'
                                    });
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Faktur Pembelian Obat',
                                        msg:'Gagal menghapus Faktur.',
                                        showType:'show'
                                    });
                                }
                            }
                        })
                    }
                }],
            pagination:true,
            rownumbers:true,
            rowStyler:function(index,row,css){
                if (row.batas_tempo<=7){
                    return 'background-color:#FF0000;';
                }
            }
        });
    });
    
    function saveFaktur(){
        var dataString = "task=simpanFaktur&no_faktur=" + $("#no_faktur").val() +
            "&supplier=" + $("#supplier").val() + 
            "&tgl_pembelian=" + $("#tgl_pembelian").val() +
            "&tgl_jatuh_tempo=" + $("#tgl_jatuh_tempo").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#no_faktur").val(), 'No Faktur');
        bvalid = bvalid && checkSelect($("#supplier").val(), 'Supplier');
        bvalid = bvalid && checkSelect($("#tgl_pembelian").val(), 'Tanggal Pembelian');
        bvalid = bvalid && checkSelect($("#tgl_jatuh_tempo").val(), 'Tanggal Jatuh Tempo');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/obat.php",  
                data: dataString,  
                success: function(data) {
                    if(data == 'LOGIN') {    
                    alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                    window.location.reload();
                    } else if(data!='0'){
                        $("#id_faktur").val(data);
                        id_faktur.disabled = true;
                        no_faktur.disabled = true;
                        supplier.disabled = true;
                        tgl_pembelian.disabled = true;
                        tgl_jatuh_tempo.disabled = true;
                        $('#simpanFaktur').linkbutton({
                            disabled:true
                        });
                        $('#detailObat').datagrid({
                            url:'json/obat.php?task=getDetailFaktur&id_faktur=' + $('#id_faktur').val()
                        });
                        openWinDetailFaktur();
                        return false;
                    } else {
                        $.messager.show({
                            title:'Pembelian Obat',
                            msg:'Faktur gagal disimpan.',
                            showType:'show'
                        });
                        frmDetailObat.reset();
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function simpanBeliObat(){
        var qty = 0;
        var diskon = 0;
        var harga = 0;
        if($("#jumlah").val()!="") qty = parseInt($("#jumlah").val());
        if($("#retur").val()!="") retur = parseInt($("#retur").val());
        if($("#harga").val()!="") harga = parseFloat($("#harga").val());
        if($("#diskon").val()!="") diskon = parseFloat($("#diskon").val());
        var total = qty * harga;
        var diskon = diskon/100 * total; 
        var pajak = (total - diskon) * 0.1;

        var dataString = "task=simpanBeliObat&id_faktur=" + $("#id_faktur").val() +
            "&id_obat=" + $("#nama_obatBeliId").val() + 
            "&id_pembelian_obat=" + $("#id_pembelian_obat").val() + 
            "&penyimpanan=" + $("#penyimpanan").val() + 
            "&qty=" + $("#jumlah").val() + 
            "&harga=" + $("#harga").val() + 
            "&retur=" + $("#retur").val() + 
            "&diskon=" + $("#diskon").val() + 
            "&pajak=" + pajak + 
            "&tgl_kadaluarsa=" + $("#tgl_kadaluarsa").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#id_faktur").val(), 'No Faktur');
        bvalid = bvalid && checkSelect($("#nama_obatBeliId").val(), 'Obat');
        bvalid = bvalid && checkSelect($("#jumlah").val(), 'Quantity');
        bvalid = bvalid && checkSelect($("#harga").val(), 'Harga');
        bvalid = bvalid && checkSelect($("#tgl_kadaluarsa").val(), 'Tanggal Kadaluarsa');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/obat.php",  
                data: dataString,  
                success: function(data) {
                if(data == 'LOGIN'){    
                    alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                    window.location.reload();
                } else if(data == 'DUPLIKAT'){
                    $.messager.show({
                        title:'Pembelian Obat',
                        msg:'Pembelian Obat gagal disimpan. Terjadi Duplikasi',
                        showType:'show'
                    });
                    frmDetailFaktur.reset();
                    $("#nama_obatBeliId").val("");
                    $("#id_pembelian_obat").val("");
                    nama_obatBeli.focus();
                } else if(data>'0'){
                        frmDetailFaktur.reset();
                        $("#nama_obatBeliId").val("");
                        $("#id_pembelian_obat").val("");
                        $('#detailObat').datagrid('reload');
                        $("#frmHarga").form("load",'json/obat.php?task=generateHargaObat&id_pembelian_obat=' + data);
                        openWinHarga();
                        return false;
                    } else if (data=='0'){
                        $.messager.show({
                            title:'Pembelian Obat',
                            msg:'Pembelian Obat gagal disimpan.',
                            showType:'show'
                        });
                        frmDetailFaktur.reset();
                        $("#nama_obatBeliId").val("");
                        $("#id_pembelian_obat").val("");
                        return false;
                    } else if (data=='-1'){
                        $.messager.show({
                            title:'Pembelian Obat',
                            msg:'Pembelian Obat gagal disimpan. Faktur sudah di distribusikan ke gudang',
                            showType:'show'
                        });
                        frmDetailFaktur.reset();
                        $("#nama_obatBeliId").val("");
                        $("#id_pembelian_obat").val("");
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function openWinHarga(){
        $('#winHarga').window('open');
        hpp.focus();
    }
    
    function closeWinHarga(){
        frmHarga.reset();
        $("#winHarga").window('close');
        nama_obatBeli.focus();
    }
    
    function newFaktur(){
        frmFaktur.reset();
        no_faktur.disabled = false;
        supplier.disabled = false;
        tgl_jatuh_tempo.disabled = false;
        tgl_pembelian.disabled = false;
        $('#simpanFaktur').linkbutton({
            disabled:false
        });
        $('#detailObat').datagrid({
            url:'json/obat.php?task=getDetailFaktur&id_faktur=' + $('#id_faktur').val()
        });
        $('#id_faktur').val("");
        no_faktur.focus();
    }
    
    function openWinFaktur(){
        $('#winFaktur').window('open');
        no_faktur.focus();
    }
    
    function closeWinFaktur(){
        frmFaktur.reset();
        $("#id_faktur").val("");
        tgl_pembelian.disabled = false;
        tgl_jatuh_tempo.disabled = false;
        supplier.disabled = false;
        no_faktur.disabled = false;
        $('#simpanFaktur').linkbutton({
            disabled:false
        });
        $("#winFaktur").window('close');
        $('#detailObat').datagrid({
            url:'json/obat.php?task=getDetailFaktur&id_faktur=' + $('#id_faktur').val()
        });
        $("#dataFaktur").datagrid('reload');
    }
    
    function openWinDetailFaktur(){
        $('#winDetailFaktur').window('open');
        nama_obatBeli.focus();
    }
    
    function closeWinDetailFaktur(){
        frmDetailFaktur.reset();
        $("#winDetailFaktur").window('close');
        no_faktur.focus();
    }
    
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:1,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate:getHargaHpp
    });
    
    function getHargaHpp(id){
        $.ajax({
            type: "GET",
            url: "json/obat.php",
            data: "task=getHargaObat&id=" + id,
            success: function(data) {
                $("#harga").val(data);
            }
        });
        jumlah.focus();
    }
    
    function loadData(){
    $('#dataFaktur').datagrid({
        url:'json/obat.php?task=getFaktur&kode=' + $('#kode_obat').val() + '&nama=' + $('#obat').val() + '&startDate=' + $('#startDate').val() + '&endDate=' + $('#endDate').val() + '&ids=' + $('#ids').val()
    });
}
    
    function getPajak(){
        var qty = 0;
        var diskon = 0;
        var harga = 0;
        if($("#jumlah").val()!="") qty = parseInt($("#jumlah").val());
        if($("#retur").val()!="") retur = parseInt($("#retur").val());
        if($("#harga").val()!="") harga = parseFloat($("#harga").val());
        if($("#diskon").val()!="") diskon = parseFloat($("#diskon").val());
        var total = qty * harga;
        var diskon = diskon/100 * total; 
        var pajak = (total - diskon) * 0.1;
        
        $("#pajak").val(pajak);
    }
    
    function getDiskonP(){
        var diskonP = 0;
        if($("#jumlah").val()!="") qty = parseInt($("#jumlah").val());
        if($("#harga").val()!="") harga = parseFloat($("#harga").val());
        var total = qty * harga;
        var diskonP = ($("#rupiahe").val() / total) * 100;
        if (diskonP > 100){
            $("#rupiahe").val("");
            $("#diskon").val("");
            rupiahe.focus();
        } else if (diskonP <= 100) {
            $("#diskon").val(diskonP);
            diskon.focus();
        }
    }
    
    function updateHarga(){
        var dataString = "task=updateHarga&id_obat=" + $("#id_obat").val() +
            "&hpp=" + $("#hpp").val() +
            "&umum=" + $("#umum").val() +
            "&askes=" + $("#askes").val() +
            "&jps=" + $("#jps").val();

        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    $.messager.show({
                        title:'Update Harga Obat',
                        msg:'Update Harga Obat berhasil.',
                        showType:'show'
                    });
                    closeWinHarga();
                    nama_obatBeli.focus();
                    return false;
                } else {
                    $.messager.show({
                        title:'Update Harga Obat',
                        msg:'Update Harga Obat gagal.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    };
    
    var cal1;
    window.onload = function() {
        cal1 = new dhtmlxCalendarObject(['tgl_pembelian', 'tgl_jatuh_tempo','startDate','endDate','tgl_kadaluarsa'],true, {
            isYearEditable: true,
            isMonthEditable: true
        });
        cal1.setSkin('simplecolordark');
    }
    
</script>