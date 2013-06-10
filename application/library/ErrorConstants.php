<?php
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:23
 */
interface ErrorConstants
{
    const CODE_NO_IMPLEMENT=999;

    const CODE_SUCCESS=0;

    const CODE_UNKNOWN=-1;

    const CODE_MONGO=-2;

    const CODE_INVALID_MODEL=-3;

    const CODE_UPDATE_NEED_WHERE=-4;

    const CODE_REMOVE_NEED_WHERE=-5;

    // validator error
    const CODE_VALIDATOR_ERROR=-6;

}