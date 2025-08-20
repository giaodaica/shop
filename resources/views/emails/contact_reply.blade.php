<!-- filepath: c:\Users\ADMIN\Documents\GitHub\shop\resources\views\emails\contact_reply.blade.php -->
<div style="font-family: Arial, Helvetica, sans-serif; background: #f6f6f6; padding: 32px;">
    <div style="max-width: 520px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); padding: 32px;">
        <div style="text-align:center; margin-bottom: 24px;">
            <img src="{{ asset('assets/images/logooutfitly.png') }}" alt="OUTFITLY" style="height: 48px;">
        </div>
        <h2 style="color: #198754; text-align:center; margin-bottom: 24px;">Phản hồi liên hệ của bạn</h2>
        <p style="font-size: 16px; color: #333;">Xin chào <strong>{{ $contact->name }}</strong>,</p>
        <p style="font-size: 16px; color: #333;">
            Chúng tôi đã nhận được liên hệ của bạn với tiêu đề: <br>
            <span style="color: #0d6efd; font-weight: bold;">"{{ $contact->title }}"</span>
        </p>
        <div style="background: #f8f9fa; border-left: 4px solid #198754; padding: 16px; margin: 24px 0;">
            <div style="color: #555; margin-bottom: 8px;">Nội dung bạn gửi:</div>
            <div style="color: #222;">{!! nl2br(e($contact->content)) !!}</div>
        </div>
        <div style="background: #f1f3f4; border-left: 4px solid #0d6efd; padding: 16px; margin-bottom: 24px;">
            <div style="color: #555; margin-bottom: 8px;">Phản hồi từ OUTFITLY:</div>
            <div style="color: #222;">{!! nl2br(e($admin_reply)) !!}</div>
        </div>
        <p style="font-size: 15px; color: #888; text-align:center; margin-top: 32px;">
            Nếu bạn cần hỗ trợ thêm, vui lòng phản hồi lại email này hoặc liên hệ bộ phận CSKH của chúng tôi.<br>
            <strong>OUTFITLY</strong>
        </p>
    </div>
