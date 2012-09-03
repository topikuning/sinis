<script>
    $(function(){
        $('#dataPendaftaran').datagrid({
            title:'Data List Pendaftaran',
            height:350,
            singleSelect:true,
            nowrap: false,
            striped: true,
            url:'json/listPerawatan.php?task=cariPerawatanDiet&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue"),
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
                var rows = $('#dataPendaftaran').datagrid('getSelections');
                var noDftr = rows[0].id_pendaftaran;
                $('#dataDiet').datagrid({
                    title:'Data Diet Pasien',
                    height:240,
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
                                            $("#id_detail_diet").val("");
                                            $("#checkWaktu").val("");
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
                openWinDiet();
            }
        });
    });
    
    function loadDataPendaftaran(){
        $('#dataPendaftaran').datagrid({
            url:'json/listPerawatan.php?task=cariPerawatanDiet&id_pasien=' + $('#no_rm_pasien').val() + '&pasien=' + $('#pasien').val() + '&startDate=' + $('#startDate').datebox("getValue") + '&endDate=' + $('#endDate').datebox("getValue")
        });
    }
    
    function openWinDiet(){
        $('#winDiet').window('open');
        diet.focus();
    }
    
    function closeWinDiet(){
        $('#winDiet').window('close');
    }
    
    function simpanDetailDiet(){
        var rows = $('#dataPendaftaran').datagrid('getSelections');
        var fid = rows[0].id_pendaftaran;
        var rm = rows[0].id_pasien;
        var ruang = rows[0].id_ruang;
        var waktuDiet;
        if(dietPagi.checked) waktuDiet = "1";
        else if (dietSiang.checked) waktuDiet = "2";
        else if (dietSore.checked) waktuDiet = "3";
        else waktuDiet = "";
        
        var dataString = "task=simpanDetailDiet&id_pendaftaran=" + fid +
            "&id_detail_diet=" + $("#id_detail_diet").val() + 
            "&id_pasien=" + rm + 
            "&id_diet=" + $("#diet").val() + 
            "&id_jenis_diet=" + $("#jns_diet").val() + 
            "&waktu_diet=" + waktuDiet +
            "&tgl_diet=" + $("#tanggalDiet").datebox("getValue") + 
            "&keterangan=" + $("#keterangan").val() +
            "&ruangan=" + ruang;

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
</script>