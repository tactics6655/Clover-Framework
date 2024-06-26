<?php

declare(strict_types=1);

namespace Clover\Classes\Database\Driver;

use Clover\Classes\Data\ArrayObject;
use PDO;
use Exception;

class PHPDataObject extends PDO
{

	private ?array $options = null;

	private string $port = "3306";

	private string $username;

	private string $password;

	private string $hostname;

	private string $database;

	public function __construct()
	{
	}

	public function setPort($port)
	{
		$this->port = $port;
	}

	public function setHostName($hostname)
	{
		$this->hostname = $hostname;
	}

	public function setDatabase($database)
	{
		$this->database = $database;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function connect()
	{
		try {
			$dns = ('mysql:' . implode(';', isset($this->database) ? [
				'host=' . $this->hostname,
				'port=' . $this->port,
				'dbname=' . $this->database,
			] : [
				'host=' . $this->hostname
			]));

			parent::__construct($dns, $this->username, $this->password, $this->options);
		} catch (Exception $e) {
			throw new Exception($this->getLocalizedErrorMessage($e));
		}
	}

	public function getDriverName()
	{
		return $this->getAttribute(PDO::ATTR_DRIVER_NAME);
	}

	public function getTimeout()
	{
		return $this->getAttribute(PDO::ATTR_TIMEOUT);
	}

	public function getUpdateQuery($table, $columns, $values)
	{
		$setter = new ArrayObject();
		$columns = new ArrayObject($columns);
		foreach ($columns as $index => $column) {
			$setter->addWithKey($column, $values[$index]);
		}

		$query = "UPDATE $table SET {$setter->join(',')}";

		return $query;
	}

	public function insertByArray($table, $columns, $values)
	{
		$column_count = count($columns);
		$column_separated = implode(",", $columns);
		$placeholder_separated = implode(",", array_fill(0, $column_count, "?"));
		$sql = "INSERT INTO $table ($column_separated) VALUES ($placeholder_separated)";

		$stmt = $this->prepare($sql);
		$stmt->execute($values);
	}

	public function getLocalizedErrorMessage($e)
	{
		$errCode = $e->errorInfo[1];
		$errCodeArguments = preg_match_all("|(?:\')(.*)(?:\')|U", $e->errorInfo[2], $matches);
		if (isset($matches)) {
			$errCodeArguments = $matches[1];
		}

		$message = "";
		switch ($errCode) {
			case "1004":
				$message = sprintf("%s 파일을 정상적으로 생성할 수 없습니다.", $errCodeArguments[0]);
				break;
			case "1005":
				$message = sprintf("%s 테이블을 정상적으로 생성할 수 없습니다.", $errCodeArguments[0]);
				break;
			case "1006":
				$message = sprintf("%s 데이터베이스를 생성할 수 없습니다. (%s 라인)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1007":
				$message = sprintf("%s 데이터베이스는 이미 존재하기 때문에 생성할 수 없습니다.", $errCodeArguments[0]);
				break;
			case "1008":
				$message = sprintf("%s 데이터베이스가 존재하지 않아 제거할 수 없습니다.", $errCodeArguments[0]);
				break;
			case "1009":
				$message = sprintf("%s 데이터베이스를 제거할 수 없습니다 (%s 라인).", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1010":
				$message = sprintf("%s 데이터베이스 디렉토리를 제거할 수 없습니다 (%s 라인).", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1011":
				$message = sprintf("%s 데이터베이스 파일을 제거할 수 없습니다 (%s 라인).", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1012":
				$message = "시스템 테이블 내의 레코드를 읽을 수 없습니다.";
				break;
			case "1013":
				$message = sprintf("%s 상태를 읽어올 수 없습니다. (%s 라인)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1014":
				$message = sprintf("작업중인 디렉토리에는 접근할 수 없습니다 (%s 라인)", $errCodeArguments[0]);
				break;
			case "1015":
				$message = sprintf("잠겨진 파일에는 접근할 수 없습니다 (%s 라인)", $errCodeArguments[0]);
				break;
			case "1016":
				$message = sprintf("%s 파일은 작업중이므로 접근할 수 없습니다 (%s 라인)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1017":
				$message = sprintf("%s 파일을 찾을 수 없습니다 (%s 라인)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1018":
				$message = sprintf("%s 폴더를 읽을 수 없습니다 (%s 라인)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1019":
				$message = sprintf("%s 폴더를 변경할 수 없습니다 (%s 라인)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1020":
				$message = sprintf("%s 테이블에서 마지막 읽기 이후로 레코드가 변경되었습니다.", $errCodeArguments[0]);
				break;
			case "1021":
				$message = sprintf("디스크 용량이 부족합니다 (현재 디스크 점유율 : %s)", $errCodeArguments[0]);
				break;
			case "1022":
				$message = sprintf("%s 테이블에서 중복키는 작성할 수 없습니다.", $errCodeArguments[0]);
				break;
			case "1023":
				$message = sprintf("%s를 닫는 도중에 오류가 발생하였습니다 (라인 %s)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1024":
				$message = sprintf("%s 파일을 읽어오는 도중에 오류가 발생하였습니다 (라인 %s)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1025":
				$message = sprintf("%s 파일의 이름을 %s에서 %s로 변경하는 도중에 오류가 발생하였습니다 (라인 %s)", $errCodeArguments[0], $errCodeArguments[1], $errCodeArguments[2]);
				break;
			case "1026":
				$message = sprintf("%s 파일을 쓰는 도중에 오류가 발생하였습니다 (라인 %s)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1027":
				$message = sprintf("%s 파일이 변경금지상태입니다.", $errCodeArguments[0]);
				break;
			case "1028":
				$message = "정렬이 중단되었습니다.";
				break;
			case "1029":
				$message = sprintf("%s의 %s 뷰(View)가 존재하지 않습니다", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1030":
				$message = sprintf("저장소 엔진에서 %s 오류가 발생하였습니다", $errCodeArguments[0]);
				break;
			case "1031":
				$message = sprintf("%s의 테이블 저장소 엔진에는 이옵션이 존재하지 않습니다.", $errCodeArguments[0]);
				break;
			case "1032":
				$message = sprintf("%s에서 레코드를 찾을 수 없습니다.", $errCodeArguments[0]);
				break;
			case "1033":
				$message = sprintf("%s파일에서 잘못된 정보를 발견하였습니다.", $errCodeArguments[0]);
				break;
			case "1034":
				$message = sprintf("%s 테이블에서 잘못된 키 파일을 발견하였습니다. 복구를 시도하십시오.", $errCodeArguments[0]);
				break;
			case "1035":
				$message = sprintf("%s 테이블에서 오래된 키 파일을 발견하였습니다. 복구를 시도하십시오.", $errCodeArguments[0]);
				break;
			case "1036":
				$message = sprintf("%s 테이블은 읽기전용입니다.", $errCodeArguments[0]);
				break;
			case "1037":
				$message = sprintf("메모리가 부족합니다. 서버를 재시작하고 다시 시도하십시오 (%d bytes 필요)", $errCodeArguments[0]);
				break;
			case "1038":
				$message = sprintf("정렬 메모리가 부족합니다. 서버 정렬 버퍼 크기(/etc/mysql/my.cnf의 sort_buffer_size)를 늘리십시오.", $errCodeArguments[0]);
				break;
			case "1039":
				$message = sprintf("%s 파일을 읽는 도중에 예기치 않은 EOF 발견 (라인 %s)", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1040":
				$message = "지나치게 많은 접속량";
				break;
			case "1041":
				$message = "메모리가 부족합니다. mysqld 또는 다른 프로세스가 사용 가능한 모든 메모리를 사용하고 있는지 확인하십시오. 그렇지 않은 경우 mysqld가 더 많은 메모리를 사용하거나 더 많은 스왑 공간을 추가할 수 있도록 'ulimit' 옵션을 사용해야 할 수 있습니다.";
				break;
			case "1042":
				$message = "주소에 대한 호스트 이름을 가져올 수 없습니다.";
				break;
			case "1043":
				$message = "잘못된 핸드쉐이크(Handshake).";
				break;
			case "1044":
				$message = sprintf("%s@%s 계정이 %s 데이터베이스에 접근이 제한되었거나 데이터베이스에 접속할 수 없습니다.", $errCodeArguments[0], $errCodeArguments[1], $errCodeArguments[2]);
				break;
			case "1045":
				$message = sprintf("%s@%s 계정이 존재하지 않거나 접속 계정정보가 잘못되었습니다.", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1046":
				$message = "데이터베이스가 선택되지 않았습니다.";
				break;
			case "1047":
				$message = "알 수 없는 명령어입니다.";
				break;
			case "1048":
				$message = sprintf("%s 컬럼값은 null이 될 수 없습니다(IS NOT null).", $errCodeArguments[0]);
				break;
			case "1049":
				$message = sprintf("%s는 알 수 없는 데이터베이스입니다.", $errCodeArguments[0]);
				break;
			case "1050":
				$message = sprintf("%s 테이블은 이미 존재합니다.", $errCodeArguments[0]);
				break;
			case "1051":
				$message = sprintf("%s는 알 수 없는 테이블입니다.", $errCodeArguments[0]);
				break;
			case "1052":
				$message = sprintf("%s의 %s 컬럼이 모호합니다.", $errCodeArguments[0], $errCodeArguments[1]);
				break;
			case "1053":
				$message = "서버가 종료중입니다.";
				break;
			case "1054":
				$message = sprintf("'%s'에서의 알 수 없는 컬럼 '%s'", $errCodeArguments[1], $errCodeArguments[0]);
				break;
			case "1055":
				$message = sprintf("%s는 'GROUP BY'절 내부에 존재하지 않습니다", $errCodeArguments[0]);
				break;
			case "1056":
				$message = sprintf("%s로 그룹화할 수 없습니다.", $errCodeArguments[0]);
				break;
			case "1057":
				$message = "문장에 합산 함수 및 열에 동일 문장에 있음";
				break;
			case "1058":
				$message = "열 개수가 값 개수와 일치하지 않음";
				break;
			case "1059":
				$message = sprintf("식별자 이름 %s가 지나치게 깁니다.", $errCodeArguments[0]);
				break;
			case "1060":
				$message = sprintf("%s는 중복된 열 이름입니다", $errCodeArguments[0]);
				break;
			case "1061":
				$message = sprintf("%s는 중복된 키 이름입니다", $errCodeArguments[0]);
				break;
			case "1062":
				$message = sprintf("%s키의 %s 항목이 중복되었습니다.", $errCodeArguments[0], $errCodeArguments[0]);
				break;
			case "1063":
				$message = sprintf("%s열에 잘못된 열 지정자를 지정하였습니다.", $errCodeArguments[0]);
				break;
			case "1064":
				$message = sprintf("%s라인 %d의 '%s' 근처에서 파싱 오류가 발생하였습니다.", $errCodeArguments[0], $errCodeArguments[1], $errCodeArguments[2]);
				break;
			case "1146":
				$message = sprintf("%s 테이블이 존재하지 않습니다.", $errCodeArguments[0]);
				break;
			case "2002":
				$message = sprintf("SQLSTATE[HY000] [2002] 연결이 거부되었습니다.");
				break;
			default:
				break;
		}

		return $message;
	}

	/**
	 * @param \PDOStatement $statement
	 */
	public function fetch($statement, $type)
	{
		switch ($type) {
			case 'all':
				$res = $statement->fetchAll(PDO::FETCH_ASSOC);
				break;
			case 'one':
				$res = $statement->fetch()[0] ?? "";
				break;
			case 'self':
				$res = $statement->fetch(PDO::FETCH_ASSOC);
				break;
			case 'column':
				$res = $statement->fetchColumn(PDO::FETCH_ASSOC);
				break;
			case 'alias':
				$res = $statement->fetch(PDO::FETCH_NAMED);
				break;
			case 'number':
				$res = $statement->fetch(PDO::FETCH_NUM);
				break;
			case 'both':
				$res = $statement->fetch(PDO::FETCH_BOTH);
				break;
			case 'object':
				$res = $statement->fetch(PDO::FETCH_OBJ);
				break;
			default:
				$res = $statement->fetchAll(PDO::FETCH_ASSOC);
				break;
		}

		return $res;
	}
}
