<script>
    $(function(){
        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPerawatan.php?task=cariPerawatanUtilitas&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
            frozenColumns:[[
                    {title:'ID',field:'id_pendaftaran',width:80,sortable:true,hidden:true},
                    {title:'id_penggunaan_kamar',field:'id_penggunaan_kamar',width:80,sortable:true,hidden:true},
                    {title:'id_tipe_pasien',field:'id_tipe_pasien',width:80,sortable:true,hidden:true},
                    {field:'id_pasien',title:'No RM',width:50,sortable:true},
                    {field:'nama_pasien',title:'Nama Pasien',width:200,sortable:true}
                ]],
            columns:[[
                    {field:'tipe_pasien',title:'Tipe Pasien',width:150},
                    {field:'id_ruang',title:'ID Ruang',width:200,hidden:true},
                    {field:'ruang',title:'Ruang',width:200,hidden:true},
                    {field:'double_bed',title:'Double Bed',width:100,hidden:true},
                    {field:'ruang_asal',title:'Ruang Asal',width:100},
                    {field:'id_kamar',title:'ID Kamar',width:80,hidden:true},
                    {field:'kamar',title:'Kamar',width:80},
                    {field:'id_detail_kamar',title:'Bed',width:100,hidden:true},
                    {field:'bed',title:'Bed',width:100},
                    {field:'id_kelas',title:'ID Kelas',width:80,hidden:true},
                    {field:'kelas',title:'Kelas',width:60},
                    {field:'lama_perawatan',title:'Lama Perawatan',width:80,align:'center'},
                    {field:'tgl_pendaftaran_view',title:'Tanggal Pendaftaran',width:120},
                    {field:'tgl_pendaftaran',title:'Tanggal Pendaftaran',width:80,hidden:true},
                    {field:'jam_daftar',title:'Jam Daftar',width:60},
                    {field:'tarif',title:'Tarif',width:80, align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'status',title:'Status',width:100,
                        formatter:function(value){
                            if(value=='1')
                                return 'Open';
                            else if(value=='2')
                                return 'Pindah Ruang';
                            else if(value=='3')
                                return 'Keluar';
                        }
                    }
                ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(){
                popFasilitas();
            }
        });
    });
    
    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPerawatan.php?task=cariPerawatanUtilitas&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
    }
    
    function popFasilitas(){
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
        $('#dataFasilitas').datagrid({
            title:'Data Fasilitas',
            height:250,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/tindakan.php?task=getFasilitasRuangPux&no_pendaftaran=' + noDftr,
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
        openWinLayanan();
    }
    
    function saveFasilitas(){
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;

        var dataString = "task=simpanFasilitasPux&id_pendaftaran=" + fid +
            "&id_fasilitas_ruang=" +$("#id_fasilitas_ruang").val() +
            "&id_tindakan=" +$("#tindakanFId").val() +
            "&jumlah=" + $("#jumlah").val() + 
            "&id_dokter=" + dokterF.getSelectedValue() + 
            "&advice=" + $("#adviceF").val() + 
            "&id_tarif=" + $("#id_tarifF").val() +
            "&id_kamar=" + rows[0].id_penggunaan_kamar +
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
                    if(data=='1'){
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

        var dataString = "task=getTarifPux&id_detail_tindakan=" + id + 
                        "&no_pendaftaran=" + noDftr +
                        "&id_kamar=" + rows[0].id_penggunaan_kamar;

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
    var dFasilitas = new dhtmlXCombo("tindakanF","tindakanF",200);
    var dokterF = dhtmlXComboFromSelect("dokterF");
    dFasilitas.enableFilteringMode(true,"json/data.php?task=dFasilitas",true);
    dokterF.enableFilteringMode(true);
    dFasilitas.attachEvent("onChange", onChangeFuncF);
    dFasilitas.attachEvent("onKeyPressed", onKeyPressedFuncF);
    dokterF.attachEvent("onKeyPressed", keyDokterF);
</script>