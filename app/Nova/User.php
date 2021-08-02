<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Vyuldashev\NovaPermission\Permission;
use Vyuldashev\NovaPermission\Role;
use Vyuldashev\NovaPermission\RoleSelect;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;
    public static $group = '角色及权限';
    public static $priority = 1;
    public static $name = '用户';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'email',
    ];

    public static function label()
    {
        return '用户';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
//            Storage::disk('public')->putFile($folder, $request->avatar),
//            Avatar::make('头像', 'head_image')->disableDownload(),
            Avatar::make('头像','head_image')
                ->disk('public'),

            Text::make('用户名', 'name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('邮箱', 'email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            DateTime::make('注册时间', 'created_at')
                ->sortable()
                ->onlyOnIndex(),

            MorphToMany::make('角色', 'roles', Role::class),
            MorphToMany::make('权限', 'permissions', Permission::class),
            RoleSelect::make('角色', 'roles'),

//            MorphToMany::make('角色', 'roles', Role::class)->canSee(
//                function ($request) {
//                    return $request->user()->can('manage_users');
//                }
//            ),
//            MorphToMany::make('权限', 'permissions', Permission::class)->canSee(
//                function ($request) {
//                    return $request->user()->can('manage_users');
//                }
//            ),
//            RoleSelect::make('角色', 'roles')->canSee(
//                function ($request) {
//                    return $request->user()->can('manage_users');
//                }
//            ),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
