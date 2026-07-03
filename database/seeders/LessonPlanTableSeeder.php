<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class LessonPlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('lesson_plans')->Insert([
            [
                'teacher_link_id' => '1',
                'unit_no' => '1',
                'unit_name' => 'Numbers',
                'title' => 'Counting Backwards (20-0)',
                'duration' => '00:40:00',
                'description' => 'Students will learn how to count backwards from 20-0 in a very fun and exciting way. The lesson includes a hands-on/interactive component where students will be asked to hop backwards while counting. This lesson is great for kindergarten through first grade.',
                'objective' => '<p><span>Students will be able to (1) orally count backwards from 20-0 and (2) write numbers backwards from 20-0 in order.</span></p>',
                'materials_required' => 'A pencil and marker for each student, ten stickers, 21 pieces of large construction paper with a number (from 0-20) written in large print on each, index cards for each student.',
                'introduction' => '<ul><li>Students can be seated on the rug close to the teacher.</li><li>The teacher will begin by showing students a sheet of stickers. There should be 10 stickers on the sheet (stickers can be substituted with many other items). Together, students and teacher will count the stickers starting from 1 up to 10.</li><li>One by one, teacher will give a sticker to a student. After each sticker is removed, teacher will ask “<em>How many stickers to do we have left”?&nbsp;</em>By the time all stickers are passed out, students would have counted down from 10-0.</li></ul>',
                'procedure' => '<p><u>Modeling</u></p><ul><li>Refer to a number line (or something similar) in the classroom. As a class, notice how numbers get “bigger/greater” when counting forward and “smaller/less” when counting backwards.</li><li>Use hand/body gestures to refer to forward and backward.</li><li>Write the words “less”, “take-away” and “backwards” on the board or display the words on a pocket chart. Ask children to tell you what they know about those words.</li><li>Together, chorally practice counting backwards from 20-0 as teachers points to each number on the number line. Teacher can physically move backwards as students count to show the relation.</li></ul><p><u>Guided Practice</u></p><ul><li>Teacher should place pieces of large construction paper with one number (in order from 20-0) written in large print on each.</li><li>Each student will receive a small index card with a “mystery number” (0-20) written on it.</li><li>Each child will get a turn find their number on the construction paper number line on the floor.&nbsp;They will then begin hopping/counting backwards from their mystery number. When the rest of the class catches on, they will join in on the counting.</li><li>Continue process until all students have hopped and counted backwards.</li></ul><p><u>Independent Practice</u></p><ul><li>Teacher will create a simple fill-in-the-blank counting worksheet to show their understanding of counting backwards. This will be used as an assessment.</li></ul><p>Example: 20, 19, __, 17, __, __, 14, 13, __, __, 9, etc.)</p>',
                'conclusion' => '<p>Recall sticker activity from the lessons opening.&nbsp;Review the lesson with students. Ask students, "what did we learn about numbers today"? Count backwards with students.&nbsp;Ask for questions.</p>',
                'assessment' => 'Students will be asked to complete a worksheet (independent practice) on counting backwards. Students will fill in blanks on a number line).',
                'modification' => 'The teacher can work in with students in small groups for those who need extra assistance. Students who are reluctant to participate can work with a partner. Teacher can provide verbal and physical cues to help students.',
                'notes' => null,
                'status' => 'approved',
                'created_at' => '2020-07-13 18:00:16',
                'updated_at' => '2020-07-13 18:00:16',
                'deleted_at' => null,
            ],

            [
                'teacher_link_id' => '1',
                'unit_no' => '1',
                'unit_name' => 'Numbers',
                'title' => 'Big and Small',
                'duration' => '00:40:00',
                'description' => 'A fun lesson to help students understand the concept of big and small and how to compare the two. The lesson involved hands-on activities to make the learning fun and engaging.',
                'objective' => '<p><span>Students will be able to compare objects or things and describe their sizes.</span></p>',
                'materials_required' => 'blank papers, pencils, crayons, large and small paper plates, strings/yard, large and small beads',
                'introduction' => '<ul><li>Wear a pair of shoes that are too big for you then gather the children to form a circle close to you.</li><li>Tell the children you are having problems with your shoes because they keep coming off. Ask them what the problem might be.</li><li>When a students responds with something like "that is because they are too big", ask that student to lend you his/her shoes.</li><li>Then ask the children again about why the shoes are not right for your feet. When they say that it’s because it is too small or little, respond delightfully “You’re all right!”</li></ul>',
                'procedure' => '<ul><li>Ask the children to help you think of things that are big and small. Then tell them that big and small refer to size.</li><li>Tell the children that today’s activities will be about big and small.</li><li>On large paper plates, ask students to draw big/large things (elephants, houses, cars, etc.)</li><li>On the small paper plates, ask students to draw small things (erasers, rice, pins, etc.)</li><li>Next, students will be asked to make a bracelet or necklace using a big-small pattern.</li></ul>',
                'conclusion' => '<ul><li>After children finish the activities, gather the children.</li><li>Ask them what they learned about big and small (and other sizes if other words are introduced).</li><li>Have a simple fun conversation to end the class by asking them “How do you think it feels to be an ant in a big world?”</li></ul>',
                'assessment' => 'Assess the large and small paper plate drawings as well as the large-small pattern bracelet.',
                'modification' => null,
                'notes' => null,
                'status' => 'approved',
                'created_at' => '2020-07-14 18:06:59',
                'updated_at' => '2020-07-14 18:06:59',
                'deleted_at' => null,
            ],

            [
                'teacher_link_id' => '1',
                'unit_no' => '1',
                'unit_name' => 'Numbers',
                'title' => 'Even or Odd Nature Walk',
                'duration' => '01:40:00',
                'description' => 'Students will do a nature walk to find things in nature that are grouped in pairs that are odd or even.',
                'objective' => '<p><span>Students will determine whether a group of objects (up to 20) has an odd or even number of members.</span></p>',
                'materials_required' => '<ul><li>A school garden or area with plants</li><li>Worksheet</li><li>Pencil</li></ul>',
                'introduction' => '<p><span>Students can be seated on the rug close to the teacher.</span></p>',
                'procedure' => '<h4><strong>Modeling</strong></h4><ul><li>Students will work in partners and go on a walk outside in the playground or garden and search for leaves or flowers.</li><li>Students will look at the features of the leaf or flower. If the leaf or flower is still attached to the tree, do not pick it!</li><li>Students can draw a picture of the leaf or flower with as many details as possible.</li><li>Have students count the pedals or the lines on the leaf.</li><li>Students will determine if the total number of lines are even or odd.</li><li>Students will determine if the total number of found objects is even or odd.</li></ul><h4><strong>Independent Practice</strong></h4><ul><li>Students can work in partners or independently to complete the Nature Walk Worksheet.</li></ul>',
                'conclusion' => '<p><span>Hold a class discussion about the project. See if students can name the type of plants or flowers that they found.</span></p>',
                'assessment' => 'Students will be asked to complete a worksheet.',
                'modification' => 'The teacher can work in with students in small groups for those who need extra assistance. Students who are reluctant to participate can work with a partner. Teacher can provide verbal and physical cues to help students.',
                'notes' => null,
                'status' => 'pending',
                'created_at' => '2020-07-14 19:28:05',
                'updated_at' => '2020-07-14 19:28:05',
                'deleted_at' => null,
            ],

            [
                'teacher_link_id' => '82',
                'unit_no' => '1',
                'unit_name' => 'Adaption for survival in animals',
                'title' => 'Camouflage and Environment',
                'duration' => '00:30:00',
                'description' => 'Students will make butterflies of various colors and then they will experience the advantage that butterflies that are the same color as their environment have against predators.',
                'objective' => '<p>Students will be able to identify various animals that use camouflage as a defense mechanism.</p>',
                'materials_required' => '<ul><li>10 6X8 inch (approximately) sheets of red construction paper</li><li>10 6X8 inch (approximately) sheets of blue construction paper</li><li>10 6X8 inch (approximately) sheets of orange construction paper</li><li>5 large sheets of Red construction paper</li><li>white writing/drawing paper</li><li>scissors</li><li>markers or colored pencils</li><li>pencil</li></ul>',
                'introduction' => '<ul><li>Let each student choose a piece of red, blue or orange construction paper (you can choose different colors for the class, but only 3 colors total).</li><li>Lead students in cutting out butterflies from the paper that they have.</li><li class="ql-indent-1">Have students make 4 butterflies each, they don’t have to be of equal size.</li><li class="ql-indent-1">They are NOT to draw on the butterflies or fold them, they should stay flat with no markings.</li></ul>',
                'procedure' => '<h4><strong>Guided Practice</strong></h4><ul><li>The teacher will then lie out 4 large sheets of red construction paper, or enough to cover a desk or 2.</li><li>Call students up a few at a time to bring their butterflies and spread them out on the colored construction paper.</li><li>All of the students’ butterflies should be spread out evenly on the papers.</li><li>Now ask students to raise their hand if they made red butterflies, count the students and multiply the number of students by 4 to figure out how many red butterflies there are.</li><li class="ql-indent-1">Ex. 10 x 4 = 40 red butterflies</li><li>Now ask students to raise their hand if they made orange butterflies, count the students and multiply the number of students by 4.</li><li class="ql-indent-1">Ex. 10 x 4 = 40 orange butterflies</li><li>Now ask students to raise their hand if they made blue butterflies, count the students and multiply the number of students by 4.</li><li class="ql-indent-1">Ex. 10 x 4 = 40 blue butterflies</li><li>Now have students line up and tell them that they will be predators. They are to quickly choose 1 butterfly and then keep walking. They should choose the first one they find without looking carefully.</li><li>Have students repeat this step multiple times. (but they only take 1 butterfly at a time.</li><li>Monitor students and make sure that they are choosing quickly and not searching for their own or their friend’s butterflies.</li><li>When you see that one of the colors of butterflies has “gone extinct” stop the line and have students go back to their seats.</li><li>Count the remaining butterflies. You should have 0 butterflies from one group (maybe blue), maybe you will have less than 10 of the other group (maybe orange) and you should have nearly all of the butterflies from the red group, as the red butterflies were harder to spot on the red construction paper.</li><li>Find a student that has all blue butterflies, ask them why they “hunted” only blue butterflies.</li><li>Lead a discussion on why the blue (for example) group went extinct before the other group.</li><li>Ask them what advantage the red group of butterflies had over the other colors. (they blended in with the environment)</li><li>Write Camouflage on the board</li><li>Lead a discussion about the benefits of camouflage and ask students what animals they know that can camouflage.</li><li>Show a video or read a book about animals that can camouflage.</li><li class="ql-indent-1">BBC Earth Unplugged; Top 10 Animal Camouflage</li><li class="ql-indent-1"><a href="http://oakdome.com/k5/lesson-plans/powerpoint/animal-camouflage-pictures-and-information.php" rel="noopener noreferrer" target="_blank" style="color: rgb(255, 129, 83);">http://oakdome.com/k5/lesson-plans/powerpoint/animal-camouflage-pictures-and-information.php</a></li></ul><h4><strong>Independent Practice</strong></h4><ul><li>If materials are available, allow students to research an animal that uses camouflage as a defense. This could be a great activity during computer lab if available.</li><li>Tell students to draw a picture of an animal hiding in its environment. For example: they can draw a green frog in a tree or a snowy owl or polar bear in the arctic.</li><li>Have students write a summary of the animal that they drew. For example: The snowy owl is cool for many reasons. It lives in the snow and is white. Its white color helps the snowy owl hide from its predators and stay camouflaged. As you can see, the snow owl is a master of disguise!</li></ul>',
                'conclusion' => '<p><span style="color: rgb(139, 139, 139);">Students can show their pictures and read their summary to their partners and then the teacher can choose students to read the summary and show their animal to the class.</span></p>',
                'assessment' => 'Students will write a summary and draw a picture about an animal that uses camouflage as a defense mechanism.',
                'modification' => 'If teacher notices that some students need extra attention, he/she can conduct small group instruction. Students can ask teacher question in order to help clear up misconception.',
                'notes' => null,
                'status' => 'approved',
                'created_at' => '2020-07-18 07:35:46',
                'updated_at' => '2020-07-18 08:09:15',
                'deleted_at' => null,
            ],
        ]);
    }
}
