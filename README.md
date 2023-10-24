# Mailer 0.8.17

Email creation and transfer.

## How to install an extension

[Download ZIP file](https://github.com/GiovanniSalmeri/yellow-mailer/archive/main.zip) and copy it into your `system/extensions` folder. [Learn more about extensions](https://github.com/annaesvensson/yellow-update).

## How to use Mailer

If Mailer is installed, it will be automatically used for email delivering by all other Yellow extensions which employ the method `toolbox->mail`. You can choose in the settings between `sendmail`, `qmail`, and `SMTP` as transport. All of them are more reliable than the `mail` command of PHP.

## How to use Mailer from sending complex emails

Besides sending simple text emails, Mailer supports an alternative interface of `toobox->mail` for sending complex emails (HTML, attachments, iCal) from other extensions:

`toolbox->mail($action, $mail, true): bool`

`$mail` is an associative array where several fields can be set.

The fields for the content are the following:

`$mail["text"]["plain"]["heading"]`  
`$mail["text"]["plain"]["body"]`  
`$mail["text"]["plain"]["signature"]`  
`$mail["text"]["style-sheet"]` name of style sheet in the `system/themes/` directory (without `.css`); you can also set the `default` style sheet of the site or a `void` style sheet.

The body and optional heading and signature should be written in markdown, without linebreaks inside a paragraph (paragraphs will be reflowed by mail user agents). If a style sheet is set, HTML content is automatically generated, but the single parts can be overriden with the following fields:

`$mail["text"]["html"]["heading"]`  
`$mail["text"]["html"]["body"]`  
`$mail["text"]["html"]["signature"]`  

The fields for the email addresses are the following:

`$mail["headers"]["to"]` an array where the value is the email address, the key (if a string) is the name  
`$mail["headers"]["cc"]` "  
`$mail["headers"]["bcc"]` "  
`$mail["headers"]["reply-to"]` "  
`$mail["headers"]["from"]` an array of one address; if not set, the webmaster email is used  

The fields for other headers are the following:

`$mail["headers"]["subject"]`  
`$mail["headers"]["custom"]` an array where the keys are the names of the custom headers without `X-`, the values are their contents  

The field for the attachments is the following:

`$mail["attachments"]` an array of names of files located in the directory `media/attachments/`  

An iCalendar part can be added with the following fields:

`$mail["ical"]["time"]` an array of two values (start and end) in format `YYYY-MM-DD HH:MM`  
`$mail["ical"]["location"]`  
`$mail["ical"]["geo"]` latitude and longitude in decimal format, comma-separated, e.g. `37.386013,-122.082932`  
`$mail["ical"]["summary"]`  
`$mail["ical"]["description"]`  

## Examples

Sending an email from an extension:

```
$mail["text"]["plain"]["body"] = "**Yellow** is the best content management system in the world!";
$mail["text"]["style-sheet"] = "void"
$mail["headers"]["subject"] = "My first email";
$mail["headers"]["to"] = [ "john@example.org", "Mary Penn" => "marypenn@example.com" ];
$mail["attachments"] = [ "yellow.pdf" ];

$status = $this->yellow->toolbox->mail("message", $mail, true);
```

## Settings

The following settings can be configured in file `system/extensions/yellow-system.ini`:

`MailerSender` =  address of envelope sender  
`MailerTransport` =  how to deliver the email, `sendmail`, `qmail`, or `smtp`  
`MailerSendmailPath` = path of sendmail or qmail  
`MailerSmtpServer` = address of the SMTP server (e.g. `smtp.server.com`); a non-standard port can be specified (e.g. `smtp.server.com:2525`)  
`MailerSmtpSecurity` = protocol for secure email transport, `ssl`, `starttls`, or `none`; `ssl` is [always to be preferred](https://nostarttls.secvuln.info/) to `starttls`  
`MailerSmtpUsername` = SMTP username  
`MailerSmtpPassword` = SMTP password  
`MailerAttachmentDirectory` = directory for attachments  
`MailerAttachmentsMaxSize` = maximum total size of the attachments of an email  

The address in `MailerSender` receives non-delivery reports and is included in the `Return-Path` header of delivered emails. It can be dynamically changed with `$this->yellow->system->set("mailerSender", "address@domain")`, but for security reasons must never be assigned a user-supplied value.

## Developer

Giovanni Salmeri. [Get help](https://datenstrom.se/yellow/help/).
