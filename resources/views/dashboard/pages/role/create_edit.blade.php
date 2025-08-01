@if (isset($data))
    <form action="{{ route('dashboard.roles.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    @else
        <form action="{{ route('dashboard.roles.store') }}" method="POST" enctype="multipart/form-data">
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
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<div class="modal-body">
    {{-- Danh mục cha --}}
    <div class="form-group row">
        <label class="col-12">{{ __('Danh mục cha') }}</label>
        <div class="col-12">
            <select name="parent_id" class="form-control select2 col-md-5" id="kt_select2_2" style="width: 100%">
                @if (!empty(old('parent_id')))
                    {!! \App\Http\Controllers\Spatie\RoleController::buildMenuDropdownList($dataCategory, old('parent_id')) !!}
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
    {{-- title --}}
    <div class="form-group {{ $errors->has('title') ? 'has-danger' : '' }}">
        <label class="form-control-label">{{ __('Tiêu đề') }}</label>
        <input type="text" class="form-control" name="title"
            value="{{ old('title', isset($data) ? $data->title : null) }}" >
        @if ($errors->has('title'))
            <div class="form-control-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
    {{-- name --}}
    <div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
        <label class="form-control-label">{{ __('Name') }}</label>
        <input type="text" class="form-control" name="name"
            value="{{ old('name', isset($data) ? $data->name : null) }}">
        @if ($errors->has('name'))
            <div class="form-control-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>
    {{-- description --}}
    <div class="form-group row">
        <div class="col-12 col-md-12">
            <label for="locale">{{ __('Mô tả') }}:</label>
            <textarea id="description" name="description" class="form-control ckeditor-basic" data-height="150"
                data-startup-mode="">{{ old('description', isset($data) ? $data->description : null) }}</textarea>
            @if ($errors->has('description'))
                <span class="form-text text-danger">{{ $errors->first('description') }}</span>
            @endif
        </div>
    </div>
    @if (isset($data))
        <div class="text-center">{{ __('Chú ý: bạn đang chỉnh sửa vai trò:') }} <span
                class="text-danger">{{ old('title', isset($data) ? $data->title : null) }}</span> </div>
    @endif
    {{-- PHẦN CHỌN PERMISSION --}}
    <div class="mb-2 fw-bold text-primary">
        {{ __('Các quyền mà vai trò này sẽ được sử dụng') }}
    </div>
    <div id="kt_tree_3" class="tree-demo"></div>
    <input type="hidden" id="permission_ids" name="permission_ids"
        value="{{ implode(',', old('permission_ids', isset($permissionsSelected) ? $permissionsSelected : [])) }}"
        data-permissions-json='{!! $permissionsJson !!}'>
    <!-- JSTree CSS và JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
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
