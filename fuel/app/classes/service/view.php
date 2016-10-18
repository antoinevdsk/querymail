<?php

namespace Service;

class View {

	protected static $aGlobals = array();
	protected static $aVariables = array();

	/**
	 * Add global variable to view for rendering
	 * @param string $key
	 * @param mixed $value
	 */
	public static function addGlobal($key,$value)
	{
		self::$aGlobals[$key]=$value;
	}

	/**
	 * Add variable to view for rendering
	 * @param string $key
	 * @param mixed $value
	 */
	public static function addVariable($key,$value)
	{
		self::$aVariables[$key]=$value;
	}

	/**
	 * Display page into constructed site layout
	 * @param string $file
	 * @param array $data
	 * @param boolean $auto_filter
	 */
	public static function forge($file = null, $data = null, $sMenuFile = null)
	{
		$sRequestUri = $_SERVER['REQUEST_URI'];
		$iPos = strpos(substr($sRequestUri, 1), '/');
		if($iPos){
			$sRequestUri = substr($sRequestUri, 0, $iPos+1);
		}
		$iPos = strpos(substr($sRequestUri, 1), '?');
		if($iPos){
			$sRequestUri = substr($sRequestUri, 0, $iPos+1);
		}

		$aData=array();
		$aData['javascript'] = implode("\n",\Service\Javascript::getCall());
		$aData['sRequestUri'] = $sRequestUri;
		isset($data['angularController']) && $aData['angularController'] = $data['angularController'];

		// set variables
		if($data == null) $data=array();
		$data=array_merge(self::$aVariables,$data);

		// create the view
		if(!\Input::is_ajax()){
        	$view = \View::forge('layout/layout',$aData);
        	//assign views as variables, lazy rendering
	        $view->content = \View::forge($file, $data);
	        if(!empty($sMenuFile)){
	        	$view->menu = \View::forge($sMenuFile, $data);
	        }else{
	        	$view->menu = '';
	        }
		}else{
			$data=array_merge($data,$aData);
			$view = \View::forge($file, $data);
		}

		// set globals vars
        foreach (self::$aGlobals as $k=>$v){
        	$view->set_global('global_'.$k,$v,false);
        }

        // set javascript vars
       	$view->set_global('jsVars',\Service\Javascript::getVars());

        // return the view object to the Request
        return $view;
	}

}
