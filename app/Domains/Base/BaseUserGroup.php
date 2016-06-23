<?php
namespace Domains\Base;

use Domains\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * =============================================
 *   This is generated class. Do not touch it.
 * =============================================
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @property string $id
 * @property string $title
 *
 * @property-read User[]|Collection $users
 *
 */
abstract class BaseUserGroup extends Model
{
    /**
     * Model table name
     * @var string
     */
    protected $table = 'user_groups';

    /**
     * Disable auto increment primary key
     * @var bool
     */
    public $incrementing = FALSE;

    /**
     * Additional timestamps
     * @var array|bool
     */
    public $timestamps = FALSE;


    /**
     * @return HasMany|Relation
     */
    public function users()
    {
        return $this->hasMany(User::class, 'group_id', 'id');
    }

}
