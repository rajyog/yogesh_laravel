<?php

function dbGlobal() {
    $db_name = env('DB_DATABASE');
    config(['database.connections.global' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => $db_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
    ]]);

    return TRUE;
}

function dbGlobalLanguage($language_name) {
    $language = $language_name;
    $db_name = env('DB_DATABASE_OTHER_GLOBAL') . '_' . $language;
    if($language == ""){
        $db_name = env('DB_DATABASE_OTHER_GLOBAL');
    }

    $dbbb = config(['database.connections.global_lang' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => $db_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
    ]]);
    return TRUE;
}

function dbSchool($school_id) {
    $setdbs = $school_id;
    if ($school_id < 10) {
        $setdbs = "000" . $school_id;
    } elseif ($school_id < 100) {
        $setdbs = "00" . $school_id;
    } elseif ($school_id < 1000) {
        $setdbs = "0" . $school_id;
    }
    $db_name = env('DB_DATABASE_SCHOOL') . $setdbs;
    config(['database.connections.schools' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => $db_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
    ]]);

    return TRUE;
}

function dbPortal() {
    $db_name = env('DB_DATABASE_PORTAL');
    config(['database.connections.portal' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => $db_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
    ]]);
    return TRUE;
}

function dbUsteer() {
    $db_name = env('DB_DATABASE_USTEER');
    config(['database.connections.usteer' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => $db_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
    ]]);

    return TRUE;
}

function dbUsteerGlobal() {
    $db_name = env('DB_DATABASE_USTEER_GLOBAL');
    config(['database.connections.usteer_global' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => $db_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
    ]]);

    return TRUE;
}

function dbUsteerUni($uni_id) {

    $setdbs = $uni_id;
    if ($uni_id < 10) {
        $setdbs = "000" . $uni_id;
    } elseif ($uni_id < 100) {
        $setdbs = "00" . $uni_id;
    } elseif ($uni_id < 1000) {
        $setdbs = "0" . $uni_id;
    }

    $db_name = env('DB_DATABASE_USTEER_UNI') . $setdbs;
    config(['database.connections.usteer_university' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => $db_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
    ]]);

    return TRUE;
}

function getSchoolDatabase($school_id) {
    $setdbs = $school_id;
    if ($school_id < 10) {
        $setdbs = "000" . $school_id;
    } elseif ($school_id < 100) {
        $setdbs = "00" . $school_id;
    } elseif ($school_id < 1000) {
        $setdbs = "0" . $school_id;
    }
    $db_name = env('DB_DATABASE_SCHOOL') . $setdbs;
    return $db_name;
}

function getUniDatabase($uni_id) {
    $setdbs = $uni_id;
    if ($uni_id < 10) {
        $setdbs = "000" . $uni_id;
    } elseif ($uni_id < 100) {
        $setdbs = "00" . $uni_id;
    } elseif ($uni_id < 1000) {
        $setdbs = "0" . $uni_id;
    }

    $db_name = env('DB_DATABASE_USTEER_UNI') . $setdbs;
    return $db_name;
}
