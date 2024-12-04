<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="#" type="image/x-icon">
    <title>For each 3</title>
    <style>
        body{
            display:flex;
            flex-direction: row;
            margin: 0 auto;
        }
        #tweet{
            border: 0.5px solid #d0d0d0;
            margin: 0rem 0rem 0.01rem; 
            text-align: center;
            width: 100%;
        }
        img{margin-top: 1rem;}
        main{
            display:flex;
            flex-direction: column;
            width: 50%;
            margin-top: 1rem;
            justify-content: center;
            align-items: center;
        }
        aside{width: 25%;}
    </style>
</head>
<body>

    <?php
        $tweets = [ 
            ["tweet" => "¡Hola mundo! Este es mi primer tweet.", 
            "usuario" => "usuario1", "fecha" => "03-12-2024 17:11:00" ], 
            [ "tweet" => "¡Estoy aprendiendo PHP y es muy interesante!", 
            "usuario" => "usuario2", "fecha" => "03-12-2024 16:45:00" ], 
            ["tweet" => "¿Alguna recomendación de libros de programación?", 
            "usuario" => "usuario3", "fecha" => "03-12-2024 15:30:00"],
            ["tweet" => "¿Cuál es tu lenguaje de programación favorito?", 
            "usuario" => "usuario4", "fecha" => "03-12-2024 11:30:00" ],
            [ "tweet" => "Estoy participando en un hackathon virtual. ¡Es muy divertido!", 
            "usuario" => "usuario5", "fecha" => "03-12-2024 10:45:00" ], 
            ["tweet" => "Recordatorio del día: No te olvides de hacer commit regularmente.",
            "usuario" => "usuario6", "fecha" => "03-12-2024 09:55:00" ],
            ["tweet" => "El café es el mejor amigo de un programador.",
            "usuario" => "usuario7", "fecha" => "03-12-2024 08:30:00"]
        ];
        
        echo"<aside><img src='../img/inicioX.png' height='auto'></aside>";
        echo"<main>";

        foreach($tweets as $publicacion){
            
            echo"<div id='tweet'>";

            foreach($publicacion as $elemento => $elemento2){   

                 echo"<p>  $elemento : $elemento2  </p>";

            }

            echo"</div>";
        }
        echo"</main>";
        echo"<aside><img src='../img/inicioX2.png'></aside>";
    ?>
</body>
</html>