<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>车辆登记信息修改</title>
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Ratchet">
<link rel="stylesheet" href="/wx-pages/css/ratchet.css?v=20150408">
<link rel="stylesheet" href="/wx-pages/css/style.css?v=20150408">
<link rel="stylesheet" href="/wx-pages/css/index.css?v=20150408">
<script type='text/javascript' src='http://libs.useso.com/js/jquery/1.6.1/jquery.min.js'></script> 
<script type="text/javascript" src="/wp-includes/js/jquery/jquery.form.js"></script> 
<script type="text/javascript" src="/wx-pages/script/wx_main.js"></script>
<style type="text/css">
html, body {
	height: 100%;
}
</style>
<meta charset="utf-8">
</head>
<?php
include ('includes/dbconfig.php');
session_start();
if (!$_SESSION['openid']) {
	echo "<script>window.location.href='/wx-pages/wx_islogin.php';</script>";
}else if(isset($_GET['property_ID'])){
	$openid = $_SESSION['openid'];
	$property_ID = $_GET['property_ID'];
	$row = $db->row_select("wx_property", "type = 'car' and property_ID='" . $property_ID . "'", 1, "*", "property_ID");
	$strtemp = "";
	foreach ($row as $key => $value) {
		$property_ID = $value['property_ID']; 
		$a = $value['a'];
		$b = $value['b'];
		$c = $value['c'];
		$d = $value['d'];
		$e = $value['e'];
		$f = $value['f'];
		$g = $value['g'];
    }
}
?>
<body>
<form name="fm1" id="fm1" >
  <input name="openid" type="hidden" value="<?php echo $openid ;?>">
  <input name="property_ID" type="hidden" value="<?php echo $property_ID ;?>">
  <input name="type" id="type" type="hidden" value="car">
  <input name="uptype" id="uptype" type="hidden" value="update">
  <input name="g" id="g" type="hidden" value="<?php echo $g;?>">
  <!--content begin-->
  <div class="content">
    <div class="table-mod">
      
      <ul class="table-view card">
        <li class="table-view-cell"> <i class="ico-remark"></i>
          <input type="text" class="ipt" name="a" id="a" value="<?php echo $a;?>" placeholder="车辆所有人（须与行驶证一致）"/>          
        </li>
        <li class="table-view-cell"> <i class="ico-remark"></i>
          <input class="ipt" type="text" name="b" id="b" value="<?php echo $b;?>" placeholder="联系人（日常具体事物联系）"/>
        </li>
        <li class="table-view-cell"> <i class="ico-remark"></i>
          <input class="ipt" type="text" name="c" id="c" value="<?php echo $c;?>"  placeholder="固定电话（如：01088632548） "/>
        </li>
        <li class="table-view-cell"> <i class="ico-remark"></i>
          <input class="ipt" type="text" name="d" id="d" value="<?php echo $d;?>" placeholder="移动电话 "/>
        </li>
        <li class="table-view-cell"> <i class="ico-remark"></i>
          <input class="ipt" type="text" name="e" id="e" value="<?php echo $e;?>" placeholder="车险到期日期（如：20160809）"/>
        </li>
        <li class="table-view-cell"> <i class="ico-remark"></i>
          <input class="ipt" type="text" name="f" id="f" value="<?php echo $f;?>" placeholder="厂商及型号 "/>
        </li>
        
        <li class="table-view-cell" id="coupons_view">
        <?php if(empty($g)){?>
        <p class="form-row form-row-wide">
          <div class="imagup">
            <p><span class="required">*尚未添加行驶证图片。</span><br/>说明：须为gif/jpg格式，不超过2M。</p>
            <div class="imagup_btn1"> &nbsp;<span>添加行驶证图片</span>
              <input id="fileupload1" type="file" name="mypic1" value="上传效果图">
            </div>
            <div class="imagup_progress"> <span class="imagup_bar"></span><span class="imagup_percent">0%</span > </div>
            <div class="imagup_files" style="display:none;">
            </div>
            <div style="height:2px"></div>
            <div id="showimges" class="showimg">
            </div>
          </div>
        <?php }else {?>
          <p class="form-row form-row-wide">
          <div class="imagup">
            <?php if($approved == 0){?>
            <p><span class="required">*</span>说明：须为gif/jpg格式，不超过2M。</p>
            <div class="imagup_btn1"> &nbsp;<span id="weixin">修改行驶证图片</span>
            </div>
            <div class="imagup_btn1"> &nbsp;<span id="upload">确认上传</span>
            </div>
            <?php }?>
            <div style="height:2px"></div>
            <div class="showimg">
            <img id="showimges" src="<?php echo "/wx-pages/uploads/".trim($g);?>" style="width:280px">
            </div>
          </div>
          <?php }?>
     </li>
      </ul>
      <ul class="table-view card" style="border:none">
        <div class="pull-right" id="clsShow">
          <?php 
		  if(empty($images1)||empty($images1)||empty($images1)||empty($images1)){}else{?>
          <button class="btn btn-positive btn-positive2" type="button" style="background-color:#825623; border-color:#825623" onClick="javascript:location.href='/wx-pages/wx_propertyimg.php?property_ID=<?php echo $property_ID;?>'" >现状图库</button> &nbsp;&nbsp;&nbsp;&nbsp;
          <?php } if($approved == 0){?>
          <button class="btn btn-positive btn-positive2" type="button" style="background-color:#CCC; border-color:#CCC" id='sp_del' rel=' <?php echo $property_ID;?>'>删除</button> &nbsp;&nbsp;&nbsp;&nbsp;
          <button class="btn btn-positive btn-positive2" type="button" style="background-color:#825623; border-color:#825623" id="property_updata" onclick="javascript:submitValidate_c();">修改</button>
          <?php }if($approved == 1){?>
          <button class="btn btn-positive btn-positive2" type="button" style="background-color:#CCC; border-color:#CCC" id="property_updata" onClick="javascript:noticeDialog('车辆已认证，如需修改请致电：15008029518',5000);">修改</button>
          <?php }?>
        </div>
      </ul>
    </div>
    <div style="height:30px;"></div>
  </div>
  <!--content end-->
