# Silverstripe Blog Search

The extension adds a form to your Blog and BlogPost pages to search the blog based on category or a search term.

Just use `$BlogSearchForm` in `Blog.ss` and `BlogPost.ss`.

# Installation Instructions

## Composer
Run the following to add this module as a requirement and install it via composer.

```bash
composer require "webfox/silverstripe-blog-search"
```

The blog search form extension get applied automatically, and the form will have both a keyword and category search.
However you can disable either component as below:

```yaml
WebFox\BlogSearch\Form\BlogSearchForm:
  keyword: false
  category: false
```

then browse to /dev/build?flush=all

# Requirements
* Silverstripe 4.0+

For Silverstripe 3.0+ support please see the v1.0 branch.
