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

<script src="/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="/assets/vendor/php-email-form/validate.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<!-- datepicker -->
<!-- Bootstrap Datepicker -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> -->
<!-- select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- DataTables Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>




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
  new AutoNumeric('.format-rupiahEdit', {
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
<script>
  document.addEventListener("DOMContentLoaded", function () {
    new simpleDatatables.DataTable(".datatable");
  });
</script>
<script>
  $(document).ready(function () {
    // Inisialisasi DataTable
    var tableKeuangan = $('#tableKeuangan').DataTable({
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
      dom: 'Blfrtip',
      buttons: [{
        extend: 'excelHtml5',
        footer: true,
        text: '<i class="fa-solid fa-file-excel"></i>',
        exportOptions: {
          modifier: {
            page: 'all'
          }
        },
      },
      {
        extend: 'print',
        footer: true,
        text: '<i class="fa-solid fa-print"></i>',
        exportOptions: {
          modifier: {
            page: 'all'
          }
        }
      }
      ],
      footerCallback: function (row, data, start, end, display) {
        var api = this.api();

        var intVal = function (i) {
          return typeof i === 'string' ? i.replace(/[\.,]/g, '').replace(/[^\d\-]/g, '') *
            1 :
            typeof i === 'number' ? i : 0;
        };

        var totalNominal = api.column(4, {
          page: 'current'
        }).nodes().reduce(function (a, b) {
          return a + intVal($(b).data('nominal'));
        }, 0);

        $('#totalNominal').html('Rp ' + totalNominal.toLocaleString('id-ID'));
      }
    });

    function filterByDate() {
      var startDate = $('#start_date').val();
      var endDate = $('#end_date').val();

      $.fn.dataTable.ext.search.pop(); // Hapus filter lama agar tidak tumpang tindih

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        var date = $(tableKeuangan.row(dataIndex).node()).find('td[data-tanggal]').data('tanggal');

        if (startDate && date < startDate) {
          return false;
        }
        if (endDate && date > endDate) {
          return false;
        }
        return true;
      });

      tableKeuangan.draw();
    }

    $('#start_date, #end_date').on('change', filterByDate);
  });
</script>
<script>
  $(document).ready(function () {
    // Inisialisasi DataTable
    var tablePemeliharaan = $('#tablePemeliharaan').DataTable({
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
      ],
      dom: 'Blfrtip',
      buttons: [{
        extend: 'excelHtml5',
        footer: true,
        text: '<i class="fa-solid fa-file-excel"></i>',
        exportOptions: {
          modifier: {
            page: 'all'
          }
        },
      },
      {
        extend: 'print',
        footer: true,
        text: '<i class="fa-solid fa-print"></i>',
        exportOptions: {
          modifier: {
            page: 'all'
          }
        }
      }
      ],
      footerCallback: function (row, data, start, end, display) {
        var api = this.api();

        var intVal = function (i) {
          return typeof i === 'string' ? i.replace(/[\.,]/g, '').replace(/[^\d\-]/g, '') *
            1 :
            typeof i === 'number' ? i : 0;
        };

        var totalNominal = api.column(5, {
          page: 'current'
        }).nodes().reduce(function (a, b) {
          return a + intVal($(b).data('biaya'));
        }, 0);

        $('#totalBiaya').html('Rp ' + totalNominal.toLocaleString('id-ID'));
      }
    });

    function filterByDate() {
      var startDate = $('#start_date').val();
      var endDate = $('#end_date').val();

      $.fn.dataTable.ext.search.pop(); // Hapus filter lama agar tidak tumpang tindih

      $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        var date = $(tablePemeliharaan.row(dataIndex).node()).find('td[data-tanggal]').data(
          'tanggal');

        if (startDate && date < startDate) {
          return false;
        }
        if (endDate && date > endDate) {
          return false;
        }
        return true;
      });

      tablePemeliharaan.draw();
    }

    $('#start_date, #end_date').on('change', filterByDate);
  });
</script>
</body>

</html>