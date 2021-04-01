<?php

class ViewHandler {

    /**
     * default view key
     * @var string DEFAULT_VIEW
     */
    const DEFAULT_VIEW = '__default__';

    /**
     * puffer content for all view part
     *@var array $viewContent
     */
    private $viewContent = array();

    /**
     * View variables array
     * @var array $viewVariables
     */
    private $viewVariables = array();

    /**
     * actual view
     * @var string $currentView
     */
    private $currentView = self::DEFAULT_VIEW;

    /**
     * Layout name, which one was use when render
     * @var string $layout
     */
    private $layout = 'main';

    public function __construct(){
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        ob_start();
    }

    /**
     * save the view from output puffer to layout.
     * end of the operation delete the puffer
     */
    private function saveCurrentView() {
        $this->viewContent[$this->currentView] .= ob_get_contents();
        ob_clean();
    }


    /**
     * save the output before change.
     * call when the view, or part of view is change
     * @param string $name View name
     */
    public function newView(string $name) {
        $this->saveCurrentView();
        $this->currentView = $name;
    }

    /**
     * Load the default view
     */
    public function defaultViewLoad() {
        $this->newView(self::DEFAULT_VIEW);
    }

    //TODO: View handler
}
