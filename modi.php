<?php
require_once 'data.php';
use Illuminate\Database\Capsule\Manager as DB;

$error2 =  "";
    echo'
    <!-- Icon -->
    <link rel="shortcut icon" href="img/icons/edit.png">
    <title>Modify</title>

</head>

<body>
';
//Se valdia si hay una sesión existente
if($loggedin)
{
    if($access == 1)
    {
        //! Llama a el header
        require_once 'header.php';

        if(isset($_POST['alumn']))
        {
            //*Escapamos variables
            $alumno = sanitizeString($_POST['alumn']);
            $cali1 = sanitizeString($_POST['cali1']);
            $cali2 = sanitizeString($_POST['cali2']);
            $cali3 = sanitizeString($_POST['cali3']);

            //!Update de las calificaciones
            $Ncali = DB::table('materias')
                ->where('users_id_user', $alumno)
                ->update(['español' => $cali1, 'matematicas' => $cali2, 'historia' => $cali3]);

            //!Consulta para saber el nombre del alumno
            $name = DB::table('users')->where('id_user', $alumno)->first();

            //Validamos que se haya modificado las calificaciones del alumno
            if($Ncali)
            {
                die("
                <div class='check is-size-4'>
                        <meta http-equiv='Refresh' content='3;url=modi.php'>
                        <h1>Calificaciones del alumno(a) ". $name->name . " " . $name->lastname ." modificadas<h1>
                    </div>
                    </div></body></html>
                ");
            }
            else
            {
                $error2 = "Algo ha ido mal, por favor, inténtalo de nuevo";
            }
        }

        //? Consulta a la base de datos, llama alumnos
        $users = DB::table('materias')
        ->leftJoin('users', 'materias.users_id_user', '=', 'users.id_user')
        ->orderBy('lastname')
        ->get();

        //? Módulo para elegir alumnos
        echo'
        <div class="container animate__animated animate__fadeIn animate__slow mt-3 mb-4">
            <form method="post" action="modi.php">
                <label></label>
                <h4 class="is-size-3">Selecciona un alumno.</h4>
                <div class="field">
                    <label class="label" for="alumn">Alumno</label>
                    <div class="control">
                        <div class="select is-medium">
                            <select name="id_alumno" required>
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
                <button class="mt-3 button is-primary" type="submit">Seleccionar</button>
                <br>
            </form>
        </div>
        ';

        //Validamos que el id se haya mandado
        if(isset($_POST['id_alumno']))
        {
            //Recogemos el id en una variable
            $id_alumno = $_POST['id_alumno'];

            //!Consulta para saber de que alumno se eligió, y se une la tabla materias para saber sus calificaciones
            $alumno = DB::table('materias')->where('users_id_user', $id_alumno)
            ->leftJoin('users', 'materias.users_id_user', '=', 'users.id_user')
            ->first();

            //Se valida que el alumno exista
            if($alumno)
            {
                //?Módulo para cambiar calificaciones
                echo'
                <div class="container animate__animated animate__fadeIn animate__slow mt-3 mt-4 mb-4">
                    <form method="post" action="modi.php">
                        <label></label>
                        <h4 class="is-size-3">Modifica las calificaciones del alumno(a) '. $alumno->name . " " . $alumno->lastname .'.</h4>
                        <span class="error is-size-4"><h4 class="mt-3 mb-3">'.$error2.'</h4></span>
                        <div class="field">
                            <div class="control">
                            <input class="input" type="text" name="id_alumno" value="'. $alumno->id_user .'" hidden>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                            <input class="input" type="text" name="alumn" value="'. $alumno->id_user .'" hidden>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label" for="cali1">Español</label>
                            <div class="control">
                                <input class="input" type="number" id="cali1" name="cali1" min="1" max="10" value="'. $alumno->español .'" placeholder="Español">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label" for="cali2">Matemáticas</label>
                            <div class="control">
                                <input class="input" type="number" id="cali2" name="cali2" min="1" max="10" value="'. $alumno->matematicas .'" placeholder="Matemáticas">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label" for="cali3">Historia</label>
                            <div class="control">
                                <input class="input" type="number" id="cali3" name="cali3" min="1" max="10" value="'. $alumno->historia .'" placeholder="Historia">
                            </div>
                        </div>
                        <button class="mt-3 button is-primary" type="submit">Modificar</button>
                    </form>
                </div>
                ';
            }
            else
            {
                echo'
                <div class="container animate__animated animate__fadeIn animate__slow mt-3 mt-4 mb-4">
                    <span class="error is-size-4"><h4 class="mt-3 mb-3">Ese alumno no tiene calificaciones, por favor agruege las calificaciones o haga click <a href="index.php" class="linkM">aquí</a></h4></span>
                </div>
                ';
            }
        }
        echo"
        </body>

        </html>
        ";
    }
    else{
        echo'<meta http-equiv="Refresh" content="0;url=index.php">';
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