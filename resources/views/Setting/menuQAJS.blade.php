<script>
    $(document).ready(function($) {

        ////----- Open the modal to CREATE a link -----////
        $('body').on('click', '#tam-qa', function() {
            $('#save-QA').val("add");
            $('#modalFormData').trigger("reset");
            $("#hapus-QA").hide();
            $('#modalqa').modal('show');
            var modlastid = $('#modlastid').val();
            $('#ID_Modul').val(modlastid);
        });

        // Clicking the save button on the open modal for both CREATE and UPDATE
        $('body').on('click', '#save-QA', function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            e.preventDefault();
            var formData = {
                Name: $('#Nameid').val(),
                Menu: $('#Menuid').val(),
                Ordinal: $('#Ordinal').val(),
                Ordinallama: $('#Ordinallama').val(),
            };

            var state = $('#save-QA').val();
            var link_id = $('#id').val();

            if (state == "add") {
                var type = "POST";
                var ajaxurl = 'MenuQA';
            } else if (state == "update") {
                type = "PUT";
                ajaxurl = 'MenuQA/' + link_id;
            }

            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {

                    if (state == "add") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Register Berhasil!',
                            text: 'Silahkan di cek Kembali'
                        });

                        $('#modalFormData').trigger("reset");
                        $('#modalqa').modal('hide');

                        menuQA();

                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Edit Berhasil!',
                            text: 'Silahkan di cek Kembali'
                        });
                        $('#modalFormData').trigger("reset");
                        $('#modalqa').modal('hide');

                        menuQA();
                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });
    });

    ////----- Open the modal to UPDATE a link -----////
    function editqa(id) {

        var dataklik = id;
        const myArray = dataklik.split("+");
        var MenuQA = myArray[0];

        // $('#t1').val(link_id);
        $.get('MenuQA/' + MenuQA, function(data) {
            $('#id').val(data.ID_QA);
            $('#Name').val(myArray[1]);
            $('#Menu').val(data.Patch);
            $('#Ordinal').val(data.Ordinal);
            $('#Nameid').val(data.ID_User);
            $('#Menuid').val(data.ID_Modul);

            $('#save-QA').val("update");
            $("#hapus-QA").show();
            $('#modalqa').modal('show');
            tampilordinal();
        });
    }

    //!----- Hapus Quick Akses -----////
    function hapusQA() {
        id = $('#id').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "DELETE",
            url: 'MenuQA/' + id,
            success: function(data) {
                Swal.fire({
                    icon: 'success',
                    title: 'success',
                    text: 'Berhasih di Hapus!'
                });
                $('#modalFormData').trigger("reset");
                $('#modalqa').modal('hide');
                menuQA();
            }
        });
    }
</script>
