<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?= $language ?>">
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=320, target-densitydpi=device-dpi">
<style type="text/css">
	/* Mobile-specific Styles */

	@media only screen and (max-width: 660px) {
		table[class=w0], td[class=w0] {
			width: 0 !important;
		}
		table[class=w10], td[class=w10], img[class=w10] {
			width: 10px !important;
		}
		table[class=w15], td[class=w15], img[class=w15] {
			width: 5px !important;
		}
		table[class=w30], td[class=w30], img[class=w30] {
			width: 10px !important;
		}
		table[class=w60], td[class=w60], img[class=w60] {
			width: 10px !important;
		}
		table[class=w125], td[class=w125], img[class=w125] {
			width: 80px !important;
		}
		table[class=w130], td[class=w130], img[class=w130] {
			width: 55px !important;
		}
		table[class=w140], td[class=w140], img[class=w140] {
			width: 90px !important;
		}
		table[class=w160], td[class=w160], img[class=w160] {
			width: 180px !important;
		}
		table[class=w170], td[class=w170], img[class=w170] {
			width: 100px !important;
		}
		table[class=w180], td[class=w180], img[class=w180] {
			width: 80px !important;
		}
		table[class=w195], td[class=w195], img[class=w195] {
			width: 80px !important;
		}
		table[class=w220], td[class=w220], img[class=w220] {
			width: 80px !important;
		}
		table[class=w240], td[class=w240], img[class=w240] {
			width: 180px !important;
		}
		table[class=w255], td[class=w255], img[class=w255] {
			width: 185px !important;
		}
		table[class=w275], td[class=w275], img[class=w275] {
			width: 135px !important;
		}
		table[class=w280], td[class=w280], img[class=w280] {
			width: 135px !important;
		}
		table[class=w300], td[class=w300], img[class=w300] {
			width: 140px !important;
		}
		table[class=w325], td[class=w325], img[class=w325] {
			width: 95px !important;
		}
		table[class=w360], td[class=w360], img[class=w360] {
			width: 140px !important;
		}
		table[class=w410], td[class=w410], img[class=w410] {
			width: 180px !important;
		}
		table[class=w470], td[class=w470], img[class=w470] {
			width: 200px !important;
		}
		table[class=w720], td[class=w720], img[class=w720] {
			width: 280px !important;
		}
		table[class=w800], td[class=w800], img[class=w800] {
			width: 300px !important;
		}
		table[class*=hide], td[class*=hide], img[class*=hide], p[class*=hide], span[class*=hide] {
			display: none !important;
		}
		table[class=h0], td[class=h0] {
			height: 0 !important;
		}
		p[class=footer-content-left] {
			text-align: center !important;
		}
		#headline p {
			font-size: 30px !important;
		}
		.article-content, #left-sidebar {
			-webkit-text-size-adjust: 90% !important;
			-ms-text-size-adjust: 90% !important;
		}
		.header-content, .footer-content-left {
			-webkit-text-size-adjust: 80% !important;
			-ms-text-size-adjust: 80% !important;
		}
		img {
			height: auto;
			line-height: 100%;
		}
	}
	/* Client-specific Styles */
	#outlook a {
		padding: 0;
	}/* Force Outlook to provide a "view in browser" button. */
	body {
		width: 100% !important;
	}
	.ReadMsgBody {
		width: 100%;
	}
	.ExternalClass {
		width: 100%;
		display: block !important;
	}/* Force Hotmail to display emails at full width */
	/* Reset Styles */
	/* Add 100px so mobile switch bar doesn't cover street address. */
	body {
		background-color: #dedede;
		margin: 0;
		padding: 0;
	}
	img {
		outline: none;
		text-decoration: none;
		display: block;
	}
	br, strong br, b br, em br, i br {
		line-height: 100%;
	}
	h1, h2, h3, h4, h5, h6 {
		line-height: 100% !important;
		-webkit-font-smoothing: antialiased;
	}
	h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
		color: blue !important;
	}
	h1 a:active, h2 a:active, h3 a:active, h4 a:active, h5 a:active, h6 a:active {
		color: red !important;
	}
	/* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
	h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
		color: purple !important;
	}
	/* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
	table td, table tr {
		border-collapse: collapse;
	}
	.yshortcuts, .yshortcuts a, .yshortcuts a:link, .yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
		color: black;
		text-decoration: none !important;
		border-bottom: none !important;
		background: none !important;
	}/* Body text color for the New Yahoo.  This example sets the font of Yahoo's Shortcuts to black. */
	/* This most probably won't work in all email clients. Don't include code blocks in email. */
	code {
		white-space: normal;
		word-break: break-all;
	}
	#background-table {
		background-color: #dedede;
	}
	/* Webkit Elements */
	#top-bar {
		border-radius: 6px 6px 0px 0px;
		-moz-border-radius: 6px 6px 0px 0px;
		-webkit-border-radius: 6px 6px 0px 0px;
		-webkit-font-smoothing: antialiased;
		background-color: #0c4c66;
		color: #fff;
		font-size: 0.75em;
		font-weight: bold;
	}
	#top-bar a {
		font-weight: normal;
		color: #ffffff;
		text-decoration: none;
	}
	#footer {
		border-radius: 0px 0px 6px 6px;
		-moz-border-radius: 0px 0px 6px 6px;
		-webkit-border-radius: 0px 0px 6px 6px;
		-webkit-font-smoothing: antialiased;
	}
	#footer a {
		color:#fff;
	}
	/* Fonts and Content */
	body, td {
		font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif;
	}
	.header-content, .footer-content-left, .footer-content-right {
		-webkit-text-size-adjust: none;
		-ms-text-size-adjust: none;
	}
	/* Prevent Webkit and Windows Mobile platforms from changing default font sizes on header and footer. */
	.header-content {
		font-size: 12px;
		color: #ededed;
	}
	.header-content a {
		font-weight: bold;
		color: #ffffff;
		text-decoration: none;
	}
	#headline p {
		color: #444444;
		font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif;
		font-size: 36px;
		text-align: center;
		margin-top: 0px;
		margin-bottom: 30px;
	}
	#headline p a {
		color: #444444;
		text-decoration: none;
	}
	.article-title {
		font-size: 18px;
		line-height: 24px;
		color: #333;
		font-weight: bold;
		margin-top: 0px;
		margin-bottom: 18px;
		font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif;
	}
	.article-title a {
		color: #b0b0b0;
		text-decoration: none;
	}
	.article-title.with-meta {
		margin-bottom: 0;
	}
	.article-meta {
		font-size: 13px;
		line-height: 20px;
		color: #ccc;
		font-weight: bold;
		margin-top: 0;
	}
	.article-content {
		font-size: 13px;
		line-height: 18px;
		color: #444444;
		margin-top: 0px;
		margin-bottom: 18px;
		font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif;
	}
	.article-content a {
		color: #c4a102;
		font-weight: bold;
		text-decoration: none;
	}
	.article-content dl {
		float: left;
		clear: both;
		width: 100%;
		height: auto;
		margin: 0 0 20px 0;
		padding: 0;
	}
	.article-content dl dt {
		float: left;
		clear: both;
		width: 20%;
		height: auto;
		margin: 4px 0;
		padding: 0;
		font-weight: bold;
	}
	.article-content dl dd {
		float: left;
		clear: none;
		width: 80%;
		height: auto;
		margin: 4px 0;
		padding: 0;
	}

	.article-content img {
		max-width: 100%
	}
	.article-content ol, .article-content ul {
		margin-top: 0px;
		margin-bottom: 18px;
		margin-left: 19px;
		padding: 0;
	}
	.article-content li {
		font-size: 13px;
		line-height: 18px;
		color: #444444;
	}
	.article-content li a {
		color: #c4a102;
		text-decoration: underline;
	}
	.article-content p {
		margin-bottom: 15px;
	}
	.footer-content-left {
		font-size: 12px;
		line-height: 15px;
		color: #ededed;
		margin-top: 0px;
		margin-bottom: 15px;
	}
	.footer-content-left a {
		color: #ffffff;
		font-weight: bold;
		text-decoration: none;
	}
	.footer-content-right {
		font-size: 11px;
		line-height: 16px;
		color: #ededed;
		margin-top: 0px;
		margin-bottom: 15px;
	}
	.footer-content-right a {
		color: #ffffff;
		font-weight: bold;
		text-decoration: none;
	}
	#footer {
		background-color: #333;
		color: #ededed;
	}
	#footer a {
		color: #ffffff;
		text-decoration: none;
		font-weight: bold;
	}
	#permission-reminder {
		white-space: normal;
	}
	#street-address {
		color: #b0b0b0;
		white-space: normal;
	}
