<html>

<!--
  pgn4web javascript chessboard
  copyright (C) 2009 Paolo Casaschi
  see README file and http://pgn4web.casaschi.net
  for credits, license and more details
-->

<head>

<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"> 

<title>pgn4web PGN viewer</title> 

<script src="pgn4web.js" type="text/javascript"></script>

<style type="text/css">

body
{color: black; background: white; font-family: sans-serif; padding: 20px;}
 
a:link, a:visited, a:hover, a:active
{ color: black; text-decoration: none; }

</style>

</head>

<body>

<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr>
<td align="left" valign="middle"> 
<h1 style="font-family: sans-serif; color: red;"><a style="color: red;" href=.>pgn4web PGN viewer</a></h1> 
</td>
<td align="right" valign="middle">
<a href=.><img src=pawns.png border=0></a>
</td>
</tr></tbody></table>

<div style="height: 2em;">&nbsp;</div>

<form id="textForm" action="viewer.php">
<table width="100%" cellspacing=0 cellpadding=3 border=0><tbody><tr><td>
<textarea id="pgnText" rows=6 style="width:100%;"></textarea>
</td></tr><tr><td>
<input id="enterTextButton" type="submit" value="show PGN from textbox" style="width:100%;">
</td></tr/></tbody></table>
</form>

<form id="urlForm" action="viewer.php">
<table width="100%" cellspacing=0 cellpadding=3 border=0><tbody><tr><td>
<input id="fetchButton" type="submit" value="fetch PGN or zipped PGN">
</td><td width="100%">
<input id="urlBox" type="text" value="" style="width:100%">
</td><td>
<input id="clearButton" type="submit" value="clear">
</td><td>
<input id="nicButton" type="submit" value="latest NIC">
</td><td>
<input id="twicButton" type="submit" value="latest TWIC">
</td></tr/></tbody></table>
</form>

<form id="uploadForm" action="viewer.php">
<table width="100%" cellspacing=0 cellpadding=3 border=0><tbody><tr><td>
<input id="uploadButton" type="submit" value="upload PGN or zipped PGN">
</td><td width="100%">
<input id="browseButton" type="file" value="" style="width:100%">
</td></tr/></tbody></table>
</form>

<hr>

</body>

</html>
