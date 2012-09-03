<script>
    $(function(){
        if(getURL("fid")!=""){
            $("#id_faktur_penjualan").val(getURL("fid"));
            getDtlFaktur();
        }
        id_faktur_penjualan.focus()
    });
    
    function simpanPembayaran(){
        if(cekLunas==true&&!$('#lunas').attr('checked')) {
            var dataString = "task=hapusPembayaranJualObat&id_faktur_penjualan=" + $("#id_faktur_penjualan").val();
        	
            $.ajax({  
                type: "GET",  
                url: "json/apotik.php",  
                data: dataString,  
                success: function(data) {
                    if(data!='0'){
                        $.messager.show({
                            title:'Pembatalan Pembayaran',
                            msg:'Pembatalan Pembayaran Telah Berhasil.',
                            showType:'show'
                        });
                        frmFakturPenjualan.reset();
                        frmPembayaran.reset();
                        $("#detailTagihan").val("");
                        $("#tipe_pendaftaran").val("");
                        $("#tipe_asuransi").val("");
                        $("#karyawan").val("");
                        closeWinPembayaran()
                        return false;
                    } else {
                        $.messager.show({
                            title:'Pembatalan Pembayaran Obat',
                            msg:'Pembatalan Pembayaran Obat gagal.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });
            return false;
        }
        return false;
    };
    
    function getDtlFaktur(){
        var dataString = "task=getDetailFakturPenjualanHapus&id_pasien=" + $("#id_pasien").val() +
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
                    $("#detailTagihan").html(returnData[12]);
                } else {
                    frmFakturPenjualan.reset();
                    $("#detailTagihan").html("Data tidak ditemukan");
                }
            }  
        });  
        return false;
    };
    
    function bersihkan(){
        $("#id_pasien").val("");
        $("#jns_customer").val("");
        $("#nama_pasien").val("");
        $("#tipe_pasien").val("");
        $("#tipe_pendaftaran").val("");
        $("#tipe_asuransi").val("");
        $("#karyawan").val("");
        $("#alamat").val("");
        $("#ruang").val("");
        $("#no_resep").val("");
    }

    function openWinPembayaran(){
        if($('#id_faktur_penjualan').val()!=''){
            getTotalTagihan();
            $('#winPembayaran').window('open');
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
        var dataString = "task=getTotalTagihanObat&id_faktur_penjualan=" + $("#id_faktur_penjualan").val();
        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    $.messager.show({
                        title:'Pembayaran Obat',
                        msg:'No Faktur Belum Ada Pembayaran.',
                        showType:'show'
                    });
                    closeWinPembayaran();
                } else {
                    $('#lunas').attr('checked', true);
                    window.cekLunas = true;
                }
            }  
        });  

        return false;
    }
</script>