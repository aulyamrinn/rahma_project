/**
 * SchoolCare - Sistem Pengaduan Sarana dan Prasarana Sekolah
 * JavaScript Helpers & Interactions
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('appSidebar');

    if (sidebarToggle && sidebar) {
        let backdrop = null;

        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');

            if (sidebar.classList.contains('show')) {
                backdrop = document.createElement('div');
                backdrop.className = 'sidebar-backdrop';
                document.body.appendChild(backdrop);

                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    if (backdrop) backdrop.remove();
                });
            } else if (backdrop) {
                backdrop.remove();
            }
        });
    }

    // 2. Image Preview for File Inputs
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const previewContainer = document.getElementById('previewContainer');

    if (photoInput && photoPreview && previewContainer) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                if (file.size > 3 * 1024 * 1024) {
                    alert('Ukuran file foto melebihi batas maksimal 3 MB.');
                    this.value = '';
                    previewContainer.classList.add('d-none');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    photoPreview.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('d-none');
            }
        });
    }

    // 3. Delete Confirmation Dialog
    const deleteButtons = document.querySelectorAll('.btn-confirm-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const form = this.closest('form');
            const message = this.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // 4. Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 6000);
    });
});

// Helper for quick login filling
window.quickFillLogin = function (login, password) {
    const loginField = document.getElementById('login');
    const passwordField = document.getElementById('password');

    if (loginField && passwordField) {
        loginField.value = login;
        passwordField.value = password;
        loginField.focus();
    }
};

