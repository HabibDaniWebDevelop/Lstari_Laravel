<script>
    $(document).ready(function($) {

        ////----- Open the modal to CREATE a link -----////
        $('body').on('click', '#tam-user', function() {
            $('#save-user').val("add");
            $('#modalFormData').trigger("reset");
            $("#reset-psw").hide();
            $('#modaluser').modal('show');
            var modlastid = $('#modlastid').val();
            $('#ID_Modul').val(modlastid);
        });

        // Clicking the save button on the open modal for both CREATE and UPDATE
        $('body').on('click', '#save-user', function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            e.preventDefault();

            var formData = {
                Name: $('#Name').val(),
                level: $('#Level').val(),
                status: $('#status').val(),
            };

            var state = $('#save-user').val();
            var link_id = $('#id').val();

            if (state == "add") {
                var type = "POST";
                var ajaxurl = 'user';
            }

            if (state == "update") {
                type = "PUT";
                ajaxurl = 'user/' + link_id;
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

                        $('#modalFormData').trigger("reset");
                        $('#modaluser').modal('hide');

                        menuuser();

                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: 'Edit Berhasil!',
                            showConfirmButton: false,
                            timer: 1200
                        });
                        $('#modalFormData').trigger("reset");
                        $('#modaluser').modal('hide');

                        menuuser();
                    }
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal!',
                        text: 'Datat sudah ada'
                    });
                    $('#modaluser').modal('hide');
                    console.log('Error:', data);
                }
            });
        });

        // Clicking the save button on the open modal for both CREATE and UPDATE
        $('body').on('click', '#reset-psw', function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            e.preventDefault();
            var link_id = $('#id').val();
            var type = "POST";
            var ajaxurl = '/UserUpdatePSW';
            var formData = {
                id: link_id,
                Name: $('#Name').val(),
            };

            $.ajax({
                type: type,
                url: ajaxurl,
                data: formData,
                dataType: 'json',
                success: function(data) {

                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: 'Edit Berhasil!',
                        showConfirmButton: false,
                        timer: 1200
                    });
                    $('#modalFormData').trigger("reset");
                    $('#modaluser').modal('hide');
                    menuuser();

                },
                error: function(data) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal!',
                        text: 'Datat sudah ada'
                    });
                    $('#modaluser').modal('hide');
                    console.log('Error:', data);
                }
            });
        });

    });

    function edituser(id) {
        var dataklik = id;
        const myArray = dataklik.split("+");
        var link_user = myArray[0];

        $('#t1').val(myArray[1]);
        $('#t2').val(myArray[2]);
        $('#t3').val(myArray[3]);
        $('#t4').val(myArray[4]);
        $('#t5').val(myArray[5]);
        $('#t6').val(myArray[6]);

        if (myArray[3] == '') {
            $('#t7').val(1);
        } else if (myArray[4] == '') {
            $('#t7').val(2);
        } else if (myArray[5] == '') {
            $('#t7').val(3);
        } else {
            $('#t7').val(4);
        }

        // $('#t1').val(link_id);
        $.get('user/' + link_user, function(data) {
            $('#id').val(data.id);
            $('#Name').val(data.name);
            $('#Level').val(data.level);
            $('#status').val(data.status);

            $('#reset-psw').val(data.id);
            $('#save-user').val("update");
            $("#reset-psw").show();
            $('#modaluser').modal('show');
        });
    }
</script>