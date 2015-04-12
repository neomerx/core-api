<?php namespace Neomerx\CoreApi\Support;

class Translate
{
    const FILE_NAME_WO_EXT = 'application';

    const KEY_ERR_UNEXPECTED = 'error_unexpected';
//    const KEY_ERR_IMAGE_GENERATION_FAILED           = 'image_generation_failed';
//    const KEY_ERR_LOGIN_FAILED                      = 'login_failed';
//    const KEY_ERR_LOGIN_TOO_MANY_ATTEMPTS_TRY_LATER = 'login_too_many_attempts_try_later';

//    const KEY_APP_ORDER_STATUS_NEW                 = 'order_status_new';
//    const KEY_APP_RULES_ALL                        = 'rules_all';
//    const KEY_APP_PRODUCT_TAX_TYPE_SHIPPING        = 'product_tax_type_shipping';
//    const KEY_APP_PRODUCT_TAX_TYPE_EXEMPT          = 'product_tax_type_exempt';
//    const KEY_APP_PRODUCT_TAX_TYPE_TAXABLE         = 'product_tax_type_taxable';
//    const KEY_APP_CUSTOMER_TYPE_GENERAL            = 'customer_type_general';
//    const KEY_APP_CUSTOMER_TYPE_MEMBER             = 'customer_type_member';
//    const KEY_APP_CUSTOMER_TYPE_NOT_LOGGED_IN      = 'customer_type_not_logged_in';
//    const KEY_APP_CUSTOMER_TYPE_PRIVATE            = 'customer_type_private';
//    const KEY_APP_CUSTOMER_TYPE_RETAIL             = 'customer_type_retail';
//    const KEY_APP_CUSTOMER_TYPE_WHOLESALE          = 'customer_type_wholesale';
//    const KEY_APP_WAREHOUSE_DEFAULT_NAME           = 'warehouse_default_name';
//    const KEY_APP_STORE_DEFAULT_NAME               = 'store_default_name';

    /**
     * Get translated message.
     *
     * @param $key
     *
     * @return string
     */
    public static function trans($key)
    {
        return trans(CoreApiServiceProvider::PACKAGE_NAMESPACE.'::'.self::FILE_NAME_WO_EXT.'.'.$key);
    }
}
