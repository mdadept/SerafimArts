<?php
/**
 * This file is part of SerafimArts package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 03.05.2016 7:44
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Blueprints;

use Carbon\Carbon;
use Serafim\Blueprint\Mapping as UI;
use Domains\User\User as UserEntity;

/**
 * @UI\Blueprint(entity=UserEntity::class, title="Пользователи", icon="supervisor_account")
 */
class User
{
    /**
     * @var string
     * @UI\PrimaryKey
     */
    protected $id;

    /**
     * @var string
     * @UI\Image(title="Аватар", width=32, height=32)
     */
    protected $avatar;

    /**
     * @var string
     * @UI\Text(title="Имя", sortable=true, width=250)
     */
    protected $name;

    /**
     * @var string
     * @UI\Text(title="Email", sortable=true)
     */
    protected $email;

    /**
     * @var string
     * @UI\DateTime(title="Создан", readDecorator="dateFormat", sortable=true, width=150)
     */
    protected $created_at;

    /**
     * @var string
     * @UI\DateTime(title="Обновлён", readDecorator="dateFormat", sortable=true, width=150)
     */
    protected $updated_at;

    /**
     * @var string
     * @UI\HasOne(title="Группа", field="title", width=120, route="group")
     */
    protected $group;

    /**
     * @param string $date
     * @return string
     */
    private function dateFormat($date)
    {
        $locale = Carbon::getLocale();

        Carbon::setLocale(app('config')->get('app.locale'));

        $result = (new Carbon($date))->diffForHumans();

        Carbon::setLocale($locale);

        return $result;
    }
}