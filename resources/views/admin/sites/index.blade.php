@extends('layouts.app')

@section('title', 'Sites')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0">આઈડીઑ</h3>
            <button class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#addSiteModal">
                <i class="bi bi-plus-circle"></i> આઈડી ઉમેરો
            </button>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th>આઈડી</th>
                            <th>વિગત</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sites as $site)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $site->site_code }}</td>
                                <td>{{ $site->description ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="editSite(
                                            {{ $site->id }},
                                            '{{ $site->site_code }}',
                                            '{{ $site->description }}'
                                        )">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form action="{{ route('sites.destroy', $site->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this site?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No sites found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- ADD SITE MODAL -->
<div class="modal fade" id="addSiteModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('sites.store') }}" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">આઈડી ઉમેરો</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">આઈડી</label>
                    <input type="text"
                           name="site_code"
                           class="form-control @error('site_code') is-invalid @enderror"
                           value="{{ old('site_code') }}"
                           required>
                    @error('site_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">વિગત</label>
                    <input type="text"
                           name="description"
                           class="form-control"
                           value="{{ old('description') }}">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save</button>
            </div>

        </form>
    </div>
</div>

<!-- EDIT SITE MODAL -->
<div class="modal fade" id="editSiteModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editSiteForm" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">આઈડી સુધારો</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">આઈડી</label>
                    <input type="text" name="site_code" id="editSiteCode"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">વિગત</label>
                    <input type="text" name="description" id="editSiteDescription"
                           class="form-control">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-success">Update</button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function editSite(id, code, description) {
        document.getElementById('editSiteForm').action = `/admin/sites/${id}`;
        document.getElementById('editSiteCode').value = code;
        document.getElementById('editSiteDescription').value = description ?? '';

        new bootstrap.Modal(document.getElementById('editSiteModal')).show();
    }

    @if($errors->any())
        new bootstrap.Modal(document.getElementById('addSiteModal')).show();
    @endif
</script>
@endpush
