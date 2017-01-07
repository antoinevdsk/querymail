<?php

namespace Service;

use Amenadiel\JpGraph\Graph\Graph;
use Amenadiel\JpGraph\Graph\PieGraph;
use Amenadiel\JpGraph\Plot\BarPlot;
use Amenadiel\JpGraph\Plot\GroupBarPlot;
use Amenadiel\JpGraph\Plot\LinePlot;
use Amenadiel\JpGraph\Plot\PiePlotC;

class Kpi
{

    public static function getSection(\Model\QmailKpi $oKpi, $bForMail = false)
    {
        // no kpi during excluded hours
        if ($oKpi->EXCLUDED_HOURS != null && $oKpi->EXCLUDED_HOURS != '') {
            $aExcludedHours = explode(',', $oKpi->EXCLUDED_HOURS);
            if (in_array(date('G'), $aExcludedHours)) {
                throw new \Exception('Cannot generate KPI because current hour is excluded', 100);
                return '';
            }
        }

        $sContent = '';
        $iStart = microtime(true);

        // get data
        $aParam = null;
        if (!empty($oKpi->JSON_TEST_PARAM)) {
            $aParam = json_decode($oKpi->JSON_TEST_PARAM, true);
        }

        $sError = '';
        try {
            $aQueryData = self::getData($oKpi->QUERY, $oKpi->GROUP, $oKpi->CONNEXION, $aParam);
        } catch (\Exception $e) {
            $sError .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-bottom: 10px;"><tr><td style="padding: 7px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #ffffff; font-weight: bold; background-color: #dd0000;">' . $e->getMessage() . '</td></tr></table>';
        }
        try {
            $aQueryDataCompare = self::getData($oKpi->QUERY_COMPARE, $oKpi->GROUP, $oKpi->CONNEXION, $aParam);
        } catch (\Exception $e) {
            $sError .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-bottom: 10px;"><tr><td style="padding: 7px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #ffffff; font-weight: bold; background-color: #dd0000;">' . $e->getMessage() . '</td></tr></table>';
        }

        if (!empty($sError)) {
            $sContent = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-bottom: 10px;"><tr><td style="padding: 7px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #333333; font-weight: bold; background-color: #ececeb;">' . $oKpi->NAME . '</td></tr></table>';
            return $sContent . $sError . '<hr />';
        }


        // get section depending on mode
        if (!empty($aQueryData)) {
            $sContent = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="padding-bottom: 10px;"><tr><td style="padding: 7px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; color: #333333; font-weight: bold; background-color: #ececeb;">' . $oKpi->NAME . '</td></tr></table>';
            if (!empty($oKpi->DESCRIPTION)) {
                $sContent .= '<p style="margin-top: 5px; font-size: 14px;">' . $oKpi->DESCRIPTION . '</p>';
            }
            if ($oKpi->DISPLAY_QUERIES == 1) {
                $sContent .= '<table width="100%" style="border: 1px solid #cccccc; border-collapse: collapse;"><tr><td width="40" align="center" style="padding: 5px; border-right: 1px solid #cccccc; background-color: #eeeeee;" align="top"><img src="/_assets/img/emails/database.png" width="20" height="19" border="0" align="top" alt="" /></td><td style="padding: 5px; font-size: 13px; font-family: monospace;">' . nl2br($oKpi->QUERY) . '</td></tr></table><br />';
            }
            if ($oKpi->isModeHtml()) {
                $sContent .= self::getHtml($oKpi, $aQueryData, $aQueryDataCompare);
            } elseif ($oKpi->isModeGraph()) {
                $sContent .= self::getGraph($oKpi, $aQueryData, $bForMail);
            } elseif ($oKpi->isModeGraphHtml()) {
                $sContent .= self::getGraph($oKpi, $aQueryData, $bForMail);
                $sContent .= self::getHtml($oKpi, $aQueryData, $aQueryDataCompare);
            } elseif ($oKpi->isModeHtmlGraph()) {
                $sContent .= self::getHtml($oKpi, $aQueryData, $aQueryDataCompare);
                $sContent .= self::getGraph($oKpi, $aQueryData, $bForMail);
            }
            if ($bForMail && $oKpi->ATTACH_CSV == 1) {
                self::attachCsvFile($oKpi, $aQueryData);
            }
            $iDuration = round(microtime(true) - $iStart, 3);
            $sContent .= '<p align="right" style="font-size: 9px;">Generated in ' . $iDuration . 's</p>';
            return $sContent . '<hr />';
        }
        return $sContent;
    }

