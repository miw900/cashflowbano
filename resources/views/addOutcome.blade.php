@extends('layout.db')
@section('content')
    <!-- Begin Page Content -->
<div class="container-fluid">

  <div class="card shadow mb-4">
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Tambah Pengeluaran:</h6>
    </div>
    <!-- Card Body -->
    <div class="card-body">

      <form name='kirim' method='post' action="{{ route('outcome.tambah') }}">
            @csrf
            <div class="row">
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Total:</label>
                            <input class="form-control form-control-sm" type="number" placeholder="Total..." aria-label=".form-control-sm example" name='total' required>
                            @if ($errors->has('total'))
                                <div class="text-danger">
                                    {{ $errors->first('total') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <label>Tanggal:</label>
                            <input class="form-control form-control-sm" type="date" aria-label=".form-control-sm example" name='tanggal' required>
                            @if ($errors->has('tanggal'))
                                <div class="text-danger">
                                    {{ $errors->first('tanggal') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <label>Keterangan:</label>
                    <textarea class="form-control form-control-sm" placeholder="keterangan..." aria-label=".form-control-sm example" name='keterangan' style="height: 112px" required></textarea>
                    @if ($errors->has('keterangan'))
                        <div class="text-danger">
                            {{ $errors->first('keterangan') }}
                        </div>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block mt-4" name='kirim'>Kirim</button>
        </form>




   <!-- Display Flash Messages -->
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
    </div>
  </div>

</div>

    
@endsection
