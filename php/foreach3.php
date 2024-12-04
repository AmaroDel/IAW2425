<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foreach3</title>
    <style>
        .tweet-container {
            width: 100%;
            max-width: 500px;
            margin: 10px auto;
            padding: 10px;
            border: 1px solid #e1e8ed;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
            font-family: Arial, sans-serif;
        }
        .tweet-header {
            font-size: 14px;
            color: #657786;
        }
        .tweet-content {
            font-size: 16px;
            color: #14171a; 
        }
    </style>
</head>
<body>

<?php
    $tweets = [
        "primer tweet",
        "segundo tweet",
        "tercer tweet",
        "cuarto tweet"
    ];

    function mostrarTweet($tweet) {
        return "<div class='tweet-container'>
                    <div class='tweet-header'>Usuario @ejemplo</div>
                    <div class='tweet-content'>$tweet</div>
                </div>";
    }

    foreach ($tweets as $tweet) {
        echo mostrarTweet($tweet);
    }
?>

</body>
</html>