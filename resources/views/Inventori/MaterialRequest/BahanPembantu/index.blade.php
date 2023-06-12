<?php $title = 'Bahan Pembantu'; ?>
@extends('layouts.backend-Theme-3.app')
@section('title', '$title')

@section('Dashboard')
    <h2 class="m-0">{{ $title }}</h2>
    <ol class="breadcrumb sm-2">
        <li class="breadcrumb-item"><a href="/">Home </a></li>
        <li class="breadcrumb-item">Inventori </li>
        <li class="breadcrumb-item">MaterialRequest </li>
        <li class="breadcrumb-item active">BahanPembantu </li>
    </ol>
@endsection

@section('css')

    <style>

    </style>
    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.css') !!}">

@endsection

@section('container')
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="min-height:calc(100vh - 242px);">

                @include('Inventori.MaterialRequest.BahanPembantu.data')

            </div>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end animate" id="menuklik" style="display:none">
        <div class="text-center fw-bold mb-2" id="judulklik"></div>
        <a class="dropdown-item" id="removeRow"><span class=""><span class="tf-icons bx bx-trash"></span>&nbsp;
                Hapus</a>
    </div>
@endsection

@section('script')
    {{-- Bootstrap Select --}}
    <script src="{!! asset('assets/sneatV1/assets/vendor/libs/BootstrapSelect/bootstrap-select.min.js') !!}"></script>

    <script>
        $(document).ready(function() {
            let transfercek = $('#transfercek').val();
            // console.log(transfercek);
            if (transfercek > 0) {
                disableBoostrapSelect();
                KlikBaru();

                for (let index = 1; index <= transfercek; index++) {
                    setTimeout(function() {
                        newRow();
                    }, index * 20);
                }

                // $('#GudangT').prop('disabled', true);
            }

            $('input').keydown(function(event) {
                if (event.which == 39) { // tombol panah kanan
                    $(this).next('input').focus();
                } else if (event.which == 37) { // tombol panah kiri
                    $(this).prev('input').focus();
                } else if (event.which == 13) { // tombol enter
                    $(this).next('input').focus();
                }
            });

            $('select').keydown(function(event) {
                if (event.which == 39) { // tombol panah kanan
                    $(this).next('input').focus();
                } else if (event.which == 37) { // tombol panah kiri
                    $(this).prev('input').focus();
                } else if (event.which == 13) { // tombol enter
                    $(this).next('input').focus();
                }
            });

            // Alihkan fokus ke kotak input pencarian ketika kotak opsi dibuka
            $('select[name="barangStock"]').on('shown.bs.select', function(e, clickedIndex, isSelected,
                previousValue) {
                $(this).parent().find('.bs-searchbox input').focus();
            });


            // tambahkan event listener ke elemen input dan select yang ada di tabel
            $('table').on('keydown', 'input, select', function(event) {

                if (event.which == 39 || event.which == 9 || event.which == 13) { // tombol panah kanan
                    //event.preventDefault(); // mencegah tindakan default
                    var nextTd = $(this).closest('td').next('td');
                    while (nextTd.length > 0 && nextTd.find('select[disabled], option[disabled]').length >
                        0) { // mencari td berikutnya yang dapat diakses
                        nextTd = nextTd.next('td');
                    }
                    nextTd.find('input, select').focus().select();
                    $.fn.tab();

                } else if (event.which == 37) { // tombol panah kiri
                    event.preventDefault();
                    var prevTd = $(this).closest('td').prev('td');
                    while (prevTd.length > 0 && prevTd.find('select[disabled], option[disabled]').length >
                        0) { // mencari td sebelumnya yang dapat diakses
                        prevTd = prevTd.prev('td');
                    }
                    prevTd.find('input, select').focus().select();
                    $.fn.tab(-1);
                }
            });
        });

        //tombol arah di tabel
        $(document).on('keydown', '#tabel1 input, #tabel1 select', function(e) {

            if (e.which === 9 || e.which === 13 || e.which === 39 || e.which === 37 || e.which === 38 || e.which === 40) {

                var row = $(this).closest('tr').index();
                var col = $(this).closest('td').index();
                var lastRow = $('#tabel1 tbody tr:last-child').index();
                console.log('Input is at row ' + row + ' and column ' + col);

                //tab, enter, kanan
                if (e.which === 9 || e.which === 13 || e.which === 39) {
                    e.preventDefault();
                    if (row == lastRow && col == 9) {
                        newRow();
                        $('#tabel1 tbody tr').eq(row + 1).find('td').eq('2').find('input').focus();
                        return;
                    }

                    $('#tabel1 tbody tr').eq(row).find('td').eq(col + 1).find('input').focus();
                }

                //kiri
                else if (e.which === 37) {
                    e.preventDefault();
                    // setting mentok
                    if (col == 4) {
                        row--;
                        col = 9;
                    }
                    $('#tabel1 tbody tr').eq(row).find('td').eq(col - 1).find('input').focus();
                }

                //atas
                else if (e.which === 38) {
                    e.preventDefault();
                    $('#tabel1 tbody tr').eq(row - 1).find('td').eq(col).find('input').focus();
                }

                //bawah
                else if (e.which === 40) {
                    e.preventDefault();
                    if (row == lastRow && col != 1) {
                        newRow();
                        return;
                    }
                    $('#tabel1 tbody tr').eq(row + 1).find('td').eq(col).find('input').focus();
                }
            }
        });

        // Data Table Settings
        $('#tabel1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": true,
            "responsive": true,
            "fixedColumns": true,
        });

        // Function for klik baru
        function KlikBaru() {

            $('#btn-menu .btn').prop('disabled', true);
            $('#btn_simpan').prop('disabled', false);
            $('#btn_batal').prop('disabled', false);
            $('#tanggal').prop('disabled', false)
            // $('#GudangT').prop('disabled', false)

            $('#action').val('simpan')
            // Getting idMaterialRequest from hidden input
            let idMaterialRequest = $('#idMaterialRequest').val()
            if (idMaterialRequest != "") {
                // If idMaterialRequest have value. It will reload th page
                window.location.reload()
                return;
            }
            // Empty row in tbody
            $("#tabel1  tbody").empty()

            // Get Row
            $.ajax({
                url: '/Inventori/MaterialRequest/BahanPembantu/generateRow',
                type: 'GET',
                data: {
                    index: 1
                },
                dataType: "json",
                beforeSend: function() {
                    // $(".preloader").show();
                },
                complete: function() {
                    // $(".preloader").fadeOut();

                    let isi = $('#t_1').val();
                    $('#barangStock_1').val(isi).change()

                },
                success: function(data) {
                    $("#tabel1 > tbody").append(data.data);
                    
                    $('#kodeNonStock_1').append($('#data-select-master-nonstok option').clone());
                    $('#kodeNonStock_1').selectpicker();
                    //console.log(data.data)
                    $('#barangStock_1').append($('#data-select-master option').clone());
                    $('#barangStock_1').selectpicker();
                    
                }
            });

        }

        function somethingFun() {
            let barangStocks = $('select .inputBarangStock')
            let units = $('.unit')
            for (let index = 0; index < barangStocks.length; index++) {
                var barang = $(barangStocks[index]).val()
                var unit = $(unit[index]).val()
                console.log('barang = ' + barang);
                console.log('unit = ' + unit);
            }
        }

        function PilihGudang() {

            var cekgudang = $('#GudangT').val();
            $.ajax({
                url: '/Inventori/MaterialRequest/BahanPembantu/LockGudang?id=' + cekgudang,
                success: function(data) {
                    $("#data-select-master").html(data);
                    KlikBaru();
                    $('#GudangT').prop('disabled', true);
                },
            });
        }

        function enableBootstrapSelect() {
            
            $('.barangStock').removeAttr("disabled")
            $('.barangStock').selectpicker('destroy')
            $('.barangStock').selectpicker()

            $('.kodeNonStock').removeAttr("disabled")
            $('.kodeNonStock').selectpicker('destroy')
            $('.kodeNonStock').selectpicker()
        }

        function disableBoostrapSelect() {
            $('.kodeNonStock').attr("disabled", true)  
            $('.kodeNonStock').selectpicker('destroy')
            $('.kodeNonStock').selectpicker()
            
            $('.barangStock').attr("disabled", true)  
            $('.barangStock').selectpicker('destroy')
            $('.barangStock').selectpicker()
        }

        function getDetailBarangStock(value, index) {
            // var cekgudang = $('#GudangT').val();
            var barangStock = $('#barangStock_' + index).val()
            $('#barangStock_' + index).selectpicker('toggle');
            //$('#kodeNonStock_' + index).selectpicker('toggle');

            if (barangStock == null) {
                return;
            }
            // else if (cekgudang == null) {
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Oops...',
            //         text: "Lokasi Gudang harus di isi",
            //     })

            //     disableBoostrapSelect()
            //     $('#barangStock_1').val('')
            //     enableBootstrapSelect()
            //     return;
            // }

            // Get data from that value then set Unit and etc.
            $.ajax({
                url: '/Inventori/MaterialRequest/BahanPembantu/getBarang',
                type: 'GET',
                data: {
                    idBarang: barangStock
                },
                dataType: "json",
                beforeSend: function() {
                    $(".preloader").show();
                },
                complete: function() {
                    $(".preloader").fadeOut();
                },
                success: function(data) {
                    if (data.data.length != 0) {
                        let dataBarang = data.data[0]
                        // $('#barangStock_' + index).val(dataBarang.Description).change()
                        // setTimeout(function() {
                        $('#unit_' + index).val(dataBarang.UID).change()
                        $('#uom_' + index).val(dataBarang.Unit).change()
                        $('#sw_' + index).val(dataBarang.SW).change()
                        $('#jumlah_' + index).val('1')
                        $('#keperluan_' + index).val("Rutin").change()
                        $('#kategori_' + index).val("Biasa").change()
                        $('#ulang_' + index).val('N').change()
                        $('#unit_' + index).prop('disabled', true);
                        // }, 50);
                        // return
                      
                        $('#kodeNonStock_' + index).selectpicker('val', '0')
                        $('#barangNonStock_'+index).val('')
                    }
                    return
                }
            });
        }

        function getDetailBarangNonStock(value, index) {
            $('#barangStock_' + index).selectpicker('val', '0')
            $('#barangNonStock_'+index).val('')
            $('#unit_' + index).prop('disabled', false)
            $('#jumlah_' + index).val('1')
            $('#keperluan_' + index).val("Rutin").change()
            $('#kategori_' + index).val("Biasa").change()
            $('#ulang_' + index).val('N').change()
        }



        // Function for createNewRow
        function newRow() {

            // Setup table row
            let transfercek = $('#transfercek').val();
            if (transfercek == '0') {
                var number = $('#tabel1 tr').length;
            } else {
                var ulangtambah = parseInt($('#ulangtambah').val()) + 1;
                var number = ulangtambah;
                $('#ulangtambah').val(ulangtambah);
            }

            $.ajax({
                url: '/Inventori/MaterialRequest/BahanPembantu/generateRow',
                type: 'GET',
                data: {
                    index: number
                },
                dataType: "json",
                beforeSend: function() {
                    $(".preloader").show();
                },
                complete: function() {
                    $(".preloader").fadeOut();
                    let transfercek = $('#transfercek').val();

                    if (transfercek > 0 && ulangtambah <= transfercek) {
                        setTimeout(function() {
                            let isi = $('#t_' + ulangtambah).val();
                            $('#barangStock_' + ulangtambah).val(isi).change()
                        }, ulangtambah * 10);
                    } else if (transfercek > 0 && ulangtambah > transfercek) {
                        setTimeout(function() {
                            removeRow2(ulangtambah)
                            $('#transfercek').val('0');
                            enableBootstrapSelect()
                        }, ulangtambah * 50);
                    } else {
                        setTimeout(function() {
                            enableBootstrapSelect()
                        }, ulangtambah * 50);
                    }

                },
                success: function(data) {
                    $("#tabel1 > tbody").append(data.data);
                    $('#kodeNonStock_' + number).append($('#data-select-master-nonstok option').clone());
                    $('#barangStock_' + number).append($('#data-select-master option').clone());
                    $('#barangStock_' + number).focus()
                    //$('#barangStock_' + number).selectpicker();

                }
            });
        }

        // Function if btn_batal clicked
        function klikBatal() {
            history.replaceState({}, document.title, window.location.pathname);
            location.reload();
        }

        function KlikSimpan() {
            let action = $('#action').val()
            if (action == 'simpan') {
                SimpanMRBahanPembantu();
            } else if (action == 'ubah') {
                UbahMRB();
            }
        }

        // Function for saving surat Pengantar
        function SimpanMRBahanPembantu() {
            // Gather all required data

            let idEmployee = $('#idEmployee').text()
            let idDepartment = $('#idDepartment').text()
            let tanggal = $('#tanggal').val()
            let catatan = $('#catatan').val()
            // let GudangT = $('#GudangT').val()

            if (tanggal == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "tanggal cant be blank",
                })
                return;
            }

            // if (GudangT == null) {
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Oops...',
            //         text: "Gudang Tujuan harus di isi",
            //     })
            //     return;
            // }

            // Get tbody length for get items name
            let rowLength = $('#tabel1 tbody tr').length;

            // Final data items 
            let data_items = [] 

            for (let length = 0; length < rowLength; length++) {
                index = length + 1; 
                let barangStock = $('#barangStock_' + index).val()
                let kodeNonStock = $('#kodeNonStock_' + index).val()
                let barangNonStock = $('#barangNonStock_' + index).val()
                let jumlah = $('#jumlah_' + index).val()
                let unit = $('#unit_' + index).val()
                let proses = $('#proses_' + index).val()
                let keperluan = $('#keperluan_' + index).val()
                let kategori = $('#kategori_' + index).val()
                let ulang = $('#ulang_' + index).val()
                let keterangan = $('#keterangan_' + index).val()
                let sw = $('#sw_' + index).val()
                let uom = $('#uom_' + index).val()

                // console.log(kodeNonStock + '++' + kodeNonStock + '++' + index ); 

                if ((kodeNonStock == 0 && barangNonStock != "") || (kodeNonStock != 0 && barangNonStock == "")) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Kolom KODE NON STOCK Harus di isi",
                    })
                    return;
                }
                
                // Checking for barangStock or barangNonStock in this row is not empty
                if (barangStock != 0 || barangNonStock != "") {
                    if (jumlah <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Kolom Jumlah Wajib di Isi Jika Anda Memilih Barang",
                        })
                    }
                    // If barangStock is not empty, it will send data that contain barangStock to server.
                    if (barangStock != 0) {
                        let itemStock = {
                            barang: barangStock,
                            jumlah: jumlah,
                            unit: unit,
                            proses: proses,
                            keperluan: keperluan,
                            uom: uom,
                            kategori: kategori,
                            ulang: ulang,
                            keterangan: keterangan,
                            sw: sw,
                            jenisBarang: 1
                        }
                        data_items.push(itemStock)
                    } else {
                        let itemNonStock = {
                            barang: barangNonStock,
                            kodeNonStock: kodeNonStock,
                            jumlah: jumlah,
                            unit: unit,
                            proses: proses,
                            keperluan: keperluan,
                            uom: uom,
                            kategori: kategori,
                            ulang: ulang,
                            keterangan: keterangan,
                            sw: sw,
                            jenisBarang: 2
                        }
                        data_items.push(itemNonStock)
                    }
                }
            }
            let data = {
                idEmployee: idEmployee,
                idDepartment: idDepartment,
                tanggal: tanggal,
                catatan: catatan,
                // GudangT: GudangT,
                items: data_items
            }
            // console.log(data);
            // return

            //!  ------------------------    Send Request to Server     ------------------------ !!
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Hit backend
            $.ajax({
                type: "POST",
                url: "/Inventori/MaterialRequest/BahanPembantu",
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    $(".preloader").show();
                    $('#btn-menu .btn').prop('disabled', true);
                },
                complete: function() {
                    $(".preloader").fadeOut();
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: data.message,
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    console.log(data.data.ID);
                    $('#cari').val(data.data.ID);
                    Cari();
                },
                error: function(xhr, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message
                    })

                    $('#btn_simpan').prop('disabled', false);
                    $('#btn_batal').prop('disabled', false);

                    return;
                }
            })
        }

        function UbahMRB() {
            // Gather all required data

            let idEmployee = $('#idEmployee').text()
            let idDepartment = $('#idDepartment').text()
            let tanggal = $('#tanggal').val()
            let catatan = $('#catatan').val()
            // let GudangT = $('#GudangT').val()
            let id = $('#btn_ubah').val()

            if (tanggal == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "tanggal cant be blank",
                })
                return;
            }

            // Get tbody length for get items name
            let rowLength = $('#tabel1 tbody tr').length;

            // Final data items
            let data_items = []

            for (let length = 0; length < rowLength; length++) {
                index = length + 1;
                let barangStock = $('#barangStock_' + index).val()
                let barangNonStock = $('#barangNonStock_' + index).val()
                let jumlah = $('#jumlah_' + index).val()
                let unit = $('#unit_' + index).val()
                let proses = $('#proses_' + index).val()
                let keperluan = $('#keperluan_' + index).val()
                let kategori = $('#kategori_' + index).val()
                let ulang = $('#ulang_' + index).val()
                let keterangan = $('#keterangan_' + index).val()
                let sw = $('#sw_' + index).val()
                let uom = $('#uom_' + index).val()

                // Checking for barangStock or barangNonStock in this row is not empty
                if (barangStock != 0 || barangNonStock != "") {
                    if (jumlah <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Kolom Jumlah Wajib di Isi Jika Anda Memilih Barang",
                        })
                        return;
                    }
                    // If barangStock is not empty, it will send data that contain barangStock to server.
                    if (barangStock != 0) {
                        let itemStock = {
                            barang: barangStock,
                            jumlah: jumlah,
                            unit: unit,
                            proses: proses,
                            keperluan: keperluan,
                            uom: uom,
                            kategori: kategori,
                            ulang: ulang,
                            keterangan: keterangan,
                            sw: sw,
                            jenisBarang: 1
                        }
                        data_items.push(itemStock)
                    } else {
                        let itemNonStock = {
                            barang: barangNonStock,
                            jumlah: jumlah,
                            unit: unit,
                            proses: proses,
                            keperluan: keperluan,
                            uom: uom,
                            kategori: kategori,
                            ulang: ulang,
                            keterangan: keterangan,
                            sw: sw,
                            jenisBarang: 2
                        }
                        data_items.push(itemNonStock)
                    }
                }
            }
            let data = {
                id: id,
                idEmployee: idEmployee,
                idDepartment: idDepartment,
                tanggal: tanggal,
                catatan: catatan,
                // GudangT: GudangT,
                items: data_items
            }
            // console.log(data);
            // return

            //!  ------------------------    Send Request to Server     ------------------------ !!
            // Setup CSRF Token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Hit backend
            $.ajax({
                type: "POST",
                url: "/Inventori/MaterialRequest/BahanPembantu/update",
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    // $(".preloader").show();
                    // $('#btn-menu .btn').prop('disabled', true);
                },
                complete: function() {
                    // $(".preloader").fadeOut();
                },
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: data.message,
                        timer: 2000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    console.log(data.data.ID);
                    $('#cari').val(data.data.ID);
                    Cari();

                },
                error: function(xhr, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON.message
                    });

                    $('#btn_simpan').prop('disabled', false);
                    $('#btn_batal').prop('disabled', false);
                    $('#btn_baru').prop('disabled', false);

                    return;
                }
            })
        }

        // KlikCetak
        // function klikCetak() {
        //     // Return print page
        //     let cari = $('#btn_cetak').val()
        //     // Make request
        //     window.open('/Inventori/MaterialRequest/BahanPembantu/cetak?id=' + cari, '_blank');
        // }

        // Function for search surat Pengantar
        function Cari() {
            // Get SW from cari
            let cari = $('#cari').val()
            // Check if value is blank
            if (cari == '') {
                return;
            }

            // Make request
            let formData = {
                id: cari
            };

            $.ajax({
                url: "/Inventori/MaterialRequest/BahanPembantu/cari",
                type: "GET",
                data: formData,
                beforeSend: function() {
                    $(".preloader").show();
                },
                success: function(data) {
                    $('#btn-menu .btn').prop('disabled', true);
                    $('#btn_ubah').prop('disabled', false);
                    // $('#btn_cetak').prop('disabled', false);
                    $('#btn_batal').prop('disabled', false);
                    $('#btn_baru').prop('disabled', false);
                    $("#tampil").html(data);
                    $('#btn_ubah').val(cari);
                    // $('#btn_cetak').val(cari);

                    if ($('#Active').val() == 'P' || $('#Active').val() == 'C') {
                        $('#btn_ubah').addClass('d-none');
                        $('#btn_simpan').addClass('d-none');
                        // $('#btn_Posting').addClass('d-none');
                    } else {
                        $('#btn_ubah').removeClass('d-none');
                        $('#btn_simpan').removeClass('d-none');
                        // $('#btn_Posting').removeClass('d-none');
                    }

                },
                complete: function() {
                    $(".preloader").fadeOut();
                }
            });
        }

        function KlikUbah() {

            console.log('klik ubah');
            $('#action').val('ubah')
            $('#btn-menu .btn').prop('disabled', true);
            $('#btn_simpan').prop('disabled', false);
            $('#btn_batal').prop('disabled', false);

            // enable tanggal
            $('#tanggal').prop('readonly', false)
            $('.barangStock').prop('disabled', false);
            $('.barangNonStock').prop('disabled', false);
            $('.jumlah').prop('disabled', false);
            $('.proses').prop('disabled', false);
            $('.keperluan').prop('disabled', false);
            $('.kategori').prop('disabled', false);
            $('.ulang').prop('disabled', false);
            $('.keterangan').prop('disabled', false);
        }
        
        $('#cari').change(function(event) {
            Cari()
        })

        function nextRowEvent(index, event) {
            let keyCode = event.keyCode || event.which;
            if (keyCode === 40 || keyCode === 13) {
                // count total row
                let number = $('#tabel1 tbody tr').length;
                if (number == index) {
                    disableBoostrapSelect()
                    newRow();
                } else {
                    let nextrow = index + 1
                    $('#barangStock_' + nextrow).focus()
                }

            }
        }

        function klikme(index, e) {
            $('.klik').css('background-color', 'white');

            // if ($("#menuklik").css('display') == 'block') {
            //     $(" #menuklik ").hide();
            // } else {
            var top = e.pageY + 15;
            var left = e.pageX - 100;
            window.idfiell = index;
            $("#judulklik").html(index);
            $('#removeRow').attr('onclick', 'removeRow2(' + index + ')')

            $(this).css('background-color', '#f4f5f7');
            $("#menuklik").css({
                display: "block",
                top: top,
                left: left
            });
            // }
            event.preventDefault();

            return false; //blocks default Webbrowser right click menu
        }

        // Function for dismiss
        $("body").on("click", function() {
            if ($("#menuklik").css('display') == 'block') {
                $(" #menuklik ").hide();
            }
            $('.klik').css('background-color', 'white');
        });

        function removeRow2(index) {

            // check if removeAction allowed
            let removeAction = $('#removeAction').val()
            if (removeAction === 'false') {
                return false;
            }

            disableBoostrapSelect();

            // Get idMaterialRequest value from hidden input for checking
            let idMaterialRequest = $('#idMaterialRequest').val()
            // Check if idMaterialRequest have value
            if (idMaterialRequest == "") {
                // If idMaterialRequest didn't have value or blank it will just remove the row

                // Remove row 
                $("#Row_" + index).remove()

                // Getting Row length for checking row length
                let number = $('#tabel1 tbody tr').length;
                // check row length
                if (number == 0) {
                    // If length of row is 0 it will reload the page
                    window.location.reload();
                } else {
                    // loop row
                    $("#tabel1 tbody tr").each((i, elem) => {
                        newIndex = i + 1
                        // Set new row index
                        elem.id = "Row_" + newIndex

                        // Set new barangStock properties
                        $(elem).find('.barangStock').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.barangStock').attr('onchange', "getDetailBarangStock(this, " + newIndex +
                            ")")
                        $(elem).find('.barangStock').attr('id', "barangStock_" + newIndex)

                        // Set new barangNonStock properties
                        $(elem).find('.barangNonStock').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.barangNonStock').attr('id', "barangNonStock_" + newIndex)

                        // Set new jumlah properties
                        $(elem).find('.jumlah').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.jumlah').attr('id', "jumlah_" + newIndex)

                        // Set new unit properties
                        $(elem).find('.unit').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.unit').attr('id', "unit_" + newIndex)

                        // Set new proses properties
                        $(elem).find('.proses').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.proses').attr('id', "proses_" + newIndex)

                        // Set new keperluan properties
                        $(elem).find('.keperluan').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.keperluan').attr('id', "keperluan_" + newIndex)

                        // Set new kategori properties
                        $(elem).find('.kategori').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.kategori').attr('id', "kategori_" + newIndex)

                        // Set new ulang properties
                        $(elem).find('.ulang').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.ulang').attr('id', "ulang_" + newIndex)

                        // Set new keterangan properties
                        $(elem).find('.keterangan').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.keterangan').attr('onkeypress', "nextRowEvent(" + newIndex + ",event)")
                        $(elem).find('.keterangan').attr('id', "keterangan_" + newIndex)
                        $(elem).find('.sw').attr('id', "sw_" + newIndex)
                        $(elem).find('.uom').attr('id', "uom_" + newIndex)

                        // Set Nomor
                        $(elem).find('.nomor').val(newIndex)

                    })
                }
            } else { // If SWSuratPengantar have value
                // get row length
                let number = $('#tabel1 tbody tr').length;
                if (number !== 1) {
                    // If row length is more than 1 it will remove last row
                    $("#Row_" + index).remove()
                    // loop row
                    $("#tabel1 tbody tr").each((i, elem) => {
                        newIndex = i + 1
                        // Set new row index
                        elem.id = "Row_" + newIndex

                        // Set new barangStock properties
                        $(elem).find('.barangStock').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.barangStock').attr('onchange', "getDetailBarangStock(this, " + newIndex +
                            ")")
                        $(elem).find('.barangStock').attr('id', "barangStock_" + newIndex)

                        // Set new barangNonStock properties
                        $(elem).find('.barangNonStock').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.barangNonStock').attr('id', "barangNonStock_" + newIndex)

                        // Set new jumlah properties
                        $(elem).find('.jumlah').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.jumlah').attr('id', "jumlah_" + newIndex)

                        // Set new unit properties
                        $(elem).find('.unit').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.unit').attr('id', "unit_" + newIndex)

                        // Set new proses properties
                        $(elem).find('.proses').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.proses').attr('id', "proses_" + newIndex)

                        // Set new keperluan properties
                        $(elem).find('.keperluan').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.keperluan').attr('id', "keperluan_" + newIndex)

                        // Set new kategori properties
                        $(elem).find('.kategori').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.kategori').attr('id', "kategori_" + newIndex)

                        // Set new ulang properties
                        $(elem).find('.ulang').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.ulang').attr('id', "ulang_" + newIndex)

                        // Set new keterangan properties
                        $(elem).find('.keterangan').attr('oncontextmenu', "klikme(" + newIndex + ",event)")
                        $(elem).find('.keterangan').attr('onkeypress', "nextRowEvent(" + newIndex + ",event)")
                        $(elem).find('.keterangan').attr('id', "keterangan_" + newIndex)
                        $(elem).find('.sw').attr('id', "sw_" + newIndex)
                        $(elem).find('.uom').attr('id', "uom_" + newIndex)

                        // Set Nomor
                        $(elem).find('.nomor').val(newIndex)

                    })
                } else {
                    // If row length is less than 1 or 0 it will show alert because that row is last item. it can't be deleted.
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "This is last item in this transaction. You can't Delete it.",
                    })
                    return;
                }
            }
            enableBootstrapSelect();
        }
    </script>
@endsection
