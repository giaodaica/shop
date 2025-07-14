<?php

namespace App\Http\Controllers;

use App\Mail\RefundMoneyMail;
use Illuminate\Http\Request;
use App\Models\RefundMoney;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RefundMoneyController extends Controller
{
    public function store(Request $request, $id)
    {
        // dd($request->all());
        $user = Auth::user();
        $order = \App\Models\Order::findOrFail($id);

        // Chỉ cho phép chủ đơn hàng gửi yêu cầu hoàn tiền
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này!');
        }

        // Chặn nếu đơn hàng đã hoàn tiền
        if ($order->status === 'refunded') {
            abort(403, 'Đơn hàng này đã được hoàn tiền!');
        }

        $refund = RefundMoney::where('order_id', $id)->where('user_id', $user->id)->first();
        if ($refund && $refund->status !== 'admin') {
            return back()->with('error', 'Yêu cầu hoàn tiền đã được xử lý hoặc đang chờ duyệt.');
        }

        // Debug: log dữ liệu request
        \Log::info('Refund request data:', $request->all());

        $data = [
            'user_id' => $user->id,
            'order_id' => $id,
            'status' => 'pending',
        ];

        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        // Kiểm tra số tiền hoàn phải đúng bằng giá trị đơn hàng
        if ($request->amount != $order->final_amount) {
            return back()->withErrors(['amount' => 'Sai giá trị đơn hàng'])->withInput();
        }
        $data['amount'] = $request->amount;

        // Debug: log dữ liệu sẽ ghi vào DB
        \Log::info('Refund DB data:', $data);

        // Kiểm tra xem có dữ liệu STK hay QR
        $hasStkData = $request->filled('bank_code') && $request->filled('account_number') && $request->filled('account_name') && $request->filled('reason');
        $hasQrData = $request->hasFile('qr_image');

        if ($hasStkData) {
            $request->validate([
                'bank_code' => 'required|string',
                'account_number' => 'required|string',
                'account_name' => 'required|string',
                'reason' => 'required|string',
            ]);
            $data['bank'] = $request->bank_code;
            $data['stk'] = $request->account_number;
            $data['bank_account_name'] = $request->account_name;
            $data['reason'] = $request->reason;
        } elseif ($hasQrData) {
            $request->validate([
                'qr_image' => 'required|image|max:4096',
            ]);
            $file = $request->file('qr_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/qr_refund'), $fileName);
            $data['QR_images'] = 'uploads/qr_refund/' . $fileName;
        } else {
            return back()->withErrors(['general' => 'Vui lòng điền đầy đủ thông tin STK hoặc upload mã QR.'])->withInput();
        }

        // Nếu đã có record thì update, chưa có thì tạo mới
        if ($refund) {
            $refund->update($data);
        } else {
            RefundMoney::create($data);
        }

        return redirect()->route('home.orderDetail', $id)
            ->with('success', 'Yêu cầu hoàn tiền đã được gửi thành công, vui lòng chờ duyệt!');
    }

    public function showRefundRequest($id)
    {
        $order = \App\Models\Order::findOrFail($id);

        // Chỉ cho phép chủ đơn hàng truy cập
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền truy cập đơn hàng này!');
        }

        // Chặn nếu đơn hàng đã hoàn tiền
        if ($order->status === 'refunded') {
            abort(403, 'Đơn hàng này đã được hoàn tiền!');
        }

        $user = Auth::user();
        $refund = \App\Models\RefundMoney::where('order_id', $id)->where('user_id', $user->id)->first();

        // Nếu đã gửi yêu cầu hoàn tiền và chưa bị admin từ chối, chặn truy cập
        if ($refund && in_array($refund->status, ['pending'])) {
            abort(403, 'Bạn đã gửi yêu cầu hoàn tiền cho đơn hàng này và đang chờ xử lý!');
        }

        $total = $order->final_amount ?? 0;
        return view('pages.shop.refund-request', compact('order', 'total', 'refund'));
    }
    public function index()  {
        $data_refund = RefundMoney::join('users', 'refund_money.user_id', '=', 'users.id')->join('orders', 'refund_money.order_id', '=', 'orders.id')
            ->select('refund_money.*', 'users.name as customer_name', 'users.default_phone as customer_phone', 'orders.code_order as order_code')
            ->get();
            // dd($data_refund);
        return view('dashboard.pages.order.refund', compact('data_refund'));
    }
    public function show($id)
    {
        $refund = RefundMoney::findOrFail($id);
        $order = $refund->order;
        $user = $refund->user;
        return view('dashboard.pages.order.refund_detail', compact('refund', 'order', 'user'));
    }
    public function change(Request $request,$id){
        // dd($request->all());

        $data_change = $request->validate([
            'images' => 'required|image|max:4096',
            'status_old' => 'required|in:pending',
            'status_new' => 'required|in:approved,rejected',
        ],
        [
            'images.required' => 'Vui lòng tải lên ảnh bill',
            'images.image' => 'Ảnh bill phải là một tệp hình ảnh',
            'images.max' => 'Ảnh bill không được vượt quá 4MB',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
        ]);



        switch($request->status_new){
            case 'approved':
                $data_change['status'] = $request->status_new;
                break;
            case 'rejected':
                $data_change['status'] = $request->status_new;
                break;
            default:
                return back()->with('error', 'Trạng thái không hợp lệ');
        }
        $refund = RefundMoney::findOrFail($id);
        $code_order = $refund->order->code_order;
        if ($refund->status !== $request->status_old) {
            return back()->with('error', 'Trạng thái yêu cầu hoàn tiền đã thay đổi, vui lòng làm mới trang và thử lại.');
        }
        if ($request->hasFile('images')) {
            $file = $request->file('images');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/refund'), $fileName);
            $data_change['images'] = 'uploads/refund/' . $fileName;
        }
        $refund->update($data_change);
        $email = $refund->user->email;
        Mail::to($email)->send(new RefundMoneyMail($refund, $code_order));
        return redirect()->route('dashboard.order.refund.show', $id)->with('success', 'Cập nhật yêu cầu hoàn tiền thành công!');
    }
}
