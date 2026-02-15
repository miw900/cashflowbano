<!-- resources/views/orders/edit.blade.php -->
@extends('layout.db')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Pengeluaran</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('outcome.update', $Outcome->id) }}" method="POST">
                    @csrf
                    @method('PUT')                    
                    <div class="form-group">
                        <label for="total">Total:</label>
                        <input type="text" class="form-control" id="total" name="total" value="{{ $Outcome->total }}" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan:</label>
                        <textarea class="form-control" id="catatan" name="keterangan" required>{{ $Outcome->keterangan }}</textarea>
                    </div>                    
                    <div class="form-group">                        
                        <input type="hidden" class="form-control" id="tanggal" name="tanggal" value="{{ $Outcome->tanggal }}" required>
                    </div>                  
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
