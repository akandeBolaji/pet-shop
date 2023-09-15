<?php

namespace App\Libraries\CurrencyExchanger;

use App\Traits\HandlesResponse;
use PetShop\CurrencyExchanger\Contracts\ResponseHandlerContract;

class ResponseHandler implements ResponseHandlerContract {
    use HandlesResponse;
}