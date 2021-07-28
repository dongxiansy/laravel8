<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            'auth',
            [
                'except' => ['show', 'create', 'store', 'index']
            ]
        );
    }

    public function index()
    {
        $users = User::paginate(7);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    /**
     * @throws AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('update', $user);
        return view('users.show', compact('user'));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name'     => 'required|unique:users|max:50',
                'email'    => 'required|email|unique:users|max:255',
                'password' => 'required|confirmed|min:6'
            ]
        );

        $user = User::create(
            [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
            ]
        );
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    /**
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function update(User $user, Request $request)
    {
        // 编辑权限授权策略 验证当前用户编辑的是自己的信息
        $this->authorize('update', $user);
        $this->validate(
            $request,
            [
                'name'     => 'required|max:50',
                'password' => 'nullable|confirmed|min:6'
            ]
        );

        $data         = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(User $user)
    {
        // 删除授权策略 验证当前用户是管理员并且删除的用户不是自己
        $this->authorize('destroy', $user);

        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
}
