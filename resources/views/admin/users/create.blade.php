@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('page-title', 'Tambah Pengguna Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card sc-card">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus-fill text-primary fs-5"></i>
                    <h6 class="mb-0 fw-bold">Formulir Tambah Pengguna</h6>
                </div>
                <a href="{{ route('admin.pengguna.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.pengguna.store') }}" method="POST">
                    @csrf

                    @include('admin.users._form')

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-light border px-4 rounded-pill">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                            <i class="bi bi-check-lg me-1"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
