<?php

class Controller_Kpi extends \Controller
{

    public function before()
    {
        parent::before();
        $this->cleanQuery('QUERY')->cleanQuery('QUERY_COMPARE');
    }

    public function action_index($iMailId, $iKpiId = null)
    {
        $oKpi = $this->_getKpi($iMailId, $iKpiId);
        $aConnexions = \Service\Utils::getAllConnexion();
        $aModes = array(
            MODE_HTML => 'Html',
            MODE_GRAPH => 'Graph',
            MODE_HTML_GRAPH => 'Html + Graph',
            MODE_GRAPH_HTML => 'Graph + Html',
        );
        $aGraphModes = array(
            GRAPH_MODE_BAR => 'Bar',
            GRAPH_MODE_PIE => 'Pie',
            GRAPH_MODE_LINE => 'Line',
        );

        $aExcludedHours = array();
        for ($i = 0; $i < 24; $i++) {
            $aExcludedHours[$i] = $i;
        }
        $aExcludedHoursSelected = false;
        if (!empty($oKpi->EXCLUDED_HOURS)) {
            $aExcludedHoursSelected = explode(',', $oKpi->EXCLUDED_HOURS);
        }

        $aData = array(
            'oKpi' => $oKpi,
            'sConnexionSelect' => \Service\Utils::createSelect($aConnexions, array('name' => 'CONNEXION', 'class' => 'form-control'), $oKpi->CONNEXION),
            'sModeSelect' => \Service\Utils::createSelect($aModes, array('name' => 'MODE', 'class' => 'form-control'), $oKpi->MODE),
            'sGraphModeSelect' => \Service\Utils::createSelect($aGraphModes, array('name' => 'GRAPH_MODE', 'class' => 'form-control'), $oKpi->GRAPH_MODE),
            'sExcludedHours' => \Service\Utils::createCheckbox($aExcludedHours, 'EXCLUDED_HOURS', $aExcludedHoursSelected),
            'queryvar' => \Config::get('querymail.queryvar')
        );
        return Response::forge(\Service\View::forge('kpi', $aData));
    }

    public function action_preview()
    {
        $sExcludedHours = \Input::post('EXCLUDED_HOURS', '');
        $sExcludedHours = $sExcludedHours != '' ? implode(',', $sExcludedHours) : '';
        $oKpi = \Model\QmailKpi::forge(array(
            'NAME' => \Input::post('NAME'),
            'QUERY' => \Input::post('QUERY'),
            'QUERY_COMPARE' => \Input::post('QUERY_COMPARE'),
            'CONNEXION' => \Input::post('CONNEXION'),
            'GROUP' => \Input::post('GROUP'),
            'IS_ACTIVE' => \Input::post('IS_ACTIVE'),
            'MODE' => \Input::post('MODE'),
            'GRAPH_MODE' => \Input::post('GRAPH_MODE'),
            'DISPLAY_QUERIES' => \Input::post('DISPLAY_QUERIES'),
            'ATTACH_CSV' => \Input::post('ATTACH_CSV'),
            'INVERT_COLOR' => \Input::post('INVERT_COLOR'),
            'FORMAT_INTEGER' => \Input::post('FORMAT_INTEGER'),
            'DIFF_PERCENT' => \Input::post('DIFF_PERCENT'),
            'JSON_TEST_PARAM' => \Input::post('JSON_TEST_PARAM'),
            'DESCRIPTION' => \Input::post('DESCRIPTION', ''),
            'EXCLUDED_HOURS' => $sExcludedHours,
        ));
        try {
            return \Service\Kpi::getSection($oKpi);
        } catch (\Exception $e) {
            if ($e->getCode() == 100) {
                return $e->getMessage();
            }
        }
    }

    private function _getKpi($iMailId, $iKpiId = null)
    {
        $oKpi = false;
        if ($iKpiId) {
            $oKpi = \Model\QmailKpi::find_one_by(array(
                'KPI_ID' => $iKpiId,
                'MAIL_ID' => $iMailId
            ));
        }
        if (!$oKpi) {
            $oKpi = \Model\QmailKpi::forge(array(
                'KPI_ID' => null,
                'MAIL_ID' => $iMailId,
                'NAME' => '',
                'QUERY' => '',
                'QUERY_COMPARE' => '',
                'CONNEXION' => '',
                'GROUP' => '',
                'PRIORITY' => 1,
                'IS_ACTIVE' => 1,
                'MODE' => MODE_HTML,
                'GRAPH_MODE' => GRAPH_MODE_BAR,
                'DISPLAY_QUERIES' => 0,
                'ATTACH_CSV' => 0,
                'INVERT_COLOR' => 0,
                'FORMAT_INTEGER' => 0,
                'DIFF_PERCENT' => 1,
                'TS_UPDATE' => null,
                'JSON_TEST_PARAM' => '',
                'EXCLUDED_HOURS' => '',
                'DESCRIPTION' => '',
            ));
        }
        return $oKpi;
    }

    public function action_delete($iMailId, $iKpiId)
    {
        $oKpi = $this->_getKpi($iMailId, $iKpiId);
        if ($oKpi) {
            $oKpi->delete();
        }
        return Response::redirect('/mail/index/' . $oKpi->MAIL_ID);
    }

    public function action_save($iMailId, $iKpiId = null)
    {
        $oKpi = $this->_getKpi($iMailId, $iKpiId);
        $sExcludedHours = \Input::post('EXCLUDED_HOURS', '');
        $sExcludedHours = $sExcludedHours != '' ? implode(',', $sExcludedHours) : '';
        $oKpi->set(array(
            'MAIL_ID' => $iMailId,
            'NAME' => \Input::post('NAME'),
            'QUERY' => \Input::post('QUERY'),
            'QUERY_COMPARE' => \Input::post('QUERY_COMPARE'),
            'CONNEXION' => \Input::post('CONNEXION'),
            'GROUP' => \Input::post('GROUP'),
            'PRIORITY' => \Input::post('PRIORITY'),
            'IS_ACTIVE' => \Input::post('IS_ACTIVE', 0),
            'MODE' => \Input::post('MODE'),
            'GRAPH_MODE' => \Input::post('GRAPH_MODE'),
            'DISPLAY_QUERIES' => \Input::post('DISPLAY_QUERIES', 0),
            'ATTACH_CSV' => \Input::post('ATTACH_CSV', 0),
            'INVERT_COLOR' => \Input::post('INVERT_COLOR', 0),
            'FORMAT_INTEGER' => \Input::post('FORMAT_INTEGER', 0),
            'DIFF_PERCENT' => \Input::post('DIFF_PERCENT', 0),
            'JSON_TEST_PARAM' => \Input::post('JSON_TEST_PARAM', 0),
            'DESCRIPTION' => \Input::post('DESCRIPTION', ''),
            'EXCLUDED_HOURS' => $sExcludedHours,
            'TS_UPDATE' => date('Y-m-d H:i:s'),
        ));
        $oKpi->save();
        return Response::redirect('/kpi/index/' . $oKpi->MAIL_ID . '/' . $oKpi->KPI_ID);
    }

    private function cleanQuery($sPostName)
    {
        $sQuery = \Input::post($sPostName);
        if (!empty($sQuery)) {
            $sQuery = trim($sQuery);
            if (substr($sQuery, -1) == ";") {
                $sQuery = substr($sQuery, 0, strlen($sQuery) - 1);
            }
            $_POST[$sPostName] = $sQuery;
        }
        return $this;
    }

}
