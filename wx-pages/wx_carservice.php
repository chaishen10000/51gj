<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>车管家</title>
  <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="apple-mobile-web-app-title" content="Ratchet" />
  <link rel="stylesheet" href="/wx-pages/css/ratchet.css?v=20150408" />
  <link rel="stylesheet" href="/wx-pages/css/style.css?v=20150408" />
  <link rel="stylesheet" href="/wx-pages/css/index.css" />
  <link rel="stylesheet" href="/wx-pages/css/date_common.css" />
  <script type="text/javascript">
	  /**
	   *防止“返回”本页面，导致订单数据错误。
	   */
	  if (e.NavigationMode == NavigationMode.Back){
		  location.reload(true);  
	  }
  </script>
  <?php
	include ('includes/dbconfig.php');
	session_start();
	//$_SESSION['openid'] = "oN8WPs8kWI3zaX8ToMMF5brBjdGE";
	if (!$_SESSION['openid']) {
		echo "<script>window.location.href='/wx-pages/wx_islogin.php';</script>";
	} else {
		$openid = $_SESSION['openid'];
		//查询车信息
		$row = $db->row_select("wx_property", "type = 'car' and openid='" . $openid . "'", 0, "*", "property_ID");
		$strtemp = "";
		foreach ($row as $key => $value) {
			$carname = $value['f'];
			if(mb_strlen($carname,'UTF8')>13){
			$carname = mb_substr($carname, 0, 6, 'utf-8') . "..." . mb_substr($carname, -7, 7, 'utf-8');
			}
			//输出车信息
			$strtemp.= "<li class='table-view-cell carjs' data-h='".$value['h']."' data-ID='" . $value['property_ID'] . "'> <i class='ico-map-marker'></i><div class='ipt'/>";
			$strtemp.= $carname;
			$strtemp.= '</div></li>';
		}
		//查询会员级别
		$row1 = $db->row_select("wp_users", "openid='" . $openid . "'", 1, "now_level", "ID");
		foreach ($row1 as $key => $value) {
			$level = $value['now_level'];
		}
		//获取会员折扣
		$row2 = $db->row_select("wx_member", "level=" . $level, 1, "discount", "ID");;
		foreach ($row2 as $key => $value) {
			$discount = $value['discount'];
		}
		//查询车管家服务类型
		$row = $db->row_select("wx_service", "class = '50' or class = '60' or class = '70'", 0, "*", "service_ID","asc");
		$strtemp2 = "";
		$i= 0;
		foreach ($row as $key => $value) {
			$typename = $value['itemname'];
			$i = $i+1;
			//输出车管家服务类型
			$strtemp2 .= "<li class='table-view-cell carservjs ' data-t='".$value['class']."'> <i class='ico-remark'></i><div class='ipt'/>";
			$strtemp2 .= $typename." <span style='font-size:13px;color:#F00''>服务明细 免责条款</span></li>";
		}
		//查询车保养服务子类型
		$row = $db->row_select("wx_service", "class = '51' or class = '52' or class = '53'", 0, "*", "service_ID","asc");
		$strtemp3 = "";
		$chstr = "";
		$i= 0;
		foreach ($row as $key => $value) {
			$typename = $value['itemname'];
			$i = $i+1;
			if($i==1) $chstr = "checked='checked'";
			if($i!=1) $chstr = "";
			$strtemp3 .= $typename."  <input class='ipt bytype' type='radio' name='car_serv' id='car_serv_".$i."' data-t='50' value='".$value['class']. "' ".$chstr."/>  
           | ";
		}
	}
?>
  <script type="text/javascript">var discount = <?php echo $discount;?></script>
  <script type="text/javascript" src='http://libs.useso.com/js/jquery/1.6.1/jquery.min.js'></script>
  <script type="text/javascript" src="/wx-pages/script/wx_main.js"></script>
  <script type="text/javascript" src="/wx-pages/script/date_main.js" ></script>
  <script type="text/javascript" src="/wx-pages/script/date_iscroll.js" ></script>
  <script type="text/javascript">
      
	  $(function(){
		  //$('#time').date();//只显示日期，不显示时间
		  $('#time_serv').date({theme:"datetime"}); //显示日期及时间
	  });
  </script>
  <meta charset="utf-8" />
  </head>

  <body>
<form name="fm1" method="post" action="" id="fm1" novalidate>
    <!--content begin-->
    <div class="content content2">
    <!--banner begin-->
    <div class="banner"> <a href="/wx-pages/fwjs.htm" data-ignore="push">
      <div> <img src="/wx-pages/images/sd_front.jpg" width="100%" alt="" /> </div>
      <div class="banner-nav navigate-right"> 查看服务介绍 </div>
      </a> </div>
    <!--banner end-->
    <div class="table-mod">
    <div class="table-mod">
        <h4>*请选择您需要的车管家服务</h4>
        <ul class="table-view card">
        <?php echo $strtemp2;?>
      </ul>
      </div>
    <div class="table-mod">
        <div class="table-mod">
        <h4>*请选择需要服务的车辆</h4>
        <ul class="table-view card">
        <?php echo $strtemp;?>
        <li class='table-view-cell'> <i class='ico-map-marker'></i>
            <div class='ipt'/>
            <a href="/wx-pages/wx_car.php">添加新车辆</a>
          </div>
        </li>
      </ul>
      </div>
    <!--table-view end-->
    <div class="table-mod">
        <h4>填写预约信息</h4>
        <ul class="table-view card">
        <li class="table-view-cell"> <i class="ico-bell"></i>
            <input class="ipt" type="text" name="time_serv" id="time_serv" placeholder="请选择服务时间"/>
          </li>
        <li class="table-view-cell baoy" style="display:none"> <i class="ico-remark"></i> <?php echo $strtemp3?> </li>
        <li class="table-view-cell addit_servli"> <i class="ico-remark"></i> 可选服务1：洗车（15-40元）
            <input class="ipt addit_serv" type="checkbox" name="car_clean" id="car_clean" value="90"/>
          </li>
        <li class="table-view-cell addit_servli"> <i class="ico-remark"></i> 可选服务2：加油
            <input class="ipt addit_serv" type="checkbox" name="car_filling" id="car_filling" value="80"/>
          </li>
        <li class="table-view-cell"> <i class="ico-remark"></i>
            <input class="ipt" type="text" name="remarks" id="remarks" placeholder="备注：如地址、联系人信息" />
          </li>
      </ul>
      </div>
  </div>
    
    <!--content end-->
    
    <div class="bar bar-footer">
    <div class="price_car pull-left"> <span class="price-real brush2"><strong><span id="realityprice"></span></strong></span> <span class="through"><span id="originalprice"></span></span> </div>
    <div class="pull-right">
        <button class="btn btn-positive btn-positive2" type="button" onclick="javascript:submitCarValidate();" id="orderbt">预定</button>
      </div>
  </div>
    <input type="hidden" class="ipt" name="property_ID" id="property_ID" value="" />
    <input type="hidden" name="openid" id="openid" value="<?php echo $openid;?>" />
    <input type="hidden" name="type" id="type" value="车管家" />
    <input type="hidden" class="ipt" name="service" id="service" />
    <input type="hidden" class="ipt" name="total_fee" id="total_fee" />
    <input type="hidden" class="ipt" name="cash_fee" id="cash_fee" />
  </form>
<div id="datePlugin"></div>
</body>
</html>