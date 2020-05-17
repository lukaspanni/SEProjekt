<?php

function return_404_error(){
    http_response_code(404);
    echo "Not Found";
}

function return_501_error(){
    http_response_code(501);
    echo "Not Implemented";
}

?>
