# yii2-active-record-search

This module provides a simple but very flexible way to create a search index of almost any ActiveRecord entries.

The base idea is:

- we want a search index for every (configured) app language
- we want to group different Types of Models in search results
- we want to be able to get simple strings for different Types of Models where we can execute simple LIKE SQL queries on. No matter where the data for these strings come from.
- we want a fast frontend for the search module, so all required info to build links for the results should be pre-defined

## Module Config

see [Module](src/Module.php) for available module params.
These should allow to customize almost all aspects of the module and its behavior.

simple example config:

```php
    'modules' => [
        'search' => [
            'class' => \dmstr\activeRecordSearch\Module::class,
            'layout' => '@backend/views/layouts/box',
            'frontendLayout' => '@app/views/layouts/container',
        ],
    ]
```

The module provides 2 types of controllers:

- frontend:
    - `\dmstr\activeRecordSearch\controllers\FrontendController`
- backend:
    - `\dmstr\activeRecordSearch\controllers\SearchGroupController` to manage (translate, en/disable) search groups which are created by the indexer
    - `\dmstr\activeRecordSearch\controllers\SearchController` to manage search items, attentions these will be overwritten by next indexer run. Should usually not be required
    - `\dmstr\activeRecordSearch\controllers\SearchGroupTranslationController` search group translations. Should usually not be required

Additionally the module provide a simple Search-Input widget:

- `\dmstr\activeRecordSearch\widgets\SearchInput`
  which will be used in frontend controller if not overwritten via modul `searchInputWidget` property

## Indexer

The heart of this module is the [SearchIndexer](src/components/SearchIndexer.php).

The Indexer has to be configured for all Type of Models that should be indexed.

see [SearchIndexer](src/components/SearchIndexer.php) for available module params and example.

### run the indexer

The Indexer should be defined and called as yii cli cmd:

```
    $config['controllerMap']['search-index'] = \dmstr\activeRecordSearch\commands\IndexController::class;
```

To automate indexer runs create cron jobs.

Example script which can run as cron

```
#!/bin/bash

. /root/export-env
LOG=/tmp/search-index-update
date > $LOG
yii search-index/update >> $LOG
date >> $LOG
```

### Simple Indexer Config example

- In this example we index 2 types of models (products and accessories)
- For both types we define the AR model classes. These will be used to get the "data" by calling their `find()->all()` Methods
- The string that we will use for the search will be build (concatenation) from the values of the defined `attributes`
- For both types we definie the route that should be used to build the URL in results
- We also define which url_params should be used to build the result URL
- `link_text` defines the text for the result Link

```php
 $config['components']['searchIndexer'] = [
     'class' => \dmstr\activeRecordSearch\components\SearchIndexer::class,
     'languages' => function() {
         return project\components\CountryHelper::activeLanguages();
     },
     'fallbackLanguage' => 'en',
     'searchItems' => [
         'products'  => [
             'model_class'      => Product::class,
             'route'      => '/frontend/product/detail',
             'attributes' => [
                 'name',
                 'desc'
             ],
             'url_params' => ['productId' => 'id'],
             'link_text'  => 'name',
             'group'      => 'Products',
         ],
         'accessories'  => [
             'model_class'      => Accessory::class,
             'route'      => '/frontend/accessory/detail',
             'attributes' => [
                 'name',
                 'desc'
             ],
             'url_params' => ['accessoryId' => 'id'],
             'link_text'  => 'name',
             'group'      => 'P&A',
         ],
     ],
 ];
 
```

### Complex Indexer Config example

- Here we define a bunch of different types where you can see that almost every param can be a callback so that one is able to define "non-static" results.
- The find_method param can be used to overwrite the default find(). Useful to filter models e.g. by their status flags
- Callbacks can be used in almost any place e.g. to generate link_text values from more than one attribute or even from attributes of different models (see tags where we use name
  prefixed by the name from tagGroup relation model)
- `products['attributes']` is an example where you can see how to define virtual attributes from relation models with simple array notation
- `news['attributes']['content']` is an example how to get parts of a json struct as 'content'
- if you have SEO url rules, you can define all required `url_params`
- ...

