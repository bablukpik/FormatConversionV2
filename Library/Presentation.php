<?php
namespace Library;

class Presentation {
    /**
     * Data from POST
     */
    public $data;

    /**
     * Data from GET
     */
    public $params;

    /**
     * Data from Files
     */
    public $files;

    public function __construct()
    {
        if ($this->isPost()) {
            $this->data = $_POST;
        }

        if (!empty($_GET)) {
            $this->params = $_GET;
        }

        if (isset($_FILES) && !empty($_FILES)) {
            $this->files = $_FILES;
        }
    }

    /**
     * Check is post
     *
     * @return bool
     */
    public function isPost()
    {
        if (isset($_POST) &&  !empty($_POST)) {
            return true;
        }

        return false;
    }

    /**
     * Render view
     *
     * @param $view
     * @param array $data
     * @return string
     */
    public function render($view, $data = array())
    {
        ob_start();
        extract($data);
        include(APP_PATH . '/View/'. $view . '.php');
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }

    /**
     * @param $url
     */
    public static function redirect($url)
    {
        header('Location: ' . $url);

        exit;
    }
}