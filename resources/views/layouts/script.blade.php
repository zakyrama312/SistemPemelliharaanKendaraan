@php
use App\Helpers\FormatHelper;
@endphp
<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("bi-eye");
            eyeIcon.classList.add("bi-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("bi-eye-slash");
            eyeIcon.classList.add("bi-eye");
        }
    });
</script>

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
    new AutoNumeric('.format-rupiah1', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        currencySymbol: 'Rp ',
        currencySymbolPlacement: 'p',
        unformatOnSubmit: true
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[id^="largeModal"]').forEach(modal => {
            modal.addEventListener('shown.bs.modal', function() {
                modal.querySelectorAll('.format-rupiahEdit').forEach(el => {
                    new AutoNumeric(el, {
                        digitGroupSeparator: '.',
                        decimalCharacter: ',',
                        currencySymbol: 'Rp ',
                        currencySymbolPlacement: 'p',
                        unformatOnSubmit: true
                    });
                });

                modal.querySelectorAll('.format-rupiahEdit1').forEach(el => {
                    new AutoNumeric(el, {
                        digitGroupSeparator: '.',
                        decimalCharacter: ',',
                        currencySymbol: 'Rp ',
                        currencySymbolPlacement: 'p',
                        unformatOnSubmit: true
                    });
                });

                const hargaBbmField = modal.querySelector('#harga_bbm_edit');
                const biayaField = modal.querySelector('#biaya_edit');
                const jumlahLiterField = modal.querySelector('#jumlah_liter_edit');

                const hargaBbmAutoNumeric = AutoNumeric.getAutoNumericElement(hargaBbmField);
                const biayaAutoNumeric = AutoNumeric.getAutoNumericElement(biayaField);

                function hitungBiayaEdit() {
                    const liter = parseFloat(jumlahLiterField.value.replace(',', '.')) || 0;
                    const harga = hargaBbmAutoNumeric ? hargaBbmAutoNumeric.getNumber() : 0;
                    const total = liter * harga;

                    if (biayaAutoNumeric) {
                        biayaAutoNumeric.set(total);
                    }
                }

                jumlahLiterField.addEventListener('input', hitungBiayaEdit);
                hargaBbmField.addEventListener('input', hitungBiayaEdit);

            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hargaBbmField = document.getElementById('harga_bbm');
        const biayaField = document.getElementById('biaya');
        const jumlahLiterField = document.getElementById('jumlah_liter');

        // Ambil instance AutoNumeric yang sudah kamu inisialisasi sebelumnya
        const hargaBbmAutoNumeric = AutoNumeric.getAutoNumericElement(hargaBbmField);
        const biayaAutoNumeric = AutoNumeric.getAutoNumericElement(biayaField);

        function hitungBiaya() {
            const liter = parseFloat(jumlahLiterField.value.replace(',', '.')) || 0;
            const harga = hargaBbmAutoNumeric ? hargaBbmAutoNumeric.getNumber() : 0;
            const total = liter * harga;

            if (biayaAutoNumeric) {
                biayaAutoNumeric.set(total);
            }
        }

        jumlahLiterField.addEventListener('input', hitungBiaya);
        hargaBbmField.addEventListener('input', hitungBiaya);
    });
</script>

<script>
    $(".select2").select2({
        placeholder: "Pilih Nama",
        allowClear: true,
        theme: "bootstrap-5",
    });

    $('#modalCreate').on('shown.bs.modal', function() {
        $('.select2').select2({
            dropdownParent: $('#modalCreate') // Set parent dropdown ke modal
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
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
    document.addEventListener("DOMContentLoaded", function() {
        new simpleDatatables.DataTable(".datatable");
    });
</script>
<script>
    $('#btnPrintBbm').on('click', function(e) {
        e.preventDefault();

        const start = $('#start_date').val();
        const end = $('#end_date').val();
        const slug =
            "{{ isset($kendaraan) && !is_iterable($kendaraan) ? $kendaraan->slug : '' }}"; // pastikan ini tersedia dari controller

        if (!start || !end) {
            alert("Silakan pilih tanggal mulai dan akhir terlebih dahulu.");
            return;
        }

        const url = `/bbm/print/${slug}?start_date=${start}&end_date=${end}`;
        window.open(url, '_blank');
    });
</script>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var tableBahanBakar = $('#tableBahanBakar').DataTable({
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
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();

                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\.,]/g, '').replace(/[^\d\-]/g, '') *
                        1 :
                        typeof i === 'number' ? i : 0;
                };

                var totalNominalBiaya = api.column(4, {
                    page: 'current'
                }).nodes().reduce(function(a, b) {
                    return a + intVal($(b).data('nominal'));
                }, 0);

                $('#totalNominalBiaya').html('Rp ' + totalNominalBiaya.toLocaleString('id-ID'));
            }
        });

        function filterByDate() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.fn.dataTable.ext.search.pop(); // Hapus filter lama agar tidak tumpang tindih

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                var date = $(tableBahanBakar.row(dataIndex).node()).find('td[data-tanggal]').data(
                    'tanggal');

                if (startDate && date < startDate) {
                    return false;
                }
                if (endDate && date > endDate) {
                    return false;
                }
                return true;
            });

            tableBahanBakar.draw();
        }

        $('#start_date, #end_date').on('change', filterByDate);
    });
