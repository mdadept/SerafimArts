<?php
/**
 * This file is part of SerafimArts package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Providers;

use PhpDeal\ContractApplication;
use Illuminate\Config\Repository;
use Illuminate\Support\ServiceProvider;

/**
 * Class DbcServiceProvider
 * @package Providers
 */
class DbcServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment() === 'local') {
            /** @var Repository $repo */
            $repo = $this->app->make(Repository::class);

            ContractApplication::getInstance()->init($repo->get('dbc'));
        }
    }



    /**
     * @return void
     */
    public function register()
    {
        //
    }
}