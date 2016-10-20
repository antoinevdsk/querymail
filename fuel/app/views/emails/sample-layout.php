<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Query mail</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        hr {
            margin: 10px 0;
            padding: 0;
            height: 1px;
            background-color: #fff;
            border: none;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body>
<!-- container -->
<!-- header -->
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="100%" style="padding: 10px 0; background-color: #ffffff;">
                        <!-- header -->
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="105" align="left" valign="middle">
                                    <font face="Arial, Helvetica, sans-serif"><img
                                            src="<?= ($bForMail) ? HTMLPATH : '/'; ?>_assets/img/querymail.png"
                                            width="100" height="100" border="0" align="top" alt="Company logo"/></font>
                                </td>
                                <td valign="left">
                                    <font face="Arial, Helvetica, sans-serif" size="4">
                                        &nbsp;&nbsp;&nbsp;<?= $sSubject; ?></font>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100%" height="20" bgcolor="#ffffff" valign="top">
                        <span style="display:block; background-color:#248dcc;"><img
                                src="<?= ($bForMail) ? HTMLPATH : '/'; ?>_assets/img/emails/emailBorder.png" width="98%"
                                height="14" border="0" align="top" alt="" style="display:block;"/></span>
                    </td>
                </tr>
                <!-- /header -->
                <!-- content -->
                <tr>
                    <td bgcolor="#FFFFFF" style="font-family: Arial, Helvetica, sans-serif;"><font
                            face="Arial, Helvetica, sans-serif"><?= $bodyContent ?></font></td>
                </tr>
            </table>
            <!-- /content -->
            <!-- footer -->
            <table border="0" cellpadding="0" cellspacing="5" width="100%" bgcolor="#ececeb">
                <tr>
                    <td style="padding: 10px 0;">
                        <font face="Arial, Helvetica, sans-serif" size="2" color="#666666">
                            <strong>Query Mail</strong><br/>
                            by Antoine Vanderstukken<br/>
                            <a href="https://fr.linkedin.com/in/antoine-vanderstukken-8ab1535"
                               style="color: #2596bf;"><span style="color:#2596bf;">LinkedIn</span></a>
                            <a href="https://github.com/antoinevdsk" style="color: #2596bf;"><span
                                    style="color:#2596bf;">Github</span></a>
                        </font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br><br>
<!-- /footer -->
<!-- /container -->
</body>
</html>