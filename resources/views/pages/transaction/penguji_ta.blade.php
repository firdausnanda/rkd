@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Data Penguji Tugas Akhir</h4>
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
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label for="dosen">Nama Dosen</label>
                            <select class="selectpicker d-block me-0 w-100" data-live-search="true" id="dosen-select">
                                <option value="-" selected disabled>-</option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 mb-3 d-flex align-items-end">
                            <button class="btn btn-primary btn-filter mb-2"><i
                                    class="fa-solid fa-magnifying-glass mr-2"></i>Cari
                                Data</button>
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div id="konten" class="d-none">

                        <div class="ml-2 mt-4">
                            <div class="row justify-content-md-center">
                                <div class="col-6 col-md-4"><label>Nama yang diberi tugas</label></div>
                                <div class="col-12 col-md-8">
                                    <input type="text" readonly
                                        class="form-control-plaintext bg-white font-weight-bold text-dark" id="nama_dosen">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-4"><label>Prodi</label></div>
                                <div class="col-12 col-md-8">
                                    <input type="text" readonly class="form-control-plaintext bg-white" id="prodi_dosen">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-4"><label>Jabatan Fungsional</label></div>
                                <div class="col-12 col-md-8">
                                    <input type="text" readonly class="form-control-plaintext bg-white"
                                        id="jabfung_dosen">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-4"><label>Jabatan Struktural</label></div>
                                <div class="col-12 col-md-8">
                                    <input type="text" readonly class="form-control-plaintext bg-white"
                                        id="jabatan_struktural_dosen">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-4"><label>NIP/NIDN/NIDK</label></div>
                                <div class="col-12 col-md-8">
                                    <input type="text" readonly class="form-control-plaintext bg-white" id="nidn_dosen">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-4"><label>Status</label></div>
                                <div class="col-12 col-md-8">
                                    <h3><span class="badge" id="status"></span></h3>
                                    <h3><span class="d-none" id="ta_aktif"></span></h3>
                                    {{-- <input type="text" readonly class="form-control-plaintext bg-white" id="status"> --}}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h5 style="font-weight: bold;">Data Mahasiswa</h5>
                        </div>

                        <div class="table-responsive mb-4 mt-4">
                            <table id="table-ta" class="table" style="width:100%">
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
                </div>
            </div>

        </div>

    </div>

    {{-- Modal Tambah Data --}}
    <div class="modal fade fadeinUp" id="tambah-mahasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Tambah Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-store">
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="nim">NIM</label>
                            <input type="text" class="form-control" name="nim">
                            <span class="invalid-feedback">
                                <strong id="nim_msg"></strong>
                            </span>
                            <input type="hidden" class="form-control" id="sgas" name="sgas">
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama_mahasiswa">Nama Mahasiswa</label>
                            <input type="text" class="form-control" name="nama_mahasiswa">
                            <span class="invalid-feedback">
                                <strong id="nama_mahasiswa_msg"></strong>
                            </span>
                        </div>
                        <div class="form-group mb-4">
                            <label for="peran">Peran</label>
                            <select name="peran" class="form-control">
                                <option value="Penguji 1">Penguji 1</option>
                                <option value="Penguji 2">Penguji 2</option>
                                <option value="Penguji 3">Penguji 3</option>
                            </select>
                            <span class="invalid-feedback">
                                <strong id="peran_msg"></strong>
                            </span>
                        </div>
                        <div class="form-group mb-4">
                            <label for="judul_ta">Judul Tugas Akhir</label>
                            <textarea class="form-control" name="judul_ta" rows="7"></textarea>
                            <span class="invalid-feedback">
                                <strong id="judul_ta_msg"></strong>
                            </span>
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
    <div class="modal fade fadeinUp" id="edit-mahasiswa" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Edit Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-update">
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="nim">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim">
                            <input type="hidden" class="form-control" id="pa" name="pa">
                        </div>
                        <div class="form-group mb-4">
                            <label for="nama_mahasiswa">Nama Mahasiswa</label>
                            <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa">
                        </div>
                        <div class="form-group mb-4">
                            <label for="peran">Peran</label>
                            <select name="peran" class="form-control" id="peran">
                                <option value="Penguji 1">Penguji 1</option>
                                <option value="Penguji 2">Penguji 2</option>
                                <option value="Penguji 3">Penguji 3</option>
                            </select>
                            <span class="invalid-feedback">
                                <strong id="peran_msg"></strong>
                            </span>
                        </div>
                        <div class="form-group mb-4">
                            <label for="judul_ta">Judul Tugas Akhir</label>
                            <textarea class="form-control" name="judul_ta" id="ta_e" rows="7"></textarea>
                            <span class="invalid-feedback">
                                <strong id="judul_ta_msg"></strong>
                            </span>
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

    {{-- Modal Print --}}
    <div class="modal fade" id="print-ta" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog"
        aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Print</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <button type="button" class="btn btn-success btn-print"><i
                            class="fa-solid fa-print mr-2"></i>Print</button> --}}
                    <button type="button" class="btn btn-success btn-print-ttd"><i
                            class="fa-solid fa-print mr-2"></i>Cetak Semua</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // Init Datatable
            var table = $('#table-ta').DataTable({
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
                    url: `/${$('#role').text()}/penguji-ta`,
                    type: "GET",
                    data: function(d) {
                        d.dosen = $('#dosen-select').val()
                        d.ta = $('#ta-select').val()
                        d.semester = $('#semester-select').val()

                    }
                },
                buttons: [{
                        text: '<i class="fa-solid fa-plus mr-2"></i> Tambah Data',
                        className: 'btn btn-primary btn-tambah me-2',
                        action: function(e, dt, node, config) {
                            if ($('#status').text() == 'Pending' && $('#ta_aktif').text() == 1) {
                                $('#tambah-mahasiswa').modal('show');
                            } else if ($('#status').text() == 'Pending') {
                                Swal.fire('Gagal!', 'Periode tidak aktif, hubungi BSDM', 'error')
                            } else if ($('#ta_aktif').text() == 0) {
                                Swal.fire('Gagal!', 'Silakan hubungi admin', 'error')
                            } else {
                                Swal.fire('Gagal!', 'Silakan hubungi admin', 'error')
                            }
                        }
                    },
                    {
                        text: '<i class="fa-solid fa-print mr-2"></i> Print',
                        className: 'btn btn-success btn-tambah me-2',
                        action: function(e, dt, node, config) {
                            if ($('#status').text() == 'Pending') {
                                Swal.fire('Gagal!', 'Silakan hubungi admin', 'error')
                            } else if ($('#prodi_dosen').val() == '-') {
                                $('#print-ta').modal('show')
                                // Swal.fire('Gagal!', 'Data dosen belum memiliki homebase', 'error')
                            } else {
                                $('#print-ta').modal('show')
                            }
                        }
                    }
                ],
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
                        width: '20%',
                        className: 'text-center align-middle fs-14',
                        data: 'nama_mahasiswa'
                    },
                    {
                        targets: 3,
                        width: '15%',
                        className: 'text-center align-middle fs-14',
                        data: 'nama_mahasiswa',
                        render: function(data, type, row, meta) {
                            if ($('#status').text() == 'Pending' && $('#ta_aktif').text() == 1) {
                                return `<div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi <i class="fa-solid fa-chevron-down ml-2"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item btn-update" href="#">Update</a>
                                            <a class="dropdown-item btn-hapus" href="#">Delete</a>
                                            </div>
                                        </div>`
                            } else {
                                return `<div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi <i class="fa-solid fa-chevron-down ml-2"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <a class="dropdown-item btn-print" href="#">Print</a>
                                            </div>
                                        </div>`
                                return ''
                            }
                        }
                    }
                ],
                initComplete: function() {
                    $('#table-ta').DataTable().buttons().container().appendTo(
                        '#table-ta_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Cari
            $('.btn-filter').click(function(e) {
                e.preventDefault();

                if ($("#dosen-select").val() == '-') {
                    Swal.fire('Error!', 'Dosen harus dipilih dulu', 'error')
                } else {
                    $.ajax({
                        type: "POST",
                        url: `/${$('#role').text()}/penguji-ta/sgas`,
                        data: {
                            ta: $("#ta-select").val(),
                            semester: $("#semester-select").val(),
                            dosen: $("#dosen-select").val()
                        },
                        dataType: "JSON",
                        beforeSend: function() {
                            Swal.showLoading()
                        },
                        success: function(response) {
                            Swal.hideLoading()
                            table.ajax.reload()

                            $('#nama_dosen').val(response.data[0].nama);
                            $('#prodi_dosen').val(response.data[0].prodi ? response.data[0]
                                .prodi.nama_prodi : '-');
                            $('#jabfung_dosen').val(response.data[0].jabatan_fungsional);
                            $('#jabatan_struktural_dosen').val(response.data[0]
                                .jabatan_struktural);
                            $('#nidn_dosen').val(response.data[0].nidn);

                            response.data[1].validasi_penguji_ta == 0 ? $('#status').text('Pending') :
                                $(
                                    '#status').text('Approved');
                            response.data[1].validasi_penguji_ta == 0 ? $('#status').removeClass(
                                    'bg-success').addClass('bg-danger') : $('#status')
                                .removeClass('bg-danger').addClass('bg-success');

                            $('#sgas').val(response.data[1].id);

                            $('#ta_aktif').text(response.data[2].is_active);

                            $('#konten').addClass('d-block').removeClass('d-none')

                            Swal.fire('Sukses!', '', 'success')
                        },
                        error: function(response) {
                            Swal.hideLoading()
                            Swal.fire('Error!', 'Server Error', 'error')
                        }
                    });

                }


            });

            // Submit Store
            $('#form-store').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: `/${$('#role').text()}/penguji-ta`,
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#tambah-mahasiswa').modal('hide')
                        table.ajax.reload()
                        $('#form-store')[0].reset()
                        Swal.fire('Sukses!', 'Data diupdate', 'success')
                    },
                    error: function(response) {
                        Swal.hideLoading()
                        var name = response.responseJSON.data;
                        if (response.status == 422) {
                            $.each(name, function(key, value) {
                                $("#" + key + "_msg").html(value);
                                $(`*[name='${key}']`).addClass('is-invalid');
                            });
                        }

                        Swal.fire('Error!', response.responseJSON.meta.message, 'error')
                    }
                });

            });

            // Update
            $('#table-ta tbody').on('click', '.btn-update', function() {
                var data = table.row($(this).parents('tr')).data();
                $('#nim').val(data.nim)
                $('#pa').val(data.id)
                $('#nama_mahasiswa').val(data.nama_mahasiswa)
                $('#ta_e').val(data.judul_ta)
                $('#peran').val(data.peran)
                $('#edit-mahasiswa').modal('show')
            });

            // Submit Update
            $('#form-update').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: `/${$('#role').text()}/penguji-ta`,
                    type: "PUT",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#edit-mahasiswa').modal('hide')
                        table.ajax.reload()
                        Swal.fire('Sukses!', 'Data diupdate', 'success')
                    },
                    error: function(response) {
                        Swal.hideLoading()
                        Swal.fire('Error!', 'Server Error', 'error')
                    }
                });
            });

            // Hapus
            $('#table-ta tbody').on('click', '.btn-hapus', function() {
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
                            url: `/${$('#role').text()}/penguji-ta`,
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

            // Print With All
            $('.btn-print-ttd').click(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "GET",
                    url: `/${$('#role').text()}/penguji-ta/print-all`,
                    data: {
                        id: $('#sgas').val()
                    },
                    cache: false,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        var blob = new Blob([response], {
                            type: 'application/pdf'
                        });
                        var url = URL.createObjectURL(blob);
                        window.open(url, '_blank');
                    },
                    error: function(response) {
                        Swal.hideLoading()
                        Swal.fire('Data Tidak Ditemukan!', 'Periksa kembali data anda.',
                            'error');
                    },
                });

            });

            // Print
            $('#table-ta tbody').on('click', '.btn-print', function(e) {
                e.preventDefault();

                var data = table.row($(this).parents('tr')).data();

                $.ajax({
                    type: "GET",
                    url: `/${$('#role').text()}/penguji-ta/print`,
                    data: {
                        id: data.id,
                        id_sgas: data.id_sgas
                    },
                    cache: false,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        var blob = new Blob([response], {
                            type: 'application/pdf'
                        });
                        var url = URL.createObjectURL(blob);
                        window.open(url, '_blank');
                    },
                    error: function(response) {
                        Swal.hideLoading()
                        Swal.fire('Data Tidak Ditemukan!', 'Periksa kembali data anda.',
                            'error');
                    },
                });

            });

        });
    </script>
@endsection
