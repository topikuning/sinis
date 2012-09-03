<script>
    $(function(){
        shift.focus();
    });
    
    function getLaporanPenjualan(){
        $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
        $.getJSON("json/apotik.php", {task: 'getLaporanPenjualan', 
                  shift: $("#shift").val(), 
                  status: $("#status").val(), 
                  startDate: $("#startDate").datebox("getValue")},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
    
</script>