<?php

class Controller_Home extends \Controller
{

    public function action_index()
    {
        $aProjects = \Model\QmailProject::getProjects();
        $iProjectId = \Input::get('PROJECT_ID', 0);
        $aData = array(
            'oMails' => \Model\QmailMail::getMails($iProjectId),
            'aProjects' => $aProjects,
            'sSelectProject' => \Service\Utils::createSelect(array(0 => '-- None --') + $aProjects, array('name' => 'PROJECT_ID', 'onchange' => 'this.form.submit()', 'class' => 'form-control'), $iProjectId)
        );
        return Response::forge(\Service\View::forge('home', $aData));
    }

}
