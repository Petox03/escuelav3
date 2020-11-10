<?php
require_once 'data.php';
use Illuminate\Database\Capsule\Manager as DB;

$error =  "";
    echo'
    <!-- Icon -->
    <link rel="shortcut icon" href="img/icons/delete.png">
    <title>Delete</title>

</head>

<body>
';

//Se valdia si hay una sesión existente
if($loggedin)
{
    $C_alumnos = DB::table('materias')->count();

    $alumnos = DB::table('materias')
        ->leftJoin('users', 'materias.users_id_user', '=', 'users.id_user')
        ->orderBy('lastname')
        ->get();

    if($access == 1)
    {
        //! Llama a el header
        require_once 'header.php';


        if(isset($_GET['id_del']))
        {
            $id_user = $_GET['id_del'];

            $name = DB::table('users')->where('id_user', $id_user)->first();

            $del = DB::table('materias')->where('users_id_user', $id_user)->delete();

            if($del)
            {
                die("
                <div class='error center is-size-4'>
                        <meta http-equiv='Refresh' content='3;url=delete.php'>
                        <h1>Calificaciones del alumno(a) ". $name->name . " " . $name->lastname ." Eliminadas<h1>
                    </div>
                    </div></body></html>
                ");
            }
        }

        echo'
        <div class="container mb-4">
            <div class="columns">
                <div class="del shadow is-12 is-offset-7 animate__animated animate__fadeIn animate__slow mt-6 padDelete">
        ';

            if($C_alumnos == 0)
            {
                echo'<h1 class="error">No hay alumnos con calificaciones</h1>';
            }
            else
            {
                foreach($alumnos as $u)
                {
                    echo'
                    <div class="container is-fluid">
                        <br>
                        <FONT size="5" class="fonttxt">'. $u->name . " " . $u->lastname . '</FONT> <a type="button" style="color: white;" class="button linkdel is-primary ml-6" href="delete.php?id_del='.$u->id_user.'">Delete</a>
                        <br>
                        <br>
                        </div>
                    ';
                }
            }

        echo'
                </div>
            </div>
        </div>
        ';
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