<div class="pagination pagination-small pagination-centered ">
<?php
	if($data['pageCount'] > 1){
		echo '<ul>';
		echo '<li>';
		echo '<a href="'.CleanURI($_SERVER['REQUEST_URI']).'p='.$data['prev'].'">«</a>';
		echo '</li>';
		for($i = 0; $i <= $data['pageCount']; $i += 1){
			echo '<li>';
			echo '<a href="'.CleanURI($_SERVER['REQUEST_URI']).'p='.($i+1).'">'.($i+1).'</a>';			
			echo '</li>';
		}
		echo '<li>';
		echo '<a href="'.CleanURI($_SERVER['REQUEST_URI']).'p='.$data['next'].'">»</a>';
		echo '</li>';
		echo '</ul>';
	}
	function CleanURI($uri) {
		$pagePos = strpos($uri, 'p=');
		if($pagePos !== false){
			return substr($uri, 0, $pagePos - 1). '&';
		}
		
		$questionMark = strpos($uri, '?');
		if($questionMark === false){
			return $uri . '?list&';			
		}
		
		$ampMark = strpos($uri, '&');
		if($ampMark === false){
			return $uri . '&';
		}
		return $uri;
	} 
?>
</div>