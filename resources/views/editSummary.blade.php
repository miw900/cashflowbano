<!-- resources/views/orders/edit.blade.php -->
@extends('layout.db')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Summary</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('summary.update', $summary->month_year) }}" method="POST">
                    @csrf
                    @method('PUT')                    
                    <div class="form-group">
                        <label for="total_income">total Income:</label>
                        <input type="text" class="form-control" id="total_income" name="total_income" value="{{ $summary->total_income }}" required>
                    </div>
                    <div class="form-group">
                        <label for="total_outcome">Total Outcome:</label>
                        <textarea class="form-control" id="catatan" name="total_outcome" required>{{ $summary->total_outcome }}</textarea>
                    </div>                    
                    <div class="form-group">  
                        <label for="total">Total:</label>                      
                        <label type="" class="form-control" id="total" name="total" value="{{ $summary->total }}" required>{{ $summary->total }}</label>
                    </div>                  
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
