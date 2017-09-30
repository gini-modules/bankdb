<?php

namespace Gini\Controller\API;

class Bank extends \Gini\Controller\API
{
    // return [{
    //     name: xx,
    //     code: xx,
    //     province: {
    //         name: xx, 
    //         code: xx
    //     }, 
    //     city: {
    //         name: xx,
    //         code: xx
    //     },
    //     company: {
    //         name: xx,
    //         code: xx
    //     }
    // }, ...]
    public function actionGetBranches($criteria)
    {
        
    }

    // return {
    //     name: xx,
    //     code: xx,
    //     province: {
    //         name: xx, 
    //         code: xx
    //     }, 
    //     city: {
    //         name: xx,
    //         code: xx
    //     },
    //     company: {
    //         name: xx,
    //         code: xx
    //     }
    // }
    public function actionGetBranch($name)
    {

    }

}
