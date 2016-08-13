# XHP-LIB

Forked from https://github.com/facebook/xhp-lib/tree/1205aad1045912c4ccb747b297f0093a4196c202 by Facebook Inc.

This is a community maintained fork of xhp-lib/php-lib focused on non-hack syntax.  It is meant to be used with https://github.com/phplang/xhp on PHP.  Users of HHVM should install the official xhp-lib at https://github.com/facebook/xhp-lib which has full hack type checker support.

## Autoloading

xhp-lib ships with an ClassLoader implementation which can be used by projects in a composer autoload "files" include as follows:

In composer.json:
```
{
  "autoload": {
    "files": [
      "src/xhp-autoload.php"
    ]
  }
}
```

And in src/xhp-autoload.php:
```
<?php

(new PhpLang\XhpLib\ClassLoader([
  ':view' => 'view/',
])->register();
```

In the above example, tags like &lt;view:card:foo/&gt; will be autoloaded from `view/card/foo.php`
