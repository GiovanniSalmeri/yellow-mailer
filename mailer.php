<?php
// Mailer extension, https://github.com/GiovanniSalmeri/yellow-mailer

class YellowMailer {
    const VERSION = "0.9.1";
    public $yellow;         //access to API
    private $smtpSocket;

    // Handle initialisation
    public function onLoad($yellow) {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault("mailerSender", "postmaster@".$this->yellow->toolbox->getServer("SERVER_NAME"));
        $this->yellow->system->setDefault("mailerTransport", "sendmail"); // sendmail / qmail / smtp
        $this->yellow->system->setDefault("mailerSendmailPath", "/usr/sbin/sendmail");
        $this->yellow->system->setDefault("mailerSmtpServer", "");
        $this->yellow->system->setDefault("mailerSmtpSecurity", "ssl"); // ssl / starttls / none
        $this->yellow->system->setDefault("mailerSmtpUsername", "");
        $this->yellow->system->setDefault("mailerSmtpPassword", "");
        $this->yellow->system->setDefault("mailerAttachmentDirectory", "media/attachments/");
        $this->yellow->system->setDefault("mailerAttachmentsMaxSize", "20000000");
        $this->yellow->language->setDefaults(array(
            "Language: en",
            "MailerMissingField: Missing required field",
            "MailerBadType: Bad variable type",
            "MailerInvalidAddress: Invalid email address",
            "MailerInvalidTime: Invalid date or time",
            "MailerInvalidGeo: Invalid geographic location",
            "MailerMissingTheme: Missing theme file",
            "MailerMissingAttachment: Missing attachment",
            "MailerTooBigAttachments: Attachments too big",
            "MailerUnknownFields: Unknown fields",
            "MailerCannotOpenSendmail: Cannot open sendmail",
            "MailerCannotWriteToSendmail: Cannot write to sendmail",
            "MailerCannotCloseSendmail: Cannot close sendmail",
            "MailerCannotOpenSmtp: Cannot open SMTP server",
            "MailerSmtpUnknownSecurity: Unknown security protocol",
            "MailerSmtpCannotStartTls: Cannot start TLS",
            "MailerSmtpAuthenticationError: Bad username or password",
            "MailerSmtpSenderError: Sender rejected",
            "MailerSmtpRecipientError: Recipient rejected",
            "MailerSmtpError: Error in SMTP response",
            "MailerUnknownTransport: Unknown mail transport",
            "Language: it",
            "MailerMissingField: Campo richiesto mancante",
            "MailerBadType: Tipo di variabile errato",
            "MailerInvalidAddress: Indirizzo email non valido",
            "MailerInvalidTime: Data od ora non valida",
            "MailerInvalidGeo: Posizione geografica non valida",
            "MailerMissingTheme: File del tema mancante",
            "MailerMissingAttachment: Allegato mancante",
            "MailerTooBigAttachments: Allegati troppo grandi",
            "MailerUnknownFields: Campi sconosciuti",
            "MailerCannotOpenSendmail: Impossibile aprire sendmail",
            "MailerCannotWriteToSendmail: Impossibile scrivere su sendmail",
            "MailerCannotCloseSendmail: Impossibile chiudere sendmail",
            "MailerCannotOpenSmtp: Impossibile aprire il server SMTP",
            "MailerSmtpUnknownSecurity: Protocollo di sicurezza sconosciuto",
            "MailerSmtpCannotStartTls: Impossibile avviare TLS",
            "MailerSmtpAuthenticationError: Nome utente o password errati",
            "MailerSmtpSenderError: Mittente rifiutato",
            "MailerSmtpRecipientError: Destinatario rifiutato",
            "MailerSmtpError: Errore nella risposta SMTP",
            "MailerUnknownTransport: Trasporto di posta sconosciuto",
            "Language: sv",
            "MailerMissingField: Saknar obligatoriskt fält",
            "MailerBadType: Felaktig typ av variabel",
            "MailerInvalidAddress: Ogiltig e-postadress",
            "MailerInvalidTime: Ogiltigt datum eller tid",
            "MailerInvalidGeo: Ogiltig geografisk plats",
            "MailerMissingTheme: Saknar temafil",
            "MailerMissingAttachment: Saknar bilagor",
            "MailerTooBigAttachments: Bilagorna för stora",
            "MailerUnknownFields:  Okända fält",
            "MailerCannotOpenSendmail: Kan inte öppna sendmail",
            "MailerCannotWriteToSendmail: Kan inte skriva till sendmail",
            "MailerCannotCloseSendmail: Kan inte stänga sendmail",
            "MailerCannotOpenSmtp: Kan inte öppna SMTP-server",
            "MailerSmtpUnknownSecurity: Okänt säkerhetsprotokoll",
            "MailerSmtpCannotStartTls: Kan inte starta TLS",
            "MailerSmtpAuthenticationError: Dåligt användarnamn eller lösenord",
            "MailerSmtpSenderError: Avsändaren avvisad",
            "MailerSmtpRecipientError: Mottagaren avvisad",
            "MailerSmtpError: Fel i SMTP-svar",
            "MailerUnknownTransport: Okänd posttransport",
            "Language: fr",
            "MailerMissingField: Champ obligatoire manquant",
            "MailerBadType: Mauvais type de variable",
            "MailerInvalidAddress: Adresse électronique non valide",
            "MailerInvalidTime: Date ou heure non valide",
            "MailerInvalidGeo: Emplacement géographique non valide",
            "MailerMissingTheme: Fichier de thème manquant",
            "MailerMissingAttachment: Pièce jointe manquante",
            "MailerTooBigAttachments: Pièces jointes trop grandes",
            "MailerUnknownFields: Champs inconnus",
            "MailerCannotOpenSendmail: Impossible d'ouvrir sendmail",
            "MailerCannotWriteToSendmail: Impossible d'écrire à sendmail",
            "MailerCannotCloseSendmail: Impossible de fermer sendmail",
            "MailerCannotOpenSmtp: Impossible d'ouvrir le serveur SMTP",
            "MailerSmtpUnknownSecurity: Protocole de sécurité inconnu",
            "MailerSmtpCannotStartTls: Impossible de démarrer TLS",
            "MailerSmtpAuthenticationError: Mauvais nom d'utilisateur ou mot de passe",
            "MailerSmtpSenderError: Expéditeur rejeté",
            "MailerSmtpRecipientError: Destinataire rejeté",
            "MailerSmtpError: Erreur dans la réponse SMTP",
            "MailerUnknownTransport: Transport de courrier inconnu",
            "Language: de",
            "MailerMissingField: Fehlendes Pflichtfeld",
            "MailerBadType: Schlechter Variablentyp",
            "MailerInvalidAddress: Ungültige E-Mail Adresse",
            "MailerInvalidTime: Ungültiges Datum oder ungültige Uhrzeit",
            "MailerInvalidGeo: Ungültiger geografischer Standort",
            "MailerMissingTheme: Fehlende Theme-Datei",
            "MailerMissingAttachment: Fehlender Anhang",
            "MailerTooBigAttachments: Anhänge zu groß",
            "MailerUnbekannteFelder: Unbekannte Felder",
            "MailerCannotOpenSendmail: Kann sendmail nicht öffnen",
            "MailerCannotWriteToSendmail: Kann nicht in sendmail schreiben",
            "MailerCannotCloseSendmail: Kann sendmail nicht schließen",
            "MailerCannotOpenSmtp: SMTP-Server kann nicht geöffnet werden",
            "MailerSmtpUnknownSecurity: Unbekanntes Sicherheitsprotokoll",
            "MailerSmtpCannotStartTls: Kann TLS nicht starten",
            "MailerSmtpAuthenticationError: Falscher Benutzername oder Passwort",
            "MailerSmtpSenderFehler: Absender abgelehnt",
            "MailerSmtpRecipientError: Empfänger abgelehnt",
            "MailerSmtpFehler: Fehler in SMTP-Antwort",
            "MailerUnknownTransport: Unbekannter Mail-Transport",
            "Language: es",
            "MailerMissingField: Falta un campo obligatorio",
            "MailerBadType: Tipo de variable incorrecto",
            "MailerInvalidAddress: Dirección de correo electrónico no válida",
            "MailerInvalidTime: Fecha u hora no válida",
            "MailerInvalidGeo: Ubicación geográfica no válida",
            "MailerMissingTheme: Falta un archivo de tema",
            "MailerMissingAttachment: Falta un archivo adjunto",
            "MailerTooBigAttachments: Archivos adjuntos demasiado grandes",
            "MailerUnknownFields: Campos desconocidos",
            "MailerCannotOpenSendmail: No se puede abrir sendmail",
            "MailerCannotWriteToSendmail: No se puede escribir en sendmail",
            "MailerCannotCloseSendmail: No se puede cerrar sendmail",
            "MailerCannotOpenSmtp: No se puede abrir el servidor SMTP",
            "MailerSmtpUnknownSecurity: Protocolo de seguridad desconocido",
            "MailerSmtpCannotStartTls: No se puede iniciar TLS",
            "MailerSmtpAuthenticationError: Nombre de usuario o contraseña incorrectos",
            "MailerSmtpSenderError: Remitente rechazado",
            "MailerSmtpRecipientError: Destinatario rechazado",
            "MailerSmtpError: Error en la respuesta SMTP",
            "MailerUnknownTransport: Transporte de correo desconocido",
            "Language: pt",
            "MailerMissingField: Campo obrigatório em falta",
            "MailerBadType: Mau tipo de variável",
            "MailerInvalidAddress: Endereço de e-mail inválido",
            "MailerInvalidTime: Data ou hora inválida",
            "MailerInvalidGeo: Localização geográfica inválida",
            "MailerMissingTheme: Arquivo de tema em falta",
            "MailerMissingAttachment: Anexo ausente",
            "MailerTooBigAttachments: Anexos demasiado grandes",
            "MailerUnknownFields: Campos Desconhecidos",
            "MailerCannotOpenSendmail: Não é possível abrir sendmail",
            "MailerCannotWriteToSendmail: Não é possível escrever para sendmail",
            "MailerCannotCloseSendmail: Não é possível fechar sendmail",
            "MailerCannotOpenSmtp: Não é possível abrir o servidor SMTP",
            "MailerSmtpUnknownSecurity: Protocolo de segurança desconhecido",
            "MailerSmtpCannotStartTls: Não é possível iniciar o TLS",
            "MailerSmtpAuthenticationError: Mau nome de usuário ou senha",
            "MailerSmtpSenderError: Remetente rejeitado",
            "MailerSmtpRecipientError: Recipiente rejeitado",
            "MailerSmtpError: Erro na resposta SMTP",
            "MailerUnknownTransport: Transporte de correio desconhecido",
            "Language: pl",
            "MailerMissingField: Wypełnij wymagane pola",
            "MailerBadType: Niewłaściwy typ zmiennej",
            "MailerInvalidAddress: Niepoprawny adres email",
            "MailerInvalidTime: Nieprawidłowa data lub godzina",
            "MailerInvalidGeo: Nieprawidłowa lokalizacja geograficzna",
            "MailerMissingTheme: Brak pliku motywu",
            "MailerMissingAttachment: Brak załącznika",
            "MailerTooBigAttachments: Zbyt duże załączniki",
            "MailerUnknownFields: Nieznane pola",
            "MailerCannotOpenSendmail: Nie można otworzyć programu sendmail",
            "MailerCannotWriteToSendmail: Brak możliwości zapisu do sendmaila",
            "MailerCannotCloseSendmail: Nie można zamknąć programu sendmail",
            "MailerCannotOpenSmtp: Nie można otworzyć serwera SMTP",
            "MailerSmtpUnknownSecurity: Nieznany protokół zabezpieczeń",
            "MailerSmtpCannotStartTls: Nie można uruchomić TLS",
            "MailerSmtpAuthenticationError: Nieprawidłowa nazwa użytkownika lub hasło",
            "MailerSmtpSenderError: Nadawca odrzucony",
            "MailerSmtpRecipientError: Odbiorca odrzucony",
            "MailerSmtpError: Błąd w odpowiedzi protokołu SMTP",
            "MailerUnknownTransport: Nieznany transport poczty",
        ));
    }

