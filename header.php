<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-SVH3P6TYLL"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-SVH3P6TYLL');
        </script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
        <style>
            body {
               margin: 0;
               padding: 0;
            }
            .container {
                z-index: 1;
                position: fixed;
                top: 0;
                left: 0;
                background-color: black;
                width: 100%;
                height: 85px;

                display: flex;
                justify-content: space-around;
                align-items: center;
                padding: 10px 0px;
            }

            .logo {
                height: 80%;
            }
            .material-symbols-outlined {
                font-size: 38px;
            }
            a {
                display: flex;
                align-items: center;
            }
            /* Icones */
            .icons {
                font-size: 30px;
                color: white;
                width: 50%;
                display: flex;
                justify-content: end;
            }
            .span_ico {
                display: flex;
                align-items: center;
                margin: 0px 20px;
            }
            .icons a, .menu {
                text-decoration: none;
                color: white;
                padding: 0px 10px;
            }

            .descer {
                padding: 63px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img class="logo" src="https://www.detalhado.com.br/wp-content/uploads/2019/11/pizzaiolo.png">
            
            <div class="icons">

            </div>
        </div>
        <div class="descer"></div>
    </body>
</html>
