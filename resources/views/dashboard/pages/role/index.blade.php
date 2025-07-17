@extends('dashboard.layouts.layout')

@section('main-content')
    <style>
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f6;
            border: 1px solid #e2e2e5;
            border-radius: 3px;
        }

        *,
        :after,
        :before {
            box-sizing: border-box;
        }

        .nested-list-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* Hoặc 'flex-start' nếu muốn nút gần tên hơn */
            gap: 8px;
            /* Khoảng cách giữa các phần tử */
            padding: 5px 10px;
            height: auto;
            /* Bỏ cố định 42px nếu chữ dài bị cắt */
        }
    </style>
    <div class="page-content" id="kt_page_sticky_card">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8">
                    <div class="well">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="lead text-right">
                                <div class="float-right">
                                    <a href="#" id="nestable-menu-action" data-action="collapse-all"
                                        class="btn btn-info m-btn">
                                        {{ __('Thu gọn') }}
                                    </a>
                                    <a href="#" id="nestable-menu-checkall" data-action="0" class="btn btn-primary m-btn">
                                        {{ __('Chọn tất cả') }}
                                    </a>
                                    <a href="#" class="btn btn-danger m-btn delete_selected">
                                        {{ __('Xóa mục đã chọn') }}
                                    </a>
                                </div>
                            </div>
                            <button type="button" data-url="{{ route('dashboard.roles.create') }}" class="btn btn-success font-weight-bolder loadModal_toggle">
                                <i class="fas fa-plus-circle icon-md"></i> {{__('Thêm mới')}}
                            </button>
                        </div>
                        <form action="{{ route('dashboard.roles.order') }}" method="POST" id="nestable-form">
                            @csrf
                            <div class="dd" id="nestable">
                                {!! $datatable !!}
                            </div>
                        </form>

                    </div>
                </div>

                <div class="col-sm-4 d-none d-sm-block">
                    <div class="well">
                        <div class="m-demo-icon">
                            <i class="flaticon-light icon-lg"></i> {{ __('Kéo thả để sắp xếp quyền') }}
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal load --}}
    <div class="modal fade" id="loadModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>

    {{-- Modal xóa --}}
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dashboard.roles.destroy', 0) }}" method="POST" class="form-horizontal">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Xác nhận thao tác') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="ki ki-close"></i>
                        </button>
                    </div>

                    <div class="modal-body">{{ __('Bạn thực sự muốn xóa?') }}</div>

                    <div class="modal-footer">
                        <input type="hidden" name="id" class="id" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Hủy') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Xóa') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        const PATH_ROOT = "{{ url('/') }}";
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nestable2@1.6.0/jquery.nestable.min.js"></script>

    <script>
        $(document).ready(function() {

            // Load modal edit/create
            $(document).on('click', '.loadModal_toggle, .edit_toggle', function(e) {
                e.preventDefault();
                const url = $(this).data("url");
                $('#loadModal .modal-content').empty().load(url, function() {
                    $('#loadModal').modal('show');
                    // Nếu dùng select2
                    $("#kt_select2_2, #kt_select2_3, #kt_select2_4").select2();
                });
            });

            // Xử lý nút xóa đơn
            $(document).on('click', '.delete_toggle', function(e) {
                e.preventDefault();
                const id = $(this).attr('rel');
                $('#deleteModal .id').val(id);
                $('#deleteModal').modal('toggle');
            });

            // Xóa nhiều
            $('.delete_selected').click(function(e) {
                e.preventDefault();
                let ids = [];
                $("#nestable .nested-list-content input[type='checkbox']:checked").each(function() {
                    ids.push($(this).attr('rel'));
                });

                if (ids.length > 0) {
                    $('#deleteModal .id').val(ids.join(','));
                    $('#deleteModal').modal('toggle');
                } else {
                    alert('{{ __('Vui lòng chọn dữ liệu cần xóa') }}');
                }
            });

            // Check all toggle
            $("#nestable-menu-checkall").click(function(e) {
                e.preventDefault();
                const action = $(this).attr('data-action');
                const check = action == 1;
                $(".nested-list-content .m-checkbox input[type='checkbox']").prop('checked', check)
                    .change();
                $(this).attr('data-action', check ? 0 : 1).text(check ? 'Bỏ chọn tất cả' : 'Chọn tất cả');
            });

            // Nestable init
            $('.dd').nestable({
                dropCallback: function(details) {
                    let order = [];
                    $("li[data-id='" + details.destId + "']").find('ol:first').children().each(function(
                        index, elem) {
                        order.push($(elem).attr('data-id'));
                    });

                    let rootOrder = [];
                    if (order.length === 0) {
                        $("#nestable > ol > li").each(function(index, elem) {
                            rootOrder.push($(elem).attr('data-id'));
                        });
                    }

                    $.post('{{ route('dashboard.roles.order') }}', {
                        _token: '{{ csrf_token() }}',
                        source: details.sourceId,
                        destination: details.destId,
                        order: JSON.stringify(order),
                        rootOrder: JSON.stringify(rootOrder)
                    }).done(function() {
                        $(".success-indicator").fadeIn(100).delay(1000).fadeOut();
                    });
                }
            });

            // Nestable expand/collapse
            $('#nestable-menu-action').on('click', function() {
                const action = $(this).attr('data-action');
                const isExpand = action === 'expand-all';

                $('.dd').nestable(isExpand ? 'expandAll' : 'collapseAll');
                $(this).attr('data-action', isExpand ? 'collapse-all' : 'expand-all').text(isExpand ?
                    'Thu gọn' : 'Mở rộng');
            });

            // Checkbox ảnh hưởng con
            $("#nestable input[type='checkbox']").change(function() {
                $(this).closest('.dd-item').find("input[type='checkbox']").prop('checked', this.checked);
            });

        });
    </script>
@endsection
