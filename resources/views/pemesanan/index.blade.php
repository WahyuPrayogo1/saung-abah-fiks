@extends('backend.master')

@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Dashboard</div>
                <h2 class="page-title pb-2">Data Pemesanan</h2>
            </div>
        </div>
    </div>
</div>

<div class="container-xl mt-5">
    <div class="row row-deck row-cards">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Pemesanan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabel_pemesanan" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Meja</th>
                                <th>Produk Dipesan</th>
                                <th>Total Harga</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#tabel_pemesanan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('pemesanans.table') }}',
                type: 'GET'
            },
            language: {
                emptyTable: "Tidak ada data tersedia",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'meja',
                    name: 'meja.nama'
                },
                {
                    data: 'produk',
                    name: 'produk',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'total_harga',
                    name: 'total_harga',
                    render: function (data) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'pembayaran',
                    name: 'pembayaran',
                    render: function(data) {
                        return data === 'online' ?
                            '<span class="badge bg-blue text-blue-fg">Online</span>' :
                            '<span class="badge bg-cyan text-cyan-fg">Kasir</span>';
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data) {
                        let badgeClass = 'badge';
                        if (data === 'completed') badgeClass = 'bg-blue text-blue-fg';
                        if (data === 'cancelled') badgeClass = 'bg-red text-red-fg';
                        if (data === 'pending') badgeClass = 'bg-yellow text-yellow-fg';

                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 2000
        });
        @endif
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data pemesanan akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Fungsi format ke Rupiah
    function formatRupiah(angka) {
        if (!angka) return 'Rp 0';
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }
</script>

@endsection
