<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{

    //  get token from user device
    /**
     * @OA\Post(
     * path="/api/getToken",
     * summary="authentication with device token",
     * description="Login with token",
     * @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       required={"deviceToken"},
     *       @OA\Property(property="deviceToken", type="string", format="string", example="hdj84t3489hdfr"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *     )
     * )
     */



    // get price from user to ( alert )
    /**
     * @OA\Post(
     * path="/api/userPrice",
     * description="user pass current price ( alert )",
     * @OA\RequestBody(
     *    required=true,
     *    description="",
     *    @OA\JsonContent(
     *       required={"user_id,price,metalName"},
     *       @OA\Property(property="user_id", type="bigint", format="bigint", example="1"),
     *        @OA\Property(property="price", type="float", format="string", example="332"),
     *       @OA\Property(property="metalName", type="string", format="string", example="GOLD"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="",
     *     )
     * )
     */



    // GET LAST PRICE
    /**
    * @OA\get(
    * path="/api/getLastprice",
    * description="get last price to metal",
    *   @OA\Response(
    *     response=200,
    *     description="Success",

    *  ),
    * )
    */


    //GET HESTORICAL SILVER
    /**
    * @OA\get(
    * path="/api/histsilver",
    * description="call historical price of silver",
    *   @OA\Response(
    *     response=200,
    *     description="Success",

    *  ),
    * )
    */


    //GET HESTORICAL GOLD
    /**
    * @OA\get(
    * path="/api/histgold",
    * description="call historical price of gold",
    *   @OA\Response(
    *     response=200,
    *     description="Success",

    *  ),
    * )
    */


    //GET HESTORICAL PLATINUM
    /**
    * @OA\get(
    * path="/api/histplatinum",
    * description="call historical price of platinum",
    *   @OA\Response(
    *     response=200,
    *     description="Success",

    *  ),
    * )
    */




}
