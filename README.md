Koine View
-----------------

Simple view renderer for phtml files

Code information:

[![Build Status](https://travis-ci.org/koinephp/View.png?branch=master)](https://travis-ci.org/koinephp/View)
[![Coverage Status](https://coveralls.io/repos/koinephp/View/badge.png?branch=master)](https://coveralls.io/r/koinephp/View?branch=master)
[![Code Climate](https://codeclimate.com/github/koinephp/View.png)](https://codeclimate.com/github/koinephp/View)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/koinephp/View/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/koinephp/View/?branch=master)

Package information:

[![Latest Stable Version](https://poser.pugx.org/koine/view/v/stable.svg)](https://packagist.org/packages/koine/view)
[![Total Downloads](https://poser.pugx.org/koine/view/downloads.svg)](https://packagist.org/packages/koine/view)
[![Latest Unstable Version](https://poser.pugx.org/koine/view/v/unstable.svg)](https://packagist.org/packages/koine/view)
[![License](https://poser.pugx.org/koine/view/license.svg)](https://packagist.org/packages/koine/view)

### Usage

```php
$config  = new Koine\View\Config;

$config->addViewPath('/path1')
    ->addViewPath('/path2')
    ->addViewPaths(array(
        'path3',
        'path4',
    ));

$config->registerHelper(
    // will provide the "escape" method for the views
    new MyEscaper()
);

$viewRenderer = $config->getViewRenderer();

echo $viewRenderer->render('post_template.phtml', array(
    'title'        => 'Some Title',
    'body'         => 'Some content',
    'relatedPosts' => $relatedPosts,
));
```

The templates:

```phtml
<!-- _post_template.phtml -->
<article>
    <h1><?= $this->escape($title) ?></h1>
    <div class="body"><?= $this->escape($body) ?></div>

    <?= $this->partial('related_posts', array(
            'posts' => $relatedPosts
        ));
    ?>
</article>

<!-- _related_posts.phtml -->
<sidebar class="related">
    <h2>Related Posts</h2>
    <?php foreach ($posts as $post) : ?>
        <?= $this->partial('related_post', array(
            'title' => $post['title'],
            'url'   => $post['url'],
        )) ?>
    <?php endforeach ?>
</sidebar>

<!-- _related_post.phtml -->
<a href="<?= $this->escape($url) ?>"><?= $this->escape($title) ?></a>
```


### Installing

#### Via Composer
Append the lib to your requirements key in your composer.json.

```javascript
{
    // composer.json
    // [..]
    require: {
        // append this line to your requirements
        "koine/view": "dev-master"
    }
}
```

### Alternative install
- Learn [composer](https://getcomposer.org). You should not be looking for an alternative install. It is worth the time. Trust me ;-)
- Follow [this set of instructions](#installing-via-composer)

### Issues/Features proposals

[Here](https://github.com/koinephp/view/issues) is the issue tracker.

### Contributing

Only TDD code will be accepted. Please follow the [PSR-2 code standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

### How to run the tests:

```bash
phpunit --configuration tests/phpunit.xml
```

### To check the code standard run:

```bash
phpcs --standard=PSR2 lib
phpcs --standard=PSR2 tests
```

### Lincense
[MIT](MIT-LICENSE)

### Authors

- [Marcelo Jacobus](https://github.com/mjacobus)
