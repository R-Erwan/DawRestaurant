<?php

namespace Exception;

class PasswordResetRateLimitException extends \Exception {
    public function __construct($message = "Too many reset request", $code = 429) {
        parent::__construct($message, $code);
    }
}