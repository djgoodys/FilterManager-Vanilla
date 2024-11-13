# composer-modify-autoload

Simple library for updated the vendor/autoload.php so it manually includes any files specified in composer.json's files array.
See [composer issues #6768](https://github.com/composer/composer/issues/6768)

## composer.json

Files that need to be included at the very beginning
```
{
 // ... 
  "autoload": {
    "priority": [
      "your_file.php"
    ]
  }
 // ...
}
```

Autorun script after composer update
```
{
 // ... 
  "scripts": {
    "post-autoload-dump": [
      "@php vendor/bin/composer-modify-autoload.php"
    ]
  }
 // ...
}
```

See also [funkjedi/composer-include-files](https://github.com/funkjedi/composer-include-files)
