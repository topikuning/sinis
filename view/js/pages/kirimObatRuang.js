<script>
    $(function(){

        $('#dataObat').datagrid({
            title:'Data Stock Obat',
            height:300,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible: true,
            url:'json/obat.php?task=cariObatRuang&kode_obat=&obat=&startDate=&endDate=',
            sortName: 'kode_obat',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'kode_obat',
            frozenColumns:[[
                    {title:'ID',field:'kode_obat',width:80,sortable:true},
                    {field:'nama_obat',title:'Nama Obat',width:250,sortable:true}
                ]],
            columns:[[
                    {field:'penyimpanan',title:'Penyimpanan',width:120,sortable:true},
                    {field:'jumlah_stock',title:'Jumlah Stock',width:50,sortable:true},
                    {field:'stock_limit',title:'Stock Limit',width:50,sortable:true},
                    {field:'tgl_kadaluarsa',title:'Tanggal Kadaluarsa',width:100,sortable:true},
                    {field:'hpp',title:'Harga Pokok',width:80,align:'right',
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
            pagination:true,
            rownumbers:true
        });

        $('#dataDistribusiObat').datagrid({
            title:'Daftar Kiriman Obat',
            height:200,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible: true,
            url:'json/obat.php?task=cariDistribusiObatApotik',
            sortName: 'id_obat',
            sortOrder: 'desc',
            remoteSort: false,
            chace:false,
            idField:'id_obat',
            frozenColumns:[[
                    {title:'ID',field:'id_distribusi_obat',width:80,sortable:true,hidden:true},
                    {title:'ID',field:'id_obat',width:80,sortable:true,hidden:true},
                    {title:'Kode Obat',field:'kode_obat',width:80,sortable:true},
                    {field:'nama_obat',title:'Nama Obat',width:250,sortable:true}
                ]],
            columns:[[
                    {field:'jumlah_stock',title:'Jumlah',width:50,sortable:true},
                    {field:'tgl_kadaluarsa',title:'Tanggal Kadaluarsa',width:100,sortable:true}
                ]],
            onDblClickRow:function(){
                var rows = $('#dataDistribusiObat').datagrid('getSelections');
                var id_distribusi_obat = rows[0].id_distribusi_obat;
                $("#frmSimpanDistObat").form("load",'json/obat.php?task=getDetailDistObat&id_distribusi_obat=' + id_distribusi_obat);
                openWinDistObat();
            },
            pagination:true,
            rownumbers:true
        });

    });
    
    function simpanDistObat(){
        var dataString = "task=simpanDistObatRuang&id_obat=" + $("#id_obat").val() +
            "&id_distribusi_obat=" + $("#id_distribusi_obat").val() + 
            "&id_penyimpanan=" + $("#penyimpanan").val() + 
            "&jumlah=" + $("#jumlah").val() + 
            "&tgl_kadaluarsa_baru=" + $("#tgl_kadaluarsa").datebox("getValue");

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#penyimpanan").val(), 'Penyimpanan');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/obat.php",  
                data: dataString,  
                success: function(data) {
                    if(data=='1'){
                        $.messager.show({
                            title:'Distribusi Obat',
                            msg:'Distribusi Obat berhasil disimpan.',
                            showType:'show'
                        });
                        $("#dataObat").datagrid("reload");
                        $("#dataDistribusiObat").datagrid("reload");
                        closeWinDistObat();
                        return false;
                    } else if(data=='2'){
                        $.messager.show({
                            title:'Distribusi Obat',
                            msg:'Distribusi Obat berhasil disimpan. Data Distribusi Gagal di update.',
                            showType:'show'
                        });
                        frmSimpanDistObat.reset();
                        return false;
                    } else if (data=='0'){
                        $.messager.show({
                            title:'Distribusi Obat',
                            msg:'Distribusi Obat gagal disimpan.',
                            showType:'show'
                        });
                        frmSimpanDistObat.reset();
                        closeWinDistObat();
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function openWinDistObat(){
        $('#winSimpanDistObat').window('open');
        penyimpanan.focus();
    }
    
    function closeWinDistObat(){
        frmSimpanDistObat.reset();
        $("#winSimpanDistObat").window('close');
    }
    
</script>