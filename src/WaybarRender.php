<?php

namespace Timer;

class WaybarRender
{
    public function __construct(
        public string $text,
        public string $alt = '',
        public string $tooltip = '',
        public string $class = ''
    )
    {
        //
    }

    public function toJson() : string
    {
        return json_encode([
            'text' => $this->text,
            'alt' => $this->alt,
            'tooltip' => $this->tooltip,
            'class' => $this->class,
        ]);
    }

}