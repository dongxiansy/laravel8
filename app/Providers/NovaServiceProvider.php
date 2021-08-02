<?php

namespace App\Providers;

use App\Models\User;
use App\Nova\Dashboards\UserInsights;
use App\Nova\Metrics\TopicCount;
use App\Nova\Metrics\UserCount;
use App\Observers\UserObserver;
use Beyondcode\CustomDashboardCard\CustomDashboard;
use Beyondcode\CustomDashboardCard\NovaCustomDashboard;
use Coroowicaksono\ChartJsIntegration\StackedChart;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Nova;
use Bakerkretzmar\NovaSettingsTool\SettingsTool;
use Laravel\Nova\NovaApplicationServiceProvider;
use Coroowicaksono\ChartJsIntegration\LineChart;
use OptimistDigital\NovaSettings\NovaSettings;
use Vyuldashev\NovaPermission\NovaPermissionTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Nova::sortResourcesBy(
            function ($resource) {
                return $resource::$priority ?? 9999;
            }
        );

        Nova::serving(
            function () {
                User::observe(UserObserver::class);
            }
        );

//        NovaSettings::addSettingsFields(
//            function () {
//                return [
//                    Text::make('Some setting', 'some_setting'),
//                    Number::make('A number', 'a_number'),
//                ];
//            }
//        );
        NovaCustomDashboard::cards(
            [
                (new TopicCount)->withMeta(
                    [
                        'card-name' => '话题总数'
                    ]
                ),
                (new UserCount)->withMeta(
                    [
                        'card-name' => '用户总数'
                    ]
                ),
            ]
        );
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define(
            'viewNova',
            function ($user) {
//                return $user->hasNovaPermission();
                return in_array(
                    $user->email,
                    [
                        'dongxiansy@163.com'
                    ]
                );
            }
        );
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
//            (new CustomDashboard),
            (new LineChart())
                ->title('Revenue')
                ->animations(
                    [
                        'easing'  => 'easeinout',
                        'enabled' => true,
                    ]
                )
                ->series(
                    array(
                        [
                            'barPercentage' => 0.5,
                            'label'         => 'Average Sales',
                            'borderColor'   => '#f7a35c',
                            'data'          => [80, 90, 80, 40, 62, 79, 79, 90, 90, 90, 92, 91],
                        ],
                        [
                            'barPercentage' => 0.5,
                            'label'         => 'Average Sales #2',
                            'borderColor'   => '#90ed7d',
                            'data'          => [90, 80, 40, 22, 79, 129, 30, 40, 90, 92, 91, 80],
                        ]
                    )
                )
                ->options(
                    [
                        'legend' => [
                            'display' => true,
                            'position' => 'left'
                        ],
                        'xaxis' => [
                            'categories' => ['Jan', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct']
                        ],
                    ]
                )
                ->width('2/3'),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
//             new UserInsights,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new NovaPermissionTool,
//            new NovaSettings,
            (new SettingsTool)->canSee(
                function ($request) {
                    return true;
                }
            ),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
