@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Manajemen Admin</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Admin</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Daftar Admin</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#createAdminModal">
                                <i class="bi bi-plus-circle"></i> Tambah Admin
                            </button>
                        </div>

                        <!-- Success Alert -->
                        <div class="alert alert-success alert-dismissible fade show d-none" id="successAlert">
                            <i class="bi bi-check-circle-fill"></i>
                            <span id="successMessage"></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <!-- Error Alert -->
                        <div class="alert alert-danger alert-dismissible fade show d-none" id="errorAlert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span id="errorMessage"></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Dibuat</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admins as $index => $admin)
                                        <tr>
                                            <th scope="row">{{ $index + 1 }}</th>
                                            <td>{{ $admin->name }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>{{ $admin->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info btn-edit"
                                                        data-id="{{ $admin->id }}" title="Edit Admin">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                        data-id="{{ $admin->id }}" data-name="{{ $admin->name }}"
                                                        title="Hapus Admin">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="bi bi-inbox"></i> Belum ada data admin
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Create Admin Modal -->
    <div class="modal fade" id="createAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus"></i> Tambah Admin Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createAdminForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="create_name" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="create_name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="create_email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="create_email" name="email" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="create_password" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="create_password" name="password" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="create_password_confirmation" class="col-sm-3 col-form-label">Konfirmasi</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="create_password_confirmation"
                                    name="password_confirmation" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="createSubmitBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            <i class="bi bi-check-circle"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil-square"></i> Edit Admin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAdminForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_admin_id" name="admin_id">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="edit_name" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="edit_email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="edit_password" class="col-sm-3 col-form-label">Password Baru</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="edit_password" name="password">
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="edit_password_confirmation" class="col-sm-3 col-form-label">Konfirmasi</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="edit_password_confirmation"
                                    name="password_confirmation">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-warning" id="editSubmitBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            <i class="bi bi-pencil-square"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Function to show alerts
            function showAlert(type, message) {
                const alertId = type === 'success' ? '#successAlert' : '#errorAlert';
                const messageId = type === 'success' ? '#successMessage' : '#errorMessage';

                $(messageId).text(message);
                $(alertId).removeClass('d-none');

                // Auto hide after 5 seconds
                setTimeout(() => {
                    $(alertId).addClass('d-none');
                }, 5000);
            }

            // Function to clear form errors
            function clearFormErrors(formId) {
                $(formId + ' .form-control').removeClass('is-invalid');
                $(formId + ' .invalid-feedback').text('');
            }

            // Function to show form errors
            function showFormErrors(formId, errors) {
                $.each(errors, function(field, messages) {
                    const input = $(formId + ' [name="' + field + '"]');
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(messages[0]);
                });
            }

            // Function to toggle button loading state
            function toggleButtonLoading(button, loading) {
                const spinner = button.find('.spinner-border');
                const icon = button.find('i:not(.spinner-border)');

                if (loading) {
                    spinner.removeClass('d-none');
                    icon.addClass('d-none');
                    button.prop('disabled', true);
                } else {
                    spinner.addClass('d-none');
                    icon.removeClass('d-none');
                    button.prop('disabled', false);
                }
            }

            // Password validation untuk edit form
            function validateEditPassword() {
                const password = $('#edit_password').val();
                const confirmation = $('#edit_password_confirmation').val();

                // Clear previous errors
                $('#edit_password, #edit_password_confirmation').removeClass('is-invalid');
                $('#edit_password').siblings('.invalid-feedback').text('');
                $('#edit_password_confirmation').siblings('.invalid-feedback').text('');

                // Jika password diisi
                if (password.length > 0) {
                    let isValid = true;

                    // Validasi panjang password
                    if (password.length < 6) {
                        $('#edit_password').addClass('is-invalid');
                        $('#edit_password').siblings('.invalid-feedback').text('Password minimal 6 karakter.');
                        isValid = false;
                    }

                    // Validasi konfirmasi password
                    if (confirmation.length === 0) {
                        $('#edit_password_confirmation').addClass('is-invalid');
                        $('#edit_password_confirmation').siblings('.invalid-feedback').text(
                            'Konfirmasi password diperlukan.');
                        isValid = false;
                    } else if (password !== confirmation) {
                        $('#edit_password_confirmation').addClass('is-invalid');
                        $('#edit_password_confirmation').siblings('.invalid-feedback').text(
                            'Konfirmasi password tidak cocok.');
                        isValid = false;
                    }

                    return isValid;
                }

                // Jika password kosong, hapus juga konfirmasi
                if (password.length === 0 && confirmation.length > 0) {
                    $('#edit_password_confirmation').val('');
                }

                return true;
            }

            // Real-time password validation untuk edit form
            $('#edit_password, #edit_password_confirmation').on('keyup blur', function() {
                validateEditPassword();
            });

            // Create Admin Form
            $('#createAdminForm').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#createSubmitBtn');
                toggleButtonLoading(submitBtn, true);
                clearFormErrors('#createAdminForm');

                $.ajax({
                    url: '/admin/admins/store',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#createAdminModal').modal('hide');
                            $('#createAdminForm')[0].reset();
                            showAlert('success', response.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            showFormErrors('#createAdminForm', xhr.responseJSON.errors);
                        } else {
                            showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    complete: function() {
                        toggleButtonLoading(submitBtn, false);
                    }
                });
            });

            // Edit Admin Button Click
            $(document).on('click', '.btn-edit', function() {
                const adminId = $(this).data('id');

                // Clear form terlebih dahulu
                clearFormErrors('#editAdminForm');
                $('#edit_password').val('');
                $('#edit_password_confirmation').val('');

                console.log('Loading admin data for ID:', adminId);

                $.ajax({
                    url: '/admin/admins/' + adminId + '/edit',
                    type: 'GET',
                    success: function(admin) {
                        console.log('Admin data received:', admin);

                        $('#edit_admin_id').val(admin.id);
                        $('#edit_name').val(admin.name);
                        $('#edit_email').val(admin.email);

                        // Pastikan modal terbuka setelah data dimuat
                        $('#editAdminModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('Error loading admin data:', xhr);
                        showAlert('error', 'Gagal memuat data admin.');
                    }
                });
            });

            // Edit Admin Form
            $('#editAdminForm').on('submit', function(e) {
                e.preventDefault();

                // Validasi password jika diisi
                if ($('#edit_password').val().length > 0 && !validateEditPassword()) {
                    return false;
                }

                const adminId = $('#edit_admin_id').val();
                const submitBtn = $('#editSubmitBtn');
                toggleButtonLoading(submitBtn, true);
                clearFormErrors('#editAdminForm');

                // Prepare form data
                let formData = $(this).serialize();

                // Jika password kosong, hapus dari form data untuk menghindari validasi
                const password = $('#edit_password').val();
                if (password.length === 0) {
                    // Rebuild form data tanpa password fields
                    const formArray = $(this).serializeArray();
                    const filteredData = formArray.filter(item =>
                        item.name !== 'password' && item.name !== 'password_confirmation'
                    );
                    formData = $.param(filteredData);
                }

                $.ajax({
                    url: '/admin/admins/' + adminId,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#editAdminModal').modal('hide');
                            showAlert('success', response.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            if (xhr.responseJSON.errors) {
                                showFormErrors('#editAdminForm', xhr.responseJSON.errors);
                            } else {
                                showAlert('error', xhr.responseJSON.message ||
                                    'Validasi gagal.');
                            }
                        } else {
                            showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    complete: function() {
                        toggleButtonLoading(submitBtn, false);
                    }
                });
            });

            // Delete Admin Button Click
            $(document).on('click', '.btn-delete', function() {
                const adminId = $(this).data('id');
                const adminName = $(this).data('name');

                if (confirm('Yakin ingin menghapus admin "' + adminName +
                        '"? Tindakan ini tidak dapat dibatalkan.')) {

                    $.ajax({
                        url: '/admin/admins/' + adminId,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                showAlert('success', response.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function() {
                            showAlert('error', 'Gagal menghapus admin. Silakan coba lagi.');
                        }
                    });
                }
            });

            // Reset form when modal is hidden
            $('#createAdminModal, #editAdminModal').on('hidden.bs.modal', function() {
                const formId = $(this).find('form').attr('id');
                $('#' + formId)[0].reset();
                clearFormErrors('#' + formId);
            });
        });
    </script>
@endpush
