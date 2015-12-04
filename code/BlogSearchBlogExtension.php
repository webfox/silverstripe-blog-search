<?php

/**
 * Class BlogExtension
 *
 * @property Blog $owner
 */
class BlogSearchBlogExtension extends SiteTreeExtension
{

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
            ]);
        }

        if ($categoryParam) {
            $blogPosts = $blogPosts->filter(['Categories.ID' => $categoryParam]);
        }


        return $blogPosts;

    }

}