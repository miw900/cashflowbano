<!-- resources/views/income/edit.blade.php -->
@extends('layout.db')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Income</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('income.update', $income->tanggal_income) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="gopay">Gopay:</label>
                        <input type="text" class="form-control" id="gopay" name="gopay" value="{{ $income->gopay }}" required>
                    </div>
                    <div class="form-group">
                        <label for="bsi">BSI:</label>
                        <input type="text" class="form-control" id="bsi" name="bsi" value="{{ $income->bsi }}" required>
                    </div>
                    <div class="form-group">
                        <label for="cash">Cash:</label>
                        <input type="text" class="form-control" id="cash" name="cash" value="{{ $income->cash }}" required>
                    </div> 
                    <div class="form-group">
                        <label for="total">Total Income:</label>
                        <input type="text" class="form-control" id="total" name="total" value="{{ $income->total }}" required>
                    </div>                 
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
