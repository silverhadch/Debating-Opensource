<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function getPDOConnection() {
    $config = include('config/config_pdo.php');
    $driver = $config['driver'];
    $server = $config['server'];
    $dbname = $config['dbname'];
    $username = $config['username'];
    $password = $config['password'];

    switch ($driver) {
        case 'mysql':
        case 'mariadb':
            $dsn = "mysql:host=$server;dbname=$dbname";
            break;
        case 'pgsql':
            $dsn = "pgsql:host=$server;dbname=$dbname";
            break;
        case 'sqlite':
            $dsn = "sqlite:$dbname";
            break;
        case 'sqlsrv':
            $dsn = "sqlsrv:Server=$server;Database=$dbname";
            break;
        case 'oci':  // Oracle
            $dsn = "oci:dbname=//$server/$dbname";
            break;
        case 'ibm':  // IBM DB2
            $dsn = "ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE=$dbname;HOSTNAME=$server;PORT=50000;PROTOCOL=TCPIP;";
            break;
        case 'odbc':  // ODBC
            $dsn = "odbc:DSN=$dbname";
            break;
        case 'firebird':  // Firebird
            $dsn = "firebird:dbname=$server:$dbname";
            break;
        case 'cubrid':  // CUBRID
            $dsn = "cubrid:host=$server;port=33000;dbname=$dbname";
            break;
        case 'informix':  // Informix
            $dsn = "informix:host=$server;service=1526;database=$dbname;server=ids_server;protocol=onsoctcp;";
            break;
        case 'access':  // Microsoft Access
            $dsn = "odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=$dbname;";
            break;
        case 'sybase':  // Sybase
            $dsn = "odbc:Driver={Adaptive Server Enterprise};Server=$server;Database=$dbname;";
            break;
        default:
            throw new Exception("Unsupported database driver: $driver");
    }

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return null;
    }
}
$connection_pdo = getPDOConnection();
?>
