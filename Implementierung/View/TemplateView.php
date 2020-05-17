<?php


class TemplateView extends View
{

    protected $header;
    protected $template;
    protected $footer;
    protected $scripts;
    protected $styles;
    protected $main;

    public function __construct($model = null, $main_name = null, $template_name = "default", $header_name = "header", $footer_name = "footer", $scripts = null, $styles = null)
    {
        parent::__construct($model);
        $template_file = SERVER_ROOT . "View/Layouts/" . $template_name . ".php";
        $header_file = SERVER_ROOT . "View/fragments/" . $header_name . ".php";
        $footer_file = SERVER_ROOT . "View/fragments/" . $footer_name . ".php";
        if (file_exists($template_file)) {
            $this->template = $template_file;
        }
        if (file_exists($header_file)) {
            $this->header = $header_file;
        }
        if (file_exists($footer_file)) {
            $this->footer = $footer_file;
        }
        $this->setMain($main_name);
        $this->scripts = $scripts;
        $this->styles = $styles;

    }

    public function setMain($main_name){
        $main_file = SERVER_ROOT . "View/fragments/" . $main_name . ".php";
        if (file_exists($main_file)) {
            $this->main = $main_file;
        }
    }


    public function render()
    {
        if (isset($this->template)) {
            require($this->template);
        } else {
            return_404_error();
        }
    }
}