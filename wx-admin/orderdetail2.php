<!DOCTYPE html>
<html>
 <head> 
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  <title>订单详情</title> 
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" /> 
  <meta name="apple-mobile-web-app-capable" content="yes" /> 
  <meta name="apple-mobile-web-app-status-bar-style" content="black" /> 
  <meta name="apple-mobile-web-app-title" content="Ratchet" /> 
  <link rel="stylesheet" href="/wx-pages/css/ratchet.css?v=20150408" /> 
  <link rel="stylesheet" href="/wx-pages/css/style.css?v=20150408" /> 
  <link rel="stylesheet" href="/wx-pages/css/index.css?v=20150408">
  <script type='text/javascript' src='http://libs.useso.com/js/jquery/1.6.1/jquery.min.js'></script>
  <script type="text/javascript" src="/wp-includes/js/jquery/jquery.form.js"></script>
  <script type='text/javascript' src='/wx-admin/script/admin_main.js'></script>
  <style type="text/css"> 
	.table_list{overflow:hidden;zoom:1;} 
	.table_list li{ width:50%;float:left;} 
  </style> 
  <meta charset="utf-8" /> 
 </head> 
 <body> 
<?php
session_start();
if ($_SESSION['user_level'] <7 ) {
	//PC端后台
	echo "<script>window.location.href='/wp-admin/';</script>";
}else{
include ('includes/dbconfig.php');
    $order_ID = $_GET["order_ID"];
    $row = $db->row_select("wx_order", "order_ID=" . $order_ID, 1, "*", "order_ID");
    foreach ($row as $key => $value) {
		$openid = $value['openid'];
        $state = $value['state'];
        $type = $value['type'];
        $service = $value['service'];
        $time_serv = $value['time_serv'];
        $property_ID = $value['property_ID'];
        $cash_fee = (int)$value['cash_fee'];
		$cash_fee1 = $cash_fee/100;
		$total_fee = (int)$value['total_fee'];
		$total_fee1 = $total_fee/100;
		$customcheck = $value['customcheck'];
		$fimages = $value['fimages'];
		$aimages = $value['aimages'];
    }
    if ($_GET["action"] == 1) {
        $urow = array();
        $urow['state'] = '服务中';
        $upstate = serviceupdate($db, $urow, $order_ID);
		if($upstate == "True") echo "<script>window.location.href='orderdetail.php?order_ID=$order_ID';</script>"; 
    }
    if ($_GET["action"] == 2) {
        $urow = array();
        $urow["state"] = "已服务";
        $upstate = serviceupdate($db, $urow, $order_ID);
		if($upstate == "True") echo "<script>window.location.href='orderdetail.php?order_ID=$order_ID';</script>"; 
    } else {
        $row = $db->row_select("wx_property", "property_ID=" . $property_ID, 1, "e", "property_ID");
        foreach ($row as $key => $value) {
            $address = $value['e'];
        }
        $obj = json_decode($service);
        $sqlstr = "";
        //常规清洁
        if (isset($obj->{'11'})) $sqlstr.= getserviceitem($db, '11') . "x" . $obj->{'11'} . " ";
        if (isset($obj->{'12'})) $sqlstr.= getserviceitem($db, '12') . "x" . $obj->{'12'} . " ";
        if (isset($obj->{'13'})) $sqlstr.= getserviceitem($db, '13') . "x" . $obj->{'13'} . " ";
        if (isset($obj->{'14'})) $sqlstr.= getserviceitem($db, '14') . "x" . $obj->{'14'} . " ";
        //深度清洁
        if (isset($obj->{'21'})) $sqlstr.= getserviceitem($db, '21') . "x" . $obj->{'21'} . " ";
        if (isset($obj->{'22'})) $sqlstr.= getserviceitem($db, '22') . "x" . $obj->{'22'} . " ";
        if (isset($obj->{'23'})) $sqlstr.= getserviceitem($db, '23') . "x" . $obj->{'23'} . " ";
        if (isset($obj->{'24'})) $sqlstr.= getserviceitem($db, '24') . "x" . $obj->{'24'} . " ";
        //除尘除螨
        if (isset($obj->{'31'})) $sqlstr.= getserviceitem($db, '31') . "x" . $obj->{'31'} . " ";
        if (isset($obj->{'32'})) $sqlstr.= getserviceitem($db, '32') . "x" . $obj->{'32'} . " ";
        if (isset($obj->{'33'})) $sqlstr.= getserviceitem($db, '33') . "x" . $obj->{'33'} . " ";
        if (isset($obj->{'34'})) $sqlstr.= getserviceitem($db, '34') . "x" . $obj->{'34'} . " ";
        //家电清洁
        if (isset($obj->{'41'})) $sqlstr.= getserviceitem($db, '41') . "x" . $obj->{'41'} . " ";
        if (isset($obj->{'42'})) $sqlstr.= getserviceitem($db, '42') . "x" . $obj->{'42'} . " ";
        if (isset($obj->{'43'})) $sqlstr.= getserviceitem($db, '43') . "x" . $obj->{'43'} . " ";
        if (isset($obj->{'44'})) $sqlstr.= getserviceitem($db, '44') . "x" . $obj->{'44'} . " ";
        if (isset($obj->{'45'})) $sqlstr.= getserviceitem($db, '45') . "x" . $obj->{'45'} . " ";
    }
	if($type=="房管家") $sqlstr = "房管家整体清洁养护";
	if($type=="住前打理") $sqlstr = "住前整体清洁、整理";
	if($type=="住后整理") $sqlstr = "住后整体清洁、整理";
	
	//获取客户ID
	$users = $db->row_select('wp_users', 'openid=\''.$openid.'\'', 1, 'ID,user_nicename', 'ID');
	$ID = $users[0]["ID"];
	$user_nicename = $users[0]["user_nicename"];

function getserviceitem($db, $class) {
    $row = $db->row_select("wx_service", "class='" . $class . "'", 1, "itemname", "service_ID");
    foreach ($row as $key => $value) {
        $itemname = $value['itemname'];
    }
    return $itemname;
}

function serviceupdate($db, $urow, $order_ID) {
    $row = $db->row_update("wx_order", $urow, "order_ID=" . $order_ID);
    //记录日志
    if ($row) {
        return "True"; 
    }
}
?>
    <!--banner begin--> 
    <div class="banner"> 
      <div style="text-align:center;"> 
       <img style="margin:0 auto;" src="/wx-pages/images/none.png" width="100%" alt="" /> 
      </div> 
      <h3 style="text-align:center;color:#E6550F">目前状态：<?php echo $state;?></h3> 
    </div> 
    <!--banner end--> 
    <ul class="table-view table-view2" id="onup"> 
    <li class="table-view-cell table-disabled" id="numtable0"> 
      <div class="table-cell" style="text-align:center; font-weight:700;"> 
       客户 
      </div> 
      <div class="table-cell" style="text-align:left; ">
       <a href="/wp-admin/user-edit.php?user_id=<?php echo $ID;?>" target="new"> <?php echo $user_nicename;?></a>
      </div> 
     </li> 
     <li class="table-view-cell table-disabled" id="numtable0"> 
      <div class="table-cell" style="text-align:center; font-weight:700;"> 
       项目 
      </div> 
      <div class="table-cell" style="text-align:left; ">
       <?php echo $type;?> 
      </div> 
     </li> 
     <li class="table-view-cell table-disabled" id="numtable0"> 
      <div class="table-cell" style="text-align:center; font-weight:700;"> 
       内容 
      </div> 
      <div class="table-cell" style="text-align:left; ">
       <?php echo $sqlstr;?>
      </div> 
     </li> 
     <li class="table-view-cell table-disabled" id="numtable0"> 
      <div class="table-cell" style="text-align:center; font-weight:700;"> 
       时间 
      </div> 
      <div class="table-cell" style="text-align:left; ">
       <?php echo $time_serv;?>
      </div> 
     </li> 
     <li class="table-view-cell table-disabled" id="numtable0"> 
      <div class="table-cell" style="text-align:center; font-weight:700;"> 
       地址 
      </div> 
      <div class="table-cell" style="text-align:left;">
       <a href="/wx-admin/propertydetail.php?property_ID=<?php echo $property_ID;?>"> <?php echo $address;?></a> 
      </div> 
     </li> 
     <li class="table-view-cell table-disabled" id="numtable0"> 
      <div class="table-cell" style="text-align:center; font-weight:700;"> 
       价格 
      </div> 
      <div class="table-cell" style="text-align:left; ">
       <?php echo $cash_fee1;?>元
       <?php if($cash_fee1<$total_fee1){?>（<span style="text-decoration:line-through"><?php echo $total_fee1;?></span>元）
       <?php }?> 
      </div> 
     </li> 
     <?php if($state == "已服务"){?>
    <li class="table-view-cell" id="coupons_view">
    <p class="form-row form-row-wide">
      <div class="imagup">
        <p><span class="required">*</span>说明：须为gif/jpg格式，不超过2M。</p>
        <div class="imagup_btn1"> &nbsp;<span>添加服务前效果图</span>
          <input id="fileupload1" type="file" name="mypic1" value="上传效果图">
        </div>
        <div class="imagup_btn2"> &nbsp;<span>添加服务后效果图</span>
          <input id="fileupload2" type="file" name="mypic2" value="上传效果图">
        </div>
        <div class="imagup_progress"> <span class="imagup_bar"></span><span class="imagup_percent">0%</span > </div>
        <div class="imagup_files">
          <div id="showimges"></div>
        </div>
        <div style="height:5px"></div>
        <?php 
			if(!empty($fimages)){
			$imagearr = explode(";",$fimages);
			echo "<h4>服务前效果</h4>";
			foreach($imagearr as $u){
	    ?>
        <div class="showimg" id="show<?php echo substr($u,0,strlen($u)-4);?>"><img src='/wx-admin/uploads/<?php echo $u?>' width='100%'>
          <div class="imagup_delimg" data-fag="first" data-id="<?php echo $order_ID?>" rel="<?php echo $u?>">删除</div>
        </div>
        <div style="height:2px"></div>
        <?php }}
			if(!empty($aimages)){
				$imagearr = explode(";",$aimages);
				echo '<h4>服务后效果</h4>';
				foreach($imagearr as $u){
			?>
        <div class="showimg" id="show<?php echo substr($u,0,strlen($u)-4);?>"><img src='/wx-admin/uploads/<?php echo $u?>' width='100%'>
          <div class="imagup_delimg" data-fag="after" data-id="<?php echo $order_ID?>" rel="<?php echo $u?>">删除</div>
        </div>
        <div style="height:2px"></div>
        <?php }}
			 if(empty($fimages)&& empty($aimages)){?>
        <div id="showimg"></div>
        <?php }?>
      </div>
    </p>
  </li>
    <?php }?>
    <?php if($state == "已完成"){?>
    <li class="table-view-cell" id="coupons_view">
    <?php if(!empty($images)){?>
    <div id="showimg"><img src='/wx-admin/uploads/<?php echo $images?>' width='100%'></div>
    <?php }else {?>
    <div id="showimg"></div>
    <?php }?>
  </li>
    <?php }?>
  </ul>
    <input name="order_ID" id="order_ID" type="hidden" value="<?php echo $order_ID?>">
    <?php }?>
 </body>
</html>