@extends(env('Template').'.layouts.applists')
@section('title', $title)
@section('here')
@endsection
@section('addcss')
    @parent
@endsection
@section('addjs')
    @parent
@endsection
@section('formbody')
    <div class="x-body">
        <div class="layui-row">

        </div>
        <xblock>
            <button class="layui-btn" onclick="store()"><i class="layui-icon download">&#xe654;</i>添加</button>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
        </xblock>
        <table class="layui-table x-admin layui-form">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>
                    <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i>
                    </div>
                    ID
                </th>
                <th>应用名称</th>
                <th>版本号</th>
                <th>版本名称</th>
                <th>系统类型</th>
                <th>安装包类型</th>
                <th>是否发行</th>
                <th>静默更新</th>
                <th>强制更新</th>
                <th>下载地址</th>
                <th>更新内容</th>
                <th>时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="view">
            </tbody>
        </table>
        <div id="layer_pages"></div>
    </div>
@endsection
@section("layermsg")
    @parent
@endsection
@section('form')
    <script id="demo" type="text/html">
        <%#  layui.each(d.data, function(index, item){ %>
        <tr>
            <td>
                <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<% item.id %>'>
                    <i class="layui-icon">&#xe605;</i>
                </div>
                <span style=""><% item.id %></span>
            </td>
            <td><% item.app_name %></td>
            <td><% item.edition_number %></td>
            <td><% item.edition_name %></td>
            <td><% item.platform %></td>
            <td>
                <%# if(item.package_type==0){ %>
                整包更新
                <%# }else if(item.package_type==1){ %>
                Wgt热更新
                <%# }%>
            </td>
            <td>
                <%# if(item.edition_issue==0){ %>
                否
                <%# }else if(item.edition_issue==1){ %>
                是
                <%# }%>
            </td>
            <td>
                <%# if(item.edition_silence==0){ %>
                否
                <%# }else if(item.edition_silence==1){ %>
                是
                <%# }%>
            </td>
            <td>
                <%# if(item.edition_force==0){ %>
                否
                <%# }else if(item.edition_force==1){ %>
                是
                <%# }%>
            </td>
            <td><% item.edition_url %></td>
            <td><% item.edition_content %></td>
            <td><% item.create_time %></td>
            <td class="td-manage">
                <a title="编辑" onclick="update(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe642;</i>
                </a>
                <a title="删除" onclick="del(<% item.id %>,<% d.current_page %>)" href="javascript:;">
                    <i class="layui-icon">&#xe640;</i>
                </a>
            </td>
        </tr>
        <%#  }); %>
        <%#  if(d.length === 0){ %>
        无数据
        <%#  } %>
    </script>
@endsection

