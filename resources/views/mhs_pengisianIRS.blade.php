<!-- resources/views/dashboard/mahasiswa.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengisian Rencana Studi - SIP-IRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Add DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    <!-- CSS dan JS dari public -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" type="text/css">
    <script type="text/javascript" src="{{ asset('js/javascript.js') }}"></script>
    <style>
        /* Tabel IRS */
        /* Mengubah warna header tabel */
        

        .table thead th {
            background-color: #FED488; /* Sesuaikan warna header */
            color: black; /* Teks putih */
            font-family: 'Poppins';
            text-align: center; /* Menengahkan teks */
            font-size: 12px;
        }

        .table tbody td {
            color: black; /* Teks putih */
            font-family: 'Poppins';
            text-align: center; /* Menengahkan teks */
            font-size: 12px;
        }

        /* Menambahkan roundness pada tabel */
        .table {
            border-radius: 10px; /*Sesuaikan besar roundness*/
            overflow: hidden; /*Menghindari isi tabel keluar dari roundness */
            table-layout: fixed; /* Ukuran kolom tetap */
            width: 100%; /* Pastikan tabel mengambil seluruh lebar kontainer */
            padding: 10px;
        }
        
        .table th, .table td {
            word-wrap: break-word; /* Agar teks yang panjang tidak melar keluar kolom */
            text-align: center; /* Pusatkan teks */
        }

        /* Roundness untuk header */
        /* .table thead th:first-child {
            border-top-left-radius: 10px;
        } */
        /* .table thead th:last-child {
            border-top-right-radius: 10px;
        } */
        
        /* Roundness untuk footer jika dibutuhkan */
        .table tfoot td:first-child {
            border-bottom-left-radius: 10px;
        }
        .table tfoot td:last-child {
            border-bottom-right-radius: 10px;
        }
        .button-group-right {
            display: flex;
            justify-content: flex-end;
            gap: 10px; /* Jarak antar tombol */
            margin-top: 15px; /* Jarak dari elemen atas */
            margin-bottom: 15px;
            margin-right: 15px; 
        }
        .button-group-tabel {
            display: flex;
            justify-content: center;
            gap: 5px; /* Jarak antar tombol */
            /* margin-top: 15px; /* Jarak dari elemen atas */
            /* margin-bottom: 15px;
            margin-right: 15px;  */ */
        }

        /* Adjust search bar size */
        .searchInput {
            max-width: 300px; /* Sesuaikan ukuran maksimal */
        }

        /* Optional: Add spacing between buttons */
        .input-group .btn {
            margin-left: 5px; /* Tambahkan jarak antar tombol */
        }

        .card-body {
            /* overflow-x: auto; Agar tabel tidak keluar dari card */
            padding: 15px; /* Tambahkan padding agar terlihat rapi */
            width: auto; /* Sesuaikan lebar dengan konten */
            max-width: auto; /* Pastikan tidak melebihi layar */
            box-sizing: border-box; /* Hitung padding dalam ukuran elemen */
        }

        /* Card styling dengan margin */
        .card {
            margin: 5px; /* Berikan margin 10px di sekeliling card */
            width: auto; /* Pastikan mengikuti ukuran konten */
            max-width: 90%; /* Agar tidak melampaui lebar layar */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Tambahkan sedikit bayangan untuk estetika */
        }
        /* Styling untuk DataTables */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #027683 !important;
            color: white !important;
            border: 1px solid #027683 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #67C3CC !important;
            color: white !important;
            border: 1px solid #67C3CC !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .dataTables_wrapper .dataTables_paginate {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            margin-top: 10px;
        }

        /* Table responsive tanpa geser */
        .table-responsive {
            overflow-x: auto;
            max-width: 100%; /* Agar tabel tetap berada dalam kontainer */
        }   
        

    </style> 
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- untuk manggil komponen sidebar -->
        <x-sidebar-mahasiswa :mahasiswa="$mahasiswa"></x-sidebar-mahasiswa>
        <!-- Wave decoration -->
        <div class="wave-decoration"> 
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 35%; width: 35%;">
                <path d="M0.00,49.98 C150.00,150.00 349.20,-49.00 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #fff;"></path>
            </svg>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                {{-- <div>
                    <h1>{{$Periode_sekarang->jenis}}</h1>
                </div> --}}
            </div>

            <!-- Pengisian IRS Cards -->
            <div class="col-12">
                <div class="card shadow-sm h-100">
                <h5 class="card-header" style="background-color: #027683; color: white;">Pengisian Rencana Studi - Daftar Jadwal Kuliah</h5>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="margincard">
                                <div class="fw-bold" style="font-size: 12px;">MAX BEBAN SKS</div>
                                <span class="badge irs-badge" style="background-color: #67C3CC;">0 SKS</span>
                            </div>
                            <div class="margincard" style="margin-left: 10px;">
                                <div class="fw-bold" style="font-size: 12px;">TOTAL SKS</div>
                                <span class="badge irs-badge" style="background-color: #67C3CC;">0 SKS</span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mt-2">
                        <!-- Search bar -->
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari Mata Kuliah" aria-label="Search" aria-describedby="button-addon2" style="max-width: 250px;max-height:40px">
                        <button class="btn" style="background-color: #6878B1; color:#fff;max-width: 250px;max-height:40px" type="button" id="button-addon2">
                            <span class="material-icons">search</span>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="semesterFilter" data-bs-toggle="dropdown" aria-expanded="false">
                                Filter Semester
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="semesterFilter">
                                <li><button class="dropdown-item" id="semua">Semua Semester</button></li>
                                <li><button class="dropdown-item" id="1">Semester 1</button></li>
                                <li><button class="dropdown-item" id="2">Semester 2</button></li>
                                <li><button class="dropdown-item" id="3">Semester 3</button></li>
                                <li><button class="dropdown-item" id="4">Semester 4</button></li>
                                <li><button class="dropdown-item" id="5">Semester 5</button></li>
                                <li><button class="dropdown-item" id="6">Semester 6</button></li>
                                <li><button class="dropdown-item" id="7">Semester 7</button></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button class="dropdown-item" id="pilihan">Pilihan</button></li>
                            </ul>                         
                        </div>
                    </div>
                    <div class="banner text-center mt-2 rounded-top" 
                        style="background-color: #027683; 
                                color: white; 
                                height: 40px; /* Tinggi banner */
                                display: flex; /* Mengaktifkan Flexbox */
                                justify-content: center; /* Pusat horizontal */
                                align-items: center; /* Pusat vertikal */ 
                                font-size: 18px;">
                        <span class="fw-medium">Daftar Jadwal Kuliah</span>
                    </div>
                   
                    <div class="table-responsive">
                        <table class="table table-bordered" id="jadwalTable">
                            <thead>
                                <tr>
                                    <th style="width: 3rem;">No</th>
                                    <th style="width: 5rem;">Kode MK</th>
                                    <th style="width: 6rem;">Mata Kuliah</th>
                                    <th style="width: 5rem;">Semester</th>
                                    <th style="width: 4rem;">Kelas</th>
                                    <th style="width: 4rem;">SKS</th>
                                    <th style="width: 4rem;">Ruang</th>
                                    <th style="width: 5rem;">Hari</th>
                                    <th style="width: 6rem;">Jam Mulai</th>
                                    <th style="width: 6rem;">Jam Selesai</th>
                                    <th style="width: 4rem;">Kuota</th>
                                    <th style="width: 10rem;">Aksi</th>
                                </tr>
                            </thead>                
                            <tbody>
                                @foreach($jadwalKuliah as $index => $jadwal)
                                    <tr data-semester="{{ $jadwal->semester }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $jadwal->kode_matkul }}</td>
                                        <td>{{ $jadwal->nama_matkul }}</td>
                                        <td>{{ $jadwal->semester }}</td>
                                        <td>{{ $jadwal->kelas }}</td>
                                        <td>{{ $jadwal->sks }}</td>
                                        <td>{{ $jadwal->namaruang }}</td>
                                        <td>{{ $jadwal->hari }}</td>
                                        <td>{{ $jadwal->jam_mulai }}</td>
                                        <td>{{ $jadwal->jam_selesai }}</td>
                                        <td>{{ $jadwal->kuota_terisi }} / {{ $jadwal->kuota }}</td>
                                        <td>
                                            <div class="button-group-tabel">
                                                @if (!$jadwalStatus[$jadwal->id_jadwal]['sudah_diambil_jadwal'] && !$jadwalStatus[$jadwal->id_jadwal]['sudah_diambil_matkul'])
                                                    <form class="ambil-jadwal-form">
                                                        @csrf
                                                        <input type="hidden" name="id_jadwal" value="{{ $jadwal->id_jadwal }}">
                                                        <input type="hidden" name="status" value="draft">
                                                        <button type="submit" 
                                                                class="btn btn-primary mb-2 rounded-3 ambil-btn"
                                                                style="color:white; background-color: #028391; border-color: #028391; font-size: 15px; padding: 5px 10px;">
                                                            Ambil
                                                        </button>
                                                    </form>
                                                @elseif ($jadwalStatus[$jadwal->id_jadwal]['sudah_diambil_matkul'])
                                                    <button class="btn btn-secondary mb-2 rounded-3"
                                                            onclick="swal('Mata Kuliah Sudah Diambil', 'Anda tidak dapat mengambil mata kuliah yang sama lebih dari satu kali.', 'warning')"
                                                            style="background-color: #ccc; font-size: 15px; padding: 5px 10px;">
                                                        Terambil 
                                                    </button>
                                                @else
                                                    <button class="btn btn-secondary mb-2 rounded-3"
                                                            style="background-color: #ccc; font-size: 15px; padding: 5px 10px;">
                                                        Terambil 
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach                                
                            </tbody>
                        </table>
                    </div>
        <div class="button-group-right">
            <div class="button-group-right">
                <a href="{{ route('mhs_newIRS') }}" class="btn" style="color:white; background-color:#FFB939">Keluar</a>
            </div>
            <div class="button-group-right">
                <a href="{{ route('mhs_draftIRS') }}" class="btn" style="color: white; background-color: #6878B1">Lanjutkan</a>
            </div>
        </div>
        </div>
    </div>
  </div>
        </div>
    </div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#jadwalTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json',
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Tidak ada data yang tersedia",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
    });

    // Search functionality
    $('#button-addon2').on('click', function() {
        table.search($('#searchInput').val()).draw();
    });

    $('#searchInput').on('keyup', function(e) {
        if (e.key === 'Enter') {
            table.search($(this).val()).draw();
        }
    });

    // Semester Filter
    $('.dropdown-item').on('click', function() {
        var selectedSemester = $(this).attr('id');
        
        // Update dropdown button text
        $('#semesterFilter').html($(this).text() + ' <span class="caret"></span>');
        
        if (selectedSemester === 'semua' || selectedSemester === 'pilihan') {
            // Jika pilih "Semua Semester" atau "Pilihan", tampilkan semua
            table.column(3).search('').draw();
        } else {
            // Filter berdasarkan semester
            table.column(3).search('^' + selectedSemester + '$', true, false).draw();
        }
    });

    // Optional: Kombinasi search dan filter semester
    $('#searchInput').on('keyup', function() {
        var searchValue = $(this).val();
        var currentSemester = $('#semesterFilter').text().trim().replace(' ', '');
        
        // Jika semester sudah dipilih
        if (currentSemester !== 'FilterSemester' && currentSemester !== 'SemuaSemester') {
            table
                .column(3)
                .search('^' + currentSemester.replace('Semester', '') + '$', true, false)
                .column(2)
                .search(searchValue)
                .draw();
        } else {
            // Jika belum pilih semester, hanya search
            table.search(searchValue).draw();
        }
    });
});
</script>

