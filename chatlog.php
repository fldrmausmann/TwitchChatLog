<?php
error_reporting(E_ALL);
// Prevent PHP from stopping the script after 30 sec
set_time_limit(0);
date_default_timezone_set('Europe/Berlin');
$script_tz = date_default_timezone_get();
$mysqlhost="localhost";
$mysqluser="user";
$mysqlpass="password";
$mysqldata="twitchchat";
$mysqli = new mysqli($mysqlhost,$mysqluser,$mysqlpass,$mysqldata);
if ($mysqli->connect_error) {
  die('Verbindungsfehler (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}
if (!mysqli_set_charset($mysqli, "utf8")) {
  printf("Error loading character set utf8: %s\n", mysqli_error($mysqli));
}

$channellist = array(
  "#channel1",
  "#channel2"
);
$server = "irc.chat.twitch.tv";
$port = 6667;
$nick = "ircuser";
$pass = "ircoauth";

$socket = fsockopen("$server", $port);
if (!$socket) {
    printf("errno: %s, errstr: %s", $errno, $errstr);
}

fputs($socket,"PASS $pass\n");
fputs($socket,"NICK $nick\n");
foreach($channellist as $chan) {
	fputs($socket,"JOIN ".$chan."\n");
}

// keep the program executing
while(1) {
  // get the data from server
  while($data = fgets($socket, 1024)) {
    // echo the data received to page
    #echo nl2br($data);
    // flush old data, it isn't needed any longer.
    flush();
    // We split data by whitespace for later use
    echo $data;
    $ex = explode(' ', $data);
    // check for ping from server - pong back
    if($ex[0] == "PING") fputs($socket, "PONG ".$ex[1]."\n");
    // Regular expression to split the data for what is needed.
    $search_string = "/^:([a-z0-9_\-]+)![a-z0-9\-_]+@[a-z0-9\-_]+[\.]?[a-z.0-9\-]+\s([A-Z]+)\s(#[a-z0-9\-_]+)\s:(.*)/"; 
    $do = preg_match($search_string, $data, $matches);
    // check that there is a command received
    if(isset($matches['2'])) {
      switch($matches['2']) {
        case "PRIVMSG":
          $user = $matches['1'];
          $channel = $matches['3'];
	        if (isset($matches[4])) {
            $chat_text = $matches[4];
            $query = "INSERT INTO chat (time, channel, user, message) VALUES ('".date('Y-m-d H:i:s')."','".$channel."','".$user."','".test_input($chat_text)."')";
            $result = $mysqli->query($query);
          }
        break;
      }
    }
  }
}
function test_input($data) {
  $data = trim($data);
  $data = htmlentities($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
