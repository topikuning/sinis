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
        
        dokter.focus();

        $( "#dokter" ).change(function(){
            $.getJSON("json/data.php", {task: 'getBiayaVisit', id_dokter: $(this).val(), id_pendaftaran: noDftr},
            function(data) {
                if(data.length>0){
                    var value = data[0].dataValue;

                    $("#tarifVisite").attr("value", value);
                }
            });
        });

        $('#dataVisit').datagrid({
            title:'Data Visit Dokter',
            height:350,
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
                {title:'ID',field:'id_visit',width:50, hidden:true}
            ]],
            columns:[[
                {field:'dokter',title:'Dokter',width:250},
                {field:'jenis_dokter',title:'Jenis Dokter',width:250},
                {field:'tgl_visit',title:'Tanggal Visit',width:100},
                {field:'tarif',title:'Biaya',width:100, align:'right',
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
                $('#frmVisitDOkter').form('load','json/perawatan.php?task=getVisit&id_visit=' + id_visit);
				dokter.focus();
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
                    window.location='index.php?page=vstdktr&fid=' + rows[0].no_pendaftaran;
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
                    window.location='index.php?page=vstdktr&fid=' + data + '&pid=' + $("#id_pasien").val();
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
	
    function simpanVisitDokter(){      
        var dataString = "task=simpanVisitDokter&id_pendaftaran=" + getURL('fid') +
                       "&id_visit=" + $("#visit").val() + 
                       "&id_pasien=" + $("#id_pasien").val() + 
                       "&id_dokter=" + $("#dokter").val() + 
                       "&tgl_visit=" + $("#tglVisite").datebox("getValue") + 
                       "&tarif=" + $("#tarifVisite").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#dokter").val(), 'Dokter');
        bvalid = bvalid && checkSelect($("#tglVisite").datebox("getValue"), 'Tanggal Visit');
        
        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='1'){
                        $.messager.show({
                            title:'Visit Dokter',
                            msg:'Visit Dokter berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataVisit').datagrid('reload');
						$('#visit').val("");
						$('#dokter').val("");
						$('#tarifVisite').val("");
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
</script>