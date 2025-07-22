@if (isset($data))
    <form action="{{ route('dashboard.permissions.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    @else
        <form action="{{ route('dashboard.permissions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
@endif

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        @if (isset($data))
            Chỉnh sửa
        @else
            Thêm mới
        @endif
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="form-group row">
        <label class="col-12">{{ __('Danh mục cha') }}</label>
        <div class="col-6">
            <select name="parent_id" class="form-control select2 col-md-5" id="kt_select2_2" style="width: 100%">
                <option value=''>-- Không chọn --</option>
                @if (!empty(old('parent_id')))
                    {!! \App\Http\Controllers\Spatie\RoleController::buildMenuDropdownList($dataCategory, old('parent_id')) !!}
                @elseif(isset($id))
                    {!! \App\Http\Controllers\Spatie\RoleController::buildMenuDropdownList($dataCategory, $id) !!}
                @else
                    <?php $itSelect = []; ?>
                    @if (isset($data))
                        <?php array_push($itSelect, $data->parent_id); ?>
                    @endif
                    {!! \App\Http\Controllers\Spatie\RoleController::buildMenuDropdownList($dataCategory, $itSelect) !!}
                @endif
            </select>
            @if ($errors->has('parent_id'))
                <div class="form-control-feedback">{{ $errors->first('parent_id') }}</div>
            @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('title') ? 'has-danger' : '' }}">
        <label class="form-control-label">{{ __('Tiêu đề') }}</label>
        <input type="text" class="form-control" name="title"
            value="{{ old('title', isset($data) ? $data->title : null) }}">
        @if ($errors->has('title'))
            <div class="form-control-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
    <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
        <label class="form-control-label">{{ __('Name') }}</label>
        <input type="text" class="form-control" name="name"
            value="{{ old('name', isset($data) ? $data->name : null) }}">
        @if ($errors->has('name'))
            <div class="form-control-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Hủy') }}</button>
    <button type="submit" class="btn btn-success m-btn m-btn--custom m-btn--icon">
        @if (isset($data))
            {{ __(' Chỉnh sửa') }}
        @else
            {{ __(' Thêm mới') }}
        @endif
    </button>
</div>
</form>