```php
 $config['components']['searchIndexer'] = [
     'class' => \dmstr\activeRecordSearch\components\SearchIndexer::class,
     'languages' => function() {
         return project\components\CountryHelper::activeLanguages();
     },
     'searchItems' => [
         'products'        => [
             'model_class'      => Product::class,
             'find_method' => function ($item) {
                 return $item['model_class']::find()->andWhere(['archived' => 0]);
             },
             'route'      => '/frontend/product/detail',
             'attributes' => [
                 'name',
                 'frame',
                 'tags' => ['name'],
             ],
             'url_params' => ['productId' => 'id', 'productName' => 'name'],
             'link_text'  => function ($item) {
                 $parts = [];
                 if ($item->getClassificationTag()) {
                     $cTag = $item->getClassificationTag();
                     $cTag !== null && $parts[] = $cTag->name;
                 }
                 $parts[] = $item->name;
                 return implode(': ', array_filter($parts));
             },
             'group'      => 'Products',
         ],
         'product-archive' => [
             'model_class'      => Product::class,
             'find_method' => function ($item) {
                 return $item['model_class']::find()->andWhere(['archived' => 1]);
             },
             'route'      => '/frontend/product/detail',
             'attributes' => [
                 'name',
                 'frame',
                 'tags' => ['name'],
             ],
             'url_params' => ['productId' => 'id', 'productName' => 'name'],
             'link_text'  => function ($item) {
                 $parts = [];
                 if ($item->getClassificationTag()) {
                     $cTag = $item->getClassificationTag();
                     $cTag !== null && $parts[] = $cTag->name;
                 }
                 $parts[] = $item->name;
                 return implode(': ', array_filter($parts));
             },
             'group'      => 'Products Archive',
         ],
         'accessories'  => [
             'model_class'      => Accessory::class,
             'route'      => '/frontend/accessory/detail',
             'attributes' => [
                 'name',
                 'tags' => ['name'],
             ],
             'url_params' => ['accessoryId' => 'id'],
             'link_text'  => 'productName',
             'group'      => 'P&A',
         ],
         'news'         => [
             'model_class'      => PublicationItem::class,
             'route'      => '/publication/default/detail',
             'attributes' => [
                 'title',
                 'content' => function ($item) {
                     $content = Json::decode($item->content_widget_json);
                     return html_entity_decode(strip_tags($content['text_html']));
                 }
             ],
             'url_params' => [
                 'itemId' => 'id',
                 'title'  => function ($item) {
                     return $item->title;
                 }
             ],
             'link_text'  => 'title',
             'group'      => 'News',
         ],
         'tags'         => [
             'model_class'      => \project\modules\cruds\models\Tag::class,
             'route'      => '/productfinder/default/index',
             'find_method' => function ($item) {
                 // get used tagIds from finder
                 $tag_ids = \project\modules\productfinder\models\Productfinder::getFacetIdList('tag_ids');
                 return $item['model_class']::find()->andWhere(['id' => $tag_ids]);
             },
             'attributes' => [
                 'name',
             ],
             'url_params' => [
                 'mainTag'     => 'id',
                 'mainTagName' => 'name',
             ],
             'link_text'  => function ($item) {
                 return implode(': ', [$item->tagGroup->name, $item->name]);
             },
             'group'      => 'Product Tags',
         ],
     ],
 ];
```

## Host info for URL Requests in Console Applications

If there is the need to make HTTP requests to fetch content, a Yii2 console application must be able to generate absolute URLs.
Since console apps lack an HTTP context, you need to set the UrlManager's hostInfo property to enable proper URL generation.
You can ie. set an ENV `CONSOLE_HOST_INFO` to the current URL of your page `https://example.com/`.

```php
'urlManager' => [
    'hostInfo' => getenv('CONSOLE_HOST_INFO'),
]
```

```php
'pages' => [
    // ...
    'attributes' => [
        'content' => function (Page $item) {
            // This method will fetch data using http requests
            return $item->fetchContentForSearchIndexer();
        }
    ],
    // ...
],
```
