@extends('layouts.main')

@section('konten')
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="">
                        <h4 style="font-weight: bold;">Data Pengajaran</h4>
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
                        <div class="col-lg-3 mb-3">
                            <label for="dosen">Nama Dosen</label>
                            <select class="selectpicker form-control" data-live-search="true" id="dosen-select">
                                <option value="-" selected disabled>-</option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3 d-flex align-items-end">
                            <button class="btn btn-primary btn-filter"><i class="fa-solid fa-magnifying-glass mr-2"></i>Cari
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
                                <div class="col-6 col-md-4"><label>NIP/NIDN/NIDK</label></div>
                                <div class="col-12 col-md-8">
                                    <input type="text" readonly class="form-control-plaintext bg-white" id="nidn_dosen">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mb-4 mt-4">
                            <table id="table-pengajaran" class="table" style="width:100%">
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
    <div class="modal fade fadeinUp" id="tambah-pengajaran" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Tambah Data Pengajaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form id="form-store">
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="prodi">Prodi</label>
                            <select class="selectpicker form-control" data-live-search="true" name="prodi" id="prodi_s">
                                <option value="-" selected disabled>-</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->kode_prodi }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="kurikulum">Kurikulum</label>
                            <select class="selectpicker form-control" data-live-search="true" name="kurikulum" id="kurikulum_s">
                                <option value="-" selected disabled>-- Pilih Kurikulum --</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="matkul">Matakuliah</label>
                            <select class="selectpicker form-control" data-live-search="true" name="matkul" id="matkul_s">
                                <option value="-" selected disabled>-- Pilih Matakuliah --</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="semester">Semester</label>
                            <select class="selectpicker form-control" data-live-search="true" name="semester" id="semester_s">
                                <option value="-" selected disabled>-- Pilih Semester --</option>
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
    <div class="modal fade fadeinUp" id="edit-pengajaran" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            var table = $('#table-pengajaran').DataTable({
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
                    url: "{{ route('superadmin.pengajaran.index') }}",
                    type: "GET"
                },
                buttons: [{
                        text: '<i class="fa-solid fa-plus mr-2"></i> Tambah Data',
                        className: 'btn btn-primary btn-tambah me-2',
                        action: function(e, dt, node, config) {
                            $('#tambah-pengajaran').modal('show');
                        }
                    },
                    {
                        text: '<i class="fa-solid fa-print mr-2"></i> Print',
                        className: 'btn btn-success btn-tambah me-2',
                        action: function(e, dt, node, config) {
                            alert('print')
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
                        data: 'kelas'
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
                        render: function(data, type, row, meta) {
                            console.log(row);
                            return row.kelas
                        }
                    },
                    {
                        targets: 9,
                        width: '10%',
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
                    $('#table-pengajaran').DataTable().buttons().container().appendTo(
                        '#table-pengajaran_wrapper .col-md-6:eq(0)');
                    $('.btn-tambah').removeClass("btn-secondary");
                }
            });

            // Filter
            $('.btn-filter').click(function(e) {
                e.preventDefault();

                if ($("#dosen-select").val() == '-') {
                    Swal.fire('Error!', 'Dosen harus dipilih dulu', 'error')
                } else {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('superadmin.pengajaran.sgas') }}",
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

                            $('#nama_dosen').val(response.data.nama);
                            $('#prodi_dosen').val(response.data.prodi.nama_prodi);
                            $('#jabfung_dosen').val(response.data.jabatan_fungsional);
                            $('#nidn_dosen').val(response.data.nidn);
                            $('#konten').addClass('d-block').removeClass('d-none')

                            Swal.fire('Sukses!', '', 'success')
                        },
                        error: function(response) {
                            Swal.hideLoading()
                            Swal.fire('Error!', 'Server Error', 'error')
                        }
                    });

                    // Semester Value
                    if ($('#semester-select').val() == 'ganjil') {
                        $("#semester_s").append(`<option value="I">I</option>`);
                        $("#semester_s").append(`<option value="III">III</option>`);
                        $("#semester_s").append(`<option value="V">V</option>`);
                        $("#semester_s").append(`<option value="VII">VII</option>`);
                        $("#semester_s").selectpicker('refresh');
                    }else{
                        $("#semester_s").append(`<option value="II">II</option>`);
                        $("#semester_s").append(`<option value="IV">IV</option>`);
                        $("#semester_s").append(`<option value="VI">VI</option>`);
                        $("#semester_s").append(`<option value="VIII">VIII</option>`);
                        $("#semester_s").selectpicker('refresh');
                    }
                }


            });

            // Kurikulum Filter
            $('#prodi_s').change(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "GET",
                    url: "{{ route('superadmin.pengajaran.kurikulum') }}",
                    data: {
                        prodi: $('#prodi_s').val()
                    },
                    dataType: "JSON",
                    beforeSend: function () { 
                        $("#kurikulum_s").empty().append('<option value="" selected disabled>- Pilih Kurikulum -</option>');
                        $("#kurikulum_s").selectpicker('refresh');
                        $("#matkul_s").empty().append('<option value="" selected disabled>- Pilih Matakuliah -</option>');
                        $("#matkul_s").selectpicker('refresh');
                    },
                    success: function(response) {
                        $.each(response.data, function (indexInArray, valueOfElement) { 
                            $("#kurikulum_s").append(`<option value="${valueOfElement.kurikulum}">${valueOfElement.kurikulum}</option>`);
                            $("#kurikulum_s").selectpicker('refresh');
                        });
                    }
                });
            });
            
            // Matakuliah Filter
            $('#kurikulum_s').change(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "GET",
                    url: "{{ route('superadmin.pengajaran.matakuliah') }}",
                    data: {
                        prodi: $('#prodi_s').val(),
                        kurikulum: $('#kurikulum_s').val()
                    },
                    dataType: "JSON",
                    beforeSend: function () { 
                        $("#matkul_s").empty().append('<option value="" selected disabled>- Pilih Matakuliah -</option>');
                        $("#matkul_s").selectpicker('refresh');
                    },
                    success: function(response) {
                        $.each(response.data, function (indexInArray, valueOfElement) { 
                            $("#matkul_s").append(`<option value="${valueOfElement.id}">${valueOfElement.kode_matakuliah} - ${valueOfElement.nama_matakuliah}</option>`);
                            $("#matkul_s").selectpicker('refresh');
                        });
                    }
                });
            });

            // Update
            $('#table-pengajaran tbody').on('click', '.btn-update', function() {
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
                    url: "{{ route('superadmin.pengajaran.store') }}",
                    data: $(this).serialize(),
                    dataType: "JSON",
                    beforeSend: function() {
                        Swal.showLoading()
                    },
                    success: function(response) {
                        Swal.hideLoading()
                        $('#tambah-pengajaran').modal('hide')
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
