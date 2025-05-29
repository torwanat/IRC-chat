<?php
$colors = ["#000000", "#FF0000", "#00FF00", "#0000FF", "#FFFF00", "#00FFFF", "#FF00FF", "#F0F0F0", "#0F0F0F", "#F0000F", "#0FFFF0"];
$memcache = memcache_connect('localhost', 11211);
$users = $memcache->get("colors");
$content = array(
    "message" => "",
    "id" => "",
    "nick" => "",
    "timestamp" => "",
    "color" => "",
);
if (isset($_POST)) {
    $data = file_get_contents("php://input");
    $json = json_decode($data, true);
    $nick = $json["nick"];
    if ($json["message"][0] == "/") {
        $command = explode(" ", $json["message"])[0];
        switch ($command) {
            case '/color':
                if (count(explode(" ", $json["message"])) > 1) {
                    if (isset($users)) {
                        $users[$nick] = explode(" ", $json["message"])[1];
                        $memcache->set("colors", $users);
                    } else {
                        $memcache->set("colors", array(
                            $nick => explode(" ", $json["message"])[1]
                        ));
                    }
                    $content["message"] = "Użytkownik " . $nick . " ma teraz kolor: " . explode(" ", $json["message"])[1];
                }
                break;
            case '/nick':
                if (count(explode(" ", $json["message"])) > 1) {
                    $new_nick = explode(" ", $json["message"])[1];
                    if (isset($users)) {
                        if (isset($users[$nick])) {
                            $oldColor = $users[$nick];
                            unset($users[$nick]);
                            $users[$new_nick] = $oldColor;
                            $memcache->set("colors", $users);
                        } else {
                            $newColor = $colors[array_rand($colors)];
                            $users[$new_nick] = $newColor;
                            $memcache->set("colors", $users);
                        }
                    } else {
                        $newColor = $colors[array_rand($colors)];
                        $memcache->set("colors", array(
                            $new_nick => $newColor
                        ));
                    }
                    $content["message"] = "Użytkownik " . $nick . " nazywa się teraz: " . $new_nick;
                }
                break;
            case '/quit':
                if (isset($users)) {
                    unset($users[$nick]);
                    $memcache->set("colors", $users);
                }
                $content["message"] = "Użytkownik " . $nick . " opuścił czat";
                break;
            default:
                break;
        }
        $content["nick"] = "SYSTEM";
        $content["color"] = "#000000";
        $content["timestamp"] = date("H:i");
        $id = rand(0, 1000000) . "." . rand(0, 1000000);
        $content["id"] = $id;
        $memcache->set("content_key", $content);
    } else {
        $color = "";
        if (isset($users)) {
            if (isset($users[$nick])) {
                $color = $users[$nick];
            } else {
                $color = $colors[array_rand($colors)];
                $users[$nick] = $color;
                $memcache->set("colors", $users);
            }
        } else {
            $color = $colors[array_rand($colors)];
            $memcache->set("colors", array(
                $nick => $color
            ));
        }
        $content["message"] = $json["message"];
        $content["nick"] = $nick;
        $content["color"] = $color;
        $content["timestamp"] = date("H:i");
        $id = rand(0, 1000000) . "." . rand(0, 1000000);
        $content["id"] = $id;
        $memcache->set("content_key", $content);
    }
}

?>