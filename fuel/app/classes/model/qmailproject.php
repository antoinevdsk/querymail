<?php

namespace Model;

class QmailProject extends \Model_Crud
{
	
	protected static $_table_name = 'QMAIL_PROJECT';
	protected static $_primary_key = 'PROJECT_ID';
	protected static $_connection = 'querymail';
	
	public static function getProjects()
	{
		$aData = array();
		$aProjects = self::find_all();
		foreach ($aProjects as $aProject){
			$aData[$aProject['PROJECT_ID']] = $aProject['NAME'];
		}
		return $aData;
	}
	
}