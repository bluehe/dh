<?php

namespace dh\components;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Class Menu
 * Theme menu widget.
 */
class Tab extends Widget {

    /**
     * @inheritdoc
     */
    public $items = [];
    public $showImg = false;
    public $template = '<div class="list-group-item" data-id="{id}">{img} <a class="clickurl" target="_blank" href="{url}" title="{title}">{title}</a></div>';

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run() {
        echo $this->renderItems($this->items);
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item) {
        $template = ArrayHelper::getValue($item, 'template', $this->template);
        $replace = [
            '{id}' => $item['id'],
            '{img}' => $this->showImg ? Html::img(['api/getfav', 'url' => $item['url']]) : null,
            '{url}' => Url::to($item['url']),
            '{title}' => $item['title'],
            '{label}' => isset($item['label']) ? '<span class="badge">' . $item['label'] . '</span>' : null,
        ];
        return strtr($template, $replace);
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items) {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $lines[] = $this->renderItem($item);
        }
        return implode("\n", $lines);
    }

}
