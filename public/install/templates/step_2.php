<?php if (!defined('IN_INSTALL')) exit('Request Error!'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>萤火商城 安装向导 - 配置数据文件</title>
    <link href="templates/style/install.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="templates/js/jquery.min.js"></script>
    <script type="text/javascript" src="templates/js/common.js"></script>
    <script type="text/javascript" src="templates/js/forms.js"></script>
</head>
<body>
<form name="form" id="form" method="post" action="index.php">
    <div class="header"></div>
    <div class="mainBody">
        <div class="table">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="40" colspan="2" align="left"><span class="title">填写数据库信息</span></td>
                </tr>
                <tr>
                    <td width="30%" height="40" align="right">数据库服务器：</td>
                    <td><input type="text" name="dbhost" id="dbhost" class="input" value="localhost"/>
                        <span class="cnote">数据库服务器地址, 一般为 localhost</span></td>
                </tr>
                <tr>
                    <td height="40" align="right">数据库名称：</td>
                    <td><input type="text" name="dbname" id="dbname" class="input" value="yoshop2_db"/></td>
                </tr>
                <tr>
                    <td height="40" align="right">数据库用户：</td>
                    <td><input type="text" name="dbuser" id="dbuser" class="input" value="root"/></td>
                </tr>
                <tr>
                    <td height="40" align="right">数据库密码：</td>
                    <td><input type="password" name="dbpwd" id="dbpwd" class="input" onblur="CheckPwd()"/>
                        <span class="cnote"><span id="cpwdTxt"></span></span>
                        <input type="hidden" name="cpwd" id="cpwd" value="false"></td>
                </tr>

                <tr>
                    <td height="40" colspan="2" align="left"><span class="title">默认管理员信息</span></td>
                </tr>
                <tr>
                    <td height="40" align="right">管理员账号：</td>
                    <td>
                        <div class="readonly">admin</div>
                    </td>
                </tr>
                <tr>
                    <td height="40" align="right">管理员密码：</td>
                    <td>
                        <div class="readonly">yinghuo</div>
                    </td>
                </tr>
                <!--                <tr>-->
                <!--                    <td height="40" align="right">安装测试数据：</td>-->
                <!--                    <td><input type="checkbox" name="testdata" value="true" checked="checked"/>-->
                <!--                        是-->
                <!--                    </td>-->
                <!--                </tr>-->
            </table>
        </div>
    </div>
    <div class="footer"><span class="step3"></span> <span class="copyright"><?php echo $cfg_copyright; ?></span> <span
                class="formSubBtn"> <a href="javascript:void(0);" onclick="history.go(-1);return false;" class="back">返 回</a> <a
                    href="javascript:void(0);" onclick="CheckForm();return false;" class="submit">开始安装</a>
		<input type="hidden" name="s" id="s" value="3">
		</span></div>
</form>
</body>
</html>