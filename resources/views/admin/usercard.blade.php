@extends('layout.dash')

@section('konten')
<div class="container">
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="bi bi-person-plus"></i> Add User
    </button>

    <div class="row">
        @foreach ($users as $user)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <img src="{{ $user->gambar ? asset('storage/'.$user->gambar) : 'default.jpg' }}" class="card-img-top" alt="User Image">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="card-text">{{ $user->email }}</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                        <i class="bi bi-eye"></i> View
                    </button>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                    <form action="{{ route('usercontrol.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('usercontrol.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="name" placeholder="Name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="alamat" placeholder="Address" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="no_telp" placeholder="Phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <select name="role" class="form-control" required>
                            <option value="a">Admin</option>
                            <option value="h">HR</option>
                            <option value="p">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="gambar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-success w-100">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@foreach ($users as $user)
<!-- View User Modal -->
<div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-labelledby="userModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel{{ $user->id }}">{{ $user->name }}'s Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Address:</strong> {{ $user->alamat }}</p>
                <p><strong>Phone:</strong> {{ $user->no_telp }}</p>
                <img src="{{ $user->gambar ? asset('storage/'.$user->gambar) : 'default.jpg' }}" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('usercontrol.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="mb-3">
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="alamat" value="{{ $user->alamat }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="no_telp" value="{{ $user->no_telp }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <select name="role" class="form-control" required>
                            <option value="a" {{ $user->role == 'a' ? 'selected' : '' }}>Admin</option>
                            <option value="h" {{ $user->role == 'h' ? 'selected' : '' }}>HR</option>
                            <option value="p" {{ $user->role == 'p' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" placeholder="New Password (Leave blank to keep current)" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="file" name="gambar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-warning w-100">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
