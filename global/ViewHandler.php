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

    /**
     * Return content of view part
     * @param string $view part of view, where is the content
     * @param string $default default content if the part of view doesnt exist
     */
    public function getView(string $view, string $default = ''): string {
        if(!isset($this->viewContent[$view])) {
            return $default;
        }
        return $this->viewContent[$view];
    }

    /**
     * Make variable available on view
     * @param string $varName name of variable
     * @param mixed $value value of variable
     * @param bool $saveSession save in session?
     */
    public function setVar(string $varName, $value, bool $saveSession = false) {
        $this->viewVariables[$varName] = $value;
        if($saveSession === true) {
            $_SESSION['view_variable_array'][$varName] = $value;
        }
    }

    /**
     * Get the variable value
     * If the variable in the session, save it
     * @param string $varName name of variable
     * @param null $default value if the variable does not exist
     * @return mixed value of variable
     */
    public function getVar(string $varName, $default = null) {
        if(!isset($this->viewVariables[$varName])) {
            if (isset($_SESSION['view_variable_array']) && isset($_SESSION['view_variable_array'][$varName])) {
                $value = $_SESSION['view_variable_array'][$varName];
                unset($_SESSION['view_variable_array'][$varName]);
                return $value;
            }
            return $default;
        }
        return $this->viewVariables[$varName];
    }

    /**
     * Session messages
     * (light flash message)
     * @param string $message the message
     */

    public function setMessageSession(string $message) {
        $this->setVar('_message', $message, true);
    }

    /**
     * get the "flash message"
     * @return string The message
     */
    public function getMessageSession() {
        return $this->getVar('_message', '');
    }

    /**
     * Set the layout to the render
     * @param string $layout layout to render
     * @return void
     */

    public function setLayout(string $layout) {
        $this->layout = $layout;
    }

    /**
     * render the view used by controller
     * @example If controller is $controller=controllerExample and the $view=viewExample, the file is view/controllerExample/viewExample.php
     * @param string $controller name of controller (url compatible)
     * @param string $viewName name of view
     */
    public function render(string $controller, string $viewName) {
        include (__DIR__ . "/../view/$controller/$viewName.php");
        $this->renderLayout();
    }

    /**
     * render the layout
     */
    private function renderLayout() {
        $this->newView('layout');
        include_once (__DIR__. "/../view/layouts/$this->layout.php");
        ob_flush();
    }

}
