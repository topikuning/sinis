<script>
    $(function(){
        startDate.focus();
    });
    
    $('#startHour').timespinner({showSeconds:true});
    $('#endHour').timespinner({showSeconds:true});
    
    function getLaporanKasir(){
        var bvalid = true;
        bvalid = bvalid && checkSelect($("#startDate").val(), 'Tanggal');
        bvalid = bvalid && checkSelect($("#startHour").val(), 'Jam');
        if(bvalid){
            $("#detailLaporan").html("<img src='../images/loader.gif'> Loading...");
            $.getJSON("json/data.php", {task: 'getLaporanKasir', 
                      startDate: $("#startDate").val(), 
                      endDate: $("#endDate").val(),
                      startHour: $("#startHour").val(),
                      endHour: $("#endHour").val(),
                      kasir: $("#kasir").val()},
            function(data) {
                if(data.length>0){
                    $("#detailLaporan").html(data[0].display);
                }
            });        
        }
    }
    
    var calendar;
    window.onload = function() {
        cal1 = new dhtmlxCalendarObject(['startDate', 'endDate'],true, {
            isYearEditable: true,
            isMonthEditable: true
        });
        cal1.setSkin('simplecolordark');
    }
</script>