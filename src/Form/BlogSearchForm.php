<?php

namespace WebFox\BlogSearch\Form;

use SilverStripe\Blog\Model\Blog;
use SilverStripe\Blog\Model\BlogCategory;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextField;

/**
 * Class BlogSearchForm
 * @package WebFox\BlogSearch\Form
 */
class BlogSearchForm extends Form
{
    /**
     * @var bool
     */
    private static $keyword = true;

    /**
     * @var bool
     */
    private static $category = true;

    /**
     * @var array
     */
    private static $allowed_actions = ['search'];

    /**
     * @var array
     */
    private static $field_order = [
        'Category',
        'Keyword',
    ];

    /**
     * BlogSearchForm constructor.
     * @param $controller
     * @param string $name
     */
    public function __construct($controller, $name = "BlogSearchForm")
    {
        parent::__construct(
            $controller,
            $name,
            $this->getFormFields(),
            $this->getFormActions(),
            $this->getFormValidator()
        );

        $this->addExtraClass("blog-form");
        $this->extend('updateForm', $this);
    }

    /**
     * @return Blog
     */
    public function getBlog()
    {
        /** @var SiteTree $page */
        $page = Controller::curr()->data();

        return $page instanceof Blog ? $page : $page->Parent();
    }

    public function search(array $data, BlogSearchForm $form, HTTPRequest $request)
    {
        $link = $this->getBlog()->Link();
        $getVars = [];

        // if we have a category
        if (isset($data['Category']) && $data['Category']) {
            $getVars['category'] = $data['Category'];
        }

        // if we have a keyword
        if (isset($data['Keyword']) && $data['Keyword']) {
            $getVars['keyword'] = $data['Keyword'];
        }

        $this->extend('updateSearch', $getVars, $data);

        if ($getVars && !empty($getVars)) {
            // if we only have a category
            if (count($getVars) === 1 && array_key_exists('category', $getVars)) {
                $link = BlogCategory::get()->byID($data['Category'])->getLink();
            } else {
                // if we have more than just a category
                $link = $this->getBlog()->Link('?' . http_build_query($getVars));
            }
        }

        return Controller::curr()->redirect($link);
    }

    /**
     * @return FieldList Fields for this form.
     */
    protected function getFormFields()
    {
        $request = Controller::curr()->getRequest();
        $fields = FieldList::create();

        if (self::config()->get('category')) {
            $categories = $this->getBlog()->Categories()->map()->toArray();

            /** @var DropDownField $categoryField */
            $categoryField = DropdownField::create(
                'Category',
                _t('BlogSearchForm.Category', 'Category'),
                $categories,
                $request->getVar('category')
            );
            $categoryField->setHasEmptyDefault(true);

            $fields->push($categoryField);
        }

        if (self::config()->get('keyword')) {
            $keywordField = TextField::create(
                'Keyword',
                _t('BlogSearchForm.Keyword', 'Keyword'),
                $request->getVar('keyword')
            );
            $fields->push($keywordField);
        }

        $fields->changeFieldOrder($this->config()->get('field_order'));

        $this->extend('updateFormFields', $fields);

        return $fields;
    }

    /**
     * @return FieldList Actions for this form.
     */
    protected function getFormActions()
    {
        $fieldList = FieldList::create(
            $addAction = FormAction::create('search', 'Search')
        );

        $this->extend('updateFormActions', $fields);

        return $fieldList;
    }

    /**
     * @return Validator Validator for this form.
     */
    protected function getFormValidator()
    {
        $validator = RequiredFields::create([]);

        $this->extend('updateFormValidator', $validator);

        return $validator;
    }
}
