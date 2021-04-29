<?php
declare(strict_types=1);

namespace Vicfntm\Wifisher\Client;


interface NotifyInterface {
    public function send(...$params) ;
}