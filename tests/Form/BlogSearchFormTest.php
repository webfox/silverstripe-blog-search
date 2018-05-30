<?php

namespace WebFox\BlogSearch\Tests\Form;

use SilverStripe\Blog\Model\Blog;
use SilverStripe\Blog\Model\BlogCategory;
use SilverStripe\Blog\Model\BlogController;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Session;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Dev\SapphireTest;
use WebFox\BlogSearch\Form\BlogSearchForm;

/**
 * Class BlogSearchFormTest
 * @package WebFox\BlogSearch\Tests\Form
 */
class BlogSearchFormTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = '../fixtures.yml';

    /**
     *
     */
    public function testSearch()
    {
        /** @var Blog $page */
        $page = $this->objFromFixture(Blog::class, 'one');
        /** @var BlogCategory $category */
        $category = $this->objFromFixture(BlogCategory::class, 'one');
        $request = $this->createRequest($page);
        $form = BlogSearchForm::create(Controller::curr());
        $baseURL = 'http://localhost/blog/';

        $response = $form->search([
            'Keyword' => 'Keyword',
            'Category' => $category->ID,
        ], $form, $request);
        $this->assertEquals($baseURL . '?category=1&keyword=Keyword', $response->getHeader('location'));

        
        $request = $this->createRequest($page);
        $response = $form->search([
            'Category' => $category->ID,
        ], $form, $request);
        $this->assertEquals($baseURL . 'category/one', $response->getHeader('location'));
    }

    /**
     * @param SiteTree $page
     *
     * @return \SilverStripe\Control\HTTPRequest
     */
    private function createRequest(SiteTree $page) {
        $request = new HTTPRequest('GET', $page->URLSegment);
        $session = new Session([]);
        $request->setSession($session);
        /** @var ContentController $controller */
        $controller = BlogController::create($page);
        $controller->setRequest($request);
        $controller->pushCurrent();

        return $request;
    }
}
