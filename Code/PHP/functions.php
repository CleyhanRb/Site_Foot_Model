<?php 

function get_base_url() {

    $protocol = filter_input(INPUT_SERVER, 'HTTPS');
    if (empty($protocol)) {
        $protocol = "http";
    }

    $host = filter_input(INPUT_SERVER, 'HTTP_HOST');

    $request_uri_full = filter_input(INPUT_SERVER, 'REQUEST_URI');
    $last_slash_pos = strrpos($request_uri_full, "/");
    if ($last_slash_pos === FALSE) {
        $request_uri_sub = $request_uri_full;
    }
    else {
        $request_uri_sub = substr($request_uri_full, 0, $last_slash_pos + 1);
    }

    return $protocol . "://" . $host . $request_uri_sub;

}

function checkConnected(){
   if (isset($_SESSION['user_id']) && isset($_SESSION['user_username']) && isset($_SESSION['user_email']) && isset($_SESSION['user_rank']) && isset($_SESSION['user_secured'])) return true;
   else return false;
}

function cotesMatch($id){
    global $db;
    $getMatchs = $db->prepare("SELECT * FROM bets WHERE match_id = ? AND choice = ?");
    $getMatchs->execute(array($id, 0));
    $cA = $getMatchs->rowCount();

    $getMatchs->execute(array($id, 1));
    $cB = $getMatchs->rowCount();
    
    $getMatchs->execute(array($id, 2));
    $cN = $getMatchs->rowCount();

    $CA = 1/(($cA + 1)/ ($cA + $cB + $cN + 3));
    $CB = 1 /(($cB + 1) / ($cA + $cB + $cN + 3));
    $CN = 1/(($cN + 1)/ ($cA + $cB + $cN + 3));

    $CA = round($CA,2);
    $CB = round($CB,2);
    $CN = round($CN,2);

    return array($CA,$CB, $CN);
}

?>