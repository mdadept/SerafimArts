<?php
/**
 * This file is part of serafimarts.ru package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 13.06.2016 03:46
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Common\Observers;

use cebe\markdown\GithubMarkdown;
use Domains\Article\Article;

/**
 * Class ContentRenderObserver
 * @package Common\Observers
 */
class ContentRenderObserver
{
    /**
     * @var GithubMarkdown
     */
    private $md;

    /**
     * ContentRenderObserver constructor.
     * @param GithubMarkdown $md
     */
    public function __construct(GithubMarkdown $md)
    {
        $this->md = $md;
    }

    /**
     * @param string $content
     * @return string
     */
    private function parse(string $content) : string
    {
        $result = $this->md->parse($content);

        $result = $this->replaceYoutube($result);

        return $result;
    }

    /**
     * @param string $content
     * @return string
     */
    private function replaceYoutube(string $content) : string
    {
        return preg_replace_callback('/\[\[([a-zA-Z0-9\-]+)\]\]/isu', function($matches) {
            $link = sprintf(
                'https://www.youtube.com/embed/%s?color=white&enablejsapi=1&iv_load_policy=3&rel=0&showinfo=0',
                $matches[1]
            );
            return '<iframe src="' . $link . '" frameborder="0" allowfullscreen></iframe>';
        }, $content);
    }
    
    /**
     * @param Article $article
     */
    public function saving(Article $article)
    {
        $article->setAttribute('content_rendered', $this->parse((string)$article->content));
        $article->setAttribute('preview_rendered', $this->parse((string)$article->preview));
    }
}