</script>
<script>
    $(document).ready(function() {
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
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();

                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\.,]/g, '').replace(/[^\d\-]/g, '') *
                        1 :
                        typeof i === 'number' ? i : 0;
                };

                var totalNominal = api.column(4, {
                    page: 'current'
                }).nodes().reduce(function(a, b) {
                    return a + intVal($(b).data('nominal'));
                }, 0);

                $('#totalNominal').html('Rp ' + totalNominal.toLocaleString('id-ID'));
            }
        });

        function filterByDate() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.fn.dataTable.ext.search.pop(); // Hapus filter lama agar tidak tumpang tindih

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
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
<!-- <script>
    $(document).ready(function () {
        const tablePemeliharaan = $('#tablePemeliharaan').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            dom: 'Blfrtip',
            buttons: [{
                extend: 'excelHtml5',
                footer: true,
                text: '<i class="fa-solid fa-file-excel"></i>',
                filename: 'Laporan_Pemeliharaan_' + new Date().toISOString().slice(0, 10),
                title: null,
                exportOptions: {
                    modifier: {
                        page: 'all'
                    }
                },
                customize: function (xlsx) {
                    const sheet = xlsx.xl.worksheets['sheet1.xml'];
                    const rows = $('row', sheet);

                    // Tambahkan header laporan
                    const customRows = `
                        <row r="1">
                            <c t="inlineStr" r="A1"><is><t>OPD</t></is></c>
                            <c t="inlineStr" r="B1"><is><t>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG KOTA TEGAL</t></is></c>
                        </row>
                        <row r="2">
                            <c t="inlineStr" r="A2"><is><t>Tgl</t></is></c>
                            <c t="inlineStr" r="B2"><is><t>1 Januari 2024 s.d 31 Desember 2024</t></is></c>
                        </row>
                        <row r="3">
                            <c t="inlineStr" r="A3"><is><t>LAPORAN PEMELIHARAAN BARANG TAHUN ANGGARAN {{ \Carbon\Carbon::parse(now())->translatedFormat('Y')  }}</t></is></c>
                        </row>
                    `;

                    sheet.childNodes[0].childNodes[1].innerHTML =
                        customRows + sheet.childNodes[0].childNodes[1].innerHTML;

                    // Geser seluruh baris ke bawah
                    rows.each(function () {
                        const r = parseInt($(this).attr('r'));
                        $(this).attr('r', r + 3);
                        $('c', this).each(function () {
                            const cellRef = $(this).attr('r');
                            const col = cellRef.replace(/[0-9]/g, '');
                            const row = parseInt(cellRef.replace(/[A-Z]/g,
                                '')) + 3;
                            $(this).attr('r', col + row);
                        });
                    });
                }
            },
            {
                extend: 'print',
                footer: true,
                text: '<i class="fa-solid fa-print"></i>',
                exportOptions: {
                    modifier: {
                        page: 'all'
                    }
                },
                title: '',
                customize: function (win) {
                    $(win.document.body).prepend(`
                        <div style="margin-bottom: 20px;">
                            <h3 style="text-align: center;">LAPORAN PEMELIHARAAN BARANG TAHUN ANGGARAN {{ \Carbon\Carbon::parse(now())->translatedFormat('Y')  }}</h3>
                            <p><strong>OPD:</strong> DINAS PEKERJAAN UMUM DAN PENATAAN RUANG KOTA TEGAL</p>
                            <p><strong>Tanggal:</strong> 1 Januari 2024 s.d 31 Desember 2024</p>
                        </div>
                    `);
                }
            }
            ],
            footerCallback: function (row, data, start, end, display) {
                const api = this.api();

                const intVal = function (i) {
                    return typeof i === 'string' ?
                        i.replace(/[\.,]/g, '').replace(/[^\d\-]/g, '') * 1 :
                        typeof i === 'number' ?
                            i :
                            0;
                };

                const totalNominal = api.column(7, {
                    page: 'current'
                })
                    .nodes()
                    .reduce(function (a, b) {
                        return a + intVal($(b).data('biaya'));
                    }, 0);

                $('#totalBiaya').html('Rp ' + totalNominal.toLocaleString('id-ID'));
            }
        });

        // Fungsi filter tanggal
        function filterByDate() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            $.fn.dataTable.ext.search.pop(); // hapus filter sebelumnya
            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                const date = $(tablePemeliharaan.row(dataIndex).node()).find('td[data-tanggal]').data(
                    'tanggal');
                if (startDate && date < startDate) return false;
                if (endDate && date > endDate) return false;
                return true;
            });

            tablePemeliharaan.draw();
        }

        $('#start_date, #end_date').on('change', filterByDate);
    });
