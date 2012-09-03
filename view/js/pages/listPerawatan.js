<script>

    $(function(){

        $("#dari").inputmask("d-m-y");
        $("#tglVisite").inputmask("d-m-y");
        $("#waktu").inputmask("h:i:s");
        $("#no_rm_pasien").inputmask("999999",{
            "placeholder": ""
        });
        $("#hingga").inputmask("d-m-y");
        $("#tgl_pindah").inputmask("d-m-y");
        $("#tgl_ganti").inputmask("d-m-y");
        $( "#tipe_pendaftaran" ).change(function(){
            $.getJSON("json/data.php", {
                task: 'listRuang', 
                id_tipe_pendaftaran: $(this).val(), 
                id_pasien: id_pasien.value
            },
            function(data) {
                var opt = '';
                opt += '<option value="">[Pilih Ruang]</option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#ruang").html(opt);
                }
            });
            if($(this).val()=="2"){
                if(id_tipe_pasien.value=="2" || id_tipe_pasien.value=="3" || id_tipe_pasien.value=="12"){
                    kelas.value = "JPS";
                    id_kelas.value = "6";
                } else {
                    kelas.value = "Kelas II";
                    id_kelas.value = "2";
                }
            } else {
                switch(id_kelas_pendaftaran.value){
                    case '1':
                        kelas.value = "Kelas I";
                        id_kelas.value = "1";
                        break;
                    case '2':
                        kelas.value = "Kelas II";
                        id_kelas.value = "2";
                        break;
                    case '3':
                        kelas.value = "Kelas III";
                        id_kelas.value = "3";
                        break;
                    case '4':
                        kelas.value = "Kelas VIP";
                        id_kelas.value = "4";
                        break;
                    case '5':
                        kelas.value = "Kelas VVIP";
                        id_kelas.value = "5";
                        break;
                    case '6':
                        kelas.value = "JPS";
                        id_kelas.value = "6";
                        break;
                    case '8':
                        kelas.value = "JPS";
                        id_kelas.value = "6";
                        break;
                    case '9':
                        kelas.value = "KELAS II";
                        id_kelas.value = "2";
                        break;
                    case '13':
                        kelas.value = "JPS";
                        id_kelas.value = "6";
                        break;
                    case '14':
                        kelas.value = "KELAS II";
                        id_kelas.value = "2";
                        break;
                    case '15':
                        kelas.value = "JPS";
                        id_kelas.value = "6";
                        break;
                    default:
                        break;
                }
            }
        });

        $( "#ruang" ).change(function(){
            $.getJSON("json/data.php", {
                task: 'listDokter', 
                id_ruang: $(this).val()
            },
            function(data) {
                var opt = '';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#dokter_konsul").html(opt);
                }
            });
        });

        $( "#ruang_tujuan" ).change(function(){
            $.getJSON("json/data.php", {
                task: 'listKelas', 
                id_ruang: $(this).val(), 
                id_pasien: $("#id_pasien_pindah").val()
            },
            function(data) {
                var opt = '';
                opt += '<option value="">[Pilih Kelas]</option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#kelas_tujuan").html(opt);
                }
            });
        });

        $("#kelas_tujuan").change(function(){
            $.getJSON("json/data.php", {
                task: 'listKamar', 
                id_ruang: $("#ruang_tujuan").val(), 
                id_kelas: $(this).val()
            },
            function(data) {
                var opt = '';
                opt += '<option value="">[Pilih Kamar]</option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#kamar_tujuan").html(opt);
                }
            });
        });
        $("#kamar_tujuan").change(function(){
            $.getJSON("json/data.php", {
                task: 'listBed', 
                id_kamar: $(this).val()
            },
            function(data) {
                var opt = '';
                opt += '<option value="">[Pilih Bed]</option>';
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#bed_tujuan").html(opt);
                }
            });
        });

        $("#kamar_pindah").change(function(){
            var opt = '';
            opt += '<option value="">[Pilih Bed]</option>';
            $("#bed_pindah").html(opt);
            $.getJSON("json/data.php", {
                task: 'listBed', 
                id_kamar: $(this).val()
            },
            function(data) {
                if(data.length>0){
                    for (var i = 0; i < data.length; i++) {
                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                    };

                    $("#bed_pindah").html(opt);
                }
            });
        });

        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPerawatan.php?task=cariPerawatan&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
            frozenColumns:[[
                    {title:'ID',field:'id_pendaftaran',width:80,sortable:true,hidden:true},
                    {title:'id_penggunaan_kamar',field:'id_penggunaan_kamar',width:80,sortable:true,hidden:true},
                    {title:'id_tipe_pasien',field:'id_tipe_pasien',width:80,sortable:true,hidden:true},
                    {field:'id_pasien',title:'No RM',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'alamat',title:'Alamat',width:200},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:85},
                    {field:'id_ruang',title:'ID Ruang',width:1,hidden:true},
                    {field:'ruang',title:'Ruang',width:200,hidden:true},
                    {field:'double_bed',title:'Double Bed',width:1,hidden:true},
                    {field:'ruang_asal',title:'Ruang Asal',width:85},
                    {field:'id_kamar',title:'ID Kamar',width:1,hidden:true},
                    {field:'kamar',title:'Kamar',width:80},
                    {field:'id_detail_kamar',title:'Bed',width:1,hidden:true},
                    {field:'bed',title:'Bed',width:40},
                    {field:'id_kelas',title:'ID Kelas',width:1,hidden:true},
                    {field:'kelas',title:'Kelas',width:45},
                    {field:'lama_perawatan',title:'Lama',width:45,align:'center'},
                    {field:'tgl_pendaftaran_view',title:'Tanggal Masuk',width:75,sortable:true},
                    {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:1,hidden:true},
                    {field:'usia',title:'Usia',width:1,hidden:true},
                    {field:'dokter',title:'Dokter',width:1,hidden:true},
                    {field:'kelamin',title:'Kelamin',width:1,hidden:true},
                    {field:'jam_daftar',title:'Jam Daftar',width:60},
                    {field:'tarif',title:'Tarif',width:75,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }},
                    {field:'askep',title:'Askep',width:75,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }}
                ]],
            pagination:true,
            rownumbers:true,
            remoteSort: false,
            onDblClickRow:function(){
                popDiagnosa();
            },
            toolbar:[{
                    id:'btnLayanan',
                    text:'Layanan',
                    iconCls:'icon-openrm',
                    handler:function(){

                        var rows = $('#dataPendaftaran').datagrid('getSelections');
                        var noDftr = rows[0].id_pendaftaran;
                        var idPasien = rows[0].id_pasien;

                        $("#tglInput").inputmask("d-m-y");
                        $("#idp").val(rows[0].id_pendaftaran);
                        $("#rm_pas").val(idPasien);
                        $("#nm_pas").val(rows[0].nama_pasien);
                        $("#kl_pas").val(rows[0].kelas);
                        $("#jn_pas").val(rows[0].tipe_pasien);
                        $("#jk_pas").val(rows[0].kelamin);
                        $("#ag_pas").val(rows[0].usia);
                        $("#tarifVisite").val("");
                        dokter.setComboValue(rows[0].dokter);

                        $('#dataFasilitas').datagrid({
                            title:'Data Fasilitas',
                            height:250,
                            singleSelect:true,
                            nowrap: false,
                            striped: true,
                            url:'json/tindakan.php?task=getFasilitasRuang&no_pendaftaran=' + noDftr,
                            sortName: 'id_fasilitas_ruang',
                            sortOrder: 'des',
                            remoteSort: false,
                            showFooter:true,
                            collapsible:true,
                        
                            idField:'id_fasilitas_ruang',
                            frozenColumns:[[
                                    {title:'ID',field:'id_fasilitas_ruang',width:50,hidden: true},
                                ]],
                            columns:[[
                                    {field:'tindakan',title:'Fasilitas',width:250},
                                    {field:'dokter',title:'Dokter',width:150},
                                    {field:'id_dokter',title:'idd',width:1,hidden:true},
                                    {field:'jumlah',title:'Jumlah',width:50},
                                    {field:'tarif',title:'Tarif',width:120,align:'right',
                                        formatter:function(value){
                                            if(value>0)
                                                return formatCurrency(value);
                                            else
                                                return 'Rp. 0';
                                        }
                                    },
                                    {field:'total',title:'Total',width:120,align:'right',
                                        formatter:function(value){
                                            if(value>0)
                                                return formatCurrency(value);
                                            else
                                                return 'Rp. 0';
                                        }
                                    }
                                ]],
                            pagination:true,
                            rownumbers:true,
                            onDblClickRow:function(rowIndex){
                                var rows = $('#dataFasilitas').datagrid('getSelections');
                                var id_fasilitas_ruang = rows[0].id_fasilitas_ruang;
                                var fasilitas = rows[0].tindakan;
                                $('#inFasilitas').form('load','json/tindakan.php?task=getDtlFasilitas&id_fasilitas_ruang=' + id_fasilitas_ruang);
                                dFasilitas.setComboText(fasilitas)
                                dokterF.setComboValue(rows[0].id_dokter)
                                dFasilitas.DOMelem_input.select()
                            },
                            toolbar:[{
                                    id:'btndelfasilitas',
                                    text:'Hapus',
                                    iconCls:'icon-remove',
                                    handler:function(){
                                        var rows = $('#dataFasilitas').datagrid('getSelections');
                                        var id = "task=hapusFasilitas&id_fasilitas=" + rows[0].id_fasilitas_ruang;
                                        $.ajax({  
                                            type: "POST",  
                                            url: "json/tindakanPost.php",  
                                            data: id,  
                                            success: function(data) {  
                                                if(data=='1'){
                                                    $.messager.show({
                                                        title:'Fasilitas',
                                                        msg:'Penghapusan Fasilitas Nomor ' + rows[0].id_fasilitas_ruang + ' berhasil.',
                                                        showType:'show'
                                                    });
                                                    var row = $('#dataFasilitas').datagrid('getSelected');
                                                    var index = $('#dataFasilitas').datagrid('getRowIndex', row);
                                                    $('#dataFasilitas').datagrid('deleteRow', index);
                                                    $('#dataFasilitas').datagrid('reload');
                                                } else if (data=='2'){
                                                    $.messager.show({
                                                        title:'Fasilitas',
                                                        msg:'Penghapusan Gagal. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                                                        showType:'show'
                                                    });
                                                    return false;
                                                } else if (data=='0') {
                                                    $.messager.show({
                                                        title:'Fasilitas',
                                                        msg:'Penghapusan Fasilitas no ' + rows[0].id_fasilitas_ruang + ' gagal.',
                                                        showType:'show'
                                                    });
                                                }
                                            }
                                        })
                                    }
                                }]
                        });

                        $('#dataTindakan').datagrid({
                            title:'Data Tindakan',
                            height:250,
                            singleSelect:true,
                            nowrap: false,
                            striped: true,
                            url:'json/tindakan.php?task=getTindakanRuang&no_pendaftaran=' + noDftr,
                            sortName: 'id_tindakan_ruang',
                            sortOrder: 'des',
                            remoteSort: false,
                            showFooter:true,
                            collapsible:true,
                        
                            idField:'id_tindakan_ruang',
                            frozenColumns:[[
                                    {title:'ID',field:'id_tindakan_ruang',width:1,hidden: true},
                                ]],
                            columns:[[
                                    {field:'tindakan',title:'Tindakan',width:250},
                                    {field:'icd',title:'ICD',width:60,hidden: true},
                                    {field:'tanggal',title:'Tanggal',width:100},
                                    {field:'dokter',title:'Dokter',width:150},
                                    {field:'advice',title:'Keterangan',width:150},
                                    {field:'tarif',title:'Tarif',width:80,align:'right',
                                        formatter:function(value){
                                            if(value>0)
                                                return formatCurrency(value);
                                            else
                                                return 'Rp. 0';
                                        }

                                    },
                                    {field:'operator',title:'Operator',width:80}
                                ]],
                            pagination:true,
                            rownumbers:true,
                            onDblClickRow:function(rowIndex){
                                var rows = $('#dataTindakan').datagrid('getSelections');
                                var id_tindakan_ruang = rows[0].id_tindakan_ruang;
                                var tindakan = rows[0].tindakan;
                                $('#inTindakan').form('load','json/tindakan.php?task=getDtlTindakan&id_tindakan_ruang=' + id_tindakan_ruang);
                                dTindakan.setComboText(tindakan)
                                dTindakan.DOMelem_input.select()
                            },
                            toolbar:[{
                                    id:'btndel',
                                    text:'Hapus',
                                    iconCls:'icon-remove',
                                    handler:function(){
                                        var rows = $('#dataTindakan').datagrid('getSelections');
                                        var id = "task=hapusTindakan&id_tindakan=" + rows[0].id_tindakan_ruang;
                                        $.ajax({  
                                            type: "POST",  
                                            url: "json/tindakanPost.php",  
                                            data: id,  
                                            success: function(data) {  
                                                if(data=='1'){
                                                    $.messager.show({
                                                        title:'Tindakan',
                                                        msg:'Penghapusan Tindakan Nomor ' + rows[0].id_tindakan_ruang + ' berhasil.',
                                                        showType:'show'
                                                    });
                                                    var row = $('#dataTindakan').datagrid('getSelected');
                                                    var index = $('#dataTindakan').datagrid('getRowIndex', row);
                                                    $('#dataTindakan').datagrid('deleteRow', index);
                                                    $('#dataTindakan').datagrid('reload');
                                                } else if (data=='2'){
                                                    $.messager.show({
                                                        title:'Tindakan',
                                                        msg:'Penghapusan Gagal. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                                                        showType:'show'
                                                    });
                                                    return false;
                                                } else if (data=='0') {
                                                    $.messager.show({
                                                        title:'Diagnosa',
                                                        msg:'Penghapusan Tindakan no ' + rows[0].id_tindakan_ruang + ' gagal.',
                                                        showType:'show'
                                                    });
                                                }
                                            }
                                        })
                                    }
                                },{
                                    id:'btndiskon',
                                    text:'Diskon',
                                    iconCls:'icon-bayar',
                                    handler:function(){
                                        $('#frmDiskon').form('load','json/data.php?task=getResumeTagihanPasien&id_pasien=' + idPasien);
                                        openWinDiskon();
                                    }
                                }]
                        });
                        
                        $('#dataVisit').datagrid({
                            title:'Data Visit Dokter',
                            height:250,
                            singleSelect:true,
                            nowrap: false,
                            striped: true,
                            showFooter:true,
                            url:'json/perawatan.php?task=getVisitDokter&id_pendaftaran=' + noDftr,
                            sortName: 'id_visit',
                            sortOrder: 'asc',
                            remoteSort: false,
                            collapsible: true,
                        
                            idField:'id_visit',
                            frozenColumns:[[
                                    {title:'ID',field:'id_visit',width:50,hidden:true},
                                    {title:'idd',field:'id_dokter',width:50,hidden:true}
                                ]],
                            columns:[[
                                    {field:'dokter',title:'Dokter',width:250},
                                    {field:'jenis_dokter',title:'Jenis Dokter',width:250},
                                    {field:'tgl_visit',title:'Tanggal Visit',width:100},
                                    {field:'tarif',title:'Biaya',width:100,align:'right',
                                        formatter:function(value){
                                            if(value>0)
                                                return formatCurrency(value);
                                            else
                                                return 'Rp. 0';
                                        }
                                    }
                                ]],
                            pagination:true,
                            rownumbers:true,
                            onDblClickRow:function(rowIndex){
                                var rows = $('#dataVisit').datagrid('getSelections');
                                var id_visit = rows[0].id_visit;
                                var id_dokter = rows[0].id_dokter;
                                $('#frmVisitDOkter').form('load','json/perawatan.php?task=getVisit&id_visit=' + id_visit);
                                dokterV.setComboValue(id_dokter);
                                dokterV.DOMelem_input.select()
                            },
                            toolbar:[{
                                    id:'btndel',
                                    text:'Hapus',
                                    iconCls:'icon-remove',
                                    handler:function(){
                                        var rows = $('#dataVisit').datagrid('getSelections');
                                        var id_visit = "task=hapusVisit&id_visit=" + rows[0].id_visit;
                                        $.ajax({  
                                            type: "GET",  
                                            url: "json/perawatan.php",  
                                            data: id_visit,  
                                            success: function(data) {  
                                                if(data=='1'){
                                                    $.messager.show({
                                                        title:'Visit Dokter',
                                                        msg:'Penghapusan Visit Dokter berhasil.',
                                                        showType:'show'
                                                    });
                                                    var row = $('#dataVisit').datagrid('getSelected');
                                                    var index = $('#dataVisit').datagrid('getRowIndex', row);
                                                    $('#dataVisit').datagrid('deleteRow', index);
                                                    $('#dataVisit').datagrid('reload');
                                                } else if (data=='2') {
                                                    $.messager.show({
                                                        title:'Visit Dokter',
                                                        msg:'Penghapusan Gagal. Pasien Sudah Melunasi Tagihan',
                                                        showType:'show'
                                                    });
                                                } else if (data=='0') {
                                                    $.messager.show({
                                                        title:'Visit Dokter',
                                                        msg:'Penghapusan Visit Dokter gagal.',
                                                        showType:'show'
                                                    });
                                                }
                                            }
                                        })
                                    }
                                }]
                        });

                        openWinLayanan();
                    }
                },{
                    id:'btnDiet',
                    text:'Diet',
                    iconCls:'icon-openrm',
                    handler:function(){

                        var rows = $('#dataPendaftaran').datagrid('getSelections');
                        var noDftr = rows[0].id_pendaftaran;
                        var idPasien = rows[0].id_pasien;

                        $("#idpdi").val(rows[0].id_pendaftaran);
                        $("#rm_di").val(idPasien);
                        $("#nm_di").val(rows[0].nama_pasien);
                        $("#kl_di").val(rows[0].kelas);
                        $("#jn_di").val(rows[0].tipe_pasien);
                        $("#jk_di").val(rows[0].kelamin);
                        $("#ag_di").val(rows[0].usia);
                        $("#diet").val("");
                        $("#jns_diet").val("");
                        $("#keterangan").val("");
                        $("#waktuDiet").val("");

                        $('#dataDiet').datagrid({
                            title:'Data Diet Pasien',
                            height:250,
                            singleSelect:true,
                            nowrap: false,
                            striped: true,
                            url:'json/perawatan.php?task=getDetailDiet&no_pendaftaran=' + noDftr,
                            sortName: 'id_detail_diet',
                            sortOrder: 'asc',
                            remoteSort: false,
                            collapsible: true,                        
                            idField:'id_detail_diet',
                            frozenColumns:[[
                                    {title:'ID',field:'id_detail_diet',width:1,hidden:true}
                                ]],
                            columns:[[
                                    {field:'id_diet',title:'ID Diet',width:1,hidden:true},
                                    {field:'diet',title:'Diet',width:100},
                                    {field:'id_jenis_diet',title:'ID Jenis Diet',width:1,hidden:true},
                                    {field:'jenis_diet',title:'Jenis Diet',width:100},
                                    {field:'waktu_diet',title:'Waktu Diet',width:100,
                                        formatter:function(value){
                                            if(value=='1')
                                                return 'Pagi';
                                            else if (value=='2')
                                                return 'Siang';
                                            else if (value=='3')
                                                return 'Sore';
                                        }
                                    },
                                    {field:'tgl_diet',title:'Tanggal',width:80},
                                    {field:'keterangan',title:'Keterangan',width:100}
                                ]],
                            pagination:true,
                            rownumbers:true,
                            onDblClickRow:function(rowIndex){
                                var rows = $('#dataDiet').datagrid('getSelections');
                                var id_detail_diet = rows[0].id_detail_diet;
                                $('#dietPasien').form('load','json/perawatan.php?task=getDiet&id_detail_diet=' + id_detail_diet);
                                setTimeout("setCheckDiet()",300)
                                diet.focus();
                            },
                            toolbar:[{
                                    id:'btndel',
                                    text:'Hapus',
                                    iconCls:'icon-remove',
                                    handler:function(){
                                        var rows = $('#dataDiet').datagrid('getSelections');
                                        var id_detail_diet = "task=hapusDiet&id_detail_diet=" + rows[0].id_detail_diet;

                                        $.ajax({  
                                            type: "GET",  
                                            url: "json/perawatan.php",  
                                            data: id_detail_diet,  
                                            success: function(data) {  
                                                if(data=='1'){
                                                    $.messager.show({
                                                        title:'Diet Pasien',
                                                        msg:'Penghapusan Diet Pasien berhasil.',
                                                        showType:'show'
                                                    });
                                                    var row = $('#dataDiet').datagrid('getSelected');
                                                    var index = $('#dataDiet').datagrid('getRowIndex', row);
                                                    $('#dataDiet').datagrid('deleteRow', index);
                                                    $('#dataDiet').datagrid('reload');
                                                    $("#id_detail_diet").val("");
                                                    $("#checkWaktu").val("");
                                                } else if (data=='0') {
                                                    $.messager.show({
                                                        title:'Diet Pasien',
                                                        msg:'Penghapusan Diet Pasien gagal.',
                                                        showType:'show'
                                                    });
                                                }
                                            }
                                        })
                                    }
                                }]
                        });

                        openWinDietPop();
                        $('#id_detail_diet').val('');
                        $('#checkWaktu').val('');
                    }
                },{
                    id:'btnDiagnosa',
                    text:'Diagnosa',
                    iconCls:'icon-openrm',
                    handler:function(){
                        popDiagnosa();
                    }
                },{
                    id:'btnkonsul',
                    text:'Konsul',
                    iconCls:'icon-daftar',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            if(row.status=='1'){
                                $("#id_pasien").val(row.id_pasien);
                                $("#id_tipe_pasien").val(row.id_tipe_pasien);
                                $("#id_kelas_pendaftaran").val(row.id_kelas);
                                $("#id_asal_pendaftaran").val(row.id_pendaftaran);

                                openWinDaftar();
                            }
                        }
                    }
                },{
                    id:'btnedit',
                    text:'Pindah Kamar',
                    iconCls:'icon-redo',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            if(row.status=='1'){
                                $("#id_pindah_kamar").val(row.id_penggunaan_kamar);
                                $("#ruangan").html(row.ruang);
                                $("#kelas_ruangan").html(row.kelas);
                                $.getJSON("json/data.php", {
                                    task: 'listKamar', 
                                    id_ruang: row.id_ruang, 
                                    id_kelas: row.id_kelas
                                },
                                function(data) {
                                    var opt = '';
                                    opt += '<option value="">[Pilih Kamar]</option>';
                                    if(data.length>0){
                                        for (var i = 0; i < data.length; i++) {
                                            opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                        };

                                        $("#kamar_pindah").html(opt);
                                    }
                                });

                                openWinGanti();
                            }
                        }
                    }
                },{
                    id:'btnpindah',
                    text:'Pindah Ruang',
                    iconCls:'icon-undo',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            if(row.status=='1'){
                                frmPindah.reset();
                                $("#doubleBed").val('2');
                                $("#id_penggunaan_kamar").val(row.id_penggunaan_kamar);
                                $("#id_pendaftaran").val(row.id_pendaftaran);
                                $("#id_pasien_pindah").val(row.id_pasien);
                                $("#tgl_masuk").val(row.tgl_pendaftaran);
                                $("#jam_masuk").val(row.jam_daftar);
                                $.getJSON("json/data.php", {
                                    task: 'listRuang', 
                                    id_tipe_pendaftaran: 6, 
                                    id_pasien: row.id_pasien
                                },
                                function(data) {
                                    var opt='';
                                    opt += '<option value="">[Pilih Ruang]</option>';
                                    if(data.length>0){
                                        for (var i = 0; i < data.length; i++) {
                                            opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                        };

                                        $("#ruang_tujuan").html(opt);
                                    }
                                });
                                var opt = '';
                                opt += '<option value="">[Pilih Bed]</option>';
                                $("#bed_tujuan").html(opt);
                                var opt = '';
                                opt += '<option value="">[Pilih Kelas]</option>';
                                $("#kelas_tujuan").html(opt);
                                var opt = '';
                                opt += '<option value="">[Pilih Kamar]</option>';
                                $("#kamar_tujuan").html(opt);

                                openWinPindah();
                            }
                        }
                    }
                },{
                    id:'btneditkelas',
                    text:'Pindah Kelas',
                    iconCls:'icon-edit',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        $("#ruang_edit").val(row.id_ruang);
                        $("#id_pendaftaran_edit").val(row.id_pendaftaran);
                        $("#id_pasien_edit").val(row.id_pasien);
                        $("#tgl_masuke").val(row.tgl_pendaftaran);
                        tipe_edit.disabled = true;
                        kelas_edit.disabled = false;
                        if(row.id_tipe_pasien=='1'){
                            $("#tipe_edit").val('2');
                            var opt = '';
                            $.getJSON("json/data.php", {task: 'listTipePx', tipe: $("#tipe_edit").val()},
                            function(data) {
                                if(data.length>0){
                                    var opt = '';
                                    for (var i = 0; i < data.length; i++) {
                                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                    };

                                    $("#tipe_pasien_edit").html(opt);
                                }
                            });
                            $( "#tipe_pasien_edit" ).change(function(){
                                if($("#tipe_pasien_edit").val() == 2 || $("#tipe_pasien_edit").val() == 3 || $("#tipe_pasien_edit").val() == 12){
                                    var opt = '';
                                    $.getJSON("json/data.php", {
                                        task: 'listKelasJPS'
                                    },
                                    function(data) {
                                        if(data.length>0){
                                            var opt = '';
                                            opt += '<option value=""></option>';
                                            for (var i = 0; i < data.length; i++) {
                                                opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                            };
                                            $("#kelas_edit").html(opt);
                                        }
                                    });
                                } else {
                                    var opt = '';
                                    $.getJSON("json/data.php", {
                                        task: 'listKelasUmum'
                                    },
                                    function(data) {
                                        if(data.length>0){
                                            var opt = '';
                                            for (var i = 0; i < data.length; i++) {
                                                opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                            };
                                            $("#kelas_edit").html(opt);
                                        }
                                    });
                                }
                            });
                        } else if (row.id_tipe_pasien!='2' && row.id_tipe_pasien!='3' && row.id_tipe_pasien!='12' && row.id_tipe_pasien!='1') {
                            $("#tipe_edit").val('3');
                            kelas_edit.disabled = false;
                            tipe_edit.disabled = true;
                            var opt = '';
                            $.getJSON("json/data.php", {task: 'listTipePx', tipe: $("#tipe_edit").val()},
                            function(data) {
                                if(data.length>0){
                                    var opt = '';
                                    for (var i = 0; i < data.length; i++) {
                                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                    };

                                    $("#tipe_pasien_edit").html(opt);
                                }
                            });
                            $( "#tipe_pasien_edit" ).change(function(){
                                if($("#tipe_pasien_edit").val() == 2 || $("#tipe_pasien_edit").val() == 3 || $("#tipe_pasien_edit").val() == 12){
                                    var opt = '';
                                    $.getJSON("json/data.php", {
                                        task: 'listKelasJPS'
                                    },
                                    function(data) {
                                        if(data.length>0){
                                            var opt = '';
                                            opt += '<option value=""></option>';
                                            for (var i = 0; i < data.length; i++) {
                                                opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                            };
                                            $("#kelas_edit").html(opt);
                                        }
                                    });
                                } else {
                                    var opt = '';
                                    $.getJSON("json/data.php", {
                                        task: 'listKelasUmum'
                                    },
                                    function(data) {
                                        if(data.length>0){
                                            var opt = '';
                                            for (var i = 0; i < data.length; i++) {
                                                opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                            };
                                            $("#kelas_edit").html(opt);
                                        }
                                    });
                                }
                            });
                        } else {
                            tipe_edit.disabled = true;
                            kelas_edit.disabled = false;
                            $("#tipe_edit").val('1');
                            var opt = '';
                            $.getJSON("json/data.php", {
                                task: 'listKelasUmum'
                            },
                            function(data) {
                                if(data.length>0){
                                    var opt = '';
                                    for (var i = 0; i < data.length; i++) {
                                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                    };

                                    $("#kelas_edit").html(opt);
                                }
                            });
                            var opt = '';
                            $.getJSON("json/data.php", {task: 'listTipePx', tipe: $("#tipe_edit").val()},
                            function(data) {
                                if(data.length>0){
                                    var opt = '';
                                    for (var i = 0; i < data.length; i++) {
                                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                    };

                                    $("#tipe_pasien_edit").html(opt);
                                }
                            });
                        }
                        kelas_edit.focus();
                        openWinEditKelas();
                    }
                },{
                    id:'btneditharga',
                    text:'Update Harga',
                    iconCls:'icon-kelas',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        $("#ruang_harga").val(row.id_ruang);
                        $("#id_pendaftaran_harga").val(row.id_pendaftaran);
                        $("#id_pasien_harga").val(row.id_pasien);
                        if(row.id_tipe_pasien!='2' && row.id_tipe_pasien!='3' && row.id_tipe_pasien!='12'){
                            var opt = '';
                            $.getJSON("json/data.php", {
                                task: 'listKelasUmum'
                            },
                            function(data) {
                                if(data.length>0){
                                    var opt = '';
                                    for (var i = 0; i < data.length; i++) {
                                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                    };

                                    $("#kelas_harga").html(opt);
                                }
                            });
                        } else {
                            var opt = '';
                            $.getJSON("json/data.php", {
                                task: 'listKelasHarga'
                            },
                            function(data) {
                                if(data.length>0){
                                    var opt = '';
                                    for (var i = 0; i < data.length; i++) {
                                        opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                                    };

                                    $("#kelas_harga").html(opt);
                                }
                            });
                        }
                        kelas_harga.focus();
                        openWinEditHarga();
                    }
                },{
                    id:'btntagihan',
                    text:'Tagihan',
                    iconCls:'icon-calc',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            window.open ("?page=dftrtghnpx&fid=" + row.id_pendaftaran + "&pid=" + row.id_pasien);
                        }
                    }
                },{
                    id:'btnsum',
                    text:'Summary',
                    iconCls:'icon-sum',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            var halaman = "?page=smry&fid=" + row.id_pendaftaran + "&pid=" + row.id_pasien;
                            //window.location = "?page=smry&fid=" + row.id_pendaftaran + "&pid=" + row.id_pasien;
                            window.open (halaman);
                        }
                    }
                },{
                    id:'btnLab',
                    text:'Laborat',
                    iconCls:'icon-lab',
                    handler:function(){
                        var row = $('#dataPendaftaran').datagrid('getSelected');
                        var index = $('#dataPendaftaran').datagrid('getRowIndex', row);
                        if(index>=0){
                            window.open ("../../reports/Hasil_Labsmry.php?so1_RM_Px2E=%3D&sv1_RM_Px2E="+ row.id_pasien + "&Submit=Search");
                        }
                    }
                },{
                    id:'btnRM',
                    text:'Rekam Medis',
                    iconCls:'icon-double',
                    handler:function(){
                        goToRM();
                    }
                },{
                    id:'btnClose',
                    text:'Selesai',
                    iconCls:'icon-logout',
                    handler:function(){
                        openClosePerawatan();
                    }
                }]
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
                    $("#kamar_tujuan").val(rows[0].id_kamar);
                    $.getJSON("json/data.php", {
                        task: 'listBed', 
                        id_kamar: rows[0].id_kamar
                    },
                    function(data) {
                        var opt = '';
                        opt += '<option value="">[Pilih Bed]</option>';
                        if(data.length>0){
                            for (var i = 0; i < data.length; i++) {
                                opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                            };

                            $("#bed_tujuan").html(opt);
                        }
                    });
                    $("#bed_tujuan").val(rows[0].id_detail_kamar);
                    closeSearchKamar();
                    bed_tujuan.focus();
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
    });

    function simpanPendaftaran(){
        var noDftr = getURL("fid");
        var dataString = "task=simpanPendaftaran&id_tipe_pendaftaran=" + $("#tipe_pendaftaran").val() +
            "&id_pendaftaran=" + $("#id_asal_pendaftaran").val() +
            "&tgl_pendaftaran=" +$("#tgl_pendaftaran").val() +
            "&jadwal=" +$("#jadwal").val() +
            "&waktu=" +$("#waktu").val() +
            "&id_ruang_asal=" + $("#ruang_asal").val() + 
            "&id_ruang=" + $("#ruang").val() + 
            "&id_kelas=" + $("#id_kelas").val() + 
            "&id_kamar=''" + 
            "&id_detail_kamar=''" + 
            "&id_dokter=" + $("#dokter_konsul").val() + 
            "&biaya_pendaftaran=0" + 
            "&asal_rujukan=''" + 
            "&perujuk=''" + 
            "&alasan_rujuk=''" + 
            "&id_pasien=" + $("#id_pasien").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#id_pasien").val(), 'No RM');
        bvalid = bvalid && checkSelect($("#tipe_pendaftaran").val(), 'Tipe Pendaftaran');
        bvalid = bvalid && checkSelect($("#ruang").val(), 'Ruang');
        bvalid = bvalid && checkSelect($("#id_kelas").val(), 'Kelas');

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
                            msg:'Pendaftaran berhasil disimpan dengan No ID : <b>' + returnData[1] + '</b>, No Antrian : <b>' + returnData[2] + '</b> di Ruang : <b>' + returnData[3] + '</b>.',
                            showType:'show'
                        });
                        $("#winDaftar").window('close');
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
                            msg:'Gagal menyimpan pendaftaran.<b>' + returnData[1] + '</b>',
                            showType:'show'
                        });
                        return false;
                    } else if(status=='LUNAS') {
                        $.messager.show({
                            title:'Pendaftaran',
                            msg:'Pasien Sudah Melunasi Tagihan.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    };
    
    function popDiagnosa(){
        
        $('#id_diagnosa').val('');
        $('#penyakitPrimerId').val('');
        $('#penyakitSekunderId').val('');
        
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var noDaftar = rows[0].id_pendaftaran;
        var noDaftar = rows[0].id_pendaftaran;
        var idRuang = rows[0].id_ruang;
        if(idRuang=='18'){
            window.open('index.php?page=pmrrdlg&fid=' + noDaftar);
        } else if (idRuang=='17'){
            window.open('index.php?page=pmrlab&fid=' + noDaftar);
        } else {
            var rows = $('#dataPendaftaran').datagrid('getSelections');
            var noDftr = rows[0].id_pendaftaran;
            var idPasien = rows[0].id_pasien;

            $('#frmDetailDiagnosa').form('load','json/diagnosa.php?task=cariDtlDiagnosa&no_pendaftaran=' + noDftr)
            $("#idx").val(rows[0].id_pendaftaran);
            $("#rm_px").val(idPasien);
            $("#nm_px").val(rows[0].nama_pasien);
            $("#kl_px").val(rows[0].kelas);
            $("#jn_px").val(rows[0].tipe_pasien);
            $("#jk_px").val(rows[0].kelamin);
            $("#ag_px").val(rows[0].usia);
            dokterD.setComboValue(rows[0].dokter);

            $('#dataDiagnosa').datagrid({
                title:'Data Diagnosa',
                height:150,
                singleSelect:true,
                nowrap: false,
                striped: true,
                url:'json/diagnosa.php?task=getDetailDiagnosa&no_pendaftaran=' + noDftr,
                sortName: 'id_diagnosa',
                sortOrder: 'asc',
                remoteSort: false,
                collapsible: true,
                idField:'id_diagnosa',
                frozenColumns:[[
                        {title:'ID',field:'id_diagnosa',width:50,hidden:true},
                    ]],
                columns:[[
                        {field:'status',title:'Status',width:1,hidden:true},
                        {field:'primer',title:'primer',width:1,hidden:true},
                        {field:'sekun',title:'sekun',width:1,hidden:true},
                        {field:'dokter',title:'dokter',width:1,hidden:true},
                        {field:'nama_dokter',title:'Nama Dokter',width:150},
                        {field:'tgl_diagnosa',title:'Tanggal Diagnosa',width:80},
                        {field:'jam_diagnosa',title:'Jam Diagnosa',width:60},
                        {field:'diagnosa_primer',title:'Diagnosa Primer',width:100},
                        {field:'icd_primer',title:'ICD',width:60},
                        {field:'diagnosa_sekunder',title:'Diagnosa Sekunder',width:100},
                        {field:'icd_sekunder',title:'ICD',width:60},

                    ]],
                rownumbers:true,
                onDblClickRow:function(rowIndex){
                    var rows = $('#dataDiagnosa').datagrid('getSelections');
                    var id_diagnosa = rows[0].id_diagnosa;
                    var primer = rows[0].primer;
                    var sekun = rows[0].sekun;
                    var status = rows[0].status;
                    if(status=='1') {
                        $("#penyakitPrimerId").val(primer)
                        dPrim.setComboText(rows[0].diagnosa_primer)
                        $("#penyakitSekunderId").val(sekun)
                        sekunder.setComboText(rows[0].diagnosa_sekunder)
                        dokterD.setComboValue(rows[0].dokter)
                        $("#id_diagnosa").val(id_diagnosa)
                        dokterD.DOMelem_input.select()
                    }
                },
                toolbar:[{
                        id:'btndel',
                        text:'Hapus',
                        iconCls:'icon-remove',
                        handler:function(){
                            var rows = $('#dataDiagnosa').datagrid('getSelections');
                            var id = "task=hapusDiagnosa&id_diagnosa=" + rows[0].id_diagnosa;
                            var tgl_diagnosa = rows[0].tgl_diagnosa;
                            var tanggal = getToday();
                            if(tgl_diagnosa == tanggal) {
                                $.ajax({  
                                    type: "POST",  
                                    url: "json/diagnosaPost.php",  
                                    data: id,  
                                    success: function(data) {  
                                        if(data=='1'){
                                            $.messager.show({
                                                title:'Diagnosa',
                                                msg:'Penghapusan Diagnosa Nomor ' + rows[0].id_diagnosa + ' berhasil.',
                                                showType:'show'
                                            });
                                            $('#dataDiagnosa').datagrid('reload');
                                        } else if (data=='0') {
                                            $.messager.show({
                                                title:'Diagnosa',
                                                msg:'Penghapusan Diagnosa no ' + rows[0].id_diagnosa + ' gagal.',
                                                showType:'show'
                                            });
                                        }
                                    }
                                })
                            }
                        }
                    }]
            });

            $('#dataDetailDiagnosa').datagrid({
                title:'Data Detail Diagnosa',
                height:80,
                singleSelect:true,
                nowrap: false,
                striped: true,
                url:'json/diagnosa.php?task=getDetailDiagnosaLain&no_pendaftaran=' + noDftr,
                sortName: 'id_detail_diagnosa',
                sortOrder: 'asc',
                remoteSort: false,
                collapsible: true,
                idField:'id_detail_diagnosa',
                frozenColumns:[[
                        {title:'ID',field:'id_detail_diagnosa',width:50},
                    ]],
                columns:[[
                        {field:'tgl_diagnosa',title:'Tanggal Diagnosa',width:80},
                        {field:'jam_diagnosa',title:'Jam Diagnosa',width:60},
                        {field:'diagnosa',title:'Diagnosa',width:150},
                        {field:'keluhan',title:'Keluhan',width:150},
                        {field:'hasil_pemeriksaan',title:'Hasil Pemeriksaan',width:200},
                        {field:'terapi',title:'Terapi',width:150},
                        {field:'nadi',title:'Nadi',width:50},
                        {field:'tensi',title:'Tensi',width:50},
                        {field:'temp',title:'Temp',width:50},
                        {field:'nafas',title:'Nafas',width:50},
                        {field:'berat_badan',title:'BB',width:50},
                        {field:'tinggi_badan',title:'TB',width:50}
                    ]],
                rownumbers:true
            });

            openWinDiagnosa();
        }
    }

    function simpanPindahRuang(){
        var dataString = "task=simpanPindahRuang&id_pendaftaran=" + $("#id_pendaftaran").val() +
            "&doubleBed=" +$("#doubleBed").val() +
            "&id_penggunaan_kamar=" +$("#id_penggunaan_kamar").val() +
            "&id_pasien=" +$("#id_pasien_pindah").val() +
            "&tgl_masuk=" +$("#tgl_masuk").val() +
            "&tgl_pindah=" +$("#tgl_pindah").val() +
            "&jam_pindah=" +$("#jam_pindah").val() +
            "&ruang_tujuan=" + $("#ruang_tujuan").val() + 
            "&kelas_tujuan=" + $("#kelas_tujuan").val() + 
            "&kamar_tujuan=" + $("#kamar_tujuan").val() + 
            "&jam_masuk=" + $("#jam_masuk").val() + 
            "&bed_tujuan=" + $("#bed_tujuan").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#tgl_pindah").val(), 'Tanggal Pindah');
        bvalid = bvalid && checkSelect($("#ruang_tujuan").val(), 'Ruang');
        bvalid = bvalid && checkSelect($("#kelas_tujuan").val(), 'Kelas');
        bvalid = bvalid && checkSelect($("#kamar_tujuan").val(), 'Kamar');
        bvalid = bvalid && checkSelect($("#bed_tujuan").val(), 'Bed');

        if(bvalid){
            $('#pinRu').linkbutton({
                disabled:true
            });
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='TANGGAL'){
                        $.messager.show({
                            title:'Pindah Ruang',
                            msg:'<b>TANGGAL PINDAH TIDAK BOLEH LEBIH KECIL DARI TANGGAL MASUK.</b>',
                            showType:'show'
                        });
                        tgl_pindah.focus();
                        $('#pinRu').linkbutton({
                            disabled:false
                        });
                        return false;
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Pindah Ruang',
                            msg:'Pindah Ruang berhasil disimpan.',
                            showType:'show'
                        });
                        closeWinPindah();
                        $('#dataPendaftaran').datagrid('reload');
                        return false;
                    } else {
                        $.messager.show({
                            title:'Pindah Ruang',
                            msg:'Gagal menyimpan Pindah Ruang.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function simpanPindahKelas(){
        var dataString = "task=simpanPindahKelas&id_pendaftaran=" + $("#id_pendaftaran_kelas").val() +
            "&id_kelas=" +$("#tujuan_kelas").val() +
            "&id_ruang=" +$("#id_ruang_kelas").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#tujuan_kelas").val(), 'Tujuan Kelas');

        if(bvalid){
            $('#RubKel').linkbutton({
                disabled:true
            });
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Pindah Kelas',
                            msg:'Pindah Kelas berhasil disimpan.',
                            showType:'show'
                        });
                        closeWinPindahKelas();
                        $('#dataPendaftaran').datagrid('reload');
                        return false;
                    } else {
                        $.messager.show({
                            title:'Pindah Kelas',
                            msg:'Gagal menyimpan Pindah Kelas.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function simpanEditKelas(){
        var dataString = "task=simpanEditKelas&id_pendaftaran=" + $("#id_pendaftaran_edit").val() +
            "&tipe_edit=" +$("#tipe_edit").val() +
            "&id_pasien=" +$("#id_pasien_edit").val() +
            "&tgl_ganti=" +$("#tgl_ganti").val() +
            "&id_kelas=" +$("#kelas_edit").val() + 
            "&tgl_masuke=" +$("#tgl_masuke").val() + 
            "&tipe_px=" +$("#tipe_pasien_edit").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#tipe_edit").val(), 'Tipe Edit Kelas');
        bvalid = bvalid && checkSelect($("#kelas_edit").val(), 'Tujuan Kelas');
        bvalid = bvalid && checkSelect($("#tipe_pasien_edit").val(), 'Tipe Pasien');

        if(bvalid){
            $('#upKel').linkbutton({
                disabled:true
            });
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,  
                success: function(data) {
                    alert(data);
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Edit Kelas',
                            msg:'Edit Kelas berhasil disimpan.',
                            showType:'show'
                        });
                        closeWinEditKelas();
                        $('#dataPendaftaran').datagrid('reload');
                        return false;
                    } else {
                        $.messager.show({
                            title:'Edit Kelas',
                            msg:'Gagal menyimpan Edit Kelas.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function simpanEditHarga(){
        var dataString = "task=simpanEditHarga&id_pendaftaran=" + $("#id_pendaftaran_harga").val() +
            "&tipe_edit=" +$("#tipe_harga").val() +
            "&id_pasien=" +$("#id_pasien_harga").val() +
            "&id_kelas=" +$("#kelas_harga").val() +
            "&dari=" +$("#dari").val() +
            "&hingga=" +$("#hingga").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#kelas_harga").val(), 'Tujuan Kelas');

        if(bvalid){
            $('#edGa').linkbutton({
                disabled:true
            });
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,  
                success: function(data) {
                    alert(data);
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Edit Harga',
                            msg:'Perubahan Harga berhasil disimpan.',
                            showType:'show'
                        });
                        closeWinEditHarga();
                        $('#dataPendaftaran').datagrid('reload');
                        return false;
                    } else {
                        $.messager.show({
                            title:'Edit Harga',
                            msg:'Gagal menyimpan Perubahan Harga.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function simpanPindahKamar(){
        var dataString = "task=simpanPindahKamar&id_penggunaan_kamar=" +$("#id_pindah_kamar").val() +
            "&bed_pindah=" + $("#bed_pindah").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#bed_pindah").val(), 'Bed');

        if(bvalid){
            $('#pinKa').linkbutton({
                disabled:true
            });
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Pindah Kamar',
                            msg:'Pindah Kamar berhasil disimpan.',
                            showType:'show'
                        });
                        closeWinGanti();
                        $('#dataPendaftaran').datagrid('reload');
                        return false;
                    } else {
                        $.messager.show({
                            title:'Pindah Kamar',
                            msg:'Gagal menyimpan Pindah Kamar.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function openWinDaftar(){
        $('#winDaftar').window('open'); 
        tipe_pendaftaran.focus();
    }
    
    function openWinDiagnosa(){
        $('#winDiagnosa').window('open');
        $('#id_detail_diagnosa').val("");
        frmDetailDiagnosa.reset();
        //$('#frmDetailDiagnosa').form('load','json/diagnosa.php?task=cariDtlDiagnosa&no_pendaftaran=' + noDftr)
    }

    function closeWinDaftar(){
        frmDaftar.reset();
        $("#winDaftar").window('close');
    }

    function openWinPindah(){
        $('#winPindah').window('open');        
        tgl_pindah.focus();
        $('#pinRu').linkbutton({
            disabled:false
        });
    }

    function closeWinPindah(){
        frmPindah.reset();
        $("#winPindah").window('close');
    }

    function openWinGanti(){
        $('#winGanti').window('open');        
        kamar_pindah.focus();
        $('#pinKa').linkbutton({
            disabled:false
        });
    }

    function closeWinGanti(){
        frmGanti.reset();
        $("#winGanti").window('close');
    }

    function openWinPindahKelas(){
        $('#RubKel').linkbutton({
            disabled:false
        });
        $('#winPindahKelas').window('open');
    }

    function closeWinPindahKelas(){
        frmPindahKelas.reset();
        $("#winPindahKelas").window('close');
    }

    function openWinEditKelas(){
        $('#winEditKelas').window('open');        
        tipe_pasien_edit.focus();
        $('#upKel').linkbutton({
            disabled:false
        });
    }

    function closeWinEditKelas(){
        $("#id_pendaftaran_edit").val("");
        $("#id_pasien_edit").val("");
        $("#tipe_edit").val("");
        $("#tipe_pasien_edit").val("");
        $("#kelas_edit").val("");
        $("#id_pendaftaran_edit").val("");
        frmEditKelas.reset();
        $("#winEditKelas").window('close');
    }

    function openWinEditHarga(){
        $('#winEditHarga').window('open');        
        kelas_harga.focus();
        $('#edGa').linkbutton({
            disabled:false
        });
    }

    function closeWinEditHarga(){
        frmEditHarga.reset();
        $("#winEditHarga").window('close');
    }

    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPerawatan.php?task=cariPerawatan&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
    }

    function checkKamar(){
        $("#dataListKamar").datagrid({
            url:'json/kamar.php?task=getKamar&id_ruang=' + $('#ruang_tujuan').val() + '&id_kelas=' + $('#kelas_tujuan').val()
        })
        $('#winSearchKamar').window('open')
    }

    function closeSearchKamar(){
        $('#winSearchKamar').window('close')
    }

    function saveTindakan(){
        var citoCheck = '0';
        if(cito.checked)
            citoCheck = '1';
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;
        var dataString = "task=simpanTindakan&id_pendaftaran=" + fid +
            "&id_tindakan_ruang=" +$("#id_tindakan_ruang").val() +
            "&id_tindakan=" +$("#tindakanId").val() +
            "&id_dokter=" + dokter.getSelectedValue() + 
            "&advice=" + $("#advice").val() + 
            "&id_tarif=" + $("#id_tarif").val() +
            "&tarif=" + $("#tarif").val() +
            "&cito=" + citoCheck +
            "&id_operator=" + $("#operator").val() + 
            "&tglInput=" + $("#tglInput").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#tindakanId").val(), 'Tindakan');
        bvalid = bvalid && checkSelect(dokter.getSelectedValue(), 'Dokter');
        bvalid = bvalid && checkSelect($("#operator").val(), 'Operator');
        bvalid = bvalid && checkSelect($("#tglInput").val(), 'Tanggal');

        if(bvalid){                  
            $.ajax({  
                type: "POST",  
                url: "json/tindakanPost.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Tindakan',
                            msg:'Tindakan berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataTindakan').datagrid('reload');
                        $("#id_tindakan_ruang").val("");
                        $("#tindakanId").val("");
                        $("#advice").val("");
                        $("#id_tarif").val("");
                        $("#tarif").val("");
                        dTindakan.setComboValue("");
                        dTindakan.DOMelem_input.focus();
                        return false;
                    } else if (data=='2'){
                        $.messager.show({
                            title:'Tindakan',
                            msg:'Gagal menyimpan Tindakan. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                            showType:'show'
                        });
                        return false;
                    } else if (data=='0'){
                        $.messager.show({
                            title:'Tindakan',
                            msg:'Gagal menyimpan Tindakan.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
        dTindakan.DOMelem_input.focus();
    }

    function saveFasilitas(){
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;

        var dataString = "task=simpanFasilitas&id_pendaftaran=" + fid +
            "&id_fasilitas_ruang=" +$("#id_fasilitas_ruang").val() +
            "&id_tindakan=" +$("#tindakanFId").val() +
            "&jumlah=" + $("#jumlah").val() + 
            "&id_dokter=" + dokterF.getSelectedValue() + 
            "&advice=" + $("#adviceF").val() + 
            "&id_tarif=" + $("#id_tarifF").val() +
            "&tarifF=" + $("#tarifF").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#tindakanFId").val(), 'Fasilitas');
        bvalid = bvalid && checkSelect(dokterF.getSelectedValue(), 'Pelaksana');
        bvalid = bvalid && checkSelect($("#jumlah").val(), 'Jumlah');

        if(bvalid){                  
            $.ajax({  
                type: "POST",  
                url: "json/tindakanPost.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Fasilitas',
                            msg:'Fasilitas berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataFasilitas').datagrid('reload');
                        $("#id_fasilitas_ruang").val()
                        $("#tindakanFId").val("");
                        $("#jumlah").val("");  
                        $("#adviceF").val(""); 
                        $("#id_tarifF").val("");
                        $("#tarifF").val("");
                        dFasilitas.setComboValue("");
                        dFasilitas.DOMelem_input.focus();
                        return false;
                    } else if (data=='2'){
                        $.messager.show({
                            title:'Fasilitas',
                            msg:'Penyimpanan Gagal. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                            showType:'show'
                        });
                        return false;
                    } else {
                        $.messager.show({
                            title:'Fasilitas',
                            msg:'Gagal menyimpan Fasilitas. ' + data,
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
        dFasilitas.DOMelem_input.focus();
    }

    function simpanVisitDokter(){
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;
        var dataString = "task=simpanVisitDokter&id_pendaftaran=" + fid +
            "&id_visit=" + $("#visit").val() + 
            "&id_pasien=" + $("#rm_pas").val() + 
            "&id_dokter=" + dokterV.getSelectedValue() + 
            "&tgl_visit=" + $("#tglVisite").val() + 
            "&tarif=" + $("#tarifVisite").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect(dokterV.getSelectedValue(), 'Dokter');
        bvalid = bvalid && checkSelect($("#tglVisite").val(), 'Tanggal Visit');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Visit Dokter',
                            msg:'Visit Dokter berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataVisit').datagrid('reload');
                        $('#visit').val("");
                        $('#tarifVisite').val("");
                        dokterV.setComboValue("");
                        return false;
                    } else if(data=='2'){
                        $.messager.show({
                            title:'Visit Dokter',
                            msg:'Penyimpanan Gagal, Pasien Sudah Melunasi Tagihan.',
                            showType:'show'
                        });
                        return false;
                    } else if(data=='0'){
                        $.messager.show({
                            title:'Visit Dokter',
                            msg:'Gagal menyimpan Visit Dokter.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    }

    function setTarif(id){
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var noDftr = rows[0].id_pendaftaran;

        var dataString = "task=getTarif&id_detail_tindakan=" + id + "&no_pendaftaran=" + noDftr;

        $.ajax({  
            type: "POST",  
            url: "json/tindakanPost.php",  
            data: dataString,  
            success: function(data) {
                var returnData = data.split(":");
                if(returnData[2]=='1'){
                    $('#tarif').val(returnData[1]);
                    $('#id_tarif').val(returnData[0]);
                } else {
                    $('#tarifF').val(returnData[1]);
                    $('#id_tarifF').val(returnData[0]);
                }
            }  
        });
        return false;
    }
    
    function simpanDiskon(){
        var dataString = "task=simpanDiskonTindakan&id_pendaftaran=" + $("#idp").val() +
            "&id_pasien=" + $("#rm_pas").val() + 
            "&diskon=" + $("#diskon").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#diskon").val(), 'Diskon');

        if(bvalid){
            if(parseInt($("#diskon").val()) > parseFloat($("#kurang").val())){
                $.messager.show({
                    title:'Diskon',
                    msg:'Pemberian Diskon melibihi total tagihan.',
                    showType:'show'
                });
                $("#diskon").val("");
                diskon.focus();
            } else {
                $.ajax({  
                    type: "GET",  
                    url: "json/tindakan.php",  
                    data: dataString,  
                    success: function(data) {
                        if(data=='LOGIN'){
                            alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                            window.location.reload();
                        } else if(data=='1'){
                            $.messager.show({
                                title:'Diskon',
                                msg:'Diskon berhasil disimpan. ' + data,
                                showType:'show'
                            });
                            $('#winDiskon').window('close');
                            return false;
                        } else if (data=='2') {
                            $.messager.show({
                                title:'Diskon',
                                msg:'Pemberian Diskon Gagal. Pasien Sudah Melunasi Tagihan, Silahkan Close Perawatan',
                                showType:'show'
                            });
                        } else {
                            $.messager.show({
                                title:'Diskon',
                                msg:'Gagal menyimpan Diskon.',
                                showType:'show'
                            });
                            $("#diskon").val("");
                            diskon.focus();
                            return false;
                        }
                    }  
                });  
                return false;
            }
        }
    }

    function openWinDiskon(){
        frmDiskon.reset();
        $('#winDiskon').window('open');
        diskon.focus();
    }
    
    function openWinDietPop(){
        $('#winDietPop').window('open');
    }
    
    function saveDiagnosa(){
        
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;
        var dataString = "task=simpanDiagnosa&id_pendaftaran=" + fid +
            "&id_diagnosa=" +$("#id_diagnosa").val() +
            "&id_pasien=" +$("#rm_px").val() +
            "&id_dokter=" + dokterD.getSelectedValue() + 
            "&diagnosa_primer=" + $("#penyakitPrimerId").val() +
            "&diagnosa_sekunder=" + $("#penyakitSekunderId").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#rm_px").val(), 'No RM');
        bvalid = bvalid && checkSelect(dokterD.getSelectedValue(), 'Dokter');
        bvalid = bvalid && checkSelect($("#penyakitPrimerId").val(), 'Diagnosa Primer');

        if(bvalid){                  
            $.ajax({  
                type: "POST",  
                url: "json/diagnosaPost.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Diagnosa',
                            msg:'Diagnosa berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataDiagnosa').datagrid('reload');
                        $("#id_diagnosa").val("")
                        dPrim.setComboValue("")
                        $("#penyakitPrimerId").val("");
                        sekunder.setComboValue("")
                        sekunder.readonly(true)
                        $("#penyakitSekunderId").val("");
                        diagnosa_lain.focus();
                        return false;
                    } else if(status=='0') {
                        $.messager.show({
                            title:'Diagnosa',
                            msg:'Gagal menyimpan Diagnosa.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    }
    
    function simpanDetailDiagnosa(){
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;
        var dataString = "task=simpanDetailDiagnosa&id_pendaftaran=" + fid +
            "&id_detail_diagnosa=" + $("#id_detail_diagnosa").val() + 
            "&diagnosa_lain=" + $("#diagnosa_lain").val() + 
            "&keluhan_lain=" + $("#keluhan_lain").val() +
            "&hasil_pemeriksaan=" + $("#hasil_pemeriksaan").val() + 
            "&terapi=" + $("#terapi").val() + 
            "&nadi=" + $("#nadi").val() + 
            "&tensi=" + $("#tensi").val() + 
            "&temperatur=" + $("#temperatur").val() + 
            "&nafas=" + $("#nafas").val() + 
            "&berat_badan=" + $("#berat_badan").val() + 
            "&tinggi_badan=" + $("#tinggi_badan").val() + 
            "&jenis=" + $("#jKonsultasi").val() + 
            "&id_ruang=" + $("#ruangKonsul").val();

        $.ajax({  
            type: "POST",  
            url: "json/diagnosaPost.php",  
            data: dataString,  
            success: function(data) {
                if(data=='1'){
                    $.messager.show({
                        title:'Diagnosa',
                        msg:'Detail Diagnosa berhasil disimpan.',
                        showType:'show'
                    });
                    $('#frmDetailDiagnosa').form('load','json/diagnosa.php?task=cariDtlDiagnosa&no_pendaftaran=' + fid)
                    return false;
                } else {
                    $.messager.show({
                        title:'Diagnosa',
                        msg:'Gagal menyimpan Detail Diagnosa. ' + data,
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    }
    
    function simpanDetailDiet(){
        var waktuDiet;
        if(dietPagi.checked) waktuDiet = "1";
        else if (dietSiang.checked) waktuDiet = "2";
        else if (dietSore.checked) waktuDiet = "3";
        else waktuDiet = "";

        var dataString = "task=simpanDetailDiet&id_pendaftaran=" + $("#idpdi").val() +
            "&id_detail_diet=" + $("#id_detail_diet").val() + 
            "&id_pasien=" + $("#rm_di").val() + 
            "&id_diet=" + $("#diet").val() + 
            "&id_jenis_diet=" + $("#jns_diet").val() + 
            "&waktu_diet=" + waktuDiet +
            "&tgl_diet=" + $("#tanggalDiet").datebox("getValue") + 
            "&keterangan=" + $("#keterangan").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#diet").val(), 'Diet');
        bvalid = bvalid && checkSelect($("#jns_diet").val(), 'Jenis Diet');
        bvalid = bvalid && checkSelect(waktuDiet, 'Waktu Diet');
        bvalid = bvalid && checkSelect($("#tanggalDiet").datebox("getValue"), 'Tanggal Diet');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(data=='1'){
                        $.messager.show({
                            title:'Diet Pasien',
                            msg:'Diet Pasien berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataDiet').datagrid('reload');
                        $("#id_detail_diet").val("");
                        $("#diet").val("");
                        $("#jns_diet").val("");
                        $("#tanggalDiet").val("");
                        $("#keterangan").val("");
                        diet.focus();
                        return false;
                    } else if(data=='2') {
                        $.messager.show({
                            title:'Diet Pasien',
                            msg:'Gagal menyimpan Diet Pasien. Menu Diet sudah disimpan',
                            showType:'show'
                        });
                        return false;
                    } else if(data=='0'){
                        $.messager.show({
                            title:'Diet Pasien',
                            msg:'Gagal menyimpan Diet Pasien.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    }

    function setCheckDiet(){
        if($('#checkWaktu').val()=="1") dietPagi.checked = true;
        if($('#checkWaktu').val()=="2") dietSiang.checked = true;
        if($('#checkWaktu').val()=="3") dietSore.checked = true;
    }

    //DHTML
    var dTindakan = new dhtmlXCombo("tindakan","tindakan",200);
    var dFasilitas = new dhtmlXCombo("tindakanF","tindakanF",200);
    var dPrim = new dhtmlXCombo("penyakitPrimer","penyakitPrimer",200);
    var sekunder = new dhtmlXCombo("penyakitSekunder","penyakitSekunder",200);
    var dokter = dhtmlXComboFromSelect("dokter");
    var dokterF = dhtmlXComboFromSelect("dokterF");
    var dokterV = dhtmlXComboFromSelect("dokterVisite");
    var dokterD = dhtmlXComboFromSelect("dokterD");
    dTindakan.enableFilteringMode(true,"json/data.php?task=dTindakan",true);
    dFasilitas.enableFilteringMode(true,"json/data.php?task=dFasilitas",true);
    dPrim.enableFilteringMode(true,"json/data.php?task=periksa",true);
    sekunder.enableFilteringMode(true,"json/data.php?task=periksa",true);
    dokter.enableFilteringMode(true);
    dokterF.enableFilteringMode(true);
    dokterV.enableFilteringMode(true);
    dokterD.enableFilteringMode(true);
    dTindakan.attachEvent("onChange", onChangeFunc);
    dFasilitas.attachEvent("onChange", onChangeFuncF);
    dPrim.attachEvent("onChange", onChangeDiagP);
    sekunder.attachEvent("onChange", onChangeDiagS);
    dokterV.attachEvent("onChange", changeVisite);
    dTindakan.attachEvent("onKeyPressed", onKeyPressedFunc);
    dFasilitas.attachEvent("onKeyPressed", onKeyPressedFuncF);
    dokter.attachEvent("onKeyPressed", keyDokter);
    dokterF.attachEvent("onKeyPressed", keyDokterF);
    dokterV.attachEvent("onKeyPressed", keyDokterV);
    dokterD.attachEvent("onKeyPressed", keyDokterD);
    dPrim.attachEvent("onKeyPressed", keyDiagP);
    sekunder.attachEvent("onKeyPressed", keyDiagS);
    sekunder.readonly(true);

</script>
