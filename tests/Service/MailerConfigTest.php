<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

// config/packages/mailer.yaml : expéditeur par défaut des mails du site
final class MailerConfigTest extends KernelTestCase
{
    public function testMailsWithoutSenderAreSentFromContact(): void
    {
        $mailer = static::getContainer()->get(MailerInterface::class);

        $mailer->send(
            new Email()
                ->to('test@example.com')
                ->subject('test')
                ->text('test'),
        );

        self::assertEmailAddressContains(
            self::getMailerMessage(),
            'From',
            'contact@sylvainpillet.com',
        );
    }
}
