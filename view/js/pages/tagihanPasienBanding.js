<script>
    $(function(){
        var noDftr = getURL('fid');
        var pasienId = getURL('pid');
        
        if (pasienId!=""){
            id_pasien.value=pasienId;
            loadDataTagihanPasien();
        }
        
        $('#statusBayar').linkbutton({
            disabled:true
        });
        
        $('#checkout').linkbutton({
            disabled:true
        });
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        
        id_pasien.focus();
        
        $.getJSON("json/data.php", {task: 'getTagihanPasienBanding', id_pendaftaran: noDftr, id_pasien: pasienId, id_kelas:''},
        function(data) {
            if(data.length>0){
                $("#detailTagihan").html(data[0].display);
            }
        });

        $( "#allAsuransi" ).click(function(){
            if(allAsuransi.checked) {
                var kurang = $("#kurang").val();
                $("#asuransi").val(kurang);
                $("#bayar").val(0);
            } else {
                $("#asuransi").val(0);
            }
        });
		
        $( "#lunas" ).click(function(){
            if(lunas.checked) {
                var kurang = $("#kurang").val();
                var asuransi = $("#asuransi").val();
                var sisa = kurang - asuransi;
                $("#bayar").val(sisa);
            } else {
                $("#bayar").val(0);
            }
        });
		
        $('#dataListPasien').datagrid({
            title:'Data Pasien',
            height:300,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'id_pasien',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'id_pasien',
            frozenColumns:[[
                    {title:'No RM',field:'id_pasien',width:50,sortable:true}
                ]],
            columns:[[
                    {field:'no_pendaftaran',title:'Nomor Pendaftaran',width:150,sortable:true,hidden:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:150,sortable:true},
                    {field:'jns_kelamin',title:'L/P',width:30,sortable:true},
                    {field:'tmp_lahir',title:'Tempat Lahir',width:100,sortable:true},
                    {field:'tgl_lahir',title:'Tanggal Lahir',width:80,sortable:true},
                    {field:'alamat',title:'Alamat',width:80,sortable:true},
                    {field:'kelurahan',title:'Kelurahan',width:80,sortable:true},
                    {field:'kecamatan',title:'Kecamatan',width:80,sortable:true},
                    {field:'kota',title:'Kota',width:80,sortable:true},
                    {field:'marital',title:'Marital',width:80,sortable:true},
                    {field:'asuransi',title:'Tipe Asuransi',width:80,sortable:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:80,sortable:true}
                ]],
            onDblClickRow:function(){
                var rows = $('#dataListPasien').datagrid('getSelections');
                $("#id_pasien").val(rows[0].id_pasien);
                $("#id_pendaftaran").val(rows[0].no_pendaftaran);
                loadDataTagihanPasien();
                closeWinSearchPasien();
            },
            pagination:true,
            rownumbers:true
        });
    });
    
    function openWinSearchPasien(){
        $("#winSearchPasien").window('open');
        srcNamaPasien.focus();
    }
    
    function closeWinSearchPasien(){
        $("#winSearchPasien").window('close');
        id_pasien.focus();
    }
    
    function loadDataListPasien(){
        $("#dataListPasien").datagrid({
            url:'json/data.php?task=getDataListPasien&nama_pasien=' + $('#srcNamaPasien').val() + 
                '&alamat=' + $('#srcAlamat').val() +
                '&tgl_lahir=' + $('#srcTglLahir').datebox("getValue") +
                '&tgl_lahir_to=' + $('#srcTglLahirTo').datebox("getValue") +
                '&kecamatan=' + $('#srcKecamatan').val() +
                '&kelurahan=' + $('#srcKelurahan').val() +
                '&asuransi=' + $('#srcAsuransi').val() +
                '&tipe_pasien=' + $('#srcTipePasien').val()
        })
        $('#dataListPasien').datagrid('reload')
    }
    
    function loadDataTagihanPasien(){
        $("#detailTagihan").html("<img src='../images/loader.gif'> Loading...");
        $('#frmDtlPasien').form('load','json/data.php?task=cariDtlPasienTagih&id_pasien=' + id_pasien.value)
        $.getJSON("json/data.php", {task: 'getTagihanPasienBanding', id_pendaftaran: '', 
                                    id_pasien: id_pasien.value,
                                    id_kelas: kelas.value},
        function(data) {
            if(data.length>0){
                if(data[0].status=='1'){
                    $('#statusBayar').linkbutton({
                        disabled:false
                    });
                    $('#checkout').linkbutton({
                        disabled:true
                    });
                } else if (data[0].status=='2'){                    
                    $('#statusBayar').linkbutton({
                        disabled:true
                    });
                    $('#checkout').linkbutton({
                        disabled:true
                    });
                } else if (data[0].status=='0'){                    
                    $('#statusBayar').linkbutton({
                        disabled:true
                    });
                    $('#checkout').linkbutton({
                        disabled:false
                    });
                }
                $("#detailTagihan").html(data[0].display);
            }
        });        
    }
    
    function openWinBayar(){
        frmBayar.reset();
        if($('#tipe_asuransi').val()=='2'){
            $('#asuransi').val('0');
            asuransi.disabled = true;
            allAsuransi.disabled = true;
        } else {
            $('#asuransi').val('0');
            asuransi.disabled = false;
            allAsuransi.disabled = false;
        }
        $('#frmBayar').form('load','json/data.php?task=getResumeTagihanPasien&id_pasien=' + id_pasien.value);
        $('#winBayar').window('open');
        $('#bayar').val('');
        bayar.focus();
    }

    function simpanPembayaran(){
        var status;
        var bayar;
        var sisa;
        var kembalian;
        
        if($("#sisa").val()==""){
            sisa = parseInt($("#kurang").val()) - parseInt($("#bayar").val()) - parseInt($("#asuransi").val());
            kembalian = parseInt($("#bayar").val()) - parseInt($("#kurang").val()) + parseInt($("#asuransi").val());
        } else {
            sisa = parseInt($("#sisa").val());
            kembalian = parseInt($("#kembalian").val());
        }
        
        if(sisa==0){
            status = "Lunas";
        } else {
            status = "Kurang";
        }
        
        if(kembalian>0)
            bayar = parseInt($("#bayar").val()) - kembalian;
        else
            bayar = parseInt($("#bayar").val());
        
        var dataString = "task=simpanPembayaranTagihan&id_pendaftaran=" + $("#id_pendaftaran").val() +
                       "&id_pasien=" + id_pasien.value + 
                       "&status=" + status + 
                       "&asuransi=" + $("#asuransi").val() + 
                       "&bayar=" + bayar;

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#bayar").val(), 'Bayar');

        if(bvalid){
            $.ajax({  
                type: "GET",  
                url: "json/data.php",  
                data: dataString,  
                success: function(data) {
                    var returnData = data.split(":");
                    var status = returnData[0];
                    if(status=='SUCCESS'){
                        $.messager.show({
                            title:'Pembayaran',
                            msg:'Pembayaran tagihan berhasil disimpan dengan status <b>' + returnData[1] + '</b>',
                            showType:'show'
                        });
                        $('#winBayar').window('close');
                        $.getJSON("json/data.php", {task: 'getTagihanPasien', id_pendaftaran: '', id_pasien: id_pasien.value},
                        function(data) {
                            if(data.length>0){
                                $("#detailTagihan").html(data[0].display);
                            }
                        });
                        if(returnData[3]=='1'){
                            if(returnData[1]=="Lunas"){
                                $.ajax({  
                                    type: "GET",  
                                    url: "json/data.php",  
                                    data: "task=cetakKwitansiLunas&id_pasien=" + $("#id_pasien").val() + "&id_pembayaran=" + returnData[2],  
                                    success: function(dRet) {
                                        if(dRet=='1'){
                                            var win = window.open('report/cetakKwitansiLunas.html','cetakKwitansi','height=400,width=300,resizable=1,scrollbars=0, menubar=0');
                                            //win.print();
                                        }
                                    }
                                });
                            } else {
                                if(returnData[4]>0) {
                                    $.ajax({  
                                        type: "GET",  
                                        url: "json/data.php",  
                                        data: "task=cetakKwitansiTagihan&id_pembayaran=" + returnData[2],  
                                        success: function(dRet) {
                                            if(dRet=='1'){
                                                var win = window.open('report/cetakKwitansiTagihan.html','cetakKwitansi','height=400,width=300,resizable=1,scrollbars=0, menubar=0');
                                                //win.print();
                                            }
                                        }
                                    });
                                }
                            }
                        } else {
                            if(returnData[1]=="Lunas"){
                                $.ajax({  
                                    type: "GET",  
                                    url: "json/data.php",  
                                    data: "task=cetakKwitansiLunas&id_pasien=" + $("#id_pasien").val() + "&id_pembayaran=" + returnData[2],  
                                    success: function(dRet) {
                                        if(dRet=='1'){
                                            var win = window.open('report/cetakKwitansiLunas.html','cetakKwitansi','height=400,width=300,resizable=1,scrollbars=0, menubar=0');
                                            //win.print();
                                        }
                                    }
                                });
                            } else {
                                $.ajax({  
                                    type: "GET",  
                                    url: "json/data.php",  
                                    data: "task=cetakKwitansiTagihan&id_pembayaran=" + returnData[2],  
                                    success: function(dRet) {
                                        if(dRet=='1'){
                                            var win = window.open('report/cetakKwitansiTagihan.html','cetakKwitansi','height=400,width=300,resizable=1,scrollbars=0, menubar=0');
                                            //win.print();
                                        }
                                    }
                                });
                            }
                        }
                        loadDataTagihanPasien();
                        //window.location='index.php?page=dftrtghnpx&pid=' + id_pasien.value;
                        return false;
                    } else if(status=='FAILED') {
                        $.messager.show({
                            title:'Pembayaran',
                            msg:'Gagal menyimpan Pembayaran.',
                            showType:'show'
                        });
                        $("#bayar").val("");
                        bayar.focus();
                        return false;
                    } else if(status=='kurang') {
                        $.messager.show({
                            title:'Pembayaran',
                            msg:'Pembayaran kurang (pasien tidak boleh kredit).',
                            showType:'show'
                        });
                    }
                }  
            });  
            return false;
        }
    }

    function openWinDiskon(){
        $('#frmDiskon').form('load','json/data.php?task=getResumeTagihanPasien&id_pasien=' + id_pasien.value);
        $('#winDiskon').window('open');
        diskon.focus();
    }

    function getSisaBayar(){
        var sisa;
        var kembalian;
        var kurang;
        var bayar;
        var asuransi;
        
        if($("#kurang").val()=="")
            kurang = 0;
        else
            kurang = parseInt($("#kurang").val());

        if($("#bayar").val()=="")
            bayar = 0;
        else
            bayar = parseInt($("#bayar").val());

        if($("#asuransi").val()=="")
            asuransi = 0;
        else
            asuransi = parseInt($("#asuransi").val());

        sisa = kurang - bayar - asuransi;
        kembalian = bayar - kurang + asuransi;
        
        if(sisa<0) sisa=0;
        $("#sisa").val(sisa);
        $("#kurang").val(kurang);
        $("#bayar").val(bayar);
        $("#asuransi").val(asuransi);
        
        if(kembalian<0)
            $("#kembalian").val('0');
        else
            $("#kembalian").val(kembalian);
    }
    
    function simpanDiskon(){
        var dataString = "task=simpanDiskonDokter&id_pendaftaran=" + id_pendaftaran.value +
            "&id_pasien=" + id_pasien.value + 
            "&diskon=" + $("#diskon").val() + 
            "&level=" + levelDiskon.value;

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#diskon").val(), 'Diskon');

        if(bvalid){
            if(parseInt($("#diskon").val()) > parseInt($("#kurang_diskon").val())){
                $.messager.show({
                    title:'Diskon',
                    msg:'Pemberian Diskon melibihi total tagihan.',
                    showType:'show'
                });
                $("#diskon").val("");
                diskon.focus();
            } else {
                $.ajax({  
                    type: "GET",  
                    url: "json/data.php",  
                    data: dataString,  
                    success: function(data) {
                        if(data=='1'){
                            $.messager.show({
                                title:'Diskon',
                                msg:'Diskon berhasil disimpan. ',
                                showType:'show'
                            });
                            $('#winDiskon').window('close');
                            loadDataTagihanPasien();
                            return false;
                        } else {
                            $.messager.show({
                                title:'Diskon',
                                msg:'Gagal menyimpan Diskon.',
                                showType:'show'
                            });
                            $("#diskon").val("");
                            diskon.focus();
                            return false;
                        }
                    }  
                });  
                return false;
            }
        }
    }

    function openClosePerawatan(){
        $('#winClosePerawatan').window('open');
        kondisiKeluar.focus();
    }

    function simpanClosePerawatan(){
        var dataString = "task=checkOutPasien&id_pasien=" + id_pasien.value +
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
                    window.location='index.php?page=dftrtghnpx';
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

    function cetakLaporanTagihan(){
        $.ajax({  
            type: "GET",  
            url: "json/data.php",  
            data: "task=cetakLaporanTagihanBanding&id_pasien=" + $("#id_pasien").val() + "&id_kelas=" + $("#kelas").val(),
            success: function(dRet) {
                if(dRet=='1'){
                    var win = window.open('report/cetakLaporanTagihan.html','cetakLaporan','height=400,width=1000,resizable=1,scrollbars=0, menubar=0');
                    //win.print();
                }
            }
        });
    }
    
</script>