    private static function attachCsvFile(\Model\QmailKpi $oKpi, $aQueryData)
    {
        $sFilePath = '/tmp/' . uniqid('qmail_attach');
        $rCsvFile = fopen($sFilePath, 'w');
        fputcsv($rCsvFile, array_keys($aQueryData[0]));
        foreach ($aQueryData as $aRow) {
            fputcsv($rCsvFile, $aRow);
        }
        $oKpi->setCsvFilePath($sFilePath);
    }

    private static function getHtml(\Model\QmailKpi $oKpi, $aQueryData, $aQueryDataCompare)
    {
        $aData = array(
            'oKpi' => $oKpi,
            'aQueryData' => $aQueryData,
            'aQueryDataCompare' => $aQueryDataCompare,
        );
        return \View::forge('kpitable', $aData);
    }

    private static function getGraph(\Model\QmailKpi $oKpi, $aQueryData, $bForMail = false)
    {
        if ($oKpi->isGraphModeBar()) {
            $img = self::graphBarPlot($oKpi, $aQueryData);
        } elseif ($oKpi->isGraphModePie()) {
            $img = self::graphPie($oKpi, $aQueryData);
        } elseif ($oKpi->isGraphModeLine()) {
            $img = self::graphLine($oKpi, $aQueryData);
        }
        if ($bForMail) {
            $sFileName = '/tmp/' . time() . '-' . $oKpi->KPI_ID . '.png';
            file_put_contents($sFileName, $img);
            return '<p align="center"><img src="' . $sFileName . '" /></p>';
        } else {
            return '<p align="center"><img src="data:image/png;base64,' . base64_encode($img) . '" /></p>';
        }
    }

    private static function graphPie(\Model\QmailKpi $oKpi, $aQueryData)
    {
        // Create the Pie Graph.
        $graph = new PieGraph(700, 300, 'auto');
        $graph->SetScale("textlin");
        //$graph->SetMargin(10,10,10,100);

        // Set A title for the plot
        $graph->title->Set($oKpi->NAME);

        // formatage des valeurs par index
        $aAllValues = array();
        $sKpiFieldName = '';
        foreach ($aQueryData as $aData) {
            $i = 0;
            foreach ($aData as $sField => $sVal) {
                if ($i == 0) {
                    $aAllValues[$i][] = $sVal . " - %.1f%%";
                } elseif ($i == 1) {
                    $sKpiFieldName = $sField;
                    $aAllValues[$i][] = $sVal;
                }
                $i++;
            }
        }

        // Create
        $p1 = new PiePlotC($aAllValues[1]); // les data doivent être dans la 2ème colonne
        $p1->SetSize(0.35);
        $graph->Add($p1);

        //$p1->value->SetColor('white');
        $p1->SetLabels($aAllValues[0]); // les labels doivent être dans le 1ère colonne
        $p1->SetLabelPos(1);
        $p1->SetLabelType(PIE_VALUE_PER);
        $p1->value->Show();

        // middle
        $p1->midtitle->Set($sKpiFieldName);

        $p1->SetColor('black');

        // Display the graph
        ob_start();
        $graph->Stroke();
        $img = ob_get_contents();
        ob_end_clean();
        return $img;
    }

