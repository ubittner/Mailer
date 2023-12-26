<?php

/**
 * @project       Mailer/Mailer/
 * @file          module.php
 * @author        Ulrich Bittner
 * @copyright     2023 Ulrich Bittner
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 */

/** @noinspection PhpRedundantMethodOverrideInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection PhpUnused */

declare(strict_types=1);

include_once __DIR__ . '/helper/MA_autoload.php';

class Mailer extends IPSModule
{
    //Helper
    use MA_ConfigurationForm;
    use MA_Notification;

    //Constants
    private const LIBRARY_GUID = '{9B229E71-4D0F-E386-330F-1AC86B01BE18}';
    private const MODULE_GUID = '{C6CF3C5C-E97B-97AB-ADA2-E834976C6A92}';
    private const MODULE_NAME = 'Mailer';
    private const MODULE_PREFIX = 'MA';
    private const MODULE_VERSION = '7.0-1, 08.09.2022';
    private const SMTP_MODULE_GUID = '{375EAF21-35EF-4BC4-83B3-C780FD8BD88A}';

    public function Create()
    {
        //Never delete this line!
        parent::Create();

        ########## Properties

        $this->RegisterPropertyString('Note', '');
        $this->RegisterPropertyBoolean('EnableActive', false);
        $this->RegisterPropertyInteger('SMTP', 0);
        $this->RegisterPropertyString('RecipientList', '[]');

        ########## Variables

        //Active
        $id = @$this->GetIDForIdent('Active');
        $this->RegisterVariableBoolean('Active', 'Aktiv', '~Switch', 10);
        $this->EnableAction('Active');
        if (!$id) {
            $this->SetValue('Active', true);
        }
    }

    public function ApplyChanges()
    {
        //Wait until IP-Symcon is started
        $this->RegisterMessage(0, IPS_KERNELSTARTED);

        //Never delete this line!
        parent::ApplyChanges();

        //Check runlevel
        if (IPS_GetKernelRunlevel() != KR_READY) {
            return;
        }

        //Delete all references
        foreach ($this->GetReferenceList() as $referenceID) {
            $this->UnregisterReference($referenceID);
        }

        //Register reference
        $id = $this->ReadPropertyInteger('SMTP');
        if ($id > 1 && @IPS_ObjectExists($id)) {
            $this->RegisterReference($id);
        }

        //WebFront options
        IPS_SetHidden($this->GetIDForIdent('Active'), !$this->ReadPropertyBoolean('EnableActive'));
    }

    public function Destroy()
    {
        //Never delete this line!
        parent::Destroy();
    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        $this->SendDebug('MessageSink', 'Message from SenderID ' . $SenderID . ' with Message ' . $Message . "\r\n Data: " . print_r($Data, true), 0);
        if ($Message == IPS_KERNELSTARTED) {
            $this->KernelReady();
        }
    }

    public function CreateSMTPInstance(): void
    {
        $id = @IPS_CreateInstance(self::SMTP_MODULE_GUID);
        if (is_int($id)) {
            IPS_SetName($id, 'E-Mail, Send (SMTP)');
            $infoText = 'Instanz mit der ID ' . $id . ' wurde erfolgreich erstellt!';
        } else {
            $infoText = 'Instanz konnte nicht erstellt werden!';
        }
        $this->UpdateFormField('InfoMessage', 'visible', true);
        $this->UpdateFormField('InfoMessageLabel', 'caption', $infoText);
    }

    public function UIShowMessage(string $Message): void
    {
        $this->UpdateFormField('InfoMessage', 'visible', true);
        $this->UpdateFormField('InfoMessageLabel', 'caption', $Message);
    }

    #################### Request Action

    public function RequestAction($Ident, $Value)
    {
        if ($Ident == 'Active') {
            $this->SetValue($Ident, $Value);
        }
    }

    #################### Private

    private function KernelReady(): void
    {
        $this->ApplyChanges();
    }

    private function CheckMaintenance(): bool
    {
        $result = false;
        if (!$this->GetValue('Active')) {
            $this->SendDebug(__FUNCTION__, 'Abbruch, die Instanz ist inaktiv!', 0);
            $result = true;
        }
        return $result;
    }
}
