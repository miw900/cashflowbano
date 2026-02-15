@extends('layout.db')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Summaries</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row mt-3">
                    
                    <div class="col-md-7 mt-4">
                        <a href="{{ route('income') }}"><h7 class="m-0 font-weight-bold">back: </h7></a><br>

                        <!-- Tombol untuk membuka modal export -->
                        <button class="btn btn-sm btn-primary shadow-sm mt-1" data-toggle="modal" data-target="#exportModal">
                            Generate Report
                        </button>
                    </div>
                    <div class="col-md-5 mt-5 d-flex align-items-center">
                        <form class="form-inline my-2 my-lg-0" method="get" name='cari'>
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name='cari' required>
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
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
                                <th scope="col">Tanggal</th>
                                <th scope="col">Total Income</th>
                                <th scope="col">Total Outcome</th>
                                <th scope="col">Total</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($summary as $order=> $item)
                                <tr>
                                    <th scope="row">{{ ($summary->currentPage() - 1) * $summary->perPage() + $order+ 1 }}</th>
                                    <td>{{ \Carbon\Carbon::parse($item->month_year)->format('m-Y') }}</td>
                                    <td>{{ $item->total_income }}</td>
                                    <td>{{ $item->total_outcome }}</td>
                                    <td>{{ $item->total }}</td>                                    
                                    
                                    <td>                                                                                
                                        <a href="{{ route('summary.edit', $item->month_year) }}" class="btn btn-success btn-sm">Edit</a>                                            
                                        
                                        <form action="{{ route('summary.destroy', $item->month_year) }}" method="POST" style="display:inline;">
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
                        {{ $summary->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->


    <!-- Filter Modal -->
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
                    <form id="filterForm" method="get" action="{{ route('outcome') }}">
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

<!-- export Modal -->
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportModalLabel">Export Data Summary</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('export_summary') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="year">Pilih Tahun:</label>
                                <input type="number" class="form-control" id="year" name="year" list="year-options" min="1900" max="2100" required>
                                <datalist id="year-options">
                                    @for ($year = 2000; $year <= 2100; $year++)
                                        <option value="{{ $year }}"></option>
                                    @endfor
                                </datalist>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>


                    </div>
                </div>
            </div>
        </div>

    <script>
        function applyFilters() {
            document.getElementById('filterForm').submit();
        }
    </script>


    <style>
        .catatan {
            min-width: 500px;
            max-width: 700px; /* Atur max-width sesuai kebutuhan */
            overflow: auto; /* Izinkan scrolling jika teks melebihi lebar */
            white-space: pre-wrap; /* Membungkus teks agar tetap terlihat */
            word-wrap: break-word; /* Memastikan teks yang panjang tidak keluar dari kotak */
        }
    </style>

    
@endsection
