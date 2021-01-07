<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Big+Shoulders+Stencil+Text:wght@900&display=swap"
          rel="stylesheet">
    <title>Alles Im Rudel</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;

            background: #000;
            color: #fff;
            text-align: center;
        }

        .wrapper {
            height: 100vh;
            width: 100%;
            position: relative;
            user-select: none;
        }

        .container {
            position: absolute;
            top: 47.5%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 95%;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        .titleImage {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .titleImage img {
            width: 100%;
            filter: drop-shadow(0px 0px 30px #fff);
        }

        .titleText {
            font-family: 'Anton', sans-serif;
            letter-spacing: 15px;
            font-size: 100px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <a href="https://allesimrudel.de">
            <div class="titleImage">
                <img src="/assets/head.png" alt="Alles Im Rudel" id="image"/>
            </div>
            <div class="titleText">
                Alles im Rudel
            </div>
        </a>
    </div>
    <script type="text/javascript">
        let minRadius = 75;
        let maxRadius = 150;
        let currentRadius = 75;
        let addRadius = 1;

        function loop() {
            currentRadius += addRadius;
            if (currentRadius === minRadius || currentRadius === maxRadius) {
                addRadius = -addRadius;
            }
            let image = document.getElementById('image');
            if (image) {
                image.style.filter = "drop-shadow(0px 0px " + currentRadius / 10 + "px #fff)"
            }
            setTimeout(loop, 1)
        }

        loop();
    </script>
</div>
</body>
</html>
