<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use yii\helpers\Html;

/**
 *
 * @var array $result
 * @var dmstr\activeRecordSearch\models\SearchGroup[] $resultGroups
 * @var dmstr\activeRecordSearch\models\FrontendSearch $model
 * @var string $searchInputWidget
 *
 */

?>

<div class="<?= str_replace('/', '-', $this->context->action->uniqueId) . '-view' ?>">
    <div class="container">
        <?php
            if ($searchInputWidget) {
                echo $searchInputWidget::widget(['model' => $model, 'wrapperClass' => 'search-input-frontend']);
            }
        ?>
    </div>
    <div class="container search-result">
    <?php
    if (!empty($result)) {
        $empty = true;
        foreach ($result as $group => $items) {
            if (count($items) == 0) {
                continue;
            }
            $empty = false;
            echo '<div class="search-result-group">';
            echo HTML::tag('h3', HTML::encode($resultGroups[$group]->name), ['class' => 'search-result-title']);
            foreach ($items as $item) {
                $link = HTML::a(HTML::encode($item['link_text']), \dmstr\activeRecordSearch\models\FrontendSearch::itemUrl($item));
                echo HTML::tag('div', $link, ['class' => 'search-result-link']);
            }
            echo '</div>';
        }
    }
    ?>
    </div>

    <?php
    if (!empty($empty)) {
        echo HTML::tag('div', Yii::t('search', 'Unfortunately we could not find anything. Please check spelling or change search term.'), ['class' => 'container search-no-result']);
    }
    ?>

</div>