    // Handle mail sending
    public function onMail($action, $headers, $message) {
        $mail = [];
        $simple = false;
        if (is_array($headers) && is_string($message)) {
            $mail["headers"] = $this->normaliseHeaders($headers);
            $mail["message"]["text"]["plain"]["body"] = $message;
            $simple = true;
        } elseif (is_array($headers) && is_array($message)) {
            $mail["headers"] = $this->normaliseHeaders($headers);
            $mail["message"] = $message;
	} elseif (is_array($headers) && $message===true) { // TODO: remove later, for backwards compatibility
            $mail = $this->convertOldFormat($headers);
        } else {
            return 500;
        }
        list($success, $errors) = $this->send($mail, false, $simple);
        if (!$success) $this->yellow->toolbox->log("error", "Mailer: ".implode(", ", $errors));
        return $success ? 200 : 500;

        // TODO: is an error page better?
        // if (!$success) { 
            // $this->yellow->page->error(500, implode(", ", $errors));
        // } else {
            // return 200;
        // }
    }

    // Close SMTP socket on shutdown
    public function onShutdown() {
        if (is_resource($this->smtpSocket)) {
            $this->smtpCommand("QUIT"); // 221
            @fclose($this->smtpSocket);
        }
    }

    // TODO: remove later, for backwards compatibility
    private function convertOldFormat($oldMail) {
        $mail = [];
        if (isset($oldMail["headers"])) $mail["headers"] = $oldMail["headers"];
        if (isset($oldMail["text"])) $mail["message"]["text"] = $oldMail["text"];
        if (isset($oldMail["attachments"])) $mail["message"]["attachments"] = $oldMail["attachments"];
        if (isset($oldMail["ical"])) $mail["message"]["ical"] = $oldMail["ical"];
        return $mail;
    }

