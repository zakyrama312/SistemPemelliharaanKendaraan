<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
  <div class="copyright">
    <!-- &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved -->
  </div>
  <div class="credits">
    <!-- All the links in the footer should remain intact. -->
    <!-- You can delete the links only if you purchased the pro version. -->
    <!-- Licensing information: https://bootstrapmade.com/license/ -->
    <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
    <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
  </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
    class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/chart.js/chart.umd.js"></script>
<script src="/assets/vendor/echarts/echarts.min.js"></script>
<script src="/assets/vendor/quill/quill.js"></script>
<script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="/assets/vendor/php-email-form/validate.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- datepicker -->
<!-- Bootstrap Datepicker -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> -->
<!-- select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<!-- Template Main JS File -->
<script src="/assets/js/main.js"></script>
<script>
  new AutoNumeric('.format-rupiah', {
    digitGroupSeparator: '.',
    decimalCharacter: ',',
    currencySymbol: 'Rp ',
    currencySymbolPlacement: 'p',
    unformatOnSubmit: true
  });
</script>
<script>
  $(".select2").select2({
    placeholder: "Pilih Nama",
    allowClear: true,
    theme: "bootstrap-5",
  });

  $('#modalCreate').on('shown.bs.modal', function () {
    $('.select2').select2({
      dropdownParent: $('#modalCreate') // Set parent dropdown ke modal
    });
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    flatpickr(".format-tanggal", {
      enableTime: false, // Aktifkan pilihan jam & menit
      dateFormat: "d/m/Y", // Format seperti di gambar (DD/MM/YYYY HH:mm)
      time_24hr: true, // Gunakan format 24 jam
      locale: "id",
      defaultDate: null, // Bisa set default ke hari ini,
    });
  });
</script>
</body>

</html>