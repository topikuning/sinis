<script>
    $.fn.autosugguest({  
    className:'ausu-suggest',
    methodType:'POST',
    minChars:2,
    rtnIDs:true,
    dataFile:'json/dataList.php'
    });
    $(function(){
        var noDftr = getURL('fid');
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        
        id_pasien.focus();
        openWinDiagnosa();

        $('#dataDiagnosa').datagrid({
            title:'Data Diagnosa',
            height:250,
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
                {title:'ID',field:'id_diagnosa',width:50},
            ]],
            columns:[[
                {field:'status',title:'Status',width:150,hidden:true},
                {field:'nama_dokter',title:'Nama Dokter',width:150},
                {field:'tgl_diagnosa',title:'Tanggal Diagnosa',width:80},
                {field:'jam_diagnosa',title:'Jam Diagnosa',width:60},
                {field:'diagnosa_primer',title:'Diagnosa Primer',width:300},
                {field:'icd_primer',title:'ICD',width:80},
                {field:'diagnosa_sekunder',title:'Diagnosa Sekunder',width:300},
                {field:'icd_sekunder',title:'ICD',width:80}
            ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(rowIndex){
                var rows = $('#dataDiagnosa').datagrid('getSelections');
                var id_diagnosa = rows[0].id_diagnosa;
                var status = rows[0].status;
                if(status=='1') {
                    $('#frmDiagnosa').form('load','json/diagnosa.php?task=getDiagnosa&id_diagnosa=' + id_diagnosa);
                    openWinDiagnosa();
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
            height:250,
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
            pagination:true,
            rownumbers:true
        });

        $('#dataListDiagnosa').datagrid({
            title:'Data List Diagnosa',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            remoteSort: false,
            idField:'id_penyakit',
            columns:[[
                {title:'ID',field:'id_penyakit',width:50},
                {field:'nama_penyakit',title:'Diagnosa',width:350},
                {field:'icd',title:'ICD',width:80}
            ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(){
                var rows = $('#dataListDiagnosa').datagrid('getSelections');
                var id_penyakit = rows[0].id_penyakit;
                var nama_penyakit = rows[0].nama_penyakit;
                if($('#idDiagnosa').val()=='primer'){
                    $('#penyakitPrimer').val(nama_penyakit);
                    $('#penyakitPrimerId').val(id_penyakit);
                    $('#penyakitPrimer').focus();
                    $('#penyakitSekunder').val('');
                    $('#penyakitSekunderId').val('');
                } else {
                    $('#penyakitSekunder').val(nama_penyakit);
                    $('#penyakitSekunderId').val(id_penyakit);
                    $('#penyakitSekunder').focus();
                }
                $('#winSearch').window('close');
            }
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
                    {field:'no_pendaftaran',title:'Nomor Pendaftaran',width:150,sortable:true,hidden:true},
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
                if(rows[0].no_pendaftaran>0){
                    window.location='index.php?page=dgns&fid=' + rows[0].no_pendaftaran;
                    closeWinSearchPasien();
                } else {
                    $.messager.show({
                        title:'Diagnosa',
                        msg:'Pasien tidak terdaftar.',
                        showType:'show'
                    });
                }
            },
            pagination:true,
            rownumbers:true
        });
    });
    
    function resetWinDiagnosa(){
        $('#dokter').val("");
        $('#id_penyakit_primer').val("");
        $('#id_penyakit_sekunder').val("");
    }
    
    function saveDiagnosa(){
        var dataString = "task=simpanDiagnosa&id_pendaftaran=" + getURL('fid') +
                       "&id_diagnosa=" +$("#id_diagnosa").val() +
                       "&id_pasien=" +$("#id_pasien").val() +
                       "&id_dokter=" + $("#dokter").val() + 
                       "&diagnosa_primer=" + $("#penyakitPrimerId").val() +
                       "&diagnosa_sekunder=" + $("#penyakitSekunderId").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#id_pasien").val(), 'No RM');
        bvalid = bvalid && checkSelect($("#dokter").val(), 'Dokter');
        bvalid = bvalid && checkSelect($("#penyakitPrimerId").val(), 'Diagnosa Primer');

        if(bvalid){                  
            $.ajax({  
                type: "POST",  
                url: "json/diagnosaPost.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='1'){
                        $.messager.show({
                            title:'Diagnosa',
                            msg:'Diagnosa berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataDiagnosa').datagrid('reload');
                        if($("#id_diagnosa").val()==""){
                            $("#penyakitPrimer").val("");
                            $("#penyakitPrimerId").val("");
                            $("#penyakitSekunder").val("");
                            $("#penyakitSekunderId").val("");
                        }
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
        var dataString = "task=simpanDetailDiagnosa&id_pendaftaran=" + getURL('fid') +
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
                    $('#dataDetailDiagnosa').datagrid('reload');
                    var noDftr = getURL('fid');
                    $('#frmDetailDiagnosa').form('load','json/diagnosa.php?task=cariDtlDiagnosa&no_pendaftaran=' + noDftr)
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

    function openWinSearch(id){
        $('#winSearch').window('open')
        srcDiagnosa.focus();
        idDiagnosa.value = id;
    }

    function openWinDiagnosa(){
        resetWinDiagnosa();
        $('#winDiagnosa').window('open');
        dokter.focus();
        var noDftr = getURL('fid');
        $('#frmDiagnosa').form('load','json/diagnosa.php?task=cariDokterJb&no_pendaftaran=' + noDftr)
        $('#frmDetailDiagnosa').form('load','json/diagnosa.php?task=cariDtlDiagnosa&no_pendaftaran=' + noDftr)
    }

    function loadDataDiagnosa(){
        $('#dataDiagnosa').datagrid({
            url:'json/diagnosa.php?task=getDetailDiagnosa&no_pendaftaran=' + noDftr
        });
        $('#dataDiagnosa').datagrid('reload');
    }

    function loadDiagnosaPasien(){
        $.ajax({  
            type: "GET",  
            url: "json/data.php",  
            data: "task=getPendaftaran&id_pasien=" + $("#id_pasien").val(),  
            success: function(data) {
                if(data>'0'){
                    window.location='index.php?page=dgns&fid=' + data;
                } else {
                    $.messager.show({
                        title:'Diagnosa',
                        msg:'Pasien tidak terdaftar di ruangan ini.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
    }

    function loadDataListDiagnosa(){
        $('#dataListDiagnosa').datagrid({
            url:'json/diagnosa.php?task=getListDiagnosa&srcDiagnosa=' + $('#srcDiagnosa').val() + '&srcICD=' + $('#srcICD').val()
        });
        $('#dataListDiagnosa').datagrid('reload');
    }

</script>