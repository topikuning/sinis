<script>
    function getRekapPendapatan(){
        $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
        $.getJSON("json/data.php", {task: 'getRekapPendapatan', 
                  startDate: $("#startDate").datebox("getValue"), 
                  endDate: $("#endDate").datebox("getValue")},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
    
    function cetakRekapPendapatan(){
        $("#loading").html("<img src='../images/loader.gif'> Loading...");
        $.ajax({  
            type: "GET",  
            url: "json/data.php",  
            data: "task=cetakRekapPendapatan" + 
                  "&startDate=" + $("#startDate").datebox("getValue") + 
                  "&endDate=" + $("#endDate").datebox("getValue"),  
            success: function(dRet) {
                if(dRet=='1'){
                    $("#loading").html("");
                    var win = window.open('report/rekapPendapatan.html','cetakLaporanPembelianObat','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                }
            }
        });                                        
    }
    
</script>