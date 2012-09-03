<script>
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php'
    });
    $(function(){
        var noDftr = getURL('fid');
        var pasienId = getURL('pid');
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        id_pasien.focus();
        $('#frmSummary').form('load','json/perawatan.php?task=getSummary&no_pendaftaran=' + noDftr)

    });
    
    function simpanSummary(){
        var noDftr = getURL('fid');
        var pasienId = getURL('pid');
        var dataString = "task=simpanSummary" +
            "&id_pendaftaran=" + noDftr +
            "&id_pasien=" + pasienId +
            "&id_summary=" +$("#id_summary").val() +
            "&id_diagnosa=" +$("#id_diag").val() +
            "&id_detailD=" +$("#id_det").val() +
            "&dokter=" +$("#dokter").val() +
            "&keluhan=" +$("#keluhan").val() +
            "&penyakitPrimer=" +$("#penyakitPrimer").val() +
            "&penyakitPrimerId=" +$("#penyakitPrimerId").val() +
            "&lama=" +$("#lama").val() +
            "&penyakitLama=" +$("#penyakitLama").val() +
            "&obtAkhir=" +$("#obtAkhir").val() +
            "&etiologi=" +$("#etiologi").val() +
            "&tinggi_badan=" +$("#tinggi_badan").val() +
            "&berat_badan=" +$("#berat_badan").val() +
            "&nadi=" +$("#nadi").val() +
            "&tekanan_darah=" +$("#tekanan_darah").val() +
            "&temperatur=" +$("#temperatur").val() +
            "&nafas=" +$("#nafas").val() +
            "&hasilLab=" +$("#hasilLab").val() +
            "&hasilRad=" +$("#hasilRad").val() +
            //"&diagAkhir=" +$("#diagAkhir").val() +
            "&diagPa=" +$("#diagPa").val() +
            "&masalah=" +$("#masalah").val() +
            "&konsul=" +$("#konsul").val() +
            "&tindakan=" +$("#tindakan").val() +
            "&fasilitas=" +$("#fasilitas").val() +
            "&perjalanan=" +$("#perjalanan").val() +
            //"&keadaan=" +$("#keadaan").val() +
            //"&progno=" +$("#progno").val() +
            //"&sebabMati=" +$("#sebabMati").val() +
            "&usul=" +$("#usul").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#dokter").val(), 'Dokter');
        bvalid = bvalid && checkSelect($("#penyakitPrimer").val(), 'Diagnosa Akhir');
        bvalid = bvalid && checkSelect($("#penyakitPrimerId").val(), 'Diagnosa Akhir');
        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,
                success: function(data) {
                    alert(data);
                    if(data=='1'){
                        $.messager.show({
                            title:'Summary',
                            msg:'Summary berhasil disimpan.',
                            showType:'show'
                        });
                        return false;
                    } else {
                        $.messager.show({
                            title:'Summary',
                            msg:'Gagal menyimpan Summary.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    };
    
    function cetakSummary(){
        var noDftr = getURL('fid');
        $.ajax({  
            type: "GET",  
            url: "json/perawatan.php",  
            data: "task=cetakSummary&id_pendaftaran=" + noDftr,  
            success: function(dRet) {
                if(dRet=='1'){
                    var win = window.open('report/cetakSummary.html','cetakSummary','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                }
            }
        });
    }

</script>