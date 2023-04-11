@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Data Akun</h4>
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <table id="table-akun" class="table     " style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>USERNAME</th>
                                    <th>EMAIL/NIDN</th>
                                    <th>ROLE</th>
                                    <th>PRODI</th>
                                    <th>STATUS</th>
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
    <div class="modal fade fadeinUp" id="TambahDataAkun" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Tambah Data Akun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-store">
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="name">Username</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Email / NIDN</label>
                            <input type="text" class="form-control" name="email" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="role">Role</label><br>
                            <select class="selectpicker form-control" data-live-search="true" id="role_s" name="role"
                                required>
                                @foreach ($role as $r)
                                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="prodi_store" class="form-group mb-4 d-none">
                            <label for="prodi">Prodi</label><br>
                            <select class="selectpicker form-control" data-live-search="true" id="prodi_s" name="prodi">
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->kode_prodi }}">{{ $p->nama_prodi }}</option>
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
    <div class="modal fade fadeinUp" id="EditDataAkun" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Edit Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-update">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="id_akun" name="id_akun">
                        <div class="form-group mb-4">
                            <label for="name">Username</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Email/NIDN</label>
                            <input type="text" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group mb-4">
                            <label for="role">Role</label><br>
                            <select class="selectpicker form-control" data-live-search="true" id="role"
                                name="role">
                                @foreach ($role as $r)
                                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="prodi_edit" class="form-group mb-4 d-none">
                            <label for="prodi">Prodi</label><br>
                            <select class="selectpicker form-control" data-live-search="true" id="prodi"
                                name="prodi">
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->kode_prodi }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
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

    <!-- Modal Reset Password -->
    <div class="modal fade" id="ResetPassword" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Reset Password</h5>
                </div>
                <form id="form-reset">
                    <div class="modal-body">
                        <input type="hidden" id="id">
                        <div class="form-group mb-4">
                            <label for="password-new"></label>
                            <input type="password" class="form-control" id="password-old" name="password-old">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
            var table = $('#table-akun').DataTable({
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
                    url: "{{ route('superadmin.akun.index') }}",
                    type: "GET"
                },
                buttons: [{
                    text: '<i class="fa-solid fa-plus mr-2"></i> Tambah Data',
                    className: 'btn btn-primary btn-tambah me-2',
                    action: function(e, dt, node, config) {
                        $('#TambahDataAkun').modal('show');
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
                        width: '20%',
                        className: 'text-center align-middle',
                        data: 'name'
                    },
                    {
                        targets: 2,
                        width: '10%',
                        className: 'text-center align-middle fs-14',
                        data: 'email'
                    },
                    {
                        targets: 3,
                        width: '15%',
                        className: 'text-center align-middle fs-14',
                        render: function(data, type, row, meta) {
                            return `${row.roles[0].name}`
                        }
                    },
                    {
                        targets: 4,
                        width: '20%',
                        className: 'text-center align-middle fs-14',
                        data: 'kode_prodi',
                        render: function(data, type, row, meta) {
                            if (data == '' || data == null) {
                                return `-`
                            }
                            return `${row.prodi.nama_prodi}`
                        }
                    },
                    {
                        targets: 5,
                        width: '15%',
                        className: 'text-center align-middle fs-14',
                        data: 'is_active',
                        render: function(data, type, row, meta) {
                            if (data == 1) {
                                return `<h5><span class="badge badge-success">Aktif</span></h5>`
                            }
                            return `<h5><span class="badge badge-danger">Non Aktif</span></h5>`
                        }
                    },
                    {
                        targets: 6,
                        width: '15%',
                        className: 'text-center align-middle',
                        data: 'is_active',
                        render: function(data, type, row, meta) {

                            if (data == 1) {
                                var aktif = 'Non - Aktifkan'
                            } else {
                                var aktif = 'Aktifkan'
                            }

                            return `<div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aksi <i class="fa-solid fa-chevron-down ml-2"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <a class="dropdown-item btn-update" href="#">Update Profile</a>
                                        <a class="dropdown-item btn-reset" href="#">Reset Password</a>
                                        <a class="dropdown-item btn-aktif" href="#">${aktif}</a>
                                        </div>
                                    </div>`
                        }
                    }
                ],
                initComplete: function() {
                    $('#table-akun').DataTable().buttons().container().appendTo(
                        '#table-akun_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Update
            $('#table-akun tbody').on('click', '.btn-update', function() {
                var data = table.row($(this).parents('tr')).data();

                $('#id_akun').val(data.id)
                $('#name').val(data.name)
                $('#email').val(data.email)
                $('#EditDataAkun #role').val(data.roles[0].name).change()
                if (data.kode_prodi != '' || data.kode_prodi != null) {
                    $('#prodi').val(data.prodi)
                }
                $('#EditDataAkun').modal('show')
            });

            // Reset Password
            $('#table-akun tbody').on('click', '.btn-reset', function() {
                var data = table.row($(this).parents('tr')).data();

                $('#id').val(data.id)
                $('#ResetPassword').modal('show')
            });

            // Aktif Non Aktif Akun
            $('#table-akun tbody').on('click', '.btn-aktif', function() {
                var data = table.row($(this).parents('tr')).data();

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Akun akan dinonaktifkan/aktifkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "PUT",
                            url: "{{ route('superadmin.akun.aktif') }}",
                            data: {
                                id: data.id,
                                aktif: data.is_active
                            },
                            dataType: "JSON",
                            beforeSend: function() {
                                Swal.showLoading()
                            },
                            success: function(response) {
                                Swal.hideLoading()
                                table.ajax.reload()
                                Swal.fire('Sukses!', 'Data diupdate', 'success')
                            },
                            error: function(response) {
                                Swal.hideLoading()
                                Swal.fire('Error!', 'Server Error', 'error')
                            }
                        });
                    }
                })
            });

            // Change Role Update
            $('#EditDataAkun #role').change(function(e) {
                e.preventDefault();
                if ($('#EditDataAkun #role').val() == 'prodi') {
                    $('#prodi_edit').addClass('d-block').removeClass('d-none')
                } else {
                    $('#prodi_edit').addClass('d-none').removeClass('d-block')
                }
            });

            // Change Role Store
            $('#TambahDataAkun #role_s').change(function(e) {
                e.preventDefault();
                if ($('#TambahDataAkun #role_s').val() == 'prodi') {
                    $('#prodi_store').addClass('d-block')
                } else {
                    $('#prodi_store').addClass('d-none').removeClass('d-block')
                }
            });

            // Submit Store
            $('#form-store').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('superadmin.akun.store') }}",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#TambahDataAkun').modal('hide')
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
                    url: "{{ route('superadmin.akun.update') }}",
                    type: "PUT",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#EditDataAkun').modal('hide')
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

            // Submit Reset Password
            $('#form-reset').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "PUT",
                    url: "{{ route('superadmin.akun.reset') }}",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#ResetPassword').modal('hide')
                        table.ajax.reload()
                        $('#form-reset')[0].reset()
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
