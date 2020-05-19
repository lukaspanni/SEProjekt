<?php


class JSONView extends View
{

    public function __construct($data = null)
    {
        if ($data != null) {
            $this->set('data', $data);
        }
    }

    public function render()
    {
        echo json_encode($this->data);
    }
}