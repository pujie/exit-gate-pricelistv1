<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products (Data Odoo)') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables-buttons/css/buttons.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/padi/common.css')}}">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <table id="odooTable" class="stripe table hover">
            <thead>
                <tr><th>ID</th><th>Kode</th><th>Nama</th><th>Harga</th></tr>
            </thead>
            <tbody></tbody>
        </table>
        <input id="fileInput" type="file" style="display:none;" />

    </div>
    <script src="{{ asset('assets/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.js') }}"></script>
    <script src="{{ asset('assets/datatables-buttons/js/dataTables.buttons.js') }}"></script>
    <script src="{{ asset('assets/datatables-buttons/js/buttons.html5.js') }}"></script>
    <script src="{{ asset('assets/jszip/jszip.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#odooTable').DataTable({
                ajax:{"url": "/products"}, // Memanggil route JSON kita
                responsive: true, // Wajib untuk PWA/Mobile
                processing: true,
                pageLength: 10,
                dom:"Bfrtip",
                columns: [
                    { data: 'id' },
                    { data: 'code' },
                    { data: 'name' },
                    { data: 'price' },
                ],
                language: {
                    search: "Cari Produk:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ produk",
                    paginate: {
                        next: "Lanjut",
                        previous: "Kembali"
                    }
                },
                buttons:[
                    {
                        extend: 'excelHtml5',
                        text: 'Unduh Spreadsheet',
                        className: 'btn-export',
                        exportOptions: {
                            columns: [ 0, 1, 2 ]
                        },
                    },
                ],

            });
        });
    </script>
    <script>
        $('#fileInput').on('change', function() {
            let formData = new FormData();
            formData.append('file', $(this)[0].files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('import.excel') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert('Data berhasil diimport!');
                    $('#myTable').DataTable().ajax.reload(); // Refresh tabel
                },
                error: function(err) {
                    alert('Terjadi kesalahan saat upload.');
                    console.log('Terjadi kesalahan saat upload.',err);
                }
            });
        });
    </script>
</x-app-layout>
