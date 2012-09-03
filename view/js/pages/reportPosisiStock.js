<script>
    $(function(){
        nama_obatJ.focus();
    });
    
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:1,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate:''
    });
    
    function getLaporanPosisiStock(){
        $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
        $.getJSON("json/obat.php", {task: 'getLaporanPosisiStock', 
                  id_obat: $("#nama_obatJId").val(), 
                  id_obatS: $("#nama_obatSJId").val(), 
                  ruang: $("#ruang").val(), 
                  startDate: $("#startDate").datebox("getValue"), 
                  endDate: $("#endDate").datebox("getValue")},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
    
    function cetakLaporanPosisiStock(){
        $("#loading").html("<img src='../images/loader.gif'> Loading...");
        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: "task=cetakLaporanPosisiStock&id_obat=" + $("#nama_obatJId").val() + 
                  "&id_obatS=" + $("#nama_obatSJId").val() + 
                  "&startDate=" + $("#startDate").datebox("getValue") + 
                  "&endDate=" + $("#endDate").datebox("getValue") +
                  "&ruang=" + $("#ruang").val(),  
            success: function(dRet) {
                if(dRet=='1'){
                    $("#loading").html("");
                    var win = window.open('report/laporanPosisiStock.html','cetakLaporanPembelianObat','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                }
            }
        });                                        
    }
    
</script>
