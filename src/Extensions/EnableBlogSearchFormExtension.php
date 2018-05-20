<?php

namespace WebFox\BlogSearch\Extensions;

use SilverStripe\Core\Extension;
use WebFox\BlogSearch\Form\BlogSearchForm;

/**
 * Class EnableBlogSearchFormExtension
 * @package WebFox\BlogSearch\Extensions
 *
 * @property BlogController|BlogPostController $owner
 */
class EnableBlogSearchFormExtension extends Extension
{
    /**
     * @var array
     */
    private static $allowed_actions = [
        'BlogSearchForm'
    ];

    /**
     * @return mixed
     */
    public function BlogSearchForm()
    {
        $form = BlogSearchForm::create($this->owner);
        return $form->config()->get('keyword') || $form->config()->get('category')
            ? $form
            : false;
    }
}
