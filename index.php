<?php
require_once 'data.php';
use Illuminate\Database\Capsule\Manager as DB;

$error = "";

if($loggedin)
{
    if($access == 2)
    {
        echo'
        <!-- Icon -->
        <link rel="shortcut icon" href="img/icons/escuela.png">
        ';
    }
    else
    {
        echo'
        <!-- Icon -->
        <link rel="shortcut icon" href="img/icons/add.png">
        ';
    }
}
echo'
    <title>Home</title>
</head>

<body>
';

//Se valdia si hay una sesión existente
if($loggedin)
{
    //! Llama a el header
    require_once 'header.php';

    //? Maestro
    if($access == 1){

        //se valida que alumn no esté vacio
        if(!empty($_POST['alumn']))
        {
            //*Escapamos variables
            $alumno = sanitizeString($_POST['alumn']);
            $cali1 = sanitizeString($_POST['cali1']);
            $cali2 = sanitizeString($_POST['cali2']);
            $cali3 = sanitizeString($_POST['cali3']);

            //!Consulta para saber el nombre del alumno
            $name = DB::table('users')->where('id_user', $alumno)->first();

            //!Consulta para validar que no haya un alumno con el mismo id en la tabla
            $validar = DB::table('materias')->where('users_id_user',$alumno)
            ->first();

            //validamos si la consulta es cierta o no
            if(!$validar)
            {
                //!Inserción de calificaciones
                $calificaciones = DB::table('materias')->insertOrIgnore(
                    ['users_id_user' => $alumno, 'español' => $cali1, 'matematicas' => $cali2, 'historia' => $cali3]
                );
                die("
                    <div class='check is-size-4'>
                        <meta http-equiv='Refresh' content='3;url=index.php'>
                        <h1>Calificaciones del alumno(a) ". $name->name . " " . $name->lastname ." agregadas<h1>
                    </div>
                    </div></body></html>
                ");
            }
            else{
                $error = "Ese alumno ya tiene calificaciones";
            }
        }


        //? Consulta a la base de datos, llama alumnos
        $users = DB::table('users')->where('id_user',"<>",1)->orderBy('lastname')->get();

        //? módulo de adhesión de calificaciones de maestro
        echo'
        <div class="container animate__animated animate__fadeIn animate__slow mt-3 mb-4">
            <form method="post" action="index.php" name="miformulario">
                <label></label>
                <h4 class="is-size-3">Ingrese las calificaciones de un alumno.</h4>
                <span class="error is-size-4"><h4 class="mt-3 mb-3">'.$error.'</h4></span>
                <div class="field">
                    <label class="label" for="alumn">Alumno</label>
                    <div class="control">
                        <div class="select is-medium">
                            <select id="alumn" name="alumn" required>
                            ';
                                foreach($users as $u)
                                {
                                    echo'<option value="'.$u->id_user.'">'. $u->name . " " . $u->lastname .'</option>';
                                }
        echo'
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="cali1">Español</label>
                    <div class="control">
                        <input class="input" type="number" id="cali1" name="cali1" min="1" max="10" value="0" placeholder="Español">
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="cali2">Matemáticas</label>
                    <div class="control">
                        <input class="input" type="number" id="cali2" name="cali2" min="1" max="10" value="0" placeholder="Matemáticas">
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="cali3">Historia</label>
                    <div class="control">
                        <input class="input" type="number" id="cali3" name="cali3" min="1" max="10" value="0" placeholder="Historia">
                    </div>
                </div>
                <button class="mt-3 button is-primary" type="submit">Agregar</button>
                <br>
            </form>
        ';
    }
    //?alumnos
    else{

        //!consulta para saber las calificaciones de los alumnos
        $cali = DB::table('materias')->where('users_id_user', $id)->first();

        echo'<div class="container animate__animated animate__fadeIn animate__slow mt-3 mb-4">';

        if($cali)
        {
            echo<<<_cali
                <form method="post">
                    <label></label>
                    <h4 class="is-size-3">Calificaciones de '$name  $lastname'</h4>
                    <br>
                    <div class="field">
                        <label class="label">Español</label>
                        <div class="control">
                            <input class="input" type="text" value="$cali->español" readonly>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Matemáticas</label>
                        <div class="control">
                            <input class="input" type="text" value="$cali->matematicas" readonly>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Historia</label>
                        <div class="control">
                            <input class="input" type="text" value="$cali->historia" readonly>
                        </div>
                    </div>
                    <br>
            _cali;

            $prom = ($cali->español + $cali->matematicas + $cali->historia)/3;

            echo<<<_cali2
                    <div class="field">
                        <label class="label">Promedio general</label>
                        <div class="control">
                            <input class="input" type="text" value="$prom" readonly>
                        </div>
                    </div>
                </form>
            _cali2;
        }
        else{
            echo'<h1 class="is-size-1 error center ml-6">Aún no hay calificaciones</h1>';
        }
        echo'</div>';
    }

    echo"
    </body>

    </html>
    ";
}
//Si no hay sesión activa redirije al login
else
{
    //! Metadata para redirijir al index
    echo'
    <meta http-equiv="Refresh" content="0;url=index.php">
    </div></body></html>
    ';
}
?>