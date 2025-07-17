@if(isset($data))
<form action="{{ route('dashboard.roles.update', $data->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
@else
<form action="{{ route('dashboard.roles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
@endif
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        @if(isset($data))
            Chỉnh sửa
        @else
            Thêm mới
        @endif
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<div class="modal-body">
    <div class="form-group {{ $errors->has('parent_id')? 'has-danger':'' }}">
        <label class="form-control-label">{{__('Danh mục cha')}}</label>
        <select name="parent_id" class="form-control select2 col-md-5" id="kt_select2_2">
            <option value="0">-- {{__('Không chọn')}} --</option>
            @if( !empty(old('parent_id')) )
                {!!\App\Library\Helpers::buildMenuDropdownList($dataCategory,old('parent_id')) !!}
            @else
                <?php $itSelect = [] ?>
                @if(isset($data))
                    <?php array_push($itSelect, $data->parent_id)?>
                @endif
                {!!\App\Library\Helpers::buildMenuDropdownList($dataCategory,$itSelect) !!}
            @endif
        </select>
        @if($errors->has('parent_id'))
            <div class="form-control-feedback">{{ $errors->first('parent_id') }}</div>
        @endif
    </div>
    <div class="form-group {{ $errors->has('title')? 'has-danger':'' }}">
        <label class="form-control-label">{{__('Tiêu đề')}}</label>
        <input type="text" class="form-control" name="title" value="{{old('title', isset($data) ? $data->title : null)}}" autofocus="true">
        @if($errors->has('title'))
            <div class="form-control-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
    <div class="form-group {{ $errors->has('name')? 'has-danger':'' }}">
        <label class="form-control-label">{{__('Name')}}</label>
        <input type="text" class="form-control" name="name" value="{{old('name', isset($data) ? $data->name : null)}}">
        @if($errors->has('name'))
            <div class="form-control-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Hủy')}}</button>
    <button type="submit" class="btn btn-success m-btn m-btn--custom m-btn--icon">
        @if(isset($data))
            {{__(' Chỉnh sửa')}}
        @else
            {{__(' Thêm mới')}}
        @endif
    </button>
</div>
</form>