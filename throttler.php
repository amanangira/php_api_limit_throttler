<?php
        session_start();
        const cap = 3;//Max http requests a host can make
        const period = 5;//the period in which it limits,60 means 1 minuts
        $stamp_init = date("Y-m-d H:i:s");
        if( !isset( $_SESSION['FIRST_REQUEST_TIME'] ) ){
                $_SESSION['FIRST_REQUEST_TIME'] = $stamp_init;
        }
        $first_request_time = $_SESSION['FIRST_REQUEST_TIME'];
        $stamp_expire = date( "Y-m-d H:i:s", strtotime( $first_request_time )+( period ) );
        if( !isset( $_SESSION['REQ_COUNT'] ) ){
                $_SESSION['REQ_COUNT'] = 0;
        }
        $req_count = $_SESSION['REQ_COUNT'];
        $req_count++;
        if( $stamp_init > $stamp_expire ){//Expired
                $req_count = 1;
                $first_request_time = $stamp_init;
        }
        $_SESSION['REQ_COUNT'] = $req_count;
        $_SESSION['FIRST_REQUEST_TIME'] = $first_request_time;
        header('X-RateLimit-Limit: '.cap);
        header('X-RateLimit-Remaining: ' . ( cap-$req_count ) );
        if( $req_count > cap){//Too many requests
                http_response_code( 429 );
                exit();
        }

?>
