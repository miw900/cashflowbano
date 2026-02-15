<!-- resources/views/orders/edit.blade.php -->
@extends('layout.db')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Order</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama:</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $order->nama }}" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga:</label>
                        <input type="text" class="form-control" id="harga" name="harga" value="{{ $order->harga }}" required>
                    </div>
                    <div class="form-group">
                        <label for="catatan">Catatan:</label>
                        <textarea class="form-control" id="catatan" name="catatan" required>{{ $order->catatan }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="jam_ambil">Jam Ambil:</label>
                        <input type="time" class="form-control" id="jam_ambil" name="jam_ambil" value="{{ $order->jam_ambil }}" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_ambil">Tanggal Ambil:</label>
                        <input type="date" class="form-control" id="tanggal_ambil" name="tanggal_ambil" value="{{ $order->tanggal_ambil }}" required>
                    </div>                  
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
