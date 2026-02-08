@extends('layouts.app')

@section('title', 'Areas')

@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0">વિસ્તારો</h3>
            <button class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#addAreaModal">
                <i class="bi bi-plus-circle"></i> વિસ્તાર ઉમેરો
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
                            <th>વિસ્તાર</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($areas as $area)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $area->zone->name }}</td>
                                <td>{{ $area->name }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="editArea(
                                            {{ $area->id }},
                                            {{ $area->zone_id ?? ''}},
                                            '{{ $area->name ?? '' }}'
                                        )">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form action="{{ route('areas.destroy', $area->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this area?')">
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
                                <td colspan="4" class="text-center">No areas found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- ADD AREA MODAL -->
<div class="modal fade" id="addAreaModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('areas.store') }}" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">વિસ્તાર ઉમેરો</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">ઝોન</label>
                    <select name="zone_id"
                            class="form-select @error('zone_id') is-invalid @enderror"
                            required>
                        <option value="">ઝોન પસંદ કરો</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}"
                                {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('zone_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">વિસ્તાર</label>
                    <input type="text"
                           name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save</button>
            </div>

        </form>
    </div>
</div>

<!-- EDIT AREA MODAL -->
<div class="modal fade" id="editAreaModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="editAreaForm" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">વિસ્તાર સુધારો</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">ઝોન</label>
                    <select name="zone_id" id="editAreaZone" class="form-select" required>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">વિસ્તાર</label>
                    <input type="text" name="name" id="editAreaName"
                           class="form-control" required>
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
    function editArea(id, zoneId, name) {
        document.getElementById('editAreaForm').action = `/admin/areas/${id}`;
        document.getElementById('editAreaZone').value = zoneId;
        document.getElementById('editAreaName').value = name;

        new bootstrap.Modal(document.getElementById('editAreaModal')).show();
    }

    @if($errors->any())
        new bootstrap.Modal(document.getElementById('addAreaModal')).show();
    @endif
</script>
@endpush
