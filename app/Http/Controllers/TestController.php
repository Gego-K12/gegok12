<?php
/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Userprofile;
use Exception;
use App\Traits\Common;
use App\Traits\MSG91;
class TestController extends Controller
{
	use Common;
	use MSG91;

   public function index(Request $request)
   {
	   	try
	   	{


		   	if($request->key=='123')
		   	{
		   		$user=new User;
		   		$user->name=$request->data['subscriber']['firstname'];
		   		$user->email=$request->data['subscriber']['email'];
		   		$user->mobile_no='7412589635';
		   		$user->password=$request->data['subscriber']['lastname'];
		   		$user->save();
		   		
		   	}
		}

		catch(Exception $e)
		{
		}
   }
   public function createaudio()
   {
   	  return view('audio');
   } 


   public function storeAudio(Request $request){
		try{
			$filename= date('Y-m-dHis').'.'.$request->encoding;
			
		    $this->putContentsByFilename('uploads/audio',$request->file,$filename);
		}
		catch(Exception $e)
		{
		}


   }

    public function checksms()
    {
    	$content="Hai";
        $this->sendSMS($content,'919042781117');
    }



}
