<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Rikisimo Installation</title>
	<style type="text/css">
		html, body, div, span, object, iframe,
		h1, h2, h3, h4, h5, h6, p, blockquote, pre,
		a, abbr, acronym, address, code,
		del, dfn, em, img, q, dl, dt, dd, ol, ul, li,
		fieldset, form, label, legend, input[type=hidden],
		table, caption, tbody, tfoot, thead, tr, th, td {
		  margin: 0;
		  padding: 0;
		  border: 0;
		  font-weight: inherit;
		  font-style: inherit;
		  font-family: inherit;
		  vertical-align: baseline;
		}
		body {
			background-color: #fff;
			color: #444;
			font-family: helvetica, arial, verdana, sans-serif;
		}
		label {
			display: block;
			font-size: 18px;
		}
		input[type=text], input[type=password] {
			padding: 5px;
			font-size: 18px;
			margin-bottom: 15px;
			width: 290px;
			border: 1px solid #bbb;
		}
		#main {
			width: 300px;
			margin-left: auto;
			margin-right: auto;
			margin-top: 30px;
			padding: 20px;
			background-color: #eee;
			border: 2px solid #ddd;
		}
		#main h1 {
			padding-bottom: 20px;
			text-align: center;
			color: #111;
		}
		.error {
			color:#c00;
			margin-bottom: 15px;
		}
	</style>
</head>
<body>
	<div id="main">
		<div id="install">
			<h1>Rikisimo</h1>		
            <?php
				if ($session->check('Message.flash')) {
					echo '<div class="error">';
	         		echo $session->Flash();					
					echo '</div>';
				}
                echo $content_for_layout;
            ?>
            </div>
        </div>
    </div>
</body>
</html>