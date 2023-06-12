{{-- ? ---------------- script untuk messanging & Notification ---------------- --}}
<script>
    $(document).ready(function() {
        Messaging_list();
        setInterval(Messaging_count, 1800000); //30 menit

        // Notification_list();
        // setInterval(Notification_count, 1800000); //30 menit
    });
</script>

{{-- ? ---------------- script untuk messanging ---------------- --}}
<script>
    function Messaging_count() {
        $.get("{{ url('/Messaging_count') }}", function(data) {
            $("#mscount").html(data);
            // alert(data);
            if (data == '0') {
                $("#ntcountmaster").addClass('d-none');
            } else {
                $("#ntcountmaster").removeClass('d-none');
            }
        });
    }

    function Messaging_list() {
      
        $.get("{{ url('/Messaging_list') }}", function(data) {
            $("#pesan").html(data);
        });
    }

    function Messaging_read(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var formData = {
            id: id,
        };
        var type = "PUT";
        var ajaxurl = '/messaging/read/' + id;

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function(data) {
                Messaging_list();
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    }

    function Messaging_readall() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var type = "POST";
        var ajaxurl = '/messaging/readall';

        $.ajax({
            type: type,
            url: ajaxurl,
            data: '',
            dataType: 'json',
            success: function(data) {
                Messaging_list();
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    }

    function Messaging_write(id,no) {
        $.get("{{ url('/Messaging_write') }}/" + id+'&'+no, function(data) {
            $("#pesan").html(data);
        });
    }

    function Messaging_kirim(aksi) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // if (aksi == "P") {
        //     pesan1 = 'Sedang di Kerjakan!';
        // }
        // else if (aksi == "S") {
        //   pesan1 = 'OK Selesai!';
        // }
        // else if (aksi == "C") {
        //   pesan1 = 'Telah di Batal!';
        // }
        // else if (aksi == '0'){
        //   pesan1 = $('#pesan2').val();
        //   aksi = "P";
        // }

        pesan1 = $('#pesan2').val();

        var id = $('#id').val();
        var state = $('#type').val();

        if (state == "add") {
            var type = "POST";
            var ajaxurl = '/messaging';

            var formData = {
                pilihh: $('#pilihh').val(),
                pesan: $('#pesan3').val(),
                name: $('#name').val(),
            };
        }

        if (state == "update") {
            var type = "PUT";
            var ajaxurl = '/messaging/' + id; 

            var formData = {
                pilihh: aksi,
                pesan: pesan1,
            };
        }

        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function(data) {

                if (state == "add") {
                    Messaging_list();
                } else {
                    Messaging_list();
                }
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Upss Error !',
                    text: data.responseJSON.message,
                    showConfirmButton: false,
                    timer: 2400
                })
            }
        });
    }
</script>

{{-- ? ---------------- script untuk Notification ---------------- -- --}}

{{-- <script>
  function Notification_count() {
    $.get("{{ url('/Notification_count') }}", function(data) {
      $("#ntcount").html(data);
      // alert(data);
      if(data=='0'){ $("#mscountmaster").addClass('d-none'); }
      else{ $("#mscountmaster").removeClass('d-none');}
    });
  }

  function Notification_list() {
    $.get("{{ url('/Notification_list') }}", function(data) {
      $("#Notif").html(data);
    });
  }

  function Notification_read(id) {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var formData = {
      id: id,
    };
    var type = "PUT";
    var ajaxurl = '/Notification/NTread/' + id;

    $.ajax({
      type: type,
      url: ajaxurl,
      data: formData,
      dataType: 'json',
      success: function (data) {
        Notification_list();
      },
      error: function (data) {
        console.log('Error:', data);
      }
    });
  }

  function Notification_readall() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var type = "POST";
    var ajaxurl = '/Notification/NTreadall';

    $.ajax({
      type: type,
      url: ajaxurl,
      data: '',
      dataType: 'json',
      success: function (data) {
        Notification_list();
      },
      error: function (data) {
        console.log('Error:', data);
      }
    });
  } --}}

</script>
