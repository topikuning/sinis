<script>
    $(function(){
        var noDftr = getURL('fid');
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        
        diet.focus();

        $('#dataDiet').datagrid({
            title:'Data Diet Pasien',
            height:350,
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
                    {title:'ID',field:'id_detail_diet',width:50, hidden:true}
                ]],
            columns:[[
                    {field:'id_diet',title:'ID Diet',width:250, hidden:true},
                    {field:'diet',title:'Diet',width:250},
                    {field:'id_jenis_diet',title:'ID Jenis Diet',width:250, hidden:true},
                    {field:'jenis_diet',title:'Jenis Diet',width:250},
                    {field:'waktu_diet',title:'Waktu Diet',width:80,
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
                    {field:'keterangan',title:'Keterangan',width:250}
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
                    window.location='index.php?page=diet&fid=' + rows[0].no_pendaftaran;
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
    
    function loadDiagnosaPasien(){
        $.ajax({  
            type: "GET",  
            url: "json/data.php",  
            data: "task=getPendaftaran&id_pasien=" + $("#id_pasien").val(),  
            success: function(data) {
                if(data>'0'){
                    window.location='index.php?page=diet&fid=' + data + '&pid=' + $("#id_pasien").val();
                } else {
                    $.messager.show({
                        title:'Tindakan',
                        msg:'Pasien tidak terdaftar di ruangan ini.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
    }
	
    function simpanDetailDiet(){
        var waktuDiet;
        if(dietPagi.checked) waktuDiet = "1";
        else if (dietSiang.checked) waktuDiet = "2";
        else if (dietSore.checked) waktuDiet = "3";
        else waktuDiet = "";
        
        var dataString = "task=simpanDetailDiet&id_pendaftaran=" + getURL('fid') +
            "&id_detail_diet=" + $("#id_detail_diet").val() + 
            "&id_pasien=" + $("#id_pasien").val() + 
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
                        id_pasien.focus();
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

    function goToDiagnosa(){
        var noDftr = getURL('fid');
        window.location = "?page=dgns&fid=" + noDftr;
    }
    
    function goToVisitDokter(){
        var noDftr = getURL('fid');
        
        window.location = "?page=vstdktr&fid=" + noDftr;
    }
    
</script>