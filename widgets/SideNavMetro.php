<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-widgets
 * @version 1.1.0
 */

namespace hscstudio\heart\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * A custom extended side navigation menu extending Yii Menu
 *
 * For example:
 *
 * ```php
 * echo SideNav::widget([
 *     'items' => [
 *         [
 *             'url' => ['/site/index'],
 *             'label' => 'Home',
 *             'icon' => 'home'
 *         ],
 *         [
 *             'url' => ['/site/about'],
 *             'label' => 'About',
 *             'icon' => 'info-sign',
 *             'items' => [
 *                  ['url' => '#', 'label' => 'Item 1'],
 *                  ['url' => '#', 'label' => 'Item 2'],
 *             ],
 *         ],
 *     ],
 * ]);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
class SideNavMetro extends \kartik\widgets\SideNav
{

    /**
     * Allowed panel stypes
     */
    private static $_validTypes = [
        self::TYPE_DEFAULT,
        self::TYPE_PRIMARY,
        self::TYPE_INFO,
        self::TYPE_SUCCESS,
        self::TYPE_DANGER,
        self::TYPE_WARNING,
    ];
	
	public $colors = [
		'stick-red',
		'stick-yellow',
		'stick-green',
		'stick-blue',
		'stick-light-blue',
		'stick-aqua',
		'stick-navy',
		'stick-teal',
		'stick-olive',
		'stick-lime',
		'stick-orange',
		'stick-fuchsia',
		'stick-purple',
		'stick-maroon',
		'stick-black'
	];
	
	/**
     * @var string indicator for a menu sub-item
     */
    public $indItem = '<i class="indicatora fa fa-angle-right"></i> ';
	
	/**
     * @var string indicator for a opened sub-menu
     */
    public $indMenuOpen = '<i class="indicatora fa fa-angle-double-down"></i>';

    /**
     * @var string indicator for a closed sub-menu
     */
    public $indMenuClose = '<i class="indicator fa fa-angle-double-left"></i>';
	
    public function init()
    {
        parent::init();
        //SideNavAsset::register($this->getView());
        $this->activateParents = true;
        $this->submenuTemplate = "\n<ul class='nav nav-pills nav-stacked'>\n{items}\n</ul>\n";
        $this->linkTemplate = '<a href="{url}">{icon}{label}</a>';
        $this->labelTemplate = '{icon}{label}';
        $this->markTopItems();
        Html::addCssClass($this->options, 'nav nav-pills nav-stacked kv-sidenav');
    }

    /**
     * Renders the side navigation menu.
     * with the heading and panel containers
     */
    public function run()
    {
        $heading = '';
        if (isset($this->heading) && $this->heading != '') {
            Html::addCssClass($this->headingOptions, 'panel-heading');
            $heading = Html::tag('div', '<h3 class="panel-title">' . $this->heading . '</h3>', $this->headingOptions);
        }
        $body = Html::tag('div', $this->renderMenu(), ['class' => 'metro table']);
        $type = in_array($this->type, self::$_validTypes) ? $this->type : self::TYPE_DEFAULT;
        Html::addCssClass($this->containerOptions, "panel panel-{$type}");
		$this->containerOptions['style']="padding-left:10px;";
        echo Html::tag('div', $heading . $body, $this->containerOptions);
    }

    /**
     * Renders the main menu
     */
    protected function renderMenu()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = $_GET;
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        return Html::tag($tag, $this->renderItems($items), $options);
    }

    /**
     * Marks each topmost level item which is not a submenu
     */
    protected function markTopItems()
    {
        $items = [];
        foreach ($this->items as $item) {
            if (empty($item['items'])) {
                $item['top'] = true;
            }
            $items[] = $item;
        }
        $this->items = $items;
    }

    /**
     * Renders the content of a side navigation menu item.
     *
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     * @throws InvalidConfigException
     */
    protected function renderItem($item)
    {
        $this->validateItems($item);
        $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
        $url = Url::to(ArrayHelper::getValue($item, 'url', '#'));
        if (empty($item['top'])) {
            if (empty($item['items'])) {
                $template = str_replace('{icon}', $this->indItem . '{icon}', $template);
            } else {
                $template = '<a href="{url}" class="kv-toggle">{icon}{label}</a>';
                $openOptions = ($item['active']) ? ['class' => 'opened'] : ['class' => 'opened', 'style' => 'display:none'];
                $closeOptions = ($item['active']) ? ['class' => 'closed', 'style' => 'display:none'] : ['class' => 'closed'];
                $indicator = Html::tag('span', $this->indMenuOpen, $openOptions) . Html::tag('span', $this->indMenuClose, $closeOptions);
                $template = str_replace('{icon}', $indicator . '{icon}', $template);
            }
        }
        $icon = empty($item['icon']) ? '' : '<span class="' . $this->iconPrefix . $item['icon'] . '"></span> &nbsp;';
        unset($item['icon'], $item['top']);
        return strtr($template, [
            '{url}' => $url,
            '{label}' => $item['label'],
            '{icon}' => $icon
        ]);
    }

    /**
     * Validates each item for a valid label and url.
     *
     * @throws InvalidConfigException
     */
    protected function validateItems($item)
    {
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
    }
}