@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Data Program Studi</h4>
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <table id="table-prodi" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>KODE PRODI</th>
                                    <th>NAMA PRODI</th>
                                    <th class="no-content"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Modal Tambah Data --}}
    <div class="modal fade fadeinUp" id="tambah-prodi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Tambah Data Prodi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-store">
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="kode">Kode Prodi</label>
                            <input type="text" class="form-control" name="kode" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama">Nama Prodi</label>
                            <input type="text" class="form-control" name="nama">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Save">
                        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Data --}}
    <div class="modal fade fadeinUp" id="edit-prodi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Edit Data Prodi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-update">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="id_prodi" name="id_prodi">
                        <div class="form-group mb-4">
                            <label for="kode">Kode Prodi</label>
                            <input type="text" class="form-control" name="kode" id="kode" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama">Nama Prodi</label>
                            <input type="text" class="form-control" name="nama" id="nama">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Edit">
                        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // Init Datatable
            var table = $('#table-prodi').DataTable({
                oLanguage: {
                    "oPaginate": {
                        "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Search...",
                    "sLengthMenu": "Results :  _MENU_",
                    "infoFiltered": " - filtered from _MAX_ records",
                },
                ordering: false,
                processing: true,
                lengthChange: false,
                ajax: {
                    url: `/${$('#role').text()}/prodi`,
                    type: "GET"
                },
                buttons: [{
                    text: '<i class="fa-solid fa-plus mr-2"></i> Tambah Data',
                    className: 'btn btn-primary btn-tambah me-2',
                    action: function(e, dt, node, config) {
                        $('#tambah-prodi').modal('show');
                    }
                }],
                columnDefs: [{
                        targets: 0,
                        width: '5%',
                        className: 'text-center align-middle',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        targets: 1,
                        width: '10%',
                        className: 'text-center align-middle',
                        data: 'kode_prodi'
                    },
                    {
                        targets: 2,
                        width: '20%',
                        className: 'text-center align-middle fs-14',
                        data: 'nama_prodi'
                    },
                    {
                        targets: 3,
                        width: '15%',
                        className: 'text-center align-middle',
                        render: function(data, type, row, meta) {

                            return `<div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi <i class="fa-solid fa-chevron-down ml-2"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item btn-update" href="#">Update</a>
                                        <a class="dropdown-item btn-hapus" href="#">Delete</a>
                                        </div>
                                    </div>`
                        }
                    }
                ],
                initComplete: function() {
                    $('#table-prodi').DataTable().buttons().container().appendTo(
                        '#table-prodi_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Update
            $('#table-prodi tbody').on('click', '.btn-update', function() {
                var data = table.row($(this).parents('tr')).data();

                $('#id_prodi').val(data.id)
                $('#kode').val(data.kode_prodi)
                $('#nama').val(data.nama_prodi)
                $('#edit-prodi').modal('show')
            });

            // Hapus
            $('#table-prodi tbody').on('click', '.btn-hapus', function() {
                var data = table.row($(this).parents('tr')).data();

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: `/${$('#role').text()}/prodi`,
                            data: {
                                id: data.id
                            },
                            dataType: "JSON",
                            beforeSend: function() {
                                Swal.showLoading()
                            },
                            success: function(response) {
                                Swal.hideLoading()
                                table.ajax.reload()
                                Swal.fire('Sukses!', 'Data dihapus', 'success')
                            },
                            error: function(response) {
                                Swal.hideLoading()
                                Swal.fire('Error!', 'Server Error', 'error')
                            }
                        });
                    }
                })
            });

            // Submit Store
            $('#form-store').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: `/${$('#role').text()}/prodi`,
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#tambah-prodi').modal('hide')
                        table.ajax.reload()
                        $('#form-store')[0].reset()
                        Swal.fire('Sukses!', 'Data diupdate', 'success')
                    },
                    error: function(response) {
                        Swal.hideLoading()
                        Swal.fire('Error!', 'Server Error', 'error')
                    }
                });

            });

            // Submit Update
            $('#form-update').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: `/${$('#role').text()}/prodi`,
                    type: "PUT",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#edit-prodi').modal('hide')
                        table.ajax.reload()
                        $('#form-update')[0].reset()
                        Swal.fire('Sukses!', 'Data diupdate', 'success')
                    },
                    error: function(response) {
                        Swal.hideLoading()
                        Swal.fire('Error!', 'Server Error', 'error')
                    }
                });
            });
        });
    </script>
@endsection