</script> -->
<script>
    $(document).ready(function() {
        const tablePemeliharaan = $('#tablePemeliharaan').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    footer: true,
                    text: '<i class="fa-solid fa-file-excel"></i>',
                    filename: 'Laporan_Pemeliharaan_' + new Date().toISOString().slice(0, 10),
                    title: null,
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    },
                    customize: function(xlsx) {
                        const sheet = xlsx.xl.worksheets['sheet1.xml'];
                        const rows = $('row', sheet);
                        const offset = 5; // 5 baris header custom

                        // === 1. Geser semua baris data ke bawah ===
                        rows.each(function() {
                            const r = parseInt($(this).attr('r'));
                            $(this).attr('r', r + offset);
                            $('c', this).each(function() {
                                const cellRef = $(this).attr('r');
                                const col = cellRef.replace(/[0-9]/g, '');
                                const row = parseInt(cellRef.replace(/[A-Z]/g,
                                    '')) + offset;
                                $(this).attr('r', col + row);
                            });
                        });

                        // === 2. Tambahkan merge cell untuk judul utama dan Spesifikasi Barang ===
                        let mergeCells = $('mergeCells', sheet);
                        if (mergeCells.length === 0) {
                            $('worksheet', sheet).append(
                                '<mergeCells count="2"><mergeCell ref="A1:J1"/><mergeCell ref="B5:C5"/></mergeCells>'
                            );
                        } else {
                            let count = parseInt(mergeCells.attr('count'));
                            mergeCells.attr('count', count + 2);
                            mergeCells.append('<mergeCell ref="A1:J1"/><mergeCell ref="B5:C5"/>');
                        }

                        // === 3. Tambahkan baris header baru (custom header) ===
                        const headerRows = `
                        <row r="1">
                            <c t="inlineStr" r="A1" s="51"><is><t>LAPORAN PEMELIHARAAN BARANG TAHUN ANGGARAN ${new Date().getFullYear()}</t></is></c>
                        </row>
                        <row r="2">
                            <c t="inlineStr" r="A2"><is><t>OPD</t></is></c>
                            <c t="inlineStr" r="B2"><is><t>DINAS PEKERJAAN UMUM DAN PENATAAN RUANG KOTA TEGAL</t></is></c>
                        </row>
                        <row r="3">
                            <c t="inlineStr" r="A3"><is><t>Tgl</t></is></c>
                            <c t="inlineStr" r="B3"><is><t>${$('#start_date').val()
                            ? new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }).format(new Date($('#start_date').val()))
                            : '-'
                        } s.d ${$('#end_date').val()
                            ? new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }).format(new Date($('#end_date').val()))
                            : '-'
                        }</t></is></c>
                        </row>
                        <row r="4">
                            <c t="inlineStr" r="A4"><is><t></t></is></c>
                        </row>
                        <row r="5">
                            <c t="inlineStr" r="A5"><is><t></t></is></c>
                            <c t="inlineStr" r="B5"><is><t>Spesifikasi Barang</t></is></c>
                        </row>
                        <row r="6">
                            <c t="inlineStr" r="A6"><is><t></t></is></c>
                            <c t="inlineStr" r="B6"><is><t>No. Kode Barang</t></is></c>
                            <c t="inlineStr" r="C6"><is><t>No Register</t></is></c>
                        </row>
                    `;
                        const styles = xlsx.xl['styles.xml'];
                        const fonts = $('fonts', styles);
                        fonts.attr('count', parseInt(fonts.attr('count')) + 1);
                        fonts.append(`
                    <font>
                        <b/>
                        <sz val="12"/>
                        <name val="Calibri"/>
                    </font>
                `);
                        const cellXfs = $('cellXfs', styles);
                        cellXfs.attr('count', parseInt(cellXfs.attr('count')) + 1);
                        cellXfs.append(`
                    <xf xfId="0" applyAlignment="1" fontId="${fonts.children().length - 1}">
                        <alignment horizontal="center"/>
                    </xf>
                `);
                        const boldCenterStyle = cellXfs.children().length - 1;


                        $('sheetData row:first', sheet).before(headerRows);

                        // === 4. Pastikan style "51" adalah Bold & Center (dari bawaan ExcelJS biasanya sudah) ===
                        // Jika tidak ada style 51 di hasil export, bisa tambahkan atau edit di xlsx.xl.styles['styles.xml']
                    }


                },
                {
                    extend: 'print',
                    footer: true,
                    text: '<i class="fa-solid fa-print"></i>',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    },
                    title: '',
                    customize: function(win) {
                        const tahun = new Date().getFullYear();
                        const tglRange =
                            `${$('#start_date').val() || '-'} s.d ${$('#end_date').val() || '-'}`;

                        $(win.document.body).prepend(`
                            <div style="margin-bottom: 20px;">
                                <h3 style="text-align: center;">LAPORAN PEMELIHARAAN BARANG TAHUN ANGGARAN ${tahun}</h3>
                                <p><strong>OPD:</strong> DINAS PEKERJAAN UMUM DAN PENATAAN RUANG KOTA TEGAL</p>
                                <p><strong>Tanggal:</strong> ${tglRange}</p>
                            </div>
                        `);

                        // Tambah baris header "Spesifikasi Barang"
                        $(win.document.body).find('thead tr').eq(0).before(`
                            <tr>
                                <th></th>
                                <th colspan="2" style="text-align:center; border:1px solid black;">Spesifikasi Barang</th>
                                <th colspan="7" style="border:1px solid black;"></th>
                            </tr>
                        `);

                        $(win.document.body).find('table').css('border-collapse', 'collapse');
                        $(win.document.body).find('table, th, td').css('border', '1px solid black');
                    }
                }
            ],
            footerCallback: function(row, data, start, end, display) {
                const api = this.api();
                const intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\.,]/g, '').replace(/[^\d\-]/g, '') * 1 :
                        typeof i === 'number' ? i : 0;
                };
                const totalNominal = api
                    .column(7, {
                        page: 'current'
                    })
                    .nodes()
                    .reduce(function(a, b) {
                        return a + intVal($(b).data('biaya'));
                    }, 0);

                $('#totalBiaya').html('Rp ' + totalNominal.toLocaleString('id-ID'));
            }
        });

        // === Filter berdasarkan tanggal input ===
        function filterByDate() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            $.fn.dataTable.ext.search.pop(); // reset filter
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const date = $(tablePemeliharaan.row(dataIndex).node()).find('td[data-tanggal]').data(
                    'tanggal');
                if (startDate && date < startDate) return false;
                if (endDate && date > endDate) return false;
                return true;
            });

            tablePemeliharaan.draw();
        }

        $('#start_date, #end_date').on('change', filterByDate);
    });
</script>