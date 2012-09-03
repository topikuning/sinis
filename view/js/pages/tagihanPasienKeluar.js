<script>
    function loadDataTagihanPasien(){
        $("#detailTagihan").html("<img src='../images/loader.gif'> Loading...");
        $.getJSON("json/data.php", {task: 'getTagihanPasienKeluar', id_pendaftaran: id_daftar.value},
        function(data) {
            if(data.length>0){
                $("#detailTagihan").html(data[0].display);
            }
        });        
    }
</script>