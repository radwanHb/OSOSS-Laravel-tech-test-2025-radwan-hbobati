<?php

namespace App\Traits;

trait HttpResponseTrait
{
    protected function success( $data = [], $message = 'success', $errNum = 200)
    {
        return response([
            'status' => true,
            'data' => $data,
            'msg' => $message,
            'errNum'=>$errNum
        ]);
    }

    protected function failure($message, $errNum = 422)
    {
        return response([
            'status' => false,
            'msg' => $message,
            'errNum'=>$errNum
        ]);
    }




    public function validationfailure($validator)
    {

        $errors=[];
        $msgs=[];

        $inputs = array_keys($validator->errors()->toArray());

        foreach($inputs as $input)
        {
            $error = $this->getErrorCode($input);
            array_push($msgs,$error['msg']);
            array_push($errors,$error['errNum']);

        }

        $this->fixer($errors,$msgs);

        return response([
            'status' => false,
            'msg' => $msgs,
            'errNum'=>$errors
        ]);


    }

    public function getErrorCode($input)
    {
        if ($input == "userName")
            return [
                'msg'=>'اسم المستخدم غير متاح',
                'errNum'=>'E001'
            ];

        else if ($input == "password")
            return [
                'msg'=>'كلمة المرور قصيرة',
                'errNum'=>'E002'
            ];


        else if ($input == "firstName")
            return [
                'msg'=>'رجاء ادخل الاسم الاول',
                'errNum'=>'E003'
            ];

        else if ($input == "lastName")
            return [
                'msg'=>'رجاء ادخل الاسم الاخير',
                'errNum'=>'E003'
            ];

        else
            return "";
    }




    public function fixer(&$msgs,&$errors){

        if(count($msgs)==1){

            $msgs= $msgs[0];
            $errors= $errors[0];

        }


    }
}

