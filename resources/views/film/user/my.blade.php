@extends(env('WapTemplate').'.wap')

@section("header")
    <header class="blackHeader"><a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/whiteBack.png")}}" class="left backImg"></a><span class="headerTitle">账户详情</span></header>

@endsection

@section("js")
    @parent
@endsection

@section("css")

    @parent


@endsection

@section("onlinemsg")
    @parent
@endsection

@section('body')

    <?php

    $withdrawals= \App\Memberwithdrawal::where("userid",$Member->id)->where("status","0")->sum("amount");
    //总投资额
    $buyamounts=  \App\Productbuy::where("userid",$Member->id)->sum("amount");
    $recharges= \App\Memberrecharge::where("userid",$Member->id)->where("status","1")->sum("amount");
    $youhui= \App\Memberrecharge::where("userid",$Member->id)->where("status","1")->where("type","优惠活动")->sum("amount");

    //总投资额
    $buyamounts=  \App\Productbuy::where("userid",$Member->id)->sum("amount");

    //已投项目
    $buycounts=  \App\Productbuy::where("userid",$Member->id)->count();

    //投资收益
    $moneylog_moneys= \App\Moneylog::where("moneylog_userid",$Member->id)->where("moneylog_type","项目分红")->sum("moneylog_money");


    //结束项目
    $buyjscounts=  \App\Productbuy::where("userid",$Member->id)->where("status","0")->count();

    $xiaxians=  \App\Member::where("inviter",$Member->invicode)->count();

    //本金回收
    $buyjsamounts=  \App\Productbuy::where("userid",$Member->id)->where("status","1")->sum("amount");

    $Dlist=\App\Productbuy::where("userid",$Member->id)->where("status","1")->get();
    $Ylist=\App\Productbuy::where("userid",$Member->id)->whereIn("status",["0","1"])->get();


    $Dmoneys=0;
    $Ymoneys=0;

    $Products= \App\Product::get();
    foreach ($Products as $Product){
        $Products[$Product->id]=$Product;
    }


    foreach ($Dlist as $item){
        //$item->rate=isset($this->Memberlevels[$item->level])?$this->Memberlevels[$item->level]->rate:'';
        if(isset($Products[$item->productid])){
            if($Products[$item->productid]->hkfs == 0){
                $moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100;
                //$item->moneyCount= round($moneyCount,2);
            }else{
                $moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100*($item->sendday_count-$item->useritem_count);
                //$moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100*$Products[$item->productid]->shijian;
                //$item->moneyCount= round($moneyCount,2);
            }
            $Dmoneys+=$moneyCount;

            /*if($this->Products[$item->productid]->hkfs == 0){
                $elseMoney = $item->rate * $item->amount/100;
                $item->elseMoney= round($elseMoney,2);
            }else{
                $elseMoney = $item->rate * $item->amount/100*$this->Products[$item->productid]->shijian;
                $item->elseMoney= round($elseMoney,2);
            }*/

        }



    }

    foreach ($Ylist as $item){
        if(isset($Products[$item->productid])){
            if($Products[$item->productid]->hkfs == 0){
                $moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100;
                //$item->moneyCount= round($moneyCount,2);
            }else{
                $moneyCount = $Products[$item->productid]->jyrsy * $item->amount/100*$item->useritem_count;
                //$item->moneyCount= round($moneyCount,2);
            }

            $Ymoneys+=$moneyCount;
        }



    }


    ?>

    <div class="financeTop">
        <div class="max">
            <p class="financeDetail">账户总额(元)<br>
                <span class="earningsNum">¥<?php echo sprintf("%.2f",$Member->amount+$Member->is_dongjie); ?></span>
            </p>
            <p class="financeDetail">可用金额(元)<br>
                <span class="earningsNum">¥<?php echo sprintf("%.2f",$Member->amount); ?></span>
            </p>
        </div>
    </div>

    <div class="max">
        <div class="financeDetail">
            <p>待收利息（元）</p>
            <p>¥<?php echo sprintf("%.2f",$Dmoneys); ?></p>
        </div>
        <div class="financeDetail">
            <p>已收利息（元）</p>
            <p>¥<?php echo sprintf("%.2f",$Ymoneys); ?></p>
        </div>
        <div class="financeDetail">
            <p>待收本金（元）</p>
            <p>¥<?php echo sprintf("%.2f",$buyjsamounts); ?></p>
        </div>
        <div class="financeDetail">
            <p>正在提现（元）</p>
            <p>¥<?php echo sprintf("%.2f",$withdrawals); ?></p>
        </div>
        <div class="clear"></div>
    </div>





@endsection


@section("footbox")
    @parent
@endsection

@section("footer")
    @parent
@endsection

