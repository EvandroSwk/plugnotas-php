<?php

namespace EvandroSwk\Plugnotas\Interfaces;

use EvandroSwk\Plugnotas\Configuration;

interface IDfe
{
    public function cancel($id);
    public function download($id);
    public function find($id);
    public function send($configuration = null);
    public function toArray();
    public function validate();
    public static function fromArray($items);
}
