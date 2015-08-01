<?php

$a = array(
    ""
    , "Basic"
    , "Click"
    , "Form"
    , "Select"
    , "InputTyping"
    , "JavaScript"
    , "jQuery"
    , "Async"
    , "Links"
    , "Events"
    , "Alerts"
    , "CSS"
    , "Frames"
    , "Windows"
    , "Mouse"
    , "Develop"
    , "Session"
    , "Cookies"
    , "Navigate"
);

foreach ($a as $f) {
    if ($f == "") {
        continue;
    }
    include_once __DIR__ . '/' . $f . '.php';
}

function using() {
    global $a;

    foreach ($a as $f) {
        if ($f == "")
            continue;
        eval("use $f;");
    }
}
