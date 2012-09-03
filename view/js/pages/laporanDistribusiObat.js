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
    
    function getDistribusiObat(){
        $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
        $.getJSON("json/apotik.php", {task: 'getDistribusiObat', 
                  id_obat: $("#nama_obatJId").val(), 
                  id_obatS: $("#nama_obatSJId").val(), 
                  startDate: $("#startDate").datebox("getValue"),
                  endDate: $("#endDate").datebox("getValue"),
                  ruang:$("#ruang").val(),
                  asal_ruang:$("#asal_ruang").val()},
        function(data) {
            if(data.length>0){
                $("#detailLaporan").html(data[0].display);
            }
        });        
    }
    
</script>