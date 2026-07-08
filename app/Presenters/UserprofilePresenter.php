<?php

namespace App\Presenters;

use App\Models\User;
use Carbon\Carbon;
use Laracasts\Presenter\Presenter;

class UserprofilePresenter extends Presenter
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function getParentName($user_id)
    {
        $user = User::where('id', $user_id)->first();

        foreach ($user->parent as $parent) {
            $array['firstname'] = $parent->userParent->userprofile->firstname;
            $array['lastname'] = $parent->userParent->userprofile->lastname;
            $array['email'] = $parent->userParent->email;
            $array['mobile_no'] = $parent->userParent->mobile_no;
        }

        return $array;
    }

    public function getAge($date_of_birth)
    {
        $to = date('Y', strtotime($date_of_birth));
        $now = Carbon::now();
        $from = date('Y', strtotime($now));
        $age = $from - $to;

        return $age;
    }

    public function integerToRoman($integer)
    {
        $array =
          [
              'M' => 1000,
              'CM' => 900,
              'D' => 500,
              'CD' => 400,
              'C' => 100,
              'XC' => 90,
              'L' => 50,
              'XL' => 40,
              'X' => 10,
              'IX' => 9,
              'V' => 5,
              'IV' => 4,
              'I' => 1,
          ];

        // Convert the integer into an integer (just to make sure)
        $integer = intval($integer);
        $result = '';

        foreach ($array as $roman => $value) {
            // Determine the number of matches
            $matches = intval($integer / $value);

            // Add the same number of characters to the string
            $result .= str_repeat($roman, $matches);

            // Set the integer to be the remainder of the integer and the value
            $integer = $integer % $value;
        }

        // The Roman numeral should be built, return it
        return $result;
    }
}
