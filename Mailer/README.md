# Mailer

Zur Verwendung dieses Moduls als Privatperson, Einrichter oder Integrator wenden Sie sich bitte zunächst an den Autor.

Für dieses Modul besteht kein Anspruch auf Fehlerfreiheit, Weiterentwicklung, sonstige Unterstützung oder Support.  
Bevor das Modul installiert wird, sollte unbedingt ein Backup von IP-Symcon durchgeführt werden.  
Der Entwickler haftet nicht für eventuell auftretende Datenverluste oder sonstige Schäden.  
Der Nutzer stimmt den o.a. Bedingungen, sowie den Lizenzbedingungen ausdrücklich zu.

### Inhaltsverzeichnis

1. [Modulbeschreibung](#1-modulbeschreibung)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Schaubild](#3-schaubild)
4. [Externe Aktion](#4-externe-aktion)
5. [PHP-Befehlsreferenz](#5-php-befehlsreferenz)
   1. [Nachricht an ***alle*** Empfänger](#51-nachricht-an-alle-empfänger-versenden)
   2. [Nachricht an ***einen*** Empfänger](#52-nachricht-an-einen-empfänger-versenden)

### 1. Modulbeschreibung

Dieses Modul versendet Nachrichten an einen oder mehrere Empfänger über eine SMTP Instanz.

### 2. Voraussetzungen

- IP-Symcon ab Version 6.1
- SMTP Instanz

### 3. Schaubild

```
                            +----------------+
Externe Aktion------------->| Mailer (Modul) |
                            |                |
                            | Empfänger 1    |
                            |                |
                            | Empfänger 2    |
                            |                |
                            | Empfänger n    |
                            +-------+--------+
                                    |
                                    |
                                    |
                                    |
                                    |
                                    v
                            +----------------+
                            |  SMTP (Modul)  |
                            +----------------+                       
```

### 4. Externe Aktion

Das Modul kann über eine externe Aktion gesteuert werden.  
Nachfolgendes Beispiel versendet eine Nachricht an alle aktivierten Empfänger.

> MA_SendMessage(12345, 'Betreff', 'Text');

### 5. PHP-Befehlsreferenz

#### 5.1 Nachricht an ***alle*** Empfänger versenden  

```
MA_SendMessage(integer INSTANCE_ID, string SUBJECT, string TEXT);
```

Der Befehl liefert keinen Rückgabewert.

| Parameter     | Beschreibung                  |
|---------------|-------------------------------|
| `INSTANCE_ID` | ID der Instanz                |
| `SUBJECT`     | Betreff der Nachricht         |
| `TEXT`        | Text der Nachricht            |

Beispiel:  
> MA_SendMessage(12345, 'Hinweis', 'Dies ist eine Nachricht');  

---

#### 5.2 Nachricht an ***einen*** Empfänger versenden

```
MA_SendMessageEx(integer INSTANCE_ID, string SUBJECT, string TEXT, string ADDRESS);
```

Der Befehl liefert keinen Rückgabewert.

| Parameter     | Beschreibung                  |
|---------------|-------------------------------|
| `INSTANCE_ID` | ID der Instanz                |
| `SUBJECT`     | Betreff der Nachricht         |
| `TEXT`        | Text der Nachricht            |
| `ADDRESS`     | E-Mail-Adresse des Empfängers |

Beispiel:  
> MA_SendMessageEx(12345, 'Hinweis', 'Dies ist eine Nachricht', 'max@mustermann.de');

---