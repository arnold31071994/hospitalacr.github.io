<?php 
	const SERVER="localhost";
	const DB="hospital";
	const USER="root";
	const PASS="";

	const SGBD="mysql:host=".SERVER.";dbname=".DB;

	const METHOD="AES-256-CBC";
	const SECRET_KEY='$ARNOLD@2020';
	const SECRET_IV= '037970';

	const SERVER2="localhost";
	const DB2="dbo";
	const USER2="root";
	const PASS2="";

	const JCE="mysql:host=".SERVER2.";dbname=".DB2;


	const SQLSERVER="186.149.198.216";
	const SQLBD="SAMARITANO_TEST_21022020";
	const SQLUSER="pyc";
	const SQLPASS="hopema";

	const SQL="sqlsrv:server=".SQLSERVER.";Database=".SQLBD;


/*$con=mysqli_init(); mysqli_ssl_set($con, NULL, NULL, {ca-cert filename}, NULL, NULL); mysqli_real_connect($con, "alucard007.mysql.database.azure.com", "arnold@alucard007", {your_password}, {your_database}, 3306);*/

 ?>