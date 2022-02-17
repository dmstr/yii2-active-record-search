<?php

namespace dmstr\activeRecordSearch\components;

use dmstr\activeRecordSearch\models\Search;
use dmstr\activeRecordSearch\models\SearchGroup;
use Yii;
use yii\base\Component;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/**
 * Search IndexController
 * @package dmstr\activeRecordSearch\commands
 * Author: Jens Giessmann <j.giessmann@herzogkommunikation.de>
 *
 * Example Config:
 *
 * $config['components']['searchIndexer'] = [
 *     'class' => \dmstr\activeRecordSearch\components\SearchIndexer::class,
 *     'languages' => function() {
 *         return project\components\CountryHelper::activeLanguages();
 *     },
 *     'searchItems' => [
 *         'products'        => [
 *             'model_class'      => Product::class,
 *             'find_method' => function ($item) {
 *                 return $item['model_class']::find()->andWhere(['archived' => 0]);
 *             },
 *             'route'      => '/frontend/product/detail',
 *             'attributes' => [
 *                 'name',
 *                 'frame',
 *                 'tags' => ['name'],
 *             ],
 *             'url_params' => ['productId' => 'id', 'productName' => 'name'],
 *             'link_text'  => function ($item) {
 *                 $parts = [];
 *                 if ($item->getClassificationTag()) {
 *                     $cTag = $item->getClassificationTag();
 *                     $cTag !== null && $parts[] = $cTag->name;
 *                 }
 *                 $parts[] = $item->name;
 *                 return implode(': ', array_filter($parts));
 *             },
 *             'group'      => 'Products',
 *         ],
 *         'product-archive' => [
 *             'model_class'      => Product::class,
 *             'find_method' => function ($item) {
 *                 return $item['model_class']::find()->andWhere(['archived' => 1]);
 *             },
 *             'route'      => '/frontend/product/detail',
 *             'attributes' => [
 *                 'name',
 *                 'frame',
 *                 'tags' => ['name'],
 *             ],
 *             'url_params' => ['productId' => 'id', 'productName' => 'name'],
 *             'link_text'  => function ($item) {
 *                 $parts = [];
 *                 if ($item->getClassificationTag()) {
 *                     $cTag = $item->getClassificationTag();
 *                     $cTag !== null && $parts[] = $cTag->name;
 *                 }
 *                 $parts[] = $item->name;
 *                 return implode(': ', array_filter($parts));
 *             },
 *             'group'      => 'Products Archive',
 *         ],
 *         'news'         => [
 *             'model_class'      => PublicationItem::class,
 *             'route'      => '/publication/default/detail',
 *             'attributes' => [
 *                 'title',
 *                 'content' => function ($item) {
 *                     $content = Json::decode($item->content_widget_json);
 *                     return html_entity_decode(strip_tags($content['text_html']));
 *                 }
 *             ],
 *             'url_params' => [
 *                 'itemId' => 'id',
 *                 'title'  => function ($item) {
 *                     return $item->title;
 *                 }
 *             ],
 *             'link_text'  => 'title',
 *             'group'      => 'News',
 *         ],
 *         'tags'         => [
 *             'model_class'      => \project\modules\cruds\models\Tag::class,
 *             'route'      => '/productfinder/default/index',
 *             'find_method' => function ($item) {
 *                 // get used tagIds from finder
 *                 $tag_ids = \project\modules\productfinder\models\Productfinder::getFacetIdList('tag_ids');
 *                 return $item['model_class']::find()->andWhere(['id' => $tag_ids]);
 *             },
 *             'attributes' => [
 *                 'name',
 *             ],
 *             'url_params' => [
 *                 'mainTag'     => 'id',
 *                 'mainTagName' => 'name',
 *             ],
 *             'link_text'  => function ($item) {
 *                 return implode(': ', [$item->tagGroup->name, $item->name]);
 *             },
 *             'group'      => 'Product Tags',
 *         ],
 *     ],
 * ];

 *
 */
class SearchIndexer extends Component
{

    /**
     * definition what should be indexed
     *
     * @var
     */
    public $searchItems;
    /**
     * array of languages for which we should index
     *
     * @var array
     */
    public $languages = [];

    /**
     * fallbackLanguage (for translatable behaviour) which will be used to create new SearchGroup entries.
     * if not set, we will use the first lang from the languages array.
     *
     * @var
     */
    public $fallbackLanguage;

    /**
     * php mem_limit for the indexer
     * @var string
     */
    public $memoryLimit = '1024M';
    /**
     * php max_execution_time for the indexer
     * @var int
     */
    public $maxExecutionTime = 1800;

    /**
     * output some debug infos while indexing
     * @var bool
     */
    public $debug = false;

    protected $transaction;
    protected $currentLang;

