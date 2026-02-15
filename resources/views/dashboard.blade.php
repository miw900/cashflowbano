@extends('layout.db')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pesanan Menunggu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders }}</div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
                

                <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pemasukan Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ 'Rp ' . number_format($summary->total_income, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-list-alt fa-2x text-gray-300 ml-1"></i>
                        </div>
                    </div>
                    </div>
                </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1"> Pengeluaran Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ 'Rp ' . number_format($summary->total_outcome, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-laugh-wink fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    </div>
                </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-brown text-uppercase mb-1">Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ 'Rp ' . number_format($summary->total, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                        <i class="fas fa-chart-bar fa-2x text-brown-300"></i>
                        </div>
                    </div>
                    </div>
                </div>
                </div>


            </div>

            
            <!-- Kalender Section -->
            <div class="container mt-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0">Kalender Orderan</h5>
                        <div>
                            <button class="btn btn-primary btn-sm" id="prevMonth"><<</button>
                            <span id="currentMonth" class="mx-2"></span>
                            <button class="btn btn-primary btn-sm" id="nextMonth">>></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Minggu</th>
                                    <th>Senin</th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jumat</th>
                                    <th>Sabtu</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBody">
                                <!-- Kalender akan di-generate di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

</div>

@endsection
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





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        aspectRatio: 0.5
        let today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        function updateCalendar(year, month) {
            let firstDay = new Date(year, month, 1).getDay(); // Hari pertama bulan ini
            let daysInMonth = new Date(year, month + 1, 0).getDate(); // Total hari dalam bulan ini
            let calendarBody = $('#calendarBody');
            calendarBody.empty();

            let date = 1;
            for (let i = 0; i < 6; i++) { // Maksimal 6 minggu dalam sebulan
                let row = $('<tr></tr>');

                for (let j = 0; j < 7; j++) {
                    let cell = $('<td></td>');
                    
                    if (i === 0 && j < firstDay) {
                        // Kosongkan sel sebelum tanggal 1
                        cell.html('');
                    } else if (date > daysInMonth) {
                        // Hentikan jika sudah melewati jumlah hari dalam bulan
                        break;
                    } else {
                        cell.html(`<span class="date-cell" data-date="${year}-${(month+1).toString().padStart(2, '0')}-${date.toString().padStart(2, '0')}">${date}</span>`);
                        date++;
                    }
                    row.append(cell);
                }

                calendarBody.append(row);
            }

            $('#currentMonth').text(new Date(year, month).toLocaleString('id-ID', { month: 'long', year: 'numeric' }));
            loadOrders(year, month + 1);
        }

        function loadOrders(year, month) {
            $.ajax({
                url: `/api/orders?year=${year}&month=${month}`,
                type: 'GET',
                success: function (data) {
                    data.forEach(order => {
                        let orderDate = order.tanggal_ambil;
                        let cell = $(`.date-cell[data-date="${orderDate}"]`);
                        if (cell.length > 0) {
                          cell.append(`<br><a href="/orders/${order.id}" class="badge bg-success" style="color: white;">${order.nama}</a>`);
                        }
                    });
                }
            });
        }

        $('#prevMonth').click(function () {
            if (currentMonth === 0) {
                currentYear--;
                currentMonth = 11;
            } else {
                currentMonth--;
            }
            updateCalendar(currentYear, currentMonth);
        });

        $('#nextMonth').click(function () {
            if (currentMonth === 11) {
                currentYear++;
                currentMonth = 0;
            } else {
                currentMonth++;
            }
            updateCalendar(currentYear, currentMonth);
        });

        updateCalendar(currentYear, currentMonth);
    });
</script>


