<script>
    $(function(){
        nama_obatJ.focus();
        $('#startHour').timespinner({showSeconds:true});
        $('#endHour').timespinner({showSeconds:true});
    });
    
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:1,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate:''
    });
    
    function getRekapPenjualanObat(){
        var tpLaporan;
        if(perCustomer.checked) tpLaporan = '1';
        else if(perBarang.checked) tpLaporan = '2';
        else if(perFaktur.checked) tpLaporan = '3';
        else if(perBarangPasien.checked) tpLaporan = '4';
        else if(perTanggal.checked) tpLaporan = '5';
        else if(perRuang.checked) tpLaporan = '6';
        else if(perDokter.checked) tpLaporan = '7';
        else if(perObat.checked) tpLaporan = '8';

        $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
        $.getJSON("json/apotik.php", {task: 'getRekapPenjualanObat', 
                  id_obat: $("#nama_obatJId").val(), 
                  id_obatS: $("#nama_obatSJId").val(), 
                  //shift: $("#shift").val(), 
                  status: $("#status").val(), 
                  startDate: $("#startDate").datebox("getValue"),
                  endDate: $("#endDate").datebox("getValue"),
                  startHour: $("#startHour").val(),
                  endHour: $("#endHour").val(),
                  tipeLaporan: tpLaporan,
                  ruang:$("#ruang").val()},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
    
</script>