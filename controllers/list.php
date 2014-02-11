<?php

class List_Controller 
{
    public $templatelist = 'list';
    public $templateitem = 'item';
    public $paginationItems = 10;

    public function month_spanish($month)
    {
    	switch($month)
    	{
    		case "1": $m = "Enero"; break;
    		case "2": $m = "Febrero"; break;
    		case "3": $m = "Marzo"; break;
    		case "4": $m = "Abril"; break;
    		case "5": $m = "Mayo"; break;
    		case "6": $m = "Junio"; break;
    		case "7": $m = "Julio"; break;
    		case "8": $m = "Agosto"; break;
    		case "9": $m = "Septiembre"; break;
    		case "10": $m = "Octubre"; break;
    		case "11": $m = "Noviembre"; break;
    		case "12": $m = "Diciembre"; break;
    	}
    	return $m;
    }
    
    public function load_date($date)
    {
    	return date("d",$date)." de ".$this->month_spanish(date("n",$date))." de ".date("Y",$date);
    }
    
    public function main(array $getVars)
    {
    	session_start();
		if(!isset($_SESSION['myusername'])){
			header("location:main_login.php");
		}
		
		$mainlist = new View_Model('templates/mainlist');
		$mnglist = new View_Model('templates/mgnlist');
    	$header = new View_Model('templates/header');
    	$footer = new View_Model('templates/footer');
    	$listModel = new List_Model;
    	if(strlen($getVars['id'])>0)
    	{
	    	$view = new View_Model($this->templateitem);
    		$header->assign('title', 'Transcargo - Detalle de orden');
	    	$item = $listModel->get_item($getVars['id']);
	    	$fromPlace = $view->get_place($item['fromPlaceId']);
	    	$toPlace = $view->get_place($item['toPlaceId']);
	    	$view->assign('order',$item['code']);
	    	$view->assign('date',$item['tripDate']);
	    	$view->assign('isContainerMovement', $item['movementType'] == 2);
	    	$view->assign('clients',$view->get_general_list('clients'));
	    	$view->assign('places',$view->get_general_list('places'));
	    	$view->assign('moveTypes',$view->get_general_list('moveTypes'));
	    	$view->assign('autoComments', $view->get_general_list('infoTypes'));
	    	$view->assign('shippings', $view->get_general_list('shippings'));
	    	$view->assign('containerTypes', $view->get_general_list('containers'));
	    	$view->assign('statusTypes', $view->get_general_list('statusTypes'));
	    	$view->assign('transports', $view->get_transports_list());
	    	$view->assign('drivers', $view->get_general_list('drivers'));
	    	$view->assign('activities', $view->get_activities_list($fromPlace['placeType'],$toPlace['placeType']));
	    	$view->assign('chassises', $view->get_general_list('chassises'));
    	}
    	else 
    	{       		
    		/* HEADER INFO */
    		$uppercontent = new View_Model('templates/uppercontent');
    		$createorderlnk = new View_Model('templates/createorder');
    		$actionsdropdown = new View_Model('templates/actionsdropdown');
		    $view = new View_Model($this->templatelist);
	    	$header->assign('title', 'Transcargo - Listado de ordenes');
	    	$view->assign('uppercontent', $uppercontent->render(FALSE));
	    	$view->assign('createorderlink', $createorderlnk->render(FALSE));
	    	$view->assign('actionsdropdown', $actionsdropdown->render(FALSE));
	    	
	    	/* CREATE ORDER PANEL */	    	
		    $createViewModel = new View_Model('create');
	    	 	
	    	$createViewModel->assign('places', $createViewModel->get_places());
	    	$createViewModel->assign('clients', $createViewModel->get_general_list('clients'));
	    	$createViewModel->assign('moveTypes',$createViewModel->get_general_list('moveTypes'));
	    	$createViewModel->assign('autoComments',$createViewModel->get_infoTypes());
	    	$createViewModel->assign('containerTypes',$createViewModel->get_general_list('containers'));
	    	$createViewModel->assign('shippings', $createViewModel->get_general_list('shippings'));
	    	$createViewModel->assign('statusTypes', $createViewModel->get_general_list('statusTypes'));
	    	
		    $view->assign('create', $createViewModel->render(FALSE));
	    	
		    /* DATA OF THE LIST */
    		$pagination = new View_Model('templates/pagination');
    		$currentPage = 1;
    		$paginationDisplay = '';
    		$pageCount = 0;
    		if(strlen($getVars['p'])>0){
    			$currentPage = $getVars['p'];
    		}
	    	$maxItemLimit = $currentPage * $this->paginationItems;
	    	$minItemLimit = $maxItemLimit - $this->paginationItems + 1;
	    	$pagedList = array();
	    	$searchOption = $getVars['s'];
	    	$searchParam = $getVars['f'];
    		if (strlen($getVars['frame'])>0)
	    	{
	    		$filter = $getVars["ftype"];
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
	    			    		
	    		$list = $listModel->get_prg_list($option, $searchOption, $searchParam, $filter);
	    		$pageCount = count($list)/$this->paginationItems;
	    		$paginationDisplay = 'pagination2';
	    		
	    		$index = 1;
				foreach($list as $item) {
					if($index <= $maxItemLimit  && $index >= $minItemLimit)
						array_push($pagedList, $item);					
					$index += 1;
				}
	    		$dateRange = $listModel->calculate_daterange($option);
	    		if(date("Y", $dateRange["toDate"]) > 2000)
	    			$view->assign('dateRange', date("d", $dateRange["fromDate"])." a ".$this->load_date($dateRange["toDate"]));
	    		else
	    			$view->assign('dateRange', $this->load_date($dateRange["fromDate"]));
	    		$view->assign('option', $option);
	    		$mainlist->assign('list', $pagedList);
	        	$view->assign('list2' , $mainlist->render(FALSE));
	    		$view->assign('tab', 1);
	    	}
	    	else if(strlen($getVars['last'])>0)
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
	    			    		
	    		$list = $listModel->get_mng_list($searchOption, $searchParam);
	    		$pageCount = count($list)/$this->paginationItems;
	    		$paginationDisplay = 'pagination3';
	    	
	    		$index = 1;
				foreach($list as $item){
					if($index <= $maxItemLimit  && $index >= $minItemLimit)
						array_push($pagedList, $item);					
					$index += 1;
				}
				
	    		$mnglist->assign('list', $pagedList);
	        	$view->assign('list3' , $mnglist->render(FALSE));
	    		$view->assign('tab', 2);
	    	}
	    	else
	    	{
	    		$option = $getVars['main'];
	    		if(strlen($option) == 0)
	    			$option = 'today';
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
		        $list = $listModel->get_list($searchOption, $searchParam, $option);
	    		$pageCount = count($list)/$this->paginationItems;
	    		$paginationDisplay = 'pagination1';
	    		
	    		$index = 1;
				foreach($list as $item){
					if($index <= $maxItemLimit  && $index >= $minItemLimit)
						array_push($pagedList, $item);					
					$index += 1;
				}
					    		   
	    		$mainlist->assign('list', $pagedList);
	        	$view->assign('list1' , $mainlist->render(FALSE));
	        	$view->assign('option', $option);
	    		$view->assign('tab', 0);	 
	    	}
	    	$pagination->assign('pageCount', $pageCount);
    		$pagination->assign('prev', $currentPage == 1 ? '' : $currentPage - 1);
    		$pagination->assign('next', $currentPage == $pageCount ? '' :  $currentPage + 1);
	    	$view->assign($paginationDisplay, $pagination->render(FALSE));
    	}		
    	$view->assign('header', $header->render(FALSE));
    	$view->assign('footer', $footer->render(FALSE));
	    $view->render();
    }
}