    // Normalise headers in format expected by make
    private function normaliseHeaders($input) {
        $headers = [];
        foreach ($input as $key=>$value) {
            $key = strtolower($key);
            if (in_array($key, [ "from", "to", "cc", "bcc", "reply-to" ])) {
                $headers[$key] = is_string($value) ? $this->parseAddresses($value) : $value;
            } elseif (in_array($key, [ "subject", "content-type", "content-transfer-encoding", ])) {
                $headers[$key] = $value;
            } elseif (substr($key, 0, 2)=="x-") {
                $headers["custom"][substr($key, 2)] = $value;
            }
        }
        return $headers;
    }

    // Parses an address string as defined in RFC2822
    private function parseAddresses($text) {
        $state = [ "normal"=>0,  "quoted"=>1, "delimited"=>2, "escaped"=>3 ];
        $currentState = $state["normal"];
        $display = $address = "";
        $list = [];
        $text .= ",";
        for ($i=0; $i<strlen($text); $i++) {
            if ($currentState==$state["normal"]) {
                if ($text[$i]=="\"") {
                    $currentState = $state["quoted"];
                } elseif ($text[$i]=="<") {
                    $currentState = $state["delimited"];
                } elseif ($text[$i]==":") {
                    $display = "";
                } elseif ($text[$i]=="," || $text[$i]==";") {
                    $address = trim($address); $display = trim($display);
                    if ($address=="") {
                        $address = $display; $display = "";
                    }
                    if ($address!=="") {
                        if ($display=="") {
                            $list[] = $address;
                        } else {
                            $n = 0; while (isset($list[$unique = $display.($n ? "[$n]" : "")])) $n++;
                            $list[$unique] = $address;
                        }
                        $display = $address = "";
                    }
                }
                else {
                    $display .= $text[$i];
                }
            } elseif ($currentState==$state["quoted"]) {
                if ($text[$i]=="\"") {
                    $currentState = $state["normal"];
                } elseif ($text[$i]=="\\") {
                    $currentState = $state["escaped"];
                } else {
                    $display .= $text[$i];
                }
            } elseif ($currentState==$state["delimited"]) {
                if ($text[$i]==">") {
                    $currentState = $state["normal"];
                } else {
                    $address .= $text[$i];
                }
            } elseif ($currentState==$state["escaped"]) {
                $currentState = $state["quoted"];
                $display .= $text[$i];
            }
        }
        return $list;
    }

