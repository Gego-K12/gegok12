<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentFormatExport implements FromArray, WithStyles, ShouldAutoSize
{
    protected $classes;

    public function __construct($classes)
    {
        $this->classes = $classes;
    }

    public function array(): array
    {
        return [

            // Header row
            [
                'firstname',
                'lastname',
                'mobile_no',
                'email',
                'gender',
                'date_of_birth',
                'blood_group',
                'class',
                'section',
                'address',
                'city',
                'state',
                'country',
                'pincode',
                'birth_place',
                'native_place',
                'mother_tongue',
                'caste',
                'sub_caste',
                'aadhar_number',
                'joining_date',
                'admission_number',
                'EMIS_number',
                'roll_number',
                'id_card_number',
                'board_registration_number',
                'mode_of_transport',
                'driver_name',
                'driver_contact_number',
                'siblings',
                'siblings_count',
                'sibling_relation',
                'sibling_name',
                'sibling_date_of_birth',
                'sibling_class',
                'notes',
                'parent_firstname',
                'parent_lastname',
                'parent_mobile_no',
                'parent_alternate_no',
                'parent_email',
                'parent_qualification',
                'parent_occupation',
                'parent_sub_occupation',
                'parent_designation',
                'parent_organization_name',
                'parent_official_address',
                'parent_annual_income',
                'relation'
            ],

            // Example row
            [
                '',
                '',
                '',
                '',
                '(male,female)',
                '',
                '(a+,a1+,b+,b1+,o+,ab+,a1b+,a-,a1-,b-,b1-,o-,ab-,a1b-)',
                implode(',', $this->classes),
                '(A,B)',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '(BC,BCM,FC,MBC,OBC,Others,SC,SCA,ST)',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '(only for 10th and 12th students)',
                '(auto,car,city_bus,cycle,rickshaw,school_bus,taxi,walking)',
                'if auto,rickshaw,taxi',
                'if auto,rickshaw,taxi',
                '(yes,no)',
                '',
                'sibling_relation1,sibling_relation2',
                'sibling_name1,sibling_name2',
                'sibling_date_of_birth1,sibling_date_of_birth2',
                'sibling_standard1,sibling_standard2',
                '',
                '',
                '',
                '',
                '',
                '',
                'UG Degree,PG Degree',
                '(business,central_government_employee,private,home_maker,state_government_employee,others)',
                'enter if not home_maker',
                'enter if not home_maker',
                'enter if not home_maker',
                'enter if not home_maker',
                'enter if not home_maker',
                '(father,mother,guardian)'
            ]

        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header row bold
        $sheet->getStyle('A1:AW1')
              ->getFont()
              ->setBold(true);

        // Freeze header
        $sheet->freezePane('A2');

        // Header row height
        $sheet->getRowDimension(1)
              ->setRowHeight(25);

        // Wrap long text
        $sheet->getStyle('A1:AW2')
              ->getAlignment()
              ->setWrapText(true);

        return [];
    }
}