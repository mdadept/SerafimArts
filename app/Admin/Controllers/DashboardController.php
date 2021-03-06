<?php
/**
 * This file is part of serafimarts.ru package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Admin\Controllers;

use Carbon\Carbon;
use Domains\Analytic\Repository\AnalyticRepository;
use Illuminate\View\View;

/**
 * Class DashboardController
 * @package Admin\Controllers
 */
class DashboardController extends Controller
{
    /**
     * @param AnalyticRepository $repository
     * @return View
     */
    public function show(AnalyticRepository $repository)
    {
        $view = view('admin.dashboard', [
            'unique' => (object)[
                'day'   => $repository->getUniqueUsersPerPeriod(Carbon::now()->subDay()),
                'month' => $repository->getUniqueUsersPerPeriod(Carbon::now()->subMonth())
            ],
            'visits' => (object)[
                'day'   => $repository->getUsersPerPeriod(Carbon::now()->subDay()),
                'month' => $repository->getUsersPerPeriod(Carbon::now()->subMonth())
            ],
            'stats' => (object)[
                'month' => $repository
                    ->getUserStatsPerPeriod(Carbon::now()->subMonth(), function(Carbon $time) {
                        return $time->addDay();
                    })
                    ->map(function(array $data) {
                        $data['x'] = $data['x']->toDateString();
                        return $data;
                    })
                    ->toArray(),
                
                'day' => $repository
                    ->getUserStatsPerPeriod(Carbon::now()->subDay(), function(Carbon $time) {
                        return $time->addHour();
                    })
                    ->map(function(array $data) {
                        $data['x'] = $data['x']->toDateTimeString();
                        return $data;
                    })
                    ->toArray()
            ]
        ]);

        return \AdminSection::view($view, 'Dashboard');
    }
}