<?php
class Export_Controller 
{	
    public function main(array $getVars)
    {    	
    	//$export = new View_Model('export');    	
	    //$export->render();
	    
    	$view = new View_Model('export');    
    	$header = new View_Model('templates/header');
    	
    	$listView = new View_Model('templates/mainlist');
    	
	    $header->assign('title', 'Transcargo - Expotar');
    	$view->assign('header', $header->render(FALSE));
		$list = Array();
		
    	$listModel = new List_Model;
    	if (strlen($getVars['frame'])>0)
	    {
	    	$option = $getVars['frame'];
    		if($searchOption == 'advance')
    		{
    			$searchOption .= ',';
    			if($getVars['date'] != '') {
    				$searchOption .= $getVars['date'] . ',';
    				$searchParam .= 'date,';
    			}
    			if($getVars['client'] != '') {
    				$searchOption .= $getVars['client'] . ',';
    				$searchParam .= 'client,';
    			}
    			if($getVars['driver'] != '') {
    				$searchOption .= $getVars['driver'] . ',';
    				$searchParam .= 'driver,';
    			}
    			if($getVars['container'] != '') {
    				$searchOption .= $getVars['container'] . ',';
    				$searchParam .= 'container,';
    			}
   			}
	    			    		
	    	$list = $listModel->get_prg_list($option, $searchOption, $searchParam);
	    }
	    else if(strlen($getVars['last'])>0)
	    {
    		$listView = new View_Model('templates/mnglist');
	    	if($searchOption == 'advance')
	    	{
	    		$searchOption .= ',';
	    		if($getVars['date'] != '') {
	    			$searchOption .= $getVars['date'] . ',';
	    			$searchParam .= 'date,';
	    		}
	    		if($getVars['client'] != '') {
	    			$searchOption .= $getVars['client'] . ',';
	    			$searchParam .= 'client,';
	    		}
	    		if($getVars['driver'] != '') {
	    			$searchOption .= $getVars['driver'] . ',';
	    			$searchParam .= 'driver,';
	    		}
	    		if($getVars['container'] != '') {
	    			$searchOption .= $getVars['container'] . ',';
	    			$searchParam .= 'container,';
	    		}
	    	}
	    	    			    		
	    	$list = $listModel->get_mng_list($searchOption, $searchParam);
	    }	      
	    else
	    {
	    	if($searchOption == 'advance')
	    	{
	    		$searchOption .= ',';
	    		if($getVars['date'] != '') {
	    			$searchOption .= $getVars['date'] . ',';
	    			$searchParam .= 'date,';
	    		}
	    		if($getVars['client'] != '') {
	    			$searchOption .= $getVars['client'] . ',';
	    			$searchParam .= 'client,';
	    		}
	    		if($getVars['driver'] != '') {
	    			$searchOption .= $getVars['driver'] . ',';
	    			$searchParam .= 'driver,';
	    		}
	    		if($getVars['container'] != '') {
	    			$searchOption .= $getVars['container'] . ',';
	    			$searchParam .= 'container,';
	    		}
	    	}
		    $list = $listModel->get_list($searchOption, $searchParam);
	    }
	    $listView->assign('list', $list);
    	$view->assign('list', $listView->render(FALSE));
    	$view->render();
    }
}