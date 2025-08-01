<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

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
}
