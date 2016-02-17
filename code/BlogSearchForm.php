<?php

class BlogSearchForm extends Form
{

    private static $keyword = true;
    private static $category = true;

    private static $allowed_actions = ['search'];
    
    private static $field_order = [
        'Category',
        'Keyword'
    ];

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

    public function search(array $data, BlogSearchForm $form, SS_HTTPRequest $request)
    {

        $link = $this->getBlog()->Link();

        //if we have keyword and category
        if (isset($data['Category']) && isset($data['Keyword']) && $data['Category'] && $data['Keyword']) {
            $link = $this->getBlog()->Link("?keyword={$data['Keyword']}&category={$data['Category']}");
        } elseif (isset($data['Category']) && $data['Category']) {
            $link = BlogCategory::get_by_id('BlogCategory', $data['Category'])->getLink();
        } elseif (isset($data['Keyword']) && $data['Keyword']) {
            $link = $this->getBlog()->Link("?keyword={$data['Keyword']}");
        }

        return Controller::curr()->redirect($link);


    }

    /**
     * @return FieldList Fields for this form.
     */
    protected function getFormFields()
    {

        $request = Controller::curr()->getRequest();
        $fields = new FieldList();

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

        $fields->changeFieldOrder(self::config()->get('field_order'));

        $this->extend('updateFormFields', $fields);

        return $fields;
    }

    /**
     * @return FieldList Actions for this form.
     */
    protected function getFormActions()
    {
        $fieldList = new FieldList(
            $addAction = new FormAction('search', 'Search')
        );

        $this->extend('updateFormActions', $fields);

        return $fieldList;
    }

    /**
     * @return Validator Validator for this form.
     */
    protected function getFormValidator()
    {
        $validator = new RequiredFields(array(
            'Email'
        ));

        $this->extend('updateFormValidator', $validator);

        return $validator;
    }

}
