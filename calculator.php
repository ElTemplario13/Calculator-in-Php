<?php
    session_start();

    if(!$_POST) {
        $_SESSION["contenido"] = "";
    } else {
        echo '<link rel="shortcut icon" href="./assets/calc.png" type="image/x-icon">';
        
        $valor = match(array_keys($_POST)[0]) {
            "num0" => 0,
            "num1" => 1,
            "num2" => 2,
            "num3" => 3,
            "num4" => 4,
            "num5" => 5,
            "num6" => 6,
            "num7" => 7,
            "num8" => 8,
            "num9" => 9,
            "dot" => ".",
            default => 0
        };

        $operador = match(array_keys($_POST)[0]){
            "opSum" => "+",
            "opRes" => "-",
            "opMul" => "*",
            "opDiv" => "/",
            "equal" => "=",
            "c" => "c",
            "elevate" => "^",
            "percentage" => "%",
            "arrow" => "←",
            default  => ""
        };

        if($operador == "+" || $operador == "-" || $operador == "*" || $operador == "/" || $operador == "%" || $operador == "^"){
            // ? Capturando el contenido de la primera variable, gracias al operador.
            @$_SESSION["number1"] = $_SESSION["contenido"] ?? 0;
            // ? Capturando el último operador antes del 'igual'
            @$_SESSION["operador"] = $operador ?? 'No hay operador';
            // ? Si esta llena, borre el contenido y luego permita que se llene otra variable
            @isset($_SESSION["contenido"]) ? $_SESSION["contenido"] = "" : "";
        } elseif ($operador == '=') {
            $operaciones = [
                '+' => function($a, $b) { return $a + $b; },
                '-' => function($a, $b) { return $a - $b; },
                '*' => function($a, $b) { return $a * $b; },
                '/' => function($a, $b) { return $b != 0 ? $a / $b : 'Error: División por cero'; },
                '%' => function ($a) { return $a * 0.01; },
                '^' => function ($a,$b) { return $a**$b; }
            ];

            @array_key_exists($_SESSION["operador"], $operaciones) 
                ? $_SESSION["contenido"] = $operaciones[$_SESSION["operador"]]($_SESSION["number1"], $_SESSION["contenido"]) 
                : print "No existe el operador"; 

        } elseif ($operador == "←" || $operador == "c") {
            $unicas_operaciones = [
                '←' => function ($a) { return substr($a, 0, -1); },
                'c' => function () { ; }
            ];

            @array_key_exists($operador,$unicas_operaciones) 
            ? $_SESSION["contenido"] = $unicas_operaciones[$operador]($_SESSION["contenido"]) 
            : print "No existe el operador";
        } else {
            $_SESSION["contenido"] .= (string) $valor;
        }
    }

    (double) $contenidoActualizado = $_SESSION["contenido"] ?? "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/calc.png" type="image/x-icon">
    <title>Calculator</title>
