<script>
    $(function(){
        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            remoteSort: false,
            url:'json/listPendaftaranRuang.php?task=cariPendaftaranRR',
            showFooter:true,
            pageList: [50,100,200,500],
            idField:'id_pendaftaran',
            frozenColumns:[[
                    {field:'no_antrian',title:'No Antrian',width:30,sortable:true},
                    {title:'ID',field:'id_pendaftaran',width:50,sortable:true,hidden:true},
                    {field:'id_pasien',title:'No RM',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'id_tipe_pasien',title:'ID Pasien',width:120,hidden:true},
                    {field:'tipe_pasien',title:'Tipe Pasien',width:100},
                    {field:'kelas',title:'Kelas',width:70},
                    {field:'id_kelas_pendaftaran',title:'ID Kelas',width:120,hidden:true},
                    {field:'id_tipe_pendaftaran',title:'ID Tipe Pendaftaran',width:120,hidden:true},
                    {field:'asal_ruang',title:'Ruang Asal',width:100,hidden:true},
                    {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:100,hidden:true},
                    {field:'alamat',title:'Alamat',width:100},
                    {field:'usia',title:'Usia',width:100},
                    {field:'id_ruang',title:'ID Ruang',width:120,hidden:true},
                    {field:'ruang',title:'Ruang',width:150},
                    {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:100},
                    {field:'jam_daftar',title:'Jam Daftar',width:80},
                    {field:'asal_rujukan',title:'Asal Rujukan',width:120},
                    {field:'perujuk',title:'Perujuk',width:120},
                    {field:'jadwal',title:'Jadwal Layanan',width:180},
                    {field:'dokter',title:'Dokter',width:1,hidden:true},
                    {field:'kelamin',title:'Kelamin',width:1,hidden:true}
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
                                    {field:'dokter',title:'Pelaksana',width:150},
                                    {field:'id_dokter',title:'idd',width:1,hidden:true},
                                    //{field:'advice',title:'Advice',width:150},
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
                                }]
                        });
                        openWinLayanan();
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
                }]
        });
    });
    
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
                        $("#id_fasilitas_ruang").val("")
                        $("#tindakanFId").val("");
                        $("#jumlah").val(1);  
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

    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPendaftaranRuang.php?task=cariPendaftaranRR&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
    }
    
    //DHTML
    
    var dTindakan = new dhtmlXCombo("tindakan","tindakan",200);
    var dFasilitas = new dhtmlXCombo("tindakanF","tindakanF",200);
    var dokter = dhtmlXComboFromSelect("dokter");
    var dokterF = dhtmlXComboFromSelect("dokterF");
    dTindakan.enableFilteringMode(true,"json/data.php?task=dTindakan",true);
    dFasilitas.enableFilteringMode(true,"json/data.php?task=dFasilitas",true);
    dokter.enableFilteringMode(true);
    dokterF.enableFilteringMode(true);
    dTindakan.attachEvent("onChange", onChangeFunc);
    dFasilitas.attachEvent("onChange", onChangeFuncF);
    dTindakan.attachEvent("onKeyPressed", onKeyPressedFunc);
    dFasilitas.attachEvent("onKeyPressed", onKeyPressedFuncF);
    dokter.attachEvent("onKeyPressed", keyDokter);
    dokterF.attachEvent("onKeyPressed", keyDokterF);

</script>