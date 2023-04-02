@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Validasi Data</h4>
                    </div>

                    {{-- Filter --}}
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <label for="ta">Tahun Akademik</label>
                            <select class="selectpicker form-control" data-live-search="true" id="ta-select">
                                @foreach ($ta as $t)
                                    <option value="{{ $t->id }}" selected>{{ $t->tahun_akademik }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label for="semester">Semester</label>
                            <select class="selectpicker form-control" data-live-search="true" id="semester-select">
                                <option value="ganjil" selected>Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label for="dosen">Status</label>
                            <select class="selectpicker form-control" data-live-search="true" id="status-select">
                                <option value="0" selected>Pending</option>
                                <option value="1">Approved</option>
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3 d-flex align-items-end">
                            <button class="btn btn-primary btn-filter"><i class="fa-solid fa-magnifying-glass mr-2"></i>Cari
                                Data</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="table-validasi" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>NIDN</th>
                                    <th>NAMA</th>
                                    <th>PRODI</th>
                                    <th>TA</th>
                                    <th>SEMESTER</th>
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

    {{-- Modal Validasi --}}
    <div class="modal fade fadeinUp" id="modal-validasi" tabindex="-1" role="dialog" tabindex="-1" data-backdrop="static"
        data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Validasi Data <span
                            class="font-weight-bold" id="nama"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-sgas" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>KODE MATAKULIAH</th>
                                    <th>MATAKULIAH</th>
                                    <th>PRODI</th>
                                    <th>SEMESTER</th>
                                    <th>JUMLAH KELAS</th>
                                    <th>JUMLAH DOSEN</th>
                                    <th>SKS</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {

            // Init Datatable
            var table = $('#table-validasi').DataTable({
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
                    url: "{{ route('superadmin.validasi.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.semester = $('#semester-select').val()
                        d.ta = $('#ta-select').val()
                        d.status = $('#status-select').val()
                    }
                },
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
                        width: '5%',
                        className: 'text-center align-middle',
                        data: 'dosen.nidn'
                    },
                    {
                        targets: 2,
                        width: '20%',
                        className: 'text-center align-middle fs-14',
                        data: 'dosen.nama'
                    },
                    {
                        targets: 3,
                        width: '15%',
                        className: 'text-center align-middle fs-14',
                        render: function(data, type, row, mete) {
                            if (row.dosen.id_prodi == null || row.dosen.id_prodi == '') {
                                return '-'
                            }
                            return row.dosen.prodi.nama_prodi
                        }
                    },
                    {
                        targets: 4,
                        width: '15%',
                        className: 'text-center align-middle fs-14',
                        data: 'tahun_akademik.tahun_akademik'
                    },
                    {
                        targets: 5,
                        width: '15%',
                        className: 'text-center align-middle fs-14',
                        data: 'semester',
                        render: function(data, type, row, mete) {
                            if (data == 'ganjil') {
                                return 'Ganjil'
                            }
                            return 'Genap'
                        }
                    },
                    {
                        targets: 6,
                        width: '10%',
                        className: 'text-center align-middle fs-14',
                        data: 'validasi',
                        render: function(data, type, row, mete) {
                            if (row.validasi == 0) {
                                return '<span class="badge bg-danger">Pending</span>'
                            }
                            return '<span class="badge bg-success">Approved</span>'
                        }
                    },
                    {
                        targets: 7,
                        width: '15%',
                        className: 'text-center align-middle',
                        render: function(data, type, row, meta) {
                            return `<button class="btn btn-primary btn-sm btn-lihat"><i class="fa-solid fa-eye"></i></button>`
                        }
                    }
                ],
                initComplete: function() {
                    $('#table-validasi').DataTable().buttons().container().appendTo(
                        '#table-validasi_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Filter
            $('.btn-filter').click(function(e) {
                e.preventDefault();

                table.ajax.reload()
            });

            // Validasi 
            $('#table-validasi tbody').on('click', '.btn-lihat', function() {
                var data = table.row($(this).parents('tr')).data();
                
                if (data.validasi = 1) {
                  var buttonText = '<i class="fa-solid fa-circle-xmark mr-2"></i> Batalkan Validasi'
                  var buttonClass = 'btn btn-danger btn-tambah me-2'
                }else{
                  var buttonText = '<i class="fa-solid fa-circle-check mr-2"></i> Validasi Data'
                  var buttonClass = 'btn btn-primary btn-tambah me-2'
                }
                console.log(data);

                table2 = $('#table-sgas').DataTable({
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
                    bDestroy: true,
                    ajax: {
                        url: "{{ route('superadmin.validasi.index') }}",
                        type: "GET",
                        data: function(d) {
                            d.id_sgas = data.id
                        }
                    },
                    buttons: [{
                        text: buttonText,
                        className: buttonClass,
                        action: function(e, dt, node, config) {
                            Swal.fire({
                                title: 'Apakah anda yakin?',
                                text: "Data akan diubah!",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Lanjutkan!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        type: "PUT",
                                        url: "{{ route('superadmin.validasi.update') }}",
                                        data: {
                                            id_sgas: data.id,
                                            status_validasi: data.validasi
                                        },
                                        dataType: "JSON",
                                        beforeSend: function() {
                                            Swal.showLoading()
                                        },
                                        success: function(response) {
                                            Swal.hideLoading()
                                            table.ajax.reload()
                                            $('#modal-validasi')
                                                .modal('hide')
                                            Swal.fire('Sukses!',
                                                'Data dihapus',
                                                'success')
                                        }
                                    });
                                }
                            })
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
                            data: 'matakuliah.kode_matakuliah'
                        },
                        {
                            targets: 2,
                            width: '20%',
                            className: 'text-center align-middle fs-14',
                            data: 'matakuliah.nama_matakuliah'
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
                            data: 'semester'
                        },
                        {
                            targets: 5,
                            width: '10%',
                            className: 'text-center align-middle fs-14',
                            data: 'kelas'
                        },
                        {
                            targets: 6,
                            width: '10%',
                            className: 'text-center align-middle fs-14',
                            data: 'total_dosen'
                        },
                        {
                            targets: 7,
                            width: '5%',
                            className: 'text-center align-middle fs-14',
                            data: 'matakuliah.sks'
                        },
                        {
                            targets: 8,
                            width: '5%',
                            className: 'text-center align-middle fs-14',
                            data: 'total',
                            render: function(data, type, row, meta) {
                                var total = row.total_sks * row.kelas / row.total_dosen
                                return total.toFixed(2)
                            }
                        }
                    ],
                    initComplete: function() {
                        $('#table-sgas').DataTable().buttons().container().appendTo(
                            '#table-sgas_wrapper .col-md-6:eq(0)');
                        $('.btn-tambah').removeClass("btn-secondary");
                    }
                });

                $('#nama').text(data.dosen.nama)
                $('#modal-validasi').modal('show')
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
                            url: "{{ route('superadmin.prodi.delete') }}",
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
                    url: "{{ route('superadmin.prodi.store') }}",
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
                    url: "{{ route('superadmin.prodi.update') }}",
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
