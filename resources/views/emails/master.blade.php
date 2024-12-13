<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins';
        }
    </style>
</head>
<body
    style="
        background-color: #fff;
        width: 900px;
        margin: 0 auto;
        margin-top: 15px;
    "
>
<section
        style="
				padding: 45px 50px 20px 50px;
				background-color: #cfcbcb76;
				border-radius: 6px;
			"
>

    <!--<editor-fold desc="Body">-->
    {!! $content !!}
    <!--</editor-fold>-->

    <div class="footer">

        <p style="margin-top: 10px; margin-bottom: 0; color: #2f364b">
            Powered by
        </p>
        <p style="margin-top: 0; color: #2f364b">{{env("APP_NAME","FieldScope")}}</p>
    </div>
</section>
</body>
</html>
