<?php

namespace CannyDain\Lib\Emailing;

use CannyDain\Lib\Emailing\Models\Email;

class NullEmailer implements EmailerInterface
{
    public function sendEmail(Email $email)
    {

    }
}