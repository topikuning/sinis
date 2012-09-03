<script>
    $(function(){
        nama_obatJ.focus();
    });
    
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php',
        afterUpdate:''
    });
    
    function getLaporanPembelian(){
        var tpLaporan;
        if(byObat.checked) tpLaporan = '1';
        else if (byTglEntry.checked) tpLaporan = '2';
        else if (byTglBeli.checked) tpLaporan = '3';
        else if (bySupplier.checked) tpLaporan = '4';
        
        $.getJSON("json/obat.php", {task: 'getLaporanPembelian', 
                  id_obat: $("#nama_obatJId").val(), 
                  id_obatS: $("#nama_obatSJId").val(), 
                  id_supplier: $("#supplier").val(), 
                  startEntryDate: $("#startEntryDate").datebox("getValue"), 
                  endEntryDate: $("#endEntryDate").datebox("getValue"),
                  startDate: $("#startDate").datebox("getValue"), 
                  endDate: $("#endDate").datebox("getValue"),
                  tipeLaporan: tpLaporan},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
    
    function cetakLaporanPembelian(){
        var tpLaporan;
        if(byObat.checked) tpLaporan = '1';
        else if (byTglEntry.checked) tpLaporan = '2';
        else if (byTglBeli.checked) tpLaporan = '3';
        else if (bySupplier.checked) tpLaporan = '4';
        
        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: "task=cetakLaporanPembelian&id_obat=" + $("#nama_obatJId").val() + 
                  "&id_obatS=" + $("#nama_obatSJId").val() + 
                  "&id_supplier=" + $("#supplier").val() + 
                  "&startEntryDate=" + $("#startEntryDate").datebox("getValue") + 
                  "&endEntryDate=" + $("#endEntryDate").datebox("getValue") +
                  "&startDate=" + $("#startDate").datebox("getValue") + 
                  "&endDate=" + $("#endDate").datebox("getValue") +
                  "&tipeLaporan=" + tpLaporan,  
            success: function(dRet) {
                if(dRet=='1'){
                    var win = window.open('report/laporanPembelianObat.html','cetakLaporanPembelianObat','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                }
            }
        });                                        
    }
    
</script>