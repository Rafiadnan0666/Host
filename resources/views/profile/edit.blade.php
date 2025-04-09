@extends('layout.dash')

@section('konten')
<div class="my-5">
    <!-- 👤 Profile Section -->
    <div class="card shadow-sm mb-5 p-4 border-0 rounded-4">
        <div class="d-flex align-items-center">
            <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                 class="rounded-circle shadow-sm me-4" width="90" height="90" alt="Profile Image">
            <div>
                <h3 class="mb-0">{{ $user->name }}</h3>
                <small class="text-muted">{{ $user->email }}</small>
                <p class="mt-2">{{ $user->bio ?? 'No bio added yet. Go stand out!' }}</p>
            </div>
        </div>
    </div>

    <!-- 📚 Edit Profile Section -->
    <div class="card shadow-sm mb-5 p-4 border-0 rounded-4">
        <h5 class="mb-3"><i class="bi bi-pencil-square me-2"></i>Edit Profile Information</h5>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Save Changes</button>
            </div>
        </form>
    </div>

    <!-- 🔒 Change Password Section -->
    <div class="card shadow-sm mb-5 p-4 border-0 rounded-4">
        <h5 class="mb-3"><i class="bi bi-lock me-2"></i>Change Password</h5>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-warning"><i class="bi bi-lock-fill me-2"></i>Change Password</button>
            </div>
        </form>
    </div>

    <!-- 🗑️ Delete Account Section -->
    <div class="card shadow-sm mb-5 p-4 border-0 rounded-4">
        <h5 class="mb-3"><i class="bi bi-trash me-2"></i>Delete Account</h5>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            <p class="text-danger">Warning: Deleting your account is permanent and cannot be undone.</p>
            <div class="mb-3">
                <button type="submit" class="btn btn-danger"><i class="bi bi-trash-fill me-2"></i>Delete Account</button>
            </div>
        </form>
    </div>
</div>
@endsection
