<?php
function html($text) {
    return htmlspecialchars(stripslashes(trim($text)), ENT_QUOTES);
}
function html_out($text) {
    echo html($text);
}