
<script>
    $(function(){

        $( "#tipe_pendaftaran" ).change(function(){
            var opt = '';
            opt += '<option value=""></option>';
            $("#bed_pindah").html(opt);
            $.getJSON("json/data.php", {task: 'listRuangDaftar', id_tipe_pendaftaran: $(this).val(), id_pasien: id_pasien.value},
            function(data) {
                var opt = '';
                opt += '<option value=""></option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#ruang").html(opt);
                }
            });
            $.getJSON("json/data.php", {task: 'getBiayaPendaftaran', tipe_pendaftaran: $(this).val(), id_pasien:$("#id_pasien").val()},
            function(data) {
                if(data.length>0){
                    var value = data[0].dataValue;

                    $("#biaya").attr("value", value);
                }
            });
            if($(this).val()=='6'){
                kamar.disabled = false;
                bed.disabled = false;
            } else {
                kamar.disabled = true;
                bed.disabled = true;
            }
        });

        $( "#ruang" ).change(function(){
            $.getJSON("json/data.php", {task: 'listKelas', id_ruang: $(this).val(), id_pasien: id_pasien.value},
            function(data) {
                var opt = '';
                opt += '<option value=""></option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#kelas").html(opt);
                }
            });
            $.getJSON("json/data.php", {task: 'listDokter', id_ruang: $(this).val()},
            function(data) {
                var opt = '';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#dokter").html(opt);
                }
            });
        });
        
        $("#listTipeAsuransi").change(function(){
            $.getJSON("json/data.php", {task: 'listTipePasien', id_tipe_asuransi: $(this).val()},
            function(data) {
                var opt = '';
                opt += '<option value=""></option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#listTipePasien").html(opt);
                }
            });
        });

        $("#listKota").change(function(){
            $.getJSON("json/data.php", {task: 'listKecamatan', id_kota: $(this).val()},
            function(data) {
                var opt = '';
                opt += '<option value=""></option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#listKecamatan").html(opt);
                }
            });
        });

        $("#listKecamatan").change(function(){
            $.getJSON("json/data.php", {task: 'listKelurahan', id_kecamatan: $(this).val()},
            function(data) {
                var opt = '';
                opt += '<option value=""></option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#listKelurahan").html(opt);
                }
            });
        });

        $("#asal_rujukan").change(function(){
            $.getJSON("json/data.php", {task: 'listPerujuk', asal_rujukan: $(this).val()},
            function(data) {
                var opt = '';
                opt += '<option value=""></option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#perujuk").html(opt);
                }
            });
        });
        $("#kelas").change(function(){
            if($("#tipe_pendaftaran").val()=='6'){
                $.getJSON("json/data.php", {task: 'listKamar', id_ruang: $("#ruang").val(), id_kelas: $(this).val()},
                function(data) {
                    var opt = '';
                    opt += '<option value=""></option>';
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {
                            opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                        };

                        $("#kamar").html(opt);
                    }
                });
            }
        });
        $("#kamar").change(function(){
            $.getJSON("json/data.php", {task: 'listBed', id_kamar: $(this).val()},
            function(data) {
                var opt = '';
                opt += '<option value=""></option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#bed").html(opt);
                }
            });
        });
        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPendaftaran.php?task=cariPendaftaran&no_rm=' + $('#no_rm').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue") + '&tipe_pasien=' + $('#tipe_pasien').val() + '&status=' + $('#status').val() + '&closed=' + $('#closed').val(),
            sortName: 'id_pendaftaran',
            sortOrder: 'desc',
            remoteSort: false,
            pageList: ['10','100','200','500'],
            showFooter:true,
            chace:false,
            idField:'id_pendaftaran',
            frozenColumns:[[
                    //{title:'ID',field:'id_pendaftaran',width:50,sortable:true},
                    {field:'id_pasien',title:'RM Px',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Px',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'usia',title:'Usia',width:100},
                    {field:'id_tipe_pendaftaran',title:'ID Tipe Pendaftaran',width:120,sortable:true,hidden:true},
                    {field:'alamat',title:'Alamat Px',width:150},
                    {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:100,sortable:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:110,sortable:true},
                    {field:'kelas',title:'Kelas',width:100,sortable:true},
                    {field:'ruang',title:'Ruang',width:120,sortable:true},
                    {field:'tgl_pendaftaran_view',title:'Tgl Pendaftaran',width:80},
                    {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:80,hidden:true},
                    {field:'jam_daftar',title:'Jam Daftar',width:80},
                    {field:'status',title:'Status',width:80},
                    {field:'biaya_pendaftaran',title:'Biaya Pendaftaran',width:100,align:'right',
                        formatter:function(value){
                            return formatCurrency(value);
                        }
                    },
                    {field:'operator',title:'Operator',width:80},
                ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(rowIndex){
                var rows = $('#dataPendaftaran').datagrid('getSelections');
                if(rows[0].status=='Perawatan'){
                    $.messager.show({
                        title:'List Pendaftaran',
                        msg:'Pasien dalam perawatan.',
                        showType:'show'
                    });                                
                } else if(rows[0].status=='Closed'){
                    $.messager.show({
                        title:'List Pendaftaran',
                        msg:'Pasien sudah keluar.',
                        showType:'show'
                    });                                
                } else {
                    var dataString = "task=detailPendaftaran&id_pendaftaran=" + rows[0].id_pendaftaran;

                    $.ajax({  
                        type: "GET",  
                        url: "json/listPendaftaran.php",  
                        data: dataString,  
                        success: function(data) {
                            var rec = data.split(":");
                            $("#id_pendaftaran").val(rec[0]);
                            $("#id_pasien").val(rec[1]);
                            $("#tgl_pendaftaran").val(rec[3]);
                            $("#tipe_pendaftaran").val(rec[2]);
                            $.getJSON("json/data.php", {task: 'listRuang', id_tipe_pendaftaran: rec[2], id_pasien: rec[1]},
                            function(data) {
                                var opt = '';
                                opt += '<option value="">[Pilih Ruang]</option>';
                                if(data.length>0){
                                    for (var i = 0; i < data.length; i++) {
                                        if(data[i].optionValue==rec[5])
                                            opt += '<option value="' + data[i].optionValue + '" selected>' + data[i].optionDisplay + '</option>';
                                        else
                                            opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                    };

                                    $("#ruang").html(opt);
                                }
                            });
                            $.getJSON("json/data.php", {task: 'listKelas', id_ruang: rec[5], id_pasien: rec[1]},
                            function(data) {
                                var opt = '';
                                opt += '<option value="">[Pilih Kelas]</option>';
                                if(data.length>0){
                                    for (var i = 0; i < data.length; i++) {
                                        if(data[i].optionValue==rec[6])
                                            opt += '<option value="' + data[i].optionValue + '" selected>' + data[i].optionDisplay + '</option>';
                                        else
                                            opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                    };

                                    $("#kelas").html(opt);
                                }
                            });
                            if(rec[2]=='6'){
                                var opt = '';
                                opt += '<option value="">[Pilih Kamar]</option>';
                                $("#kamar").html(opt);
                                $.getJSON("json/data.php", {task: 'listKamar', id_ruang: rec[5], id_kelas: rec[6]},
                                function(data) {
                                    if(data.length>0){
                                        for (var i = 0; i < data.length; i++) {
                                            if(data[i].optionValue==rec[7])
                                                opt += '<option value="' + data[i].optionValue + '" selected>' + data[i].optionDisplay + '</option>';
                                            else
                                                opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                        };

                                        $("#kamar").html(opt);
                                    }
                                });
                                var opt = '';
                                opt += '<option value="">[Pilih Bed]</option>';
                                $("#bed").html(opt);
                                $.getJSON("json/data.php", {task: 'listBedAll', id_kamar: rec[7]},
                                function(data) {
                                    var opt = '';
                                    opt += '<option value="">[Pilih Bed]</option>';
                                    if(data.length>0){
                                        for (var i = 0; i < data.length; i++) {
                                            if(data[i].optionValue==rec[8])
                                                opt += '<option value="' + data[i].optionValue + '" selected>' + data[i].optionDisplay + '</option>';
                                            else
                                                opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                        };

                                        $("#bed").html(opt);
                                    }
                                });
                                kamar.disabled = false;
                                bed.disabled = false;
                            } else {
                                var opt = '';
                                opt += '<option value="">[Pilih Kamar]</option>';
                                $("#kamar").html(opt);
                                var opt = '';
                                opt += '<option value="">[Pilih Bed]</option>';
                                $("#bed").html(opt);
                                kamar.disabled = true;
                                bed.disabled = true;
                            }
                            $("#dokter").val(rec[9]);
                            $("#biaya").val(rec[10]);
                            $("#asal_rujukan").val(rec[11]);
                            $("#perujuk").val(rec[12]);
                            $("#alasan_rujuk").val(rec[13]);
                        }  
                    });  

                    openWinDaftar();
                }
            },
            toolbar:[{
                    id:'btndel',
                    text:'Batalkan',
                    iconCls:'icon-remove',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            var rows = $('#dataPendaftaran').datagrid('getSelections');
                            var noDaftar = "task=batalDaftar&id_pendaftaran=" + rows[0].id_pendaftaran;
                            /*if(rows[0].status=='Perawatan'){
                                $.messager.show({
                                    title:'List Pendaftaran',
                                    msg:'Pasien dalam perawatan.',
                                    showType:'show'
                                });                                
                            } else*/ 
			    if(rows[0].status=='Closed'){
                                $.messager.show({
                                    title:'List Pendaftaran',
                                    msg:'Pasien sudah keluar.',
                                    showType:'show'
                                });                                
                            } else {
                                var tanggal = getToday();
                                if(rows[0].tgl_pendaftaran==tanggal) {
                                    $.ajax({  
                                        type: "POST",  
                                        url: "json/pendaftaran.php",  
                                        data: noDaftar,  
                                        success: function(data) {  
                                            if(data=='1' || data=='0'){
                                                $.messager.show({
                                                    title:'List Pendaftaran',
                                                    msg:'Pembatalan pendaftaran no ' + rows[0].id_pendaftaran + ' berhasil.',
                                                    showType:'show'
                                                });

                                                $('#dataPendaftaran').datagrid('deleteRow', index);
                                                $('#dataPendaftaran').datagrid('reload');
                                            /*} else if (data=='0') {
                                                $.messager.show({
                                                    title:'List Pendaftaran',
                                                    msg:'Pembatalan pendaftaran no ' + rows[0].id_pendaftaran + ' gagal, pasien sudah mendapatkan tindakan.',
                                                    showType:'show'
                                                });*/
                                            } else if (data=='2') {
                                                $.messager.show({
                                                    title:'List Pendaftaran',
                                                    msg:'Pembatalan pendaftaran no ' + rows[0].id_pendaftaran + ' gagal.',
                                                    showType:'show'
                                                });
                                            }
                                        }
                                    })
                                }
                            }
                        } else {
                            $.messager.show({
                                title:'List Pendaftaran',
                                msg:'Data Pendaftaran belum dipilih.',
                                showType:'show'
                            });
                        }
                    }
                },{
                    id:'btnprint',
                    text:'Cetak Karcis',
                    iconCls:'icon-print',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            var rows = $('#dataPendaftaran').datagrid('getSelections');
                            var tanggal = getToday();
                            //if($('#startDate').datebox("getValue")==tanggal){
                                $.ajax({  
                                    type: "POST",  
                                    url: "json/pendaftaran.php",  
                                    data: "task=cetakKarcis&idDaftar=" + rows[0].id_pendaftaran,  
                                    success: function(dRet) {
                                        if(dRet=='1'){
                                            var win = window.open('report/cetakKarcis.html','cetakKarcis','height=400,width=300,resizable=1,scrollbars=1, menubar=0');
                                            //win.print();
                                        }
                                    }
                                });
                            //}
                        } else {
                            $.messager.show({
                                title:'List Pendaftaran',
                                msg:'Data Pendaftaran belum dipilih.',
                                showType:'show'
                            });
                        }
                    }
                },{
                    id:'btnprintsjp',
                    text:'Cetak SJP',
                    iconCls:'icon-print',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            var rows = $('#dataPendaftaran').datagrid('getSelections');
                            var tanggal = getToday();
                            //if($('#startDate').datebox("getValue")==tanggal){
                                $.ajax({  
                                    type: "POST",  
                                    url: "json/pendaftaran.php",  
                                    data: "task=cetakSJP&idDaftar=" + rows[0].id_pendaftaran,  
                                    success: function(dRet) {
                                        if(dRet=='1'){
                                            var win = window.open('report/cetakSJP.html','cetakKarcis','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                                        }
                                    }
                                });
                            //}
                        } else {
                            $.messager.show({
                                title:'List Pendaftaran',
                                msg:'Data Pendaftaran belum dipilih.',
                                showType:'show'
                            });
                        }
                    }
                }]
        });
    });
    
    function simpanPendaftaran(){
        var dataString = "task=updatePendaftaran&id_pendaftaran=" + $("#id_pendaftaran").val() +
            "&id_tipe_pendaftaran=" + $("#tipe_pendaftaran").val() +
            "&tgl_pendaftaran=" +$("#tgl_pendaftaran").val() +
            "&id_ruang_asal=" + $("#ruang_asal").val() + 
            "&id_ruang=" + $("#ruang").val() + 
            "&id_kelas=" + $("#kelas").val() + 
            "&id_kamar=" + $("#kamar").val() + 
            "&id_detail_kamar=" + $("#bed").val() + 
            "&id_dokter=" + $("#dokter").val() + 
            "&biaya_pendaftaran=" + $("#biaya").val() + 
            "&asal_rujukan=" + $("#asal_rujukan").val() + 
            "&perujuk=" + $("#perujuk").val() + 
            "&alasan_rujuk=" + $("#alasan_rujuk").val() + 
            "&id_pasien=" + $("#id_pasien").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#id_pasien").val(), 'No RM');
        bvalid = bvalid && checkSelect($("#tipe_pendaftaran").val(), 'Tipe Pendaftaran');
        bvalid = bvalid && checkSelect($("#ruang").val(), 'Ruang');
        bvalid = bvalid && checkSelect($("#kelas").val(), 'Kelas');

        if(bvalid){                  
            $.ajax({  
                type: "POST",  
                url: "json/pendaftaran.php",  
                data: dataString,  
                success: function(data) {
                    var returnData = data.split(":");
                    var status = returnData[0];
                    if(status=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(status=='TRUE'){
                        $.messager.show({
                            title:'Pendaftaran',
                            msg:'Pendaftaran berhasil diupdate',
                            showType:'show'
                        });
                        $("#winDaftar").window('close');
                        frmDaftar.reset();
                        $('#dataPendaftaran').datagrid("reload");
                        return false;
                    } else if(status=='FALSE') {
                        $.messager.show({
                            title:'Pendaftaran',
                            msg:'Update pendaftaran gagal.',
                            showType:'show'
                        });
                        frmDaftar.reset();
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function openWinDaftar(){
//        var tanggal = getToday();
//        if($('#startDate').datebox("getValue")==tanggal)
            $('#winDaftar').window('open');
    }
    
    function closeWinDaftar(){
        frmDaftar.reset();
        $("#winDaftar").window('close');
    }
    
    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPendaftaran.php?task=cariPendaftaran&no_rm=' + $('#no_rm').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue") + '&tipe_pasien=' + $('#tipe_pasien').val() + '&status=' + $('#status').val() + '&closed=' + $('#closed').val()
        });
    }
</script>
