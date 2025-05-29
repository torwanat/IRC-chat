<?php
$time = time();
$last_id = "";
$current_id = "";
if (isset($_POST)) {
    $data = file_get_contents("php://input");
    $last_id = json_decode($data, true);
}
$res = array(
    "status" => null,
    "message" => "",
    "currentId" => "",
    "nick" => "",
    "timestamp" => "",
    "color" => ""
);

$memcache = memcache_connect('localhost', 11211);

header('Content-Type: application/json');

function getData()
{
    $current_content = $GLOBALS["memcache"]->get("content_key");
    if (!isset($current_content["id"])) {
        return false;
    }
    $current_id = $current_content["id"];
    if ($GLOBALS["last_id"] == "0") {
        $GLOBALS["res"]["currentId"] = $current_id;
        return true;
    } else if ($GLOBALS["last_id"] != $current_id) {
        $GLOBALS["res"]["message"] = $current_content["message"];
        $GLOBALS["res"]["nick"] = $current_content["nick"];
        $GLOBALS["res"]["currentId"] = $current_id;
        $GLOBALS["res"]["timestamp"] = $current_content["timestamp"];
        $GLOBALS["res"]["color"] = $current_content["color"];
        return true;
    }
    return false;
}

while (time() - $time < 5) {
    if (getData()) {
        $res["status"] = "NEW";
        break;
    }
    $res["status"] = null;
    $res["currentId"] = $last_id;
    usleep(100);
}

echo json_encode($res);
$res["status"] = null;

?>