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
            overflow: auto;
        }

        *,
        :after,
        :before {
            box-sizing: border-box;
        }

        .dd-list,
        .dd-list .dd-list {
            padding-left: 0 !important;
            margin-bottom: 0 !important;
            border: none !important;
            background: none !important;
        }

        .dd-item {
            list-style: none !important;
            margin-bottom: 0 !important;
        }

        .nested-list-content {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 0 !important;
            min-height: 44px !important;
            background: #f8f9fa !important;
            border-bottom: 1px solid #e2e2e5 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            margin-bottom: 0 !important;
            position: relative;
        }

        .nested-list-content:last-child {
            border-bottom: none !important;
        }

        .dd-handle,
        .nested-list-handle {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 40px !important;
            min-width: 40px !important;
            height: 44px !important;
            background: #e2e2e5 !important;
            border-right: 1px solid #d1d1d1 !important;
            cursor: move !important;
            font-size: 18px !important;
            color: #888 !important;
            margin-right: 0 !important;
            border-radius: 0 !important;
        }

        .m-checkbox {
            margin: 0 10px 0 0 !important;
            flex: 1 1 auto !important;
            display: flex !important;
            align-items: center !important;
        }

        .btnControll {
            margin-left: auto !important;
            display: flex !important;
            gap: 6px !important;
        }

        .nested-list-content>* {
            vertical-align: middle !important;
        }

        .dd-list .dd-list {
            margin-left: 40px !important;
            border-left: none !important;
            background: none !important;
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
                                    <!-- Đã xóa các nút Thu gọn, Chọn tất cả, Xóa mục đã chọn -->
                                </div>
                            </div>
                            <button type="button" data-url="{{ route('dashboard.permissions.create') }}"
                                class="btn btn-success font-weight-bolder loadModal_toggle">
                                <i class="fas fa-plus-circle icon-md"></i> {{ __('Thêm mới') }}
                            </button>
                        </div>
                        <form action="{{ route('dashboard.permissions.order') }}" method="POST" id="nestable-form">
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
                            <i class="flaticon-light icon-lg"></i> {{ __('Kéo thả để sắp xếp danh mục') }}
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal load --}}
    <div class="modal fade" id="loadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
    {{-- Modal xóa --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dashboard.permissions.destroy', 0) }}" method="POST" class="form-horizontal">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Xác nhận thao tác') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">{{ __('Bạn thực sự muốn xóa?') }}</div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" class="id" />
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Hủy') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Xóa') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Load modal edit/create
            $(document).on('click', '.loadModal_toggle, .edit_toggle', function(e) {
                e.preventDefault();
                const url = $(this).data("url");
                $('#loadModal .modal-content').html(
                    '<div style="text-align:center;padding:40px"><span class="spinner-border"></span> Đang tải...</div>'
                );
                $.get(url)
                    .done(function(data) {
                        if (typeof data === 'string' && data.trim().length > 0 && data.indexOf('<form') !== -1) {
                            $('#loadModal .modal-content').html(data);
                            $("#kt_select2_2, #kt_select2_3, #kt_select2_4").each(function() {
                                if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
                                    $(this).select2('destroy');
                                }
                                $(this).select2({
                                    dropdownParent: $('#loadModal')
                                });
                            });
                            var modal = new bootstrap.Modal(document.getElementById('loadModal'));
                            modal.show();
                        } else {
                            $('#loadModal .modal-content').html(
                                '<div class="alert alert-danger">Không load được form. Vui lòng thử lại hoặc kiểm tra route!</div>'
                            );
                        }
                    })
                    .fail(function(xhr) {
                        let msg = 'Lỗi khi tải form: ' + (xhr.status ? xhr.status + ' ' + xhr.statusText : 'Không xác định');
                        $('#loadModal .modal-content').html('<div class="alert alert-danger">' + msg + '</div>');
                    });
            });
            // Xử lý nút xóa đơn
            $(document).on('click', '.delete_toggle', function(e) {
                e.preventDefault();
                const id = $(this).attr('rel');
                $('#deleteModal .id').val(id);
                var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });
            
        });
    </script>
@endsection
