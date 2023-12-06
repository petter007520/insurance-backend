@extends(env('WapTemplate').'.wap')

@section("header")

    <header>
        <a href="javascript:history.go(-1);"><img src="{{asset("mobile/film/images/back.png")}}" class="left backImg"></a>
        <span class="headerTitle">注册协议</span>

    </header>

@endsection

@section("js")
    @parent

@endsection

@section("css")

    @parent






@endsection



@section('body')


    <div class="investTop">

        <div class="instru">网站注册服务协议</div>
        <div class="line">

            <div class="baseInfo">

                <h3>特别提示：</h3>
                <p>（1）<?php echo \Cache::get('CompanyLong'); ?>（网址：<?php echo \Cache::get('domain'); ?>）（下称“本网站”或“平台”）由（下称“<?php echo \Cache::get('CompanyLong'); ?></p>
                <p>（2）请您在注册成为本网站用户前务必仔细阅读并决定是否同意以下条款。您现在可以选择：阅读本协议并决定是否继续下一步操作或者不阅读直接退出，阅读后表示同意或不同意，自行决定在本网站注册或不注册；但是，若您一旦在本网站进行注册，则视为您已阅读本协议并同意全部条款、接受本网站的服务，从而受本协议全部条款的约束。若您不同意接受以下条款，您将不能得到本网站提供的服务。</p>
                <p>（3）请您确认您为已年满18周岁且具有完成民事行为能力的自然人后再进行注册；否则，您将不能得到本网站提供的服务，如您进行注册由此可能引发相关行为不具有法律效力的后果且由于您的行为造成本网站或他人财产或人身权益损害或合同权益落空，均应由您的监护人负责并承担全部赔偿责任。</p>
                <p>（4）当您在注册成为本网站用户时，您即已明确地做出意思表示确认您的注册行为和通过本网站进行的交易及与本网站之间的全部行为，为您真实意愿的本人行为，且不是代表任何他人、组织、机构、法人的代理行为。</p>
                <p>第一章使用规则</p>
                <p>第一条在使用本网站服务前，您必须先按照本网站要求完成注册，成为本网站注册用户。</p>
                <p>第二条为了保障您的权益，您在自愿注册使用本网站服务前，必须仔细阅读并充分理解知悉本协议所有条款。一经注册或使用本网站服务即视为对本协议的充分理解和接受，如有违反而导致任何法律后果的发生，您将以自己的名义独立承担相应的法律责任。</p>
                <p>第三条在本协议履行过程中，<?php echo \Cache::get('CompanyLong'); ?>可根据情况对本协议进行修改。一旦本协议的内容发生变动，<?php echo \Cache::get('CompanyLong'); ?>将通过本网站公布最新的服务协议，不再向注册用户作个别通知。如果用户不同意<?php echo \Cache::get('CompanyLong'); ?>对本协议所做的修改，用户应停止使用本网站服务。如果用户继续使用本网站服务，则视为用户接受<?php echo \Cache::get('CompanyLong'); ?>对本协议所做的修改，并应遵照修改后的协议执行。</p>
                <p>第四条<?php echo \Cache::get('CompanyLong'); ?>对于注册用户的通知及任何其他的协议、告示或其他关于用户使用会员账户及服务的通知，用户同意<?php echo \Cache::get('CompanyLong'); ?>可通过本网站公告、电子邮件、手机短信、无线通讯装置等电子方式或常规的信件传递等方式进行，该等通知于发送之日视为已送达收件人。因信息传输等原因导致用户未在前述通知发出当日收到该等通知的，<?php echo \Cache::get('CompanyLong'); ?>不承担责任。</p>
                <p>第五条<?php echo \Cache::get('CompanyLong'); ?>可以依其判断暂时停止提供、限制或改变本网站服务，只要用户仍然使用本网站服务，即表示用户仍然同意本协议。</p>
                <p>第二章注册用户</p>
                <p>第六条本网站的注册用户是指年满18周岁并符合中华人民共和国法律规定的具有完全民事权利和民事行为能力，能够独立承担民事责任的自然人。</p>
                <p>第七条注册用户承诺以下事项：</p>
                <p>1. 用户必须依本网站要求提示提供其本人真实、最新、有效及完整的资料。</p>
                <p>2. 用户有义务维持并更新其本人的资料，确保其为合法、真实、有效、最新及完整。若用户提供任何错误、虚假、过时或不完整的资料，或者本网站依其独立判断怀疑资料为错误、虚假、过时或不完整，本网站有权暂停或终止用户的会员账户，并拒绝用户使用本网站服务的部分或全部功能。在此情况下，<?php echo \Cache::get('CompanyLong'); ?>不承担任何责任，并且用户同意负担因此所产生的直接或间接的任何支出或损失。</p>
                <p>3. 如因用，导致本网户未及时更新基本资料站服务无法提供或提供时发生任何错误，用户不得将此作为取消交易或拒绝付款的理由，<?php echo \Cache::get('CompanyLong'); ?>亦不承担任何责任，所有后果应由用户承担。</p>
                <p>第三章本网站服务的内容</p>
                <p>第八条<?php echo \Cache::get('CompanyLong'); ?>将通过本网站为注册用户提供如下交易管理服务：</p>
                <p>1. 用户在本网站进行注册时将生成用户账户，注册用户账户将记载用户在本网站的活动，上述注册用户账户是用户登录本网站的唯一账户。</p>
                <p>2. 用户保证并承诺通过本网站进行交易的资金来源合法。</p>
                <p>3. 用户确认，其在本网站上按本网站服务流程所确认的交易状态以及用户通过其注册用户账户向本网站发起指令时，将成为本网站为注册用户进行相关交易或操作（包括但不限于支付或收取款项、冻结资金、订立协议等）的不可撤销的指令并被视为明确的意思表示。因此，用户应确保并自行负责始终由用户本人对其用户账户进行登录、使用及操作。用户同意相关指令的执行时间以本网站在平台中进行实际操作的时间为准。用户同意本网站有权依据本协议或/及本网站相关纠纷处理规则等约定对相关事项进行处理。
                    用户未能及时对交易状态进行修改、确认或未能提交相关申请所引起的任何纠纷或损失由用户自行负责，<?php echo \Cache::get('CompanyLong'); ?>不承担任何责任。</p>
                <p>4. 用户了解<?php echo \Cache::get('CompanyLong'); ?>并不是银行或金融机构，按照中国法律规定，<?php echo \Cache::get('CompanyLong'); ?>不提供任何形式的资金管理及划付服务，亦不对用户在本网站有关操作所引起的第三方支付资金托管机构所代表用户、为用户利益或为有关交易目的做出的资金管理或划付等行为的结果、时效性及有效性承担任何责任。</p>
                <p>5. 用户通过本网站进行各项交易或接受交易款项时，若用户未遵从本协议条款或本网站公布的交易规则中的操作指示，<?php echo \Cache::get('CompanyLong'); ?>不承担任何责任。若发生上述状况而款项已先行拨入用户账户下或已汇入用户的银行账户，用户同意立即从相关用户账户中返还款项及放弃要求支付此笔款项之权利，并承担由此产生的费用。</p>
                <p>6. <?php echo \Cache::get('CompanyLong'); ?>有权基于交易安全等方面的考虑不时设定涉及交易的相关事项，包括但不限于交易限额、交易次数等，用户了解<?php echo \Cache::get('CompanyLong'); ?>的前述设定可能会对交易造成一定影响，对此没有异议。</p>
                <p>7. 如果<?php echo \Cache::get('CompanyLong'); ?>发现了因系统故障或其他任何原因导致的处理错误，无论有利于<?php echo \Cache::get('CompanyLong'); ?>还是有利于注册用户，<?php echo \Cache::get('CompanyLong'); ?>都有权纠正该错误。如果该错误导致用户实际收到的款项多于应获得的金额，则无论错误的性质和原因，用户应根据<?php echo \Cache::get('CompanyLong'); ?>向其发出的有关纠正错误的通知的具体要求返还多收的款项或进行其他操作。用户理解并同意，用户因前述处理错误而多付或少付的款项均不计利息，<?php echo \Cache::get('CompanyLong'); ?>不承担因前述处理错误而导致的任何损失或责任（包括注册用户可能因前述错误导致的利息、汇率等损失）。</p>
                <p>第九条<?php echo \Cache::get('CompanyLong'); ?>将通过本网站及本网站合作的联动优势电子商务有限公司（“第三方支付资金托管机构”）为注册用户提供如下客户服务：</p>
                <p>1. 银行卡认证：为使用<?php echo \Cache::get('CompanyLong'); ?>委托的第三方支付机构提供的充值、提现、结算等服务，用户应按照本网站规定的流程提交以其本人名义登记的有效银行借记卡等信息，经由<?php echo \Cache::get('CompanyLong'); ?>及第三方支付机构审核通过后，本网站会将注册用户的账户与前述银行账户进行绑定。如用户未按照本网站规定提交相关信息或提交的信息错误、虚假、过时或不完整，或者<?php echo \Cache::get('CompanyLong'); ?>有合理的理由怀疑用户提交的信息为错误、虚假、过时或不完整，<?php echo \Cache::get('CompanyLong'); ?>有权拒绝为用户提供银行卡认证服务，用户因此未能使用充值、取现、结算等服务而产生的损失自行承担。注册用户的账户与银行卡的绑定，需要使用用户注册时填写的手机号码来完成。如用户在使用本网站服务期间注销、更换、遗失手机号码，可能造成其账户内注册用户个人信息的泄露，或导致其账户不能正常使用，由此产生的一切后果和损失，将由注册用户个人承担，本网站对此不承担任何责任。</p>
                <p>2. 第三方资金托管：第三方支付机构为<?php echo \Cache::get('CompanyLong'); ?>注册用户提供独立的第三方资金托管账户保证其资金独立性，与<?php echo \Cache::get('CompanyLong'); ?>平台的自有资金相互隔离。</p>
                <p>3. 第三方资金托管账户：是第三方支付机构</p>
                <p>4. 充值：您可以通过平台账户发起指令并使用第三方支付机构提供网银支付方式，充值完成即视为您授权将充值资金交付给第三方支付机构代为保管。第三方支付机构将根据其在本网站公布的收费标准向注册用户收取一定的充值手续费。本网站为注册用户先行垫付前述充值手续费，如果用户账户中存在未经投资的充值金额，在用户提现时，网站将优先提取这部分金额，并有权将这部分金额所对应的充值手续费予以扣除。本网站有权随时决定终止为注册用户先行垫付充值手续费。</p></p>
                <p>5. 提现服务：注册用户可以要求第三方支付机构退返第三方支付机构代管的用户可支配款项，用户必须提供一个与注册用户的名称一致的有效的中国大陆银行账户，当注册用户通过平台账户向第三方支付机构发起提现指令时，将于收到指令后的一至五个工作日内，将相应的款项转入注册用户提供的有效银行账户（根据注册用户提供的银行不同，会产生转入时间上的差异）。注册用户将按照第三方支付机构收费标准承担一定的提现费用。</p>
                <p>6. 查询：<?php echo \Cache::get('CompanyLong'); ?>将对注册用户在本网站的所有操作进行记录，不论该操作之目的最终是否实现。用户可以通过其会员账户实时查询会员账户名下的交易记录和账户内资金状况。用户理解并同意用户最终收到款项的服务是由用户经过认证的银行卡对应的银行提供的，需向该银行请求查证。用户理解并同意通过本网站查询的任何信息仅作为参考，不作为相关操作或交易的证据或依据；如该等信息与本网站记录存在任何不一致，应以本网站平台上提供的书面记录为准。</p>
                <p>7. 注册用户了解，上述充值、及提现服务涉及<?php echo \Cache::get('CompanyLong'); ?>与银行、担保方、第三方支付机构等第三方的合作。注册用户同意：</p>
                <p>(a) 受银行、第三方支付机构等有关方仅在工作日进行资金代扣及划转的现状等各种原因所限，<?php echo \Cache::get('CompanyLong'); ?>不对前述服务的资金到账时间做任何承诺，也不承担与此相关的责任，包括但不限于由此产生的利息、货币贬值等损失；</p>
                <p>(b) 一经注册用户使用前述服务，即表示用户不可撤销地授权<?php echo \Cache::get('CompanyLong'); ?>进行相关操作，且该等操作是不可逆转的，注册用户不能以任何理由拒绝付款或要求取消交易。就前述服务，用户应按照银行、担保方（如有）、第三方支付机构等有关方的规定向其支付费用，具体请见该等机构网站的相关信息或用户与其签订的相关协议。用户与银行、担保方（如有）、第三方支付机构等有关方之间就费用支付事项产生的争议或纠纷，与<?php echo \Cache::get('CompanyLong'); ?>无关。</p>
                <p>8. 用户应自行负责其在每次使用本网站服务时应通过安全的计算机及网络环境直接登录本网站。本网站不建议用户通过邮件或其他网站提供的链接登录，且<?php echo \Cache::get('CompanyLong'); ?>对用户使用了不安全的计算机、计算设备、移动设备或网络环境而导致的任何损失均不承担责任，无论用户是否知晓该等不安全状态或是否有意使用。尽管如此，如用户使用了不安全的计算机、计算设备、移动设备或网络环境而导致<?php echo \Cache::get('CompanyLong'); ?>或任何其他方受到任何损失，<?php echo \Cache::get('CompanyLong'); ?>有权要求用户赔偿该等损失、或协助受到损失的一方要求用户赔偿该等损失、或为该等索赔主张提供有关用户信息或交易信息。</p>
                <p>9. 用户同意，本网站有权在提供服务过程中以各种方式投放各种商业性广告或其他任何类型的商业信息（包括但不限于在本网站的任何页面上投放广告），并且，用户同意接受本网站通过电子邮件或其他方式向注册用户发送商品促销或其他相关商业信息。</p>
                <p>第十条<?php echo \Cache::get('CompanyLong'); ?>将通过本网站为注册用户提供如下合同管理服务：</p>
                <p>1. 用户使用会员账户登录本网站后，根据本网站的相关规则，以会员账户ID在本网站通过点击确认或类似方式签署的电子合同即视为用户本人真实意愿并以用户本人名义签署的合同，具有法律效力。用户有义务妥善保管自己的账户密码等账户信息，并应确保并自行负责始终由用户本人对其用户账户进行登录、使用及操作。用户通过前述方式订立的电子合同对合同各方具有法律约束力，用户不得以其账户密码等账户信息被盗用或其他理由否认已订立的合同的效力或不按照该等合同履行相关义务。</p>
                <p>2. 用户根据本协议以及本网站的相关规则签署电子合同后，不得擅自修改该合同。本网站向用户提供电子合同的备案、查看、核对服务，如对电子合同真伪或电子合同的内容有任何疑问，用户可通过使用本网站查询并进行核对。如对此有任何争议，应以本网站平台上记录的合同为准。</p>
                <p>3. 用户不得私自仿制、伪造在本网站上签订的电子合同或印章，不得用伪造的合同进行招摇撞骗或进行其他非法使用，否则由用户自行承担责任。</p>
                <p>第十一条除外责任</p>
                <p>1. 在任何情况下，对于注册用户使用本网站服务过程中涉及由第三方提供相关服务的责任由该第三方承担，<?php echo \Cache::get('CompanyLong'); ?>不承担该等责任。<?php echo \Cache::get('CompanyLong'); ?>不承担责任的情形包括但不限于：</p>
                <p>(a) 因银行、第三方支付机构等第三方未按照注册用户和/或本网站指令进行操作引起的任何损失或责任；</p>
                <p>(b) 因银行、第三方支付机构等第三方原因导致资金未能及时到账或未能到账引起的任何损失或责任；</p>
                <p>(c) 因银行、第三方支付机构等第三方对交易限额或次数等方面的限制而引起的任何损失或责任；</p>
                <p>(d) 因其他第三方的行为或原因导致的任何损失或责任；</p>
                <p>(e) 因他人实施网络诈骗、黑客攻击等违法犯罪导致用户的任何损失。</p>
                <p>2. 因用户自身的原因导致的任何损失或责任，由其自行负责，<?php echo \Cache::get('CompanyLong'); ?>不承担责任。<?php echo \Cache::get('CompanyLong'); ?>不承担责任的情形包括但不限于：</p>
                <p>(a) 用户未按照本协议或本网站不时公布的任何规则进行操作导致的任何损失或责任；</p>
                <p>(b) 因用户使用的银行卡的原因导致的损失或责任，包括用户使用未经认证的银行卡或使用非用户本人的银行卡或使用信用卡，用户的银行卡被冻结、挂失等导致的任何损失或责任；</p>
                <p>(c) 用户向本网站发送的指令信息不明确、或存在歧义、不完整等导致的任何损失或责任；</p>
                <p>(d) 用户账户内余额不足导致的任何损失或责任；</p>
                <p>(e) 其他因用户原因导致的任何损失或责任。</p>
                <p>第四章风险提示</p>
                <p>第十二条注册用户了解并认可，任何通过本网站进行的交易并不能避免以下风险的产生，<?php echo \Cache::get('CompanyLong'); ?>不能也没有义务为如下风险负责：</p>
                <p>1. 宏观经济风险：因宏观经济形势变化，可能引起价格等方面的异常波动，用户有可能遭受损失；</p>
                <p>2. 政策风险：有关法律、法规及相关政策、规则发生变化，可能引起价格等方面异常波动，用户有可能遭受损失；</p>
                <p>3. 利率风险：市场利率变化可能对购买或持有产品的实际收益产生影响；</p>
                <p>4. 不可抗力因素导致的风险；</p>
                <p>5. 因用户的过错导致的任何损失，该过错包括但不限于：决策失误、操作不当、遗忘或泄露密码、密码被他人破解、用户使用的计算机系统被第三方侵入、用户委托他人代理交易时他人恶意或不当操作而造成的损失。</p>
                <p>第十三条上述并不能揭示注册用户通过本网站进行交易的全部风险及市场的全部情形。用户在做出交易决策前，应全面了解相关交易，谨慎决策。</p>
                <p>第五章服务费用</p>
                <p>第十四条当注册用户使用本网站服务时，<?php echo \Cache::get('CompanyLong'); ?>可能会向用户收取相关服务费用。各项服务费用详见用户使用本网站服务时在网站上所列之收费方式说明或与用户签订的相关协议。<?php echo \Cache::get('CompanyLong'); ?>保留单方面制定及调整服务费用的权利。</p>
                <p>第十五条注册用户在使用本网站服务过程中（如充值或取现等）可能需要向第三方（如银行或第三方支付公司、担保方等）支付一定的费用。</p>
                <p>第六章账户安全及管理</p>
                <p>第十六条注册用户了解并同意，确保用户账户及密码的机密安全是用户的责任。用户将对利用该会员账户及密码所进行的一切行动及言论，负完全的责任，并同意以下事项：</p>
                <p>1. 用户不对其他任何人泄露账户或密码，亦不可使用其他任何人的账户或密码。<?php echo \Cache::get('CompanyLong'); ?>不对因如下原因导致的用户的平台账户遭他人非法使用及因此造成的损失承担责任，包括但不限于：（1）因黑客或病毒攻击；（2）用户的保管疏忽；（3）户使用了不安全的计算机、计算设备、移动设备或网络环境，无论用户是否知晓该等不安全状态或是否有意使用；及（4）其他并非本网站故意或过失的情形。</p>
                <p>2. 本网站通过用户的会员账户及密码来识别用户的指令，用户确认，使用用户账户和密码登录后在本网站的一切行为均代表用户本人。用户的平台账户操作所产生的电子信息记录均为注册用户行为的有效凭据，并由用户本人承担由此产生的全部责任。</p>
                <p>3. 冒用他人账户及密码的，<?php echo \Cache::get('CompanyLong'); ?>保留追究实际使用人连带责任的权利。</p>
                <p>4. 用户应根据本网站的相关规则以及本网站的相关提示创建一个安全密码，应避免选择过于明显的单词或日期。</p>
                <p>第十七条注册用户如发现有第三人冒用或盗用用户账户及密码，或其他任何未经合法授权的情形，应立即以有效方式通知本网站，要求本网站暂停相关服务，否则由此产生的一切责任由用户本人承担。同时，用户理解本网站对用户的请求采取行动需要合理期限，在此之前，<?php echo \Cache::get('CompanyLong'); ?>对第三人使用该服务所导致的损失不承担任何责任。</p>
                <p>第十八条<?php echo \Cache::get('CompanyLong'); ?>有权基于单方独立判断，在其认为可能发生危害交易安全等情形时，不经通知而先行暂停、中断或终止向注册用户提供本协议项下的全部或部分会员服务（包括收费服务），并将注册资料从<?php echo \Cache::get('CompanyLong'); ?>平台上移除或删除，且无需对用户或任何第三方承担任何责任。前述情形包括但不限于：</p>
                <p>1. 本网站认为用户提供的个人资料不具有真实性、有效性或完整性；</p>
                <p>2. 本网站发现异常交易或有疑义或有违法之虞时；</p>
                <p>3. 本网站认为用户账户涉嫌洗钱、套现、传销、被冒用或其他本网站认为有风险之情形；</p>
                <p>4. 本网站认为用户已经违反本协议中规定的各类规则及精神；</p>
                <p>5. 用户在使用本网站收费服务时未按规定向本网站支付相应的服务费用；</p>
                <p>6. 用户账户已连续两年内未实际使用且账户中余额为零；</p>
                <p>7. 本网站基于交易安全等原因，根据其单独判断需先行暂停、中断或终止向用户提供本协议项下的全部或部分会员服务（包括收费服务），并将注册资料移除或删除的其他情形。</p>
                <p>第十九条注册用户同意在必要时，本网站无需进行事先通知即有权终止提供会员账户服务，并可能立即暂停、关闭或删除注册用户账户及该会员账户中所有相关资料及档案。</p>
                <p>第二十条注册用户同意，会员账户的暂停、中断或终止不代表用户责任的终止，用户仍应对使用本网站服务期间的行为承担可能的违约或损害赔偿责任，同时本网站仍可保有用户的相关信息。</p>
                <p>第七章用户承诺</p>
                <p>第二十一条注册用户承诺绝不为任何非法目的或以任何非法方式使用本网站服务，并承诺遵守中国相关法律、法规及一切使用互联网之国际惯例，遵守所有与本网站服务有关的网络协议、规则和程序。</p>
                <p>第二十二条注册用户同意并保证不得利用本网站服务从事侵害他人权益或违法之行为，若有违反者应负所有法律责任。上述行为包括但不限于：</p>
                <p>1. 反对宪法所确定的基本原则，危害国家安全、泄漏国家秘密、颠覆国家政权、破坏国家统一的；</p>
                <p>2. 侵害他人名誉、隐私权、商业秘密、商标权、著作权、专利权、其他知识产权及其他权益；</p>
                <p>3. 违反依法律或合约所应负之保密义务；</p>
                <p>4. 冒用他人名义使用本网站服务；</p>
                <p>5. 从事任何不法交易行为，如贩卖枪支、毒品、禁药、盗版软件或其他违禁物；</p>
                <p>6. 提供赌博资讯或以任何方式引诱他人参与赌博；</p>
                <p>7. 涉嫌洗钱、套现或进行传销活动的；</p>
                <p>8. 从事任何可能含有电脑病毒或是可能侵害本网站服务系統、资料等行为；</p>
                <p>9. 利用本网站服务系统进行可能对互联网或移动网正常运转造成不利影响之行为；</p>
                <p>10. 侵犯本网站的商业利益，包括但不限于发布非经本网站许可的商业广告；</p>
                <p>11. 利用本网站服务上传、展示或传播虚假的、骚扰性的、中伤他人的、辱骂性的、恐吓性的、庸俗淫秽的或其他任何非法的信息资料；</p>
                <p>12. 其他本网站有正当理由认为不适当之行为。</p>
                <p>第二十三条<?php echo \Cache::get('CompanyLong'); ?>保有依其单独判断删除本网站内各类不符合法律政策或不真实或不适当的信息内容而无须通知会员的权利，并无需承担任何责任。若用户未遵守以上规定的，<?php echo \Cache::get('CompanyLong'); ?>有权作出独立判断并采取暂停或关闭会员账户等措施，而无需承担任何责任。</p>
                <p>第二十四条注册用户同意，由于用户违反本协议，或违反通过援引并入本协议并成为本协议一部分的文件，或由于用户使用本网站服务违反了任何法律或第三方的权利而造成任何第三方进行或发起的任何补偿申请或要求（包括律师费用），用户会对<?php echo \Cache::get('CompanyLong'); ?>及其关联方、合作伙伴、董事以及雇员给予全额补偿并使之不受损害。</p>
                <p>第二十五条注册用户承诺，其通过本网站上传或发布的信息均真实有效，其向本网站提交的任何资料均真实、有效、完整、详细、准确。如因违背上述承诺，造成<?php echo \Cache::get('CompanyLong'); ?>或其他方损失的，用户将承担相应责任。</p>
                <p>1. 用户承诺其通过注册等方式向本网站提供的用户电子邮箱信息，均真实、有效。</p>
                <p>2. 用户承诺其应随时通过登录本网站及查阅其电子邮箱，本网站对于全部通知事项均通过本网站或用户电子邮箱进行告知，不再通过其他方式联络或通知用户。</p>
                <p>3. 如因用户提供的电子邮箱等信息有误、或者用户未及时登记网站、查阅其电子信箱，所告知用户未获知相关事项，由此造成用户损失均由用户自行承担，本网站不承担任何责任。</p>
                <p>第八章服务中断或故障</p>
                <p>第二十六条注册用户同意，基于互联网的特殊性，<?php echo \Cache::get('CompanyLong'); ?>不保证本网站服务不会中断，也不担保服务的及时性和/或安全性。若因系统相关状况无法正常运作，使用户无法使用任何本网站服务或使用任何本网站服务时受到任何影响时，<?php echo \Cache::get('CompanyLong'); ?>对注册用户或第三方不负任何责任，前述状况包括但不限于：</p>
                <p>1. 本网站系统停机维护期间；</p>
                <p>2. 电信设备出现故障不能进行数据传输的；</p>
                <p>3. 由于黑客攻击、网络供应商技术调整或故障、网站升级、银行方面的问题等原因而造成的本网站服务中断或延迟；</p>
                <p>4. 因台风、地震、海啸、洪水、停电、战争、恐怖袭击等不可抗力之因素，造成本网站系统障碍不能执行业务的。</p>
                <p>第九章责任范围及限制</p>
                <p>第二十七条<?php echo \Cache::get('CompanyLong'); ?>未对任何本网站服务提供任何形式的保证，包括但不限于以下事项：</p>
                <p>1. 本网站服务将符合用户的需求；</p>
                <p>2. 本网站服务将不受干扰、及时提供或免于出错；</p>
                <p>3. 用户经由本网站服务购买或取得之任何产品、服务、资讯或其他资料将符合用户的期望。</p>
                <p>第二十八条用户自<?php echo \Cache::get('CompanyLong'); ?>及<?php echo \Cache::get('CompanyLong'); ?>工作人员或经由本网站服务取得的建议或资讯，无论其为书面或口头，均不构成<?php echo \Cache::get('CompanyLong'); ?>对本网站服务的任何保证。</p>
                <p>第二十九条在法律允许的情况下，<?php echo \Cache::get('CompanyLong'); ?>对于与本协议有关或由本协议引起的，或者，由于使用本网站、或由于其所包含的或以其它方式通过本网站提供给用户的全部信息、内容、材料、产品（包括软件）和服务、或购买和使用产品引起的任何间接的、惩罚性的、特殊的、派生的损失（包括但不限于业务损失、收益损失、利润损失、使用数据或其他经济利益的损失），不论是如何产生的，也不论是由对本协议的违约（包括违反保证）还是由侵权造成的，均不负有任何责任，即使其事先已被告知此等损失的可能性。另外即使本协议规定的排他性救济没有达到其基本目的，也应排除<?php echo \Cache::get('CompanyLong'); ?>对上述损失的责任。</p>
                <p>第十章隐私保护</p>
                <p>第三十条<?php echo \Cache::get('CompanyLong'); ?>对于注册用户提供的、<?php echo \Cache::get('CompanyLong'); ?>自行收集的、经认证的个人信息将按照本协议予以保护、使用或者披露。<?php echo \Cache::get('CompanyLong'); ?>无需用户同意即可向<?php echo \Cache::get('CompanyLong'); ?>关联实</p>体转让与本网站有关的全部或部分权利和义务。未经本网站事先书面同意，用户不得转让其在本协议项下的任何权利和义务。
                <p>第三十一条<?php echo \Cache::get('CompanyLong'); ?>可能自公开及私人资料来源收集注册用户的额外资料，以更好地掌握用户情况，提升本网站服务、解决争议并有助确保在本网站进行安全交易。</p>
                <p>第三十二条<?php echo \Cache::get('CompanyLong'); ?>按照注册用户在本网站的行为自动追踪观测用户的某些资料。在不透露用户的隐私资料的前提下，<?php echo \Cache::get('CompanyLong'); ?>有权对整个会员数据库进行分析并对会员数据库进行商业上的利用。</p>
                <p>第三十三条注册用户同意，<?php echo \Cache::get('CompanyLong'); ?>可在本网站的某些网页上使用资料收集装置。</p>
                <p>第三十四条注册用户同意<?php echo \Cache::get('CompanyLong'); ?>可使用关于用户的相关资料（包括但不限于<?php echo \Cache::get('CompanyLong'); ?>持有的有关用户的档案中的资料，<?php echo \Cache::get('CompanyLong'); ?>从用户目前及以前在本网站上的活动所获取的其他资料以及<?php echo \Cache::get('CompanyLong'); ?>通过其他方式自行收集的资料）以解决争议、对纠纷进行调停。用户同意<?php echo \Cache::get('CompanyLong'); ?>可通过人工或自动程序对注册用户的资料进行评价。</p>
                <p>第三十五条<?php echo \Cache::get('CompanyLong'); ?>采用行业标准惯例以保护注册用户的资料。用户因履行本协议提供给本网站的信息，本网站不会恶意出售或免费共享给任何第三方，以下情况除外：</p>
                <p>1. 提供独立服务且仅要求服务相关的必要信息的供应商，如印刷厂、邮递公司等；</p>
                <p>2. 具有合法调阅信息权限并从合法渠道调阅信息的政府部门或其他机构，如公安机关、法院；</p>
                <p>3. <?php echo \Cache::get('CompanyLong'); ?>的关联企业。</p>
                <p>第三十六条<?php echo \Cache::get('CompanyLong'); ?>有义务根据有关法律要求向司法机关和政府部门提供您的个人资料。在注册用户未能按照与<?php echo \Cache::get('CompanyLong'); ?>签订的协议或者与本网站其他用户签订的协议等其他法律文本的约定履行自己应尽的义务时，<?php echo \Cache::get('CompanyLong'); ?>有权根据自己的判断，或者与该笔交易有关的其他用户的请求披露用户的个人信息和资料，并做出评论。您严重违反本网站的相关规则<?php echo \Cache::get('CompanyLong'); ?>有权对用户提供的及<?php echo \Cache::get('CompanyLong'); ?>自行收集的用户的个人信息和资料编辑入网站黑名单，并将该黑名单对与本网站提供服务有关的第三方披露，且<?php echo \Cache::get('CompanyLong'); ?>有权将您提交或<?php echo \Cache::get('CompanyLong'); ?>自行收集的您的个人资料和信息与该等第三方进行数据共享，由此可能造成的您的任何损失，<?php echo \Cache::get('CompanyLong'); ?>不承担法律责任。</p>
                <p>第十一章知识产权</p>
                <p>第三十七条本网站提供的网络服务中包含的任何文本、图片、图形、音频和/或视频资料均受版权、商标和/或其它财产所有权法律的保护，未经相关权利人同意，上述资料均不得在任何媒体直接或间接发布、播放、出于播放或发布目的而改写或再发行，或者被用于其他任何商业目的。所有这些资料或资料的任何部分仅可作为私人和非商业用途而保存在某台计算机内。本网站不就由上述资料产生或在传送或递交全部或部分上述资料过程中产生的延误、不准确、错误和遗漏或从中产生或由此产生的任何损害赔偿，以任何形式，向用户或任何第三方负责。</p>
                <p>第三十八条本网站为提供网络服务而使用的任何软件（包括但不限于软件中所含的任何图象、照片、动画、录像、录音、音乐、文字和附加程序、随附的帮助材料）的一切权利均属于该软件的著作权人，未经该软件的著作权人许可，用户不得对该软件进行反向工程（reverse engineer）、反向编译（decompile）或反汇编（disassemble）。</p>
                <p>第十二章条款的解释、法律适用及争端解决</p>
                <p>第三十九条本协议是由注册用户与<?php echo \Cache::get('CompanyLong'); ?>共同签订的，适用于用户在本网站的全部活动。本协议内容包括但不限于协议正文条款及已经发布的或将来可能发布的各类规则，所有条款和规则为协议不可分割的一部分，与协议正文具有同等法律效力。</p>
                <p>第四十条本协议不涉及注册用户与本网站的其他用户之间，因网上交易而产生的法律关系及法律纠纷。但用户在此同意将全面接受并履行与本网站其他用户在本网站签订的任何电子法律文本，并承诺按照该法律文本享有和（或）放弃相应的权利、承担和（或）豁免相应的义务。</p>
                <p>第四十一条如本协议中的任何条款无论因何种原因完全或部分无效或不具有执行力，则应认为该条款可与本协议相分割，并可被尽可能接近各方意图的、能够保留本协议要求的经济目的的、有效的新条款所取代，而且，在此情况下，本协议的其他条款仍然完全有效并具有约束力。</p>
                <p>第四十二条本协议的订立、执行和解释及争议的解决均应适用中国法律并受中国法院管辖。如双方就本协议内容或其执行发生任何争议，双方应尽量友好协商解决；协商不成时，任何一方均可提交北京仲裁委员会适用北京仲裁委员会仲裁规则项下的简易程序进行仲裁。</p>
                <p>第四十三条若本协议的部分条款被认定为无效或者无法实施时，本协议中的其他条款仍然有效。</p>
                <p>第四十四条若您对本网站有任何投诉和建议，你可以将投诉信发送到本网站指定的如下邮箱：dekegs@126.com</p>
                <p>第四十五条<?php echo \Cache::get('CompanyLong'); ?>对本协议拥有最终的解释权。</p>
                <p>本协议最后更新版本：{{$ym}}</p>

            </div>
        </div>
    </div>

@endsection


@section("footcategory")
    @parent
@endsection

@section("footer")
    @parent
@endsection
@section("playSound")

@endsection
