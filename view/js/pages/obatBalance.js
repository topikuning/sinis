    <script>
    $(function(){
    $( "#ruang" ).change(function(){
        getJumlah();
        $("#s_real").val('');
    });
});
    
function cekJumlah(key) {
    if(key == 13){
        getJumlah();
        $("#s_real").focus();
    }
}

function getJumlah() {
    var bvalid = true;
    bvalid = bvalid && checkSelect($("#ruang").val(), 'Ruang');
    if(bvalid && dObat.getSelectedValue() != null){
        
        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: "task=getBalVal&id_obat=" + dObat.getSelectedValue() + 
                "&ruang=" + $("#ruang").val(),  
            success: function(data) {
                $('#spinner').window('close')
                if(data!='NIHIL'){
                    $("#s_sistem").val(data);
                    $("#s_real").val('');
                } else {
                    alert('a');
                }
            },
            beforeSend: function() {
                $('#spinner').window('open')
                $("#s_sistem").val('PROSES');
                $("#s_real").val('PROSES');
            }
        });
    }
}

function setJumlah() {
    var bvalid = true;
    bvalid = bvalid && checkSelect($("#ruang").val(), 'Ruang');
    bvalid = bvalid && checkSelect($("#s_real").val(), 'Jumlah');
    bvalid = bvalid && checkSelect($("#s_sistem").val(), 'Jumlah');
    if(bvalid && dObat.getSelectedValue() != null){
        
        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: "task=setValBal&id_obat=" + dObat.getSelectedValue() + 
                "&ruang=" + $("#ruang").val() + 
                "&sistem=" + $("#s_sistem").val() +
                "&real=" + $("#s_real").val(),  
            success: function(data) {
                $('#spinner').window('close')
                if(data!='GAGAL'){
                    $("#s_sistem").val($("#s_real").val());
                } else {
                    alert('ERROR');
                }
            },
            beforeSend: function() {
                $('#spinner').window('open')
            }
        });
  
//        $.getJSON("json/obat.php", {
//            task: 'setValBal', 
//            id_obat: dObat.getSelectedValue(), 
//            ruang: $("#ruang").val(),
//            sistem: $("#s_sistem").val(),
//            real: $("#s_real").val()
//        },
//        function(data) {
//            if(data != 0)
//                $("#s_sistem").val($("#s_real").val());
//        });
    }
}

//DHTML
//var dokter = dhtmlXComboFromSelect("ruang");
var dObat = new dhtmlXCombo("nama_obat","nama_obat",200);
dObat.enableFilteringMode(true,"json/data.php?task=dObat2",true);
dObat.attachEvent("onChange", getJumlah);
dObat.attachEvent("onKeyPressed", cekJumlah);
    </script>