<?php
	require_once("connect.inc");

	if (!$mysqli = new my_mysqli($host, $user, $passwd, $db, $port, $socket)) {
		printf("[001] Cannot connect to the server using host=%s, user=%s, passwd=***, dbname=%s, port=%s, socket=%s\n",
			$host, $user, $db, $port, $socket);
	}

	if (!is_bool($tmp = $mysqli->autocommit(true)))
		printf("[002] Expecting boolean/any, got %s/%s\n", gettype($tmp), $tmp);

	if (!$mysqli->query('SET AUTOCOMMIT = 0'))
		printf("[003] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$res = $mysqli->query('SELECT @@autocommit as auto_commit'))
		printf("[004] [%d] %s\n", $mysqli->errno, $mysqli->error);

	$tmp = $res->fetch_assoc();
	$res->free_result();
	if ($tmp['auto_commit'])
		printf("[005] Cannot turn off autocommit\n");

	if (true !== ($tmp = $mysqli->autocommit( true)))
		printf("[006] Expecting true, got %s/%s\n", gettype($tmp), $tmp);

	if (!$res = $mysqli->query('SELECT @@autocommit as auto_commit'))
		printf("[007] [%d] %s\n", $mysqli->errno, $mysqli->error);
	$tmp = $res->fetch_assoc();
	$res->free_result();
	if (!$tmp['auto_commit'])
		printf("[008] Cannot turn on autocommit\n");

	if (!$mysqli->query('DROP TABLE IF EXISTS test_mysqli_autocommit_oo_table_1'))
		printf("[009] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$mysqli->query('CREATE TABLE test_mysqli_autocommit_oo_table_1(id INT) ENGINE = InnoDB')) {
		printf("[010] Cannot create test table, [%d] %s\n", $mysqli->errno, $mysqli->error);
	}

	if (!$mysqli->query('INSERT INTO test_mysqli_autocommit_oo_table_1(id) VALUES (1)'))
		printf("[011] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$mysqli->query('ROLLBACK'))
		printf("[012] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$res = $mysqli->query('SELECT COUNT(*) AS num FROM test_mysqli_autocommit_oo_table_1'))
		printf("[013] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if ((!$tmp = $res->fetch_assoc()) || (1 != $tmp['num']))
		printf("[014] Expecting 1 row in table test, found %d rows. [%d] %s\n",
			$tmp['num'], $mysqli->errno, $mysqli->error);

	$res->free_result();

	if (!$mysqli->query('DROP TABLE IF EXISTS test_mysqli_autocommit_oo_table_1'))
		printf("[015] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$mysqli->query('SET AUTOCOMMIT = 1'))
		printf("[016] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$res = $mysqli->query('SELECT @@autocommit as auto_commit'))
		printf("[017] [%d] %s\n", $mysqli->errno, $mysqli->error);

	$tmp = $res->fetch_assoc();
	$res->free_result();
	if (!$tmp['auto_commit'])
		printf("[018] Cannot turn on autocommit\n");

	if (true !== ($tmp = $mysqli->autocommit( false)))
		printf("[019] Expecting true, got %s/%s\n", gettype($tmp), $tmp);

	if (!$mysqli->query('CREATE TABLE test_mysqli_autocommit_oo_table_1(id INT) ENGINE = InnoDB')) {
		printf("[020] Cannot create test table, [%d] %s\n", $mysqli->errno, $mysqli->error);
	}

	if (!$mysqli->query('INSERT INTO test_mysqli_autocommit_oo_table_1(id) VALUES (1)'))
		printf("[021] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$mysqli->query('ROLLBACK'))
		printf("[022] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$res = $mysqli->query('SELECT COUNT(*) AS num FROM test_mysqli_autocommit_oo_table_1'))
		printf("[023] [%d] %s\n", $mysqli->errno, $mysqli->error);
	$tmp = $res->fetch_assoc();
	if (0 != $tmp['num'])
		printf("[24] Expecting 0 rows in table test, found %d rows\n", $tmp['num']);
	$res->free_result();

	if (!$mysqli->query('INSERT INTO test_mysqli_autocommit_oo_table_1(id) VALUES (1)'))
		printf("[025] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$mysqli->query('COMMIT'))
		printf("[025] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if (!$res = $mysqli->query('SELECT COUNT(*) AS num FROM test_mysqli_autocommit_oo_table_1'))
		printf("[027] [%d] %s\n", $mysqli->errno, $mysqli->error);

	if ((!$tmp = $res->fetch_assoc()) || (1 != $tmp['num']))
		printf("[028] Expecting 1 row in table test, found %d rows. [%d] %s\n",
			$tmp['num'], $mysqli->errno, $mysqli->error);
	$res->free_result();

	if (!$mysqli->query('DROP TABLE IF EXISTS test_mysqli_autocommit_oo_table_1'))
		printf("[029] [%d] %s\n", $mysqli->errno, $mysqli->error);

	$mysqli->close();
	print "done!";
?>
<?php
	$test_table_name = 'test_mysqli_autocommit_oo_table_1'; require_once("clean_table.inc");
?>