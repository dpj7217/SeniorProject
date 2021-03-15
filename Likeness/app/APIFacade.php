<?php

namespace App;

use Illuminate\Support\Facades\Facade;

class APIFacade extends Facade {

    protected static function getFacadeAccessor() {
        return 'api';
    }
}