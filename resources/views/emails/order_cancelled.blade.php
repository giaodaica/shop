<div style="font-family: Arial, Helvetica, sans-serif; background: #f6f6f6; padding: 32px;">
    <div style="max-width: 520px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
        <h2 style="color: #dc3545; text-align:center; margin-bottom: 24px;">Đơn hàng đã bị hủy</h2>
        <p style="font-size: 16px; color: #333;">Xin chào <strong>{{ $present->user->name }}</strong>,</p>
        <p style="font-size: 16px; color: #333;">
            Đơn hàng <strong>#{{ $present->code_order }}</strong> của bạn đã bị hủy.<br>
            <span style="color:#888;">Lý do: Khách hàng yêu cầu hủy</span>
        </p>

        {{-- Bảng danh sách sản phẩm --}}
        @if(isset($data_product) && count($data_product))
        <h4 style="margin-top:24px; color:#198754;">Danh sách sản phẩm</h4>
        <table style="width:100%; border-collapse:collapse; margin: 16px 0;">
            <thead>
                <tr style="background:#f1f3f4;">
                    <th style="padding:8px; border:1px solid #e0e0e0; text-align:left;">Sản phẩm</th>
                    <th style="padding:8px; border:1px solid #e0e0e0;">Số lượng</th>
                    <th style="padding:8px; border:1px solid #e0e0e0;">Giá</th>
                    <th style="padding:8px; border:1px solid #e0e0e0;">Tổng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data_product as $item)
                <tr>
                    <td style="padding:8px; border:1px solid #e0e0e0;">{{ $item['product_name'] ?? $item['name'] }}</td>
                    <td style="padding:8px; border:1px solid #e0e0e0; text-align:center;">{{ $item['quantity'] }}</td>
                    <td style="padding:8px; border:1px solid #e0e0e0; text-align:right;">{{ number_format($item['sale_price']) }} VND</td>
                    <td style="padding:8px; border:1px solid #e0e0e0; text-align:right;">{{ number_format($item['sale_price'] * $item['quantity']) }} VND</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($present && $present->status_pay != 'cod_paid' && $present->pay_method != 'COD')
            <p>💰 <strong>Hoàn tiền:</strong> {{ number_format($final_amount) }} VND. Tiền sẽ được hoàn trong 3–7 ngày làm việc.</p>
        @endif

        @if($voucher && $type->type == 'refund_new')
            <p>🎁 <strong>Voucher mới</strong> hạn từ {{ $voucher->start_date }} đến {{ $voucher->end_date }}. Có giá trị tương đương voucher cũ.</p>
        @elseif($voucher && $type->type == 'refund_reuse')
            <p>🎁 <strong>Voucher:</strong> Đã được khôi phục trạng thái chưa sử dụng.</p>
        @endif

        @if($present->status_pay != 'cod_paid' && $present->pay_method != 'COD')
            <p>Bạn vui lòng cung cấp thông tin để chúng tôi hoàn tiền <a href="{{ Route('order.refund.request', $present->id) }}" style="color:#198754;">tại đây</a>.</p>
        @endif

        <p style="font-size: 15px; color: #888; text-align:center; margin-top: 32px;">
            Xin cảm ơn bạn đã đồng hành cùng chúng tôi.<br>
            <strong>OUTFITLY</strong>
        </p>
    </div>
</div>