    // Send email (after sanitising, validating and building)
    public function send(&$mail, $dontValidate = false, $simple = false) {
        $this->sanitise($mail);
        if (!$dontValidate) {
            list($success, $errors) = $this->validate($mail, false);
            if (!$success) return [ false, $errors ];
        }
        $completeMail = $this->make($mail, false, $simple);
        $mailerTransport = strtolower($this->yellow->system->get("mailerTransport"));
        if ($mailerTransport=="sendmail" || $mailerTransport=="qmail") {
            return $this->sendmailSend($completeMail);
        } elseif ($mailerTransport=="smtp") {
            return $this->smtpSend($completeMail, $mail);
        } else {
            return [ false, [$this->yellow->language->getText("mailerUnknownTransport")] ];
        }
    }

    // Validate array $mail for missing, bad or unknown fields
    public function validate($mail, $sanitise = true) {
        if ($sanitise) $this->sanitise($mail);
        $checks = [
            [ [ "headers", "from" ], false, "array", "email" ],
            [ [ "headers", "to" ], true, "array", "email" ],
            [ [ "headers", "cc" ], false, "array", "email" ],
            [ [ "headers", "bcc" ], false, "array", "email" ],
            [ [ "headers", "reply-to" ], false, "array", "email" ],
            [ [ "headers", "subject" ], true, "string", null ],
            [ [ "headers", "content-type" ], false, "string", null ],
            [ [ "headers", "content-transfer-encoding" ], false, "string", null ],
            [ [ "headers", "custom" ], false, "array", "string" ],
            [ [ "message", "text", "plain", "heading" ], false, "string", null ],
            [ [ "message", "text", "plain", "body" ], true, "string", null ],
            [ [ "message", "text", "plain", "signature" ], false, "string", null ],
            [ [ "message", "text", "style-sheet" ], false, "theme", null ],
            [ [ "message", "text", "html", "heading" ], false, "string", null ],
            [ [ "message", "text", "html", "body" ], false, "string", null ],
            [ [ "message", "text", "html", "signature" ], false, "string", null ],
            [ [ "message", "attachments" ], false, "array", "file" ],
            [ [ "message", "ical", "time", "0" ], isset($mail["ical"]), "time", null ],
            [ [ "message", "ical", "time", "1" ], isset($mail["ical"]), "time", null ],
            [ [ "message", "ical", "location" ], false, "string", null ],
            [ [ "message", "ical", "geo" ], false, "geo", null ],
            [ [ "message", "ical", "summary" ], isset($mail["ical"]), "string", null ],
            [ [ "message", "ical", "description" ], false, "string", null ],
        ];

        $errors = [];
        $attachmentsSize = 0;
        foreach ($checks as [ $keys, $mandatory, $type, $subType ]) {
            $errorList = [];
            $fieldExists = true;
            $mailField = $mail;
            foreach($keys as $key) {
                if (isset($mailField[$key])) { $mailField = $mailField[$key]; }
                else { $fieldExists = false; break; }
            }
            if (!$fieldExists && $mandatory) {
                $errorList[] = $this->yellow->language->getText("mailerMissingField");
            } elseif ($fieldExists) {
                $check = $this->errorType($mailField, $type, $attachmentsSize);
                if ($check) {
                    $errorList[] = $check;
                } elseif ($type=="array") {
                    foreach ($mailField as $item) {
                        $check = $this->errorType($item, $subType, $attachmentsSize);
                        if ($check) $errorList[] = $check;
                    }
                }
            }
            if ($errorList) $errors[] = "[\"".implode("\"][\"", $keys)."\"]: ".implode(", ", $errorList);
            // Unset the fields checked so as to leave at the end only the unknown ones
            if (count($keys)==1) unset($mail[$keys[0]]);
            elseif (count($keys)==2) unset($mail[$keys[0]][$keys[1]]);
            elseif (count($keys)==3) unset($mail[$keys[0]][$keys[1]][$keys[2]]);
            elseif (count($keys)==4) unset($mail[$keys[0]][$keys[1]][$keys[2]][$keys[3]]);
        }
        if ($attachmentsSize > $this->yellow->system->get("mailerAttachmentsMaxSize")) $errors[] = $this->yellow->language->getText("mailerTooBigAttachments");
        $mail = $this->array_clean($mail);
        if ($mail) $errors[] = $this->yellow->language->getText("mailerUnknownFields").": ".preg_replace('/\s+/', " ", var_export($mail, true));
        return [ !$errors, $errors ];
    }

    // Check for errors in a single field
    private function errorType($var, $type, &$attachmentsSize) {
        if ($type=="string") {
            return is_string($var) ? false : $this->yellow->language->getText("mailerBadType");
        } elseif ($type=="array") {
            return is_array($var) ? false : $this->yellow->language->getText("mailerBadType");
        } elseif ($type=="email") {
            return filter_var($var, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE) ? false : $this->yellow->language->getText("mailerInvalidAddress");
        } elseif ($type=="geo") {
            return @preg_match('/^\s*([+-]?\d+\.\d+)\s*,\s*([+-]?\d+\.\d+)\s*$/', $var, $matches) && $matches[1] >= -90 && $matches[1] <= 90 && $matches[2] >= -180 && $matches[2] <= 180 ? false : $this->yellow->language->getText("mailerInvalidGeo");
        } elseif ($type=="time") {
            return date_create_from_format("Y-m-d H:i", $var) ? false : $this->yellow->language->getText("mailerInvalidTime");
        } elseif ($type=="theme") {
            if ($var!=="void" && $var!=="default") {
                $themeDirectory = $this->yellow->system->get("coreThemeDirectory");
                $fileNameTheme = $themeDirectory.$this->yellow->lookup->normaliseName($var).".css";
                return @is_file($fileNameTheme) ? false : $this->yellow->language->getText("mailerMissingTheme");
            } else {
                return false;
            }
        } elseif ($type=="file") {
            $path = $this->yellow->system->get("mailerAttachmentDirectory").$var;
            if (@is_file($path)) {
                $attachmentsSize += filesize($path);
                return false;
            } else {
                return $this->yellow->language->getText("mailerMissingAttachment")." ".@(string)$var;
            }
            return @is_file($var) ? false : $mailerBadType;
        }
    }

