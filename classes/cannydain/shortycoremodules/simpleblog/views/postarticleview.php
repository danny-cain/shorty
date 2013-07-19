<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Article;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog;

class PostArticleView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var Article
     */
    protected $_article;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var Blog
     */
    protected $_blog;

    protected $_saveURI = '';

    public function display()
    {
        echo '<h1>New Blog Post in "'.$this->_blog->getName().'"</h1>';

        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->editText('title', 'Title', $this->_article->getTitle(), 'The title of this blog post');
            $this->_formHelper->editText('uri', 'Friendly URI', $this->_article->getUri(), 'A friendly uri section for this blog post.  This will form part of the uri to read the post');
            foreach ($this->_article->getTags() as $tag)
            {
                echo '<div>';
                    $this->_formHelper->hiddenField('tags[]', $tag);
                    echo $tag;
                    echo ' <a href="javascript://" onclick="$(this).parent().remove(); return false;">[delete]</a>';
                echo '</div>';
            }
            $this->_formHelper->editText('tag', 'Tags', '', 'Any tags you wish to apply to this article');
            $this->_formHelper->editRichText('content','Article', $this->_article->getContent(), 'The blog post itself!');
            $this->_formHelper->submitButton('Post Article');
        $this->_formHelper->endForm();
        $this->_writeScript();
    }

    protected function _writeScript()
    {
        echo <<<HTML
<script type="text/javascript">
    $(document).ready(function()
    {
        $('[name=tag]').keypress(function(e)
        {
            if (e.which != 13)
                return true;

            var input = $(this);
            var tag = input.val();

            tag = tag.replace('"', '&quot;', 'g');
            input.val('');

            var newTag = $('<div></div>');
            newTag.append('<input type="hidden" name="tags[]" value="' + tag + '" />');
            newTag.append(tag);
            newTag.append(' <a href="javascript://" onclick=\"$(this).parent().remove(); return false;">[delete]</a>');

            input.before(newTag);

            return false;
        });
    });
</script>
HTML;

    }

    public function updateModelFromPost(Request $request)
    {
        $tags = $request->getParameter('tags');

        if (!is_array($tags))
            $tags = array();

        $this->_article->setTitle($request->getParameter('title'));
        $this->_article->setUri($request->getParameter('uri'));
        $this->_article->setTags($tags);
        $this->_article->setContent($request->getParameter('content'));
    }

    /**
     * @param \CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog $blog
     */
    public function setBlog($blog)
    {
        $this->_blog = $blog;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog
     */
    public function getBlog()
    {
        return $this->_blog;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\SimpleBlog\Models\Article $article
     */
    public function setArticle($article)
    {
        $this->_article = $article;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\SimpleBlog\Models\Article
     */
    public function getArticle()
    {
        return $this->_article;
    }

    public function setSaveURI($saveURI)
    {
        $this->_saveURI = $saveURI;
    }

    public function getSaveURI()
    {
        return $this->_saveURI;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}