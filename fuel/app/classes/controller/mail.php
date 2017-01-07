<?php

class Controller_Mail extends \Controller
{
    /**
     * Files we want to attach to the mail
     * @var array
     */
    private $aAttachedFiles = array();

    public function action_index($iMailId = null)
    {
        $oMail = $this->getMail($iMailId);
        $aTemplates = $this->_getTemplates();
        $aData = array(
            'oMail' => $oMail,
            'sSendUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/mail/send/' . $oMail->MAIL_ID,
            'aKpiDatas' => \Model\QmailKpi::getKpi($iMailId),
            'sSelectProject' => \Service\Utils::createSelect(\Model\QmailProject::getProjects(), array('name' => 'PROJECT_ID', 'class' => 'form-control'), $oMail->PROJECT_ID),
            'sTemplateSelect' => \Service\Utils::createSelect($aTemplates, array('name' => 'TEMPLATE', 'class' => 'form-control'), $oMail->TEMPLATE)
        );
        return Response::forge(\Service\View::forge('mail', $aData));
    }

    public function action_preview($iMailId)
    {
        $sMail = $this->generateMail($iMailId, false);
        if (empty($sMail)) {
            $sMail = 'Email is empty';
        }
        return Response::forge($sMail);
    }

    public function action_delete($iMailId)
    {
        \Model\QmailKpi::deleteAll($iMailId);
        \Model\QmailMail::deleteMail($iMailId);
        return \Response::redirect();
    }

    public function action_send($iMailId)
    {
        $iStart = microtime(true);

        $sMail = $this->generateMail($iMailId, true);
        if (!empty($sMail)) {
            $oMailKpi = $this->getMail($iMailId);
            // Override subject if given in url
            $oMail = \Email::forge();
            $oMail->to(explode(',',$oMailKpi->TO));
            $oMail->from($oMailKpi->FROM, $oMailKpi->FROM_NAME);
            $oMail->html_body($sMail);

            $oMail->subject(\Input::get('subject', $oMailKpi->SUBJECT));
            $this->attachFiles($oMail);
            try {
                if ($oMail->send()) {
                    $sLog = '[OK] Email has been sent to ' . $oMailKpi->TO;
                } else {
                    $sLog = 'Error! Cannot send email to ' . $oMailKpi->TO;
                }
            } catch (\Exception $e) {
                $sLog = $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
            }

            $oMailKpi->set(array(
                'GENERATION_DURATION' => round(microtime(true) - $iStart, 2),
                'TS_SEND' => date('Y-m-d H:i:s')
            ));
            $oMailKpi->save();
        } else {
            $sLog = 'Email content is empty! No mail sent';
        }

        return Response::forge($sLog);
    }

    /**
     * Get the list of available template files by parsing the view folder
     * @return array
     */
    private function _getTemplates()
    {
        $aFiles = \File::read_dir(APPPATH . '/views/emails', 1);
        $aFilesArray = array();
        foreach ($aFiles as $sFile) {
            $sFile = str_replace('.php', '', $sFile);
            $aFilesArray[$sFile] = \Inflector::humanize(\Inflector::underscore(str_replace('-', '_', $sFile)));
        }
        return $aFilesArray;
    }

    private function generateMail($iMailId, $bForMail)
    {
        $aKpiDatas = \Model\QmailKpi::getActiveKpi($iMailId);
        $sMail = '';
        foreach ($aKpiDatas as $oKpi) {
            $oKpi->JSON_TEST_PARAM = \Input::get('param', '');
            try {
                $sMail .= \Service\Kpi::getSection($oKpi, $bForMail);
            } catch (\Exception $e) {
                // do nothing
            }
            if ($bForMail && $oKpi->ATTACH_CSV && $oKpi->hasCsvFilePath()) {
                $this->aAttachedFiles[] = array(
                    'mimeType' => 'text/csv',
                    'name' => strtolower(str_replace(array(' ', '.', '/'), array('_', '_', '-'), $oKpi->NAME)) . '.csv',
                    'filePath' => $oKpi->getCsvFilePath()
                );
            }
        }
        if (!empty($sMail)) {
            $oMail = $this->getMail($iMailId);
            $aData = array(
                'bodyContent' => $sMail,
                'oMail' => $oMail,
                'bForMail' => $bForMail,
                'sSubject' => \Input::get('subject', $oMail->SUBJECT)
            );
            header('Content-Type:text/html; charset=UTF-8'); // hack jpgraph header
            return \View::forge('emails/' . $oMail->TEMPLATE, $aData);
        } else {
            return '';
        }
    }

    private function attachFiles($oMail)
    {
        foreach ($this->aAttachedFiles as $aFile) {
            // Rename file
            $sDirName = dirname($aFile['filePath']);
            $sFinaleFilePath = $sDirName . '/' . $aFile['name'];
            rename($aFile['filePath'], $sFinaleFilePath);

            $oMail->attach(
                $sFinaleFilePath,
                false,
                null,
                $aFile['mimeType']
            );
        }
        return $this;
    }

    private function getMail($iMailId = null)
    {
        $oMail = false;
        if ($iMailId) {
            $oMail = \Model\QmailMail::find_by_pk($iMailId);
        }
        if (!$oMail) {
            $oMail = \Model\QmailMail::forge(array(
                'MAIL_ID' => null,
                'FROM' => '',
                'FROM_NAME' => '',
                'TO' => '',
                'SUBJECT' => '',
                'TEMPLATE' => '',
                'PROJECT_ID' => null,
                'TS_UPDATE' => null,
            ));
        }
        return $oMail;
    }

    public function action_save($iMailId = null)
    {
        $oMail = $this->getMail($iMailId);
        $oMail->set(array(
            'FROM' => \Input::post('FROM'),
            'FROM_NAME' => \Input::post('FROM_NAME'),
            'TO' => \Input::post('TO'),
            'SUBJECT' => \Input::post('SUBJECT'),
            'TEMPLATE' => \Input::post('TEMPLATE'),
            'PROJECT_ID' => \Input::post('PROJECT_ID'),
            'TS_UPDATE' => date('Y-m-d H:i:s'),
        ));
        $oMail->save();
        return Response::redirect('/mail/index/' . $oMail->MAIL_ID);
    }

}
