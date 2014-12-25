
<div class="tab">
        <ul class="tab-label">
            <li class="current"><a href="">基本信息</a></li>
            <li><a href="manage_2.html">机具信息</a></li>
            <li><a href="">用户信息</a></li>
            <li><a href="">应用信息</a></li>
        </ul>
        <div class="more"><a href="">帮助建议</a></div>
        <div class="tab-main">
            <table class="formList">
                <tr>
                    <td class="name">企业名称：</td>
                    <td class="value">创博亚太科技山东有限公司</td>
                    <td class="name">当前状态：</td>
                    <td class="value">正常</td>
                </tr>
                <tr>
                    <td class="name">简称：</td>
                    <td class="value"><input type="text" class="input_text"/></td>
                    <td class="name">上级物业：</td>
                    <td class="value">山大华特物业</td>
                </tr>
                <tr class="line">
                    <td class="name">所属地区：</td>
                    <td class="value"><select>
                        <option>山东</option>
                    </select></td>
                    <td class="name">行业类型：</td>
                    <td class="value"><select>
                        <option>电信IT</option>
                    </select></td>
                </tr>
                <tr>
                    <td class="name">地址：</td>
                    <td class="value"><input type="text" class="input_text address"/></td>
                </tr>
                <tr>
                    <td class="name">联系电话：</td>
                    <td class="value"><input type="text" class="input_text"/></td>
                    <td class="name">联系电话：</td>
                    <td class="value"><input type="text" class="input_text"/></td>
                </tr>
                <tr>
                    <td class="name">管理员：</td>
                    <td colspan="3">
                        <input type="radio" id="id_1" name="usertype" value="a"/><label for="id_1">系统管理员</label>
                        <input type="radio" id="id_2" name="usertype" value="b" checked="1"/><label for="id_2">普通管理员</label>
                    </td>
                </tr>
                <tr>
                    <td class="name">组成员：</td>
                    <td colspan="3">
                        <input type="checkbox" id="id_11"/><label for="id_11">张三</label>
                        <input type="checkbox" id="id_12"/><label for="id_12">李四</label>
                        <input type="checkbox" id="id_13"/><label for="id_13">王五</label>
                        <input type="checkbox" id="id_14"/><label for="id_14">李四</label>
                        <input type="checkbox" id="id_15"/><label for="id_15">张三</label>
                        <input type="checkbox" id="id_16"/><label for="id_16">王五</label>
                    </td>
                </tr>
                <tr class="line">
                    <td class="name">联系人：</td>
                    <td><input type="text" class="input_text"/></td>
                    <td class="name">手机号码：</td>
                    <td class="value"><input type="text" class="input_text"/>
                    </td>
                </tr>
                <tr class="btnBox">
                    <td colspan="4">
                        <span class="sBtn">
                            <a class="left" onclick="test2();">保存设置</a><a class="right"></a>
                        </span>
                        <span class="sBtn-cancel">
                            <a class="left">取消</a><a class="right"></a>
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        var test2 = function(){
            var v = $("input[type='radio'][name='usertype']:checked").val();
            alert(v);
        }
    </script>
