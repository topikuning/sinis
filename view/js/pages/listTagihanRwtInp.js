<script>
    $(function(){
        tipe_pasien.focus();
    });
    
    function getLaporanTagihanRawatInap(){
        $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
        $.getJSON("json/data.php", {task: 'getLaporanTagihanRawatInap', 
            tipe_pasien: $("#tipe_pasien").val(), 
            status: $("#status").val(), 
            startDate: $("#startDate").datebox("getValue"), 
            endDate: $("#endDate").datebox("getValue")},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
    
</script>