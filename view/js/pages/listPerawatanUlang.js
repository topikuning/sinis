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

        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPerawatan.php?task=cariPerawatanUlang&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
            frozenColumns:[[
                    {title:'ID',field:'id_pendaftaran',width:80,sortable:true,hidden:true},
                    {title:'id_penggunaan_kamar',field:'id_penggunaan_kamar',width:80,sortable:true,hidden:true},
                    {title:'id_tipe_pasien',field:'id_tipe_pasien',width:80,sortable:true,hidden:true},
                    {field:'id_pasien',title:'No RM',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'tipe_pasien',title:'Tipe Pasien',width:100},
                    {field:'id_ruang',title:'ID Ruang',width:1,hidden:true},
                    {field:'ruang',title:'Ruang',width:200,hidden:true},
                    {field:'ruang_asal',title:'Ruang Asal',width:100},
                    {field:'id_kamar',title:'ID Kamar',width:1,hidden:true},
                    {field:'kamar',title:'Kamar',width:80},
                    {field:'id_detail_kamar',title:'Bed',width:1,hidden:true},
                    {field:'bed',title:'Bed',width:100},
                    {field:'id_kelas',title:'ID Kelas',width:1,hidden:true},
                    {field:'kelas',title:'Kelas',width:60},
                    {field:'lama_perawatan',title:'Lama Perawatan',width:50,align:'center'},
                    {field:'tgl_pendaftaran_view',title:'Tanggal Pendaftaran',width:80},
                    {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:1,hidden:true},
                    {field:'usia',title:'Usia',width:1,hidden:true},
                    {field:'dokter',title:'Dokter',width:1,hidden:true},
                    {field:'kelamin',title:'Kelamin',width:1,hidden:true},
                    {field:'jam_daftar',title:'Jam Daftar',width:60},
                    {field:'tarif',title:'Tarif',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }},
                    {field:'status',title:'Status',width:100}
                ]],
            pagination:true,
            rownumbers:true,
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
                            pageList: [100,200,300,400],
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
                    id:'btnDiagnosa',
                    text:'Diagnosa',
                    iconCls:'icon-openrm',
                    handler:function(){
                        popDiagnosa();
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
                            window.open (halaman);
                        }
                    }
                },{
                    id:'btnsum',
                    text:'Close',
                    iconCls:'icon-logout',
                    handler:function(){
                        reClose();
                    }
                }]
        });
    });
    
    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPerawatan.php?task=cariPerawatanUlang&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
    }
    
    function popDiagnosa(){
        $('#id_diagnosa').val('');
        $('#penyakitPrimerId').val('');
        $('#penyakitSekunderId').val('');
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var noDaftar = rows[0].id_pendaftaran;
        var rows = $('#dataPendaftaran').datagrid('getSelections');
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
                            if(tgl_diagnosa=tanggal) {
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
    
    function openWinDiagnosa(){
        $('#winDiagnosa').window('open');
        $('#id_detail_diagnosa').val("");
        frmDetailDiagnosa.reset();
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
            "&tglInput=" + $("#tglInput").val() +
            "&kelase=" + rows[0].id_kelas;

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
    
    function reClose(){
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;
        var perintah = "task=reClose&id_pendaftaran=" + fid +
            "&id_penggunaan=" + rows[0].id_penggunaan_kamar;
        
        var bvalid = true;
        bvalid = bvalid && checkSelect(rows[0].id_penggunaan_kamar, 'Pasien');
        bvalid = bvalid && checkSelect(rows[0].id_pendaftaran, 'Pasien');
        
        if(bvalid){
            $.ajax({
                type: "POST",  
                url: "json/pendaftaran.php",  
                data: perintah,  
                success: function(data) {
                    if(data!='0'){
                        $.messager.show({
                            title:'Rawat Ulang',
                            msg:'Berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataPendaftaran').datagrid('reload');
                    } else if (data=='0'){
                        $.messager.show({
                            title:'Rawat Ulang',
                            msg:'Gagal disimpan.',
                            showType:'show'
                        });
                        $('#dataPendaftaran').datagrid('reload');
                    }
                }
            })
        }
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

        var dataString = "task=getTarifPindah&id_detail_tindakan=" + id + "&kelas=" + rows[0].id_kelas;

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