<script>
    $(function(){
        var fid = getURL("fid");
        var pid = getURL("pid");
        var nid = getURL("nid");
        id_faktur_penjualan.focus();
        if(pid=="0"){
            if(fid!=""){
                $('#id_faktur_penjualan').val(fid);
                $("#nama_pasien").val(nid);
                $("#alamat").val("");
                if(pid=="0")
                    $("#jns_customer").val("Umum");
                else
                    $("#jns_customer").val("Pasien");
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
        
        $( "#lunas" ).click(function(){
            if(lunas.checked) {
                var diskon = parseFloat($("#diskonObat").val());
                var total = parseFloat($("#total").val());
                var asuransi = parseFloat($("#asuransi").val());
                var admin = 500;
                $("#asuransi").val('0');
                $("#administrasi").val(admin);
                $("#bayar").val(total - asuransi - diskon + admin);
            } else {
                $("#bayar").val("0");
            }
        });
		
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
    });
    
    function simpanPembayaran(){
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
                            cetakBayarObat(data);
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
                $.messager.show({
                    title:'Pembayaran Obat',
                    msg:'Pembayaran kurang (pasien tidak boleh kredit).',
                    showType:'show'
                });
            }
        }
        return false;
    };
    
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
    
    function getDiskonTemp(){
        var dataString = "task=getDiskonTemp&faktur=" + $("#id_faktur_penjualan").val();
        $.ajax({
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data!='ERROR'){
                    $("#diskonObat").val(parseFloat(data));
                } else {
                    $("#diskonObat").val(0);
                }
            }
        });
        return false;
    };
    
    function newFakturPenjualan(){
        $("#id_faktur_penjualan").val("");
        $("#no_resep").val("");
        $("#jns_customer").val("");
        $("#dokter").val("");
        $("#id_pasien").val("");
        $("#ruang").val("");
        $("#nama_pasien").val("");
        $("#alamat").val("");
        $("#tipe_pasien").val("");
        $("#tipe_asuransi").val("");
        $("#karyawan").val("");
        $("#tipe_pendaftaran").val("");
        $("#detailTagihan").html("");
        $("#boleh").val("");
        id_faktur_penjualan.disabled = false;
        id_faktur_penjualan.focus();
        return false;
    }
    
    function openWinPembayaran(){
        if($('#id_faktur_penjualan').val()!=''){
            $('#bayarF').linkbutton({
                disabled:false
            });
            getTotalTagihan();
            $('#administrasi').val('0');
            $('#winPembayaran').window('open');
            if($("#diskonObat").val() == 0)
                getDiskonTemp();
            if($("#tipe_asuransi").val()=="2") {
                allAsuransi.disabled = true;
            } else if ($("#tipe_asuransi").val()=="") {
                allAsuransi.disabled = true;
            } else {
                if($("#level").val() == 46 && $('#tipe_asuransi').val()=='1'){
                    allAsuransi.disabled = false;
                    allAsuransi.checked = true;
                    lunas.disabled = true;
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

    function getTotalTagihan(){
        var dataString = "task=getTotalTagihanObatRetur&id_faktur_penjualan=" + $("#id_faktur_penjualan").val();

        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    var diskon;
                    if($('#karyawan').val()=='1' && $('#id_faktur_penjualan').val()==''){
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
                }
            }
        });        
    }
    
    function panggilFaktur(){
        
        var fakture = $("#id_faktur_penjualan").val();
        var dataString = "task=getFakturAll&id_faktur_penjualan="+ fakture;
        
        var bvalid = true;
        bvalid = bvalid && checkSelect($("#id_faktur_penjualan").val(), 'No Faktur');
        
        if(bvalid){
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
                        $("#detailTagihan").html(returnData[12]);
                        $("#boleh").val(returnData[14]);
                        id_faktur_penjualan.disabled = true;
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
                        $("#detailTagihan").html("Data tidak ditemukan");
                        return false;
                    }
                }
            });
        }
    }
    
    function cetakKW(){
        var fakture = $("#id_faktur_penjualan").val();
        var dataString = "task=cetakKWAll&id_faktur_penjualan=" + fakture
        
        var bvalid = true;
        bvalid = bvalid && checkSelect($("#id_faktur_penjualan").val(), 'No Faktur');
        
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
    
</script>
