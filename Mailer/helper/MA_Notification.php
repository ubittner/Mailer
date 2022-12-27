<?php

/**
 * @project       Mailer/Mailer
 * @file          MA_Notification.php
 * @author        Ulrich Bittner
 * @copyright     2022 Ulrich Bittner
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 */

/** @noinspection PhpUnused */

declare(strict_types=1);

trait MA_Notification
{
    /**
     * Sends a message to all activated recipients.
     *
     * @param string $Subject
     * @param string $Text
     * @return void
     * @throws Exception
     */
    public function SendMessage(string $Subject, string $Text): void
    {
        $this->SendDebug(__FUNCTION__, 'wird ausgeführt', 0);
        $this->SendDebug(__FUNCTION__, 'Betreff: ' . $Subject, 0);
        $this->SendDebug(__FUNCTION__, 'Text: ' . $Text, 0);
        if ($this->CheckMaintenance()) {
            return;
        }
        $recipients = json_decode($this->ReadPropertyString('RecipientList'), true);
        foreach ($recipients as $recipient) {
            if ($recipient['Use']) {
                $address = $recipient['Address'];
                if (strlen($address) >= 6) {
                    $this->SendData($Subject, $Text, $recipient['Address']);
                } else {
                    $this->SendDebug(__FUNCTION__, 'Abbruch, E-Mail Adresse hat weniger als 6 Zeichen!', 0);
                }
            }
        }
    }

    /**
     * Sends a message to a specific address.
     *
     * @param string $Subject
     * @param string $Text
     * @param string $Address
     * @return void
     * @throws Exception
     */
    public function SendMessageEx(string $Subject, string $Text, string $Address): void
    {
        $this->SendDebug(__FUNCTION__, 'wird ausgeführt', 0);
        $this->SendDebug(__FUNCTION__, 'Betreff: ' . $Subject, 0);
        $this->SendDebug(__FUNCTION__, 'Text: ' . $Text, 0);
        $this->SendDebug(__FUNCTION__, 'Adresse: = ' . $Address, 0);
        if ($this->CheckMaintenance()) {
            return;
        }
        $this->SendData($Subject, $Text, $Address);
    }

    #################### Private

    /**
     * Sends the data to the SMTP instance.
     *
     * @param string $Subject
     * @param string $Text
     * @param string $Address
     * @return bool
     * @throws Exception
     */
    private function SendData(string $Subject, string $Text, string $Address): bool
    {
        $this->SendDebug(__FUNCTION__, 'wird ausgeführt', 0);
        $this->SendDebug(__FUNCTION__, 'Betreff: ' . $Subject, 0);
        $this->SendDebug(__FUNCTION__, 'Text: ' . $Text, 0);
        $this->SendDebug(__FUNCTION__, 'Adresse: ' . $Address, 0);
        if ($this->CheckMaintenance()) {
            return false;
        }
        $id = $this->ReadPropertyInteger('SMTP');
        if ($id == 0 || @!IPS_ObjectExists($id)) {
            $this->SendDebug(__FUNCTION__, 'Abbruch, keine SMTP Instanz ausgewählt!', 0);
            return false;
        }
        if (empty($Subject)) {
            $this->SendDebug(__FUNCTION__, 'Abbruch, kein Betreff angegeben!', 0);
            return false;
        }
        if (empty($Text)) {
            $this->SendDebug(__FUNCTION__, 'Abbruch, kein Text angegeben!', 0);
            return false;
        }
        if (empty($Address) || strlen($Address) < 6) {
            $this->SendDebug(__FUNCTION__, 'Abbruch, E-Mail Adresse hat weniger als 6 Zeichen!', 0);
            return false;
        }
        return @SMTP_SendMailEx($id, $Address, $Subject, $Text);
    }
}