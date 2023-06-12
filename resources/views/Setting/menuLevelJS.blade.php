<script>
  
      $(document).ready(function($){
  
        ////----- Open the modal to CREATE a link -----////
        $('body').on('click', '#tam-menuLevel', function () {
          $('#save-menuLevel').val("add");
          $('#modalFormData').trigger("reset");
          $('#modalmenuLevel').modal('show');
          var modlastid = $('#modlastid').val();
          $('#ID_Modul').val(modlastid);
        });
  
        // Clicking the save button on the open modal for both CREATE and UPDATE
        $('body').on('click', '#save-menuLevel', function (e) {
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
  
          e.preventDefault();
          var formData = {
              Name: $('#Name').val(),
            };
  
          var state = $('#save-menuLevel').val();
          var link_id = $('#id').val();
  
          if (state == "add") {
              var type = "POST";
              var ajaxurl = 'menuLevel';
            }
  
          if (state == "update") {
            type = "PUT";
            ajaxurl = 'menuLevel/' + link_id;
          }
  
          $.ajax({
              type: type,
              url: ajaxurl,
              data: formData,
              dataType: 'json',
              success: function (data) {
  
                if (state == "add") {
                  Swal.fire({
                    icon: 'success',
                    title: 'Register Berhasil!',
                    text: 'Silahkan di cek Kembali'
                  });
  
                  $('#modalFormData').trigger("reset");
                  $('#modalmenuLevel').modal('hide');

                  menuLevel();
  
                } else {
                  Swal.fire({
                    icon: 'success',
                    title: 'Edit Berhasil!',
                    text: 'Silahkan di cek Kembali'
                  });
                  $('#modalFormData').trigger("reset");
                  $('#modalmenuLevel').modal('hide');

                  menuLevel();
                }
              },
              error: function (data) {
                console.log('Error:', data);
              }
            });
    
        });
  
      });

      ////----- Open the modal to UPDATE a link -----////
      function editlevel(id) {
        // alert('tess');
        var link_Level = id;
        
        $.get('menuLevel/' + link_Level, function (data) {
        $('#id').val(data.Id_Level);
        $('#Name').val(data.Nama_level);
        $('#Status').val(data.Status);
        
        $('#save-menuLevel').val("update");
        $('#modalmenuLevel').modal('show');
        });
        
      }
  
  </script>
