<?php
/*
 * DATABASE
 */
const DB_HOST = "localhost";
const DB_USER = "root";
const DB_PASS = "";
const DB_NAME = "reifeitorio";

/*
 * PROJECT URLs
 */
const URI = 'https://';

/*
 * SITE
 */
const SITE_NAME = "Re-IFetório";
const SITE_TITLE = "Faça reservas de produtos em cantinas IFPR.";
const SITE_DESC = "Descrição.";
const SITE_LANG = "pt_BR";

/*
 * DATES
 */
date_default_timezone_set('America/Sao_Paulo');
const DATE_FORMAT_BR = "d/m/Y H:i:s";
const DATE_FORMAT_APP = "Y-m-d H:i:s";

define("DATE_BR", date(DATE_FORMAT_BR, time()));

define("DATE_APP", date(DATE_FORMAT_APP, time()));

/*
 * PASSWORD
 */
const PASSWD_MIN_LEN = 8;
const PASSWD_MAX_LEN = 40;
const PASSWD_ALGO = PASSWORD_DEFAULT;
const PASSWD_OPTION = ['cost' => 10];

/*
 * ALERTS
 */
const ALERT_INFO = "info";
const ALERT_SUCCESS = "success";
const ALERT_WARNING = "warning";
const ALERT_ERROR = "error";