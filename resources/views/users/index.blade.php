@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Users</div>

            <div class="card-body">
                <label for="status">Status:</label>
                <select class="form-control" id="status">
                    <option value="">-- All --</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush

@push('scripts')
<script type="module">
    $('#status').on('change', function() {
        console.log($('#status').val());
        window.LaravelDataTables["users-table"].draw();
	});
</script>
@endpush
