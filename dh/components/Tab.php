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
    public $urlTemplate = '<div class="list-group-item" data-id="{id}">{img} <a class="clickurl url" target="_blank" href="{url}" title="{title}">{title}</a> {label}</div>';
    public $userTemplate = '<div class="list-group-item">{img} <a class="url" href="{url}" title="{title}">{title}</a> {label}</div>';

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
        if (isset($item['template_id']) && $item['template_id'] == 'user') {
            $template = ArrayHelper::getValue($item, 'template', $this->userTemplate);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->urlTemplate);
        }

        $replace = [
            '{id}' => isset($item['id']) ? $item['id'] : null,
            '{img}' => isset($item['img']) ? $item['img'] : null,
            '{url}' => Url::to($item['url']),
            '{title}' => $item['title'],
            '{label}' => isset($item['label']) ? '<span class="badge' . (isset($item['label_class']) ? ' ' . $item['label_class'] : '') . '">' . $item['label'] . '</span>' : null,
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
        foreach ($items as $item) {
            $lines[] = $this->renderItem($item);
        }
        return implode("\n", $lines);
    }

}
