<?php
/**
 * This file is part of serafimarts.ru package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class CachedMiddleware
 * @package Interfaces\Http\Middleware
 */
class CachedMiddleware
{
    /**
     * @var Repository
     */
    private $cache;

    /**
     * CachedMiddleware constructor.
     * @param Repository $cache
     */
    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $content = $this->cache->remember($request->getPathInfo(), Carbon::now()->addHour(), function() use ($request, $next) {
            /** @var Response $response */
            $response = $next($request);
            return $response->getContent();
        });

        return response($content);
    }
}