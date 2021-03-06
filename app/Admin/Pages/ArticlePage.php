<?php
/**
 * This file is part of serafimarts.ru package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Admin\Pages;

use Carbon\Carbon;
use Domains\Article\Category;
use Domains\Article\Enum\EnumArticleType;
use Domains\User\User;
use SleepingOwl\Admin\Model\ModelConfiguration;

/**
 * Class ArticlePage
 * @package Admin\Pages
 */
class ArticlePage implements Page
{
    /**
     * ArticlePage constructor.
     * @param ModelConfiguration $model
     */
    public function __construct(ModelConfiguration $model)
    {
        $model
            ->setTitle('Статьи')
            ->onDisplay(function() {
                return \AdminDisplay::table()
                    ->setApply(function ($query) {
                        $query->orderBy('publish_at', 'asc');
                    })
                    ->setColumns(
                        \AdminColumn::relatedLink('part.series.title', 'Серия'),
                        \AdminColumn::relatedLink('category.title', 'Категория'),
                        \AdminColumn::text('title', 'Заголовок')->setWidth('200px'),
                        \AdminColumn::text('preview', 'Краткое описание'),
                        \AdminColumn::relatedLink('user.name', 'Автор')
                    )
                    ->with('user', 'category', 'part.series')
                    ->paginate(15);
            })
            ->onCreateAndEdit(function () {
                return \AdminForm::panel()
                    ->addHeader(
                        \AdminFormElement::time('publish_at', 'Дата публикации')
                            ->setDefaultValue(Carbon::now()),

                        \AdminFormElement::checkbox('is_draft', 'Это черновик')
                            ->setDefaultValue(true)
                    )
                    ->addBody(
                        \AdminFormElement::text('title', 'Заголовок')
                            ->required(),
                        \AdminFormElement::select('category_id', 'Категория', Category::class)
                            ->setDisplay('title')
                            ->required(),
                        \AdminFormElement::select('user_id', 'Автор', User::class)
                            ->setDisplay('name')
                            ->setDefaultValue(\Auth::user()),
                        \AdminFormElement::textarea('content', 'Содержание')
                            ->required(),
                        \AdminFormElement::textarea('preview', 'Краткое описание')->setRows(3)
                    )
                ;
            });
    }
}