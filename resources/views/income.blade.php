@extends('layout.db')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Cashflow: Harian</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                
                <div class="row mt-3">
                 <div class="col-md-7 mt-4">
                        <h7 class="m-0 font-weight-bold"><a href="{{ route('summary') }}">bulanan</a></h7><br>
                        <!-- Tombol untuk membuka modal export -->
                        <button class="btn btn-sm btn-primary shadow-sm mt-1" data-toggle="modal" data-target="#exportModal">
                            Generate Report
                        </button>
                    </div>
                    <div class="col-md-5 mt-5 d-flex align-items-center">
                        <form class="form-inline my-2 my-lg-0" method="get" name='cari' action="{{ route('summary.store') }}">
                            <input class="form-control mr-sm-2" type="month" placeholder="Search" aria-label="Search" name='date' required>
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Save Data</button>
                        </form>
                        <button type="button" class="btn btn-outline-primary my-2 my-sm-0 ml-2" data-toggle="modal" data-target="#filterModal">Filter</button>
                    </div>
                </div>
                <!-- Display Flash Messages -->
                @if(session('success'))
                    <div id="success-alert" class='col-md-10 col-sm-12 col-xs-12'>
                        <div class='alert alert-primary mt-4 ml-5' role='alert'>
                            <p><center>{{ session('success') }}</center></p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div id="error-alert" class='col-md-10 col-sm-12 col-xs-12'>
                        <div class='alert alert-danger mt-4 ml-5' role='alert'>
                            <p><center>{{ session('error') }}</center></p>
                        </div>
                    </div>
                @endif
                                    
                
                <div class="table-responsive service">
                    <table class="table table-bordered table-hover mt-3 text-nowrap css-serial">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Gopay</th>
                                <th scope="col">BSI</th>
                                <th scope="col">CASH</th>
                                <th scope="col">Total</th>
                                <th scope="col">Total Outcome</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($income as $in=> $item)
                                <tr>
                                    <th scope="row">{{ ($income->currentPage() - 1) * $income->perPage() + $in+ 1 }}</th>
                                    <td>{{ 'Rp ' . number_format($item->gopay, 0, ',', '.') }}</td>
                                    <td>{{ 'Rp ' . number_format($item->bsi, 0, ',', '.') }}</td>
                                    <td>{{ 'Rp ' . number_format($item->cash, 0, ',', '.') }}</td>
                                    <td>{{ 'Rp ' . number_format($item->total, 0, ',', '.') }}</td>
                                    <td>{{ 'Rp ' . number_format($item->total_outcome, 0, ',', '.') }}</td>
                                      
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_income)->format('d-m-Y') }}</td>
                              
                                    <td>
                                        <a href="{{ route('income.edit', $item->tanggal_income) }}" class="btn btn-success btn-sm">Edit</a>
                                        <form action="{{ route('income.destroy', $item->tanggal_income) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>                                    
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Menampilkan kontrol paginasi -->
                    <div class="d-flex justify-content-center">
                        {{ $income->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    
        <!-- export Modal -->
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportModalLabel">Export Data CashFlow</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('export_incomes') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="startDate">Pilih Bulan:</label>
                                <input type="month" class="form-control" id="month" name="month" required>
                                                           
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

            <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter outcome</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="get" action="{{ route('income') }}">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" placeholder="Start Date">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" placeholder="End Date">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>


    
    
    <style>
        .catatan {
            min-width: 500px;
            max-width: 700px; /* Atur max-width sesuai kebutuhan */
            overflow: auto; /* Izinkan scrolling jika teks melebihi lebar */
            white-space: pre-wrap; /* Membungkus teks agar tetap terlihat */
            word-wrap: break-word; /* Memastikan teks yang panjang tidak keluar dari kotak */
        }
    </style>

    <script>
        // Fungsi untuk menyembunyikan notifikasi setelah beberapa detik
        setTimeout(function() {
            var successAlert = document.getElementById('success-alert');
            var errorAlert = document.getElementById('error-alert');

            if (successAlert) {
                successAlert.style.display = 'none';
            }

            if (errorAlert) {
                errorAlert.style.display = 'none';
            }
        }, 5000); // 5000 milidetik = 5 detik
    </script>
@endsection

