<?php
namespace Akasima\RichPanel\Skins;

use View;
use Xpressengine\Skin\AbstractSkin;

class SettingsSkin extends AbstractSkin
{
    /**
     * render
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $view = View::make(sprintf('rich_panel::views.settingsSkin.%s', $this->view), $this->data);

        return $view;
    }

    /**
     * get manage URI
     *
     * @return string
     */
    public static function getSettingsURI()
    {
    }
}