    // Unset empty arrays recursively
    private function array_clean($array) {
        foreach ($array as $key=>&$value) {
            if (is_array($value)) {
                $value = $this->array_clean($value);
                if (!$value) unset($array[$key]);
            }
        }
        unset($value);
        return $array;
    }

    // Sanitise values of $mail array, translate in punycode international addresses
    private function sanitise(&$mail) {
        if (!isset($mail["message"])) $mail = $this->convertOldFormat($mail); // TODO: remove later, for backwards compatibility
        if (isset($mail["headers"]) && is_array($mail["headers"])) array_walk_recursive($mail["headers"], function(&$value) {
            $value = filter_var(trim($value), FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
        });
        if (isset($mail["message"]["text"]) && is_array($mail["message"]["text"])) array_walk_recursive($mail["message"]["text"], function(&$value) {
            $value = str_replace([ "\r", "\n" ], [ "", "\r\n" ], rtrim($value)); // http://pobox.com/~djb/docs/smtplf.html
            $value = preg_replace('/[^[:print:]\t\r\n]/u', "", $value);
        });
        if (isset($mail["message"]["attachments"]) && is_array($mail["message"]["attachments"])) array_walk_recursive($mail["message"]["attachments"], function(&$value) {
            $value = filter_var(trim($value), FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW);
        });
        if (function_exists("idn_to_ascii")) { // international addresses
            foreach ([ "from", "to", "cc", "bcc", "reply-to" ] as $headerName) {
                if (@is_array($mail["headers"][$headerName])) {
                    foreach ($mail["headers"][$headerName] as $key=>$address) {
                        if (is_string($address)) {
                            list($local, $domain) = explode("@", $address, 2);
                            if ($this->highCharacters($domain)) $mail["headers"][$headerName][$key] = $local."@".idn_to_ascii($domain, 0, defined("INTL_IDNA_VARIANT_UTS46") ? INTL_IDNA_VARIANT_UTS46 : null);
                        }
                    }
                }
            }
        }
    }

    // Build mail as a single message/rfc822
    public function make(&$mail, $sanitise = true, $simple = false) {
        if ($sanitise) $this->sanitise($mail);
        $output = null;
        $output .= "Date: ".date(DATE_RFC2822)."\r\n";
        $output .= "MIME-Version: 1.0\r\n";
        if (!isset($mail["headers"]["from"])) $mail["headers"]["from"] = [$this->yellow->system->get("author")=>$this->yellow->system->get("email")];
        foreach ([ "from", "to", "cc", "bcc", "reply-to" ] as $headerName) {
            if (isset($mail["headers"][$headerName])) $output .= $this->encodeEmailHeader($headerName, $mail["headers"][$headerName])."\r\n";
        }
        if (isset($mail["headers"]["subject"])) $output .= $this->encodeHeader("subject", $mail["headers"]["subject"])."\r\n";
        if (isset($mail["headers"]["custom"])) {
            foreach ($mail["headers"]["custom"] as $key=>$header) {
                $output .= $this->encodeHeader("X-".$key, $header)."\r\n";
            }
        }
        if ($simple) {
            if (!isset($mail["headers"]["content-type"])) $mail["headers"]["content-type"] = "text/plain; charset=utf-8";
            foreach ([ "content-type", "content-transfer-encoding" ] as $headerName) {
                if (isset($mail["headers"][$headerName])) $output .= $this->encodeHeader($headerName, $mail["headers"][$headerName])."\r\n";
            }
            $output .= "\r\n";
            $output .= $mail["message"]["text"]["plain"]["body"]."\r\n";
        } else {
            if (isset($mail["message"]["ical"])) {
                $icalText = $this->makeIcal($mail["message"]["ical"], $mail["headers"]);
                // TODO: suppress one of the two when email clients finally agree on iCal detection
                $mail["message"]["ical-text"] = $icalText;
                $mail["message"]["attachments"]["ical-text"] = $icalText;
            }
            if (isset($mail["message"]["attachments"])) {
                $output .= $this->makeMixed($mail["message"]["text"], $mail["message"]["attachments"]);
            } else {
                $output .= $this->makeMailText($mail["message"]["text"]);
            }
        }
        return $output;
    }

    // Build iCalendar object RFC 5545
    private function makeIcal($ical, $headers) {
        $date_rfc5545 = "Ymd\THis\Z";
        $quote = function($string) { return '"'. str_replace([ '^', '"' ], [ "^^", "^'" ], $string).'"'; }; // RFC 6868
        $escape = function($string) { return addcslashes($string, '\,;'); };
        $start = gmdate($date_rfc5545, strtotime($ical["time"][0]));
        $end = gmdate($date_rfc5545, strtotime($ical["time"][1]));
        $fromEmail = current($headers["from"]);
        $fromName = key($headers["from"]);
        $lines = [];
        $lines[] = "BEGIN:VCALENDAR";
        $lines[] = "PRODID:-//github.com/GiovanniSalmeri//NONSGML YellowMailer ".$this::VERSION."//EN";
        $lines[] = "VERSION:2.0";
        $lines[] = "METHOD:REQUEST";
        $lines[] = "BEGIN:VEVENT";
        $lines[] ="UID:".md5($start."/".$ical["summary"]."@".$this->yellow->toolbox->getServer("SERVER_NAME"));
        $lines[] = "DTSTAMP:".gmdate($date_rfc5545);
        $lines[] = "DTSTART:".$start;
        $lines[] = "DTEND:".$end;
        $lines[] = "ORGANIZER".(is_string($fromName) ? ";CN=".$quote($fromName) : "").":mailto:$fromEmail";
        foreach ($headers["to"] as $key=>$toEmail) {
            $lines[] = "ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE".(is_string($key) ? ";CN=".$quote($key) : "").":mailto:$toEmail";
        }
        if (isset($ical["location"])) $lines[] = "LOCATION:".$escape($ical["location"]);
        if (isset($ical["geo"])) $lines[] ="GEO:".str_replace([ ",", " " ], [ ";", "" ], $ical["geo"]);
        $lines[] = "SUMMARY:".$escape($ical["summary"]);
        if (isset($ical["description"])) $lines[] = "DESCRIPTION:".$escape($ical["description"]);
        $lines[] = "END:VEVENT";
        $lines[] = "END:VCALENDAR";
        $output = null;
        foreach ($lines as $line) {
            while (strlen($line) > 1) {
                $fragment = mb_strcut($line, 0, 73);
                $line = " ".substr($line, strlen($fragment));
                $output .= $fragment."\r\n";
            }
        }
        return $output;
    }

    // Build message with attachments
    private function makeMixed($mailText, $attachments) {
        $boundary = "==mixed";
        $output = null;
        $output .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";
        $output .= "--$boundary\r\n";
        $output .= $this->makeMailText($mailText);
        foreach ($attachments as $key=>$file) {
            $output .= "--$boundary\r\n";
            $output .= $this->makeAttachment($file, $key==="ical-text");
        }
        $output .= "--$boundary--\r\n";
        return $output;
    }

    // Build body of message
    private function makeMailText($mailText) {
        if (isset($mailText["style-sheet"]) || isset($mailText["ical-text"])) {
            return $this->makeAlternative($mailText);
        } else {
            return $this->makePlaintext($mailText["plain"]);
        }
    }

    // Build MIME attachment
    private function makeAttachment($file, $isIcalText = false) {
        if ($isIcalText) {
            $content = $file;
            $name = "icalendar.ics";
            $mimeType = "application/ics";
            $size = strlen($content);
        } else {
            $path = $this->yellow->system->get("mailerAttachmentDirectory").$file;
            $content = file_get_contents($path);
            $name = basename($file);
            $mimeType = mime_content_type($path);
            $size = filesize($path);
        }
        list($encoding, $encodedContent) = $this->encodePart($content, !$isIcalText);
        $output = null;
        $output .= "Content-Type: $mimeType\r\n";
        $output .= "Content-Disposition: attachment;\r\n";
        $output .= $this->encodeParameter("filename", $name).";\r\n";
        $output .= " size=$size\r\n";
        $output .= "Content-Transfer-Encoding: $encoding\r\n\r\n";
        $output .= $encodedContent."\r\n";
        return $output;
    }

    // Build body of message with plaintext and HTML and/or iCalendar
    private function makeAlternative($mailText) {
        $boundary = "==alternative";
        $output = null;
        $output .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n\r\n";
        $output .= "--$boundary\r\n";
        $output .= $this->makePlaintext($mailText["plain"]);
        if (isset($mailText["style-sheet"])) {
            $output .= "--$boundary\r\n";
            $output .= $this->makeHtml($mailText);
        }
        if (isset($mailText["ical-text"])) {
            $output .= "--$boundary\r\n";
            $output .= $this->makePlaintext($mailText["ical-text"], true);
        }
        $output .= "--$boundary--\r\n";
        return $output;
    }

    // Build body of message with plaintext
    private function makePlaintext($plain, $isIcalText = false) {
        $plainText = null;
        if ($isIcalText) {
            $plainText = $plain;
            $mimeType = "text/calendar";
        } else {
            if (isset($plain["heading"])) $plainText .= $plain["heading"]."\r\n\r\n".str_repeat("=", 30)."\r\n\r\n";
            $plainText .= $plain["body"]."\r\n";
            if (isset($plain["signature"])) $plainText .= "\r\n-- \r\n".$plain["signature"]."\r\n";
            $mimeType = "text/plain";
        }
        list($encoding, $encodedText) = $this->encodePart($plainText);
        $output = null;
        $output .= "Content-Type: $mimeType; charset=UTF-8\r\n";
        $output .= "Content-Transfer-Encoding: $encoding\r\n\r\n";
        $output .= $encodedText."\r\n";
        return $output;
    }

    // Build body of message with HTML
    private function makeHtml($mailText) {
        $htmlText = null;
        $htmlText .= "<!DOCTYPE html>\r\n";
        $htmlText .= "<html>\r\n";
        $htmlText .= "<head>\r\n";
        $htmlText .= "<meta charset=\"utf-8\" />\r\n";
        $htmlText .= $this->getStyleSheet($mailText["style-sheet"]);
        $htmlText .= "</head>\r\n";
        $htmlText .= "<body>\r\n";
        foreach ([ "heading"=>"header", "body"=>"main", "signature"=>"footer" ] as $key=>$tagName) {
            if (isset($mailText["html"][$key])) {
                $htmlText .= "<$tagName>\r\n".$mailText["html"][$key]."</$tagName>\r\n";
            } elseif (isset($mailText["plain"][$key])) {
                $htmlText .= "<$tagName>\r\n".$this->getHtmlContent($mailText["plain"][$key])."</$tagName>\r\n";
            }
        }
        $htmlText .= "</body>\r\n";
        $htmlText .= "</html>\r\n";
        list($encoding, $encodedText) = $this->encodePart($htmlText);
        $output = null;
        $output .= "Content-Type: text/html; charset=UTF-8\r\n";
        $output .= "Content-Transfer-Encoding: $encoding\r\n\r\n";
        $output .= $encodedText."\r\n\r\n";
        return $output;
    }

    // Encode text-only header
    private function encodeHeader($title, $text) {
        $unencodedHeader = ucwords($title, "-").": ".trim($text);
        if (!$this->highCharacters($text) && !preg_match('/\S{78,}/', $text)) {
            return wordwrap($unencodedHeader, 78, "\r\n ");
        } else {
            $base64 = mb_encode_mimeheader($unencodedHeader, "UTF-8", "B");
            $quotedPrintable = substr_replace(mb_encode_mimeheader($unencodedHeader."à", "UTF-8", "Q"), "", -8, 6);
            return strlen($quotedPrintable) < strlen($base64) || !$this->highCharacters($text) ? $quotedPrintable : $base64;
        }
    }

    // Encode header with email addresses
    private function encodeEmailHeader($title, $addresses) {
        $output = ucwords($title, "-").":\r\n ";
        foreach($addresses as $name=>$address) {
            if (is_string($name)) {
                if (!$this->highCharacters($name) && !preg_match('/\S{76,}/', $name)) {
                    $output .= wordwrap('"'.addcslashes($name, '\\"').'"', 76, "\r\n ");
                } else {
                    $base64 = mb_encode_mimeheader($name, "UTF-8", "B");
                    $quotedPrintable = substr_replace(mb_encode_mimeheader($name."à", "UTF-8", "Q"), "", -8, 6);
                    $output .= strlen($quotedPrintable) < strlen($base64) || !$this->highCharacters($name) ? $quotedPrintable : $base64;
                }
            }
            $lastNewline = strrpos($output, "\n");
            $lastLineLength = $lastNewline===false ? strlen($output) : strlen($output) - $lastNewline -1;
            $output .= $lastLineLength > 0 && 77 - $lastLineLength < strlen($address) ? "\r\n  " : "";
            $output .= is_string($name) ? " <".$address.">" : $address;
            $output .= $name===key(array_slice($addresses, -1)) ? "" : ",\r\n "; // TODO: PHP>=7.3 array_key_last()
        }
        return $output;
    }

    // Encode parameter according to RFC 2231
    private function encodeParameter($name, $value) {
        $encoded = $this->highCharacters($value);
        $value = $encoded ? "UTF-8''". rawurlencode($value) : addcslashes(trim($value), '\\"');
        $partsMaxLength = 76 - strlen($name) - ($encoded ? 7 : 8);
        $sectionsNumber = preg_match_all("/.{0,$partsMaxLength}[^\%][^\%]|./", $value, $sections, PREG_PATTERN_ORDER);
        $headers = [];
        foreach ($sections[0] as $key=>$section) {
            $headers[] = " ". $name.($sectionsNumber > 1 ? "*".$key : "").($encoded ? "*" : "")."=".($encoded ? "" : '"').$section.($encoded ? "" : '"');
        }
        return implode(";\r\n", $headers);
    }

    // Encode with the shorter among base64 and quoted-printable
    private function encodePart($text, $forceBase64 = false) {
        $base64 = chunk_split(base64_encode($text));
        if ($forceBase64) {
            return [ "base64", $base64 ];
        } else {
            $quotedPrintable = quoted_printable_encode($text);
            if (strlen($base64) < strlen($quotedPrintable)) {
                return [ "base64", $base64 ];
            } else {
                return [ "quoted-printable", $quotedPrintable ];
            }
        }
    }

    // Tell whether text contains characters > 127
    private function highCharacters($text) {
        return preg_match('/[\x7F-\xFF]/', $text);
    }

    // Transform markdown text into HTML
    private function getHtmlContent($text) {
        $markdown = new YellowMarkdownParser($this->yellow, $this->yellow->page);
        $markdown->no_markup = true;
        $markdown->hard_wrap = true;
        return str_replace("\n", "\r\n", $markdown->transform($text));
    }

    // Return content of style sheet
    private function getStyleSheet($styleSheet) {
        if ($styleSheet==="void") {
            return "";
        } else {
            $themeDirectory = $this->yellow->system->get("coreThemeDirectory");
            if ($styleSheet==="default") {
                $fileNameTheme = $themeDirectory.$this->yellow->lookup->normaliseName($this->yellow->system->get("theme")).".css";
            } elseif (is_string($styleSheet)) {
                $fileNameTheme = $themeDirectory.$this->yellow->lookup->normaliseName($styleSheet).".css";
            }
            $output = null;
            $output .= "<style>\r\n";
            $output .= str_replace([ "\r", "\n" ], [ "", "\r\n" ], file_get_contents($fileNameTheme));
            $output .= "</style>\r\n";
            return $output;
        }
    }

    // Send mail with sendmail or qmail
    private function sendmailSend($completeMail) {
        $errors = [];
        $sendmailFormat = $this->yellow->system->get("mailerTransport")=="qmail" ? "%s -f%s" : "%s -oi -f%s -t";
        $sendmailCommand = sprintf($sendmailFormat, escapeshellcmd($this->yellow->system->get("mailerSendmailPath")), $this->yellow->system->get("mailerSender"));
        if (!($fileHandle = @popen($sendmailCommand, "w"))) {
            $errors[] = $this->yellow->language->getText("mailerCannotOpenSendmail");
        } else {
            $completeMail = str_replace("\r", "", $completeMail); // qmail requires, sendmail allows
            if (@fwrite($fileHandle, $completeMail)==false) {
                $errors[] = $this->yellow->language->getText("mailerCannotWriteToSendmail");
                pclose($fileHandle);
            } else {
                if (@pclose($fileHandle)!==0) $errors[] = $this->yellow->language->getText("mailerCannotCloseSendmail");
            }
        }
        return [ !$errors, $errors ];
    }

    // Send mail with SMTP
    private function smtpSend($completeMail, $mail) {
        $sender = $this->yellow->system->get("mailerSender");
        $server = $this->yellow->system->get("mailerSmtpServer");
        $security = strtolower($this->yellow->system->get("mailerSmtpSecurity"));
        $username = $this->yellow->system->get("mailerSmtpUsername");
        $password = $this->yellow->system->get("mailerSmtpPassword");
        $connectionTimeout = 30;
        $responseTimeout = 8; // RFC2821 4.5.3.2. requires 300!
        $hostname = $this->yellow->toolbox->getServer("SERVER_NAME"); // gethostname();

        $mailerCannotOpenSmtp = $this->yellow->language->getText("mailerCannotOpenSmtp");
        $mailerSmtpError = $this->yellow->language->getText("mailerSmtpError");
        $mailerSmtpCannotStartTls = $this->yellow->language->getText("mailerSmtpCannotStartTls");
        $mailerSmtpAuthenticationError = $this->yellow->language->getText("mailerSmtpAuthenticationError");
        $mailerSmtpSenderError = $this->yellow->language->getText("mailerSmtpSenderError");
        $mailerSmtpRecipientError = $this->yellow->language->getText("mailerSmtpRecipientError");
        $mailerSmtpUnknownSecurity = $this->yellow->language->getText("mailerSmtpUnknownSecurity");

        if (!is_resource($this->smtpSocket)) {
            $securityParameters = [
                "ssl" => [ "ssl://", 465 ],
                "starttls" => [ "tcp://", 587 ],
                "none" => [ "tcp://", 25 ],
            ];
            if (!isset($securityParameters[$security])) return [ false, [ $mailerSmtpUnknownSecurity ] ];
            list($protocol, $port) = $securityParameters[$security];
            if (preg_match('/^(.+):(\d+)$/', $server, $matches)) list($server, $port) = [ $matches[1], $matches[2] ];

            $this->smtpSocket = @fsockopen($protocol.$server, $port, $errorNumber, $errorMessage, $connectionTimeout);
            if (!$this->smtpSocket) return [ false, [ $mailerCannotOpenSmtp ] ];
            @stream_set_timeout($this->smtpSocket, $responseTimeout);

            if (!$this->smtpCommand(null)) return [ false, [ $mailerSmtpError ] ]; // 220
            if (!$this->smtpCommand("EHLO ".$hostname)) return [ false, [ $mailerSmtpError ] ]; // 250
            if ($security=="starttls") {
                if (!$this->smtpCommand("STARTTLS")) return [ false, [ $mailerSmtpError ] ]; // 220
                if (!@stream_socket_enable_crypto($this->smtpSocket, true,
                    // https://bugs.php.net/bug.php?id=80008
                    STREAM_CRYPTO_METHOD_TLSv1_0_CLIENT |
                    STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT |
                    STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT)) {
                    return [ false, [ $mailerSmtpCannotStartTls ] ];
                };
                if (!$this->smtpCommand("EHLO ".$hostname)) return [ false, [ $mailerSmtpError ] ]; // 250
            }
            if (!is_string_empty($username) && !is_string_empty($password)) {
                if (!$this->smtpCommand("AUTH PLAIN ".base64_encode("\0".$username."\0".$password))) return [ false, [ $mailerSmtpAuthenticationError ] ]; // 235
            }
        }
        if (!$this->smtpCommand("MAIL FROM: <".$sender.">")) return [ false, [ $mailerSmtpSenderError ] ]; // 250
        foreach ([ "to", "cc", "bcc" ] as $headerName) {
            if (isset($mail["headers"][$headerName])) {
                foreach ($mail["headers"][$headerName] as $recipient) {
                    if (!$this->smtpCommand("RCPT TO: <".$recipient.">")) return [ false, [ $mailerSmtpRecipientError ] ]; // 250, 251 or 252
                }
            }
        }

        if (!$this->smtpCommand("DATA")) return [ false, [ $mailerSmtpError ] ]; // 354
        $completeMail = str_replace("\n.", "\n..", $completeMail); // RFC2821 4.5.2
        if (!$this->smtpCommand($completeMail.".")) return [ false, [ $mailerSmtpError ] ]; // 250
        return [ true, [] ];
    }

    // Issue SMTP command and check answer
    private function smtpCommand($command) {
        if ($command) @fputs($this->smtpSocket, $command."\r\n");
        while (($line = @fgets($this->smtpSocket, 512))!==false) {
            if (substr($line, 3, 1)==" ") {
                return (intval($line)<400); // anything 2## or 3## means success
            }
        }
    }
}
