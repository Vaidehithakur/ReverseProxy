<?php

    $url = isset($_GET['url']) ? $_GET['url'] : "";
    $blackListedURI = [];

    function error($msg) {
        header("HTTP/1.0 404 Not Found");
        echo $msg;
        exit;
    }

    function fetch($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FOLLOW_LOCATION);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 45);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
      }

    function checkIfBlacklistedURI($url, $blackListedURI) {
        if(in_array($url, $blackListedURI)) {
            return false;
        }
        return true;
    }


    if (!isset($_GET['apikey'])) {
        echo "You need an API key";
      }
      if ($_GET['apikey'] !== 'this_is_an_api_key') {
        echo "INVALID!";
        exit;
      }
      $url = isset($_GET['url']) ? $_GET['url'] : "";
      if (checkIfBlacklistedURI($url, $blackListedURI)) {
          $page = fetch($url);
          echo $page;
          header("X-Frame-Options: SAMEORIGIN");
      }
      else {
          error("Cannot fetch $url.");
      }
