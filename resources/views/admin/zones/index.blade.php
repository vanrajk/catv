@extends('layouts.app')

@section('title', 'Zones')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0">ઝોનો</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addZoneModal">
                <i class="bi bi-plus-circle"></i> ઝોન ઉમેરો
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
                            <th>ઝોન</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($zones as $zone)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $zone->name }}</td>
                                <td>
                                    <button
                                        class="btn btn-sm btn-warning"
                                        onclick="editZone({{ $zone->id }}, '{{ $zone->name }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form action="{{ route('zones.destroy', $zone->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this zone?')">
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
                                <td colspan="3" class="text-center">No zones found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- ADD ZONE MODAL -->
<div class="modal fade" id="addZoneModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('zones.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">ઝોન ઉમેરો</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">જોણે</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT ZONE MODAL -->
<div class="modal fade" id="editZoneModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editZoneForm" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">ઝોન સુધારો</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">ઝોન</label>
                    <input type="text" name="name" id="editZoneName" class="form-control" required>
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
    function editZone(id, name) {
        document.getElementById('editZoneName').value = name;
        document.getElementById('editZoneForm').action = `/admin/zones/${id}`;
        new bootstrap.Modal(document.getElementById('editZoneModal')).show();
    }
</script>
@endpush
