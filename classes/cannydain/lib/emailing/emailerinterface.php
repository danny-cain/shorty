<?php

namespace CannyDain\Lib\Emailing;

use CannyDain\Lib\Emailing\Models\Email;

interface EmailerInterface
{
    public function sendEmail(Email $email);
}