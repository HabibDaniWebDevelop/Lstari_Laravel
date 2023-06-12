
<style>

footer {
  display: none;
}

  @media (min-width: 992px) {
  footer {
    display: block;
  }
}
</style>

{{-- <footer class="content-footer footer bg-navbar-theme fixed-bottom">
    <div class="container-fluid d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
      <div>      
        Tanggal: {{ date('d F Y'), }} <b> <span id="jam" style="font-size:24"></span></b>
      </div>
      <div class="mb-2 mb-md-0">
        <strong>Copyright &copy; IT Developer PT. Lestari Mulia Sentosa {{ date('Y') }} </a>.</strong>
      </div>

    </div>
  </footer> --}}

  <script type="text/javascript">
    window.onload = function() { jam(); }
   
    function jam() {
        var e = document.getElementById('jam');
        var now = new Date();
        now.setMinutes(now.getMinutes() + 20); // timestamp
        now = new Date(now).toLocaleTimeString(); // Date object
        e.innerHTML = now;
   
        setTimeout('jam()', 1000);
    }
   
</script>