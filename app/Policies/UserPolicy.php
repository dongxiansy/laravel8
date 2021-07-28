<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 更新操作授权策略
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     * @author xdong <dongxian@fanxiapp.com>
     * @date   2021/7/28 15:51
     */
    public function update(User $currentUser, User $user): bool
    {
        return $currentUser->id === $user->id;
    }

    /**
     * 管理员删除操作授权策略
     *
     * @param User $currentUser
     * @param User $user
     * @return bool
     * @author xdong <dongxian@fanxiapp.com>
     * @date   2021/7/28 15:51
     */
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
