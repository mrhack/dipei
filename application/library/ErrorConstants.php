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


    //login \ register
    const CODE_LOGIN_FAILED=-1000;


    const CODE_UPLOAD_OVER_LIMIT_SIZE=8000;
    const CODE_UPLOAD_OVERFLOW_SIZE=8001;
    const CODE_UPLOAD_UNCOMPLETED=8002;
    const CODE_UPLOAD_EMPTY_LIST=8003;
    const CODE_UPLOAD_EMPTY_FILE=8004;
    const CODE_UPLOAD_OVERFLOW_POST=8005;
    const CODE_UPLOAD_ILLEGAL_TYPE=8006;
    const CODE_UPLOAD_IO=8007;
    const CODE_UPLOAD_FAILED=8008;
}