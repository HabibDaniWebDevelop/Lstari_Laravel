<script>
    jQuery(document).ready(function($) {

        ////----- Open the modal to CREATE a link -----////
        $('body').on('click', '#btn-add', function() {
            // $('#btn-save').val("add");
            // $('#modalFormData').trigger("reset");
            // $('#linkEditorModal').modal('show');
            // var modlastid = $('#modlastid').val();
            // $('#ID_Modul').val(modlastid);

            $.get('/ListMenu/tambah', function(data) {
                $('#btn-save').val("add");
                $('#modalFormData').trigger("reset");
                $("#modalListMenu").html(data);
                $('#linkEditorModal').modal('show');
            });

        });

        // Clicking the save button on the open modal for both CREATE and UPDATE
        $('body').on('click', '#btn-save', function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            var formData = {
                Name: $('#Name').val(),
                Ordinal: $('#Ordinal').val(),
                Parent: $('#Parent').val(),
                Patch: $('#Patch').val(),
                Status: $('#Status').val(),
                Icon: $('#Icon').val(),
                made_by: $('#made_by').val(),
                Ordinallama: $('#Ordinallama').val(),
            };
            var state = $('#btn-save').val();
            var link_id = $('#ID_Modul').val();

            if (state == "add") {
                var type = "POST";
                var ajaxurl = 'links';
                var tt1 = parseInt($('#lasno').val()) + parseInt(1);
            }

            if (state == "update") {
                type = "PUT";
                ajaxurl = 'links/' + link_id;
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
                            title: 'success',
                            text: 'Register Berhasil!',
                            showConfirmButton: false,
                            timer: 1200
                        });

                        jQuery('#modalFormData').trigger("reset");
                        jQuery('#linkEditorModal').modal('hide');
                        menuListMenu();

                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'success!',
                            text: 'Edit Berhasil!',
                            showConfirmButton: false,
                            timer: 1200
                        });
                        // $("#link" + link_id).replaceWith(link);
                        jQuery('#modalFormData').trigger("reset");
                        jQuery('#linkEditorModal').modal('hide');
                        menuListMenu();
                    }

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });

        ////----- DELETE a link and remove from the page -----////
        jQuery('.delete-link').click(function() {
            var link_id = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "DELETE",
                url: 'links/' + link_id,
                success: function(data) {
                    console.log(data);
                    $("#link" + link_id).remove();
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        });
    });

    ////----- Open the modal to UPDATE a link -----////
    function editListMenu(id, id2) {
        $.get('links/' + id, function(data) {
            $('#btn-save').val("update");
            $('#modalFormData').trigger("reset");
            $("#modalListMenu").html(data);
            $('#linkEditorModal').modal('show');
            $('#akseslis').val(id2);
        });

    };
</script>
