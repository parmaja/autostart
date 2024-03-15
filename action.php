<?php

use dokuwiki\Extension\ActionPlugin;
use dokuwiki\Extension\EventHandler;
use dokuwiki\Extension\Event;

/**
* Autostart Plugin: Redirects to the namespace's start page if available
*
* @author Jesús A. Álvarez <zydeco@namedfork.net>
*/


class action_plugin_autostart extends ActionPlugin
{

    public function page_exists($id) {
        if (function_exists('page_exists'))
            return page_exists($id);
        else
            return @file_exists(wikiFN($id));
    }

    public function register(EventHandler $controller)
    {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'AFTER', $this, 'preprocess', array ());
    }

    public function preprocess(Event $event, $param)
    {
        global $conf;
        global $ID;
        if (!$this->page_exists($ID)) {
            if($this->page_exists($ID.':'.$conf['start']))
                // start page inside namespace
                $id = $ID.':'.$conf['start'];
            elseif($this->page_exists($ID.':'.noNS(cleanID($ID))))
                // page named like the NS inside the NS
                $id = $ID.':'.noNS(cleanID($ID));
            if ($id) header('Location: ' . wl($id,'',true));
        }
    }

}


