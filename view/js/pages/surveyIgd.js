<script>
    
    $(function(){
    
        $( "#surveyKe" ).change(function(){
            if($(this).val() == 'now'){
                history.replaceState("", "", "index.php?page=igdsrv&fid="+$('#idd').val()+"&pid="+$('#id_pasien').val());
                $('#surveyIGD').form('load','json/perawatan.php?task=getSurvey&no_pendaftaran=' + $('#idd').val())
            } else {
                //window.location = "index.php?page=igdsrv&fid=" + $(this).val()+"&pid="+ $('#id_pasien').val();
                history.replaceState("", "", "index.php?page=igdsrv&fid="+$(this).val()+"&pid="+$('#id_pasien').val());
                $('#surveyIGD').form('load','json/perawatan.php?task=getSurvey&no_pendaftaran=' + $(this).val())
            }
        });
    
        var noDftr = getURL('fid');
        var pasienId = getURL('pid');
        $('#jam_d').timespinner({showSeconds:true});
        $('#jam_p').timespinner({showSeconds:true});
        $('#jam_t').timespinner({showSeconds:true});
        $('#jam_l').timespinner({showSeconds:true});
        $('#idd').val(noDftr);
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        $('#surveyIGD').form('load','json/perawatan.php?task=getSurvey&no_pendaftaran=' + noDftr)
        id_pasien.focus();        
    });
    
    function simpanSurvey(){
       
        var dataString = "task=simpanSurveyIGD&id_pendaftaran=" + getURL('fid') +
            "&id_pasien=" + getURL('pid') +
            "&pekerjaan=" + $("input:radio[name=pekerjaan]:checked").val() +
            "&triage=" + $("input:radio[name=triage]:checked").val() +
            "&transportasi=" + $("input:radio[name=transportasi]:checked").val() +
            "&jTrans=" + $("input:radio[name=jTrans]:checked").val() +
            "&pengantar=" + $("input:radio[name=pengantar]:checked").val() +
            "&asuransi=" + $("input:radio[name=asuransi]:checked").val() +
            "&inform=" + $("input:radio[name=inform]:checked").val() +
            "&ic=" + $("input:radio[name=ic]:checked").val() +
            "&kasus=" + $("input:radio[name=kasus]:checked").val() +
            "&jKasus=" + $("input:radio[name=jKasus]:checked").val() +
            "&emergency=" + $("input:radio[name=emergency]:checked").val() +
            "&status=" + $("input:radio[name=status]:checked").val() +
            "&lanjut=" + $("input:radio[name=lanjut]:checked").val() +
            "&alergi=" + $("input:radio[name=alergi]:checked").val() +
            "&medikasi=" + $("input:radio[name=medikasi]:checked").val() +
            "&teratur=" + $("input:radio[name=teratur]:checked").val() +
            "&rpd=" + $("input:radio[name=rpd]:checked").val() +
            "&amenor=" + $("input:radio[name=amenor]:checked").val() +
            "&jam_datang=" + $("#jam_datang").datebox("getValue") + 
            "&jam_periksa=" + $("#jam_periksa").datebox("getValue") + 
            "&jam_terapi=" + $("#jam_terapi").datebox("getValue") + 
            "&jam_lanjut=" + $("#jam_lanjut").datebox("getValue") +
            "&bagian=" + $("#bagian").val() +
            "&jam_d=" + $("#jam_d").val() +
            "&jam_p=" + $("#jam_p").val() +
            "&jam_t=" + $("#jam_t").val() +
            "&jam_l=" + $("#jam_l").val() +
            "&id_survey=" + $("#id_survey").val() +
            "&peristiwa=" + $("#peristiwa").val()+ 
            "&saving=" + $("input:radio[name=saving]:checked").val();
        
        if($("input:radio[name=triage]:checked").val() == undefined || $("input:radio[name=jKasus]:checked").val() == undefined || $("input:radio[name=emergency]:checked").val() == undefined)
            var cek = "";
        else
            var cek = 1;
            
        var bvalid = true;
        
        bvalid = bvalid && checkSelect(cek, 'Triage, Kasus, Emergency dan Jenis Kasus');
        bvalid = bvalid && checkSelect($("#peristiwa").val(), 'Peristiwa');
        bvalid = bvalid && checkSelect($("#bagian").val(), 'Bagian');
        
        if(bvalid){
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,
                success: function(data) {
                    if(data=='1'){
                        $.messager.show({
                            title:'Survey IGD',
                            msg:'Survey IGD berhasil disimpan.',
                            showType:'show'
                        });
                        return false;
                    } else {
                        $.messager.show({
                            title:'Survey IGD',
                            msg:'Gagal menyimpan Survey IGD. ' + data,
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    }
</script>