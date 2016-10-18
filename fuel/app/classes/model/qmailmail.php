<?php

namespace Model;

class QmailMail extends \Model_Crud
{
	
	protected static $_table_name = 'QMAIL_MAIL';
	protected static $_primary_key = 'MAIL_ID';
	protected static $_connection = 'querymail';
	
	public static function getMails($iProjectId)
	{
		$sQuery = 'SELECT m.*, IFNULL(k.NB_KPI, 0) NB_KPI
					FROM QMAIL_MAIL m
					LEFT JOIN (
						SELECT COUNT(*) NB_KPI, MAIL_ID
						FROM QMAIL_KPI
						GROUP BY MAIL_ID
					) k ON k.MAIL_ID = m.MAIL_ID';
		if($iProjectId > 0){
			$sQuery .= ' WHERE m.PROJECT_ID = :projectId';
		}
		return \DB::query($sQuery)->param('projectId', $iProjectId)->as_object()->execute(self::$_connection);
	}
	
	public static function deleteMail($iMailId)
	{
		$sQuery = 'DELETE FROM '.self::$_table_name.' WHERE MAIL_ID = :mailId';
		return \DB::query($sQuery)->param('mailId', $iMailId)->execute(self::$_connection);
	}
	
}