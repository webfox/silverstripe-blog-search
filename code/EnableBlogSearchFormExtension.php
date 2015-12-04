<?php

/**
 * Class BlogExtension
 *
 * @property Blog_Controller|BlogPost_Controller $owner
 */
class EnableBlogSearchFormExtension extends SiteTreeExtension
{

    private static $allowed_actions = [
        'BlogSearchForm'
    ];


    public function BlogSearchForm()
    {
        return BlogSearchForm::create($this->owner);
    }

}