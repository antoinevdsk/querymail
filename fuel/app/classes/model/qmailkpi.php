<?php

namespace Model;

class QmailKpi extends \Model_Crud
{

	protected static $_table_name = 'QMAIL_KPI';
	protected static $_primary_key = 'KPI_ID';
	protected static $_connection = 'querymail';

	protected $_sCsvFilePath;

	public static function getKpi($iMailId)
	{
		$sQuery = 'SELECT * FROM '.self::$_table_name.' WHERE MAIL_ID = :mailId ORDER BY PRIORITY, NAME';
		return \DB::query($sQuery)->param('mailId', $iMailId)->as_object('\Model\QmailKpi')->execute(self::$_connection);
	}

	public static function getActiveKpi($iMailId)
	{
		$sQuery = 'SELECT * FROM '.self::$_table_name.' WHERE MAIL_ID = :mailId AND IS_ACTIVE = 1 ORDER BY PRIORITY, NAME';
		return \DB::query($sQuery)->param('mailId', $iMailId)->as_object('\Model\QmailKpi')->execute(self::$_connection);
	}

	public static function deleteAll($iMailId)
	{
		$sQuery = 'DELETE FROM '.self::$_table_name.' WHERE MAIL_ID = :mailId';
		return \DB::query($sQuery)->param('mailId', $iMailId)->execute(self::$_connection);
	}

	public function isModeHtml(){
		return $this->MODE == MODE_HTML;
	}

	public function isModeGraph(){
		return $this->MODE == MODE_GRAPH;
	}

	public function isModeGraphHtml(){
		return $this->MODE == MODE_GRAPH_HTML;
	}

	public function isModeHtmlGraph(){
		return $this->MODE == MODE_HTML_GRAPH;
	}

	public function isGraphModeBar(){
		return $this->GRAPH_MODE == GRAPH_MODE_BAR;
	}

	public function isGraphModePie(){
		return $this->GRAPH_MODE == GRAPH_MODE_PIE;
	}

	public function isGraphModeLine(){
		return $this->GRAPH_MODE == GRAPH_MODE_LINE;
	}

	public function getCsvFilePath(){
		return $this->_sCsvFilePath;
	}

	public function setCsvFilePath($sCsvFilePath){
		$this->_sCsvFilePath = $sCsvFilePath;
		return $this;
	}

	public function hasCsvFilePath(){
		return isset($this->_sCsvFilePath);
	}

	public function getRealSubject()
	{

	}
}