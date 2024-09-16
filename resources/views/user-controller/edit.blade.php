@extends('layouts.main')

@if (Auth::user()->role === 'admin')
@section('navbar')
<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a class="nav-link" href="{{ route('admin.index') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
</div>

<!-- Nav Item - Utilities Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true"
        aria-controls="collapseUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>User Control</span>
    </a>
    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ route('user-control.index') }}">User Controller</a>
        </div>
    </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
        aria-controls="collapsePages">
        <i class="fas fa-fw fa-folder"></i>
        <span>Student Control</span>
    </a>
    <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ route('student.data') }}">Student Data</a>
        </div>
    </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>
@endsection
@endif

@section('main')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Add User</h4>
            <form onsubmit="confirmUpdate(event)" class="forms-sample" method="POST"
                action="{{ route('user-control.update', [$user->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- or @method('PATCH') depending on your route definition -->
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <div class="d-flex align-items-center">
                        <input class="form-control" type="file" id="image" name="image" accept="image/*">
                        <img id="imagePreview" src="{{ $user->getThumbnail() }}" alt="Image Preview"
                            style="display: ; margin-left: 10px; width: 100px; height: 100px; object-fit: cover;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="fullname"
                        placeholder="Kevin Example" name="name" value="{{ $user['name'] }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        placeholder="Email" name="email" value="{{ $user['email'] }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        placeholder="Password" name="password" required>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Password Confirmation</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" placeholder="password_confirmation" name="password_confirmation"
                        required>
                    @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}
                <a href="{{ route('user-control.index') }}" class="btn btn-light">Back</a>
                <button type="submit" class="btn btn-primary me-2">Update Info User</button>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
    function confirmUpdate(event) {
       event.preventDefault();

       Swal.fire({
           title: 'Do you want to update it?',
           text: 'Updated data cannot be recovered!',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#d33',
           cancelButtonColor: '#3085d6',
           confirmButtonText: 'Yes, update it!',
           cancelButtonText: 'Cancel'
       }).then((result) => {
           if (result.isConfirmed) {
               event.target.submit();
           } else {
               Swal.fire('Your data is safe!');
           }
       });
   }
</script>
