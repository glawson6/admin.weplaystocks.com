<!DOCTYPE html>
<html class="x-border-box"><head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>MessageBox</title>
<style type="text/css">
.x-message-box .ext-mb-download {
    background: url("contents/message/download.gif") no-repeat scroll 6px 0px transparent;
    height: 52px!important;
}
</style>
<link rel="stylesheet" type="text/css" href="contents/message/msg-box_files/ext-all.css">
<link rel="stylesheet" type="text/css" href="contents/message/msg-box_files/example.css">
<script type="text/javascript" src="contents/message/msg-box_files/bootstrap.js"></script><script type="text/javascript" src="contents/message/msg-box_files/ext-all.js"></script>
<script type="text/javascript" src="contents/message/msg-box_files/examples.js"></script>
<script src="contents/message/msg-box_files/msg-box.js"></script>
</head>
<body id="ext-gen1009" class="x-gecko x-reset">
<h1>MessageBox Dialogs</h1>
<p>The example shows how to use the MessageBox class. Some of the buttons have animations, some are normal.</p>
<p>The js is not minified so it is readable. See <a href="contents/message/msg-box.js">msg-box.js</a>.</p>

<p>
    <b>Confirm</b><br>
    Standard Yes/No dialog. &nbsp;
    <button id="mb1">Show</button>
</p>

<p>
    <b>Prompt</b><br>
    Standard prompt dialog. &nbsp;
    <button id="mb2">Show</button>
</p>

<p>
    <b>Multi-line Prompt</b><br>
    A multi-line prompt dialog. &nbsp;
    <button id="mb3">Show</button>
</p>

<p>
    <b>Yes/No/Cancel</b><br>
    Standard Yes/No/Cancel dialog. &nbsp;
    <button id="mb4">Show</button>
</p>

<p>
    <b>Progress Dialog</b><br>
    Dialog with measured progress bar. &nbsp;
    <button id="mb6">Show</button>
</p>

<p>
    <b>Wait Dialog</b><br>
    Dialog with indefinite progress bar and custom icon (will close after 8 sec). &nbsp;
    <button id="mb7" >Show</button>
    <input type="hidden" name="time" id="time" value="2000"/>
</p>

<p>
    <b>Alert</b><br>
    Standard alert message dialog. &nbsp;
    <button id="mb8">Show</button>
</p>

<p>
    <b>Icons</b><br>
    Standard alert with optional icon. &nbsp;
    <select id="icons">
        <option value="ext-mb-error" id="error" selected="selected">Error</option>
        <option value="ext-mb-info" id="info">Informational</option>
        <option value="ext-mb-question" id="question">Question</option>
        <option value="ext-mb-warning" id="warning">Warning</option>
    </select> &nbsp;
    <button id="mb9">Show</button>
</p>


</body></html>