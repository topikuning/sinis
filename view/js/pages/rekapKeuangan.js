<script>
    function getRekapKeuangan(){
        var tpLaporan;
        if(hariRawat.checked) tpLaporan = '1';
        else if(ibs.checked) tpLaporan = '2';
        else if(tindakanRuang.checked) tpLaporan = '3';
        else if(radiologi.checked) tpLaporan = '4';
        else if(laborat.checked) tpLaporan = '5';
        else if(fasilitas.checked) tpLaporan = '6';
        else if(visit.checked) tpLaporan = '7';

        $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
        $.getJSON("json/data.php", {task: 'getRekapKeuangan',
                  ruang: $("#ruang").val(), 
                  tipe_pasien: $("#tipe_pasien").val(), 
                  startDate: $("#startDate").datebox("getValue"), 
                  endDate: $("#endDate").datebox("getValue"), 
                  dokter: $("#dokter").val(), 
                  kelas: $("#kelas").val(), 
                  tipePerawatan: $("#tipe_perawatan").val(), 
                  tipeLaporan: tpLaporan},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
</script>