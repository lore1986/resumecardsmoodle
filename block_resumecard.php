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
 * Class definition for the Recently accessed courses block.
 *
 * @package    block_recentlyaccessedcourses
 * @copyright  2018 Victor Deniz <victor@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_resumecard extends block_base {
    public function init() {
        $this->title = get_string('resumecard', 'block_resumecard');
    }

    public function get_content() {

        $renderer = $this->page->get_renderer('block_resumecard');

        if ($this->content !== null) {
          return $this->content;
        }
     
        $this->content         =  new stdClass;
        $this->content->text  = $renderer->get_card_info();
        /* $this->content->footer = 'Footer here...'; */

        return $this->content;
    }
        
        /* $this->content->text .= $renderer->time_map_list_link($listitems, $videoattributes); */
        //print_r($this->config);
        
        /* $list= $this->config->timevideo;
        $listdescriptions= $this->config->description;
        
        $listitems = array();
        $listed = count($list);

        for($x = 0; $x < $listed; $x++){
            $singlelement = array();
            $singlelement['time'] = $list[$x];
            $singlelement['description'] = $listdescriptions[$x];

            $listitems[] = $singlelement;
        }

        $videourl = $this->config->videourl;
        $videotitle = $this->config->text;
        $videoid = $this->config->videoid;

        $videoattributes = array();
        $videoattributes['url'] = $videourl;
        $videoattributes['title'] = $videotitle;
        $videoattributes['idhtml'] = $videoid;

        
 */

}