</style>
<!--[if gte mso 9]>
<style _tmplitem="335" >
.article-content ol, .article-content ul {
   margin: 0 0 0 24px;
   padding: 0;
   list-style-position: inside;
}
</style>
<![endif]-->
</head>
<body>


<table id="background-table" border="0" cellpadding="0" cellspacing="0"  width="800">
  <tbody>
    <tr width="800">
      <td align="center" bgcolor="#dedede" width="800">
		<table class="w800" style="margin:0 10px; width:800px;" border="0" cellpadding="0" cellspacing="0" width="800">
          <tbody>
            <tr>
              <td class="w800" width="800"  style="width:800px;">
				<table id="top-bar" class="w800"  border="0" cellpadding="0" cellspacing="0" width="" style="width:100%;padding:6px;">
                  <tbody>
                    <tr>
                      <td class="w15" width="15"></td>
                      <td class="w325" align="left" valign="middle" width="350"><?= webConfig('nombre') ?></td>
                      <td class="w30" width="130"></td>
                      <td class="w255" align="right" valign="middle" width="255"><a href="http://<?= webConfig('web') ?>"><?= webConfig('web') ?></a></td>
                      <td class="w15" width="15"></td>
                    </tr>
                  </tbody>
                </table>
			  </td>
            </tr>
            <tr>
              <td id="header" class="w800" align="center" width="800" style="background:#fff;">
				<table class="w800" border="0" cellpadding="0" bgcolor="#fff"  cellspacing="0" width="800"  style="background:#fff;">
                  <tbody>
                    <tr>
                      <td class="w30" width="30" bgcolor="#fff" style="background:#fff;"></td>
						<td class="w720" width="720" bgcolor="#fff">
							<div id="headline" align="center">
							  <?= $header ?>
							</div>
						</td>
                      <td class="w30" width="30" bgcolor="#fff" style="background:#fff;"></td>
                    </tr>
                  </tbody>
                </table>
			  </td>
            </tr>

            <tr id="simple-content-row">
              <td class="w800" bgcolor="#f8f8f8" width="800"><table class="w800" border="0" cellpadding="0" cellspacing="0" width="800">
                  <tbody>
                    <tr>
                      <td class="w30" width="30"></td>
                      <td class="w720" width="720">
                          <layout label="Text only">
                            <table class="w720" border="0" cellpadding="0" cellspacing="0" width="720">
                              <tbody>
                                <tr>
                                  <td class="w720" width="720">
                                    <div class="article-content" align="left">
                                      <div id="message" style="padding:24px;"><p><?=$message?></p></div>
                                    </div>
								  </td>
                                </tr>

                              </tbody>
                            </table>
                          </layout>
                        </td>
                      <td class="w30" width="30"></td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>

            <tr>
              <td class="w800" width="800">
			    <table id="footer" class="w800" bgcolor="#333" border="0" cellpadding="0" cellspacing="0" width="800" style="text-align: center;width:100%;">
                  <tbody>
                    <tr>
                      <td class="w720 h0" height="15"  style="text-align: center;width:100%;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class="w720" valign="top" width="720" style="text-align: center;width:100%;">
						<?= webconfig('nombre') ?>&nbsp;&nbsp;•&nbsp;&nbsp; 
						<?=trad('telefono')?>: <?= webConfig('telefono') ?>&nbsp;&nbsp;•&nbsp;&nbsp;<?= webConfig('movil') ?>&nbsp;&nbsp;•&nbsp;&nbsp;
						<?=trad('email')?>: <a href="mailto:<?= webConfig('email') ?>" style="color: #fff;"><?= webConfig('email') ?></a>
					  </td>
                     
                    </tr>
                    <tr>
                      <td class="w720 h0" height="15" style="text-align: center;width:100%;">&nbsp;</td>
                    </tr>
                  </tbody>
                </table>
			  </td>
            </tr>
            <tr>
              <td class="w800" height="30" width="800"></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
</body>
</html>