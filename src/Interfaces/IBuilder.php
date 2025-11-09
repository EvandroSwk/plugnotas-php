<?php

namespace EvandroSwk\Plugnotas\Interfaces;

interface IBuilder
{
    public function toArray();
    public static function fromArray($items);
}
