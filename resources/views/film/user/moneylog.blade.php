@extends('wap.wap')

@section("header")
    @parent
    <div class="top" id="top" >
        <div class="kf">
            <p><a class="sb-back" href="javascript:history.back(-1)" title="返回"
                  style=" display: block; width: 40px;    height: 40px;
                          margin: auto; background: url('{{asset("mobile/images/arrow_left.png")}}') no-repeat 15px center;float: left;
                          background-size: auto 16px;font-weight:bold;">
                </a>
            </p>
            <div style="display: block;width:100%; position: absolute;top: 0;
     left: 0;text-align: center;  height: 40px; line-height: 40px; ">
                <a href="javascript:;" style="text-align: center;  font-size: 16px; ">{{Cache::get('CompanyLong')}}</a>
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="{{asset("mobile/public/Front/css/common.css")}}" />

    <link rel="stylesheet" type="text/css" href="{{asset("mobile/public/style/css/style.css")}}"/>
    <link href="{{asset("mobile/public/Front/user/user.css")}}" type="text/css" rel="stylesheet">
    <script type="text/javascript" charset="utf-8" src="{{asset("mobile/public/Front/user/user.js").'?t='.time()}}"></script>

@endsection

@section("js")
    @parent

     <script type="text/javascript" src="{{ asset("admin/lib/layui/layui.js")}}" charset="utf-8"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset("admin/lib/layui/css/layui.css")}}"/>
@endsection

@section("css")

    @parent


