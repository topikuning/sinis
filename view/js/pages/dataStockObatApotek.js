<script>
    $(function(){

        $('#dataObat').datagrid({
            title:'Data Stock Obat',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            collapsible: true,
            url:'json/obat.php?task=cariObatApotik&kode_obat=&obat=&startDate=&endDate=',
            sortName: 'kode_obat',
            sortOrder: 'asc',
            remoteSort: false,
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
            onDblClickRow:function(){
                var rows = $('#dataObat').datagrid('getSelections');
                var id_obat = rows[0].id_obat;
                var id_penyimpanan = rows[0].id_penyimpanan;
                var tgl_kadaluarsa = rows[0].tgl_kadaluarsa;
                $('#id_obat').val(id_obat);
                $('#id_penyimpanan').val(id_penyimpanan);
                $('#tgl_kadaluarsa_baru').val(tgl_kadaluarsa);
                $.getJSON("json/data.php", {task: 'listRuangDistribusi', kode_obat: rows[0].kode_obat},
                function(data) {
                    var opt = '';
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {
                            opt += '<option value="' + data[i].optionValue + '">' + data[i].optionDisplay + '</option>';
                        };
                        $("#ruangTujuan").html(opt);
                    }
                });
                openWinDistObat();
            },
            pagination:true,
            rownumbers:true
        });

    });
    
    function simpanDistObat(){
        var dataString = "task=simpanDistObat&id_obat=" + $("#id_obat").val() +
            "&id_penyimpanan=" + $("#id_penyimpanan").val() + 
            "&ruangTujuan=" + $("#ruangTujuan").val() + 
            "&jmlObat=" + $("#jmlObat").val() + 
            "&tgl_kadaluarsa_baru=" + $("#tgl_kadaluarsa_baru").val();

        var bvalid = true;

        bvalid = bvalid && checkSelect($("#ruangTujuan").val(), 'Ruang Tujuan');
        bvalid = bvalid && checkSelect($("#jmlObat").val(), 'Jumlah');

        if(bvalid){                  
            $.ajax({  
                type: "GET",  
                url: "json/obat.php",  
                data: dataString,  
                success: function(data) {
                    var returnData = data.split(":");
                    var status = returnData[0];
                    if(status=='LOGIN'){
                        alert ('WAKTU LOGIN ANDA HABIS, SILAHKAN LOGIN ULANG!')
                        window.location.reload();
                    } else if(status=='TRUE'){
                        $.messager.show({
                            title:'Distribusi Obat',
                            msg:'Distribusi Obat berhasil disimpan.',
                            showType:'show'
                        });
                        $("#dataObat").datagrid("reload");
                        closeWinDistObat();
                        return false;
                    } else if(status=='WARNING'){
                        $.messager.show({
                            title:'Distribusi Obat',
                            msg:'Distribusi Obat berhasil disimpan. <b>' + returnData[1] + '</b>',
                            showType:'show',
                            timeout:0
                        });
                        $("#dataObat").datagrid("reload");
                        closeWinDistObat();
                        frmDistObat.reset();
                        return false;
                    } else if (status=='ERROR'){
                        $.messager.show({
                            title:'Distribusi Obat',
                            msg:'Distribusi Obat gagal disimpan. <b>' + returnData[1] + '</b>',
                            showType:'show',
                            timeout:0
                        });
                        frmDistObat.reset();
                        closeWinDistObat();
                        return false;
                    }
                }  
            });  
            return false;
        }
    };

    function loadDataObat(){
        $('#dataObat').datagrid({
            url:'json/obat.php?task=cariObatApotik&kode_obat=' + $("#kode_obat").val() + 
                '&obat=' + $("#obat").val() +
                '&startDate=' + $("#startDate").datebox("getValue") + 
                '&endDate=' + $("#endDate").datebox("getValue")
        });
    }

    function openWinDistObat(){
        $('#winDistObat').window('open');
        ruangTujuan.focus();
    }
    
    function closeWinDistObat(){
        frmDistObat.reset();
        $("#winDistObat").window('close');
    }
    
</script>