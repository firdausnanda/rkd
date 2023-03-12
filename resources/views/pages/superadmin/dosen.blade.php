@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Data Dosen</h4>
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <table id="table-dosen" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>NIP/NIDN/NIDK</th>
                                    <th>NAMA</th>
                                    <th>PRODI</th>
                                    <th>JABATAN FUNGSIONAL</th>
                                    <th>STATUS PEGAWAI</th>
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
    <div class="modal fade fadeinUp" id="tambah-dosen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Tambah Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-store">
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="nidn">NIP/NIDN/NIDK</label>
                            <input type="text" class="form-control" name="nidn" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama">
                        </div>
                        <div class="form-group mb-4">
                            <label for="password">Prodi</label>
                            <select class="selectpicker form-control" data-live-search="true" name="prodi">
                                <option value="-">-</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="jabfung">Jabatan Fungsional</label><br>
                            <select class="selectpicker form-control" data-live-search="true" name="jabfung">
                                <option value="-">-</option>
                                <option value="Asisten Ahli">Asisten Ahli</option>
                                <option value="Lektor">Lektor</option>
                                <option value="Lektor Kepala">Lektor Kepala</option>
                                <option value="Guru Besar">Guru Besar</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="status">Status</label><br>
                            <select class="selectpicker form-control" data-live-search="true" name="status">
                                <option value="-">-</option>
                                <option value="Tetap">Tetap</option>
                                <option value="Tidak Tetap">Tidak Tetap</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan">
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
    <div class="modal fade fadeinUp" id="edit-dosen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Edit Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-update">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="id_dosen" name="id_dosen">
                        <div class="form-group mb-4">
                            <label for="nidn">NIP/NIDN/NIDK</label>
                            <input type="text" class="form-control" name="nidn" id="nidn" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" id="nama" id="nama">
                        </div>
                        <div class="form-group mb-4">
                            <label for="password">Prodi</label>
                            <select class="selectpicker form-control" data-live-search="true" name="prodi"
                                id="prodi">
                                <option value="-">-</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="jabfung">Jabatan Fungsional</label><br>
                            <select class="selectpicker form-control" data-live-search="true" name="jabfung"
                                id="jabfung">
                                <option value="-">-</option>
                                <option value="Asisten Ahli">Asisten Ahli</option>
                                <option value="Lektor">Lektor</option>
                                <option value="Lektor Kepala">Lektor Kepala</option>
                                <option value="Guru Besar">Guru Besar</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="status">Status</label><br>
                            <select class="selectpicker form-control" data-live-search="true" name="status"
                                id="status">
                                <option value="-">-</option>
                                <option value="Tetap">Tetap</option>
                                <option value="Tidak Tetap">Tidak Tetap</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" id="keterangan">
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
            var table = $('#table-dosen').DataTable({
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
                    url: "{{ route('superadmin.dosen.index') }}",
                    type: "GET"
                },
                buttons: [{
                    text: '<i class="fa-solid fa-plus mr-2"></i> Tambah Data',
                    className: 'btn btn-primary btn-tambah me-2',
                    action: function(e, dt, node, config) {
                        $('#tambah-dosen').modal('show');
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
                        data: 'nidn'
                    },
                    {
                        targets: 2,
                        width: '20%',
                        className: 'text-center align-middle fs-14',
                        data: 'nama'
                    },
                    {
                        targets: 3,
                        width: '15%',
                        className: 'text-center align-middle fs-14',
                        data: 'prodi.nama_prodi'
                    },
                    {
                        targets: 4,
                        width: '10%',
                        className: 'text-center align-middle fs-14',
                        data: 'jabatan_fungsional',
                        render: function(data, type, row, meta) {
                            if (data == '' || data == null) {
                                return `-`
                            }
                            return `${data}`
                        }
                    },
                    {
                        targets: 5,
                        width: '10%',
                        className: 'text-center align-middle fs-14',
                        data: 'status'
                    },
                    {
                        targets: 6,
                        width: '10%',
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
                        targets: 7,
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
                                        <a class="dropdown-item btn-update" href="#">Update</a>
                                        <a class="dropdown-item btn-aktif" href="#">${aktif}</a>
                                        </div>
                                    </div>`
                        }
                    }
                ],
                initComplete: function() {
                    $('#table-dosen').DataTable().buttons().container().appendTo(
                        '#table-dosen_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Update
            $('#table-dosen tbody').on('click', '.btn-update', function() {
                var data = table.row($(this).parents('tr')).data();

                $('#id_dosen').val(data.id)
                $('#nidn').val(data.nidn)
                $('#nama').val(data.nama)
                $('#prodi').val(data.id_prodi).change()
                $('#jabfung').val(data.jabatan_fungsional).change()
                $('#status').val(data.status).change()
                $('#keterangan').val(data.keterangan)
                $('#edit-dosen').modal('show')
            });

            // Aktif Non Aktif Akun
            $('#table-dosen tbody').on('click', '.btn-aktif', function() {
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
                            url: "{{ route('superadmin.dosen.aktif') }}",
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

            // Submit Store
            $('#form-store').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "{{ route('superadmin.dosen.store') }}",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#tambah-dosen').modal('hide')
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
                    url: "{{ route('superadmin.dosen.update') }}",
                    type: "PUT",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#edit-dosen').modal('hide')
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
