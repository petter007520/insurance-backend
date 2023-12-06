  <?php  include("/template/top.php");?>
  <link rel="stylesheet" href="/mobile/public/Front/css/common.css" /> 
  <link rel="stylesheet" href="/mobile/public/Front/css/react.css" />
  </head>
  <body>
  <div class="wrap"> 
    <?php  include("/template/menu.php");?>
   <div class="full-line"></div>
  <div class="container-wrap">
    <div class="wrapwidth">
      <div class="safeindex">
        <div id="forgot-password-react-wrapper"><div >
          <div class="forgot-password">
            <div class="forgot-password-body" id="isnplay">
             <div><h2 class="orange">找回密码</h2>
                <ul>
                     <li><label>用户名：</label><input type="text" class="borderchange" placeholder="" id="username"></li>
                     <li><label>手机号码：</label><input type="text" class="borderchange" placeholder="" id="mobile" style="margin-left:5px;"><span class="error-tip"></span></li>
                     <li><label style="width:100px;padding-right:0px;">验证码：</label><input type="text" id="code" class="borderchange" placeholder="" style="width:100px;margin-left:22px;">
                     <span class="captcha">
                      <a href="javascript:;"><img alt="auth code" src="../../include/checkcode.php" id="codeImg" onClick="retCode();" width="150" height="40"><span  onClick="retCode();"> 换一张</span></a></span></li>
                      <span id="error"></span>
                      <li class="submit-btn"><input type="submit" class="btn btn-primary forgetPassWordBtn" value="重置密码" style="background:#f13131;border:none;"></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <?php  include("/template/foot.php");?>
  <script type="text/javascript">
   $('.forgetPassWordBtn').click(function(){
      var username = $("#username").val();
      var mobile   = $("#mobile").val(); 
      var code     = $("#code").val();
      if(!username){
        $("#error").html("请填写用户名");
        return ;
      }
      if(!mobile){
        $("#error").html("请填写手机号码");
        return ;
      }
      if(!code){
        $("#error").html("请填写验证码");
        return ;
      }

      $.ajax({
      type : "POST",
      url : "/action/register.php?action=forgot",
      dataType : "json",
      data : 'username=' + username + '&mobile=' + mobile  + '&code=' + code,
      success : function (data) {
        if(data.status == "y"){
            $html = "";
            $html+= "<div><h2 class=\"resetpass orange\">恭喜您，已经成功重置您的密码！</h2>";
            $html+= "<p>新密码已通过短信发送到您的手机，请使用新密码登录！</p><br>";
            $html+= "<a class=\"btn btn-orange\" href=\"/mobile/login.php\">立即登录</a></div>";
            $("#isnplay").html($html);
        }else{
            retCode();
            $("#error").html(data.info);
        }
      }
    });

  });
  function retCode() {
    $("#codeImg").attr("src", "../../include/checkcode.php?" + Math.random());
  }
</script>
 </body>
</html>