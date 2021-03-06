<?php
/**
 * This file is part of serafimarts.ru package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Admin\Pages;

use Carbon\Carbon;
use Domains\Article\Article;
use Domains\Article\Category;
use Domains\Article\Enum\EnumArticleType;
use Domains\Article\Enum\EnumSizeType;
use Domains\User\User;
use Illuminate\Database\Eloquent\Builder;
use SleepingOwl\Admin\Model\ModelConfiguration;

/**
 * Class MainArticlePage
 * @package Admin\Pages
 */
class MainArticlePage implements Page
{
    /**
     * ArticlePage constructor.
     * @param ModelConfiguration $model
     */
    public function __construct(ModelConfiguration $model)
    {
        $model
            ->setTitle('Главная страница')
            ->onDisplay(function() {
                return \AdminDisplay::table()
                    ->setApply(function ($query) {
                        $query->orderBy('order_id', 'asc');
                    })
                    ->setColumns(
                        \AdminColumn::text('content', 'Краткое описание'),
                        \AdminColumn::order()
                    )
                    ->paginate(15);
            })
            ->onCreateAndEdit(function () {
                return \AdminForm::panel()
                    ->addHeader(
                        \AdminFormElement::select('type', 'Тип', array_flip(EnumArticleType::getConstants()))
                            ->setDefaultValue(EnumArticleType::TEXT)
                    )
                    ->addBody(
                        \AdminFormElement::image('image', 'Изображение'),
                        \AdminFormElement::text('video', 'Ссылка на Youtube'),
                        \AdminFormElement::textarea('content', 'Содержание')
                            ->required(),
                        \AdminFormElement::text('button_description', 'Кнопка "Читать дальше"')
                            ->setDefaultValue('Читать дальше')
                            ->required(),
                        \AdminFormElement::select('relation_id', 'Ссылка на запись', Article::class)
                            ->setDisplay('title')
                    )
                ;
            });
    }
}