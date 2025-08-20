<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Mail\ContactReply;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    //

    public function hello()
    {
        return view('pages.contact.index');
    }
    public function send(Request $request)
    {
        // dd($_POST);
        $request->validate([
            'name'    => 'required|string',
            'email'   => 'required|email',
            'phone'   => 'required|numeric',
            'title'   => 'required|string',
            'content' => 'required|string',
        ], [
            'name.string'      => 'Tên không hợp lệ',
            'name.required'      => 'Bạn hãy nhập tên',
            'email.email'      => 'Email bạn nhập không hợp lệ',
            'email.required'      => 'Bạn chưa nhập email',
            'phone.numeric'    => 'Số điện thoại không hợp lệ',
            'phone.required'    => 'Bạn chưa nhập số điện thoại',
            'title.required'   => 'Bạn không được để trống tiêu đề',
            'title.string'     => 'Tiêu đề không hợp lệ',
            'content.required' => 'Bạn không được để trống nội dung',
            'content.string'   => 'Nội dung không hợp lệ',
        ]);
        $content = htmlspecialchars($request->input('content'));
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'title' => $request->title,
            'content' => $content,
        ]);
        return redirect()->back()->with('success','Cảm ơn bạn đã liên hệ,chúng tôi sẽ trả lời bạn trong thời gian sớm nhất');
    }
    public function verify()
    {
        return view('pages.contact.verify');
    }
    public function index(){
        $data = Contact::orderBy('created_at', 'desc')->paginate(10);
        // dd($data);
        return view('dashboard.pages.contact.index', compact('data'));
    }
    public function show($id)
    {
        $contact = Contact::leftJoin('users', 'contacts.user_id', '=', 'users.id')
            ->select('contacts.*', 'users.name as user_name', 'users.email as user_email')
            ->where('contacts.id', $id)
            ->first();
        return view('dashboard.pages.contact.show', compact('contact'));
    }
    public function reply(Request $request, $id)
    {

        $request->validate([
            'reply_title' => 'required|string',
            'admin_reply' => 'required|string',
        ],
            [
                'reply_title.required' => 'Vui lòng nhập tiêu đề phản hồi',
                'reply_title.string' => 'Tiêu đề phản hồi không hợp lệ',
                'admin_reply.required' => 'Vui lòng nhập nội dung phản hồi',
                'admin_reply.string' => 'Nội dung phản hồi không hợp lệ',
            ]
    );
        $contact = Contact::findOrFail($id);
        $name = Auth::user()->name;
        $contact->update([
            'admin_reply' => $request->admin_reply,
            'time_reply' => now(),
            'is_replied' => 1,
            'user_id' => Auth::id()
        ]);
        Mail::to($contact->email)->send(new ContactReply($name, $request->reply_title, $request->admin_reply,$contact));
        return redirect()->back()->with('success', 'Đã phản hồi khách hàng.');
    }
    public function delete($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->back()->with('success', 'Đã xóa liên hệ thành công.');
    }
}
