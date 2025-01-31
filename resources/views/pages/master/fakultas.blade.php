@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Data Fakultas</h4>
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <table id="table-fakultas" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>NAMA FAKULTAS</th>
                                    <th>ALIAS</th>
                                    <th>NAMA DEKAN</th>
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
    <div class="modal fade fadeinUp" id="tambah-fakultas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Tambah Data Fakultas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-store">
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="nama">Nama Fakultas</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="alias">Alias</label>
                            <input type="text" class="form-control" name="alias">
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama_dekan">Nama Dekan</label>
                            <select name="nama_dekan" class="selectpicker d-block me-0 w-100" data-live-search="true" id="dosen-select">
                                <option value="-" selected disabled>-</option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
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
    <div class="modal fade fadeinUp" id="edit-fakultas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Edit Data Fakultas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-update">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="id_fakultas" name="id_fakultas">
                        <div class="form-group mb-4">
                            <label for="nama">Nama Fakultas</label>
                            <input type="text" class="form-control" name="nama" id="nama" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="alias">Alias</label>
                            <input type="text" class="form-control" name="alias" id="alias">
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama_dekan">Nama Dekan</label>
                            <select name="nama_dekan" class="selectpicker d-block me-0 w-100" data-live-search="true" id="nama_dekan">
                                <option value="">Pilih Dekan</option>
                                @foreach($dosen as $d)
                                    <option value="{{ $d->nidn }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Update">
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
            var table = $('#table-fakultas').DataTable({
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
                    url: `/${$('#role').text()}/fakultas`,
                    type: "GET"
                },
                buttons: [{
                    text: '<i class="fa-solid fa-plus mr-2"></i> Tambah Data',
                    className: 'btn btn-primary btn-tambah me-2',
                    action: function(e, dt, node, config) {
                        $('#tambah-fakultas').modal('show');
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
                        data: 'nama_fakultas'
                    },
                    {
                        targets: 2,
                        width: '20%',
                        className: 'text-center align-middle fs-14',
                        data: 'alias'
                    },
                    {
                        targets: 3,
                        width: '20%',
                        className: 'text-center align-middle fs-14',
                        data: 'dekan',
                        render: function(data, type, row, meta) {
                            if (data) {
                                return `${data} <br><span class="text-info">${row.nidn_dekan}</span>`;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        targets: 4,
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
                    $('#table-fakultas').DataTable().buttons().container().appendTo(
                        '#table-fakultas_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Update
            $('#table-fakultas tbody').on('click', '.btn-update', function() {
                var data = table.row($(this).parents('tr')).data();
                console.log(data);
                $('#id_fakultas').val(data.id)
                $('#nama').val(data.nama_fakultas)
                $('#alias').val(data.alias)
                $('#nama_dekan').val(data.nidn_dekan).change();
                $('#edit-fakultas').modal('show')
            });

            // Hapus
            $('#table-fakultas tbody').on('click', '.btn-hapus', function() {
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
                            url: `/${$('#role').text()}/fakultas`,
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
                                Swal.fire('Error!', 'Data tidak valid!', 'error')
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
                    url: `/${$('#role').text()}/fakultas`,
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#tambah-fakultas').modal('hide')
                        table.ajax.reload()
                        $('#form-store')[0].reset()
                        Swal.fire('Sukses!', 'Data diupdate', 'success')
                    },
                    error: function(response) {
                        Swal.hideLoading()
                        Swal.fire('Error!', 'Data tidak valid!', 'error')
                    }
                });

            });

            // Submit Update
            $('#form-update').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: `/${$('#role').text()}/fakultas`,
                    type: "PUT",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#edit-fakultas').modal('hide')
                        table.ajax.reload()
                        $('#form-update')[0].reset()
                        Swal.fire('Sukses!', 'Data diupdate', 'success')
                    },
                    error: function(response) {
                        Swal.hideLoading()
                        Swal.fire('Error!', 'Data tidak valid!', 'error')
                    }
                });
            });
        });
    </script>
@endsection
