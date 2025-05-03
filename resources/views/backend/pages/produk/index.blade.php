@extends('backend.master')

@section('content')

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Dashboard
                </div>
                <h2 class="page-title pb-2">
                    Data Produk
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('produk.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-xl mt-5">
    <div class="row row-deck row-cards">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Produk</h3>
            </div>
            <div class="card-body">
                <div id="table-default" class="table-responsive">
                    <table id="tabel_produk" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Harga</th>
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
   $(document).ready(function() {
    var table = $('#tabel_produk').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('produk.index') }}',
        },
        language: {
            "emptyTable": "Tidak ada data yang tersedia",
            "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
            "infoEmpty": "Menampilkan 0 hingga 0 dari 0 entri",
            "infoFiltered": "(terfilter dari _MAX_ total entri)",
            "paginate": {
                "previous": "Sebelumnya",
                "next": "Berikutnya"
            }
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1; // Nomor urut
                },
                name: 'nomor_urut',
                orderable: false,
                searchable: false
            },
            { data: 'id', name: 'id' },
            { data: 'nama', name: 'nama' },
            { data: 'jenis', name: 'jenis' },
            { data: 'status', name: 'status' },
            { data: 'harga', name: 'harga' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ]
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        }).then(function() {
            location.reload();  // Reload halaman setelah SweetAlert
        });
    @endif
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data produk ini akan dihapus permanen!",
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

</script>

@endsection
