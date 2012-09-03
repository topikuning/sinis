<script>
    $(function(){
        var noDftr = getURL('fid');
        var idPasien = getURL('pid');
        
        $('#frmDtlPasien').form('load','json/diagnosa.php?task=cariDtlPasien&no_pendaftaran=' + noDftr)
        
        id_pasien.focus();
            
        $('#dataRekamMedis').datagrid({
            title:'Rekam Medis Pasien',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            idField:'id_pendaftaran',
            remoteSort: false,
            chace:false,
            expanded:true,
            frozenColumns:[[
                {title:'ID',field:'id_pendaftaran',width:50,
                    formatter:function(value){
                        return '<span style="color:red">'+value+'</span>';
                    }
                },
            ]],
            columns:[[
                {field:'id_tipe_pendaftaran',title:'Tipe Kunjungan',width:150,hidden:true},
                {field:'tipe_pendaftaran',title:'Tipe Kunjungan',width:150},
                {field:'tgl_pendaftaran',title:'Tanggal Kunjungan',width:150},
                {field:'ruang',title:'Ruang',width:450}
            ]],
            view: detailview,
            detailFormatter:function(index,row){
                    return '<div id="ddv-' + index + '" style="padding:5px 0"></div>';
            },
            onExpandRow: function(index,row){
                $('#ddv-'+index).panel({
                    border:false,
                    cache:false,
                    href:'pages/detRekamMedis.php?id_pendaftaran=' + row.id_pendaftaran + '&id_tipe_pendaftaran=' + row.id_tipe_pendaftaran,
                    onLoad:function(){
                        $('#dataRekamMedis').datagrid('fixDetailRowHeight',index);
                    }
                });
                $('#dataRekamMedis').datagrid('fixDetailRowHeight',index);
            },
            pagination:true,
            rownumbers:true
        });

        $('#dataListRuang').datagrid({
            title:'Data List Ruang',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            remoteSort: false,
            idField:'id_ruang',
            columns:[[
                {title:'ID',field:'id_ruang',width:50},
                {field:'ruang',title:'Ruang',width:200}
            ]],
            pagination:true,
            rownumbers:true,
            onDblClickRow:function(rowIndex){
                var rows = $('#dataListRuang').datagrid('getSelections');
                var ruang = rows[0].ruang;
                var id_ruang = rows[0].id_ruang;
                $('#ruang').val(ruang);
                $('#id_ruang').val(id_ruang);
                $('#ruang').focus();
                $('#winSearchRuang').window('close');
            }
        });
    });
    
    function loadDataListRuang(){
        $('#dataListRuang').datagrid({
            url:'json/data.php?task=getListRuang&tipe_ruang=' + $('#tipe_ruang').val()
        });
    }
    
    function loadDataRekamMedis(){
        if($("#id_pasien").val()!=""){
            $('#dataRekamMedis').datagrid({
                url:'json/rekamMedis.php?task=getRekamMedisPasien&id_pasien=' + $("#id_pasien").val() +
                    '&nama_pasien=' + $("#pasien").val() +
                    '&id_ruang=' + $("#id_ruang").val() +
                    '&startDate=' + $("#startDate").datebox("getValue") +
                    '&endDate=' + $("#endDate").datebox("getValue")
            });
            $('#frmDtlPasien').form('load','json/rekamMedis.php?task=cariDtlPasien&id_pasien=' + $("#id_pasien").val())
        } else {
            $.messager.show({
                title:'Rekam Medis',
                msg:'Nomor RM Harus Diisi.',
                showType:'show'
            });
            id_pasien.focus();
        }
    }
    
    function openWinSrcRuang(){
        $('#winSearchRuang').window('open')
        tipe_ruang.focus();
    }
</script>