    <?php
    /**
     * Autostart Plugin: Redirects to the namespace's start page if available
     *
     * @author Jesús A. Álvarez <zydeco@namedfork.net>
     */

    if (!defined('DOKU_INC')) die();
    if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
    require_once (DOKU_PLUGIN . 'action.php');

    class action_plugin_autostart extends DokuWiki_Action_Plugin
    {
        function getInfo() {
            return array (
                'author' => 'Jesús A. Álvarez',
                'email' => 'zydeco@namedfork.net',
                'date' => '2020-11-15',
                'name' => 'Autostart Plugin',
                'desc' => "Redirect from the namespace's name to its start page",
                'url' => 'https://www.dokuwiki.org/plugin:autostart',
            );
        }

        function page_exists($id) {
            if (function_exists('page_exists'))
                return page_exists($id);
            else
                return @file_exists(wikiFN($id));
        }

        function register(Doku_Event_Handler $controller) {
            $controller->register_hook('ACTION_ACT_PREPROCESS', 'AFTER', $this, 'preprocess', array ());
        }

        function preprocess(Doku_Event $event, $param) {
            global $conf;
            global $ID;
            //if (!$this->page_exists($ID) && $event->data == 'show')
            if (!$this->page_exists($ID))
            {
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


