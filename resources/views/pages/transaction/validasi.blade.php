@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Validasi Data</h4>
                    </div>

                    @role('admin')
                        <div class="alert alert-primary" role="alert">
                            <strong class="font-weight-bold">Perhatian!</strong><br>
                            Data Dosen yang muncul adalah data dosen yang memiliki homebase di
                            <b>{{ Auth::user()->fakultas->nama_fakultas }}</b>
                        </div>
                    @endrole

                    {{-- Filter --}}
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <label for="ta">Jenis Kegiatan</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="kegiatan-select">
                                        <option value="1">Pengajaran</option>
                                        <option value="2">Bimbingan Akademik</option>
                                        <option value="3">Pembimbingan Tugas Akhir</option>
                                        <option value="4">Praktik Lapangan</option>
                                        <option value="5">Penguji Tugas Akhir</option>
                                    </select>
                                </div>
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
                                <div class="col-lg-3 mb-3">
                                    <label for="dosen">Status</label>
                                    <select class="selectpicker form-control" data-live-search="true" id="status-select">
                                        <option value="0" selected>Pending</option>
                                        <option value="1">Approved</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3 d-flex align-items-end">
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
                        <h5 class="mt-3 text-center"><span class="badge bg-primary" style="font-size: 16px!important">Total
                                SKS : <span class="font-weight-bold" id="total_sks"></span></span></h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Validasi PA --}}
    <div class="modal fade fadeinUp" id="modal-validasi-pa" tabindex="-1" role="dialog" tabindex="-1"
        data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Validasi Data Akademik</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-akademik" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>NAMA MAHASISWA</th>
                                    <th class="no-content"></th>
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

    {{-- Modal Validasi Tugas Akhir --}}
    <div class="modal fade fadeinUp" id="modal-validasi-ta" tabindex="-1" role="dialog" tabindex="-1"
        data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Validasi Data Tugas Akhir
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-ta" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>NAMA MAHASISWA</th>
                                    <th>JUDUL</th>
                                    <th class="no-content"></th>
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

    {{-- Modal Validasi PKL --}}
    <div class="modal fade fadeinUp" id="modal-validasi-pkl" tabindex="-1" role="dialog" tabindex="-1"
        data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Validasi Data Tugas Akhir
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-pkl" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>NAMA MAHASISWA</th>
                                    <th>PKL</th>
                                    <th class="no-content"></th>
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

    {{-- Modal Validasi Penguji Tugas Akhir --}}
    <div class="modal fade fadeinUp" id="modal-validasi-penguji-ta" tabindex="-1" role="dialog" tabindex="-1"
        data-backdrop="static" data-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Validasi Data Penguji Tugas
                        Akhir
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="table-penguji-ta" class="table" style="width:100%">
                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>NAMA MAHASISWA</th>
                                    <th>JUDUL</th>
                                    <th class="no-content"></th>
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
                    url: `/${$('#role').text()}/validasi`,
                    type: "GET",
                    data: function(d) {
                        d.kegiatan = $('#kegiatan-select').val()
                        d.semester = $('#semester-select').val()
                        d.ta = $('#ta-select').val()
                        d.status = $('#status-select').val()
                    }
                },
                buttons: [{
                    text: '<i class="fa-solid fa-check mr-2"></i> Validasi Data',
                    attr: {
                        id: 'validasiButton'
                    },
                    className: 'btn mt-2 btn-success btn-tambah me-2',
                    action: function(e, dt, node, config) {
                        Swal.fire({
                            icon: 'question',
                            title: "Anda akan validasi data ini?",
                            text: "Pastikan data ini benar untuk divalidasi",
                            showCancelButton: true,
                            confirmButtonColor: '#df4759',
                            confirmButtonText: 'Lanjutkan',
                            cancelButtonText: "Batalkan",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                getSelect()
                            }
                        });
                    }
                }],
                columnDefs: [{
                        targets: 0,
                        width: '2%',
                        defaultContent: "",
                        className: 'text-center align-middle select-checkbox'
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
                            if (row.dosen.id_prodi == null || row.dosen.id_prodi == '' || row.dosen
                                .id_prodi == '-') {
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

                            let kegiatan = $('#kegiatan-select').val();
                            let validasi = ''

                            switch (kegiatan) {
                                case '1':
                                    validasi = row.validasi == 0 ?
                                        '<span class="badge bg-danger">Pending</span>' :
                                        '<span class="badge bg-success">Approved</span>'
                                    break;

                                case '2':
                                    validasi = row.validasi_pa == 0 ?
                                        '<span class="badge bg-danger">Pending</span>' :
                                        '<span class="badge bg-success">Approved</span>'
                                    break;

                                case '3':
                                    validasi = row.validasi_ta == 0 ?
                                        '<span class="badge bg-danger">Pending</span>' :
                                        '<span class="badge bg-success">Approved</span>'
                                    break;

                                case '4':
                                    validasi = row.validasi_pkl == 0 ?
                                        '<span class="badge bg-danger">Pending</span>' :
                                        '<span class="badge bg-success">Approved</span>'
                                    break;

                                case '5':
                                    validasi = row.validasi_penguji_ta == 0 ?
                                        '<span class="badge bg-danger">Pending</span>' :
                                        '<span class="badge bg-success">Approved</span>'
                                    break;
                            }

                            return validasi
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
                select: {
                    style: 'os',
                    selector: 'td:first-child',
                    style: 'multi'
                },
                initComplete: function() {
                    $('#table-validasi').DataTable().buttons().container().appendTo(
                        '#table-validasi_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Filter
            $('.btn-filter').click(function(e) {
                e.preventDefault();
                if ($('#status-select').val() == 0) {
                    $('#validasiButton').text('Validasi Data')
                    $('#validasiButton').removeClass('btn mt-2 btn-danger btn-tambah me-2')
                    $('#validasiButton').addClass('btn mt-2 btn-success btn-tambah me-2')
                } else {
                    $('#validasiButton').text('Batalkan Validasi')
                    $('#validasiButton').removeClass('btn mt-2 btn-success btn-tambah me-2')
                    $('#validasiButton').addClass('btn mt-2 btn-danger btn-tambah me-2')
                }
                table.ajax.reload()
            });

            // Validasi 
            $('#table-validasi tbody').on('click', '.btn-lihat', function() {
                var data = table.row($(this).parents('tr')).data();

                switch ($('#kegiatan-select').val()) {
                    case '1':

                        if (data.validasi == 1) {
                            var buttonText =
                                '<i class="fa-solid fa-circle-xmark mr-2"></i> Batalkan Validasi'
                            var buttonClass = 'btn btn-danger btn-tambah me-2'
                        } else {
                            var buttonText = '<i class="fa-solid fa-circle-check mr-2"></i> Validasi Data'
                            var buttonClass = 'btn btn-primary btn-tambah me-2'
                        }

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
                                url: `/${$('#role').text()}/validasi`,
                                type: "GET",
                                data: function(d) {
                                    d.id_sgas = data.id,
                                        d.kegiatan = $('#kegiatan-select').val()
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
                                                url: `/${$('#role').text()}/validasi`,
                                                data: {
                                                    id_sgas: data.id,
                                                    kegiatan: $(
                                                        '#kegiatan-select'
                                                    ).val()
                                                },
                                                dataType: "JSON",
                                                beforeSend: function() {
                                                    Swal.showLoading()
                                                },
                                                success: function(
                                                    response) {
                                                    Swal.hideLoading()
                                                    table.ajax
                                                        .reload()
                                                    $('#modal-validasi')
                                                        .modal(
                                                            'hide')
                                                    Swal.fire(
                                                        'Sukses!',
                                                        'Data dihapus',
                                                        'success'
                                                    )
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
                                        var total = row.matakuliah.sks * row.kelas / row
                                            .total_dosen
                                        return total.toFixed(2)
                                    }
                                }
                            ],
                            initComplete: function() {
                                $('#table-sgas').DataTable().buttons().container().appendTo(
                                    '#table-sgas_wrapper .col-md-6:eq(0)');
                                $('.btn-tambah').removeClass("btn-secondary");
                            },
                            footerCallback: function(row, data, start, end, display) {
                                var api = this.api();

                                // Remove the formatting to get integer data for summation
                                var intVal = function(i) {
                                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') *
                                        1 :
                                        typeof i ===
                                        'number' ? i : 0;
                                };

                                // Total SKS over all pages
                                total = api
                                    .column(8)
                                    .data()
                                    .reduce(function(a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0);

                                // Update footer
                                $('#total_sks').text(total);

                            },
                        });

                        $('#total_sks').text()
                        $('#nama').text(data.dosen.nama)

                        $('#modal-validasi').modal('show')

                        break;

                    case '2':

                        if (data.validasi_pa == 1) {
                            var buttonText =
                                '<i class="fa-solid fa-circle-xmark mr-2"></i> Batalkan Validasi'
                            var buttonClass = 'btn btn-danger btn-tambah me-2'
                        } else {
                            var buttonText = '<i class="fa-solid fa-circle-check mr-2"></i> Validasi Data'
                            var buttonClass = 'btn btn-primary btn-tambah me-2'
                        }

                        var table3 = $('#table-akademik').DataTable({
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
                                url: `/${$('#role').text()}/validasi`,
                                type: "GET",
                                data: function(d) {
                                    d.id_sgas = data.id,
                                        d.kegiatan = $('#kegiatan-select').val()
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
                                                url: `/${$('#role').text()}/validasi`,
                                                data: {
                                                    id_sgas: data.id,
                                                    kegiatan: $(
                                                        '#kegiatan-select'
                                                    ).val()
                                                },
                                                dataType: "JSON",
                                                beforeSend: function() {
                                                    Swal.showLoading()
                                                },
                                                success: function(
                                                    response) {
                                                    Swal.hideLoading()
                                                    table.ajax
                                                        .reload()
                                                    $('#modal-validasi-pa')
                                                        .modal(
                                                            'hide')
                                                    Swal.fire(
                                                        'Sukses!',
                                                        'Data dihapus',
                                                        'success'
                                                    )
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
                                    data: 'nim'
                                },
                                {
                                    targets: 2,
                                    width: '40%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'nama_mahasiswa'
                                },
                                {
                                    targets: 3,
                                    width: '15%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'nama_mahasiswa',
                                    render: function(data, type, row, meta) {
                                        return ''
                                    }
                                }
                            ],
                            initComplete: function() {
                                $('#table-akademik').DataTable().buttons().container()
                                    .appendTo(
                                        '#table-akademik_wrapper .col-md-6:eq(0)');
                                $('.btn-tambah').removeClass("btn-secondary");
                            }
                        });

                        $('#modal-validasi-pa').modal('show')

                        break;

                    case '3':

                        if (data.validasi_ta == 1) {
                            var buttonText =
                                '<i class="fa-solid fa-circle-xmark mr-2"></i> Batalkan Validasi'
                            var buttonClass = 'btn btn-danger btn-tambah me-2'
                        } else {
                            var buttonText = '<i class="fa-solid fa-circle-check mr-2"></i> Validasi Data'
                            var buttonClass = 'btn btn-primary btn-tambah me-2'
                        }

                        var table4 = $('#table-ta').DataTable({
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
                                url: `/${$('#role').text()}/validasi`,
                                type: "GET",
                                data: function(d) {
                                    d.id_sgas = data.id,
                                        d.kegiatan = $('#kegiatan-select').val()
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
                                                url: `/${$('#role').text()}/validasi`,
                                                data: {
                                                    id_sgas: data.id,
                                                    kegiatan: $(
                                                        '#kegiatan-select'
                                                    ).val()
                                                },
                                                dataType: "JSON",
                                                beforeSend: function() {
                                                    Swal.showLoading()
                                                },
                                                success: function(
                                                    response) {
                                                    Swal.hideLoading()
                                                    table.ajax
                                                        .reload()
                                                    $('#modal-validasi-ta')
                                                        .modal(
                                                            'hide')
                                                    Swal.fire(
                                                        'Sukses!',
                                                        'Data dihapus',
                                                        'success'
                                                    )
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
                                    data: 'nim'
                                },
                                {
                                    targets: 2,
                                    width: '40%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'nama_mahasiswa'
                                },
                                {
                                    targets: 3,
                                    width: '15%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'judul_ta'
                                },
                                {
                                    targets: 4,
                                    width: '15%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'judul_ta',
                                    render: function(data, type, row, meta) {
                                        return ''
                                    }
                                }
                            ],
                            initComplete: function() {
                                $('#table-ta').DataTable().buttons().container()
                                    .appendTo(
                                        '#table-ta_wrapper .col-md-6:eq(0)');
                                $('.btn-tambah').removeClass("btn-secondary");
                            }
                        });

                        $('#modal-validasi-ta').modal('show')

                        break;

                    case '4':

                        if (data.validasi_pkl == 1) {
                            var buttonText =
                                '<i class="fa-solid fa-circle-xmark mr-2"></i> Batalkan Validasi'
                            var buttonClass = 'btn btn-danger btn-tambah me-2'
                        } else {
                            var buttonText = '<i class="fa-solid fa-circle-check mr-2"></i> Validasi Data'
                            var buttonClass = 'btn btn-primary btn-tambah me-2'
                        }

                        var table5 = $('#table-pkl').DataTable({
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
                                url: `/${$('#role').text()}/validasi`,
                                type: "GET",
                                data: function(d) {
                                    d.id_sgas = data.id,
                                        d.kegiatan = $('#kegiatan-select').val()
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
                                                url: `/${$('#role').text()}/validasi`,
                                                data: {
                                                    id_sgas: data.id,
                                                    kegiatan: $(
                                                        '#kegiatan-select'
                                                    ).val()
                                                },
                                                dataType: "JSON",
                                                beforeSend: function() {
                                                    Swal.showLoading()
                                                },
                                                success: function(
                                                    response) {
                                                    Swal.hideLoading()
                                                    table.ajax
                                                        .reload()
                                                    $('#modal-validasi-ta')
                                                        .modal(
                                                            'hide')
                                                    Swal.fire(
                                                        'Sukses!',
                                                        'Data dihapus',
                                                        'success'
                                                    )
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
                                    data: 'nim'
                                },
                                {
                                    targets: 2,
                                    width: '40%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'nama_mahasiswa'
                                },
                                {
                                    targets: 3,
                                    width: '15%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'tempat_kegiatan'
                                },
                                {
                                    targets: 4,
                                    width: '15%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'tempat_kegiatan',
                                    render: function(data, type, row, meta) {
                                        return ''
                                    }
                                }
                            ],
                            initComplete: function() {
                                $('#table-pkl').DataTable().buttons().container()
                                    .appendTo(
                                        '#table-pkl_wrapper .col-md-6:eq(0)');
                                $('.btn-tambah').removeClass("btn-secondary");
                            }
                        });

                        $('#modal-validasi-pkl').modal('show')

                        break;

                    case '5':

                        if (data.validasi_penguji_ta == 1) {
                            var buttonText =
                                '<i class="fa-solid fa-circle-xmark mr-2"></i> Batalkan Validasi'
                            var buttonClass = 'btn btn-danger btn-tambah me-2'
                        } else {
                            var buttonText = '<i class="fa-solid fa-circle-check mr-2"></i> Validasi Data'
                            var buttonClass = 'btn btn-primary btn-tambah me-2'
                        }

                        var table6 = $('#table-penguji-ta').DataTable({
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
                                url: `/${$('#role').text()}/validasi`,
                                type: "GET",
                                data: function(d) {
                                    d.id_sgas = data.id,
                                        d.kegiatan = $('#kegiatan-select').val()
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
                                                url: `/${$('#role').text()}/validasi`,
                                                data: {
                                                    id_sgas: data.id,
                                                    kegiatan: $(
                                                        '#kegiatan-select'
                                                    ).val()
                                                },
                                                dataType: "JSON",
                                                beforeSend: function() {
                                                    Swal.showLoading()
                                                },
                                                success: function(
                                                    response) {
                                                    Swal.hideLoading()
                                                    table.ajax
                                                        .reload()
                                                    $('#modal-validasi-ta')
                                                        .modal(
                                                            'hide')
                                                    Swal.fire(
                                                        'Sukses!',
                                                        'Data dihapus',
                                                        'success'
                                                    )
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
                                    data: 'nim'
                                },
                                {
                                    targets: 2,
                                    width: '40%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'nama_mahasiswa'
                                },
                                {
                                    targets: 3,
                                    width: '15%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'judul_ta'
                                },
                                {
                                    targets: 4,
                                    width: '15%',
                                    className: 'text-center align-middle fs-14',
                                    data: 'judul_ta',
                                    render: function(data, type, row, meta) {
                                        return ''
                                    }
                                }
                            ],
                            initComplete: function() {
                                $('#table-penguji-ta').DataTable().buttons().container()
                                    .appendTo(
                                        '#table-penguji-ta_wrapper .col-md-6:eq(0)');
                                $('.btn-tambah').removeClass("btn-secondary");
                            }
                        });

                        $('#modal-validasi-penguji-ta').modal('show')

                        break;


                }
            });

            // Get Selected Row
            function getSelect() {
                var data = table.rows({
                    selected: true
                }).data();

                var dataDosen = [];
                for (var i = 0; i < data.length; i++) {
                    dataDosen.push({
                        id: data[i].id
                    });
                }

                if (data.length > 0) {
                    $.ajax({
                        type: "PUT",
                        url: `/${$('#role').text()}/validasi/bulk-update`,
                        data: {
                            dataDosen: dataDosen,
                            kegiatan: $('#kegiatan-select').val(),
                            validasi: $('#status-select').val()
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            Swal.showLoading()
                        },
                        success: function(response) {
                            Swal.hideLoading()
                            table.ajax.reload();
                            Swal.fire('Sukses!', "Data berhasil disimpan.",
                                'success');
                        },
                        error: function(response) {
                            Swal.hideLoading()
                            Swal.fire('Oops!', "Gagal menyimpan data.",
                                'error');
                            // console.log(response.responseJSON.message);
                        },
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih minimal 1 data!',
                    });
                }


            };
        });
    </script>
@endsection
