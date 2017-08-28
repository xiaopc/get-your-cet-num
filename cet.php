<?php
if ($_GET["action"]=="req" && !empty($_POST["name"]) && !empty($_POST["id"]) && !empty($_POST["level"]) ) {
    $sendJson = json_encode( array('ks_xm' => $_POST["name"], 'ks_sfz' => $_POST["id"], 'jb' => $_POST["level"]) ,JSON_UNESCAPED_UNICODE);
    $postFields = "action=&params=".urlencode($sendJson);
    //var_dump($postFields);
    $req = curl_init();  
    curl_setopt($req, CURLOPT_URL, "http://app.cet.edu.cn:7066/baas/app/setuser.do?method=UserVerify");  
    curl_setopt($req, CURLOPT_HEADER, false);  
    curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($req, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($req, CURLOPT_TIMEOUT, 1);
    curl_setopt($req, CURLOPT_POST, true);
    curl_setopt($req, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($req, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=UTF-8','User-Agent:Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36'));  
    $result=curl_exec($req);
    curl_close($req);
    if ($result == false) {
        die('{"msg": "request error!"}');
    } else {
        die($result);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    	<meta name="renderer" content="webkit">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>find your cet number</title>
        <style type="text/css">
            @import url('https://fonts.googleapis.com/css?family=Josefin+Sans');
            .title {
                font-family: 'Josefin Sans', sans-serif;
                font-size: 36pt;
                margin: 0;
            }
            input[type="text"]:focus {
                outline: none;
                border-bottom: 2px solid #666;
            }
            input[type="text"] {
                width: 150pt;
                padding: 1pt;
                font-family: 'Josefin Sans', sans-serif;
                font-size: 18pt;
                border: none;
                border-bottom: 2px solid #ccc;
                transition: all 0.5s;
            }
            input.large {
                max-width: 99%;
                width: 310pt;
            }
            input.small {
                width: 100pt;
            }
            input.xsmall {
                width: 50pt;
            }
            button,input[type="button"],input[type="submit"] {
                background: #eee;
                border: 0;
                font-family: 'Josefin Sans', sans-serif;
                font-size: 22pt;
                transition: all 0.3s;
                border-bottom: 3px solid #ddd;
            }
            button:hover,input[type="button"]:hover,input[type="submit"]:hover {
                background: #ddd;
            }
            button:focus,input[type="button"]:focus,input[type="submit"]:focus {
                outline: none;
                background: #ccc;
                border-bottom: 3px solid #ccc;
            }
            .form-group {
                display: block;
                margin: 10pt 0pt;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <p class="title">find your cet number</p>
            <form id="login-form">
                <div class="form-group">
                    <input type="text" name="name" id="form-name" placeholder="your name">
                    <input type="text" name="id" id="form-id" placeholder="your identity card">
                    <input type="hidden" name="level" id="form-level">
                
                <div class="form-group">
                    <input type="submit" id="form-submit-4" value="band 4" onclick="$('#form-level').val(1)">
                    <input type="submit" id="form-submit-6" value="band 6" onclick="$('#form-level').val(2)">
                </div>
                
                <div id="form-res" class="form-group">
                    <p class="title" id="res-text">click to copy:</p>
                    <input type="text" name="result" id="form-result" data-clipboard-target="#form-result" readonly>
                </div>
            </form>
        </div>
        <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.bootcss.com/jquery-noty/2.4.1/packaged/jquery.noty.packaged.min.js"></script>
        <script src="https://cdn.bootcss.com/clipboard.js/1.7.1/clipboard.min.js"></script>
        <script>
            $(function(){ 
                $("#form-res").hide();
                $("#login-form").on("submit",function(e){
                    $.post("?action=req", {"name": $("#form-name").val(),
                                           "id": $("#form-id").val(),
                                           "level": $("#form-level").val(),
                                         },
                            function(data){
                                if (data.msg!="") {
                                    var n = noty({text: data.msg, layout: 'topLeft', type: 'error', timeout: 5000, progressBar: true});
                                } else {
                                    var cp = new Clipboard('#form-result');
                                    cp.on('success', function(e) {
                                        $("#res-text").html("copied!");
                                        e.clearSelection();
                                    });
                                    
                                    $("#form-result").val(data.ks_bh);
                                    $("#form-res").slideDown();
                                }
                            }, "json");
                    e.preventDefault();
                });
            }); 
        </script>
    </body>
</html>

