<?php namespace Neomerx\CoreApi\Support;

use \Illuminate\Support\Facades\Config as ConfigFacade;

class Config
{
    const CONFIG_FILE_NAME_WO_EXT = CoreApiServiceProvider::PACKAGE_NAMESPACE;

    const KEY_CARRIERS                    = 'carriers';
    const KEY_CARRIERS_TARIFF_CALCULATORS = 'tariff_calculators';
    const PARAM_CALCULATOR_CODE           = 'code';
    const PARAM_CALCULATOR_FACTORY        = 'factory';
    const PARAM_CALCULATOR_NAME           = 'name';
    const PARAM_CALCULATOR_DESCRIPTION    = 'description';

    const KEY_AUTH                           = 'auth';
    const PARAM_AUTH_CACHE_ACL_KEY           = 'auth_cache_acl_key';
    const PARAM_AUTH_CACHE_ACL_IN_MINUTES    = 'auth_cache_acl_in_min';
    const PARAM_AUTH_CACHE_TOKENS_PREFIX     = 'auth_cache_tokens_prefix';
    const PARAM_AUTH_CACHE_TOKENS_IN_MINUTES = 'auth_cache_tokens_in_min';

    /**
     * @param string $key
     *
     * @return mixed
     */
    public static function get($key)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return array_get(ConfigFacade::get(self::CONFIG_FILE_NAME_WO_EXT), $key);
    }
}
