<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['email'] = array(
    'protocol'     => 'smtp',
    'smtp_host'    => 'sandbox.smtp.mailtrap.io',
    'smtp_port'    => 2525,
    'smtp_user'    => '647b65fc125f93',
    'smtp_pass'    => 'fd7aea1bc093cb',
    'smtp_crypto'  => 'tls',
    'smtp_timeout' => 15,
    'charset'      => 'utf-8',
    'mailtype'     => 'text',
    'wordwrap'     => TRUE,
    'newline'      => "\r\n",
    'crlf'         => "\r\n",
    'send_mail_to' => 'Sonideep1000@gmail.com'
);