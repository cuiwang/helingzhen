<?php
$body = "<!DOCTYPE html><html lang='zh-cmn-Hans'><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
		<title>简历</title>
		<meta charset='utf-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no'>
		<meta name='format-detection' content='telephone=no'>
		</head>
		<body>
		<table border='0' cellspacing='0' cellpadding='0' width='600'>
		  <tbody>
		  <tr>
		    <td style='BORDER-BOTTOM: #d6d3ce 1px solid; BORDER-LEFT: #d6d3ce 1px solid; BORDER-TOP: #d6d3ce 1px solid; BORDER-RIGHT: #d6d3ce 1px solid'>
		      <table border='0' cellspacing='0' cellpadding='0' width='600' bgcolor='#ffffff'>
		        <tbody>
		        <tr>
		          <td>
		            <table border='0' cellspacing='0' cellpadding='0' width='580' align='center'><tbody>
		              <tr>
		                <td height='10' colspan='3'></td></tr>
		              <tr>
		                <td valign='top' width='1%' nowrap=''><span style='COLOR: #000000; FONT-SIZE: 40px; FONT-WEIGHT: bold'>".$mylist[uname]."</span></td>
		                <td valign='top' align='right'>".$mylist[sex]."| ".$mylist[marriage]." | ".$mylist[birth]."生 | 籍贯：".$mylist[register]." |
		                  现居住于".$mylist[address]." <br> ".$mylist[school]."|".$mylist[ed]."<br>".$mylist[present]."<br>mobile:".$mylist[mobile]."<br>E-mail: <a href='mailto:".$mylist[email]."'>".$mylist[email]."</a> </td>
		                <td width='1%' nowrap=''>
		                  <div style='PADDING-BOTTOM: 5px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px; PADDING-TOP: 0px' class='photo'><a href='#' target='_blank'></div></td></tr>
		              <tr>
		                <td height='10' colspan='3'></td></tr></tbody></table></td></tr>
		              <tr>
		                <td><br>
		                  <table border='0' cellspacing='0' cellpadding='2' width='580' bgcolor='#f6f7f8'>
		                    <tbody>
		                    <tr>
		                      <td style='BORDER-BOTTOM: #e7e7e7 1px solid; BORDER-LEFT: #e7e7e7 1px solid; BORDER-TOP: #e7e7e7 1px solid; BORDER-RIGHT: #e7e7e7 1px solid'>&nbsp;&nbsp;<span style='COLOR: #8866ff; FONT-SIZE: 14px'>工作经历</span></td></tr></tbody></table><br>
		                  <table border='0' cellspacing='0' cellpadding='0'>
		                    <tbody>
								".$exper."
		</tbody></table></td></tr>

		              <tr>
		                <td><br>
		                  <table border='0' cellspacing='0' cellpadding='2' width='580' bgcolor='#f6f7f8'>
		                    <tbody>
		                    <tr>
		                      <td style='BORDER-BOTTOM: #e7e7e7 1px solid; BORDER-LEFT: #e7e7e7 1px solid; BORDER-TOP: #e7e7e7 1px solid; BORDER-RIGHT: #e7e7e7 1px solid'>&nbsp;&nbsp;<span style='COLOR: #8866ff; FONT-SIZE: 14px'>证书</span></td></tr></tbody></table><br>
		                  <table border='0' cellspacing='0' cellpadding='0' width='580'>
		                    <tbody>
							".$card."
							 <tr>
		                <td height='10' colspan='3'></td></tr>
		   </tr></tbody></table></td></tr>

		</tbody></table></td></tr></tbody></table></td></tr></tbody></table>



		</body></html>";