<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use http\Env\Response;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class UsersImport implements ToCollection, WithHeadingRow
{
    // public function model(array $row)
    // {
    //     return new User([
    //         'name' => $row[0],
    //         'email'    => $row[1],
    //         'password' => Hash::make($row[2]),
    //         'state' => $row[3],
    //         'phone'    => $row[4],
    //     ]);
    // }

    public $info;

    /**
     * @param Collection $rows
     * @return array
     */
    public function collection(Collection $rows)
    {
        $rowNumber = 0;
        $infos = array();
        foreach ($rows as $row) 
        {
            $rowNumber++;
            $row = $row->toArray();
            $validate = Validator::make($row, User::RULES);
            if ($validate->fails()) {
                $errors = $validate->errors();
                $infos[] = [
                    'Row'=>$rowNumber,
                    'message'=>'failed',
                    'error'=>$errors
                ];
            }
            else {
                $method=$row['method'];
                if ($method == 'create' || $method == 'update' ){
                    $user=User::updateOrCreate(['email' => $row['email']], ['name' => $row['name'],'state' => $row['state'], 'phone' => $row['phone'], 'cover_image' => $row['cover_image'], 'password' =>bcrypt($row['password']), 'c_password' =>bcrypt($row['c_password'])]);
                    if($user){
                        $infos[] = [
                            'Row'=>$rowNumber,
                            'message'=>'success'
                        ];
                    }
                   else{
                       $infos[] = [
                           'Row'=>$rowNumber,
                           'message'=>'failed update or create'
                       ];
                   }
                }
                elseif($method == 'delete'){
                    $user = User::where('email',$row['email'])->first();
                    if($user!=null)
                    {
                        $user->delete();
                    }
                    if($user){
                        $infos[] = [
                            'Row'=>$rowNumber,
                            'message'=>'success'
                        ];
                    }
                    else{
                        $infos[] = [
                            'Row'=>$rowNumber,
                            'message'=>'failed delete'
                        ];
                    }
                } 
            }
            $rowNumber;
        }
        // dd($rowNumber);
        $this->info=$infos;
    }
}