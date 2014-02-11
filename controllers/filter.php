<?php
class Filter_Controller
{
	public function main(array $getVars)
    {
    	$filter = $getVars['filter'];
    	if($filter == 'advance') {
	    	$filterViewModel = new View_Model('advancefilter');
    		/* TODO:
    		 * Enviar lista de meses y fechas ordenadas descendientemente
    		 * Enviar lista de clientes
    		 * Enviar lista de choferes
    		 * Enviar lista de contenedores */
	    	
    		$dates = array();
			for ($i=0; $i<12; $i++)
			{
	    		array_push($dates, mktime(0, 0, 0, date('m') - $i, date("d"), date("Y")));
			}
			$filterViewModel->assign('dates', $dates);			
	    	$filterViewModel->assign('clients', $filterViewModel->get_general_list('clients'));
	    	$filterViewModel->assign('drivers', $filterViewModel->get_general_list('drivers'));
	    	$filterViewModel->assign('containers', $filterViewModel->get_general_list('currentcontainers'));
	    	
    	}
    	else {    		
	    	$filterViewModel = new View_Model('filter');
    	}
	    
    	$filterViewModel->assign('text', $getVars['text']);
	    $filterViewModel->assign('filter', $filter);
    	echo $filterViewModel ->render(FALSE);
    }
}