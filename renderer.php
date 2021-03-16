<?php
    // This file is part of Moodle - http://moodle.org/
    //
    // Moodle is free software: you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation, either version 3 of the License, or
    // (at your option) any later version.
    //
    // Moodle is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.
    //
    // You should have received a copy of the GNU General Public License
    // along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

    /**
     *  @package    block_resumecard
        * @copyright  Lorenzo Gaspari
        * @license    use it as you like
     */

defined('MOODLE_INTERNAL') || die();
use core_completion\progress;

class block_resumecard_renderer extends plugin_renderer_base {
    
    function get_card_info(){
        global $DB, $USER, $DB, $CFG;

        $data_to_render = new stdClass ();

        // Get progress for link to resume
        $usersCourses = enrol_get_all_users_courses($USER->id, true);
        /* print_r(count(enrol_get_all_users_courses($USER->id, true))); */
        $keyIndex = null ;
        foreach($usersCourses as $courseKey =>$courseU){
            $date= 0;
            $prevDate = 0;
            $completion = new completion_info($courseU);

            $progress = $completion->get_progress_all(0);
            $arr[]= 0;
            
            foreach ($progress as &$value) {
                
                if($value->id == $USER->id )
                {     
                    $arr = $value->progress;
                    if(empty($arr)){
                        $keysArr[] = array(
                            'courseid' => $courseU->id,
                            'coursemodule' => null
                            );
                    }else{
                        foreach ($arr as $key=>$info) {
                            $date = $info->timemodified;
                            
                            if($date > $prevDate){
                                $prevDate = $date;
                                $keyIndex = $key;
                            }
                       }
                       $keysArr[] = array(
                        'courseid' => $courseU->id,
                        'coursemodule' => $keyIndex,
						   'progress' => round(progress::get_course_progress_percentage($courseU),0)
                        );
                    }  
                } 
            }
        }

        $linktoResume = null;

        foreach ($keysArr as $arrValKey => $dataCm) {
            $courseRecord = $DB->get_record('course',['id' => $dataCm['courseid']]);
            $category = $DB->get_record('course_categories',array('id'=>$courseRecord->category));
            $courseimage = (string) \core_course\external\course_summary_exporter::get_course_image($courseRecord);
            $nocoursesurl = ('http://localhost/moodle/blocks/resumecard/pix/course_no_image.png');
            $coursesummary = $courseRecord->summary;
            $courseurl = new moodle_url('/course/view.php', ['id' => $dataCm['courseid']]);

            if($dataCm['coursemodule'] == null){
                $linktoResume[] = array(
                    'courseid' =>$dataCm['courseid'],
                    'courseurl'=> $courseurl,
                    'courseimg' => $courseimage,
                    'coursename' => $courseRecord->fullname,
                    'coursecat' => $category->name
                );
            }elseif($courseimage == null){
                $modinfo = get_fast_modinfo($courseRecord);
                $cccmmm = $modinfo->get_cm($dataCm['coursemodule']);
                $linktoResume[] = array(
                    'moduleurl' => $cccmmm->url,
                    'courseid' =>$dataCm['courseid'],
                    'courseurl'=> $courseurl,
                    'courseimg' => $nocoursesurl,
                    'coursename' => $courseRecord->fullname,
                    'coursecat' => $category->name,
					'progress' => $dataCm['progress']
                );
            }elseif($courseimage == null && $dataCm['coursemodule'] == null){
                $linktoResume[] = array(
                    'courseid' =>$dataCm['courseid'],
                    'courseimg' => $nocoursesurl,
                    'courseurl'=> $courseurl,
                    'coursename' => $courseRecord->fullname,
                    'coursecat' => $category->name
                );
            }
            else{
                $modinfo = get_fast_modinfo($courseRecord);
                $cccmmm = $modinfo->get_cm($dataCm['coursemodule']);
                $linktoResume[] = array(
                    'moduleurl' => $cccmmm->url,
                    'courseid' =>$dataCm['courseid'],
                    'courseimg' => $courseimage,
                    'courseurl'=> $courseurl,
                    'coursename' => $courseRecord->fullname,
                    'coursecat' => $category->name,
					'progress' => $dataCm['progress']
                );
            }
                  
        }
        
        $data_to_render->cardarry = $linktoResume;

        return $this->render_from_template('block_resumecard/resume_card', $data_to_render);

    }
   
}