<?php
require_once 'data.php';
use Illuminate\Database\Capsule\Manager as DB;

$error = $user = $pass = $Rpass = $name = $ape = "";

    echo'
    <!-- Icon -->
    <link rel="shortcut icon" href="img/icons/login.png">
    <title>Singup</title>

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
        $Rpass = sanitizeString($_POST['Rpass']);
        $name = sanitizeString($_POST['name']);
        $ape = sanitizeString($_POST['ape']);

        //Validamos que las variables no estén vacías
        if($user == "" || $pass == "" || $Rpass == "" || $name == "" || $ape == "")
        {
            //Si lo están, mandamos un mensaje de error
            $error = 'Falta algún dato';
        }
        else
        {
            //Sino, seguimos con el código

            //Se validan que las contraseñas sean iguales
            if ($pass != $Rpass)
            {
                //Si no son iguales manda un error
                $error = "Las contraseñas no son iguales, inténtelo de nuevo";
                $pass = $Rpass = "";
            }
            else
            {
                //! Consulta si hay un user igual al que se intenta registrar
                $users = DB::table('users')->where('user',$user)->first();

                //Se comprueba si existe un user con las condiciones puestas
                if($users)
                {
                    //Manda un error puesto que hay un usuario con el mismo nombre
                    $error = "Ese usuario ya está ocupado";
                    $user = "";
                }
                else
                {
                    //? Inserción de los datos del nuevo usuario a la base de datos
                    $usuario = DB::table('users')->insertGetId(
                        ['user' => $user, 'pass' => $pass, 'idaccess' => '2', 'name' => $name, 'lastname' => $ape]
                    );

                    //? Aviso y metadata para la redirección
                    die("
                    <div class='check is-size-4'>
                        <meta http-equiv='Refresh' content='10;url=login.php'>
                        <h1>Registro exitoso, por favor, inicie sesión será redirigido en breve<h1>
                        <h1>sino, haga click <a href='login.php' class='linkL'>aquí</a></h1>
                    </div>
                    </div></body></html>");
                }
            }
        }
    }

    //? Módulo de Singup
    echo<<<_singup
        <div class="columns">
            <div class="column is-12 is-offset-7 mt-6 mb-4 login shadow animate__animated animate__fadeIn animate__slow">
                <form method='post' action='singup.php'>
                    <label></label>
                    <h4 class="is-size-3">Ingrese sus datos para registrarse.</h4>
                    <span class='error is-size-4'><h4 class="mt-3 mb-3">$error</h4></span>
                    <div class="field">
                        <label class="label" for="user">Usuario</label>
                        <div class="control">
                            <input value="$user" class="input input is-info is-rounded" name="user" id="user" type="text" placeholder="Usuario" requiered>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="name">Nombre</label>
                        <div class="control">
                            <input value="$name" class="input input is-info is-rounded" name="name" id="name" type="text" placeholder="Nombre" requiered>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="ape">Apellido</label>
                        <div class="control">
                            <input value="$ape" class="input input is-info is-rounded" name="ape" id="ape" type="text" placeholder="Apellido" requiered>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="pass">Contraseña</label>
                        <div class="control">
                            <input value="$pass" class="input input is-info is-rounded" name="pass" id="pass" type="password" placeholder="Contraseña" requiered>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label" for="Rpass">Repetir Contraseña</label>
                        <div class="control">
                            <input value="$Rpass" class="input input is-info is-rounded" name="Rpass" id="Rpass" type="password" placeholder="Contraseña" requiered>
                        </div>
                    </div>
                    <button class="mt-3 button is-primary" type='submit'>Registrarse</button>
                    <a href="login.php" class="mt-3 ml-4 button is-primary" type='submit'>Iniciar sesión</a>
                    <br>
                    <p style="color:'white';"></p>
                </form>
            </div>
        </div>
    </div>
    _singup;
}
//Si ya se está loggeado, redirije a la página principal
else
{
    //! Metadata para enviar al index
    echo'
    <meta http-equiv="Refresh" content="0;url=index.php">
    </div></body></html>
    ';
}
?>