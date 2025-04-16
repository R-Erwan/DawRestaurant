<?php

namespace Exception;

class PasswordResetRateLimitException extends \Exception {
    public function __construct($message = "Trop de demandes de reinitialisation,", $code = 429) {
        parent::__construct($message, $code);
    }
}