{{-- Menangani searching
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        // Ambil nilai input dan ubah ke huruf kecil untuk pencarian tidak case-sensitive
        const searchValue = this.value.toLowerCase();

        // Ambil semua baris tabel di tbody
        const rows = document.querySelectorAll('#irsTable tr');

        // Loop melalui setiap baris untuk mencocokkan nilai
        rows.forEach(row => {
            // Ambil kolom "Mata Kuliah" (kolom ketiga, indeks ke-2)
            const mataKuliah = row.cells[2].textContent.toLowerCase();

            // Jika teks kolom mengandung nilai pencarian, tampilkan baris
            if (mataKuliah.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
{{-- Filter Semester--}}
{{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
    const semesterDropdownItems = document.querySelectorAll('.dropdown-item');
    const jadwalTableRows = document.querySelectorAll('#jadwalTable tbody tr');

    semesterDropdownItems.forEach(item => {
        item.addEventListener('click', function() {
            const selectedSemester = item.getAttribute('data-semester'); // Assuming this is the selected semester

            jadwalTableRows.forEach(row => {
                const rowSemester = row.getAttribute('data-semester'); 
                if (selectedSemester === 'pilihan' || rowSemester === selectedSemester) {
                    row.style.display = ''; // Show row
                } else {
                    row.style.display = 'none'; // Hide row
                }
            });
        });
    });
}); --}} 
{{-- </script> --}}

<div class="d-flex justify-content-center mt-3">
    <nav aria-label="Page navigation">
        <ul class="pagination" id="pagination">
            <!-- Pagination buttons will be dynamically inserted here -->
        </ul>
    </nav>
</div>