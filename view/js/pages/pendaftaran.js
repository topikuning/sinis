<script>
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:3,
        rtnIDs:false,
        dataFile:'json/dataList.php'
    });
	
    $(function(){
        $("#tglLahirField").inputmask('d-m-y');
        noRm.focus();
        $('#detailPasien').propertygrid({
            width:720,
            height:400,
            showGroup:true,
            scrollbarSize:0
        });
		
        $( "#tipe_pendaftaran" ).change(function(){
            var opt = '';
            opt += '<option value=""></option>';
            $("#bed_pindah").html(opt);
            $.getJSON("json/data.php", {task: 'listRuangDaftar', id_tipe_pendaftaran: $(this).val(), id_pasien: noRm.value},
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
            $.getJSON("json/data.php", {task: 'listKelas', id_ruang: $(this).val(), id_pasien: noRm.value},
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
            $( "#dokter" ).combobox({
                url:'json/data.php?task=listDokter&id_ruang=' + $(this).val(),
                valueField:'optionValue',
                textField:'optionDisplay'
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
        $('#dataListKamar').datagrid({
            title:'Detail Kamar',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'id_kamar',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'id_kamar',
            frozenColumns:[[
                    {title:'ID',field:'id_kamar',width:30,sortable:true}
                ]],
            columns:[[
                    {field:'id_detail_kamar',title:'ID Obat',width:150,sortable:true,hidden:true},
                    {field:'kamar',title:'Kamar',width:250,sortable:true},
                    {field:'bed',title:'Bed',width:150,sortable:true},
                    {field:'status',title:'Harga',width:80,
                        formatter:function(value){
                            if(value==0)
                                return 'Kosong';
                            else
                                return 'Terisi';
                        }
                    }
                ]],
            onDblClickRow:function(){
                var rows = $('#dataListKamar').datagrid('getSelections');
                if(rows[0].status=='0'){
                    $("#kamar").val(rows[0].id_kamar);
                    $.getJSON("json/data.php", {task: 'listBed', id_kamar: rows[0].id_kamar},
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
                    $("#bed").val(rows[0].id_detail_kamar);
                    closeSearchKamar();
                } else {
                    $.messager.show({
                        title:'Kamar',
                        msg:'Bed Sudah terisi.',
                        showType:'show'
                    });
                }
            },
            pagination:true,
            rownumbers:true
        });
        $('#dataListPasien').datagrid({
            title:'Data Pasien',
            height:300,
            singleSelect:true,
            nowrap: false,
            striped: true,
            sortName: 'id_pasien',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'id_pasien',
            frozenColumns:[[
                    {title:'No RM',field:'id_pasien',width:50,sortable:true}
                ]],
            columns:[[
                    {field:'nama_pasien',title:'Nama Pasien',width:150,sortable:true},
                    {field:'jns_kelamin',title:'L/P',width:30,sortable:true},
                    {field:'tmp_lahir',title:'Tempat Lahir',width:100,sortable:true},
                    {field:'tgl_lahir',title:'Tanggal Lahir',width:80,sortable:true},
                    {field:'alamat',title:'Alamat',width:80,sortable:true},
                    {field:'kelurahan',title:'Kelurahan',width:80,sortable:true},
                    {field:'kecamatan',title:'Kecamatan',width:80,sortable:true},
                    {field:'kota',title:'Kota',width:80,sortable:true},
                    {field:'marital',title:'Marital',width:80,sortable:true},
                    {field:'asuransi',title:'Tipe Asuransi',width:80,sortable:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:80,sortable:true}
                ]],
            onDblClickRow:function(){
                var rows = $('#dataListPasien').datagrid('getSelections');
                $("#noRm").val(rows[0].id_pasien);
                loadDataPasien(rows[0].id_pasien);
                closeWinSearchPasien();
            },
            pagination:true,
            rownumbers:true
        });
    });
    
    function simpanPendaftaran(){
        var dataString = "task=simpanPendaftaran&id_tipe_pendaftaran=" + $("#tipe_pendaftaran").val() +
            "&id_pendaftaran=0" +
            "&tgl_pendaftaran=" +$("#tgl_pendaftaran").val() +
            "&id_ruang_asal=" + $("#ruang_asal").val() + 
            "&id_ruang=" + $("#ruang").val() + 
            "&id_kelas=" + $("#kelas").val() + 
            "&id_kamar=" + $("#kamar").val() + 
            "&id_detail_kamar=" + $("#bed").val() + 
            "&id_dokter=" + $("#dokter").combobox('getValue') + 
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
        if($("#tipe_pendaftaran").val()=="6"){
            bvalid = bvalid && checkSelect($("#kamar").val(), 'Kamar');
            bvalid = bvalid && checkSelect($("#bed").val(), 'Bed');
        }
        if(bvalid){                  
            $.ajax({  
                type: "POST",  
                url: "json/pendaftaran.php",  
                data: dataString,  
                success: function(data) {
                    var returnData = data.split(":");
                    var status = returnData[0];
                    if(status=='TRUE'){
                        $.messager.show({
                            title:'Pendaftaran',
                            msg:'Pendaftaran berhasil disimpan dengan No ID : <b>' + returnData[1] + '</b>, No Antrian : <b>' + returnData[2] + '</b> di Ruang : <b>' + returnData[3] + '</b>.',
                            showType:'fade'
                        });
                        $("#winDaftar").window('close');
                        $.messager.confirm('Pendaftaran', 'YA: KARCIS, TIDAK: SJP, X: TIDAK CETAK', function(r){
                            if (r){
                                $.ajax({  
                                    type: "POST",  
                                    url: "json/pendaftaran.php",  
                                    data: "task=cetakKarcis&idDaftar=" + returnData[1],  
                                    success: function(dRet) {
                                        if(dRet=='1'){
                                            var win = window.open('report/cetakKarcis.html','cetakKarcis','height=400,width=300,resizable=1,scrollbars=1, menubar=0');
                                        }
                                    }
                                });
                            } else {
                                $.ajax({  
                                    type: "POST",  
                                    url: "json/pendaftaran.php",  
                                    data: "task=cetakSJP&idDaftar=" + returnData[1],  
                                    success: function(dRet) {
                                        if(dRet=='1'){
                                            var win = window.open('report/cetakSJP.html','cetakKarcis','height=500,width=1000,resizable=1,scrollbars=1, menubar=0');
                                        }
                                    }
                                });
                            }
                        });
                        frmDaftar.reset();
                        return false;
                    } else if(status=='WARNING') {
                        $.messager.show({
                            title:'Pendaftaran',
                            msg:'Pendaftaran berhasil di simpan, dengan Catatan : <b>' + returnData[1] + '</b>',
                            showType:'show'
                        });
                        $("#winDaftar").window('close');
                        frmDaftar.reset();
                        return false;
                    } else if(status=='FALSE') {
                        $.messager.show({
                            title:'Pendaftaran',
                            msg:'Gagal menyimpan pendaftaran. <b>' + returnData[1] + '</b>',
                            showType:'show'
                        });
                        return false;
                    } else if(status=='KOSONG') {
                        $.messager.show({
                            title:'Pendaftaran',
                            msg:'Gagal menyimpan pendaftaran. <b>' + returnData[1] + '</b>',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function simpanPasien(){
        var tglLahir;
        if($("#tglLahirField").val()==''){
            if($("#usia").val()==''){
                tglLahir = '';
            } else {
                tglLahir = getTglLahir($("#usia").val());
            }
        } else {
            tglLahir = $("#tglLahirField").val();
        }

        var dataString = "task=simpanPasien&pasienId=" + $("#pasienId").val() +
            "&tipe="+ $("#tipe").val() +
            "&agamaField="+ $("#agamaField").val() +
            "&listKota="+ $("#listKota").val() +
            "&listKecamatan="+ $("#listKecamatan").val() +
            "&listKelurahan="+ $("#listKelurahan").val() +
            "&listTipeAsuransi="+ $("#listTipeAsuransi").val() +
            "&kelaminField="+ $("#kelaminField").val() +
            "&maritalField="+ $("#maritalField").val() +
            "&listPendidikan="+ $("#listPendidikan").val() +
            "&listTipePasien="+ $("#listTipePasien").val() +
            "&listGolDarah="+ $("#listGolDarah").val() +
            "&titleDepanField="+ $("#titleDepanField").val() +
            "&namaPasienField="+ $("#namaPasienField").val() +
            "&gelarField="+ $("#gelarField").val() +
            "&alamatField="+ $("#alamatField").val() +
            "&telpField="+ $("#telpField").val() +
            "&hpField="+ $("#hpField").val() +
            "&tmpLahirField="+ $("#tmpLahirField").val() +
            "&tglLahirField="+ tglLahir +
            "&sukuField="+ $("#sukuField").val() +
            "&kebangsaanField="+ $("#kebangsaanField").val() +
            "&jenisIdField="+ $("#jenisIdField").val() +
            "&noIdField="+ $("#noIdField").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#namaPasienField").val(), 'Nama Pasien');
        bvalid = bvalid && checkSelect($("#kelaminField").val(), 'Jenis Kelamin');
        bvalid = bvalid && checkSelect(tglLahir, 'Tanggal Lahir / Usia');
        bvalid = bvalid && checkSelect($("#maritalField").val(), 'Status Perkawinan');
        bvalid = bvalid && checkSelect($("#listTipeAsuransi").val(), 'Tipe Asuransi');
        bvalid = bvalid && checkSelect($("#listTipePasien").val(), 'Tipe Pasien');
        bvalid = bvalid && checkSelect($("#alamatField").val(), 'Alamat');

        if(bvalid){                  
            $.ajax({  
                type: "POST",  
                url: "json/pasienPost.php",  
                data: dataString,  
                success: function(data) {
                    var returnData = data.split(":");
                    var status = returnData[0];
                    if(status=='TRUE'){
                        $.messager.show({
                            title:'Pasien',
                            timeout:'0',
                            width:'400',
                            height:'150',
                            msg:'Pasien berhasil disimpan dengan No Rekam Medis : <FONT COLOR="#FF0000"> <h2 align="center">' + returnData[1] + '</h2>.</font>',
                            showType:'show'
                        });
                        $("#winPasien").window('close');
                        noRm.focus();                        
                        frmPasien.reset();
                        return false;
                    } else if(status=='WARNING') {
                        $.messager.show({
                            title:'Pasien',
                            msg:'Pasien berhasil di simpan, dengan Catatan : <b>' + returnData[1] + '</b>',
                            showType:'show'
                        });
                        $("#winPasien").window('close');
                        noRm.focus();                        
                        frmPasien.reset();
                        return false;
                    } else if(status=='FALSE') {
                        $.messager.show({
                            title:'Pasien',
                            msg:'Gagal menyimpan Pasien.',
                            showType:'show'
                        });
                        titleDepanField.focus();
                        frmPasien.reset();
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function loadDataPasien(id){
        $('#detailPasien').propertygrid({url:'json/pasien.php?task=cariPasienID&id=' + id});
        id_pasien.value=id;
    }
    
    function checkKamar(){
        $("#dataListKamar").datagrid({
            url:'json/kamar.php?task=getKamar&id_ruang=' + $('#ruang').val() + '&id_kelas=' + $('#kelas').val()
        })
        $('#winSearchKamar').window('open')
    }
    
    function loadDataListPasien(){
        $("#dataListPasien").datagrid({
            url:'json/data.php?task=getDataListPasien&nama_pasien=' + $('#srcNamaPasien').val() + 
                '&alamat=' + $('#srcAlamat').val() +
                '&tgl_lahir=' + $('#srcTglLahir').datebox("getValue") +
                '&tgl_lahir_to=' + $('#srcTglLahirTo').datebox("getValue") +
                '&kecamatan=' + $('#srcKecamatan').val() +
                '&kelurahan=' + $('#srcKelurahan').val() +
                '&asuransi=' + $('#srcAsuransi').val() +
                '&tipe_pasien=' + $('#srcTipePasien').val()
        })
    }
    
    function closeSearchKamar(){
        $('#winSearchKamar').window('close')
    }
    
    function openWinDaftar(){
        if(noRm.value=='') {
            $.messager.show({
                title:'Pendaftaran',
                msg:'No. Rekam Medis Pasien belum diisi!',
                showType:'show'
            });
            noRm.focus();
        } else {
            $("#id_pasien").val(noRm.value);
            $("#winDaftar").window("open");
        }
    }
    
    function openWinSearchPasien(){
        $("#winSearchPasien").window('open');
        srcNamaPasien.focus();
    }
    
    function closeWinSearchPasien(){
        $("#winSearchPasien").window('close');
        noRm.focus();
    }
    
    function openWinPasien(){
        $('#winPasien').window('open');
        $('#tipe').val('Insert');
        titleDepanField.focus();
    }
    
    function openWinEditPasien(){
        if(noRm.value=='') {
            $.messager.show({
                title:'Pasien',
                msg:'NO Rekam Medis Pasien belum diisi!',
                showType:'show'
            });
            noRm.focus();
        } else {
            $('#winPasien').window('open');
            $('#frmPasien').form('load','json/pasien.php?task=getPasienDetail&id_pasien=' + $("#noRm").val());
            $('#tipe').val('Edit');
            titleDepanField.focus();
            $( "#listTipePasien" ).focus(function(){
                $.getJSON("json/data.php", {task: 'listTipePasien', id_tipe_asuransi: listTipeAsuransi.value},
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
        }
    }

    function pasienReload(){
        if($("#pasienId").val()=='')
            frmPasien.reset();
        else
            $('#frmPasien').form('load','json/pasien.php?task=getPasienDetail&id_pasien=' + $("#noRm").val());
    }
    function closeWinDaftar(){
        frmDaftar.reset();
        $("#winDaftar").window('close');
    }
    
    function closeWinPasien(){
        frmPasien.reset();
        $("#winPasien").window('close');
    }
</script>