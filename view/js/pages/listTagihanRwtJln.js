<script>
    $(function(){
        tipe_pasien.focus();
    });
    
    function getLaporanTagihanRawatJalan(){
        if($("#tipe_pasien").val()==""){
            $("#detailLaporan").html('Tipe Pasien Belum Dipilih.');
        } else if ($("#status").val()==""){
            $("#detailLaporan").html('Status Belum Dipilih.');
        } else {
            $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
            $.getJSON("json/data.php", {task: 'getLaporanTagihanRawatJalan', 
                tipe_pasien: $("#tipe_pasien").val(), 
                status: $("#status").val(),
                rawat: $("#rawat").val(),
                startDate: $("#startDate").datebox("getValue"), 
                endDate: $("#endDate").datebox("getValue")},
            function(data) {
                if(data.length>0){
                    $("#detailLaporan").html(data[0].display);
                }
            });    
        }
    }

</script>