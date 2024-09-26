<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Arr;

class SubscriptionLogicException extends Exception
{
    public function render($request)
    {
        if (config('app.env') !== 'production') {
            return response()->json([
                'message' => $this->getMessage(),
                'exception' => get_class($this),
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'trace' => collect($this->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all()
            ], $this->getCode());
        }

        return response()->json(['message' => $this->getMessage()], $this->getCode());
    }
}
