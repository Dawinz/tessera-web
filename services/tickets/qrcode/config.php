<?php
require_once dirname(__DIR__) . '/includes/sr_env.php';

class RaviKoQr
{
	public $conn;

	public function __construct()
	{
		$this->conn = sr_mysqli('ticket');
		if (!$this->conn) {
			die('connection failed');
		}
	}

	public function insertQr($qrUname, $final, $qrimage, $qrlink, $seat_id)
	{
		$sql = "INSERT INTO qrcodes(qrUsername,qrContent,qrImg,qrlink,id3) VALUES('$qrUname','$final','$qrimage','$qrlink','$seat_id')";
		$query = $this->conn->query($sql);
		return $query;
	}

	public function displayImg()
	{
		$sql = "SELECT qrimg,qrlink from qrcodes where qrimg='$qrimage'";
	}
}
$meravi = new RaviKoQr();
