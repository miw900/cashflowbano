@extends('layout.db')
@section('content')
    <!-- Begin Page Content -->
<div class="container-fluid">

  <div class="card shadow mb-4">
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Tambah Pesanan:</h6>
    </div>
    <!-- Card Body -->
    <div class="card-body">

      <form name='kirim' method='post' action="{{ route('orders.tambah') }}">
            @csrf
            <div class="row">
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Nama:</label>
                            <input class="form-control form-control-sm" type="text" placeholder="Nama.." aria-label=".form-control-sm example" name='nama' required>
                            @if ($errors->has('nama'))
                                <div class="text-danger">
                                    {{ $errors->first('nama') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <label>Harga:</label>
                            <input class="form-control form-control-sm" type="text" placeholder="Harga..." aria-label=".form-control-sm example" name='harga' required>
                            @if ($errors->has('harga'))
                                <div class="text-danger">
                                    {{ $errors->first('harga') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-6">
                            <label>Jam Ambil:</label>
                            <input class="form-control form-control-sm" type="time" placeholder="Jam Ambil..." aria-label=".form-control-sm example" name='jam_ambil' required>
                            @if ($errors->has('jam_ambil'))
                                <div class="text-danger">
                                    {{ $errors->first('jam_ambil') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <label>Tanggal Ambil:</label>
                            <input class="form-control form-control-sm" type="date" aria-label=".form-control-sm example" name='tanggal_ambil' required>
                            @if ($errors->has('tanggal_ambil'))
                                <div class="text-danger">
                                    {{ $errors->first('tanggal_ambil') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <label>Catatan:</label>
                    <textarea class="form-control form-control-sm" placeholder="Catatan..." aria-label=".form-control-sm example" name='catatan' style="height: 112px" required></textarea>
                    @if ($errors->has('catatan'))
                        <div class="text-danger">
                            {{ $errors->first('catatan') }}
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
