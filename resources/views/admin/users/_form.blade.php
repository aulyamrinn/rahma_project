@php
    $isEdit = isset($user);
    $selectedRole = old('role', $isEdit ? $user->role : 'siswa');
@endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label fw-bold text-dark">
            Nama Lengkap <span class="text-danger">*</span>
        </label>
        <input type="text"
               name="name"
               id="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $isEdit ? $user->name : '') }}"
               placeholder="Contoh: Rahma Putri"
               required
               autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="role" class="form-label fw-bold text-dark">
            Role Pengguna <span class="text-danger">*</span>
        </label>
        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="siswa" {{ $selectedRole === 'siswa' ? 'selected' : '' }}>Siswa (Pelapor Sarana)</option>
            <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>Admin (Pengelola Sarpras)</option>
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="email" class="form-label fw-bold text-dark">
        Alamat Email <span class="text-danger">*</span>
    </label>
    <input type="email"
           name="email"
           id="email"
           class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $isEdit ? $user->email : '') }}"
           placeholder="nama@schoolcare.sch.id"
           required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Fields specifically for Siswa -->
<div class="row {{ $selectedRole === 'admin' ? 'd-none' : '' }}" id="siswaFields">
    <div class="col-md-6 mb-3">
        <label for="nisn" class="form-label fw-bold text-dark">
            NISN <span class="text-danger">*</span>
        </label>
        <input type="text"
               name="nisn"
               id="nisn"
               class="form-control @error('nisn') is-invalid @enderror"
               value="{{ old('nisn', $isEdit ? $user->nisn : '') }}"
               placeholder="Contoh: 20241005"
               {{ $selectedRole === 'siswa' ? 'required' : '' }}>
        @error('nisn')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="kelas" class="form-label fw-bold text-dark">
            Kelas / Jurusan <span class="text-danger">*</span>
        </label>
        <input type="text"
               name="kelas"
               id="kelas"
               class="form-control @error('kelas') is-invalid @enderror"
               value="{{ old('kelas', $isEdit ? $user->kelas : '') }}"
               placeholder="Contoh: XII RPL 1"
               {{ $selectedRole === 'siswa' ? 'required' : '' }}>
        @error('kelas')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label fw-bold text-dark">
            Kata Sandi
            @if(!$isEdit)
                <span class="text-danger">*</span>
            @else
                <span class="text-muted fw-normal small">(Opsional)</span>
            @endif
        </label>
        <input type="password"
               name="password"
               id="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengubah sandi' : 'Minimal 6 karakter' }}"
               {{ $isEdit ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-4">
        <label for="password_confirmation" class="form-label fw-bold text-dark">
            Konfirmasi Kata Sandi
            @if(!$isEdit)
                <span class="text-danger">*</span>
            @endif
        </label>
        <input type="password"
               name="password_confirmation"
               id="password_confirmation"
               class="form-control"
               placeholder="Ulangi kata sandi"
               {{ $isEdit ? '' : 'required' }}>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const siswaFields = document.getElementById('siswaFields');
    const nisnInput = document.getElementById('nisn');
    const kelasInput = document.getElementById('kelas');

    function toggleSiswaFields() {
        const isSiswa = roleSelect.value === 'siswa';
        siswaFields.classList.toggle('d-none', !isSiswa);
        nisnInput.required = isSiswa;
        kelasInput.required = isSiswa;
    }

    roleSelect.addEventListener('change', toggleSiswaFields);
    toggleSiswaFields();
});
</script>
@endpush
