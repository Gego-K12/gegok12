<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class QualificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds_
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('qualifications')->Insert([
            'id' => '1',
            'display_name' => 'Others',
            'type' => 'others',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '2',
            'display_name' => 'Basic Training Certificate (BTC)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '3',
            'display_name' => 'Primary Teachers Certificate (PTC)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '4',
            'display_name' => 'Elementary Teacher Education (ETE)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '5',
            'display_name' => 'Nursery Teacher Training (NTT)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '6',
            'display_name' => 'Diploma in Education (DED)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '7',
            'display_name' => 'Teachers Training Certificate (TTC)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '8',
            'display_name' => 'Junior Basic Training (JBT)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '9',
            'display_name' => 'Nursery Teacher Education Program',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '10',
            'display_name' => 'Pre-School Teacher Education Program',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '11',
            'display_name' => 'Physical Education Program (C.P.Ed.)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '12',
            'display_name' => 'Bachelor of Elementary Education (B.EI.Ed.)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '13',
            'display_name' => 'Elementary Teacher Education Program',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '14',
            'display_name' => 'Bachelor of Architecture (B.Arch)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '15',
            'display_name' => 'Bachelor of Arts (B.A.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '16',
            'display_name' => 'Bachelor of Ayurvedic Medicine & Surgery (B.A.M.S.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '17',
            'display_name' => 'Bachelor of Business Administration (B.B.A.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '18',
            'display_name' => 'Bachelor of Commerce (B.Com.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '19',
            'display_name' => 'Bachelor of Computer Applications (B.C.A.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '20',
            'display_name' => 'Bachelor of Dental Surgery (B.D.S.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '21',
            'display_name' => 'Bachelor of Design (B.Des. / B.D.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '22',
            'display_name' => 'Bachelor of Education (B.Ed.)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '23',
            'display_name' => 'Bachelor of Engineering / Bachelor of Technology (B.E./B.Tech.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '24',
            'display_name' => 'Bachelor of Fine Arts (BFA / BVA)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '25',
            'display_name' => 'Bachelor of Fisheries Science (B.F.Sc./ B.Sc.[Fisheries])',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '26',
            'display_name' => 'Bachelor of Homoeopathic Medicine and Surgery (B.H.M.S.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '27',
            'display_name' => 'Bachelor of Laws (L.L.B.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '28',
            'display_name' => 'Bachelor of Library Science (B.Lib. / B.Lib.Sc.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '29',
            'display_name' => 'Bachelor of Mass Communications (B.M.C. / B.M.M.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '30',
            'display_name' => 'Bachelor of Medicine and Bachelor of Surgery (M.B.B.S.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '31',
            'display_name' => 'Bachelor of Nursing',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '32',
            'display_name' => 'Bachelor of Pharmacy (B.Pharm / B.Pharma.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '33',
            'display_name' => 'Bachelor of Physical Education (B.P.Ed.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '34',
            'display_name' => 'Bachelor of Physiotherapy (B.P.T.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '35',
            'display_name' => 'Bachelor of Science (B.Sc.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '36',
            'display_name' => 'Bachelor of Social Work (BSW / B.A.[SW])',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '37',
            'display_name' => 'Bachelor of Veterinary Science & Animal Husbandry (B.V.Sc. & A.H. / B.V.Sc)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '38',
            'display_name' => 'Diploma in Education (D.Ed.)',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '39',
            'display_name' => 'Doctor of Medicine (M.D.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '40',
            'display_name' => 'Doctor of Medicine in Homoeopathy (M.D.[Homoeopathy])',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '41',
            'display_name' => 'Doctor of Pharmacy (Pharm.D)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '42',
            'display_name' => 'Doctor of Philosophy (Ph.D.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '43',
            'display_name' => 'Doctorate of Medicine (D.M.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '44',
            'display_name' => 'Master of Architecture (M.Arch.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '45',
            'display_name' => 'Master of Arts (M.A.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '46',
            'display_name' => 'Master of Business Administration (M.B.A.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '47',
            'display_name' => 'Master of Commerce (M.Com.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '48',
            'display_name' => 'Master of Computer Applications (M.C.A.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '49',
            'display_name' => 'Master of Dental Surgery (M.D.S.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '50',
            'display_name' => 'Master of Design (M.Des./ M.Design.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '51',
            'display_name' => 'Master of Education (M.Ed.)',
            'type' => 'teacher',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '52',
            'display_name' => 'Master of Engineering / Master of Technology (M.E./ M.Tech.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '53',
            'display_name' => 'Master of Fine Arts (MFA / MVA)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '54',
            'display_name' => 'Master of Laws (L.L.M.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '55',
            'display_name' => 'Master of Library Science (M.Lib./ M.Lib.Sc.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '56',
            'display_name' => 'Master of Mass Communications / Mass Media (M.M.C / M.M.M.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '57',
            'display_name' => 'Master of Pharmacy (M.Pharm)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '58',
            'display_name' => 'Master of Philosophy (M.Phil.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '59',
            'display_name' => 'Master of Physical Education (M.P.Ed. / M.P.E.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '60',
            'display_name' => 'Master of Physiotherapy (M.P.T.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '61',
            'display_name' => 'Master of Science (M.Sc.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '62',
            'display_name' => 'Master of Social Work / Master of Arts in Social Work (M.S.W. / M.A.[SW])',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '63',
            'display_name' => 'Master of Surgery (M.S.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '64',
            'display_name' => 'Master of Veterinary Science (M.V.Sc.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '65',
            'display_name' => 'Master of Education Program (M.Ed.)',
            'type' => 'pg',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('qualifications')->Insert([
            'id' => '66',
            'display_name' => 'Montessori Teacher Training Course',
            'type' => 'ug',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
