<script>
    $(function(){
        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            remoteSort: false,
            url:'json/listPendaftaranRuang.php?task=cariPendaftaranRuangvk&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
            showFooter:true,
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
                    {field:'asal_ruang',title:'Ruang Asal',width:100},
                    {field:'tipe_pendaftaran',title:'Tipe Pendaftaran',width:100},
                    {field:'id_ruang',title:'ID Ruang',width:120,hidden:true},
                    {field:'ruang',title:'Ruang',width:150},
                    {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:100},
                    {field:'jam_daftar',title:'Jam Daftar',width:80},
                    {field:'asal_rujukan',title:'Asal Rujukan',width:120},
                    {field:'perujuk',title:'Perujuk',width:120},
                    {field:'jadwal',title:'Jadwal Layanan',width:180},
                    {field:'usia',title:'Usia',width:1,hidden:true},
                    {field:'dokter',title:'Dokter',width:1,hidden:true},
                    {field:'kelamin',title:'Kelamin',width:1,hidden:true}
                ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(){
                popTindakan();
            },
            toolbar:[{
                    id:'btnTindakan',
                    text:'Tindakan',
                    iconCls:'icon-openrm',
                    handler:function(){
                        popTindakan();
                    }
                },{
                    id:'btnClose',
                    text:'Close Perawatan',
                    iconCls:'icon-logout',
                    handler:function(){
                        openClosePerawatan();
                    }
                }]
        });
    });

    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPendaftaranRuang.php?task=cariPendaftaranRuangvk&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
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
    
    function popTindakan(){
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
                                } else if (data=='2'){
                                    $.messager.show({
                                        title:'Tindakan',
                                        msg:'Gagal menghapus Tindakan. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                                        showType:'show'
                                    });
                                    return false;
                                } else if (data=='2'){
                                    $.messager.show({
                                        title:'Fasilitas',
                                        msg:'Penghapusan. Pasien sudah melunasi Tagihan, Silahkan Close Perawatan.',
                                        showType:'show'
                                    });
                                    return false;
                                } else if (data=='0') {
                                    $.messager.show({
                                        title:'Tindakan',
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
    
    //DHTML
    
    var dTindakan = new dhtmlXCombo("tindakan","tindakan",200);
    var dokter = dhtmlXComboFromSelect("dokter");
    dTindakan.enableFilteringMode(true,"json/data.php?task=dTindakan",true);
    dokter.enableFilteringMode(true);
    dTindakan.attachEvent("onChange", onChangeFunc);
    dokter.attachEvent("onKeyPressed", keyDokter);
    dTindakan.attachEvent("onKeyPressed", onKeyPressedFunc);
    

</script>