</head>
<body>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        :root {
            --widthBoxButtons: 400px;
        }

        * {
            font-family: "Montserrat", serif;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            border: 0;
        }

        body {
            background-color: #FCAE1E;
            width: 100vw;
            height: 90vh;
            display: grid;
            place-content: center;
        }

        h1 {
            text-align: center;
            padding-bottom: 20px;
            padding-left: 20px;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        .cuerpo {
            width: 350px;
            height: 560px;
            border-radius: 5px;
        }

        .calc_form {
            width: 380px;
            height: 560px;
            border-radius: 10px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
            background-color: #2C3539;
            padding-top: 50px;
            padding-left: 10px;
            padding-right: 10px;
            display: grid;
            justify-items: center;
            align-content: center;
            grid-template-columns: repeat(4,1fr);
            row-gap: 35px;
        }

        input[type="text"] {
            position: absolute;
            top: 16%;
            left: 40.2%;
            width: 330px;
            height: 45px;
            padding-left: 5px;
            background-color: whitesmoke;
            font-weight: 600;
            color: black;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        }
        
        button[type="submit"] {
            background-color: red;
            width: 60px;
            height: 60px;
            color: white;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
            scale: 1;
            transition: scale ease .5s;

            &:hover {
                scale: 1.1;
            }

            &:active {
                scale: .9;
            }
        }

        :is(.opDiv,.opMul,.opSum,.opRes,.c,.arrow,.percentage,.elevate){
            background-color: blueviolet !important;
        }

        .opEqual {
            background-color: #33b864 !important;
        }

        .social {
            width: var(--widthBoxButtons);
            height: 100px;
            position: absolute;
            top: 85%;
            left: 5%;
        }

        .cajas_sociales {
            width: var(--widthBoxButtons);
            height: 50px;
            display: flex;
            justify-content: space-evenly;
        }

        .github_repository{
            width: 150px;
            height: 50px;
            background-color: white;
            border: 1px solid black;
            border-radius: 5px;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            scale: 1;
            font-size: 15px;
            transition: scale ease .5s;

            &:hover {
                scale: 1.1;
            }

            &:active {
                scale: .9;
            }

            & > a {
                color: black;
                text-decoration: none;
                font-size: 15px;
            }

        }

        .X_account {
            width: 150px;
            height: 50px;
            background-color: white;
            border: 1px solid black;
            border-radius: 5px;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            scale: 1;
            text-decoration: none;
            font-size: 15px;
            color: black;
            transition: scale ease .5s;

            &:hover {
                scale: 1.1;
            }

            &:active {
                scale: .9;
            }
            
            & > a {
                color: black;
                text-decoration: none;
                font-size: 15px;
            }
        }
    </style>
    <h1>Calculator in PHP</h1>
    <section class="cuerpo">
        <input type="text" id="pantalla" value="<?= $contenidoActualizado ?>" disabled>
        <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="post" class="calc_form">
            <button class="c" name="c" type="submit" value="c">C</button>
            <button class="arrow" name="arrow" type="submit" value="←">←</button>
            <button class="percentage" name="percentage" type="submit" value="%">%</button>
            <button class="elevate" name="elevate" type="submit" value="^">^</button>
            <button class="num7" name="num7" type="submit" value="7">7</button>
            <button class="num8" name="num8" type="submit" value="8">8</button>
            <button class="num9" name="num9" type="submit" value="9">9</button>
            <button class="opDiv" name="opDiv" type="submit" value="/">/</button>
            <button class="num4" name="num4" type="submit" value="4">4</button>
            <button class="num5" name="num5" type="submit" value="5">5</button>
            <button class="num6" name="num6" type="submit" value="6">6</button>
            <button class="opMul" name="opMul" type="submit" value="*">*</button>
            <button class="num1" name="num1" type="submit" value="1">1</button>
            <button class="num2" name="num2" type="submit" value="2">2</button>
            <button class="num3" name="num3" type="submit" value="3">3</button>
            <button class="opSum" name="opSum" type="submit" value="+">+</button>
            <button class="opEqual" name="equal" type="submit" value="=">=</button>
            <button class="num0" name="num0" type="submit" value="0">0</button>
            <button class="dot" name="dot" type="submit" value=".">.</button>
            <button class="opRes" name="opRes" type="submit" value="-">-</button>
        </form>
    </section>
    <section class="social">
        <div>
            <h3>Socials</h3>
            <div class="cajas_sociales" href="https://github.com/ElTemplario13" target="_blank" rel="noopener noreferrer">
                <a class="github_repository">
                    Repository
                    <img src="./assets/github.png" alt="Icono de gihub" width="30px">
                </a>
                <a class="X_account" href="http://www.google.com" target="_blank" rel="noopener noreferrer">
                    X account
                    <img src="./assets/x.png" alt="Icono de X" width="30px">
                </a>
            </div>
        </div>
    </section>
</body>
</html>