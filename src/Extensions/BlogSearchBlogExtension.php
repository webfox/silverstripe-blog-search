<?php

namespace WebFox\BlogSearch\Extensions;

use SilverStripe\Control\Controller;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataList;

/**
 * Class BlogSearchBlogExtension
 * @package WebFox\BlogSearch\Extensions
 *
 * @property Blog $owner
 */
class BlogSearchBlogExtension extends DataExtension
{
    /**
     * @param DataList $blogPosts
     * @return DataList
     */
    public function updateGetBlogPosts(DataList &$blogPosts)
    {
        $request = Controller::curr()->getRequest();

        $searchParam = $request->getVar('keyword');
        $categoryParam = $request->getVar('category');


        if ($searchParam) {
            $blogPosts = $blogPosts->filterAny([
                'Title:PartialMatch' => Convert::raw2sql($searchParam),
                'Content:PartialMatch' => Convert::raw2sql($searchParam),
                'Summary:PartialMatch' => Convert::raw2sql($searchParam),
                'Tags.Title:PartialMatch' => Convert::raw2sql($searchParam),
            ]);
        }

        if ($categoryParam) {
            $blogPosts = $blogPosts->filter(['Categories.ID' => $categoryParam]);
        }


        return $blogPosts;
    }
}
