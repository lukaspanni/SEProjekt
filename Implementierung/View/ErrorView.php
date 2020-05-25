<?php


class ErrorView extends TemplateView
{

    public function __construct($model)
    {
        parent::__construct($model, "error", "default");
    }

    public function render()
    {
        if ($this->model != null) {
            http_response_code($this->model->getHttpStatusCode());
        }
        parent::render();
    }

}
