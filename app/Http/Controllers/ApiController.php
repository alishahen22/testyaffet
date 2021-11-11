<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{

    //  get token from user device
    /**
     * @OA\Post(
     * path="/api/getToken",

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
     *    description="pass price , metalName , user_deviceToken and currency ",
     *    @OA\JsonContent(
     *       required={"price,metalName,user_deviceToken,currency"},
     *       @OA\Property(property="user_deviceToken", type="string", format="string", example="hdj84t3489hdfr"),
     *        @OA\Property(property="price", type="float", format="string", example="332"),
     *       @OA\Property(property="metalName", type="string", format="string", example="GOLD"),
     *       @OA\Property(property="currency", type="string", format="string", example="EGP"),
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
        * @OA\Get(
        *     path="/api/getLastprice",
        *      description="get last price to 3 metals",
        *     @OA\Response(response="200", description="Success")
        * )
        */
        //GET HESTORICAL SILVER


        /**
        * @OA\Get(
        *     path="/api/getHistPrice/silver",
        *      description="call historical price of silver",
        *     @OA\Response(response="200", description="Success")
        * )
        */

        //GET HESTORICAL GOLD

       /**
        * @OA\Get(
        *     path="/api/getHistPrice/gold",
        *      description="call historical price of gold",
        *     @OA\Response(response="200", description="Success")
        * )
        */

        //GET HESTORICAL PLATINUM
        /**
        * @OA\Get(
        *     path="/api/getHistPrice/platinum",
        *      description="call historical price of platinum",
        *     @OA\Response(response="200", description="Success")
        * )
        */

                //Get last Currency

        /**
         * @OA\Get(
         *     path="/api/getLastCurrency",
         *      description="Get last Currency",
         *     @OA\Response(response="200", description="Success")
         * )
         */



}
