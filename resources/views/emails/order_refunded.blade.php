<div style="font-family: Arial, Helvetica, sans-serif; background: #f6f6f6; padding: 32px;">
    <div style="max-width: 480px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
        <h2 style="color: #198754; text-align:center; margin-bottom: 24px;">Hoàn tiền thành công</h2>
        <p style="font-size: 16px; color: #333;">Xin chào <strong>{{ $refund->user->name }}</strong>,</p>
        <p style="font-size: 16px; color: #333;">
            Chúng tôi đã hoàn tiền vào tài khoản của bạn cho đơn hàng <strong>#{{ $code_order }}</strong> với số tiền
            <span style="color: #dc3545; font-weight: bold;">{{ number_format($refund->amount) }} VND</span>.
        </p>
        <div style="margin: 24px 0; text-align:center;">
            <a href="{{ route('home.orderDetail',$refund->order_id) }}" style="background: #198754; color: #fff; padding: 10px 28px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                Xem bill hoàn tiền
            </a>
        </div>
        <p style="font-size: 15px; color: #888; text-align:center; margin-top: 32px;">
            Cảm ơn bạn đã tin tưởng OUTFITLY!
        </p>
    </div>
</div>
