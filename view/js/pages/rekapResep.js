<script>
    $(function(){
        shift.focus();
    });
    
    function getRekapResep(){
        $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
        $.getJSON("json/apotik.php", {task: 'getRekapResep', 
                  jenis_perawatan: $("#jenis_perawatan").val(), 
                  tipe_pasien: $("#tipe_pasien").val(), 
                  startDate: $("#startDate").datebox("getValue"),
                  endDate: $("#endDate").datebox("getValue")},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
    
    function cetakRekapResep(){
        $("#loading").html("<img src='../images/loader.gif'> Loading...");
        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: "task=cetakRekapResep&jenis_perawatan=" + $("#jenis_perawatan").val() +
                  "&tipe_pasien=" + $("#tipe_pasien").val() +
                  "&startDate=" + $("#startDate").datebox("getValue") + 
                  "&endDate=" + $("#endDate").datebox("getValue"),  
            success: function(dRet) {
                if(dRet=='1'){
                    $("#loading").html("");
                    var win = window.open('report/rekapResep.html','cetakLaporanPenjualanObat','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                }
            }
        });                                        
    }
    
</script>