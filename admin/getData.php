<?php 
    require("includes/connection.php");
    require("includes/function.php");
    require("language/language.php");
    
    $pageLength = 30;
    $pageStart = ($_GET['page'] - 1) * $pageLength;
    $pageEnd = $pageStart + $pageLength;
    

    $type=$_GET['type'];

    $items=array();

    if(!isset($_GET['page']))
    	$items[] = array("id"=>0, "text"=>'---Select---');

    if($type=='category'){
    	if(isset($_GET['search'])){
        
	        $search=trim($_GET['search']);
	        
	        $sql_total=mysqli_query($mysqli,"SELECT * FROM tbl_category WHERE `status`='1' AND `category_name` LIKE '%$search%'");
	        
	        $query = "SELECT * FROM tbl_category WHERE `status`='1' AND `category_name` LIKE '%$search%' LIMIT $pageStart, $pageEnd";
	    }
	    else{
	    	$sql_total=mysqli_query($mysqli,"SELECT * FROM tbl_category WHERE `status`='1'");
	        $query = "SELECT * FROM tbl_category WHERE `status`='1' LIMIT $pageStart, $pageEnd";
	    }
	    
	    $total_items=mysqli_num_rows($sql_total);
	    
	    $res=mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
	    
	    $numRows = mysqli_num_rows($res);
	    
	    if($numRows != 0) {
	        while($row = mysqli_fetch_array($res)) {
	            $items[] = array("id"=>$row['cid'], "text"=>$row['category_name']);
	        }
	    }else {
	        $items[] = array("id"=>"0", "text"=>"No Results Found...");    
	    }
    }
    else if($type=='news'){
    	if(isset($_GET['search'])){
        
	        $search=trim($_GET['search']);
	        
	        $sql_total=mysqli_query($mysqli,"SELECT * FROM tbl_news WHERE `status`='1' AND `news_heading` LIKE '%$search%'");
	        
	        $query = "SELECT * FROM tbl_news WHERE `status`='1' AND `news_heading` LIKE '%$search%' LIMIT $pageStart, $pageEnd";
	    }
	    else{
	    	$sql_total=mysqli_query($mysqli,"SELECT * FROM tbl_news WHERE `status`='1'");
	        $query = "SELECT * FROM tbl_news WHERE `status`='1' LIMIT $pageStart, $pageEnd";
	    }
	    
	    $total_items=mysqli_num_rows($sql_total);
	    
	    $res=mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
	    
	    $numRows = mysqli_num_rows($res);
	    
	    if($numRows != 0) {
	        while($row = mysqli_fetch_array($res)) {
	            $items[] = array("id"=>$row['id'], "text"=>stripslashes($row['news_heading']));
	        }
	    }else {
	        $items[] = array("id"=>"0", "text"=>"No Results Found...");    
	    }
    }
    
    $response=array('items' =>$items, 'total_count' => $total_items);
    
    echo json_encode($response);

?>