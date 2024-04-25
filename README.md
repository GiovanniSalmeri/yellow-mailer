# Mailer 0.9.1

Email creation and transfer.

## How to install an extension

[Download ZIP file](https://github.com/GiovanniSalmeri/yellow-mailer/archive/refs/heads/main.zip) and copy it into your `system/extensions` folder. [Learn more about extensions](https://github.com/annaesvensson/yellow-update).

## How to use Mailer for sending emails

Mailer is meant to improve the functionality of other extensions, by providing `sendmail`, `qmail`, or `SMTP` transports for email delivering. All of them are more reliable than the default `mail` function of PHP.

When installed, Mailer will be automatically used by all extensions which employ the [standard method](https://datenstrom.se/yellow/help/api-for-developers#yellow-toolbox) `toolbox->mail` (for example [Contact](https://github.com/annaesvensson/yellow-contact)).

Delivering errors are logged in `yellow-website.log`.

## How to use Mailer for creating complex emails

Besides supporting various transports for email delivering, Mailer allows other exensions to create complex emails (with HTML, or attachments, or iCal events). In order to do this, the following alternative interface of `toobox->mail` is supported.

`toolbox->mail($action, $headers, $message): bool`

`$headers` is an associative array. The following fields can be set:

`$headers["to"]` an array where values are the email addresses, keys (if strings) are the names  
`$headers["cc"]` 〃  
`$headers["bcc"]` 〃  
`$headers["reply-to"]` 〃  
`$headers["from"]` an array of one address; if not set, the webmaster email is used  
`$headers["subject"]` a string  
`$headers["custom"]` an array where keys are the names of the custom headers without `X-`, values are the contents of the headers  

`$message` is an associative array. The following fields can be set:

`$message["text"]["plain"]["heading"]`  
`$message["text"]["plain"]["body"]`  
`$message["text"]["plain"]["signature"]`  
`$message["text"]["style-sheet"]` name of style sheet in the `system/themes/` directory (without `.css`); you can also set the `default` style sheet of the site or a `void` style sheet.  

The body and optional heading and signature can use markdown formatting, without linebreaks inside a paragraph. If a style sheet is set, HTML content is automatically generated, but each part can be overriden with the following fields:

`$message["text"]["html"]["heading"]`  
`$message["text"]["html"]["body"]`  
`$message["text"]["html"]["signature"]`  

Attachments can be added wih the following field:

`$message["attachments"]` an array of names of files located in the directory `media/attachments/`  

An iCalendar part can be added with the following fields:

`$message["ical"]["time"]` an array of two values (start and end) in format `YYYY-MM-DD HH:MM`  
`$message["ical"]["location"]`  
`$message["ical"]["geo"]` latitude and longitude in decimal format, comma-separated, e.g. `37.386013,-122.082932`  
`$message["ical"]["summary"]`  
`$message["ical"]["description"]`  

Creation errors are logged in `yellow-website.log`.

## Examples

Sending a simple email from an extension:

```
$headers = [
    "From" => "Lucy White <lucy@example.net>",
    "To" => "john@example.org, Mary Penn <marypenn@example.com>",
    "Subject" => "My first email",
];
$message = "Yellow is the best content management system in the world!";

$status = $this->yellow->toolbox->mail("message", $headers, $message);
```

Sending a complex email from an extension:

```
$headers = [
    "From" => [ "Lucy White" => "lucy@example.net" ],
    "To" => [ "john@example.org", "Mary Penn" => "marypenn@example.com" ],
    "Subject" => "My first email",
];
$message["text"]["plain"]["body"] = "**Yellow** is the best content management system in the world!";
$message["text"]["style-sheet"] = "void"
$message["attachments"] = [ "yellow.pdf" ];

$status = $this->yellow->toolbox->mail("message", $headers, $message);
```

In `To`, `Cc`, `Bcc`, `Reply-To`, and `From` fields, either format of headers (associative array of strings or associative array of arrays) can actually be used for both simple and complex emails.

## Settings

The following settings can be configured in file `system/extensions/yellow-system.ini`:

`MailerSender` = address of envelope sender  
`MailerTransport` = how to deliver the email, `sendmail`, `qmail`, or `smtp`  
`MailerSendmailPath` = path of sendmail or qmail  
`MailerSmtpServer` = address of the SMTP server (e.g. `smtp.server.com`); a non-standard port can be specified (e.g. `smtp.server.com:2525`)  
`MailerSmtpSecurity` = protocol for secure email transport, `ssl`, `starttls`, or `none`; `ssl` is [always to be preferred](https://nostarttls.secvuln.info/) to `starttls`  
`MailerSmtpUsername` = SMTP username  
`MailerSmtpPassword` = SMTP password  
`MailerAttachmentDirectory` = directory for attachments  
`MailerAttachmentsMaxSize` = maximum total size of the attachments of an email  

The address in `MailerSender` receives non-delivery reports. For emails to be successfully delivered, this address should be in the domain of the SMTP server, or from which sendmail is run. For security reasons it must never be assigned a user-supplied value.

## Developer

Giovanni Salmeri. [Get help](https://datenstrom.se/yellow/help/).