    private static function graphBarPlot(\Model\QmailKpi $oKpi, $aQueryData)
    {
        // Create the graph. These two calls are always required
        $graph = new Graph(700, 300, 'auto');
        $graph->SetScale("textlin");
        //$graph->SetFrame(true);
        $graph->SetMargin(50, 10, 10, 10);

        //$theme_class=new \UniversalTheme;
        //$graph->SetTheme($theme_class);

        $graph->SetBox(false);
        $graph->ygrid->SetFill(false);

        // formatage des valeurs par index
        $aAllValues = array();
        foreach ($aQueryData as $aData) {
            $i = 0;
            foreach ($aData as $sField => $sVal) {
                if ($i == 0) {
                    $aAllValues[$i][] = $sVal;
                } else {
                    $aAllValues[$sField][] = $sVal;
                }
                $i++;
            }
        }

        // on prend comme axe X la valeur de chaque première colonne
        $graph->xaxis->SetTickLabels($aAllValues[0]);
        if (count($aAllValues[0]) > 5 && count($aAllValues[0]) < 25) {
            $graph->xaxis->SetTextTickInterval(3);
        } elseif (count($aAllValues[0]) >= 25) {
            $graph->xaxis->SetTextTickInterval(4);
        }
        unset($aAllValues[0]);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);


        $aPlots = array();
        $i = 0;
		$aColors = \Config::get('querymail.colors');
        foreach ($aAllValues as $sField => $aValues) {
            $oPlot = new BarPlot($aValues);
            $oPlot->SetColor("white");
            $oPlot->SetFillColor(isset($aColors[$i]) ? $aColors[$i] : '#CCCCCC');
            $oPlot->SetLegend($sField);
            $i++;

            $aPlots[] = $oPlot;
        }

        $gbplot = new GroupBarPlot($aPlots);
        $graph->Add($gbplot);

        $graph->title->Set($oKpi->NAME);
        $graph->legend->SetFrameWeight(1);

        // Display the graph
        ob_start();
        $graph->Stroke();
        $img = ob_get_contents();
        ob_end_clean();
        return $img;
    }

    private static function graphLine(\Model\QmailKpi $oKpi, $aQueryData)
    {
        // Create the graph. These two calls are always required
        $graph = new Graph(700, 300, 'auto');
        $graph->SetScale("textlin");
        //$graph->SetFrame(true);
        $graph->SetMargin(50, 10, 10, 10);

        //$theme_class=new \UniversalTheme;
        //$graph->SetTheme($theme_class);

        $graph->SetBox(false);
        $graph->ygrid->SetFill(false);

        // formatage des valeurs par index
        $aAllValues = array();
        foreach ($aQueryData as $aData) {
            $i = 0;
            foreach ($aData as $sField => $sVal) {
                if ($i == 0) {
                    $aAllValues[$i][] = $sVal;
                } else {
                    $aAllValues[$sField][] = $sVal;
                }
                $i++;
            }
        }

        // on prend comme axe X la valeur de chaque première colonne
        $graph->xaxis->SetTickLabels($aAllValues[0]);
        unset($aAllValues[0]);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);


        foreach ($aAllValues as $sField => $aValues) {
            $oPlot = new LinePlot($aValues);
            $oPlot->SetLegend($sField);
            $graph->Add($oPlot);

        }

        $graph->title->Set($oKpi->NAME);
        $graph->legend->SetFrameWeight(1);

        // Display the graph
        ob_start();
        $graph->Stroke();
        $img = ob_get_contents();
        ob_end_clean();
        return $img;
    }

    private static function getData($sQuery, $sGroup, $sConnexion, $aParam)
    {
        if (!empty($sQuery)) {
            // query
            if (is_array($aParam)) {
                $aParam = array_merge($aParam, \Config::get('querymail.queryvar'));
            } else {
                $aParam = \Config::get('querymail.queryvar');
            }
            $oQuery = \DB::query($sQuery)->parameters($aParam);
            $aQueryData = $oQuery->execute($sConnexion);
            $aFormatData = array();
            if (!empty($sGroup) && count($aQueryData) > 0) {
                foreach ($aQueryData as $aData) {
                    foreach ($aData as $sField => $mData) {
                        if ($sField != $sGroup) {
                            $aFormatData[$sField][$aData[$sGroup]] = $mData;
                        }
                    }
                }
                $aQueryData = $aFormatData;
            } else {
                $aQueryData = $aQueryData->as_array();
            }
            return $aQueryData;
        }
        return null;
    }

}
