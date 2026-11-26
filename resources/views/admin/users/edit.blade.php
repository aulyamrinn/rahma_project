@extends('layouts.app')

@section('title', 'Edit Pengguna: ' . $user->name)
@section('page-title', 'Edit Data Pengguna')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card sc-card mb-3 bg-light border-0">
            <div class="card-body py-3 px-4 d-flex flex-column flex-sm-row justify-content-between gap-2 small">
                <div>
                    <i class="bi bi-calendar-plus text-primary me-1"></i>
                    <strong>Akun Dibuat:</strong> {{ $user->created_at->translatedFormat('d F Y, H:i') }} WIB
                </div>
                <div>
                    <i class="bi bi-clock-history text-info me-1"></i>
                    <strong>Terakhir Diperbarui:</strong>
                    @if($user->updated_at && $user->updated_at->ne($user->created_at))
                        {{ $user->updated_at->translatedFormat('d F Y, H:i') }} WIB ({{ $user->updated_at->diffForHumans() }})
                    @else
                        Belum pernah diubah
                    @endif
                </div>
            </div>
        </div>

        <div class="card sc-card">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-primary fs-5"></i>
                    <h6 class="mb-0 fw-bold">Edit Pengguna: {{ $user->name }}</h6>
                </div>
                <a href="{{ route('admin.pengguna.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.pengguna.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('admin.users._form', ['user' => $user])

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-light border px-4 rounded-pill">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold" style="background-color: var(--sc-blue); border-color: var(--sc-blue);">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
