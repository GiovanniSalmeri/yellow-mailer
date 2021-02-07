# Mailer 0.8.16

Email creation and transfer.

**This extension is experimental and should not be used in a production environment.**

## How to use Mailer

This extension is for developers who wish to use advanced mailing capabilities in their extensions. It exposes the following functions:

`validate($mail)`  
Sanitise an email, check its correctness and return an array `[$success, $errors]`, where `$success` is a boolean and `$errors` an array of error messages. This function is automatically called by `make($mail)`

`make($mail)`  
Return the email in `message/rfc822` format. This function is used by `send($mail)`

`send($mail, $dontValidate = false)`  
Send an email and return an array `[$success, $errors]`, where `$success` is a boolean and `$errors` an array of error messages

## How to define an email

`$mail` is an associative array where several fields can be set.

The fields for the content are the following:

`$mail['text']['plain']['heading']`  
`$mail['text']['plain']['body']`  
`$mail['text']['plain']['signature']`  
`$mail['text']['style-sheet']` name of style sheet in the `system/themes/` directory (without `.css`); you can also set the `default` style sheet of the site or a `void` style sheet.

The body and optional heading and signature should be written in markdown, without linebreaks inside a paragraph (paragraphs will be reflowed by mail user agents). If a style sheet is set, HTML content is automatically generated, but the single parts can be overriden with the following fields:

`$mail['text']['html']['heading']`  
`$mail['text']['html']['body']`  
`$mail['text']['html']['signature']`  

The fields for the email addresses are the following:

`$mail['headers']['to']` an array where the value is the email address, the key (if a string) is the name  
`$mail['headers']['cc']` "  
`$mail['headers']['bcc']` "  
`$mail['headers']['reply-to']` "  
`$mail['headers']['from']` an array of one address; if not set, the webmaster email is used  

The fields for other headers are the following:

`$mail['headers']['subject']`  
`$mail['headers']['custom']` an array where the keys are the names of the custom headers without `X-`, the values are their contents  

The field for the attachments is the following:

`$mail['attachments']` an array of names of files located in the directory `media/attachments/`  

An iCalendar part can be added with the following fields:

`$mail['ical']['time']` an array of two values (start and end) in format `YYYY-MM-DD HH:MM`  
`$mail['ical']['location']`  
`$mail['ical']['geo']` latitude and longitude in decimal format, comma-separated, e.g. `37.386013,-122.082932`  
`$mail['ical']['summary']`  
`$mail['ical']['description']`  

## Example

Sending an email from an extension:

```
$mail['text']['plain']['body'] = "Yellow is the best content management system in the world!";
$mail['headers']['subject'] = "My first email";
$mail['headers']['to'] = ["john@example.org", "Mary Penn" => "marypenn@example.com"];

$mailer = $this->yellow->extension->get("mailer");
$mailer->send($mail);
```

## Settings

The following settings can be configured in file `system/extensions/yellow-system.ini`:

`mailerSender` (default:  `postmaster@`hostname) =  address of envelope sender  
`mailerTransport` (default:  `sendmail`) =  how to deliver the email (possible values: `sendmail`, `qmail`, `smtp`)  
`mailerSendmailPath` (default:  `/usr/sbin/sendmail`) = path of sendmail or qmail  
`mailerSmtpServer` = address of the SMTP server (e.g. `smtp.server.com`); if necessary a non-standard port can be specified (e.g. `smtp.server.com:2525`)  
`mailerSmtpSecurity` (default:  `starttls`) = protocol for secure email transport (possible values: `starttls`,  `ssl`, `none`)  
`mailerSmtpUsername` = SMTP username  
`mailerSmtpPassword` = SMTP password  
`mailerAttachmentDirectory` (default:  `media/attachments/`) = Directory for attachments  
`mailerAttachmentsMaxSize` (default:  `20000000`) = Maximum total size of the attachments of an email  

The address in `mailerSender` receives non-delivery reports and is included in the `Return-Path` header of delivered emails. Can be dynamically changed with `$this->yellow->system->set("mailerSender", "address@domain")`, but for security reasons must never be assigned a user-supplied value.

## Installation

[Download extension](https://github.com/GiovanniSalmeri/yellow-mailer/archive/master.zip) and copy zip file into your `system/extensions` folder. Right click if you use Safari.

## Developer

Giovanni Salmeri. [Get help](https://github.com/GiovanniSalmeri/yellow-mailer/issues).
