<p>Xin chào {{ $present->user->name }},</p>

<p>Đơn hàng <strong>#{{ $present->code_order }}</strong> của bạn đã bị hủy.</p>
<p>Lý do : Khách hàng yêu cầu hủy</p>
@if($present && $present->status_pay != 'cod_paid' && $present->pay_method != 'COD')
    <p>💰 <strong>Hoàn tiền:</strong> {{ number_format($final_amount) }} VND. Tiền sẽ được hoàn trong 3–7 ngày làm việc.</p>
@endif

@if($voucher && $type->type == 'refund_new')
    <p>🎁 <strong>Voucher mới</strong> hạn từ {{ $voucher->start_date }} đến {{ $voucher->end_date }}. Có giá trị tương đương voucher cũ</p>
@elseif($voucher && $type->type == 'refund_reuse')
    <p>🎁 <strong>Voucher:</strong> Đã được khôi phục trạng thái chưa sử dụng.</p>
@endif
@if($present->status_pay != 'cod_paid' && $present->pay_method != 'COD')
    <p>Bạn vui lòng cung cấp thông tin để chúng tôi hoàn tiền <a href="{{ Route('order.refund.request', $present->id) }}">tại đây</a>.</p>
@endif
<p>Xin cảm ơn bạn đã đồng hành cùng chúng tôi.</p>
<p>OUTFITLY</p>
{{-- {{dd($voucher)}} --}}