</form>

<?php
/**
	请开启PHP的扩展
	extension=php_curl.dll
	extension=php_openssl.dll
	**/
	//设置报错级别，忽略警告，设置字符
	error_reporting(E_ALL || ~E_NOTICE);
	require_once "includes/jssdk.php";
	//配置JSSDK参数
	$jssdk = new JSSDK();
	$signPackage = $jssdk->GetSignPackage();
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
$(document).ready(function(){
  wx.config({
    debug: false, //调试阶段建议开启
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
           /*
            * 所有要调用的 API 都要加到这个列表中
            * 这里以图像接口为例
            */
          "chooseImage",
          "previewImage",
          "uploadImage",
          "downloadImage"
    ]
  });
  var btn = document.getElementById('weixin');
  //定义images用来保存选择的本地图片ID，和上传后的服务器图片ID
  var images = {
      localId: [],
      serverId: []
  };
  wx.ready(function () {
    // 在这里调用 API
    btn.onclick = function(){
        wx.chooseImage ({
			count: 1, // 默认9
            sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
            sourceType: ['camera'], // 可以指定来源是相册（album）还是相机（camera），默认二者都有
            success : function(res){
                images.localId = res.localIds;  //保存到images
                document.getElementById('showimges').src=images.localId;
                // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
            }
        });
    }
    document.getElementById('upload').onclick = function(){
        var i = 0, len = images.localId.length;
        function wxUpload(){
            wx.uploadImage({
                localId: images.localId[i], // 需要上传的图片的本地ID，由chooseImage接口获得
                isShowProgressTips: 1, // 默认为1，显示进度提示
                success: function (res) {
                    i++;
                    //将上传成功后的serverId保存到serverid
                    images.serverId.push(res.serverId);
                    //(serverId 即 media_id,公众号此后可根据该media_id来获取多媒体)
					//将上传的图片通过AJAX远程提交给php下载到本地服务器
                    $.get("/wx-pages/wx_property_do.php", {act:"newimg", media_id: res.serverId, accesstoken: "<?php echo $signPackage["accesstoken"];?>" ,time: "2pm" },
                    function(data){
                        if(data=="error"){
							alert("上传错误，请重试！");
						}else{
						    $("#g").val(data);
							$("#upload").html("上传成功");
						}
                    });                 

                    if(i < len){
                        wxUpload();
                    }  
                }
            });
        }      
        wxUpload(); 
    }
    /*document.getElementById('getServices').onclick = function(){
         alert(images.serverId);
         //images.serverId   不能直接通过src读取,images.localId才可以直接赋值给img src
         var a=document.getElementById ("a");
            a.innerHTML = images.serverId;
    }*/
  });

});
</script>
</body>
</html>