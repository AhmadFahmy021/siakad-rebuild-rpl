@extends('layouts.main')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Siswa</h4>
                <h5 class="text-muted fw-normal mt-1 mb-0">Selamat datang, <strong>{{ Auth::user()->name }}</strong></h5>
            </div>
        </div>
    </div>
@endsection
