<?php
/**
 * This file is part of serafimarts.ru package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 26.04.2016 15:56
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Article;

use Carbon\Carbon;
use Domains\User\User;
use PhpDeal\Annotation as Contract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Class Article
 * @package Domains\Article
 *
 * @property-read string $id
 * @property-read string $url
 * @property-read string $title
 * @property-read string $user_id
 * @property-read string $category_id
 * @property-read string $preview
 * @property-read string $preview_rendered
 * @property-read string $content
 * @property-read string $content_rendered
 * @property-read bool $is_draft
 * @property-read Carbon $publish_at
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Category $category
 *
 * @method Article|Builder|QueryBuilder published()
 *
 * @Contract\Invariant("is_uuid($this->id)")
 * @Contract\Invariant("$this->user_id")
 * @Contract\Invariant("$this->category_id")
 */
class Article extends Model
{
    /**
     * @var string
     */
    protected $table = 'articles';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    public $timestamps = [
        'created_at',
        'updated_at',
        'publish_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Relation
     */
    public function user() : Relation
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Relation
     */
    public function category() : Relation
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * @param Builder $query
     * @return Builder|Article
     */
    public static function scopePublished(Builder $query) : Builder
    {
        $query = $query
            ->where('is_draft', false)
            ->where('publish_at', '<=', Carbon::now());

        /** @var Builder|\Illuminate\Database\Query\Builder $query */
        return $query->orderBy('publish_at', 'desc');
    }

    /**
     * @param $value
     * @return Carbon
     *
     * @Contract\Verify("is_string($value) || $value instanceof \DateTime")
     */
    public function getPublishAtAttribute($value) : Carbon
    {
        return Carbon::parse($value);
    }
}