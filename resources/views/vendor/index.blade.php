@extends('layouts.template')

@section('content')
   <div class="card card-outline card-primary">
       <div class="card-header">
           <h3 class="card-title">{{ $page->title }}</h3>
           <div class="card-tools">
               <button onclick="modalAction('{{ url('/vendor/import') }}')" class="btn btn-info">
                   <i class="fa fa-file-import"></i> Import Vendor
               </button>
               <a href="{{ url('/vendor/export_excel') }}" class="btn btn-primary">
                   <i class="fa fa-file-excel"></i> Export Excel
               </a>
               <a href="{{ url('/vendor/export_pdf') }}" class="btn btn-warning">
                   <i class="fa fa-file-pdf"></i> Export PDF
               </a>
               <button onclick="modalAction('{{ url('/vendor/create_ajax') }}')" class="btn btn-success">
                   <i class="fa fa-plus"></i> Tambah Vendor
               </button>
           </div>
       </div>
       <div class="card-body">
           @if (session('success'))
               <div class="alert alert-success">{{ session('success') }}</div>
           @endif
           @if (session('error'))
               <div class="alert alert-danger">{{ session('error') }}</div>
           @endif
           <table class="table table-bordered table-striped table-hover table-sm" id="table_vendor">
               <thead>
                   <tr>
                       <th>No</th>
                       <th>Nama Vendor</th>
                       <th>Alamat</th>
                       <th>Kota</th>
                       <th>No. Telp</th>
                       <th>Alamat Web</th>
                       <th width="15%">Aksi</th>
                   </tr>
               </thead>
           </table>
       </div>
   </div>

   <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<style>
    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endpush

@push('js')
<script>
   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });

   function modalAction(url = '') {
    console.log('Called URL:', url); // debugging
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
            console.log('Sending request...'); // debugging
        },
        success: function(response) {
            console.log('Response:', response); // debugging
            $('#myModal').html(response);
            $('#myModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
            alert('Terjadi kesalahan: ' + error);
        }
    });
   }

   function handleDelete(url) {
       if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
           $.ajax({
               url: url,
               type: 'POST',
               data: {
                   '_method': 'DELETE',
                   '_token': $('meta[name="csrf-token"]').attr('content')
               },
               success: function(response) {
                   if(response.success) {
                       alert('Data berhasil dihapus');
                       dataVendor.ajax.reload();
                   } else {
                       alert('Gagal menghapus data');
                   }
               },
               error: function(xhr) {
                   alert('Error: ' + xhr.statusText);
               }
           });
       }
   }

   var dataVendor;

   $(document).ready(function() {
       dataVendor = $('#table_vendor').DataTable({
           processing: true,
           serverSide: true,
           ajax: {
               url: "{{ url('vendor/list') }}",
               type: "POST"
           },
           columns: [
               { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
               { data: 'vendor_nama', name: 'vendor_nama' },
               { data: 'alamat', name: 'alamat' },
               { data: 'kota', name: 'kota' },
               { data: 'no_telp', name: 'no_telp' },
               { 
                   data: 'alamat_web', 
                   name: 'alamat_web',
                   render: function(data) {
                       return '<a href="' + data + '" target="_blank">' + data + '</a>';
                   }
               },
               { 
                   data: 'aksi', 
                   name: 'aksi',
                   orderable: false, 
                   searchable: false,
                   render: function(data, type, row) {
                       return `
                           <button onclick="modalAction('${url('/vendor/show_ajax')}/${row.vendor_id}')" class="btn btn-info btn-sm">
                               <i class="fa fa-eye"></i>
                           </button>
                           <button onclick="modalAction('${url('/vendor/edit_ajax')}/${row.vendor_id}')" class="btn btn-warning btn-sm">
                               <i class="fa fa-edit"></i>
                           </button>
                           <button onclick="modalAction('${url('/vendor/confirm_ajax')}/${row.vendor_id}')" class="btn btn-danger btn-sm">
                               <i class="fa fa-trash"></i>
                           </button>
                       `;
                   }
               }
           ],
           order: [[1, 'asc']],
           language: {
               url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
           }
       });

      // Handle Create/Edit Form Submit
    $(document).on('submit', '#form-tambah, #form-edit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            beforeSend: function() {
                // Disable submit button
                form.find('button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                if(response.status) {
                    // Sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });
                    $('#myModal').modal('hide');
                    dataVendor.ajax.reload();
                } else {
                    // Gagal dengan pesan
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });
                    
                    // Clear error messages
                    $('.error-text').text('');
                    
                    // Show validation errors if any
                    if(response.msgField) {
                        $.each(response.msgField, function(field, message) {
                            $('#error-' + field).text(message[0]);
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                // Error handling
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan: ' + error
                });
                console.error(xhr.responseText);
            },
            complete: function() {
                // Re-enable submit button
                form.find('button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Handle Modal Action (untuk create dan edit)
    function modalAction(url) {
        $.ajax({
            url: url,
            type: 'GET',
            beforeSend: function() {
                // Bisa tambahkan loading indicator jika perlu
            },
            success: function(response) {
                $('#myModal').html(response);
                $('#myModal').modal('show');
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat form: ' + error
                });
            }
        });
    }

    // Handle Delete
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: "Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.status) {
                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            );
                            dataVendor.ajax.reload();
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endpush