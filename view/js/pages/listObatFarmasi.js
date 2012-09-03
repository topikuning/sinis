<script>
    $(function(){
        $('#dataObat').datagrid({
            title:'Detail Harga Obat',
            height:400,
            singleSelect:true,
            nowrap: false,
            striped: true,
            showFooter:true,
            url:'json/obat.php?task=getListHargaObat&nama_obat=' + $('#nama_obat').val(),
            sortName: 'nama_obat',
            sortOrder: 'asc',
            remoteSort: false,
            chace:false,
            idField:'nama_obat',
            frozenColumns:[[
                    {title:'ID',field:'id_obat',width:30,sortable:true},
                    {title:'Kode Obat',field:'kode_obat',width:50,sortable:true},
                    {field:'nama_obat',title:'Nama Obat',width:150,sortable:true}
                ]],
            columns:[[
                    {field:'hpp',title:'HPP',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'umum',title:'Umum',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'askes',title:'Askes',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    },
                    {field:'jps',title:'JPS',width:80,align:'right',
                        formatter:function(value){
                            if(value>0)
                                return formatCurrency(value);
                            else
                                return 'Rp. 0';
                        }
                    }
                ]],
            onDblClickRow:function(){
                var rows = $('#dataObat').datagrid('getSelections');
                $('#id_obat').val(rows[0].id_obat);
                $('#nama').val(rows[0].nama_obat);
                $('#hpp_asli').val(rows[0].hpp);
                $('#umum_asli').val(rows[0].umum);
                $('#askes_asli').val(rows[0].askes);
                $('#jps_asli').val(rows[0].jps);
                $('#hpp').val(rows[0].hpp);
                $('#umum').val(rows[0].umum);
                $('#askes').val(rows[0].askes);
                $('#jps').val(rows[0].jps);
                openWinHarga();
            },
            pagination:true,
            rownumbers:true
        });
    });
    
    function updateHarga(){
        var dataString = "task=updateHarga&id_obat=" + $("#id_obat").val() +
                         "&hpp=" + $("#hpp").val() +
                         "&umum=" + $("#umum").val() +
                         "&askes=" + $("#askes").val() +
                         "&jps=" + $("#jps").val();

        $.ajax({  
            type: "GET",  
            url: "json/obat.php",  
            data: dataString,  
            success: function(data) {
                if(data!='0'){
                    $.messager.show({
                        title:'Update Harga Obat',
                        msg:'Update Harga Obat berhasil.',
                        showType:'show'
                    });
                    $("#dataObat").datagrid("reload");
                    closeWinHarga();
                    return false;
                } else {
                    $.messager.show({
                        title:'Update Harga Obat',
                        msg:'Update Harga Obat gagal.',
                        showType:'show'
                    });
                    return false;
                }
            }  
        });  
        return false;
    };
    
    function loadDataObat(){
        $('#dataObat').datagrid({
            url:'json/obat.php?task=getListHargaObat&nama_obat=' + $('#nama_obat').val()
        });
    }

    function openWinFaktur(){
        $('#winFaktur').window('open');
        no_faktur.focus();
    }
    
    function closeWinFaktur(){
        frmFaktur.reset();
        $("#winFaktur").window('close');
    }
    
    function openWinHarga(){
        $('#winHarga').window('open');
        hpp.focus();
    }
    
    function closeWinHarga(){
        frmHarga.reset();
        $("#winHarga").window('close');
    }
    
</script>