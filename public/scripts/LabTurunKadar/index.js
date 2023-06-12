function html_table_to_excel(filename,type){
    let data = document.getElementById('theTable');

    let file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});

    XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

    XLSX.writeFile(file, filename+'.' + type);
}

function calculateWeighDiff(indexTable) {
    let carat = $('#carat_'+indexTable).val()
    let toleransi = $('#caratTolerance_'+indexTable).val()
    let berat = $('#weightLab_'+indexTable).val()

    let selisihberat = ((carat - toleransi) / 100) * berat;
    console.log(Number(selisihberat).toFixed(2));
    $('#weightDiff_'+indexTable).val(Number(selisihberat).toFixed(2))
}

function klikCetak() {
    let nomorNota = $('#nomorNota').val()
    if (nomorNota !== ''){
        window.open('/Produksi/Laboratorium/LabTurunKadar/cetak?nomorNota='+nomorNota, '_blank');
    }else{
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'nomorNota belum terpilih',
        })
        return
    }
}

function CariNotaTukangLuar() {
    let nomorNota = $('#nomorNota').val()
    if (nomorNota == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "Nomor Nota Tidak Boleh Kosong",
        })
        return;
    }

    let data = {nomorNota:nomorNota}

    $.ajax({
        type: "GET",
        url: "/Produksi/Laboratorium/LabTurunKadar/search",
        data:data,
        dataType: 'json',
        beforeSend: function () {
            $(".preloader").show();  
        },
        complete: function () {
            $(".preloader").fadeOut(); 
        },
        success: function(data) {
            // Empty Table
            $("#TransactionValue").empty()
            // Enable Cetak
            $('#btn_cetak').prop('disabled',false)
            // Enable Export Excel
            $('#btn_export_excel').prop('disabled',false)
            // add table
            $("#TransactionValue").html(data.data.html);
            // set Tukang
            $('#employeeName').val(data.data.detail.NamaTukang);
            // set Kadar
            $('#kadar').val(data.data.detail.Kadar);
            // set Proses
            $('#proses').val(data.data.detail.Proses);
            return;
        },
        error: function(xhr){
            // Disable Cetak
            $('#btn_cetak').prop('disabled',true)
            // Disable Export Excel
            $('#btn_export_excel').prop('disabled',true)
            let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            })
            return;
        }
    })
}

function updatecjepsi(idNTHKO, ordinalNTHKO, index) {
    // Check if idNTHKO is blank or null 
    if (idNTHKO == null || idNTHKO == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'idNTHKO belum terpilih',
        })
        return
    }

    // Check if ordinalNTHKO is blank or null
    if (ordinalNTHKO == null || ordinalNTHKO == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'ordinalNTHKO belum terpilih',
        })
        return
    }

    // check if index is blank or null
    if (index == null || index == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'index belum terpilih',
        })
        return
    }

    // get Tanggal from input with id index
    let tanggal = $('#tanggalLab_'+index).val()

    // Check if tanggal is blank or null
    if (tanggal == null || tanggal == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'tanggal belum terisi',
        })
        return
    }

    // Get Carat by id with cata+index
    let carat = $('#carat_'+index).val()

    // Check if carat is blank or null
    if (carat == null || carat == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'carat belum terisi',
        })
        return
    }

    // Get caratTolerance by id with caratTolerance+index
    let caratTolerance = $('#caratTolerance_'+index).val()

    // Check if caratTolerance is blank or null
    if (caratTolerance == null || caratTolerance == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'caratTolerance belum terisi',
        })
        return
    }

    // get weightLab by id with weightLab+index
    let weightLab = $('#weightLab_'+index).val()

    // Check if weightLab is blank or null
    if (weightLab == null || weightLab == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'weightLab belum terisi',
        })
        return
    }
    
    // get weightDiff by id with weightDiff+index
    let weightDiff = $('#weightDiff_'+index).val()

    // Check if weightDiff is blank or null
    if (weightDiff == null || weightDiff == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'weightDiff belum terisi',
        })
        return
    }

    // get remarks by id with remarks+index
    let remarks = $('#remarks_'+index).val()

    // wrap all data to object
    let data = {
        idNTHKO: idNTHKO,
        ordinalNTHKO: ordinalNTHKO,
        tanggal: tanggal,
        carat: carat,
        caratTolerance: caratTolerance,
        weightLab: weightLab,
        weightDiff: weightDiff,
        remarks:remarks
    }

    // get csrf token from meta content with name 'csrf-token' using jquery and set csrf-token to headers
    let token = $('meta[name="csrf-token"]').attr('content')
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': token
        }
    })
    // send data to server with ajax
    $.ajax({
        url: '/Produksi/Laboratorium/LabTurunKadar',
        type: 'PUT',
        data: data,
        beforeSend: function () {
            $(".preloader").show();  
        },
        complete: function () {
            $(".preloader").fadeOut(); 
        },
        success: function (result) {
            $('#modalKu_'+index).modal('toggle');
            // Empty Table
            $("#TransactionValue").empty()
            // add table
            $("#TransactionValue").html(result.data.html);
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: result.message,
            })
            return
        },
        error: function(xhr){
            let message = xhr?.responseJSON?.message == undefined ? "Server Error" : xhr?.responseJSON?.message
            // It will executed if response from backend is error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            })
            return;
        }
    })
}