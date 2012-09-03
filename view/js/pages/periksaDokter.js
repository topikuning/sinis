<script>
    $(function(){
        var noDftr = getURL('fid');
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        
        id_pasien.focus();

        $( "#dokter" ).change(function(){
            $("#tarifPemeriksaane").attr("value", 30000);
        });

        $('#dataPemeriksaan').datagrid({
            title:'Data Pemeriksaan Dokter',
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
                {field:'tgl_visit',title:'Tanggal Pemeriksaan',width:100},
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
                var rows = $('#dataPemeriksaan').datagrid('getSelections');
                var id_visit = rows[0].id_visit;
                
                $('#frmPemeriksaanDOkter').form('load','json/perawatan.php?task=getVisit&id_visit=' + id_visit);
                openWinPemeriksaan();
            },
            toolbar:[{
                id:'btndel',
                text:'Hapus',
                iconCls:'icon-remove',
                handler:function(){
                    var rows = $('#dataPemeriksaan').datagrid('getSelections');
                    var id_visit = "task=hapusVisit&id_visit=" + rows[0].id_visit;

                    $.ajax({  
                        type: "GET",  
                        url: "json/perawatan.php",  
                        data: id_visit,  
                        success: function(data) {  
                            if(data=='1'){
                                $.messager.show({
                                    title:'Pemeriksaan Dokter',
                                    msg:'Penghapusan Pemeriksaan Dokter berhasil.',
                                    showType:'show'
                                });
                                var row = $('#dataPemeriksaan').datagrid('getSelected');
                                var index = $('#dataPemeriksaan').datagrid('getRowIndex', row);
                                $('#dataPemeriksaan').datagrid('deleteRow', index);
                                $('#dataPemeriksaan').datagrid('reload');
                            } else if (data=='0') {
                                $.messager.show({
                                    title:'Pemeriksaan Dokter',
                                    msg:'Penghapusan Pemeriksaan Dokter gagal.',
                                    showType:'show'
                                });
                            }
                        }
                    })
                }
            }]
        });

    });
    
    function simpanPemeriksaanDokter(){      
        var dataString = "task=simpanVisitDokter&id_pendaftaran=" + getURL('fid') +
                       "&id_visit=" + $("#visit").val() + 
                       "&id_pasien=" + $("#id_pasien").val() + 
                       "&id_dokter=" + $("#dokter").val() + 
                       "&tgl_visit=" + $("#tglPemeriksaane").datebox("getValue") + 
                       "&tarif=" + $("#tarifPemeriksaane").val();

        var bvalid = true;
        
        bvalid = bvalid && checkSelect($("#dokter").val(), 'Dokter');
        bvalid = bvalid && checkSelect($("#tglPemeriksaane").datebox("getValue"), 'Tanggal Pemeriksaan');
        
        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/perawatan.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='1'){
                        $.messager.show({
                            title:'Pemeriksaan Dokter',
                            msg:'Pemeriksaan Dokter berhasil disimpan.',
                            showType:'show'
                        });
                        $('#dataPemeriksaan').datagrid('reload');
                        return false;
                    } else if(data=='0'){
                        $.messager.show({
                            title:'Pemeriksaan Dokter',
                            msg:'Gagal menyimpan Pemeriksaan Dokter.',
                            showType:'show'
                        });
                        return false;
                    }
                }  
            });  
            return false;
        }
    }

    function simpanClosePerawatan(){
        var dataString = "task=simpanClosePerawatan&id_pendaftaran=" + getURL('fid') +
                       "&kondisi_keluar=" + $("#kondisiKeluar").val() + 
                       "&keterangan_keluar=" + $("#keteranganKeluar").val();

        $.ajax({  
            type: "POST",  
            url: "json/diagnosaPost.php",  
            data: dataString,  
            success: function(data) {
                if(data=='1'){
                    $.messager.alert('Perawatan',
                        'Close Perawatan Berhasil. Generate Jasa Berhasil.',
                        'alert'
                    );
                    $('#winClosePerawatan').window('close');
                    window.location = "?page=lstdftrpr";
                } else {
                    $.messager.show({
                        title:'Perawatan',
                        msg:'Close Perawatan gagal. ' + data,
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    }

    function openClosePerawatan(){
        $('#winClosePerawatan').window('open');
        kondisiKeluar.focus();
    }

    function openWinPemeriksaan(){
        var noDftr = getURL('fid');

        if(noDftr!=""){
            $('#winPemeriksaan').window('open');
            dokter.focus();
        }
    }
    
    function closeWinPemeriksaan(){
        frmPemeriksaanDOkter.reset();
        $('#winPemeriksaan').window('close');
        id_pasien.focus();
    }

    function goToDiagnosa(){
        var noDftr = getURL('fid');
        
        window.location = "?page=dgns&fid=" + noDftr;
    }
    
    function goToDiet(){
        var noDftr = getURL('fid');
        
        window.location = "?page=diet&fid=" + noDftr;
    }
    
    function goToTindakan(id){
        var noDftr = getURL('fid');
        if(id=='22')
            window.location = "?page=tndknibs&fid=" + noDftr + "&pid=" + $('#id_pasien').val();
        else
            window.location = "?page=tndkn&fid=" + noDftr + "&pid=" + $('#id_pasien').val();
    }
    
    function goToRM(){
        var noDftr = getURL('fid');
        
        window.location = "?page=rmpx&fid=" + noDftr + "&pid=" + $('#id_pasien').val();
    }
    
    $.fn.autosugguest({  
        className:'ausu-suggest',
        methodType:'POST',
        minChars:2,
        rtnIDs:true,
        dataFile:'json/dataList.php'
    });

</script>