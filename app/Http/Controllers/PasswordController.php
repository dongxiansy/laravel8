<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function __construct()
    {
        // 忘记密码页面 1分钟内只能访问2次
        $this->middleware('throttle:2,1', [
            'only' => ['showLinkRequestForm']
        ]);

        // 发送重置密码链接 10分钟内只能发送3次
        $this->middleware('throttle:3,10', [
            'only' => ['sendResetLinkEmail']
        ]);
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function showResetForm(Request $request)
    {
        $token = $request->route()->parameter('token');
        return view('auth.passwords.reset', compact('token'));
    }

    /**
     * 发送邮件找回密码
     *
     * @param Request $request
     * @return RedirectResponse
     * @author xdong <dongxian@fanxiapp.com>
     * @date   2021/7/28 16:34
     */
    public function sendResetLinkEmail(Request $request)
    {
        // 验证器验证邮箱格式
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        // 以邮箱查找用户信息
        $user = User::where("email", $email)->first();

        if (is_null($user)) {
            session()->flash('danger', '邮箱未注册');
            return redirect()->back()->withInput();
        }

        // 生成token，会在视图 emails.reset_link 里拼接链接
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'email'      => $email,
                'token'      => Hash::make($token),
                'created_at' => new Carbon,
            ]
        );

        // 将生成的token链接发送给用户
        Mail::send(
            'emails.reset_link',
            compact('token'),
            function ($message) use ($email) {
                $message->to($email)->subject("忘记密码");
            }
        );

        session()->flash('success', '重置邮件发送成功，请查收');
        return redirect()->back();
    }

    /**
     * 重置密码
     *
     * @param Request $request
     * @return RedirectResponse
     * @author xdong <dongxian@fanxiapp.com>
     * @date   2021/7/28 16:45
     */
    public function reset(Request $request)
    {
        $request->validate(
            [
                'token'                 => 'required',
                'email'                 => 'required|email',
                'password'              => 'required|confirmed|min:6',
                'password_confirmation' => 'required|confirmed|min:6',
            ]
        );

        $email = $request->email;
        $token = $request->token;
        // 找回密码链接的有效时间
        $expires = 60 * 10;

        $user = User::where("email", $email)->first();

        if (is_null($user)) {
            session()->flash('danger', '邮箱未注册');
            return redirect()->back()->withInput();
        }

        // 读取重置的记录
        $record = (array)DB::table('password_resets')->where('email', $email)->first();

        if ($record) {
            // 检查是否过期
            if (Carbon::parse($record['created_at'])->addSeconds($expires)->isPast()) {
                session()->flash('danger', '链接已过期，请重新尝试');
                return redirect()->back();
            }

            // 检查令牌是否正确
            if (!Hash::check($token, $record['token'])) {
                session()->flash('danger', '令牌错误');
                return redirect()->back();
            }

            // 更新用户密码
            $user->update(['password' => bcrypt($request->password)]);

            session()->flash('success', '密码重置成功，请使用新密码登录');
            return redirect()->route('login');
        }

        session()->flash('danger', '未找到重置记录');
        return redirect()->back();
    }
}
