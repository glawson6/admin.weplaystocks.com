<?php

class ApplicationKeyValues {

    public static $HOME_PAGE_IMAGE_BASE_PATH = "startaxi.content/image/homepageimage/";
    public static $VEHICLE_TYPE_IMAGE_BASE_PATH = "startaxi.content/image/vehicletype/";
    public static $LOCATION_TYPE_IMAGE_BASE_PATH = "startaxi.content/image/location/";
    public static $MONEY_IMAGE_PATH = "startaxi.content/image/money/money_pound.png";
    public static $BANNER_FOLDER_PATH = "startaxi.content/banner/";
    public static $DEFAULT_BANNER_NAME = "banner.png";
    public static $SUCCSS_PASSWORD_CHANGE_REQUEST_MESSAGE = "check your email";
    public static $SUCCSS_PASSWORD_CHANGE_MESSAGE = "Username and Password Successfully change";
    public static $RETURN_TIME_BUFFER_IN_SECOND = "86400";
    public static $AIRPORT_LOCATION_TYPE_ID = "1";
    public static $STATION_LOCATION_TYPE_ID = "2";
    public static $BASE_LOCATION_TYPE_ID = "3";
    public static $PUBLIC_LOCATION_TYPE_ID = "4";
    public static $NON_PUBLIC_LOCATION_TYPE_ID = "5";
    public static $AIR_PORT_CHARGE_ID = "1";
    public static $FIRST_MILE_CHARGE_ID = "2";
    public static $FIXED_STD_CHARGE_ID = "3";
    public static $FIXED_HIGHER_CHARGE_ID = "4";
    public static $SEAT_PER_CHARGE_ID = "5";
    public static $NIGHT_CHARGE_ID = "6";
    public static $CONFIRMATION_CODE = "A@2DE";
    public static $IS_PANDING = 0;
    public static $IS_CONFIRM = 1;
    public static $IS_IGNORE = 2;
    public static $NORMAL_USER = 1;
    public static $ADMIN = 2;
    public static $SUPER_ADMIN = 3;
    public static $IS_DROP = 0;
    public static $IS_RETURN = 1;
    public static $IS_REQUEST_TOP = 1;
    public static $IS_REQUEST_BOTTOM = 2;
    public static $IS_CONFIRMATION_TOP = 3;
    public static $IS_CONFIRMATION_BOTTOM = 4;
    public static $IS_ADVANCE_PAYMENT_TOP = 5;
    public static $IS_ADVANCE_PAYMENT_BOTTOM = 6;
    public static $IS_REQUEST_PAYMENT = 1;
    public static $IS_NOT_PAID = 0;
    public static $IS_PAID = 1;
    public static $POSITION_DEFAULT = -1;
    public static $POSITION_ONE = 1;
    public static $POSITION_TWO = 2;
    public static $POSITION_THREE = 3;
    public static $POSITION_FOUR = 4;
    public static $POSITION_NONE = 0;
    
    public static $CASH_PAYMENT=1;
    public static $ONLINE_PAYMENT = 2;
    public static $PAYMENT_AT_PICK_UP = 3;
    public static $ONLINE_PAYMENT_RBS = 4;
    
    
    //rbd payment
    
    public static $INSTID = 210377;
    public static $CARTID = 4406080400000000;
  //    public static $SUCCESSURL = 'http://localhost:82/star/rbs_payment_success.php';
    public static $SUCCESSURL = 'http://startaxitest.rayzay.org.uk/rbs_payment_success.php';
    public static $FAILUREURL = '';
    public static $CURRENCY = 'GBP';

}

?>
