<?php

namespace FormDataManager\Handlers;

use FormDataManager\Interfaces\HandlerInterface;
use MODX\Revolution\Mail\modMail;
use MODX\Revolution\Mail\modPHPMailer;
use MODX\Revolution\modX;
use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

class Email implements HandlerInterface
{
    private modPHPMailer $mailer;
    private modX $modx;

    public function __construct(modX $modX)
    {
        $this->mailer = new modPHPMailer($modX);
        $this->modx = $modX;
    }

    public function run(xPDOSimpleObject $object): bool
    {
        $chunk = $this->modx->getObject('modChunk',array('name' => 'templaterequest'));

        $formData = '<ul>';

        foreach ($object->toArray() as $item=>$value) {
            $formData .= "<li>$item: $value</li>";
        }

        $formData .= '</ul>';
        $formData = $chunk->process(['data' => $formData]);
        $sender = 'Max1mus1995.ms@gmail.com';
        $recipient = 'albertenshtain51@gmail.com';

        $this->mailer->set(modMail::MAIL_BODY, $formData);
        $this->mailer->set(modMail::MAIL_FROM, $sender);
        $this->mailer->set(modMail::MAIL_FROM_NAME, 'Bob');
        $this->mailer->set(modMail::MAIL_SENDER, $sender);
        $this->mailer->set(modMail::MAIL_SUBJECT, 'TESTING');
        $this->mailer->address('to', $recipient);
        $this->mailer->address('reply-to', $sender);
        $this->mailer->setHTML(true);

        if (!$this->mailer->send()) {
            $err = 'Error: ' . $this->mailer->mailer->ErrorInfo;
            $this->mailer->modx->log(xPDO::LOG_LEVEL_ERROR, $err);
        }

        $this->mailer->reset();
        return true;
    }
}