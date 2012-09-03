<script>
    function getLaporanPembelian(){
        $.getJSON("json/data.php", {task: 'getRekapJasa', 
                  startDate: $("#startDate").datebox("getValue"), 
                  endDate: $("#endDate").datebox("getValue"),
                  id_dokter: $("#dokter").val(),
                  id_ruang: $("#ruang").val()},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
    
    function cetakLaporanPembelian(){        
        $.ajax({  
            type: "GET",  
            url: "json/data.php",  
            data: "task=cetakRekapJasa&startDate=" + $("#startDate").datebox("getValue") + 
                  "&endDate=" + $("#endDate").datebox("getValue") +
                  "&id_dokter=" + $("#dokter").val() +
                  "&id_ruang=" + $("#ruang").val(),  
            success: function(dRet) {
                if(dRet=='1'){
                    var win = window.open('report/rekapJasa.html','cetakLaporanPembelianObat','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                }
            }
        });                                        
    }
    
</script>