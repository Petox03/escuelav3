<?php
require_once 'data.php';
use Illuminate\Database\Capsule\Manager as DB;

$error =  "";
    echo'
    <!-- Icon -->
    <link rel="shortcut icon" href="img/icons/login.png">
    <title>Login</title>

</head>

<body>
    <!-- project start -->
    <div class="container">
';

//Comprobamos que no haya una sesión activa
if(!$loggedin)
{
    //se comprueba si se ha enviado al variable user
    if (isset($_POST['user']))
    {
        //Escapamos las variables
        $user = sanitizeString($_POST['user']);
        $pass = sanitizeString($_POST['pass']);

        //Validamos que las variables no estén vacías
        if($user == "" || $pass == "")
        {
            //Si lo están, mandamos un mensaje de error
            $error = 'Falta algún dato';
        }
        else
        {
            //Sino, seguimos con el código

            //? Consulta a la base de datos, llama user y pass
            $users = DB::table('users')->select(['user', 'pass'])->where('user', $user)->where('pass', $pass)->first();

            //Se valida que la consulta traiga datos existentes
            if (!$users)
            {
                //Si no trae datos existentes (Válidos), manda un error
                $error = "cuenta y/o contraseña inválida";
            }
            else
            {
                //Si trae datos válidos, continua

                //Se declaran las variables de sesión
                $_SESSION['user'] = $user;
                $_SESSION['pass'] = $pass;

                //Redirije a el index y detiene el código aquí
                die("
                <div class='check is-size-4'>
                    <meta http-equiv='Refresh' content='3;url=index.php'>
                    <h1>Haz iniciado sesión correctamente, serás redirigido en breve<h1>
                    <h1>sino, haz click <a href='index.php' class='linkL'>aquí</a></h1>
                </div>
                </div></body></html>");
            }
        }
    }

    //? Módulo de login
    echo<<<_login
        <div class="columns">
            <div class="column is-12 is-offset-7 mt-6 login shadow animate__animated animate__fadeIn animate__slow">
                <form method='post' action='login.php'>
                    <label></label>
                    <h4 class="is-size-3">Ingrese sus datos para iniciar sesión.</h4>
                    <span class='error is-size-4'><h4 class="mt-3 mb-3">$error</h4></span>
                    <div class="field">
                        <label class="label">Usuario</label>
                        <div class="control">
                            <input class="input input is-info is-rounded" name="user" type="text" placeholder="Usuario" requiered>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Contraseña</label>
                        <div class="control">
                            <input class="input input is-info is-rounded" name="pass" type="password" placeholder="Contraseña" requiered>
                        </div>
                    </div>
                    <button class="mt-3 button is-primary" type='submit'>Iniciar sesión</button>
                    <a href="singup.php" class="mt-3 ml-4 button is-primary" type='submit'>Registrarse</a>
                    <br>
                    <p style="color:'white';"></p>
                </form>
            </div>
        </div>
    </div>
    _login;
}
//Si ya se está loggeado, redirije a la página principal
else
{
    //! Metadata para redirijir al index
    echo'
    <meta http-equiv="Refresh" content="0;url=index.php">
    </div></body></html>
    ';
}
?>