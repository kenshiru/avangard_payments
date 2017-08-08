<?php
/**
 * Created by PhpStorm.
 * User: alexandrmochalov
 * Date: 3/8/17
 * Time: 1:01 AM
 */

namespace Avangard\Interfaces;


interface AvangardApiClientConstantsInterface
{
    const METHOD_REGISTRATION = 'new_order';
    const METHOD_GET_ORDER_INFO = 'get_order_info';
    const METHOD_REVERSE_ORDER = 'reverse_order';
    const METHOD_GET_ORDER_OPERATIONS = 'get_opers_list';
    const METHOD_GET_ORDER_OPERATIONS_BY_DATE  = 'get_opers_by_date';
    const METHOD_CANCEL_ORDER = 'cancel_order';

}