    /**
     * init system for this long running process
     * - set memory_limit and max_execution_time
     * - disable/remove audit LogTarget (which eats a lot of memory...)
     */
    protected function sysInit()
    {
        ini_set('memory_limit', $this->memoryLimit);
        ini_set('max_execution_time', $this->maxExecutionTime);
        // unset audit logTarget due to huge memory peaks
        if (isset(Yii::$app->log->targets['audit'])) {
            Yii::$app->log->targets['audit'] = null;
            unset(Yii::$app->log->targets['audit']);
        }
    }

    /**
     * Update search index
     */
    public function update()
    {

        $this->sysInit();

        $detailInfo = [];
        $sTime = microtime(true);

        $this->transaction = Yii::$app->db->beginTransaction();

        Search::deleteAll();

        $this->languages = $this->initLanguages();
        $this->fallbackLanguage = $this->initFallbackLanguage();

        $this->updateSearchItemGroupsFromConfig();

        $cmd_lang = \Yii::$app->language;
        foreach ($this->languages as $lang) {
            $this->currentLang = $lang;
            $detailInfo[$this->currentLang] = [];
            \Yii::$app->language = $this->currentLang;
            $this->out("Start processing language: {$this->currentLang}");
            $this->memDebug();
            foreach ($this->searchItems as $itemKey => $item) {
                if (! $this->checkItemConfig($item)) {
                    $this->out('Config item with key: ' . $itemKey . ' is empty/unset and will be ignored.');
                    continue;
                }
                /** @var ActiveQuery $query */
                $models = $this->getItemsQuery($item)->all();
                #$this->memDebug();
                $detailInfo[$this->currentLang][$item['group']] = 0;
                // todo skip if empty
                foreach ($models as $model) {
                    $text = '';
                    foreach ($item['attributes'] as $a_key => $a_value) {
                        if (is_array($a_value)) {
                            // try to get submodels
                            if (!empty($model->$a_key)) {
                                $this->debug('process submodels');
                                // if 1:1 relation, init dummy array for loop below
                                $sub_models = is_array($model->$a_key) ? $model->$a_key : [$model->$a_key];
                            } else {
                                $this->debug('no submodels found');
                            }
                            // get values from submodel
                            foreach ($a_value as $prop) {
                                foreach ($sub_models as $sub_model) {
                                    $text .= ' ' . $this->processProperty($sub_model, $prop);
                                }
                            }
                        } else {
                            $this->debug('process basemodel property');
                            $text .= ' ' . $this->processProperty($model, $a_value);
                        }
                    }
                    if (empty(trim($text))) {
                        $this->debug('text for this model is empty -> continue');
                        continue;
                    }

                    $searchItem = new Search();
                    $searchItem->model_class = $item['model_class'];
                    $searchItem->route = $this->processRoute($model, $item['route']);
                    $searchItem->language = $this->currentLang;
                    $searchItem->model_id = $model->id;
                    $searchItem->search_text = $text;
                    $searchItem->url_params = Json::encode($this->processUrlParams($model, $item['url_params']));
                    $searchItem->link_text = $this->processProperty($model, $item['link_text']);
                    $searchItem->group = $item['group'];
                    if ($searchItem->validate()) {
                        if ($searchItem->save()) {
                            $this->outItemInfo($searchItem);
                        } else {
                            \Yii::$app->language = $cmd_lang;
                            $this->transaction->rollBack();
                            Yii::error('error while saving model ' . $item['model_class'] . ' with lang ' . $this->currentLang . ', id ' . $model->id . ' rolled back transaction!');
                            Yii::error($searchItem->errors);
                        }
                    } else {
                        \Yii::$app->language = $cmd_lang;
                        $this->transaction->rollBack();
                        Yii::error('error while validating model ' . $item['model_class'] . ' with lang ' . $this->currentLang . ', id ' . $model->id . ' rolled back transaction!');
                        Yii::error($searchItem->errors);
                        exit;
                    }
                    ++$detailInfo[$this->currentLang][$item['group']];
                    // cleanup mem
                    $searchItem = null;
                    unset($searchItem);
                    $model = null;
                }
                // cleanup mem
                $models = null;
                unset($models);

            }
            $this->out('Done language: ' . $this->currentLang);
            $this->memDebug();
        }
        \Yii::$app->language = $cmd_lang;
        if ($this->transaction->isActive) {
            $this->transaction->commit();
        }
        $this->out("done, used time: " . (microtime(true) - $sTime) . 'sec');
        $this->out(VarDumper::dumpAsString($detailInfo));

    }

    /**
     * if item is array we check required properties.
     * if item is null, item will be ignored.
     *
     * @param $item
     */
    protected function checkItemConfig($item) {
        if (empty($item)) {
            return false;
        }
        $required = ['model_class', 'route', 'attributes', 'url_params', 'link_text', 'group'];
        foreach ($required as $key) {
            if (!array_key_exists($key, $item)) {
                Yii::error('Required property [' . $key . '] not set in config item.' . PHP_EOL . print_r($item, 1));
                exit;
            }
        }
        return true;
    }

