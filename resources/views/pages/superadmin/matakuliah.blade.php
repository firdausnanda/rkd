@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Data Matakuliah</h4>
                    </div>

                    {{-- Filter --}}
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label for="prodi">Prodi</label>
                            <select class="form-control" id="prodi-select">
                                <option value="-">-</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->kode_prodi }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <table id="table-matakuliah" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>KODE MATAKULIAH</th>
                                    <th>NAMA MATAKULIAH</th>
                                    <th>SKS</th>
                                    <th>PRODI</th>
                                    <th>KURIKULUM</th>
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
    <div class="modal fade fadeinUp" id="tambah-matakuliah" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Tambah Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-store">
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="kode_matkul">Kode Matakuliah</label>
                            <input type="text" class="form-control" name="kode_matkul" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama">Nama Matakuliah</label>
                            <input type="text" class="form-control" name="nama">
                        </div>
                        <div class="form-group mb-4">
                            <label for="sks">SKS</label>
                            <input type="text" class="form-control" name="sks">
                        </div>
                        <div class="form-group mb-4">
                            <label for="t">SKS Teori (T)</label>
                            <input type="text" class="form-control" name="t">
                        </div>
                        <div class="form-group mb-4">
                            <label for="p">SKS Praktek (P)</label>
                            <input type="text" class="form-control" name="p">
                        </div>
                        <div class="form-group mb-4">
                            <label for="k">SKS Klinik (K)</label>
                            <input type="text" class="form-control" name="k">
                        </div>
                        <div class="form-group mb-4">
                            <label for="kurikulum">Kurikulum</label>
                            <input type="text" class="form-control" name="kurikulum">
                        </div>
                        <div class="form-group mb-4">
                            <label for="prodi">Prodi</label>
                            <select class="selectpicker form-control" data-live-search="true" name="prodi">
                                <option value="-">-</option>
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
    <div class="modal fade fadeinUp" id="edit-matakuliah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Edit Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-update">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="id_matakuliah" name="id_matakuliah">
                        <div class="form-group mb-4">
                            <label for="kode_matkul">Kode Matakuliah</label>
                            <input type="text" class="form-control" name="kode_matkul" id="kode_matkul" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama">Nama Matakuliah</label>
                            <input type="text" class="form-control" name="nama" id="nama">
                        </div>
                        <div class="form-group mb-4">
                            <label for="sks">SKS</label>
                            <input type="text" class="form-control" name="sks" id="sks">
                        </div>
                        <div class="form-group mb-4">
                            <label for="t">SKS Teori (T)</label>
                            <input type="text" class="form-control" name="t" id="t">
                        </div>
                        <div class="form-group mb-4">
                            <label for="p">SKS Praktek (P)</label>
                            <input type="text" class="form-control" name="p" id="p">
                        </div>
                        <div class="form-group mb-4">
                            <label for="k">SKS Klinik (K)</label>
                            <input type="text" class="form-control" name="k" id="k">
                        </div>
                        <div class="form-group mb-4">
                            <label for="kurikulum">Kurikulum</label>
                            <input type="text" class="form-control" name="kurikulum" id="kurikulum">
                        </div>
                        <div class="form-group mb-4">
                            <label for="prodi">Prodi</label>
                            <select class="selectpicker form-control" data-live-search="true" name="prodi"
                                id="prodi">
                                <option value="-">-</option>
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // Init Datatable
            var table = $('#table-matakuliah').DataTable({
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
                    url: "{{ route('superadmin.matakuliah.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.id = $("#prodi-select").val();
                    },
                },
                buttons: [{
                    text: '<i class="fa-solid fa-plus mr-2"></i> Tambah Data',
                    className: 'btn btn-primary btn-tambah me-2',
                    action: function(e, dt, node, config) {
                        $('#tambah-matakuliah').modal('show');
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
                        data: 'kode_matakuliah'
                    },
                    {
                        targets: 2,
                        width: '20%',
                        className: 'text-center align-middle fs-14',
                        data: 'nama_matakuliah'
                    },
                    {
                        targets: 3,
                        width: '15%',
                        className: 'text-center align-middle fs-14',
                        data: 'sks',
                        render: function(data, type, row, meta) {
                            return `${row.sks}(${row.t}T, ${row.p}P, ${row.k}K)`
                        }
                    },
                    {
                        targets: 4,
                        width: '10%',
                        className: 'text-center align-middle fs-14',
                        data: 'prodi.nama_prodi'
                    },
                    {
                        targets: 5,
                        width: '10%',
                        className: 'text-center align-middle fs-14',
                        data: 'kurikulum'
                    },
                    {
                        targets: 6,
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
                    $('#table-matakuliah').DataTable().buttons().container().appendTo(
                        '#table-matakuliah_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Filter
            $('#prodi-select').change(function(e) {
                e.preventDefault();
                table.ajax.reload()
            });

            // Update
            $('#table-matakuliah tbody').on('click', '.btn-update', function() {
                var data = table.row($(this).parents('tr')).data();

                $('#id_matakuliah').val(data.id)
                $('#kode_matkul').val(data.kode_matakuliah)
                $('#nama').val(data.nama_matakuliah)
                $('#sks').val(data.sks)
                $('#t').val(data.t)
                $('#p').val(data.p)
                $('#k').val(data.k)
                $('#kurikulum').val(data.kurikulum)
                $('#prodi').val(data.kode_prodi).change()
                $('#edit-matakuliah').modal('show')
            });

            // Hapus
            $('#table-matakuliah tbody').on('click', '.btn-hapus', function() {
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
                            url: "{{ route('superadmin.matakuliah.delete') }}",
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
                    url: "{{ route('superadmin.matakuliah.store') }}",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#tambah-matakuliah').modal('hide')
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
                    url: "{{ route('superadmin.matakuliah.update') }}",
                    type: "PUT",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#edit-matakuliah').modal('hide')
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
