@extends('layout.db')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Order:</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="row mt-3">
                    
                    <div class="col-md-7 mt-4">
                        <h7 class="m-0 font-weight-bold">Daily Order: </h7><br>

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
                                <th scope="col">Nama</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Catatan</th>
                                <th scope="col">Jam Ambil</th>
                                <th scope="col">Tanggal Ambil</th>
                                <th scope="col">Status</th>
                                <th scope="col">Transaksi</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order => $item)
                                <tr>
                                    <th scope="row">{{ ($orders->currentPage() - 1) * $orders->perPage() + $order + 1 }}</th>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="catatan">{{ $item->catatan }}</td>
                                    <td>{{ $item->jam_ambil }}</td>
                                    <td>{{ $item->tanggal_ambil }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->transaksi }}</td>
                                    <td>
                                        @if($item->status !== 'done')
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#paymentModal-{{ $item->id }}">Done</button>
                                            <a href="{{ route('orders.edit', $item->id) }}" class="btn btn-success btn-sm">Edit</a>                                            
                                        @endif
                                        <form action="{{ route('orders.destroy', $item->id) }}" method="POST" style="display:inline;">
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
                        {{ $orders->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    @foreach ($orders as $item)
        <!-- Modal -->
        <div class="modal fade" id="paymentModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel-{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel-{{ $item->id }}">Payment Options</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('addincome', $item->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="payment_method">Choose Payment Method:</label>
                                <select class="form-control" id="payment_method" name="payment_method">
                                    <option value="cash">Cash</option>
                                    <option value="gopay">Gopay</option>
                                    <option value="bsi">BSI</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Orders</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="filterForm" method="get" action="{{ route('orders') }}">
                        <div class="form-group">
                            <label for="status">Status</label><br>
                            <input type="checkbox" name="status[]" value="pending"> Pending<br>
                            <input type="checkbox" name="status[]" value="done"> Done
                        </div>
                        <div class="form-group">
                            <label for="transaksi">Transaksi</label><br>
                            <input type="checkbox" name="transaksi[]" value="gopay"> GoPay<br>
                            <input type="checkbox" name="transaksi[]" value="cash"> Cash<br>
                            <input type="checkbox" name="transaksi[]" value="bsi"> BSI
                        </div>
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
                        <h5 class="modal-title" id="exportModalLabel">Export Data CashFlow</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('export_orders') }}" method="POST">
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
