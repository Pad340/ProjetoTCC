<?php
/**
 * DATABASE
 */
const CONF_DB_HOST = "localhost";
const CONF_DB_USER = "root";
const CONF_DB_PASS = "";
const CONF_DB_NAME = "reifeitorio";

/**
 * PROJECT URLs
 */
const CONF_URL_BASE = "https://www.reifeitorio.com";
const CONF_URL_TEST = "https://www.localhost/projetotcc";

/**
 * SITE
 */
const CONF_SITE_NAME = "Re-IFetório";
const CONF_SITE_TITLE = "Faça reservas de produtos em sua cantina IFPR";
const CONF_SITE_DESC = "Descrição.";
const CONF_SITE_LANG = "pt_BR";

/**
 * DATES
 */
const CONF_DATE_BR = "d/m/Y H:i:s";
const CONF_DATE_APP = "Y-m-d H:i:s";

/**
 * PASSWORD
 */
const CONF_PASSWD_ALGO = PASSWORD_DEFAULT;
const CONF_PASSWD_OPTION = ['cost' => 10];
const CONF_PASSWD_MIN_LEN = 8;
const CONF_PASSWD_MAX_LEN = 40;

/**
 * VIEW
 */
const CONF_VIEW_EXT = "php";
const CONF_VIEW_THEME = "web";
const CONF_VIEW_APP = "app";
const CONF_VIEW_ADMIN = "adm";

/**
 * UPLOAD
 */
const CONF_UPLOAD_DIR = "storage";
const CONF_UPLOAD_IMAGE_DIR = "images";
const CONF_UPLOAD_FILE_DIR = "files";
const CONF_UPLOAD_MEDIA_DIR = "medias";

/**
 * IMAGES
 */
const CONF_IMAGE_CACHE = CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache";
const CONF_IMAGE_SIZE = 2000;
const CONF_IMAGE_QUALITY = ["jpg" => 75, "png" => 5];