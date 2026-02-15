<!-- resources/views/orders/edit.blade.php -->
@extends('layout.db')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail Order</h6>
            </div>
            <div class="card-body">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <label type="text" class="form-control" id="nama" name="nama" value="" required>{{ $order->nama }}</label>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga:</label>
                        <label type="text" class="form-control" id="harga" name="harga" value="" required>{{ $order->harga }}</label>
                    </div>
                    <div class="form-group">
                        <label for="harga">Catatan:</label>
                        <label type="text" class="form-control" id="harga" name="harga" value="" required>{{ $order->catatan }}</label>
                    </div>
                    
                    <div class="form-group">
                        <label for="jam_ambil">Jam Ambil:</label>
                        <label type="time" class="form-control" id="jam_ambil" name="jam_ambil" value="" required>{{ $order->jam_ambil }}</label>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_ambil">Tanggal Ambil:</label>
                        <label type="date" class="form-control" id="tanggal_ambil" name="tanggal_ambil" value="" required>{{ $order->tanggal_ambil }}</label>
                    </div>                  
                   <button type="button" class="btn btn-primary" onclick="goBack()">Back</button>
                
            </div>
        </div>
    </div>
@endsection

<script>
    function goBack() {
        window.history.back(); // Kembali ke halaman sebelumnya
    }
</script>