    /**
     * process languages param
     *
     * @return array|false|mixed
     */
    protected function initLanguages()
    {
        if (is_callable($this->languages)) {
            $this->debug('get languages from config callable');
            return call_user_func($this->languages);
        }
        if (is_array($this->languages)) {
            $this->debug('get languages from config array');
            return $this->languages;
        }
        return [];
    }

    /**
     * init fallbackLanguage param which is used as init translation lang when creating new groups
     *
     * @return false|mixed|string
     */
    protected function initFallbackLanguage()
    {
        if (is_callable($this->fallbackLanguage)) {
            $this->debug('get fallbackLanguage from config callable');
            return call_user_func($this->fallbackLanguage);
        }
        if (is_string($this->fallbackLanguage)) {
            $this->debug('get fallbackLanguage from config string');
            return $this->fallbackLanguage;
        }
        return $this->languages[0];
    }

    /**
     * get Query for item
     * if not defined we will use the default AR::find() Method
     * if defined as callback return result
     * if defined as string exec model_class::find_method()
     *
     * @param $item
     *
     * @return mixed
     */
    protected function getItemsQuery($item)
    {
        // as default we use the default find() Method from AR-ModelClass
        if (empty($item['find_method'])) {
            return $item['model_class']::find();
        }
        // if callback is defined, use this
        if (is_callable($item['find_method'])) {
            return $item['find_method']($item);
        }
        // otherwise call find_method from given item
        return $item['model_class']::{$item['find_method']}();

    }

    /**
     * get route for item, can be defined as callback or string
     *
     * @param $item
     * @param $param
     *
     * @return string
     */
    protected function processRoute($item, $param)
    {
        if (is_callable($param)) {
            $this->debug('process callable to get route from model');
            return trim($param($item));
        }
        return $param;
    }

    /**
     * get url_params for each item
     * each param can be defined as string (attribute name) or callback
     *
     * @param $item
     * @param $params
     *
     * @return array
     */
    protected function processUrlParams($item, $params)
    {
        $url_params = [];
        foreach ($params as $p_key => $p_value) {
            $value = $this->processProperty($item, $p_value);
            if (!empty($value)) {
                $url_params[$p_key] = $value;
            }
        }
        return $url_params;
    }

    /**
     * process given property for given item
     *
     * if property is a callback, it will be called with item as param
     * if property is a string, and item is an object we return $item->$prop
     * if property is a string, and item is an array we return $item[$prop]
     *
     * @param $item
     * @param $prop
     *
     * @return string
     */
    protected function processProperty($item, $prop)
    {
        if (is_callable($prop)) {
            $this->debug('process callable');
            return trim($prop($item));
        }

        if (is_object($item) && isset($item->$prop)) {
            $this->debug('process submodel prop : ' . $prop . ' -> ' . $item->$prop);
            return trim($item->$prop);
        }

        if (is_array($item) && isset($item[$prop])) {
            $this->debug('process sub array key : ' . $prop . ' -> ' . $item[$prop]);
            return trim($item[$prop]);
        }

        return '';
    }

    /**
     * if required create new SearchGroup while indexing
     *
     * @return void
     */
    protected function updateSearchItemGroupsFromConfig() {
        foreach ($this->searchItems as $item) {

            if (! isset($item['group'])) {
                continue;
            }

            if (SearchGroup::find()->andWhere(['ref_name' => $item['group']])->one() === null) {
                $cmd_lang = \Yii::$app->language;
                \Yii::$app->language = $this->fallbackLanguage;
                $this->out("need to create new group for " . $item['group']);
                $sGroup = new SearchGroup();
                $sGroup->ref_name = $item['group'];
                $sGroup->name = $item['group'];
                if (! $sGroup->save()) {
                    VarDumper::dumpAsString($sGroup->errors);
                }
                \Yii::$app->language = $cmd_lang;
            }
        }
    }

    /**
     * info, debug output methods while indexing
     */

    /**
     * @param Search $searchItem
     *
     * @return void
     */
    protected function outItemInfo(Search $searchItem)
    {
        $values = [$this->currentLang, $searchItem->model_class, $searchItem->model_id];
        if ($this->debug) {
            $values = [$this->currentLang, $searchItem->model_class, $searchItem->model_id, $searchItem->search_text];
        }
        $msg = 'Saved: ' . implode(' | ', $values);
        $this->out($msg);
    }

    protected function debug($msg) {
        $this->debug && $this->out($msg);
    }

    protected function out($msg)
    {
        echo $msg . PHP_EOL;
    }
    protected function err($msg)
    {
        echo $msg . PHP_EOL;
    }

    protected function memDebug()
    {
        if (YII_ENV_DEV) {
            $this->out($this->currentLang . " memory_usage: " . memory_get_usage() / 1024 / 1024);
            $this->out($this->currentLang . " memory_usage real: " . memory_get_usage(true) / 1024 / 1024);
            $this->out($this->currentLang .  " memory_peak_usage: " . memory_get_peak_usage(true) / 1024 / 1024);
            sleep(1);
        }
    }
}
