<script>
    $(function(){
        shift.focus();
    });
    
    function getLaporanPenjualan(){
        var bvalid = true;
        bvalid = bvalid && checkSelect($("#startDate").datebox("getValue"), 'Tanggal Awal');
        bvalid = bvalid && checkSelect($("#endDate").datebox("getValue"), 'Tanggal Akhir');
        
        if(bvalid) {
            $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
            $.getJSON("json/apotik.php", {task: 'getLaporanPenjualanHarian', 
                      shift: $("#shift").val(),
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