<?php
/**
 * DokuWiki Plugin resultimages (Action Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Anna Dabrowska <dokuwiki@cosmocode.de>
 */

class action_plugin_resultimages extends DokuWiki_Action_Plugin
{

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     *
     * @return void
     */
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('SEARCH_RESULT_FULLPAGE', 'BEFORE', $this, 'addImage');
    }

    /**
     * Inserts page's first image into the result body
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     *
     * @return void
     */
    public function addImage(Doku_Event $event, $param)
    {
        // exit if the current result will not even have a preview snippet
        if ($event->data['position'] > FT_SNIPPET_NUMBER) return;

        $img = p_get_metadata($event->data['page'], 'relation firstimage');

        if (empty($img) || !is_file(mediaFN($img))) return;

        $event->data['resultBody'] =
            ['image' => '<a href="' . wl($event->data['page']) . '"><img src="' . ml($img) . '" /></a>'] +
            $event->data['resultBody'];
    }
}
