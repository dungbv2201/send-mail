<?php

namespace Dung\Email\Cron;

use Dung\Email\Model\Email;

class SendMail
{
    protected $email;

    public function __construct(
        Email $email
    )
    {
        $this->email = $email;
    }

    public function execute()
    {
        $this->email->send();

    }



}