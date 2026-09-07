<?php

namespace App\Widgets;

use hoaaah\LaravelBreadcrumb\Breadcrumb;
use Illuminate\Support\Facades\Lang;
use LogicException;

class BreadCrumbCustom extends Breadcrumb
{
    public $homeUrl = '/';
    public $homeText = '';

    public function __construct()
    {
        $this->homeText = Lang::get('messages.home');
    }

    /**
     * @param array $configs
     * @return string
     */
    public static function widget(array $configs): string
    {
        $breadcrumbClass = new self();
        $return = '';

        if (isset($configs['homeUrl'])) $breadcrumbClass->homeUrl = $configs['homeUrl'];

        if (isset($configs['tagWrapper'])) $breadcrumbClass->tagWrapper = $configs['tagWrapper'];

        if (!isset($configs['items'])) {
            throw new LogicException('This widget need <code>items</code> configuration');
        }

        $return .= $breadcrumbClass->begin();
        $return .= $breadcrumbClass->renderItems($configs['items']);
        $return .= $breadcrumbClass->end();

        return $return;
    }
}