@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')
    <?php
    //待确认提现

     $daiwithdrawal= \App\Memberwithdrawal::where("userid",$Member->id)->where("status","0")->sum("amount");

    //待确认充值
    $dairecharge=\App\Memberrecharge::where("userid",$Member->id)->where("status","0")->sum("amount");

    //优惠收入
    $youhuirecharge=\App\Memberrecharge::where("userid",$Member->id)->where("type","优惠活动")->sum("amount");
    $tongzis=  \App\Productbuy::where("userid",$Member->id)->where("status","1")->sum("amount");
    $Memberamounts=$Member->amount+$Member->is_dongjie+$daiwithdrawal+$dairecharge+$youhuirecharge+$tongzis;


    ?>

    <div class="user_zx_right" >
        <div class="box" style="margin-top: 50px">
            <div class="tagMenu">
                <ul class="menu">
                    <li class="current"><a href="{{route("user.moneylog")}}">资金统计</a></li>
                    <li><a href="{{route("user.recharge")}}">马上充值</a></li>
                    <li><a href="{{route("user.recharges")}}">充值记录</a></li>
                    <li><a href="{{route("user.withdraw")}}">马上提现</a></li>
                    <li><a href="{{route("user.withdraws")}}">提现记录</a></li>
                    <li><a href="{{route("user.offline")}}">下线分成记录</a></li>

                </ul>
                <div class="hite"> <span id="account"></span> </div>
            </div>
        </div>

    <div class="myinfo" style="padding: 20px; margin-bottom: 15px;background:#fff;">
        <p style="margin:15px 0px;">尊敬的<?php echo \Cache::get('CompanyLong'); ?>用户，以下是您在<?php echo \Cache::get('CompanyLong'); ?>的资金情况，敬请仔细审阅</p>
        <table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" style="margin-top:10px;" height="314">
            <tbody><tr>
                <td bgcolor="#f5f5f5" height="34" style="padding-left:5px;font-size:13px; line-height:34px;border:#e6e6e6 solid 1px;"><span style="width: 5px;height: 20px;background-color: #0697DA;float: left;margin:7px;display: block;"></span>资金存量</td>
            </tr>
            <tr>
                <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px; padding:0px;">
                    <table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" height="171">
                        <tbody><tr height="50">
                            <td width="179" style="padding-left:0px; font-size:14px;" align="right">可用现金金额：</td>
                            <td width="142"><span style="color:#f13131; font-size:14px;">￥<?php echo $Member->amount; ?></span></td>
                            <td width="372">（可以用来直接提现或投标的金额）</td>
                        </tr>
{{--

                        <!-- <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        <tr height="50">
                        <td width="179" style="padding-left:30px; font-size:14px;" align="right">待收本息金额：</td>
                        <td width="142"><span style="color:#f13131; font-size:14px;">￥0.00</span></td>
                        <td width="372">（已经投资，尚未回收的本金和利息总额，未扣除佣金）</td>
                        </tr>

                        <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        <tr height="50">
                        <td width="179" style="padding-left:30px; font-size:14px;" align="right">待收本金金额：</td>
                        <td width="142"><span style="color:#f13131; font-size:14px;">￥0.00</span></td>
                        <td width="372">（已经投资，尚未回收的本金总额）</td>
                        </tr>

                        <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        <tr height="50">
                        <td width="179" style="padding-left:30px; font-size:14px;" align="right">待收利息金额：</td>
                        <td width="142"><span style="color:#f13131; font-size:14px;">￥0.00</span></td>
                        <td width="372">（已经投资，尚未回收的利息总额）</td>
                        </tr> -->
--}}

                        <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        <tr height="50">
                            <td width="189" style="padding-left:0px; font-size:14px;" align="right">待确认提现：</td>
                            <td width="142"><span style="color:#f13131; font-size:14px;">￥<?php echo $daiwithdrawal; ?></span></td>
                            <td width="372">（您申请提现中的金额）</td>
                        </tr>
                        <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        <tr height="50">
                            <td width="189" style=" font-size:14px;" align="right">待确认充值：</td>
                            <td width="142"><span style="color:#f13131; font-size:14px;">￥<?php echo $dairecharge; ?></span></td>
                            <td width="372">（等待确认的线下充值金额）</td>
                        </tr>
                        <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        <tr height="50">
                            <td width="189" style=" font-size:14px;" align="right">优惠收入总额：</td>
                            <td width="142"><span style="color:#f13131; font-size:14px;">￥<?php echo $youhuirecharge; ?></span></td>
                            <td width="372">（可以用来直接投标的金额）</td>
                        </tr>
{{--                        <!-- <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        <tr height="50">
                        <td width="179" style="padding-left:30px; font-size:14px;" align="right">冻结资金：</td>
                        <td width="142"><span style="color:#f13131; font-size:14px;">￥0.00</span></td>
                        <td width="372"></td>
                        </tr> -->--}}
                        <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:2px;"></div></td></tr>
                        <tr height="50">
                            <td width="189" style=" font-size:14px;" align="right">账户资金总额：</td>
                            <td width="142"><span style="color:#f13131; font-size:14px;">￥<?php echo $Memberamounts; ?></span></td>
                            <td width="372">（您在<?php echo \Cache::get('CompanyLong'); ?>平台上现有现金资产的总额）</td>
                        </tr>
                        <tr><td colspan="3"><p style="margin:15px;">账户资产总额 = 可用现金金额 + 待收本金金额 + 待确认提现 + 待确认充值 + 冻结资金</p></td></tr>
                        </tbody></table></td>
            </tr>
            </tbody></table>

        <?php
        //累计充值金额
        $leijirecharge=\App\Memberrecharge::where("userid",$Member->id)->whereIn("status",["0","1"])->sum("amount");
        //累计提现
        $leijiwithdrawal= \App\Memberwithdrawal::where("userid",$Member->id)->whereIn("status",["0","1"])->sum("amount");
        //投资金额
          $leijibuys=  \App\Productbuy::where("userid",$Member->id)->sum("amount");

        ?>
        <table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" style="margin-top:10px;" height="254">
            <tbody><tr>
                <td bgcolor="#f5f5f5" height="34" style="padding-left:5px;font-size:13px; line-height:34px;border:#e6e6e6 solid 1px;"><span style="width: 5px;height: 20px;background-color: #0697DA;float: left;margin:7px;display: block;"></span>资金流量</td>
            </tr>
            <tr>
                <td style="border-right:#e6e6e6 solid 1px;border-bottom:#e6e6e6 solid 1px;border-left:#e6e6e6 solid 1px; padding:15px;">
                    <table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" height="171">
                        <tbody><tr height="50">
                            <td width="189" style=" font-size:14px;" align="right">累计投资金额：</td>
                            <td width="142"><span style="color:#f13131; font-size:14px;">￥<?php echo $leijibuys; ?></span></td>
                            <td width="372">（注册至今，您账户投资资金总和）</td>
                        </tr>
                        <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        <tr height="50">
                            <td width="189" style=" font-size:14px;" align="right">累计充值金额：</td>
                            <td width="142"><span style="color:#f13131; font-size:14px;">￥<?php echo $leijirecharge; ?></span></td>
                            <td width="372">（注册至今，您账户累计充值总额）</td>
                        </tr>
                        <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        <tr height="50">
                            <td width="189" style=" font-size:14px;" align="right">累计提现金额：</td>
                            <td width="142"><span style="color:#f13131; font-size:14px;">￥<?php echo $leijiwithdrawal; ?></span></td>
                            <td width="372">（注册至今，您账户累计提现总额）</td>
                        </tr>
                        <tr><td align="center" colspan="3"><div style="background:#e6e6e6; width:98%; height:1px;"></div></td></tr>
                        </tbody></table></td>
            </tr>
            </tbody></table>
    </div>
    </div>



@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

