@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Report Dosen</h4>
                    </div>

                    {{-- Filter --}}
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <label for="ta">Tahun Akademik</label>
                            <select class="selectpicker form-control" data-live-search="true" id="ta-select">
                                @foreach ($ta as $t)
                                    <option value="{{ $t->id }}">{{ $t->tahun_akademik }}</option>
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
                        <div class="col-lg-3 mb-3 d-flex align-items-end">
                            <button class="btn btn-primary btn-filter"><i class="fa-solid fa-magnifying-glass mr-2"></i>Cari
                                Data</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="table-dosen" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>NIP/NIDN/NIDK</th>
                                    <th>NAMA DOSEN</th>
                                    <th>PRODI</th>
                                    <th>MATAKULIAH</th>
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

    {{-- Modal Show --}}
    <div class="modal fade fadeinUp" id="show-matakuliah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Detail Data Matakuliah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <table id="table-matakuliah" class="table" style="width:100%">
                        <thead>
                            <tr align="center">
                                <th>No</th>
                                <th>KODE MATAKULIAH</th>
                                <th>NAMA MATAKULIAH</th>
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
                    url: `{{ route('report.dosen') }}`,
                    type: "GET",
                    data: function(d) {
                        d.ta = $('#ta-select').val()
                        d.semester = $('#semester-select').val()
                    }
                },
                buttons: [{
                    text: '<i class="fa-solid fa-print mr-2"></i> Export Data',
                    className: 'btn btn-success btn-tambah me-2',
                    action: function(e, dt, node, config) {

                        $.ajax({
                            type: "POST",
                            url: "{{ route('report.printDosen') }}",
                            data: {
                                ta: $('#ta-select').val(),
                                semester: $('#semester-select').val()
                            },
                            cache: false,
                            xhrFields: {
                                responseType: 'blob'
                            },
                            beforeSend: function() {
                                Swal.showLoading()
                            },
                            success: function(data) {
                                Swal.hideLoading()
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(data);
                                link.download = `dosen.xlsx`;
                                link.click();
                                Swal.fire('Sukses!',
                                    'Data berhasil diambil.',
                                    'success');
                            },
                            error: function(data) {
                                Swal.hideLoading()
                                alert('Not downloaded');
                            }
                        });
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
                        data: 'prodi.nama_prodi',
                        render: function(data, type, row, meta) {
                            if (data) return data
                            return '-'
                        }
                    },
                    {
                        targets: 4,
                        width: '10%',
                        className: 'text-center align-middle fs-14',
                        render: function(data, type, row, meta) {
                            return '<button class="btn btn-outline-info btn-sm btn-detail">Detail</button>'
                        }
                    }
                ],
                initComplete: function() {
                    $('#table-dosen').DataTable().buttons().container().appendTo(
                        '#table-dosen_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Cari
            $('.btn-filter').click(function(e) {
                e.preventDefault();
                table.ajax.reload()
            });

            // Show Matakuliah
            $('#table-dosen tbody').on('click', '.btn-detail', function(e) {
                e.preventDefault();
                var data = table.row($(this).parents('tr')).data();

                var table2 = $('#table-matakuliah').DataTable({
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
                        url: `{{ route('report.dosen') }}`,
                        type: "GET",
                        data: {
                            id_sgas: data.sgas[0].id
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
                        },
                    ],
                    initComplete: function() {
                        $('#table-matakuliah').DataTable().buttons().container().appendTo(
                            '#table-matakuliah_wrapper .col-md-6:eq(0)');
                        $('.btn-tambah').removeClass("btn-secondary");
                    }
                });

                $('#show-matakuliah').modal('show')
            });
        });
    </script>
@endsection
