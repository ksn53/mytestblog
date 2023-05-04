<?php
namespace App;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use App\Model\Subscribtions;
use App\Model\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerController
{
    public function sendToSubs($subject, $html)
    {
        $emails = $this->allSubEmails();
        foreach ($emails as $email) {
            $this->sendMail($email[0], 'admin@user-virtual-machine', $subject, $html . "<br><br><a href='http://" . $_SERVER['HTTP_HOST'] . "/unsubscribe/" . $email[1] . "'>Отписаться от рассылки</a>");
        }
    }
    public function sendToUsers($subject, $html)
    {
        $emails = $this->allUserEmails();
        foreach ($emails as $email) {
            $this->sendMail($email, 'admin@user-virtual-machine', $subject, $html . "<br><br>Отписаться от рассылки вы можете в панели управления учётной записью.");
        }
    }
    public function sendMail($to, $from, $subject, $html)
    {
        $transport = new EsmtpTransport('localhost');
            $mailer = new Mailer($transport);
            $email = (new Email())
                ->from($from)
                ->to($to)
                ->replyTo($from)
                ->subject($subject)
                ->html($html)
            ;
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                return false;
            }

    }
    public function allUserEmails()
    {
        $emails=[];
        $userData = User::where('subscribed', 1)->get();
        foreach ($userData as $user) {
            $emails[] = $user->email;
        }
        return $emails;
    }
    public function allSubEmails()
    {
        $emails = [];
        $subscribtions = Subscribtions::get();
        foreach ($subscribtions as $subscribtion) {
            $emails[] = [$subscribtion->email, $subscribtion->unsubscribe];
        }
        return $emails;
    }
}