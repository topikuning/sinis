<script>
    $(function(){
        $.getJSON("json/apotik.php", {task: 'getStsk'},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    });
    
    function cetakLaporanPenjualan(){
        $.ajax({  
            type: "GET",  
            url: "json/apotik.php",  
            data: "task=cetakStsk",  
            success: function(dRet) {
                alert(dRet);
                if(dRet=='1'){
                    var win = window.open('report/laporanPenjualanObat.html','cetakLaporanPenjualanObat','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                    window.location="?page=strksr";
                }
            }
        });
    }
    
</script>