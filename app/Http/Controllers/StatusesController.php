<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 发布微博数据
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     * @author xdong <dongxian@fanxiapp.com>
     * @date   2021/7/28 17:45
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'content' => 'required|max:140'
            ]
        );

        Auth::user()->statuses()->create(
            [
                'content' => $request['content']
            ]
        );
        session()->flash('success', '发布成功！');
        return redirect()->back();
    }
}
